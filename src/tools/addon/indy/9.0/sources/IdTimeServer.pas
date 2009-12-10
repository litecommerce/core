{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10389: IdTimeServer.pas 
{
{   Rev 1.0    2002.11.12 10:56:56 PM  czhower
}
unit IdTimeServer;

interface

{
 2000-3-May    J. Peter Mugaas
  -Added BaseDate to the date the calculations are based on can be
   adjusted to work after the year 2035
2000-30-April  J. Peter Mugaas
  -Adjusted the formula for the integer so that the Time is now
   always based on Universal Time (also known as Greenwhich Mean
  -Time Replaced the old forumala used to calculate the time with
   a new one suggested by Jim Gunkel.  This forumala is more
   accurate than the old one.
2000-24-April  J. Peter Mugaaas
  -This now uses the Internet Byte order functions
2000-22-Apr    J Peter Mugass
  -Ported to Indy
  -Fixed a problem where the server was not returning anything
2000-13-Jan MTL
  -Moved to new Palette Scheme (Winshoes Servers)
1999-13-Apr
  -Final Version

Original Author: Ozz Nixon
  -Based on RFC 868
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

const
  {This indicates that the default date is Jan 1, 1900 which was specified
   by RFC 868.}
  TIME_DEFBASEDATE = 2;

Type
  TIdTimeServer = class(TIdTCPServer)
  protected
    FBaseDate : TDateTime;
    //
    function DoExecute(AThread: TIdPeerThread): Boolean; override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    {This property is used to set the Date the Time server bases it's   
     calculations from.  If both the server and client are based from the same
     date which is higher than the original date, you can extend it beyond the
     year 2035}
    property BaseDate : TDateTime read FBaseDate write FBaseDate;
    property DefaultPort default IdPORT_TIME;
  end;

implementation

uses
  IdGlobal,
  IdStack, SysUtils;

constructor TIdTimeServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_TIME;
    {This indicates that the default date is Jan 1, 1900 which was specified
    by RFC 868.}
  FBaseDate := 2;
end;

function TIdTimeServer.DoExecute(AThread: TIdPeerThread): Boolean;
begin
  Result := true;
  with AThread.Connection do begin
    WriteCardinal(Trunc(extended(Now + IdGlobal.TimeZoneBias - Int(FBaseDate)) * 24 * 60 * 60));
    Disconnect;
  end;
end;

end.
