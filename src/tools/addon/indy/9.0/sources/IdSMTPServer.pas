{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10325: IdSMTPServer.pas 
{
{   Rev 1.3    16/05/2003 9:24:50 PM  ANeillans
{ Fixed Bug 683558 
}
{
{   Rev 1.2    16/05/2003 9:11:56 PM  ANeillans
{ Fixed Bug 664163 
}
{
{   Rev 1.1    02/05/2003 9:23:48 AM  ANeillans
{ Fixed bug: 730575 
}
{
{   Rev 1.0    2002.11.12 10:51:36 PM  czhower
}
unit IdSMTPServer;

{
 Indy SMTP Server - Version 5.1b

 Original Programmer: Andrew Neillans (E-Mail: andy@neillans.co.uk HTTP://www.neillans.co.uk)
 No Copyright. Code is given to the Indy Pit Crew.

 Revision History:
 13-Feb-02: Andy
     - Minor changes, just really to make the responses make sense more :)
 09-Jan-02: Andy
     - Added NoEncode option
 28-Nov-01:
     - Added support for FileStreams in place of MemoryStreams (be nice to server-resources time!!)
     - Allen O'Neill allen_oneill@hotmail.com
 24-Nov-01:
     - Changes merged, submitted by Sheng Quanhu (shengqh@263.net)
       Adds : SMTPState, CustomError, AuthMode
     - Corrected 'formatting' problems
 15-Sept-01:
     - Corrected LMsg bug - reported on newsgroups by Ing. Schardl robert (robert.schardl@asystem.at)
 22-Jul-01:
     - Added NoDecode - Allows you to use the ProcessMessageParsed to do all parsing, except
       the messagebody / messageparts. - 5.0g
 21-Jul-01 - Andy
    - Fixed Al's CommandDATA Changes - some changes in TIdMessageClient also. - 5.0f
 21 July 2001 - Allen O'Neill
    - Fixed CommandDATA OnParsed to use TidIOHandler assignment correctly
 20-Jul-2001:
    - Completed Auth :)
      WARNING! AUTH is UNTESTED! Any problems, e-mail me - address above.
    - Corrected a parsing 'oversight' - Thanks to  Tony McKiernan for finding it :)
 18-Jul-2001:
    - Added Auth Support - 5.0e
 02-Jul-2001:
    - Repaired again - changed in CommandHandler :) - 5.0d
 30-Jun-2001:
    - Modified ProcessRAW.
    - Added CustomError String in RCPT Processing
 29-Jun-2001:
    - Repaired - changes in CommandHandler broke replies. - 5.0c
 28-Jun-2001:
    - Work nearly complete - 99% functionality - 5.0b
    - Completed parsed BCC
 24-Jun-2001 -> 26-Jun-2001 - Andrew
    - Work continues ... 5.0a
 10-May-2001 - Kudzu
    - Extensive work to port to CommandHandler design and other changes
 17-Apr-2001:
    - Some bug fixes :(
 12-Apr-2001:
    - EHLO (along with the associated LOGIN) command now supported.
    - Moved ReceivedHeader and XServer to the Messages section
 19-Feb-2001:
    - Minor updates.
 18-Feb-2001:
    - Another typo correct - RSET was sending a 200 response, instead of a 250.
    - Changes implemented, as asked by Chad Z. Hower <chad-jm@hower.org>
 17-Feb-2001: J. Peter Mugaas
   Moved various message properties into Persistant classes so that there is a more
   logical structure and to prevent a developer from being overwhelmed by the various properites
   in one place
   I hard-coded the Error reply codes for the SMTP Protocol and I moved all other constants
   to IdResourceStrings.  I felt I had to hard-code error reply codes in the SMTP Component
   to prevent developers from mistakenly removing or changing those values breaking client
   programs in the process.  Idiot-proofing.
 17-Feb-2001:
    - Added BCC Support (forgot to add it before, sorry.)
 16-Feb-2001:
    - Adjusted EHLOString - now a TStrings :)
 08-Feb-2001:
    - Minor tweaks.
 05-Feb-2001:
    - Added EHLOString Property - this is what is returned when an acceptable EHLO received.
 27-Jan-2001:
    - Added basic Received Header support
 26-Jan-2001:
    - Corrected a small glitch with Message storage.
    - Corrected a typo - incorrect status number is sent when connection established. Was 200,
      should be 220.
 25-Jan-2001:
    - Added new variable - EndData (sent when OnDATAEvent has fired)
 10-Jan-2001:
    - Actually got the modification to work! Thanks to Jan Pedersen <jan@jpsoft.dk>
 09-Jan-2001:
    - Modified to support TIdMessage
 02-Jan-2001:
    - Added TURN, SOML, VRFY, EXPN, SAML & SEND events, as requested by J. Peter Mugaas.
 31-Dec-2000:
    - Found an error with the component, which only materialises when multiple connections
      attempt to use the MailData : TStrings var - it causes it to save all the information
      as a single email for the first user!
      Fixed it (I hope) by adding a new class to store the session information. Some of
      the new variables in the class are there for future EXPANSION.
 30-Dec-2000:
    - New DCR File, with a much improved icon. Now fits in with the other Indy Icons.
    - Fixed a 'cross over' style bug with EHLO and HELO.    
    - Added a new variables / properties....
        WelcomeString and BasicConnect properties.
         If BasicConnect = True then contents of WelcomeString will be sent to the client,
         with 200 prefixed. This makes the server easier to build / maintain.
        EnableEHLO property.
         If true, the EHLO event can fire, if false it will return a default error.
 29-Dec-2000:
    - Changed some of the syntax of the code, as recommended by J. Peter Mugaas.
    - Nearly a complete rewrite of the procedures
 05-Dec-2000:
    - Corrected a silly error which meant the DATA command was not processed correctly.
    - Changed it so that the SMTPServer Component is now registered onto the Indy Servers Tab.
    - Added the DCR to include an icon - not a good one, but more descriptive than the Delphi Standard.
 04-Dec-2000:
    - Created Unit.
    - Created new SMTP Server Component in accordance to RFC 821

}
interface

uses
  Classes,
  IdTCPClient,
  IdTCPServer,
  IdMessage,
  IdEMailAddress,
  IdCoderMIME,
  IdMessageClient,
  IdIOHandlerSocket,
  IdStack;

type
  TOnReceiveRaw = procedure(ASender: TIdCommand; var VStream: TStream;
    RCPT: TIdEMailAddressList; var CustomError: string) of object;
  TOnReceiveMessage = procedure(ASender: TIdCommand; var AMsg: TIdMessage; RCPT:
    TIdEMailAddressList; var CustomError: string) of object;

  TBasicHandler = procedure(ASender: TIdCommand) of object;
  TUserHandler = procedure(ASender: TIdCommand; var Accept: Boolean; Username,
    Password: string) of object;
  THasAddress = procedure(const ASender: TIdCommand; var Accept, ToForward:
    Boolean; EMailAddress: string; var CustomError: string) of object;
  THasAddress2 = procedure(const ASender: TIdCommand; var Accept: Boolean;
    EMailAddress: string) of object;
  TIdSMTPReceiveMode = (rmRaw, rmMessage, rmMessageParsed);
  TIdStreamType = (stFileStream, stMemoryStream);


  // Start Messages

  TIdSMTPGreeting = class(TPersistent)
  protected
    fHelloReply,
    fNoHello,
    fAuthFailed: string;
    fEHLOReply: TStrings;
    fEHLONotSupported: string;
    procedure SetEHLOReply(const AValue: TStrings);
  public
    constructor Create;
    procedure Assign(Source: TPersistent); override;
    destructor Destroy; override;
  published
    property EHLONotSupported: string read fEHLONotSupported write fEHLONotSupported;
    property HelloReply: string read fHelloReply write fHelloReply;
    property NoHello: string read fNoHello write fNoHello;
    property AuthFailed: string read fAuthFailed write fAuthFailed;
    property EHLOReply: TStrings read FEHLOReply write SetEHLOReply;
  end;

  TIdSMTPRcpReplies = class(TPersistent)
  protected
    fAddressOkReply: string;
    FAddressErrorReply: string;
    FAddressWillForwardReply: string;
  public
    constructor Create;
    procedure Assign(Source: TPersistent); override;
  published
    property AddressOkReply: string read fAddressOkReply write fAddressOkReply;
    property AddressErrorReply: string read FAddressErrorReply write
      FAddressErrorReply;
    property AddressWillForwardReply: string read FAddressWillForwardReply write
      FAddressWillForwardReply;
  end;

  TIdSMTPDataReplies = class(TPersistent)
  public
    fStartDataReply: string;
    fEndDataReply: string;
  public
    constructor Create;
    procedure Assign(Source: TPersistent); override;
  published
    property StartDataReply: string read fStartDataReply write fStartDataReply;
    property EndDataReply: string read fEndDataReply write fEndDataReply;
  end;

  TIdSMTPMessages = class(TPersistent)
  protected
    FNoopReply,
    FRSetReply,
    FQuitReply,
    FErrorReply,
    FSequenceError,
    FNotLoggedIn,
    fReceived,
    fXServer,
    FSyntaxErrorReply: string;

    FDataReplies: TIdSMTPDataReplies;
    FGreeting: TIdSMTPGreeting;
    FRcpReplies: TIdSMTPRcpReplies;
    procedure SetDataReplies(const AValue: TIdSMTPDataReplies);
    procedure SetGreeting(const AValue: TIdSMTPGreeting);
    procedure SetRcpReplies(const AValue: TIdSMTPRcpReplies);
  public
    constructor Create;
    procedure Assign(Source: TPersistent); override;
    destructor Destroy; override;
  published
    property NoopReply: string read FNoopReply write FNoopReply;
    property RSetReply: string read FRSetReply write FRSetReply;
    property QuitReply: string read FQuitReply write FQuitReply;
    property ErrorReply: string read FErrorReply write FErrorReply;
    property SequenceError: string read FSequenceError write FSequenceError;
    property NotLoggedIn: String read FNotLoggedIn write FNotLoggedIn;
    property XServer: string read fXServer write fXServer;
    property ReceivedHeader: string read fReceived write fReceived;
    property SyntaxErrorReply: string read FSyntaxErrorReply write FSyntaxErrorReply;
    property Greeting: TIdSMTPGreeting read FGreeting write SetGreeting;
    property RcpReplies: TIdSMTPRcpReplies read FRcpReplies write SetRcpReplies;
    property DataReplies: TIdSMTPDataReplies read FDataReplies write SetDataReplies;
  end;

  // End Messages

  TIdSMTPServer = class(TIdTCPServer)
  protected
    FOnReceiveRaw: TOnReceiveRaw;
    FOnReceiveMessage: TOnReceiveMessage;
    FOnReceiveMessageParsed: TOnReceiveMessage;
    fAllowEHLO: Boolean;
    fAuthMode: Boolean;
    fNoDecode: Boolean;
    fNoEncode: Boolean;
    fOnCommandRCPT: THasAddress;
    fOnCommandMAIL: THasAddress2;
    fReceiveMode: TIdSMTPReceiveMode;
    fMessages: TIdSMTPMessages;
    fOnCommandHELP,
    fOnCommandSOML,
    fOnCommandSEND,
    fOnCommandSAML,
    fOnCommandVRFY,
    fOnCommandEXPN,
    fOnCommandTURN,
    fOnCommandAUTH: TBasicHandler;
    fCheckUser: TUserHandler;
    //
    procedure CommandData(ASender: TIdCommand);
    procedure CommandMail(ASender: TIdCommand);
    procedure CommandRcpt(ASender: TIdCommand);
    procedure CommandRSET(ASender: TIdCommand);
    procedure CommandHELO(ASender: TIdCommand);
    procedure CommandEHLO(ASender: TIdCommand);
    procedure CommandAUTH(ASender: TIdCommand);
    // Expose to user
    procedure CommandHELP(ASender: TIdCommand);
    procedure CommandSOML(ASender: TIdCommand);
    procedure CommandSEND(ASender: TIdCommand);
    procedure CommandSAML(ASender: TIdCommand);
    procedure CommandVRFY(ASender: TIdCommand);
    procedure CommandEXPN(ASender: TIdCommand);
    procedure CommandTURN(ASender: TIdCommand);
    procedure InitializeCommandHandlers; override;
  private
    FRawStreamType: TIdStreamType;
    // Internal
    function DoAuthLogin(ASender: TIdCommand; const Login: string):Boolean;
    procedure SetRawStreamType(const Value: TIdStreamType);
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure SetMessages(AValue: TIdSMTPMessages);
  published
    property AuthMode: Boolean read fAuthMode write fAuthMode;
    property Messages: TIdSMTPMessages read fMessages write SetMessages;
    property OnReceiveRaw: TOnReceiveRaw read FOnReceiveRaw write FOnReceiveRaw;
    property OnReceiveMessage: TOnReceiveMessage read FOnReceiveMessage write
      FOnReceiveMessage;
    property OnReceiveMessageParsed: TOnReceiveMessage read
      FOnReceiveMessageParsed write FOnReceiveMessageParsed;
    property ReceiveMode: TIdSMTPReceiveMode read FReceiveMode write
      FReceiveMode;
    property AllowEHLO: boolean read fAllowEHLO write fAllowEHLO;
    property NoDecode: Boolean read fNoDecode write fNoDecode;
    property NoEncode: Boolean read fNoEncode write fNoEncode;
    property OnCommandRCPT: THasAddress read fOnCommandRCPT write
      fOnCommandRCPT;
    property OnCommandMAIL: THasAddress2 read fOnCommandMAIL write
      fOnCommandMAIL;
    // Expose to user
    property OnCommandAUTH: TBasicHandler read fOnCommandAUTH write
      fOnCommandAUTH;
    property CheckUser: TUserHandler read fCheckUser write fCheckUser;
    property RawStreamType : TIdStreamType read FRawStreamType write SetRawStreamType;
    property OnCommandHELP: TBasicHandler read fOnCommandHelp write
      fOnCommandHelp;
    property OnCommandSOML: TBasicHandler read fOnCommandSoml write
      fOnCommandSoml;
    property OnCommandSEND: TBasicHandler read fOnCommandSend write
      fOnCommandSend;
    property OnCommandSAML: TBasicHandler read fOnCommandSaml write
      fOnCommandSaml;
    property OnCommandVRFY: TBasicHandler read fOnCommandVrfy write
      fOnCommandVrfy;
    property OnCommandEXPN: TBasicHandler read fOnCommandExpn write
      fOnCommandExpn;
    property OnCommandTURN: TBasicHandler read fOnCommandTurn write
      fOnCommandTurn;
  end;

  TIdSMTPState = (idSMTPNone,idSMTPHelo,idSMTPMail,idSMTPRcpt,idSMTPData);
  TIdSMTPServerThread = class(TIdPeerThread)
  protected
    procedure BeforeRun; override;
  public
    SMTPState: TIdSMTPState;
    From: string;
    RCPTList: TIdEMailAddressList;
    HELO: Boolean;
    EHLO: Boolean;
    Username: string;
    Password: string;
    LoggedIn: Boolean;
    //
    constructor Create(ACreateSuspended: Boolean = True); override;
    destructor Destroy; override;
  end;

implementation

uses
  IdGlobal,
  IdResourceStrings,
  SysUtils;

constructor TIdSMTPServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := 25;
  fAuthMode := false;
  fMessages := TIdSMTPMessages.Create;
  Greeting.Text.Text := RSSMTPSvrWelcome;
  Greeting.NumericCode := 220;
  ReplyUnknownCommand.NumericCode := 500;
  ReplyUnknownCommand.Text.Text := 'Syntax Error';    {Do not Localize}
  ThreadClass := TIdSMTPServerThread;
  if not (csDesigning in ComponentState) then
  begin
  end;
end;

destructor TIdSMTPServer.Destroy;
begin
  FreeandNil(fMessages);
  inherited;
end;

procedure TIdSMTPServer.SetMessages(AValue: TIdSMTPMessages);
begin
  FMessages.Assign(AValue);
end;

constructor TIdSMTPServerThread.Create(ACreateSuspended: Boolean = True);
begin
  inherited;
  SMTPState := idSMTPNone;
end;

procedure TIdSMTPServerThread.BeforeRun;
begin
  SMTPState := idSMTPNone;
  From:='';
  HELO:=false;
  EHLO:=false;
  Username:='';
  Password:='';
  LoggedIn:=false;
  FreeAndNil(RCPTList);
  RCPTList := TIdEMailAddressList.Create(nil);
  inherited BeforeRun;
end;

destructor TIdSMTPServerThread.Destroy;
begin
  FreeAndNil(RCPTList);
  inherited;
end;

procedure TIdSMTPServer.InitializeCommandHandlers;
begin
  inherited;
  with CommandHandlers.Add do
  begin
    // AUTH
    Command := 'AUTH';    {Do not Localize}
    OnCommand := CommandAUTH;
  end;
  with CommandHandlers.Add do
  begin
    // HELP
    Command := 'HELP';    {Do not Localize}
    OnCommand := CommandHELP;
  end;
  with CommandHandlers.Add do
  begin
    // SOML
    Command := 'SOML';    {Do not Localize}
    OnCommand := CommandSOML;
  end;
  with CommandHandlers.Add do
  begin
    // SEND
    Command := 'SEND';    {Do not Localize}
    OnCommand := CommandSEND;
  end;
  with CommandHandlers.Add do
  begin
    // SAML
    Command := 'SAML';    {Do not Localize}
    OnCommand := CommandSAML;
  end;
  with CommandHandlers.Add do
  begin
    // VRFY
    Command := 'VRFY';    {Do not Localize}
    OnCommand := CommandVRFY;
  end;
  with CommandHandlers.Add do
  begin
    // EXPN
    Command := 'EXPN';    {Do not Localize}
    OnCommand := CommandEXPN;
  end;
  with CommandHandlers.Add do
  begin
    // TURN
    Command := 'TURN';    {Do not Localize}
    OnCommand := CommandTURN;
  end;
  with CommandHandlers.Add do
  begin
    // RSET <CRLF>
    Command := 'RSET';    {Do not Localize}
    ReplyNormal.NumericCode := 250;
    ReplyNormal.Text.Text := Messages.NoopReply;
    OnCommand := CommandRSET;
  end;
  with CommandHandlers.Add do
  begin
    // DATA <CRLF>
    Command := 'DATA';    {Do not Localize}
    //      Moved into the actual event, due to the response being sent AFTER the command completed!!!
    //      ReplyNormal.NumericCode := 354;
    //      ReplyNormal.Text.Text := Messages.DataReplies.StartDataReply;
    OnCommand := CommandData;
  end;
  with CommandHandlers.Add do
  begin
    // HELO <SP> <domain> <CRLF>
    Command := 'HELO';    {Do not Localize}
    CmdDelimiter := ' ';    {Do not Localize}
    OnCommand := CommandHELO;
  end;
  with CommandHandlers.Add do
  begin
    // EHLO <SP> <domain> <CRLF>
    Command := 'EHLO';    {Do not Localize}
    CmdDelimiter := ' ';    {Do not Localize}
    OnCommand := CommandEHLO;
  end;
  with CommandHandlers.Add do
  begin
    // MAIL <SP> FROM:<reverse-path> <CRLF>
    Command := 'MAIL';    {Do not Localize}
    CmdDelimiter := ' ';    {Do not Localize}
    OnCommand := CommandMail;
  end;
  with CommandHandlers.Add do
  begin
    // NOOP
    Command := 'NOOP';    {Do not Localize}
    ReplyNormal.NumericCode := 250;
    ReplyNormal.Text.Text := FMessages.NOOPReply;
  end;
  with CommandHandlers.Add do
  begin
    // QUIT
    Command := 'QUIT';    {Do not Localize}
    CmdDelimiter := ' ';    {Do not Localize}
    Disconnect := True;
    ReplyNormal.NumericCode := 221;
    ReplyNormal.Text.Text := FMessages.QuitReply;
  end;
  with CommandHandlers.Add do
  begin
    // RCPT <SP> TO:<forward-path> <CRLF>
    Command := 'RCPT';    {Do not Localize}
    CmdDelimiter := ' ';    {Do not Localize}
    OnCommand := CommandRcpt;
  end;
end;

procedure TIdSMTPServer.CommandData(ASender: TIdCommand);
var
  LMsg: TIdMessage;
  LMsgClient: TIdMessageClient;
  LStream: TMemoryStream;
  LFileStream : TFileStream;
  LFileName : String;
  x: integer;
  CustomError:string;
begin
  with TIdSMTPServerThread(ASender.Thread) do
  begin
    if SMTPState <> idSMTPRcpt then
    begin
      Connection.Writeln('503 '+ FMessages.SequenceError);    {Do not Localize}
      Exit;
    end;
  end;

  if TIdSMTPServerThread(ASender.Thread).HELO then
  begin
    ASender.Thread.Connection.Writeln('354 ' +    {Do not Localize}
      Messages.DataReplies.StartDataReply);
    case ReceiveMode of
      rmRaw:
        begin
          if not Assigned(OnReceiveRaw) then
            raise EIdTCPServerError.Create('No OnReceiveRaw defined.');    {Do not Localize}
          if FRawStreamType = stMemoryStream then begin
            LStream := TMemoryStream.Create;
            try
              ASender.Thread.Connection.Capture(LStream, '.', True);    {Do not Localize}
              OnReceiveRaw(ASender, TStream(LStream), TIdSMTPServerThread(ASender.Thread).RCPTList,CustomError);
            finally
              FreeAndNil(LStream);
            end;
            end
          else
          if FRawStreamType = stFileStream then begin
            LFileName := MakeTempFilename;
            LFileStream := TFileStream.Create(LFileName,fmCreate);
            try
              ASender.Thread.Connection.Capture(LFileStream, '.', True);    {Do not Localize}
              OnReceiveRaw(ASender, TStream(LFileStream), TIdSMTPServerThread(ASender.Thread).RCPTList,CustomError);
            finally
              FreeAndNil(LFileStream);
              if FileExists(LFileName) then
                DeleteFile(LFileName);
            end;
            end;
        end;
      rmMessage:
        begin
          if not Assigned(OnReceiveMessage) then
            raise EIdTCPServerError.Create('No OnReceiveMessage defined.');    {Do not Localize}
          LMsg := TIdMessage.Create(Nil);
          try
            ASender.Thread.Connection.Capture(LMsg.Headers, '');    {Do not Localize}
              // Was ' ' but this doesnt work right ;)    {Do not Localize}
            ASender.Thread.Connection.Capture(LMsg.Body, '.', True);    {Do not Localize}
            LMsg.Headers.Values['X-Server'] := FMessages.XServer;    {Do not Localize}
            OnReceiveMessage(ASender, LMsg, TIdSMTPServerThread(ASender.Thread).RCPTList, CustomError);
          finally
            FreeAndNil(LMsg);
          end;
        end;
      rmMessageParsed:
        begin
          if not Assigned(OnReceiveMessageParsed) then
            raise
              EIdTCPServerError.Create('No OnReceiveMessageParsed defined.');    {Do not Localize}
          try
            LMsg := TIdMessage.Create(Nil);
            LMsg.NoDecode := fNoDecode;
            LMsg.NoEncode := fNoEncode;
            LMsgClient := TIdMessageClient.Create(Nil);
            LMsgClient.IOHandler := ASender.Thread.Connection.IOHandler;

            LMsgClient.ProcessMessage(LMsg);
            LMsg.Headers.Values['X-Server'] := FMessages.XServer;    {Do not Localize}
            // Match RCTPList to the TO Field in msg. Difference is the BCCList.
            // Check the TO
            if TIdSMTPServerThread(ASender.Thread).RCPTList.Count > 0 then
              for x := 1 to TIdSMTPServerThread(ASender.Thread).RCPTList.Count do
              begin
                if IndyPos(TIdSMTPServerThread(ASender.Thread).RCPTList.Items[x
                  - 1].Address, LMsg.Recipients.EMailAddresses) = 0 then
                begin
                  if IndyPos(TIdSMTPServerThread(ASender.Thread).RCPTList.Items[x - 1].Address, LMsg.CCList.EMailAddresses) = 0 then
                    Lmsg.BCCList.Add.Text := TIdSMTPServerThread(ASender.Thread).RCPTList.Items[x - 1].Text;
                end;
              end;
            OnReceiveMessageParsed(ASender, LMsg, TIdSMTPServerThread(ASender.Thread).RCPTList, CustomError);
          finally
            LMsgClient.IOHandler := nil;
            FreeAndNil(LMsgClient);
            FreeAndNil(LMsg);
          end;
        end;
    end;
    CustomError := Trim(CustomError);
    if CustomError = '' then    {Do not Localize}
    begin
      ASender.Thread.Connection.WriteLn('250 ' + Messages.DataReplies.EndDataReply);    {Do not Localize}
      TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPData;
    end
    else
      ASender.Thread.Connection.Writeln(CustomError);
  end
  else // No EHLO / HELO was received
    ASender.Thread.Connection.Writeln('501 ' + FMessages.Greeting.NoHello);    {Do not Localize}
end;

procedure TIdSMTPServer.CommandAUTH(ASender: TIdCommand);
var
  Login: string;
begin
  if TIdSMTPServerThread(ASender.Thread).EHLO then
    if not Assigned(fOnCommandAUTH) then
    begin
      if Length(ASender.UnparsedParams) > 0 then
      begin
        Login := ASender.UnparsedParams;
        DoAuthLogin(ASender, Login)
      end
      else
        ASender.Thread.Connection.WriteLn('500 ' + FMessages.ErrorReply);    {Do not Localize}
    end // If Assigned (fOnCommandAUTH) Then
    else
    begin
      OnCommandAuth(Asender);
    end
  else // EHLO needs to be enabled for the AUTH command to work
    ASender.Thread.Connection.Writeln('500 ' + FMessages.ErrorReply);    {Do not Localize}
end;

function TIdSMTPServer.DoAuthLogin(ASender: TIdCommand; const Login:string): Boolean;
var
  S: string;
  Username, Password: string;
  AuthFailed: Boolean;
  Accepted: Boolean;
begin
  Result := False;
  AuthFailed := False;

  if UpperCase(Login) = 'LOGIN' then    {Do not Localize}
  begin // LOGIN USING THE LOGIN AUTH - BASE64 ENCODED
    s := 'Username:';    {Do not Localize}
    s := TIdEncoderMIME.EncodeString(s);
    //  s := SendRequest( '334 ' + s );    {Do not Localize}
    ASender.Thread.connection.Writeln('334 ' + s);    {Do not Localize}
    s := Trim(ASender.Thread.Connection.ReadLn);
    if s <> '' then    {Do not Localize}
    begin
      try
        s := TIdDecoderMIME.DecodeString(s);

        Username := s;
        // What? Endcode this string literal?
        s := 'Password:';    {Do not Localize}
        s := TIdEncoderMIME.EncodeString(s);
        //    s := SendRequest( '334 ' + s );    {Do not Localize}
        ASender.Thread.connection.Writeln('334 ' + s);    {Do not Localize}
        s := Trim(ASender.Thread.Connection.ReadLn);
        if Length(s) = 0 then
          AuthFailed := True
        else
        begin
          Password := TIdDecoderMIME.DecodeString(s);
        end;
      // when TIdDecoderMime.DecodeString(s) raise a exception,catch it and set AuthFailed as true
      except
        AuthFailed := true;
      end;
    end
    else
      AuthFailed := True;
  end;

  // Add other login units here

  if AuthFailed then
  begin
    Result := False;
    ASender.Thread.Connection.Writeln('535 ' + fMessages.FGreeting.fAuthFailed);    {Do not Localize}
  end
  else
  begin
    Accepted := False;
    if Assigned(fCheckUser) then
      CheckUser(ASender, Accepted, Username, Password)
    else
      Accepted := True;
    TIdSMTPServerThread(ASender.Thread).LoggedIn := Accepted;
    TIdSMTPServerThread(ASender.Thread).Username := Username;
    if not Accepted then
      ASender.Thread.Connection.Writeln('535 ' + fMessages.FGreeting.fAuthFailed)    {Do not Localize}
    else
      ASender.Thread.Connection.Writeln('235 welcome ' + Trim(Username));    {Do not Localize}
  end;
end;

procedure TIdSMTPServer.CommandMail(ASender: TIdCommand);
var
  Accept: Boolean;
  EMailAddress: TIdEMailAddressItem;
begin
  if TIdSMTPServerThread(ASender.Thread).HELO then
  begin
    if AuthMode AND (not TIdSMTPServerThread(ASender.Thread).LoggedIn) then
    begin
      ASender.Thread.Connection.Writeln('553 ' + FMessages.NotLoggedIn);    {Do not Localize}
      Exit;
    end;

    //reset all information
    TIdSMTPServerThread(ASender.Thread).From := '';    {Do not Localize}
    TIdSMTPServerThread(ASender.Thread).RCPTList.Clear;
    TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPHelo;

    if Uppercase(Copy(ASender.UnparsedParams, 1, 5)) = 'FROM:' then    {Do not Localize}
    begin
      EMailAddress := TIdEMailAddressItem.Create(nil);
      try
       EMailAddress.Text := Trim(Copy(ASender.UnparsedParams, 6, Length(ASender.UnparsedParams)));
       if Assigned(fOnCommandMail) then
       begin
        OnCommandMAIL(ASender, Accept, EMailAddress.Address);
        if Accept then
        begin
          ASender.Thread.Connection.Writeln('250 ' + Format(FMessages.RcpReplies.AddressOKReply, [EMailAddress.Text]));    {Do not Localize}
          TIdSMTPServerThread(ASender.Thread).From := EMailAddress.Text;
          TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPMail;
        end
        else
          ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.RcpReplies.AddressErrorReply, [EMailAddress.Text]));    {Do not Localize}
       end
       else
       begin
        TIdSMTPServerThread(ASender.Thread).From := EMailAddress.Text;
        ASender.Thread.Connection.Writeln('250 ' + Format(FMessages.RcpReplies.AddressOKReply, [EMailAddress.Text]));    {Do not Localize}
        TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPMail;
       end;
      Finally
       EMailAddress.Free;
      End;
    end
    else
      // JP: Also needs to be moved into a resource string
      ASender.Thread.Connection.Writeln('501 Parameter error! Example: mail from:<user@domain.com>');    {Do not Localize}
  end
  else // No EHLO / HELO was received
    ASender.Thread.Connection.Writeln('501 ' + FMessages.Greeting.NoHello);    {Do not Localize}
end;

procedure TIdSMTPServer.CommandRcpt(ASender: TIdCommand);
var
  Accept, ToForward: Boolean;
  EMailAddress: TIdEMailAddressItem;
  CustomError: string;
begin
  with TIdSMTPServerThread(ASender.Thread) do
  begin
    if (SMTPState <> idSMTPMail) AND (SMTPState <> idSMTPRcpt) then
    begin
      Connection.Writeln('503 '+ FMessages.SequenceError);    {Do not Localize}
      Exit;
    end;
  end;

  if TIdSMTPServerThread(ASender.Thread).HELO then
  begin
    if (Uppercase(Copy(ASender.UnparsedParams, 1, 3)) = 'TO:') then    {Do not Localize}
    begin
      EMailAddress := TIdEMailAddressItem.Create(nil);
      EMailAddress.Text := Trim(Copy(ASender.UnparsedParams, 4,
        Length(ASender.UnparsedParams)));
      if Assigned(fONCommandRCPT) then
      begin
        Accept := False;
        ToForward := False;
        OnCommandRCPT(ASender, Accept, ToForward, EMailAddress.Address,
          CustomError);
        if Trim(CustomError) = '' then    {Do not Localize}
        begin
          if Accept and ToForward then
            ASender.Thread.Connection.Writeln('251 ' +    {Do not Localize}
              Format(FMessages.RcpReplies.AddressWillForwardReply,
              [EMailAddress.Text]));
          if Accept and not ToForward then
            ASender.Thread.Connection.Writeln('250 ' +    {Do not Localize}
              Format(FMessages.RcpReplies.AddressOkReply, [EMailAddress.Text]));
          if not Accept and (Trim(CustomError) = '') then    {Do not Localize}
            ASender.Thread.Connection.Writeln('500 ' +    {Do not Localize}
              Format(FMessages.RcpReplies.AddressErrorReply,
              [EMailAddress.Text]));
          if Accept then
          begin
            TIdSMTPServerThread(ASender.Thread).RcptList.Add.Text :=
              EMailAddress.Text;
            TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPRcpt;
          end
        end
        else
          ASender.Thread.Connection.Writeln(Format(CustomError,
            [EMailAddress.Text]));
      end
      else
      begin
        TIdSMTPServerThread(ASender.Thread).RcptList.Add.Text := EMailAddress.Text;
        ASender.Thread.Connection.Writeln('250 ' + Format(FMessages.RcpReplies.AddressOkReply, [EMailAddress.Text]));    {Do not Localize}
        TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPRcpt;
      end;
      EMailAddress.Free;
    end
    else
      // JP: Also needs to be moved into a resource string
      ASender.Thread.Connection.Writeln('501 Command parameter error! Example: rcpt to:<a@b.c>');    {Do not Localize}
  end
  else // No EHLO / HELO was received
    ASender.Thread.Connection.Writeln('501 ' + FMessages.Greeting.NoHello);    {Do not Localize}
end;

procedure TIdSMTPServer.CommandRSET(ASender: TIdCommand);
begin
  TIdSMTPServerThread(ASender.Thread).RCPTList.Clear;
  TIdSMTPServerThread(ASender.Thread).From := '';    {Do not Localize}
  with TIdSMTPServerThread(ASender.Thread) do
  begin
    if Ehlo or Helo then
      SMTPState := idSMTPHelo
    else
      SMTPState := idSMTPNone;
  end;
end;

procedure TIdSMTPServer.CommandHELO(ASender: TIdCommand);
begin
  with TIdSMTPServerThread(ASender.Thread) do
  begin
    if SMTPState <> idSMTPNone then
    begin
      Connection.Writeln('503 '+ FMessages.SequenceError);    {Do not Localize}
      Exit;
    end;
  end;

  if Length(ASender.UnparsedParams) > 0 then
  begin
    ASender.Thread.Connection.Writeln('250 ' + Format(FMessages.Greeting.HelloReply, [ASender.UnparsedParams]));    {Do not Localize}
    TIdSMTPServerThread(ASender.Thread).HELO := True;
    TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPHelo;
  end
  else
    // JP: Also needs to be moved into a resource string
    ASender.Thread.Connection.Writeln('501 Syntax Error in parameters or arguments');    {Do not Localize}
end;

procedure TIdSMTPServer.CommandEHLO(ASender: TIdCommand);
begin
  if fAllowEHLO then
  begin
    with TIdSMTPServerThread(ASender.Thread) do
    begin
      if SMTPState <> idSMTPNone then
      begin
        Connection.Writeln('503 ' + FMessages.SequenceError);    {Do not Localize}
        Exit;
      end;
    end;

    if Length(ASender.UnparsedParams) > 0 then
    begin
      ASender.Thread.Connection.WriteStrings(FMessages.Greeting.EHLOReply);
      TIdSMTPServerThread(ASender.Thread).HELO := True;
      TIdSMTPServerThread(ASender.Thread).EHLO := True;
      TIdSMTPServerThread(ASender.Thread).SMTPState := idSMTPHelo;
    end
    else
      // JP: Also needs to be moved into a resource string
      ASender.Thread.Connection.Writeln('501 Syntax Error in parameters or arguments');    {Do not Localize}
  end
  else
    ASender.Thread.Connection.Writeln('500 ' +    {Do not Localize}
      FMessages.Greeting.EHLONotSupported);
end;

// Simple events, simply exposed with minimal processing

procedure TIdSMTPServer.CommandHELP(ASender: TIdCommand);
begin
  if Assigned(fOnCommandHELP) then
    OnCommandHelp(ASender)
  else
    ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.FErrorReply, [ASender.UnparsedParams]));    {Do not Localize}
end;

procedure TIdSMTPServer.CommandSOML(ASender: TIdCommand);
begin
  if Assigned(fOnCommandSOML) then
    OnCommandSOML(ASender)
  else
    ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.FErrorReply, [ASender.UnparsedParams]));    {Do not Localize}
