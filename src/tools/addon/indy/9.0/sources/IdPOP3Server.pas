{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10279: IdPOP3Server.pas 
{
    Rev 1.1    2/19/2003 5:59:48 PM  BGooijen
  Now an error is returned when the parameters of APOP and TOP are wrong. 
  before the change the connection was closed on such error.
}
{
{   Rev 1.0    2002.11.12 10:48:00 PM  czhower
}
unit IdPOP3Server;

interface

{
 Indy POP3 Server

 Original Programmer: Luke Croteau
 Current Maintainer:  Andrew Neillans
 No Copyright. Code is given to the Indy Pit Crew.

 Quick Notes:
    A few of the methods return a default message number if a number isn't entered.
    The LIST, DELE, RETR, UIDL, and TOP command will return a -1 in the parameters
    if the value isn't specified by the client.
    Some functions require this capability. For example, the LIST command can operate
    either by a certain message number or a with no arguments. See RFC1939 for details.

 Revision History:
 02-May-2002: Andy Neillans
    - Bug Fix 551116 - StrToIntDef needed 'trimming#
 30-Apr-2002: Allen O'Neill.
    - Failsafe .. added check for ParamCount in reading Username and password - previously
      if either were sent in blank we got an IndexOutOfBounds error.
 13-Apr-2002:
   - Corrections :) And some Greeting.Text / And other response, glitches
 3-Apr-2002:
   - Minor changes. (Greeting.Text)
 1-Apr-2002:
   - Completed rewrite! At Last!
 15-Feb-2002: Andy
   - Started rewrite for use of CommandHandlers
 13-Jan-2002:
    -Fixed formatting bug.
 26-Dec-2000:
    -Andrew Neillans found a bug on line 157. Originally it was
     If Assigned(OnCommandLIST) then OnCommandRETR(...).
     Changed to OnCommandLIST(...). Thanks Andrew!
 29-Oct-2000:
    -I discovered I really shouldn't program at night.
     The error wasn't that it shouldn't be Succ (Because it should), but
     because I forgot to implement LIST
 27-Oct-2000:
    -Fixed a dumb bug. Originally coded command parsing as Succ(PosInStrArray)
     Should be just PosInStrArray b/c it is not a dynamic array. The bounds
     are constant.
 25-Oct-2000:
    -Created Unit.
    -Created new IdPOP3Server Server Component according to RFC 1939
}

uses
  Classes,
  IdAssignedNumbers,
  IdGlobal,
  IdTCPServer,
  IdMailBox;


type

  TIdPOP3ServerState = (Auth, Trans, Update);

  TIdPOP3ServerThread = class(TIdPeerThread)
  protected
    // what needs to be stored...
    fUser : String;
    fPassword : String;
    fState :TIdPOP3ServerState;
    procedure BeforeRun; override;
  public
    constructor Create(ACreateSuspended: Boolean = True); override;
    destructor Destroy; override;
   // Any functions for vars
    property Username : String read fUser write fUser;
    property Password : String read fPassword write fPassword;    
    property State    : TIdPOP3ServerState read fState write fState;
  end;

  TIdPOP3ServerNoParamEvent = procedure (ASender: TIdCommand) of object;
  TIdPOP3ServerMessageNumberEvent = procedure (ASender: TIdCommand; AMessageNum :Integer) of object;

  TIdPOP3ServerLogin = procedure (AThread :TIdPeerThread; LThread : TIdPOP3ServerThread) of object;

  TIdPOP3ServerAPOPCommandEvent = procedure (ASender: TIdCommand; AMailboxID :String; ADigest :String) of object;
  TIdPOP3ServerTOPCommandEvent = procedure (ASender: TIdCommand; AMessageNum :Integer; ANumLines :Integer) of object;


  TIdPOP3Server = class(TIdTcpServer)
  protected
    fCommandLogin : TIdPOP3ServerLogin;
    fCommandList,
    fCommandRetr,
    fCommandDele,
    fCommandUIDL  : TIdPOP3ServerMessageNumberEvent;
    fCommandTop   : TIdPOP3ServerTOPCommandEvent;
    fCommandQuit,
    fCommandStat,
    fCommandRset  : TIdPOP3ServerNoParamEvent;
    fCommandAPOP  : TIdPOP3ServerAPOPCommandEvent;

    // CommandHandlers
    procedure CommandUser(ASender: TIdCommand); //
    procedure CommandPass(ASender: TIdCommand); //
    procedure CommandList(ASender: TIdCommand); //
    procedure CommandRetr(ASender: TIdCommand); //
    procedure CommandDele(ASender: TIdCommand); //
    procedure CommandQuit(ASender: TIdCommand); //
    procedure CommandAPOP(ASender: TIdCommand); //
    procedure CommandStat(ASender: TIdCommand); //
    procedure CommandRset(ASender: TIdCommand); //
    procedure CommandTop(ASender: TIdCommand);  //
    procedure CommandUIDL(ASender: TIdCommand); //

    procedure InitializeCommandHandlers; override;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
  published
    property DefaultPort default IdPORT_POP3;
    // These procedures / functions are exposed
    property CheckUser   : TIdPOP3ServerLogin              read fCommandLogin write fCommandLogin;
    property OnLIST      : TIdPOP3ServerMessageNumberEvent read fCommandList write fCommandList;
    property OnRETR      : TIdPOP3ServerMessageNumberEvent read fCommandRetr write fCommandRetr;
    property OnDELE      : TIdPOP3ServerMessageNumberEvent read fCommandDele write fCommandDele;
    property OnUIDL      : TIdPOP3ServerMessageNumberEvent read fCommandUidl write fCommandUidl;
    property OnSTAT      : TIdPOP3ServerNoParamEvent       read fCommandStat write fCommandStat;
    property OnTOP       : TIdPOP3ServerTOPCommandEvent    read fCommandTop  write fCommandTop;
    property OnRSET      : TIdPOP3ServerNoParamEvent       read fCommandRset write fCommandRset;
    property OnQUIT      : TIdPOP3ServerNoParamEvent       read fCommandQuit write fCommandQuit;
    property OnAPOP      : TIdPOP3ServerAPOPCommandEvent   read fCommandApop write fCommandApop;
  End;

implementation

uses
  IdResourceStrings,
  IdRFCReply,
  SysUtils;

procedure TIdPOP3Server.InitializeCommandHandlers;
begin
  inherited;
  with CommandHandlers.Add do begin
    Command := 'USER';
    OnCommand := CommandUSER;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'PASS';
    OnCommand := CommandPass;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'LIST';
    OnCommand := CommandList;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'RETR';
    OnCommand := CommandRetr;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'DELE';
    OnCommand := CommandDele;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'UIDL';
    OnCommand := CommandUIDL;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'STAT';
    OnCommand := CommandSTAT;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'TOP';
    OnCommand := CommandTOP;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'NOOP';
    ReplyNormal.Text.Text := '+OK NOOP';
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'APOP';
    OnCommand := CommandAPOP;
    ParseParams := True;
  End;
  with CommandHandlers.Add do begin
    Command := 'RSET';
    ReplyNormal.Text.Text := '+OK Reset';
    OnCommand := CommandRset;
    ParseParams := False;
  end;

  with CommandHandlers.Add do begin
    Command := 'QUIT';
    OnCommand := CommandQuit;
    Disconnect := True;
    ReplyNormal.Text.Text := '+OK Closing communication channel'; // USE ResourceString ??
    ParseParams := False;
  end;
end;

{ Command Handler Functions here }

procedure TIdPOP3Server.CommandUser(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  if ASender.Params.Count > 0 then
      LThread.Username := ASender.Params.Strings[0];
  ASender.Thread.Connection.WriteLn('+OK Password required');
end;

procedure TIdPOP3Server.CommandPass(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  if ASender.Params.Count > 0 then
      LThread.Password := ASender.Params.Strings[0];
  if Assigned(CheckUser) then 
    CheckUser(ASender.Thread, LThread);
  // User to set return state of LThread.State as required.

  If LThread.State <> Trans Then
   ASender.Thread.Connection.Writeln('-ERR Login failed')
  Else
   ASender.Thread.Connection.Writeln('+OK Login OK');
end;

procedure TIdPOP3Server.CommandList(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then
   Begin
    If Assigned(fCommandList) Then
     OnList(ASender, StrToIntDef(Trim(ASender.Params.Text), -1))
    Else
     ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['LIST']));
   End
  Else
   ASender.Thread.Connection.WriteLn('-ERR Please login first'); // RS
end;

procedure TIdPOP3Server.CommandRetr(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then
   Begin
    If Assigned(fCommandRetr) Then
     OnRetr(ASender, StrToIntDef(Trim(ASender.Params.Text), -1))
    Else
   ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['RETR']));
   End
  Else
   ASender.Thread.Connection.WriteLn('-ERR Please login first'); // RS
end;

procedure TIdPOP3Server.CommandDele(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then
   Begin
    If Assigned(fCommandDele) Then
    Begin
     Try
      StrToInt(Trim(ASender.Params.Text));
      OnDele(ASender, StrToInt(Trim(ASender.Params.Text)))
     Except
      ASender.Thread.Connection.WriteLn('-ERR Invalid Message Number'); // RS
     End;
    End
    Else
   ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['DELE']));
   End
  Else
   ASender.Thread.Connection.WriteLn('-ERR Please login first'); // RS
