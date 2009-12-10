{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10259: IdMessageCoderUUE.pas 
{
{   Rev 1.0    2002.11.12 10:46:14 PM  czhower
}
unit IdMessageCoderUUE;

interface

uses
  Classes,
  IdCoder3to4, IdMessageCoder, IdMessage;

type
  TIdMessageDecoderUUE = class(TIdMessageDecoder)
  public
    function ReadBody(ADestStream: TStream; var AMsgEnd: Boolean): TIdMessageDecoder; override;
  end;

  TIdMessageDecoderInfoUUE = class(TIdMessageDecoderInfo)
  public
    function CheckForStart(ASender: TIdMessage; ALine: string): TIdMessageDecoder; override;
  end;

  TIdMessageEncoderUUEBase = class(TIdMessageEncoder)
  protected
    FEncoderClass: TIdEncoder3to4Class;
  public
    procedure Encode(ASrc: TStream; ADest: TStream); override;
  end;

  TIdMessageEncoderUUE = class(TIdMessageEncoderUUEBase)
  public
    constructor Create(AOwner: TComponent); override;
  end;

  TIdMessageEncoderInfoUUE = class(TIdMessageEncoderInfo)
  public
    constructor Create; override;
  end;

implementation

uses
  IdCoderUUE, IdCoderXXE, IdException, IdGlobal, IdResourceStrings,
  SysUtils;

{ TIdMessageDecoderInfoUUE }

function TIdMessageDecoderInfoUUE.CheckForStart(ASender: TIdMessage;
 ALine: string): TIdMessageDecoder;
var
  LPermissionCode: integer;
begin
  LPermissionCode := StrToIntDef(Copy(ALine, 7, 3), 0);
  if AnsiSameText(Copy(ALine, 1, 6), 'begin ') and (Copy(ALine, 10, 1) = ' ') and (LPermissionCode > 0)    {Do not Localize}
   then begin
    Result := TIdMessageDecoderUUE.Create(ASender);
    with TIdMessageDecoderUUE(Result) do begin
      FFilename := Copy(ALine, 11, MaxInt);
      FPartType := mcptAttachment;
    end;
  end else begin
    Result := nil;
  end;
end;

{ TIdMessageDecoderUUE }

function TIdMessageDecoderUUE.ReadBody(ADestStream: TStream; var AMsgEnd: Boolean): TIdMessageDecoder;
var
  LDecoder: TIdDecoder4to3;
  LLine: string;
begin
  AMSgEnd := False;
  Result := nil;
  LLine := ReadLn;
  if (Length(LLine) > 0) then
  begin
    case LLine[1] of
      'M': begin    {Do not Localize}
        LDecoder := TIdDecoderUUE.Create(nil);
      end;
      'h': begin    {Do not Localize}
        LDecoder := TIdDecoderXXE.Create(nil);
      end;
      else begin
        raise EIdException.Create(RSUnrecognizedUUEEncodingScheme);
      end;
    end;
  end;
  try
    repeat
      if (Length(Trim(LLine)) = 0) or (LLine = LDecoder.FillChar) then begin
        // UUE: Comes on the line before end. Supposed to be `, but some put a
        // blank line instead
      end else begin
        LDecoder.DecodeToStream(LLine, ADestStream);
      end;
      LLine := ReadLn;
    until AnsiSameText(Trim(LLine), 'end');    {Do not Localize}
  finally FreeAndNil(LDecoder); end;
end;

{ TIdMessageEncoderInfoUUE }

constructor TIdMessageEncoderInfoUUE.Create;
begin
  inherited;
  FMessageEncoderClass := TIdMessageEncoderUUE;
end;

{ TIdMessageEncoderUUEBase }

procedure TIdMessageEncoderUUEBase.Encode(ASrc, ADest: TStream);
var
  s: string;
  LEncoder: TIdEncoder3to4;
begin
  ASrc.Position := 0;
  s := 'begin ' + IntToStr(PermissionCode) + ' ' + Filename + EOL;    {Do not Localize}
  ADest.WriteBuffer(s[1], Length(s));
  LEncoder := FEncoderClass.Create(nil); try
    while ASrc.Position < ASrc.Size do begin
      s := LEncoder.Encode(ASrc, 45) + EOL;
      ADest.WriteBuffer(s[1], Length(s));
    end;
    s := LEncoder.FillChar + EOL + 'end' + EOL;    {Do not Localize}
    ADest.WriteBuffer(s[1], Length(s));
  finally FreeAndNil(LEncoder); end;
end;

{ TIdMessageEncoderUUE }

constructor TIdMessageEncoderUUE.Create(AOwner: TComponent);
begin
  inherited;
  FEncoderClass := TIdEncoderUUE;
end;

initialization
  TIdMessageDecoderList.RegisterDecoder('UUE', TIdMessageDecoderInfoUUE.Create);    {Do not Localize}
  TIdMessageEncoderList.RegisterEncoder('UUE', TIdMessageEncoderInfoUUE.Create);    {Do not Localize}
end.
