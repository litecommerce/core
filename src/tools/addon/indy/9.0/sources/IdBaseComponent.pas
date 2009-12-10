{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10081: IdBaseComponent.pas 
{
{   Rev 1.0    2002.11.12 10:31:32 PM  czhower
}
unit IdBaseComponent;

interface

uses
  Classes;

// ***********************************************************
// TIdBaseComponent is the base class for all Indy components.
// ***********************************************************
type
  TIdBaseComponent = class(TComponent)
  public
    function GetVersion: string;
    property Version: string read GetVersion;
  published
  end;

implementation

uses
  IdGlobal;

function TIdBaseComponent.GetVersion: string;
begin
  Result := gsIdVersion;
end;

end.
