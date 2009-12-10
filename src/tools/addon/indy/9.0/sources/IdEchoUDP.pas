{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10149: IdEchoUDP.pas 
{
{   Rev 1.0    2002.11.12 10:37:18 PM  czhower
}
unit IdEchoUDP;

interface
uses Classes, IdAssignedNumbers, IdUDPBase, IdUDPClient;
type
  TIdEchoUDP = class(TIdUDPClient)
  protected
    FEchoTime: Cardinal;
  public
    constructor Create(AOwner: TComponent); override;
    {This sends Text to the peer and returns the reply from the peer}
    Function Echo(AText: String): String;
    {Time taken to send and receive data}
    Property EchoTime: Cardinal read FEchoTime;
  published
    property Port default IdPORT_ECHO;
  end;

implementation
uses IdGlobal;

{ TIdIdEchoUDP }

constructor TIdEchoUDP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Port := IdPORT_ECHO;
end;

function TIdEchoUDP.Echo(AText: String): String;
var
  StartTime: Cardinal;
begin
  StartTime := GetTickCount;
  Send(AText);
  Result := ReceiveString;
  {This is just in case the TickCount rolled back to zero}
  FEchoTime :=  GetTickDiff(StartTime,IdGlobal.GetTickCount);
end;

end.
