{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10335: IdSocks.pas 
{
{   Rev 1.0    2002.11.12 10:52:26 PM  czhower
}
unit IdSocks;

interface

uses
  Classes, IdAssignedNumbers,
  IdIOHandler, IdComponent, IdStack;

type
  TSocksVersion = (svNoSocks, svSocks4, svSocks4A, svSocks5);
  TSocksAuthentication = (saNoAuthentication, saUsernamePassword);

const
  ID_SOCKS_AUTH = saNoAuthentication;
  ID_SOCKS_VER = svNoSocks;

type
  TIdSocksRequest = record
    Version: Byte;
    OpCode: Byte;
    Port: Word;
    IpAddr: TIdInAddr;
    UserName: String[255];
  end;

  TIdSocksResponse = record
    Version: Byte;
    OpCode: Byte;
    Port: Word;
    IpAddr: TIdInAddr;
  end;

  TIdSocksInfo = class(TIdComponent)
  protected
    FAuthentication: TSocksAuthentication;
    FHost: string;
    FPassword: string;
    FPort: Integer;
    FUsername: string;
    FVersion: TSocksVersion;
    FIOHandler: TIdIOHandler;
    //
    procedure AssignTo(ASource: TPersistent); override;
  public
    constructor Create(AOwner: TComponent); override;
    procedure MakeSocksConnection(const AHost: string; const APort: Integer);
    procedure MakeSocks4Connection(const AHost: string; const APort: Integer);
    procedure MakeSocks5Connection(const AHost: string; const APort: Integer);
    //
    property IOHandler: TIdIOHandler write FIOHandler;
  published
    property Authentication: TSocksAuthentication read FAuthentication write FAuthentication default ID_SOCKS_AUTH;
    property Host: string read FHost write FHost;
    property Password: string read FPassword write FPassword;
    property Port: Integer read FPort write FPort default IdPORT_SOCKS;
    property Username: string read FUsername write FUsername;
    property Version: TSocksVersion read FVersion write FVersion default ID_SOCKS_VER;
  end;

implementation

uses
  IdGlobal, IdException, IdResourceStrings, SysUtils;

{ TIdSocksInfo }

procedure TIdSocksInfo.AssignTo(ASource: TPersistent);
begin
  if ASource is TIdSocksInfo then
    with TIdSocksInfo(ASource) do begin
      Authentication := Self.Authentication;
      Host := Self.Host;
      Password := Self.Password;
      Port := Self.Port;
      Username := Self.Username;
      Version := Self.Version;
    end
  else
    inherited AssignTo(ASource);
end;

constructor TIdSocksInfo.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Authentication := ID_SOCKS_AUTH;
  Version := ID_SOCKS_VER;
  Port := IdPORT_SOCKS;
end;

procedure TIdSocksInfo.MakeSocks4Connection(const AHost: string; const APort: Integer);
var
  i: Integer;
  LRequest: TIdSocksRequest;
  LResponse: TIdSocksResponse;
begin
  LRequest.Version := 4;
  LRequest.OpCode  := 1;
  LRequest.Port := GStack.WSHToNs(APort);
  if Version = svSocks4A then begin
    LRequest.IpAddr := GStack.StringToTInAddr('0.0.0.1');    {Do not Localize}
  end else begin
    LRequest.IpAddr := GStack.StringToTInAddr(GStack.ResolveHost(AHost));
  end;
  LRequest.UserName := Username;
  i := Length(LRequest.UserName); // calc the len of username
  LRequest.UserName[i + 1] := #0;
  if Version = svSocks4A then begin
    Move(AHost[1], LRequest.UserName[i + 2], Length(AHost));
    i := i + 1 + Length(AHost);
    LRequest.UserName[i + 1] := #0;
  end;
  i := 8 + i + 1; // calc the len of request
  FIOHandler.Send(LRequest, i);
  FIOHandler.Recv(LResponse, Sizeof(LResponse));
  case LResponse.OpCode of
    90: ;// request granted, do nothing
    91: raise EIdSocksRequestFailed.Create(RSSocksRequestFailed);
    92: raise EIdSocksRequestServerFailed.Create(RSSocksRequestServerFailed);
    93: raise EIdSocksRequestIdentFailed.Create(RSSocksRequestIdentFailed);
    else raise EIdSocksUnknownError.Create(RSSocksUnknownError);
  end;
end;

procedure TIdSocksInfo.MakeSocks5Connection(const AHost: string; const APort: Integer);
var
  len, pos: Integer;
  tempBuffer: array [0..255] of Byte;
  ReqestedAuthMethod,
  ServerAuthMethod: Byte;
  tempPort: Word;
begin
  // defined in rfc 1928
  if Authentication = saNoAuthentication then begin
    tempBuffer[2] := $0   // No authentication
  end else begin
    tempBuffer[2] := $2;  // Username password authentication
  end;

  ReqestedAuthMethod := tempBuffer[2];
  tempBuffer[0] := $5;     // socks version
  tempBuffer[1] := $1;     // number of possible authentication methods

  len := 2 + tempBuffer[1];
  FIOHandler.Send(tempBuffer, len);
  try
    FIOHandler.Recv(tempBuffer, 2); // Socks server sends the selected authentication method
  except
    On E: Exception do begin
      raise EIdSocksServerRespondError.Create(RSSocksServerRespondError);
    end;
  end;

  ServerAuthMethod := tempBuffer[1];
  if (ServerAuthMethod <> ReqestedAuthMethod) or (ServerAuthMethod = $FF) then begin
    raise EIdSocksAuthMethodError.Create(RSSocksAuthMethodError);
  end;

  // Authentication process
  if Authentication = saUsernamePassword then begin
    tempBuffer[0] := 1; // version of subnegotiation
    tempBuffer[1] := Length(Username);
    pos := 2;
    if Length(Username) > 0 then begin
      Move(Username[1], tempBuffer[pos], Length(Username));
    end;
    pos := pos + Length(Username);
    tempBuffer[pos] := Length(Password);
    pos := pos + 1;
    if Length(Password) > 0 then begin
      Move(Password[1], tempBuffer[pos], Length(Password));
    end;
    pos := pos + Length(Password);

    FIOHandler.Send(tempBuffer, pos); // send the username and password
    try
      FIOHandler.Recv(tempBuffer, 2);    // Socks server sends the authentication status
    except
      On E: Exception do begin
        raise EIdSocksServerRespondError.Create(RSSocksServerRespondError);
      end;
    end;

    if tempBuffer[1] <> $0 then begin
      raise EIdSocksAuthError.Create(RSSocksAuthError);
    end;
  end;

  // Connection process
  tempBuffer[0] := $5;   // socks version
  tempBuffer[1] := $1;   // connect method
  tempBuffer[2] := $0;   // reserved
  // for now we stick with domain name, must ask Chad how to detect
  // address type
  tempBuffer[3] := $3;   // address type: IP V4 address: X'01'    {Do not Localize}
                         //               DOMAINNAME:    X'03'    {Do not Localize}
                         //               IP V6 address: X'04'    {Do not Localize}
  // host name
  tempBuffer[4] := Length(AHost);
  pos := 5;
  if Length(AHost) > 0 then begin
    Move(AHost[1], tempBuffer[pos], Length(AHost));
  end;
  pos := pos + Length(AHost);
  // port
  tempPort := GStack.WSHToNs(APort);
  Move(tempPort, tempBuffer[pos], SizeOf(tempPort));
  pos := pos + 2;

  FIOHandler.Send(tempBuffer, pos); // send the connection packet
  try
    FIOHandler.Recv(tempBuffer, 5);    // Socks server replies on connect, this is the first part
  except
    raise EIdSocksServerRespondError.Create(RSSocksServerRespondError);
  end;

  case tempBuffer[1] of
    0: ;// success, do nothing
    1: raise EIdSocksServerGeneralError.Create(RSSocksServerGeneralError);
    2: raise EIdSocksServerPermissionError.Create(RSSocksServerPermissionError);
    3: raise EIdSocksServerNetUnreachableError.Create(RSSocksServerNetUnreachableError);
    4: raise EIdSocksServerHostUnreachableError.Create(RSSocksServerHostUnreachableError);
    5: raise EIdSocksServerConnectionRefusedError.Create(RSSocksServerConnectionRefusedError);
    6: raise EIdSocksServerTTLExpiredError.Create(RSSocksServerTTLExpiredError);
    7: raise EIdSocksServerCommandError.Create(RSSocksServerCommandError);
    8: raise EIdSocksServerAddressError.Create(RSSocksServerAddressError);
    else
       raise EIdSocksUnknownError.Create(RSSocksUnknownError);
  end;

  // type of destination address is domain name
  case tempBuffer[3] of
    // IP V4
    1: len := 4 + 2; // 4 is for address and 2 is for port length
    // FQDN
    3: len := tempBuffer[4] + 2; // 2 is for port length
    // IP V6
    4: len := 16 + 2; // 16 is for address and 2 is for port length
  end;

  try
    // Socks server replies on connect, this is the seconf part
    FIOHandler.Recv(tempBuffer[5], len-1);
  except
    raise EIdSocksServerRespondError.Create(RSSocksServerRespondError);
  end;
end;

procedure TIdSocksInfo.MakeSocksConnection(const AHost: string; const APort: Integer);
begin
  case Version of
    svSocks4, svSocks4A: MakeSocks4Connection(AHost, APort);
    svSocks5: MakeSocks5Connection(AHost, APort);
  end;
end;

end.
