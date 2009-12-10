{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10313: IdRSH.pas 
{
{   Rev 1.0    2002.11.12 10:50:50 PM  czhower
}
unit IdRSH;

(*******************************************************}
-2001.02.15 - J. Peter Mugaas
              Started this unit
{                                                       }
{       Indy Execute Client TIdRSH                      }
{                                                       }
{       Copyright (C) 2001 Indy Pit Crew                }
{       Original author J. Peter Mugaas                 }
{       2001-February-15                                }
{                                                       }
{*******************************************************)

interface

uses
  Classes,
  IdAssignedNumbers,
  IdRemoteCMDClient,
  IdTCPClient,
  SyncObjs;

type
  TIdRSH = class(TIdRemoteCMDClient)
  protected
    FClientUserName : String;
    FHostUserName : String;
  public
    Constructor Create(AOwner: TComponent); override;
    Function Execute(ACommand: String): String; override;
  published
    property ClientUserName : String read FClientUserName write FClientUserName;
    property HostUserName : String read FHostUserName write FHostUserName;
    property Port default IdPORT_cmd;
    property UseReservedPorts: Boolean read FUseReservedPorts write FUseReservedPorts
     default IDRemoteFixPort;
  end;

implementation

uses
  IdComponent,
  IdGlobal,
  IdSimpleServer,
  IdTCPConnection,
  IdThread,
  SysUtils;

{ TIdRSH }

constructor TIdRSH.Create(AOwner: TComponent);
begin
  inherited;
  Port := IdPORT_cmd;
  FClientUserName := '';    {Do not Localize}
  FHostUserName := '';    {Do not Localize}
end;

function TIdRSH.Execute(ACommand: String): String;
begin
  Result := InternalExec(FClientUserName,FHostUserName,ACommand);
end;

end.
