{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10145: IdEcho.pas 
{
{   Rev 1.0    2002.11.12 10:37:00 PM  czhower
}
unit IdEcho;
{*******************************************************}
{                                                       }
{       Indy Echo Client TIdEcho                        }
{                                                       }
{       Copyright (C) 2000 Winshoes Working Group       }
{       Original author J. Peter Mugaas                 }
{       2000-April-24                                   }
{                                                       }
{*******************************************************}

interface

uses
  Classes,
  IdAssignedNumbers,
  IdTCPClient;

type
  TIdEcho = class(TIdTCPClient)
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

uses
  IdComponent,
  IdGlobal,
  IdTCPConnection,
  SysUtils;

{ TIdEcho }

constructor TIdEcho.Create(AOwner: TComponent);
begin
  inherited;
  Port := IdPORT_ECHO;
end;

function TIdEcho.Echo(AText: String): String;
var
  StartTime: Cardinal;
begin
  {Send time monitoring}
  BeginWork(wmWrite, Length(AText)+2);
  try
    StartTime := IdGlobal.GetTickCount;
    Write(AText);
  finally
    EndWork(wmWrite);
  end;
  {Receive time monitoring}
  BeginWork(wmRead);
  try
    Result := CurrentReadBuffer;
    {This is just in case the TickCount rolled back to zero}
    FEchoTime :=  GetTickDiff(StartTime,IdGlobal.GetTickCount);

  finally
    EndWork(wmRead);
  end;
end;

end.
