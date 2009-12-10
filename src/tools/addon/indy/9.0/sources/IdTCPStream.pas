{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10369: IdTCPStream.pas 
{
{   Rev 1.0    2002.11.12 10:55:28 PM  czhower
}
unit IdTCPStream;

interface

uses
  Classes,
  IdTCPConnection;

type
  TIdTCPStream = class(TStream)
  protected
    FConnection: TIdTCPConnection;
  public
    constructor Create(AConnection: TIdTCPConnection); reintroduce;
    function Read(var ABuffer; ACount: Longint): Longint; override;
    function Write(const ABuffer; ACount: Longint): Longint; override;
    function Seek(AOffset: Longint; AOrigin: Word): Longint; override;
    //
    property Connection: TIdTCPConnection read FConnection;
  end;

implementation

{ TIdTCPStream }

constructor TIdTCPStream.Create(AConnection: TIdTCPConnection);
begin
  inherited Create;
  FConnection := AConnection;
end;

function TIdTCPStream.Read(var ABuffer; ACount: Integer): Longint;
begin
  Connection.ReadBuffer(ABuffer, ACount);
  Result := ACount;
end;

function TIdTCPStream.Seek(AOffset: Integer; AOrigin: Word): Longint;
begin
  Result := -1;
end;

function TIdTCPStream.Write(const ABuffer; ACount: Integer): Longint;
begin
  Connection.WriteBuffer(ABuffer, ACount);
  Result := ACount;
end;

end.
