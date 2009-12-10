{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10257: IdMessageCoderMIME.pas 
{
    Rev 1.4    6/14/2003 10:40:36 AM  BGooijen
  fix for the bug where the attachments are empty
}
{
{   Rev 1.2    5/23/03 9:51:04 AM  RLebeau
{ Minor tweak to previous fix.
}
{
{   Rev 1.1    5/23/03 9:43:12 AM  RLebeau
{ Fixed bugs where message body is parsed incorrectly when MIMEBoundary is
{ empty.
}
{
{   Rev 1.0    2002.11.12 10:46:04 PM  czhower
}
unit IdMessageCoderMIME;

// for all 3 to 4s:
//// TODO: Predict output sizes and presize outputs, then use move on
// presized outputs when possible, or presize only and reposition if stream

interface

uses
  Classes,
  IdMessageCoder, IdMessage;

type
  TIdMessageDecoderMIME = class(TIdMessageDecoder)
  protected
    FFirstLine: string;
    FBodyEncoded: Boolean;
    FMIMEBoundary: string;
  public
    constructor Create(AOwner: TComponent); reintroduce; overload;
    constructor Create(AOwner: TComponent; ALine: string); reintroduce; overload;
    function ReadBody(ADestStream: TStream;
      var VMsgEnd: Boolean): TIdMessageDecoder; override;
    procedure ReadHeader; override;
    //
    property MIMEBoundary: string read FMIMEBoundary write FMIMEBoundary;
    property BodyEncoded: Boolean read FBodyEncoded write FBodyEncoded;
  end;

  TIdMessageDecoderInfoMIME = class(TIdMessageDecoderInfo)
  public
    function CheckForStart(ASender: TIdMessage; ALine: string): TIdMessageDecoder; override;
  end;

  TIdMessageEncoderMIME = class(TIdMessageEncoder)
  public
    procedure Encode(ASrc: TStream; ADest: TStream); override;
  end;

  TIdMessageEncoderInfoMIME = class(TIdMessageEncoderInfo)
  public
    constructor Create; override;
    procedure InitializeHeaders(AMsg: TIdMessage); override;
  end;

const
  IndyMIMEBoundary = '=_NextPart_2rfkindysadvnqw3nerasdf'; {do not localize}
  IndyMultiPartAlternativeBoundary = '=_NextPart_2altrfkindysadvnqw3nerasdf'; {do not localize}
  IndyMultiPartRelatedBoundary = '=_NextPart_2relrfksadvnqindyw3nerasdf'; {do not localize}
  MIMEGenericText = 'text/'; {do not localize}
  MIME7Bit = '7bit'; {do not localize}

implementation

uses
  IdCoder, IdCoderMIME, IdException, IdGlobal, IdResourceStrings, IdCoderQuotedPrintable,
  SysUtils, IdCoderHeader;

{ TIdMessageDecoderInfoMIME }

function TIdMessageDecoderInfoMIME.CheckForStart(ASender: TIdMessage;
 ALine: string): TIdMessageDecoder;
begin
  if (ASender.MIMEBoundary.Boundary <> '') And AnsiSameText(ALine, '--' + ASender.MIMEBoundary.Boundary) then begin    {Do not Localize}
    Result := TIdMessageDecoderMIME.Create(ASender);
  end else if AnsiSameText(ASender.ContentTransferEncoding, 'base64') or    {Do not Localize}
    AnsiSameText(ASender.ContentTransferEncoding, 'quoted-printable') then begin    {Do not Localize}
      Result := TIdMessageDecoderMIME.Create(ASender, ALine);
  end else begin
    Result := nil;
  end;
end;

{ TIdCoderMIME }

constructor TIdMessageDecoderMIME.Create(AOwner: TComponent);
begin
  inherited;
  if AOwner is TIdMessage then begin
    FMIMEBoundary := TIdMessage(AOwner).MIMEBoundary.Boundary;
    if (TIdMessage(AOwner).ContentTransferEncoding <> '') and
      (TIdMessage(AOwner).ContentTransferEncoding <> '7bit')  then begin
      FBodyEncoded := True;
    end else begin
      FBodyEncoded := False;
    end;
  end;
end;

constructor TIdMessageDecoderMIME.Create(AOwner: TComponent; ALine: string);
begin
  Create(AOwner);
  FFirstLine := ALine;
end;

function TIdMessageDecoderMIME.ReadBody(ADestStream: TStream; var VMsgEnd: Boolean): TIdMessageDecoder;
var
  s: string;
  LDecoder: TIdDecoder;
  LLine: string;
begin
  VMsgEnd := FALSE;
  Result := nil;
  if FBodyEncoded then begin
    s := TIdMessage(Owner).ContentTransferEncoding;
  end else begin
    s := FHeaders.Values['Content-Transfer-Encoding']; {Do not Localize}
  end;
  if AnsiSameText(s, 'base64') then begin {Do not Localize}
    LDecoder := TIdDecoderMIME.Create(nil);
  end else if AnsiSameText(s, 'quoted-printable') then begin {Do not Localize}
    LDecoder := TIdDecoderQuotedPrintable.Create(nil);
  end else begin
    LDecoder := nil;
  end;
  try
    repeat
      if FFirstLine = '' then begin // TODO: Improve this. Not very efficient
        LLine := ReadLn;
      end else begin
        LLine := FFirstLine;
        FFirstLine := '';    {Do not Localize}
      end;
      if LLine = '.' then begin // Do not use ADELIM since always ends with . (standard) {Do not Localize}
        VMsgEnd := True;
        Break;
      end;
      // New boundary - end self and create new coder
      if MIMEBoundary <> '' then begin
        if AnsiSameText(LLine, '--' + MIMEBoundary) then begin    {Do not Localize}
          Result := TIdMessageDecoderMIME.Create(Owner);
          Break;
        // End of all coders (not quite ALL coders)
        end
        else if AnsiSameText(LLine, '--' + MIMEBoundary + '--') then begin    {Do not Localize}
          // POP the boundary
          if Owner is TIdMessage then begin
            TIdMessage(Owner).MIMEBoundary.Pop;
          end;
          Break;
        // Data to save, but not decode
        end else if LDecoder = nil then begin
          if (Length(LLine) > 0) and (LLine[1] = '.') then begin // Process . in front for no encoding    {Do not Localize}
            Delete(LLine, 1, 1);
          end;
          LLine := LLine + EOL;
          ADestStream.WriteBuffer(LLine[1], Length(LLine));
        // Data to decode
        end else begin
          //for TIdDecoderQuotedPrintable, we have
          //to make sure all EOLs are intact
          if LDecoder is TIdDecoderQuotedPrintable then begin
            LDecoder.DecodeToStream(LLine+EOL,ADestStream);
          end else if LLine <> '' then begin
            LDecoder.DecodeToStream(LLine, ADestStream);
          end;
        end;
      end;
    until False;
  finally FreeAndNil(LDecoder); end;
end;

procedure TIdMessageDecoderMIME.ReadHeader;
var
  ABoundary,
  s: string;
  LLine: string;

  procedure CheckAndSetType(AContentType, AContentDisposition: string);
  var
    S: string;
    LFileNamePos: Integer; //APR BugFix #515207
  begin
    s := AContentDisposition;
    s := Fetch(s, ';');    {Do not Localize}
    // Content-Disposition: inline; - Even this we treat as attachment. It can easily
    // contain binary data which text part is not suited for.
    if (AnsiSameText(s, 'attachment')) or (IndyPos('NAME', UpperCase(AContentType)) > 0) then begin  {Do not Localize}
      FPartType := mcptAttachment;
      s := AContentDisposition;

      LFileNamePos := IndyPos('FILENAME', UpperCase(s));
      if LFileNamePos > 0 then begin
        s := Copy(s, LFileNamePos + 9, Length(s));    {Do not Localize}
      end else begin
        S := ''; //FileName not found
      end;
      if Length(s) = 0 then begin
        // Get filename from Content-Type
        s := AContentType;
        s := Copy(s, IndyPos('NAME', UpperCase(s)) + 5, Length(s));    {Do not Localize}
      end;
      if Length(s) > 0 then begin
        if s[1] = '"' then begin    {Do not Localize}
          Fetch(s, '"');    {Do not Localize}
          FFilename := Fetch(s, '"');    {Do not Localize}
        end else begin
          FFilename := s;
        end;
        FFilename := DecodeHeader(FFileName);
      end;
    end else begin
      FPartType := mcptText;
    end;
  end;

begin
  if FBodyEncoded then begin // Read header from the actual message since body parts don't exist    {Do not Localize}
    CheckAndSetType(TIdMessage(Owner).ContentType, TIdMessage(OWner).ContentDisposition);
  end else begin
    // Read header
    repeat
      LLine := ReadLn;
      if LLine = '.' then begin // TODO: abnormal situation (Masters!)    {Do not Localize}
        FPartType := mcptUnknown;
        Exit;
      end;//if
      if LLine = '' then begin
        Break;
      end;
      if LLine[1] in LWS then begin
        if FHeaders.Count > 0 then begin
          FHeaders[FHeaders.Count - 1] := FHeaders[FHeaders.Count - 1] + ' ' + Copy(LLine, 2, MaxInt);    {Do not Localize}
        end else begin
          FHeaders.Add(StringReplace(Copy(LLine, 2, MaxInt), ': ', '=', [])); {Do not Localize}
        end;
      end else begin
        FHeaders.Add(StringReplace(LLine, ': ', '=', []));    {Do not Localize}
      end;
    until False;
    s := FHeaders.Values['Content-Type'];    {Do not Localize}
    ABoundary := TIdMIMEBoundary.FindBoundary(s);
    if Length(ABoundary) > 0 then begin
      if Owner is TIdMessage then begin
        TIdMessage(Owner).MIMEBoundary.Push(ABoundary);
        // Also update current boundary
        FMIMEBoundary := ABoundary;
      end;
    end;
    CheckAndSetType(FHeaders.Values['Content-Type']    {Do not Localize}
     , FHeaders.Values['Content-Disposition']);    {Do not Localize}
  end;
end;

{ TIdMessageEncoderInfoMIME }

constructor TIdMessageEncoderInfoMIME.Create;
begin
  inherited;
  FMessageEncoderClass := TIdMessageEncoderMIME;
end;

procedure TIdMessageEncoderInfoMIME.InitializeHeaders(AMsg: TIdMessage);
begin
  if AMsg.MessageParts.RelatedPartCount > 0 then begin
    AMsg.ContentType
     := 'multipart/related; type="multipart/alternative"; boundary="' + {do not localize}
     IndyMultiPartRelatedBoundary + '"';    {Do not Localize}
  end else begin
    if AMsg.MessageParts.AttachmentCount > 0 then begin
      AMsg.ContentType := 'multipart/mixed; boundary="' {do not localize}
       + IndyMIMEBoundary + '"';    {Do not Localize}
    end else begin
      if AMsg.MessageParts.TextPartCount > 0 then begin
        AMsg.ContentType :=
         'multipart/alternative; boundary="' {do not localize}
         + IndyMIMEBoundary + '"';    {Do not Localize}
      end;
    end;
  end;
end;

{ TIdMessageEncoderMIME }

procedure TIdMessageEncoderMIME.Encode(ASrc, ADest: TStream);
var
  s: string;
  LEncoder: TIdEncoderMIME;
  LSPos, LSSize : Int64;
begin
  ASrc.Position := 0;
  LSPos := 0;
  LSSize := ASrc.Size;
  LEncoder := TIdEncoderMIME.Create(nil); try
    while LSPos < LSSize do begin
      s := LEncoder.Encode(ASrc, 57) + EOL;
      Inc(LSPos,57);
      ADest.WriteBuffer(s[1], Length(s));
    end;
  finally FreeAndNil(LEncoder); end;
end;

initialization
  TIdMessageDecoderList.RegisterDecoder('MIME'    {Do not Localize}
   , TIdMessageDecoderInfoMIME.Create);
  TIdMessageEncoderList.RegisterEncoder('MIME'    {Do not Localize}
   , TIdMessageEncoderInfoMIME.Create);
end.
