{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10379: IdThreadMgr.pas 
{
{   Rev 1.0    2002.11.12 10:56:08 PM  czhower
}
unit IdThreadMgr;

(*
Changes

02 Oct 2001 - Allen O'Neill - Added support for thread priority - new property Threadpriority, new line added to OnCreate    {Do not Localize}
*)

interface

uses
  Classes,
  IdException, IdBaseComponent, IdGlobal, IdThread,
  SyncObjs;

type
  TIdThreadMgr = class(TIdBaseComponent)
  protected
    FActiveThreads: TThreadList;
    FThreadClass: TIdThreadClass;
    FThreadPriority: TIdThreadPriority;
  public
    constructor Create(AOwner: TComponent); override;
    function CreateNewThread: TIdThread; virtual;
    destructor Destroy; override;
    function GetThread: TIdThread; virtual; abstract;
    procedure ReleaseThread(AThread: TIdThread); virtual; abstract;
    procedure TerminateThreads; virtual;
    //
    property ActiveThreads: TThreadList read FActiveThreads;
    property ThreadClass: TIdThreadClass read FThreadClass write FThreadClass;
    property ThreadPriority: TIdThreadPriority read FThreadPriority
     write FThreadPriority default tpNormal;
  end;

  EIdThreadMgrError = class(EIdException);
  EIdThreadClassNotSpecified = class(EIdThreadMgrError);

implementation

uses
  IdResourceStrings, IdTCPServer,
  SysUtils;

{ TIdThreadMgr }

constructor TIdThreadMgr.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FActiveThreads := TThreadList.Create;
  FThreadPriority := tpNormal;
end;

function TIdThreadMgr.CreateNewThread: TIdThread;
begin
  if ThreadClass = nil then begin
    raise EIdThreadClassNotSpecified.create(RSThreadClassNotSpecified);
  end;
  Result := TIdThreadClass(ThreadClass).Create;
  SetThreadPriority(Result, ThreadPriority);
end;

destructor TIdThreadMgr.Destroy;
begin
  FreeAndNil(FActiveThreads);
  inherited Destroy;
end;

procedure TIdThreadMgr.TerminateThreads;
begin
  //
end;

end.
