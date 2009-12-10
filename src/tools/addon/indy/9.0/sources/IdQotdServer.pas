{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10283: IdQotdServer.pas 
{
{   Rev 1.0    2002.11.12 10:48:38 PM  czhower
}
unit IdQotdServer;

interface

{
2000-May-15  J. Peter Mugaas
 -renamed events to have Id prefix
2000-Apr-22  J Peter Mugaas
  Ported to Indy
2000-Jan-13 MTL
  Moved to new Palette Scheme (Winshoes Servers)
1999-May-13
  Final Version
Original Author: Ozz Nixon
  (RFC 865) [less than 512 characters total, multiple lines OK!]
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Type
  TIdQOTDGetEvent = procedure ( Thread: TIdPeerThread ) of object;

  TIdQOTDServer = class ( TIdTCPServer )
  protected
    FOnCommandQOTD : TIdQOTDGetEvent;
    //
    function DoExecute ( Thread : TIdPeerThread ): boolean; override;
  public
    constructor Create ( AOwner : TComponent ); override;
  published
    property OnCommandQOTD : TIdQOTDGetEvent read fOnCommandQOTD
      write fOnCommandQOTD;
    property DefaultPort default IdPORT_QOTD;
  end;

implementation

uses
  SysUtils;

constructor TIdQOTDServer.Create ( AOwner : TComponent );
begin
  inherited;
  DefaultPort := IdPORT_QOTD;
end;

function TIdQOTDServer.DoExecute ( Thread: TIdPeerThread ) : boolean;
begin
  result := true;
  if Thread.Connection.Connected then begin
    if assigned ( OnCommandQOTD ) then begin
      OnCommandQOTD ( Thread );
    end;
  end;
  Thread.Connection.Disconnect;
end; {doExecute}

end.
