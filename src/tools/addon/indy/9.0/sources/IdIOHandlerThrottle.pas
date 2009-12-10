{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10217: IdIOHandlerThrottle.pas 
{
{   Rev 1.0    2002.11.12 10:42:54 PM  czhower
}
unit IdIOHandlerThrottle;

interface
uses
  Classes,
  IdComponent, IdGlobal, IdIOHandler;

type
  TIdIOHandlerThrottle = class(TIdIOHandler)
  protected
    FChainedHandler : TIdIOHandler;
    FBytesPerSec : Cardinal;
    function GetBitsPerSec : Cardinal;
    procedure SetBitsPerSec(AValue : Cardinal);
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
  published
    property BytesPerSec : Cardinal read FBytesPerSec write FBytesPerSec;
    property BitsPerSec : Cardinal read GetBitsPerSec write SetBitsPerSec;
    property ChainedHandler : TIdIOHandler read FChainedHandler write FChainedHandler;
  end;

implementation
uses IdException, IdResourceStrings, SysUtils;

type EIdThrottleNoChainedIOHandler = class(EIdException);

{ TIdIOHandlerThrottle }

procedure TIdIOHandlerThrottle.Close;
begin
  inherited;
  if Assigned(FChainedHandler) then
  begin
    FChainedHandler.Close;
  end;
end;

procedure TIdIOHandlerThrottle.ConnectClient(const AHost: string;
  const APort: Integer; const ABoundIP: string; const ABoundPort,
  ABoundPortMin, ABoundPortMax, ATimeout: Integer);
begin
  inherited;
  if Assigned(FChainedHandler) then
  begin
    FChainedHandler.ConnectClient(AHost,APort,ABoundIP,ABoundPort,ABoundPortMin,ABoundPortMax,ATimeout);
  end
  else
  begin
    raise EIdThrottleNoChainedIOHandler.Create(RSIHTChainedNotAssigned);
  end;
end;

function TIdIOHandlerThrottle.Connected: Boolean;
begin
  if Assigned(FChainedHandler) then
  begin
    Result := FChainedHandler.Connected;
  end
  else
  begin
    Result := False;
  end;
end;

constructor TIdIOHandlerThrottle.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
end;

destructor TIdIOHandlerThrottle.Destroy;
begin
  inherited Destroy;
end;

function TIdIOHandlerThrottle.GetBitsPerSec: Cardinal;
begin
  Result := FBytesPerSec * 8;
end;


procedure TIdIOHandlerThrottle.Notification(AComponent: TComponent;
  Operation: TOperation);
begin
  if (Operation = opRemove) then begin
    if (AComponent = FChainedHandler) then begin
      FChainedHandler := nil;
    end;
  end;
  inherited;
end;

procedure TIdIOHandlerThrottle.Open;
begin
  inherited Open;
  if Assigned(FChainedHandler) then
  begin
    FChainedHandler.Open;
  end
  else
  begin
    raise EIdThrottleNoChainedIOHandler.Create(RSIHTChainedNotAssigned);
  end;
end;

function TIdIOHandlerThrottle.Readable(AMSec: integer): boolean;
begin
  if Assigned(FChainedHandler) then
  begin
    Result := FChainedHandler.Readable(AMSec);
  end
  else
  begin
    Result := False;
  end;
end;

function TIdIOHandlerThrottle.Recv(var ABuf; ALen: integer): integer;
var LWaitTime : Cardinal;
    LRecVTime : Cardinal;
begin
  if Assigned(FChainedHandler) then
  begin
    if FBytesPerSec > 0 then begin
      LRecvTime := IdGlobal.GetTickCount;
      Result := FChainedHandler.Recv(ABuf, ALen);
      LRecvTime := GetTickDiff(LRecvTime, IdGlobal.GetTickCount);
      LWaitTime := Cardinal(Result * 1000) div FBytesPerSec;
      if LWaitTime > LRecVTime then begin
        IdGlobal.Sleep(LWaitTime - LRecvTime);
      end;
    end else begin
      Result := FChainedHandler.Recv(ABuf, ALen);
    end;
  end
  else
  begin
    Result := 0;
  end;
end;

function TIdIOHandlerThrottle.Send(var ABuf; ALen: integer): integer;
var WaitTime : Cardinal;
    SendTime : Cardinal;
begin
  if Assigned(FChainedHandler) then
  begin
    if FBytesPerSec > 0 then
    begin
      WaitTime :=  Cardinal(ALen * 1000) div FBytesPerSec;
      SendTime := IdGlobal.GetTickCount;
      Result := FChainedHandler.Send(ABuf,ALen);
      SendTime := GetTickDiff(SendTime,IdGlobal.GetTickCount);
      if WaitTime > SendTime then
      begin
        IdGlobal.Sleep(WaitTime - SendTime);
      end;
    end
    else
    begin
      Result := FChainedHandler.Send(ABuf,ALen);
    end;
  end
  else
  begin
    Result := 0;
  end;
end;

procedure TIdIOHandlerThrottle.SetBitsPerSec(AValue: Cardinal);
begin
  FBytesPerSec := AValue div 8;
end;

end.
 