end;

procedure TIdPOP3Server.CommandQuit(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then
   Begin
    If Assigned(fCommandQuit) Then OnQuit(ASender)
   End;
end;

procedure TIdPOP3Server.CommandAPOP(ASender: TIdCommand);
Var
 LThread: TIdPOP3ServerThread;
begin
 LThread := TIdPOP3ServerThread(ASender.Thread);
 If LThread.State = Auth Then
  Begin
   If Assigned(fCommandAPOP) Then
    Begin
      if ASender.Params.Count = 2 then begin
        OnAPOP(ASender, ASender.Params.Strings[0], ASender.Params.Strings[1]);
        // User to set return state of LThread.State as required.
        If LThread.State <> Trans Then begin
          ASender.Thread.Connection.Writeln('-ERR Login failed');
        End
      end else begin
        ASender.Thread.Connection.WriteLn('-ERR Invalid Syntax'); //RS
      end
     end
   Else
    ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['APOP']));
  End
 Else
  ASender.Thread.Connection.WriteLn('-ERR Wrong State');
end;

procedure TIdPOP3Server.CommandStat(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then
   Begin
    If Assigned(fCommandStat) Then
     OnStat(ASender)
    Else
   ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['STAT']));
   End
  Else
   ASender.Thread.Connection.WriteLn('-ERR Please login first'); // RS
