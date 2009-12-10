{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10229: IdIrcServer.pas 
{
{   Rev 1.0    2002.11.12 10:43:48 PM  czhower
}
unit IdIrcServer;

interface

{
2000-15-May:  Renamed events to Id Prefix
2000-22-Apr: J Peter Mugass
  -Ported to Indy
2000-13-Jan MTL
  -Moved to new Palette Scheme (Winshoes Servers)
1999-13-Apr
  -Final Version
Original Author: Ozz Nixon
  -RFC 1459 - Internet Relay Chat
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Const
   KnownCommands:Array [1..40] of string=
      (
       'ADMIN',    {Do not Localize}
       'AWAY',    {Do not Localize}
       'CONNECT',    {Do not Localize}
       'ERROR',    {Do not Localize}
       'INFO',    {Do not Localize}
       'INVITE',    {Do not Localize}
       'ISON',    {Do not Localize}
       'JOIN',    {Do not Localize}
       'KICK',    {Do not Localize}
       'KILL',    {Do not Localize}
       'LINKS',    {Do not Localize}
       'LIST',    {Do not Localize}
       'MODE',    {Do not Localize}
       'NAMES',    {Do not Localize}
       'NICK',    {Do not Localize}
       'NOTICE',    {Do not Localize}
       'OPER',    {Do not Localize}
       'PART',    {Do not Localize}
       'PASS',    {Do not Localize}
       'PING',    {Do not Localize}
       'PONG',    {Do not Localize}
       'PRIVMSG',    {Do not Localize}
       'QUIT',    {Do not Localize}
       'REHASH',    {Do not Localize}
       'RESTART',    {Do not Localize}
       'SERVER',    {Do not Localize}
       'SQUIT',    {Do not Localize}
       'STATS',    {Do not Localize}
       'SUMMON',    {Do not Localize}
       'TIME',    {Do not Localize}
       'TOPIC',    {Do not Localize}
       'TRACE',    {Do not Localize}
       'USER',    {Do not Localize}
       'USERHOST',    {Do not Localize}
       'USERS',    {Do not Localize}
       'VERSION',    {Do not Localize}
       'WALLOPS',    {Do not Localize}
       'WHO',    {Do not Localize}
       'WHOIS',    {Do not Localize}
       'WHOWAS'    {Do not Localize}
       );

Type
  TIdIrcGetEvent = procedure ( Thread: TIdPeerThread) of object;
  TIdIrcOtherEvent = procedure ( Thread: TIdPeerThread; Command, Parm : String) of object;
  TIdIrcOneParmEvent = procedure (Thread: TIdPeerThread; Parm : String) of object;
  TIdIrcTwoParmEvent = procedure (Thread: TIdPeerThread; Parm1, Parm2 : String) of object;
  TIdIrcThreeParmEvent = procedure (Thread: TIdPeerThread; Parm1, Parm2, Parm3 : String) of object;
  TIdIrcFiveParmEvent = procedure (Thread: TIdPeerThread; Parm1, Parm2, Parm3, Parm4, Parm5 : String) of object;
  TIdIrcUserEvent = procedure ( Thread: TIdPeerThread; UserName, HostName, ServerName, RealName : String) of object;
  TIdIrcServerEvent = procedure ( Thread: TIdPeerThread; ServerName, Hopcount, Info : String) of object;

  TIdIRCServer = class( TIdTCPServer )
  protected
    fOnCommandOther : TIdIrcOtherEvent;
    fOnCommandPass : TIdIrcOneParmEvent;
    fOnCommandNick : TIdIrcTwoParmEvent;
    fOnCommandUser : TIdIrcUserEvent;
    fOnCommandServer : TIdIrcServerEvent;
    fOnCommandOper : TIdIrcTwoParmEvent;
    fOnCommandQuit : TIdIrcOneParmEvent;
    fOnCommandSQuit : TIdIrcTwoParmEvent;
    fOnCommandJoin : TIdIrcTwoParmEvent;
    fOnCommandPart : TIdIrcOneParmEvent;
    fOnCommandMode : TIdIrcFiveParmEvent;
    fOnCommandTopic : TIdIrcTwoParmEvent;
    fOnCommandNames : TIdIrcOneParmEvent;
    fOnCommandList : TIdIrcTwoParmEvent;
    fOnCommandInvite : TIdIrcTwoParmEvent;
    fOnCommandKick : TIdIrcThreeParmEvent;
    fOnCommandVersion : TIdIrcOneParmEvent;
    fOnCommandStats : TIdIrcTwoParmEvent;
    fOnCommandLinks : TIdIrcTwoParmEvent;
    fOnCommandTime : TIdIrcOneParmEvent;
    fOnCommandConnect : TIdIrcThreeParmEvent;
    fOnCommandTrace : TIdIrcOneParmEvent;
    fOnCommandAdmin : TIdIrcOneParmEvent;
    fOnCommandInfo : TIdIrcOneParmEvent;
    fOnCommandPrivMsg : TIdIrcTwoParmEvent;
    fOnCommandNotice : TIdIrcTwoParmEvent;
    fOnCommandWho : TIdIrcTwoParmEvent;
    fOnCommandWhoIs : TIdIrcTwoParmEvent;
    fOnCommandWhoWas : TIdIrcThreeParmEvent;
    fOnCommandKill : TIdIrcTwoParmEvent;
    fOnCommandPing : TIdIrcTwoParmEvent;
    fOnCommandPong : TIdIrcTwoParmEvent;
    fOnCommandError : TIdIrcOneParmEvent;
    fOnCommandAway : TIdIrcOneParmEvent;
    fOnCommandRehash : TIdIrcGetEvent;
    fOnCommandRestart : TIdIrcGetEvent;
    fOnCommandSummon : TIdIrcTwoParmEvent;
    fOnCommandUsers : TIdIrcOneParmEvent;
    fOnCommandWallops : TIdIrcOneParmEvent;
    fOnCommandUserHost : TIdIrcOneParmEvent;
    fOnCommandIsOn : TIdIrcOneParmEvent;
    //
    function DoExecute( Thread : TIdPeerThread ): boolean; override;
  public
    constructor Create( AOwner : TComponent ); override;
  published
    property OnCommandPass : TIdIrcOneParmEvent read fOnCommandPass write fOnCommandPass;
    property OnCommandNick : TIdIrcTwoParmEvent read fOnCommandNick write fOnCommandNick;
    property OnCommandUser : TIdIrcUserEvent read fOnCommandUser write fOnCommandUser;
    property OnCommandServer : TIdIrcServerEvent read fOnCommandServer write fOnCommandServer;
    property OnCommandOper : TIdIrcTwoParmEvent read fOnCommandOper write fOnCommandOper;
    property OnCommandQuit : TIdIrcOneParmEvent read fOnCommandQuit write fOnCommandQuit;
    property OnCommandSQuit : TIdIrcTwoParmEvent read fOnCommandSQuit write fOnCommandSQuit;
    property OnCommandJoin : TIdIrcTwoParmEvent read fOnCommandJoin write fOnCommandJoin;
    property OnCommandPart : TIdIrcOneParmEvent read fOnCommandPart write fOnCommandPart;
    property OnCommandMode : TIdIrcFiveParmEvent read fOnCommandMode write fOnCommandMode;
    property OnCommandTopic : TIdIrcTwoParmEvent read fOnCommandTopic write fOnCommandTopic;
    property OnCommandNames : TIdIrcOneParmEvent read fOnCommandNames write fOnCommandNames;
    property OnCommandList : TIdIrcTwoParmEvent read fOnCommandList write fOnCommandList;
    property OnCommandInvite : TIdIrcTwoParmEvent read fOnCommandInvite write fOnCommandInvite;
    property OnCommandKick : TIdIrcThreeParmEvent read fOnCommandKick write fOnCommandKick;
    property OnCommandVersion : TIdIrcOneParmEvent read fOnCommandVersion write fOnCommandVersion;
    property OnCommandStats : TIdIrcTwoParmEvent read fOnCommandStats write fOnCommandStats;
    property OnCommandLinks : TIdIrcTwoParmEvent read fOnCommandLinks write fOnCommandLinks;
    property OnCommandTime : TIdIrcOneParmEvent read fOnCommandTime write fOnCommandTime;
    property OnCommandConnect : TIdIrcThreeParmEvent read fOnCommandConnect write fOnCommandConnect;
    property OnCommandTrace : TIdIrcOneParmEvent read fOnCommandTrace write fOnCommandTrace;
    property OnCommandAdmin : TIdIrcOneParmEvent read fOnCommandAdmin write fOnCommandAdmin;
    property OnCommandInfo : TIdIrcOneParmEvent read fOnCommandInfo write fOnCommandInfo;
    property OnCommandPrivMsg : TIdIrcTwoParmEvent read fOnCommandPrivMsg write fOnCommandPrivMsg;
    property OnCommandNotice : TIdIrcTwoParmEvent read fOnCommandNotice write fOnCommandNotice;
    property OnCommandWho : TIdIrcTwoParmEvent read fOnCommandWho write fOnCommandWho;
    property OnCommandWhoIs : TIdIrcTwoParmEvent read fOnCommandWhoIs write fOnCommandWhoIs;
    property OnCommandWhoWas : TIdIrcThreeParmEvent read fOnCommandWhoWas write fOnCommandWhoWas;
    property OnCommandKill : TIdIrcTwoParmEvent read fOnCommandKill write fOnCommandKill;
    property OnCommandPing : TIdIrcTwoParmEvent read fOnCommandPing write fOnCommandPing;
    property OnCommandPong : TIdIrcTwoParmEvent read fOnCommandPong write fOnCommandPong;
    property OnCommandError : TIdIrcOneParmEvent read fOnCommandError write fOnCommandError;
    property OnCommandAway : TIdIrcOneParmEvent read fOnCommandAway write fOnCommandAway;
    property OnCommandRehash : TIdIrcGetEvent read fOnCommandRehash write fOnCommandRehash;
    property OnCommandRestart : TIdIrcGetEvent read fOnCommandRestart write fOnCommandRestart;
    property OnCommandSummon : TIdIrcTwoParmEvent read fOnCommandSummon write fOnCommandSummon;
    property OnCommandUsers : TIdIrcOneParmEvent read fOnCommandUsers write fOnCommandUsers;
    property OnCommandWallops : TIdIrcOneParmEvent read fOnCommandWallops write fOnCommandWallops;
    property OnCommandUserHost : TIdIrcOneParmEvent read fOnCommandUserHost write fOnCommandUserHost;
    property OnCommandIsOn : TIdIrcOneParmEvent read fOnCommandIsOn write fOnCommandIsOn;
    property OnCommandOther : TIdIrcOtherEvent read fOnCommandOther write fOnCommandOther;
  end;

implementation

uses
  IdGlobal,
  IdResourceStrings,
  SysUtils;

constructor TIdIRCServer.Create ( AOwner : TComponent );
begin
  inherited;
  DefaultPort := IdPORT_IRC;
end;

function TIdIRCServer.DoExecute ( Thread : TIdPeerThread ) : boolean;
var
  s, sCmd, sCmd2, sCmd3, sCmd4 : String;

  procedure NotHandled;
  begin
    Thread.Connection.Writeln( '421 ' + RSCMDNotRecognized );    {Do not Localize}
  end;

begin
  result := true;
  while Thread.Connection.Connected do begin
    s := Thread.Connection.ReadLn;
    sCmd := Fetch ( s, ' ');    {Do not Localize}
    Case Succ ( PosInStrArray ( Uppercase ( sCmd ), KnownCommands ) ) of
      1 : {ADMIN}
          if assigned ( OnCommandAdmin ) then begin
            OnCommandAdmin ( Thread, S );
          end // if assigned ( OnCommandAdmin ) then
          else
            NotHandled;
      2 : {AWAY}
          if assigned ( OnCommandAway ) then begin
            OnCommandAway ( Thread, S );
          end // if assigned ( OnCommandAway ) then
          else
            NotHandled;
      3 : {CONNECT}
          if assigned(OnCommandConnect) then begin
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            sCmd3 :=  Fetch ( s, ' ' );    {Do not Localize}
            OnCommandConnect ( Thread, sCmd2, sCmd3, S );
          end  // if assigned(OnCommandConnect) then
          else
            NotHandled;
      4 : {ERROR}
          if assigned ( OnCommandError ) then begin
            OnCommandError ( Thread, S );
          end // if assigned ( OnCommandError ) then
          else
            NotHandled;
      5 : {INFO}
          if assigned ( OnCommandInfo ) then begin
            OnCommandInfo ( Thread, S );
          end  // if assigned ( OnCommandInfo ) then
          else
            NotHandled;
      6 : {INVITE}
          if assigned ( OnCommandInvite ) then begin
            sCmd2 := Fetch( s, ' ' );    {Do not Localize}
            OnCommandInvite ( Thread, sCmd2, S );
          end // if assigned ( OnCommandInvite ) then
          else
            NotHandled;
      7 : {ISON}
          if assigned ( OnCommandIsOn ) then begin
            OnCommandIsOn( Thread, S );
          end // if assigned ( OnCommandIsOn ) then
          else
            NotHandled;
      8 : {JOIN}
          if assigned( OnCommandJoin ) then begin
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            OnCommandJoin ( Thread, sCmd2, S );
          end // if assigned( OnCommandJoin ) then
          else
            NotHandled;
      9 : {KICK}
          if assigned( OnCommandKick ) then begin
            sCmd2 := Fetch ( s,' ' );    {Do not Localize}
            sCmd3 := Fetch ( s, ' ' );    {Do not Localize}
            OnCommandKick( Thread, sCmd2, sCmd3, S );
          end // if assigned( OnCommandKick ) then
          else
            NotHandled;
      10 : {KILL}
          if assigned ( OnCommandKill ) then begin
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            OnCommandKill ( Thread, sCmd2, S );
          end // if assigned ( OnCommandKill ) then
          else
            NotHandled;
      11 : {LINKS}
          if assigned ( OnCommandLinks ) then begin
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            OnCommandLinks ( Thread, sCmd2, S );
          end // if assigned ( OnCommandLinks ) then
          else
            NotHandled;
      12 : {LIST}
          if assigned ( OnCommandList ) then begin
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            OnCommandList ( Thread, sCmd2, S );
          end // if assigned ( OnCommandList ) then
          else
            NotHandled;
      13 : {MODE}
          if assigned ( OnCommandMode ) then begin
            sCmd := Fetch ( s, ' ' );    {Do not Localize}
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            sCmd3 := Fetch ( s, ' ' );    {Do not Localize}
            sCmd4 :=  Fetch ( s, ' ' );    {Do not Localize}
            OnCommandMode ( Thread, sCmd, sCmd2, sCmd3, sCmd4, S);
          end  // if assigned ( OnCommandMode ) then
          else
            NotHandled;
      14 : {NAMES}
          if assigned( OnCommandNames ) then begin
            OnCommandNames( Thread, S );
          end  // if assigned( OnCommandNames ) then
          else
            NotHandled;
      15 : {NICK}
          if assigned ( OnCommandNick ) then begin
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            OnCommandNick ( Thread, sCmd2, S );
          end  // if assigned ( OnCommandNick ) then
          else
            NotHandled;
      16 : {NOTICE}
          if assigned ( OnCommandNotice ) then begin
            sCmd2 := Fetch( s, ' ' );    {Do not Localize}
            OnCommandNotice ( Thread, sCmd2, S );
          end  // if assigned ( OnCommandNotice ) then
          else
            NotHandled;
      17 : {OPER}
          if assigned ( OnCommandOper ) then begin
            sCmd2 := Fetch( s, ' ' );    {Do not Localize}
            OnCommandOper ( Thread, sCmd2, S );
          end  // if assigned ( OnCommandOper ) then
          else
            NotHandled;
      18 : {PART}
          if assigned ( OnCommandPart ) then begin
            OnCommandPart ( Thread, S );
          end // if assigned ( OnCommandPart ) then
          else
            NotHandled;
      19 : {PASS}
           if assigned( OnCommandPass ) then begin
             OnCommandPass( Thread, S );
           end // if assigned( OnCommandPass ) then
           else
             NotHandled;
      20 : {PING}
           if assigned(OnCommandPing) then begin
             sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
             OnCommandPing ( Thread, sCmd2, S );
           end // if assigned(OnCommandPing) then
           else
             NotHandled;
      21 : {PONG}
           if assigned(OnCommandPong) then begin
             sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
             OnCommandPong ( Thread, sCmd2, S );
           end // if assigned ( OnCommandPong ) then
           else
             NotHandled;
      22 : {PRIVMSG}
          if assigned( OnCommandPrivMsg ) then begin
            sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
            OnCommandPrivMsg ( Thread, sCmd2, S );
          end // if assigned( OnCommandPrivMsg ) then
          else
            NotHandled;
      23 : {QUIT}
          if assigned ( OnCommandQuit ) then begin
            OnCommandQuit( Thread, s );
          end // if assigned ( OnCommandQuit ) then
          else
            NotHandled;
      24 : {REHASH}
          if assigned ( OnCommandRehash ) then begin
            OnCommandRehash ( Thread );
          end // if assigned ( OnCommandRehash ) then
          else
            NotHandled;
      25 : {RESTART}
          if assigned ( OnCommandRestart ) then begin
            OnCommandRestart ( Thread );
          end // if assigned ( OnCommandRestart ) then
          else
            NotHandled;
      26 : {SERVER}
         if assigned ( OnCommandServer ) then begin
           sCmd := Fetch ( s, ' ' );    {Do not Localize}
           sCmd2 := Fetch( s, ' ' );    {Do not Localize}
           OnCommandServer ( Thread, sCmd, sCmd2, S );
         end // if assigned ( OnCommandServer ) then
         else
           NotHandled;
      27 : {SQUIT}
         if assigned ( OnCommandSQuit ) then begin
           sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
           OnCommandSQuit ( Thread, sCmd2, S );
         end  // if assigned ( OnCommandSQuit ) then
         else
           NotHandled;
      28 : {STAT}
         if assigned ( OnCommandStats ) then begin
           sCmd2 := Fetch( s, ' ' );    {Do not Localize}
           OnCommandStats ( Thread, sCmd2, S );
         end  // if assigned ( OnCommandStats ) then
         else
           NotHandled;
      29 : {SUMMON}
         if assigned( OnCommandSummon ) then begin
           sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
           OnCommandSummon ( Thread, sCmd2, S );
         end // if assigned( OnCommandSummon ) then
         else
           NotHandled;
      30 : {TIME}
         if assigned ( OnCommandTime ) then begin
           OnCommandTime ( Thread, S );
         end  // if assigned ( OnCommandTime ) then
         else
           NotHandled;
      31 : {TOPIC}
         if assigned ( OnCommandTopic ) then begin
           sCmd2 := Fetch( s, ' ');    {Do not Localize}
           OnCommandTopic ( Thread, sCmd2, S );
         end  // if assigned ( OnCommandTopic ) then
         else
           NotHandled;
      32 : {TRACE}
         if assigned ( OnCommandTrace ) then begin
           OnCommandTrace ( Thread, S );
         end // if assigned ( OnCommandTrace ) then
         else
           NotHandled;
      33 : {USER}
         if assigned ( OnCommandUser ) then begin
           sCmd := Fetch( s, ' ' );    {Do not Localize}
           sCmd2 := Fetch( s, ' ' );    {Do not Localize}
           sCmd3 := Fetch( s, ' ' );    {Do not Localize}
           OnCommandUser ( Thread, sCmd, sCmd2, sCmd3, S);
         end  // if assigned ( OnCommandUser ) then
         else
           NotHandled;
      34 : {USERHOST}
         if assigned ( OnCommandUserHost ) then begin
           OnCommandUserHost ( Thread, S );
         end // if assigned ( OnCommandUserHost ) then
         else
           NotHandled;
      35 : {USERS}
         if assigned ( OnCommandUsers ) then begin
           OnCommandUsers( Thread, S );
         end // if assigned ( OnCommandUsers ) then
         else
           NotHandled;
      36 : {VERSION}
         if assigned ( OnCommandVersion ) then begin
           OnCommandVersion ( Thread, S );
         end // if assigned ( OnCommandVersion ) then
         else
           NotHandled;
      37 : {WALLOPS}
         if assigned ( OnCommandWallops ) then begin
           OnCommandWallops ( Thread, S );
         end // if assigned ( OnCommandWallops ) then
         else
           NotHandled;
      38 : {WHO}
         if assigned ( OnCommandWho ) then begin
           sCmd2 := Fetch( s, ' ' );    {Do not Localize}
           OnCommandWho( Thread, sCmd2, S );
         end  // if assigned ( OnCommandWho ) then
         else
           NotHandled;
      39 : {WHOIS}
         if assigned( OnCommandWhoIs ) then begin
           sCmd2 := Fetch ( s, ' ' );    {Do not Localize}
           OnCommandWhoIs( Thread, sCmd2, S );
         end  // if assigned ( OnCommandWhoIs ) then
         else
           NotHandled;
      40 : {WHOWAS}
         if assigned ( OnCommandWhoWas ) then begin
           sCmd2 := Fetch( s, ' ' );    {Do not Localize}
           sCmd3 := Fetch( s, ' ' );    {Do not Localize}
           OnCommandWhoWas ( Thread, sCmd2, sCmd3, S );
         end  //if assigned ( OnCommandWhoWas ) then
         else
           NotHandled;
    else begin
      if assigned ( OnCommandOther ) then
        OnCommandOther ( Thread, sCmd, S );
      end;
    end;
  end;
end;

end.