end;

procedure TIdSMTPServer.CommandSEND(ASender: TIdCommand);
begin
  if Assigned(fOnCommandSEND) then
    OnCommandSEND(ASender)
  else
    ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.FErrorReply, [ASender.UnparsedParams]));    {Do not Localize}
end;

procedure TIdSMTPServer.CommandSAML(ASender: TIdCommand);
begin
  if Assigned(fOnCommandSAML) then
    OnCommandSAML(ASender)
  else
    ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.FErrorReply, [ASender.UnparsedParams]));    {Do not Localize}
end;

procedure TIdSMTPServer.CommandVRFY(ASender: TIdCommand);
begin
  if Assigned(fOnCommandVRFY) then
    OnCommandVRFY(ASender)
  else
    ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.FErrorReply, [ASender.UnparsedParams]));    {Do not Localize}
end;

procedure TIdSMTPServer.CommandEXPN(ASender: TIdCommand);
begin
  if Assigned(fOnCommandEXPN) then
    OnCommandEXPN(ASender)
  else
    ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.FErrorReply, [ASender.UnparsedParams]));    {Do not Localize}
end;

procedure TIdSMTPServer.CommandTURN(ASender: TIdCommand);
begin
  if Assigned(fOnCommandTURN) then
    OnCommandTURN(ASender)
  else
    ASender.Thread.Connection.Writeln('500 ' + Format(FMessages.FErrorReply, [ASender.UnparsedParams]));    {Do not Localize}
