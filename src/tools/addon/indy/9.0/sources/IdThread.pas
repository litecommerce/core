{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10375: IdThread.pas 
{
    Rev 1.2    4/21/2003 8:23:48 PM  BGooijen
  Changed Handle to ThreadID
}
{
    Rev 1.1    3/22/2003 1:56:42 AM  BGooijen
  Fixed a bug where non-paged memory was leaked when an exception occured in
  TIdListenerThread.Run
}
{
{   Rev 1.0    2002.11.12 10:55:54 PM  czhower
}
unit IdThread;

{
2002-03-12 -Andrew P.Rybin
  -TerminatingExceptionClass,SynchronizeEx
}
{$I IdCompilerDefines.inc}

interface

uses
  Classes,
  IdException,
  IdGlobal,
  SysUtils, SyncObjs;

type
  EIdThreadException = class(EIdException);
  EIdThreadTerminateAndWaitFor = class(EIdThreadException);

  TIdThreadStopMode = (smTerminate, smSuspend);
  TIdThread = class;
  TIdExceptionThreadEvent = procedure(AThread: TIdThread; AException: Exception) of object;
  TIdNotifyThreadEvent = procedure(AThread: TIdThread) of object;
  TIdSynchronizeThreadEvent = procedure(AThread: TIdThread; AData: Pointer) of object;

  // Expose protected members
  TIdBaseThread = class(TThread)
  public
    procedure Synchronize(Method: TThreadMethod); overload;
    procedure Synchronize(Method: TMethod); overload;
    //
    property  ReturnValue;
    property  Terminated;
  End;//TIdBaseThread

  TIdThread = class(TIdBaseThread)
  protected
    FData: TObject;
    FLock: TCriticalSection;
    FStopMode: TIdThreadStopMode;
    FStopped: Boolean;
    FTerminatingException: string;
    FTerminatingExceptionClass: TClass;
    FOnException: TIdExceptionThreadEvent;
    FOnStopped: TIdNotifyThreadEvent;
    //
    procedure AfterRun; virtual; //3* Not abstract - otherwise it is required
    procedure AfterExecute; virtual;//5 Not abstract - otherwise it is required
    procedure BeforeExecute; virtual;//1 Not abstract - otherwise it is required
    procedure BeforeRun; virtual; //2* Not abstract - otherwise it is required
    procedure Cleanup; virtual;//4*
    procedure DoException (AException: Exception); virtual;
    procedure DoStopped; virtual;
    procedure Execute; override;
    function  GetStopped: Boolean;
    procedure Run; virtual; abstract;
  public
    constructor Create(ACreateSuspended: Boolean = True); virtual;
    destructor Destroy; override;
    procedure Start; virtual;
    procedure Stop; virtual;

    // Here to make virtual
    procedure Terminate; virtual;
    procedure TerminateAndWaitFor; virtual;
    //
    property Data: TObject read FData write FData;
    property StopMode: TIdThreadStopMode read FStopMode write FStopMode;
    property Stopped: Boolean read GetStopped;
    // in future versions (D6+) we must move to TThread.FatalException
    property TerminatingException: string read FTerminatingException;
    property TerminatingExceptionClass: TClass read FTerminatingExceptionClass;
    // events
    property OnException: TIdExceptionThreadEvent read FOnException write FOnException;
    property OnStopped: TIdNotifyThreadEvent read FOnStopped write FOnStopped;
  End;//TIdThread

  TIdThreadClass = class of TIdThread;

implementation
uses IdResourceStrings;

var
  GThreadLock: TCriticalSection;
  GThreadCount: Integer;

procedure IncThread(AThread: TIdThread);
Begin
  if NOT Assigned(GThreadLock) then begin
    GThreadLock := TCriticalSection.Create;
  end;
  GThreadLock.Enter;
  inc(GThreadCount);
  GThreadLock.Leave;
End;//IncThread

procedure DecThread(AThread: TIdThread);
Begin
  if Assigned(GThreadLock) then begin
    GThreadLock.Enter;
    dec(GThreadCount);
    GThreadLock.Leave;
  end;
End;//DecThread

procedure WaitAllTerminated;
var
  LDone: Boolean;
Begin
  while Assigned(GThreadLock) do begin
    GThreadLock.Enter;
    LDone := GThreadCount = 0;
    GThreadLock.Leave;
    if LDone then begin
      FreeAndNIL(GThreadLock);
    end
    else begin
      Sleep(5000);
    end;
  end;
End;//WaitAllTerminated

procedure TIdThread.TerminateAndWaitFor;
begin
  if FreeOnTerminate then begin
    raise EIdThreadTerminateAndWaitFor.Create(RSThreadTerminateAndWaitFor); 
  end;
  Terminate;
  Start;
  WaitFor;
end;

procedure TIdThread.BeforeRun;
begin
end;

procedure TIdThread.AfterRun;
begin
end;

procedure TIdThread.BeforeExecute;
begin
end;

procedure TIdThread.AfterExecute;
Begin
end;

procedure TIdThread.Execute;
begin
  try
    try
      BeforeExecute;
      while not Terminated do begin
        if Stopped then begin
          DoStopped;
          // It is possible that either in the DoStopped or from another thread,
          // the thread is restarted, in which case we dont want to restop it.
          if Stopped then begin // DONE: if terminated?
            if Terminated then begin
              Break;
            end;
            Suspend; // Thread manager will revive us
            if Terminated then begin
              Break;
            end;
          end;
        end;

        try
          BeforeRun;
          try
            while not Stopped do begin
              try
                Run;
              except
                on E: Exception do begin
                  Terminate;
                  raise;
                end;
              end;//trye
            end;//while
          finally
            AfterRun;
          end;//tryf
        finally
          Cleanup;
        end;

      end;//while NOT Terminated
    finally
      AfterExecute;
    end;
  except
    on E: Exception do begin
      FTerminatingExceptionClass := E.ClassType;
      FTerminatingException := E.Message;
      DoException(E);
      Terminate;
    end;
  end;//trye
end;

constructor TIdThread.Create(ACreateSuspended: Boolean);
begin
  // Before inherited - inherited creates the actual thread and if not suspeded
  // will start before we initialize
  FStopped := ACreateSuspended;
  FLock := TCriticalSection.Create;
  inherited Create(ACreateSuspended);
  {$IFNDEF VCL6ORABOVE}
    if (ThreadID=0) then begin
      RaiseLastWin32Error;
    end;
  {$ENDIF}
  try
    IncThread(SELF);
  except end;
end;

destructor TIdThread.Destroy;
begin
  FreeOnTerminate := FALSE; //prevent destroy between Terminate & WaitFor
  Terminate;
  inherited Destroy; //+WaitFor!
  try
    Cleanup;
  finally
    try
      FreeAndNil(FLock);
      if ThreadID<>0 then begin // did we ever create a thread?
        DecThread(SELF);
      end;
    except end;
  end;//tryf
end;

procedure TIdThread.Start;
begin
  FLock.Enter; try
    if Stopped then begin
      // Resume is also called for smTerminate as .Start can be used to initially start a
      // thread that is created suspended
      FStopped := Terminated;//FALSE
      Resume;
    end;
  finally FLock.Leave; end;
end;

procedure TIdThread.Stop;
begin
  FLock.Enter;
  try
    if not Stopped then begin
      case FStopMode of
        smTerminate: Terminate;
        // DO NOT suspend here. Suspend is immediate. See Execute for implementation
        smSuspend: ;
      end;
      FStopped := True;
    end;
  finally FLock.Leave; end;
end;

function TIdThread.GetStopped: Boolean;
begin
  if Assigned(FLock) then begin
    FLock.Enter;
    try
      // Suspended may be true if checking stopped from another thread
      Result := Terminated or FStopped or Suspended;
    finally FLock.Leave; end;
  end else begin
    Result := TRUE; //user call Destroy
  end;
End;//GetStopped

procedure TIdThread.DoStopped;
begin
  if Assigned(OnStopped) then begin
    OnStopped(Self);
  end;
end;

procedure TIdThread.DoException (AException: Exception);
Begin
  if Assigned(FOnException) then begin
    FOnException(self, AException);
  end;
end;

procedure TIdThread.Terminate;
begin
  FLock.Enter;
  try
    FStopped := True;
    inherited Terminate;
  finally
    FLock.Leave;
  end;//tryf
end;

procedure TIdThread.Cleanup;
begin
  FreeAndNil(FData);
end;

{ TIdBaseThread }

procedure TIdBaseThread.Synchronize(Method: TThreadMethod);
Begin
  inherited Synchronize(Method);
End;//

procedure TIdBaseThread.Synchronize(Method: TMethod);
Begin
  inherited Synchronize(TThreadMethod(Method));
End;//

initialization
finalization
  WaitAllTerminated;
end.
