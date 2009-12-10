{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10189: IdHostnameServer.pas 
{
{   Rev 1.0    2002.11.12 10:40:54 PM  czhower
}
unit IdHostnameServer;

interface
{
2000-May-18: J. Peter Mugaas
  -Ported to Indy
2000-Jan-13: MTL
  -13-JAN-2000 MTL: Moved to new Palette Scheme (Winshoes Servers)
1999-May-13: Ozz Nixon
  -Final version

Original Author: Ozz Nixon

Based on RFC 953
}

uses
  Classes,
  IdAssignedNumbers,
  IdTCPServer;

Const
   KnownCommands:Array [1..9] of string=
      (
      'HNAME',    {Do not Localize}
      'HADDR',    {Do not Localize}
      'ALL',    {Do not Localize}
      'HELP',    {Do not Localize}
      'VERSION',    {Do not Localize}
      'ALL-OLD',    {Do not Localize}
      'DOMAINS',    {Do not Localize}
      'ALL-DOM',    {Do not Localize}
      'ALL-INGWAY'    {Do not Localize}
      );

Type
  THostNameGetEvent = procedure(Thread: TIdPeerThread) of object;
  THostNameOneParmEvent = procedure(Thread: TIdPeerThread;Parm:String) of object;

  TIdHostNameServer = class(TIdTCPServer)
  protected
    FOnCommandHNAME:THostNameOneParmEvent;
    FOnCommandHADDR:THostNameOneParmEvent;
    FOnCommandALL:THostNameGetEvent;
    FOnCommandHELP:THostNameGetEvent;
    FOnCommandVERSION:THostNameGetEvent;
    FOnCommandALLOLD:THostNameGetEvent;
    FOnCommandDOMAINS:THostNameGetEvent;
    FOnCommandALLDOM:THostNameGetEvent;
    FOnCommandALLINGWAY:THostNameGetEvent;
    //
    function DoExecute(Thread: TIdPeerThread): boolean; override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    property OnCommandHNAME: THostNameOneParmEvent read fOnCommandHNAME write fOnCommandHNAME;
    property OnCommandHADDR: THostNameOneParmEvent read fOnCommandHADDR write fOnCommandHADDR;
    property OnCommandALL: THostNameGetEvent read fOnCommandALL write fOnCommandALL;
    property OnCommandHELP: THostNameGetEvent read fOnCommandHELP write fOnCommandHELP;
    property OnCommandVERSION: THostNameGetEvent read fOnCommandVERSION write fOnCommandVERSION;
    property OnCommandALLOLD: THostNameGetEvent read fOnCommandALLOLD write fOnCommandALLOLD;
    property OnCommandDOMAINS: THostNameGetEvent read fOnCommandDOMAINS write fOnCommandDOMAINS;
    property OnCommandALLDOM: THostNameGetEvent read fOnCommandALLDOM write fOnCommandALLDOM;
    property OnCommandALLINGWAY: THostNameGetEvent read fOnCommandALLINGWAY write fOnCommandALLINGWAY;
  end;

implementation

uses
  IdGlobal,
  SysUtils;

constructor TIdHostNameServer.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  DefaultPort := IdPORT_HOSTNAME;
end;

function TIdHostNameServer.DoExecute(Thread: TIdPeerThread): boolean;
Var
   S,sCmd:String;

begin
  result := true;
  while Thread.Connection.Connected do
  begin
    S := Thread.Connection.ReadLn;
    sCmd := UpperCase ( Fetch ( s, CHAR32 ) );
    case Succ(PosInStrArray ( Uppercase ( sCmd ), KnownCommands ) ) of
      1 : {hname}
          if assigned ( OnCommandHNAME ) then
            OnCommandHNAME ( Thread, S );
      2 : {haddr}
          if assigned ( OnCommandHADDR ) then
            OnCommandHADDR ( Thread, S );
      3 : {all}
          if assigned ( OnCommandALL ) then
            OnCommandALL ( Thread );
      4 : {help}
          if assigned ( OnCommandHELP ) then
            OnCommandHELP ( Thread );
      5 : {version}
          if assigned ( OnCommandVERSION ) then
            OnCommandVERSION ( Thread );
      6 : {all-old}
          if assigned ( OnCommandALLOLD ) then
            OnCommandALLOLD ( Thread );
      7 : {domains}
          if assigned ( OnCommandDOMAINS ) then
            OnCommandDOMAINS ( Thread );
      8 : {all-dom}
          if assigned ( OnCommandALLDOM ) then
            OnCommandALLDOM ( Thread );
      9 : {all-ingway}
          if assigned ( OnCommandALLINGWAY ) then
            OnCommandALLINGWAY ( Thread );
    end; //while Thread.Connection.Connected do
  end; //while Thread.Connection.Connected do
  Thread.Connection.Disconnect;
end; {doExecute}

end.
