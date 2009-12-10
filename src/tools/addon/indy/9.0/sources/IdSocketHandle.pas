{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10333: IdSocketHandle.pas 
{
{   Rev 1.0    2002.11.12 10:52:20 PM  czhower
}
unit IdSocketHandle;

interface

uses
  Classes,
  IdException,
  IdGlobal,
  IdStack, IdStackConsts;

type
  TIdSocketHandle = class;

  TIdSocketHandles = class(TOwnedCollection)
  protected
    FDefaultPort: integer;
    //
    function GetItem(Index: Integer): TIdSocketHandle;
    procedure SetItem(Index: Integer; const Value: TIdSocketHandle);
  public
    constructor Create(AOwner: TComponent); reintroduce;
    function Add: TIdSocketHandle; reintroduce;
    function BindingByHandle(const AHandle: TIdStackSocketHandle): TIdSocketHandle;
    property Items[Index: Integer]: TIdSocketHandle read GetItem write SetItem; default;
    //
    property DefaultPort: integer read FDefaultPort write FDefaultPort;
  end;

  TIdSocketHandle = class(TCollectionItem)
  protected
    FHandle: TIdStackSocketHandle;
    FHandleAllocated: Boolean;
    FIP, FPeerIP: string;
    FPort, FPeerPort: integer;
    FClientPortMin,
    FClientPortMax : Integer;
    function TryBind : Boolean;
    function BindPortReserved : Boolean;
  public
    function Accept(ASocket: TIdStackSocketHandle): boolean;
    procedure AllocateSocket(const ASocketType: Integer = Id_SOCK_STREAM;
     const AProtocol: Integer = Id_IPPROTO_IP);
		// Returns True if error was ignored (Matches iIgnore), false if no error occurred
    procedure Assign(Source: TPersistent); override;
    procedure Bind;
    procedure CloseSocket(const AResetLocal: boolean = True); virtual;
    function Connect(const AFamily: Integer = Id_PF_INET): Integer; virtual;
    constructor Create(ACollection: TCollection); override;
    destructor Destroy; override;
    procedure GetSockOpt(level, optname: Integer; optval: PChar; optlen: Integer);
    procedure Listen(const anQueueCount: integer = 5);
    function Readable(AMSec: Integer = IdTimeoutDefault): boolean;
    function Recv(var ABuf; ALen, AFlags: Integer): Integer;
    function RecvFrom(var ABuffer; const ALength, AFlags: Integer; var VIP: string;
     var VPort: Integer): Integer; virtual;
    procedure Reset(const AResetLocal: boolean = True);
    function Send(var Buf; len, flags: Integer): Integer;
		procedure SendTo(const AIP: string; const APort: Integer; var ABuffer;
     const ABufferSize: Integer);
    procedure SetPeer(const asIP: string; anPort: integer);
    procedure SetSockOpt(level, optname: Integer; optval: PChar; optlen: Integer);
    function Select(ASocket: TIdStackSocketHandle; ATimeOut: Integer): boolean;
    procedure UpdateBindingLocal;
    procedure UpdateBindingPeer;
    //
    property HandleAllocated: Boolean read FHandleAllocated;
    property Handle: TIdStackSocketHandle read FHandle;
  	property PeerIP: string read FPeerIP;
    property PeerPort: integer read FPeerPort;
  published
    property ClientPortMin : Integer read FClientPortMin write FClientPortMin default 0;
    property ClientPortMax : Integer read FClientPortMax write FClientPortMax default 0;
    property IP: string read FIP write FIP;
    property Port: integer read FPort write FPort;
  end;
  EIdSocketHandleError = class(EIdException);
  EIdPackageSizeTooBig = class(EIdSocketHandleError);
  EIdNotAllBytesSent = class (EIdSocketHandleError);
  EIdCouldNotBindSocket = class (EIdSocketHandleError);
  EIdCanNotBindPortInRange = class (EIdSocketHandleError);
  EIdInvalidPortRange = class(EIdSocketHandleError);
implementation

uses
  IdAntiFreezeBase,
  IdComponent,
  IdResourceStrings;

{ TIdSocketHandle }

procedure TIdSocketHandle.AllocateSocket(const ASocketType: Integer = Id_SOCK_STREAM;
 const AProtocol: Integer = Id_IPPROTO_IP);
begin
  // If we are reallocating a socket - close and destroy the old socket handle
  CloseSocket;
  if HandleAllocated then begin
    Reset;
  end;
  FHandle := GStack.CreateSocketHandle(ASocketType, AProtocol);
  FHandleAllocated := True;
end;

procedure TIdSocketHandle.CloseSocket(const AResetLocal: boolean = True);
begin
  if HandleAllocated then begin
    // Must be first, closing socket will trigger some errors, and they
    // may then check (in other threads) Connected, which checks this.
    FHandleAllocated := False;
    GStack.WSShutdown(Handle, Id_SD_Default);
    // SO_LINGER is false - socket may take a little while to actually close after this
    GStack.WSCloseSocket(Handle);
  end;
end;

function TIdSocketHandle.Connect(const AFamily: Integer = Id_PF_INET): Integer;
begin
  Result := GStack.WSConnect(Handle, AFamily, PeerIP, PeerPort);
  if Result <> Id_Socket_Error then begin
    // UpdateBindingLocal needs to be called even though Bind calls it. After Bind is may be
    // 0.0.0.0 (INADDR_ANY). After connect it will be a real IP.
    UpdateBindingLocal;
    //TODO: Could Peer binding ever be other than what we specified above? Need to reread it?
    UpdateBindingPeer;
  end;
end;

destructor TIdSocketHandle.Destroy;
begin
  CloseSocket;
  inherited;
end;

function TIdSocketHandle.Recv(var ABuf; ALen, AFlags: Integer): Integer;
begin
  result := GStack.WSRecv(Handle, ABuf, ALen, AFlags);
end;

function TIdSocketHandle.Send(var Buf; len, flags: Integer): Integer;
begin
  result := GStack.WSSend(Handle, Buf, len, flags);
end;

procedure TIdSocketHandle.SetSockOpt(level, optname: Integer; optval: PChar; optlen: Integer);
begin
  GStack.CheckForSocketError(GStack.WSSetSockOpt(Handle, level, optname, optval, optlen));
end;

procedure TIdSocketHandle.SendTo(const AIP: string; const APort: Integer; var ABuffer;
  const ABufferSize: Integer);
var
  BytesOut: Integer;
begin
  BytesOut := GStack.WSSendTo(Handle, ABuffer, ABufferSize, 0, AIP, APort);
  if BytesOut = Id_SOCKET_ERROR then begin
    if GStack.WSGetLastError() = Id_WSAEMSGSIZE then begin
      raise EIdPackageSizeTooBig.Create(RSPackageSizeTooBig);
    end else begin
      GStack.CheckForSocketError;
    end;
  end else if BytesOut <> ABufferSize then begin
    raise EIdNotAllBytesSent.Create(RSNotAllBytesSent);
  end;
end;

function TIdSocketHandle.RecvFrom(var ABuffer; const ALength, AFlags: Integer; var VIP: string;
 var VPort: Integer): Integer;
begin
  result := GStack.WSRecvFrom(Handle, ABuffer, ALength, AFlags, VIP, VPort);
end;

procedure TIdSocketHandle.Bind;
begin
  if (Port = 0) and (FClientPortMin <> 0) and (FClientPortMax <> 0) then
  begin
    if (FClientPortMin > FClientPortMax) then begin
      Raise EIdInvalidPortRange.CreateFmt( RSInvalidPortRange, [FClientPortMin, FClientPortMax]);
    end
    else
    begin
      if not BindPortReserved then
        Raise EIdCanNotBindPortInRange.CreateFmt( RSCanNotBindRange ,[FClientPortMin,
          FClientPortMax]);
    end;
  end
  else
  begin
    if not TryBind then begin
      raise EIdCouldNotBindSocket.Create(RSCouldNotBindSocket);
    end;
  end;
end;

procedure TIdSocketHandle.SetPeer(const asIP: string; anPort: integer);
begin
  FPeerIP := asIP;
  FPeerPort := anPort;
end;

procedure TIdSocketHandle.Listen(const anQueueCount: integer);
begin
  GStack.CheckForSocketError(GStack.WSListen(Handle, anQueueCount));
end;

function TIdSocketHandle.Accept(ASocket: TIdStackSocketHandle): boolean;
var
  LAcceptedSocket: TIdStackSocketHandle;
begin
  Reset;
  LAcceptedSocket := GStack.WSAccept(ASocket, FIP, FPort);
  Result := not GStack.CheckForSocketError(LAcceptedSocket,
   [Id_WSAEINTR, // CloseSocket while in Accept
   Id_WSAENOTSOCK]); // CloseSocket just prior to Accept
  if Result then begin
    FHandle := LAcceptedSocket;
    FHandleAllocated := True;
    // UpdateBindingLocal is necessary as it may be listening on multiple IPs/Ports
    UpdateBindingLocal;
    UpdateBindingPeer;
  end;
end;

constructor TIdSocketHandle.Create(ACollection: TCollection);
begin
  inherited Create(ACollection);
  Reset;
  FClientPortMin := 0;
  FClientPortMax := 0;
  if assigned(ACollection) then begin
    Port := TIdSocketHandles(ACollection).DefaultPort;
  end;
end;

function TIdSocketHandle.Readable(AMSec: Integer = IdTimeoutDefault): boolean;
var
  ReadList: TList;
begin
  if not FHandleAllocated then begin
    raise EIdConnClosedGracefully.Create(RSConnectionClosedGracefully);
  end;

  if TIdAntiFreezeBase.ShouldUse then begin
    if AMSec = IdTimeoutInfinite then begin
      repeat
        Result := Readable(GAntiFreeze.IdleTimeOut);
      until Result;
      Exit;
    end else if AMSec > GAntiFreeze.IdleTimeOut then begin
      Result := Readable(AMSec - GAntiFreeze.IdleTimeOut);
      if Result then begin
        Exit;
      end else begin
        AMSec := GAntiFreeze.IdleTimeOut;
      end;
    end;
  end;
  ReadList := TList.Create; try
    ReadList.Add(Pointer(Handle));
    Result := GStack.WSSelect(ReadList, nil, nil, AMSec) = 1;
    TIdAntiFreezeBase.DoProcess(result = false);
  finally ReadList.free; end;
end;

procedure TIdSocketHandle.Assign(Source: TPersistent);
var
  LSource: TIdSocketHandle;
begin
  if ClassType <> Source.ClassType then begin
    inherited
  end else begin
    LSource := TIdSocketHandle(Source);
    IP := LSource.IP;
    Port := LSource.Port;
    FPeerIP := LSource.PeerIP;
    FPeerPort := LSource.PeerPort;
  end;
end;

procedure TIdSocketHandle.UpdateBindingLocal;
var
  LFamily: integer;
begin
  GStack.WSGetSockName(Handle, LFamily, FIP, FPort);
end;

procedure TIdSocketHandle.UpdateBindingPeer;
var
  LFamily: integer;
begin
  GStack.WSGetPeerName(Handle, LFamily, FPeerIP, FPeerPort);
end;

procedure TIdSocketHandle.Reset(const AResetLocal: boolean = True);
begin
  FHandleAllocated := False;
  FHandle := Id_INVALID_SOCKET;
  if AResetLocal then begin
    FIP := '';    {Do not Localize}
    FPort := 0;
  end;
  FPeerIP := '';    {Do not Localize}
  FPeerPort := 0;
end;

function TIdSocketHandle.TryBind: Boolean;
begin
  Result := not GStack.CheckForSocketError(GStack.WSBind(Handle, Id_PF_INET, IP, Port)
   , [Id_WSAEADDRINUSE]);
  if Result then begin
    UpdateBindingLocal;
  end;
end;

function TIdSocketHandle.BindPortReserved: Boolean;
var
  i : Integer;
begin
  Result := false;
  for i := FClientPortMax downto FClientPortMin do begin
    FPort := i;
    if TryBind then begin
      Result := True;
      Exit;
    end;
  end;
end;

procedure TIdSocketHandle.GetSockOpt(level, optname: Integer; optval: PChar; optlen: Integer);
begin
  GStack.CheckForSocketError(GStack.WSGetSockOpt(Handle, level, optname, optval, optlen));
end;

function TIdSocketHandle.Select(ASocket: TIdStackSocketHandle;
  ATimeOut: Integer): boolean;
var
  ReadList: TList;
begin
  ReadList := TList.Create; try
    ReadList.Add(Pointer(ASocket));
    Result := GStack.WSSelect(ReadList, nil, nil, ATimeOut) = 1;
    TIdAntiFreezeBase.DoProcess(result = false);
  finally ReadList.free; end;
end;

{ TIdSocketHandles }

function TIdSocketHandles.Add: TIdSocketHandle;
begin
  Result := Inherited Add as TIdSocketHandle;
  Result.Port := DefaultPort;
end;

function TIdSocketHandles.BindingByHandle(const AHandle: TIdStackSocketHandle): TIdSocketHandle;
var
  i: integer;
begin
  Result := nil;
  i := Count - 1;
  while (i >= 0) and (Items[i].Handle <> AHandle) do begin
    dec(i);
  end;
  if i >= 0 then begin
    Result := Items[i];
  end;
end;

constructor TIdSocketHandles.Create(AOwner: TComponent);
begin
  inherited Create(AOwner, TIdSocketHandle);
end;

function TIdSocketHandles.GetItem(Index: Integer): TIdSocketHandle;
begin
  Result := TIdSocketHandle(inherited Items[index]);
end;

procedure TIdSocketHandles.SetItem(Index: Integer; const Value: TIdSocketHandle);
begin
  inherited SetItem(Index, Value);
end;

end.
