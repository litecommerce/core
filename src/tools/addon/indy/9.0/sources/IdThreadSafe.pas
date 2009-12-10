{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10385: IdThreadSafe.pas 
{
{   Rev 1.0    2002.11.12 10:56:28 PM  czhower
}
unit IdThreadSafe;

interface

uses
  Classes,
  SyncObjs;

type
  TIdThreadSafe = class
  protected
    FCriticalSection: TCriticalSection;
  public
    constructor Create; virtual;
    destructor Destroy; override;
    procedure Lock;
    procedure Unlock;
  end;

  // Yes we know that integer operations are "atomic". However we do not like to rely on
  // internal compiler implementation. This is a safe and proper way to keep our code independent
  TIdThreadSafeInteger = class(TIdThreadSafe)
  protected
    FValue: Integer;
    //
    function GetValue: Integer;
    procedure SetValue(const AValue: Integer);
  public
    function Decrement: Integer;
    function Increment: Integer;
    //
    property Value: Integer read GetValue write SetValue;
  end;

  TIdThreadSafeCardinal = class(TIdThreadSafe)
  protected
    FValue: Cardinal;
    //
    function GetValue: Cardinal;
    procedure SetValue(const AValue: Cardinal);
  public
    function Decrement: Cardinal;
    function Increment: Cardinal;
    //
    property Value: Cardinal read GetValue write SetValue;
  end;

  TIdThreadSafeString = class(TIdThreadSafe)
  protected
    FValue: string;
    //
    function GetValue: string;
    procedure SetValue(const AValue: string);
  public
    procedure Append(const AValue: string);
    procedure Prepend(const AValue: string);
    //
    property Value: string read GetValue write SetValue;
  end;

  TIdThreadSafeStringList = class(TIdThreadSafe)
  protected
    FValue: TStringList;
  public
    constructor Create(const ASorted: Boolean = False); reintroduce;
    destructor Destroy; override;
    procedure Add(const AItem: string);
    procedure AddObject(const AItem: string; AObject: TObject);
    procedure Clear;
    function Lock: TStringList; reintroduce;
    function ObjectByItem(const AItem: string): TObject;
    procedure Remove(const AItem: string);
    procedure Unlock; reintroduce;
  end;

  TIdThreadSafeList = class(TThreadList)
  public
    function IsCountLessThan(const AValue: Cardinal): Boolean;
  End;

implementation

uses
  IdGlobal, // For FreeAndNil
  SysUtils;

{ TIdThreadSafe }

constructor TIdThreadSafe.Create;
begin
  inherited;
  FCriticalSection := TCriticalSection.Create;
end;

destructor TIdThreadSafe.Destroy;
begin
  FreeAndNil(FCriticalSection);
  inherited;
end;

procedure TIdThreadSafe.Lock;
begin
  FCriticalSection.Enter;
end;

procedure TIdThreadSafe.Unlock;
begin
  FCriticalSection.Leave;
end;

{ TIdThreadSafeInteger }

function TIdThreadSafeInteger.Decrement: Integer;
begin
  Lock; try
    Result := FValue;
    Dec(FValue);
  finally Unlock; end;
end;

function TIdThreadSafeInteger.GetValue: Integer;
begin
  Lock; try
    Result := FValue;
  finally Unlock; end;
end;

function TIdThreadSafeInteger.Increment: Integer;
begin
  Lock; try
    Result := FValue;
    Inc(FValue);
  finally Unlock; end;
end;

procedure TIdThreadSafeInteger.SetValue(const AValue: Integer);
begin
  Lock; try
    FValue := AValue;
  finally Unlock; end;
end;

{ TIdThreadSafeString }

procedure TIdThreadSafeString.Append(const AValue: string);
begin
  Lock; try
    FValue := FValue + AValue;
  finally Unlock; end;
end;

function TIdThreadSafeString.GetValue: string;
begin
  Lock; try
    Result := FValue;
  finally Unlock; end;
end;

procedure TIdThreadSafeString.Prepend(const AValue: string);
begin
  Lock; try
    FValue := AValue + FValue;
  finally Unlock; end;
end;

procedure TIdThreadSafeString.SetValue(const AValue: string);
begin
  Lock; try
    FValue := AValue;
  finally Unlock; end;
end;

{ TIdThreadSafeStringList }

procedure TIdThreadSafeStringList.Add(const AItem: string);
begin
  with Lock do try
    Add(AItem);
  finally Unlock; end;
end;

procedure TIdThreadSafeStringList.AddObject(const AItem: string; AObject: TObject);
begin
  with Lock do try
    AddObject(AItem, AObject);
  finally Unlock; end;
end;

procedure TIdThreadSafeStringList.Clear;
begin
  with Lock do try
    Clear;
  finally Unlock; end;
end;

constructor TIdThreadSafeStringList.Create(const ASorted: Boolean = False);
begin
  inherited Create;
  FValue := TStringList.Create;
  FValue.Sorted := ASorted;
end;

destructor TIdThreadSafeStringList.Destroy;
begin
  inherited Lock; try
    FreeAndNil(FValue);
  finally inherited Unlock; end;
  inherited;
end;

function TIdThreadSafeStringList.Lock: TStringList;
begin
  inherited Lock;
  Result := FValue;
end;

function TIdThreadSafeStringList.ObjectByItem(const AItem: string): TObject;
var
  i: Integer;
begin
  Result := nil;
  with Lock do try
    i := IndexOf(AItem);
    if i > -1 then begin
      Result := Objects[i];
    end;
  finally Unlock; end;
end;

procedure TIdThreadSafeStringList.Remove(const AItem: string);
var
  i: Integer;
begin
  with Lock do try
    i := IndexOf(AItem);
    if i > -1 then begin
      Delete(i);
    end;
  finally Unlock; end;
end;

procedure TIdThreadSafeStringList.Unlock;
begin
  inherited Unlock;
end;

{ TIdThreadSafeCardinal }

function TIdThreadSafeCardinal.Decrement: Cardinal;
begin
  Lock; try
    Result := FValue;
    Dec(FValue);
  finally Unlock; end;
end;

function TIdThreadSafeCardinal.GetValue: Cardinal;
begin
  Lock; try
    Result := FValue;
  finally Unlock; end;
end;

function TIdThreadSafeCardinal.Increment: Cardinal;
begin
  Lock; try
    Result := FValue;
    Inc(FValue);
  finally Unlock; end;
end;

procedure TIdThreadSafeCardinal.SetValue(const AValue: Cardinal);
begin
  Lock; try
    FValue := AValue;
  finally Unlock; end;
end;

{ TIdThreadSafeList }

function TIdThreadSafeList.IsCountLessThan(const AValue: Cardinal): Boolean;
Begin
  if Assigned(SELF) then begin
    try
      Result := Cardinal(LockList.Count) < AValue;
    finally
      UnlockList;
    end;
  end else begin
    Result := TRUE; // none always <
  end;
End;

end.
