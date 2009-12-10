{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10193: IdHTTPHeaderInfo.pas
{
{   Rev 1.2    20/4/2003 3:48:34 PM  SGrobety
{ Fix to previous fix (Dumb me)
}
{
{   Rev 1.0    2002.11.12 10:41:12 PM  czhower
}
{
  HTTP Header definition - RFC 2616

  Copyright: (c) Chad Z. Hower and The Indy Pit Crew.

  Author: Doychin Bondzhev (doychin@dsoft-bg.com)
}


unit IdHTTPHeaderInfo;

{
REVIEW:   public - Authentication: TIdAuthentication;
  This nees to be a property
}

interface

uses
  Classes, SysUtils, IdAuthentication, IdGlobal, IdHeaderList;

Type
  TIdEntityHeaderInfo = class(TPersistent)
  protected
    FCacheControl: String;
    FRawHeaders: TIdHeaderList;
    FConnection: string;
    FContentEncoding: string;
    FContentLanguage: string;
    FContentLength: Integer;
    FContentRangeEnd: Cardinal;
    FContentRangeStart: Cardinal;
    FContentType: string;
    FContentVersion: string;
    FCustomHeaders: TIdHeaderList;
    FDate: TDateTime;
    FExpires: TDateTime;
    FLastModified: TDateTime;
    FPragma: string;
    FHasContentLength: Boolean;
    //
    procedure AssignTo(Destination: TPersistent); override;
    procedure ProcessHeaders; virtual;
    procedure SetHeaders; virtual;
    procedure SetContentLength(const AValue: Integer);
    procedure SetCustomHeaders(const AValue: TIdHeaderList);
  public
    procedure Clear; virtual;
    constructor Create; virtual;
    destructor Destroy; override;
    //
    property HasContentLength: Boolean read FHasContentLength;
    property RawHeaders: TIdHeaderList read FRawHeaders;
  published
    property CacheControl: String read FCacheControl write FCacheControl;
    property Connection: string read FConnection write FConnection;
    property ContentEncoding: string read FContentEncoding write FContentEncoding;
    property ContentLanguage: string read FContentLanguage write FContentLanguage;
    property ContentLength: Integer read FContentLength write SetContentLength;
    property ContentRangeEnd: Cardinal read FContentRangeEnd write FContentRangeEnd;
    property ContentRangeStart: Cardinal read FContentRangeStart write FContentRangeStart;
    property ContentType: string read FContentType write FContentType;
    property ContentVersion: string read FContentVersion write FContentVersion;
    property CustomHeaders: TIdHeaderList read FCustomHeaders write SetCustomHeaders;
    property Date: TDateTime read FDate write FDate;
    property Expires: TDateTime read FExpires write FExpires;
    property LastModified: TDateTime read FLastModified write FLastModified;
    property Pragma: string read FPragma write FPragma;
  end;

  TIdProxyConnectionInfo = class(TPersistent)
  protected
    FAuthentication: TIdAuthentication;
    FPassword: string;
    FPort: Integer;
    FServer: string;
    FUsername: string;
    FBasicByDefault: Boolean;

    procedure AssignTo(Destination: TPersistent); override;
    procedure SetProxyPort(const Value: Integer);
    procedure SetProxyServer(const Value: string);
  public
    constructor Create;
    procedure Clear;
    destructor Destroy; override;
    procedure SetHeaders(Headers: TIdHeaderList);
    //
    property Authentication: TIdAuthentication read FAuthentication write FAuthentication;
  published

    property BasicAuthentication: boolean read FBasicByDefault write FBasicByDefault;
    property ProxyPassword: string read FPassword write FPassword;
    property ProxyPort: Integer read FPort write SetProxyPort;
    property ProxyServer: string read FServer write SetProxyServer;
    property ProxyUsername: string read FUsername write FUserName;
  end;

  TIdRequestHeaderInfo = class(TIdEntityHeaderInfo)
  protected
    FAccept: string;
    FAcceptCharSet: string;
    FAcceptEncoding: string;
    FAcceptLanguage: string;
    FExpect: string;
    FFrom: string;
    FPassword: string;
    FReferer: string;
    FUserAgent: string;
    FUserName: string;
    FHost: string;
    FBasicByDefault: Boolean;
    FProxyConnection: string;
    //
    procedure AssignTo(Destination: TPersistent); override;
  public
    Authentication: TIdAuthentication;
    //
    procedure Clear; override;
    procedure ProcessHeaders; override;
    procedure SetHeaders; override;
  published
    property Accept: string read FAccept write FAccept;
    property AcceptCharSet: string read FAcceptCharSet write FAcceptCharSet;
    property AcceptEncoding: string read FAcceptEncoding write FAcceptEncoding;
    property AcceptLanguage: string read FAcceptLanguage write FAcceptLanguage;
    property BasicAuthentication: boolean read FBasicByDefault write FBasicByDefault;
    property Host: string read FHost write FHost;
    property From: string read FFrom write FFrom;
    property Password: String read FPassword write FPassword;
    property Referer: string read FReferer write FReferer;
    property UserAgent: string read FUserAgent write FUserAgent;
    property Username: String read FUsername write FUsername;
    property ProxyConnection: string read FProxyConnection write FProxyConnection;
  end;

  TIdResponseHeaderInfo = class(TIdEntityHeaderInfo)
  protected
    FLocation: string;
    FServer: string;
    FProxyConnection: string;
    FProxyAuthenticate: TIdHeaderList;
    FWWWAuthenticate: TIdHeaderList;
    //
    procedure SetProxyAuthenticate(const Value: TIdHeaderList);
    procedure SetWWWAuthenticate(const Value: TIdHeaderList);
  public
    procedure Clear; override;
    constructor Create; override;
    destructor Destroy; override;
    procedure ProcessHeaders; override;
  published
    property Location: string read FLocation write FLocation;
    property ProxyConnection: string read FProxyConnection write FProxyConnection;
    property ProxyAuthenticate: TIdHeaderList read FProxyAuthenticate write SetProxyAuthenticate;
    property Server: string read FServer write FServer;
    property WWWAuthenticate: TIdHeaderList read FWWWAuthenticate write SetWWWAuthenticate;
  end;

implementation

const
  DefaultUserAgent = 'Mozilla/3.0 (compatible; Indy Library)'; {do not localize}

{ TIdGeneralHeaderInfo }

constructor TIdEntityHeaderInfo.Create;
begin
  inherited Create;

  FRawHeaders := TIdHeaderList.Create;
  FRawHeaders.FoldLength := 1024;

  FCustomHeaders := TIdHeaderList.Create;

  Clear;
end;

destructor TIdEntityHeaderInfo.Destroy;
begin
  FreeAndNil(FRawHeaders);
  FreeAndNil(FCustomHeaders);
  inherited Destroy;
end;

procedure TIdEntityHeaderInfo.AssignTo(Destination: TPersistent);
begin
  if Destination is TIdEntityHeaderInfo then
  begin
    with Destination as TIdEntityHeaderInfo do
    begin
      FRawHeaders.Assign(Self.FRawHeaders);
      FContentEncoding := Self.FContentEncoding;
      FContentLanguage := Self.FContentLanguage;
      FContentLength := Self.FContentLength;
      FContentRangeEnd:= Self.FContentRangeEnd;
      FContentRangeStart:= Self.FContentRangeStart;
      FContentType := Self.FContentType;
      FContentVersion := Self.FContentVersion;
      FDate := Self.FDate;
      FExpires := Self.FExpires;
      FLastModified := Self.FLastModified;
    end;
  end
  else
    inherited AssignTo(Destination);
end;

procedure TIdEntityHeaderInfo.Clear;
begin
  FConnection := '';
  FContentVersion := '';
  FContentEncoding := '';
  FContentLanguage := '';
  // S.G. 20/4/2003: Was FContentType := 'Text/HTML'
  // S.G. 20/4/2003: Shouldn't be set here but in response.
  // S.G. 20/4/2003: Requests, by default, have NO content-type. This caused problems
  // S.G. 20/4/2003: with some netscape servers
  FContentType := '';
  FContentLength := -1;
  FContentRangeStart := 0;
  FContentRangeEnd := 0;
  FDate := 0;
  FLastModified := 0;
  FExpires := 0;
  FRawHeaders.Clear;
end;

procedure TIdEntityHeaderInfo.ProcessHeaders;
Var
  LSecs, LMinutes, LHours: Integer;
begin
  // Set and Delete so that later we copy remaining to optional headers
  with FRawHeaders do
  begin
    FConnection := Values['Connection']; {do not localize}
    FContentVersion := Values['Content-Version']; {do not localize}
    FContentEncoding := Values['Content-Encoding']; {do not localize}
    FContentLanguage := Values['Content-Language']; {do not localize}
    FContentType := Values['Content-Type']; {do not localize}
    FContentLength := StrToIntDef(Trim(Values['Content-Length']), -1); {do not localize}
    FHasContentLength := FContentLength >= 0;

    FDate := idGlobal.GMTToLocalDateTime(Values['Date']); {do not localize}
    FLastModified := GMTToLocalDateTime(Values['Last-Modified']); {do not localize}
    if StrToIntDef(Values['Expires'], -1) <> -1 then begin
      // This is happening when expires is returned as integer number in seconds
      LSecs := StrToInt(Values['Expires']);
      LHours := LSecs div 3600;
      LMinutes := (LSecs mod 3600) div 60;
      LSecs := (LSecs mod 3600) mod 60;
      FExpires := Now + EncodeTime(LHours, LMinutes, LSecs, 0);
    end
    else begin
      FExpires := GMTToLocalDateTime(Values['Expires']); {do not localize}
    end;
    FPragma := Values['Pragma'];  {do not localize}
  end;
end;

procedure TIdEntityHeaderInfo.SetHeaders;
begin
  RawHeaders.Clear;
  with RawHeaders do
  begin
    if Length(FConnection) > 0 then
    begin
      Values['Connection'] := FConnection; {do not localize}
    end;
    if Length(FContentVersion) > 0 then
    begin
      Values['Content-Version'] := FContentVersion; {do not localize}
    end;
    if Length(FContentEncoding) > 0 then
    begin
      Values['Content-Encoding'] := FContentEncoding; {do not localize}
    end;
    if Length(FContentLanguage) > 0 then
    begin
      Values['Content-Language'] := FContentLanguage; {do not localize}
    end;
    if Length(FContentType) > 0 then
    begin
      Values['Content-Type'] := FContentType; {do not localize}
    end;
    if FContentLength >= 0 then
    begin
      Values['Content-Length'] := IntToStr(FContentLength); {do not localize}
    end;
    if Length(FCacheControl) > 0 then
    begin
      Values['Cache-control'] := FCacheControl; {do not localize}
    end;
    if FDate > 0 then
    begin
      Values['Date'] := DateTimeToInternetStr(FDate); {do not localize}
    end;
    if FExpires > 0 then
    begin
      Values['Expires'] := DateTimeToInternetStr(FExpires); {do not localize}
    end;

    if (FLastModified > 0) then
    begin
      Values['Last-Modified'] := DateTimeGMTToHttpStr(FLastModified); { do not localize}
    end;

    if Length(FPragma) > 0 then
    begin
      Values['Pragma'] := FPragma; {do not localize}
    end;

    if FCustomHeaders.Count > 0 then
    begin
      // Append Custom headers
      Text := Text + FCustomHeaders.Text;
    end;
  end;
end;

{ TIdProxyConnectionInfo }

constructor TIdProxyConnectionInfo.Create;
begin
  inherited Create;
  Clear;
end;

destructor TIdProxyConnectionInfo.Destroy;
begin
  if Assigned(FAuthentication) then
  begin
    FreeAndNil(FAuthentication);
  end;
  inherited Destroy;
end;

procedure TIdProxyConnectionInfo.AssignTo(Destination: TPersistent);
begin
  if Destination is TIdProxyConnectionInfo then
  begin
    with Destination as TIdProxyConnectionInfo do
    begin
      FPassword := Self.FPassword;
      FPort := Self.FPort;
      FServer := Self.FServer;
      FUsername := Self.FUsername;
      FBasicByDefault := Self.FBasicByDefault;
    end;
  end
  else inherited AssignTo(Destination);
end;

procedure TIdProxyConnectionInfo.Clear;
begin
  FServer := '';
  FUsername := '';
  FPassword := '';
  FPort := 0;
end;

procedure TIdProxyConnectionInfo.SetHeaders(Headers: TIdHeaderList);
Var
  S: String;
begin
  with Headers do
  begin
    if Assigned(Authentication) then
    begin
      S := Authentication.Authentication;
      if Length(S) > 0 then
      begin
        Values['Proxy-Authorization'] := S;
      end
      else
    end
    else begin  // Use Basic authentication by default
      if FBasicByDefault then
      begin
        FAuthentication := TIdBasicAuthentication.Create;
        with Authentication do
        begin
          Params.Values['Username'] := Self.FUsername;
          Params.Values['Password'] := Self.FPassword;
          S := Authentication;
        end;

        if Length(S) > 0 then
        begin
          Values['Proxy-Authorization'] := S;
        end;
      end;
    end;
  end;
end;

procedure TIdProxyConnectionInfo.SetProxyPort(const Value: Integer);
begin
  if Value <> FPort then
    FreeAndNil(FAuthentication);
  FPort := Value;
end;

procedure TIdProxyConnectionInfo.SetProxyServer(const Value: string);
begin
  if not AnsiSameText(Value, FServer) then
    FreeAndNil(FAuthentication);
  FServer := Value;
end;

{ TIdRequestHeaderInfo }

procedure TIdRequestHeaderInfo.ProcessHeaders;
var
  RangeDecode: string;
begin
  // Set and Delete so that later we copy remaining to optional headers
  with FRawHeaders do
  begin
    FAccept := Values['Accept']; {do not localize}
    FAcceptCharSet := Values['Accept-Charset']; {do not localize}
    FAcceptEncoding := Values['Accept-Encoding']; {do not localize}
    FAcceptLanguage := Values['Accept-Language']; {do not localize}
    FHost := Values['Host']; {do not localize}
    FFrom := Values['From']; {do not localize}
    FReferer := Values['Referer']; {do not localize}
    FUserAgent := Values['User-Agent']; {do not localize}
    RangeDecode := Values['Range']; {do not localize}

    if RangeDecode <> '' then
    begin
      Fetch(RangeDecode, '=');
      FContentRangeStart := StrToIntDef(Fetch(RangeDecode,'-'), 0);
      FContentRangeEnd := StrToIntDef(Fetch(RangeDecode), 0);
    end;
  end;
  inherited ProcessHeaders;
end;

procedure TIdRequestHeaderInfo.AssignTo(Destination: TPersistent);
begin
  if Destination is TIdRequestHeaderInfo then
  begin
    with Destination as TIdRequestHeaderInfo do
    begin
      FAccept := Self.FAccept;
      FAcceptCharSet := Self.FAcceptCharset;
      FAcceptEncoding := Self.FAcceptEncoding;
      FAcceptLanguage := Self.FAcceptLanguage;

      FFrom := Self.FFrom;
      FPassword := Self.FPassword;
      FReferer := Self.FReferer;
      FUserAgent := Self.FUserAgent;
      FUsername := Self.FUsername;
      FBasicByDefault := Self.FBasicByDefault;
    end;
  end
  else
  inherited AssignTo(Destination);
end;

procedure TIdRequestHeaderInfo.Clear;
begin
  FAccept := 'text/html, */*'; {do not localize}
  FAcceptCharSet := '';
  FUserAgent := DefaultUserAgent;

  FBasicByDefault := false;

  inherited Clear;
end;

procedure TIdRequestHeaderInfo.SetHeaders;
Var
  S: String;
begin
  inherited SetHeaders;

  with RawHeaders do
  begin
    if Length(FProxyConnection) > 0 then
    begin
      Values['Proxy-Connection'] := FProxyConnection; {do not localize}
    end;
    if Length(FHost) > 0 then
    begin
      Values['Host'] := FHost; {do not localize}
    end;
    if Length(FAccept) > 0 then
    begin
      Values['Accept'] := FAccept; {do not localize}
    end;
    if Length(FAcceptCharset) > 0 then
    begin
      Values['Accept-Charset'] := FAcceptCharSet;
    end;
    if Length(FAcceptEncoding) > 0 then
    begin
      Values['Accept-Encoding'] := FAcceptEncoding; {do not localize}
    end;
    if Length(FAcceptLanguage) > 0 then
    begin
      Values['Accept-Language'] := FAcceptLanguage; {do not localize}
    end;
    if Length(FFrom) > 0 then
    begin
      Values['From'] := FFrom; {do not localize}
    end;
    if Length(FReferer) > 0 then
    begin
      Values['Referer'] := FReferer; {do not localize}
    end;
    if Length(FUserAgent) > 0 then
    begin
      Values['User-Agent'] := FUserAgent; {do not localize}
    end;

    if FLastModified > 0 then
    begin
      Values['If-Modified-Since'] := DateTimeToInternetStr(FLastModified); {do not localize}
    end;

    if (FContentRangeStart <> 0) or (FContentRangeEnd <> 0) then
    begin
      if FContentRangeEnd <> 0 then
      begin
        Values['Range'] := 'bytes=' + IntToStr(FContentRangeStart) + '-' + IntToStr(FContentRangeEnd);  {do not localize}
      end else begin
        Values['Range'] := 'bytes=' + IntToStr(FContentRangeStart) + '-'; {do not localize}
      end;
    end;

    if Assigned(Authentication) then
    begin
      S := Authentication.Authentication;
      if Length(S) > 0 then
      begin
        Values['Authorization'] := S; {do not localize}
      end;
    end
    else begin  // Use Basic authentication by default
      if FBasicByDefault then
      begin
        Authentication := TIdBasicAuthentication.Create;
        with Authentication do
        begin
          Params.Values['Username'] := Self.FUserName;
          Params.Values['Password'] := Self.FPassword;
          S := Authentication;
        end;

        if Length(S) > 0 then
        begin
          Values['Authorization'] := S; {do not localize}
        end;
      end;
    end;
  end;
end;

{ TIdResponseHeaderInfo }

constructor TIdResponseHeaderInfo.Create;
begin
  inherited Create;

  FContentType := 'text/html';
  FWWWAuthenticate := TIdHeaderList.Create;
  FProxyAuthenticate := TIdHeaderList.Create;
end;

destructor TIdResponseHeaderInfo.Destroy;
begin
  FreeAndNil(FWWWAuthenticate);
  FreeAndNil(FProxyAuthenticate);
  inherited Destroy;
end;

procedure TIdResponseHeaderInfo.SetProxyAuthenticate(const Value: TIdHeaderList);
begin
  FProxyAuthenticate.Assign(Value);
end;

procedure TIdResponseHeaderInfo.SetWWWAuthenticate(const Value: TIdHeaderList);
begin
  FWWWAuthenticate.Assign(Value);
end;

procedure TIdResponseHeaderInfo.ProcessHeaders;
Var
  RangeDecode: string;
begin
  with FRawHeaders do
  begin;
    FLocation := Values['Location']; {do not localize}
    FServer := Values['Server']; {do not localize}
    FProxyConnection := Values['Proxy-Connection']; {do not localize}

    RangeDecode := Values['Content-Range']; {do not localize}
    if RangeDecode <> '' then
    begin
      Fetch(RangeDecode);
      FContentRangeStart := StrToInt(Fetch(RangeDecode,'-'));
      FContentRangeEnd := StrToInt(Fetch(RangeDecode,'/'));
    end else begin
      // Reset range variables if a range isn't given
      FContentRangeStart := 0;
      FContentRangeEnd := 0;
    end;
    FWWWAuthenticate.Clear;
    Extract('WWW-Authenticate', FWWWAuthenticate);   {do not localize}

    FProxyAuthenticate.Clear;
    Extract('Proxy-Authenticate', FProxyAuthenticate); {do not localize}
  end;

  inherited ProcessHeaders;
end;

procedure TIdResponseHeaderInfo.Clear;
begin
  inherited Clear;
  // S.G. 20/4/2003: Default to text/HTML
  FContentType := 'text/html';

  FLocation := '';
  FServer := '';
  if Assigned(FProxyAuthenticate) then
  begin
    FProxyAuthenticate.Clear;
  end;

  if Assigned(FWWWAuthenticate) then
  begin
    FWWWAuthenticate.Clear;
  end;
end;

procedure TIdEntityHeaderInfo.SetCustomHeaders(const AValue: TIdHeaderList);
begin
  FCustomHeaders.Assign(AValue);
end;

procedure TIdEntityHeaderInfo.SetContentLength(const AValue: Integer);
begin
  FContentLength := AValue;
  FHasContentLength := FContentLength >= 0;
end;

end.
