{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10135: IdDNSResolver.pas 
{
{   Rev 1.5    4/30/2003 03:06:56 AM  JPMugaas
}
{
{   Rev 1.2    1/3/2003 1:53:52 PM  VVassiliev
}
{
{   Rev 1.1    01/02/2003 9:42:18 AM  VVassiliev
{ Bug fix 659874, 660267
}
{
{   Rev 1.0    2002.11.12 10:36:22 PM  czhower
}
{
  IdDNSResolver.

  Started: sometime.
  Finished:

  The Resolver does NOT support COMPLETE XFER's since these should be based   
  on the TCP protocol. Use the appropriate component for that (if one exists!).

  The resolver also does not support Chaos RR. Only IN RR are supported as of this time.
  Part of code from Ray Malone
}

// SG 28/1/02: Changed the DNSStrToDomain function according to original Author of the old comp: Ray Malone
{SG 10/07/01 Added support for qrStar query}
{VV 12/09/01 Added construction of reverse query (PTR)}
{DS 12/31/01 Corrected ReponsiblePerson spelling }
{VV 01/02/03 TQueryResult.DNSStrToDomain fix}

{ TODO : Add structure of IDHEADER IN FIGURE }

unit IdDNSResolver;

interface

uses
  Classes,
  IdGlobal,
  IdUDPClient;

type
  { TODO : Solve problem with obsolete records }
  TQueryRecordTypes = (qtA, qtNS, qtMD, qtMF, qtName, qtSOA, qtMB,
    qtMG, qtMR, qtNull, qtWKS, qtPTR, qtHINFO, qtMINFO, qtMX, qtTXT, qtSTAR);
const
  // Lookup table for query record values.
  QueryRecordValues: array [0..16] of word= (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,255);
  QueryRecordTypes: Array [0..16] of TQueryRecordTypes = (qtA, qtNS, qtMD, qtMF, qtName, qtSOA, qtMB,
    qtMG, qtMR, qtNull, qtWKS, qtPTR, qtHINFO, qtMINFO, qtMX, qtTXT, qtSTAR);
type
  TQueryType = set of TQueryRecordTypes;

  TResultRecord = class(TCollectionItem) // Rename to REsourceRecord
  private
    FRecType: TQueryRecordTypes;
    FRecClass: word;
    FName: string;
    FTTL: cardinal;
    FRData: String;
    FRDataLength: Integer;
  public
    // Parse the data (descendants only)
    procedure Parse(CompleteMessage: String; APos: Integer); virtual;
    { TODO : This needs to change }
    property RecType: TQueryRecordTypes read FRecType;
    property RecClass: word read FRecClass;
    property Name: string read FName;
    property TTL: cardinal read FTTL;
    Property RDataLength: Integer read FRDataLength;
    property RData: String read FRData;
    destructor Destroy; override;
  end;

  TRDATARecord = class(TResultRecord)
  private
    FIPAddress: String;
  public
    procedure Parse(CompleteMessage: String; APos: Integer); override;
    constructor Create(Collection: TCollection); override;
    procedure Assign(Source: TPersistent); override;
    property IPAddress: string read FIPAddress;
  end;

  TARecord = class(TRDATARecord)
  end;

  TWKSRecord = Class(TResultRecord)
  private
    FByteCount: integer;
    FAddress: String;
    FProtocol: Word;
    FData: PByte;
    function GetABit(index: integer): Byte;
  public
    constructor Create(Collection: TCollection); override;
    destructor Destroy; override;
    procedure Parse(CompleteMessage: String; APos: Integer); override;
    property Address: String read FAddress;
    property Protocol: Word read FProtocol;
    property BitMap[index: integer]: Byte read GetABit;
    property ByteCount: integer read FByteCount;
  end;

  TMXRecord = class(TResultRecord)
  private
    FExchangeServer: string;
    FPreference: Word;
  public
    procedure Parse(CompleteMessage: String; APos: Integer); override;
    constructor Create(Collection: TCollection); override;
    procedure Assign(Source: TPersistent); override;

    property ExchangeServer: string read FExchangeServer;
    property Preference: word read FPreference;
  end;

  TTextRecord = class(TResultRecord)
  private
    FText: TStrings;
  public
    constructor Create(Collection: TCollection); override;
    destructor Destroy; override;
    procedure Parse(CompleteMessage: String; APos: Integer); override;
    Property Text: TStrings read FText;
  end;

  THINFORecord = Class(TTextRecord)
  private
    FCPU: String;
    FOS: String;
  public
    procedure Parse(CompleteMessage: String; APos: Integer); override;
    property CPU: String read FCPU;
    property OS: String read FOS;
  end;

  TMINFORecord = Class(TResultRecord)
  private
    FResponsiblePerson: String;
    FErrorMailbox: String;
  public
    procedure Parse(CompleteMessage: String; APos: Integer); override;
    property ResponsiblePersonMailbox: String read FResponsiblePerson;
    property ErrorMailbox: String read FErrorMailbox;
  end;

  TSOARecord = class(TResultRecord)
  private
    FSerial: cardinal;
    FMinimumTTL: Cardinal;
    FRefresh: Cardinal;
    FRetry: Cardinal;
    FMNAME: string;
    FRNAME: string;
    FExpire: Cardinal;
  public
    procedure Parse(CompleteMessage: String; APos: Integer); override;

    property Primary: string read FMNAME;
    property ResponsiblePerson: string read FRNAME;
    property Serial: cardinal read FSerial;
    property Refresh: Cardinal read FRefresh;
    property Retry: Cardinal read FRetry;
    property Expire: Cardinal read FExpire;

    property MinimumTTL: Cardinal read FMinimumTTL;
  end;

  TNAMERecord = class(TResultRecord)
  private
    FHostName: string;
  public
    procedure Parse(CompleteMessage: String; APos: Integer); override;
    constructor Create(Collection: TCollection); override;
    procedure Assign(Source: TPersistent); override;
    property HostName: string read FHostName;
  end;

  TNSRecord = class(TNAMERecord)
  end;

  TCNRecord = class(TNAMERecord)
  end;


  TQueryResult = class(TCollection)
  private
    FRec: TResultRecord;
    FDomainName: String;
    FQueryClass: Word;
    FQueryType: Word;
    FQueryPointerList: TStringList;
    function DNSStrToDomain(SrcStr: string; var Idx: Integer): string;
    function NextDNSLabel(DNSStr: string; Var APos: Integer): string;
    procedure SetItem(Index: Integer; Value: TResultRecord);
    function GetItem(Index: Integer): TResultRecord;
  protected
    function GetOwner: TPersistent; override;
  public
    constructor Create(AResultRecord: TResultRecord);
    destructor destroy; override;
    function Add(Answer: string; var APos: Integer): TResultRecord;
    procedure Clear; reintroduce;

    Property QueryClass: Word read FQueryClass;
    Property QueryType: Word read FQueryType;
    Property DomainName: String read FDomainName;

    property Items[Index: Integer]: TResultRecord read GetItem write SetItem; default;
  end;



  TPTRRecord = Class(TNAMERecord)
  end;

  // This class is used INTERNALLY. It does not need to be accessed by the user
  TDNSHeader = class
  private
    FID: Word;
    FBitCode: Word;
    FQDCount: Word;
    FANCount: Word;
    FNSCount: Word;
    FARCount: Word;
    function GetAA: Word;
    function GetOpCode: Word;
    function GetQr: Word;
    function GetRA: Word;
    function GetRCode: Word;
    function GetRD: Word;
    function GetTC: Word;
    procedure SetAA(const Value: Word);
    procedure SetOpCode(const Value: Word);
    procedure SetQr(const Value: Word);
    procedure SetRA(const Value: Word);
    procedure SetRCode(const Value: Word);
    procedure SetRD(const Value: Word);
    procedure SetTC(const Value: Word);

  public
    constructor Create;
    procedure ClearByteCode;

    property ID: Word read FID write FID;

    property Qr: Word read GetQr write SetQr;
    property OpCode: Word read GetOpCode write SetOpCode;
    property AA: Word read GetAA write SetAA;
    property TC: Word read GetTC write SetTC;
    property RD: Word read GetRD write SetRD;
    property RA: Word read GetRA write SetRA;
    property RCode: Word read GetRCode write SetRCode;
    property BitCode: Word read FBitCode;
    property QDCount: Word read FQDCount write FQDCount;
    property ANCount: Word read FANCount write FANCount;
    property NSCount: Word read FNSCount write FNSCount;
    property ARCount: Word read FARCount write FARCount;
  end;

  TIdDNSResolver = class(TIdUDPClient)
  private
    FDNSHeader: TDNSHeader;
    FQueryResult: TQueryResult;
    FInternalQuery: string;
    FQuestionLength: Integer;
    FAllowRecursiveQueries: Boolean;
    procedure SetAllowRecursiveQueries(const Value: Boolean);
  protected
    FQueryRecords: TQueryType; // Compression dictionary


    procedure ParseAnswers(Answer: String; AnswerNum: Cardinal);
    procedure CreateQuery(ADomain: string);
    procedure FillResult(AResult: string);
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Resolve(ADomain: string);
    property QueryResult: TQueryResult read FQueryResult;
  published
    property QueryRecords: TQueryType read FQueryRecords write FQueryRecords;
    property AllowRecursiveQueries: Boolean read FAllowRecursiveQueries write SetAllowRecursiveQueries default true;
  end;

implementation

uses
  IdAssignedNumbers,
  IdBaseComponent,
  IdResourceStrings,
  IdException,
  SysUtils;

const
  cRCodeNoError   = 0;
  cRCodeFormatErr = 1;
  cRCodeServerErr = 2;
  cRCodeNameErr   = 3;
  cRCodeNotImplemented = 4;
  cRCodeRefused  = 5;

  cRCodeStrs : Array[cRCodeNoError..cRCodeRefused] Of String =
    (RSCodeNoError,
    RSCodeQueryFormat,
    RSCodeQueryServer,
    RSCodeQueryName,
    RSCodeQueryNotImplemented,
    RSCodeQueryQueryRefused);

{ TODO : Move to IdGlobal }
function WordToTwoCharStr(AWord : Word): String;
begin
  Result := Chr ( Hi ( AWord ) ) + Chr ( Lo ( AWord ) );
end;

function FourCharToCardinal(AChar1,AChar2,AChar3,AChar4 : Char): Cardinal;
var
  LCardinal: TIdCardinalBytes;
begin
  LCardinal.Byte1 := Ord(AChar4);
  LCardinal.Byte2 := Ord(AChar3);
  LCardinal.Byte3 := Ord(AChar2);
  LCardinal.Byte4 := Ord(AChar1);
  Result := LCardinal.Whole;
end;

{ TODO : Move to IdGlobal }
function TwoCharToWord(AChar1,AChar2: Char):Word;
//Since Replys are returned as Strings, we need a rountime to convert two
// characters which are a 2 byte U Int into a two byte unsigned integer
begin
  Result := Word((Ord(AChar1) shl 8) and $FF00) or Word(Ord(AChar2) and $00FF);
end;

{ TODO : Move these to member }
function GetErrorStr(Code, Id :Integer): String;
begin
  case code Of
    1 : Result := Format ( RSQueryInvalidQueryCount, [ Id ] );
    2 : Result := Format ( RSQueryInvalidPacketSize, [ Id ] );
    3 : Result := Format ( RSQueryLessThanFour, [ Id ] );
    4 : Result := Format ( RSQueryInvalidHeaderID, [ Id ] );
    5 : Result := Format ( RSQueryLessThanTwelve, [ Id ] );
    6 : Result := Format ( RSQueryPackReceivedTooSmall, [Id] );
  end;  //case code Of
end;

// SG 28/1/02: Changed that function according to original Author of the old comp: Ray Malone
function TQueryResult.DNSStrToDomain(SrcStr: string; var Idx: Integer): string;
var
  LabelStr : String;
  Len : Integer;
  SavedIdx : Integer;
  AChar :Char;
  fRPackSize: Integer;
begin
    Result := '';                {Do not Localize}
    fRPackSize := Length(SrcStr);
    SavedIdx := 0;
    repeat
      Len := byte(SrcStr[Idx]);
      while (Len and $C0) = $C0 do // {!!0.01} added loop for pointer
      begin                         // that points to a pointer. Removed  >63 hack. Am I really that stupid?
        if SavedIdx = 0 then SavedIdx := Succ(Idx); // it is important to return to original index  spot
	// when we go down more than 1 level.
        aChar := char(Len and $3F);                       // strip first two bits ($C) from first byte of offset pos
        Idx := TwoCharToWord(aChar, SrcStr[Idx + 1]) + 1; // add one to index for delphi string index
        Len := byte(SrcStr[Idx]);  // if len is another $Cx we will (while) loop again
      end;
      Assert(Idx < fRPackSize, GetErrorStr(2, 2)); // loop screwed up. This  very very unlikely now  could be removed.
      SetLength(LabelStr, Len);
      if Len > 0 then
      begin
        Move(SrcStr[Idx + 1], LabelStr[1], Length(LabelStr));
        Inc(Idx, Length(LabelStr) + 1);
      end;
      if Pred(Idx) > fRPackSize then // len byte was corrupted puting us past end of packet
        raise  EIdDnsResolverError.Create(GetErrorStr(2, 3));
      Result := Result + LabelStr + '.';  // concat and add period.  {Do not Localize}
    until (SrcStr[Idx] = char(0)) or (Idx >= Length(SrcStr)); // name field ends with nul byte
    if Result[Length(Result)] = '.' then  // remove final period    {Do not Localize}
    begin
      System.Delete(Result, Length(Result), 1);
    end;
    if SavedIdx > 0 then Idx := SavedIdx; // restore original Idx +1
    Inc(Idx); // set to first char of next item in  the resource
end;

function TQueryResult.NextDNSLabel(DNSStr: string; Var APos: Integer): string;
var
  LabelLength: Byte;
  function IsPointer(TestVal: Integer): boolean;
  begin
    result := (TestVal AND $C0) <> 0;
  end;
begin
  result := '';      {Do not Localize}
  if Length(DNSStr) > APos then
  begin
    LabelLength := Integer(DNSStr[APos]);
    if IsPointer(LabelLength) then
    begin
      // do not dereference pointers
      result := '';    {Do not Localize}
      Inc(APos, 2);
    end
    else
    begin
      if (LabelLength > 0) then
      begin
        result := Copy(DNSStr, APos + 1, LabelLength);
        inc(APos, LabelLength + 1);
      end
      else
      begin
        result := '';      {Do not Localize}
        Inc(APos);
      end;
    end;
  end;
end;




{ TODO : Move these to member }
function GetRCodeStr(RCode : Integer): String;
begin
  if Rcode in [cRCodeNoError..cRCodeRefused] then
  begin
    Result :=  cRCodeStrs[Rcode];
  end  // if Rcode in [cRCodeNoError..cRCodeRefused] then
  else
  begin
    Result := RSCodeQueryUnknownError;
  end; //else.. if Rcode in [cRCodeNoError..cRCodeRefused] then
end;

{ TIdDNSResolver }

constructor TIdDNSResolver.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Port := IdPORT_DOMAIN;
  FQueryResult := TQueryResult.Create(nil);
  FDNSHeader := TDNSHeader.Create;
  FAllowRecursiveQueries := true;
end;

procedure TIdDNSResolver.CreateQuery(ADomain: string);


  function DoDomainName(ADNS : String): string;
  var
    BufStr : String;
    aPos : Integer;
  begin                         { DoDomainName }
    Result := '';
    while Length(aDns) > 0 do
    begin
      aPos := Pos('.', aDns);    {Do not Localize}
      if aPos = 0 then
      begin
        aPos := Length(aDns) + 1;
      end; //if aPos = 0 then
      BufStr := Copy(aDns, 1, aPos - 1);
      Delete(aDns, 1, aPos);
      Result := Result + Chr(Length(BufStr)) + BufStr;
    end;
  end;

  function DoHostAddress(aDNS :String): string;
  var
    BufStr,
    BufStr2 : String;
    aPos : Integer;
  begin                         { DoHostAddress }
    while Length( aDns ) > 0 do
    begin
      aPos := IndyPos( '.', aDns );   {Do not Localize}
      if aPos =0 then
      begin
        aPos := Length(aDns) + 1;
      end;  //if aPos =0 then
      BufStr := Copy(aDns, 1, aPos-1 );
      Delete ( aDns, 1, aPos);
      BufStr2 := Chr ( Length ( BufStr ) ) + BufStr + BufStr2;
    end;  // while Length( aDns ) > 0 do
    Result := BufStr2 + Chr ( 07 ) + 'in-addr' + Chr ( 04 ) + 'arpa'; {do not localize}
  end;                          { DoHostAddress }


var
  ARecType: TQueryRecordTypes;
  iQ: Integer;
  AQuestion: string;
begin
  AQuestion := '';
  FDNSHeader.ClearByteCode;
  FDNSHeader.Qr := 0;
  FDNSHeader.OpCode := 0;
  FDNSHeader.RD := Word(FAllowRecursiveQueries);
  iQ := 0;
  // Iterate thru questions
  FInternalQuery := WordToTwoCharStr(FDNSHeader.ID);
  FInternalQuery := FInternalQuery + WordToTwoCharStr(FDNSHeader.BitCode);
  { TODO : Optimize for non-double loop }
  for ARecType := Low(TQueryRecordTypes) to High(TQueryRecordTypes) do begin
    if ARecType in QueryRecords then begin
      inc(iQ);
    end;
  end;
  FDNSHeader.QDCount := iQ;
  if FDNSHeader.QDCount = 0 then begin
    FInternalQuery := '';    {Do not Localize}
    Exit;
  end;
  FInternalQuery := FInternalQuery + WordToTwoCharStr(FDNSHeader.FQDCount);
  FInternalQuery := FInternalQuery + Chr(0) + Chr(0) + Chr(0) + Chr(0) + Chr(0) + Chr(0);
  for ARecType := Low(TQueryRecordTypes) to High(TQueryRecordTypes) do begin
    if ARecType in QueryRecords then begin
      // Create the question
      if (ARecType = qtPTR) and (IndyPos('in-addr', ADomain) = 0) then begin {do not localize}
        AQuestion := AQuestion + DoHostAddress(ADomain) + Chr(0);
      end else begin
        AQuestion := AQuestion + DoDomainName(ADomain) + Chr(0);
      end;
      AQuestion := AQuestion + WordToTwoCharStr(QueryRecordValues[Ord(ARecType)]);
      AQuestion := AQuestion + WordToTwoCharStr(1);
    end;
  end;
  FInternalQuery := FInternalQuery + AQuestion;
  FQuestionLength := Length(AQuestion);
end;

destructor TIdDNSResolver.Destroy;
begin
  FQueryResult.Free;
  FDNSHeader.Free;
  inherited Destroy;
end;


procedure TIdDNSResolver.ParseAnswers(Answer: String; AnswerNum: Cardinal);
var
  i: integer;
  APos: Integer;
begin
  QueryResult.Clear;
  APos := 13; // Header is 12 byte long we need next byte
  // first, get the question
  // extract the domain name
  QueryResult.FDomainName :=  QueryResult.DNSStrToDomain(Answer, APos);
  // get the query type
  QueryResult.FQueryType := TwoCharToWord(Answer[APos], Answer[APos + 1]);
  Inc(APos, 2);
  // get the Query Class
  QueryResult.FQueryClass := TwoCharToWord(Answer[APos], Answer[APos + 1]);
  Inc(APos, 2);
  for i := 1 to AnswerNum  do
  begin
    QueryResult.Add(Answer, APos);
  end;
end;

procedure TIdDNSResolver.FillResult(AResult: string);
var
  ReplyId: Word;
  NAnswers: Word;
begin
  { TODO : Check bytes received }
  // Check to see if the reply is the one waited for
  ReplyId := TwoCharToWord(AResult[1],AResult[2]);
  if ReplyId <> FDNSHeader.FId then begin
    raise EIdDnsResolverError.Create(GetErrorStr(4, fDNSHeader.Fid));
  end;
  FDNSHeader.FBitCode := TwoCharToWord(AResult[3], AResult[4]);
  if FDNSHeader.RCode <> 0 then begin
    raise EIdDnsResolverError.Create(GetRCodeStr(FDNSHeader.RCode));
  end;
  if Length(AResult) < 12 then begin
    Raise EIdDnsResolverError.Create(GetErrorStr(5, 29));
  end;
  if Length(AResult) < Length(FInternalQuery) then begin
    raise EIdDnsResolverError.Create(GetErrorStr(5, 30));
  end;
  FDNSHeader.FQDCount := TwoCharToWord(AResult[5], AResult[6]);
  FDNSHeader.FANCount:= TwoCharToWord(AResult[7], AResult[8]);
  FDNSHeader.FNSCount := TwoCharToWord(AResult[9], AResult[10]);
  FDNSHeader.FARCount := TwoCharToWord(AResult[11], AResult[12]);
  NAnswers := FDNSHeader.FANCount + FDNSHeader.FNSCount + FDNSHeader.FARCount;
  if NAnswers > 0 then begin
    // Move Pointer to Start of answers
    if Length(AResult) > 12 then
      ParseAnswers(AResult, NAnswers);
  end;
end;

procedure TIdDNSResolver.Resolve(ADomain: string);
var
  AResult: string;
begin
  // Resolve queries the DNS for the records contained in the
  CreateQuery(ADomain);
  if Length(FInternalQuery) = 0 then
    raise EIdDnsResolverError.CreateFmt(RSQueryInvalidQueryCount, [0]);
  Send(FInternalQuery);
  AResult := ReceiveString;
  if Length(AResult) > 4 then
    FillResult(AResult)
  else
    raise EIdDnsResolverError.Create(RSDNSTimeout);
end;

{ TARecord }

procedure TRDATARecord.Assign(Source: TPersistent);
begin
  if Source is TARecord then begin
    FIPAddress := TARecord(Source).IPAddress;
  end else begin
    inherited Assign(Source);
  end;
end;

constructor TRDATARecord.Create(Collection: TCollection);
begin
//  FRecType := rtA;
  inherited Create(Collection);
end;

{ TMXRecord }

procedure TMXRecord.Assign(Source: TPersistent);
begin
  if Source is TMXRecord then begin
    FExchangeServer := TMXRecord(Source).ExchangeServer;
    FPreference := TMXRecord(Source).Preference;
  end else begin
    inherited Assign(Source);
  end;
end;

constructor TMXRecord.Create(Collection: TCollection);
begin
//  FRecType := rtMX;
  inherited Create(Collection);
end;

{ TCNAMERecord }

procedure TNAMERecord.Assign(Source: TPersistent);
begin
  if Source is TNAMERecord then begin
    FHostName := TNAMERecord(Source).HostName;
  end else begin
    inherited Assign(Source);
  end;
end;

constructor TNAMERecord.Create(Collection: TCollection);
begin
//  FRecType := rtCNAME;
  inherited Create(Collection);
end;

{ TQueryResult }

function TQueryResult.Add(Answer: string; var APos: Integer): TResultRecord;
var
  RRName: String;
  RR_type, RR_Class: word;
  RR_TTL: Cardinal;
  RD_Length: word;
  RData: String;
begin
  // extract the RR data
  RRName := DNSStrToDomain(Answer, APos);
  RR_Type := TwoCharToWord(Answer[APos], Answer[APos + 1]);
  RR_Class := TwoCharToWord(Answer[APos + 2], Answer[APos + 3]);
  RR_TTL := FourCharToCardinal(Answer[APos + 4], Answer[APos + 5], Answer[APos + 6], Answer[APos + 7]);
  RD_Length := TwoCharToWord(Answer[APos + 8], Answer[APos + 9]);
  RData := Copy(Answer, APos + 10, RD_Length);
  // remove what we have read from the buffer
  // Read the record type
  case TQueryRecordTypes(RR_Type - 1) of
    qtA:
    begin
      result := TARecord.Create(Self);
    end;
    qtNS:
    begin
      result := TNSRecord.Create(Self);
    end;
    qtMX:
    begin
      result := TMXRecord.Create(Self);
    end;
    qtName:
    begin
      result := TNAMERecord.Create(Self);
    end;
    qtSOA:
    begin
      result := TSOARecord.Create(Self);
    end;
    qtHINFO:
    begin
      result := THINFORecord.Create(Self);
    end;
    qtTXT:
    begin
      result := TTextRecord.Create(Self);
    end;
    qtWKS:
    begin
      result := TWKSRecord.Create(Self);
    end;
    qtPTR:
    begin
      result := TPTRRecord.Create(Self);
    end;
    qtMINFO:
    begin
      result := TMINFORecord.Create(Self);
    end;
    else
      // Unsoppurted query type, return generic record
      result := TResultRecord.Create(self);
  end; // case
  // Set the "general purprose" options
  if assigned(result) then
  begin
    if RR_Type <= High(QueryRecordTypes) then
      result.FRecType := QueryRecordTypes[Ord(RR_Type) - 1];
    result.FRecClass := RR_Class;
    result.FName := RRName;
    result.FTTL := RR_TTL;
    Result.FRData := Copy(RData, 1, RD_Length);
    Result.FRDataLength := RD_Length;
    // Parse the result
    // Since the DNS message can be compressed, we need to have the whole message to parse it, in case
    // we encounter a pointer
    Result.Parse(Copy(Answer, 1, APos + 9 + RD_Length), APos + 10);
  end;
  // Set the new position
  inc(APos, RD_Length + 10);
end;

constructor TQueryResult.Create(AResultRecord: TResultRecord);
begin
  inherited Create(TResultRecord);
  FRec := AResultRecord;
  FQueryPointerList := TStringList.Create;
end;

destructor TQueryResult.destroy;
begin
  FQueryPointerList.Free;
  inherited;
end;

function TQueryResult.GetItem(Index: Integer): TResultRecord;
begin
  Result := TResultRecord(inherited GetItem(Index));
end;

function TQueryResult.GetOwner: TPersistent;
begin
  Result := FRec;
end;

procedure TQueryResult.SetItem(Index: Integer; Value: TResultRecord);
begin
  inherited SetItem(Index, Value);
end;

{ TDNSHeader }

procedure TDNSHeader.ClearByteCode;
begin
  FBitCode := 0;
end;

constructor TDNSHeader.Create;
begin
  Randomize;
  FId := Random(65535);
end;

function TDNSHeader.GetAA: Word;
begin
  Result := (FBitCode and $0700) shr 10;
end;

function TDNSHeader.GetOpCode: Word;
begin
  Result := ((FBitCode and $7800) shr 11) and $000F;
end;

function TDNSHeader.GetQr: Word;
begin
  Result := FBitCode shr 15;
end;

function TDNSHeader.GetRA: Word;
begin
  Result := (FBitCode and $0800) shr 7;
end;

function TDNSHeader.GetRCode: Word;
begin
  Result := FBitCode and $000F;
end;

function TDNSHeader.GetRD: Word;
begin
  Result := (FBitCode and $0100) shr 8;
end;

function TDNSHeader.GetTC: Word;
begin
  Result := (FBitCode and $0200) shr 9;
end;

procedure TDNSHeader.SetAA(const Value: Word);
begin
  if Value = 0 then begin
    FBitCode := FBitCode and $FBFF;
  end else begin
    FBitCode := FBitCode or $0400;
  end;
end;

procedure TDNSHeader.SetOpCode(const Value: Word);
begin
  case Value of
    0: FBitCode := FBitCode and $87FF;
    1: FBitCode := FBitCode and $8FFF;
    2: FBitCode := FBitCode and $4BFF;
  end;
end;

procedure TDNSHeader.SetQr(const Value: Word);
begin
  if Value = 0 then begin
    FBitCode := FBitCode and $EFFF;
  end else begin
    FBitCode := FBitCode or $8000;
  end;
end;

procedure TDNSHeader.SetRA(const Value: Word);
begin
  if Value = 0 then begin
    FBitCode := FBitCode and $FF7F;
  end else begin
    FBitCode := FBitCode or $0080;
  end;
end;

procedure TDNSHeader.SetRCode(const Value: Word);
begin
  FBitCode := (FBitCode and $FFF0) or (Value and $000F);
end;

procedure TDNSHeader.SetRD(const Value: Word);
begin
  if Value = 0 then begin
    FBitCode := FBitCode and $FEFFF;
  end else begin
    FBitCode := FBitCode or $0100;
  end;
end;

procedure TDNSHeader.SetTC(const Value: Word);
begin
  if Value = 0 then begin
    FBitCode := FBitCode and $FDFF;
  end else begin
    FBitCode := FBitCode or $0200;
  end;
end;

procedure TIdDNSResolver.SetAllowRecursiveQueries(const Value: Boolean);
begin
  FAllowRecursiveQueries := Value;
end;

procedure TRDATARecord.Parse(CompleteMessage: String; APos: Integer);
begin
  inherited;
  if Length(RData) > 0 then
    FIPAddress := Format('%d.%d.%d.%d',[Word(RData[1]), Word(RData[2]), Word(RData[3]), Word(RData[4])]);  {Do not Localize}
end;

{ TResultRecord }


destructor TResultRecord.Destroy;
begin
  inherited;
end;

procedure TResultRecord.Parse;
begin

end;

procedure TNAMERecord.Parse(CompleteMessage: String; APos: Integer);
begin
  inherited;
  FHostName := (Collection as TQueryResult).DNSStrToDomain(CompleteMessage, APos);
end;


procedure TQueryResult.Clear;
begin
  inherited Clear;
  FQueryPointerList.Clear;
end;

procedure TMXRecord.Parse(CompleteMessage: String; APos: Integer);
var
  Chars: Array[0..1] of char;
begin
  inherited;
  Move(CompleteMessage[APos], Chars, 2);
  FPreference := TwoCharToWord(Chars[0], Chars[1]);
  Inc(Apos, 2);
  FExchangeServer := (Collection as TQueryResult).DNSStrToDomain(CompleteMessage, APos);
end;

{ TTextRecord }

constructor TTextRecord.Create(Collection: TCollection);
begin
  inherited;
  FText := TStringlist.Create;
end;

destructor TTextRecord.Destroy;
begin
  FText.free;
  inherited;
end;

procedure TTextRecord.Parse(CompleteMessage: String; APos: Integer);
var
  Buffer: string;
begin
  FText.Clear;
  repeat
    Buffer := (Collection as TQueryResult).NextDNSLabel(CompleteMessage, APos);
    if Buffer = '' then   {Do not Localize}
    begin
      Break
    end
    else
    begin
      FText.Add(Buffer);
    end;
  until false;
  inherited;
end;

{ TSOARecord }

procedure TSOARecord.Parse(CompleteMessage: String;APos: Integer);
begin
  inherited;
  FMNAME := (Collection as TQueryResult).DNSStrToDomain(CompleteMessage, APos);
  FRNAME := (Collection as TQueryResult).DNSStrToDomain(CompleteMessage, APos);
  FSerial := FourCharToCardinal(CompleteMessage[APos], CompleteMessage[APos + 1], CompleteMessage[APos + 2], CompleteMessage[APos + 3]);
  inc(Apos, 4);
  FRefresh := FourCharToCardinal(CompleteMessage[APos], CompleteMessage[APos + 1], CompleteMessage[APos + 2], CompleteMessage[APos + 3]);
  inc(Apos, 4);
  FRetry := FourCharToCardinal(CompleteMessage[APos], CompleteMessage[APos + 1], CompleteMessage[APos + 2], CompleteMessage[APos + 3]);
  inc(Apos, 4);
  FExpire := FourCharToCardinal(CompleteMessage[APos], CompleteMessage[APos + 1], CompleteMessage[APos + 2], CompleteMessage[APos + 3]);
  inc(Apos, 4);
  FMinimumTTL := FourCharToCardinal(CompleteMessage[APos], CompleteMessage[APos + 1], CompleteMessage[APos + 2], CompleteMessage[APos + 3]);
end;

{ TWKSRecord }

constructor TWKSRecord.Create;
begin

end;

destructor TWKSRecord.Destroy;
begin
  inherited;
end;

function TWKSRecord.GetABit(index: integer): Byte;
var
  realPos: PByte;
begin
  realPos := FData;
  Inc(realPos, Index);
  result := realPos^;
end;

procedure TWKSRecord.Parse(CompleteMessage: String; APos: Integer);
begin
  inherited;
  FAddress := Format('%d.%d.%d.%d',[Word(RData[1]), Word(RData[2]), Word(RData[3]), Word(RData[4])]);   {Do not Localize}
  FProtocol := Word(Rdata[5]);
  FData := PByte(PChar(FRData));
  Inc(FData, 5);
end;

{ TMINFORecord }

procedure TMINFORecord.Parse(CompleteMessage: String; APos: Integer);
begin
  inherited;
  FResponsiblePerson := (Collection as TQueryResult).DNSStrToDomain(CompleteMessage, APos);
  FErrorMailbox := (Collection as TQueryResult).DNSStrToDomain(CompleteMessage, APos);
end;

{ THINFORecord }

procedure THINFORecord.Parse(CompleteMessage: String; APos: Integer);
begin
  inherited;
  FCPU := (Collection as TQueryResult).NextDNSLabel(CompleteMessage, APos);
  FOS := (Collection as TQueryResult).NextDNSLabel(CompleteMessage, APos);
end;


end.
