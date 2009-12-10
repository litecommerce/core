{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10117: IdCustomHTTPServer.pas 
{
{   Rev 1.5    05.6.2003 ã. 11:03:56  DBondzhev
{ Socket exctions should not be stopped after DoCommandGet
}
{
    Rev 1.4    5/8/2003 4:51:40 PM  BGooijen
  fixed av on FSessionList.PurgeStaleSessions(Terminated);
}
{
    Rev 1.3    2/25/2003 10:44:30 AM  BGooijen
  The Serversoftware wasn't send to the client, because of duplicate properties
  (.Server and .ServerSoftware).
}
{
{   Rev 1.2    11.2.2003 13:33:30  TPrami
{ - Fixed URL get parameter handling (RFC 1866 section 8.2.1.)
}
{
{   Rev 1.1    5/12/2002 10:17:32 AM  SGrobety
}
{
{   Rev 1.0    2002.11.12 10:34:42 PM  czhower
}
unit IdCustomHTTPServer;

interface
uses
  Classes,
  IdAssignedNumbers,
  IdException, IdGlobal, IdHeaderList, IdTCPServer, IdThread, IdCookie,
  IdHTTPHeaderInfo, IdStackConsts,
  SyncObjs, SysUtils;

const
  Id_TId_HTTPServer_KeepAlive = false;
  Id_TId_HTTPServer_ParseParams = True;
  Id_TId_HTTPServer_SessionState = False;
  {This probably should be something else but I don't know what   
  I have fixed a problem which was caused by a timeout of 0 so I am extremely
  suspecious of this}
  Id_TId_HTTPSessionTimeOut = 0;
  Id_TId_HTTPAutoStartSession = False;
  GResponseNo = 200;
  GFContentLength = -1;
  GServerSoftware = gsIdProductName + '/' + gsIdVersion;    {Do not Localize}
  GContentType = 'text/html';    {Do not Localize}
  GSessionIDCookie = 'IDHTTPSESSIONID';    {Do not Localize}

type
  // Forwards
  TIdHTTPSession = Class;
  TIdHTTPCustomSessionList = Class;
  TIdHTTPRequestInfo = Class;
  TIdHTTPResponseInfo = Class;
  //events
  TOnSessionEndEvent = procedure(Sender: TIdHTTPSession) of object;
  TOnSessionStartEvent = procedure(Sender: TIdHTTPSession) of object;
  TOnCreateSession = procedure(ASender: TIdPeerThread;
   var VHTTPSession: TIdHTTPSession) of object;
  TOnCreatePostStream = procedure(ASender: TIdPeerThread;
   var VPostStream: TStream) of object;
  TIdHTTPGetEvent = procedure(AThread: TIdPeerThread;
   ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo) of object;
  TIdHTTPOtherEvent = procedure(Thread: TIdPeerThread;
   const asCommand, asData, asVersion: string) of object;
  TIdHTTPInvalidSessionEvent = procedure(Thread: TIdPeerThread;
    ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo;
    var VContinueProcessing: Boolean; const AInvalidSessionID: String) of object;
  //objects
  EIdHTTPServerError = class(EIdException);
  EIdHTTPHeaderAlreadyWritten = class(EIdHTTPServerError);
  EIdHTTPErrorParsingCommand = class(EIdHTTPServerError);
  EIdHTTPUnsupportedAuthorisationScheme = class(EIdHTTPServerError);
  EIdHTTPCannotSwitchSessionStateWhenActive = class(EIdHTTPServerError);

  TIdHTTPRequestInfo = class(TIdRequestHeaderInfo)
  protected
    FAuthExists: Boolean;
    FCookies: TIdServerCookies;
    FParams: TStrings;
    FPostStream: TStream;
    FRawHTTPCommand: string;
    FRemoteIP: string;
    FSession: TIdHTTPSession;
    FDocument: string;
    FCommand: string;
    FVersion: string;
    FAuthUsername: string;
    FAuthPassword: string;
    FUnparsedParams: string;
    FQueryParams: string;
    FFormParams: string;
    //
    procedure DecodeAndSetParams(const AValue: String);
  public
    constructor Create; override;
    destructor Destroy; override;
    property Session: TIdHTTPSession read FSession;
    //
    property AuthExists: Boolean read FAuthExists;
    property AuthPassword: string read FAuthPassword;
    property AuthUsername: string read FAuthUsername;
    property Command: string read FCommand;
    property Cookies: TIdServerCookies read FCookies;
    property Document: string read FDocument write FDocument; // writable for isapi compatibility. Use with care
    property Params: TStrings read FParams;
    property PostStream: TStream read FPostStream write FPostStream;
    property RawHTTPCommand: string read FRawHTTPCommand;
    property RemoteIP: String read FRemoteIP;
    property UnparsedParams: string read FUnparsedParams write FUnparsedParams; // writable for isapi compatibility. Use with care
    property FormParams: string read FFormParams write FFormParams; // writable for isapi compatibility. Use with care
    property QueryParams: string read FQueryParams write FQueryParams; // writable for isapi compatibility. Use with care
    property Version: string read FVersion;
  end;

  TIdHTTPResponseInfo = class(TIdResponseHeaderInfo)
  protected
    FAuthRealm: string;
    FContentType: string;
    FConnection: TIdTCPServerConnection;
    FResponseNo: Integer;
    FCookies: TIdServerCookies;
    FContentStream: TStream;
    FContentText: string;
    FCloseConnection: Boolean;
    FFreeContentStream: Boolean;
    FHeaderHasBeenWritten: Boolean;
    FResponseText: string;
    FSession: TIdHTTPSession;
    //
    procedure ReleaseContentStream;
    procedure SetCookies(const AValue: TIdServerCookies);
    procedure SetHeaders; override;
    procedure SetResponseNo(const AValue: Integer);
    procedure SetCloseConnection(const Value: Boolean);
  public
    procedure CloseSession;
    constructor Create(AConnection: TIdTCPServerConnection); reintroduce;
    destructor Destroy; override;
    procedure Redirect(const AURL: string);
    procedure WriteHeader;
    procedure WriteContent;
    //
    property AuthRealm: string read FAuthRealm write FAuthRealm;
    property CloseConnection: Boolean read FCloseConnection write SetCloseConnection;
    property ContentStream: TStream read FContentStream write FContentStream;
    property ContentText: string read FContentText write FContentText;
    property Cookies: TIdServerCookies read FCookies write SetCookies;
    property FreeContentStream: Boolean read FFreeContentStream write FFreeContentStream;
    // writable for isapi compatibility. Use with care
    property HeaderHasBeenWritten: Boolean read FHeaderHasBeenWritten write FHeaderHasBeenWritten;
    property ResponseNo: Integer read FResponseNo write SetResponseNo;
    property ResponseText: String read FResponseText write FResponseText;
    property ServerSoftware: string read FServer write FServer;
    property Session: TIdHTTPSession read FSession;
  end;
  
  TIdHTTPSession = Class(TObject)
  protected
    FContent: TStrings;
    FLastTimeStamp: TDateTime;
    FLock: TCriticalSection;
    FOwner: TIdHTTPCustomSessionList;
    FSessionID: string;
    FRemoteHost: string;
    //
    procedure SetContent(const Value: TStrings);
    function GetContent: TStrings;
    function IsSessionStale: boolean; virtual;
    procedure DoSessionEnd; virtual;
  public
    constructor Create(AOwner: TIdHTTPCustomSessionList); virtual;
    constructor CreateInitialized(AOwner: TIdHTTPCustomSessionList; const SessionID,
                                  RemoteIP: string); virtual;
    destructor Destroy; override;
    procedure Lock;
    procedure Unlock;
    //
    property Content: TStrings read GetContent write SetContent;
    property LastTimeStamp: TDateTime read FLastTimeStamp;
    property RemoteHost: string read FRemoteHost;
    property SessionID: String read FSessionID;
  end;

  TIdHTTPCustomSessionList = class(TComponent)
  private
    FSessionTimeout: Integer;
    FOnSessionEnd: TOnSessionEndEvent;
    FOnSessionStart: TOnSessionStartEvent;
  protected
    // remove a session from the session list. Called by the session on "Free"
    procedure RemoveSession(Session: TIdHTTPSession); virtual; abstract;
  public
    procedure Clear; virtual; abstract;
    procedure PurgeStaleSessions(PurgeAll: Boolean = false); virtual; abstract;
    function CreateUniqueSession(const RemoteIP: String): TIdHTTPSession; virtual; abstract;
    function CreateSession(const RemoteIP, SessionID: String): TIdHTTPSession; virtual; abstract;
    function GetSession(const SessionID, RemoteIP: string): TIdHTTPSession; virtual; abstract;
    procedure Add(ASession: TIdHTTPSession); virtual; Abstract;
  published
    property SessionTimeout: Integer read FSessionTimeout write FSessionTimeout;
    property OnSessionEnd: TOnSessionEndEvent read FOnSessionEnd write FOnSessionEnd;
    property OnSessionStart: TOnSessionStartEvent read FOnSessionStart write FOnSessionStart;
  end;
  
  TIdCustomHTTPServer = class(TIdTCPServer)
  protected
    FAutoStartSession: Boolean;
    FKeepAlive: Boolean;
    FParseParams: Boolean;
    FServerSoftware: string;
    FMIMETable: TIdMimeTable;
    FSessionList: TIdHTTPCustomSessionList;
    FSessionState: Boolean;
    FSessionTimeOut: Integer;
    FOkToProcessCommand : Boolean; // allow descendents to process requests without requiring FOnCommandGet to be assigned
    FOnCreatePostStream: TOnCreatePostStream;
    FOnCreateSession: TOnCreateSession;
    FOnInvalidSession: TIdHTTPInvalidSessionEvent;
    FOnSessionEnd: TOnSessionEndEvent;
    FOnSessionStart: TOnSessionStartEvent;
    FOnCommandGet: TIdHTTPGetEvent;
    FOnCommandOther: TIdHTTPOtherEvent;
    FSessionCleanupThread: TIdThread;
    //
    procedure DoOnCreateSession(AThread: TIdPeerThread; var VNewSession: TIdHTTPSession); virtual;
    procedure DoInvalidSession(AThread: TIdPeerThread;
     ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo;
     var VContinueProcessing: Boolean; const AInvalidSessionID: String); virtual;
    procedure DoCommandOther(AThread: TIdPeerThread; const asCommand, asData
     , asVersion: string); virtual;
    procedure DoCommandGet(AThread: TIdPeerThread;
     ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo);
     virtual;
    procedure CreatePostStream(ASender: TIdPeerThread; var VPostStream: TStream); virtual;
    procedure DoCreatePostStream(ASender: TIdPeerThread;
     var VPostStream: TStream);
    function DoExecute(AThread: TIdPeerThread): Boolean; override;
    procedure SetActive(AValue: Boolean); override;
    procedure SetSessionState(const Value: Boolean);
    function GetSessionFromCookie(AThread: TIdPeerThread;
     AHTTPrequest: TIdHTTPRequestInfo; AHTTPResponse: TIdHTTPResponseInfo;
     var VContinueProcessing: Boolean): TIdHTTPSession;
    { to be published in TIdHTTPServer}
    property OnCreatePostStream: TOnCreatePostStream read FOnCreatePostStream
     write FOnCreatePostStream;
    property OnCommandGet: TIdHTTPGetEvent read FOnCommandGet
     write FOnCommandGet;
  public
    constructor Create(AOwner: TComponent); Override;
    function CreateSession(AThread: TIdPeerThread;
     HTTPResponse: TIdHTTPResponseInfo;
     HTTPRequest: TIdHTTPRequestInfo): TIdHTTPSession;
    destructor Destroy; override;
    function EndSession(const SessionName: string): boolean;
    function ServeFile(AThread: TIdPeerThread; ResponseInfo: TIdHTTPResponseInfo; aFile: TFileName): cardinal; virtual;
    //
    property MIMETable: TIdMimeTable read FMIMETable;
    property SessionList: TIdHTTPCustomSessionList read FSessionList;
  published
    property AutoStartSession: boolean read FAutoStartSession write FAutoStartSession default Id_TId_HTTPAutoStartSession;
    property DefaultPort default IdPORT_HTTP;
    property OnInvalidSession: TIdHTTPInvalidSessionEvent read FOnInvalidSession
     write FOnInvalidSession;
    property OnSessionStart: TOnSessionStartEvent read FOnSessionStart
     write FOnSessionStart;
    property OnSessionEnd: TOnSessionEndEvent read FOnSessionEnd
     write FOnSessionEnd;
    property OnCreateSession: TOnCreateSession read FOnCreateSession
     write FOnCreateSession;
    property KeepAlive: Boolean read FKeepAlive write FKeepAlive
     default Id_TId_HTTPServer_KeepAlive;
    property ParseParams: boolean read FParseParams write FParseParams
     default Id_TId_HTTPServer_ParseParams;
    property ServerSoftware: string read FServerSoftware write FServerSoftware;
    property SessionState: Boolean read FSessionState write SetSessionState
     default Id_TId_HTTPServer_SessionState;
    property SessionTimeOut: Integer read FSessionTimeOut write FSessionTimeOut
     default Id_TId_HTTPSessionTimeOut;
    property OnCommandOther: TIdHTTPOtherEvent read FOnCommandOther
     write FOnCommandOther;
  end;
  TIdHTTPDefaultSessionList = Class(TIdHTTPCustomSessionList)
  protected
    SessionList: TThreadList;
    procedure RemoveSession(Session: TIdHTTPSession); override;
    // remove a session surgically when list already locked down (prevent deadlock)
    procedure RemoveSessionFromLockedList(AIndex: Integer; ALockedSessionList: TList);
  public
    Constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Clear; override;
    procedure Add(ASession: TIdHTTPSession); override;
    procedure PurgeStaleSessions(PurgeAll: Boolean = false); override;
    function CreateUniqueSession(const RemoteIP: String): TIdHTTPSession; override;
    function CreateSession(const RemoteIP, SessionID: String): TIdHTTPSession; override;
    function GetSession(const SessionID, RemoteIP: string): TIdHTTPSession; override;
  end;

implementation
uses
  IdCoderMIME, IdResourceStrings, IdURI, IdIOHandlerSocket, IdTCPConnection;

const
  SessionCapacity = 128;

  // Calculate the number of MS between two TimeStamps
function TimeStampInterval(StartStamp, EndStamp: TDateTime): integer;
var
  days: Integer;
  hour, min, s, ms: Word;
begin
  days := Trunc(EndStamp - StartStamp); // whole days
  DecodeTime(EndStamp - StartStamp, hour, min, s, ms);
  result := (((days * 24 + hour) * 60 + min) * 60 + s) * 1000 + ms;
end;

function GetRandomString(NumChar: cardinal): string;
const
  CharMap='qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM1234567890';    {Do not Localize}
var
  i: integer;
  MaxChar: cardinal;
begin
  randomize;
  MaxChar := length(CharMap) - 1;
  for i := 1 to NumChar do
  begin
    // Add one because CharMap is 1-based
    Result := result + CharMap[Random(maxChar) + 1];
  end;
end;

type
  TIdHTTPSessionCleanerThread = Class(TIdThread)
  protected
    FSessionList: TIdHTTPCustomSessionList;
  public
    constructor Create(SessionList: TIdHTTPCustomSessionList); reintroduce;
    procedure AfterRun; override;
    procedure Run; override;
  end; // class
  
{ TIdCustomHTTPServer }

constructor TIdCustomHTTPServer.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FSessionState := Id_TId_HTTPServer_SessionState;
  DefaultPort := IdPORT_HTTP;
  ParseParams := Id_TId_HTTPServer_ParseParams;
  FSessionList := TIdHTTPDefaultSessionList.Create(Self);
  FMIMETable := TIdMimeTable.Create(True);
  FSessionTimeOut := Id_TId_HTTPSessionTimeOut;
  FAutoStartSession := Id_TId_HTTPAutoStartSession;
  FKeepAlive := Id_TId_HTTPServer_KeepAlive;
  FOkToProcessCommand := false;
end;

procedure TIdCustomHTTPServer.DoOnCreateSession(AThread: TIdPeerThread; Var VNewSession: TIdHTTPSession);
begin
  VNewSession := nil;
  if Assigned(FOnCreateSession) then
  begin
    OnCreateSession(AThread, VNewSession);
  end;
end;

function TIdCustomHTTPServer.CreateSession(AThread: TIdPeerThread; HTTPResponse: TIdHTTPResponseInfo;
  HTTPRequest: TIdHTTPRequestInfo): TIdHTTPSession;
begin
  if SessionState then begin
    DoOnCreateSession(AThread, Result);
    if not Assigned(result) then
    begin
      result := FSessionList.CreateUniqueSession(HTTPRequest.RemoteIP);
    end
    else begin
      FSessionList.Add(result);
    end;

    with HTTPResponse.Cookies.Add do
    begin
      CookieName := GSessionIDCookie;
      Value := result.SessionID;
      Path := '/';    {Do not Localize}
      MaxAge := -1; // By default the cookies wil be valid until the user has closed his browser window.
      // MaxAge := SessionTimeOut div 1000;
    end;
    HTTPResponse.FSession := result;
    HTTPRequest.FSession := result;
  end else begin
    result := nil;
  end;
end;

destructor TIdCustomHTTPServer.Destroy;
begin
  Active := false; // Set Active to false in order to cloase all active sessions.
  
  FreeAndNil(FMIMETable);
  FreeAndNil(FSessionList);
  inherited Destroy;
end;

procedure TIdCustomHTTPServer.DoCommandGet(AThread: TIdPeerThread;
  ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo);
begin
  if Assigned(FOnCommandGet) then begin
    FOnCommandGet(AThread, ARequestInfo, AResponseInfo);
  end;
end;

procedure TIdCustomHTTPServer.DoCommandOther(AThread: TIdPeerThread;
  const asCommand, asData, asVersion: string);
begin
  if Assigned(FOnCommandOther) then begin
    OnCommandOther(AThread, asCommand, asData, asVersion);
  end;
end;

function TIdCustomHTTPServer.DoExecute(AThread: TIdPeerThread): boolean;
var
  LRequestInfo: TIdHTTPRequestInfo;
  LResponseInfo: TIdHTTPResponseInfo;

  procedure ReadCookiesFromRequestHeader;
  var
    LRawCookies: TStringList;
    i: Integer;
    S: String;
  begin
    LRawCookies := TStringList.Create; try
      LRequestInfo.RawHeaders.Extract('cookie', LRawCookies);    {Do not Localize}
      for i := 0 to LRawCookies.Count -1 do begin
        S := LRawCookies[i];
        while IndyPos(';', S) > 0 do begin    {Do not Localize}
          LRequestInfo.Cookies.AddSrcCookie(Fetch(S, ';'));    {Do not Localize}
          S := Trim(S);
        end;
        if S <> '' then  
          LRequestInfo.Cookies.AddSrcCookie(S);
      end;
    finally LRawCookies.Free; end;
  end;

var
  i: integer;
  s, sInputLine, sCmd, sVersion: String;
  LURI: TIdURI;
  LImplicitPostStream: Boolean;
  LRawHTTPCommand: string;
  ContinueProcessing: Boolean;
  LCloseConnection: Boolean;
begin
  ContinueProcessing := True;
  Result := True;
  LCloseConnection := not KeepAlive;
  try
    try repeat
      with AThread.Connection do begin
        sInputLine := ReadLn;
        LRawHTTPCommand := sInputLine;
        i := idGlobal.RPos(' ', sInputLine, -1);    {Do not Localize}
        if i = 0 then begin
          raise EIdHTTPErrorParsingCommand.Create(RSHTTPErrorParsingCommand);
        end;
        sVersion := Copy(sInputLine, i + 1, MaxInt);
        SetLength(sInputLine, i - 1);
        {TODO Check for 1.0 only at this point}
        sCmd := UpperCase(Fetch(sInputLine, ' '));    {Do not Localize}

        // These essentially all "retrieve" so they are all "Get"s
        if ((sCmd = 'GET') or (sCmd = 'POST')    {Do not Localize}
         or (sCmd = 'HEAD')) and (Assigned(OnCommandGet) or FOkToProcessCommand) then begin    {Do not Localize}
          LRequestInfo := TIdHTTPRequestInfo.Create; try
            LRequestInfo.FRawHTTPCommand := LRawHTTPCommand;
            LRequestInfo.FRemoteIP := (AThread.Connection.IOHandler as TIdIOHandlerSocket).Binding.PeerIP;
            LRequestInfo.FCommand := sCmd;
            // Retrieve the HTTP header
            LRequestInfo.RawHeaders.Clear;
            Capture(LRequestInfo.RawHeaders, '');    {Do not Localize}
            LRequestInfo.ProcessHeaders;
            // Grab Params so we can parse them
            // POSTed data - may exist with GETs also. With GETs, the action
            // params from the form element will be posted
            // TODO: Rune this is the area that needs fixed. Ive hacked it for now
            // Get data can exists with POSTs, but can POST data exist with GETs?
            // If only the first, the solution is easy. If both - need more
            // investigation.

            // i := StrToIntDef(LRequestInfo.Headers.Values['Content-Length'], -1);    {Do not Localize}
            LRequestInfo.PostStream := nil;
            CreatePostStream(AThread, LRequestInfo.FPostStream);
            LImplicitPostStream := LRequestInfo.PostStream = nil;
            try
              if LImplicitPostStream then begin
                LRequestInfo.PostStream := TStringStream.Create('');    {Do not Localize}
              end;
              if LRequestInfo.ContentLength > 0 then begin
                AThread.Connection.ReadStream(LRequestInfo.PostStream
                 , LRequestInfo.ContentLength);
              end else begin
                if sCmd = 'POST' then begin    {Do not Localize}
                  if not LRequestInfo.HasContentLength then
                    AThread.Connection.ReadStream(LRequestInfo.PostStream, -1, True);
                  {LResponseInfo := TIdHTTPResponseInfo.Create(AThread.Connection);
                  try
                    LResponseInfo.SetResponseNo(406);
                    LResponseInfo.WriteHeader;
                    LResponseInfo.WriteContent;
                    raise EIdClosedSocket.Create('');  // Force the server to close the connection and to free all associated resources   
                  finally
                    LResponseInfo.Free;
                  end;
                  {if LowerCase(LRequestInfo.ContentType) = 'application/x-www-form-urlencoded' then begin   
                    S := ReadLn;
                    LRequestInfo.PostStream.Write(S[1], Length(S));
                  end
                  else}
                end;
              end;
              if LRequestInfo.PostStream is TStringStream then begin
                LRequestInfo.FormParams := TStringStream(LRequestInfo.PostStream).DataString;
                LRequestInfo.UnparsedParams := LRequestInfo.FormParams;
              end;
            finally
              if LImplicitPostStream then begin
                FreeAndNil(LRequestInfo.FPostStream);
              end;
            end;
            // GET data - may exist with POSTs also
            LRequestInfo.QueryParams := sInputLine;
            sInputLine := Fetch(LRequestInfo.FQueryParams, '?');    {Do not Localize}
            // glue together parameters passed in the URL and those
            //
            if Length(LRequestInfo.QueryParams) > 0 then begin
              if Length(LRequestInfo.UnparsedParams) = 0 then begin
                LRequestInfo.FUnparsedParams := LRequestInfo.QueryParams;
              end else begin
                LRequestInfo.FUnparsedParams := LRequestInfo.UnparsedParams + '&'  {Do not Localize}
                 + LRequestInfo.QueryParams;
              end;
            end;
            // Parse Params
            if ParseParams then begin
              if (LowerCase(LRequestInfo.ContentType) = 'application/x-www-form-urlencoded') then begin    {Do not Localize}
                LRequestInfo.DecodeAndSetParams(LRequestInfo.UnparsedParams);
              end
              else begin
                // Parse only query params when content type is not 'application/x-www-form-urlencoded'    {Do not Localize}
                LRequestInfo.DecodeAndSetParams(LRequestInfo.QueryParams);
              end;
            end;
            // Cookies
            ReadCookiesFromRequestHeader;
            // Host
            // LRequestInfo.FHost := LRequestInfo.Headers.Values['host'];    {Do not Localize}
            LRequestInfo.FVersion := sVersion;
            // Parse the document input line
            if sInputLine = '*' then begin    {Do not Localize}
              LRequestInfo.FDocument := '*';    {Do not Localize}
            end else begin
              LURI := TIdURI.Create(sInputLine);
              // SG 29/11/01: Per request of Doychin
              // Try to fill the "host" parameter
              LRequestInfo.FDocument := TIdURI.URLDecode(LURI.Path) + TIdURI.URLDecode(LURI.Document) + LURI.Params;
              if (Length(LURI.Host) > 0) and (Length(LRequestInfo.FHost) = 0) then begin
                LRequestInfo.FHost := LURI.Host;
              end;
              LURI.Free;
            end;

            s := LRequestInfo.RawHeaders.Values['Authorization'];    {Do not Localize}
            LRequestInfo.FAuthExists := Length(s) > 0;
            if LRequestInfo.AuthExists then begin
              if AnsiCompareText(Fetch(s, ' '), 'Basic') = 0 then begin    {Do not Localize}
                s := TIdDecoderMIME.DecodeString(s);
                LRequestInfo.FAuthUsername := Fetch(s, ':');    {Do not Localize}
                LRequestInfo.FAuthPassword := s;
              end else begin
                raise EIdHTTPUnsupportedAuthorisationScheme.Create(
                 RSHTTPUnsupportedAuthorisationScheme);
              end;
            end;
            LResponseInfo := TIdHTTPResponseInfo.Create(AThread.Connection); try
              LResponseInfo.CloseConnection := not (FKeepAlive and
                AnsiSameText(LRequestInfo.Connection, 'Keep-alive')); {Do not Localize}
              // Session management
              GetSessionFromCookie(AThread, LRequestInfo, LResponseInfo
               , ContinueProcessing);
              // SG 05.07.99
              // Set the ServerSoftware string to what it's supposed to be.    {Do not Localize}
              if Length(Trim(ServerSoftware)) > 0  then begin
                LResponseInfo.ServerSoftware := ServerSoftware;
              end;
              try
                if ContinueProcessing then begin
                  DoCommandGet(AThread, LRequestInfo, LResponseInfo);
                end;
              except
                on E: EIdSocketError do begin
                  raise;
                end;
                on E: Exception do begin
                  LResponseInfo.ResponseNo := 500;
                  LResponseInfo.ContentText := E.Message;
                end;
              end;
              // Write even though WriteContent will, may be a redirect or other
              if not LResponseInfo.HeaderHasBeenWritten then begin
                LResponseInfo.WriteHeader;
              end;
              // Always check ContentText first
              if (Length(LResponseInfo.ContentText) > 0)
               or Assigned(LResponseInfo.ContentStream) then begin
                LResponseInfo.WriteContent;
              end;
            finally
              LCloseConnection := LResponseInfo.CloseConnection;
              FreeAndNil(LResponseInfo);
            end;
          finally FreeAndNil(LRequestInfo); end;
        end else begin
          DoCommandOther(AThread, sCmd, sInputLine, sVersion);
        end;
      end;
    until LCloseConnection;
    except
      on E: EIdSocketError do begin
        if E.LastError <> Id_WSAECONNRESET then raise;
      end;
      on E: EIdClosedSocket do
        AThread.Connection.Disconnect;
    end;
  finally AThread.Connection.Disconnect; end;
end;

procedure TIdCustomHTTPServer.DoInvalidSession(AThread: TIdPeerThread;
  ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo;
  var VContinueProcessing: Boolean; const AInvalidSessionID: String);
begin
  if Assigned(FOnInvalidSession) then begin
    FOnInvalidSession(AThread, ARequestInfo, AResponseInfo, VContinueProcessing, AInvalidSessionID)
  end;
end;

function TIdCustomHTTPServer.EndSession(const SessionName: string): boolean;
var
  ASession: TIdHTTPSession;
begin
  ASession := SessionList.GetSession(SessionName, '');    {Do not Localize}
  result := Assigned(ASession);
  if result then
  begin
    ASession.free;
  end;
end;

function TIdCustomHTTPServer.GetSessionFromCookie(AThread: TIdPeerThread;
  AHTTPRequest: TIdHTTPRequestInfo; AHTTPResponse: TIdHTTPResponseInfo;
  var VContinueProcessing: Boolean): TIdHTTPSession;
var
  CurrentCookieIndex: Integer;
  SessionId: String;
begin
  Result := nil;
  VContinueProcessing := True;
  if SessionState then
  begin
    CurrentCookieIndex := AHTTPRequest.Cookies.GetCookieIndex(0, GSessionIDCookie);
    while (result = nil) and (CurrentCookieIndex >= 0) do
    begin
      SessionId := AHTTPRequest.Cookies.Items[CurrentCookieIndex].Value;
      Result := FSessionList.GetSession(SessionID, AHTTPrequest.RemoteIP);
      if not Assigned(Result) then
        DoInvalidSession(AThread, AHTTPRequest, AHTTPResponse, VContinueProcessing, SessionId);
      Inc(CurrentCookieIndex);
      CurrentCookieIndex := AHTTPRequest.Cookies.GetCookieIndex(CurrentCookieIndex, GSessionIDCookie);
    end;    { while }
    // check if a session was returned. If not and if AutoStartSession is set to
    // true, Create a new session
    if (FAutoStartSession and VContinueProcessing) and (result = nil) then
    begin
      Result := CreateSession(AThread, AHTTPResponse, AHTTPrequest);
    end;
  end;
  AHTTPRequest.FSession := result;
  AHTTPResponse.FSession := result;
end;

function TIdCustomHTTPServer.ServeFile(AThread: TIdPeerThread; ResponseInfo: TIdHTTPResponseInfo;
 AFile: TFileName): Cardinal;
begin
  if Length(ResponseInfo.ContentType) = 0 then begin
    ResponseInfo.ContentType := MIMETable.GetFileMIMEType(aFile);
  end;
  ResponseInfo.ContentLength := FileSizeByName(aFile);
  ResponseInfo.WriteHeader;
  //TODO: allow TransferFileEnabled function
  result := aThread.Connection.WriteFile(aFile);
end;

procedure TIdCustomHTTPServer.SetActive(AValue: Boolean);
begin
  if (not (csDesigning in ComponentState)) and (FActive <> AValue)
      and (not (csLoading in ComponentState)) then begin
    if AValue then
    begin
      // starting server
      // set the session timeout and options
      if FSessionTimeOut <> 0 then
        FSessionList.FSessionTimeout := FSessionTimeOut
      else
        FSessionState := false;
      // Session events
      FSessionList.OnSessionStart := FOnSessionStart;
      FSessionList.OnSessionEnd := FOnSessionEnd;
      // If session handeling is enabled, create the housekeeper thread
      if SessionState then
        FSessionCleanupThread := TIdHTTPSessionCleanerThread.Create(FSessionList);
    end
    else
    begin
      // Stopping server
      // Boost the clear thread priority to give it a good chance to terminate
      if assigned(FSessionCleanupThread) then begin
        SetThreadPriority(FSessionCleanupThread, tpNormal);
        FSessionCleanupThread.TerminateAndWaitFor;
        FreeAndNil(FSessionCleanupThread);
      end;
      FSessionCleanupThread := nil;
      FSessionList.Clear;
    end;
  end;
  inherited;
end;

procedure TIdCustomHTTPServer.SetSessionState(const Value: Boolean);
begin
  // ToDo: Add thread multiwrite protection here
  if (not ((csDesigning in ComponentState) or (csLoading in ComponentState))) and Active then
    raise EIdHTTPCannotSwitchSessionStateWhenActive.Create(RSHTTPCannotSwitchSessionStateWhenActive);
  FSessionState := Value;
end;

procedure TIdCustomHTTPServer.DoCreatePostStream(ASender: TIdPeerThread;
  var VPostStream: TStream);
begin
  if Assigned(OnCreatePostStream) then begin
    OnCreatePostStream(ASender, VPostStream);
  end;
end;

procedure TIdCustomHTTPServer.CreatePostStream(ASender: TIdPeerThread;
  var VPostStream: TStream);
begin
  DoCreatePostStream(ASender, VPostStream);
end;

{ TIdHTTPSession }

constructor TIdHTTPSession.Create(AOwner: TIdHTTPCustomSessionList);
begin
  inherited Create;

  FLock := TCriticalSection.Create;
  FContent := TStringList.Create;
  FOwner := AOwner;
  if assigned( AOwner ) then
  begin
    if assigned(AOwner.OnSessionStart) then
    begin
      AOwner.OnSessionStart(self);
    end;
  end;
end;

{TIdSession}
constructor TIdHTTPSession.CreateInitialized(AOwner: TIdHTTPCustomSessionList; const SessionID, RemoteIP: string);
begin
  inherited Create;

  FSessionID := SessionID;
  FRemoteHost := RemoteIP;
  FLastTimeStamp := Now;
  FLock := TCriticalSection.Create;
  FContent := TStringList.Create;
  FOwner := AOwner;
  if assigned( AOwner ) then
  begin
    if assigned(AOwner.OnSessionStart) then
    begin
      AOwner.OnSessionStart(self);
    end;
  end;
end;

destructor TIdHTTPSession.Destroy;
begin
// code added here should also be reflected in
// the TIdHTTPDefaultSessionList.RemoveSessionFromLockedList method
// Why? It calls this function and this code gets executed?
  DoSessionEnd;
  FContent.Free;
  FLock.Free;
  if Assigned(FOwner) then begin
    FOwner.RemoveSession(self);
  end;
  inherited;
end;

procedure TIdHTTPSession.DoSessionEnd;
begin
  if assigned(FOwner) and assigned(FOwner.FOnSessionEnd) then
    FOwner.FOnSessionEnd(self);
end;

function TIdHTTPSession.GetContent: TStrings;
begin
  result := FContent;
end;

function TIdHTTPSession.IsSessionStale: boolean;
begin
  result := TimeStampInterval(FLastTimeStamp, Now) > Integer(FOwner.SessionTimeout);
end;

procedure TIdHTTPSession.Lock;
begin
  // ToDo: Add session locking code here
  FLock.Enter;
end;

procedure TIdHTTPSession.SetContent(const Value: TStrings);
begin
  FContent.Assign(Value);
end;

procedure TIdHTTPSession.Unlock;
begin
  // ToDo: Add session unlocking code here
  FLock.Leave;
end;

{ TIdHTTPRequestInfo }

constructor TIdHTTPRequestInfo.Create;
begin       
  inherited;
  FCookies := TIdServerCookies.Create(self);
  FParams  := TStringList.Create;
  ContentLength := -1;
end;

procedure TIdHTTPRequestInfo.DecodeAndSetParams(const AValue: String);
var
  p, p2: PChar;
  s: string;
begin
  // Convert special characters
  // ampersand '&' separates values    {Do not Localize}
  Params.BeginUpdate; try
    Params.Clear;
    p := PChar(AValue);
    p2 := p;
    while (p2 <> nil) and (p2[0] <> #0) do begin
      p2 := StrScan(p, '&');    {Do not Localize}
      if p2 = nil then begin
        p2 := StrEnd(p);
      end;
      SetString(s, p, p2 - p);
      // See RFC 1866 section 8.2.1. TP
      s := StringReplace(s, '+', ' ', [rfReplaceAll]);  {do not localize}
      Params.Add(TIdURI.URLDecode(s));
      p := p2 + 1;
    end;
  finally Params.EndUpdate; end;
end;

destructor TIdHTTPRequestInfo.Destroy;
begin
  FreeAndNil(FCookies);
  FreeAndNil(FParams);
  FreeAndNil(FPostStream);
  inherited;
end;

{ TIdHTTPResponseInfo }

procedure TIdHTTPResponseInfo.CloseSession;
var
  i: Integer;
begin
  i := Cookies.GetCookieIndex(0, GSessionIDCookie);
  if i > -1 then begin
    Cookies.Delete(i);
  end;
  Cookies.Add.CookieName := GSessionIDCookie;
  FreeAndNil(FSession);
end;

constructor TIdHTTPResponseInfo.Create(AConnection: TIdTCPServerConnection);
begin
  inherited Create;

  FFreeContentStream := True;
  ContentLength := GFContentLength;
  {Some clients may not support folded lines}
  RawHeaders.FoldLines := False;
  FCookies := TIdServerCookies.Create(self);
  {TODO Specify version - add a class method dummy that calls version}
  ServerSoftware := GServerSoftware;
  ContentType := GContentType;

  FConnection := AConnection;
  ResponseNo := GResponseNo;
end;

destructor TIdHTTPResponseInfo.Destroy;
begin
  FreeAndNil(FCookies);
  ReleaseContentStream;
  inherited Destroy;
end;

procedure TIdHTTPResponseInfo.Redirect(const AURL: string);
begin
  ResponseNo := 302;
  Location := AURL;
end;

procedure TIdHTTPResponseInfo.ReleaseContentStream;
begin
  if FreeContentStream then begin
    FreeAndNil(FContentStream);
  end else begin
    FContentStream := nil;
  end;
end;

procedure TIdHTTPResponseInfo.SetCloseConnection(const Value: Boolean);
begin
  Connection := iif(Value, 'close', 'keep-alive');    {Do not Localize}
  FCloseConnection := Value;
end;

procedure TIdHTTPResponseInfo.SetCookies(const AValue: TIdServerCookies);
begin
  FCookies.Assign(AValue);
end;

procedure TIdHTTPResponseInfo.SetHeaders;
begin
  inherited SetHeaders;

  with RawHeaders do
  begin
    if Server <> '' then
      Values['Server'] := Server;    {Do not Localize}
    if ContentType <> '' then
      Values['Content-Type'] := ContentType;    {Do not Localize}
    if Location <> '' then
    begin
      Values['Location'] := Location;    {Do not Localize}
    end;
    if ContentLength > -1 then
    begin
      Values['Content-Length'] := IntToStr(ContentLength);    {Do not Localize}
    end;
    if FLastModified > 0 then
    begin
      Values['Last-Modified'] := DateTimeGMTToHttpStr(FLastModified); { do not localize}
    end;

    if AuthRealm <> '' then {Do not Localize}
    begin
      ResponseNo := 401;
      Values['WWW-Authenticate'] := 'Basic realm="' + AuthRealm + '"';    {Do not Localize}
      if ContentLength = -1 then begin
        FContentText := '<HTML><BODY><B>' + IntToStr(ResponseNo) + ' ' + RSHTTPUnauthorized + '</B></BODY></HTML>';    {Do not Localize}
        ContentLength := Length(FContentText);
      end;
    end;
  end;
end;

procedure TIdHTTPResponseInfo.SetResponseNo(const AValue: Integer);
begin
  FResponseNo := AValue;
  case FResponseNo of
    100: ResponseText := RSHTTPContinue;
    // 2XX: Success
    200: ResponseText := RSHTTPOK;
    201: ResponseText := RSHTTPCreated;
    202: ResponseText := RSHTTPAccepted;
    203: ResponseText := RSHTTPNonAuthoritativeInformation;
    204: ResponseText := RSHTTPNoContent;
    205: ResponseText := RSHTTPResetContent;
    206: ResponseText := RSHTTPPartialContent;
    // 3XX: Redirections
    301: ResponseText := RSHTTPMovedPermanently;
    302: ResponseText := RSHTTPMovedTemporarily;
    303: ResponseText := RSHTTPSeeOther;
    304: ResponseText := RSHTTPNotModified;
    305: ResponseText := RSHTTPUseProxy;
    // 4XX Client Errors
    400: ResponseText := RSHTTPBadRequest;
    401: ResponseText := RSHTTPUnauthorized;
    403: ResponseText := RSHTTPForbidden;
    404: begin
      ResponseText := RSHTTPNotFound;
      // Close connection
      CloseConnection := true;
    end;
    405: ResponseText := RSHTTPMethodeNotAllowed;
    406: ResponseText := RSHTTPNotAcceptable;
    407: ResponseText := RSHTTPProxyAuthenticationRequired;
    408: ResponseText := RSHTTPRequestTimeout;
    409: ResponseText := RSHTTPConflict;
    410: ResponseText := RSHTTPGone;
    411: ResponseText := RSHTTPLengthRequired;
    412: ResponseText := RSHTTPPreconditionFailed;
    413: ResponseText := RSHTTPRequestEntityToLong;
    414: ResponseText := RSHTTPRequestURITooLong;
    415: ResponseText := RSHTTPUnsupportedMediaType;
    // 5XX Server errors
    500: ResponseText := RSHTTPInternalServerError;
    501: ResponseText := RSHTTPNotImplemented;
    502: ResponseText := RSHTTPBadGateway;
    503: ResponseText := RSHTTPServiceUnavailable;
    504: ResponseText := RSHTTPGatewayTimeout;
    505: ResponseText := RSHTTPHTTPVersionNotSupported;
    else
      ResponseText := RSHTTPUnknownResponseCode;
  end;

  {if ResponseNo >= 400 then
    // Force COnnection closing when there is error during the request processing
    CloseConnection := true;
  end;}
end;

procedure TIdHTTPResponseInfo.WriteContent;
begin
  if not HeaderHasBeenWritten then begin
    WriteHeader;
  end;
  with FConnection do begin
    if Assigned(ContentStream) then begin
      WriteStream(ContentStream);
    end else if ContentText <> '' then begin
      Write(ContentText);
    end else begin
      FConnection.WriteLn('<HTML><BODY><B>' + IntToStr(ResponseNo) + ' ' + ResponseText    {Do not Localize}
       + '</B></BODY></HTML>');    {Do not Localize}
    end;
    // Clear All - This signifies that WriteConent has been called.
    ContentText := '';    {Do not Localize}
    ReleaseContentStream;
  end;
end;

procedure TIdHTTPResponseInfo.WriteHeader;
var
  i: Integer;
begin
  if HeaderHasBeenWritten then begin
    raise EIdHTTPHeaderAlreadyWritten.Create(RSHTTPHeaderAlreadyWritten);
  end;
  FHeaderHasBeenWritten := True;

  if ContentLength = -1 then
  begin
    if Length(ContentText) > 0 then
    begin
      ContentLength := Length(ContentText)
    end
    else
      if Assigned(ContentStream) then
      begin
        ContentLength := ContentStream.Size;
      end;
  end;

  SetHeaders;

  with FConnection do
  begin
    OpenWriteBuffer; try
      // Write HTTP status response
      // Client will be forced to close the connection. We are not going to support
      // keep-alive feature for now
      WriteLn('HTTP/1.1 ' + IntToStr(ResponseNo) + ' ' + ResponseText);    {Do not Localize}
      // Write headers
      for i := 0 to RawHeaders.Count -1 do begin
        WriteLn(RawHeaders[i]);
      end;
      // Write cookies
      for i := 0 to Cookies.Count - 1 do begin
        WriteLn('Set-Cookie: ' + Cookies[i].ServerCookie);    {Do not Localize}
      end;
      // HTTP headers ends with a double CR+LF
      WriteLn;
    finally CloseWriteBuffer; end;
  end;
end;

{ TIdHTTPDefaultSessionList }

procedure TIdHTTPDefaultSessionList.Add(ASession: TIdHTTPSession);
begin
  SessionList.Add(ASession);
end;

procedure TIdHTTPDefaultSessionList.Clear;
var
  ASessionList: TList;
  i: Integer;
begin
  ASessionList := SessionList.LockList;
  try
    for i := ASessionList.Count - 1 DownTo 0 do
      if ASessionList[i] <> nil then
      begin
        TIdHTTPSession(ASessionList[i]).DoSessionEnd;
        TIdHTTPSession(ASessionList[i]).FOwner := nil;
        TIdHTTPSession(ASessionList[i]).Free;
      end;
    ASessionList.Clear;
    ASessionList.Capacity := SessionCapacity;
  finally
    SessionList.UnlockList;
  end;
end;

constructor TIdHTTPDefaultSessionList.Create(AOwner: TComponent);
begin
  inherited;

  SessionList := TThreadList.Create;
  SessionList.LockList.Capacity := SessionCapacity;
  SessionList.UnlockList;
end;

function TIdHTTPDefaultSessionList.CreateSession(const RemoteIP, SessionID: String): TIdHTTPSession;
begin
  result := TIdHTTPSession.CreateInitialized(Self, SessionID, RemoteIP);
  SessionList.Add(result);
end;

function TIdHTTPDefaultSessionList.CreateUniqueSession(
  const RemoteIP: String): TIdHTTPSession;
var
  SessionID: String;
begin
  SessionID := GetRandomString(15);
  while GetSession(SessionID, RemoteIP) <> nil do
  begin
    SessionID := GetRandomString(15);
  end;    // while
  result := CreateSession(RemoteIP, SessionID);
end;

destructor TIdHTTPDefaultSessionList.destroy;
begin
  Clear;
  SessionList.free;
  inherited;
end;

function TIdHTTPDefaultSessionList.GetSession(const SessionID, RemoteIP: string): TIdHTTPSession;
var
  ASessionList: TList;
  i: Integer;
  ASession: TIdHTTPSession;
begin
  Result := nil;
  ASessionList := SessionList.LockList;
  try
    // get current time stamp
    for i := 0 to ASessionList.Count - 1 do
    begin
      ASession := TIdHTTPSession(ASessionList[i]);
      Assert(ASession <> nil);
      // the stale sessions check has been removed... the cleanup thread should suffice plenty
      if AnsiSameText(ASession.FSessionID, SessionID) and ((length(RemoteIP) = 0) or AnsiSameText(ASession.RemoteHost, RemoteIP)) then
      begin
        // Session found
        ASession.FLastTimeStamp := Now;
        result := ASession;
        break;
      end;
    end;
  finally
    SessionList.UnlockList;
  end;
end;

procedure TIdHTTPDefaultSessionList.PurgeStaleSessions(PurgeAll: Boolean = false);
var
  i: Integer;
  aSessionList: TList;
begin
  // S.G. 24/11/00: Added a way to force a session purge (Used when thread is terminated)
  // Get necessary data
  aSessionList := SessionList.LockList;
  try
    // Loop though the sessions.
    for i := aSessionList.Count - 1 downto 0 do
    begin
      // Identify the stale sessions
      if Assigned(ASessionList[i]) and
         (PurgeAll or TIdHTTPSession(aSessionList[i]).IsSessionStale) then
      begin
        RemoveSessionFromLockedList(i, aSessionList);
      end;
    end;
  finally
    SessionList.UnlockList;
  end;
end;

procedure TIdHTTPDefaultSessionList.RemoveSession(Session: TIdHTTPSession);
var
  ASessionList: TList;
  Index: integer;
begin
  ASessionList := SessionList.LockList;
  try
    Index := ASessionList.IndexOf(TObject(Session));
    if index > -1 then
    begin
      ASessionList.Delete(index);
    end;
  finally
    SessionList.UnlockList;
  end;
end;

procedure TIdHTTPDefaultSessionList.RemoveSessionFromLockedList(AIndex: Integer;
  ALockedSessionList: TList);
begin
  TIdHTTPSession(ALockedSessionList[AIndex]).DoSessionEnd;
  // must set the owner to nil or the session will try to remove itself from the
  // session list and deadlock
  TIdHTTPSession(ALockedSessionList[AIndex]).FOwner := nil;
  TIdHTTPSession(ALockedSessionList[AIndex]).Free;
  ALockedSessionList.Delete(AIndex);
end;

{ TIdHTTPSessionClearThread }

procedure TIdHTTPSessionCleanerThread.AfterRun;
begin
  if Assigned(FSessionList) then
    FSessionList.PurgeStaleSessions(true);
  inherited AfterRun;
end;

constructor TIdHTTPSessionCleanerThread.Create(SessionList: TIdHTTPCustomSessionList);
begin
  inherited Create(false);
  SetThreadPriority(Self, tpIdle); // Set priority to the lowest possible
  FSessionList := SessionList;
  FreeOnTerminate := False;
end;

procedure TIdHTTPSessionCleanerThread.Run;
begin
  Sleep(1000);
  if Assigned(FSessionList) then begin
    FSessionList.PurgeStaleSessions(Terminated);
  end;
end;

end.
