unit IdContainers;

{********************************************************************}
{*  IdContainers.pas                                                 *}
{*                                                                  *}
{*  Provides compatibility with the Contnr.pas unit from            *}
{*  Delphi 5 not found in Delphi 4.                                 *}
{*                                                                  *}
{*  Based on ideas from the Borland VCL Contnr.pas interface.       *}
{*                                                                  *}
{********************************************************************}

{
  $Log:  10109: IdContainers.pas 
{
{   Rev 1.0    2002.11.12 10:33:52 PM  czhower
}
  Revision 1.0  2001-02-20 02:02:09-05  dsiders
  Initial revision
}

interface

uses
  SysUtils, Classes;


type

  {TIdObjectList}

  TIdObjectList = class(TList)
  private
    FOwnsObjects: Boolean;
  protected
    function GetItem(AIndex: Integer): TObject;
    procedure SetItem(AIndex: Integer; AObject: TObject);
    procedure Notify(AItemPtr: Pointer; AAction: TListNotification); override;
  public
    constructor Create; overload;
    constructor Create(AOwnsObjects: Boolean); overload;
    function Add(AObject: TObject): Integer;
    function FindInstanceOf(AClassRef: TClass; AMatchExact: Boolean = True; AStartPos: Integer = 0): Integer;
    function IndexOf(AObject: TObject): Integer;
    function Remove(AObject: TObject): Integer;
    procedure Insert(AIndex: Integer; AObject: TObject);
    property Items[AIndex: Integer]: TObject read GetItem write SetItem; default;
    property OwnsObjects: Boolean read FOwnsObjects write FOwnsObjects;
  end;


implementation


{TIdObjectList}

function TIdObjectList.Add(AObject: TObject): Integer;
begin
  Result := inherited Add(AObject);
end;


constructor TIdObjectList.Create;
begin
  inherited Create;
  FOwnsObjects := True;
end;


constructor TIdObjectList.Create(AOwnsObjects: Boolean);
begin
  inherited Create;
  FOwnsObjects := AOwnsObjects;
end;


function TIdObjectList.FindInstanceOf(AClassRef: TClass;
  AMatchExact: Boolean = True; AStartPos: Integer = 0): Integer;
var
  iPos: Integer;
  bIsAMatch: Boolean;

begin
  Result := -1;   // indicates item is not in object list

  for iPos := AStartPos to Count - 1 do
  begin
    bIsAMatch :=
      ((not AMatchExact) and Items[iPos].InheritsFrom(AClassRef)) or
      (AMatchExact and (Items[iPos].ClassType = AClassRef));

    if (bIsAMatch) then
    begin
      Result := iPos;
      break;
    end;
  end;
end;

function TIdObjectList.GetItem(AIndex: Integer): TObject;
begin
  Result := inherited Items[AIndex];
end;


function TIdObjectList.IndexOf(AObject: TObject): Integer;
begin
  Result := inherited IndexOf(AObject);
end;


procedure TIdObjectList.Insert(AIndex: Integer; AObject: TObject);
begin
  inherited Insert(AIndex, AObject);
end;


procedure TIdObjectList.Notify(AItemPtr: Pointer; AAction: TListNotification);
begin
  if (OwnsObjects and (AAction = lnDeleted)) then
    TObject(AItemPtr).Free;

  inherited Notify(AItemPtr, AAction);
end;


function TIdObjectList.Remove(AObject: TObject): Integer;
begin
  Result := inherited Remove(AObject);
end;


procedure TIdObjectList.SetItem(AIndex: Integer; AObject: TObject);
begin
  inherited Items[AIndex] := AObject;
end;

end.
