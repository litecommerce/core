{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10181: IdHashElf.pas 
{
{   Rev 1.0    2002.11.12 10:40:14 PM  czhower
}
unit IdHashElf;

interface

uses
  Classes,
  IdHash;

type
  TIdHashElf = class(TIdHash32)
  public
    function HashValue(AStream: TStream): LongWord; override;
  end;

implementation

{ TIdHashElf }

function TIdHashElf.HashValue(AStream: TStream): LongWord;
var
  i: Integer;
  LByte: Byte;
	LTemp: LongWord;
begin
	Result := 0;
  AStream.Position := 0;
  // Faster than a while - While would read .Size which is slow
  for i := 1 to AStream.Size do begin
    AStream.ReadBuffer(LByte, SizeOf(LByte));
		Result := (Result shl 4) + LByte;
		LTemp := Result and $F0000000;
		if LTemp <> 0 then begin
    	Result := Result xor (LTemp Shr 24);
    end;
		Result := Result and not LTemp;
	end;
end;

end.
