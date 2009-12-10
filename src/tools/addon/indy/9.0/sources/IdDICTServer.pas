{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10129: IdDICTServer.pas 
{
{   Rev 1.0    2002.11.12 10:35:42 PM  czhower
}
unit IdDICTServer;

interface

{
2000-15-May: J. Peter Mugaas - renamed events to have Id prefix
2000-22-Apr: J. Peter Mugaas
  Ported to Indy
2000-23-JanL MTL Moved to new Palette Scheme
1999-23-Apr: Final Version
Original Author: Ozz Nixon
  RFC 2229 - Dictionary Protocol (Structure).
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Const
   KnownCommands:Array [1..10] of string=
      (
       'AUTH',       {Do not Localize}
       'CLIENT',     {Do not Localize}
       'DEFINE',     {Do not Localize}
       'HELP',       {Do not Localize}
       'MATCH',      {Do not Localize}
       'OPTION',     {Do not Localize}
       'QUIT',       {Do not Localize}
       'SASLAUTH',   {Do not Localize}
       'SHOW',       {Do not Localize}
       'STATUS'      {Do not Localize}
       );

Type
  TIdDICTGetEvent = procedure ( Thread: TIdPeerThread) of object;
  TIdDICTOtherEvent = procedure ( Thread: TIdPeerThread; Command, Parm:String ) of object;
  TIdDICTDefineEvent = procedure ( Thread: TIdPeerThread; Database, WordToFind : String ) of object;
  TIdDICTMatchEvent = procedure ( Thread: TIdPeerThread; Database, Strategy,WordToFind : String ) of object;
  TIdDICTShowEvent = procedure ( Thread: TIdPeerThread; Command : String ) of object;
  TIdDICTAuthEvent = procedure ( Thread: TIdPeerThread; Username, authstring : String ) of object;

  TIdDICTServer = class(TIdTCPServer)
  protected
    fOnCommandHELP:TIdDICTGetEvent;
    fOnCommandDEFINE:TIdDICTDefineEvent;
    fOnCommandMATCH:TIdDICTMatchEvent;
    fOnCommandQUIT:TIdDICTGetEvent;
    fOnCommandSHOW:TIdDICTShowEvent;
    fOnCommandAUTH, fOnCommandSASLAuth:TIdDICTAuthEvent;
    fOnCommandOption:TIdDICTOtherEvent;
    fOnCommandSTAT:TIdDICTGetEvent;
    fOnCommandCLIENT:TIdDICTShowEvent;
    fOnCommandOther:TIdDICTOtherEvent;
    //
    function DoExecute(Thread: TIdPeerThread ): boolean; override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    property DefaultPort default IdPORT_DICT;
    //
    property OnCommandHelp: TIdDICTGetEvent read fOnCommandHelp write fOnCommandHelp;
    property OnCommandDefine: TIdDICTDefineEvent read fOnCommandDefine write fOnCommandDefine;
    property OnCommandMatch: TIdDICTMatchEvent read fOnCommandMatch write fOnCommandMatch;
    property OnCommandQuit: TIdDICTGetEvent read fOnCommandQuit write fOnCommandQuit;
    property OnCommandShow: TIdDICTShowEvent read fOnCommandShow write fOnCommandShow;
    property OnCommandAuth: TIdDICTAuthEvent read fOnCommandAuth write fOnCommandAuth;
    property OnCommandSASLAuth: TIdDICTAuthEvent read fOnCommandSASLAuth write fOnCommandSASLAuth;
    property OnCommandOption: TIdDICTOtherEvent read fOnCommandOption write fOnCommandOption;
    property OnCommandStatus: TIdDICTGetEvent read fOnCommandStat write fOnCommandStat;
    property OnCommandClient: TIdDICTShowEvent read fOnCommandClient write fOnCommandClient;
    property OnCommandOther: TIdDICTOtherEvent read fOnCommandOther write fOnCommandOther;
  end;

implementation

uses
  IdGlobal,
  IdResourceStrings,
  SysUtils;

constructor TIdDICTServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_DICT;
end;

function TIdDICTServer.DoExecute(Thread : TIdPeerThread ) : boolean;
var
  s, sCmd, sCmd2 : String;

  procedure NotHandled;
  begin
    Thread.Connection.Writeln('500 ' + RSCMDNotRecognized);   {Do not Localize}
  end;

begin
  result := true;
  s := Thread.Connection.ReadLn;
  sCmd := UpperCase(Fetch(s));
  Case Succ ( PosInStrArray ( Uppercase ( sCmd ), KnownCommands ) ) of
    1 : {auth}
        if assigned ( OnCommandAuth ) then
        begin
          sCmd2 := UpperCase(Fetch(s));
          OnCommandAuth ( Thread, sCmd2, S);
        end
        else
          NotHandled;
    2 : {client}
        if assigned ( OnCommandClient ) then
          OnCommandClient ( Thread, S)
        else
          NotHandled;
    3 : {define}
        if assigned ( OnCommandDefine ) then
        begin
          sCmd := UpperCase(Fetch (s));
          sCmd2 := UpperCase(Fetch(s));
          OnCommandDefine ( Thread, sCmd, sCmd2 );
        end
        else
          NotHandled;
    4 : {help}
        if assigned ( OnCommandHelp ) then
          OnCommandHelp ( Thread )
        else
          NotHandled;
    5 : {match}
        if assigned ( OnCommandMatch ) then
        begin
          sCmd := UpperCase(Fetch (s));
          sCmd2 := UpperCase(Fetch(s));
          OnCommandMatch ( Thread, sCmd, sCmd2, S );
        end
        else
          NotHandled;
    6 : {option}
        if assigned(OnCommandOption) then
          OnCommandOption(Thread, s, '')  {Do not Localize}
        else
          NotHandled;
    7 : {quit}
        if assigned ( OnCommandQuit ) then
          OnCommandQuit ( Thread )
        else
          NotHandled;
    8 : {saslauth}
        if assigned ( OnCommandSASLAuth ) then
        begin
          sCmd2 := UpperCase(Fetch(s));
          OnCommandSASLAuth(Thread, sCmd2, s);
        end
        else
          NotHandled;
    9 : {show}
        if assigned ( OnCommandShow ) then
          OnCommandShow ( Thread, s )
        else
          NotHandled;
    10 : {status}
        if assigned ( OnCommandStatus ) then
          OnCommandStatus ( Thread )
       else
         NotHandled;
    else
    begin
      if assigned ( OnCommandOther ) then
        OnCommandOther ( Thread, sCmd, S);
    end;  //else
  end; // Case Succ ( PosInStrArray ( Uppercase ( sCmd ), KnownCommands ) ) of
end; {doExecute}

end.