end;

{ TIdSMTPMessages }

procedure TIdSMTPMessages.Assign(Source: TPersistent);
var
  o: TIdSMTPMessages;
begin
  inherited;
  if ClassType <> Source.ClassType then
    inherited
  else
  begin
    o := TIdSMTPMessages(Source);
    FNoopReply := o.NoopReply;
    FRSetReply := o.RSetReply;
    FQuitReply := o.QuitReply;
    FErrorReply := o.ErrorReply;
    ReceivedHeader := o.ReceivedHeader;
    FXserver := o.XServer;
    FSyntaxErrorReply := o.SyntaxErrorReply;
    FGreeting.Assign(o.Greeting);
    FRcpReplies.Assign(o.RcpReplies);
    FDataReplies.Assign(o.DataReplies);
  end;
end;

constructor TIdSMTPMessages.Create;
begin
  inherited Create;
  FNoopReply := RSSMTPSvrOk;
  FRSetReply := RSSMTPSvrOk;
  FQuitReply := RSSMTPSvrQuit;
  FErrorReply := RSSMTPSvrCmdGeneralError;
//  FSequenceError := RSSMTPSvrBadSequence;  // TO DO:   need to put back in place later
  FSyntaxErrorReply := RSSMTPSvrCmdGeneralError;
//  FNotLoggedIn := RSSMTPNotLoggedIn;       // TO DO:  need to put back in place later
  FGreeting := TIdSMTPGreeting.Create;

  FRcpReplies := TIdSMTPRcpReplies.Create;
  FDataReplies := TIdSMTPDataReplies.Create;
  ReceivedHeader := RSSMTPSvrReceivedHeader;
  fXServer := RSSMTPSvrXServer;

