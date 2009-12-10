{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10245: IdMappedFTP.pas 
{
{   Rev 1.0    2002.11.12 10:45:00 PM  czhower
}
{-----------------------------------------------------------------------------
 Unit Name: IdMappedFTP
 Author:    Andrew P.Rybin [magicode@mail.ru]
 Creation:  14.12.2001
 Version:   0.0.0
 Purpose:
 History:
  2001.12.14 - beta preview (but FTPVC work fine ;-)

 Th (rfc959):
  1) EOL = #13#10  [#10 last char]
  2) reply = IntCode (three digit number) ' ' text
  3) PORT h1,h2,h3,h4,p1,p2 -> Client Listen >>'200 Port command successful.'    
  4) PASV -> Server Listen >>'227 Entering Passive Mode (%d,%d,%d,%d,%d,%d).'
 Err:
   426 RSFTPDataConnClosedAbnormally

 !!! PROBLEM with PORT (Listen)
-----------------------------------------------------------------------------}

unit IdMappedFTP;

interface

uses
  Classes, IdTCPServer, IdMappedPortTCP, IdAssignedNumbers, IdThread,
    IdTCPConnection, SysUtils;

type
  TIdMappedFtpDataThread = class;

  TIdMappedFtpThread = class(TIdMappedPortThread)
  protected
    FFtpCommand: string;
    FFtpParams: string;
    FHost, FoutboundHost: string; //local,remote(mapped)
    FPort, FoutboundPort: Integer;
    FDataChannelThread: TIdMappedFtpDataThread;
    //
    function GetFtpCmdLine: string; //Cmd+' '+Params    {Do not Localize}
    procedure CreateDataChannelThread;
    //procedure FreeDataChannelThread;
    function ProcessFtpCommand: Boolean; virtual;
    procedure ProcessOutboundDc(const APASV: Boolean); virtual;
    procedure ProcessDataCommand; virtual;
  public
    constructor Create(ACreateSuspended: Boolean = True); override;
    property FtpCommand: string read FFtpCommand write FFtpCommand;
    property FtpParams: string read FFtpParams write FFtpParams;
    property FtpCmdLine: string read GetFtpCmdLine;

    property Host: string read FHost write FHost;
    property OutboundHost: string read FOutboundHost write FOutboundHost;
    property Port: Integer read FPort write FPort;
    property OutboundPort: Integer read FOutboundPort write FOutboundPort;

    property DataChannelThread: TIdMappedFtpDataThread read FDataChannelThread;
  end; //TIdMappedFtpThread

  TIdMappedFtpDataThread = class(TIdThread)
  protected
    FMappedFtpThread: TIdMappedFtpThread;
    FConnection: TIdTcpConnection;
    FOutboundClient: TIdTCPConnection;
    FReadList: TList;
    FNetData: string;
    //
    procedure BeforeRun; override;
    procedure Run; override;
  public
    constructor Create(AMappedFtpThread: TIdMappedFtpThread); reintroduce;
    destructor Destroy; override;

    property MappedFtpThread: TIdMappedFtpThread read FMappedFtpThread;
    property Connection: TIdTcpConnection read FConnection; //local
    property OutboundClient: TIdTCPConnection read FOutboundClient;
      //remote(mapped)
    property NetData: string read FNetData write FNetData;
  end; //TIdMappedFtpDataThread

  TIdMappedFtpOutboundDcMode = (fdcmClient, fdcmPort, fdcmPasv);

  TIdMappedFTP = class(TIdMappedPortTCP)
  protected
    FOutboundDcMode: TIdMappedFtpOutboundDcMode;

    function DoExecute(AThread: TIdPeerThread): boolean; override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    property DefaultPort default IdPORT_FTP;
    property MappedPort default IdPORT_FTP;
    property OutboundDcMode: TIdMappedFtpOutboundDcMode read FOutboundDcMode
      write FOutboundDcMode default fdcmClient;
  end; //TIdMappedFTP

//=============================================================================

implementation

uses
  IdGlobal, IdStack, IdIOHandlerSocket, IdException, IdResourceStrings,
  IdTcpClient, IdSimpleServer;

const
  //  iLastGetCmd = 2;
  saDataCommands: array[0..5] of string = (
    {GET}'RETR', 'LIST', 'NLST',    {Do not Localize}
    {PUT}'STOU', 'APPE', 'STOR');    {Do not Localize}

function IsDataCommand(const upcaseCmd: string): Boolean;
var
  i: Integer;
begin
  for i := Low(saDataCommands) to High(saDataCommands) do
  begin
    if upcaseCmd = saDataCommands[i] then
    begin
      Result := TRUE;
      EXIT;
    end; //if
  end; //for
  Result := FALSE; //not found
end; //

{ TIdMappedFTP }

