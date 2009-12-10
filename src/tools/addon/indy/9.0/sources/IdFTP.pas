{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10161: IdFTP.pas 
{
    Rev 1.4    3/19/2003 2:40:18 PM  BGooijen
  The IOHandler of the datachannel was not freed
}
{
    Rev 1.3    3/19/2003 1:41:26 PM  BGooijen
  Fixed datachannel over socks connection (uploading files)
}
{
    Rev 1.2    3/13/2003 10:54:56 AM  BGooijen
  The transfertype is now set in .login, instead of in .connect, when autologin
  = true
}
{
    Rev 1.1    3/12/2003 12:48:00 PM  BGooijen
  Fixed datachannel over socks connection
}
{
{   Rev 1.0    2002.11.12 10:38:30 PM  czhower
}
unit IdFTP;

{
Change Log:
2002-09-18 - Remy Lebeau
  - added AFromBeginning parameter to InternalPut to correctly honor the AAppend parameter of Put
2002-01-xx - Andrew P.Rybin
  - Proxy support, OnAfterGet (ex:decrypt, set srv timestamp)
  - J.Peter Mugaas: not readonly ProxySettings
  A Neillans - 10/17/2001
    Merged changes submitted by Andrew P.Rybin
    Correct command case problems - some servers expect commands in Uppercase only.
  SP - 06/08/2001
    Added a few more functions
  Doychin - 02/18/2001
    OnAfterLogin event handler and Login method

    OnAfterLogin is executed after successfull login  but before setting up the
      connection properties. This event can be used to provide FTP proxy support
      from the user application. Look at the FTP demo program for more information
      on how to provide such support.

  Doychin - 02/17/2001
    New onFTPStatus event
    New Quote method for executing commands not implemented by the compoent

-CleanDir contributed by Amedeo Lanza

TODO: Chage the FTP demo to demonstrate the use of the new events and add proxy support
}

interface

uses
  Classes,
  IdAssignedNumbers, IdException, IdRFCReply,
  IdSocketHandle, IdTCPConnection, IdTCPClient, IdThread, IdFTPList, IdFTPCommon, IdGlobal;

type
  //Added by SP
  TIdCreateFTPList = procedure(ASender: TObject; Var VFTPList: TIdFTPListItems) of object;
  TIdCheckListFormat = procedure(ASender: TObject; const ALine: String; Var VListFormat: TIdFTPListFormat) of object;
  TOnAfterClientLogin = TNotifyEvent;
  TIdFtpAfterGet = procedure (ASender: TObject; VStream: TStream) of object; //APR

const
  Id_TIdFTP_TransferType = ftBinary;
  Id_TIdFTP_Passive = False;

type
  //APR 011216:
  TIdFtpProxyType = (fpcmNone,//Connect method:
    fpcmUserSite, //Send command USER user@hostname
    fpcmSite, //Send command SITE (with logon)
    fpcmOpen, //Send command OPEN
    fpcmUserPass,//USER user@firewalluser@hostname / PASS pass@firewallpass
    fpcmTransparent, //First use the USER and PASS command with the firewall username and password, and then with the target host username and password.
    fpcmHttpProxyWithFtp //HTTP Proxy with FTP support. Will be supported in Indy 10
  ); //TIdFtpProxyType

  TIdFtpProxySettings = class (TPersistent)
  protected
    FHost, FUserName, FPassword: String;
    FProxyType: TIdFtpProxyType;
    FPort: Integer;
  public
    procedure Assign(Source: TPersistent); override;
  published
    property  ProxyType: TIdFtpProxyType read FProxyType write FProxyType;
    property  Host: String read FHost write FHost;
    property  UserName: String read FUserName write FUserName;
    property  Password: String read FPassword write FPassword;
    property  Port: Integer read FPort write FPort;
  End;//TIdFtpProxySettings

  TIdFTP = class(TIdTCPClient)
  protected
    FCanResume: Boolean;
    FListResult: TStrings;
    FLoginMsg: TIdRFCReply;
    FPassive: boolean;
    FResumeTested: Boolean;
    FSystemDesc: string;
    FTransferType: TIdFTPTransferType;
    FDataChannel: TIdTCPConnection;
    FDirectoryListing: TIdFTPListItems;
    FOnAfterClientLogin: TNotifyEvent;
    FOnCreateFTPList: TIdCreateFTPList;
    FOnCheckListFormat: TIdCheckListFormat;
    FOnAfterGet: TIdFtpAfterGet; //APR
    FProxySettings: TIdFtpProxySettings;
    //
    procedure ConstructDirListing;
    procedure DoAfterLogin;
    procedure DoFTPList;
    procedure DoCheckListFormat(const ALine: String);
    function GetDirectoryListing: TIdFTPListItems;
    function GetOnParseCustomListFormat: TIdOnParseCustomListFormat;
    procedure InitDataChannel;
    procedure InternalGet(const ACommand: string; ADest: TStream; AResume: Boolean = false);
    procedure InternalPut(const ACommand: string; ASource: TStream; AFromBeginning: Boolean = true);
    procedure SetOnParseCustomListFormat(const AValue: TIdOnParseCustomListFormat);
    procedure SendPassive(var VIP: string; var VPort: integer);
    procedure SendPort(AHandle: TIdSocketHandle);
    procedure SetProxySettings(const Value: TIdFtpProxySettings);
    procedure SendTransferType;
    procedure SetTransferType(AValue: TIdFTPTransferType);
    procedure DoAfterGet (AStream: TStream); virtual; //APR
  public
    procedure Abort; virtual;
    procedure Account(AInfo: String);
    procedure Allocate(AAllocateBytes: Integer);
    procedure ChangeDir(const ADirName: string);
    procedure ChangeDirUp;
    procedure Connect(AAutoLogin: boolean = True; const ATimeout: Integer = IdTimeoutDefault); reintroduce;
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Delete(const AFilename: string);
    procedure FileStructure(AStructure: TIdFTPDataStructure);
    procedure Get(const ASourceFile: string; ADest: TStream; AResume: Boolean = false); overload;
    procedure Get(const ASourceFile, ADestFile: string; const ACanOverwrite: boolean = false; AResume: Boolean = false); overload;
    procedure Help(var AHelpContents: TStringList; ACommand: String = '');
    procedure KillDataChannel; virtual;
    procedure List(ADest: TStrings; const ASpecifier: string = ''; const ADetails: boolean = true);
    procedure Login;
    procedure MakeDir(const ADirName: string);
    procedure Noop;
    procedure Put(const ASource: TStream; const ADestFile: string = '';
     const AAppend: boolean = false); overload;
    procedure Put(const ASourceFile: string; const ADestFile: string = '';
     const AAppend: boolean = false); overload;
    procedure Quit;
    function Quote(const ACommand: String): SmallInt;
    procedure RemoveDir(const ADirName: string);
    procedure Rename(const ASourceFile, ADestFile: string);
    function ResumeSupported: Boolean;
    function RetrieveCurrentDir: string;
    procedure Site(const ACommand: string);
    function Size(const AFileName: String): Integer;
    procedure Status(var AStatusList: TStringList);
    procedure StructureMount(APath: String);
    procedure TransferMode(ATransferMode: TIdFTPTransferMode);
    procedure ReInitialize(ADelay: Cardinal = 10);
    //
    property CanResume: Boolean read ResumeSupported;
    property DirectoryListing: TIdFTPListItems read GetDirectoryListing;// FDirectoryListing;
    property LoginMsg: TIdRFCReply read FLoginMsg;
    property SystemDesc: string read FSystemDesc;
    property ListResult: TStrings read FListResult; //APR
  published
    property Passive: boolean read FPassive write FPassive default Id_TIdFTP_Passive;
    property Password;
    property TransferType: TIdFTPTransferType read FTransferType write SetTransferType default Id_TIdFTP_TransferType;
    property Username;
    property Port default IDPORT_FTP;
    property ProxySettings: TIdFtpProxySettings read FProxySettings write SetProxySettings;

    property OnAfterClientLogin: TOnAfterClientLogin read FOnAfterClientLogin write FOnAfterClientLogin;
    property OnCheckListFormat: TIdCheckListFormat read FOnCheckListFormat write FOnCheckListFormat;
    property OnCreateFTPList: TIdCreateFTPList read FOnCreateFTPList write FOnCreateFTPList;
    property OnParseCustomListFormat: TIdOnParseCustomListFormat read GetOnParseCustomListFormat
     write SetOnParseCustomListFormat;
    property OnAfterGet: TIdFtpAfterGet read FOnAfterGet write FOnAfterGet; //APR
  end;
  EIdFTPFileAlreadyExists = class(EIdException);

implementation

uses
  IdComponent, IdResourceStrings, IdStack, IdSimpleServer, IdIOHandlerSocket,
  SysUtils;

function CleanDirName(const APWDReply: string): string;
begin
  Result := APWDReply;
  Delete(result, 1, IndyPos('"', result)); // Remove first doublequote
  Result := Copy(result, 1, IndyPos('"', result) - 1); // Remove anything from second doublequote                                 // to end of line
end;

constructor TIdFTP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Port := IDPORT_FTP;
  Passive := Id_TIdFTP_Passive;
  FTransferType := Id_TIdFTP_TransferType;
  FLoginMsg := TIdRFCReply.Create(NIL);
  FListResult := TStringList.Create;
  FCanResume := false;
  FResumeTested := false;
  FProxySettings:= TIdFtpProxySettings.Create; //APR
end;

procedure TIdFTP.Connect(AAutoLogin: boolean = True;
  const ATimeout: Integer = IdTimeoutDefault); 
var
  TmpHost: String;
  TmpPort: Integer;
begin
  try
    //APR 011216: proxy support
    TmpHost:=FHost;
    TmpPort:=FPort;
    try
      if (ProxySettings.ProxyType > fpcmNone) and (Length(ProxySettings.Host) > 0) then begin
        FHost := ProxySettings.Host;
        FPort := ProxySettings.Port;
      end;
      inherited Connect(ATimeout);
    finally
      FHost := TmpHost;
      FPort := TmpPort;
    end;//tryf
    GetResponse([220]);
    Greeting.Assign(LastCmdResult);
    if AAutoLogin then begin
      Login;
      DoAfterLogin;
      // OpenVMS 7.1 replies with 200 instead of 215 - What does the RFC say about this?
      if SendCmd('SYST', [200, 215, 500]) = 500 then begin  {Do not translate}
        FSystemDesc := RSFTPUnknownHost;
      end else begin
        FSystemDesc := LastCmdResult.Text[0];
      end;
      DoStatus(ftpReady, [RSFTPStatusReady]);
    end;
  except
    Disconnect;
    raise;
  end;
end;

procedure TIdFTP.SetTransferType(AValue: TIdFTPTransferType);
begin
  if AValue <> FTransferType then begin
    if not Assigned(FDataChannel) then begin
      FTransferType := AValue;
      if Connected then begin
        SendTransferType;
      end;
    end
  end;
end;

procedure TIdFTP.SendTransferType;
var
  s: string;
begin
  case TransferType of
    ftAscii: s := 'A';      {Do not translate}
    ftBinary: s := 'I';     {Do not translate}
  end;
  SendCmd('TYPE ' + s, 200); {Do not translate}
end;

function TIdFTP.ResumeSupported: Boolean;
begin
  if FResumeTested then result := FCanResume
  else begin
    FResumeTested := true;
    FCanResume := Quote('REST 1') = 350;   {Do not translate}
    result := FCanResume;
    Quote('REST 0');  {Do not translate}
  end;
end;

procedure TIdFTP.Get(const ASourceFile: string; ADest: TStream; AResume: Boolean = false);
begin
  AResume := AResume and CanResume;
  InternalGet('RETR ' + ASourceFile, ADest, AResume);   {Do not translate}
  DoAfterGet(ADest); //APR
end;

procedure TIdFTP.Get(const ASourceFile, ADestFile: string; const ACanOverwrite: boolean = false;
  AResume: Boolean = false);
var
  LDestStream: TFileStream;
begin
  if FileExists(ADestFile) then begin
    AResume := AResume and CanResume;
    if ACanOverwrite and (not AResume) then begin
      LDestStream := TFileStream.Create(ADestFile, fmCreate);
    end
    else begin
      if (not ACanOverwrite) and AResume then begin
        LDestStream := TFileStream.Create(ADestFile, fmOpenWrite);
        LDestStream.Seek(0, soFromEnd);
      end
      else begin
        raise EIdFTPFileAlreadyExists.Create(RSDestinationFileAlreadyExists);
      end;
    end;
  end
  else begin
    LDestStream := TFileStream.Create(ADestFile, fmCreate);
  end;

  try
    Get(ASourceFile, LDestStream, AResume);
  finally
    FreeAndNil(LDestStream);
  end;
end;

procedure TIdFTP.DoAfterGet (AStream: TStream);//APR
Begin
  if Assigned(FOnAfterGet) then FOnAfterGet(SELF,AStream);
End;//TIdFTP.AtAfterFileGet


procedure TIdFTP.ConstructDirListing;
begin
  if not Assigned(FDirectoryListing) then begin
    if not (csDesigning in ComponentState) then begin
      DoFTPList;
    end;
    if not Assigned(FDirectoryListing) then begin
      FDirectoryListing := TIdFTPListItems.Create;
    end;
  end else begin
    FDirectoryListing.Clear;
  end;
end;

procedure TIdFTP.List(ADest: TStrings; const ASpecifier: string = '';      {Do not translate}
 const ADetails: boolean = true);
var
  LDest: TStringStream;
begin
  LDest := TStringStream.Create(''); try   {Do not translate}
    if ADetails then begin
      InternalGet(trim('LIST ' + ASpecifier), LDest);  {Do not translate}
    end else begin
      InternalGet(trim('NLST ' + ASpecifier), LDest);  {Do not trnalstate}
    end;
    FreeAndNil(FDirectoryListing);
    if Assigned(ADest) then begin //APR: User can use ListResult and DirectoryListing
      ADest.Text := LDest.DataString;
    end;
    FListResult.Text := LDest.DataString;
  finally FreeAndNil(LDest); end;
end;

procedure TIdFTP.InternalGet(const ACommand: string; ADest: TStream; AResume: Boolean = false);
var
  LIP: string;
  LPort: Integer;
  LResponse: Integer;
begin
  DoStatus(ftpTransfer, [RSFTPStatusStartTransfer]); try
    if FPassive then begin
      SendPassive(LIP, LPort);
      FDataChannel := TIdTCPClient.Create(nil); try
        with (FDataChannel as TIdTCPClient) do begin
          if (Self.IOHandler is TIdIOHandlerSocket) then begin
            if not assigned(IOHandler) then begin
              IOHandler:=TIdIOHandlerSocket.create(nil);
            end;
            TIdIOHandlerSocket(IOHandler).SocksInfo.Assign(TIdIOHandlerSocket(Self.IOHandler).SocksInfo);
            TIdIOHandlerSocket(IOHandler).SocksInfo.IOHandler:=IOHandler;
          end;
          InitDataChannel;
          Host := LIP;
          Port := LPort;
          Connect; try
            if AResume then begin
              Self.SendCmd('REST ' + IntToStr(ADest.Position), [350]);   {Do not tranlsate}
            end;
            Self.WriteLn(ACommand);
            Self.GetResponse([125, 150, 154]); //APR: Ericsson Switch FTP
            ReadStream(ADest, -1, True);
          finally Disconnect; end;
        end;
      finally FDataChannel.IOHandler.free; FDataChannel.IOHandler:=nil; FreeAndNil(FDataChannel); end;
    end else begin
      FDataChannel := TIdSimpleServer.Create(nil); try
        with TIdSimpleServer(FDataChannel) do begin
          InitDataChannel;
          BoundIP := (Self.IOHandler as TIdIOHandlerSocket).Binding.IP;
          BeginListen;
          SendPort(Binding);
          if AResume then begin
            Self.SendCmd('REST ' + IntToStr(ADest.Position), [350]);  {Do not translate}
          end;
          Self.SendCmd(ACommand, [125, 150, 154]); //APR: Ericsson Switch FTP
          Listen;
          ReadStream(ADest, -1, True);
        end;
      finally
        FreeAndNil(FDataChannel);
      end;
    end;
  finally
    DoStatus(ftpReady, [RSFTPStatusDoneTransfer]);
  end;
  // ToDo: Change that to properly handle response code (not just success or except)
  // 226 = download successful, 225 = Abort successful}
  LResponse := GetResponse([225, 226, 250, 426, 450]);
  if (LResponse = 426) or (LResponse = 450) then begin
    GetResponse([226, 225]);
    DoStatus(ftpAborted, [RSFTPStatusAbortTransfer]);
  end;
end;

procedure TIdFTP.Quit;
begin
  if Connected then begin
    WriteLn('QUIT');      {Do not translate}
  end;
  Disconnect;
end;

procedure TIdFTP.KillDataChannel;
begin
  // Had kill the data channel ()
  if Assigned(FDataChannel) then begin
    FDataChannel.DisconnectSocket;
  end;
end;

procedure TIdFTP.Abort;
begin
  // only send the abort command. The Data channel is supposed to disconnect
  if Connected then begin
    WriteLn('ABOR');                   {Do not translate}
  end;
  // Kill the data channel: usually, the server doesn't close it by itself
  KillDataChannel;
end;

procedure TIdFTP.SendPort(AHandle: TIdSocketHandle);
begin
  SendCmd('PORT ' + StringReplace(AHandle.IP, '.', ',', [rfReplaceAll])   {Do not translate}
   + ',' + IntToStr(AHandle.Port div 256) + ',' + IntToStr(AHandle.Port mod 256), [200]); {Do not translate}
end;

procedure TIdFTP.InternalPut(const ACommand: string; ASource: TStream; AFromBeginning: Boolean = true);
var
  LIP: string;
  LPort: Integer;
  LResponse: Integer;
begin
  DoStatus(ftpTransfer, [RSFTPStatusStartTransfer]); try
    if FPassive then begin
      SendPassive(LIP, LPort);
      WriteLn(ACommand);
      FDataChannel := TIdTCPClient.Create(nil);
      with TIdTCPClient(FDataChannel) do try
        if (Self.IOHandler is TIdIOHandlerSocket) then begin
          if not assigned(IOHandler) then begin
            IOHandler:=TIdIOHandlerSocket.create(nil);
          end;
          TIdIOHandlerSocket(IOHandler).SocksInfo.Assign(TIdIOHandlerSocket(Self.IOHandler).SocksInfo);
          TIdIOHandlerSocket(IOHandler).SocksInfo.IOHandler:=IOHandler;
        end;
        InitDataChannel;
        Host := LIP;
        Port := LPort;
        Connect;
        try
          Self.GetResponse([110, 125, 150]);
          try
            WriteStream(ASource, {false}AFromBeginning);
          except
            on E: EIdSocketError do begin
              // If 10038 - abort was called. Server will return 225
              if E.LastError <> 10038 then begin
                raise;
              end;
            end;
          end;
        finally Disconnect; end;
      finally FDataChannel.IOHandler.free; FDataChannel.IOHandler:=nil; FreeAndNil(FDataChannel); end;
    end else begin
      FDataChannel := TIdSimpleServer.Create(nil); try
        with TIdSimpleServer(FDataChannel) do begin
          InitDataChannel;
          BoundIP := (Self.IOHandler as TIdIOHandlerSocket).Binding.IP;
          BeginListen;
          SendPort(Binding);
          Self.SendCmd(ACommand, [125, 150]);
          Listen;
          WriteStream(ASource, AFromBeginning);
        end;
      finally FreeAndNil(FDataChannel); end;
    end;
  finally
    DoStatus(ftpReady, [RSFTPStatusDoneTransfer]);
  end;
  // 226 = download successful, 225 = Abort successful}
  LResponse := GetResponse([225, 226, 250, 426, 450]);
  if (LResponse = 426) or (LResponse = 450) then begin
    // some servers respond with 226 on ABOR
    GetResponse([226, 225]);
    DoStatus(ftpAborted, [RSFTPStatusAbortTransfer]);
  end;
end;

procedure TIdFTP.InitDataChannel;
begin
  FDataChannel.SendBufferSize := SendBufferSize;
  FDataChannel.RecvBufferSize := RecvBufferSize;
  FDataChannel.OnWork := OnWork;
  FDataChannel.OnWorkBegin := OnWorkBegin;
  FDataChannel.OnWorkEnd := OnWorkEnd;
end;

procedure TIdFTP.Put(const ASource: TStream; const ADestFile: string = '';
 const AAppend: boolean = false);
begin
  if length(ADestFile) = 0 then begin
    InternalPut('STOU ' + ADestFile, ASource);  {Do not localize}
  end else if AAppend then begin
    InternalPut('APPE ' + ADestFile, ASource, false);  {Do not localize}
  end else begin
    InternalPut('STOR ' + ADestFile, ASource);  {Do not localize}
  end;
end;

procedure TIdFTP.Put(const ASourceFile: string; const ADestFile: string = '';
 const AAppend: boolean = false);
var
  LSourceStream: TFileStream;
begin
  LSourceStream := TFileStream.Create(ASourceFile, fmOpenRead or fmShareDenyNone); try
    Put(LSourceStream, ADestFile, AAppend);
  finally FreeAndNil(LSourceStream); end;
end;

procedure TIdFTP.SendPassive(var VIP: string; var VPort: integer);
var
  i,bLeft,bRight: integer;
  s: string;
begin
  SendCmd('PASV', 227);      {Do not translate}
  s := Trim(LastCmdResult.Text[0]);
  // Case 1 (Normal)
  // 227 Entering passive mode(100,1,1,1,23,45)
  bLeft := IndyPos('(', s);   {Do not translate}
  bRight := IndyPos(')', s);  {Do not translate}
  if (bLeft = 0) or (bRight = 0) then begin
    // Case 2
    // 227 Entering passive mode on 100,1,1,1,23,45
    bLeft := RPos(#32, s);
    s := Copy(s, bLeft + 1, Length(s) - bLeft);
  end else begin
    s := Copy(s, bLeft + 1, bRight - bLeft - 1);
  end;
  VIP := '';                 {Do not translate}
  for i := 1 to 4 do begin
    VIP := VIP + '.' + Fetch(s, ','); {Do not translate}
  end;
  System.Delete(VIP, 1, 1);
  // Determine port
  VPort := StrToInt(Fetch(s, ',')) shl 8;   {Do not translate}
  VPort := VPort + StrToInt(Fetch(s, ',')); {Do not translate}
end;

procedure TIdFTP.Noop;
begin
  SendCmd('NOOP', 200);     {Do not translate}
end;

procedure TIdFTP.MakeDir(const ADirName: string);
begin
  SendCmd('MKD ' + ADirName, 257);  {Do not translate}
end;

function TIdFTP.RetrieveCurrentDir: string;
begin
  SendCmd('PWD', 257);              {Do not translate}
  Result := CleanDirName(LastCmdResult.Text[0]);
end;

procedure TIdFTP.RemoveDir(const ADirName: string);
begin
  SendCmd('RMD ' + ADirName, 250);  {Do not translate}
end;

procedure TIdFTP.Delete(const AFilename: string);
begin
  SendCmd('DELE ' + AFilename, 250);  {Do not translate}
end;

(*
CHANGE WORKING DIRECTORY (CWD)

  This command allows the user to work with a different
  directory or dataset for file storage or retrieval without
  altering his login or accounting information.  Transfer
  parameters are similarly unchanged.  The argument is a
  pathname specifying a directory or other system dependent
  file group designator.

CWD
  250
  500, 501, 502, 421, 530, 550
*)
procedure TIdFTP.ChangeDir(const ADirName: string);
begin
  SendCmd('CWD ' + ADirName, [200, 250]); //APR: Ericsson Switch FTP     {Do not translate}
end;

(*
CHANGE TO PARENT DIRECTORY (CDUP)

  This command is a special case of CWD, and is included to
  simplify the implementation of programs for transferring
  directory trees between operating systems having different
  syntaxes for naming the parent directory.  The reply codes
  shall be identical to the reply codes of CWD.  See
  Appendix II for further details.

CDUP
  200
  500, 501, 502, 421, 530, 550
*)
procedure TIdFTP.ChangeDirUp;
begin
  // RFC lists 200 as the proper response, but in another section says that it can return the
  // same as CWD, which expects 250. That is it contradicts itself.
  // MS in their infinite wisdom chnaged IIS 5 FTP to return 250.
  SendCmd('CDUP', [200, 250]);   {Do not translate}
end;

procedure TIdFTP.Site(const ACommand: string);
begin
  SendCmd('SITE ' + ACommand, 200);   {Do not translate}
end;

procedure TIdFTP.Rename(const ASourceFile, ADestFile: string);
begin
  SendCmd('RNFR ' + ASourceFile, 350);  {Do not translate}
  SendCmd('RNTO ' + ADestFile, 250);    {Do not translate}
end;

function TIdFTP.Size(const AFileName: String): Integer;
var
  SizeStr: String;
begin
  result := -1;
  if SendCmd('SIZE ' + AFileName) = 213 then begin  {Do not translate}
    SizeStr := Trim(LastCmdResult.Text.Text);
    system.delete(SizeStr, 1, IndyPos(' ', SizeStr)); // delete the response   {Do not translate}
    result := StrToIntDef(SizeStr, -1);
  end;
end;

//Added by SP
procedure TIdFTP.ReInitialize(ADelay: Cardinal = 10);
begin
  Sleep(ADelay); //Added
  if SendCmd('REIN', [120, 220, 500]) <> 500 then begin  {Do not translate}
    FLoginMsg.Clear;
    FCanResume := False;
    FDirectoryListing.Clear;
    FUsername := '';                 {Do not translate}
    FPassword := '';                 {Do not translate}
    FPassive := Id_TIdFTP_Passive;
    FCanResume := False;
    FResumeTested := False;
    FSystemDesc := '';
    FTransferType := Id_TIdFTP_TransferType;
  end;
end;

procedure TIdFTP.Allocate(AAllocateBytes: Integer);
begin
  SendCmd('ALLO ' + IntToStr(AAllocateBytes), [200]); {Do not translate}
end;

procedure TIdFTP.Status(var AStatusList: TStringList);
var
  LStrm: TStringStream;
  LList: TStringList;
begin
  if SendCmd('STAT', [211, 212, 213, 500]) <> 500 then   {Do not translate}
  begin
    if not Assigned(FDirectoryListing) then
    begin
      DoFTPList;
    end;
    LStrm := TStringStream.Create('');      {Do not translate}
    LList := TStringList.Create;
    //Read stream through control connection - not data channel
    ReadStream(LStrm, -1, True);
    LList.Text := LStrm.DataString;
    try
      try
        ConstructDirListing;
        FDirectoryListing.Clear;
      except
        on EAccessViolation do ConstructDirListing;
      end;
      // Parse directory listing
      if LList.Count > 0 then
      begin
        FDirectoryListing.ListFormat := FDirectoryListing.CheckListFormat(LList[0], True);
        DoCheckListFormat(LList[0]);
        FDirectoryListing.LoadList(LList);
      end;
    except
      if Assigned(AStatusList) = True then
      begin
        AStatusList.Text := LStrm.DataString;
      end;
    end;
    FreeAndNil(LStrm);
    FreeAndNil(LList);
  end;
end;

procedure TIdFTP.Help(var AHelpContents: TStringList; ACommand: String = ''); {Do not translate}
var
  LStrm: TStringStream;
begin
  LStrm := TStringStream.Create('');    {Do not translate}
  if SendCmd('HELP ' + ACommand, [211, 214, 500]) <> 500 then       {Do not translate}
  begin
    ReadStream(LStrm, -1, True);
    AHelpContents.Text := LStrm.DataString;
  end;
  FreeAndNil(LStrm);
end;

procedure TIdFTP.Account(AInfo: String);
begin
  SendCmd('ACCT ' + AInfo, [202, 230, 500]);   {Do not translate}
end;

procedure TIdFTP.StructureMount(APath: String);
begin
  SendCmd('SMNT ' + APath, [202, 250, 500]);  {Do not translate}
end;

procedure TIdFTP.FileStructure(AStructure: TIdFTPDataStructure);
var
  s: String;
begin
  case AStructure of
    dsFile: s := 'F';         {Do not translate}
    dsRecord: s := 'R';       {Do not translate}
    dsPage: s := 'P';         {Do not translate}
  end;
  SendCmd('STRU ' + s, [200, 500]);  {Do not translate}
  { TODO: Needs to be finished }
end;

procedure TIdFTP.TransferMode(ATransferMode: TIdFTPTransferMode);
var
  s: String;
begin
  case ATransferMode of
    dmBlock: begin
      s := 'B';                {Do not translate}
    end;
    dmCompressed: begin
      s := 'C';                {Do not translate}
    end;
    dmStream: begin
      s := 'S';                {Do not translate}
    end;
  end;
  SendCmd('MODE ' + s, [200, 500]);  {Do not translate}
  { TODO: Needs to be finished }
end;

destructor TIdFTP.Destroy;
begin
  FreeAndNil(FListResult);
  FreeAndNil(FLoginMsg);
  FreeAndNil(FDirectoryListing);
  FreeAndNIL(FProxySettings); //APR
  inherited Destroy;
end;

function TIdFTP.Quote(const ACommand: String): SmallInt;
begin
  result := SendCmd(ACommand);
end;

//APR 011216: ftp proxy support
// TODO: need help - "//?"
procedure TIdFTP.Login;
begin
  case ProxySettings.ProxyType of
  fpcmNone:
    begin
      if SendCmd('USER ' + FUserName, [230, 331]) = 331 then begin   {Do not translate}
        SendCmd('PASS ' + FPassword, 230);                           {Do not translate}
      end;
    end;//fpcmNone
  fpcmUserSite:
    begin
      if (Length(ProxySettings.UserName)>0) then begin
        if SendCmd('USER ' + ProxySettings.UserName, [230, 331]) = 331 then begin  {Do not translate}
          SendCmd('PASS ' + ProxySettings.Password, 230);             {Do not translate}
        end;
      end;//proxy login
      if SendCmd('USER ' + FUserName+'@'+FHost, [230, 331]) = 331 then begin     {Do not translate}
        SendCmd('PASS ' + FPassword, 230);                       {Do not translate}
      end;
    end;//fpcmUserSite
  fpcmSite:
    begin
      if (Length(ProxySettings.UserName)>0) then begin
        if SendCmd('USER ' + ProxySettings.UserName, [230, 331]) = 331 then begin  {Do not translate}
          SendCmd('PASS ' + ProxySettings.Password, 230);  {Do not translate}
        end;
      end;//proxy login
      SendCmd('SITE '+FHost);//? Server Reply? 220?
      if SendCmd('USER ' + FUserName, [230, 331]) = 331 then begin {Do not translate}
        SendCmd('PASS ' + FPassword, 230);    {Do not translate}
      end;
    end;//fpcmSite
  fpcmOpen:
    begin
      if (Length(ProxySettings.UserName)>0) then begin
        if SendCmd('USER ' + ProxySettings.UserName, [230, 331]) = 331 then begin   {Do not translate}
          SendCmd('PASS ' + ProxySettings.Password, 230);   {Do not translate}
        end;
      end;//proxy login
      SendCmd('OPEN '+FHost);//? Server Reply? 220?     {Do not translate}
      if SendCmd('USER ' + FUserName, [230, 331]) = 331 then begin  {Do not translate}
        SendCmd('PASS ' + FPassword, 230);         {Do not translate}
      end;
    end;//fpcmSite
  fpcmUserPass: //USER user@firewalluser@hostname / PASS pass@firewallpass
    begin
      if SendCmd(Format('USER %s@%s@%s',[FUserName,ProxySettings.UserName,FHost]), [230, 331])=331 then begin    {Do not translate}
        if Length(ProxySettings.Password)>0 then begin
          SendCmd('PASS '+FPassword+'@'+ProxySettings.Password, 230); {Do not translate}
        end
        else begin
          SendCmd('PASS '+FPassword, 230); {Do not translate}
        end;//if @
      end;
    end;//fpcmUserPass
  fpcmTransparent: //? +Host
    begin
      if (Length(ProxySettings.UserName)>0) then begin
        if SendCmd('USER ' + ProxySettings.UserName, [230, 331]) = 331 then begin    {Do not translate}
          SendCmd('PASS ' + ProxySettings.Password, 230);     {Do not translate}
        end;
      end;//proxy login
      if SendCmd('USER ' + FUserName, [230, 331]) = 331 then begin   {Do not translate}
        SendCmd('PASS ' + FPassword, 230);  {Do not translate}
      end;
    end;//fpcmTransparent
  fpcmHttpProxyWithFtp:
    begin
{GET ftp://XXX:YYY@indy.nevrona.com/ HTTP/1.0
Host: indy.nevrona.com
User-Agent: Mozilla/4.0 (compatible; Wincmd; Windows NT)
Proxy-Authorization: Basic B64EncodedUserPass==
Connection: close}
      raise EIdException.Create(RSSocksServerCommandError);
    end;//fpcmHttpProxyWithFtp
  end;//case
  FLoginMsg.Assign(LastCmdResult);
  SendTransferType;
End;//TIdFTP.Login

procedure TIdFTP.DoAfterLogin;
begin
  if Assigned(FOnAfterClientLogin) then begin
    OnAfterClientLogin(self);
  end;
end;

procedure TIdFTP.DoFTPList;
begin
  if Assigned(FOnCreateFTPList) then begin
    FOnCreateFTPList(self, FDirectoryListing);
  end;
end;

procedure TIdFTP.DoCheckListFormat(const ALine: String);
Var
  LListFormat: TIdFTPListFormat;
Begin
  if Assigned(FOnCheckListFormat) then begin //APR: User always right!
    LListFormat := FDirectoryListing.ListFormat; //APR: user MUST see Indy opinion
    OnCheckListFormat(Self, ALine, LListFormat);
    FDirectoryListing.ListFormat := LListFormat;
  end;
End;//TIdFTP.DoCheckListFormat

function TIdFTP.GetDirectoryListing: TIdFTPListItems;
begin
  if not Assigned(FDirectoryListing) then begin
    try
      ConstructDirListing;
    except
      on EAccessViolation do ConstructDirListing;
    end;
    // Parse directory listing
    if FListResult.Count > 0 then begin
      FDirectoryListing.ListFormat := FDirectoryListing.CheckListFormat(FListResult[0],TRUE);//APR: TRUE for IndyCheck, else always Unknown
      DoCheckListFormat(FListResult[0]);
      FDirectoryListing.LoadList(FListResult);
    end;
  end;
  Result := FDirectoryListing;
end;

function TIdFTP.GetOnParseCustomListFormat: TIdOnParseCustomListFormat;
begin
  Result := DirectoryListing.OnParseCustomListFormat
end;

procedure TIdFTP.SetOnParseCustomListFormat(const AValue: TIdOnParseCustomListFormat);
begin
  DirectoryListing.OnParseCustomListFormat := AValue;
end;

procedure TIdFTP.SetProxySettings(const Value: TIdFtpProxySettings);
Begin
  FProxySettings.Assign(Value);
End;//

{ TIdFtpProxySettings }

procedure TIdFtpProxySettings.Assign(Source: TPersistent);
Begin
  if Source is TIdFtpProxySettings then begin
    with TIdFtpProxySettings(Source) do begin
      SELF.FProxyType  := ProxyType;
      SELF.FHost := Host;
      SELF.FUserName := UserName;
      SELF.FPassword := Password;
      SELF.FPort := Port;
    end;
  end
  else begin
    inherited Assign(Source);
  end;
End;//


end.

