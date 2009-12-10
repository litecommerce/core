{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10331: IdSNTP.pas 
{
{   Rev 1.0    2002.11.12 10:52:04 PM  czhower
}
unit IdSNTP;

{*
  Winshoe SNTP (Simple Network Time Protocol)
  Behaves more or less according to RFC-2030

  2002 Jan 21 Don
    Added suggestions from R. Brian Lindahl.
    Added CheckStratum property.
    Modified Disregard to use CheckStratum property.
    Modified GetAdjustmentTime to ignore optional NTP authentication in response.

  2002 Jan 3 Don
    Corrected errors introduced in previous revision.
    Added TIdSNTP.Create to assign port number for the SNTP protocol.

  2002 Jan 3 Don
    Corrected error in TIdSNTP.GetDateTime as per Bug Report
    http://sourceforge.net/tracker/?func=detail&atid=431491&aid=498843&group_id=41862

  2001 Sep 4 Don
    Corrected error in Flip() as reported on BCB newsgroup

  2000 Apr 21 Kudzu
    Updated to match UDP core changes

  2000 Mar 28 Hadi
    Continued conversion to Indy

	2000 Mar 24 Kudzu
    Converted to Indy

	2000 Jan 13 MTL
  	Moved to new Palette Tab scheme (Winshoes Clients)
    1999

  	R. Brian Lindahl - Original Author
*}

interface

uses
	Classes,
  IdUDPClient;

const
  NTPMaxInt = 4294967297.0;

type
	// NTP Datagram format
  TNTPGram	= packed record
    Head1: byte;
    Head2: byte;
    Head3: byte;
    Head4: byte;
    RootDelay: longint;
    RootDispersion: longint;
    RefID: longint;
    Ref1: longint;
    Ref2: longint;
    Org1: longint;
    Org2: longint;
    Rcv1: longint;
    Rcv2: longint;
    Xmit1: longint;
    Xmit2: longint;
  end;

  TLr = packed record
    L1: byte;
    L2: byte;
    L3: byte;
    L4: byte;
  end;

  TIdSNTP = class(TIdUDPClient)
  protected
    FDestinationTimestamp: TDateTime;   // Destination Timestamp   T4   time reply received by client
    FLocalClockOffset: TDateTime;       // = ((T2 - T1) + (T3 - T4)) / 2
    FOriginateTimestamp: TDateTime;     // Originate Timestamp     T1   time request sent by client
    FReceiveTimestamp: TDateTime;       // Receive Timestamp       T2   time request received by server
    FRoundTripDelay: TDateTime;         // = (T4 - T1) - (T2 - T3)
    FTransmitTimestamp: TDateTime;      // Transmit Timestamp      T3   time reply sent by server
    FCheckStratum: Boolean;
    //
    function Disregard(NTPMessage: TNTPGram): Boolean;
    function GetAdjustmentTime: TDateTime;
    function GetDateTime: TDateTime;
  public
    constructor Create(AOwner: TComponent); override;
    function SyncTime: Boolean;        // get datetime and adjust if needed
    //
    property AdjustmentTime: TDateTime read GetAdjustmentTime;
    property DateTime: TDateTime read GetDateTime;
    property RoundTripDelay: TDateTime read FRoundTripDelay;
    property CheckStratum: Boolean read FCheckStratum write FCheckStratum default True;
  end;

implementation

uses
  IdGlobal,
  IdAssignedNumbers,
  SysUtils;

function Flip(var Number: longint): longint;
var
  Number1: TLr;
  Number2: TLr;
begin
  Number1 := TLr(Number);

  Number2.L1 := Number1.L4;
  Number2.L2 := Number1.L3;
  Number2.L3 := Number1.L2;
  Number2.L4 := Number1.L1;  // ds 4-Sep-2001

  Result := longint(Number2);
end;

procedure DateTimeToNTP(ADateTime: TDateTime;var Second,Fraction: longint);
var
  Value1,
  Value2: Double;
begin
  Value1 := (ADateTime + TimeZoneBias - 2) * 86400;
  Value2 := Value1;

  if Value2 > NTPMaxInt then
  begin
    Value2 := Value2 - NTPMaxInt;
  end;

  Second := LongInt(Trunc(Value2));
  Value2 := ((Frac(Value1) * 1000) / 1000) * NTPMaxInt;

  if Value2 > NTPMaxInt then
  begin
    Value2 := Value2 - NTPMaxInt;
  end;

  Fraction := LongInt(Trunc(Value2));
end;

function NTPToDateTime(Second, Fraction: longint): TDateTime;
var
  Value1: Double;
  Value2: Double;
begin
  Value1 := Second;

  if Value1 < 0 then
  begin
    Value1 := NTPMaxInt + Value1 - 1;
  end;

  Value2 := Fraction;

  if Value2 < 0 then
  begin
    Value2 := NTPMaxInt + Value2 - 1;
  end;

  // Value2 := Value2 / NTPMaxInt;
  // Value2 := Trunc(Value2 * 1000) / 1000;

  Value2 := Trunc(Value2 / NTPMaxInt * 1000) / 1000;
  Result := ((Value1 + Value2) / 86400) - TimeZoneBias + 2;
end ;

{ TIdSNTP }

constructor TIdSNTP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FPort := IdPORT_SNTP;
  FCheckStratum := True;
end;


function TIdSNTP.Disregard(NTPMessage: TNTPGram): Boolean;
var
  vStratum: Integer;
  vLeapIndicator: Integer;
begin
  vLeapIndicator := (NTPMessage.Head1 and 192 ) shr 6;
  vStratum :=  NTPMessage.Head2;

  Result := (vLeapIndicator = 3) or
    { (Stratum > 15) or (Stratum = 0) or }
    (((Int(FTransmitTimestamp)) = 0.0) and (Frac(FTransmitTimestamp) = 0.0));

  // DS ignore NTPGram when stratum is used, and value is reserved or unspecified
  if FCheckStratum and ((vStratum > 15) or (vStratum = 0)) then
  begin
    Result := True;
  end;
end;


function TIdSNTP.GetAdjustmentTime: TDateTime;
begin
  Result := FLocalClockOffset;
end;

function TIdSNTP.GetDateTime: TDateTime;
var
  NTPDataGram: TNTPGram;
  ResultString: string;
begin
  FillChar(NTPDataGram, SizeOf(NTPDataGram), 0);

  NTPDataGram.Head1 := $1B;
  DateTimeToNTP(Now, NTPDataGram.Xmit1, NTPDataGram.Xmit2);
  NTPDataGram.Xmit1 := Flip(NTPDataGram.Xmit1);
  NTPDataGram.Xmit2 := Flip(NTPDataGram.Xmit2);
  SetLength(ResultString, SizeOf(NTPDataGram));

  Move(NTPDataGram, ResultString[1], SizeOf(NTPDataGram));
  BufferSize := SizeOf(NTPDataGram);
  Send(ResultString);
  ResultString := ReceiveString;

  // DS default result is an empty TDateTime value
  Result := 0.0;

  // DS response may contain optional NTP authentication scheme info not in NTPGram
  if Length(ResultString) >= SizeOf(NTPDataGram) then
  begin
    FDestinationTimeStamp := Now ;

    // DS copy result data back into NTPDataGram
    // DS ignore optional NTP authentication scheme info in response
    Move(ResultString[1], NTPDataGram, SizeOf(NTPDataGram));

    FOriginateTimeStamp := NTPToDateTime(Flip(NTPDataGram.Org1), Flip(NTPDataGram.Org2));
    FReceiveTimestamp := NTPToDateTime(Flip(NTPDataGram.Rcv1), Flip(NTPDataGram.Rcv2));
    FTransmitTimestamp := NTPToDateTime(Flip(NTPDataGram.Xmit1), Flip(NTPDataGram.Xmit2));

    FRoundTripDelay := (FDestinationTimestamp - FOriginateTimestamp) -
      (FReceiveTimestamp - FTransmitTimestamp);

    FLocalClockOffset := ((FReceiveTimestamp - FOriginateTimestamp) +
      (FTransmitTimestamp - FDestinationTimestamp)) / 2;

    // DS update date/time when NTP datagram is not ignored
    if not Disregard(NTPDataGram) then
    begin
      Result := NTPToDateTime(Flip(NTPDataGram.Xmit1), Flip(NTPDataGram.Xmit2));
    end;
  end;
end;

function TIdSNTP.SyncTime: Boolean;
begin
  Result := DateTime <> 0.0;

  if Result then
  begin
    Result := SetLocalTime(FOriginateTimestamp + FLocalClockOffset + FRoundTripDelay);
  end;
end;

end.
