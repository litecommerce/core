{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10213: IdIOHandlerSocket.pas 
{
{   Rev 1.2    2/16/2003 03:36:00 PM  JPMugaas
{ Added comment about new patch.
}
{
{   Rev 1.1    2/15/2003 03:02:10 PM  JPMugaas
{ Now can create a SocksInfo object at design time.  Not sure if this will have
{ any unintended consequences.
}
{
{   Rev 1.0    2002.11.12 10:42:34 PM  czhower
}
unit IdIOHandlerSocket;

interface

uses
  Classes,
  IdGlobal, IdSocks, IdSocketHandle, IdIOHandler, IdException;

type
  TIdIOHandlerSocket = class(TIdIOHandler)
  protected
    FBinding: TIdSocketHandle;
    FUseNagle: boolean;
    FSocksInfo: TIdSocksInfo;

    procedure SetSocksInfo(ASocks: TIdSocksInfo);
    function GetSocksInfo: TIdSocksInfo;

    procedure Notification(AComponent: TComponent; Operation: TOperation); override;
  public
    procedure Close; override;
    procedure ConnectClient(const AHost: string; const APort: Integer; const ABoundIP: string;
     const ABoundPort: Integer; const ABoundPortMin: Integer; const ABoundPortMax: Integer;
     const ATimeout: Integer = IdTimeoutDefault); override;
    function Connected: Boolean; override;
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Open; override;
    function Readable(AMSec: integer = IdTimeoutDefault): boolean; override;
    function Recv(var ABuf; ALen: integer): integer; override;
    function Send(var ABuf; ALen: integer): integer; override;
    //
    property Binding: TIdSocketHandle read FBinding;
  published
    property SocksInfo: TIdSocksInfo read GetSocksInfo write SetSocksInfo;
    property UseNagle: boolean read FUseNagle write FUseNagle default True;
  end;

implementation

uses
  IdAntiFreezeBase, IdStackConsts, IdResourceStrings, IdStack, IdTCPConnection,
  IdComponent,
  SysUtils;

type
  TIdConnectThread = class(TThread)
  protected
    FBinding: TIdSocketHandle;
    FExceptionMessage: string;
  public
    procedure Execute; override;
  end;

{ TIdIOHandlerSocket }

procedure TIdIOHandlerSocket.Close;
begin
  inherited Close;
  if Assigned(FBinding) then begin
    FBinding.CloseSocket;
  end;
end;

procedure TIdIOHandlerSocket.ConnectClient(const AHost: string;
  const APort: Integer; const ABoundIP: string; const ABoundPort,
  ABoundPortMin, ABoundPortMax: Integer; const ATimeout: Integer = IdTimeoutDefault);

  procedure ConnectTimeout(ATimeout: Integer);
  var
    LSleepTime: Integer;
    LInfinite: Boolean;
  begin
    LInfinite := ATimeout = IdTimeoutInfinite;
    with TIdConnectThread.Create(True) do try
      FBinding := Binding;
      Resume;
      // Sleep
      if TIdAntiFreezeBase.ShouldUse then begin
        LSleepTime := Min(GAntiFreeze.IdleTimeOut, 125);
      end else begin
        LSleepTime := 125;
      end;

      if LInfinite then begin
        ATimeout := LSleepTime + 1;
      end;

      while ATimeout > LSleepTime do begin
        IdGlobal.Sleep(LSleepTime);
        ATimeout := ATimeout - LSleepTime;

        if LInfinite then begin
          ATimeout := LSleepTime + 1;
        end;

        TIdAntiFreezeBase.DoProcess;
        if Terminated then begin
          ATimeout := 0;
          Break;
        end;
      end;
      IdGlobal.Sleep(ATimeout);
      //
      if Terminated then begin
        if Length(FExceptionMessage) > 0 then begin
          raise EIdConnectException.Create(FExceptionMessage);
        end;
      end else begin
        Terminate;
        Close;
        WaitFor;
        raise EIdConnectTimeout.Create(RSConnectTimeout);
      end;
    finally Free; end;
  end;

Var
  LHost: String;
  LPort: Integer;
begin
  // Socks support
  if SocksInfo.Version in [svSocks4, svSocks4A, svSocks5] then begin
    LHost := SocksInfo.Host;
    LPort := SocksInfo.Port;
  end else begin
    LHost := AHost;
    LPort := APort;
  end;

  inherited ConnectClient(LHost, LPort, ABoundIP, ABoundPort, ABoundPortMin, ABoundPortMax, ATimeout);

  with Binding do begin
    AllocateSocket;
    IP := ABoundIP;
    Port := ABoundPort;
    ClientPortMin := ABoundPortMin;
    ClientPortMax := ABoundPortMax;
    Bind;
  end;

  if not GStack.IsIP(LHost) then begin
    DoStatus(hsResolving, [LHost]);
  end;
  // Tell the binding what its destination is
  Binding.SetPeer(GStack.ResolveHost(LHost), LPort);

  if not UseNagle then begin
    Binding.SetSockOpt(Id_IPPROTO_TCP, Id_TCP_NODELAY, PChar(@Id_SO_True), SizeOf(Id_SO_True));
  end;
  // Connect
  DoStatus(hsConnecting, [Binding.PeerIP]);
  if (ATimeout = IdTimeoutDefault) or (ATimeout = 0) then begin
    if TIdAntiFreezeBase.ShouldUse then begin
      ConnectTimeout(120000); // 2 Min
    end else begin
      GStack.CheckForSocketError(Binding.Connect);
    end;
  end else begin
    ConnectTimeout(ATimeout);
  end;

  SocksInfo.MakeSocksConnection(AHost, APort);
end;

function TIdIOHandlerSocket.Connected: Boolean;
begin
  Result := FBinding <> nil;
  if Result then begin
    Result := FBinding.HandleAllocated;
  end;
end;

constructor TIdIOHandlerSocket.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FUseNagle := True;
end;

procedure TIdIOHandlerSocket.Open;
begin
  inherited Open;

  if not Assigned(FBinding) then begin
    FBinding := TIdSocketHandle.Create(nil);
  end
  else
    FBinding.Reset(true);
end;

function TIdIOHandlerSocket.Readable(AMSec: integer): boolean;
begin
  Result := Binding.Readable(AMSec);
end;

function TIdIOHandlerSocket.Recv(var ABuf; ALen: integer): integer;
begin
  if Connected then
  begin
    Result := Binding.Recv(ABuf, ALen, 0);
  end
  else begin
    raise EIdClosedSocket.Create(RSStatusDisconnected);
  end;
end;

function TIdIOHandlerSocket.Send(var ABuf; ALen: integer): integer;
begin
  if Connected then
  begin
    Result := Binding.Send(ABuf, ALen, 0);
  end
  else begin
    raise EIdClosedSocket.Create(RSStatusDisconnected);
  end;
end;

procedure TIdIOHandlerSocket.SetSocksInfo(ASocks: TIdSocksInfo);
begin
  // All this is to preserve the compatibility with old version
  // In the case when we have SocksInfo as object created in runtime without owner form it is treated as temporary object
  // In the case when the ASocks points to an object with owner it is treated as component on form.

  if Assigned(ASocks) then begin
    if not Assigned(ASocks.Owner) then begin
      if Assigned(SocksInfo.Owner) then begin
        FSocksInfo := nil;
      end;
      SocksInfo.Assign(ASocks); // This will construct the default SocksInfo
    end
    else begin
      if Assigned(FSocksInfo) then begin
        if not Assigned(FSocksInfo.Owner) then begin
          FreeAndNil(FSocksInfo);
        end;
      end;
      FSocksInfo := ASocks;
      FSocksInfo.FreeNotification(self);
    end;
    FSocksInfo.IOHandler := Self;
  end
  else begin
    FSocksInfo := ASocks;
  end;
end;

function TIdIOHandlerSocket.GetSocksInfo: TIdSocksInfo;
begin
{
Note that we didn't create the Socks Object at design-time for some reason
but I forgot what that reason was.  If this introduces unintended consequence, this patch ill
be removed and things may have to be reworked.
}
//  if (not (csDesigning in ComponentState)) and (not Assigned(FSocksInfo)) then begin
  if (not Assigned(FSocksInfo)) then begin
    FSocksInfo := TIdSocksInfo.Create(nil);
  end;
  result := FSocksInfo;
end;

destructor TIdIOHandlerSocket.Destroy;
begin
  if Assigned(FSocksInfo) then begin
    if FSocksInfo.Owner = nil then begin
      FreeAndNil(FSocksInfo);
    end;
  end;

  FreeAndNil(FBinding);
  inherited Destroy;
end;

procedure TIdIOHandlerSocket.Notification(AComponent: TComponent; Operation: TOperation);
begin
  inherited Notification(AComponent, OPeration);

  if (Operation = opRemove) then begin
    if (AComponent = FSocksInfo) then begin
      FSocksInfo := nil;
    end;
  end;
end;

{ TIdConnectThread }

procedure TIdConnectThread.Execute;
begin
  try
    // Id_WSAEBADF (9) on Linux, Id_WSAENOTSOCK (10038) on Windows
    GStack.CheckForSocketError(FBinding.Connect, [Id_WSAEBADF, Id_WSAENOTSOCK]);
  except on
    E: Exception do begin
      FExceptionMessage := E.Message;
    end;
  end;
  // Necessary as caller checks this
  Terminate;
end;

end.
