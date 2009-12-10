{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10177: IdHash.pas 
{
{   Rev 1.0    2002.11.12 10:40:00 PM  czhower
}
unit IdHash;

interface

uses
  Classes;

type
  TIdHash = class(TObject);

  TIdHash16 = class(TIdHash)
  public
    function HashValue(const ASrc: string): Word; overload;
    function HashValue(AStream: TStream): Word; overload; virtual; abstract;
  end;

  TIdHash32 = class(TIdHash)
  public
    function HashValue(const ASrc: string): LongWord; overload;
    function HashValue(AStream: TStream): LongWord; overload; virtual; abstract;
  end;

  T4x4LongWordRecord = array [0..3] of LongWord;

  TIdHash128 = class(TIdHash)
  public
    class function AsHex(const AValue: T4x4LongWordRecord): string;
    function HashValue(const ASrc: string): T4x4LongWordRecord; overload;
    function HashValue(AStream: TStream): T4x4LongWordRecord; overload; virtual; abstract;
  end;

implementation

uses
  IdGlobal,
  SysUtils;

{ TIdHash32 }

function TIdHash32.HashValue(const ASrc: string): LongWord;
var
  LStream: TIdReadMemoryStream;
begin
  LStream := TIdReadMemoryStream.Create; try
    LStream.SetPointer(Pointer(ASrc),Length(ASrc));
    Result := HashValue(LStream);
  finally FreeAndNil(LStream); end;
end;

{ TIdHash16 }

function TIdHash16.HashValue(const ASrc: string): Word;
var
  LStream: TIdReadMemoryStream;
begin
  LStream := TIdReadMemoryStream.Create; try
    LStream.SetPointer(Pointer(ASrc),Length(ASrc));
    Result := HashValue(LStream);
  finally FreeAndNil(LStream); end;
end;

{ TIdHash128 }

function TIdHash128.HashValue(const ASrc: string): T4x4LongWordRecord;
var
  LStream: TIdReadMemoryStream;
begin
  LStream := TIdReadMemoryStream.Create; try
    LStream.SetPointer(Pointer(ASrc),Length(ASrc));
    Result := HashValue(LStream);
  finally FreeAndNil(LStream); end;
end;

class function TIdHash128.AsHex(const AValue: T4x4LongWordRecord): string;
var
  P: PChar;
  i: Integer;
Begin
  P:=PChar(@AValue);
  SetString(Result,NIL,4*4*2);//32
  for i:=0 to 15 do begin
    Result[i*2+1]:=IdHexDigits[ord(P[i]) shr 4];
    Result[i*2+2]:=IdHexDigits[ord(P[i]) and $F];
  end;//for
end;

end.