end;

destructor TIdSMTPMessages.Destroy;
begin
  FreeAndNil(FGreeting);
  FreeAndNil(FRcpReplies);
  FreeAndNil(FDataReplies);
  inherited;
end;

procedure TIdSMTPMessages.SetDataReplies(const AValue: TIdSMTPDataReplies);
begin
  FDataReplies.Assign(AValue);
end;

procedure TIdSMTPMessages.SetGreeting(const AValue: TIdSMTPGreeting);
begin
  FGreeting.Assign(AValue);
end;

procedure TIdSMTPMessages.SetRcpReplies(const AValue: TIdSMTPRcpReplies);
begin
  FRcpReplies.Assign(AValue);
end;

{ TIdSMTPDataReplies }

procedure TIdSMTPDataReplies.Assign(Source: TPersistent);
var
  o: TIdSMTPDataReplies;
begin
  inherited;
  if ClassType <> Source.ClassType then
    inherited
  else
  begin
    o := TIdSMTPDataReplies(Source);
    fStartDataReply := o.StartDataReply;
    fEndDataReply := o.EndDataReply;
  end;
end;

constructor TIdSMTPDataReplies.Create;
begin
  inherited Create;
  fStartDataReply := RSSMTPSvrStartData;
  fEndDataReply := RSSMTPSvrOk;
