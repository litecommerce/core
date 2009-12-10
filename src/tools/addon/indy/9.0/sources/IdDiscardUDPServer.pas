{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10133: IdDiscardUDPServer.pas 
{
{   Rev 1.0    2002.11.12 10:36:14 PM  czhower
}
unit IdDiscardUDPServer;

interface

uses
  Classes,
  IdAssignedNumbers, IdSocketHandle, IdUDPBase, IdUDPServer;

type
   TIdDiscardUDPServer = class(TIdUDPServer)
   public
     constructor Create(AOwner: TComponent); override;
   published
     property DefaultPort default IdPORT_DISCARD;
   end;

implementation

{ TIdDiscardUDPServer }

constructor TIdDiscardUDPServer.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  DefaultPort := IdPORT_DISCARD;
end;

end.
 
