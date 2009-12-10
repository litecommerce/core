{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10147: IdEchoServer.pas 
{
{   Rev 1.0    2002.11.12 10:37:08 PM  czhower
}
unit IdEchoServer;

interface

{
2000-Apr=22 J Peter Mugaas
  Ported to Indy
1999-May-13
  Final Version
2000-Jan-13 MTL
  Moved to new Palette Scheme (Winshoes Servers)
Original Author: Ozz Nixon
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Type
  TIdECHOServer = class ( TIdTCPServer )
  private
  protected
    function DoExecute(AThread: TIdPeerThread): boolean; override;
  public
    constructor Create ( AOwner : TComponent); override;
  published
    property DefaultPort default IdPORT_ECHO;
  end;

implementation

uses
  SysUtils;

constructor TIdECHOServer.Create ( AOwner : TComponent );
begin
  inherited;
  DefaultPort := IdPORT_ECHO;
end;

function TIdECHOServer.DoExecute (AThread: TIdPeerThread): boolean;
begin
  result := true;
  with AThread.Connection do begin
    while Connected do begin
      Write(CurrentReadBuffer);
    end;
  end;
end;

end.
