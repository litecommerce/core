{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10363: IdTCPClient.pas 
{
{   Rev 1.0    2002.11.12 10:54:52 PM  czhower
}
unit IdTCPClient;

interface

uses
  Classes, IdStack,
  IdException, IdGlobal, IdTCPConnection;

const
  BoundPortDefault = 0;

type
  TIdTCPClient = class(TIdTCPConnection)
  protected
    FBoundIP: string;
    FBoundPort: Integer;
    FBoundPortMax: Integer;
    FBoundPortMin: Integer;
    FHost: string;
    FOnConnected: TNotifyEvent;
    FPassword: string;
    FPort: integer;
    FUsername: string;
    //
    procedure SetHost(const Value: string); virtual;
    procedure SetPort(const Value: integer); virtual;
    procedure DoOnConnected; virtual;
    //
    property Username: string read FUsername write FUsername;
    property Password: string read FPassword write FPassword;
  public
    procedure Connect(const ATimeout: Integer = IdTimeoutDefault); virtual;
    function ConnectAndGetAll: string; virtual;
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    //
    property BoundPortMax: Integer read FBoundPortMax write FBoundPortMax;
    property BoundPortMin: Integer read FBoundPortMin write FBoundPortMin;
  published
    property BoundIP: string read FBoundIP write FBoundIP;
    property BoundPort: Integer read FBoundPort write FBoundPort default BoundPortDefault;
    property Host: string read FHost write SetHost;
    property OnConnected: TNotifyEvent read FOnConnected write FOnConnected;
    property Port: integer read FPort write SetPort;
  end;

implementation

uses
  IdComponent, IdIOHandlerSocket, IdResourceStrings,
  SysUtils;

{ TIdTCPClient }

procedure TIdTCPClient.Connect(const ATimeout: Integer = IdTimeoutDefault);
begin
  // Do not call Connected here, it will call CheckDisconnect
  if IOHandler <> nil then begin
    if IOHandler.Connected then begin
      raise EIdAlreadyConnected.Create(RSAlreadyConnected);
    end;
  end else begin
    IOHandler := TIdIOHandlerSocket.Create(Self);
    IOHandler.OnStatus := OnStatus;
    FFreeIOHandlerOnDisconnect := True;
  end;

  try
    IOHandler.Open;
    ResetConnection;

    // Socks support
    IOHandler.ConnectClient(Host, Port, BoundIP, BoundPort, BoundPortMin, BoundPortMax, ATimeout);

    if Assigned(Intercept) then begin
      Intercept.Connect(Self);
    end;
    DoStatus(hsConnected, [Host]);
    DoOnConnected;
  except
    // This will free IOHandler
    DisconnectSocket;
    raise;
  end;
end;

function TIdTCPClient.ConnectAndGetAll: string;
begin
  Connect; try
    Result := AllData;
  finally Disconnect; end;
end;

constructor TIdTCPClient.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FBoundPort := BoundPortDefault;
end;

destructor TIdTCPClient.Destroy;
begin
  inherited Destroy;
end;

procedure TIdTCPClient.DoOnConnected;
begin
  if Assigned(OnConnected) then begin
    OnConnected(Self);
  end;
end;

procedure TIdTCPClient.SetHost(const Value: string);
begin
  FHost := Value;
end;

procedure TIdTCPClient.SetPort(const Value: integer);
begin
  FPort := Value;
end;

end.
