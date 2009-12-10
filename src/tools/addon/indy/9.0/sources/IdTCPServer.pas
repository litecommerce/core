{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10367: IdTCPServer.pas 
{
    Rev 1.2    3/22/2003 1:56:40 AM  BGooijen
  Fixed a bug where non-paged memory was leaked when an exception occured in
  TIdListenerThread.Run
}
{
    Rev 1.1    3/21/2003 4:51:50 PM  BGooijen
  Intercept is freed in TIdPeerThread.AfterRun if a ServerIntercept is assigned
  to the server
}
{
{   Rev 1.0    2002.11.12 10:55:14 PM  czhower
}
unit IdTCPServer;

interface

{
Original Author and Maintainer:
 - Chad Z. Hower a.k.a Kudzu
2002-01-01 - Andrew P.Rybin
 - bug fix (MaxConnections, SetActive(FALSE)), TerminateListenerThreads, DoExecute
2002-04-17 - Andrew P.Rybin
 - bug fix: if exception raised in OnConnect, Threads.Remove and ThreadMgr.ReleaseThread are not called
}

uses
  Classes, SysUtils,
  IdComponent, IdException, IdSocketHandle, IdTCPConnection, IdThread, IdThreadMgr,
  IdIOHandlerSocket, IdIOHandler, IdThreadMgrDefault, IdIntercept, IdStackConsts,
  IdGlobal, IdRFCReply, IdServerIOHandler, IdServerIOHandlerSocket;

const
  IdEnabledDefault = True;
  // DO NOT change this defualt (ParseParams). Many servers rely on this
  IdParseParamsDefault = True;
  IdCommandHandlersEnabledDefault = True;
  IdListenQueueDefault = 15;

type
  TIdCommandHandler = class;
  TIdCommand = class;
  TIdPeerThread = class;
  TIdTCPServer = class;
  TIdAfterCommandHandlerEvent = procedure(ASender: TIdTCPServer; AThread: TIdPeerThread) of object;
  TIdBeforeCommandHandlerEvent = procedure(ASender: TIdTCPServer; const AData: string;
    AThread: TIdPeerThread) of object;
  TIdCommandEvent = procedure(ASender: TIdCommand) of object;
  TIdNoCommandHandlerEvent = procedure(ASender: TIdTCPServer; const AData: string;
    AThread: TIdPeerThread) of object;

  TIdCommandHandler = class(TCollectionItem)
  protected
    FCmdDelimiter: Char;
    FCommand: string;
    FData: TObject;
    FDisconnect: boolean;
    FEnabled: boolean;
    FName: string;
    FOnCommand: TIdCommandEvent;
    FParamDelimiter: Char;
    FParseParams: Boolean;
    FReplyExceptionCode: Integer;
    FReplyNormal: TIdRFCReply;
    FResponse: TStrings;
    FTag: integer;
    //
    function GetDisplayName: string; override;
    procedure SetDisplayName(const AValue: string); override;
    procedure SetResponse(AValue: TStrings);
  public
    function Check(const AData: string; AThread: TIdPeerThread): boolean; virtual;
    constructor Create(ACollection: TCollection); override;
    destructor Destroy; override;
    function GetNamePath: string; override;
    function NameIs(ACommand: string): Boolean;
    //
    property Data: TObject read FData write FData;
  published
    property CmdDelimiter: Char read FCmdDelimiter write FCmdDelimiter;
    property Command: string read FCommand write FCommand;
    property Disconnect: boolean read FDisconnect write FDisconnect;
    property Enabled: boolean read FEnabled write FEnabled default IdEnabledDefault;
    property Name: string read FName write FName;
    property OnCommand: TIdCommandEvent read FOnCommand write FOnCommand;
    property ParamDelimiter: Char read FParamDelimiter write FParamDelimiter;
    property ParseParams: Boolean read FParseParams write FParseParams default IdParseParamsDefault;
    property ReplyExceptionCode: Integer read FReplyExceptionCode write FReplyExceptionCode;
    property ReplyNormal: TIdRFCReply read FReplyNormal write FReplyNormal;
    property Response: TStrings read FResponse write SetResponse;
    property Tag: integer read FTag write FTag;
  end;

  TIdCommandHandlers = class(TOwnedCollection)
  protected
    FServer: TIdTCPServer;
    //
    function GetItem(AIndex: Integer): TIdCommandHandler;
    // This is used instead of the OwnedBy property directly calling GetOwner because
    // D5 dies with internal errors and crashes
    function GetOwnedBy: TPersistent;
    procedure SetItem(AIndex: Integer; const AValue: TIdCommandHandler);
  public
    function Add: TIdCommandHandler;
    constructor Create(AServer: TIdTCPServer); reintroduce;
    //
    property Items[AIndex: Integer]: TIdCommandHandler read GetItem write SetItem;
    // OwnedBy is used so as not to conflict with Owner in D6
    property OwnedBy: TPersistent read GetOwnedBy;
    property Server: TIdTCPServer read FServer;
  end;

  TIdCommand = class(TObject)
  protected
    FCommandHandler: TIdCommandHandler;
    FParams: TStrings;
    FPerformReply: Boolean;
    FRawLine: string;
    FReply: TIdRFCReply;
    FResponse: TStrings;
    FThread: TIdPeerThread;
    FUnparsedParams: string;
    //
    procedure DoCommand; virtual;
  public
    constructor Create; virtual;
    destructor Destroy; override;
    procedure SendReply;
    procedure SetResponse(AValue: TStrings);
    //
    property CommandHandler: TIdCommandHandler read FCommandHandler;
    property PerformReply: Boolean read FPerformReply write FPerformReply;
    property Params: TStrings read FParams;
    property RawLine: string read FRawLine;
    property Reply: TIdRFCReply read FReply write FReply;
    property Response: TStrings read FResponse write SetResponse;
    property Thread: TIdPeerThread read FThread;
    property UnparsedParams: string read FUnparsedParams;
  end;

  // This is the thread that listens for incoming connections and spawns
  // new ones to handle each one
  TIdListenerThread = class(TIdThread)
  protected
    FBinding: TIdSocketHandle;
    FServer: TIdTCPServer;
    procedure AfterRun; override;
    procedure Run; override;
  public
    constructor Create(AServer: TIdTCPServer; ABinding: TIdSocketHandle); reintroduce;
   //
    property Binding: TIdSocketHandle read FBinding write FBinding;
    property Server: TIdTCPServer read FServer;
  End;//TIdListenerThread

  TIdTCPServerConnection = class(TIdTCPConnection)
  protected
    FServer: TIdTCPServer;
//    FLastRcvTimeStamp: TDateTime;    //Timestamp of latest received command
//    FProcessingTimeout: boolean;     //To avoid double timeout processing
    //
  public
//    property LastRcvTimeStamp: TDateTime read fLastRcvTimeStamp write fLastRcvTimeStamp;
//    property ProcessingTimeout: boolean read fbProcessingTimeout write fbProcessingTimeout;
//    function Read(const piLen: Integer): string; override;
    constructor Create(AServer: TIdTCPServer); reintroduce;
  published
    property Server: TIdTCPServer read FServer;
  end;

  TIdPeerThread = class(TIdThread)
  protected
    FConnection: TIdTCPServerConnection;
    //
    procedure AfterRun; override;
    procedure BeforeRun; override;
    procedure Cleanup; override;
    // If things need freed, free them in AfterRun so that pooled threads clean themselves up.
    // Only persistent things should be handled in AfterExecute (Destroy)
    procedure Run; override;
  public
    //
    property Connection: TIdTCPServerConnection read FConnection;
  End;//TIdPeerThread

  TIdListenExceptionEvent = procedure(AThread: TIdListenerThread; AException: Exception) of object;
  TIdServerThreadExceptionEvent = procedure(AThread: TIdPeerThread; AException: Exception)
    of object;
  TIdServerThreadEvent = procedure(AThread: TIdPeerThread) of object;

  TIdTCPServer = class(TIdComponent)
  protected
    FActive: Boolean;
    FThreadMgr: TIdThreadMgr;
    FBindings: TIdSocketHandles;
    FCommandHandlers: TIdCommandHandlers;
    FCommandHandlersEnabled: Boolean;
    FCommandHandlersInitialized: Boolean;
    FGreeting: TIdRFCReply;
    FImplicitThreadMgr: Boolean;
    FImplicitIOHandler: Boolean;
    FIntercept: TIdServerIntercept;
    FIOHandler: TIdServerIOHandler;
    FListenerThreads: TThreadList;
    FListenQueue: integer;
    FMaxConnectionReply: TIdRFCReply;
    FMaxConnections: Integer;
    FReplyTexts: TIdRFCReplies;
    FReuseSocket: TIdReuseSocket;
    FTerminateWaitTime: Integer;
    FThreadClass: TIdThreadClass;
    FThreads: TThreadList;
    FOnAfterCommandHandler: TIdAfterCommandHandlerEvent;
    FOnBeforeCommandHandler: TIdBeforeCommandHandlerEvent;
    FOnConnect: TIdServerThreadEvent;
    FOnDisconnect: TIdServerThreadEvent;
    FOnException: TIdServerThreadExceptionEvent;
    FOnExecute: TIdServerThreadEvent;
    FOnListenException: TIdListenExceptionEvent;
    FOnNoCommandHandler: TIdNoCommandHandlerEvent;
    FReplyExceptionCode: Integer;
    FReplyUnknownCommand: TIdRFCReply;
    //
    procedure CheckActive;
    procedure DoAfterCommandHandler(AThread: TIdPeerThread);
    procedure DoBeforeCommandHandler(AThread: TIdPeerThread; const ALine: string);
    procedure DoConnect(AThread: TIdPeerThread); virtual;
    procedure DoDisconnect(AThread: TIdPeerThread); virtual;
    procedure DoException(AThread: TIdPeerThread; AException: Exception);
    function DoExecute(AThread: TIdPeerThread): boolean; virtual;
    procedure DoListenException(AThread: TIdListenerThread; AException: Exception);
    procedure DoOnNoCommandHandler(const AData: string; AThread: TIdPeerThread);
    function GetDefaultPort: integer;
    function GetThreadMgr: TIdThreadMgr;
    procedure InitializeCommandHandlers; virtual;
    procedure Notification(AComponent: TComponent; Operation: TOperation); override;
    procedure SetActive(AValue: Boolean); virtual;
    procedure SetBindings(const AValue: TIdSocketHandles); virtual;
    procedure SetDefaultPort(const AValue: integer); virtual;
    procedure SetIntercept(const AValue: TIdServerIntercept); virtual;
    procedure SetIOHandler(const AValue: TIdServerIOHandler); virtual;
    procedure SetThreadMgr(const AValue: TIdThreadMgr); virtual;
    procedure TerminateAllThreads;
    procedure TerminateListenerThreads; //APR
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Loaded; override;
    //
    property ImplicitIOHandler: Boolean read FImplicitIOHandler;
    property ImplicitThreadMgr: Boolean read FImplicitThreadMgr;
    property ThreadClass: TIdThreadClass read FThreadClass write FThreadClass;
    property Threads: TThreadList read FThreads;
  published
    property Active: Boolean read FActive write SetActive default False;
    property Bindings: TIdSocketHandles read FBindings write SetBindings;
    property CommandHandlers: TIdCommandHandlers read FCommandHandlers write FCommandHandlers;
    property CommandHandlersEnabled: boolean read FCommandHandlersEnabled
      write FCommandHandlersEnabled default IdCommandHandlersEnabledDefault;
    property DefaultPort: integer read GetDefaultPort write SetDefaultPort;
    property Greeting: TIdRFCReply read FGreeting write FGreeting;
    property Intercept: TIdServerIntercept read FIntercept write SetIntercept;
    property IOHandler: TIdServerIOHandler read FIOHandler write SetIOHandler;
    property ListenQueue: integer read FListenQueue write FListenQueue default IdListenQueueDefault;
    property MaxConnectionReply: TIdRFCReply read FMaxConnectionReply write FMaxConnectionReply;
    property MaxConnections: Integer read FMaxConnections write FMaxConnections default 0;
    // Occurs in the context of the peer thread
    property OnAfterCommandHandler: TIdAfterCommandHandlerEvent read FOnAfterCommandHandler
     write FOnAfterCommandHandler;
    // Occurs in the context of the peer thread
    property OnBeforeCommandHandler: TIdBeforeCommandHandlerEvent read FOnBeforeCommandHandler
     write FOnBeforeCommandHandler;
    // Occurs in the context of the peer thread
    property OnConnect: TIdServerThreadEvent read FOnConnect write FOnConnect;
    // Occurs in the context of the peer thread
    property OnExecute: TIdServerThreadEvent read FOnExecute write FOnExecute;
    // Occurs in the context of the peer thread
    property OnDisconnect: TIdServerThreadEvent read FOnDisconnect write FOnDisconnect;
    // Occurs in the context of the peer thread
    property OnException: TIdServerThreadExceptionEvent read FOnException write FOnException;
    property OnListenException: TIdListenExceptionEvent read FOnListenException
      write FOnListenException;
    property OnNoCommandHandler: TIdNoCommandHandlerEvent read FOnNoCommandHandler
      write FOnNoCommandHandler;
    property ReplyExceptionCode: Integer read FReplyExceptionCode write FReplyExceptionCode;
    property ReplyTexts: TIdRFCReplies read FReplyTexts write FReplyTexts;
    property ReplyUnknownCommand: TIdRFCReply read FReplyUnknownCommand write FReplyUnknownCommand;
    property ReuseSocket: TIdReuseSocket read FReuseSocket write FReuseSocket default rsOSDependent;
    property TerminateWaitTime: Integer read FTerminateWaitTime write FTerminateWaitTime
      default 5000;
    property ThreadMgr: TIdThreadMgr read GetThreadMgr write SetThreadMgr;
  end;
  EIdTCPServerError = class(EIdException);
  EIdNoExecuteSpecified = class(EIdTCPServerError);
  EIdTerminateThreadTimeout = class(EIdTCPServerError);

implementation

uses
  IdResourceStrings, IdStack, IdStrings, IdThreadSafe;

{ TIdTCPServer }

procedure TIdTCPServer.CheckActive;
begin
  if Active and (not (csDesigning in ComponentState)) and (not (csLoading in ComponentState))
    then begin
    raise EIdTCPServerError.Create(RSCannotPerformTaskWhileServerIsActive);
  end;
end;

constructor TIdTCPServer.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FBindings := TIdSocketHandles.Create(Self);
  // Before Command handlers
  FReplyTexts := TIdRFCReplies.Create(Self);
  FCommandHandlers := TIdCommandHandlers.Create(Self);
  FCommandHandlersEnabled := IdCommandHandlersEnabledDefault;
  FGreeting := TIdRFCReply.Create(nil);
  FMaxConnectionReply := TIdRFCReply.Create(nil);
  FThreads := TThreadList.Create;
  FThreadClass := TIdPeerThread;
  FReplyUnknownCommand := TIdRFCReply.Create(nil);
  //
  FTerminateWaitTime := 5000;
  FListenQueue := IdListenQueueDefault;
  //TODO: When reestablished, use a sleeping thread instead
//  fSessionTimer := TTimer.Create(self);
end;

destructor TIdTCPServer.Destroy;
begin
  Active := False;

  if Assigned(FIOHandler) and FImplicitIOHandler then begin
    FreeAndNil(FIOHandler);
  end;

  // Destroy bindings first
  FreeAndNil(FBindings);
  //
  FreeAndNil(FReplyUnknownCommand);
  FreeAndNil(FReplyTexts);
  FreeAndNil(FThreads);
  FreeAndNil(FMaxConnectionReply);
  FreeAndNil(FGreeting);
  FreeAndNil(FCommandHandlers);
  inherited Destroy;
end;

procedure TIdTCPServer.DoAfterCommandHandler(AThread: TIdPeerThread);
begin
  if Assigned(OnAfterCommandHandler) then begin
    OnAfterCommandHandler(Self, AThread);
  end;
end;

procedure TIdTCPServer.DoBeforeCommandHandler(AThread: TIdPeerThread; const ALine: string);
begin
  if Assigned(OnBeforeCommandHandler) then begin
    OnBeforeCommandHandler(Self, ALine, AThread);
  end;
end;

procedure TIdTCPServer.DoConnect(AThread: TIdPeerThread);
begin
  ReplyTexts.UpdateText(Greeting);
  AThread.Connection.WriteRFCReply(Greeting);
  if Assigned(OnConnect) then begin
    OnConnect(AThread);
  end;
end;

procedure TIdTCPServer.DoDisconnect(AThread: TIdPeerThread);
begin
  if Assigned(OnDisconnect) then begin
    OnDisconnect(AThread);
  end;
end;

procedure TIdTCPServer.DoException(AThread: TIdPeerThread; AException: Exception);
begin
  if Assigned(OnException) then begin
    OnException(AThread, AException);
  end;
end;

function TIdTCPServer.DoExecute(AThread: TIdPeerThread): boolean;
var
  I,L: integer;
  LLine: string;
begin
  L := CommandHandlers.Count-1;
  if CommandHandlersEnabled and (L >= 0) then begin
    Result := TRUE;
    if AThread.Connection.Connected then begin //APR: was While, but user can disable handlers
      LLine := AThread.Connection.ReadLn;
      // OLX sends blank lines during reset groups and expects no response. Not sure
      // what the RFCs say about blank lines.
      // I telnetted to some newsservers, and they dont respond to blank lines.
      // This unit is core and not NNTP, but we should be consistent.
      if LLine <> '' then begin
        DoBeforeCommandHandler(AThread, LLine);
        try
          i := 0;
          while i<=L do begin
            with CommandHandlers.Items[i] do begin
              if Enabled and Check(LLine, AThread) then begin

                Break;
              end;
            end;
            inc(i);
          end;//while
          if i > L then begin
            DoOnNoCommandHandler(LLine, AThread);
          end;

        finally DoAfterCommandHandler(AThread); end;
      end;//if >''
    end;
  end else begin
    Result := Assigned(OnExecute);
    if Result then begin
      OnExecute(AThread);
    end;
  end;
end;

procedure TIdTCPServer.DoListenException(AThread: TIdListenerThread; AException: Exception);
begin
  if Assigned(FOnListenException) then begin
    FOnListenException(AThread, AException);
  end;
end;

procedure TIdTCPServer.DoOnNoCommandHandler(const AData: string; AThread: TIdPeerThread);
begin
  if Assigned(OnNoCommandHandler) then begin
    OnNoCommandHandler(Self, AData, AThread);
  end else if ReplyUnknownCommand.ReplyExists then begin
    //Do not UpdateText here - in thread. Is done in constructor
    // TODO: wrong command name is frequently required
    AThread.Connection.WriteRFCReply(ReplyUnknownCommand);
  end else begin
    raise EIdTCPServerError.Create(RSNoCommandHandlerFound);
  end;
end;

function TIdTCPServer.GetDefaultPort: integer;
begin
  Result := FBindings.DefaultPort;
end;

procedure TIdTCPServer.Loaded;
begin
  inherited Loaded;
  // Active = True must not be performed before all other props are loaded
  if Active then begin
    FActive := False;
    Active := True;
  end;
end;

procedure TIdTCPServer.Notification(AComponent: TComponent; Operation: TOperation);
begin
  inherited Notification(AComponent, Operation);
  // remove the reference to the linked components if they are deleted
  if (Operation = opRemove) then begin
    if (AComponent = FThreadMgr) then begin
      TerminateAllThreads;
      FThreadMgr := nil;
    end else if (AComponent = FIntercept) then begin
      FIntercept := nil;
    end else if (AComponent = FIOHandler) then begin
      FIOHandler := nil;
    end;
  end;
end;

procedure TIdTCPServer.SetActive(AValue: Boolean);
var
  i: Integer;
  LListenerThread: TIdListenerThread;
begin
  // SG 28/11/01: removed the "try..finally FActive := AValue; end;" wrapper
  // SG 28/11/01: It cause the component to be locked in the "active" state, even if
  // SG 28/11/01: the socket couldn't be bound.
    if (not (csDesigning in ComponentState)) and (FActive <> AValue)
      and (not (csLoading in ComponentState)) then begin
      if AValue then begin
        // InitializeCommandHandlers must be called only at runtime, and only after streaming
        // has occured. This used to be in .Loaded and that worked for forms. It failed
        // for dynamically created instances and also for descendant classes.
        if not FCommandHandlersInitialized then begin
          FCommandHandlersInitialized := True;
          InitializeCommandHandlers;
        end;
        // Set up bindings
        if Bindings.Count = 0 then begin
          Bindings.Add;
        end;

        // Set up ThreadMgr
        ThreadMgr.ThreadClass := ThreadClass;

        // Setup IOHandler

        if not Assigned(FIOHandler) then begin
          IOHandler := TIdServerIOHandlerSocket.Create(self);
          FImplicitIOHandler := true;
        end;
        // Update reply texts for "global" replies
        ReplyTexts.UpdateText(ReplyUnknownCommand);
        ReplyTexts.UpdateText(MaxConnectionReply);
        // Set up listener threads

        IOHandler.Init;
        i := 0;
        try
          while i < Bindings.Count do begin
            with Bindings[i] do begin
              AllocateSocket;
              if (FReuseSocket = rsTrue) or ((FReuseSocket = rsOSDependent) and (GOSType = otLinux))
                then begin
                SetSockOpt(Id_SOL_SOCKET, Id_SO_REUSEADDR, PChar(@Id_SO_True), SizeOf(Id_SO_True));
              end;
              Bind;
            end;
            Inc(i);
          end;
        except
          Dec(i); // the one that failed doesn't need to be closed
          while i >= 0 do begin
            Bindings[i].CloseSocket;
            Dec(i);
          end;
          FActive := True;
          SetActive(False); // allow descendants to clean up
          raise;
        end;
        FListenerThreads := TThreadList.Create;
        for i := 0 to Bindings.Count - 1 do begin
          Bindings[i].Listen(FListenQueue);
          LListenerThread := TIdListenerThread.Create(Self, Bindings[i]);
          FListenerThreads.Add(LListenerThread);
          LListenerThread.Start;
        end;

      end else begin
        TerminateListenerThreads; 
        // Tear down ThreadMgr
        try
          TerminateAllThreads;
        finally
          if ImplicitThreadMgr and TIdThreadSafeList(Threads).IsCountLessThan(1) then begin // DONE -oAPR: BUG! Threads still live, Mgr dead ;-(
            FreeAndNil(FThreadMgr);
            FImplicitThreadMgr := False;
          end;
        end;//tryf
      end;
    end;
  FActive := AValue;
end;

procedure TIdTCPServer.SetBindings(const AValue: TIdSocketHandles);
begin
  FBindings.Assign(AValue);
end;

procedure TIdTCPServer.SetDefaultPort(const AValue: integer);
begin
  FBindings.DefaultPort := AValue;
end;

procedure TIdTCPServer.SetIntercept(const AValue: TIdServerIntercept);
begin
  FIntercept := AValue;
  // Add self to the intercept's notification list
  if assigned(FIntercept) then
  begin
    FIntercept.FreeNotification(Self);
  end;
end;

procedure TIdTCPServer.SetThreadMgr(const AValue: TIdThreadMgr);
begin
  if ImplicitThreadMgr then
  begin
    // Free the default Thread manager
    FreeAndNil(FThreadMgr);
    FImplicitThreadMgr := false;
  end;

  FThreadMgr := AValue;
  // Ensure we will be notified when the component is freed, even is it's on
  // another form
  if AValue <> nil then begin
    AValue.FreeNotification(self);
  end;
end;

procedure TIdTCPServer.SetIOHandler(const AValue: TIdServerIOHandler);
begin
  if Assigned(FIOHandler) and FImplicitIOHandler then begin
    FImplicitIOHandler := false;
    FreeAndNil(FIOHandler);
  end;
  FIOHandler := AValue;
  if AValue <> nil then begin
    AValue.FreeNotification(self);
  end;
end;

//APR-011207: for safe-close Ex: SQL Server ShutDown 1) stop listen 2) wait until all clients go out
procedure TIdTCPServer.TerminateListenerThreads;
var
  i: Integer;
  LListenerThread: TIdListenerThread;
  LListenerThreads: TList;
Begin
  if Assigned(FListenerThreads) then begin
    LListenerThreads := FListenerThreads.LockList;
    try
      for i:= 0 to LListenerThreads.Count - 1 do begin
        LListenerThread := TIdListenerThread(LListenerThreads[i]);
        with LListenerThread do begin
          // Stop listening
          Terminate;
          Binding.CloseSocket;
          // Tear down Listener thread
          WaitFor;
          Free;
        end;
      end;
    finally FListenerThreads.UnlockList; end;
    FreeAndNil(FListenerThreads);
  end;//if
End;//TerminateListenerThreads

procedure TIdTCPServer.TerminateAllThreads;
const
  LSleepTime: Integer = 250;
var
  i: Integer;
  LThreads: TList;
  LTimedOut: Boolean;
begin
  // Threads will be nil if exception happens during start up, such as trying to bind to a port
  // that is already in use.
  if Assigned(Threads) then begin
    // This will provide us with posibility to call AThread.Notification in OnDisconnect event handler
    // in order to access visual components. They can add notifications after the list has been
    // unlocked, and before/while TerminateThreads is called
    LThreads := Threads.LockList; try
      for i := 0 to LThreads.Count - 1 do begin
        with TIdPeerThread(LThreads[i]) do begin
          Connection.DisconnectSocket;
        end;
      end;
    finally Threads.UnlockList; end;
    // Must wait for all threads to terminate, as they access the server and bindings. If this
    // routine is being called from the destructor, this can cause AVs
    //
    // This method is used instead of:
    //  -Threads.WaitFor. Since they are being destroyed thread. WaitFor could AV. And Waiting for
    //   Handle produces different code for different OSs, and using common code has troubles
    //   as the handles are quite different.
    //  -Last thread signaling
    // ThreadMgr.TerminateThreads(TerminateWaitTime);

    LTimedOut := True;
    for i := 1 to (TerminateWaitTime div LSleepTime) do begin
      if TIdThreadSafeList(Threads).IsCountLessThan(1) then begin
        LTimedOut := False;
        Break;
      end;
      Sleep(LSleepTime); // moved here so not sleeping if no threads
    end;
    if LTimedOut then begin
      raise EIdTerminateThreadTimeout.Create(RSTerminateThreadTimeout);
    end;
  end;
End;//TerminateAllThreads

function TIdTCPServer.GetThreadMgr: TIdThreadMgr;
begin
  if (not (csDesigning in ComponentState)) and (not Assigned(FThreadMgr)) then
  begin
    // Set up ThreadMgr
    ThreadMgr := TIdThreadMgrDefault.Create(Self);
    FImplicitThreadMgr := true;
  end;
  Result := FThreadMgr;
end;

procedure TIdTCPServer.InitializeCommandHandlers;
begin
end;

{ TIdListenerThread }

procedure TIdListenerThread.AfterRun;
begin
  inherited AfterRun;
  // Close just your own binding. The rest will be closed
  // from their coresponding threads
  FBinding.CloseSocket;
end;

constructor TIdListenerThread.Create(AServer: TIdTCPServer; ABinding: TIdSocketHandle);
begin
  inherited Create;
  FBinding := ABinding;
  FServer := AServer;
end;

procedure TIdListenerThread.Run;
var
  LIOHandler: TIdIOHandler;
  LPeer: TIdTCPServerConnection;
  LThread: TIdPeerThread;
begin
  try
    if Assigned(Server) then begin  // This is temporary code just to test one exception
      while True do begin
        LThread := nil;
        LPeer := TIdTCPServerConnection.Create(Server);
        LIOHandler := Server.IOHandler.Accept(Binding.Handle, SELF);
        if LIOHandler = nil then begin
          FreeAndNil(LPeer);
          Stop;
          Exit;
        end
        else begin
          LThread := TIdPeerThread(Server.ThreadMgr.GetThread);
          LThread.FConnection := LPeer;
          LThread.FConnection.IOHandler := LIOHandler;
          LThread.FConnection.FFreeIOHandlerOnDisconnect := true;
        end;

        // LastRcvTimeStamp := Now;  // Added for session timeout support
        // ProcessingTimeout := False;
        if (Server.MaxConnections > 0) and // Check MaxConnections
          NOT TIdThreadSafeList(Server.Threads).IsCountLessThan(Server.MaxConnections)
        then begin
          //Do not UpdateText here - in thread. Is done in constructor
          LPeer.WriteRFCReply(Server.MaxConnectionReply);
          LPeer.Disconnect;
          Server.ThreadMgr.ReleaseThread(LThread); // Give the thread back to the thread-manager
        end else begin
          Server.Threads.Add(LThread); //APR
          // Start Peer Thread
          LThread.Start;
          Break;
        end;
      end;
    end;
  except
    on E: Exception do begin
      if Assigned(LThread) then begin
        FreeAndNil(LThread);
      end;
      if Assigned(LPeer) then begin
        if not Assigned(LPeer.IOHandler) then begin
          FreeAndNil(LIOHandler);
        end;
        FreeAndNil(LPeer);
      end;
      Server.DoListenException(Self, E);
    end;
  end;
End;

{ TIdTCPServerConnection }

constructor TIdTCPServerConnection.Create(AServer: TIdTCPServer);
begin
  inherited Create(nil);
  FServer := AServer;
end;

{ TIdPeerThread }

procedure TIdPeerThread.BeforeRun;
begin
  try
    if Assigned(Connection.IOHandler) then begin
      Connection.IOHandler.AfterAccept;
    end
    else begin
      raise EIdTCPServerError.Create('');
    end;
  except
    Terminate; //APR: was FreeOn Terminate := True; ?! It is ThreadMgr work
    raise;
  end;
  if Assigned(Connection.Server.Intercept) then begin
    Connection.Intercept := Connection.Server.Intercept.Accept(Connection);
  end;
  Connection.Server.DoConnect(Self);

  // Stop this thread if we were disconnected
  if not Connection.Connected then begin
    Stop;
  end;
end;

procedure TIdPeerThread.AfterRun;
begin
  with Connection.Server do begin
    DoDisconnect(Self);
    if Assigned(Connection.Server.Intercept) then begin
      Connection.Intercept.free;
      Connection.Intercept:=nil;
    end;
  end;
end;

procedure TIdPeerThread.Cleanup;
begin
  inherited Cleanup;
  if Assigned(FConnection) then begin
    if Assigned(FConnection.Server) then begin
      { Remove is not neede if we are going to use only ActiveThreads;  Threads.Remove(Self);}
      with Connection.Server do begin
        if Assigned(Threads) then begin
          Threads.Remove(SELF);
        end;
        //from AfterRun
        if Assigned(ThreadMgr) then begin
          ThreadMgr.ReleaseThread(Self);
        end;
      end;//with
    end;//if
    FreeAndNil(FConnection);
  end;
  // Other things are done in AfterExecute&destructor
End;//TIdPeerThread.Cleanup

procedure TIdPeerThread.Run;
begin
  try
    try
      if not Connection.Server.DoExecute(Self) then begin
        raise EIdNoExecuteSpecified.Create(RSNoExecuteSpecified);
      end;
    except
      // We handle these seperate as after these we expect .Connected to be false
      // and caught below. Other exceptions are caught by the outer except.
       on E: EIdSocketError do begin
        Connection.Server.DoException(Self, E);
        case E.LastError of
          Id_WSAECONNABORTED // WSAECONNABORTED - Other side disconnected
           , Id_WSAECONNRESET:
            Connection.Disconnect;
        end;
      end;
      on E: EIdClosedSocket do begin
        // No need to disconnect - this error means we are already disconnected or never connected 
        Connection.Server.DoException(Self, E);
      end;
      on E: EIdConnClosedGracefully do begin
        // No need to Disconnect, .Connected will detect a graceful close
        Connection.Server.DoException(Self, E);
      end;
    end;
    // If connection lost, stop thread
    if not Connection.Connected then begin
      Stop;
    end;
  // Master catch. Catch errors not known about above, or errors in Stop, etc.
  // Must be a master catch to prevent thread from doing nothing.
  except
    on E: Exception do begin
      Connection.Server.DoException(Self, E);
      raise;
    end;
  end;
end;

{ TIdCommandHandlers }

function TIdCommandHandlers.Add: TIdCommandHandler;
begin
  Result := TIdCommandHandler(inherited Add);
end;

constructor TIdCommandHandlers.Create(AServer: TIdTCPServer);
begin
  inherited Create(AServer, TIdCommandHandler);
  FServer := AServer;
end;

function TIdCommandHandlers.GetItem(AIndex: Integer): TIdCommandHandler;
begin
  Result := TIdCommandHandler(inherited Items[AIndex]);
end;

function TIdCommandHandlers.GetOwnedBy: TPersistent;
begin
  Result := GetOwner;
end;

procedure TIdCommandHandlers.SetItem(AIndex: Integer; const AValue: TIdCommandHandler);
begin
  inherited SetItem(AIndex, AValue);
end;

{ TIdCommandHandler }

function TIdCommandHandler.Check(const AData: string; AThread: TIdPeerThread): boolean;
// AData is not preparsed and is completely left up to the command handler. This will allow for
// future expansion such as wild cards etc, and allow the logic to properly remain in each of the
// command handler implementations. In the future there may be a base type and multiple descendants
var
  LUnparsedParams: string;
begin
  LUnparsedParams := '';
  Result := AnsiSameText(AData, Command); // Command by itself
  if not Result then begin
    if CmdDelimiter <> #0 then begin
      Result := AnsiSameText(Copy(AData, 1, Length(Command) + 1), Command + CmdDelimiter);
      LUnparsedParams := Copy(AData, Length(Command) + 2, MaxInt);
    end else begin
      // Dont strip any part of the params out.. - just remove the command purely on length and
      // no delim
      Result := AnsiSameText(Copy(AData, 1, Length(Command)), Command);
      LUnparsedParams := Copy(AData, Length(Command) + 1, MaxInt);
    end;
  end;
  if Result then begin
    with TIdCommand.Create do try
      FRawLine := AData;
      FCommandHandler := Self;
      FThread := AThread;
      FUnparsedParams := LUnparsedParams;
      Params.Clear;
      if ParseParams then begin
        if Self.FParamDelimiter = #32 then begin
          SplitColumnsNoTrim(LUnparsedParams,Params,#32);
        end else begin
          SplitColumns(LUnparsedParams,Params,Self.FParamDelimiter);
        end;
      end;
      PerformReply := True;
      Reply.Assign(Self.ReplyNormal);
      while True do begin
        try
          DoCommand;
        except
          on E: Exception do begin
            if PerformReply then begin
              if Self.ReplyExceptionCode > 0 then begin
                Reply.SetReply(ReplyExceptionCode, E.Message);
                SendReply;
              end else if AThread.Connection.Server.ReplyExceptionCode > 0 then begin
                Reply.SetReply(AThread.Connection.Server.ReplyExceptionCode, E.Message);
                SendReply;
              end else begin
                raise;
              end;
              Break;
            end else begin
              raise;
            end;
          end;
        end;
        if PerformReply then begin
          SendReply;
        end;
        if Response.Count > 0 then begin
          AThread.Connection.WriteRFCStrings(Response);
        end else if CommandHandler.Response.Count > 0 then begin
          AThread.Connection.WriteRFCStrings(CommandHandler.Response);
        end;
        Break;
      end;
    finally
      try
        if Disconnect then begin
          AThread.Connection.Disconnect;
        end;
      finally
        FREE;
      end;
    end;//tryf
  end;
end;

constructor TIdCommandHandler.Create(ACollection: TCollection);
begin
  inherited Create(ACollection);
  FCmdDelimiter := ' ';
  FEnabled := IdEnabledDefault;
  FName := ClassName + IntToStr(ID);
  FParamDelimiter := #32;
  FParseParams := IdParseParamsDefault;
  FReplyNormal := TIdRFCReply.Create(nil);
  FResponse := TStringList.Create;
end;

destructor TIdCommandHandler.Destroy;
begin
  FreeAndNil(FResponse);
  FreeAndNil(FReplyNormal);
  inherited Destroy;
end;

function TIdCommandHandler.GetDisplayName: string;
begin
  if Command = '' then begin
    Result := Name;
  end else begin
    Result := Command;
  end;
end;


function TIdCommandHandler.GetNamePath: string;
begin
  if Collection <> nil then begin
    // OwnedBy is used because D4/D5 dont expose Owner on TOwnedCollection but D6 does
    Result := TIdCommandHandlers(Collection).OwnedBy.GetNamePath + '.' + Name;
  end else begin
    Result := inherited GetNamePath;
  end;
end;

function TIdCommandHandler.NameIs(ACommand: string): Boolean;
begin
  Result := AnsiSameText(ACommand, FName);
end;

procedure TIdCommandHandler.SetDisplayName(const AValue: string);
begin
  FName := AValue;
  inherited SetDisplayName(AValue);
end;

procedure TIdCommandHandler.SetResponse(AValue: TStrings);
begin
  FResponse.Assign(AValue);
end;

{ TIdCommand }

constructor TIdCommand.Create;
begin
  inherited Create;
  FParams := TStringList.Create;
  FReply := TIdRFCReply.Create(nil);
  FResponse := TStringList.Create;
end;

destructor TIdCommand.Destroy;
begin
  FreeAndNil(FReply);
  FreeAndNil(FResponse);
  FreeAndNil(FParams);
  inherited Destroy;
end;

procedure TIdCommand.DoCommand;
begin
  if Assigned(CommandHandler.OnCommand) then begin
    CommandHandler.OnCommand(Self);
  end;
end;

procedure TIdCommand.SendReply;
begin
  PerformReply := False;
  TIdCommandHandlers(CommandHandler.Collection).Server.ReplyTexts.UpdateText(Reply);
  Thread.Connection.WriteRFCReply(Reply);
end;

procedure TIdCommand.SetResponse(AValue: TStrings);
begin
  FResponse.Assign(AValue);
end;

end.

