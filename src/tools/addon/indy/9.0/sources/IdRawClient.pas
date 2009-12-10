{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10291: IdRawClient.pas 
{
{   Rev 1.0    2002.11.12 10:49:22 PM  czhower
}
unit IdRawClient;

interface

uses
  IdRawBase;

type
  TIdRawClient = class(TIdRawBase)
  published
    property ReceiveTimeout;
    property Host;
    property Port;
    property Protocol;
  end;

implementation

{ TIdRawClient }

end.
