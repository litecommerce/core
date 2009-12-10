{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10377: IdThreadComponent.pas
{
{   Rev 1.2    2003.06.15 6:20:38 PM  czhower
{ Exposed real thread for TIdSync and other purposes.
}
{
{   Rev 1.1    2003.06.15 5:35:02 PM  czhower
{ Fixed OnTerminate. If OnTerminate was set, it would AV when component was
{ created.
}
{
{   Rev 1.0    2002.11.12 10:56:00 PM  czhower
}
{-----------------------------------------------------------------------------
 UnitName: IdThreadComponent
 Author:   Andrew P.Rybin [magicode@mail.ru]
 Creation: 12.03.2002
 Version:  0.1.0
 Purpose:
 History:  Based on my TmcThread
 2002-05-03 -Andrew P.Rybin
   -Stéphane Grobéty (Fulgan) suggestion: component is Data owner, don't
    FreeAndNIL Data property
   -special TThread.OnTerminate support (it is sync-event)
 2002-05-23 -APR
   -right support for Thread terminate
 2002 - Kudzu
   -Original concept with implementation by J Peter
-----------------------------------------------------------------------------}

unit IdThreadComponent;

interface

uses
  Classes, IdBaseComponent,
  IdException, IdGlobal, IdThread,
  SysUtils;

const
  IdThreadComponentDefaultPriority = tpNormal;
  IdThreadComponentDefaultStopMode = smTerminate;

type
  TIdCustomThreadComponent = class;

  TIdExceptionThreadComponentEvent = procedure(Sender: TIdCustomThreadComponent; AException: Exception) of object;
  TIdNotifyThreadComponentEvent = procedure(Sender: TIdCustomThreadComponent) of object;
  //TIdSynchronizeThreadComponentEvent = procedure(Sender: TIdCustomThreadComponent; AData: Pointer) of object;

  TIdCustomThreadComponent = class(TIdBaseComponent)
  protected
    FActive: Boolean;
    FPriority : TIdThreadPriority;
    FStopMode : TIdThreadStopMode;
    FThread: TIdThread;
    //
    FOnAfterExecute: TIdNotifyThreadComponentEvent;
    FOnAfterRun: TIdNotifyThreadComponentEvent;
    FOnBeforeExecute: TIdNotifyThreadComponentEvent;
    FOnBeforeRun: TIdNotifyThreadComponentEvent;
    FOnCleanup: TIdNotifyThreadComponentEvent;
    FOnException: TIdExceptionThreadComponentEvent;
    FOnRun: TIdNotifyThreadComponentEvent;
    FOnStopped: TIdNotifyThreadComponentEvent;
    FOnTerminate: TIdNotifyThreadComponentEvent;
    //
    function  GetActive: Boolean;
    function  GetData: TObject;
    function  GetHandle: THandle;
    function  GetPriority: TIdThreadPriority;
    function  GetReturnValue: Integer;
    function  GetStopMode: TIdThreadStopMode;
    function  GetStopped: Boolean;
    function  GetSuspended: Boolean;
    function  GetTerminatingException: string;
    function  GetTerminatingExceptionClass: TClass;
    function  GetTerminated: Boolean;
    procedure Loaded; override;
    procedure SetActive(const AValue: Boolean); virtual;
    procedure SetData(const AValue: TObject);
    procedure SetOnTerminate(const AValue: TIdNotifyThreadComponentEvent);
    procedure SetPriority(const AValue: TIdThreadPriority);
    procedure SetReturnValue(const AValue: Integer);
    procedure SetStopMode(const AValue: TIdThreadStopMode);

    // event triggers
    procedure DoAfterExecute; virtual;
    procedure DoAfterRun; virtual;
    procedure DoBeforeExecute; virtual;
    procedure DoBeforeRun; virtual;
    procedure DoCleanup; virtual;
    procedure DoException(AThread: TIdThread; AException: Exception); virtual;
    procedure DoRun; virtual;
    procedure DoStopped(AThread: TIdThread); virtual;
    procedure DoTerminate(Sender: TObject); virtual;
    //
    property Active: Boolean read GetActive write SetActive default FALSE;
    property Priority: TIdThreadPriority read GetPriority write SetPriority;
    property StopMode: TIdThreadStopMode read GetStopMode write SetStopMode;
    //
    property OnAfterExecute: TIdNotifyThreadComponentEvent read FOnAfterExecute write FOnAfterExecute;
    property OnAfterRun: TIdNotifyThreadComponentEvent read FOnAfterRun write FOnAfterRun;
    property OnBeforeExecute: TIdNotifyThreadComponentEvent read FOnBeforeExecute write FOnBeforeExecute;
    property OnBeforeRun: TIdNotifyThreadComponentEvent read FOnBeforeRun write FOnBeforeRun;
    property OnCleanup: TIdNotifyThreadComponentEvent read FOnCleanup write FOnCleanup;
    property OnException: TIdExceptionThreadComponentEvent read FOnException write FOnException;
    property OnRun: TIdNotifyThreadComponentEvent read FOnRun write FOnRun;
    property OnStopped: TIdNotifyThreadComponentEvent read FOnStopped write FOnStopped;
    property OnTerminate: TIdNotifyThreadComponentEvent read FOnTerminate write SetOnTerminate;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Start; virtual;
    procedure Stop; virtual;
    procedure Synchronize(AMethod: TThreadMethod); overload;
    procedure Synchronize(AMethod: TMethod); overload;
    procedure Terminate; virtual;
    procedure TerminateAndWaitFor; virtual;
    function  WaitFor: LongWord;
    //
    property Data: TObject read GetData write SetData;
    property Handle: THandle read GetHandle;
    property ReturnValue: Integer read GetReturnValue write SetReturnValue;
    property Stopped: Boolean read GetStopped;
    property Suspended: Boolean read GetSuspended;
    property Thread: TIdThread read FThread;
    property TerminatingException: string read GetTerminatingException;
    property TerminatingExceptionClass: TClass read GetTerminatingExceptionClass;
    property Terminated: Boolean read GetTerminated;
  end;

  TIdThreadComponent = class(TIdCustomThreadComponent)
  published
    property Active;
    property Priority default IdThreadComponentDefaultPriority;
    property StopMode default IdThreadComponentDefaultStopMode;
    //
    property OnAfterExecute;
    property OnAfterRun;
    property OnBeforeExecute;
    property OnBeforeRun;
    property OnCleanup;
    property OnException;
    property OnRun;
    property OnStopped;
    property OnTerminate;
  end;

  //For Component-writers ONLY!
  TIdThreadEx = class(TIdThread)
  protected
    FThreadComponent: TIdCustomThreadComponent;
    //
    procedure AfterRun; override;
    procedure AfterExecute; override;
    procedure BeforeExecute; override;
    procedure BeforeRun; override;
    procedure Cleanup; override;
    procedure Run; override;
  public
    constructor Create(AThreadComponent: TIdCustomThreadComponent); reintroduce;
  end;

implementation


{ TIdThreadEx }

procedure TIdThreadEx.AfterExecute;
begin
  try
    FThreadComponent.DoAfterExecute;
  finally
    FThreadComponent.FActive := FALSE;
  end;
end;

procedure TIdThreadEx.AfterRun;
begin
  FThreadComponent.DoAfterRun;
end;

procedure TIdThreadEx.BeforeExecute;
begin
  FThreadComponent.DoBeforeExecute;
end;

procedure TIdThreadEx.BeforeRun;
begin
  FThreadComponent.DoBeforeRun;
end;

procedure TIdThreadEx.Cleanup;
begin
  //don't free FData. Now Component is Data owner! inherited Cleanup;
  FThreadComponent.DoCleanup;
end;

constructor TIdThreadEx.Create(AThreadComponent: TIdCustomThreadComponent);
begin
  inherited Create(True);
  FThreadComponent := AThreadComponent;
  FOnException := FThreadComponent.DoException;
  FOnStopped := FThreadComponent.DoStopped;
end;

procedure TIdThreadEx.Run;
begin
  FThreadComponent.DoRun;
end;

{ TIdCustomThreadComponent }

procedure TIdCustomThreadComponent.DoAfterExecute;
begin
  if Assigned(FOnAfterExecute) then FOnAfterExecute(Self);
end;

procedure TIdCustomThreadComponent.DoAfterRun;
begin
  if Assigned(FOnAfterRun) then FOnAfterRun(Self);
end;

procedure TIdCustomThreadComponent.DoBeforeExecute;
begin
  if Assigned(FOnBeforeExecute) then FOnBeforeExecute(Self);
end;

procedure TIdCustomThreadComponent.DoBeforeRun;
begin
  if Assigned(FOnBeforeRun) then FOnBeforeRun(Self);
end;

procedure TIdCustomThreadComponent.DoCleanup;
begin
  if Assigned(FOnCleanup) then FOnCleanup(Self);
end;

constructor TIdCustomThreadComponent.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  StopMode := IdThreadComponentDefaultStopMode;
  Priority := IdThreadComponentDefaultPriority;
end;//TIdCustomThreadComponent.Create

destructor TIdCustomThreadComponent.Destroy;
begin
  {FThread.TerminateAndWaitFor;}
  //make sure thread is not active before we attempt to destroy it
  if Assigned(FThread) then begin
    FThread.Terminate;
    FThread.Start;//resume for terminate
  end;
  FreeAndNIL(FThread);
  inherited;
end;

procedure TIdCustomThreadComponent.DoException(AThread: TIdThread; AException: Exception);
begin
  if Assigned(FOnException) then begin
    FOnException(Self, AException);
  end;
end;

procedure TIdCustomThreadComponent.DoStopped(AThread: TIdThread);
begin
  if Assigned(FOnStopped) then begin
    FOnStopped(Self);
  end;
end;

procedure TIdCustomThreadComponent.DoTerminate;
begin
  if Assigned(FOnTerminate) then begin
    FOnTerminate(Self);
  end;
end;

function TIdCustomThreadComponent.GetData: TObject;
begin
  Result := FThread.Data;
end;

function TIdCustomThreadComponent.GetHandle: THandle;
begin
  Result := GetThreadHandle(FThread);
end;

function TIdCustomThreadComponent.GetReturnValue: Integer;
begin
  Result := FThread.ReturnValue;
end;

function TIdCustomThreadComponent.GetStopMode: TIdThreadStopMode;
begin
  if FThread = NIL then begin
    Result := FStopMode;
  end
  else begin
    Result := FThread.StopMode;
  end;
end;

function TIdCustomThreadComponent.GetStopped: Boolean;
begin
  Result := FThread.Stopped;
end;

function TIdCustomThreadComponent.GetSuspended: Boolean;
begin
  Result := FThread.Suspended;
end;

function TIdCustomThreadComponent.GetTerminated: Boolean;
begin
  Result := FThread.Terminated;
end;

function TIdCustomThreadComponent.GetTerminatingException: string;
begin
  Result := FThread.TerminatingException;
end;

function TIdCustomThreadComponent.GetTerminatingExceptionClass: TClass;
begin
  Result := FThread.TerminatingExceptionClass;
end;

procedure TIdCustomThreadComponent.Loaded;
begin
  inherited;

  if FActive then begin
    FActive := False;
    Active := True;
  end;
end;

procedure TIdCustomThreadComponent.DoRun;
begin
  if Assigned(FOnRun) then begin
    FOnRun(Self);
  end;
end;

procedure TIdCustomThreadComponent.SetActive(const AValue: Boolean);
begin
  if not (csDesigning in ComponentState) then begin
    if FActive<>AValue then begin
      if AValue then begin
        Start;
      end else begin
        Stop;
      end;
    end;//if
  end;
  FActive:= AValue; //component load
end;//SetActive

procedure TIdCustomThreadComponent.SetData(const AValue: TObject);
begin
// this should not be accessed at design-time.
  FThread.Data := AValue;
end;

procedure TIdCustomThreadComponent.SetReturnValue(const AValue: Integer);
begin
// this should not be accessed at design-time.
  FThread.ReturnValue := AValue;
end;

procedure TIdCustomThreadComponent.SetStopMode(const AValue: TIdThreadStopMode);
begin
  if Assigned(FThread) and not FThread.Terminated then begin
    FThread.StopMode := AValue;
  end;
  FStopMode := AValue;
end;

procedure TIdCustomThreadComponent.Start;
begin
  if not (csDesigning in ComponentState) then begin
    if Assigned(FThread) and FThread.Terminated then begin
      FreeAndNIL(FThread);
    end;//if Thread is dead

    if FThread = nil then begin
      FThread := TIdThreadEx.Create(Self);
    end;

    with FThread do begin
      OnTerminate := DoTerminate;
      StopMode := FStopMode;
      Priority := FPriority;
      // Start it last after necessary settings are made
      Start;
    end;
  end;
end;

procedure TIdCustomThreadComponent.Stop;
begin
  if Assigned(FThread) then begin
    FThread.Stop;
  end;
end;

procedure TIdCustomThreadComponent.Synchronize(AMethod: TThreadMethod);
begin
  FThread.Synchronize(AMethod);
end;

procedure TIdCustomThreadComponent.Synchronize(AMethod: TMethod);
begin
  FThread.Synchronize(AMethod);
end;

procedure TIdCustomThreadComponent.Terminate;
begin
  FThread.Terminate;
end;

procedure TIdCustomThreadComponent.TerminateAndWaitFor;
begin
  FThread.TerminateAndWaitFor;
end;

function TIdCustomThreadComponent.WaitFor: LongWord;
begin
  Result := FThread.WaitFor;
end;

function TIdCustomThreadComponent.GetPriority: TIdThreadPriority;
begin
  if csDesigning in ComponentState then begin
    Result := FPriority;
  end else begin
    Result := FThread.Priority;
  end;
end;

procedure TIdCustomThreadComponent.SetPriority(const AValue: TIdThreadPriority);
begin
  if (FThread <> nil) and (FThread.Terminated = False) then begin
    FThread.Priority := AValue;
  end;
  FPriority := AValue;
end;

function TIdCustomThreadComponent.GetActive: Boolean;
begin
  if csDesigning in ComponentState then begin
    Result := FActive;
  end else begin
    Result := not FThread.Stopped;
  end;
end;

procedure TIdCustomThreadComponent.SetOnTerminate(const AValue: TIdNotifyThreadComponentEvent);
begin
  FOnTerminate := AValue;
  if FThread <> nil then begin
    if Assigned(AValue) then begin
      FThread.OnTerminate := DoTerminate;
    end else begin
      FThread.OnTerminate := nil;
    end;
  end;
end;

end.

