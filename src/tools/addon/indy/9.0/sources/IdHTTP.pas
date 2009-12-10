{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10191: IdHTTP.pas 
{
{   Rev 1.3    4/30/2003 01:21:30 PM  JPMugaas
{ Added ConnectTimeout property because ReadTimeout is problematic in HTTP with
{ Connect.  Discussed that with Kudzu this morning.
}
{
{   Rev 1.2    06.3.2003 ã. 20:07:02  DBondzhev
}
{
{   Rev 1.1    01.2.2003 ã. 11:54:28  DBondzhev
}
{
{   Rev 1.0    2002.11.12 10:41:00 PM  czhower
}
unit IdHTTP;

{
  Implementation of the HTTP protcol as specified in RFC 2616, 2109, 2965.
  (See NOTE below for details of what is exactly implemented)

  Author: Hadi Hariri (hadi@urusoft.com)
  Copyright: (c) Chad Z. Hower and The Winshoes Working Group.

NOTE:
  Initially only GET and POST will be supported. As time goes on more will
  be added. For other developers, please add the date and what you have done
  below.

Initials: Hadi Hariri - HH

Details of implementation
-------------------------
2001-Nov Nick Panteleeff
 - Authentication and POST parameter extentsions
2001-Sept Doychin Bondzhev
 - New internal design and new Authentication procedures.
 - Bug fixes and new features in few other supporting components
2001-Jul-7 Doychin Bondzhev
 - new property AllowCookie
 - There is no more ExtraHeders property in Request/Response. Raw headers is used for that purpose.
2001-Jul-1 Doychin Bondzhev
 - SSL support is up again - Thanks to Gregor
2001-Jun-17 Doychin Bondzhev
 - New unit IdHTTPHeaderInfo.pas that contains the
   TIdHeaderInfo(TIdEntytiHeaderInfo, TIdRequestHeaderInfo and TIdResponseHeaderInfo)
 - Still in development and not verry well tested
   By default when there is no authorization object associated with HTTP compoenet and there is user name and password
   HTTP component creates and instance of TIdBasicAuthentication class. This behaivor is for both web server and proxy server
   authorizations
2001-Apr-17 Doychin Bondzhev
 - Added OnProxyAuthorization event. This event is called on 407 response from the HTTP Proxy.
 - Added 2 new properties in TIdHeaderInfo
    property AuthenticationScheme: TIdAuthenticationScheme - this property contains information for authentication scheme
      requested by the web server
    property ProxyAuthenticationScheme: TIdAuthenticationScheme - this property contains information for authentication scheme
      requested by the proxy server
 - Now the component authomaticly reconginizes the requested authorization scheme and it supports Basic like before and has been
   extend to support Digest authorization
2001-Mar-31 Doychin Bondzhev
 - If there is no CookieManager it does not support cookies.
2001-Feb-18 Doychin Bondzhev
 - Added OnAuthorization event. This event is called on 401 response from the HTTP server.
     This can be used to ask the user program to supply user name and password in order to acces
     the requested resource
2001-Feb-02 Doychin Bondzhev
 - Added Cookie support and relative paths on redirect
2000-Jul-25 Hadi Hariri
 - Overloaded POst and moved clearing to disconect.
2000-June-22 Hadi Hariri
  - Added Proxy support.
2000-June-10 Hadi Hariri
  - Added Chunk-Encoding support and HTTP version number. Some additional
    improvements.
2000-May-23 J. Peter Mugaas
  -added redirect capability and supporting properties.  Redirect is optional
   and is set with HandleRedirects.  Redirection is limited to RedirectMaximum
   to prevent stack overflow due to recursion and to prevent redirects between
   two places which would cause this to go on to infinity.
2000-May-22 J. Peter Mugaas
  -adjusted code for servers which returned LF instead of EOL
  -Headers are now retreived before an exception is raised.  This
   also facilitates server redirection where the server tells the client to
   get a document from another location.
2000-May-01 Hadi Hariri
  -Converted to Mercury
2000-May-01 Hadi Hariri
  -Added PostFromStream and some clean up
2000-Apr-10 Hadi Hariri
  -Re-done quite a few things and fixed GET bugs and finished POST method.
2000-Jan-13 MTL
  -Moved to the New Palette Scheme
2000-Jan-08 MTL
  -Cleaned up a few compiler hints during 7.038 build
1999-Dec-10 Hadi Hariri
  -Started.
}

interface

uses
  Classes,
  IdException, IdAssignedNumbers, IdHeaderList, IdHTTPHeaderInfo, IdSSLOpenSSL,
  IdTCPConnection,
  IdTCPClient, IdURI, IdCookie, IdCookieManager, IdAuthentication , IdAuthenticationManager,
  IdMultipartFormData;

type
  // TO DOCUMENTATION TEAM
  // ------------------------
  // For internal use. No need of documentation
  // hmConnect - Used to connect trought CERN proxy to SSL enabled sites.
  TIdHTTPMethod = (hmHead, hmGet, hmPost, hmOptions, hmTrace, hmPut, hmDelete, hmConnect);
  TIdHTTPWhatsNext = (wnGoToURL, wnJustExit, wnDontKnow, wnReadAndGo, wnAuthRequest);
  TIdHTTPConnectionType = (ctNormal, ctSSL, ctProxy, ctSSLProxy);

  // Protocol options
  TIdHTTPOption = (hoInProcessAuth, hoKeepOrigProtocol, hoForceEncodeParams);
  TIdHTTPOptions = set of TIdHTTPOption;

  // Must be documented
  TIdHTTPProtocolVersion = (pv1_0, pv1_1);

  TIdHTTPOnRedirectEvent = procedure(Sender: TObject; var dest: string; var NumRedirect: Integer; var Handled: boolean; var VMethod: TIdHTTPMethod) of object;
  TIdOnSelectAuthorization = procedure(Sender: TObject; var AuthenticationClass: TIdAuthenticationClass; AuthInfo: TIdHeaderList) of object;
  TIdOnAuthorization = procedure(Sender: TObject; Authentication: TIdAuthentication; var Handled: boolean) of object;
  // TIdProxyOnAuthorization = procedure(Sender: TObject; Authentication: TIdAuthentication; var Handled: boolean) of object;

const
  Id_TIdHTTP_ProtocolVersion = pv1_1;
  Id_TIdHTTP_RedirectMax = 15;
  Id_TIdHTTP_HandleRedirects = False;

type
  TIdCustomHTTP = class;

  // TO DOCUMENTATION TEAM
  // ------------------------
  // The following classes are used internally and no need of documentation
  // Only TIdHTTP must be documented
  //
  TIdHTTPResponse = class(TIdResponseHeaderInfo)
  protected
    FHTTP: TIdCustomHTTP;
    FResponseCode: Integer;
    FResponseText: string;
    FKeepAlive: Boolean;
    FContentStream: TStream;
    FResponseVersion: TIdHTTPProtocolVersion;
    //
    function GetKeepAlive: Boolean;
    function GetResponseCode: Integer;
  public
    constructor Create(AParent: TIdCustomHTTP); reintroduce; virtual;
    property KeepAlive: Boolean read GetKeepAlive write FKeepAlive;
    property ResponseText: string read FResponseText write FResponseText;
    property ResponseCode: Integer read GetResponseCode write FResponseCode;
    property ResponseVersion: TIdHTTPProtocolVersion read FResponseVersion write FResponseVersion;
    property ContentStream: TStream read FContentStream write FContentStream;
  end;

  TIdHTTPRequest = class(TIdRequestHeaderInfo)
  protected
    FHTTP: TIdCustomHTTP;
    FURL: string;
    FMethod: TIdHTTPMethod;
    FSourceStream: TStream;
    FUseProxy: TIdHTTPConnectionType;
  public
    constructor Create(AHTTP: TIdCustomHTTP); reintroduce; virtual;
    property URL: string read FURL write FURL;
    property Method: TIdHTTPMethod read FMethod write FMethod;
    property Source: TStream read FSourceStream write FSourceStream;
    property UseProxy: TIdHTTPConnectionType read FUseProxy;
  end;

  TIdHTTPProtocol = class(TObject)
    FHTTP: TIdCustomHTTP;
    FRequest: TIdHTTPRequest;
    FResponse: TIdHTTPResponse;
  public
    constructor Create(AConnection: TIdCustomHTTP);
    destructor Destroy; override;
    function ProcessResponse: TIdHTTPWhatsNext;
    procedure BuildAndSendRequest(AURI: TIdURI);
    procedure RetrieveHeaders;

    property Request: TIdHTTPRequest read FRequest;
    property Response: TIdHTTPResponse read FResponse;
  end;

  TIdCustomHTTP = class(TIdTCPClient)
  protected
    FCookieManager: TIdCookieManager;
    FFreeOnDestroy: Boolean;
    {Max retries for authorization}
    FMaxAuthRetries: Integer;
    FAllowCookies: Boolean;
    FAuthenticationManager: TIdAuthenticationManager;
    FProtocolVersion: TIdHTTPProtocolVersion;

    {this is an internal counter for redirercts}
    FRedirectCount: Integer;
    FRedirectMax: Integer;
    FHandleRedirects: Boolean;
    FOptions: TIdHTTPOptions;
    FURI: TIdURI;
    FHTTPProto: TIdHTTPProtocol;
    FProxyParameters: TIdProxyConnectionInfo;
    //
    FOnRedirect: TIdHTTPOnRedirectEvent;
    FOnSelectAuthorization: TIdOnSelectAuthorization;
    FOnSelectProxyAuthorization: TIdOnSelectAuthorization;
    FOnAuthorization: TIdOnAuthorization;
    FOnProxyAuthorization: TIdOnAuthorization;

    FConnectTimeout : Integer;
    //
    procedure SetHost(const Value: string); override;
    procedure SetPort(const Value: integer); override;
    procedure SetAuthenticationManager(const Value: TIdAuthenticationManager);
    procedure SetCookieManager(ACookieManager: TIdCookieManager);
    procedure SetAllowCookies(AValue: Boolean);
    function GetResponseCode: Integer;
    function GetResponseText: string;
    function DoOnAuthorization(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse): Boolean; virtual;
    function DoOnProxyAuthorization(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse): Boolean; virtual;
    function DoOnRedirect(var Location: string; var VMethod: TIdHTTPMethod; RedirectCount: integer): boolean; virtual;
    procedure Notification(AComponent: TComponent; Operation: TOperation); override;
    procedure ProcessCookies(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse);
    function SetHostAndPort: TIdHTTPConnectionType;
    procedure SetCookies(AURL: TIdURI; ARequest: TIdHTTPRequest);
    procedure ReadResult(AResponse: TIdHTTPResponse);
    procedure PrepareRequest(ARequest: TIdHTTPRequest);
    procedure ConnectToHost(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse);
    function GetResponseHeaders: TIdHTTPResponse;
    function GetRequestHeaders: TIdHTTPRequest;
    procedure SetRequestHeaders(const Value: TIdHTTPRequest);

    procedure EncodeRequestParams(const AStrings: TStrings);
    function SetRequestParams(const AStrings: TStrings): string;

    procedure CheckAndConnect(AResponse: TIdHTTPResponse);
    procedure DoOnDisconnected; override;
    function GetAuthRetries: Integer;
    function GetProxyAuthRetries: Integer;

    property InternalAuthRetries: Integer read GetAuthRetries;
    property InternalProxyAuthRetries: Integer read GetProxyAuthRetries;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure DoRequest(const AMethod: TIdHTTPMethod; AURL: string;
      const ASource, AResponseContent: TStream); virtual;
    procedure Options(AURL: string); overload;
    procedure Get(AURL: string; const AResponseContent: TStream); overload;
    function Get(AURL: string): string; overload;
    procedure Trace(AURL: string; const AResponseContent: TStream); overload;
    function Trace(AURL: string): string; overload;
    procedure Head(AURL: string);

    function Post(AURL: string; const ASource: TStrings): string; overload;
    function Post(AURL: string; const ASource: TStream): string; overload;
    function Post(AURL: string; const ASource: TIdMultiPartFormDataStream): string; overload;
    procedure Post(AURL: string; const ASource: TStrings; const AResponseContent: TStream);
      overload;
    {Post data provided by a stream, this is for submitting data to a server}
    procedure Post(AURL: string; const ASource, AResponseContent: TStream);
      overload;
    procedure Post(AURL: string; const ASource: TIdMultiPartFormDataStream; AResponseContent: TStream);
      overload;
    //
    function Put(AURL: string; const ASource: TStream): string; overload;
    procedure Put(AURL: string; const ASource, AResponseContent: TStream);
      overload;
    {This is the response code number such as 404 for File not Found}
    property ResponseCode: Integer read GetResponseCode;
    {This is the text of the message such as "404 File Not Found here Sorry"}
    property ResponseText: string read GetResponseText;
    property Response: TIdHTTPResponse read GetResponseHeaders;
    { This is the last processed URL }
    property URL: TIdURI read FURI;
    // Num retries for Authentication
    property AuthRetries: Integer read FMaxAuthRetries write FMaxAuthRetries default 3;
    property AllowCookies: Boolean read FAllowCookies write SetAllowCookies;
    {Do we handle redirect requests or simply raise an exception and let the
     developer deal with it}
    property HandleRedirects: Boolean read FHandleRedirects write FHandleRedirects default Id_TIdHTTP_HandleRedirects;
    property ProtocolVersion: TIdHTTPProtocolVersion read FProtocolVersion write FProtocolVersion default Id_TIdHTTP_ProtocolVersion;
    {This is the maximum number of redirects we wish to handle, we limit this
     to prevent stack overflow due to recursion.  Recursion is safe ONLY if
     prevented for continuing to infinity}
    property RedirectMaximum: Integer read FRedirectMax write FRedirectMax default Id_TIdHTTP_RedirectMax;
    property ProxyParams: TIdProxyConnectionInfo read FProxyParameters write FProxyParameters;
    property Request: TIdHTTPRequest read GetRequestHeaders write SetRequestHeaders;
    property HTTPOptions: TIdHTTPOptions read FOptions write FOptions;
    // Fired when a rediretion is requested.
    property OnRedirect: TIdHTTPOnRedirectEvent read FOnRedirect write FOnRedirect;
    property OnSelectAuthorization: TIdOnSelectAuthorization read FOnSelectAuthorization write FOnSelectAuthorization;
    property OnSelectProxyAuthorization: TIdOnSelectAuthorization read FOnSelectProxyAuthorization write FOnSelectProxyAuthorization;
    property OnAuthorization: TIdOnAuthorization read FOnAuthorization write FOnAuthorization;
    property OnProxyAuthorization: TIdOnAuthorization read FOnProxyAuthorization write FOnProxyAuthorization;
    // Cookie stuff
    property CookieManager: TIdCookieManager read FCookieManager write SetCookieManager;
    //
    property AuthenticationManager: TIdAuthenticationManager read FAuthenticationManager write SetAuthenticationManager;
    property ConnectTimeout : Integer read FConnectTimeout write FConnectTimeout default IdDefTimeout;
  end;

  TIdHTTP = class(TIdCustomHTTP)
  published
    // Num retries for Authentication
    property AuthRetries;
    property AllowCookies;
    {Do we handle redirect requests or simply raise an exception and let the
     developer deal with it}
    property HandleRedirects;
    property ProtocolVersion;
    {This is the maximum number of redirects we wish to handle, we limit this
     to prevent stack overflow due to recursion.  Recursion is safe ONLY if
     prevented for continuing to infinity}
    property RedirectMaximum;
    property ProxyParams;
    property Request;
    property HTTPOptions;
    // Fired when a rediretion is requested.
    property OnRedirect;
    property OnSelectAuthorization;
    property OnSelectProxyAuthorization;
    property OnAuthorization;
    property OnProxyAuthorization;
    property Host;
    property Port default IdPORT_HTTP;
    // Cookie stuff
    property CookieManager;
    //
    // property AuthenticationManager: TIdAuthenticationManager read FAuthenticationManager write SetAuthenticationManager;
    property ConnectTimeout;
  end;

  EIdUnknownProtocol = class(EIdException);
  EIdHTTPProtocolException = class(EIdProtocolReplyError)
  protected
    FErrorMessage: string;
  public
    constructor CreateError(const anErrCode: Integer; const asReplyMessage: string;
      const asErrorMessage: string); reintroduce; virtual;
    property ErrorMessage: string read FErrorMessage;
  end;

implementation

uses
  SysUtils,
  IdGlobal, IdComponent, IdCoderMIME, IdResourceStrings;

const
  ProtocolVersionString: array[TIdHTTPProtocolVersion] of string = ('1.0', '1.1');

{ EIdHTTPProtocolException }

constructor EIdHTTPProtocolException.CreateError(const anErrCode: Integer;
  const asReplyMessage: string; const asErrorMessage: string);
begin
  inherited CreateError(anErrCode, asReplyMessage);
  FErrorMessage := asErrorMessage;
end;

{ TIdHTTP }

constructor TIdCustomHTTP.Create(AOwner: TComponent);
begin
  FURI := TIdURI.Create('');

  inherited Create(AOwner);
  Port := IdPORT_HTTP;

  FMaxAuthRetries := 3;
  AllowCookies := true;
  FFreeOnDestroy := false;
  FOptions := [hoForceEncodeParams];

  FRedirectMax := Id_TIdHTTP_RedirectMax;
  FHandleRedirects := Id_TIdHTTP_HandleRedirects;
  //
  FProtocolVersion := Id_TIdHTTP_ProtocolVersion;

  FHTTPProto := TIdHTTPProtocol.Create(self);
  FProxyParameters := TIdProxyConnectionInfo.Create;
  FProxyParameters.Clear;

  FConnectTimeout := IdDefTimeout;
end;

destructor TIdCustomHTTP.Destroy;
begin
  FreeAndNil(FHTTPProto);
  FreeAndNil(FURI);
  FreeAndNil(FProxyParameters);

  {if FFreeOnDestroy then
  begin
    FreeAndNil(FCookieManager);
  end;}

  inherited Destroy;
end;

procedure TIdCustomHTTP.Options(AURL: string);
begin
  DoRequest(hmOptions, AURL, nil, nil);
end;

procedure TIdCustomHTTP.Get(AURL: string; const AResponseContent: TStream);
begin
  DoRequest(hmGet, AURL, nil, AResponseContent);
end;

procedure TIdCustomHTTP.Trace(AURL: string; const AResponseContent: TStream);
begin
  DoRequest(hmTrace, AURL, nil, AResponseContent);
end;

procedure TIdCustomHTTP.Head(AURL: string);
begin
  DoRequest(hmHead, AURL, nil, nil);
end;

procedure TIdCustomHTTP.Post(AURL: string; const ASource, AResponseContent: TStream);
var
  OldProtocol: TIdHTTPProtocolVersion;
begin
  // PLEASE READ CAREFULLY

  // Currently when issuing a POST, IdHTTP will automatically set the protocol
  // to version 1.0 independently of the value it had initially. This is because
  // there are some servers that don't respect the RFC to the full extent. In
  // particular, they don't respect sending/not sending the Expect: 100-Continue
  // header. Until we find an optimum solution that does NOT break the RFC, we
  // will restrict POSTS to version 1.0.
  if Connected then
  begin
    Disconnect;
  end;
  OldProtocol := FProtocolVersion;
  // If hoKeepOrigProtocol is SET, is possible to assume that the developer
  // is sure in operations of the server
  if not (hoKeepOrigProtocol in FOptions) then
    FProtocolVersion := pv1_0;
  DoRequest(hmPost, AURL, ASource, AResponseContent);
  FProtocolVersion := OldProtocol;
end;

procedure TIdCustomHTTP.EncodeRequestParams(const AStrings: TStrings);
var
  i: Integer;
  S: string;
begin
  for i := 0 to AStrings.Count - 1 do begin
    S := AStrings.Names[i];
    if Length(AStrings.Values[S]) > 0 then begin
      AStrings.Values[S] := TIdURI.ParamsEncode(AStrings.Values[S]);
    end;
  end;
end;

function TIdCustomHTTP.SetRequestParams(const AStrings: TStrings): string;
var
  S: string;
begin
  if Assigned(AStrings) then begin
    if hoForceEncodeParams in FOptions then
      EncodeRequestParams(AStrings);
    if AStrings.Count > 1 then
      S := StringReplace(AStrings.Text, sLineBreak, '&', [rfReplaceall])
    else
      S := AStrings.Text;
    // break trailing CR&LF
    Result := Trim(S);
  end else
    Result := '';
end;

procedure TIdCustomHTTP.Post(AURL: string; const ASource: TStrings; const AResponseContent: TStream);
var
  LParams: TStringStream;
begin
  // Usual posting request have default ContentType is application/x-www-form-urlencoded
  if (Request.ContentType = '') or (AnsiSameText(Request.ContentType, 'text/html')) then
    Request.ContentType := 'application/x-www-form-urlencoded';

  LParams := TStringStream.Create(SetRequestParams(ASource));
  try
    Post(AURL, LParams, AResponseContent);
  finally
    LParams.Free;
  end;
end;

function TIdCustomHTTP.Post(AURL: string; const ASource: TStrings): string;
var
  LResponse: TStringStream;
begin
  LResponse := TStringStream.Create('');
  try
    Post(AURL, ASource, LResponse);
  finally
    result := LResponse.DataString;
    LResponse.Free;
  end;
end;

function TIdCustomHTTP.Post(AURL: string; const ASource: TStream): string;
var
  LResponse: TStringStream;
begin
  LResponse := TStringStream.Create('');
  try
    Post(AURL, ASource, LResponse);
  finally
    result := LResponse.DataString;
    LResponse.Free;
  end;
end;

procedure TIdCustomHTTP.Put(AURL: string; const ASource, AResponseContent: TStream);
begin
  DoRequest(hmPut, AURL, ASource, AResponseContent);
end;

function TIdCustomHTTP.Put(AURL: string; const ASource: TStream): string;
var
  LResponse: TStringStream;
begin
  LResponse := TStringStream.Create('');
  try
    Put(AURL, ASource, LResponse);
  finally
    result := LResponse.DataString;
    LResponse.Free;
  end;
end;

function TIdCustomHTTP.Get(AURL: string): string;
var
  Stream: TMemoryStream;
begin
  Stream := TMemoryStream.Create;
  try
    Get(AURL, Stream);
  finally
    if Stream.Size > 0 then // DO we have result?
    begin
      SetLength(result, Stream.Size);
      Move(PChar(Stream.Memory)^, result[1], Stream.Size);
    end;
    Stream.Free;
  end;
end;

function TIdCustomHTTP.Trace(AURL: string): string;
var
  Stream: TStringStream;
begin
  Stream := TStringStream.Create(''); try
    Trace(AURL, Stream);
    result := Stream.DataString;
  finally Stream.Free; end;
end;

function TIdCustomHTTP.DoOnRedirect(var Location: string; var VMethod: TIdHTTPMethod; RedirectCount: integer): boolean;
begin
  result := HandleRedirects;
  if assigned(FOnRedirect) then
  begin
    FOnRedirect(self, Location, RedirectCount, result, VMethod);
  end;
end;

procedure TIdCustomHTTP.SetCookies(AURL: TIdURI; ARequest: TIdHTTPRequest);
var
  S: string;
begin
  if Assigned(FCookieManager) then
  begin
    // Send secure cookies only if we have Secured connection
    S := FCookieManager.GenerateCookieList(AURL, (IOHandler is TIdSSLIOHandlerSocket));
    if Length(S) > 0 then
    begin
      ARequest.RawHeaders.Values['Cookie'] := S;
    end;
  end;
end;

// This function sets the Host and Port and returns a boolean depending on
// whether a PROXY is being used or not.

function TIdCustomHTTP.SetHostAndPort: TIdHTTPConnectionType;
begin
  // First check to see if a Proxy has been specified.
  if Length(ProxyParams.ProxyServer) > 0 then
  begin
    if ((not AnsiSameText(Host, ProxyParams.ProxyServer)) or
      (Port <> ProxyParams.ProxyPort)) and (Connected) then
    begin
      Disconnect;
    end;

    FHost := ProxyParams.ProxyServer;
    FPort := ProxyParams.ProxyPort;

    if AnsiSameText(URL.Protocol, 'HTTPS') then
    begin
      Result := ctSSLProxy;

      if Assigned(IOHandler) then
      begin
        if not (IOHandler is TIdSSLIOHandlerSocket) then
        begin
          raise EIdIOHandlerPropInvalid.Create(RSIOHandlerPropInvalid);
        end else begin
          (IOHandler as TIdSSLIOHandlerSocket).PassThrough := true;
        end;
      end;
    end
    else begin
      Result := ctProxy;
      if Assigned(IOHandler) and (IOHandler is TIdSSLIOHandlerSocket) then
      begin
        (IOHandler as TIdSSLIOHandlerSocket).PassThrough := true;
      end;
    end;
  end
  else begin
    Result := ctNormal;

    if ((not AnsiSameText(Host, URL.Host)) or (Port <> StrToInt(URL.Port))) then begin
      if Connected then begin
        Disconnect;
      end;
      Host := URL.Host;
      Port := StrToInt(URL.Port);
    end;

    if AnsiSameText(URL.Protocol, 'HTTPS') then
    begin
      // Just check can we do SSL
      if not Assigned(IOHandler) or (not (IOHandler is TIdSSLIOHandlerSocket)) then
        raise EIdIOHandlerPropInvalid.Create(RSIOHandlerPropInvalid)
      else begin
        (IOHandler as TIdSSLIOHandlerSocket).PassThrough := false;
        result := ctSSL;
      end;
    end
    else
    begin
      if Assigned(IOHandler) then
      begin
        if (IOHandler is TIdSSLIOHandlerSocket) then
        begin
          (IOHandler as TIdSSLIOHandlerSocket).PassThrough := true;
        end;
      end;
    end;
  end;
end;

procedure TIdCustomHTTP.ReadResult(AResponse: TIdHTTPResponse);
var
  Size: Integer;

  function ChunkSize: integer;
  var
    j: Integer;
    s: string;
  begin
    s := ReadLn;
    j := AnsiPos(' ', s);
    if j > 0 then
    begin
      s := Copy(s, 1, j - 1);
    end;
    Result := StrToIntDef('$' + s, 0);
  end;

begin
  if Assigned(AResponse.ContentStream) then // Only for Get and Post
  begin
    if AResponse.ContentLength > 0 then // If chunked then this is also 0
    begin
      try
        ReadStream(AResponse.ContentStream, AResponse.ContentLength);
      except
        on E: EIdConnClosedGracefully do
      end;
    end
    else
    begin
      if AnsiPos('chunked', AResponse.RawHeaders.Values['Transfer-Encoding']) > 0 then {do not localize}
      begin // Chunked
        DoStatus(hsStatusText, [RSHTTPChunkStarted]);
        Size := ChunkSize;
        while Size > 0 do
        begin
          ReadStream(AResponse.ContentStream, Size);
          ReadLn; // blank line
          Size := ChunkSize;
        end;
        ReadLn; // blank line
      end
      else begin
        if not AResponse.HasContentLength then
          ReadStream(AResponse.ContentStream, -1, True);
      end;
    end;
  end;
end;

procedure TIdCustomHTTP.PrepareRequest(ARequest: TIdHTTPRequest);
var
  LURI: TIdURI;
begin
  LURI := TIdURI.Create(ARequest.URL);

  if Length(LURI.Username) > 0 then
  begin
    ARequest.Username := LURI.Username;
    ARequest.Password := LURI.Password;
  end;

  FURI.Username := ARequest.Username;
  FURI.Password := ARequest.Password;

  FURI.Path := ProcessPath(FURI.Path, LURI.Path);
  FURI.Document := LURI.Document;
  FURI.Params := LURI.Params;

  if Length(LURI.Host) > 0 then begin
    FURI.Host := LURI.Host;
  end;

  if Length(LURI.Protocol) > 0 then begin
    FURI.Protocol := LURI.Protocol;
  end else begin
    FURI.Protocol := 'http';
  end;

  if Length(LURI.Port) > 0 then begin
    FURI.Port := LURI.Port;
  end
  else begin
    if AnsiSameText(LURI.Protocol, 'http') then begin
      FURI.Port := IntToStr(IdPORT_HTTP);
    end else begin
      if AnsiSameText(LURI.Protocol, 'https') then begin
        FURI.Port := IntToStr(IdPORT_SSL);
      end else begin
        if Length(FURI.Port) > 0 then begin
         {  FURI.Port:=FURI.Port; } // do nothing, as the port is already filled in.
        end else begin
          raise EIdUnknownProtocol.Create('');
        end;
      end;
    end;
  end;


  // The URL part is not URL encoded at this place

  ARequest.URL := URL.Path + URL.Document + URL.Params;

  if ARequest.Method = hmOptions then
  begin
    if AnsiSameText(LURI.Document, '*') then
    begin
      ARequest.URL := LURI.Document;
    end;
  end;
  LURI.Free;  // Free URI Object;

  // Check for valid HTTP request methods
  if ARequest.Method in [hmTrace, hmPut, hmOptions, hmDelete] then
  begin
    if ProtocolVersion <> pv1_1 then
    begin
      raise EIdException.Create('This request method is supported in HTTP 1.1');
    end;
  end;

  if ARequest.Method in [hmPost, hmPut] then
  begin
    ARequest.ContentLength := ARequest.Source.Size;
  end
  else ARequest.ContentLength := -1;

  if FURI.Port <> IntToStr(IdPORT_HTTP) then
    ARequest.Host := FURI.Host + ':' + FURI.Port
  else
    ARequest.Host := FURI.Host;
end;

procedure TIdCustomHTTP.CheckAndConnect(AResponse: TIdHTTPResponse);
begin
  if not AResponse.KeepAlive then begin
    Disconnect;
  end;

  CheckForGracefulDisconnect(false);

  if not Connected then try
    Connect(FConnectTimeout);
  except
    on E: EIdSSLProtocolReplyError do
    begin
      Disconnect;
      raise;
    end;
  end;
end;


procedure TIdCustomHTTP.ConnectToHost(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse);
var
  LLocalHTTP: TIdHTTPProtocol;
begin
  ARequest.FUseProxy := SetHostAndPort;

  if ARequest.UseProxy = ctProxy then
  begin
    ARequest.URL := FURI.URI;
  end;

  case ARequest.UseProxy of
    ctNormal:
      if (ProtocolVersion = pv1_0) and (Length(ARequest.Connection) = 0) then
        ARequest.Connection := 'keep-alive';
    ctSSL, ctSSLProxy: ARequest.Connection := '';
    ctProxy:
      if (ProtocolVersion = pv1_0) and (Length(ARequest.Connection) = 0) then
        ARequest.ProxyConnection := 'keep-alive';
  end;

  if ARequest.UseProxy = ctSSLProxy then begin
    LLocalHTTP := TIdHTTPProtocol.Create(Self);

    with LLocalHTTP do begin
      Request.UserAgent := ARequest.UserAgent;
      Request.Host := ARequest.Host;
      Request.ContentLength := ARequest.ContentLength;
      Request.Pragma := 'no-cache';
      Request.URL := URL.Host + ':' + URL.Port;
      Request.Method := hmConnect;
      Request.ProxyConnection := 'keep-alive';
      Response.ContentStream := TMemoryStream.Create;
      try
        try
          repeat
            CheckAndConnect(Response);
            BuildAndSendRequest(nil);

            Response.ResponseText := ReadLn;
            if Length(Response.ResponseText) = 0 then begin
              Response.ResponseText := 'HTTP/1.0 200 OK'; // Support for HTTP responses whithout Status line and headers
              Response.Connection := 'close';
            end
            else begin
              RetrieveHeaders;
              ProcessCookies(LLocalHTTP.Request, LLocalHTTP.Response);
            end;

            if Response.ResponseCode = 200 then
            begin
              // Connection established
              (IOHandler as TIdSSLIOHandlerSocket).PassThrough := false;
              break;
            end
            else begin
              ProcessResponse;
            end;
          until false;
        except
          raise;
          // TODO: Add property that will contain the error messages.
        end;
      finally
        LLocalHTTP.Response.ContentStream.Free;
        LLocalHTTP.Free;
      end;
    end;
  end
  else begin
    CheckAndConnect(AResponse);
  end;

  FHTTPProto.BuildAndSendRequest(URL);

  if (ARequest.Method in [hmPost, hmPut]) then
  begin
    WriteStream(ARequest.Source, True, false);
  end;
end;

procedure TIdCustomHTTP.DoRequest(const AMethod: TIdHTTPMethod; AURL: string;
  const ASource, AResponseContent: TStream);
var
  LResponseLocation: Integer;
begin
  if Assigned(AResponseContent) then
  begin
    LResponseLocation := AResponseContent.Position;
  end
  else
    LResponseLocation := 0; // Just to avoid the waringing message

  Request.URL := AURL;
  Request.Method := AMethod;
  Request.Source := ASource;
  Response.ContentStream := AResponseContent;

  try
    repeat
      Inc(FRedirectCount);

      PrepareRequest(Request);
      ConnectToHost(Request, Response);

      // Workaround for servers wich respond with 100 Continue on GET and HEAD
      // This workaround is just for temporary use until we have final HTTP 1.1
      // realisation
      repeat
        Response.ResponseText := ReadLn;
        FHTTPProto.RetrieveHeaders;
        ProcessCookies(Request, Response);
      until Response.ResponseCode <> 100;

      case FHTTPProto.ProcessResponse of
        wnAuthRequest: begin
            Dec(FRedirectCount);
            Request.URL := AURL;
          end;
        wnReadAndGo: begin
            ReadResult(Response);
            if Assigned(AResponseContent) then
            begin
              AResponseContent.Position := LResponseLocation;
              AResponseContent.Size := LResponseLocation;
            end;
          end;
        wnGoToURL: begin
            if Assigned(AResponseContent) then
            begin
              AResponseContent.Position := LResponseLocation;
              AResponseContent.Size := LResponseLocation;
            end;
          end;
        wnJustExit: begin
            break;
          end;
        wnDontKnow:
          // TODO: This is for temporary use. Will remove it for final release
          raise EIdException.Create('Undefined situation');
      end;
    until false;
  finally
    if not Response.KeepAlive then begin
      Disconnect;
    end;
  end;
  FRedirectCount := 0;
end;

procedure TIdCustomHTTP.SetAllowCookies(AValue: Boolean);
begin
  FAllowCookies := AValue;
end;

procedure TIdCustomHTTP.ProcessCookies(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse);
var
  Cookies, Cookies2: TStringList;
  i: Integer;
begin
  Cookies := nil;
  Cookies2 := nil;
  try
    if not Assigned(FCookieManager) and AllowCookies then
    begin
      CookieManager := TIdCookieManager.Create(Self);
      FFreeOnDestroy := true;
    end;

    if Assigned(FCookieManager) then
    begin
      Cookies := TStringList.Create;
      Cookies2 := TStringList.Create;

      AResponse.RawHeaders.Extract('Set-cookie', Cookies);
      AResponse.RawHeaders.Extract('Set-cookie2', Cookies2);

      for i := 0 to Cookies.Count - 1 do
        CookieManager.AddCookie(Cookies[i], FURI.Host);

      for i := 0 to Cookies2.Count - 1 do
        CookieManager.AddCookie2(Cookies2[i], FURI.Host);
    end;
  finally
    FreeAndNil(Cookies);
    FreeAndNil(Cookies2);
  end;
end;

procedure TIdCustomHTTP.Notification(AComponent: TComponent; Operation: TOperation);
begin
  inherited Notification(AComponent, Operation);
  if Operation = opRemove then
  begin
    if (AComponent = FCookieManager) then
    begin
      FCookieManager := nil;
    end;
    if AComponent = FAuthenticationManager then
    begin
      FAuthenticationManager := nil;
    end;
  end;
end;

procedure TIdCustomHTTP.SetCookieManager(ACookieManager: TIdCookieManager);
begin
  if Assigned(FCookieManager) then
  begin
    if FFreeOnDestroy then begin
      FCookieManager.Free;
    end;
  end;

  FCookieManager := ACookieManager;
  FFreeOnDestroy := false;

  if Assigned(FCookieManager) then
  begin
    FCookieManager.FreeNotification(Self);
  end;
end;

function TIdCustomHTTP.DoOnAuthorization(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse): Boolean;
var
  i: Integer;
  S: string;
  Auth: TIdAuthenticationClass;
begin
  if not Assigned(ARequest.Authentication) then
  begin
    // Find wich Authentication method is supported from us.
    for i := 0 to AResponse.WWWAuthenticate.Count - 1 do
    begin
      S := AResponse.WWWAuthenticate[i];
      Auth := FindAuthClass(Fetch(S));
      if Auth <> nil then
        break;
    end;

    if Auth = nil then begin
      result := false;
      exit;
    end;

    if Assigned(FOnSelectAuthorization) then
    begin
      OnSelectAuthorization(self, Auth, AResponse.WWWAuthenticate);
    end;

    ARequest.Authentication := Auth.Create;
  end;

  // Clear password and reset autorization if previous failed
  {if (AResponse.FResponseCode = 401) then begin
    ARequest.Password := '';
    ARequest.Authentication.Reset;
  end;}

  result := Assigned(FOnAuthorization) or (hoInProcessAuth in HTTPOptions);

  if Result then
  begin
    with ARequest.Authentication do
    begin
      Username := ARequest.Username;
      Password := ARequest.Password;
      Params.Values['Authorization'] := Authentication;
      AuthParams := AResponse.WWWAuthenticate;
    end;

    result := false;

    repeat
      case ARequest.Authentication.Next of
        wnAskTheProgram:
          begin // Ask the user porgram to supply us with authorization information
            if Assigned(FOnAuthorization) then
            begin
              ARequest.Authentication.UserName := ARequest.Username;
              ARequest.Authentication.Password := ARequest.Password;

              OnAuthorization(self, ARequest.Authentication, result);

              if result then begin
                ARequest.BasicAuthentication := true;
                ARequest.Username := ARequest.Authentication.UserName;
                ARequest.Password := ARequest.Authentication.Password;
              end
              else begin
                break;
              end;
            end else begin
              result := False;
              break;
            end;
          end;
        wnDoRequest:
          begin
            result := true;
            break;
          end;
        wnFail:
          begin
            result := False;
            Break;
          end;
      end;
    until false;
  end;
end;

function TIdCustomHTTP.DoOnProxyAuthorization(ARequest: TIdHTTPRequest; AResponse: TIdHTTPResponse): Boolean;
var
  i: Integer;
  S: string;
  Auth: TIdAuthenticationClass;
begin
  if not Assigned(ProxyParams.Authentication) then
  begin
    // Find wich Authentication method is supported from us.
    for i := 0 to AResponse.ProxyAuthenticate.Count - 1 do
    begin
      S := AResponse.ProxyAuthenticate[i];
      try
        Auth := FindAuthClass(Fetch(S));
        break;
      except
      end;
    end;

    if i = AResponse.ProxyAuthenticate.Count then
    begin
      result := false;
      exit;
    end;

    if Assigned(FOnSelectProxyAuthorization) then
    begin
      OnSelectProxyAuthorization(self, Auth, AResponse.ProxyAuthenticate);
    end;

    ProxyParams.Authentication := Auth.Create;
  end;

  result := Assigned(OnProxyAuthorization) or (hoInProcessAuth in HTTPOptions);

  // Clear password and reset autorization if previous failed
  {if (AResponse.FResponseCode = 407) then begin
    ProxyParams.ProxyPassword := '';
    ProxyParams.Authentication.Reset;
  end;}

  if Result then
  begin
    with ProxyParams.Authentication do
    begin
      Username := ProxyParams.ProxyUsername;
      Password := ProxyParams.ProxyPassword;
      Params.Values['Authorization'] := Authentication;
      AuthParams := AResponse.ProxyAuthenticate;
    end;

    result := false;

    repeat
      case ProxyParams.Authentication.Next of
        wnAskTheProgram: // Ask the user porgram to supply us with authorization information
          begin
            if Assigned(OnProxyAuthorization) then
            begin
              ProxyParams.Authentication.Username := ProxyParams.ProxyUsername;
              ProxyParams.Authentication.Password := ProxyParams.ProxyPassword;

              OnProxyAuthorization(self, ProxyParams.Authentication, result);

              if result then begin
                ProxyParams.BasicAuthentication := true;
                ProxyParams.ProxyUsername := ProxyParams.Authentication.Username;
                ProxyParams.ProxyPassword := ProxyParams.Authentication.Password;
              end else begin
                break;
              end;
            end else begin
              result := false;
              break;
            end;
          end;
        wnDoRequest:
          begin
            result := true;
            break;
          end;
        wnFail:
          begin
            result := False;
            Break;
          end;
      end;
    until false;
  end;
end;

function TIdCustomHTTP.GetResponseCode: Integer;
begin
  result := Response.ResponseCode;
end;

function TIdCustomHTTP.GetResponseText: string;
begin
  result := Response.FResponseText;
end;

function TIdCustomHTTP.GetResponseHeaders: TIdHTTPResponse;
begin
  result := FHTTPProto.Response;
end;

function TIdCustomHTTP.GetRequestHeaders: TIdHTTPRequest;
begin
  result := FHTTPProto.Request;
end;

procedure TIdCustomHTTP.DoOnDisconnected;
begin
  inherited DoOnDisconnected;

  if Assigned(Request.Authentication) and
    (Request.Authentication.CurrentStep = Request.Authentication.Steps) then begin
    if Assigned(AuthenticationManager) then begin
      AuthenticationManager.AddAuthentication(Request.Authentication, URL);
    end;
    FreeAndNil(Request.Authentication);
  end;

  if Assigned(ProxyParams.Authentication) then begin
    ProxyParams.Authentication.Reset;
  end;
end;

procedure TIdCustomHTTP.SetAuthenticationManager(const Value: TIdAuthenticationManager);
begin
  FAuthenticationManager := Value;
  if Assigned(FAuthenticationManager) then
  begin
    FAuthenticationManager.FreeNotification(self);
  end;
end;

procedure TIdCustomHTTP.SetHost(const Value: string);
begin
  inherited SetHost(Value);
  URL.Host := Value;
end;

procedure TIdCustomHTTP.SetPort(const Value: integer);
begin
  inherited SetPort(Value);
  URL.Port := IntToStr(Value);
end;

procedure TIdCustomHTTP.SetRequestHEaders(const Value: TIdHTTPRequest);
begin
  FHTTPProto.Request.Assign(Value);
end;

procedure TIdCustomHTTP.Post(AURL: string;
  const ASource: TIdMultiPartFormDataStream; AResponseContent: TStream);
begin
  Request.ContentType := ASource.RequestContentType;
  Post(AURL, TStream(ASource), AResponseContent);
end;

function TIdCustomHTTP.Post(AURL: string;
  const ASource: TIdMultiPartFormDataStream): string;
begin
  Request.ContentType := ASource.RequestContentType;
  result := Post(AURL, TStream(ASource));
end;

{ TIdHTTPResponse }

constructor TIdHTTPResponse.Create(AParent: TIdCustomHTTP);
begin
  inherited Create;

  FHTTP := AParent;
end;

function TIdHTTPResponse.GetKeepAlive: Boolean;
var
  S: string;
  i: TIdHTTPProtocolVersion;
begin
  S := Copy(FResponseText, 6, 3);

  for i := Low(TIdHTtpProtocolVersion) to High(TIdHTtpProtocolVersion) do
    if AnsiSameText(ProtocolVersionString[i], S) then
    begin
      ResponseVersion := i;
      break;
    end;

  FHTTP.CheckForDisconnect(false);
  FKeepAlive := FHTTP.Connected;

  if FKeepAlive then
    case FHTTP.ProtocolVersion of
      pv1_1: // By default we assume that keep-alive is by default and will close the connection only there is "close"
        begin
          FKeepAlive :=
            not (AnsiSameText(Trim(Connection), 'CLOSE') or
            AnsiSameText(Trim(ProxyConnection), 'CLOSE'));
        end;
      pv1_0: // By default we assume that keep-alive is not by default and will keep the connection only if there is "keep-alive"
        begin
          FKeepAlive := AnsiSameText(Trim(Connection), 'KEEP-ALIVE') or
            AnsiSameText(Trim(ProxyConnection), 'KEEP-ALIVE') {or
            ((ResponseVersion = pv1_1) and (Length(Trim(Connection)) = 0) and
             (Length(Trim(ProxyConnection)) = 0))};
        end;
    end;
  result := FKeepAlive;
end;

function TIdHTTPResponse.GetResponseCode: Integer;
var
  S: string;
begin
  S := FResponseText;
  Fetch(S);
  S := Trim(S);
  FResponseCode := StrToIntDef(Fetch(S, ' ', False), -1);
  Result := FResponseCode;
end;

{ TIdHTTPRequest }

constructor TIdHTTPRequest.Create(AHTTP: TIdCustomHTTP);
begin
  inherited Create;

  FHTTP := AHTTP;
  FUseProxy := ctNormal;
end;

{ TIdHTTPProtocol }

constructor TIdHTTPProtocol.Create(AConnection: TIdCustomHTTP);
begin
  inherited Create;
  FHTTP := AConnection;
  // Create the headers
  FRequest := TIdHTTPRequest.Create(FHTTP);
  FResponse := TIdHTTPResponse.Create(FHTTP);
end;

destructor TIdHTTPProtocol.Destroy;
begin
  FreeAndNil(FRequest);
  FreeAndNil(FResponse);

  inherited Destroy;
end;

procedure TIdHTTPProtocol.BuildAndSendRequest(AURI: TIdURI);
var
  i: Integer;
begin
  Request.SetHeaders;
  FHTTP.ProxyParams.SetHeaders(Request.RawHeaders);
  if Assigned(AURI) then
    FHTTP.SetCookies(AURI, Request);

  // This is a wrokaround for some HTTP servers wich does not implement properly the HTTP protocol
  FHTTP.OpenWriteBuffer;
  case Request.Method of
    hmHead: FHTTP.WriteLn('HEAD ' + Request.URL + ' HTTP/' + ProtocolVersionString[FHTTP.ProtocolVersion]); {do not localize}
    hmGet: FHTTP.WriteLn('GET ' + Request.URL + ' HTTP/' + ProtocolVersionString[FHTTP.ProtocolVersion]); {do not localize}
    hmPost: FHTTP.WriteLn('POST ' + Request.URL + ' HTTP/' + ProtocolVersionString[FHTTP.ProtocolVersion]); {do not localize}
    // HTTP 1.1 only
    hmOptions: FHTTP.WriteLn('OPTIONS ' + Request.URL + ' HTTP/' + ProtocolVersionString[FHTTP.ProtocolVersion]); {do not localize}
    hmTrace: FHTTP.WriteLn('TRACE ' + Request.URL + ' HTTP/' + ProtocolVersionString[FHTTP.ProtocolVersion]); {do not localize}
    hmPut: FHTTP.WriteLn('PUT ' + Request.URL + ' HTTP/' + ProtocolVersionString[FHTTP.ProtocolVersion]); {do not localize}
    hmConnect: FHTTP.WriteLn('CONNECT ' + Request.URL + ' HTTP/' + ProtocolVersionString[FHTTP.ProtocolVersion]); {do not localize}
  end;
  // write the headers
  for i := 0 to Request.RawHeaders.Count - 1 do
    if Length(Request.RawHeaders.Strings[i]) > 0 then
      FHTTP.WriteLn(Request.RawHeaders.Strings[i]);
  FHTTP.WriteLn('');
  FHTTP.CloseWriteBuffer;
end;

procedure TIdHTTPProtocol.RetrieveHeaders;
var
  S: string;
begin
  // Set the response headers
  // Clear headers
  // Don't use Capture.

  Response.RawHeaders.Clear;
  s := FHTTP.ReadLn;
  try
    while Length(s) > 0 do
    begin
      Response.RawHeaders.Add(S);
      s := FHTTP.ReadLn;
    end;
  except
    on E: EIdConnClosedGracefully do begin
      FHTTP.Disconnect;
    end;
  end;
  Response.ProcessHeaders;
end;

function TIdHTTPProtocol.ProcessResponse: TIdHTTPWhatsNext;
  procedure RaiseException;
  var
    LRespStream: TStringStream;
    LTempStream: TStream;
    LTemp: Integer;
  begin
    LTemp := FHTTP.ReadTimeout;
    FHTTP.ReadTimeout := 2000; // Lets wait 2 seconds for any kind of content
    LRespStream := TStringStream.Create('');
    LTempStream := Response.ContentStream;
    Response.ContentStream := LRespStream;
    try
      FHTTP.ReadResult(Response);
      raise EIdHTTPProtocolException.CreateError(Response.ResponseCode, FHTTP.ResponseText, LRespStream.DataString);
    finally
      Response.ContentStream := LTempStream;
      LRespStream.Free;
      FHTTP.ReadTimeout := LTemp;
    end;
  end;

  procedure ReadContent;
  Var
    LTempResponse: TStringStream;
    LTempStream: TStream;
  begin
    LTempResponse := TStringStream.Create('');
    LTempStream := Response.ContentStream;
    Response.ContentStream := LTempResponse;
    try
      FHTTP.ReadResult(Response);
    finally
      LTempResponse.Free;
      Response.ContentStream := LTempStream;
    end;
  end;

var
  LTemp: Integer;
  LLocation: string;
  LMethod: TIdHTTPMethod;
  LResponseDigit: Integer;
  LNeedAutorization: Boolean;
begin
  result := wnDontKnow;
  LNeedAutorization := False;
  LResponseDigit := Response.ResponseCode div 100;
  // Handle Redirects
  if ((LResponseDigit = 3) and (Response.ResponseCode <> 304)) or (Length(Response.Location) > 0) then
  begin
    // LLocation := TIdURI.URLDecode(Response.Location);
    LLocation := Response.Location;

    if (FHTTP.FHandleRedirects) and (FHTTP.FRedirectCount < FHTTP.FRedirectMax) then
    begin
      LMethod := Request.Method;
      if FHTTP.DoOnRedirect(LLocation, LMethod, FHTTP.FRedirectCount) then
      begin
        result := wnGoToURL;
        Request.URL := LLocation;
        Request.Method := LMethod;
      end
      else
        RaiseException;
    end
    else // Just fire the event
    begin
      LMethod := Request.Method;
      result := wnJustExit;
      if not FHTTP.DoOnRedirect(LLocation, LMethod, FHTTP.FRedirectCount) then // If not Handled
        RaiseException
      else
        Response.Location := LLocation;
    end;

    if FHTTP.Connected then
    begin
      // This is a workaround for buggy HTTP 1.1 servers which
      // does not return any body with 302 response code
      LTemp := FHTTP.ReadTimeout;
      FHTTP.ReadTimeout := 4000; // Lets wait 4 seconds for any kind of content
      try
        ReadContent;
      except end;
      FHTTP.ReadTimeout := LTemp;
    end;
  end
  else
  begin
    // GREGOR Workaround
    // if we get an error we disconnect if we use SSLIOHandler
    if Assigned(FHTTP.IOHandler) then
    begin
      Response.KeepAlive := not (FHTTP.Connected and (FHTTP.IOHandler is TIdSSLIOHandlerSocket) and Response.KeepAlive);
    end;

    if LResponseDigit <> 2 then
    begin
      result := wnGoToURL;
      case Response.ResponseCode of
        401:
          begin // HTTP Server authorization requered
            if (FHTTP.InternalAuthRetries >= FHTTP.AuthRetries) or not FHTTP.DoOnAuthorization(Request, Response) then
            begin
              if Assigned(Request.Authentication) then
                Request.Authentication.Reset;
              RaiseException;
            end else begin
              if hoInProcessAuth in FHTTP.HTTPOptions then
                LNeedAutorization := True;
            end;
          end;
        407:
          begin // Proxy Server authorization requered
            if (FHTTP.InternalProxyAuthRetries >= FHTTP.AuthRetries) or not FHTTP.DoOnProxyAuthorization(Request, Response) then
            begin
              if Assigned(FHTTP.ProxyParams.Authentication) then
                FHTTP.ProxyParams.Authentication.Reset;
              RaiseException;
            end else begin
              if hoInProcessAuth in FHTTP.HTTPOptions then
                LNeedAutorization := True;
            end;
          end;
        else begin
          RaiseException;
        end;
      end;
    end;

    if FHTTP.Connected then begin
      if LNeedAutorization then begin
        // Read the content of Error message in temporary stream
        LTemp := FHTTP.ReadTimeout;
        FHTTP.ReadTimeout := 4000; // Lets wait 4 seconds for any kind of content
        try
          ReadContent;
        except end;
        FHTTP.ReadTimeout := LTemp;
        result := wnAuthRequest
      end
      else if (Response.ResponseCode <> 204) then
      begin
        FHTTP.ReadResult(Response);
        result := wnJustExit;
      end
      else
        result := wnJustExit;
    end;
  end;
end;

function TIdCustomHTTP.GetAuthRetries: Integer;
begin
  if Assigned(Request.Authentication) then begin
    result := Request.Authentication.AuthRetries;
  end else
    result := 0;
end;

function TIdCustomHTTP.GetProxyAuthRetries: Integer;
begin
  if Assigned(ProxyParams.Authentication) then begin
    result := ProxyParams.Authentication.AuthRetries;
  end else
    result := 0;
end;

end.

