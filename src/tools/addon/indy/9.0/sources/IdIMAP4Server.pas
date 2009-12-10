{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10207: IdIMAP4Server.pas 
{
{   Rev 1.0    2002.11.12 10:42:10 PM  czhower
}
Unit IdIMAP4Server;

Interface
{
2002-Apr-21 - J. Berg
  -use fetch()
2000-May-18 - J. Peter Mugaas
  -Ported to Indy
2000-Jan-13 - MTL
  -Moved to new Palette Scheme (Winshoes Servers)
1999-Aug-26 - Ray Malone
  -Started unit
}

Uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Const
  IMAPCommands : Array [1..25] Of String =
  ({ Client Commands - Any State}
   'CAPABILITY',    {Do not Localize}
   'NOOP',    {Do not Localize}
   'LOGOUT',    {Do not Localize}
   { Client Commands - Non Authenticated State}
   'AUTHENTICATE',    {Do not Localize}
   'LOGIN',    {Do not Localize}
   { Client Commands - Authenticated State}
   'SELECT',    {Do not Localize}
   'EXAMINE',    {Do not Localize}
   'CREATE',    {Do not Localize}
   'DELETE',    {Do not Localize}
   'RENAME',    {Do not Localize}
   'SUBSCRIBE',    {Do not Localize}
   'UNSUBSCRIBE',    {Do not Localize}
   'LIST',    {Do not Localize}
   'LSUB',    {Do not Localize}
   'STATUS',    {Do not Localize}
   'APPEND',    {Do not Localize}
   { Client Commands - Selected State}
   'CHECK',    {Do not Localize}
   'CLOSE',    {Do not Localize}
   'EXPUNGE',    {Do not Localize}
   'SEARCH',    {Do not Localize}
   'FETCH',    {Do not Localize}
   'STORE',    {Do not Localize}
   'COPY',    {Do not Localize}
   'UID',    {Do not Localize}
   { Client Commands - Experimental/ Expansion}
   'X');    {Do not Localize}


Type
  TCommandEvent = procedure (Thread : TIdPeerThread; const Tag, CmdStr: String;
   var Handled: Boolean) of Object;

  TIdIMAP4Server = class ( TIdTCPServer )
  Protected
    fOnCommandCAPABILITY : TCommandEvent;
    fONCommandNOOP: TCommandEvent;
    fONCommandLOGOUT: TCommandEvent;
    fONCommandAUTHENTICATE: TCommandEvent;
    fONCommandLOGIN: TCommandEvent;
    fONCommandSELECT : TCommandEvent;
    fONCommandEXAMINE : TCommandEvent;
    fONCommandCREATE : TCommandEvent;
    fONCommandDELETE : TCommandEvent;
    fONCommandRENAME : TCommandEvent;
    fONCommandSUBSCRIBE : TCommandEvent;
    fONCommandUNSUBSCRIBE : TCommandEvent;
    fONCommandLIST : TCommandEvent;
    fONCommandLSUB : TCommandEvent;
    fONCommandSTATUS : TCommandEvent;
    fONCommandAPPEND : TCommandEvent;
    fONCommandCHECK : TCommandEvent;
    fONCommandCLOSE : TCommandEvent;
    fONCommandEXPUNGE : TCommandEvent;
    fONCommandSEARCH : TCommandEvent;
    fONCommandFETCH : TCommandEvent;
    fONCommandSTORE : TCommandEvent;
    fONCommandCOPY : TCommandEvent;
    fONCommandUID : TCommandEvent;
    fONCommandX : TCommandEvent;
    fOnCommandError : TCommandEvent;
    procedure DoCommandCAPABILITY ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    procedure DoCommandNOOP(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    procedure DoCommandLOGOUT ( Thread : TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandAUTHENTICATE ( Thread : TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandLOGIN ( Thread : TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandSELECT ( Thread : TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandEXAMINE ( Thread : TIdPeerThread; const Tag, CmdStr :String; var Handled : Boolean );
    Procedure DoCommandCREATE ( Thread : TIdPeerThread; const Tag, CmdStr :String; var Handled : Boolean );
    Procedure DoCommandDELETE ( Thread : TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandRENAME ( Thread : TIdPeerThread; const Tag, CmdStr :String; var Handled : Boolean);
    Procedure DoCommandSUBSCRIBE ( Thread : TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandUNSUBSCRIBE ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandLIST(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandLSUB(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandSTATUS(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandAPPEND(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandCHECK(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandCLOSE(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandEXPUNGE ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandSEARCH ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandFETCH ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandSTORE ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandCOPY ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandUID ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandX ( Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Procedure DoCommandError(Thread: TIdPeerThread; const Tag, CmdStr : String; var Handled : Boolean );
    Function DoExecute(Thread: TIdPeerThread): Boolean; override;
  public
    Constructor Create(AOwner: TComponent); Override;
  published
    Property ONCommandCAPABILITY : TCommandEvent Read fOnCommandCAPABILITY write fOnCommandCAPABILITY;
    Property ONCommandNOOP : TCommandEvent Read fONCommandNOOP write fONCommandNOOP;
    Property ONCommandLOGOUT : TCommandEvent Read fONCommandLOGOUT write fONCommandLOGOUT;
    Property ONCommandAUTHENTICATE : TCommandEvent Read fONCommandAUTHENTICATE write fONCommandAUTHENTICATE;
    Property ONCommandLOGIN : TCommandEvent Read fONCommandLOGIN write fONCommandLOGIN;
    Property ONCommandSELECT : TCommandEvent Read fONCommandSELECT write fONCommandSELECT;
    Property OnCommandEXAMINE :TCommandEvent Read fOnCommandEXAMINE write fOnCommandEXAMINE;
    property ONCommandCREATE  :  TCommandEvent Read fONCommandCREATE write fONCommandCREATE;
    property ONCommandDELETE  :  TCommandEvent Read fONCommandDELETE write fONCommandDELETE;
    property OnCommandRENAME : TCommandEvent Read fOnCommandRENAME write fOnCommandRENAME;
    property ONCommandSUBSCRIBE  :  TCommandEvent read fONCommandSUBSCRIBE write fONCommandSUBSCRIBE;
    property ONCommandUNSUBSCRIBE  :  TCommandEvent read fONCommandUNSUBSCRIBE write fONCommandUNSUBSCRIBE;
    property ONCommandLIST  :  TCommandEvent read fONCommandLIST write fONCommandLIST;
    property OnCommandLSUB : TCommandEvent read fOnCommandLSUB write fOnCommandLSUB;
    property ONCommandSTATUS  :  TCommandEvent read fONCommandSTATUS write fONCommandSTATUS;
    property OnCommandAPPEND : TCommandEvent read fOnCommandAPPEND write fOnCommandAPPEND;
    property ONCommandCHECK  :  TCommandEvent read fONCommandCHECK write fONCommandCHECK;
    property OnCommandCLOSE : TCommandEvent read fOnCommandCLOSE write fOnCommandCLOSE;
    property ONCommandEXPUNGE  :  TCommandEvent read fONCommandEXPUNGE write fONCommandEXPUNGE;
    property OnCommandSEARCH : TCommandEvent read fOnCommandSEARCH write fOnCommandSEARCH;
    property ONCommandFETCH  :  TCommandEvent read fONCommandFETCH write fONCommandFETCH;
    property OnCommandSTORE : TCommandEvent read fOnCommandSTORE write fOnCommandSTORE;
    property OnCommandCOPY : TCommandEvent read fOnCommandCOPY write fOnCommandCOPY;
    property ONCommandUID  :  TCommandEvent read fONCommandUID write fONCommandUID;
    property OnCommandX : TCommandEvent read fOnCommandX write fOnCommandX;
    property OnCommandError : TCommandEvent read fOnCommandError write fOnCommandError;
  end;

Implementation

Uses
  IdGlobal,
  SysUtils;

//--------------------Start of  TIdIMAP4Server Code ---------------------
//                     Started August 26, 1999
//------------------------------------------------------------------------------
Const
   cCAPABILITY   =  1;
   cNOOP         =  2;
   cLOGOUT       =  3;
   cAUTHENTICATE =  4;
   cLOGIN        =  5;
   cSELECT       =  6;
   cEXAMINE      =  7;
   cCREATE       =  8;
   cDELETE       =  9;
   cRENAME       = 10;
   cSUBSCRIBE    = 11;
   cUNSUBSCRIBE  = 12;
   cLIST         = 13;
   cLSUB         = 14;
   cSTATUS       = 15;
   cAPPEND       = 16;
   cCHECK        = 17;
   cCLOSE        = 18;
   cEXPUNGE      = 19;
   cSEARCH       = 20;
   cFETCH        = 21;
   cSTORE        = 22;
   cCOPY         = 23;
   cUID          = 24;
   cXCmd         = 25;

constructor TIdIMAP4Server.Create(AOwner: TComponent);
begin
  Inherited;
  DefaultPort := IdPORT_IMAP4;
end;

function TIdIMAP4Server.DoExecute(Thread: TIdPeerThread): Boolean;
var
  RcvdStr,
  ArgStr,
  sTag,
  sCmd : String;
  cmdNum : Integer;
  Handled : Boolean;

begin
  result := true;
  while Thread.Connection.Connected Do
  begin
    Handled := False;
    RcvdStr := Thread.Connection.ReadLn;
    ArgStr := RcvdStr;
    sTag := UpperCase ( Fetch ( ArgStr, CHAR32 ) );
    sCmd := UpperCase ( Fetch ( ArgStr, CHAR32 ) );
    CmdNum := Succ ( PosInStrArray ( Uppercase ( sCmd ), IMAPCommands ) );
    case CmdNum Of
      cCAPABILITY   : DoCommandCAPABILITY ( Thread, sTag, ArgStr, Handled );
      cNOOP         : DoCommandNOOP ( Thread, sTag, ArgStr, Handled );
      cLOGOUT       : DoCommandLOGOUT ( Thread, sTag, ArgStr, Handled );
      cAUTHENTICATE : DoCommandAUTHENTICATE ( Thread, sTag, ArgStr, Handled );
      cLOGIN        : DoCommandLOGIN ( Thread, sTag, ArgStr, Handled );
      cSELECT       : DoCommandSELECT ( Thread, sTag, ArgStr, Handled );
      cEXAMINE      : DoCommandEXAMINE ( Thread, sTag, ArgStr, Handled );
      cCREATE       : DoCommandCREATE ( Thread, sTag, ArgStr, Handled );
      cDELETE       : DoCommandDELETE ( Thread, sTag, ArgStr, Handled );
      cRENAME       : DoCommandRENAME ( Thread, sTag, ArgStr, Handled );
      cSUBSCRIBE    : DoCommandSUBSCRIBE ( Thread, sTag, ArgStr, Handled );
      cUNSUBSCRIBE  : DoCommandUNSUBSCRIBE ( Thread, sTag, ArgStr, Handled );
      cLIST         : DoCommandLIST ( Thread, sTag, ArgStr, Handled );
      cLSUB         : DoCommandLSUB ( Thread, sTag, ArgStr, Handled );
      cSTATUS       : DoCommandSTATUS ( Thread, sTag, ArgStr, Handled );
      cAPPEND       : DoCommandAPPEND ( Thread, sTag, ArgStr, Handled );
      cCHECK        : DoCommandCHECK ( Thread, sTag, ArgStr, Handled );
      cCLOSE        : DoCommandCLOSE ( Thread, sTag, ArgStr, Handled );
      cEXPUNGE      : DoCommandEXPUNGE ( Thread, sTag, ArgStr, Handled);
      cSEARCH       : DoCommandSEARCH ( Thread, sTag, ArgStr, Handled );
      cFETCH        : DoCommandFETCH ( Thread, sTag, ArgStr, Handled );
      cSTORE        : DoCommandSTORE ( Thread, sTag, ArgStr, Handled );
      cCOPY         : DoCommandCOPY ( Thread, sTag, ArgStr, Handled );
      cUID          : DoCommandUID ( Thread, sTag, ArgStr, Handled );
    else
      begin
        if ( Length ( SCmd ) > 0 ) and ( UpCase ( SCmd[1] ) = 'X' ) then    {Do not Localize}
        begin
          DoCommandX ( Thread, sTag, ArgStr, Handled );
        end //if ( Length ( SCmd ) > 0) and ( UpCase ( SCmd[1] ) = 'X' ) then    {Do not Localize}
        else
        begin
          DoCommandError ( Thread, sTag, ArgStr, Handled );
        end; // else ..if ( Length ( SCmd ) > 0) and ( UpCase ( SCmd[1] ) = 'X' ) then    {Do not Localize}
      end; // else .. case
    end; {Case}
  end; {while}
end;                            { doExecute }

procedure TIdIMAP4Server.DoCommandCapability(Thread: TIdPeerThread; Const Tag, CmdStr :String;
                               Var Handled :Boolean);
begin
  if Assigned ( fOnCommandCAPABILITY ) then
  begin
    OnCommandCAPABILITY ( Thread, Tag, CmdStr, Handled );
  end; //if Assigned ( fOnCommandCAPABILITY ) then
end;

procedure TIdIMAP4Server.DoCommandNOOP ( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandNOOP ) then
  begin
    OnCommandNOOP ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandNOOP ) then
end;

procedure TIdIMAP4Server.DoCommandLOGOUT( Thread : TIdPeerThread;
  const Tag,CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandLOGOUT ) then
  begin
    OnCommandLOGOUT ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandLOGOUT ) then
end;

procedure TIdIMAP4Server.DoCommandAUTHENTICATE ( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandAUTHENTICATE ) then
  begin
    OnCommandAUTHENTICATE(Thread,Tag, CmdStr,Handled);
  end; // if Assigned ( fONCommandAUTHENTICATE ) then
end;

procedure TIdIMAP4Server.DoCommandLOGIN ( Thread : TIdPeerThread;
  const Tag,CmdStr :String; var Handled :Boolean );
begin
  if Assigned ( fONCommandLOGIN ) then
  begin
    OnCommandLOGIN ( Thread,Tag, CmdStr, Handled );
  end; //if Assigned ( fONCommandLOGIN ) then
end;

procedure TIdIMAP4Server.DoCommandSELECT(Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandSELECT ) then
  begin
    OnCommandSELECT ( Thread, Tag, CmdStr, Handled );
  end; //if Assigned ( fONCommandSELECT ) then
end;

procedure TIdIMAP4Server.DoCommandEXAMINE(Thread : TIdPeerThread;
  const Tag, CmdStr :String; var Handled : Boolean);
begin
  if Assigned ( fONCommandEXAMINE ) then
  begin
     OnCommandEXAMINE ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandEXAMINE ) then
end;

procedure TIdIMAP4Server.DoCommandCREATE ( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean);
begin
  if Assigned ( fONCommandCREATE ) then
  begin
    OnCommandCREATE( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandCREATE ) then
end;

procedure TIdIMAP4Server.DoCommandDELETE ( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean );
begin
  if Assigned ( fONCommandDELETE ) then
  begin
    OnCommandDELETE ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandDELETE ) then
end;

procedure TIdIMAP4Server.DoCommandRENAME ( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandRENAME ) then
  begin
    OnCommandRENAME( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandRENAME ) then
end;

procedure TIdIMAP4Server.DoCommandSUBSCRIBE( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandSUBSCRIBE ) then
  begin
    OnCommandSUBSCRIBE ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandSUBSCRIBE ) then
end;

procedure TIdIMAP4Server.DoCommandUNSUBSCRIBE( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandUNSUBSCRIBE ) then
  begin
    OnCommandUNSUBSCRIBE(Thread,Tag,CmdStr,Handled);
  end; // if Assigned ( fONCommandUNSUBSCRIBE ) then
end;

procedure TIdIMAP4Server.DoCommandLIST(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean);
begin
  if Assigned ( fONCommandLIST ) then
  begin
    OnCommandLIST(Thread,Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandLIST ) then
end;

procedure TIdIMAP4Server.DoCommandLSUB ( Thread: TIdPeerThread;
  const Tag, CmdStr :String; var Handled : Boolean );
begin
  if Assigned ( fONCommandLSUB ) then
  begin
    OnCommandLSUB ( Thread, Tag, CmdStr, Handled );
  end;  //if Assigned ( fONCommandLSUB ) then
end;

procedure TIdIMAP4Server.DoCommandSTATUS( Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean);
begin
  if Assigned ( fONCommandSTATUS ) then
  begin
    OnCommandSTATUS ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandSTATUS ) then
end;

procedure TIdIMAP4Server.DoCommandAPPEND( Thread : TIdPeerThread;
  const Tag, CmdStr : String; var Handled : Boolean );
begin
  if Assigned ( fONCommandAPPEND ) then
  begin
    OnCommandAPPEND ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned(fONCommandAPPEND) then
end;

procedure TIdIMAP4Server.DoCommandCHECK(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean);
begin
  if Assigned ( fONCommandCHECK ) then
  begin
    OnCommandCHECK ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned(fONCommandCHECK) then
end;

procedure TIdIMAP4Server.DoCommandCLOSE(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean );
begin
  if Assigned ( fONCommandCLOSE ) then
  begin
    OnCommandCLOSE ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandCLOSE ) then
end;

procedure TIdIMAP4Server.DoCommandEXPUNGE(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean);
begin
  if Assigned ( fONCommandEXPUNGE ) then
  begin
    OnCommandEXPUNGE ( Thread, Tag, CmdStr, Handled  );
  end; //if Assigned ( fONCommandEXPUNGE ) then
end;

procedure TIdIMAP4Server.DoCommandSEARCH(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean );
begin
  if Assigned ( fONCommandSEARCH ) then
  begin
    OnCommandSEARCH ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandSEARCH ) then
end;

procedure TIdIMAP4Server.DoCommandFETCH(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean);
begin
  if Assigned ( fONCommandFETCH ) then
  begin
    OnCommandFETCH ( Thread,Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandFETCH ) then
end;

procedure TIdIMAP4Server.DoCommandSTORE(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean);
begin
  if Assigned ( fONCommandSTORE ) then
  begin
    OnCommandSTORE ( Thread, Tag, CmdStr, Handled );
  end; //if Assigned ( fONCommandSTORE ) then
end;

procedure TIdIMAP4Server.DoCommandCOPY(Thread: TIdPeerThread;
  const Tag, CmdStr : String; var Handled :Boolean);
begin
  if Assigned ( fONCommandCOPY ) then
  begin
    OnCommandCOPY ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandCOPY ) then
end;

procedure TIdIMAP4Server.DoCommandUID(Thread: TIdPeerThread; const Tag, CmdStr :String;
  var Handled : Boolean );
begin
  if Assigned ( fONCommandUID ) then
  begin
    OnCommandUID ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandUID ) then
end;

procedure TIdIMAP4Server.DoCommandX(Thread: TIdPeerThread; const Tag, CmdStr :String;
  var Handled : Boolean);
begin
  if Assigned ( fONCommandX ) then
  begin
    OnCommandX ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandX ) then
end;

procedure TIdIMAP4Server.DoCommandError(Thread: TIdPeerThread; const Tag, CmdStr :String;
  var Handled : Boolean );
begin
  if Assigned ( fONCommandError ) then
  begin
    OnCommandError ( Thread, Tag, CmdStr, Handled );
  end; // if Assigned ( fONCommandError ) then
end;
//------------------------------------------------------------------------------
//                  End of  TIdIMAP4Server Code
//                     Started August 26, 1999
//------------------------------------------------------------------------------

End.
