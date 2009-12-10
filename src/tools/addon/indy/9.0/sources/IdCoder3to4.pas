{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10091: IdCoder3to4.pas 
{
{   Rev 1.3    28/05/2003 01:14:32  CCostelloe
{ StripCRLFs changes reversed out at the request of Chad
}
{
{   Rev 1.2    20/05/2003 02:02:24  CCostelloe
}
{
{   Rev 1.1    20/05/2003 01:39:14  CCostelloe
{ Bug fix: decoder code altered to ensure that any CRLFs inserted by an MTA are
{ removed
}
{
{   Rev 1.0    2002.11.12 10:32:28 PM  czhower
}
unit IdCoder3to4;

interface

uses
  Classes,
  IdCoder;

type
  TIdDecodeTable = array[1..127] of Byte;

  TIdEncoder3to4 = class(TIdEncoder)
  protected
    FCodingTable: string;
    FFillChar: Char;
  public
    function Encode(ASrcStream: TStream;
     const ABytes: Integer = MaxInt): string; override;
    procedure EncodeUnit(const AIn1, AIn2, AIn3: Byte; var VOut: Cardinal);
  published
    property CodingTable: string read FCodingTable;
    property FillChar: Char read FFillChar write FFillChar;
  end;

  TIdEncoder3to4Class = class of TIdEncoder3to4;

  TIdDecoder4to3 = class(TIdDecoder)
  protected
    FDecodeTable: TIdDecodeTable;
    FFillChar: Char;
  public
    class procedure ConstructDecodeTable(const ACodingTable: string;
     var ADecodeArray: TIdDecodeTable);
    procedure DecodeToStream(AIn: string; ADest: TStream); override;
    procedure DecodeUnit(AIn: Cardinal; var VOut1, VOut2, VOut3: Byte);
  published
    property FillChar: Char read FFillChar write FFillChar;
  end;

implementation

uses
  IdException, IdGlobal, IdResourceStrings,
  SysUtils;

{ TIdDecoder4to3 }

class procedure TIdDecoder4to3.ConstructDecodeTable(const ACodingTable: string;
 var ADecodeArray: TIdDecodeTable);
var
  i: integer;
begin
  //TODO: See if we can find an efficient way, or maybe an option to see if the requested
  //decode char is valid, that is it returns a 255 from the DecodeTable, or at maybe
  //check its presence in the encode table.
  for i := Low(ADecodeArray) to High(ADecodeArray) do begin
    ADecodeArray[i] := 255;
  end;
  for i := 1 to Length(ACodingTable) do begin
    ADecodeArray[Ord(ACodingTable[i])] := i - 1;
  end;
end;

procedure TIdDecoder4to3.DecodeToStream(AIn: string; ADest: TStream);
type
  ThreeByteRec = record
    case Integer of
     0: (Bytes : array[1..3] of Byte);
     1: (ThreeChars : array[1..3] of Char);
     2: (TwoChars : array[1..2] of Char; Waste1: Char);
     3: (OneChar: Char; Waste2: array[1..2] of Char);
  end;
var
  LBOut: ThreeByteRec;
  LOut: string;
  LUnit: TIdCardinalBytes;
  LInSize, LInPos : Integer;
  LOutBuf : String;
begin
  if (Length(AIn) mod 4) > 0 then begin
    raise EIdException.Create(RSUnevenSizeInDecodeStream);
  end;
  LOutBuf := '';    {Do not Localize}
  LInSize := Length(AIn);
  LInPos := 1;
  LOut := '';      {Do not Localize}
  while LInPos <= LInSize do begin
    Move(AIn[LInPos], LUnit, SizeOf(LUnit));
    Inc(LInPos, SizeOf(LUnit));
    DecodeUnit(LUnit.Whole, LBOut.Bytes[1], LBOut.Bytes[2], LBOut.Bytes[3]);
    // Must check Byte3 before for, as if Byte3 is FillChar, Byte 4 will be
    // also be FillChar
    if Chr(LUnit.Byte3) = FillChar then begin
      LOut := LOut + LBOut.OneChar;
    end
    else
    begin
      if Chr(LUnit.Byte4) = FillChar then
      begin
        LOut := LOut + LBOut.TwoChars;
      end
      else
      begin
        LOut := LOut + LBout.ThreeChars;
      end;
    end;
  end;
  if LOut <> '' then begin
    ADest.WriteBuffer(LOut[1], Length(LOut));
  end;
end;

procedure TIdDecoder4to3.DecodeUnit(AIn: Cardinal; var VOut1, VOut2
 , VOut3: Byte);
var
  LUnit: TIdCardinalBytes;
begin
  LUnit.Whole := AIn;
  LUnit.Whole := (FDecodeTable[LUnit.Byte1] shl 18)
   or (FDecodeTable[LUnit.Byte2] shl 12) or (FDecodeTable[LUnit.Byte3] shl 6)
   or FDecodeTable[LUnit.Byte4];
  VOut1 := LUnit.Byte3;
  VOut2 := LUnit.Byte2;
  VOut3 := LUnit.Byte1;
end;

{ TIdEncoder3to4 }

function TIdEncoder3to4.Encode(ASrcStream: TStream; const ABytes: Integer = MaxInt): string;
//TODO: Make this more efficient. Profile it to test, but maybe make single
// calls to ReadBuffer then pull from memory
var
  LBuffer : String;
  LSize : Integer;
  LLen : integer;
  LBufSize : Integer;
  LPos : Integer;
  LIn1, LIn2, LIn3: Byte;
  LUnit: TIdCardinalBytes;
begin
  Result := '';    {Do not Localize}
  LIn3 := 0;
  if (ABytes <> MaxInt) and ((ABytes mod 3) > 0) then begin
    raise EIdException.Create(RSUnevenSizeInEncodeStream);
  end;
  // No no - this will read the whole thing into memory and what if its MBs?
  // need to load it in smaller buffered chunks MaxInt is WAY too big....
  LBufSize := ASrcStream.Size - ASrcStream.Position;
  if LBufSize > ABytes then begin
    LBufSize := ABytes;
  end;
  if LBufSize = 0 then begin
    Exit;
  end;
  SetLength(result, ((LBufSize+2) div 3) * 4); // we know that the string will grow by 4/3 adjusted to 3 boundary
  LLen := 0;
  SetLength(LBuffer, LBufSize);
  ASrcStream.ReadBuffer(LBuffer[1], LBufSize);
  LPos := 1;
  while (LPos <= LBufSize) do
  begin
    LIn1 := Byte(LBuffer[LPos]);
    Inc(LPos);
    if LPos <= LBufSize then
    begin
      LIn2 := Byte(LBuffer[LPos]);
      Inc(LPos);
      if LPos <= LBufSize then
      begin
        LIn3 := Byte(LBuffer[LPos]);
        Inc(LPos);
        LSize := 3;
      end
      else
      begin
        LIn3 := 0;
        LSize := 2;
      end;
    end
    else
    begin
      LIn2 := 0;
      LSize := 1;
    end;
    EncodeUnit(LIn1, LIn2, LIn3, LUnit.Whole);
    assert(LLen + 4 <= length(result), 'TIdEncoder3to4.Encode: Calculated length exceeded (expected '+inttostr(4 * trunc((LBufSize + 2)/3))+', about to go '+inttostr(LLen + 4)+' at offset '+inttostr(LPos)+' of '+inttostr(LBufSize));
    move(LUnit, result[LLen + 1], 4);
    inc(LLen, 4);
    if LSize < 3 then begin
      Result[LLen] := FillChar;
      if LSize = 1 then begin
         Result[LLen-1] := FillChar;
      end;
    end;
  end;
  assert(LLen = 4 * trunc((LBufSize + 2)/3), 'TIdEncoder3to4.Encode: Calculated length not met (expected '+inttostr(4 * trunc((LBufSize + 2)/3))+', finished at '+inttostr(LLen + 4)+', Bufsize = '+inttostr(LBufSize));
end;

procedure TIdEncoder3to4.EncodeUnit(const AIn1, AIn2, AIn3: Byte; var VOut: Cardinal);
var
  LUnit: TIdCardinalBytes;
begin
  LUnit.Byte1 := Ord(FCodingTable[((AIn1 SHR 2) and 63) + 1]);
  LUnit.Byte2 := Ord(FCodingTable[(((AIn1 SHL 4) or (AIn2 SHR 4)) and 63) + 1]);
  LUnit.Byte3 := Ord(FCodingTable[(((AIn2 SHL 2) or (AIn3 SHR 6)) and 63) + 1]);
  LUnit.Byte4 := Ord(FCodingTable[(Ord(AIn3) and 63) + 1]);
  VOut := LUnit.Whole;
end;

end.
