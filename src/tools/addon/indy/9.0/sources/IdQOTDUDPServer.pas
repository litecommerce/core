{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10287: IdQOTDUDPServer.pas 
{
{   Rev 1.0    2002.11.12 10:49:08 PM  czhower
}
unit IdQOTDUDPServer;

interface
uses IdAssignedNumbers, IdSocketHandle, IdUDPBase, IdUDPServer, classes;
type
   TIdQotdUDPGetEvent = procedure (ABinding: TIdSocketHandle; var AQuote : String) of object;
   TIdQotdUDPServer = class(TIdUDPServer)
   protected
     FOnCommandQOTD : TIdQotdUDPGetEvent;
     procedure DoOnCommandQUOTD(ABinding: TIdSocketHandle; var AQuote : String); virtual;
     procedure DoUDPRead(AData: TStream; ABinding: TIdSocketHandle); override;
   public
     constructor Create(axOwner: TComponent); override;
   published
     property DefaultPort default IdPORT_QOTD;
     property OnCommandQOTD : TIdQotdUDPGetEvent read FOnCommandQOTD write FOnCommandQOTD;
   end;

implementation

{ TIdQotdUDPServer }

constructor TIdQotdUDPServer.Create(axOwner: TComponent);
begin
  inherited Create(axOwner);
  DefaultPort := IdPORT_QOTD;
end;

procedure TIdQotdUDPServer.DoOnCommandQUOTD(ABinding: TIdSocketHandle; var AQuote : String);
begin
  if Assigned(FOnCommandQOTD) then
  begin
    FOnCommandQOTD(ABinding, AQuote);
  end;
end;

procedure TIdQotdUDPServer.DoUDPRead(AData: TStream;
  ABinding: TIdSocketHandle);
var s : String;
begin
  inherited DoUDPRead(AData, ABinding);
  SetLength(s, AData.Size);
  AData.Read(s[1], AData.Size);
  s := '';    {Do not Localize}
  DoOnCommandQUOTD(ABinding,s);
  if (Length(s) > 0) then
  begin
    with ABinding do
    begin
      SendTo(PeerIP, PeerPort, s[1], Length(s));
    end;
  end;
end;

end.
 
