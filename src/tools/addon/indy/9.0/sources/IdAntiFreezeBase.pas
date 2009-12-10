{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10065: IdAntiFreezeBase.pas 
{
{   Rev 1.0    2002.11.12 10:29:58 PM  czhower
}
unit IdAntiFreezeBase;

interface

uses
  Classes,
  IdBaseComponent;

const
  ID_Default_TIdAntiFreezeBase_Active = True;
  ID_Default_TIdAntiFreezeBase_ApplicationHasPriority = True;
  ID_Default_TIdAntiFreezeBase_IdleTimeOut = 250;
  ID_Default_TIdAntiFreezeBase_OnlyWhenIdle = True;

type
  TIdAntiFreezeBase = class(TIdBaseComponent)
  protected
    FActive: boolean;
    FApplicationHasPriority: boolean;
    FIdleTimeOut: Integer;
    FOnlyWhenIdle: Boolean;
  public
    constructor Create(AOwner: TComponent); override;
    class procedure DoProcess(const AIdle: boolean = true; const AOverride: boolean = false);
    destructor Destroy; override;
    procedure Process; virtual; abstract;
    class function ShouldUse: boolean;
    class procedure Sleep(ATimeout: Integer);
  published
    property Active: boolean read FActive write FActive
     default ID_Default_TIdAntiFreezeBase_Active;
    property ApplicationHasPriority: Boolean read FApplicationHasPriority
     write FApplicationHasPriority
     default ID_Default_TIdAntiFreezeBase_ApplicationHasPriority;
    property IdleTimeOut: integer read FIdleTimeOut write FIdleTimeOut
     default ID_Default_TIdAntiFreezeBase_IdleTimeOut;
    property OnlyWhenIdle: Boolean read FOnlyWhenIdle write FOnlyWhenIdle
     default ID_Default_TIdAntiFreezeBase_OnlyWhenIdle;
  end;

var
  GAntiFreeze: TIdAntiFreezeBase = nil;

implementation

uses
  IdException, IdGlobal, IdResourceStrings,
  SysUtils;

{ TIdAntiFreezeBase }

constructor TIdAntiFreezeBase.Create(AOwner: TComponent);
begin
  inherited;
  if csDesigning in ComponentState then begin
    if Assigned(GAntiFreeze) then begin
      raise EIdException.Create(RSOnlyOneAntiFreeze);
    end;
  end else begin
    GAntiFreeze := Self;
  end;
  FActive := ID_Default_TIdAntiFreezeBase_Active;
  FApplicationHasPriority := ID_Default_TIdAntiFreezeBase_ApplicationHasPriority;
  IdleTimeOut := ID_Default_TIdAntiFreezeBase_IdleTimeOut;
  FOnlyWhenIdle := ID_Default_TIdAntiFreezeBase_OnlyWhenIdle;
end;

destructor TIdAntiFreezeBase.Destroy;
begin
  GAntiFreeze := nil;
  inherited;
end;

class procedure TIdAntiFreezeBase.DoProcess(const AIdle: boolean = True;
 const AOverride: boolean = False);
begin
  if ShouldUse then begin
    if ((GAntiFreeze.OnlyWhenIdle = false) or AIdle or AOverride) and GAntiFreeze.Active then begin
      GAntiFreeze.Process;
    end;
  end;
end;

class function TIdAntiFreezeBase.ShouldUse: boolean;
begin
  // InMainThread - Only process if calling client is in the main thread
  Result := (GAntiFreeze <> nil) and InMainThread;
  if Result then begin
    Result := GAntiFreeze.Active;
  end;
end;

class procedure TIdAntiFreezeBase.Sleep(ATimeout: Integer);
begin
  if ShouldUse then begin
    while ATimeout > GAntiFreeze.IdleTimeOut do begin
      IdGlobal.Sleep(GAntiFreeze.IdleTimeOut);
      ATimeout := ATimeout - GAntiFreeze.IdleTimeOut;
      DoProcess;
    end;
    IdGlobal.Sleep(ATimeout);
    DoProcess;
  end else begin
    IdGlobal.Sleep(ATimeout);
  end;
end;

end.
