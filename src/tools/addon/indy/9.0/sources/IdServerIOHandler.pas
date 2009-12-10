{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10317: IdServerIOHandler.pas 
{
{   Rev 1.0    2002.11.12 10:51:10 PM  czhower
}
unit IdServerIOHandler;

interface

uses
  Classes, SysUtils,
  IdComponent, IdIOHandlerSocket, IdStackConsts, IdIOHandler, IdThread;

type
  TIdServerIOHandler = class(TIdComponent)
  public
    procedure Init; virtual;
    function Accept(ASocket: TIdStackSocketHandle; AThread: TIdThread = nil): TIdIOHandler; virtual;
  end;

implementation

{ TIdServerIOHandler }

procedure TIdServerIOHandler.Init;
begin
  //
end;

function TIdServerIOHandler.Accept(ASocket: TIdStackSocketHandle;
  AThread: TIdThread = nil): TIdIOHandler;
begin
  result := nil;
end;

end.
