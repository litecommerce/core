{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10285: IdQOTDUDP.pas 
{
{   Rev 1.0    2002.11.12 10:48:50 PM  czhower
}
unit IdQOTDUDP;

interface
uses classes, IdAssignedNumbers, IdUDPBase, IdUDPClient;
type
  TIdQOTDUDP = class(TIdUDPClient)
  protected
    Function GetQuote : String;
  public
    constructor Create(AOwner: TComponent); override;
    { This is the quote from the server }
    Property Quote: String read GetQuote;
  published
    Property Port default IdPORT_QOTD;
  end;

implementation

{ TIdQOTDUDP }

constructor TIdQOTDUDP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Port := IdPORT_QOTD;
end;

function TIdQOTDUDP.GetQuote: String;
begin
  //The string can be anything - The RFC says the server should discard packets
  Send(' ');    {Do not Localize}
  Result := ReceiveString;
end;

end.
