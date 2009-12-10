{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10413: IdURI.pas 
{
{   Rev 1.1    29/11/2002 10:15:48 AM  SGrobety    Version: 1.1
{ Changed URL encoding
}
{
{   Rev 1.0    2002.11.12 10:59:20 PM  czhower
}
unit IdURI;

{Details of implementation
-------------------------
2002-Apr-14 Peter Mee
- Fixed reset.  Now resets FParams as well - wasn't before.
2001-Nov Doychin Bondzhev
 - Fixes in URLEncode. There is difference when encoding Path+Doc and Params
2001-Oct-17 Peter Mee
 - Minor speed improvement - removed use of NormalizePath in SetURI.
 - Fixed bug that was cutting off the first two chars of the host when a
    username / password present.
 - Fixed bug that prevented username and password being updated.
 - Fixed bug that was leaving the bookmark in the document when no ? or =
    parameters existed.
2001-Feb-18 Doychin Bondzhev
 - Added UserName and Password to support URI's like    
    http://username:password@hostname:port/path/document#bookmark
}

interface

Uses
  IdException;

type
  TIdURIOptionalFields = (ofAuthInfo, ofBookmark);
  TIdURIOptionalFieldsSet = set of TIdURIOptionalFields;

  TIdURI = class
  protected
    FDocument: string;
    FProtocol: string;
    FURI: String;
    FPort: string;
    Fpath: string;
    FHost: string;
    FBookmark: string;
    FUserName: string;
    FPassword: string;
    FParams: string;
    //
    procedure SetURI(const Value: String);
    function GetURI: String;
  public
    constructor Create(const AURI: string = ''); virtual;    {Do not Localize}
    function GetFullURI(const AOptionalFileds: TIdURIOptionalFieldsSet = [ofAuthInfo, ofBookmark]): String;
    class procedure NormalizePath(var APath: string);
    class function URLDecode(ASrc: string): string;
    class function URLEncode(const ASrc: string): string;
    class function ParamsEncode(const ASrc: string): string;
    class function PathEncode(const ASrc: string): string;
    //
    property Bookmark : string read FBookmark write FBookMark;
    property Document: string read FDocument write FDocument;
    property Host: string read FHost write FHost;
    property Password: string read FPassword write FPassword;
    property Path: string read FPath write FPath;
    property Params: string read FParams write FParams;
    property Port: string read FPort write FPort;
    property Protocol: string read FProtocol write FProtocol;
    property URI: string read GetURI write SetURI;
    property Username: string read FUserName write FUserName;
  end;

  EIdURIException = class(EIdException);

implementation

uses
  IdGlobal, IdResourceStrings,
  SysUtils;

constructor TIdURI.Create(const AURI: string = '');    {Do not Localize}
begin
  inherited Create;
  if length(AURI) > 0 then begin
    URI := AURI;
  end;
end;

class procedure TIdURI.NormalizePath(var APath: string);
var
  i: Integer;
begin
  // Normalize the directory delimiters to follow the UNIX syntax
  i := 1;
  while i <= Length(APath) do begin
    if APath[i] in LeadBytes then begin
      inc(i, 2)
    end else if APath[i] = '\' then begin    {Do not Localize}
      APath[i] := '/';    {Do not Localize}
      inc(i, 1);
    end else begin
      inc(i, 1);
    end;
  end;
end;

procedure TIdURI.SetURI(const Value: String);
var
  LBuffer: string;
  LTokenPos, LPramsPos: Integer;
  LURI: string;
begin
  FURI := Value;
  NormalizePath(FURI);
  LURI := FURI;
  FHost := '';    {Do not Localize}
  FProtocol := '';    {Do not Localize}
  FPath := '';    {Do not Localize}
  FDocument := '';    {Do not Localize}
  FPort := '';    {Do not Localize}
  FBookmark := '';    {Do not Localize}
  FUsername := '';    {Do not Localize}
  FPassword := '';    {Do not Localize}
  FParams := '';  {Do not localise}  //Peter Mee

  LTokenPos := IndyPos('://', LURI);    {Do not Localize}
  if LTokenPos > 0 then begin
    // absolute URI
    // What to do when data don't match configuration ??    {Do not Localize}
    // Get the protocol
    FProtocol := Copy(LURI, 1, LTokenPos  - 1);
    Delete(LURI, 1, LTokenPos + 2);
    // Get the user name, password, host and the port number
    LBuffer := Fetch(LURI, '/', True);    {Do not Localize}
    // Get username and password
    LTokenPos := IndyPos('@', LBuffer);    {Do not Localize}
    FPassword := Copy(LBuffer, 1, LTokenPos  - 1);
    if LTokenPos > 0 then
      Delete(LBuffer, 1, LTokenPos);
    FUserName := Fetch(FPassword, ':', True);    {Do not Localize}
    // Ignore cases where there is only password (http://:password@host/pat/doc)
    if Length(FUserName) = 0 then begin
      FPassword := '';    {Do not Localize}
    end;
    // Get the host and the port number
    FHost := Fetch(LBuffer, ':', True);    {Do not Localize}
    FPort := LBuffer;
    // Get the path
    LPramsPos := IndyPos('?', LURI);    {Do not Localize}
    if LPramsPos > 0 then begin // The case when there is parameters after the document name '?'    {Do not Localize}
      LTokenPos := RPos('/', LURI, LPramsPos);    {Do not Localize}
    end
    else begin
      LPramsPos := IndyPos('=', LURI);    {Do not Localize}
      if LPramsPos > 0 then begin // The case when there is parameters after the document name '='    {Do not Localize}
        LTokenPos := RPos('/', LURI, LPramsPos);    {Do not Localize}
      end
      else begin
        LTokenPos := RPos('/', LURI, -1);    {Do not Localize}
      end;
    end;

    FPath := '/' + Copy(LURI, 1, LTokenPos);    {Do not Localize}
    // Get the document
    if LPramsPos > 0 then begin
      FDocument := Copy(LURI, 1, LPramsPos - 1);
      Delete(LURI, 1, LPramsPos - 1);
      FParams := LURI;
    end
    else
    FDocument := LURI;
    Delete(FDocument, 1, LTokenPos);

    FBookmark := FDocument;
    FDocument := Fetch(FBookmark, '#');    {Do not Localize}
  end else begin
    // received an absolute path, not an URI
    LPramsPos := IndyPos('?', LURI);    {Do not Localize}
    if LPramsPos > 0 then begin // The case when there is parameters after the document name '?'    {Do not Localize}
      LTokenPos := RPos('/', LURI, LPramsPos);    {Do not Localize}
    end else begin
      LPramsPos := IndyPos('=', LURI);    {Do not Localize}
      if LPramsPos > 0 then begin // The case when there is parameters after the document name '='    {Do not Localize}
        LTokenPos := RPos('/', LURI, LPramsPos);    {Do not Localize}
      end else begin
        LTokenPos := RPos('/', LURI, -1);    {Do not Localize}
      end;
    end;

    FPath := Copy(LURI, 1, LTokenPos);
    // Get the document
    if LPramsPos > 0 then begin
      FDocument := Copy(LURI, 1, LPramsPos - 1);
      Delete(LURI, 1, LPramsPos - 1);
      FParams := LURI;
    end else begin
      FDocument := LURI;
    end;
    Delete(FDocument, 1, LTokenPos);
  end;

  // Parse the # bookmark from the document
  if Length(FBookmark) = 0 then begin
    FBookmark := FParams;
    FParams := Fetch(FBookmark, '#');    {Do not Localize}
  end;
end;

function TIdURI.GetURI: String;
begin
  FURI := GetFullURI;
  // result must contain only the proto://host/path/document
  // If you need the full URI then you have to call GetFullURI
  result := GetFullURI([]);
end;

class function TIdURI.URLDecode(ASrc: string): string;
var
  i: integer;
  ESC: string[2];
  CharCode: integer;
begin
  Result := '';    {Do not Localize}
  // S.G. 27/11/2002: Spaces is NOT to be encoded as "+".
  // S.G. 27/11/2002: "+" is a field separator in query parameter, space is...
  // S.G. 27/11/2002: well, a space
  // ASrc := StringReplace(ASrc, '+', ' ', [rfReplaceAll]);  {do not localize}
  i := 1;
  while i <= Length(ASrc) do begin
    if ASrc[i] <> '%' then begin  {do not localize}
      Result := Result + ASrc[i]
    end else begin
      Inc(i); // skip the % char
      ESC := Copy(ASrc, i, 2); // Copy the escape code
      Inc(i, 1); // Then skip it.
      try
        CharCode := StrToInt('$' + ESC);  {do not localize}
        if (CharCode > 0) and (CharCode < 256) then begin
          Result := Result + Char(CharCode);
        end;
      except end;
    end;
    Inc(i);
  end;
end;

class function TIdURI.ParamsEncode(const ASrc: string): string;
var
  i: Integer;
const
  UnsafeChars = ['*', '#', '%', '<', '>', ' ','[',']'];  {do not localize}
begin
  Result := '';    {Do not Localize}
  for i := 1 to Length(ASrc) do
  begin
    // S.G. 27/11/2002: Changed the parameter encoding: Even in parameters, a space
    // S.G. 27/11/2002: is much more likely to be meaning "space" than "this is
    // S.G. 27/11/2002: a new parameter"
    // S.G. 27/11/2002: ref: Message-ID: <3de30169@newsgroups.borland.com> borland.public.delphi.internet.winsock
    // S.G. 27/11/2002: Most low-ascii is actually Ok in parameters encoding.
    if (ASrc[i] in UnsafeChars) or (not (ord(ASrc[i])in [33..128])) then
    begin {do not localize}
      Result := Result + '%' + IntToHex(Ord(ASrc[i]), 2);  {do not localize}
    end
    else
    begin
      Result := Result + ASrc[i];
    end;
  end;
end;

class function TIdURI.PathEncode(const ASrc: string): string;
const
  UnsafeChars = ['*', '#', '%', '<', '>', '+', ' '];  {do not localize}
var
  i: Integer;
begin
  Result := '';    {Do not Localize}
  for i := 1 to Length(ASrc) do begin
    if (ASrc[i] in UnsafeChars) or (ASrc[i] >= #$80) or (ASrc[i] < #32) then begin
      Result := Result + '%' + IntToHex(Ord(ASrc[i]), 2);  {do not localize}
    end else begin
      Result := Result + ASrc[i];
    end;
  end;
end;

class function TIdURI.URLEncode(const ASrc: string): string;
Var
  LURI: TIdURI;
begin
  LURI := TIdURI.Create(ASrc);
  try
    LURI.Path := PathEncode(LURI.Path);
    LURI.Document := PathEncode(LURI.Document);
    LURI.Params := ParamsEncode(LURI.Params);
  finally
    result := LURI.URI;
    LURI.Free;
  end;
end;

function TIdURI.GetFullURI(
  const AOptionalFileds: TIdURIOptionalFieldsSet): String;
Var
  LURI: String;
begin
  if Length(FProtocol) = 0 then
    raise EIdURIException.Create(RSURINoProto);

  LURI := FProtocol + '://';    {Do not Localize}

  if (Length(FUserName) > 0) and (ofAuthInfo in AOptionalFileds) then begin
    LURI := LURI + FUserName;

    if Length(FPassword) > 0 then begin
      LURI := LURI + ':' + FPassword;    {Do not Localize}
    end;

    LURI := LURI + '@';    {Do not Localize}
  end;

  if Length(FHost) = 0 then
    raise EIdURIException.Create(RSURINoHost);
  LURI := LURI + FHost;
  if Length(FPort) > 0 then begin
    LURI := LURI + ':' + FPort;    {Do not Localize}
  end;
  LURI := LURI + FPath + FDocument + FParams;
  if (Length(FBookmark) > 0) and (ofBookmark in AOptionalFileds) then begin
    LURI := LURI + '#' + FBookmark;    {Do not Localize}
  end;
  result := LURI;
end;

end.
