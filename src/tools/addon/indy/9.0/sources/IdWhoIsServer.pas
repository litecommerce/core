{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10423: IdWhoIsServer.pas 
{
{   Rev 1.0    2002.11.12 11:00:14 PM  czhower
}
unit IdWhoIsServer;

{
 2000-Apr-19 Hadi Hariri
  Converted to Indy

 13-JAN-2000 MTL: Moved to new Palette Scheme (Winshoes Servers)

 5.13.99 Final Version
       ?         [responds with the following]
    Please enter a name or a NIC handle, such as "Smith" or "SRI-NIC".
    Starting with a period forces a name-only search; starting with
    exclamation point forces handle-only.  Examples:
       Smith     [looks for name or handle SMITH]
       !SRI-NIC  [looks for handle SRI-NIC only]
       .Smith, John
                 [looks for name JOHN SMITH only]

    Adding "..." to the argument will match anything from that point,
    e.g. "ZU..." will match ZUL, ZUM, etc.

    To search for mailboxes, use one of these forms:

       Smith@    [looks for mailboxes with username SMITH]
       @Host     [looks for mailboxes on HOST]
       Smith@Host

 Orig Author: Ozz Nixon (RFC 954)
}
interface

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

type
  TGetEvent = procedure(AThread: TIdPeerThread; ALookup: string) of object;

  TIdWhoIsServer = class(TIdTCPserver)
  protected
    FOnCommandLookup: TGetEvent;
    //
    function DoExecute(AThread: TIdPeerThread): boolean; override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    property OnCommandLookup: TGetEvent read FOnCommandLookup write FOnCommandLookup;
    property DefaultPort default IdPORT_WHOIS;
  end;

implementation

{ TIdWhoIsServer }

constructor TIdWhoIsServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_WHOIS;
end;

function TIdWhoIsServer.DoExecute(AThread: TIdPeerThread): boolean;
var
  LRequest: string;
begin
  Result := True;
  with AThread.Connection do begin
    // Get the domain name the client is inquiring about
    LRequest := ReadLn;
    if Assigned(OnCommandLookup) then begin
      OnCommandLookup(AThread, LRequest);
    end;
    Disconnect;
  end;
end;

end.
