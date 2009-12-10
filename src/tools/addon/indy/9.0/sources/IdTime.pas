{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10387: IdTime.pas 
{
{   Rev 1.0    2002.11.12 10:56:42 PM  czhower
}
unit IdTime;
{*******************************************************}
{                                                       }
{       Indy Time Client TIdTime                        }
{                                                       }
{       Copyright (C) 2000 Winshoes Working Group       }
{       Original author J. Peter Mugaas                 }
{       2000-April-24                                   }
{       Based on RFC RFC 868                            }
{                                                       }
{*******************************************************}
{
 2001-Sep -21 J. Peter Mugaas
  - adjusted formula as suggested by Vaclav Korecek.  The old
  one would give wrong date, time if RoundTripDelay was over
   a value of 1000 
 2000-May -04  J. Peter Mugaas
  -Changed RoundTripDelay to a cardinal and I now use the
   GetTickCount function for more accuracy
  -The formula had to adjusted for this.
 2000-May -03  J. Peter Mugaas
  -Added BaseDate to the date the calculations are based on can be
   adjusted to work after the year 2035
 2000-Apr.-29  J. Peter Mugaas
  -Made the time more accurate by taking into account time-zone
   bias by subtracting IdGlobal.TimeZoneBias.
  -I also added a correction for the time it took to receive the
   Integer from the server ( ReadInteger )
  -Changed Time property to DateTime and TimeCard to DateTimeCard
   to be more consistant with TIdSNTP.
}

interface

uses
  Classes, IdAssignedNumbers, IdTCPClient;

const
  {This indicates that the default date is Jan 1, 1900 which was specified
    by RFC 868.}
  TIME_BASEDATE = 2;
  TIME_TIMEOUT = 2500;

type
  TIdTime = class(TIdTCPClient)
  protected
    FBaseDate: TDateTime;
    FRoundTripDelay: Cardinal;
    FTimeout: Integer;
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
    property Timeout: Integer read FTimeout write FTimeout default TIME_TIMEOUT;
    property Port default IdPORT_TIME;
  end;

implementation

uses
  IdGlobal, IdTCPConnection,
  SysUtils;

{ TIdTime }

constructor TIdTime.Create(AOwner: TComponent);
begin
  inherited;
  Port := IdPORT_TIME;
  {This indicates that the default date is Jan 1, 1900 which was specified
    by RFC 868.}
  FBaseDate := TIME_BASEDATE;
  FTimeout := TIME_TIMEOUT;
end;

function TIdTime.GetDateTime: TDateTime;
var
  BufCard: Cardinal;
begin
  BufCard := GetDateTimeCard;
  if BufCard <> 0 then begin
    {The formula is The Time cardinal we receive divided by (24 * 60*60 for days + RoundTrip divided by one-thousand since this is based on seconds
    - the Time Zone difference}
    Result := ( ((BufCard + (FRoundTripDelay div 1000))/ (24 * 60 * 60) ) + Int(fBaseDate))
    -IdGlobal.TimeZoneBias;

  end else begin
    { Somehow, I really doubt we are ever going to really get a time such as
    12/30/1899 12:00 am so use that as a failure test}
    Result := 0;
  end;
end;

function TIdTime.GetDateTimeCard: Cardinal;
var
  LTimeBeforeRetrieve: Cardinal;
begin
  Result := 0;
  Connect; try
    LTimeBeforeRetrieve := IdGlobal.GetTickCount;
    // Check for timeout
    // Timeout is actually a time with no traffic, not a total timeout.
    repeat
      if ReadFromStack(True, FTimeout) = 0 then begin
        Exit;
      end;
    until InputBuffer.Size >= SizeOf(Result);
    //
    Result := ReadCardinal;
    {Theoritically, it should take about 1/2 of the time to receive the data
    but in practice, it could be any portion depending upon network conditions. This is also
    as per RFC standard}

    {This is just in case the TickCount rolled back to zero}
    FRoundTripDelay := GetTickDiff(LTimeBeforeRetrieve,IdGlobal.GetTickCount) div 2;
  finally Disconnect; end;
end;

function TIdTime.SyncTime: Boolean;
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
