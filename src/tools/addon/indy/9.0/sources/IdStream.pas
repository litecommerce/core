{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10351: IdStream.pas 
{
{   Rev 1.0    2002.11.12 10:54:04 PM  czhower
}
unit IdStream;
{
2002-04-10 -Andrew P.Rybin
  -Read*, Write*, ReadLn optimization (for many strings use TIdReadLineStreamProxy)
2002-04-16 -Andrew P.Rybin
  -TIdStreamSafe, TIdStreamLight, TIdReadLineStreamProxy, optimization, misc
}
{$I IdCompilerDefines.inc}
interface
uses
  Classes,
  IdException, IdGlobal;

type
  EIdEndOfStream = class(EIdException);

  TIdStream = class(TStream)
  // IMPORTANT!!!!!!!!
  // NO data members may exist in this class
  // This class is used to "hackcast" a TStream to add functionality
  public
    function  ReadLn(AMaxLineLength: Integer = -1; AExceptionIfEOF: Boolean = FALSE): String;
    class function FindEOL(ABuf: PChar; var VLineBufSize: Integer; var VCrEncountered: Boolean): Integer;{Ret: StringSize}
    procedure Write(const AData: string); reintroduce; overload;
    procedure WriteLn(const AData: string = ''); overload;   {Do not Localize}
    procedure WriteLn(const AData: string; const AArgs: array of const); overload;

    function  This: TIdStream; // Result := SELF; "THIS Object"
    function  BOF: Boolean; {Begin of Stream}
    function  EOF: Boolean; {End of Stream}
    procedure Skip(ASize: Integer);

    function  ReadInteger (const AConvert: Boolean = TRUE): Integer; //network order
    procedure WriteInteger(AValue: Integer; const AConvert: Boolean = True);

    function  ReadString (const AConvert: Boolean = TRUE): String;
    procedure WriteString(const AStr: String; const AConvert: Boolean = True);
  End;//TIdStream


implementation

uses
  IdResourceStrings,
  IdStack,
  IdTCPConnection,
  IdTCPStream,
  SysUtils;

const
  LBUFMAXSIZE = 2048;
  EOLArray = [CR,LF];
  WCSize = SizeOf(WideChar);

{ TIdStream }

class function TIdStream.FindEOL(ABuf: PChar; var VLineBufSize: Integer; var VCrEncountered: Boolean): Integer;
var
  i: Integer;
begin
  Result := VLineBufSize; //EOL not found => use all
  i := 0; //[0..ALineBufSize-1]
  while i < VLineBufSize do begin
    case ABuf[i] of
      LF:
        begin
          Result := i; {string size}
          VCrEncountered := TRUE;
          VLineBufSize := i+1;
          BREAK;
        end;//LF
      CR:
        begin
          Result := i; {string size}
          VCrEncountered := TRUE;
          inc(i); //crLF?
          if (i < VLineBufSize) and (ABuf[i] = LF) then begin
            VLineBufSize := i+1;
          end
          else begin
            VLineBufSize := i;
          end;
          BREAK;
        end;//CR
    end;//case
    Inc(i);
  end;//while
End;//FindEOL

function TIdStream.ReadLn(AMaxLineLength: Integer = -1; AExceptionIfEOF: Boolean = FALSE): String;

//TODO: Continue to optimize this function. Its performance severely impacts
// the coders
var
  LBufSize, LStringLen, LResultLen: Integer;
  LBuf: packed array [0..LBUFMAXSIZE] of Char;
  LStrmPos, LStrmSize: Integer; //LBytesToRead = stream size - Position
  LCrEncountered: Boolean;
begin
  // 'is' does not work here - compiler error
  if InheritsFrom(TIdTCPStream) then begin
    Result := TIdTCPStream(Self).Connection.ReadLn(LF,-1,AMaxLineLength);
  end
  else begin
    if AMaxLineLength < 0 then begin
      AMaxLineLength := MaxInt;
    end;//if
    LCrEncountered := FALSE;
    Result := '';
    { we store the stream size for the whole routine to prevent
    so do not incur a performance penalty with TStream.Size.  It has
    to use something such as Seek each time the size is obtained}
    {LStrmPos := SrcStream.Position; LStrmSize:= SrcStream.Size; 4 seek vs 3 seek}
    LStrmPos := Seek(0, soFromCurrent); //Position
    LStrmSize:= Seek(0, soFromEnd); //Size
    Seek(LStrmPos, soFromBeginning); //return position

    if (LStrmSize - LStrmPos) > 0 then begin

      while (LStrmPos < LStrmSize) and NOT LCrEncountered do begin
        LBufSize := Min(LStrmSize - LStrmPos, LBUFMAXSIZE);
        ReadBuffer(LBuf, LBufSize);
        LStringLen := FindEOL(LBuf,LBufSize,LCrEncountered);
        Inc(LStrmPos,LBufSize);

        LResultLen := Length(Result);
        if (LResultLen + LStringLen) > AMaxLineLength then begin
          LStringLen := AMaxLineLength - LResultLen;
          LCrEncountered := TRUE;
          Dec(LStrmPos,LBufSize);
          Inc(LStrmPos,LStringLen);
        end;//if
        SetLength(Result, LResultLen + LStringLen);
        Move(LBuf[0], PChar(Result)[LResultLen], LStringLen);
      end;//while
      Position := LStrmPos;
    end
    else begin
      if AExceptionIfEOF then begin
        raise EIdEndOfStream.Create(Format('End of stream: %s at %d',[ClassName,LStrmPos])); //LOCALIZE
      end;
    end;//if NOT EOF
  end;//if
End;//ReadLn

{function TIdStream.ReadLn: string;

//TODO: Continue to optimize this function. Its performance severely impacts
// the coders
var
  i: Integer;
  LBuf : String;
  LBufSize, LBufPos : Integer;
  LBytesToRead : Integer; //stream size - Position
  LLn: Integer;
  LStrmPos, LStrmSize : Integer;
  LCrEncountered : Boolean;

begin
  LCrEncountered := False;
  // 'is' does not work here - compiler error
  if InheritsFrom(TIdTCPStream) then begin
    Result := TIdTCPStream(Self).Connection.ReadLn;
  end else begin
    Result := '';
    LStrmPos := Position;
    { we store the stream size for the whole routine to prevent
    so do not incur a performance penalty with TStream.Size.  It has
    to use something such as Seek each time the size is obtained
    }
{    LStrmSize := Size;
    LBytesToRead := LStrmSize - LStrmPos;
    if LBytesToRead  > 0 then begin
      LBufPos := 0;
      while (LStrmPos < LStrmSize) and (LCrEncountered = False) do
    //  while (LStrmPos <= LBytesToRead) and (LCrEncountered = False) do
      begin
        if LBufPos < LBytesToRead then
        begin
          LBufSize := Min(LBytesToRead - LBufPos,LBUFMAXSIZE);
          SetLength(LBuf, LBufSize);
          ReadBuffer(LBuf[1], LBufSize);
          for i := 1 to LBufSize do
          begin
            case LBuf[i] of
              CR : begin
                     lln := i;
                     LBufSize := i+1;
                     if (i < LBufSize) and (LBuf[LBufSize]<>LF) then
                     begin
                       Dec(LBufSize);
                     end;
                     LCrEncountered := True;
                     Break;
                   end;
              LF : begin
                     lln := i;
                     LBufSize := i+1;
                     if (i < LBufSize) and (LBuf[LBufSize]<>CR) then
                     begin
                       Dec(LBufSize);
                     end;
                     LCrEncountered := True;
                     Break;
                   end;
            end;
          end;
          if LCrEncountered then
          begin
            Dec(lln);
            SetLength(LBuf,lln);
          end;
          Inc(LStrmPos,LBufSize);

          Result := Result + LBuf;
        end;
      end;
      Position := LStrmPos;
    end;
  end;
end; }

{nction TIdStream.ReadLn: string;
//TODO: Continue to optimize this function. Its performance severely impacts
// the coders
var
  i: Integer;
  LBuf : String;
  LBufSize, LBufPos : Integer;
  LBytesToRead : Integer; //stream size - Position
  LLn: Integer;
  LStrmPos, LStrmSize : Integer;
  LCrEncountered : Boolean;
begin
  LCrEncountered := False;
  // 'is' does not work here - compiler error
  if InheritsFrom(TIdTCPStream) then begin
    Result := TIdTCPStream(Self).Connection.ReadLn;
  end else begin
    Result := '';
    LStrmPos := Position;
    { we store the stream size for the whole routine to prevent
    so do not incur a performance penalty with TStream.Size.  It has
    to use something such as Seek each time the size is obtained
    }
{   LStrmSize := Size;
    LBytesToRead := LStrmSize - LStrmPos;
    if LBytesToRead  > 0 then begin
      LBufPos := 0;
      while (LStrmPos < LStrmSize) and (LCrEncountered = False) do
    //  while (LStrmPos <= LBytesToRead) and (LCrEncountered = False) do
      begin
        if LBufPos < LBytesToRead then
        begin
          LBufSize := LBytesToRead - LBufPos;
          if LBufSize > LBUFMAXSIZE then
          begin
            LBufSize := LBUFMAXSIZE;
          end;
          SetLength(LBuf, LBufSize);
          ReadBuffer(LBuf[1], LBufSize);
          lln := IndyPos(LF, LBuf);
          i := IndyPos(CR, LBuf);
          LCrEncountered := (lln > 0) or (i > 0);
          if LCrEncountered then
          begin
            //we only want i and lln not to equal zero unless both are zero
            //The reason is that some broken things might return just a CR or a LF
            //instead of both
            if lln = 0 then
            begin
              lln := i;
            end;
            if i = 0 then
            begin
              i := lln;
            end;
            //we do these two tests to make sure the CR and LF are together.
            //if they are appart, we assume they are two different line endings.
            if (lln > (i+1)) then
            begin
              lln := i;
            end;
            if (i > (lln+1)) then
            begin
              i := lln;
            end;
            LBufSize := IdGlobal.Max(lln,i);
          end;
          Inc(LStrmPos,LBufSize);

          Result := Result + LBuf;

          if LCrEncountered then
          begin
            SetLength(Result,Min(lln,i)-1);
          end;
        end;
      end;
      Position := LStrmPos;
    end;
  end;
end;     }


procedure TIdStream.Write(const AData: string);
var
  LDataLen: Integer;
begin
  LDataLen := Length(AData);
  if LDataLen > 0 then begin
    WriteBuffer(Pointer(AData)^, LDataLen);
  end;
end;

procedure TIdStream.WriteLn(const AData: string = '');    {Do not Localize}
begin
  Write(AData + sLineBreak);
end;

procedure TIdStream.WriteLn(const AData: string; const AArgs: array of const);
Begin
  WriteLn(Format(AData, AArgs));
End;//

function TIdStream.This: TIdStream;
Begin
  Result := SELF;
End;//

function TIdStream.BOF: Boolean;
Begin
  Result := Seek(0,soFromCurrent)<=0; //Stream.Position
End;

function TIdStream.EOF: Boolean;
var
  LPos: Int64;
Begin
  LPos := Seek(0,soFromCurrent);
  Result := LPos>=Seek(0,soFromEnd);
  Seek(LPos,soFromBeginning);
End;//EOF

procedure TIdStream.Skip(ASize: Integer);
Begin
  Seek(ASize, soFromCurrent);
End;//Skip

function TIdStream.ReadInteger(const AConvert: Boolean): Integer;
begin
  ReadBuffer(Result, SizeOf(Result));
  if AConvert then begin
    Result := Integer(GStack.WSNToHL(LongWord(Result)));
  end;
end;

procedure TIdStream.WriteInteger(AValue: Integer; const AConvert: Boolean = True);
begin
  if AConvert then begin
    AValue := Integer(GStack.WSHToNL(LongWord(AValue)));
  end;
  WriteBuffer(AValue, SizeOf(AValue));
end;

function TIdStream.ReadString(const AConvert: Boolean = TRUE): String;
var
  L: Integer;
Begin
  L := ReadInteger(AConvert);
  if L>0 then begin
    SetString(Result, NIL, L);
    ReadBuffer(Pointer(Result)^,L);
  end
  else begin
    Result := '';
  end;
End;//ReadString

procedure TIdStream.WriteString(const AStr: String; const AConvert: Boolean = True);
var
  L: Integer;
Begin
  L:= Length(AStr);
  WriteInteger(L, AConvert);
  if L>0 then begin
    WriteBuffer(Pointer(AStr)^,L);
  end;
End;//WriteS

END.
