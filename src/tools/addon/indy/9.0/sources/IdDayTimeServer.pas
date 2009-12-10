{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10123: IdDayTimeServer.pas 
{
{   Rev 1.0    2002.11.12 10:35:06 PM  czhower
}
unit IdDayTimeServer;

interface

{
2000-Apr-22: J Peter Mugass
  -Ported to Indy
1999-Apr-13
  -Final Version
2000-JAN-13 MTL
  -Moved to new Palette Scheme (Winshoes Servers)
Original Author: Ozz Nixon
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Type
  TIdDayTimeServer = class(TIdTCPServer)
  protected
    FTimeZone: String;
    //
    function DoExecute(AThread: TIdPeerThread): boolean; override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    property TimeZone: String read FTimeZone write FTimeZone;
    property DefaultPort default IdPORT_DAYTIME;
  end;

implementation

uses
  SysUtils;

constructor TIdDayTimeServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_DAYTIME;
  FTimeZone := 'EST';  {Do not Localize}
end;

function TIdDayTimeServer.DoExecute(AThread: TIdPeerThread ): boolean;
begin
  result := true;
  with AThread.Connection do begin
    Writeln(FormatDateTime('dddd, mmmm dd, yyyy hh:nn:ss', Now) + '-' + FTimeZone);    {Do not Localize}
    Disconnect;
  end;
end;

end.
