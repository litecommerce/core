{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10421: IdWhois.pas 
{
{   Rev 1.0    2002.11.12 10:59:58 PM  czhower
}
unit IdWhois;

{
2000-May-30 J. Peter Mugaas
  -made modifications so OnWork event will work for this component
2000-Apr-17 Kudzu
  -Converted to Indy
2000-Jan-13 MTL
  -Moved to new Palette Scheme (Winshoes Servers)
1999-Jan-05 - Kudzu
  -Cleaned uses clause
  -Changed result type
  -Eliminated Response prop
  -Fixed a bug in Whois
  -Added Try..finally
  -Other various mods
Original Author: Hadi Hariri
}

interface

uses
	Classes,
  IdAssignedNumbers,
  IdTCPClient;

type
  TIdWhois = class(TIdTCPClient)
  public
    constructor Create(AOwner: TComponent); override;
    function WhoIs(const ADomain: string): string;
  end;

implementation

uses
  IdGlobal,
  IdTCPConnection;

{ TIdWHOIS }

constructor TIdWHOIS.Create(AOwner: TComponent);
begin
  inherited;
  Host := 'whois.internic.net';    {Do not Localize}
  Port := IdPORT_WHOIS;
end;

function TIdWHOIS.WhoIs(const ADomain: string): string;
begin
  Connect; try
    WriteLn(ADomain);
    Result := AllData;
  finally Disconnect; end;
end;

end.
