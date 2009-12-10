{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10315: IdRSHServer.pas 
{
{   Rev 1.0    2002.11.12 10:50:56 PM  czhower
}
unit IdRSHServer;

{based on
 http://www.private.org.il/mini-tcpip.faq.html}
{ 2001, Feb  17 - J. Peter Mugaas
    based this unit on the simplied IdRexec unit with extremely
    minor modifications (the parameters for our event had to be modified
    to better represent what they are in this protocol.  The only difference
    between this protocol and Rexec is that the server handles authoriation differently
    and the port is different.  In RSH, authentication is usually done by refusing connections
    from ports which are NOT reserved, and from .rhosts files in Unix.

WARNING:
    RSH should ONLY be considered for computer systems behind a firewall as there
    are no passwords and it is easy to falsify user names (you even have to evesdrop
    on network traffic to do it. Even then, you should consider other protocols.

    You assume any and all risks involved with RSH.

    !!!YOU HAVE BEEN WANRED!!!

    The only reason we provide this component is to complement the RSH client
    and it does have one merit (CVS is partly based on it).  I personally have
    agonized over writing this component at all due to these risks.

}
interface

uses
  Classes,
  IdAssignedNumbers, IdRemoteCMDServer, IdTCPClient, IdTCPServer;

Const RSH_FORCEPORTSINRANGE = True;
type
  TIdRSHCommandEvent = procedure (AThread: TIdPeerThread;
   AStdError : TIdTCPClient; AClientUserName, AHostUserName, ACommand : String) of object;

  TIdRSHServer = class(TIdRemoteCMDServer)
  protected
    FOnCommand : TIdRSHCommandEvent;
    //
    procedure DoCMD(AThread: TIdPeerThread;
     AStdError : TIdTCPClient; AParam1, AParam2, ACommand : String); override;
  public
    constructor Create(AOwner : TComponent); override;
  published
    property OnCommand : TIdRSHCommandEvent read FOnCommand write FOnCommand;
    property DefaultPort default IdPORT_cmd;
    property ForcePortsInRange : Boolean read FForcePortsInRange write FForcePortsInRange default RSH_FORCEPORTSINRANGE;
  end;

implementation

{ TIdRSHServer }

constructor TIdRSHServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_cmd;
  FForcePortsInRange := RSH_FORCEPORTSINRANGE;
  FStdErrorPortsInRange := True;
end;

procedure TIdRSHServer.DoCMD(AThread: TIdPeerThread;
  AStdError: TIdTCPClient; AParam1, AParam2, ACommand: String);
begin
  if Assigned(FOnCommand) then begin
    FOnCommand(AThread,AStdError,AParam1,AParam2,ACommand);
  end;
end;

end.