end;

procedure TIdPOP3Server.CommandRset(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then
   Begin
    If Assigned(fCommandRSET) Then
     OnRset(ASender)
    Else
   ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['RSET']));
   End
  Else
   ASender.Thread.Connection.WriteLn('-ERR Please login first'); // RS
end;

procedure TIdPOP3Server.CommandTop(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then Begin
    If Assigned(fCommandTop) Then Begin
      if ASender.Params.Count = 2 then begin
        If (StrToIntDef(Trim(ASender.Params.Strings[0]), -1) <> -1) AND (StrToIntDef(Trim(ASender.Params.Strings[1]), -1) <> -1) Then begin
          OnTop(ASender, StrToInt(ASender.Params.Strings[0]), StrToInt(ASender.Params.Strings[1]))
        end Else begin
          ASender.Thread.Connection.WriteLn('-ERR Invalid Syntax'); //RS
        End;
      end else begin
        ASender.Thread.Connection.WriteLn('-ERR Invalid Syntax'); //RS
      end
    End Else
     ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['TOP']));
  End
  Else
   ASender.Thread.Connection.WriteLn('-ERR Please login first'); // RS
end;

procedure TIdPOP3Server.CommandUIDL(ASender: TIdCommand);
Var
  LThread: TIdPOP3ServerThread;
begin
  LThread := TIdPOP3ServerThread(ASender.Thread);
  If LThread.State = Trans Then
   Begin
    If Assigned(fCommandUidl) Then
     OnUidl(ASender, StrToIntDef(Trim(ASender.Params.Text), -1))
    Else
   ASender.Thread.Connection.WriteLn('-ERR ' + Format(RSPOP3SVRNotHandled, ['UIDL']));
   End
  Else
   ASender.Thread.Connection.WriteLn('-ERR Please login first'); // RS
end;

{ Constructor / Destructors }

constructor TIdPOP3Server.Create(AOwner: TComponent);
begin
  inherited;
  ThreadClass := TIdPOP3ServerThread;
  DefaultPort := IdPORT_POP3;
  Greeting.Text.Text := '+OK Welcome to Indy POP3 Server'; // RS
  ReplyUnknownCommand.Text.Text := '-ERR Sorry, Unknown Command'; // RS
end;

destructor TIdPOP3Server.Destroy;
begin
  inherited;
end;

{ TIdPOP3ServerThread }

constructor TIdPOP3ServerThread.Create(ACreateSuspended: Boolean);
begin
  inherited;
  FUser := '';
  fState := Auth;
end;

procedure TIdPOP3ServerThread.BeforeRun;
begin
  FUser := '';
  fState := Auth;
  fPassword := '';
  inherited BeforeRun;
end;

destructor TIdPOP3ServerThread.Destroy;
begin
  inherited;
end;

end.
