{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10309: IdRexecServer.pas 
{
{   Rev 1.0    2002.11.12 10:50:36 PM  czhower
}
unit IdRexecServer;

{based on
  http://www.winsock.com/hypermail/winsock2/2235.html
 http://www.private.org.il/mini-tcpip.faq.html}
{ 2001, Feb  17 - J. Peter Mugaas
    moved much of the code into IdRemoteCMDServer so it can be
    reused in IdRSHServer
  2001, Feb 15 - J. Peter Mugaas
    made methods for error and sucess command results
  2001, Feb 14 - J. Peter Mugaas
    started this unit

This is based on the IdRexec.pas unit and
programming comments at http://www.abandoned.org/nemon/rexeclib.py}

interface

uses
  Classes,
  IdAssignedNumbers, IdRemoteCMDServer, IdTCPClient, IdTCPServer;

type
  TIdRexecCommandEvent = procedure (AThread: TIdPeerThread;
   AStdError : TIdTCPClient; AUserName, APassword, ACommand : String) of object;

  TIdRexecServer = class(TIdRemoteCMDServer)
  protected
    FOnCommand : TIdRexecCommandEvent;
    procedure DoCMD(AThread: TIdPeerThread;
     AStdError : TIdTCPClient; AParam1, AParam2, ACommand : String); override;
  public
    constructor Create(AOwner : TComponent); override;
  published
    property OnCommand : TIdRexecCommandEvent read FOnCommand write FOnCommand;
    property DefaultPort default Id_PORT_exec;
  end;

implementation

{ TIdRexecServer }

constructor TIdRexecServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := Id_PORT_exec;
  {This variable is defined in the TIdRemoteCMDServer component.  We do not
  use it here because Rexec does not require it.  However, we have to set this to
  to false to disable forcing ports to be in a specific range. The variable in is the
  anscestor because only accepting clients in a specific range would require a change
  to the base component.}
  FForcePortsInRange := False;
  FStdErrorPortsInRange := False;
end;

procedure TIdRexecServer.DoCMD(AThread: TIdPeerThread;
  AStdError: TIdTCPClient; AParam1, AParam2, ACommand: String);
begin
  if Assigned(FOnCommand) then begin
    FOnCommand(AThread,AStdError,AParam1,AParam2,ACommand);
  end;
end;

end.
