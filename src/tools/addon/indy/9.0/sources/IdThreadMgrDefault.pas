{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10381: IdThreadMgrDefault.pas 
{
{   Rev 1.0    2002.11.12 10:56:14 PM  czhower
}
unit IdThreadMgrDefault;

interface

uses
  IdThread, IdThreadMgr;

type
  TIdThreadMgrDefault = class(TIdThreadMgr)
  public
    function GetThread: TIdThread; override;
    procedure ReleaseThread(AThread: TIdThread); override;
  end;

implementation

uses
  IdGlobal;

{ TIdThreadMgrDefault }

function TIdThreadMgrDefault.GetThread: TIdThread;
begin
  Result := CreateNewThread;
  ActiveThreads.Add(result);
end;

procedure TIdThreadMgrDefault.ReleaseThread(AThread: TIdThread);
begin
  with ActiveThreads.LockList do try    //   To avoid ReleaseThread-code is
    if IndexOf(AThread)=-1 then exit;   //   executed multiple times, because
  finally                               //   AThread.Free call ReleaseThread
    ActiveThreads.UnlockList;           //   again. Now we will detect the 2nd
  end;                                  //   time, and jump out of the routine
  ActiveThreads.Remove(AThread);        //   here.

  if not IsCurrentThread(AThread) then begin
    // Test suspended and not stopped - it may be in the process of stopping.
    if not AThread.Suspended then begin
      AThread.TerminateAndWaitFor;
    end;
    AThread.Free;
  end else begin
    AThread.FreeOnTerminate := True;
    AThread.Terminate; //APR: same reason as MgrPool. ELSE threads leak if smSuspend
  end;
end;

end.

