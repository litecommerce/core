{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10183: IdHashMessageDigest.pas 
{
{   Rev 1.0    2002.11.12 10:40:22 PM  czhower
}
{

  Implementation of the MD2, MD4 and MD5 Message-Digest Algorithm
  as specified in RFC 1319 (1115), 1320 (1186), 1321
 (See NOTE below for details of what is exactly implemented)

  Author: Pete Mee
  Port to Indy 8.1 Doychin Bondzhev (doychin@dsoft-bg.com)
  Copyright: (c) Chad Z. Hower and The Winshoes Working Group.

  NOTE:
  All MDx are ready and bug free.
}

unit IdHashMessageDigest;

{
2002-Feb-07 Pete Me
 - Fixed MD4 and MD5 for cases where n mod 512 = 448 where n is the number of
    bits processed.  An extra zero byte was being added to the input which
    offset the bit-size entry. 
2001-Oct-24 Pete Mee
 - Fixed MD4 and MD5 for cases where n mod 512 >= 448 where n is the number of
    bits processed.  This situation requires an additional block to be
    processed.
}

interface

uses
  Classes,
  IdGlobal,
  IdHash;

type
  T16x4LongWordRecord = array[0..15] of LongWord;
  T4x4x4LongWordRecord = array[0..3] of T4x4LongWordRecord;

  T384BitRecord = array [0..47] of byte;
  T128BitRecord = array [0..15] of byte;


  TIdHashMessageDigest = class(TIdHash128);

  TIdHashMessageDigest2 = class(TIdHashMessageDigest)
  protected
    FX: T384BitRecord;
    FCBuffer: T128BitRecord;
    FCheckSum: T128BitRecord;

    procedure MDCoder;
    procedure Reset;
  public
    function HashValue(AStream: TStream): T4x4LongWordRecord; override;
  end;

  TIdHashMessageDigest4 = class(TIdHashMessageDigest)
  protected
    FBuffer: T4x4LongWordRecord;
    FCBuffer: T16x4LongWordRecord;

    procedure MDCoder; virtual;

    function func_f(x, y, z : LongWord) : LongWord; virtual;
    function func_g(x, y, z : LongWord) : LongWord; virtual;
    function func_h(x, y, z : LongWord) : LongWord; virtual;
  public
    function HashValue(AStream: TStream): T4x4LongWordRecord; override;
  end;

  TIdHashMessageDigest5 = class(TIdHashMessageDigest4)
  protected
    procedure MDCoder; override;

    function func_g(x, y, z : LongWord) : LongWord; override;
    function func_i(x, y, z : LongWord) : LongWord; virtual;
  public

  end;

implementation

{ TIdHashMessageDigest2 }

const
  MD2_PI_SUBST : array [0..255] of byte = (
     41,  46,  67, 201, 162, 216, 124,   1,  61,  54,  84, 161, 236, 240,
      6,  19,  98, 167,   5, 243, 192, 199, 115, 140, 152, 147,  43, 217,
    188,  76, 130, 202,  30, 155,  87,  60, 253, 212, 224,  22, 103,  66,
    111,  24, 138,  23, 229,  18, 190,  78, 196, 214, 218, 158, 222,  73,
    160, 251, 245, 142, 187,  47, 238, 122, 169, 104, 121, 145,  21, 178,
      7,  63, 148, 194,  16, 137,  11,  34,  95,  33, 128, 127,  93, 154,
     90, 144,  50,  39,  53,  62, 204, 231, 191, 247, 151,   3, 255,  25,
     48, 179, 72, 165,  181, 209, 215,  94, 146,  42, 172,  86, 170, 198,
     79, 184,  56, 210, 150, 164, 125, 182, 118, 252, 107, 226, 156, 116,
      4, 241,  69, 157, 112,  89, 100, 113, 135,  32, 134,  91, 207, 101,
    230,  45, 168,   2,  27,  96,  37, 173, 174, 176, 185, 246,  28,  70,
     97, 105,  52,  64, 126, 15,   85,  71, 163,  35, 221,  81, 175,  58,
    195,  92, 249, 206, 186, 197, 234,  38,  44,  83,  13, 110, 133,  40,
    132,   9, 211, 223, 205, 244, 65,  129,  77,  82, 106, 220,  55, 200,
    108, 193, 171, 250,  36, 225, 123,   8,  12, 189, 177,  74, 120, 136,
    149, 139, 227,  99, 232, 109, 233, 203, 213, 254,  59,   0,  29,  57,
    242, 239, 183,  14, 102,  88, 208, 228, 166, 119, 114, 248, 235, 117,
     75,  10,  49,  68,  80, 180, 143, 237,  31,  26, 219, 153, 141,  51,
     159,  17, 131, 20);

procedure TIdHashMessageDigest2.MDCoder;
const
  NumRounds = 18;
Var
  i, j: Byte;
  T: Word;
  LCheckSumScore: Byte;
begin
  // Move the next 16 bytes into the second 16 bytes of X.
  Move(FCBuffer[0], FX[16], 16);
  for i := 0 to 15 do
  begin
    FX[i + 32] := FCBuffer[i] xor FX[i];
  end;

  { Do 18 rounds. }
  T := 0;
  for i := 0 to NumRounds - 1 do
  begin
    for j := 0 to 47 do
    begin
      T := FX[j] xor MD2_PI_SUBST[T];
      FX[j] := T and $FF;
    end;
    T := (T + i) and $FF;
  end;

  LCheckSumScore := FChecksum[15];
  for i := 0 to 15 do
  begin
    LCheckSumScore := FChecksum[i] xor MD2_PI_SUBST[FCBuffer[i]
      xor LCheckSumScore];
    FChecksum[i] := LCheckSumScore;
  end;
end;

// Clear Buffer and Checksum arrays
procedure TIdHashMessageDigest2.Reset;
begin
  FillChar(FCheckSum[0], 16, 0);
  FillChar(FCBuffer, 16, 0);
  // Initialise the X buffer to zero.
  FillChar(FX[0], 48, 0);
end;

function TIdHashMessageDigest2.HashValue(AStream: TStream): T4x4LongWordRecord;
Var
  LStartPos: Integer;
  LSize: Int64;
  S1: String;
begin
  Reset;

  LStartPos := AStream.Position;
  LSize := AStream.Size - LStartPos;

  // Code the entire file in complete 16-byte chunks.
  while LSize - AStream.Position >= SizeOf(FCBuffer) do
  begin
    AStream.Read(FCBuffer[0], SizeOf(FCBuffer));
    MDCoder;
  end;

  SetLength(S1, SizeOf(FCBuffer));

  LStartPos := AStream.Read(S1[1], 16);
  // Step 1
  FillChar(S1[LStartPos + 1], 16 - LStartPos, Byte(16 - LStartPos));
  Move(S1[1], FCBuffer[0], 16);
  MDCoder;
  // Step 2
  Move(FCheckSUm[0], FCBuffer[0], 16);
  MDCoder;

  Move(FX[0], result[0], 16);
end;

{ TIdHashMessageDigest4 }

const
  MD4_INIT_VALUES: T4x4LongWordRecord = (
    $67452301, $EFCDAB89, $98BADCFE, $10325476);

procedure TIdHashMessageDigest4.MDCoder;
var
  A, B, C, D, i : LongWord;
  I64 : Int64;
  buff : T4x4x4LongWordRecord; // 64-byte buffer

  function DoAdd(const AOne, ATwo, AThree, AFour : LongWord) : LongWord;
  begin
    I64 := AOne;
    I64 := ((I64 + ATwo) and $FFFFFFFF);
    I64 := ((I64 + AThree) and $FFFFFFFF) + AFour;
    result := I64 and $FFFFFFFF;
  end;
  
begin
  A := FBuffer[0];
  B := FBuffer[1];
  C := FBuffer[2];
  D := FBuffer[3];

  System.Move(FCBuffer[0], buff[0], SizeOf(buff));

  // The following additions utilise Int64 to avoid integer overflow

  // Round 1
  for i := 0 to 3 do
  begin 
    A := ROL(DoAdd(func_f(B, C, D), A, buff[i,0], 0), 3);
    D := ROL(DoAdd(func_f(A, B, C), D, buff[i,1], 0), 7);
    C := ROL(DoAdd(func_f(D, A, B), C, buff[i,2], 0), 11);
    B := ROL(DoAdd(func_f(C, D, A), B, buff[i,3], 0), 19);
  end;

  // Round 2
  for i := 0 to 3 do
  begin
    A := ROL(DoAdd(func_g(B, C, D), A, buff[0,i], $5A827999), 3);
    D := ROL(DoAdd(func_g(A, B, C), D, buff[1,i], $5A827999), 5);
    C := ROL(DoAdd(func_g(D, A, B), C, buff[2,i], $5A827999), 9);
    B := ROL(DoAdd(func_g(C, D, A), B, buff[3,i], $5A827999), 13);
  end;

  // Round 3
  A := ROL(DoAdd(func_h(B, C, D), A, T16x4LongWordRecord(buff)[0], $6ED9EBA1), 3);
  D := ROL(DoAdd(func_h(A, B, C), D, T16x4LongWordRecord(buff)[8], $6ED9EBA1), 9);
  C := ROL(DoAdd(func_h(D, A, B), C, T16x4LongWordRecord(buff)[4], $6ED9EBA1), 11);
  B := ROL(DoAdd(func_h(C, D, A), B, T16x4LongWordRecord(buff)[12], $6ED9EBA1), 15);
  A := ROL(DoAdd(func_h(B, C, D), A, T16x4LongWordRecord(buff)[2], $6ED9EBA1), 3);
  D := ROL(DoAdd(func_h(A, B, C), D, T16x4LongWordRecord(buff)[10], $6ED9EBA1), 9);
  C := ROL(DoAdd(func_h(D, A, B), C, T16x4LongWordRecord(buff)[6], $6ED9EBA1), 11);
  B := ROL(DoAdd(func_h(C, D, A), B, T16x4LongWordRecord(buff)[14], $6ED9EBA1), 15);
  A := ROL(DoAdd(func_h(B, C, D), A, T16x4LongWordRecord(buff)[1], $6ED9EBA1), 3);
  D := ROL(DoAdd(func_h(A, B, C), D, T16x4LongWordRecord(buff)[9], $6ED9EBA1), 9);
  C := ROL(DoAdd(func_h(D, A, B), C, T16x4LongWordRecord(buff)[5], $6ED9EBA1), 11);
  B := ROL(DoAdd(func_h(C, D, A), B, T16x4LongWordRecord(buff)[13], $6ED9EBA1), 15);
  A := ROL(DoAdd(func_h(B, C, D), A, T16x4LongWordRecord(buff)[3], $6ED9EBA1), 3);
  D := ROL(DoAdd(func_h(A, B, C), D, T16x4LongWordRecord(buff)[11], $6ED9EBA1), 9);
  C := ROL(DoAdd(func_h(D, A, B), C, T16x4LongWordRecord(buff)[7], $6ED9EBA1), 11);
  B := ROL(DoAdd(func_h(C, D, A), B, T16x4LongWordRecord(buff)[15], $6ED9EBA1), 15);

  I64 := FBuffer[0];
  Inc(I64, A);
  FBuffer[0] := I64 and $FFFFFFFF;
  I64 := FBuffer[1];
  Inc(I64, B);
  FBuffer[1] := I64 and $FFFFFFFF;
  I64 := FBuffer[2];
  Inc(I64, C);
  FBuffer[2] := I64 and $FFFFFFFF;
  I64 := FBuffer[3];
  Inc(I64, D);
  FBuffer[3] := I64 and $FFFFFFFF;
end;

function TIdHashMessageDigest4.func_f(x, y, z : LongWord) : LongWord;
begin
  result := (x and y) or ( (not x) and z);
end;

function TIdHashMessageDigest4.func_g(x, y, z : LongWord) : LongWord;
begin
  result := (x and y) or (x and z) or (y and z);
end;

function TIdHashMessageDigest4.func_h(x, y, z : LongWord) : LongWord;
begin
  result := x xor y xor z;
end;

function TIdHashMessageDigest4.HashValue(AStream: TStream): T4x4LongWordRecord;
Var
  LStartPos: Integer;
  LBitSize,
  LSize: Int64;
  S: String;
  S1: String;
  LFillSize : Integer;
begin
  LStartPos := AStream.Position;
  LSize := AStream.Size - LStartPos;

  FBuffer := MD4_INIT_VALUES;

  while LSize - AStream.Position >= SizeOf(FCBuffer) do
  begin
    AStream.Read(FCBuffer[0], SizeOf(FCBuffer));
    MDCoder;
  end;

  // Ensure S1 has sufficient size to hold a complete 64-byte chunk
  SetLength(S1, SizeOf(FCBuffer));

  // Read the last set of bytes.
  LStartPos := AStream.Read(S1[1], 64);
  // Now adjust S1 to only hold the last set of bytes.
  SetLength(S1, LStartPos);
  // Append one bit with value 1
  S1 := S1 + Chr($80);

  // Must have sufficient space to insert the 64-bit length
  if Length(S1) > 64 - SizeOf(LSize) then
  begin
    SetLength(S, 64 - SizeOf(LSize));
    FillChar(S[1], 64 - SizeOf(LSize), #0);
    S1 := S1 + S;
    Move(S1[1], FCBuffer[0], SizeOf(FCBuffer));
    MDCoder;

    // Create a new block with only zeros.
    SetLength(S1, 64 - SizeOf(LSize));
    FillChar(S1[1], 64 - SizeOf(LSize), #0);
  end else
  begin
    LFillSize := 64 - ((LSize + 9) mod 64);
    // If the bit size will fit exact at the end (LFillSize = 64)
    // then S1 need not be padded.
    if LFillSize <> 64 then
    begin
      SetLength(S, LFillSize);
      FillChar(S[1], LFillSize, #0);
      S1 := S1 + S; // Extend the rest of the block with zeros
    end;
  end;

  // Append the Number of bits processed.
  LBitSize := LSize * 8;
  Setlength(S, SizeOf(LBitSize));
  Move(LBitSize, S[1], SizeOf(LBitSize));
  S1 := S1 + S; // Append the stream size
  Move(S1[1], FCBuffer[0], SizeOf(FCBuffer));

  MDCoder;

  result := FBuffer;
end;

{ TIdHashMessageDigest5 }

const
  MD5_SINE : array [1..64] of LongWord = (
   { Round 1. }
   $d76aa478, $e8c7b756, $242070db, $c1bdceee, $f57c0faf, $4787c62a,
   $a8304613, $fd469501, $698098d8, $8b44f7af, $ffff5bb1, $895cd7be,
   $6b901122, $fd987193, $a679438e, $49b40821,
   { Round 2. }
   $f61e2562, $c040b340, $265e5a51, $e9b6c7aa, $d62f105d, $02441453,
   $d8a1e681, $e7d3fbc8, $21e1cde6, $c33707d6, $f4d50d87, $455a14ed,
   $a9e3e905, $fcefa3f8, $676f02d9, $8d2a4c8a,
   { Round 3. }
   $fffa3942, $8771f681, $6d9d6122, $fde5380c, $a4beea44, $4bdecfa9,
   $f6bb4b60, $bebfbc70, $289b7ec6, $eaa127fa, $d4ef3085, $04881d05,
   $d9d4d039, $e6db99e5, $1fa27cf8, $c4ac5665,
   { Round 4. }
   $f4292244, $432aff97, $ab9423a7, $fc93a039, $655b59c3, $8f0ccc92,
   $ffeff47d, $85845dd1, $6fa87e4f, $fe2ce6e0, $a3014314, $4e0811a1,
   $f7537e82, $bd3af235, $2ad7d2bb, $eb86d391
  );

procedure TIdHashMessageDigest5.MDCoder;
var
  A, B, C, D : LongWord;
  I64 : Int64;
  x : T16x4LongWordRecord; // 64-byte buffer

  function DoAdd(const AOne, ATwo, AThree, AFour, AFive, AROL
    : LongWord) : LongWord;
  begin
    I64 := ATwo;
    I64 := I64 + AThree + AFour + AFive;
    I64 := ROL(I64 and $FFFFFFFF, AROL);
    Inc(I64, AOne);
    result := I64 and $FFFFFFFF;
  end;
begin
  A := FBuffer[0];
  B := FBuffer[1];
  C := FBuffer[2];
  D := FBuffer[3];

  System.Move(FCBuffer[0], x[0], SizeOf(x));

  { Round 1 }
  A := DoAdd(B, A, func_f(B, C, D), x[0], MD5_SINE[1], 7);
  D := DoAdd(A, D, func_f(A, B, C), x[1], MD5_SINE[2], 12);
  C := DoAdd(D, C, func_f(D, A, B), x[2], MD5_SINE[3], 17);
  B := DoAdd(C, B, func_f(C, D, A), x[3], MD5_SINE[4], 22);
  A := DoAdd(B, A, func_f(B, C, D), x[4], MD5_SINE[5], 7);
  D := DoAdd(A, D, func_f(A, B, C), x[5], MD5_SINE[6], 12);
  C := DoAdd(D, C, func_f(D, A, B), x[6], MD5_SINE[7], 17);
  B := DoAdd(C, B, func_f(C, D, A), x[7], MD5_SINE[8], 22);
  A := DoAdd(B, A, func_f(B, C, D), x[8], MD5_SINE[9], 7);
  D := DoAdd(A, D, func_f(A, B, C), x[9], MD5_SINE[10], 12);
  C := DoAdd(D, C, func_f(D, A, B), x[10], MD5_SINE[11], 17);
  B := DoAdd(C, B, func_f(C, D, A), x[11], MD5_SINE[12], 22);
  A := DoAdd(B, A, func_f(B, C, D), x[12], MD5_SINE[13], 7);
  D := DoAdd(A, D, func_f(A, B, C), x[13], MD5_SINE[14], 12);
  C := DoAdd(D, C, func_f(D, A, B), x[14], MD5_SINE[15], 17);
  B := DoAdd(C, B, func_f(C, D, A), x[15], MD5_SINE[16], 22);

  { Round 2 }
  A := DoAdd(B, A, func_g(B, C, D), x[1], MD5_SINE[17], 5);
  D := DoAdd(A, D, func_g(A, B, C), x[6], MD5_SINE[18], 9);
  C := DoAdd(D, C, func_g(D, A, B), x[11], MD5_SINE[19], 14);
  B := DoAdd(C, B, func_g(C, D, A), x[0], MD5_SINE[20], 20);
  A := DoAdd(B, A, func_g(B, C, D), x[5], MD5_SINE[21], 5);
  D := DoAdd(A, D, func_g(A, B, C), x[10], MD5_SINE[22], 9);
  C := DoAdd(D, C, func_g(D, A, B), x[15], MD5_SINE[23], 14);
  B := DoAdd(C, B, func_g(C, D, A), x[4], MD5_SINE[24], 20);
  A := DoAdd(B, A, func_g(B, C, D), x[9], MD5_SINE[25], 5);
  D := DoAdd(A, D, func_g(A, B, C), x[14], MD5_SINE[26], 9);
  C := DoAdd(D, C, func_g(D, A, B), x[3], MD5_SINE[27], 14);
  B := DoAdd(C, B, func_g(C, D, A), x[8], MD5_SINE[28], 20);
  A := DoAdd(B, A, func_g(B, C, D), x[13], MD5_SINE[29], 5);
  D := DoAdd(A, D, func_g(A, B, C), x[2], MD5_SINE[30], 9);
  C := DoAdd(D, C, func_g(D, A, B), x[7], MD5_SINE[31], 14);
  B := DoAdd(C, B, func_g(C, D, A), x[12], MD5_SINE[32], 20);

  { Round 3. }
  A := DoAdd(B, A, func_h(B, C, D), x[5], MD5_SINE[33], 4);
  D := DoAdd(A, D, func_h(A, B, C), x[8], MD5_SINE[34], 11);
  C := DoAdd(D, C, func_h(D, A, B), x[11], MD5_SINE[35], 16);
  B := DoAdd(C, B, func_h(C, D, A), x[14], MD5_SINE[36], 23);
  A := DoAdd(B, A, func_h(B, C, D), x[1], MD5_SINE[37], 4);
  D := DoAdd(A, D, func_h(A, B, C), x[4], MD5_SINE[38], 11);
  C := DoAdd(D, C, func_h(D, A, B), x[7], MD5_SINE[39], 16);
  B := DoAdd(C, B, func_h(C, D, A), x[10], MD5_SINE[40], 23);
  A := DoAdd(B, A, func_h(B, C, D), x[13], MD5_SINE[41], 4);
  D := DoAdd(A, D, func_h(A, B, C), x[0], MD5_SINE[42], 11);
  C := DoAdd(D, C, func_h(D, A, B), x[3], MD5_SINE[43], 16);
  B := DoAdd(C, B, func_h(C, D, A), x[6], MD5_SINE[44], 23);
  A := DoAdd(B, A, func_h(B, C, D), x[9], MD5_SINE[45], 4);
  D := DoAdd(A, D, func_h(A, B, C), x[12], MD5_SINE[46], 11);
  C := DoAdd(D, C, func_h(D, A, B), x[15], MD5_SINE[47], 16);
  B := DoAdd(C, B, func_h(C, D, A), x[2], MD5_SINE[48], 23);

  { Round 4. }
  A := DoAdd(B, A, func_i(B, C, D), x[0], MD5_SINE[49], 6);
  D := DoAdd(A, D, func_i(A, B, C), x[7], MD5_SINE[50], 10);
  C := DoAdd(D, C, func_i(D, A, B), x[14], MD5_SINE[51], 15);
  B := DoAdd(C, B, func_i(C, D, A), x[5], MD5_SINE[52], 21);
  A := DoAdd(B, A, func_i(B, C, D), x[12], MD5_SINE[53], 6);
  D := DoAdd(A, D, func_i(A, B, C), x[3], MD5_SINE[54], 10);
  C := DoAdd(D, C, func_i(D, A, B), x[10], MD5_SINE[55], 15);
  B := DoAdd(C, B, func_i(C, D, A), x[1], MD5_SINE[56], 21);
  A := DoAdd(B, A, func_i(B, C, D), x[8], MD5_SINE[57], 6);
  D := DoAdd(A, D, func_i(A, B, C), x[15], MD5_SINE[58], 10);
  C := DoAdd(D, C, func_i(D, A, B), x[6], MD5_SINE[59], 15);
  B := DoAdd(C, B, func_i(C, D, A), x[13], MD5_SINE[60], 21);
  A := DoAdd(B, A, func_i(B, C, D), x[4], MD5_SINE[61], 6);
  D := DoAdd(A, D, func_i(A, B, C), x[11], MD5_SINE[62], 10);
  C := DoAdd(D, C, func_i(D, A, B), x[2], MD5_SINE[63], 15);
  B := DoAdd(C, B, func_i(C, D, A), x[9], MD5_SINE[64], 21);

  I64 := FBuffer[0];
  Inc(I64, A);
  FBuffer[0] := I64 and $FFFFFFFF;
  I64 := FBuffer[1];
  Inc(I64, B);
  FBuffer[1] := I64 and $FFFFFFFF;
  I64 := FBuffer[2];
  Inc(I64, C);
  FBuffer[2] := I64 and $FFFFFFFF;
  I64 := FBuffer[3];
  Inc(I64, D);
  FBuffer[3] := I64 and $FFFFFFFF;
end;

function TIdHashMessageDigest5.func_g(x, y, z : LongWord) : LongWord;
begin
  result := (x and z) or (y and (not z));
end;

function TIdHashMessageDigest5.func_i(x, y, z : LongWord) : LongWord;
begin
  result := y xor (x or (not z));
end;

end.
