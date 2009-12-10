{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10323: IdSMTP.pas 
{
{   Rev 1.0    2002.11.12 10:51:30 PM  czhower
}
unit IdSMTP;

interface

uses
  Classes,
  IdAssignedNumbers,
  IdEMailAddress,
  IdGlobal,
  IdHeaderList,
  IdMessage, IdMessageClient;

const
  IdDEF_UseEhlo = TRUE; //APR: default behavior

type
  TAuthenticationType = (atNone, atLogin);

  TIdSMTP = class(TIdMessageClient)
  protected
    {This is just an internal flag we use to determine if we already
     authenticated to the server }
    FDidAuthenticate : Boolean;
    FAuthenticationType: TAuthenticationType;
    FAuthSchemesSupported: TStringList;
    FMailAgent: string;
    {HELO Login}
    FHeloName : String;
    FUseEhlo: Boolean; //APR: for OLD STUPID server's    {Do not Localize}
    //
    procedure GetAuthTypes;
    function IsAuthProtocolAvailable (Auth: TAuthenticationType): Boolean; virtual;
    procedure SetAuthenticationType(const Value: TAuthenticationType);
    procedure SetUseEhlo(const Value: Boolean);
  public
    procedure Assign(Source: TPersistent); override;
    function Authenticate : Boolean; virtual;
    procedure Connect(const ATimeout: Integer = IdTimeoutDefault); override;
    constructor Create ( AOwner : TComponent ); override;
    destructor Destroy; override;
    procedure Disconnect; override;
    class procedure QuickSend ( const AHost, ASubject, ATo,
      AFrom, AText : String);
    procedure Send (AMsg: TIdMessage); virtual;
    procedure Expand( AUserName : String; AResults : TStrings); virtual;
    function Verify( AUserName : String) : String; virtual;
    //
    property AuthSchemesSupported: TStringList read FAuthSchemesSupported;
  published
    property AuthenticationType : TAuthenticationType read FAuthenticationType
      write SetAuthenticationType;
    property MailAgent: string read FMailAgent write FMailAgent;
    property HeloName : string read FHeloName write FHeloName;
    property UseEhlo: Boolean read FUseEhlo write SetUseEhlo default IdDEF_UseEhlo;
    property Password;
    property Username;
  end;

implementation

uses
  IdCoderMIME,
  IdResourceStrings,
  SysUtils;

{ TIdSMTP }

procedure TIdSMTP.Assign(Source: TPersistent);
begin
  if Source is TIdSMTP then begin
    AuthenticationType := TIdSMTP(Source).AuthenticationType;
    Host := TIdSMTP(Source).Host;
    MailAgent := TIdSMTP(Source).MailAgent;
    Password := TIdSMTP(Source).Password;
    Port := TIdSMTP(Source).Port;
    Username := TIdSMTP(Source).Username;
  end else begin
    inherited;
  end;
end;

function TIdSMTP.Authenticate : Boolean;

  function AuthLogin : Boolean;
  begin
    {for some odd reason wcSMTP does not accept lowercase 'LOGIN" (WcSMTP is    
     part of the WildCat Interactive Net Server}
    SendCmd('AUTH LOGIN', 334);    {Do not Localize}
    SendCmd(TIdEncoderMIME.EncodeString(Username), 334);
    SendCmd(TIdEncoderMIME.EncodeString(Password), 235);
    Result := True;
  end;

begin
  Result := False;  //assume failure
  case FAUthenticationType of
   atLogin : Result := AuthLogin;
  end;
  FDidAuthenticate := True;
end;

procedure TIdSMTP.Connect(const ATimeout: Integer = IdTimeoutDefault);
var
  NameToSend : String;
begin
  inherited;
  try
    GetResponse([220]);
    FAuthSchemesSupported.Clear;
    if Length(FHeloName) > 0 then
      NameToSend := FHeloName
    else
      NameToSend := LocalName;
    if FUseEhlo and (SendCmd('EHLO ' + NameToSend )=250) then begin //APR: user can prevent EHLO    {Do not Localize}
      GetAuthTypes;
    end
    else begin
      SendCmd( 'HELO ' + NameToSend, 250 );    {Do not Localize}
    end;
  except
    Disconnect;
    Raise;
  end;
end;

constructor TIdSMTP.Create(AOwner: TComponent);
begin
  inherited;
  FAuthSchemesSupported := TStringList.Create;
  FAuthSchemesSupported.Duplicates := dupIgnore; //prevent duplicates in the supported AUTH protocol list
  FUseEhlo:=IdDEF_UseEhlo;
  FAuthenticationType:=atNone;
  Port := IdPORT_SMTP;
end;

destructor TIdSMTP.Destroy;
begin
  FreeAndNil ( FAuthSchemesSupported );
  inherited;
end;

procedure TIdSMTP.Disconnect;
begin
  try
    if Connected then  begin
      WriteLn('QUIT');    {Do not Localize}
    end;
  finally
    inherited;
    FDidAuthenticate := False;
  end;
end;

procedure TIdSMTP.Expand(AUserName: String; AResults: TStrings);
begin
  SendCMD('EXPN ' + AUserName, [250, 251]);    {Do not Localize}
end;

procedure TIdSMTP.GetAuthTypes;
var
  i: Integer;
  s: string;
  LEntry : String;
begin
  for i := 0 to LastCmdResult.Text.Count - 1 do begin
    s := UpperCase(LastCmdResult.Text[i]);
    if AnsiSameText(Copy(s, 1, 5), 'AUTH ') or AnsiSameText(Copy(s, 1, 5), 'AUTH=') then begin    {Do not Localize}
      s := Copy(s, 5, MaxInt);
      while Length(s) > 0 do begin
        s := StringReplace(s, '=', ' ', [rfReplaceAll]);    {Do not Localize}
        LEntry := Fetch(s, ' ');    {Do not Localize}
        if FAuthSchemesSupported.IndexOf(LEntry) = -1 then begin
          FAuthSchemesSupported.Add(LEntry);
        end;
      end;
    end;
  end;
end;

function TIdSMTP.IsAuthProtocolAvailable(
  Auth : TAuthenticationType ) : Boolean;
begin
  case Auth of
    atLogin : Result := ( FAuthSchemesSupported.IndexOf ( 'LOGIN' ) <> -1 );    {Do not Localize}
  else
    Result := False;
  end;
end;

class procedure TIdSMTP.QuickSend (const AHost, ASubject, ATo, AFrom, AText : String);
var
  LSMTP: TIdSMTP;
  LMsg: TIdMessage;
begin
  LSMTP := TIdSMTP.Create(nil);
  try
    LMsg := TIdMessage.Create(LSMTP);
    try
      with LMsg do
      begin
        Subject := ASubject;
        Recipients.EMailAddresses := ATo;
        From.Text := AFrom;
        Body.Text := AText;
      end;
      with LSMTP do
      begin
        Host := AHost;
        Connect; try;
          Send(LMsg);
        finally Disconnect; end;
      end;
    finally
      FreeAndNil(LMsg);
    end;
  finally
    FreeAndNil(LSMTP);
  end;
end;

procedure TIdSMTP.Send(AMsg: TIdMessage);

  procedure WriteRecipient(const AEmailAddress: TIdEmailAddressItem);
  begin
    SendCmd('RCPT TO:<' + AEMailAddress.Address + '>', [250, 251]);    {Do not Localize}
  end;

  procedure WriteRecipients(AList: TIdEmailAddressList);
  var
    i: integer;
  begin
    for i := 0 to AList.Count - 1 do begin
      WriteRecipient(AList[i]);
    end;
  end;

  function NeedToAuthenticate: Boolean;
  begin
    if FAuthenticationType <> atNone then begin
      Result := IsAuthProtocolAvailable(FAuthenticationType) and (FDidAuthenticate = False);
    end else begin
      Result := False;
    end;
  end;

begin
  SendCmd('RSET');    {Do not Localize}
  if NeedToAuthenticate then begin
    Authenticate;
  end;
  SendCmd('MAIL FROM:<' + AMsg.From.Address + '>', 250);    {Do not Localize}
  WriteRecipients(AMsg.Recipients);
  WriteRecipients(AMsg.CCList);
  WriteRecipients(AMsg.BccList);
  SendCmd('DATA', 354);    {Do not Localize}
  AMsg.ExtraHeaders.Values['X-Mailer'] := MailAgent;    {Do not Localize}
  SendMsg(AMsg);
  SendCmd('.', 250);    {Do not Localize}
end;

procedure TIdSMTP.SetAuthenticationType(const Value: TAuthenticationType);
Begin
  FAuthenticationType:= Value;
  if Value=atLogin then FUseEhlo:=TRUE;
End;//

procedure TIdSMTP.SetUseEhlo(const Value: Boolean);
Begin
  FUseEhlo:= Value;
  if NOT Value then FAuthenticationType:=atNone;
End;//

function TIdSMTP.Verify(AUserName: string): string;
begin
  SendCMD('VRFY ' + AUserName, [250, 251]);    {Do not Localize}
  Result := LastCmdResult.Text[0];
end;

end.