constructor TIdMappedFTP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  DefaultPort := IdPORT_FTP;
  MappedPort := IdPORT_FTP;
  ThreadClass := TIdMappedFtpThread;
  FOutboundDcMode := fdcmClient;
end; //TIdMappedFTP.Create

function TIdMappedFTP.DoExecute(AThread: TIdPeerThread): boolean;
var
  LConnectionHandle: TObject;
  LOutBoundHandle: TObject;
begin
  Result := TRUE;
  try
    LConnectionHandle := TObject(//local client
      (AThread.Connection.IOHandler as TIdIOHandlerSocket).Binding.Handle);
    LOutBoundHandle := TObject(//remote (mapped) server
      (TIdMappedFtpThread(AThread).FOutboundClient.IOHandler as
        TIdIOHandlerSocket).Binding.Handle);
    with TIdMappedFtpThread(AThread).FReadList do
    begin
      Clear;
      Add(LConnectionHandle);
      Add(LOutBoundHandle);

      if GStack.WSSelect(TIdMappedFtpThread(AThread).FReadList, nil, nil,
        IdTimeoutInfinite) > 0 then
      begin
        //TODO: Make a select list that also has a function to check of handles
        if IndexOf(LOutBoundHandle) > -1 then
        begin
          repeat
            TIdMappedFtpThread(AThread).FNetData :=
              TIdMappedFtpThread(AThread).FOutboundClient.ReadLn; //Reply
            if Length(TIdMappedFtpThread(AThread).FNetData) > 0 then
            begin
              DoOutboundClientData(TIdMappedFtpThread(AThread));
              AThread.Connection.WriteLn(TIdMappedFtpThread(AThread).FNetData);
            end; //if
          until TIdMappedFtpThread(AThread).FOutboundClient.InputBuffer.Size <= 0;
        end; //if >-1  chance for server (passive side)
        //FTP Client:
        if IndexOf(LConnectionHandle) > -1 then
        begin
          repeat
            TIdMappedFtpThread(AThread).FNetData := AThread.Connection.ReadLn;  //USeR REQuest
            if Length(TIdMappedFtpThread(AThread).FNetData) > 0 then
            begin
              TIdMappedFtpThread(AThread).FFtpParams := TIdMappedFtpThread(AThread).FNetData;
              TIdMappedFtpThread(AThread).FFtpCommand := Fetch(TIdMappedFtpThread(AThread).FFtpParams, ' ', TRUE);    {Do not Localize}
              if TIdMappedFtpThread(AThread).ProcessFtpCommand then
              begin
                DoLocalClientData(TIdMappedPortThread(AThread)); //bServer
              end
              else
              begin
                DoLocalClientData(TIdMappedPortThread(AThread)); //bServer
                TIdMappedFtpThread(AThread).FOutboundClient.WriteLn(TIdMappedFtpThread(AThread).FtpCmdLine); //send USRREQ to FtpServer
                TIdMappedFtpThread(AThread).ProcessDataCommand;
              end;
            end;
          until AThread.Connection.InputBuffer.Size <= 0;
        end; //if >-1
      end; //if select
    end; //with
  finally
    if not TIdMappedFtpThread(AThread).FOutboundClient.Connected then
    begin
      DoOutboundDisconnect(TIdMappedPortThread(AThread));
    end; //if
  end; //tryf
end; //TIdMappedPortTCP.DoExecute

{ TIdMappedFtpThread }

constructor TIdMappedFtpThread.Create(ACreateSuspended: Boolean);
begin
  inherited Create(ACreateSuspended);
  FHost := '';    {Do not Localize}
  FoutboundHost := '';    {Do not Localize}
  FPort := 0; //system choice
  FoutboundPort := 0;
end; //TIdMappedFtpThread.Create

procedure TIdMappedFtpThread.CreateDataChannelThread;
begin
  FDataChannelThread := TIdMappedFtpDataThread.Create(SELF);
  //  FDataChannelThread.OnException := TIdTCPServer(FConnection.Server).OnException;
end; //

procedure TIdMappedFtpThread.ProcessDataCommand;
begin
  if IsDataCommand(FFtpCommand) then
  begin
    FDataChannelThread.Start;
  end;
end; //

