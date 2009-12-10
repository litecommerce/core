{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10353: IdStrings.pas 
{
{   Rev 1.0    2002.11.12 10:54:14 PM  czhower
}
unit IdStrings;

interface

Uses
  Classes;

{
2000-03-27  Pete Mee
 - Added FindFirstOf, FindFirstNotOf and TrimAllOf functions.
2002-01-03  Andrew P.Rybin
 - StrHTMLEnc/Dec,BinToHexStr,SplitColumns,IsWhiteString
2002-03-12 Andrew P.Rybin
 - SplitColumns[NoTrim]
}

function  FindFirstOf(AFind, AText: String): Integer;
function  FindFirstNotOf(AFind, AText : String) : Integer;
function  TrimAllOf(ATrim, AText : String) : String;

// Empty or contain only TAB and Space. Use it vs Length(Trim(AStr))>0
function  IsWhiteString(const AStr: String): Boolean;
function  BinToHexStr  (AData: Pointer; ADataLen: Integer): String;

// Encode reserved html chars: < > ' & "    {Do not Localize}
function  StrHtmlEncode (const AStr: String): String;
function  StrHtmlDecode (const AStr: String): String;

//in Integer(Strings.Objects[i]) - column position in AData
procedure SplitColumnsNoTrim (const AData: String; AStrings: TStrings; const ADelim: String=' ');    {Do not Localize}
procedure SplitColumns (const AData: String; AStrings: TStrings; const ADelim: String=' ');    {Do not Localize}

procedure SplitLines (AData: PChar; ADataSize: Integer; AStrings: TStrings);

// SplitString splits a string into left and right parts,
// i.e. SplitString('Namespace:tag', ':'..) will return 'Namespace' and 'Tag'
procedure SplitString(const AStr, AToken: String; var VLeft, VRight: String);

// commaadd will append AStr2 to the right of AStr1 and return the result.
// if there is any content in AStr1, a comma will be added 
function CommaAdd(Const AStr1, AStr2:String):string;

implementation

uses
  IdException,
  IdGlobal,
  SysUtils;

function StrHtmlEncode (const AStr: String): String;
begin
	Result := StringReplace(AStr,   '&', '&amp;', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '<', '&lt;', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '>', '&gt;', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '"', '&quot;', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '''', '&apos;', [rfReplaceAll]);    {Do not Localize}
end;

function StrHtmlDecode (const AStr: String): String;
begin
	Result := StringReplace(AStr,   '&apos;', '''', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '&quot;', '"', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '&gt;', '>', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '&lt;', '<', [rfReplaceAll]);    {Do not Localize}
	Result := StringReplace(Result, '&amp;', '&', [rfReplaceAll]);    {Do not Localize}
end;


{Function  ReadTimeStampCounter: Int64;
Asm
  db $0F,$31 //RDTSC
End;//Read CPU TimeStamp}

function FindFirstOf(AFind, AText: string): Integer;
var
  nCount, nPos: Integer;
begin
  Result := 0;
  for nCount := 1 to Length(AFind) do begin
    nPos := IndyPos(AFind[nCount], AText);
    if nPos > 0 then begin
      if Result = 0 then begin
        Result := nPos;
      end else if Result > nPos then begin
        Result := nPos;
      end;
    end;
  end;
end;

function FindFirstNotOf(AFind, AText : String) : Integer;
var
  i : Integer;
begin
  result := 0;
  if length(AFind) = 0 then
  begin
    result := 1;
    exit;
  end;

  if length(AText) = 0 then
  begin
    exit;
  end;

  for i := 1 to length(AText) do
  begin
    if IndyPos(AText[i], AFind) = 0 then
    begin
      result := i;
      exit;
    end;
  end;
end;

function TrimAllOf(ATrim, AText : String) : String;
begin
  while Length(AText) > 0 do
  begin
    if Pos(AText[1], ATrim) > 0 then
    begin
      System.Delete(AText, 1, 1);
    end else break;
  end;
  while Length(AText) > 0 do begin
    if Pos(AText[length(AText)], ATrim) > 0 then
    begin
      System.Delete(AText, Length(AText), 1);
    end else break;
  end;
  result := AText;
End;

function BinToHexStr (AData: Pointer; ADataLen: Integer): String;
var
  LSrc: PChar;
  i: Integer;
Begin
  LSrc:=AData;
  SetString(Result,NIL,ADataLen*2);

  for i:=0 to ADataLen-1 do begin
    Result[i*2+1]:=IdHexDigits[ord(LSrc^) shr 4];
    Result[i*2+2]:=IdHexDigits[ord(LSrc^) and $F];
    inc(LSrc);
  end;//for
End;

procedure SplitColumnsNoTrim(const AData: String; AStrings: TStrings; const ADelim: String=' ');    {Do not Localize}
var
  i: Integer;
  LDelim: Integer; //delim len
  LLeft: String;
  LLastPos: Integer;
Begin
  Assert(Assigned(AStrings));
  AStrings.Clear;
  LDelim := Length(ADelim);
  LLastPos := 1;

  i := Pos(ADelim, AData);
  while I > 0 do begin
    LLeft:= Copy(AData, LLastPos, I-LLastPos); //'abc d' len:=i(=4)-1    {Do not Localize}
    if LLeft > '' then begin    {Do not Localize}
      AStrings.AddObject(LLeft,Pointer(LLastPos));
    end;
    LLastPos := I + LDelim; //first char after Delim
    i := PosIdx (ADelim, AData, LLastPos);
  end;//while found
  if LLastPos <= Length(AData) then begin
    AStrings.AddObject(Copy(AData,LLastPos,MaxInt), Pointer(LLastPos));
  end;
End;//TIdFTPListItems.ParseColumns

procedure SplitColumns(const AData: String; AStrings: TStrings; const ADelim: String=' ');    {Do not Localize}
var
  i: Integer;
  LData: String;
  LDelim: Integer; //delim len
  LLeft: String;
  LLastPos: Integer;
  LLeadingSpaceCnt: Integer;
Begin
  Assert(Assigned(AStrings));
  AStrings.Clear;
  LDelim := Length(ADelim);
  LLastPos := 1;
  LData := Trim(AData);

  LLeadingSpaceCnt := 0;
  if Length(LData)>0 then begin //if Not WhiteStr
    while AData[LLeadingSpaceCnt+1]<=' ' do inc(LLeadingSpaceCnt);
  end
  else begin
    EXIT;
  end;

  i := Pos(ADelim, LData);
  while I > 0 do begin
    LLeft:= Copy(LData, LLastPos, I-LLastPos); //'abc d' len:=i(=4)-1    {Do not Localize}
    if LLeft > '' then begin    {Do not Localize}
      AStrings.AddObject(Trim(LLeft),Pointer(LLastPos+LLeadingSpaceCnt));
    end;
    LLastPos := I + LDelim; //first char after Delim
    i := PosIdx (ADelim, LData, LLastPos);
  end;//while found
  if LLastPos <= Length(LData) then begin
    AStrings.AddObject(Trim( Copy(LData,LLastPos,MaxInt) ), Pointer(LLastPos+LLeadingSpaceCnt));
  end;
End;//TIdFTPListItems.ParseColumns

function  IsWhiteString(const AStr: String): Boolean;
const
  WhiteSet = [TAB,' '];    {Do not Localize}
var
  i: Integer;
  LLen: Integer;
Begin
  LLen := Length(AStr);
  if LLen > 0 then begin
    Result:=TRUE; //only white
    for i:=1 to LLen do begin
      if NOT (AStr[i] in WhiteSet) then begin
        Result:=FALSE;
        EXIT;
      end;
    end;
  end
  else begin
    Result:=TRUE; //empty
  end;
End;//IsWhiteString


procedure SplitString(const AStr, AToken: String; var VLeft, VRight: String);
var
  i: Integer;
  LLocalStr: String;
begin
  { It is possible that VLeft or VRight may be the same variable as AStr. So we copy it first }
  LLocalStr := AStr;
  i := Pos(AToken, LLocalStr);
  if i = 0 then
    begin
    VLeft := LLocalStr;
    VRight := '';
    end
  else
    begin
    VLeft := Copy(LLocalStr, 1, i - 1);
    VRight := Copy(LLocalStr, i + Length(AToken), Length(LLocalStr));
    end;
end;

function CommaAdd(Const AStr1, AStr2:String):string;
begin
  if AStr1 = '' then
    result := AStr2
  else
    result := AStr1 + ',' + AStr2;
end;

procedure SplitLines (AData: PChar; ADataSize: Integer; AStrings: TStrings);
var
  P, LLast, Start: PChar;
  S: string;
begin
  AStrings.BeginUpdate;
  try
    AStrings.Clear;
    P := AData;
    if P <> NIL then begin
      LLast := P+ADataSize;

      while P < LLast do begin
        Start := P;
        while (P < LLast) and NOT (P^ in [CR, LF]) do Inc(P);
        SetString(S, Start, P - Start);
        AStrings.AddObject(S, Pointer(Start - AData +1));
        if P^ = #13 then Inc(P);
        if P^ = #10 then Inc(P);
      end;//while
    end;//if
  finally
    AStrings.EndUpdate;
  end;
End;//SplitLines

END.