end;

{ TIdSMTPRcpReplies }

procedure TIdSMTPRcpReplies.Assign(Source: TPersistent);
var
  o: TIdSMTPRcpReplies;
begin
  if ClassType <> Source.ClassType then
  begin
    inherited;
  end
  else
  begin
    o := TIdSMTPRcpReplies(Source);
    fAddressOkReply := o.AddressOkReply;
    FAddressErrorReply := o.AddressErrorReply;
    FAddressWillForwardReply := o.AddressWillForwardReply;
  end;
end;

constructor TIdSMTPRcpReplies.Create;
begin
  inherited Create;
  fAddressOkReply := RSSMTPSvrAddressOk;
  FAddressErrorReply := RSSMTPSvrAddressError;
  FAddressWillForwardReply := RSSMTPSvrAddressWillForward;
end;

{ TIdSMTPGreeting }

procedure TIdSMTPGreeting.Assign(Source: TPersistent);
var
  o: TIdSMTPGreeting;
begin
  inherited;
  if ClassType <> Source.ClassType then
  begin
    inherited;
  end
  else
  begin
    o := TIdSMTPGreeting(Source);
    FEHLONotSupported := o.EHLONotSupported;
    FHelloReply := o.HelloReply;
    fNoHello := o.NoHello;
    fEHLOReply.Assign(o.EHLOReply);
  end;
end;

constructor TIdSMTPGreeting.Create;
begin
  inherited Create;
  fEHLOReply := TStringList.Create;
  fEHLOReply.Add('250-'+GStack.WSGetHostName);    {Do not Localize}
  fEHLOReply.Add('250-AUTH LOGIN');    {Do not Localize}
  fEHLOReply.Add('250 HELP');    {Do not Localize}
  fHelloReply := RSSMTPSvrHello;
  fNoHello := RSSMTPSvrNoHello;
  fEHLONotSupported := RSSMTPSvrCmdNotRecognized;
  fAuthFailed := RSSMTPSvrAuthFailed;
end;

destructor TIdSMTPGreeting.Destroy;
begin
  FreeAndNil(FEHLOReply);
  inherited;
end;

procedure TIdSMTPGreeting.SetEHLOReply(const AValue: TStrings);
begin
  FEHLOReply := AValue;
end;

procedure TIdSMTPServer.SetRawStreamType(const Value: TIdStreamType);
begin
  FRawStreamType := Value;
end;

end.

