{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10105: IdComponent.pas 
{
{   Rev 1.0    2002.11.12 10:33:40 PM  czhower
}
unit IdComponent;

interface

uses
  Classes,
  IdAntiFreezeBase, IdBaseComponent, IdGlobal, IdStack, IdResourceStrings,
  SysUtils;

type
  TIdStatus = ( hsResolving,
                hsConnecting,
                hsConnected,
                hsDisconnecting,
                hsDisconnected,
                hsStatusText,
                ftpTransfer,  // These are to eliminate the TIdFTPStatus and the
                ftpReady,     // coresponding event
                ftpAborted);  // These can be use din the other protocols to.

const
  IdStati: array[TIdStatus] of string = (
                RSStatusResolving,
                RSStatusConnecting,
                RSStatusConnected,
                RSStatusDisconnecting,
                RSStatusDisconnected,
                RSStatusText,
                RSStatusText,
                RSStatusText,
                RSStatusText);

type
  TIdStatusEvent = procedure(ASender: TObject; const AStatus: TIdStatus;
   const AStatusText: string) of object;

  TWorkMode = (wmRead, wmWrite);
  TWorkInfo = record
    Current: Integer;
    Max: Integer;
    Level: Integer;
  end;

  TWorkBeginEvent = procedure(Sender: TObject; AWorkMode: TWorkMode;
   const AWorkCountMax: Integer) of object;
  TWorkEndEvent = procedure(Sender: TObject; AWorkMode: TWorkMode) of object;
  TWorkEvent = procedure(Sender: TObject; AWorkMode: TWorkMode; const AWorkCount: Integer)
   of object;

  TIdComponent = class(TIdBaseComponent)
  protected
    FOnStatus: TIdStatusEvent;
    FOnWork: TWorkEvent;
    FOnWorkBegin: TWorkBeginEvent;
    FOnWorkEnd: TWorkEndEvent;
    FWorkInfos: array[TWorkMode] of TWorkInfo;
    //
    procedure DoStatus(AStatus: TIdStatus); overload;
    procedure DoStatus(AStatus: TIdStatus; const aaArgs: array of const); overload;
    // GetLocalName cannot be static/class method.
    // CBuilder doesnt handle it correctly for a prop accessor
    function GetLocalName: string;
    //
    property OnWork: TWorkEvent read FOnWork write FOnWork;
    property OnWorkBegin: TWorkBeginEvent read FOnWorkBegin write FOnWorkBegin;
    property OnWorkEnd: TWorkEndEvent read FOnWorkEnd write FOnWorkEnd;
  public
    procedure BeginWork(AWorkMode: TWorkMode; const ASize: Integer = 0); virtual;
    constructor Create(axOwner: TComponent); override;
    destructor Destroy; override;
    procedure DoWork(AWorkMode: TWorkMode; const ACount: Integer); virtual;
    procedure EndWork(AWorkMode: TWorkMode); virtual;
    //
    property LocalName: string read GetLocalName;
  published
    property OnStatus: TIdStatusEvent read FOnStatus write FOnStatus;
  end;

implementation

Uses
  SyncObjs;

var
  GInstanceCount: Integer = 0;
  GStackCriticalSection: TCriticalSection;

{ TIdComponent }

constructor TIdComponent.Create(axOwner: TComponent);
begin
  inherited Create(axOwner);
  GStackCriticalSection.Acquire; try
    Inc(GInstanceCount);
    if GInstanceCount = 1 then begin
      GStack := TIdStack.CreateStack;
    end;
  finally GStackCriticalSection.Release; end;
end;

destructor TIdComponent.Destroy;
begin
  inherited Destroy;
  // After inherited - do at last possible moment
  GStackCriticalSection.Acquire; try
    Dec(GInstanceCount);
    if GInstanceCount = 0 then begin
      // This CS will guarantee that during the FreeAndNil nobody will try to use
      // or construct GStack
      FreeAndNil(GStack);
    end;
  finally GStackCriticalSection.Release; end;
end;

procedure TIdComponent.DoStatus(AStatus: TIdStatus);
begin
  DoStatus(AStatus, []);
end;

procedure TIdComponent.DoStatus(AStatus: TIdStatus; const aaArgs: array of const);
begin
//We do it this way because Format can sometimes cause
//an AV if the variable array is blank and there is something
//like a %s or %d.  This is why there was sometimes an AV
//in TIdFTP
  if assigned(OnStatus) then begin
    if Length(aaArgs)=0 then
      OnStatus(Self, AStatus, Format(IdStati[AStatus], ['']))  {Do not Localize}
    else
      OnStatus(Self, AStatus, Format(IdStati[AStatus], aaArgs));
  end;
end;

function TIdComponent.GetLocalName: string;
begin
  Result := GStack.WSGetHostName;
end;

procedure TIdComponent.BeginWork(AWorkMode: TWorkMode; const ASize: Integer = 0);
begin
  Inc(FWorkInfos[AWorkMode].Level);
  if FWorkInfos[AWorkMode].Level = 1 then begin
    FWorkInfos[AWorkMode].Max := ASize;
    FWorkInfos[AWorkMode].Current := 0;
    if assigned(OnWorkBegin) then begin
      OnWorkBegin(Self, AWorkMode, ASize);
    end;
  end;
end;

procedure TIdComponent.DoWork(AWorkMode: TWorkMode; const ACount: Integer);
begin
  if FWorkInfos[AWorkMode].Level > 0 then begin
    Inc(FWorkInfos[AWorkMode].Current, ACount);
    if assigned(OnWork) then begin
      OnWork(Self, AWorkMode, FWorkInfos[AWorkMode].Current);
    end;
  end;
end;

procedure TIdComponent.EndWork(AWorkMode: TWorkMode);
begin
  if FWorkInfos[AWorkMode].Level = 1 then begin
    if assigned(OnWorkEnd) then begin
      OnWorkEnd(Self, AWorkMode);
    end;
  end;
  Dec(FWorkInfos[AWorkMode].Level);
end;

initialization
  GStackCriticalSection := TCriticalSection.Create;
finalization
  // Dont Free. If shutdown is from another Init section, it can cause GPF when stack
  // tries to access it. App will kill it off anyways, so just let it leak
  // FreeAndNil(GStackCriticalSection);
end.
