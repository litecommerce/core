{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10391: IdTimeUDP.pas 
{
{   Rev 1.0    2002.11.12 10:57:02 PM  czhower
}
unit IdTimeUDP;

interface
uses Classes, IdAssignedNumbers, IdUDPBase, IdUDPClient;

const
  {This indicates that the default date is Jan 1, 1900 which was specified
    by RFC 868.}
  TIMEUDP_BASEDATE = 2;

type
  TIdTimeUDP = class(TIdUDPClient)
  protected
    FBaseDate: TDateTime;
    FRoundTripDelay: Cardinal;
    //
    function GetDateTimeCard: Cardinal;
    function GetDateTime: TDateTime;
  public
    constructor Create(AOwner: TComponent); override;
    {This synchronizes the local clock with the Time Server}
    function SyncTime: Boolean;
    {This is the number of seconds since 12:00 AM, 1900 - Jan-1}
    property DateTimeCard: Cardinal read GetDateTimeCard;
    {This is the current time according to the server.  TimeZone and Time used
    to receive the data are accounted for}
    property DateTime: TDateTime read GetDateTime;
    {This is the time it took to receive the Time from the server.  There is no
    need to use this to calculate the current time when using DateTime property
    as we have done that here}
    property RoundTripDelay: Cardinal read FRoundTripDelay;
  published
    {This property is used to set the Date that the Time server bases its
     calculations from.  If both the server and client are based from the same
     date which is higher than the original date, you can extend it beyond the
     year 2035}
    property BaseDate: TDateTime read FBaseDate write FBaseDate;
    property Port default IdPORT_TIME;
  end;

implementation
uses IdGlobal, IdStack;

{ TIdTimeUDP }

constructor TIdTimeUDP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Port := IdPORT_TIME;
  {This indicates that the default date is Jan 1, 1900 which was specified
    by RFC 868.}
  FBaseDate := TIMEUDP_BASEDATE;
end;

function TIdTimeUDP.GetDateTime: TDateTime;
var
  BufCard: Cardinal;
begin
  BufCard := GetDateTimeCard;
  if BufCard <> 0 then begin
    {The formula is The Time cardinal we receive divided by (24 * 60*60 for days + RoundTrip divided by one-thousand since this is based on seconds
    - the Time Zone difference}
    Result := ( ((BufCard + (FRoundTripDelay div 1000))/ (24 * 60 * 60) ) + Int(fBaseDate))
                - IdGlobal.TimeZoneBias;
  end else begin
    { Somehow, I really doubt we are ever going to really get a time such as
    12/30/1899 12:00 am so use that as a failure test}
    Result := 0;
  end;
end;

function TIdTimeUDP.GetDateTimeCard: Cardinal;
var
  LTimeBeforeRetrieve: Cardinal;
begin
  Result := 0;
  //Important - This must send an empty UDP Datagram
  Send('');    {Do not Localize}
  LTimeBeforeRetrieve := IdGlobal.GetTickCount;
  ReceiveBuffer(Result,SizeOf(Result));
  Result := GStack.WSNToHL(Result);
  {Theoritically, it should take about 1/2 of the time to receive the data
   but in practice, it could be any portion depending upon network conditions. This is also
   as per RFC standard}
  {This is just in case the TickCount rolled back to zero}
  FRoundTripDelay := GetTickDiff(IdGlobal.GetTickCount,LTimeBeforeRetrieve) div 2;
end;

function TIdTimeUDP.SyncTime: Boolean;
var
  LBufTime: TDateTime;
begin
  LBufTime := DateTime;
  Result := LBufTime <> 0;
  if Result then begin
    Result := SetLocalTime(LBufTime);
  end;
end;

end.