function TIdMappedFtpThread.ProcessFtpCommand: Boolean;
  procedure ParsePort;
  var
    LLo, LHi: Integer;
    LParm: string;
  begin
    //1.setup local
    LParm := FtpParams;
    Host := '';    {Do not Localize}
    Host := Host + Fetch(LParm, ',') + '.'; //h1    {Do not Localize}
    Host := Host + Fetch(LParm, ',') + '.'; //h2    {Do not Localize}
    Host := Host + Fetch(LParm, ',') + '.'; //h3    {Do not Localize}
    Host := Host + Fetch(LParm, ','); //h4    {Do not Localize}

    LLo := StrToInt(Fetch(LParm, ',')); //p1    {Do not Localize}
    LHi := StrToInt(LParm); //p2
    Port := (LLo * 256) + LHi;

    CreateDataChannelThread;
    DataChannelThread.FConnection := TIdTcpClient.Create(nil);
    with TIdTcpClient(DataChannelThread.FConnection) do
    begin
      Host := Self.Host;
      Port := Self.Port;
    end;

    //2.setup remote (mapped)
    ProcessOutboundDc(FALSE);

    //3. send ack to client
    Connection.WriteLn('200 ' + Format(RSFTPCmdSuccessful, ['PORT']));    {Do not Localize}
  end; //ParsePort

  procedure ParsePasv;
  var
    LParm: string;
  begin
    //1.setup local
    Host := TIdIOHandlerSocket(Connection.IOHandler).Binding.IP;

    CreateDataChannelThread;
    DataChannelThread.FConnection := TIdSimpleServer.Create(nil);
    with TIdSimpleServer(DataChannelThread.FConnection) do
    begin
      BoundIP := Self.Host;
      BoundPort := Self.Port;
      BeginListen;
      Self.Host := Binding.IP;
      Self.Port := Binding.Port;
      LParm := StringReplace(Self.Host, '.', ',', [rfReplaceAll]);    {Do not Localize}
      LParm := LParm + ',' + IntToStr(Self.Port div 256) + ',' + IntToStr(Self.Port mod 256);    {Do not Localize}
    end;

    //2.setup remote (mapped)
    ProcessOutboundDc(TRUE);

    //3. send ack to client
    Connection.WriteLn('227 ' + Format(RSFTPPassiveMode, [LParm]));    {Do not Localize}
  end; //ParsePasv

begin //===ProcessFtpCommand
  Result := FALSE; //comamnd NOT processed
  FFtpCommand := UpperCase(FFtpCommand);
  if FFtpCommand = 'PORT' then    {Do not Localize}
  begin
    ParsePort;
    Result := TRUE;
  end
  else if FFtpCommand = 'PASV' then    {Do not Localize}
  begin
    ParsePasv;
    Result := TRUE;
  end;
end; //ProcessFtpCommand

