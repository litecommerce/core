{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10383: IdThreadMgrPool.pas 
{
{   Rev 1.0    2002.11.12 10:56:22 PM  czhower
}
{
2002-06-23 -Andrew P.Rybin
  -2 deadlock fix (and also in IdThread)
}
unit IdThreadMgrPool;
interface

uses
  Classes,
  IdThread, IdThreadMgr;

type
  TIdThreadMgrPool = class(TIdThreadMgr)
  protected
    FPoolSize: Integer;
    FThreadPool: TThreadList;
    //
    procedure ThreadStopped(AThread: TIdThread);
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    function  GetThread: TIdThread; override;
    procedure ReleaseThread(AThread: TIdThread); override;
    procedure TerminateThreads; override;
  published
    property  PoolSize: Integer read FPoolSize write FPoolSize default 0;
  End;

implementation

uses
  IdGlobal,
  SysUtils;

{ TIdThreadMgrPool }

constructor TIdThreadMgrPool.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FThreadPool := TThreadList.Create;
end;

destructor TIdThreadMgrPool.Destroy;
var
  i: integer;
  LThreads: TList;
begin
  FPoolSize := 0;
  LThreads := FThreadPool.LockList;
  try
    for i:=0 to LThreads.Count-1 do begin
      with TIdThread(LThreads[i]) do begin
        //thread can use ComponentStreamSystem, we must prevent deadlock
        //Best way for IdUser's: calling TcpServer.Active:=FALSE & TerminateThreads from OnDestroy
        FreeOnTerminate := TRUE;
        Terminate;
        Start;
      end;//with
    end;
  finally FThreadPool.UnlockList; end;
  FreeAndNil(FThreadPool);
  inherited Destroy;
end;

function TIdThreadMgrPool.GetThread: TIdThread;
var
  LThreadPool: TList;
begin
  LThreadPool := FThreadPool.LockList;
  try
    if LThreadPool.Count > 0 then begin
      Result := TIdThread(LThreadPool[0]);
      LThreadPool.Delete(0);
    end else begin
      Result := CreateNewThread;
      Result.StopMode := smSuspend;
    end;
  finally FThreadPool.UnlockList; end;
  ActiveThreads.Add(Result);
end;

procedure TIdThreadMgrPool.ReleaseThread(AThread: TIdThread);
var
  LThreadPool: TList;
begin
  with ActiveThreads.LockList do try    //   To avoid ReleaseThread-code is
    if IndexOf(AThread)=-1 then exit;   //   executed multiple times, because
  finally                               //   AThread.Free call ReleaseThread
    ActiveThreads.UnlockList;           //   again. Now we will detect the 2nd
  end;                                  //   time, and jump out of the routine
  ActiveThreads.Remove(AThread);        //   here.

  LThreadPool := FThreadPool.LockList;
  try
  // PoolSize = 0 means that we will keep all active threads in the thread pool
    if ((PoolSize > 0) and (LThreadPool.Count >= PoolSize))
      or AThread.Terminated or AThread.Suspended then begin
      AThread.OnStopped := NIL;
      AThread.FreeOnTerminate := TRUE;
      AThread.Terminate;
      AThread.Start;//if suspended
    end
    else begin
      AThread.OnStopped := ThreadStopped;
      AThread.Stop;//go sleep
    end;
  finally FThreadPool.UnlockList; end;
End;//ReleaseThread

procedure TIdThreadMgrPool.TerminateThreads;
begin
  inherited TerminateThreads;

  with FThreadPool.LockList do
  try
    while Count > 0 do begin
      with TIdThread(Items[0]) do begin
        FreeOnTerminate := TRUE;
        Terminate;
        Start; //Stopped==TRUE because Terminated
      end;//with
      Delete(0);
    end;
  finally
    FThreadPool.UnlockList;
  end;
end;

procedure TIdThreadMgrPool.ThreadStopped(AThread: TIdThread);
begin
  AThread.OnStopped := NIL; //work is done. prevent from unexpected usage
  if Assigned(FThreadPool) and NOT AThread.Terminated then begin
    FThreadPool.Add(AThread);
  end
  else begin
    AThread.Terminate; //abnormal situation: application termination or what?
  end;
end;

end.
