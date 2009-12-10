{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10307: IdRexec.pas 
{
{   Rev 1.0    2002.11.12 10:50:30 PM  czhower
}
unit IdRexec;

(*******************************************************}
-2001.05.18 - J. Peter Mugaas
              I removed the property for forcing client ports
              into a specific range.  This was not necessary
              for Rexec.  It is required for the TIdRSH Component
-2001.02.15 - J. Peter Mugaas
              I moved most of the Rexec code to
              TIdRemoteCMDClient and TIdRexec now inherits
              from that class.  This change was necessary to
              reduce duplicate code with a new addition, IdRSH.
-2001.02.14 - J. Peter Mugaas
              Made it more complient with Rexec servers
              and handled the #0 or error indicator
-2001.02.13 - Modified by Kudzu
-2000.10.24 - Original Author: Laurence LIew
{                                                       }
{       Indy Rexec Client TIdRexec                      }
{                                                       }
{       Copyright (C) 2001 Winshoes Working Group       }
{       Original author Laurence LIew                   }
{       2000-October-24                                 }
{                                                       }
{*******************************************************)

interface

uses
  Classes,
  IdAssignedNumbers,
  IdRemoteCMDClient,
  IdTCPClient;

type
  TIdRexec = class(TIdRemoteCMDClient)
  public
    constructor Create(AOwner: TComponent); override;
    Function Execute(ACommand: String): String; override;
  published
    property Username;
    property Password;
    property Port default Id_PORT_exec;
  end;

implementation

uses
  IdComponent,
  IdGlobal,
  IdSimpleServer,
  IdTCPConnection,
  IdThread,
  SysUtils;

constructor TIdRexec.Create(AOwner: TComponent);
begin
  inherited;
  Port := Id_PORT_exec;
  {Rexec does not require ports to be in a specific range}
  FUseReservedPorts := False;
end;

function TIdRexec.Execute(ACommand: String): String;
begin
  Result := InternalExec(UserName,Password,ACommand);
end;

end.
