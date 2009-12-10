{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10169: IdGlobal.pas 
{
{   Rev 1.2    1/9/2003 05:44:10 PM  JPMugaas
{ Added workaround for if a space is missing after the comma in a date.  For
{ example:
{ 
{ Wed,08 Jan 2003 08:09:16 PM
}
{
{   Rev 1.1    29/11/2002 10:16:40 AM  SGrobety
{ Changed GetTickCount to use high permormance counters if possible under
{ Windows
}
{
{   Rev 1.0    2002.11.12 10:39:16 PM  czhower
}
unit IdGlobal;

interface
{
2002-04-02 - Darren Kosinski (Borland) - Have SetThreadPriority do nothing on Linux.
2002-01-28 - Hadi Hariri. Fixes for C++ Builder. Thanks to Chuck Smith.
2001-12-21 - Andrew P.Rybin
 - Fetch,FetchCaseInsensitive,IsNumeric(Chr),PosIdx,AnsiPosIdx optimization
2001-Nov-26 - Peter Mee
 - Added IndyStrToBool
2001-Nov-21 - Peter Mee
 - Moved the Fetch function's default values to constants.
 - Added FetchCaseInsensitive.
11-10-2001 - J. Peter Mugaas
  - Merged changes proposed by Andrew P.Rybin}

{$I IdCompilerDefines.inc}

{This is the only unit with references to OS specific units and IFDEFs. NO OTHER units
are permitted to do so except .pas files which are counterparts to dfm/xfm files, and only for
support of that.}

uses
  {$IFDEF MSWINDOWS}
  Windows,
  {$ENDIF}
  Classes,
  IdException,
  SyncObjs, SysUtils;

type
  TIdOSType = (otUnknown, otLinux, otWindows);

const
  IdTimeoutDefault = -1;
  IdTimeoutInfinite = -2;

  IdFetchDelimDefault = ' ';    {Do not Localize}
  IdFetchDeleteDefault = true;
  IdFetchCaseSensitiveDefault = true;
  //We make the version things an INC so that they can be managed independantly
  //by the package builder.
  {$I IdVers.inc}
  //
  CHAR0 = #0;
  BACKSPACE = #8;
  LF = #10;
  CR = #13;
  EOL = CR + LF;
  TAB = #9;
  CHAR32 = #32;
  {$IFNDEF VCL6ORABOVE}
  //Only D6&Kylix have this constant
  sLineBreak = EOL;
  {$ENDIF}

  LWS = [TAB, CHAR32];
  wdays: array[1..7] of string = ('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'    {Do not Localize}
   , 'Sat'); {do not localize}
  monthnames: array[1..12] of string = ('Jan', 'Feb', 'Mar', 'Apr', 'May'    {Do not Localize}
   , 'Jun',  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'); {do not localize}
  IdHexDigits: array [0..15] of Char = '0123456789ABCDEF';    {Do not Localize}

  {$IFDEF Linux}
  GPathDelim = '/'; {do not localize}
  GOSType = otLinux;
  INFINITE = LongWord($FFFFFFFF);     { Infinite timeout }

  // approximate values, its finer grained on Linux
  tpIdle = 19;
  tpLowest = 12;
  tpLower = 6;
  tpNormal = 0;
  tpHigher = -7;
  tpHighest = -13;
  tpTimeCritical = -20;
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  GPathDelim = '\'; {do not localize}
  GOSType = otWindows;
  infinite = windows.INFINITE; { redeclare here for use elsewhere without using Windows.pas }  // cls modified 1/23/2002
  {$ENDIF}

type
  {$IFDEF LINUX}
    {$IFNDEF VCL6ORABOVE}
    THandle = LongWord; //D6.System
    {$ENDIF}
  TIdThreadPriority = -20..19;
  {$ENDIF}
  {$IFDEF MSWINDOWS}
    {$IFNDEF VCL6ORABOVE}
    THandle = Windows.THandle;
    {$ENDIF}
  TIdThreadPriority = TThreadPriority;
  {$ENDIF}

  {This way instead of a boolean for future expansion of other actions}
  TIdMaxLineAction = (maException, maSplit);

  TIdReadLnFunction = function: string of object;
  TStringEvent = procedure(ASender: TComponent; const AString: String);
  TPosProc = function(const Substr, S: string): Integer;
  TIdReuseSocket = (rsOSDependent, rsTrue, rsFalse);

  TIdCardinalBytes = record
    case Integer of
    0: (
      Byte1: Byte;
      Byte2: Byte;
      Byte3: Byte;
      Byte4: Byte;);
    1: (Whole: Cardinal);
    2: (CharArray : array[0..3] of Char);
  end;

  TIdLocalEvent = class(TEvent)
  public
    constructor Create(const AInitialState: Boolean = False;
     const AManualReset: Boolean = False); reintroduce;
    function WaitFor: TWaitResult; overload;
  end;

  TIdMimeTable = class(TObject)
  protected
    FOnBuildCache: TNotifyEvent;
    FMIMEList: TStringList;
    FFileExt: TStringList;
    procedure BuildDefaultCache; virtual;
  public
    procedure BuildCache; virtual;
    procedure AddMimeType(const Ext, MIMEType: string);
    function GetFileMIMEType(const AFileName: string): string;
    function GetDefaultFileExt(Const MIMEType: string): string;
    procedure LoadFromStrings(AStrings: TStrings; const MimeSeparator: Char = '=');    {Do not Localize}
    procedure SaveToStrings(AStrings: TStrings; const MimeSeparator: Char = '=');    {Do not Localize}
    constructor Create(Autofill: boolean=true); virtual;
    destructor Destroy; override;
    //
    property  OnBuildCache: TNotifyEvent read FOnBuildCache write FOnBuildCache;
  end;

  //APR: for fast Stream reading (ex: StringStream killer)
  TIdReadMemoryStream = class (TCustomMemoryStream)
  public
    procedure SetPointer(Ptr: Pointer; Size: Longint);
    function Write(const Buffer; Count: Longint): Longint; override;
  End;

  // TODO: add ALL IANA charsets
  TIdCharSet = (csGB2312, csBig5, csIso2022jp, csEucKR, csIso88591);

  {$IFNDEF VCL6ORABOVE}
  PByte =^Byte;
  PWord =^Word;
  {$ENDIF}

  {$IFDEF LINUX}
  TIdPID = Integer;
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  TIdPID = LongWord;
  {$ENDIF}

  {$IFDEF MSWINDOWS}
  TIdWin32Type = (Win32s, WindowsNT40, Windows95, Windows95OSR2, Windows98, Windows98SE,Windows2000, WindowsMe, WindowsXP);
  {$ENDIF}

  //This is called whenever there is a failure to retreive the time zone information
  EIdFailedToRetreiveTimeZoneInfo = class(EIdException);
  //This usually is a property editor exception
  EIdCorruptServicesFile = class(EIdException);
  //
  EIdExtensionAlreadyExists = class(EIdException);

// Procs - KEEP THESE ALPHABETICAL!!!!!
  function  AnsiMemoryPos(const ASubStr: String; MemBuff: PChar; MemorySize: Integer): Integer;
  function  AnsiPosIdx(const ASubStr,AStr: AnsiString; AStartPos: Cardinal=0): Cardinal;
  {$IFNDEF VCL5ORABOVE}
  function  AnsiSameText(const S1, S2: string): Boolean;
  procedure FreeAndNil(var Obj);
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  function GetFileCreationTime(const Filename: string): TDateTime;
  function GetInternetFormattedFileTimeStamp(const AFilename: String): String;
  {$ENDIF}
//  procedure BuildMIMETypeMap(dest: TStringList);
  // TODO: IdStrings have optimized SplitColumns* functions, can we remove it?
  function BreakApart(BaseString, BreakString: string; StringList: TStrings): TStrings;
  procedure CommaSeparatedToStringList(AList: TStrings; const Value:string);
  function CopyFileTo(const Source, Destination: string): Boolean;
  function CurrentProcessId: TIdPID;
  function DateTimeToGmtOffSetStr(ADateTime: TDateTime; SubGMT: Boolean): string;
  function DateTimeGMTToHttpStr(const GMTValue: TDateTime) : String;
  Function DateTimeToInternetStr(const Value: TDateTime; const AIsGMT : Boolean = False) : String;
  procedure DebugOutput(const AText: string);
  function DomainName(const AHost: String): String;
  function Fetch(var AInput: string; const ADelim: string = IdFetchDelimDefault;
    const ADelete: Boolean = IdFetchDeleteDefault;
    const ACaseSensitive : Boolean = IdFetchCaseSensitiveDefault) : string;
  function FetchCaseInsensitive(var AInput: string; const ADelim: string = IdFetchDelimDefault;
    const ADelete: Boolean = IdFetchDeleteDefault) : string;
  function FileSizeByName(const AFilename: string): Int64;
  function GetMIMETypeFromFile(const AFile: TFileName): string;
  function GetSystemLocale: TIdCharSet;
  function GetThreadHandle(AThread: TThread): THandle;
  function GetTickCount: Cardinal;
  //required because GetTickCount will wrap
  function GetTickDiff(const AOldTickCount, ANewTickCount : Cardinal):Cardinal;
  function GmtOffsetStrToDateTime(S: string): TDateTime;
  function GMTToLocalDateTime(S: string): TDateTime;
  function IdPorts: TList;
  function iif(ATest: Boolean; const ATrue: Integer; const AFalse: Integer): Integer; overload;
  function iif(ATest: Boolean; const ATrue: string;  const AFalse: string): string; overload;
  function iif(ATest: Boolean; const ATrue: Boolean; const AFalse: Boolean): Boolean; overload;
  function IncludeTrailingSlash(const APath: string): string;
  function IntToBin(Value: cardinal): string;
  function IndyGetHostName: string;
  function IndyInterlockedIncrement(var I: Integer): Integer;
  function IndyInterlockedDecrement(var I: Integer): Integer;
  function IndyInterlockedExchange(var A: Integer; B: Integer): Integer;
  function IndyInterlockedExchangeAdd(var A: Integer; B: Integer): Integer;
  function IndyStrToBool(const AString: String): Boolean;
  function IsCurrentThread(AThread: TThread): boolean;
  function IsDomain(const S: String): Boolean;
  function IsFQDN(const S: String): Boolean;
  function IsHostname(const S: String): Boolean;
  function IsNumeric(AChar: Char): Boolean; overload;
  function IsNumeric(const AString: string): Boolean; overload;
  function IsTopDomain(const AStr: string): Boolean;
  function IsValidIP(const S: String): Boolean;
  function InMainThread: boolean;
  function Max(AValueOne,AValueTwo: Integer): Integer;
  {APR: Help function to construct TMethod record. Can be useful to assign regular type procedure/function as event handler
  for event, defined as object method (do not forget, that in that case it must have first dummy parameter to replace @Self,
  passed in EAX to methods of object)}
  function MakeMethod (DataSelf, Code: Pointer): TMethod;
  function MakeTempFilename(const APath: String = ''): string;
  function Min(AValueOne, AValueTwo: Integer): Integer;
  function OffsetFromUTC: TDateTime;
  function PosIdx (const ASubStr,AStr: AnsiString; AStartPos: Cardinal=0): Cardinal;//For "ignoreCase" use AnsiUpperCase
  function PosInStrArray(const SearchStr: string; Contents: array of string;
    const CaseSensitive: Boolean=True): Integer;
  function ProcessPath(const ABasePath: String; const APath: String;
    const APathDelim: string = '/'): string;    {Do not Localize}
  function RightStr(const AStr: String; Len: Integer): String;
  function ROL(AVal: LongWord; AShift: Byte): LongWord;
  function ROR(AVal: LongWord; AShift: Byte): LongWord;
  function RPos(const ASub, AIn: String; AStart: Integer = -1): Integer;
  function SetLocalTime(Value: TDateTime): boolean;
  procedure SetThreadPriority(AThread: TThread; const APriority: TIdThreadPriority; const APolicy: Integer = -MaxInt);
  procedure Sleep(ATime: cardinal);
  function StrToCard(const AStr: String): Cardinal;
  function StrInternetToDateTime(Value: string): TDateTime;
  function StrToDay(const ADay: string): Byte;
  function StrToMonth(const AMonth: string): Byte;
  function MemoryPos(const ASubStr: String; MemBuff: PChar; MemorySize: Integer): Integer;
  function TimeZoneBias: TDateTime;
  function UpCaseFirst(const AStr: string): string;
  {$IFDEF MSWINDOWS}
  function Win32Type : TIdWin32Type;
  {$ENDIF}

var
  IndyPos: TPosProc = nil;
  {$IFDEF LINUX}
  // For linux the user needs to set these variables to be accurate where used (mail, etc)
  GOffsetFromUTC: TDateTime = 0;
  GSystemLocale: TIdCharSet = csIso88591;
  GTimeZoneBias: TDateTime = 0;
  {$ENDIF}

  IndyFalseBoolStrs : array of String;
  IndyTrueBoolStrs : array of String;

implementation

uses
  {$IFDEF LINUX}
  Libc,
  IdStackLinux,
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  IdStackWindows,
  Registry,
  {$ENDIF}
  IdStack, IdResourceStrings, IdURI;

const
  WhiteSpace = [#0..#12, #14..' ']; {do not localize}


var
  FIdPorts: TList;
  {$IFDEF MSWINDOWS}
  ATempPath: string;
  {$ENDIF}

{This routine is based on JPM Open by J. Peter Mugaas.  Permission is granted
to use this with Indy under Indy's Licenses

Note that JPM Open is under a different Open Source license model.

It is available at http://www.wvnet.edu/~oma00215/jpm.html }

{$IFDEF MSWINDOWS}
function Win32Type: TIdWin32Type;
begin
  {VerInfo.dwOSVersionInfoSize := SizeOf(TOSVersionInfo);  GetVersionEx(VerInfo);}
  {is this Windows 2000 or XP?}
  if Win32MajorVersion >= 5 then begin
    if Win32MinorVersion >= 1 then begin
      Result := WindowsXP;
    end
    else begin
      Result := Windows2000;
    end;
  end
  else begin
    {is this WIndows 95, 98, Me, or NT 40}
    if Win32MajorVersion > 3 then begin
      if Win32Platform = VER_PLATFORM_WIN32_NT then begin
        Result := WindowsNT40;
      end
      else begin
        {mask off junk}
        Win32BuildNumber := Win32BuildNumber and $FFFF;
        if Win32MinorVersion >= 90 then begin
          Result := WindowsMe;
        end
        else begin
          if Win32MinorVersion >= 10 then begin
            {Windows 98}
            if Win32BuildNumber >= 2222 then begin
              Result := Windows98SE
            end
            else begin
              Result := Windows98;
            end;
          end
          else begin {Windows 95}
            if Win32BuildNumber >= 1000 then begin
              Result := Windows95OSR2
            end
            else begin
              Result := Windows95;
            end;
          end;
        end;
      end;//if VER_PLATFORM_WIN32_NT
    end
    else begin
      Result := Win32s;
    end;
  end;//if Win32MajorVersion >= 5
end;
{$ENDIF}

function GetThreadHandle(AThread : TThread) : THandle;
begin
  {$IFDEF LINUX}
  Result := AThread.ThreadID;
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  Result := AThread.Handle;
  {$ENDIF}
end;

{This is an internal procedure so the StrInternetToDateTime and GMTToLocalDateTime can share common code}
function RawStrInternetToDateTime(var Value: string): TDateTime;
var
  i: Integer;
  Dt, Mo, Yr, Ho, Min, Sec: Word;
  sTime: String;
  ADelim: string;

  Procedure ParseDayOfMonth;
  begin
    Dt :=  StrToIntDef( Fetch(Value, ADelim), 1);
    Value := TrimLeft(Value);
  end;

  Procedure ParseMonth;
  begin
    Mo := StrToMonth( Fetch ( Value, ADelim )  );
    Value := TrimLeft(Value);
  end;
begin
  Result := 0.0;
  Value := Trim(Value);
  if Length(Value) = 0 then begin
    Exit;
  end;

  try
    {Day of Week}
    if StrToDay(Copy(Value, 1, 3)) > 0 then begin
      //workaround in case a space is missing after the initial column
      if (Copy(Value,4,1)=',') and (Copy(Value,5,1)<>' ') then
      begin
        System.Insert(' ',Value,5);
      end;
      Fetch(Value);
      Value := TrimLeft(Value);
    end;

    // Workaround for some buggy web servers which use '-' to separate the date parts.    {Do not Localize}
    if (IndyPos('-', Value) > 1) and (IndyPos('-', Value) < IndyPos(' ', Value)) then begin    {Do not Localize}
      ADelim := '-';    {Do not Localize}
    end
    else begin
      ADelim := ' ';    {Do not Localize}
    end;
    //workaround for improper dates such as 'Fri, Sep 7 2001'    {Do not Localize}
    //RFC 2822 states that they should be like 'Fri, 7 Sep 2001'    {Do not Localize}
    if (StrToMonth(Fetch(Value, ADelim,False)) > 0) then
    begin
      {Month}
      ParseMonth;
      {Day of Month}
      ParseDayOfMonth;
    end
    else
    begin
      {Day of Month}
      ParseDayOfMonth;
      {Month}
      ParseMonth;
    end;
    {Year}
    // There is sometrage date/time formats like
    // DayOfWeek Month DayOfMonth Time Year

    sTime := Fetch(Value);
    Yr := StrToIntDef(sTime, 1900);
    // Is sTime valid Integer
    if Yr = 1900 then begin
      Yr := StrToIntDef(Value, 1900);
      Value := sTime;
    end;
    if Yr < 80 then begin
      Inc(Yr, 2000);
    end else if Yr < 100 then begin
      Inc(Yr, 1900);
    end;

    Result := EncodeDate(Yr, Mo, Dt);
    // SG 26/9/00: Changed so that ANY time format is accepted
    i := IndyPos(':', Value); {do not localize}
    if i > 0 then begin
      // Copy time string up until next space (before GMT offset)
      sTime := fetch(Value, ' ');  {do not localize}
      {Hour}
      Ho  := StrToIntDef( Fetch ( sTime,':'), 0);  {do not localize}
      {Minute}
      Min := StrToIntDef( Fetch ( sTime,':'), 0);  {do not localize}
      {Second}
      Sec := StrToIntDef( Fetch ( sTime ), 0);
      {The date and time stamp returned}
      Result := Result + EncodeTime(Ho, Min, Sec, 0);
    end;
    Value := TrimLeft(Value);
  except
    Result := 0.0;
  end;
end;

function IncludeTrailingSlash(const APath: string): string;
begin
  {for some odd reason, the IFDEF's were not working in Delphi 4    
  so as a workaround and to ensure some code is actually compiled into
  the procedure, I use a series of $elses}
  {$IFDEF VCL5O}
  Result := IncludeTrailingBackSlash(APath);
  {$ELSE}
    {$IFDEF VCL6ORABOVE}
    Result :=  IncludeTrailingPathDelimiter(APath);
    {$ELSE}
    Result := APath;
    if not IsPathDelimiter(Result, Length(Result)) then begin
      Result := Result + GPathDelim;
    end;
    {$ENDIF}
  {$ENDIF}
end;

{$IFNDEF VCL5ORABOVE}
function AnsiSameText(const S1, S2: string): Boolean;
begin
  Result := CompareString(LOCALE_USER_DEFAULT, NORM_IGNORECASE, PChar(S1)
   , Length(S1), PChar(S2), Length(S2)) = 2;
end;

procedure FreeAndNil(var Obj);
var
  P: TObject;
begin
  if TObject(Obj) <> nil then begin
    P := TObject(Obj);
    TObject(Obj) := nil;  // clear the reference before destroying the object
    P.Free;
  end;
end;
{$ENDIF}

{$IFDEF MSWINDOWS}
  {$IFNDEF VCL5ORABOVE}
  function CreateTRegistry: TRegistry;
  begin
    Result := TRegistry.Create;
  end;
  {$ELSE}
  function CreateTRegistry: TRegistry;
  begin
    Result := TRegistry.Create(KEY_READ);
  end;
  {$ENDIF}
{$ENDIF}

function Max(AValueOne,AValueTwo: Integer): Integer;
begin
  if AValueOne < AValueTwo then
  begin
    Result := AValueTwo
  end //if AValueOne < AValueTwo then
  else
  begin
    Result := AValueOne;
  end; //else..if AValueOne < AValueTwo then
end;

function Min(AValueOne, AValueTwo : Integer): Integer;
begin
  If AValueOne > AValueTwo then
  begin
    Result := AValueTwo
  end //If AValueOne > AValueTwo then
  else
  begin
    Result := AValueOne;
  end; //..If AValueOne > AValueTwo then
end;

{This should never be localized}
function DateTimeGMTToHttpStr(const GMTValue: TDateTime) : String;
// should adhere to RFC 2616
var
  wDay,
  wMonth,
  wYear: Word;
begin
  DecodeDate(GMTValue, wYear, wMonth, wDay);
  Result := Format('%s, %.2d %s %.4d %s %s',    {do not localize}
                   [wdays[DayOfWeek(GMTValue)], wDay, monthnames[wMonth],
                    wYear, FormatDateTime('HH":"NN":"SS', GMTValue), 'GMT']);  {do not localize}
end;

{This should never be localized}
function DateTimeToInternetStr(const Value: TDateTime; const AIsGMT : Boolean = False) : String;
var
  wDay,
  wMonth,
  wYear: Word;
begin
  DecodeDate(Value, wYear, wMonth, wDay);
  Result := Format('%s, %d %s %d %s %s',    {do not localize}
                   [wdays[DayOfWeek(Value)], wDay, monthnames[wMonth],
                    wYear, FormatDateTime('HH":"NN":"SS', Value),  {do not localize}
                    DateTimeToGmtOffSetStr(OffsetFromUTC, AIsGMT)]);
end;

function StrInternetToDateTime(Value: string): TDateTime;
begin
  Result := RawStrInternetToDateTime(Value);
end;

{$IFDEF MSWINDOWS}
function GetInternetFormattedFileTimeStamp(const AFilename: String):String;
const
  wdays: array[1..7] of string = ('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'); {do not localize}
  monthnames: array[1..12] of string = ('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',   {do not localize}
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'); {do not localize}
var
  DT1, DT2 : TDateTime;
  wDay, wMonth, wYear: Word;
begin
  DT1 := GetFileCreationTime(AFilename);
  DecodeDate(DT1, wYear, wMonth, wDay);
  DT2 := TimeZoneBias;
  Result := Format('%s, %d %s %d %s %s', [wdays[DayOfWeek(DT1)], wDay, monthnames[wMonth],   {do not localize}
   wYear, FormatDateTime('HH":"NN":"SS', DT1), DateTimeToGmtOffSetStr(DT2,False)]);   {do not localize}
end;

function GetFileCreationTime(const Filename: string): TDateTime;
var
  Data: TWin32FindData;
  H: THandle;
  FT: TFileTime;
  I: Integer;
begin
  H := FindFirstFile(PCHAR(Filename), Data);
  if H <> INVALID_HANDLE_VALUE then begin
    try
      FileTimeToLocalFileTime(Data.ftLastWriteTime, FT);
      FileTimeToDosDateTime(FT, LongRec(I).Hi, LongRec(I).Lo);
      Result := FileDateToDateTime(I);
    finally
      Windows.FindClose(H);
    end
  end else begin
    Result := 0;
  end;
end;
{$ENDIF}

function BreakApart(BaseString, BreakString: string; StringList: TStrings): TStrings;
var
  EndOfCurrentString: integer;
begin
  repeat
    EndOfCurrentString := Pos(BreakString, BaseString);
    if (EndOfCurrentString = 0) then
    begin
      StringList.add(BaseString);
    end
    else
      StringList.add(Copy(BaseString, 1, EndOfCurrentString - 1));
    delete(BaseString, 1, EndOfCurrentString + Length(BreakString) - 1); //Copy(BaseString, EndOfCurrentString + length(BreakString), length(BaseString) - EndOfCurrentString);
  until EndOfCurrentString = 0;
  result := StringList;
end;

procedure CommaSeparatedToStringList(AList: TStrings; const Value:string);
var
  iStart,
  iEnd,
  iQuote,
  iPos,
  iLength : integer ;
  sTemp : string ;
begin
  iQuote := 0;
  iPos := 1 ;
  iLength := Length(Value) ;
  AList.Clear ;
  while (iPos <= iLength) do
  begin
    iStart := iPos ;
    iEnd := iStart ;
    while ( iPos <= iLength ) do
    begin
      if Value[iPos] = '"' then  {do not localize}
      begin
        inc(iQuote);
      end;
      if Value[iPos] = ',' then  {do not localize}
      begin
        if iQuote <> 1 then
        begin
          break;
        end;
      end;
      inc(iEnd);
      inc(iPos);
    end ;
    sTemp := Trim(Copy(Value, iStart, iEnd - iStart));
    if Length(sTemp) > 0 then
    begin
      AList.Add(sTemp);
    end;
    iPos := iEnd + 1 ;
    iQuote := 0 ;
  end ;
end;

{$IFDEF LINUX}
function CopyFileTo(const Source, Destination: string): Boolean;
var
  SourceStream: TFileStream;
begin
  // -TODO: Change to use a Linux copy function
  // There is no native Linux copy function (at least "cp" doesn't use one
  // and I can't find one anywhere (Johannes Berg))
  Result := false;
  if not FileExists(Destination) then begin
    SourceStream := TFileStream.Create(Source, fmOpenRead); try
      with TFileStream.Create(Destination, fmCreate) do try
        CopyFrom(SourceStream, 0);
      finally Free; end;
    finally SourceStream.free; end;
    Result := true;
  end;
end;
{$ENDIF}
{$IFDEF MSWINDOWS}
function CopyFileTo(const Source, Destination: string): Boolean;
begin
  Result := CopyFile(PChar(Source), PChar(Destination), true);
end;
{$ENDIF}

{$IFDEF MSWINDOWS}
function TempPath: string;
var
	i: integer;
begin
  SetLength(Result, MAX_PATH);
	i := GetTempPath(Length(Result), PChar(Result));
	SetLength(Result, i);
  IncludeTrailingSlash(Result);
end;
{$ENDIF}

function MakeTempFilename(const APath: String = ''): string;
Begin
  {$IFDEF LINUX}
    {
    man tempnam
    [...]
    BUGS
       The precise meaning of `appropriate' is undefined;  it  is
       unspecified  how  accessibility  of  a directory is deter­
       mined.  Never use this function. Use tmpfile(3) instead.
    [...]

    Should we really use this?
    Alternatives would be to use tmpfile, but this creates a file.
    So maybe it would be worth checking if we ever need the name w/o a file!
  }
  Result := tempnam(nil, 'Indy');    {do not localize}
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  SetLength(Result, MAX_PATH + 1);
  if APath > '' then begin  {Do not localize}
    GetTempFileName(PChar(IncludeTrailingSlash(APath)), 'Indy', 0, PChar(Result));  {do not localize}
  end
  else begin
    GetTempFileName(PChar(ATempPath), 'Indy', 0, PChar(Result));  {do not localize}
  end;
  Result := PChar(Result);
  {$ENDIF}
End;

// Find a token given a direction (>= 0 from start; < 0 from end)
// S.G. 19/4/00:
//  Changed to be more readable
function RPos(const ASub, AIn: String; AStart: Integer = -1): Integer;
var
  i: Integer;
  LStartPos: Integer;
  LTokenLen: Integer;
begin
  result := 0;
  LTokenLen := Length(ASub);
  // Get starting position
  if AStart = -1 then begin
    AStart := Length(AIn);
  end;
  if AStart < (Length(AIn) - LTokenLen + 1) then begin
    LStartPos := AStart;
  end else begin
    LStartPos := (Length(AIn) - LTokenLen + 1);
  end;
  // Search for the string
  for i := LStartPos downto 1 do begin
    if AnsiSameText(Copy(AIn, i, LTokenLen), ASub) then begin
      result := i;
      break;
    end;
  end;
end;

function GetSystemLocale: TIdCharSet;
begin
{$IFDEF LINUX}
  Result := GSystemLocale;
{$ENDIF}
{$IFDEF MSWINDOWS}
  case SysLocale.PriLangID of
    LANG_CHINESE:
      if SysLocale.SubLangID = SUBLANG_CHINESE_SIMPLIFIED then
        Result := csGB2312
      else
        Result := csBig5;
    LANG_JAPANESE: Result := csIso2022jp;
    LANG_KOREAN: Result := csEucKR;
    else
      Result := csIso88591;
  end;
{$ENDIF}
end;

// OS-independant version
function FileSizeByName(const AFilename: string): Int64;
begin
  with TFileStream.Create(AFilename, fmOpenRead or fmShareDenyNone) do
  try
    Result := Size;
  finally Free; end;
end;


Function RightStr(const AStr: String; Len: Integer): String;
var
  LStrLen : Integer;
begin
  LStrLen := Length (AStr);
  if (Len > LStrLen) or (Len < 0) then begin
    Result := AStr;
  end  //f ( Len > Length ( st ) ) or ( Len < 0 ) then
  else begin
    //+1 is necessary for the Index because it is one based
    Result := Copy(AStr, LStrLen - Len+1, Len);
  end; //else ... f ( Len > Length ( st ) ) or ( Len < 0 ) then
end;

{$IFDEF LINUX}
function OffsetFromUTC: TDateTime;
begin
  //TODO: Fix OffsetFromUTC for Linux to be automatic from OS
  Result := GOffsetFromUTC;
end;
{$ENDIF}
{$IFDEF MSWINDOWS}
function OffsetFromUTC: TDateTime;
var
  iBias: Integer;
  tmez: TTimeZoneInformation;
begin
  Case GetTimeZoneInformation(tmez) of
    TIME_ZONE_ID_INVALID:
      raise EIdFailedToRetreiveTimeZoneInfo.Create(RSFailedTimeZoneInfo);
    TIME_ZONE_ID_UNKNOWN  :
       iBias := tmez.Bias;
    TIME_ZONE_ID_DAYLIGHT :
      iBias := tmez.Bias + tmez.DaylightBias;
    TIME_ZONE_ID_STANDARD :
      iBias := tmez.Bias + tmez.StandardBias;
    else
      raise EIdFailedToRetreiveTimeZoneInfo.Create(RSFailedTimeZoneInfo);
  end;
  {We use ABS because EncodeTime will only accept positve values}
  Result := EncodeTime(Abs(iBias) div 60, Abs(iBias) mod 60, 0, 0);
  {The GetTimeZone function returns values oriented towards convertin
   a GMT time into a local time.  We wish to do the do the opposit by returning
   the difference between the local time and GMT.  So I just make a positive
   value negative and leave a negative value as positive}
  if iBias > 0 then begin
    Result := 0 - Result;
  end;
end;
{$ENDIF}

function StrToCard(const AStr: String): Cardinal;
begin
  Result := StrToInt64Def(Trim(AStr),0);
end;

{$IFDEF LINUX}
function TimeZoneBias: TDateTime;
begin
  //TODO: Fix TimeZoneBias for Linux to be automatic
  Result := GTimeZoneBias;
end;
{$ENDIF}
{$IFDEF MSWINDOWS}
function TimeZoneBias: TDateTime;
var
  ATimeZone: TTimeZoneInformation;
begin
  case GetTimeZoneInformation(ATimeZone) of
    TIME_ZONE_ID_DAYLIGHT:
      Result := ATimeZone.Bias + ATimeZone.DaylightBias;
    TIME_ZONE_ID_STANDARD:
      Result := ATimeZone.Bias + ATimeZone.StandardBias;
    TIME_ZONE_ID_UNKNOWN:
      Result := ATimeZone.Bias;
    else
      raise EIdException.Create(SysErrorMessage(GetLastError));
  end;
  Result := Result / 1440;
end;
{$ENDIF}

{$IFDEF LINUX}
function GetTickCount: Cardinal;
var
  tv: timeval;
begin
  gettimeofday(tv, nil);
  {$RANGECHECKS OFF}
  Result := int64(tv.tv_sec) * 1000 + tv.tv_usec div 1000;
  {
    I've implemented this correctly for now. I'll argue for using
    an int64 internally, since apparently quite some functionality
    (throttle, etc etc) depends on it, and this value may wrap
    at any point in time.
    For Windows: Uptime > 72 hours isn't really that rare any more,
    For Linux: no control over when this wraps.

    IdEcho has code to circumvent the wrap, but its not very good
    to have code for that at all spots where it might be relevant.

  }
end;
{$ENDIF}

{$IFDEF MSWINDOWS}
// S.G. 27/11/2002: Changed to use high-performance counters as per suggested
// S.G. 27/11/2002: by David B. Ferguson (david.mcs@ns.sympatico.ca)
function GetTickCount: Cardinal;
var
  nTime, freq: Int64;
begin
  if Windows.QueryPerformanceFrequency(freq) then
    if Windows.QueryPerformanceCounter(nTime) then
       result:=Trunc(nTime/Freq*1000)
    else
       result:= Windows.GetTickCount
  else
    result:= Windows.GetTickCount;
end;
{$ENDIF}

function GetTickDiff(const AOldTickCount, ANewTickCount : Cardinal):Cardinal;
begin
  {This is just in case the TickCount rolled back to zero}
    if ANewTickCount >= AOldTickCount then begin
      Result := ANewTickCount - AOldTickCount;
    end else begin
      Result := High(Cardinal) - AOldTickCount + ANewTickCount;
    end;
end;

function IndyStrToBool(const AString : String) : Boolean;
var
  LCount : Integer;
begin
  // First check against each of the elements of the FalseBoolStrs
  for LCount := Low(IndyFalseBoolStrs) to High(IndyFalseBoolStrs) do
  begin
    if AnsiSameText(AString, IndyFalseBoolStrs[LCount]) then
    begin
      result := false;
      exit;
    end;
  end;
  // Second check against each of the elements of the TrueBoolStrs
  for LCount := Low(IndyTrueBoolStrs) to High(IndyTrueBoolStrs) do
  begin
    if AnsiSameText(AString, IndyTrueBoolStrs[LCount]) then
    begin
      result := true;
      exit;
    end;
  end;
  // None of the strings match, so convert to numeric (allowing an
  // EConvertException to be thrown if not) and test against zero.
  // If zero, return false, otherwise return true.
  LCount := StrToInt(AString);
  if LCount = 0 then
  begin
    result := false;
  end else
  begin
    result := true;
  end;
end;

{$IFDEF LINUX}
function SetLocalTime(Value: TDateTime): boolean;
begin
  //TODO: Implement SetTime for Linux. This call is not critical.
  result := False;
end;
{$ENDIF}
{$IFDEF MSWINDOWS}
function SetLocalTime(Value: TDateTime): boolean;
{I admit that this routine is a little more complicated than the one
in Indy 8.0.  However, this routine does support Windows NT privillages
meaning it will work if you have administrative rights under that OS

Original author Kerry G. Neighbour with modifications and testing
from J. Peter Mugaas}
var
   dSysTime: TSystemTime;
   buffer: DWord;
   tkp, tpko: TTokenPrivileges;
   hToken: THandle;
begin
  Result := False;
  if SysUtils.Win32Platform = VER_PLATFORM_WIN32_NT then
  begin
    if not Windows.OpenProcessToken(GetCurrentProcess(), TOKEN_ADJUST_PRIVILEGES or TOKEN_QUERY,
      hToken) then
    begin
      exit;
    end;
    Windows.LookupPrivilegeValue(nil, 'SE_SYSTEMTIME_NAME', tkp.Privileges[0].Luid);    {Do not Localize}
    tkp.PrivilegeCount := 1;
    tkp.Privileges[0].Attributes := SE_PRIVILEGE_ENABLED;
    if not Windows.AdjustTokenPrivileges(hToken, FALSE, tkp, sizeof(tkp), tpko, buffer) then
    begin
      exit;
    end;
  end;
  DateTimeToSystemTime(Value, dSysTime);
  Result := Windows.SetLocalTime(dSysTime);
  {Undo the Process Privillage change we had done for the set time
  and close the handle that was allocated}
  if SysUtils.Win32Platform = VER_PLATFORM_WIN32_NT then
  begin
    Windows.AdjustTokenPrivileges(hToken, FALSE,tpko, sizeOf(tpko), tkp, Buffer);
    Windows.CloseHandle(hToken);
  end;
end;
{$ENDIF}

// IdPorts returns a list of defined ports in /etc/services
function IdPorts: TList;
var
  sLocation, s: String;
  idx, i, iPrev, iPosSlash: integer;
  sl: TStringList;
begin
  if FIdPorts = nil then
  begin
    FIdPorts := TList.Create;
    {$IFDEF LINUX}
    sLocation := '/etc/';  // assume Berkeley standard placement   {do not localize}
    {$ENDIF}
    {$IFDEF MSWINDOWS}
    SetLength(sLocation, MAX_PATH);
    SetLength(sLocation, GetWindowsDirectory(pchar(sLocation), MAX_PATH));
    sLocation := IncludeTrailingSlash(sLocation);
    if Win32Platform = VER_PLATFORM_WIN32_NT then begin
      sLocation := sLocation + 'system32\drivers\etc\'; {do not localize}
    end;
    {$ENDIF}
    sl := TStringList.Create;
    try
      sl.LoadFromFile(sLocation + 'services');  {do not localize}
      iPrev := 0;
      for idx := 0 to sl.Count - 1 do
      begin
        s := sl[idx];
        iPosSlash := IndyPos('/', s);   {do not localize}
        if (iPosSlash > 0) and (not (IndyPos('#', s) in [1..iPosSlash])) then {do not localize}
        begin // presumably found a port number that isn't commented    {Do not Localize}
          i := iPosSlash;
          repeat
            dec(i);
            if i = 0 then begin
              raise EIdCorruptServicesFile.CreateFmt(RSCorruptServicesFile, [sLocation + 'services']); {do not localize}
            end;
          until s[i] in WhiteSpace;
          i := StrToInt(Copy(s, i+1, iPosSlash-i-1));
          if i <> iPrev then begin
            FIdPorts.Add(TObject(i));
          end;
          iPrev := i;
        end;
      end;
    finally
      sl.Free;
    end;
  end;
  Result := FIdPorts;
end;

function FetchCaseInsensitive(var AInput: string; const ADelim: string = IdFetchDelimDefault;
 const ADelete: Boolean = IdFetchDeleteDefault): String;
var
  LPos: integer;
begin
  if ADelim = #0 then begin
    // AnsiPos does not work with #0
    LPos := Pos(ADelim, AInput);
  end else begin
    //? may be AnsiUpperCase?
    LPos := IndyPos(UpperCase(ADelim), UpperCase(AInput));
  end;
  if LPos = 0 then begin
    Result := AInput;
    if ADelete then begin
      AInput := '';    {Do not Localize}
    end;
  end else begin
    Result := Copy(AInput, 1, LPos - 1);
    if ADelete then begin
      //This is faster than Delete(AInput, 1, LPos + Length(ADelim) - 1);
      AInput := Copy(AInput, LPos + Length(ADelim), MaxInt);
    end;
  end;
end;

function Fetch(var AInput: string; const ADelim: string = IdFetchDelimDefault;
 const ADelete: Boolean = IdFetchDeleteDefault;
 const ACaseSensitive: Boolean = IdFetchCaseSensitiveDefault): String;
var
  LPos: integer;
begin
  if ACaseSensitive then begin
    if ADelim = #0 then begin
      // AnsiPos does not work with #0
      LPos := Pos(ADelim, AInput);
    end else begin
      LPos := IndyPos(ADelim, AInput);
    end;
    if LPos = 0 then begin
      Result := AInput;
      if ADelete then begin
        AInput := '';    {Do not Localize}
      end;
    end
    else begin
      Result := Copy(AInput, 1, LPos - 1);
      if ADelete then begin
        //slower Delete(AInput, 1, LPos + Length(ADelim) - 1);
        AInput:=Copy(AInput, LPos + Length(ADelim), MaxInt);
      end;
    end;
  end else begin
    Result := FetchCaseInsensitive(AInput, ADelim, ADelete);
  end;
end;

{This searches an array of string for an occurance of SearchStr}
function PosInStrArray(const SearchStr: string; Contents: array of string; const CaseSensitive: Boolean=True): Integer;
begin
  for Result := Low(Contents) to High(Contents) do begin
    if CaseSensitive then begin
      if SearchStr = Contents[Result] then begin
        Exit;
      end;
    end else begin
      if ANSISameText(SearchStr, Contents[Result]) then begin
        Exit;
      end;
    end;
  end;  //for Result := Low(Contents) to High(Contents) do
  Result := -1;
end;

function IsCurrentThread(AThread: TThread): boolean;
begin
  result := AThread.ThreadID = GetCurrentThreadID;
end;

function IsNumeric(AChar: char): Boolean;
begin
  // Do not use IsCharAlpha or IsCharAlphaNumeric - they are Win32 routines
  Result := AChar in ['0'..'9'];    {Do not Localize}
end;

{$HINTS OFF}
function IsNumeric(const AString: string): Boolean;
var
  LCode: Integer;
  LVoid: Integer;
begin
  Val(AString, LVoid, LCode);
  Result := LCode = 0;
end;
{$HINTS ON}

function StrToDay(const ADay: string): Byte;
begin
  Result := Succ(PosInStrArray(Uppercase(ADay),
    ['SUN','MON','TUE','WED','THU','FRI','SAT']));   {do not localize}
end;

function StrToMonth(const AMonth: string): Byte;
begin
  Result := Succ(PosInStrArray(Uppercase(AMonth),
    ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC']));   {do not localize}
end;

function UpCaseFirst(const AStr: string): string;
begin
  Result := LowerCase(TrimLeft(AStr));
  if Result <> '' then begin   {Do not Localize}
    Result[1] := UpCase(Result[1]);
  end;
end;

function DateTimeToGmtOffSetStr(ADateTime: TDateTime; SubGMT: Boolean): string;
var
  AHour, AMin, ASec, AMSec: Word;
begin
  if (ADateTime = 0.0) and SubGMT then
  begin
    Result := 'GMT'; {do not localize}
    Exit;
  end;
  DecodeTime(ADateTime, AHour, AMin, ASec, AMSec);
  Result := Format(' %0.2d%0.2d', [AHour, AMin]); {do not localize}
  if ADateTime < 0.0 then
  begin
    Result[1] := '-'; {do not localize}
  end
  else
  begin
    Result[1] := '+';  {do not localize}
  end;
end;

// Currently this function is not used
(*
procedure BuildMIMETypeMap(dest: TStringList);
{$IFDEF LINUX}
begin
  // TODO: implement BuildMIMETypeMap in Linux
  raise EIdException.Create('BuildMIMETypeMap not implemented yet.');    {Do not Localize}
end;
{$ENDIF}
{$IFDEF MSWINDOWS}
var
  Reg: TRegistry;
  slSubKeys: TStringList;
  i: integer;
begin
  Reg := CreateTRegistry; try
    Reg.RootKey := HKEY_CLASSES_ROOT;
    Reg.OpenKeyreadOnly('\MIME\Database\Content Type'); {do not localize}
    slSubKeys := TStringList.Create;
    try
      Reg.GetKeyNames(slSubKeys);
      reg.Closekey;
      for i := 0 to slSubKeys.Count - 1 do
      begin
        Reg.OpenKeyreadOnly('\MIME\Database\Content Type\' + slSubKeys[i]);  {do not localize}
        dest.Append(LowerCase(reg.ReadString('Extension')) + '=' + slSubKeys[i]); {do not localize}
        Reg.CloseKey;
      end;
    finally
      slSubKeys.Free;
    end;
  finally
    reg.free;
  end;
end;
{$ENDIF}
*)

function GetMIMETypeFromFile(const AFile: TFileName): string;
var
  MIMEMap: TIdMIMETable;
begin
  MIMEMap := TIdMimeTable.Create(true);
  try
    result := MIMEMap.GetFileMIMEType(AFile);
  finally
    MIMEMap.Free;
  end;
end;

function GmtOffsetStrToDateTime(S: string): TDateTime;
begin
  Result := 0.0;
  S := Copy(Trim(s), 1, 5);
  if Length(S) > 0 then
  begin
    if s[1] in ['-', '+'] then   {do not localize}
    begin
      try
        Result := EncodeTime(StrToInt(Copy(s, 2, 2)), StrToInt(Copy(s, 4, 2)), 0, 0);
        if s[1] = '-' then  {do not localize}
        begin
          Result := -Result;
        end;
      except
        Result := 0.0;
      end;
    end;
  end;
end;

function GMTToLocalDateTime(S: string): TDateTime;
var  {-Always returns date/time relative to GMT!!  -Replaces StrInternetToDateTime}
  DateTimeOffset: TDateTime;
begin
  Result := RawStrInternetToDateTime(S);
  if Length(S) < 5 then begin
    DateTimeOffset := 0.0
  end else begin
    DateTimeOffset := GmtOffsetStrToDateTime(S);
  end;
  {-Apply GMT offset here}
  if DateTimeOffset < 0.0 then begin
    Result := Result + Abs(DateTimeOffset);
  end else begin
    Result := Result - DateTimeOffset;
  end;
  // Apply local offset
  Result := Result + OffSetFromUTC;
end;


procedure Sleep(ATime: cardinal);
begin
  {$IFDEF LINUX}
  if (not Assigned(GStack)) then begin
    GStack := TIdStack.CreateStack;
  end;
  // what if the user just calls sleep? without doing anything...
  GStack.WSSelect(nil, nil, nil, ATime);
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  Windows.Sleep(ATime);
  {$ENDIF}
end;

{ Takes a cardinal (DWORD)  value and returns the string representation of it's binary value}    {Do not Localize}
function IntToBin(Value: cardinal): string;
var
  i: Integer;
begin
  SetLength(result, 32);
  for i := 1 to 32 do
  begin
    if ((Value shl (i-1)) shr 31) = 0 then
      result[i] := '0'  {do not localize}
    else
      result[i] := '1'; {do not localize}
  end;
end;

function CurrentProcessId: TIdPID;
begin
  {$IFDEF LINUX}
  Result := getpid;
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  Result := GetCurrentProcessID;
  {$ENDIF}
end;

// Arg1=EAX, Arg2=DL
function ROL(AVal: LongWord; AShift: Byte): LongWord;
asm
  mov  cl, dl
  rol  eax, cl
end;

function ROR(AVal: LongWord; AShift: Byte): LongWord;
asm
  mov  cl, dl
  ror  eax, cl
end;

procedure DebugOutput(const AText: string);
begin
  {$IFDEF LINUX}
  __write(stderr, AText, Length(AText));
  __write(stderr, EOL, Length(EOL));
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  OutputDebugString(PChar(AText));
  {$ENDIF}
end;

function InMainThread: boolean;
begin
  Result := GetCurrentThreadID = MainThreadID;
end;

{ TIdMimeTable }

{$IFDEF LINUX}
procedure LoadMIME(const AFileName : String; AMIMEList : TStringList);
var
  KeyList: TStringList;
  i, p: Integer;
  s, LMimeType, LExtension: String;
begin
  If FileExists(AFileName) Then  {Do not localize}
  Begin
    // build list from /etc/mime.types style list file
    // I'm lazy so I'm using a stringlist to load the file, ideally
    // this should not be done, reading the file line by line is better
    // I think - at least in terms of storage
    KeyList := TStringList.Create;
    try
      KeyList.LoadFromFile(AFileName); {Do not localize}
      for i := 0 to KeyList.Count -1 do begin
        s := KeyList[i];
        p := IndyPos('#', s); {Do not localize}
        if (p>0) then
        begin
          setlength(s, p-1);
        end;
        if s <> '' then
        begin {Do not localize}
          s := Trim(s);
          LMimeType := Fetch(s);
          if LMimeType <> '' then
          begin {Do not localize}
             while (s<>'') do
             begin {Do not localize}
               LExtension := Fetch(s);
               if LExtension <> '' then
               try {Do not localize}
                 AMIMEList.Values['.'+LExtension]:= LMimeType; {Do not localize}
               except
                 on EListError do {ignore} ;
               end;
             end;
          end;
        end;
      end;
    except
      on EFOpenError do {ignore} ;
    end;
  End;
end;
{$ENDIF}

procedure FillMimeTable(AMIMEList : TStringList);
{$IFDEF MSWINDOWS}
var
  reg: TRegistry;
  KeyList: TStringList;
  i: Integer;
  s: String;
{$ENDIF}
begin
  { Protect if someone is allready filled (custom MomeConst) }
  if not Assigned(AMIMEList) then
  begin
    Exit;
  end;
  if AMIMEList.Count > 0 then
  begin
    Exit;
  end;

  AMIMEList.Duplicates := dupError;

  with AMIMEList do
  begin
    {NOTE:  All of these strings should never be translated
    because they are protocol specific and are important for some
    web-browsers}

    { Audio }
    Add('.aiff=audio/x-aiff');    {Do not Localize}
    Add('.au=audio/basic');    {Do not Localize}
    Add('.mid=midi/mid');    {Do not Localize}
    Add('.mp3=audio/x-mpg');    {Do not Localize}
    Add('.m3u=audio/x-mpegurl');    {Do not Localize}
    Add('.qcp=audio/vnd.qcelp');    {Do not Localize}
    Add('.ra=audio/x-realaudio');    {Do not Localize}
    Add('.wav=audio/x-wav');    {Do not Localize}
    Add('.gsm=audio/x-gsm');    {Do not Localize}
    Add('.wax=audio/x-ms-wax');    {Do not Localize}
    Add('.wma=audio/x-ms-wma');    {Do not Localize}
    Add('.ram=audio/x-pn-realaudio');    {Do not Localize}
    Add('.mjf=audio/x-vnd.AudioExplosion.MjuiceMediaFile');    {Do not Localize}


    { Image }
    Add('.bmp=image/bmp');    {Do not Localize}
    Add('.gif=image/gif');    {Do not Localize}
    Add('.jpg=image/jpeg');    {Do not Localize}
    Add('.jpeg=image/jpeg');    {Do not Localize}
    Add('.jpe=image/jpeg');    {Do not Localize}
    Add('.pict=image/x-pict');    {Do not Localize}
    Add('.png=image/x-png');    {Do not Localize}
    Add('.svg=image/svg-xml');    {Do not Localize}
    Add('.tif=image/x-tiff');    {Do not Localize}
    Add('.rf=image/vnd.rn-realflash');    {Do not Localize}
    Add('.rp=image/vnd.rn-realpix');    {Do not Localize}
    Add('.ico=image/x-icon');    {Do not Localize}
    Add('.art=image/x-jg');    {Do not Localize}
    Add('.pntg=image/x-macpaint');    {Do not Localize}
    Add('.qtif=image/x-quicktime');    {Do not Localize}
    Add('.sgi=image/x-sgi');    {Do not Localize}
    Add('.targa=image/x-targa');    {Do not Localize}
    Add('.xbm=image/xbm');    {Do not Localize}
    Add('.psd=image/x-psd');    {Do not Localize}
    Add('.pnm=image/x-portable-anymap');    {Do not Localize}
    Add('.pbm=image/x-portable-bitmap');    {Do not Localize}
    Add('.pgm=image/x-portable-graymap');    {Do not Localize}
    Add('.ppm=image/x-portable-pixmap');    {Do not Localize}
    Add('.rgb=image/x-rgb');    {Do not Localize}
    Add('.xbm=image/x-xbitmap');    {Do not Localize}
    Add('.xpm=image/x-xpixmap');    {Do not Localize}
    Add('.xwd=image/x-xwindowdump');    {Do not Localize}


    { Text }
    Add('.323=text/h323');    {Do not Localize}
    Add('.xml=text/xml');    {Do not Localize}
    Add('.uls=text/iuls');    {Do not Localize}
    Add('.txt=text/plain');    {Do not Localize}
    Add('.rtx=text/richtext');    {Do not Localize}
    Add('.wsc=text/scriptlet');    {Do not Localize}
    Add('.rt=text/vnd.rn-realtext');    {Do not Localize}
    Add('.htt=text/webviewhtml');    {Do not Localize}
    Add('.htc=text/x-component');    {Do not Localize}
    Add('.vcf=text/x-vcard');    {Do not Localize}


    { video/ }
    Add('.avi=video/x-msvideo');    {Do not Localize}
    Add('.flc=video/flc');    {Do not Localize}
    Add('.mpeg=video/x-mpeg2a');    {Do not Localize}
    Add('.mov=video/quicktime');    {Do not Localize}
    Add('.rv=video/vnd.rn-realvideo');    {Do not Localize}
    Add('.ivf=video/x-ivf');    {Do not Localize}
    Add('.wm=video/x-ms-wm');    {Do not Localize}
    Add('.wmp=video/x-ms-wmp');    {Do not Localize}
    Add('.wmv=video/x-ms-wmv');    {Do not Localize}
    Add('.wmx=video/x-ms-wmx');    {Do not Localize}
    Add('.wvx=video/x-ms-wvx');    {Do not Localize}
    Add('.rms=video/vnd.rn-realvideo-secure');    {Do not Localize}
    Add('.asx=video/x-ms-asf-plugin');    {Do not Localize}
    Add('.movie=video/x-sgi-movie');    {Do not Localize}

    { application/ }
    Add('.wmd=application/x-ms-wmd');    {Do not Localize}
    Add('.wms=application/x-ms-wms');    {Do not Localize}
    Add('.wmz=application/x-ms-wmz');    {Do not Localize}
    Add('.p12=application/x-pkcs12');    {Do not Localize}
    Add('.p7b=application/x-pkcs7-certificates');    {Do not Localize}
    Add('.p7r=application/x-pkcs7-certreqresp');    {Do not Localize}
    Add('.qtl=application/x-quicktimeplayer');    {Do not Localize}
    Add('.rtsp=application/x-rtsp');    {Do not Localize}
    Add('.swf=application/x-shockwave-flash');    {Do not Localize}
    Add('.sit=application/x-stuffit');    {Do not Localize}
    Add('.tar=application/x-tar');    {Do not Localize}
    Add('.man=application/x-troff-man');    {Do not Localize}
    Add('.urls=application/x-url-list');    {Do not Localize}
    Add('.zip=application/x-zip-compressed');    {Do not Localize}
    Add('.cdf=application/x-cdf');    {Do not Localize}
    Add('.fml=application/x-file-mirror-list');    {Do not Localize}
    Add('.fif=application/fractals');    {Do not Localize}
    Add('.spl=application/futuresplash');    {Do not Localize}
    Add('.hta=application/hta');    {Do not Localize}
    Add('.hqx=application/mac-binhex40');    {Do not Localize}
    Add('.doc=application/msword');    {Do not Localize}
    Add('.pdf=application/pdf');    {Do not Localize}
    Add('.p10=application/pkcs10');    {Do not Localize}
    Add('.p7m=application/pkcs7-mime');    {Do not Localize}
    Add('.p7s=application/pkcs7-signature');    {Do not Localize}
    Add('.cer=application/x-x509-ca-cert');    {Do not Localize}
    Add('.crl=application/pkix-crl');    {Do not Localize}
    Add('.ps=application/postscript');    {Do not Localize}
    Add('.sdp=application/x-sdp');    {Do not Localize}
    Add('.setpay=application/set-payment-initiation');    {Do not Localize}
    Add('.setreg=application/set-registration-initiation');    {Do not Localize}
    Add('.smil=application/smil');    {Do not Localize}
    Add('.ssm=application/streamingmedia');    {Do not Localize}
    Add('.xfdf=application/vnd.adobe.xfdf');    {Do not Localize}
    Add('.fdf=application/vnd.fdf');    {Do not Localize}
    Add('.xls=application/x-msexcel');    {Do not Localize}
    Add('.sst=application/vnd.ms-pki.certstore');    {Do not Localize}
    Add('.pko=application/vnd.ms-pki.pko');    {Do not Localize}
    Add('.cat=application/vnd.ms-pki.seccat');    {Do not Localize}
    Add('.stl=application/vnd.ms-pki.stl');    {Do not Localize}
    Add('.rmf=application/vnd.rmf');    {Do not Localize}
    Add('.rm=application/vnd.rn-realmedia');    {Do not Localize}
    Add('.rnx=application/vnd.rn-realplayer');    {Do not Localize}
    Add('.rjs=application/vnd.rn-realsystem-rjs');    {Do not Localize}
    Add('.rmx=application/vnd.rn-realsystem-rmx');    {Do not Localize}
    Add('.rmp=application/vnd.rn-rn_music_package');    {Do not Localize}
    Add('.rsml=application/vnd.rn-rsml');    {Do not Localize}
    Add('.vsl=application/x-cnet-vsl');    {Do not Localize}
    Add('.z=application/x-compress');    {Do not Localize}
    Add('.tgz=application/x-compressed');    {Do not Localize}
    Add('.dir=application/x-director');    {Do not Localize}
    Add('.gz=application/x-gzip');    {Do not Localize}
    Add('.uin=application/x-icq');    {Do not Localize}
    Add('.hpf=application/x-icq-hpf');    {Do not Localize}
    Add('.pnq=application/x-icq-pnq');    {Do not Localize}
    Add('.scm=application/x-icq-scm');    {Do not Localize}
    Add('.ins=application/x-internet-signup');    {Do not Localize}
    Add('.iii=application/x-iphone');    {Do not Localize}
    Add('.latex=application/x-latex');    {Do not Localize}
    Add('.nix=application/x-mix-transfer');    {Do not Localize}

    { WAP }
    Add('.wbmp=image/vnd.wap.wbmp');    {Do not Localize}
    Add('.wml=text/vnd.wap.wml');    {Do not Localize}
    Add('.wmlc=application/vnd.wap.wmlc');    {Do not Localize}
    Add('.wmls=text/vnd.wap.wmlscript');    {Do not Localize}
    Add('.wmlsc=application/vnd.wap.wmlscriptc');    {Do not Localize}

    { WEB }
    Add('.css=text/css');    {Do not Localize}
    Add('.htm=text/html');    {Do not Localize}
    Add('.html=text/html');    {Do not Localize}
    Add('.shtml=server-parsed-html');    {Do not Localize}
    Add('.xml=text/xml');    {Do not Localize}
    Add('.sgm=text/sgml');    {Do not Localize}
    Add('.sgml=text/sgml');    {Do not Localize}
  end;
  {$IFDEF MSWINDOWS}
  // Build the file type/MIME type map
  Reg := CreateTRegistry; try
    KeyList := TStringList.create;
    try
      Reg.RootKey := HKEY_CLASSES_ROOT;
      if Reg.OpenKeyReadOnly('\') then  {do not localize}
      begin
        Reg.GetKeyNames(KeyList);
      //  reg.Closekey;
      end;
      // get a list of registered extentions
      for i := 0 to KeyList.Count - 1 do
      begin
        if Copy(KeyList[i], 1, 1) = '.' then   {do not localize}
        begin
          if reg.OpenKeyReadOnly(KeyList[i]) then
          begin
            s := Reg.ReadString('Content Type');  {do not localize}
{          if Reg.ValueExists('Content Type') then  {do not localize}
{          begin
            FFileExt.Values[KeyList[i]] := Reg.ReadString('Content Type');  {do not localize}
{          end;   }

{ for some odd reason, the code above was triggering a memory leak inside
the TIdHTTPServer demo program even though simply testing the MIME Table
alone did not cause a memory leak.  That is what I found in my leak testing.
Got me <shrug>.

}
            if Length(s) > 0 then
            begin
              AMIMEList.Values[KeyList[i]] := s;
            end;
//            reg.CloseKey;
          end;
        end;
      end;
      if Reg.OpenKeyreadOnly('\MIME\Database\Content Type') then {do not localize}
      begin
        // get a list of registered MIME types
        KeyList.Clear;

        Reg.GetKeyNames(KeyList);
  //      reg.Closekey;
        for i := 0 to KeyList.Count - 1 do
        begin
          if Reg.OpenKeyreadOnly('\MIME\Database\Content Type\' + KeyList[i]) then {do not localize}
          begin
            s := reg.ReadString('Extension');  {do not localize}
            AMIMEList.Values[s] := KeyList[i];
    //        Reg.CloseKey;
          end;
        end;
      end;
    finally
      KeyList.Free;
    end;
  finally
    reg.free;
  end;
{$ENDIF}
{$IFDEF LINUX}
  {/etc/mime.types is not present in all Linux distributions.
  It turns out that "/etc/htdig/mime.types" and "/etc/usr/share/webmin/mime.types"
  are in the same format as what Johannes Berg had expected.
  Just read those files for best coverage.  MIME Tables are not centrolized
  on Linux.
  }
  LoadMIME('/etc/mime.types', AMIMEList);
  LoadMIME('/etc/htdig/mime.types', AMIMEList);
  LoadMIME('/etc/usr/share/webmin/mime.types', AMIMEList);
{$ENDIF}
end;

procedure TIdMimeTable.AddMimeType(const Ext, MIMEType: string);
var
  LExt,
  LMIMEType: string;
begin
  { Check and fix extension }
  LExt := AnsiLowerCase(Ext);
  if Length(LExt) = 0 then
  begin
    raise EIdException.Create(RSMIMEExtensionEmpty);
  end
  else
  begin
   if LExt[1] <> '.' then    {Do not Localize}
   begin
     LExt := '.' + LExt;    {Do not Localize}
   end;
  end;
  { Check and fix MIMEType }
  LMIMEType := AnsiLowerCase(MIMEType);
  if Length(LMIMEType) = 0 then
    raise EIdException.Create(RSMIMEMIMETypeEmpty);

  if FFileExt.IndexOf(LExt) = -1 then
  begin
    FFileExt.Add(LExt);
    FMIMEList.Add(LMIMEType);
  end
  else
    raise EIdException.Create(RSMIMEMIMEExtAlreadyExists);
end;

procedure TIdMimeTable.BuildCache;
begin
  if Assigned(FOnBuildCache) then
  begin
    FOnBuildCache(Self);
  end
  else
  begin
    if FFileExt.Count = 0 then
    begin
      BuildDefaultCache;
    end;
  end;
end;

procedure TIdMimeTable.BuildDefaultCache;
{This is just to provide some default values only}
var LKeys : TStringList;

begin
  LKeys := TStringList.Create;
  try
    FillMIMETable(LKeys);
    LoadFromStrings(LKeys);
  finally
    FreeAndNil(LKeys);
  end;
end;

constructor TIdMimeTable.Create(Autofill: boolean);
begin
  FFileExt := TStringList.Create;
  FFileExt.Sorted := False;
  FMIMEList := TStringList.Create;
  FMIMEList.Sorted := False;
  if Autofill then begin
    BuildCache;
  end;
end;

destructor TIdMimeTable.Destroy;
begin
  FreeAndNil(FMIMEList);
  FreeAndNil(FFileExt);
  inherited Destroy;
end;

function TIdMimeTable.getDefaultFileExt(const MIMEType: string): String;
var
  Index : Integer;
  LMimeType: string;
begin
  Result := '';    {Do not Localize}
  LMimeType := AnsiLowerCase(MIMEType);
  Index := FMIMEList.IndexOf(LMimeType);
  if Index <> -1 then
  begin
    Result := FFileExt[Index];
  end
  else
  begin
    BuildCache;
    Index := FMIMEList.IndexOf(LMIMEType);
    if Index <> -1 then
      Result := FFileExt[Index];
  end;
end;

function TIdMimeTable.GetFileMIMEType(const AFileName: string): string;
var
  Index : Integer;
  LExt: string;
begin
  LExt := AnsiLowerCase(ExtractFileExt(AFileName));
  Index := FFileExt.IndexOf(LExt);
  if Index <> -1 then
  begin
    Result := FMIMEList[Index];
  end
  else
  begin
    BuildCache;
    Index := FFileExt.IndexOf(LExt);
    if Index = -1 then
    begin
      Result := 'application/octet-stream' {do not localize}
    end
    else
    begin
      Result := FMIMEList[Index];
    end;
  end;  { if .. else }
end;

procedure TIdMimeTable.LoadFromStrings(AStrings: TStrings;const MimeSeparator: Char = '=');    {Do not Localize}
var
  I   : Integer;
  Ext : string;
begin
  FFileExt.Clear;
  FMIMEList.Clear;
  for I := 0 to AStrings.Count - 1 do
  begin
    Ext := AnsiLowerCase(Copy(AStrings[I], 1, Pos(MimeSeparator, AStrings[I]) - 1));
    if Length(Ext) > 0 then
      if FFileExt.IndexOf(Ext) = -1 then
        AddMimeType(Ext, Copy(AStrings[I], Pos(MimeSeparator, AStrings[I]) + 1, Length(AStrings[I])));
  end;  { For I := }
end;



procedure TIdMimeTable.SaveToStrings(AStrings: TStrings;
  const MimeSeparator: Char);
var
  I : Integer;
begin
  AStrings.Clear;
  for I := 0 to FFileExt.Count - 1 do
    AStrings.Add(FFileExt[I] + MimeSeparator + FMIMEList[I]);
end;

procedure SetThreadPriority(AThread: TThread; const APriority: TIdThreadPriority; const APolicy: Integer = -MaxInt);
begin
  {$IFDEF LINUX}
  // Linux only allows root to adjust thread priorities, so we just ingnore this call in Linux?
  // actually, why not allow it if root
  // and also allow setting *down* threadpriority (anyone can do that)
  // note that priority is called "niceness" and positive is lower priority
  if (getpriority(PRIO_PROCESS, 0) < APriority) or (geteuid = 0) then begin
    setpriority(PRIO_PROCESS, 0, APriority);
  end;
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  AThread.Priority := APriority;
  {$ENDIF}
end;

function SBPos(const Substr, S: string): Integer;
// Necessary because of "Compiler magic"
begin
  Result := Pos(Substr, S);
end;

function MemoryPos(const ASubStr: String; MemBuff: PChar; MemorySize: Integer): Integer;
var
  LSearchLength: Integer;
  LS1: Integer;
  LChar: Char;
  LPS,LPM: PChar;
begin
  LSearchLength := Length(ASubStr);
  if (LSearchLength = 0) or (LSearchLength > MemorySize) then begin
    Result := 0;
    Exit;
  end;

  LChar := PChar(Pointer(ASubStr))^; //first char
  LPS:=PChar(Pointer(ASubStr))+1;//tail string
  LPM:=MemBuff;
  LS1:=LSearchLength-1;
  LSearchLength := MemorySize-LS1;//MemorySize-LS+1
  if LS1=0 then begin //optimization for freq used LF
    while LSearchLength>0 do begin
      if LPM^= LChar then begin
        Result:=LPM-MemBuff+1;
        EXIT;
      end;
      inc(LPM);
      dec(LSearchLength);
    end;//while
  end else begin
    while LSearchLength>0 do begin
      if LPM^= LChar then begin
        inc(LPM);
        if CompareMem(LPM,LPS,LS1) then begin
          Result:=LPM-MemBuff;
          EXIT;
        end;
      end
      else begin
        inc(LPM);
      end;
      dec(LSearchLength);
    end;//while
  end;//if OneChar
  Result:=0;
End;

// Assembly is not allowed in Indy, however these routines can only be done in assembly because of
// the LOCK instruction. Both the Windows API and Kylix support these routines, but Windows 95
// fubars them up (Win98 works ok) so its necessary to have our own implementations.
function IndyInterlockedIncrement(var I: Integer): Integer;
asm
  MOV     EDX,1
  XCHG    EAX,EDX
  LOCK  XADD    [EDX],EAX
  INC     EAX
end;

function IndyInterlockedDecrement(var I: Integer): Integer;
asm
  MOV     EDX,-1
  XCHG    EAX,EDX
  LOCK  XADD    [EDX],EAX
  DEC     EAX
end;

function IndyInterlockedExchange(var A: Integer; B: Integer): Integer;
asm
  XCHG    [EAX],EDX
  MOV     EAX,EDX
end;

function IndyInterlockedExchangeAdd(var A: Integer; B: Integer): Integer;
asm
  XCHG    EAX,EDX
  LOCK  XADD    [EDX],EAX
end;

{$IFDEF LINUX}
function IndyGetHostName: string;
var
  LHost: array[1..255] of Char;
  i: LongWord;
begin
  //TODO: No need for LHost at all? Prob can use just Result
  if GetHostname(@LHost[1], 255) <> -1 then begin
    i := IndyPos(#0, LHost);
    SetLength(Result, i - 1);
    Move(LHost, Result[1], i - 1);
  end;
end;
{$ENDIF}
{$IFDEF MSWINDOWS}
function IndyGetHostName: string;
var
  i: LongWord;
begin
  SetLength(Result, MAX_COMPUTERNAME_LENGTH + 1);
  i := Length(Result);
  if GetComputerName(@Result[1], i) then begin
    SetLength(Result, i);
  end;
end;
{$ENDIF}


function IsValidIP(const S: String): Boolean;
var
  j, i: Integer;
  LTmp: String;
begin
  Result := True;
  LTmp := Trim(S);
  for i := 1 to 4 do begin
    j := StrToIntDef(Fetch(LTmp, '.'), -1);    {Do not Localize}
    Result := Result and (j > -1) and (j < 256);
    if NOT Result then begin
      Break;
    end;
  end;
end;

// everething that does not start with '.' is treathed as hostname    {Do not Localize}

function IsHostname(const S: String): Boolean;
begin
  Result := ((IndyPos('.', S) = 0) or (S[1] <> '.')) and NOT IsValidIP(S);    {Do not Localize}
end;

function IsTopDomain(const AStr: string): Boolean;
Var
  i: Integer;
  S1,LTmp: String;
begin
  i := 0;

  LTmp := AnsiUpperCase(Trim(AStr));
  while IndyPos('.', LTmp) > 0 do begin    {Do not Localize}
    S1 := LTmp;
    Fetch(LTmp, '.');    {Do not Localize}
    i := i + 1;
  end;

  Result := ((Length(LTmp) > 2) and (i = 1));
  if Length(LTmp) = 2 then begin  // Country domain names
    S1 := Fetch(S1, '.');    {Do not Localize}
    // here will be the exceptions check: com.uk, co.uk, com.tw and etc.
    if LTmp = 'UK' then begin    {Do not Localize}
      if S1 = 'CO' then result := i = 2;    {Do not Localize}
      if S1 = 'COM' then result := i = 2;    {Do not Localize}
    end;

    if LTmp = 'TW' then begin    {Do not Localize}
      if S1 = 'CO' then result := i = 2;    {Do not Localize}
      if S1 = 'COM' then result := i = 2;    {Do not Localize}
    end;
  end;
end;

function IsDomain(const S: String): Boolean;
begin
  Result := NOT IsHostname(S) and (IndyPos('.', S) > 0) and NOT IsTopDomain(S);    {Do not Localize}
end;

function DomainName(const AHost: String): String;
begin
  result := Copy(AHost, IndyPos('.', AHost), Length(AHost));    {Do not Localize}
end;

function IsFQDN(const S: String): Boolean;
begin
  Result := IsHostName(S) and IsDomain(DomainName(S));
end;

// The password for extracting password.bin from password.zip is indyrules

function ProcessPath(const ABasePath: string;
  const APath: string;
  const APathDelim: string = '/'): string;    {Do not Localize}
// Dont add / - sometimes a file is passed in as well and the only way to determine is
// to test against the actual targets
var
  i: Integer;
  LPreserveTrail: Boolean;
  LWork: string;
begin
  if IndyPos(APathDelim, APath) = 1 then begin
    Result := APath;
  end else begin
    Result := '';    {Do not Localize}
    LPreserveTrail := (Copy(APath, Length(APath), 1) = APathDelim) or (Length(APath) = 0);
    LWork := ABasePath;
    // If LWork = '' then we just want it to be APath, no prefixed /    {Do not Localize}
    if (Length(LWork) > 0) and (Copy(LWork, Length(LWork), 1) <> APathDelim) then begin
      LWork := LWork + APathDelim;
    end;
    LWork := LWork + APath;
    if Length(LWork) > 0 then begin
      i := 1;
      while i <= Length(LWork) do begin
        if LWork[i] = APathDelim then begin
          if i = 1 then begin
            Result := APathDelim;
          end else if Copy(Result, Length(Result), 1) <> APathDelim then begin
            Result := Result + LWork[i];
          end;
        end else if LWork[i] = '.' then begin    {Do not Localize}
          // If the last character was a PathDelim then the . is a relative path modifier.
          // If it doesnt follow a PathDelim, its part of a filename
          if (Copy(Result, Length(Result), 1) = APathDelim) and (Copy(LWork, i, 2) = '..') then begin    {Do not Localize}
            // Delete the last PathDelim
            Delete(Result, Length(Result), 1);
            // Delete up to the next PathDelim
            while (Length(Result) > 0) and (Copy(Result, Length(Result), 1) <> APathDelim) do begin
              Delete(Result, Length(Result), 1);
            end;
            // Skip over second .
            Inc(i);
          end else begin
            Result := Result + LWork[i];
          end;
        end else begin
          Result := Result + LWork[i];
        end;
        Inc(i);
      end;
    end;
    // Sometimes .. semantics can put a PathDelim on the end
    // But dont modify if it is only a PathDelim and nothing else, or it was there to begin with
    if (Result <> APathDelim) and (Copy(Result, Length(Result), 1) = APathDelim)
     and (LPreserveTrail = False) then begin
      Delete(Result, Length(Result), 1);
    end;
  end;
end;

{ TIdLocalEvent }

constructor TIdLocalEvent.Create(const AInitialState: Boolean = False;
 const AManualReset: Boolean = False);
begin
  inherited Create(nil, AManualReset, AInitialState, '');    {Do not Localize}
end;

function TIdLocalEvent.WaitFor: TWaitResult;
begin
  Result := WaitFor(Infinite);
end;

function iif(ATest: Boolean; const ATrue: Integer; const AFalse: Integer): Integer;
begin
  if ATest then begin
    Result := ATrue;
  end else begin
    Result := AFalse;
  end;
end;

function iif(ATest: Boolean; const ATrue: string; const AFalse: string): string;
begin
  if ATest then begin
    Result := ATrue;
  end else begin
    Result := AFalse;
  end;
end;

function iif(ATest: Boolean; const ATrue: Boolean; const AFalse: Boolean): Boolean;
begin
  if ATest then begin
    Result := ATrue;
  end else begin
    Result := AFalse;
  end;
end;

{ TIdReadMemoryStream }

procedure TIdReadMemoryStream.SetPointer(Ptr: Pointer; Size: Integer);
Begin
  inherited SetPointer(Ptr, Size);
  Seek(0,0);//Position:=0;
End;//SetPointer

function TIdReadMemoryStream.Write(const Buffer; Count: Integer): Longint;
begin
  Result := 0; //bytes actually written-NONE
End;//Write

// Universal "AnsiPosIdx" function. AnsiPosIdx&AnsiMemoryPos are just simple interfaces for it
function  AnsiPosIdx_ (const ASubStr: AnsiString; AStr: PChar; L1: Cardinal; AStartPos: Cardinal=0): Cardinal;
var
  L2: Cardinal;
  ByteType : TMbcsByteType;
  Str, SubStr, CurResult: PChar;
Begin
  Result:= 0; //not found
  //*L1 := Length(AStr);
  L2 := Length(ASubStr);
  if (L2=0) or (L2>L1) then Exit;
  Str:=Pointer(AStr);
  SubStr:=Pointer(ASubStr);
  //posIDX
  if AStartPos>0 then begin
    Str := Str + AStartPos - 1;
    L1  := L1 + 1 - AStartPos;
  end;//if
  if L1<=0 then EXIT;

  CurResult := StrPos(Str, SubStr);
  while (CurResult <> nil) and ((L1 - Cardinal(CurResult - Str)) >= L2) do begin //found and LenStr-Pos>=LenSubStr
    ByteType := StrByteType(Str, Integer(CurResult-Str));
{$IFDEF MSWINDOWS}
    if (ByteType <> mbTrailByte) and
      (Windows.CompareString(LOCALE_USER_DEFAULT, 0, CurResult, L2, SubStr, L2) = 2) then begin
      Result:=CurResult-Pointer(AStr)+1;
      Exit;
    end;//if
    if (ByteType = mbLeadByte) then Inc(Result);
{$ENDIF}
{$IFDEF LINUX}
    if (ByteType <> mbTrailByte) and
      (strncmp(CurResult, SubStr, L2) = 0) then begin
      Result:=CurResult-Pointer(AStr)+1;
      Exit;
    end;//if
{$ENDIF}
    Inc(Result);
    CurResult := StrPos(CurResult, SubStr);
  end;
End;//AnsiPosIdx

function  AnsiPosIdx(const ASubStr,AStr: AnsiString; AStartPos: Cardinal=0): Cardinal;
Begin
  Result:=AnsiPosIdx_(ASubStr, Pointer(AStr), Length(AStr), AStartPos);
End;//

function  AnsiMemoryPos(const ASubStr: String; MemBuff: PChar; MemorySize: Integer): Integer;
Begin
  Result:=AnsiPosIdx_(ASubStr, MemBuff, MemorySize, 0);
End;//


Function  PosIdx (const ASubStr,AStr: AnsiString; AStartPos: Cardinal): Cardinal;
var
  lpSubStr,lpS: PChar;
  LenSubStr,LenS: Integer;
  LChar: Char;
Begin
  LenSubStr:=Length(ASubStr);
  LenS:=Length(AStr);

  if (LenSubStr=0) or (LenSubStr>LenS) then begin
    Result:=0;//not found
    EXIT;
  end;//if

  lpSubStr:=Pointer(ASubStr);
  lpS:=Pointer(AStr);
  if AStartPos>0 then begin
    lpS:=lpS+AStartPos-1;
    LenS:=LenS+1-Integer(AStartPos);
  end;//if

  LChar :=lpSubStr[0];//first char
  lpSubStr:=lpSubStr  +1;//next char
  LenSubStr:=LenSubStr-1;//len w/o first char

  LenS:=LenS-LenSubStr; //Length(S)-Length(SubStr) +1(!) MUST BE >0
  if LenS<=0 then begin
    Result:=0;
    EXIT;
  end;//if

  while LenS>0 do begin
    if lpS^= LChar then begin
      inc(lpS);
      if CompareMem(lpS,lpSubStr,LenSubStr) then begin
        Result:=lpS-Pointer(AStr);//+1 already here
        EXIT;
      end;
    end
    else begin
      inc(lpS);
    end;
    dec(LenS);
  end;//while
  Result:=0;
End;//PosIdx

function MakeMethod (DataSelf, Code: Pointer): TMethod;
Begin
  Result.Data := DataSelf;
  Result.Code := Code;
End;//

initialization
  {$IFDEF LINUX}
  GStackClass := TIdStackLinux;
  {$ENDIF}
  {$IFDEF MSWINDOWS}
  ATempPath := TempPath;
  GStackClass := TIdStackWindows;
  {$ENDIF}
  // AnsiPos does not handle strings with #0 and is also very slow compared to Pos
  if LeadBytes = [] then begin
    IndyPos := SBPos;
  end else begin
    IndyPos := AnsiPos;
  end;

  SetLength(IndyFalseBoolStrs, 1);
  IndyFalseBoolStrs[Low(IndyFalseBoolStrs)] := 'FALSE';    {Do not Localize}
  SetLength(IndyTrueBoolStrs, 1);
  IndyTrueBoolStrs[Low(IndyTrueBoolStrs)] := 'TRUE';    {Do not Localize}

finalization
  FreeAndNil(FIdPorts);
end.
