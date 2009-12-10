{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10093: IdCoderHeader.pas 
{
{   Rev 1.0    2002.11.12 10:32:34 PM  czhower
}
unit IdCoderHeader;

//TODO: Optimize and restructure code
//TODO: Redo this unit to fit with the new coders and use the exisiting MIME stuff

{
2001-Nov-18 Peter Mee
 - Fixed multiple QP decoding in single header.
11-10-2001 - J. Peter Mugaas
  - tiny fix for 8bit header encoding suggested by Andrew P.Rybin}
interface

uses
  IdEMailAddress;

type
  TTransfer = (bit7, bit8, iso2022jp);
  CSET = set of Char;

// Procs
  function EncodeAddressItem(EmailAddr:TIdEmailAddressItem; const HeaderEncoding: Char;
    TransferHeader: TTransfer; MimeCharSet: string): string;
  function EncodeHeader(const Header: string; specials : CSET; const HeaderEncoding: Char;
   TransferHeader: TTransfer; MimeCharSet: string): string;
  function Encode2022JP(const S: string): string;
  function EncodeAddress(EmailAddr:TIdEMailAddressList; const HeaderEncoding: Char;
    TransferHeader: TTransfer; MimeCharSet: string): string;
  function DecodeHeader(Header: string):string;
  function Decode2022JP(const S: string): string;
  Procedure DecodeAddress(EMailAddr : TIdEmailAddressItem);
  Procedure DecodeAddresses(AEMails : String; EMailAddr : TIdEmailAddressList);
  procedure InitializeISO(var TransferHeader: TTransfer; var HeaderEncoding: char;
    var CharSet: string);

implementation

uses
  IdGlobal,
  SysUtils;

const
  csSPECIALS: CSET = ['(', ')', '[', ']', '<', '>', ':', ';', '.', ',', '@', '\', '"'];  {Do not Localize}

  kana_tbl : array[#$A1..#$DF] of Word = (
    $2123,$2156,$2157,$2122,$2126,$2572,$2521,$2523,$2525,$2527,
    $2529,$2563,$2565,$2567,$2543,$213C,$2522,$2524,$2526,$2528,
    $252A,$252B,$252D,$252F,$2531,$2533,$2535,$2537,$2539,$253B,
    $253D,$253F,$2541,$2544,$2546,$2548,$254A,$254B,$254C,$254D,
    $254E,$254F,$2552,$2555,$2558,$255B,$255E,$255F,$2560,$2561,
    $2562,$2564,$2566,$2568,$2569,$256A,$256B,$256C,$256D,$256F,
    $2573,$212B,$212C);

  vkana_tbl : array[#$A1..#$DF] of Word = (
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$2574,$0000,
    $0000,$252C,$252E,$2530,$2532,$2534,$2536,$2538,$253A,$253C,
    $253E,$2540,$2542,$2545,$2547,$2549,$0000,$0000,$0000,$0000,
    $0000,$2550,$2553,$2556,$2559,$255C,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000);

  sj1_tbl : array[#128..#255] of Byte = (
    $00,$21,$23,$25,$27,$29,$2B,$2D,$2F,$31,$33,$35,$37,$39,$3B,$3D,
    $3F,$41,$43,$45,$47,$49,$4B,$4D,$4F,$51,$53,$55,$57,$59,$5B,$5D,
    $00,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,
    $01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,
    $01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,
    $01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,$01,
    $5F,$61,$63,$65,$67,$69,$6B,$6D,$6F,$71,$73,$75,$77,$79,$7B,$7D,
    $02,$02,$02,$02,$02,$02,$02,$02,$02,$02,$02,$02,$02,$00,$00,$00);

  sj2_tbl : array[Char] of Word = (
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,$0000,
    $0000,$0000,$0000,$0000,$0021,$0022,$0023,$0024,$0025,$0026,
    $0027,$0028,$0029,$002A,$002B,$002C,$002D,$002E,$002F,$0030,
    $0031,$0032,$0033,$0034,$0035,$0036,$0037,$0038,$0039,$003A,
    $003B,$003C,$003D,$003E,$003F,$0040,$0041,$0042,$0043,$0044,
    $0045,$0046,$0047,$0048,$0049,$004A,$004B,$004C,$004D,$004E,
    $004F,$0050,$0051,$0052,$0053,$0054,$0055,$0056,$0057,$0058,
    $0059,$005A,$005B,$005C,$005D,$005E,$005F,$0000,$0060,$0061,
    $0062,$0063,$0064,$0065,$0066,$0067,$0068,$0069,$006A,$006B,
    $006C,$006D,$006E,$006F,$0070,$0071,$0072,$0073,$0074,$0075,
    $0076,$0077,$0078,$0079,$007A,$007B,$007C,$007D,$007E,$0121,
    $0122,$0123,$0124,$0125,$0126,$0127,$0128,$0129,$012A,$012B,
    $012C,$012D,$012E,$012F,$0130,$0131,$0132,$0133,$0134,$0135,
    $0136,$0137,$0138,$0139,$013A,$013B,$013C,$013D,$013E,$013F,
    $0140,$0141,$0142,$0143,$0144,$0145,$0146,$0147,$0148,$0149,
    $014A,$014B,$014C,$014D,$014E,$014F,$0150,$0151,$0152,$0153,
    $0154,$0155,$0156,$0157,$0158,$0159,$015A,$015B,$015C,$015D,
    $015E,$015F,$0160,$0161,$0162,$0163,$0164,$0165,$0166,$0167,
    $0168,$0169,$016A,$016B,$016C,$016D,$016E,$016F,$0170,$0171,
    $0172,$0173,$0174,$0175,$0176,$0177,$0178,$0179,$017A,$017B,
    $017C,$017D,$017E,$0000,$0000,$0000);

  base64_tbl: array [0..63] of Char = (
    'A','B','C','D','E','F','G','H',     {Do not Localize}
    'I','J','K','L','M','N','O','P',      {Do not Localize}
    'Q','R','S','T','U','V','W','X',      {Do not Localize}
    'Y','Z','a','b','c','d','e','f',      {Do not Localize}
    'g','h','i','j','k','l','m','n',      {Do not Localize}
    'o','p','q','r','s','t','u','v',       {Do not Localize}
    'w','x','y','z','0','1','2','3',       {Do not Localize}
    '4','5','6','7','8','9','+','/');      {Do not Localize}

function EncodeAddressItem(EmailAddr:TIdEmailAddressItem; const HeaderEncoding: Char;
  TransferHeader: TTransfer; MimeCharSet: string): string;
var
  S : string;
  I : Integer;
  NeedEncode : Boolean;
begin
  if EmailAddr.Name <> '' then  {Do not Localize}
  begin
    NeedEncode := False;
    for I := 1 to Length(EmailAddr.Name) do
    begin
      if (EmailAddr.Name[I] < #32) or (EmailAddr.Name[I] >= #127) then
      begin
        NeedEncode := True;
        Break;
      end;
    end;
    if NeedEncode then
      S := EncodeHeader(EmailAddr.Name, csSPECIALS, HeaderEncoding, TransferHeader, MimeCharSet)
    else
    begin                { quoted string }
      S := '"';           {Do not Localize}
      for I := 1 to Length(EmailAddr.Name) do
      begin              { quote special characters }
        if (EmailAddr.Name[I] = '\') or (EmailAddr.Name[I] = '"') then S := S + '\';    {Do not Localize}
        S := S + EmailAddr.Name[I];
      end;
      S := S + '"';   {Do not Localize}
    end;
    Result := Format('%s <%s>', [S, EmailAddr.Address])    {Do not Localize}
  end
  else Result := Format('%s', [EmailAddr.Address]);     {Do not Localize}
end;

function B64(AChar: Char): Byte;
//TODO: Make this use the more efficient MIME Coder
var
  i: Integer;
begin
  for i := Low(base64_tbl) to High(base64_tbl) do begin
    if AChar = base64_tbl[i] then begin
      Result := i;
      exit;
    end;
  end;
  Result := 0;
end;

function DecodeHeader(Header: string):string;
  // I needed a Pos function that accepts a startposition
  // PosRev from IdGlobal didn't work here. If you find this useful,    {Do not Localize}
  // you can move this function to IdGlobal.pas

  function PosStartAt(const SubStr,s : string; StartPos : Cardinal = 0; IgnoreCase : boolean = false) : integer;
  begin
    if StartPos=0 then
    begin
      if IgnoreCase
      then Result := System.Pos(AnsiUpperCase(SubStr),AnsiUpperCase(s))
      else Result := System.Pos(SubStr,s)
    end else
    begin
      if IgnoreCase
      then Result := System.Pos(AnsiUpperCase(SubStr),AnsiUpperCase(System.Copy(s,StartPos,Length(s))))
      else Result := System.Pos(SubStr,System.Copy(s,StartPos,Length(s)));
      if Result>0 then Result := (Result+integer(StartPos))-1;
    end;
  end;

var
  i, l: Integer;
  HeaderEncoding,
  HeaderCharSet,
  s: string;
  a3: array [1..3] of byte;
  a4: array [1..4] of byte;
  encodingstartpos,encodingendpos:Integer;
  substring:string;
  EncodingFound : Boolean;
begin
  // Get the Charset part.
  encodingstartpos:=PosStartAt('=?ISO', UpperCase(Header),1);
  if encodingstartpos = 0 then
    encodingstartpos:=PosStartAt('=?WINDOWS', UpperCase(Header),1);

  while encodingstartpos > 0 do
  begin
    // Assume we will find the encoding
    EncodingFound := true;

    //we need 3 more question marks first and after that a '?='    {Do not Localize}
    //to find the end of the substring, we can't just search for '?=',    {Do not Localize}
    //example: '=?ISO-8859-1?Q?=E4?='    {Do not Localize}
    encodingendpos := PosStartAt('?', UpperCase(Header),encodingstartpos+5);  {Do not Localize}
    //TODO: Who the hell put gotos in here?
    //TODO: Remove these and whiplash that person
    if encodingendpos = 0 then
    begin
      EncodingFound := false;
    end;

    if EncodingFound then
    begin
      encodingendpos:=PosStartAt('?', UpperCase(Header),encodingendpos+1);  {Do not Localize}
      if encodingendpos=0 then
      begin
        EncodingFound := false;
      end;
    end;

    if EncodingFound then
    begin
      encodingendpos:=PosStartAt('?=', UpperCase(Header),encodingendpos+1);  {Do not Localize}
      if encodingendpos > 0 then
      begin
        substring:=Copy(Header,encodingstartpos,encodingendpos-encodingstartpos+2);
        //now decode the substring
        for i := 1 to 3 do
        begin
          l := Pos('?', substring);   {Do not Localize}
          substring := Copy(substring, l+1, Length(substring) - l + 1 );
          if i = 1 then
          begin
            HeaderCharSet := Copy(substring, 1, Pos('?', substring)-1)  {Do not Localize}
          end else if i = 2 then
          begin
            HeaderEncoding := copy(substring,1,1);
          end;
        end;

        //now Substring needs to end with '?=' otherwise give up!    {Do not Localize}
        if Copy(substring,Length(substring)-1,2)<>'?=' then    {Do not Localize}
        begin
          EncodingFound := false;
        end;

        // Get the HeaderEncoding
        if (AnsiSameText(HeaderEncoding, 'Q')) {Do not Localize}
        and (EncodingFound) then
        begin
          i := 1;
          s := '';        {Do not Localize}
          repeat // substring can be accessed by index here, because we know that it ends with '?='    {Do not Localize}
            if substring[i] = '_' then  {Do not Localize}
            begin
              s := s + ' ';    {Do not Localize}
            end else if (substring[i] = '=') and (Length(substring)>=i+2+2) then //make sure we can access i+2 and '?=' is still beyond    {Do not Localize}
            begin
              s := s + chr(StrToInt('$' + substring[i+1] + substring[i+2]));   {Do not Localize}
              inc(i,2);
            end else
            begin
              s := s + substring[i];
            end;
            inc(i);
          until (substring[i]='?') and (substring[i+1]='=')   {Do not Localize}
        end else if EncodingFound then
        begin
          while Length(substring) >= 4 do
          begin
            a4[1] := b64(substring[1]);
            a4[2] := b64(substring[2]);
            a4[3] := b64(substring[3]);
            a4[4] := b64(substring[4]);
            a3[1] := Byte((a4[1] shl 2) or (a4[2] shr 4));
            a3[2] := Byte((a4[2] shl 4) or (a4[3] shr 2));
            a3[3] := Byte((a4[3] shl 6) or (a4[4] shr 0));
            substring := Copy(substring, 5, Length(substring));
            s := s + CHR(a3[1]) + CHR(a3[2]) + CHR(a3[3]);
          end;
        end;

        if EncodingFound then
        begin
          if AnsiSameText(HeaderCharSet, 'ISO-2022-JP') then  {Do not Localize}
          begin
            substring := Decode2022JP(s)
          end else
          begin
            substring := s;
          end;

          //replace old substring in header with decoded one:
          header := Copy(header, 1, encodingstartpos - 1)
            + substring + Copy(header, encodingendpos + 2, Length(Header));
          substring := '';   {Do not Localize}
        end;

      end;
    end;

    encodingstartpos:=PosStartAt('=?ISO', UpperCase(Header),encodingstartpos+1);
    if encodingstartpos = 0 then
      encodingstartpos:=PosStartAt('=?WINDOWS', UpperCase(Header),encodingstartpos+1);
  end;
  //there might be #0's in header when this it b64 encoded, e.g with:    {Do not Localize}
  //decodeheader('"Fernando Corti=?ISO-8859-1?B?8Q==?=a" <fernando@nowhere.com>');    {Do not Localize}
  while Pos(#0,header)>0 do
  begin
    Delete(header,Pos(#0,header),1);
  end;
  Result:=Header;
end;

{
function DecodeHeader(Header: string):string;
var
  i, l: Integer;
  HeaderEncoding,
  HeaderCharSet,
  s: string;
  a3: array [1..3] of byte;
  a4: array [1..4] of byte;
begin
  // Get the Charset part.
  if Pos('=?ISO', UpperCase(Header)) > 0 then
  begin
    for i := 1 to 3 do begin
      l := Pos('?', Header);
      Header := Copy(Header, l+1, Length(Header) - l + 1 );
      if i = 1 then HeaderCharSet := Copy(Header, 1, Pos('?', Header)-1)
      else if i = 2 then HeaderEncoding := Header[1];
    end;
    // Get the HeaderEncoding
    if AnsiSameText(HeaderEncoding, 'Q') then begin
      i := 1;
      repeat
        if Header[i] = '_' then
          s := s + ' '
        else if Header[i] = '=' then begin
          s := s + chr(StrToInt('$' + Header[i+1] + Header[i+2]));
          inc(i,2);
        end else
          s := s + Header[i];
        inc(i);
      until (Header[i]='?') and (Header[i+1]='=')
    end
    else begin
      while Length(Header) >= 4 do begin
        a4[1] := b64(Header[1]);
        a4[2] := b64(Header[2]);
        a4[3] := b64(Header[3]);
        a4[4] := b64(Header[4]);
        a3[1] := (a4[1] shl 2) or (a4[2] shr 4);
        a3[2] := (a4[2] shl 4) or (a4[3] shr 2);
        a3[3] := (a4[3] shl 6) or (a4[4] shr 0);
        Header := Copy(Header, 5, Length(Header));
        s := s + CHR(a3[1]) + CHR(a3[2]) + CHR(a3[3]);
      end;
    end;

    if AnsiSameText(HeaderCharSet, 'ISO-2022-JP') then   
      result := Decode2022JP(s)
    else
      Result := s;
  end
  else
    Result := Header;
end;
}
{ convert Shift_JIS to ISO-2022-JP (RFC 1468) }
function Decode2022JP(const S: string): string;
var
  T : string;
  I, L : integer;
  isK : Boolean;
  K1, K2 : byte;
  K3 : byte;
begin
  T := '';    {Do not Localize}
  isK := False;
  L := length(S);
  I := 1;
  while I <= L do
  begin
    if S[I] = #27 then
    begin
      Inc(I);
      if I+1 <= L then
      begin
        if Copy(S, I, 2) = '$B' then   {Do not Localize}
        begin
          isK := True;
        end
        else
        begin
          if Copy(S, I, 2) = '(B' then    {Do not Localize}
          begin
            isK := False; 
          end;
        end;
        Inc(I, 2);   { TODO -oTArisawa : Check RFC 1468}
      end;
    end
    else
    begin
      if isK then
      begin
        if I+1 <= L then
        begin
          K1 := byte(S[I]);
          K2 := byte(S[I + 1]);

          K3:= (K1 - 1) shr 1;
          if K1 < 95 then
            K3:= K3 + 113
          else
            K3 := K3 + 177;

          if (K1 mod 2) = 1 then
          begin
            if K2 < 96 Then
              K2 := K2 + 31
            else
              K2 := K2 + 32
          end
          else
            K2 := K2 + 126;

          T := T + char(K3) + char(k2);
          Inc(I,2);
        end
        else
          Inc(I); { invalid DBCS }
      end
      else
      begin
        T := T + S[I];
        Inc(I);
      end;
    end;
  end;
  Result := T;
end;

procedure InitializeISO(var TransferHeader: TTransfer; var HeaderEncoding: char;
  var CharSet: string);

begin
  TransferHeader := bit8;    { header part conversion type }
  HeaderEncoding := 'B';     { base64 / quoted-printable }    {Do not Localize}

  case GetSystemLocale of
    csGB2312: CharSet := 'GB2312'; {Do not Localize}
    csBig5: CharSet := 'Big5';    {Do not Localize}
    csIso2022jp:
      begin
        CharSet := 'ISO-2022-JP';  {Do not Localize}
        TransferHeader := iso2022jp { header needs conversion }
      end;
    csEUCKR: CharSet := 'EUC-KR';  {Do not Localize}
    else
      CharSet := 'ISO-8859-1';   {Do not Localize}
    HeaderEncoding := 'Q';    {Do not Localize}
  end;
end;

Procedure DecodeAddress(EMailAddr : TIdEmailAddressItem);
begin
  EMailAddr.Name := DecodeHeader(EMailAddr.Name);
end;

Procedure DecodeAddresses(AEMails : String; EMailAddr : TIdEmailAddressList);
var idx : Integer;
begin
  idx := 0;
  EMailAddr.EMailAddresses := AEMails;
  while idx < EMailAddr.Count do
  begin
    DecodeAddress(EMailAddr[idx]);
    inc(idx);
  end;
end;

function EncodeAddress(EmailAddr:TIdEMailAddressList; const HeaderEncoding: Char;
  TransferHeader: TTransfer; MimeCharSet: string): string;
var idx : Integer;
begin
  Result := '';      {Do not Localize}
  idx := 0;
  while ( idx < EmailAddr.Count ) do
  begin
    Result := Result + ', ' + EncodeAddressItem(EMailAddr[idx], HeaderEncoding, TransferHeader, MimeCharSet);  {Do not Localize}
    Inc ( idx );
  end; // while ( idx < EncodeAddress.Count ) do
  {Remove the first comma and the following space ', ' }    {Do not Localize}
  System.Delete ( Result, 1, 2 );
end;

{ convert Shift_JIS to ISO-2022-JP (RFC 1468) }
function Encode2022JP(const S: string): string;
const
  desig_asc = #27'(B';  {Do not Localize}
  desig_jis = #27'$B';   {Do not Localize}
var
  T: string;
  I, L: Integer;
  isK: Boolean;
  K1: Byte;
  K2, K3: Word;
begin
  T := '';    {Do not Localize}
  isK := False;
  L := Length(S);
  I := 1;
  while I <= L do
  begin
    if S[I] < #128 then  {Do not Localize}
    begin
      if isK then
      begin
        T := T + desig_asc;
        isK := False;
      end;
      T := T + S[I];
      INC(I);
    end else begin
      K1 := sj1_tbl[S[I]];
      case K1 of
      0: INC(I);    { invalid SBCS }
      2: INC(I, 2); { invalid DBCS }
      1:
        begin { halfwidth katakana }
          if not isK then begin
            T := T + desig_jis;
            isK := True;
          end;
          { simple SBCS -> DBCS conversion                         }
          K2 := kana_tbl[S[I]];
          if (I < L) and (Ord(S[I+1]) AND $FE = $DE) then
          begin  { convert kana + voiced mark to voiced kana }
            K3 := vkana_tbl[S[I]];
            case S[I+1] of
            #$DE:  { voiced }
              if K3 <> 0 then
              begin
                K2 := K3;
                INC(I);
              end;
            #$DF:  { semivoiced }
              if (K3 >= $2550) and (K3 <= $255C) then
              begin
                K2 := K3 + 1;
                INC(I);
              end;
            end;
          end;
          T := T + Chr(K2 SHR 8) + Chr(K2 AND $FF);
          INC(I);
        end;
      else { DBCS }
        if (I < L) then begin
          K2 := sj2_tbl[S[I + 1]];
          if K2 <> 0 then
          begin
            if not isK then begin
              T := T + desig_jis;
              isK := True;
            end;
            T := T + Chr(K1 + K2 SHR 8) + Chr(K2 AND $FF);
          end;
        end;
        INC(I, 2);
      end;
    end;
  end;
  if isK then
    T := T + desig_asc;
  Result := T;
end;

{ encode a header field if non-ASCII characters are used }
function EncodeHeader(const Header: string; specials : CSET; const HeaderEncoding: Char;
  TransferHeader: TTransfer; MimeCharSet: string): string;
const
  SPACES: set of Char = [' ', #9, #10, #13];    {Do not Localize}

var
  S, T: string;
  L, P, Q, R: Integer;
  B0, B1, B2: Integer;
  InEncode: Integer;
  NeedEncode: Boolean;
  csNeedEncode, csReqQuote: CSET;
  BeginEncode, EndEncode: string;

  procedure EncodeWord(P: Integer);
  const
    MaxEncLen = 75;
  var
    Q: Integer;
    EncLen: Integer;
    Enc1: string;
  begin
    T := T + BeginEncode;
    if L < P then P := L + 1;
    Q := InEncode;
    InEncode := 0;
    EncLen := Length(BeginEncode) + 2;

    if AnsiSameText(HeaderEncoding, 'Q') then  { quoted-printable }   {Do not Localize}
    begin
      while Q < P do
      begin
        if not (S[Q] in csReqQuote) then
        begin
          Enc1 := S[Q]
        end
        else
        begin
          if S[Q] = ' ' then  {Do not Localize}
            Enc1 := '_'   {Do not Localize}
          else
            Enc1 := '=' + IntToHex(Ord(S[Q]), 2);     {Do not Localize}
        end;
        if EncLen + Length(Enc1) > MaxEncLen then
        begin
          T := T + EndEncode + #13#10#9 + BeginEncode;
          EncLen := Length(BeginEncode) + 2;
        end;
        T := T + Enc1;
        INC(EncLen, Length(Enc1));
        INC(Q);
      end;
    end
    else
    begin { base64 }
      while Q < P do
      begin
        if EncLen + 4 > MaxEncLen then
        begin
          T := T + EndEncode + #13#10#9 + BeginEncode;
          EncLen := Length(BeginEncode) + 2;
        end;

        B0 := Ord(S[Q]);
        case P - Q of
        1: T := T + base64_tbl[B0 SHR 2] + base64_tbl[B0 AND $03 SHL 4] + '==';  {Do not Localize}
        2:
          begin
            B1 := Ord(S[Q + 1]);
            T := T             + base64_tbl[B0 SHR 2] +
              base64_tbl[B0 AND $03 SHL 4 + B1 SHR 4] +
              base64_tbl[B1 AND $0F SHL 2] + '=';  {Do not Localize}
          end;
        else
          B1 := Ord(S[Q + 1]);
          B2 := Ord(S[Q + 2]);
          T := T + base64_tbl[B0 SHR 2] +
            base64_tbl[B0 AND $03 SHL 4 + B1 SHR 4] +
            base64_tbl[B1 AND $0F SHL 2 + B2 SHR 6] +
            base64_tbl[B2 AND $3F];
        end;
        INC(EncLen, 4);
        INC(Q, 3);
      end;
    end;
    T := T + EndEncode;
  end;

begin
  case TransferHeader of
  iso2022jp:
    S := Encode2022JP(Header);
  else
    S := Header;
  end;

  {Suggested by Andrew P.Rybin for easy 8bit support}
  if HeaderEncoding='8' then begin //UpCase('8')='8'     {Do not Localize}
      Result:=S;
      EXIT;
  end;//if
  csNeedEncode := [#0..#31, #127..#255] + specials;
  csReqQuote := csNeedEncode + ['?', '=', '_'];   {Do not Localize}
  BeginEncode := '=?' + MimeCharSet + '?' + HeaderEncoding + '?';    {Do not Localize}
  EndEncode := '?=';  {Do not Localize}

  L := Length(S);
  P := 1;
  T := '';  {Do not Localize}
  InEncode := 0;
  while P <= L do
  begin
    Q := P;
    while (P <= L) and (S[P] in SPACES) do
      INC(P);
    R := P;
    NeedEncode := False;
    while (P <= L) and not (S[P] in SPACES) do
    begin
      if S[P] in csNeedEncode then
      begin
        NeedEncode := True;
      end;
      INC(P);
    end;
    if NeedEncode then
    begin
      if InEncode = 0 then
      begin
        T := T + Copy(S, Q, R - Q);
        InEncode := R;
      end;
    end
    else
    begin
      if InEncode <> 0 then
      begin
        EncodeWord(Q);
      end;
      T := T + Copy(S, Q, P - Q);
    end;
  end;
  if InEncode <> 0 then
  begin
    EncodeWord(P);
  end;
  Result := T;
end;

end.


