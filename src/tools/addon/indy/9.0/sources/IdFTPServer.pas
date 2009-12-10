{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10167: IdFTPServer.pas 
{
    Rev 1.3    1/23/2003 9:09:18 PM  BGooijen
  Changed ABOR to fix the command while uploading
}
{
{   Rev 1.2    1-9-2003 11:44:42  BGooijen
{ Added ABOR command with telnet escape characters
{ Fixed hanging of ABOR command
{ STOR and STOU now use REST-position
}
{
{   Rev 1.1    12/10/2002 07:43:04 AM  JPMugaas
{ Merged fix for a problem were resume cause the entire file to be sent instead
{ of the part requrested.
}
{
{   Rev 1.0    2002.11.12 10:39:06 PM  czhower
}
unit IdFTPServer;
{
 Original Author: Sergio Perry
 Date: 04/21/2001

 Fixes and modifications: Doychin Bondzhev
 Date: 08/10/2001

 Further Extensive changes by Chad Z. Hower (Kudzu)

TODO:
 - Change events to use DoXXXX
}

interface

uses
  Classes,
  SysUtils,  IdAssignedNumbers,
  IdException, IdFTPList, IdTCPServer, IdTCPConnection, IdUserAccounts,
  IdFTPCommon, IdThread, IdRFCReply;

type
  TIdFTPUserType = (utNone, utAnonymousUser, utNormalUser);
  TIdFTPSystems = (ftpsOther, ftpsDOS, ftpsUNIX, ftpsVAX);
  TIdFTPOperation = (ftpRetr, ftpStor);

const
  Id_DEF_AllowAnon  = False;
  Id_DEF_PassStrictCheck = True;
  Id_DEF_SystemType = ftpsDOS;

type
  TIdFTPServerThread = class;

  TOnUserLoginEvent = procedure(ASender: TIdFTPServerThread; const AUsername, APassword: string;
    var AAuthenticated: Boolean) of object;
  TOnAfterUserLoginEvent = procedure(ASender: TIdFTPServerThread) of object;
  TOnDirectoryEvent = procedure(ASender: TIdFTPServerThread; var VDirectory: string) of object;
  TOnGetFileSizeEvent = procedure(ASender: TIdFTPServerThread; const AFilename: string;
    var VFileSize: Int64) of object;
  TOnListDirectoryEvent = procedure(ASender: TIdFTPServerThread; const APath: string;
    ADirectoryListing: TIdFTPListItems) of object;
  TOnFileEvent = procedure(ASender: TIdFTPServerThread; const APathName: string) of object;
  TOnRenameFileEvent = procedure(ASender: TIdFTPServerThread; const ARenameFromFile,ARenameToFile: string) of object;
  TOnRetrieveFileEvent = procedure(ASender: TIdFTPServerThread; const AFileName: string;
    var VStream: TStream) of object;
  TOnStoreFileEvent = procedure(ASender: TIdFTPServerThread; const AFileName: string;
    AAppend: Boolean; var VStream: TStream) of object;
  EIdFTPServerException = class(EIdException);
  EIdFTPServerNoOnListDirectory = class(EIdFTPServerException);
  TIdDataChannelThread = class(TIdThread)
  protected
    FControlChannel: TIdTCPServerConnection;
    FDataChannel: TIdTCPConnection;
    FErrorReply: TIdRFCReply;
    FFtpOperation: TIdFTPOperation;
    FOKReply: TIdRFCReply;
    //
    procedure Run; override;
    procedure SetErrorReply(const AValue: TIdRFCReply);
    procedure SetOKReply(const AValue: TIdRFCReply);
  public
    constructor Create(APASV: Boolean; AControlConnection: TIdTCPServerConnection; const ADefaultDataPort : Integer = IdPORT_FTP_DATA); reintroduce;
    destructor Destroy; override;
    procedure StartThread(AOperation: TIdFTPOperation);
    procedure SetupDataChannel(const AIP: string; APort: Integer);
    //
    property OKReply: TIdRFCReply read FOKReply write SetOKReply;
    property ErrorReply: TIdRFCReply read FErrorReply write SetErrorReply;
  end;

  TIdFTPServerThread = class(TIdPeerThread)
  protected
    FUserType: TIdFTPUserType;
    FAuthenticated: Boolean;
    FALLOSize: Integer;
    FCurrentDir: string;
    FDataType: TIdFTPTransferType;
    FDataMode: TIdFTPTransferMode;
    FDefaultDataPort : Integer;
    FDataPort: Integer;
    FDataStruct: TIdFTPDataStructure;
    FDataChannelThread: TIdDataChannelThread;
    FHomeDir: string;
    FUsername: string;
    FPassword: string;
    FPASV: Boolean;
    FRESTPos: Integer;
    FRNFR: string;
    //
    procedure CreateDataChannel(APASV: Boolean = False);
    function  IsAuthenticated(ASender: TIdCommand): Boolean;
    procedure KillDataChannel;
    procedure TerminateAndFreeDataChannel;
    procedure ReInitialize;
  public
    constructor Create(ACreateSuspended: Boolean = True; const ADefaultDataPort : Integer = IdPORT_FTP_DATA); reintroduce;
    destructor Destroy; override;
    //
    property Authenticated: Boolean read FAuthenticated write FAuthenticated;
    property ALLOSize: Integer read FALLOSize write FALLOSize;
    property CurrentDir: string read FCurrentDir write FCurrentDir;
    property DataChannelThread: TIdDataChannelThread read FDataChannelThread
     write FDataChannelThread;
    property DataType: TIdFTPTransferType read FDataType write FDataType;
    property DataMode: TIdFTPTransferMode read FDataMode write FDataMode;
    property DataPort: Integer read FDataPort write FDataPort;
    property DataStruct: TIdFTPDataStructure read FDataStruct write FDataStruct;
    property HomeDir: string read FHomeDir write FHomeDir;
    property Password: string read FPassword write FPassword;
    property PASV: Boolean read FPASV write FPASV;
    property RESTPos: Integer read FRESTPos write FRESTPos;
    property Username: string read FUsername write FUsername;
    property UserType: TIdFTPUserType read FUserType write FUserType;
  end;

  TIdFTPServer = class;

  TIdOnGetCustomListFormat = procedure(ASender: TIdFTPServer; AItem: TIdFTPListItem;
   var VText: string) of object;

  { FTP Server }
  TIdFTPServer = class(TIdTCPServer)
  protected
    FAnonymousAccounts: TstringList;
    FAllowAnonymousLogin: Boolean;
    FAnonymousPassStrictCheck: Boolean;
    FCmdHandlerList: TIdCommandHandler;
    FCmdHandlerNlst: TIdCommandHandler;
    FEmulateSystem: TIdFTPSystems;
    FHelpReply: Tstrings;
    FSystemType: string;
    FDefaultDataPort : Integer;
    FUserAccounts: TIdUserManager;
    FOnAfterUserLogin: TOnAfterUserLoginEvent;
    FOnGetCustomListFormat: TIdOnGetCustomListFormat;
    FOnUserLogin: TOnUserLoginEvent;
    FOnChangeDirectory: TOnDirectoryEvent;
    FOnGetFileSize: TOnGetFileSizeEvent;
    FOnListDirectory: TOnListDirectoryEvent;
    FOnRenameFile: TOnRenameFileEvent;
    FOnDeleteFile: TOnFileEvent;
    FOnRetrieveFile: TOnRetrieveFileEvent;
    FOnStoreFile: TOnStoreFileEvent;
    FOnMakeDirectory: TOnDirectoryEvent;
    FOnRemoveDirectory: TOnDirectoryEvent;
    //Command replies
    procedure CommandUSER(ASender: TIdCommand);
    procedure CommandPASS(ASender: TIdCommand);
    procedure CommandCWD(ASender: TIdCommand);
    procedure CommandCDUP(ASender: TIdCommand);
    procedure CommandREIN(ASender: TIdCommand);
    procedure CommandPORT(ASender: TIdCommand);
    procedure CommandPASV(ASender: TIdCommand);
    procedure CommandTYPE(ASender: TIdCommand);
    procedure CommandSTRU(ASender: TIdCommand);
    procedure CommandMODE(ASender: TIdCommand);
    procedure CommandRETR(ASender: TIdCommand);
    procedure CommandSSAP(ASender: TIdCommand);
    procedure CommandALLO(ASender: TIdCommand);
    procedure CommandREST(ASender: TIdCommand);
    procedure CommandRNFR(ASender: TIdCommand);
    procedure CommandRNTO(ASender: TIdCommand);
    procedure CommandABOR(ASender: TIdCommand);
    procedure CommandDELE(ASender: TIdCommand);
    procedure CommandRMD(ASender: TIdCommand);
    procedure CommandMKD(ASender: TIdCommand);
    procedure CommandPWD(ASender: TIdCommand);
    procedure CommandLIST(ASender: TIdCommand);
    procedure CommandSITE(ASender: TIdCommand);
    procedure CommandSYST(ASender: TIdCommand);
    procedure CommandSTAT(ASender: TIdCommand);
    procedure CommandSIZE(ASender: TIdCommand);
    procedure CommandFEAT(ASender: TIdCommand);
    procedure CommandOPTS(ASender: TIdCommand);
    //
    procedure DoChangeDirectory(AThread: TIdFTPServerThread; var VDirectory: string);
    procedure DoMakeDirectory(AThread: TIdFTPServerThread; var VDirectory: string);
    procedure DoRemoveDirectory(AThread: TIdFTPServerThread; var VDirectory: string);
    procedure DoGetCustomListFormat(AItem: TIdFTPListItem; var VText: string);
    procedure InitializeCommandHandlers; override;
    procedure ListDirectory(ASender: TIdFTPServerThread; ADirectory: string;
     var ADirContents: TstringList; ADetails: Boolean);
    procedure Notification(AComponent: TComponent; Operation: TOperation); override;
    procedure SetAnonymousAccounts(const AValue: TstringList);
    procedure SetHelpReply(const AValue: Tstrings);
    procedure SetUserAccounts(const AValue: TIdUserManager);
    procedure SetEmulateSystem(const AValue: TIdFTPSystems);
    procedure ThreadException(AThread: TIdThread; AException: Exception);
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
  published
    property AllowAnonymousLogin: Boolean read FAllowAnonymousLogin write FAllowAnonymousLogin default Id_DEF_AllowAnon;
    property AnonymousAccounts: TStringList read FAnonymousAccounts write SetAnonymousAccounts;
    property AnonymousPassStrictCheck: Boolean read FAnonymousPassStrictCheck
     write FAnonymousPassStrictCheck default Id_DEF_PassStrictCheck;
    property DefaultDataPort : Integer read FDefaultDataPort write FDefaultDataPort default IdPORT_FTP_DATA;
    property EmulateSystem: TIdFTPSystems read FEmulateSystem write SetEmulateSystem default Id_DEF_SystemType;
    property HelpReply: Tstrings read FHelpReply write SetHelpReply;
    property UserAccounts: TIdUserManager read FUserAccounts write SetUserAccounts;
    property SystemType: string read FSystemType write FSystemType;
    property OnAfterUserLogin: TOnAfterUserLoginEvent read FOnAfterUserLogin
     write FOnAfterUserLogin;
    property OnChangeDirectory: TOnDirectoryEvent read FOnChangeDirectory write FOnChangeDirectory;
    property OnGetCustomListFormat: TIdOnGetCustomListFormat read FOnGetCustomListFormat
     write FOnGetCustomListFormat;
    property OnGetFileSize: TOnGetFileSizeEvent read FOnGetFileSize write FOnGetFileSize;
    property OnUserLogin: TOnUserLoginEvent read FOnUserLogin write FOnUserLogin;
    property OnListDirectory: TOnListDirectoryEvent read FOnListDirectory write FOnListDirectory;
    property OnRenameFile: TOnRenameFileEvent read FOnRenameFile write FOnRenameFile;
    property OnDeleteFile: TOnFileEvent read FOnDeleteFile write FOnDeleteFile;
    property OnRetrieveFile: TOnRetrieveFileEvent read FOnRetrieveFile write FOnRetrieveFile;
    property OnStoreFile: TOnStoreFileEvent read FOnStoreFile write FOnStoreFile;
    property OnMakeDirectory: TOnDirectoryEvent read FOnMakeDirectory write FOnMakeDirectory;
    property OnRemoveDirectory: TOnDirectoryEvent read FOnRemoveDirectory write FOnRemoveDirectory;
  end;

implementation

uses
  IdGlobal,
  IdIOHandlerSocket,
  IdResourcestrings,
  IdSimpleServer,
  IdSocketHandle,
  Idstrings,
  IdTCPClient,
  IdEMailAddress;

{ TIdDataChannelThread }

constructor TIdDataChannelThread.Create(APASV: Boolean; AControlConnection: TIdTCPServerConnection; const ADefaultDataPort : Integer = IdPORT_FTP_DATA);
begin
  inherited Create;
  StopMode := smSuspend;
  FOKReply := TIdRFCReply.Create(nil);
  FErrorReply := TIdRFCReply.Create(nil);
  FControlChannel := AControlConnection;
  if APASV then begin
    FDataChannel := TIdSimpleServer.Create(nil);
    TIdSimpleServer(FDataChannel).BoundIP := TIdIOHandlerSocket(FControlChannel.IOHandler).Binding.IP;
  end else begin
    FDataChannel := TIdTCPClient.Create(nil);
    TIdTCPClient(FDataChannel).BoundPort := ADefaultDataPort;  //Default dataport
  end;
end;

destructor TIdDataChannelThread.Destroy;
begin
  FreeAndNil(FOKReply);
  FreeAndNil(FErrorReply);
  FreeAndNil(FDataChannel);
  inherited Destroy;
end;

procedure TIdDataChannelThread.StartThread(AOperation: TIdFTPOperation);
begin
  FFtpOperation := AOperation; try
    if FDataChannel is TIdSimpleServer then begin
      TIdSimpleServer(FDataChannel).Listen;
    end else if FDataChannel is TIdTCPClient then begin
      TIdTCPClient(FDataChannel).Connect;
    end;
  except
    FControlChannel.WriteRFCReply(FErrorReply); //426
    raise;
  end;
  inherited Start;
end;

procedure TIdDataChannelThread.Run;
var
  LStrStream: TMemoryStream; //is faster than StringStream
begin
  try
    try
      try
        try
          if Data is TStream then begin
            case FFtpOperation of
              ftpRetr: FDataChannel.WriteStream(TStream(Data),False);
              ftpStor: FDataChannel.ReadStream(TStream(Data), -1, True);
            end;
          end else begin
            case FFtpOperation of
              ftpRetr: FDataChannel.Writestrings(Data as Tstrings);
              ftpStor:
                begin
                  LStrStream := TMemoryStream.Create;
                  try
                    FDataChannel.ReadStream(LStrStream, -1, True);
                    SplitLines(LStrStream.Memory, LStrStream.Size,TStrings(Data));
                  finally
                    FreeAndNil(LStrStream);
                  end;
                end;//ftpStor
            end;//case
          end;
        finally
          FreeAndNIL(FData);
        end;
      finally
        FDataChannel.Disconnect;
      end;
      FControlChannel.WriteRFCReply(FOKReply); //226
    except
      FControlChannel.WriteRFCReply(FErrorReply); //426
    end;
  finally Stop; end;
end;

procedure TIdDataChannelThread.SetupDataChannel(const AIP: string; APort: Integer);
begin
  if FDataChannel is TIdSimpleServer then begin
    with TIdSimpleServer(FDataChannel) do begin
      BoundIP := AIP;
      BoundPort := APort;
    end;
  end else begin
    with TIdTCPClient(FDataChannel) do begin
      Host := AIP;
      Port := APort;
    end;
  end;
end;

procedure TIdDataChannelThread.SetErrorReply(const AValue: TIdRFCReply);
begin
  FErrorReply.Assign(AValue);
end;

procedure TIdDataChannelThread.SetOKReply(const AValue: TIdRFCReply);
begin
  FOKReply.Assign(AValue);
end;

{ TIdFTPClient }

constructor TIdFTPServerThread.Create(ACreateSuspended: Boolean = True; const ADefaultDataPort : Integer = IdPORT_FTP_DATA);
begin
  inherited Create(ACreateSuspended);
  FDefaultDataPort := ADefaultDataPort;
  ReInitialize;
end;

procedure TIdFTPServerThread.TerminateAndFreeDataChannel;
Begin
  if Assigned(FDataChannelThread) then begin
    FDataChannelThread.Terminate; //set Terminated flag
    FDataChannelThread.Start; //can be stopped
    FreeAndNIL(FDataChannelThread);
  end;
End;//

destructor TIdFTPServerThread.Destroy;
begin
  TerminateAndFreeDataChannel;
  inherited Destroy;
end;

procedure TIdFTPServerThread.CreateDataChannel(APASV: Boolean = False);
begin
{APR 020423. We must cache it, but in future:
  if assigned(FDataChannelThread) and not APASV then begin
    exit; // we already have one.
  end;}
  TerminateAndFreeDataChannel; //let the old one terminate

  FDataChannelThread := TIdDataChannelThread.Create(APASV, Connection, FDefaultDataPort);
  FDataChannelThread.OnException := TIdFTPServer(FConnection.Server).ThreadException;
  //APR 020423 FDataChannelThread.FreeOnTerminate := True;
end;

procedure TIdFTPServerThread.KillDataChannel;
begin
  with FDataChannelThread do try
    if not Stopped then begin
      FDataChannel.DisconnectSocket;
      StopMode:=smTerminate; // otherwise the waitfor on the next line waits forever.
      WaitFor;
    end;
  except
    { absorb }
  end;
end;

procedure TIdFTPServerThread.ReInitialize;
begin
  UserType := utNone;
  FAuthenticated := False;
  FALLOSize := 0;
  FCurrentDir := '/';    {Do not Localize}
  FDataType := ftASCII;
  FDataMode := dmStream;
  FDataPort := FDefaultDataPort;
  FDataStruct := dsFile;
  FHomeDir := '';    {Do not Localize}
  FUsername := '';    {Do not Localize}
  FPassword := '';    {Do not Localize}
  FPASV := False;
  FRESTPos := 0;
  FRNFR := '';    {Do not Localize}
end;

function TIdFTPServerThread.IsAuthenticated(ASender: TIdCommand): Boolean;
begin
  if not FAuthenticated then begin
    ASender.Reply.SetReply(530, RSFTPUserNotLoggedIn);
  end
  else begin
    if Assigned(FDataChannelThread) then begin
      if not FDataChannelThread.Stopped and
        not AnsiSameText(ASender.CommandHandler.Command, 'ABOR') and {Do not Localize}
        not AnsiSameText(ASender.CommandHandler.Command, #$FF#$F4#$FF#$FF'ABOR') // ABOR with telnet escape {Do not Localize}
      then begin
        Result := False;
        Exit;
      end;
    end;
  end;
  Result := FAuthenticated;
end;

{ TIdFTPServer }

constructor TIdFTPServer.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);

  FAnonymousAccounts :=  TstringList.Create;
  // By default these user names will be treated as anonymous.
  with FAnonymousAccounts do begin
    Add('anonymous'); { do not localize }
    Add('ftp'); { do not localize }
    Add('guest'); { do not localize }
  end;
  FAllowAnonymousLogin := Id_DEF_AllowAnon;
  FAnonymousPassStrictCheck := Id_DEF_PassStrictCheck;
  DefaultPort := IDPORT_FTP;
  DefaultDataPort := IdPORT_FTP_DATA;
  FEmulateSystem := Id_DEF_SystemType;
  Greeting.NumericCode := 220;
  Greeting.Text.Text := RSFTPDefaultGreeting;
  FHelpReply := TstringList.Create;
  ThreadClass := TIdFTPServerThread;
  ReplyUnknownCommand.NumericCode := 500;
  ReplyUnknownCommand.Text.Text := RSFTPCmdSyntaxError;
  FUserAccounts := nil;
  FSystemType := Id_OS_Win32;    {Do not Localize}
end;

procedure TIdFTPServer.InitializeCommandHandlers;
begin
  inherited;
  //ACCESS CONTROL COMMANDS
  //USER <SP> <username> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'USER';    {Do not Localize}
    OnCommand := CommandUSER;
  end;
  //PASS <SP> <password> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'PASS';    {Do not Localize}
    OnCommand := CommandPASS;
  end;
  //ACCT <SP> <account-information> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'ACCT';    {Do not Localize}
    ReplyNormal.NumericCode := 202;
    ReplyNormal.Text.Text := Format(RSFTPCmdNotImplemented, ['ACCT']);    {Do not Localize}
  end;
  //CWD  <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'CWD';    {Do not Localize}
    OnCommand := CommandCWD;
    ReplyExceptionCode := 550;
  end;
  //CDUP <CRLF>
  with CommandHandlers.Add do begin
    Command := 'CDUP';    {Do not Localize}
    OnCommand := CommandCDUP;
    ReplyExceptionCode := 550;
  end;
  //SMNT <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'SMNT';    {Do not Localize}
    ReplyNormal.NumericCode := 250;
    ReplyNormal.Text.Text := RSFTPFileActionCompleted;
  end;
  //QUIT <CRLF>
  with CommandHandlers.Add do begin
    Command := 'QUIT';    {Do not Localize}
    Disconnect := True;
    ReplyNormal.NumericCode := 221;
    ReplyNormal.Text.Text := 'Goodbye.';    {Do not Localize}
  end;
  //REIN <CRLF>
  with CommandHandlers.Add do begin
    Command := 'REIN';    {Do not Localize}
    OnCommand := CommandREIN;
  end;
  //PORT <SP> <host-port> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'PORT';    {Do not Localize}
    OnCommand := CommandPORT;
  end;
  //PASV <CRLF>
  with CommandHandlers.Add do begin
    Command := 'PASV';    {Do not Localize}
    OnCommand := CommandPASV;
  end;
  //TYPE <SP> <type-code> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'TYPE';    {Do not Localize}
    OnCommand := CommandTYPE;
  end;
  //STRU <SP> <structure-code> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'STRU';    {Do not Localize}
    OnCommand := CommandSTRU;
  end;
  //MODE <SP> <mode-code> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'MODE';    {Do not Localize}
    OnCommand := CommandMODE;
  end;
  //FTP SERVICE COMMANDS
  //RETR <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'RETR';    {Do not Localize}
    OnCommand := CommandRETR;
    ReplyExceptionCode := 550;
  end;
  //STOR <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'STOR';    {Do not Localize}
    OnCommand := CommandSSAP;
    ReplyExceptionCode := 550;
  end;
  //STOU <CRLF>
  with CommandHandlers.Add do begin
    Command := 'STOU';    {Do not Localize}
    OnCommand := CommandSSAP;
    ReplyExceptionCode := 550;
  end;
  //APPE <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'APPE';    {Do not Localize}
    OnCommand := CommandSSAP;
    ReplyExceptionCode := 550;
  end;
  //ALLO <SP> <decimal-integer>
  //    [<SP> R <SP> <decimal-integer>] <CRLF>
  with CommandHandlers.Add do begin
    Command := 'ALLO';    {Do not Localize}
    OnCommand := CommandALLO;
  end;
  //REST <SP> <marker> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'REST';    {Do not Localize}
    OnCommand := CommandREST;
  end;
  //RNFR <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'RNFR';    {Do not Localize}
    OnCommand := CommandRNFR;
  end;
  //RNTO <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'RNTO';    {Do not Localize}
    OnCommand := CommandRNTO;
  end;
  //ABOR <CRLF>
  with CommandHandlers.Add do begin
    Command := 'ABOR';    {Do not Localize}
    OnCommand := CommandABOR;
  end;
  //ABOR <CRLF>
  with CommandHandlers.Add do begin // ABOR with telnet escape
    Command := #$FF#$F4#$FF#$FF'ABOR';    {Do not Localize}
    OnCommand := CommandABOR;
  end;
  //DELE <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'DELE';    {Do not Localize}
    OnCommand := CommandDELE;
  end;
  //RMD  <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'RMD';    {Do not Localize}
    OnCommand := CommandRMD;
  end;
  //MKD  <SP> <pathname> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'MKD';    {Do not Localize}
    OnCommand := CommandMKD;
  end;
  //PWD  <CRLF>
  with CommandHandlers.Add do begin
    Command := 'PWD';    {Do not Localize}
    OnCommand := CommandPWD;
  end;
  //LIST [<SP> <pathname>] <CRLF>
  FCmdHandlerList := CommandHandlers.Add;
  with FCmdHandlerList do begin
    Command := 'LIST';    {Do not Localize}
    OnCommand := CommandLIST;
  end;
  //NLST [<SP> <pathname>] <CRLF>
  FCmdHandlerNlst := CommandHandlers.Add;
  with FCmdHandlerNlst do begin
    Command := 'NLST';    {Do not Localize}
    OnCommand := CommandLIST;
  end;
  //SITE <SP> <string> <CRLF>
  with CommandHandlers.Add do begin
    Command := 'SITE';    {Do not Localize}
    OnCommand := CommandSITE;
  end;
  //SYST <CRLF>
  with CommandHandlers.Add do begin
    Command := 'SYST';    {Do not Localize}
    OnCommand := CommandSYST;
  end;
  //STAT [<SP> <pathname>] <CRLF>
  with CommandHandlers.Add do begin
    Command := 'STAT';    {Do not Localize}
    OnCommand := CommandSTAT;
  end;
  //HELP [<SP> <string>] <CRLF>
  with CommandHandlers.Add do begin
    Command := 'HELP';    {Do not Localize}
    ReplyNormal.NumericCode := 214;
    //
    if Length(FHelpReply.Text) <> 0 then
     ReplyNormal.Text := FHelpReply
    else
     ReplyNormal.Text.Text := 'HELP Command';    {Do not Localize}
  end;
  //NOOP <CRLF>
  with CommandHandlers.Add do begin
    Command := 'NOOP';    {Do not Localize}
    ReplyNormal.NumericCode := 200;
    ReplyNormal.Text.Text := Format(RSFTPCmdSuccessful, ['NOOP']);    {Do not Localize}
  end;
  with CommandHandlers.Add do begin
    Command := 'XMKD';    {Do not Localize}
    OnCommand := CommandMKD;
  end;
  with CommandHandlers.Add do begin
    Command := 'XRMD';    {Do not Localize}
    OnCommand := CommandRMD;
  end;
  with CommandHandlers.Add do begin
    Command := 'XPWD';    {Do not Localize}
    OnCommand := CommandPWD;
  end;
  with CommandHandlers.Add do begin
    Command := 'XCUP';    {Do not Localize}
    OnCommand := CommandCDUP;
  end;
  with CommandHandlers.Add do begin
    Command := 'FEAT';    {Do not Localize}
    OnCommand := CommandFEAT;
  end;
  //TODO: OPTS - what is this for? Cannot find in RFC 959
  with CommandHandlers.Add do begin
    Command := 'OPTS';    {Do not Localize}
    OnCommand := CommandOPTS;
  end;
  //SIZE [<FILE>] CRLF
  with CommandHandlers.Add do begin
    Command := 'SIZE';    {Do not Localize}
    OnCommand := CommandSIZE;
  end;
end;

destructor TIdFTPServer.Destroy;
begin
  FreeAndNil(FAnonymousAccounts);
  FreeAndNil(FHelpReply);
  inherited Destroy;
end;

procedure TIdFTPServer.ListDirectory(ASender: TIdFTPServerThread; ADirectory: string;
 var ADirContents: TstringList; ADetails: Boolean);
var
  i: Integer;
  LDirectoryList: TIdFTPListItems;
  LPathSep: string;
begin
  if Assigned(FOnListDirectory) then begin
    LDirectoryList := TIdFTPListItems.Create; try
      LPathSep := '/';    {Do not Localize}
      // Emulated System
      case FEmulateSystem of
        ftpsOther: begin
          if Assigned(OnGetCustomListFormat) then begin
            LDirectoryList.ListFormat := flfCustom;
            LDirectoryList.OnGetCustomListFormat := DoGetCustomListFormat;
          end else begin
            LDirectoryList.ListFormat := flfNone;
          end;
        end;
        ftpsDOS: begin
          LDirectoryList.ListFormat := flfDos;
          LPathSep := '\';    {Do not Localize}
        end;
        ftpsUNIX: begin
          LDirectoryList.ListFormat := flfUnix;
        end;
        ftpsVAX: begin
          LDirectoryList.ListFormat := flfVax;
        end;
      end;
      if Copy(ADirectory, Length(LPathSep), 1) <> LPathSep then begin
        ADirectory := ADirectory + LPathSep;
      end;
      // Event
      FOnListDirectory(ASender, ADirectory, LDirectoryList);

      for i := 0 to LDirectoryList.Count - 1 do begin
        if ADetails then begin
          ADirContents.Add(LDirectoryList.Items[i].Text);
        end else begin
          ADirContents.Add(LDirectoryList.Items[i].Filename);
        end;
      end;
    finally FreeAndNil(LDirectoryList); end;
  end else begin
    raise EIdFTPServerNoOnListDirectory.Create(RSFTPNoOnDirEvent);    {Do not Localize}
  end;
end;

procedure TIdFTPServer.SetHelpReply(const AValue: Tstrings);
begin
  FHelpReply.Assign(AValue);
end;

procedure TIdFTPServer.SetUserAccounts(const AValue: TIdUserManager);
begin
  FUserAccounts := AValue;
  if Assigned(FUserAccounts) then
  begin
    FUserAccounts.FreeNotification(Self);
  end;
end;

procedure TIdFTPServer.Notification(AComponent: TComponent; Operation: TOperation);
begin
  inherited Notification(AComponent, Operation);
  if (Operation = opRemove) and (AComponent = FUserAccounts) then
    FUserAccounts := nil;
end;

procedure TIdFTPServer.SetAnonymousAccounts(const AValue: TstringList);
begin
  if Assigned(AValue) then
  begin
    FAnonymousAccounts.Assign(AValue);
  end;
end;

procedure TIdFTPServer.SetEmulateSystem(const AValue: TIdFTPSystems);
begin
  if AnsiSameText(FSystemType, 'Windows 9x/NT.') or AnsiSameText(FSystemType, 'UNIX type: L8.') then    {Do not Localize}
  begin
    case AValue of
      ftpsDOS: FSystemType := 'Windows 9x/NT.';    {Do not Localize}
      ftpsUNIX,
      ftpsVAX: FSystemType := 'UNIX type: L8.';    {Do not Localize}
    end;
  end;
  FEmulateSystem := AValue;
end;

procedure TIdFTPServer.ThreadException(AThread: TIdThread;
  AException: Exception);
begin
  ShowException(AException, nil);
end;

//Command Replies/Handling
procedure TIdFTPServer.CommandUSER(ASender: TIdCommand);
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if (FAnonymousAccounts.IndexOf(Lowercase(ASender.UnparsedParams)) >= 0)
     and (AllowAnonymousLogin) then begin
      UserType := utAnonymousUser;
      FUsername := ASender.UnparsedParams;
      ASender.Reply.SetReply(331, RSFTPAnonymousUserOkay);
    end else begin
      UserType := utNormalUser;
      if Length(ASender.UnparsedParams) > 0 then begin
        FUsername := ASender.UnparsedParams;
        ASender.Reply.SetReply(331, RSFTPUserOkay);
      end else begin
        ASender.Reply.SetReply(332, RSFTPNeedAccountForLogin);
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandPASS(ASender: TIdCommand);
var
  LValidated: Boolean;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    case FUserType of
      utAnonymousUser:
      begin
        LValidated := Length(ASender.UnparsedParams) > 0;
        if FAnonymousPassStrictCheck and LValidated then begin
          LValidated := False;
          if FindFirstOf('@.', ASender.UnparsedParams) > 0 then begin    {Do not Localize}
            LValidated := True;
          end;
        end;
        if LValidated then begin
          FAuthenticated := True;
          FPassword := ASender.UnparsedParams;
          ASender.Reply.SetReply(230, RSFTPAnonymousUserLogged);
        end else begin
          FUserType := utNone;
          FAuthenticated := False;
          FPassword := '';    {Do not Localize}
          ASender.Reply.SetReply(530, RSFTPUserNotLoggedIn);
        end;
      end;//utAnonymousUser
      utNormalUser:
      begin
        if Assigned(FUserAccounts) then begin
          FAuthenticated := FUserAccounts.AuthenticateUser(FUsername, ASender.UnparsedParams);
          if FAuthenticated then begin
            FPassword := ASender.UnparsedParams;
            ASender.Reply.SetReply(230, RSFTPUserLogged);
          end else begin
            FPassword := '';    {Do not Localize}
            ASender.Reply.SetReply(530, RSFTPUserNotLoggedIn);
          end;
        end
        else if Assigned(FOnUserLogin) then begin
          LValidated := False;
          FOnUserLogin(TIdFTPServerThread(ASender.Thread), FUsername, ASender.UnparsedParams, LValidated);
          FAuthenticated := LValidated;
          if LValidated then begin
            FPassword := ASender.UnparsedParams;
            ASender.Reply.SetReply(230, RSFTPUserLogged);
          end else begin
            FPassword := '';    {Do not Localize}
            ASender.Reply.SetReply(530, RSFTPUserNotLoggedIn);
          end;
        end
        //APR 020423
        else begin
          ASender.Reply.SetReply(530, RSFTPUserNotLoggedIn); // user manager not found
        end;
      end;//utNormalUser
    else
      ASender.Reply.SetReply(503, RSFTPNeedLoginWithUser);
    end;//case
  end;//with
  //After login
  if TIdFTPServerThread(ASender.Thread).FAuthenticated and Assigned(FOnAfterUserLogin) then begin
    FOnAfterUserLogin(TIdFTPServerThread(ASender.Thread));
  end;
end;

procedure TIdFTPServer.CommandCWD(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      if Assigned(OnChangeDirectory) then begin
        case FEmulateSystem of
          ftpsDOS: s := ProcessPath(FCurrentDir, ASender.UnparsedParams, '\');    {Do not Localize}
          ftpsOther, ftpsUNIX, ftpsVAX: s := ProcessPath(FCurrentDir, ASender.UnparsedParams);
        end;
        DoChangeDirectory(TIdFTPServerThread(ASender.Thread), s);
        ASender.Reply.SetReply(250, Format(RSFTPCmdSuccessful, ['CWD']));    {Do not Localize}
        FCurrentDir := s;
      end else begin
        ASender.Reply.SetReply(550, Format(RSFTPCmdNotImplemented, ['CWD']));    {Do not Localize}
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandCDUP(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      case FEmulateSystem of
        ftpsDOS: s := '..\';    {Do not Localize}
        ftpsOther, ftpsUNIX, ftpsVAX: s := '../';    {Do not Localize}
      end;
      if Assigned(FOnChangeDirectory) then begin
        DoChangeDirectory(TIdFTPServerThread(ASender.Thread), s);
        FCurrentDir := s;
        ASender.Reply.SetReply(212, Format(RSFTPCurrentDirectoryIs, [FCurrentDir]));
      end else begin
        ASender.Reply.SetReply(550, Format(RSFTPCmdNotImplemented, ['CWD']));    {Do not Localize}
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandREIN(ASender: TIdCommand);
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      ReInitialize;
      ASender.Reply.SetReply(220, RSFTPServiceOpen);
    end;
  end;
end;

procedure TIdFTPServer.CommandPORT(ASender: TIdCommand);
var
  LLo, LHi: Integer;
  LParm, IP: string;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      FPASV := False;
      LParm := ASender.UnparsedParams;
      IP := '';    {Do not Localize}
      { h1 }
      IP := IP + Fetch(LParm, ',') + '.';    {Do not Localize}
      { h2 }
      IP := IP + Fetch(LParm, ',') + '.';    {Do not Localize}
      { h3 }
      IP := IP + Fetch(LParm, ',') + '.';    {Do not Localize}
      { h4 }
      IP := IP + Fetch(LParm, ',');    {Do not Localize}
      { p1 }
      LLo := StrToInt(Fetch(LParm, ','));    {Do not Localize}
      { p2 }
      LHi := StrToInt(LParm);
      FDataPort := (LLo * 256) + LHi;
      CreateDataChannel(False);
      FDataChannelThread.SetupDataChannel(IP, FDataPort);
      ASender.Reply.SetReply(200, Format(RSFTPCmdSuccessful, ['PORT']));    {Do not Localize}
    end;
  end;
end;

procedure TIdFTPServer.CommandPASV(ASender: TIdCommand);
var
  LParam: string;
  LBPort: Word;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      CreateDataChannel(True);
      FDataChannelThread.SetupDataChannel(TIdIOHandlerSocket(Connection.IOHandler).Binding.IP
       , FDataPort);
      with TIdSimpleServer(FDataChannelThread.FDataChannel) do begin
        BeginListen;
        LBPort := Binding.Port;
        LParam := stringReplace(BoundIP, '.', ',', [rfReplaceAll]);    {Do not Localize}
        LParam := LParam + ',' + IntToStr(LBPort div 256) + ',' + IntToStr(LBPort mod 256);    {Do not Localize}

        ASender.Reply.SetReply(227, Format(RSFTPPassiveMode, [LParam]));
        FPASV := True;
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandTYPE(ASender: TIdCommand);
var
  LType: Char;
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      if Length(ASender.UnparsedParams) = 1 then
      begin
        //Default data type is ASCII
        LType := Uppercase(ASender.UnparsedParams)[1];
        case LType of
          'A': FDataType := ftASCII;    {Do not Localize}
          'I': FDataType := ftBinary;    {Do not Localize}
        end;
        if FDataType in  [ftASCII, ftBinary] then
        begin
          ASender.Reply.SetReply(200, Format(RSFTPTYPEChanged, [LType]));
        end;
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandSTRU(ASender: TIdCommand);
var
  LDataStruct: Char;
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      if Length(ASender.UnparsedParams) = 1 then
      begin
        //Default structure is file
        LDataStruct := Uppercase(ASender.UnparsedParams)[1];
        case LDataStruct of
          'F': FDataStruct := dsFile;    {Do not Localize}
          'R': FDataStruct := dsRecord;    {Do not Localize}
          'P': FDataStruct := dsPage;    {Do not Localize}
        end;
        if FDataStruct in [dsFile, dsRecord, dsPage] then
        begin
          ASender.Reply.SetReply(200, Format(RSFTPSTRUChanged, [LDataStruct]));
        end;
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandMODE(ASender: TIdCommand);
var
  LMode: Char;
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      if Length(ASender.UnparsedParams) = 1 then
      begin
        //Default data mode is stream
        LMode := Uppercase(ASender.UnparsedParams)[1];
        case LMode of
          'B': FDataMode := dmBlock;    {Do not Localize}
          'C': FDataMode := dmCompressed;    {Do not Localize}
          'S': FDataMode := dmStream;    {Do not Localize}
        end;
        if FDataMode in [dmBlock, dmCompressed, dmStream] then
        begin
          ASender.Reply.SetReply(200, Format(RSFTPMODEChanged, [LMode]));
        end;
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandRETR(ASender: TIdCommand);
var
  s: string;
  LStream: TStream;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      //TODO: Fix reference to /
      s := ProcessPath(CurrentDir, ASender.UnparsedParams, '/');    {Do not Localize}
      if Assigned(FOnRetrieveFile) then begin
        LStream := nil;
        FOnRetrieveFile(TIdFTPServerThread(ASender.Thread), s, LStream);
        if Assigned(LStream) then begin
          LStream.Position := FRESTPos;
          FRESTPos := 0;
          FDataChannelThread.Data := LStream;
          FDataChannelThread.OKReply.SetReply(226, RSFTPDataConnClosed);
          FDataChannelThread.ErrorReply.SetReply(426, RSFTPDataConnClosedAbnormally);
          ASender.Reply.SetReply(150, RSFTPDataConnToOpen);
          ASender.SendReply;
          FDataChannelThread.StartThread(ftpRetr);
        end else begin
          ASender.Reply.SetReply(550, RSFTPFileActionAborted);
        end;
      end else begin
        ASender.Reply.SetReply(550, Format(RSFTPCmdNotImplemented, ['RETR']));    {Do not Localize}
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandSSAP(ASender: TIdCommand);
var
  LStream: TStream;
  LTmp1: string;
  LAppend: Boolean;
  Reply: TIdRFCReply;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      if AnsiSameText(ASender.CommandHandler.Command, 'STOU') then begin    {Do not Localize}
        //TODO: Find a better method of finding unique names
        RandSeed := 9944;
        Randomize;
        LTmp1 := 'Tmp' + IntToStr(Random(192));    {Do not Localize}
      end else begin
        LTmp1 := ASender.UnparsedParams;
      end;
      //
      LTmp1 := ProcessPath(FCurrentDir, LTmp1);
      LAppend := AnsiSameText(ASender.CommandHandler.Command, 'APPE');    {Do not Localize}
      //
      if Assigned(FOnStoreFile) then begin
        LStream := nil;
        FOnStoreFile(TIdFTPServerThread(ASender.Thread), LTmp1, LAppend, LStream);
        if Assigned(LStream) then begin
          //Issued previously by ALLO cmd
          if FALLOSize > 0 then begin
            LStream.Size := FALLOSize;
          end;
          if LAppend then begin
            LStream.Position := LStream.Size;
          end else begin
            LStream.Position := FRESTPos;
            FRESTPos:=0;
//was:            LStream.Position := 0;
          end;
          { Data transfer }
          try
            Reply := TIdRFCReply.Create(nil);
            {
            FDataChannelThread.Data := LStream;
            Reply.SetReply(226, RSFTPDataConnClosed);
            FDataChannelThread.OKReply := Reply;
            Reply.SetReply(426, RSFTPDataConnClosedAbnormally);
            FDataChannelThread.ErrorReply := Reply;
            ASender.Reply.SetReply(150, RSFTPDataConnToOpen);
            ASender.SendReply;   }
            FDataChannelThread.Data := LStream;
            FDataChannelThread.OKReply.SetReply(226, RSFTPDataConnClosed);
            FDataChannelThread.ErrorReply.SetReply(426, RSFTPDataConnClosedAbnormally);
            ASender.Reply.SetReply(150, RSFTPDataConnToOpen);
            ASender.SendReply;

            FDataChannelThread.StartThread(ftpStor);
          finally FreeAndNil(Reply); end;
        end else begin
          ASender.Reply.SetReply(550, RSFTPFileActionAborted);
        end;
      end else begin
        ASender.Reply.SetReply(550, Format(RSFTPCmdNotImplemented, [ASender.CommandHandler.Command]));
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandALLO(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      s := Uppercase(ASender.UnparsedParams);
      case s[1] of
        'R':    {Do not Localize}
           begin
             if s[2] = #32 then begin
               FALLOSize := StrToIntDef(Copy(s, 2, Length(s) - 2), 0);
             end;
           end;
      else
        FALLOSize := StrToIntDef(ASender.UnparsedParams, 0);
      end;
      ASender.Reply.SetReply(200, Format(RSFTPCmdSuccessful, ['ALLO']));    {Do not Localize}
    end;
  end;
end;

procedure TIdFTPServer.CommandREST(ASender: TIdCommand);
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      FRESTPos := StrToIntDef(ASender.UnparsedParams, 0);
      ASender.Reply.SetReply(350, RSFTPFileActionPending);
    end;
  end;
end;

procedure TIdFTPServer.CommandRNFR(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      s := ASender.UnparsedParams;
      if Assigned(FOnRenameFile) then
      begin
        ASender.Reply.SetReply(350, RSFTPFileActionPending);
        FRNFR := s;
      end
      else
      begin
        ASender.Reply.SetReply(350, RSFTPFileActionPending);
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandRNTO(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      s := ASender.UnparsedParams;
      if Assigned(FOnRenameFile) then
      begin
        try
          FOnRenameFile(TIdFTPServerThread(ASender.Thread), FRNFR, s);
          ASender.Reply.NumericCode := 250;
        except
          ASender.Reply.NumericCode := 550;
          raise;
        end;
      end
      else
      begin
        ASender.Reply.SetReply(550, RSFTPFileActionNotTaken);
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandABOR(ASender: TIdCommand);
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      if not FDataChannelThread.Stopped then begin
        FDataChannelThread.OkReply.SetReply(426, RSFTPDataConnClosedAbnormally);
        FDataChannelThread.ErrorReply.SetReply(426, RSFTPDataConnClosedAbnormally);
        KillDataChannel;
        ASender.Reply.SetReply(226, RSFTPDataConnClosed);
      end else begin
        ASender.Reply.SetReply(226, Format(RSFTPCmdSuccessful, ['ABOR']));    {Do not Localize}
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandDELE(ASender: TIdCommand);
(*
DELE <SP> <pathname> <CRLF>
  250 Requested file action okay, completed.
  450 Requested file action not taken. - File is busy
  550 Requested action not taken. - File unavailable, no access permitted, etc
  500 Syntax error, command unrecognized.
  501 Syntax error in parameters or arguments.
  502 Command not implemented.
  421 Service not available, closing control connection. - During server shutdown, etc
  530 Not logged in.
*)
//TODO: Need to set replies when not authenticated and set replynormal to 250
// do for all procs, list valid replies in comments. Or maybe default is 550
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      if Assigned(FOnDeleteFile) then begin
        FOnDeleteFile(TIdFTPServerThread(ASender.Thread), ASender.UnparsedParams);
        ASender.Reply.SetReply(250, RSFTPFileActionCompleted);
      end else begin
        ASender.Reply.SetReply(550, RSFTPFileActionNotTaken);
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandRMD(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      s := ProcessPath(FCurrentDir, ASender.UnparsedParams);
      if Assigned(FOnRemoveDirectory) then begin
        DoRemoveDirectory(TIdFTPServerThread(ASender.Thread), s);
        ASender.Reply.SetReply(250, RSFTPFileActionCompleted);
      end else begin
        ASender.Reply.SetReply(550, Format(RSFTPCmdNotImplemented, ['RMD']));    {Do not Localize}
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandMKD(ASender: TIdCommand);
var
  S: string;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      S := ProcessPath(FCurrentDir, ASender.UnparsedParams);
      if Assigned(FOnMakeDirectory) then begin
        FOnMakeDirectory(TIdFTPServerThread(ASender.Thread), s);
        ASender.Reply.SetReply(257, RSFTPFileActionCompleted);
      end
      else begin
        ASender.Reply.SetReply(550, Format(RSFTPCmdNotImplemented, ['MKD']));    {Do not Localize}
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandPWD(ASender: TIdCommand);
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      ASender.Reply.SetReply(257, Format(RSFTPCurrentDirectoryIs, [FCurrentDir]));
    end;
  end;
end;

procedure TIdFTPServer.CommandLIST(ASender: TIdCommand);
var
  LStream: TstringList;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      LStream := TstringList.Create;
      try
        ListDirectory(TIdFTPServerThread(ASender.Thread), ProcessPath(FCurrentDir
         , ASender.UnparsedParams), LStream, ASender.CommandHandler = FCmdHandlerList);
      finally
        FDataChannelThread.Data := LStream;
        FDataChannelThread.OKReply.SetReply(226, RSFTPDataConnClosed);
        FDataChannelThread.ErrorReply.SetReply(426, RSFTPDataConnClosedAbnormally);
        ASender.Reply.SetReply(125, RSFTPDataConnToOpen);
        ASender.SendReply;
        FDataChannelThread.StartThread(ftpRetr);
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandSITE(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do
  begin
    if IsAuthenticated(ASender) then
    begin
      s := Uppercase(ASender.UnparsedParams);
      if AnsiSameText(s, 'HELP') then    {Do not Localize}
      begin
        ASender.Reply.SetReply(214, RSFTPSITECmdsSupported);
      end
      else
      begin
        case FEmulateSystem of
          ftpsDOS: ASender.Reply.SetReply(214, Format(RSFTPDirectorySTRU, ['MS-DOS']));    {Do not Localize}
          ftpsUNIX: ASender.Reply.SetReply(214, Format(RSFTPDirectorySTRU, ['UNIX']));    {Do not Localize}
          ftpsVAX: ASender.Reply.SetReply(214, Format(RSFTPDirectorySTRU, ['VAX/VMS']));    {Do not Localize}
        end;
      end;
    end;
  end;
end;

procedure TIdFTPServer.CommandSYST(ASender: TIdCommand);
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      ASender.Reply.SetReply(215, FSystemType);
    end;
  end;
end;

procedure TIdFTPServer.CommandSTAT(ASender: TIdCommand);
var
  LStream: TstringList;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      if NOT FDataChannelThread.Stopped then begin //was .Suspended
        ASender.Reply.SetReply(211, RSFTPOpenDataConn);
      end;
      //else act as LIST command without a data channel
      ASender.Reply.SetReply(211, RSFTPDataConnToOpen);
      ASender.SendReply;
      LStream := TStringList.Create;
      try
        ListDirectory(TIdFTPServerThread(ASender.Thread), ProcessPath(FCurrentDir,
          ASender.UnparsedParams), LStream, True);
      finally
        Connection.Writestrings(LStream);
        FreeAndNil(LStream);
      end;
      ASender.Reply.SetReply(211, RSFTPCmdEndOfStat);
    end;
  end;
end;

procedure TIdFTPServer.CommandFEAT(ASender: TIdCommand);
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    begin
      ASender.Reply.SetReply(502,RSFTPCmdSyntaxError);
    end;
  end;
end;

procedure TIdFTPServer.CommandOPTS(ASender: TIdCommand);
var
  s: string;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then begin
      //TODO: Actually call event
      s := ASender.UnparsedParams;
      ASender.Reply.SetReply(202, Format(RSFTPCmdNotImplemented, ['OPTS']));    {Do not Localize}
    end;
  end;
end;

procedure TIdFTPServer.CommandSIZE(ASender: TIdCommand);
var
  s: string;
  LSize: Int64;
begin
  with TIdFTPServerThread(ASender.Thread) do begin
    if IsAuthenticated(ASender) then
    begin
      s := ProcessPath(FCurrentDir, ASender.UnparsedParams);
      if Assigned(FOnGetFileSize) then
      begin
        try
          LSize := -1;
          FOnGetFileSize(TIdFTPServerThread(ASender.Thread), s, LSize);
          if LSize > -1 then begin
            ASender.Reply.SetReply(213, IntToStr(LSize));
          end else begin
            ASender.Reply.SetReply(550, RSFTPFileActionAborted);
          end;
        except
          ASender.Reply.NumericCode := 550;
          raise;
        end;
      end else begin
        ASender.Reply.SetReply(550, Format(RSFTPCmdNotImplemented, ['SIZE']));    {Do not Localize}
      end;
    end;
  end;
end;

procedure TIdFTPServer.DoGetCustomListFormat(AItem: TIdFTPListItem; var VText: string);
begin
  if Assigned(OnGetCustomListFormat) then begin
    OnGetCustomListFormat(Self, AItem, VText);
  end;
end;

procedure TIdFTPServer.DoChangeDirectory(AThread: TIdFTPServerThread; var VDirectory: string);
begin
  if Assigned(FOnChangeDirectory) then begin
    FOnChangeDirectory(AThread, VDirectory);
  end;
end;

procedure TIdFTPServer.DoRemoveDirectory(AThread: TIdFTPServerThread; var VDirectory: string);
begin
  if Assigned(FOnRemoveDirectory) then begin
    FOnRemoveDirectory(AThread, VDirectory);
  end;
end;

procedure TIdFTPServer.DoMakeDirectory(AThread: TIdFTPServerThread; var VDirectory: string);
begin
  if Assigned(FOnMakeDirectory) then begin
    FOnMakeDirectory(AThread, VDirectory);
  end;
end;

end.
