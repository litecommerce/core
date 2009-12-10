{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10215: IdIOHandlerStream.pas 
{
    Rev 1.2    6/21/2003 10:00:00 AM  DSiders
  Update declaration of Readable in implementation to match the interface
  declaration.
}
{
{   Rev 1.1    2003.06.15 3:00:32 PM  czhower
{ -Fixed IdIOHandlerStream to function as originally designed and needed.
{ -Change ReadStream, WriteStream to Input/Output to be consistent with other
{ areas.
}
{
{   Rev 1.0    2002.11.12 10:42:42 PM  czhower
}
unit IdIOHandlerStream;

interface

uses
  Classes,
  IdGlobal, IdIOHandler;

type
  TIdIOHandlerStream = class(TIdIOHandler)
  protected
    FFreeStreams: Boolean;
    FInputStream: TStream;
    FOutputStream: TStream;
    //
    procedure SetInputStream(const AValue: TStream);
    procedure SetOutputStream(const AValue: TStream);
  public
    procedure Close; override;
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Open; override;
    function Readable(AMSec: integer = IdTimeoutDefault): boolean; override;
    function Recv(var ABuf; ALen: integer): integer; override;
    function Send(var ABuf; ALen: integer): integer; override;
    //
    property InputStream: TStream read FInputStream write SetInputStream;
    property OutputStream: TStream read FOutputStream write SetOutputStream;
  published
    property FreeStreams: Boolean read FFreeStreams write FFreeStreams;
  end;

implementation

uses
  IdException,
  SysUtils;

{ TIdIOHandlerStream }

procedure TIdIOHandlerStream.Close;
begin
  inherited;
  // After Active = False inherited just in case
  if FreeStreams then begin
    FreeAndNil(FInputStream);
    FreeAndNil(FOutputStream);
  end;
end;

constructor TIdIOHandlerStream.Create(AOwner: TComponent);
begin
  inherited;
  FFreeStreams := True;
end;

destructor TIdIOHandlerStream.Destroy;
begin
  Close;
  inherited Destroy;
end;

procedure TIdIOHandlerStream.Open;
begin
  // InputStream can be nil. For example coders use this to save to a file
  if (InputStream = nil) and (OutputStream = nil) then begin
    raise EIdException.Create('No InputStream or OutputStream assigned.');
  end;
  inherited;
end;

function TIdIOHandlerStream.Readable(AMSec: integer = IdTimeoutDefault): boolean;
begin
  Result := InputStream <> nil;
  if Result then begin
    Result := InputStream.Position < InputStream.Size;
  end;
end;

function TIdIOHandlerStream.Recv(var ABuf; ALen: integer): integer;
begin
  if InputStream = nil then begin
    // Should return closed gracefully IIRC
    Result := 0;
  end else begin
    Result := InputStream.Read(ABuf, ALen);
  end;
end;

function TIdIOHandlerStream.Send(var ABuf; ALen: integer): Integer;
begin
  if OutputStream = nil then begin
    // If its nil, we just void the output and pretend we sent it
    Result := ALen;
  end else begin
    Result := OutputStream.Write(ABuf, ALen);
  end;
end;

procedure TIdIOHandlerStream.SetInputStream(const AValue: TStream);
begin
  if Active then begin
    raise EIdException.Create('Cannot set InputStream while active.');
  end;
  FInputStream := AValue;
end;

procedure TIdIOHandlerStream.SetOutputStream(const AValue: TStream);
begin
  if Active then begin
    raise EIdException.Create('Cannot set OutputStream while active.');
  end;
  FOutputStream := AValue;
end;

end.