procedure TIdMappedFtpThread.ProcessOutboundDc(const APASV: Boolean);
var
  Mode: TIdMappedFtpOutboundDcMode;

  procedure SendPort;
  begin
    OutboundHost := (OutboundClient.IOHandler as TIdIOHandlerSocket).Binding.IP;

    DataChannelThread.FOutboundClient := TIdSimpleServer.Create(nil);
    with TIdSimpleServer(DataChannelThread.FOutboundClient) do
    begin
      BoundIP := Self.OutboundHost;
      BoundPort := Self.OutboundPort;
      BeginListen;
      Self.OutboundHost := Binding.IP;
      Self.OutboundPort := Binding.Port;
    end; //with
    OutboundClient.SendCmd('PORT ' + StringReplace(OutboundHost, '.', ',',    {Do not Localize}
      [rfReplaceAll]) + ',' + IntToStr(OutboundPort div 256) + ',' +    {Do not Localize}
      IntToStr(OutboundPort mod 256), [200]);
  end; //SendPort

  procedure SendPasv;
  var
    i, bLeft, bRight: integer;
    s: string;
  begin
    OutboundClient.SendCmd('PASV', 227);    {Do not Localize}
    s := Trim(OutboundClient.LastCmdResult.Text[0]);

    // Case 1 (Normal)
    // 227 Entering passive mode(100,1,1,1,23,45)
    bLeft := IndyPos('(', s);    {Do not Localize}
    bRight := IndyPos(')', s);    {Do not Localize}
    if (bLeft = 0) or (bRight = 0) then
    begin
      // Case 2
      // 227 Entering passive mode on 100,1,1,1,23,45
      bLeft := RPos(#32, s);
      s := Copy(s, bLeft + 1, Length(s) - bLeft);
    end
    else
    begin
      s := Copy(s, bLeft + 1, bRight - bLeft - 1);
    end;
    FOutboundHost := '';    {Do not Localize}
    for i := 1 to 4 do
    begin
      FOutboundHost := FOutboundHost + '.' + Fetch(s, ',');    {Do not Localize}
    end;
    System.Delete(FOutboundHost, 1, 1);
    // Determine port
    FOutboundPort := StrToInt(Fetch(s, ',')) * 256;    {Do not Localize}
    FOutboundPort := FOutboundPort + StrToInt(Fetch(s, ','));    {Do not Localize}

    DataChannelThread.FOutboundClient := TIdTcpCLient.Create(nil);
    with TIdTcpCLient(DataChannelThread.FOutboundClient) do
    begin
      Host := Self.FOutboundHost;
      Port := Self.OutboundPort;
    end; //with
  end; //SendPasv

begin //===ProcessOutboundDc
  Mode := TIdMappedFtp(Connection.Server).OutboundDcMode;
  if Mode = fdcmClient then
  begin
    if APASV then
      Mode := fdcmPasv
    else
      Mode := fdcmPort;
  end; //if

  if Mode = fdcmPasv then
  begin //PASV (IfFtp.pas)
    SendPasv;
  end
  else
  begin //PORT
    SendPort;
  end;
end; //TIdMappedFtpThread.ProcessOutboundDc

{TODO: procedure TIdMappedFtpThread.FreeDataChannelThread;
Begin
  if Assigned(FDataChannelThread) then begin
    //TODO: здесь надо Disconnect
    FDataChannelThread.Terminate;
    FDataChannelThread:=NIL;
  end;
End;//FreeDataChannelThread}

function TIdMappedFtpThread.GetFtpCmdLine: string;
begin
  if Length(FFtpParams) > 0 then
    Result := FFtpCommand + ' ' + FFtpParams    {Do not Localize}
  else
    Result := FFtpCommand;
end; //TIdMappedFtpThread.GetFtpCmdLine

{ TIdMappedFtpDataThread }

procedure TIdMappedFtpDataThread.BeforeRun;
begin
  inherited BeforeRun;
  //? Is it normal code?
  // TODO: check error. Send reply to client, send abort to server
  //1.Outbound PASV => connect
  try
    if FOutboundClient is TIdTcpClient then
      TIdTcpClient(FOutboundClient).Connect;
  except
    raise;
  end; //trye

  //2.Local PORT => Connect
  try
    if FConnection is TIdTcpClient then
      TIdTcpClient(FConnection).Connect;
  except
    raise;
  end; //trye

  try
    if FConnection is TIdSimpleServer then
      TIdSimpleServer(FConnection).Listen;
  except
    raise;
  end; //trye

  try
    if FOutboundClient is TIdSimpleServer then
      TIdSimpleServer(FOutboundClient).Listen;
  except
    on E: Exception do
    begin

      raise;
    end; //
  end; //trye
end; //TIdMappedFtpDataThread.BeforeRun

constructor TIdMappedFtpDataThread.Create(AMappedFtpThread: TIdMappedFtpThread);
begin
  inherited Create(TRUE);
  FMappedFtpThread := AMappedFtpThread; //owner
  StopMode := smTerminate;
  FreeOnTerminate := TRUE;
  FReadList := TList.Create;
end; //TIdMappedFtpDataThread.Create

destructor TIdMappedFtpDataThread.Destroy;
begin
  FreeAndNIL(FOutboundClient);
  FreeAndNIL(FConnection);
  FreeAndNIL(FReadList);
  inherited Destroy;
end; //TIdMappedFtpDataThread.Destroy

procedure TIdMappedFtpDataThread.Run;
var
  LConnectionHandle: TObject;
  LOutBoundHandle: TObject;
begin
  try
    try
      LConnectionHandle := TObject(
        (Connection.IOHandler as TIdIOHandlerSocket).Binding.Handle);
      LOutBoundHandle := TObject(
        (FOutboundClient.IOHandler as TIdIOHandlerSocket).Binding.Handle);
      with FReadList do
      begin
        Clear;
        Add(LConnectionHandle);
        Add(LOutBoundHandle);

        if GStack.WSSelect(FReadList, nil, nil, IdTimeoutInfinite) > 0 then
        begin
          //TODO: Make a select list that also has a function to check of handles
          if IndexOf(LConnectionHandle) > -1 then
          begin
            FNetData := Connection.CurrentReadBuffer;
            if Length(FNetData) > 0 then
            begin
              // TODO: DoLocalClientData(TIdMappedPortThread(AThread));//bServer
              FOutboundClient.Write(FNetData);
            end; //if
          end;
          if IndexOf(LOutBoundHandle) > -1 then
          begin
            FNetData := FOutboundClient.CurrentReadBuffer;
            if Length(FNetData) > 0 then
            begin
              // TODO: DoOutboundClientData(TIdMappedPortThread(AThread));
              FConnection.Write(FNetData);
            end; //if
          end;
        end; //if select
      end; //with
    finally
      if not FOutboundClient.Connected then
      begin
        // TODO: DoOutboundDisconnect(TIdMappedPortThread(AThread));
        FConnection.Disconnect; //disconnect local
        Stop;
      end; //if

      if not FConnection.Connected then
      begin
        // TODO: ^^^
        FOutboundClient.Disconnect;
        Stop;
      end; //if
    end; //tryf
  except
    FConnection.Disconnect;
    FOutboundClient.Disconnect;
    Stop;
  end; //trye
end; //TIdMappedFtpDataThread.Run

end.

