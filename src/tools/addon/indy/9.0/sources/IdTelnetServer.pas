{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10373: IdTelnetServer.pas 
{
{   Rev 1.0    2002.11.12 10:55:48 PM  czhower
}
unit IdTelnetServer;

interface

uses
  Classes,
  IdAssignedNumbers, IdTCPServer,
  IdThreadMgr, IdThreadMgrDefault;

const
  GLoginAttempts = 3;

type

  // SG 16/02/2001: Moved the TTelnetData object from TIdPeerThread to custom TIdPeerThread descendant

  TTelnetData = class(TObject)
  public
    Username, Password: String;
    HUserToken: cardinal;
  end;

  // Custom Peer thread class
  TIdTelnetPeerThread = Class(TIdPeerThread)
  private
    FTelnetData: TTelnetData;
  public
    constructor Create(ACreateSuspended: Boolean = True); override;
    destructor Destroy; override;
    Property TelnetData: TTelnetData read FTelnetData;
  end; //class


  TIdTelnetNegotiateEvent = procedure(AThread: TIdPeerThread) of object;
  TAuthenticationEvent = procedure(AThread: TIdPeerThread;
   const AUsername, APassword: string; var AAuthenticated: Boolean) of object;

  TIdTelnetServer = class(TIdTCPServer)
  protected
    FLoginAttempts: Integer;
    FOnAuthentication: TAuthenticationEvent;
    FLoginMessage: String;
    FOnNegotiate: TIdTelnetNegotiateEvent;
  public
    constructor Create(AOwner: TComponent); override;
    function DoAuthenticate(AThread: TIdPeerThread; const AUsername, APassword: string)
     : boolean; virtual;
    procedure DoNegotiate(AThread: TIdPeerThread); virtual;
    procedure DoConnect(AThread: TIdPeerThread); override;
  published
    property DefaultPort default IdPORT_TELNET;
    property LoginAttempts: Integer read FLoginAttempts write FLoginAttempts Default GLoginAttempts;
    property LoginMessage: String read FLoginMessage write FLoginMessage;
    property OnAuthentication: TAuthenticationEvent read FOnAuthentication write FOnAuthentication;
    property OnNegotiate: TIdTelnetNegotiateEvent read FOnNegotiate write FOnNegotiate;
  end;

implementation

uses
  IdException, IdGlobal, IdResourceStrings, SysUtils;

constructor TIdTelnetServer.Create(AOwner: TComponent);
begin
  inherited;
  LoginAttempts := GLoginAttempts;
  LoginMessage := RSTELNETSRVWelcomeString;
  DefaultPort := IdPORT_TELNET;
  ThreadClass := TIdTelnetPeerThread;
end;

function TIdTelnetServer.DoAuthenticate;
begin
  if not Assigned(OnAuthentication) then begin
    raise EIdException.Create(RSTELNETSRVNoAuthHandler);
  end;
  Result := False;
  OnAuthentication(AThread, AUsername, APassword, result);
end;

procedure TIdTelnetServer.DoConnect(AThread: TIdPeerThread);
Var
  Data: TTelnetData;
  i: integer;
begin
  try
    inherited;
    Data := (AThread as TIdTelnetPeerThread).TelnetData;
    // do protocol negotiation first
    DoNegotiate(AThread);
    // Welcome the user
    if length(LoginMessage) > 0 then
    begin
      AThread.Connection.WriteLn(LoginMessage);
      AThread.Connection.WriteLn('');    {Do not Localize}
    end;
    // Only prompt for creditentials if there is an authentication handler
    if assigned(OnAuthentication) then
    begin
      // ask for username/password.
      for i := 1 to LoginAttempts do
      begin
        // UserName
        AThread.Connection.Write(RSTELNETSRVUsernamePrompt);
        Data.Username := AThread.Connection.InputLn;
        // Password
        AThread.Connection.Write(RSTELNETSRVPasswordPrompt);
        Data.Password := AThread.Connection.InputLn('*');    {Do not Localize}
        AThread.Connection.WriteLn;
        // Check authentication
        if DoAuthenticate(AThread, Data.Username, Data.Password) then begin
          Break; // exit the loop
        end else begin
          AThread.Connection.WriteLn(RSTELNETSRVInvalidLogin); // translate
          if i = FLoginAttempts then begin
            raise EIdException.Create(RSTELNETSRVMaxloginAttempt); // translate
          end;
        end;
      end;
    end;
  except
    on E: Exception do begin
      AThread.Connection.WriteLn(E.Message);
      AThread.Connection.Disconnect;
    end;
  end;
end;

procedure TIdTelnetServer.DoNegotiate(AThread: TIdPeerThread);
begin
  if assigned(FOnNegotiate) then begin
    FOnNegotiate(AThread);
  end;
end;

{ TIdTelnetPeerThread }

constructor TIdTelnetPeerThread.Create(ACreateSuspended: Boolean);
begin
  Inherited;
  FTelnetData := TTelnetData.Create;
end;

destructor TIdTelnetPeerThread.Destroy;
begin
  FreeAndNil(FTelnetData);
  inherited;
end;


end.
