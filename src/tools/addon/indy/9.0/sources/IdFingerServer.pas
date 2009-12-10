{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10159: IdFingerServer.pas 
{
{   Rev 1.0    2002.11.12 10:38:16 PM  czhower
}
unit IdFingerServer;

interface

{
2000-May-15  J. Peter Mugaas
  -Added verbose querry event to complement TIdFinger
2000-Apr-22  J Peter Mugass
  -Ported to Indy
2000-Jan-13  MTL
  -Moved to new Palette Scheme (Winshoes Servers)
1999-Apr-13
  -Final Version
Original Author: Ozz Nixon
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Type
  TIdFingerGetEvent = procedure (AThread: TIdPeerThread; const AUserName: String) of object;

  TIdFingerServer = class ( TIdTCPServer )
  protected
    FOnCommandFinger : TIdFingerGetEvent;
    FOnCommandVerboseFinger : TIdFingerGetEvent;
    //
    function DoExecute(AThread: TIdPeerThread): boolean; override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    {This event fires when you make a regular querry}
    property OnCommandFinger: TIdFingerGetEvent read FOnCommandFinger
      write FOnCommandFinger;
    { This event fires when you receive a VERBOSE finger request}
    property OnCommandVerboseFinger : TIdFingerGetEvent
      read FOnCommandVerboseFinger write FOnCommandVerboseFinger;
    property DefaultPort default IDPORT_Finger;
  end;

implementation

uses
  SysUtils;

constructor TIdFingerServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_FINGER;
end;

function TIdFingerServer.DoExecute(AThread: TIdPeerThread): boolean;
Var
  s: String;
begin
  result := true;
  {We use TrimRight in case there are spaces ending the query which are problematic
  for verbose queries.  CyberKit puts a space after the /W parameter}
  s := TrimRight(AThread.Connection.Readln);
  If assigned ( FOnCommandVerboseFinger ) and
    ( UpperCase( Copy ( s, Length ( s ) -1, 2 ) )  = '/W' ) then {Do not Localize}
  begin
    {we remove the /W switch before calling the event}
    s := Copy(s, 1, Length ( s ) - 2);
    OnCommandVerboseFinger ( AThread, s );
  end  //if assigned ( FOnCommandVerboseFinger ) and
  else
  begin
    if assigned ( OnCommandFinger ) then begin
      OnCommandFinger ( AThread, s );
    end; //if assigned(OnCommandFinger) then
  end; //else .. if assigned ( FOnCommandVerboseFinger ) and
  AThread.Connection.Disconnect;
end;

end.
