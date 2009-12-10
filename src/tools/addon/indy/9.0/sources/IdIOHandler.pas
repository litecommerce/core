{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10211: IdIOHandler.pas 
{
{   Rev 1.1    2003.06.15 3:00:32 PM  czhower
{ -Fixed IdIOHandlerStream to function as originally designed and needed.
{ -Change ReadStream, WriteStream to Input/Output to be consistent with other
{ areas.
}
{
{   Rev 1.0    2002.11.12 10:42:22 PM  czhower
}
unit IdIOHandler;

interface

uses
  Classes,
  IdComponent, IdGlobal;

type
  TIdIOHandler = class(TIdComponent)
  protected
    FActive: Boolean;
  public
    procedure AfterAccept; virtual;
    procedure Close; virtual;
    procedure ConnectClient(const AHost: string; const APort: Integer; const ABoundIP: string;
     const ABoundPort: Integer; const ABoundPortMin: Integer; const ABoundPortMax: Integer;
     const ATimeout: Integer = IdTimeoutDefault); virtual;
    function Connected: Boolean; virtual;
    destructor Destroy; override;
    procedure Open; virtual;
    function Readable(AMSec: Integer = IdTimeoutDefault): Boolean; virtual; abstract;
    function Recv(var ABuf; ALen: Integer): Integer; virtual; abstract;
    function Send(var ABuf; ALen: Integer): Integer; virtual; abstract;
    //
    property Active: Boolean read FActive;
  end;

implementation

{ TIdIOHandler }

procedure TIdIOHandler.Close;
begin
  FActive := False;
end;

procedure TIdIOHandler.ConnectClient(const AHost: string;
 const APort: Integer; const ABoundIP: string; const ABoundPort,
 ABoundPortMin, ABoundPortMax: Integer; const ATimeout: Integer);
begin
  //
end;

destructor TIdIOHandler.Destroy;
begin
  Close;
  inherited;
end;

procedure TIdIOHandler.AfterAccept;
begin
  //
end;

procedure TIdIOHandler.Open;
begin
  FActive := True;
end;

function TIdIOHandler.Connected: Boolean;
begin
  Result := FActive;
end;

end.
