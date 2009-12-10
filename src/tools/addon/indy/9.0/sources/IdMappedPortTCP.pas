{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10247: IdMappedPortTCP.pas 
{
{   Rev 1.0    2002.11.12 10:45:12 PM  czhower
}
unit IdMappedPortTCP;
interface
{
2001-12-xx - Andrew P.Rybin
  -new architecture
2002-02-02 - Andrew P.Rybin
  -DoDisconnect fix
}
uses
  Classes,
  IdGlobal, IdTCPConnection, IdTCPServer, IdAssignedNumbers,
  SysUtils;

type
  TIdMappedPortThread = class (TIdPeerThread)
  protected
    FOutboundClient: TIdTCPConnection;//was TIdTCPClient
    FReadList: TList;
    FNetData: String; //data buf
    FConnectTimeOut: Integer;
    //
    procedure Cleanup; override; //Free OutboundClient
    procedure OutboundConnect; virtual;
  public
    constructor Create(ACreateSuspended: Boolean = True); override;
    destructor Destroy; override;
    //
    property  ConnectTimeOut: Integer read FConnectTimeOut write FConnectTimeOut default IdTimeoutDefault;
    property  NetData: String read FNetData write FNetData;
    property  OutboundClient: TIdTCPConnection read FOutboundClient write FOutboundClient;
    property  ReadList: TList read FReadList;
  End;//TIdMappedPortThread

  TIdMappedPortThreadEvent = procedure(AThread: TIdMappedPortThread) of object;
  TIdMappedPortOutboundConnectEvent = procedure(AThread: TIdMappedPortThread; AException: Exception) of object;//E=NIL-OK

  TIdMappedPortTCP = class(TIdTCPServer)
  protected
    FMappedHost: String;
    FMappedPort: Integer;

    //AThread.Connection.Server & AThread.OutboundClient
    FOnOutboundConnect: TIdMappedPortOutboundConnectEvent;
    FOnOutboundData: TIdMappedPortThreadEvent;
    FOnOutboundDisConnect: TIdMappedPortThreadEvent;
    //
    procedure DoConnect(AThread: TIdPeerThread); override;
    function  DoExecute(AThread: TIdPeerThread): boolean; override;
    procedure DoDisconnect(AThread: TIdPeerThread); override; //DoLocalClientDisconnect
    procedure DoLocalClientConnect(AThread: TIdMappedPortThread); virtual;
    procedure DoLocalClientData(AThread: TIdMappedPortThread); virtual;//APR: bServer

    procedure DoOutboundClientConnect(AThread: TIdMappedPortThread; const AException: Exception=NIL); virtual;
    procedure DoOutboundClientData(AThread: TIdMappedPortThread); virtual;
    procedure DoOutboundDisconnect(AThread: TIdMappedPortThread); virtual;

    function  GetOnConnect: TIdMappedPortThreadEvent;
    function  GetOnExecute: TIdMappedPortThreadEvent;
    procedure SetOnConnect(const Value: TIdMappedPortThreadEvent);
    procedure SetOnExecute(const Value: TIdMappedPortThreadEvent);
    function  GetOnDisconnect: TIdMappedPortThreadEvent;
    procedure SetOnDisconnect(const Value: TIdMappedPortThreadEvent);
    // try to hide
    property  OnBeforeCommandHandler;// NOT USED
    property  OnAfterCommandHandler;// NOT USED
    property  OnNoCommandHandler;// NOT USED
  public
    constructor Create(AOwner: TComponent); override;
  published
    property  MappedHost: String read FMappedHost write FMappedHost;
    property  MappedPort: Integer read FMappedPort write FMappedPort;
    //
    property  OnConnect: TIdMappedPortThreadEvent read GetOnConnect write SetOnConnect; //OnLocalClientConnect
    property  OnOutboundConnect: TIdMappedPortOutboundConnectEvent read FOnOutboundConnect write FOnOutboundConnect;

    property  OnExecute: TIdMappedPortThreadEvent read GetOnExecute write SetOnExecute;//OnLocalClientData
    property  OnOutboundData: TIdMappedPortThreadEvent read FOnOutboundData write FOnOutboundData;

    property  OnDisconnect: TIdMappedPortThreadEvent read GetOnDisconnect write SetOnDisconnect;//OnLocalClientDisconnect
    property  OnOutboundDisconnect: TIdMappedPortThreadEvent read FOnOutboundDisconnect write FOnOutboundDisconnect;
  End;//TIdMappedPortTCP

  //=============================================================================
  // * Telnet *
  //=============================================================================

  TIdMappedTelnetThread = class (TIdMappedPortThread)
  protected
    FAllowedConnectAttempts: Integer;
    //
    procedure OutboundConnect; override;
  public
    property  AllowedConnectAttempts: Integer read FAllowedConnectAttempts;
  End;//TIdMappedTelnetThread

  TIdMappedTelnetCheckHostPort = procedure (AThread: TIdMappedPortThread; const AHostPort: String; var VHost,VPort: String) of object;

  TIdCustomMappedTelnet = class (TIdMappedPortTCP)
  protected
    FAllowedConnectAttempts: Integer;
    FOnCheckHostPort: TIdMappedTelnetCheckHostPort;

    procedure DoCheckHostPort (AThread: TIdMappedPortThread; const AHostPort: String; var VHost,VPort: String); virtual;
    procedure SetAllowedConnectAttempts(const Value: Integer);
    procedure ExtractHostAndPortFromLine(AThread: TIdMappedPortThread; const AHostPort: String);
  public
    constructor Create(AOwner: TComponent); override;
    //
    property  AllowedConnectAttempts: Integer read FAllowedConnectAttempts write SetAllowedConnectAttempts default -1;
    //
    property  OnCheckHostPort: TIdMappedTelnetCheckHostPort read FOnCheckHostPort write FOnCheckHostPort;
  published
    property  DefaultPort default IdPORT_TELNET;
    property  MappedPort default IdPORT_TELNET;
  End;//TIdCustomMappedTelnet

  TIdMappedTelnet = class (TIdCustomMappedTelnet)
  published
    property  AllowedConnectAttempts: Integer read FAllowedConnectAttempts write SetAllowedConnectAttempts default -1;
    //
    property  OnCheckHostPort: TIdMappedTelnetCheckHostPort read FOnCheckHostPort write FOnCheckHostPort;
  End;//TIdMappedTelnet

  //=============================================================================
  // * P O P 3 *
  // USER username#host:port
  //=============================================================================

  TIdMappedPop3Thread = class (TIdMappedTelnetThread)
  protected
    procedure OutboundConnect; override;
  public
  End;//TIdMappedPop3Thread

  TIdMappedPop3 = class (TIdMappedTelnet)
  protected
    FUserHostDelimiter: String;
  public
    constructor Create(AOwner: TComponent); override;
  published
    property  DefaultPort default IdPORT_POP3;
    property  MappedPort default IdPORT_POP3;
    property  UserHostDelimiter: String read FUserHostDelimiter write FUserHostDelimiter;
  End;//TIdMappedPop3

Implementation

uses
  IdStack, IdIOHandlerSocket, IdException, IdResourceStrings, IdTCPClient;

resourcestring
  RSEmptyHost = 'Host is empty';    {Do not Localize}
  RSPop3ProxyGreeting = 'POP3 proxy ready';    {Do not Localize}
  RSPop3UnknownCommand = 'command must be either USER or QUIT';    {Do not Localize}
  RSPop3QuitMsg = 'POP3 proxy signing off';    {Do not Localize}

constructor TIdMappedPortTCP.Create(AOwner: TComponent);
Begin
  inherited Create(AOwner);
  ThreadClass := TIdMappedPortThread;
End;//

procedure TIdMappedPortTCP.DoLocalClientConnect(AThread: TIdMappedPortThread);
Begin
  if Assigned(FOnConnect) then FOnConnect(AThread);
End;//

procedure TIdMappedPortTCP.DoOutboundClientConnect(AThread: TIdMappedPortThread; const AException: Exception=NIL);
Begin
  if Assigned(FOnOutboundConnect) then FOnOutboundConnect(AThread,AException);
End;//

procedure TIdMappedPortTCP.DoLocalClientData(AThread: TIdMappedPortThread);
Begin
  if Assigned(FOnExecute) then FOnExecute(AThread);
End;//

procedure TIdMappedPortTCP.DoOutboundClientData(AThread: TIdMappedPortThread);
Begin
  if Assigned(FOnOutboundData) then FOnOutboundData(AThread);
End;//

procedure TIdMappedPortTCP.DoDisconnect(AThread: TIdPeerThread);
Begin
  inherited DoDisconnect(AThread);
  if Assigned(TIdMappedPortThread(AThread).FOutboundClient) and
    TIdMappedPortThread(AThread).FOutboundClient.Connected
  then begin//check for loop
    TIdMappedPortThread(AThread).FOutboundClient.Disconnect;
  end;
End;//DoDisconnect

procedure TIdMappedPortTCP.DoOutboundDisconnect(AThread: TIdMappedPortThread);
Begin
  if Assigned(FOnOutboundDisconnect) then begin
    FOnOutboundDisconnect(AThread);
  end;
  AThread.Connection.Disconnect; //disconnect local
End;//


procedure TIdMappedPortTCP.DoConnect(AThread: TIdPeerThread);
begin
  //WARNING: Check TIdTCPServer.DoConnect and synchronize code. Don't call inherited!=> OnConnect in OutboundConnect    {Do not Localize}
  AThread.Connection.WriteRFCReply(Greeting); //was: inherited DoConnect(AThread);
  TIdMappedPortThread(AThread).OutboundConnect;
End;

function TIdMappedPortTCP.DoExecute(AThread: TIdPeerThread): boolean;
var
  LConnectionHandle: TObject;
  LOutBoundHandle: TObject;
begin
  Result:= TRUE;
  try
    LConnectionHandle:= TObject(
      (AThread.Connection.IOHandler as TIdIOHandlerSocket).Binding.Handle);
    LOutBoundHandle:= TObject(
      (TIdMappedPortThread(AThread).FOutboundClient.IOHandler as TIdIOHandlerSocket).Binding.Handle);
    with TIdMappedPortThread(AThread).FReadList do begin
      Clear;
      Add(LConnectionHandle);
      Add(LOutBoundHandle);

      if GStack.WSSelect(TIdMappedPortThread(AThread).FReadList, nil, nil, IdTimeoutInfinite) > 0 then begin
        //TODO: Make a select list that also has a function to check of handles
        if IndexOf(LConnectionHandle) > -1 then begin
          // TODO: WSAECONNRESET (Exception [EIdSocketError] Socket Error # 10054 Connection reset by peer)
          TIdMappedPortThread(AThread).FNetData := AThread.Connection.CurrentReadBuffer;
          if Length(TIdMappedPortThread(AThread).FNetData)>0 then begin
            DoLocalClientData(TIdMappedPortThread(AThread));//bServer
            TIdMappedPortThread(AThread).FOutboundClient.Write(TIdMappedPortThread(AThread).FNetData);
          end;//if
        end;
        if IndexOf(LOutBoundHandle) > -1 then begin
          TIdMappedPortThread(AThread).FNetData := TIdMappedPortThread(AThread).FOutboundClient.CurrentReadBuffer;
          if Length(TIdMappedPortThread(AThread).FNetData)>0 then begin
            DoOutboundClientData(TIdMappedPortThread(AThread));
            AThread.Connection.Write(TIdMappedPortThread(AThread).FNetData);
          end;//if
        end;
      end;//if select
    end;//with
  finally
    if NOT TIdMappedPortThread(AThread).FOutboundClient.Connected then begin
      DoOutboundDisconnect(TIdMappedPortThread(AThread)); //&Connection.Disconnect
    end;//if
  end;//tryf
End;//TIdMappedPortTCP.DoExecute

function TIdMappedPortTCP.GetOnConnect: TIdMappedPortThreadEvent;
Begin
  Result:=TIdMappedPortThreadEvent(FOnConnect);
End;//
function TIdMappedPortTCP.GetOnExecute: TIdMappedPortThreadEvent;
Begin
  Result:=TIdMappedPortThreadEvent(FOnExecute);
End;//
function TIdMappedPortTCP.GetOnDisconnect: TIdMappedPortThreadEvent;
Begin
  Result:=TIdMappedPortThreadEvent(FOnDisconnect);
End;//OnDisconnect

procedure TIdMappedPortTCP.SetOnConnect(const Value: TIdMappedPortThreadEvent);
Begin
  TIdMappedPortThreadEvent(FOnConnect):=Value;
End;//
procedure TIdMappedPortTCP.SetOnExecute(const Value: TIdMappedPortThreadEvent);
Begin
  TIdMappedPortThreadEvent(FOnExecute):=Value;
End;//
procedure TIdMappedPortTCP.SetOnDisconnect(const Value: TIdMappedPortThreadEvent);
Begin
  TIdMappedPortThreadEvent(FOnDisconnect):=Value;
End;//OnDisconnect


{ TIdMappedPortThread }

constructor TIdMappedPortThread.Create;
Begin
  inherited Create(ACreateSuspended);
  FReadList:= TList.Create;
  FConnectTimeOut := IdTimeoutDefault;
End;

procedure TIdMappedPortThread.Cleanup;
Begin
  FreeAndNil(FOutboundClient);
  inherited Cleanup;
End;

destructor TIdMappedPortThread.Destroy;
begin
  //^FreeAndNil(FOutboundClient);
  FreeAndNil(FReadList);
  inherited Destroy;
End;

procedure TIdMappedPortThread.OutboundConnect;
Begin
  FOutboundClient := TIdTCPClient.Create(NIL);
  with TIdMappedPortTCP(Connection.Server) do begin
    try
      with TIdTcpClient(FOutboundClient) do begin
        Port := MappedPort;
        Host := MappedHost;
      end;//with
      DoLocalClientConnect(SELF);

      TIdTcpClient(FOutboundClient).Connect(FConnectTimeOut);
      DoOutboundClientConnect(SELF);
    except
      on E: Exception do begin
        DoOutboundClientConnect(SELF,E); // DONE: Handle connect failures
        Connection.Disconnect; //req IdTcpServer with "Stop this thread if we were disconnected"
      end;
    end;//trye
  end;//with
End;//for easy inheritance

//=============================================================================

{ TIdCustomMappedTelnet }

constructor TIdCustomMappedTelnet.Create(AOwner: TComponent);
Begin
  inherited Create(AOwner);
  FAllowedConnectAttempts := -1;
  ThreadClass := TIdMappedTelnetThread;
  DefaultPort := IdPORT_TELNET;
  MappedPort := IdPORT_TELNET;
End;//TIdMappedTelnet.Create

procedure TIdCustomMappedTelnet.DoCheckHostPort(AThread: TIdMappedPortThread; const AHostPort: String; var VHost,VPort: String);
Begin
  if Assigned(FOnCheckHostPort) then FOnCheckHostPort(AThread,AHostPort,VHost,VPort);
End;//

procedure TIdCustomMappedTelnet.ExtractHostAndPortFromLine(AThread: TIdMappedPortThread; const AHostPort: String);
var
  LHost,LPort: String;
  P,L: PChar;
Begin
  if Length(AHostPort)>0 then begin
    P := Pointer(AHostPort);
    L := P + Length(AHostPort);
    while (P<L) and NOT(P^ in [#0,#9,' ',':']) do begin {Do not Localize}
      inc(P);
    end;
    SetString(LHost, PChar(Pointer(AHostPort)), P-Pointer(AHostPort));
    while (P<L) and (P^ in [#9,' ',':']) do begin {Do not Localize}
      inc(P);
    end;
    SetString(LPort, P, L-P);
    LHost := TrimRight(LHost);
    LPort := TrimLeft(LPort);
  end
  else begin
    LHost := '';    {Do not Localize}
    LPort := '';    {Do not Localize}
  end;//if
  DoCheckHostPort(AThread, AHostPort,LHost,LPort);

  TIdTcpClient(AThread.OutboundClient).Host := LHost;
  TIdTcpClient(AThread.OutboundClient).Port := StrToIntDef(LPort,TIdTcpClient(AThread.OutboundClient).Port);
End;//ExtractHostAndPortFromLine

procedure TIdMappedTelnetThread.OutboundConnect;
var
  LHostPort: String;
Begin
  //don`t call inherited, NEW behavior
  FOutboundClient := TIdTCPClient.Create(NIL);
  with TIdCustomMappedTelnet(Connection.Server) do begin
    with TIdTcpClient(FOutboundClient) do begin
      Port := MappedPort;
      Host := MappedHost;
    end;//with
    FAllowedConnectAttempts := TIdCustomMappedTelnet(Connection.Server).AllowedConnectAttempts;
    DoLocalClientConnect(SELF);

    repeat
      if FAllowedConnectAttempts>0 then begin
        dec(FAllowedConnectAttempts);
      end;
      try
        LHostPort := Trim(Connection.InputLn); //~telnet input
        ExtractHostAndPortFromLine(SELF,LHostPort);

        if Length(TIdTcpClient(FOutboundClient).Host)<1 then begin
          raise EIdException.Create(RSEmptyHost);
        end;
        TIdTcpClient(FOutboundClient).Connect(FConnectTimeOut);
      except
        on E: Exception do begin // DONE: Handle connect failures
          FNetData := 'ERROR: ['+E.ClassName+'] ' + E.Message;    {Do not Localize}
          DoOutboundClientConnect(SELF,E);//?DoException(AThread,E);
          Connection.WriteLn(FNetData);
        end;
      end;//trye
    until FOutboundClient.Connected or (FAllowedConnectAttempts=0);

    if FOutboundClient.Connected then begin
      DoOutboundClientConnect(SELF)
    end
    else begin
      Connection.Disconnect; //prevent all next work
    end;
  end;//with
End;//TIdMappedTelnet.OutboundConnect

procedure TIdCustomMappedTelnet.SetAllowedConnectAttempts(const Value: Integer);
Begin
  if Value >= 0 then begin
    FAllowedConnectAttempts:= Value
  end else begin
    FAllowedConnectAttempts:=-1; //unlimited
  end;
End;//


{ TIdMappedPop3 }

constructor TIdMappedPop3.Create(AOwner: TComponent);
Begin
  inherited Create(AOwner);
  FUserHostDelimiter := '#';//standard    {Do not Localize}
  Greeting.NumericCode := 0;//same as POP3
  Greeting.Text.Text := '+OK '+RSPop3ProxyGreeting;    {Do not Localize}
  ReplyUnknownCommand.NumericCode := 0;
  ReplyUnknownCommand.Text.Text := '-ERR '+RSPop3UnknownCommand;    {Do not Localize}
  DefaultPort := IdPORT_POP3;
  MappedPort := IdPORT_POP3;
  ThreadClass := TIdMappedPop3Thread;
End;//TIdMappedPop3.Create

{ TIdMappedPop3Thread }

procedure TIdMappedPop3Thread.OutboundConnect;
var
  LHostPort,LUserName,LPop3Cmd: String;
Begin
  //don`t call inherited, NEW behavior
  with TIdMappedPop3(Connection.Server) do begin
    FOutboundClient := TIdTCPClient.Create(NIL);
    with TIdTcpClient(FOutboundClient) do begin
      Port := MappedPort;
      Host := MappedHost;
    end;//with
    FAllowedConnectAttempts := TIdMappedPop3(Connection.Server).AllowedConnectAttempts;
    DoLocalClientConnect(SELF);

    repeat
      if FAllowedConnectAttempts>0 then begin
        dec(FAllowedConnectAttempts);
      end;
      try
        // Greeting
        LHostPort := Trim(Connection.ReadLn);//USER username#host OR QUIT
        LPop3Cmd := UpperCase(Fetch(LHostPort,' ',TRUE));    {Do not Localize}
        if LPop3Cmd = 'QUIT' then begin    {Do not Localize}
          Connection.WriteLn('+OK '+RSPop3QuitMsg);    {Do not Localize}
          Connection.Disconnect;
          BREAK;
        end
        else if LPop3Cmd = 'USER' then begin    {Do not Localize}
          LUserName := Fetch(LHostPort,FUserHostDelimiter,TRUE,FALSE);//?:CaseSensetive
          FNetData := LUserName; //save for OnCheckHostPort
          LHostPort := TrimLeft(LHostPort); //trimRight above
          ExtractHostAndPortFromLine(SELF,LHostPort);
          LUserName := FNetData; //allow username substitution
        end
        else begin
          Connection.WriteRFCReply(ReplyUnknownCommand);
          Continue;
        end;//if

        if Length(TIdTcpClient(FOutboundClient).Host)<1 then begin
          raise EIdException.Create(RSEmptyHost);
        end;
        TIdTcpClient(FOutboundClient).Connect(FConnectTimeOut);
        FNetData := FOutboundClient.ReadLn;//Read Pop3 Banner for OnOutboundClientConnect

        FOutboundClient.WriteLn('USER '+LUserName);    {Do not Localize}
      except
        on E: Exception do begin // DONE: Handle connect failures
          FNetData :='-ERR ['+E.ClassName+'] '+E.Message;    {Do not Localize}
          DoOutboundClientConnect(SELF,E);//?DoException(AThread,E);
          Connection.WriteLn(FNetData);
        end;
      end;//trye
    until FOutboundClient.Connected or (FAllowedConnectAttempts=0);

    if FOutboundClient.Connected then begin
      DoOutboundClientConnect(SELF)
    end
    else begin
      Connection.Disconnect; //prevent all next work
    end;
  end;//with
End;//TIdMappedPop3.OutboundConnect

END.
