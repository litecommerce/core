{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10099: IdCoderUUE.pas 
{
{   Rev 1.0    2002.11.12 10:33:16 PM  czhower
}
unit IdCoderUUE;

interface

uses
  Classes,
  IdCoder3to4;

type
  TIdDecoderUUEBase = class(TIdDecoder4to3)
  public
    procedure DecodeToStream(AIn: string; ADest: TStream); override;
  end;

  TIdDecoderUUE = class(TIdDecoderUUEBase)
  public
    constructor Create(AOwner: TComponent); override;
    procedure DecodeToStream(AIn: string; ADest: TStream); override;
  end;

  TIdEncoderUUEBase = class(TIdEncoder3to4)
  public
    function Encode(ASrcStream: TStream; const ABytes: integer = MaxInt): string; override;
  end;

  TIdEncoderUUE = class(TIdEncoderUUEBase)
  public
    constructor Create(AOwner: TComponent); override;
  end;

const
  GUUECodeTable: string = '`!"#$%&''()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_'; {Do not Localize}

var
  GUUEDecodeTable: TIdDecodeTable;

implementation

uses
  IdGlobal,
  SysUtils;

{ TIdEncoderUUE }

constructor TIdEncoderUUE.Create(AOwner: TComponent);
begin
  inherited;
  FCodingTable := GUUECodeTable;
  FFillChar := FCodingTable[1];
end;

{ TIdDecoderUUE }

constructor TIdDecoderUUE.Create(AOwner: TComponent);
begin
  inherited;
  FDecodeTable := GUUEDecodeTable;
  FFillChar := '~';  {Do not Localize}
end;

procedure TIdDecoderUUE.DecodeToStream(AIn: string; ADest: TStream);
begin
  // Older UUEncoders use space instead of `
  inherited DecodeToStream(StringReplace(AIn, ' ' , '`', [rfReplaceAll]), ADest);    {Do not Localize}
end;

{ TIdDecoderUUEBase }

procedure TIdDecoderUUEBase.DecodeToStream(AIn: string; ADest: TStream);
var
  LLength: integer;
begin
  if Length(AIn) > 0 then begin
    LLength := FDecodeTable[Ord(AIn[1])];
    Delete(AIn, 1, 1);
    case (LLength mod 3) of
      0: begin
          SetLength(AIn,(LLength div 3)*4);
//        SetLength(AIn, (LLength div 3) * 4 + 4);
//        AIn[Length(AIn) - 2] := FillChar;
//        AIn[Length(AIn) - 1] := FillChar;
//        AIn[Length(AIn)] := FillChar;
      end;
      1: begin
        SetLength(AIn, (LLength div 3) * 4 + 4);
        AIn[Length(AIn) - 1] := FillChar;
        AIn[Length(AIn)] := FillChar;
      end;
      2: begin
        SetLength(AIn, (LLength div 3) * 4 + 4);
        AIn[Length(AIn)] := FillChar;
      end;
    end;
    inherited DecodeToStream(AIn, ADest);
  end;
end;

{ TIdEncoderUUEBase }

function TIdEncoderUUEBase.Encode(ASrcStream: TStream; const ABytes: integer): string;
var
  LStart: Integer;
begin
  LStart := ASrcStream.Position;
  Result := inherited Encode(ASrcStream, ABytes);
  Result := FCodingTable[ASrcStream.Position - LStart + 1] + Result;
end;

initialization
  TIdDecoder4to3.ConstructDecodeTable(GUUECodeTable, GUUEDecodeTable);
end.
