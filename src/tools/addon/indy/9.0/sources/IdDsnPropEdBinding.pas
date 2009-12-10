{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10139: IdDsnPropEdBinding.pas 
{
{   Rev 1.0    2002.11.12 10:36:36 PM  czhower
}
unit IdDsnPropEdBinding;

interface

uses
  {$IFDEF Linux}
  QControls, QForms, QStdCtrls, QButtons, QExtCtrls, QActnList, QGraphics,
  {$ELSE}
  Controls, Forms, StdCtrls, Buttons, ExtCtrls, ActnList, Graphics,
  {$ENDIF}

  Classes;

{We place the design-time bindings form class here so that the form can
be embedded into a test program without design-time units.  This is necessary
so that if a problem occurs, the form can be tested more easily in a test program
that can b}
type
  TIdPropEdBindingEntry = class(TForm)
  protected
      btnOk: TButton;
    btnCancel: TButton;
    lblBindings: TLabel;
    lblHostname: TLabel;
    lblPort: TLabel;
    lstBindings: TListBox;
    btnAdd: TButton;
    btnRemove: TButton;
    cmbPort: TComboBox;
    cmbHostname: TComboBox;
    actBndEditor: TActionList;
    actAdd: TAction;
    actRemove: TAction;
    fCreatedStack : Boolean;
    procedure lstBindingsClick(Sender: TObject);
    procedure actAddExecute(Sender: TObject);
    procedure actAddUpdate(Sender: TObject);
    procedure actRemoveExecute(Sender: TObject);
    procedure actRemoveUpdate(Sender: TObject);
    procedure cmbPortKeyPress(Sender: TObject; var Key: Char);
    procedure cmbHostnameKeyPress(Sender: TObject; var Key: Char);

    procedure ValidateAddBinding;
    procedure SetHostname(const Value: String);
    procedure SetPort(const Value: integer);
    function PortDescription(const PortNumber: integer): string;
    function GetHostname: String;
    function GetPort: integer;
    procedure SetBinding(const Binding: String);
    function GetBinding: string;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure SetList(const Arg: string);
    function GetList: string;
    property Binding: string read GetBinding write SetBinding;
    property Hostname: String read GetHostname write SetHostname;
    property Port: integer read GetPort write SetPort;
  end;

function MakeBindingStr(AIP : String; APort : Cardinal) : String;

implementation

uses
  IdGlobal,
  IdResourceStrings,
  IdSocketHandle,
  IdStack,
  SysUtils;

function MakeBindingStr(AIP : String; APort : Cardinal) : String;
begin
    Result := Format('%s:%d',[AIP,APort]);    {Do not Localize}
end;

{ TIdPropEdBindingEntry }

constructor TIdPropEdBindingEntry.Create(AOwner: TComponent);
var
  i: integer;
  s: string;
begin
  {we have to bypass the form streaming mechanism for this to work
  because are not putting this form in a DFM}
  inherited CreateNew(AOwner);
  {Create Stack if necessary}
  if Assigned(GStack) then
  begin
    fCreatedStack := False;
  end
  else
  begin
    GStack := GStackClass.Create;
    fCreatedStack := True;
  end;

  {initial form settings}
  Left := 188;
  Top := 125;
  AutoScroll := False;
  Caption := RSBindingFormCaption;
  //In Windows, these are the not the same thing
  //as the Height and Width properties.  
  ClientHeight := 230;
  ClientWidth := 410;
  Color := clBtnFace;
  Constraints.MinHeight := 256;
  Constraints.MinWidth := 418;
  Scaled := False;
  Font.Color := clBtnText;
  Font.Height := -11;
  Font.Name := 'MS Sans Serif';    {Do not Localize}
  Font.Style := [];

  Position := poScreenCenter;
  PixelsPerInch := 96;

  {we do the actions here so that the rest of the components can bind to them}
  actBndEditor:= TActionList.Create(Self);
  actAdd:= TAction.Create(Self);
  actAdd.ActionList := actBndEditor;
  actAdd.Caption := RSBindingAddCaption;
  actAdd.OnExecute := actAddExecute;
  actAdd.OnUpdate := actAddUpdate;

  actRemove:= TAction.Create(Self);
  actRemove.ActionList := actBndEditor;
  actRemove.Caption := RSBindingRemoveCaption;
  actRemove.OnExecute := actRemoveExecute;
  actRemove.OnUpdate := actRemoveUpdate;

  {Subcontrols and components}

  btnOk := TButton.Create(Self);
  btnOk.Parent := Self;
  btnOk.Left := 249;
  btnOk.Top := 201;
  btnOk.Width := 75;
  btnOk.Height := 25;
  btnOk.Anchors := [akRight, akBottom];
  btnOk.Caption := RSBindingOkButton;
  btnOk.Default := True;
  btnOk.ModalResult := 1;

  btnCancel:= TButton.Create(Self);
  btnCancel.Parent := Self;
  btnCancel.Left := 331;
  btnCancel.Top := 201;
  btnCancel.Width := 75;
  btnCancel.Height := 25;
  btnCancel.Anchors := [akRight, akBottom];
  btnCancel.Cancel := True;
  btnCancel.Caption := RSBindingCancel;
  btnCancel.ModalResult := 2;

  lstBindings:= TListBox.Create(Self);
  lstBindings.Parent := Self;
  lstBindings.Font.Color := clWindowText;
  lstBindings.Left := 8;
  lstBindings.Top := 24;
  lstBindings.Width := 149;
  lstBindings.Height := 169;
  lstBindings.Anchors := [akLeft, akTop, akBottom];
  lstBindings.ItemHeight := 13;
  lstBindings.OnClick := lstBindingsClick;

  lblBindings := TLabel.Create(Self);
  lblBindings.Parent := Self;
  lblBindings.Left := 8;
  lblBindings.Top := 8;
  lblBindings.Width := 40;
  lblBindings.Height := 13;
  lblBindings.Transparent := True;
  lblBindings.FocusControl :=  lstBindings;
  lblBindings.Layout := tlCenter;
  lblBindings.Caption := RSBindingLabelBindings;

  btnAdd:= TButton.Create(Self);
  btnAdd.Parent := Self;
  btnAdd.Left := 164;
  btnAdd.Top := 32;
  btnAdd.Width := 71;
  btnAdd.Height := 25;
  btnAdd.Action := actAdd;

  btnRemove:= TButton.Create(Self);
  btnRemove.Parent := Self;
  btnRemove.Left := 164;
  btnRemove.Top := 64;
  btnRemove.Width := 71;
  btnRemove.Height := 25;
  btnRemove.Action := actRemove;

  cmbPort:= TComboBox.Create(Self);
  {comboboxes need to have names in Linux}
  cmbPort.Name := 'cmbPort';    {Do not Localize}
  cmbPort.Text := '';    {Do not Localize}
  cmbPort.Parent := Self;
  cmbPort.Font.Color := clWindowText;
  cmbPort.Left := 245;
  cmbPort.Top := 72;
  cmbPort.Width := 161;
  cmbPort.Height := 21;
  cmbPort.Anchors := [akLeft, akTop, akRight];
  cmbPort.ItemHeight := 13;
  cmbPort.OnKeyPress := cmbPortKeyPress;

  lblPort := TLabel.Create(Self);
  lblPort.Parent := Self;
  lblPort.Left := 247;
  lblPort.Top := 56;
  lblPort.Width := 19;
  lblPort.Height := 13;
  lblPort.Layout := tlCenter;
  lblPort.Transparent := True;
  lblPort.FocusControl := cmbPort;
  lblPort.Caption := RSBindingPortLabel;

  cmbHostname:= TComboBox.Create(Self);
  cmbHostname.Name := 'cmbHostname';    {Do not Localize}
  cmbHostname.Text := '';    {Do not Localize}
  cmbHostname.Parent := Self;
  cmbHostname.Font.Color := clWindowText;
  cmbHostname.Left := 245;
  cmbHostname.Top := 24;
  cmbHostname.Width := 161;
  cmbHostname.Height := 21;
  cmbHostname.Anchors := [akLeft, akTop, akRight];
  cmbHostname.ItemHeight := 13;
  cmbHostname.OnKeyPress := cmbHostnameKeyPress;

  lblHostname := TLabel.Create(Self);
  lblHostname.Parent := Self;
  lblHostname.Left := 247;
  lblHostname.Top := 8;
  lblHostname.Width := 51;
  lblHostname.Height := 13;
  lblHostname.Layout := tlCenter;
  lblHostname.Transparent := True;
  lblHostname.FocusControl := cmbHostname;
  lblHostname.Caption := RSBindingHostnameLabel;

  btnOk.TabOrder := 0;
  btnCancel.TabOrder := 1;
  lstBindings.TabOrder := 2;
  btnAdd.TabOrder := 3;
  btnRemove.TabOrder := 4;
  cmbHostname.TabOrder := 5;
  cmbPort.TabOrder := 6;

  {End action section}

  {others}
  try
    cmbPort.Items.Add(RSBindingAny);
    cmbPort.Items.BeginUpdate;
    for i := 0 to IdPorts.Count - 1 do
      cmbPort.Items.Add(PortDescription(Integer(IdPorts[i])));
  finally
    cmbPort.Items.EndUpdate;
  end;
  //TODO: Change back to MAX_PATH for Windows. Make a const in IdGlobals
  SetLength(s, 250);
  cmbHostname.Items := GStack.LocalAddresses;
  cmbHostname.Items.Insert(0, '127.0.0.1');    {Do not Localize}
  cmbHostname.Items.Insert(0, RSBindingAll);    {Do not Localize}

  lstBindingsClick(self);
end;

destructor TIdPropEdBindingEntry.Destroy;
begin
  if fCreatedStack then
  begin
    FreeAndNil(GStack);
  end;
  inherited destroy;
end;

function TIdPropEdBindingEntry.GetBinding: string;
begin
  result := MakeBindingStr(Hostname,Port);
end;

function TIdPropEdBindingEntry.GetHostname: String;
begin
  if (cmbHostname.Text = RSBindingAll) or (cmbHostname.Text = '') then
  begin
    result := '0.0.0.0';
  end
  else
  begin
    result := cmbHostname.Text;
  end;
end;

function TIdPropEdBindingEntry.GetList: string;
begin
  Result := lstBindings.Items.CommaText;
end;

function TIdPropEdBindingEntry.GetPort: integer;
var
  i: integer;
  s: string;
begin
  s := cmbPort.Text;
  i := AnsiPos(':', s);    {Do not Localize}
  if i > 0 then begin
    s := Copy(s, 1, i-1);
    if s = RSBindingAll then
    begin
      s := '0';
    end;
    Result := StrToInt(s);
  end else begin
    if s = RSBindingAny then
    begin
      Result := 0;
    end
    else
    begin
      Result := GStack.WSGetServByName(s);
    end;
  end;
end;

procedure TIdPropEdBindingEntry.lstBindingsClick(Sender: TObject);
begin
  btnRemove.Enabled := lstBindings.ItemIndex > -1;
end;

function TIdPropEdBindingEntry.PortDescription(
  const PortNumber: integer): string;
begin
  with GStack.WSGetServByPort(PortNumber) do try
    Result := '';    {Do not Localize}
    if Count > 0 then begin
      Result := Format('%d: %s', [PortNumber, CommaText]);    {Do not Localize}
    end;
  finally
    Free;
  end;
end;

procedure TIdPropEdBindingEntry.SetBinding(const Binding: String);
var
  i: integer;
begin
  i := AnsiPos(':', Binding);    {Do not Localize}
  Hostname := Copy(Binding, 1, i - 1);
  Port := StrToInt(Copy(Binding, i+1, Length(Binding)));
end;

procedure TIdPropEdBindingEntry.SetHostname(const Value: String);

begin
  if (Value = '0.0.0.0') or (Value='') then
  begin
    cmbHostname.Text := RSBindingAll;
  end
  else
  begin
    cmbHostname.Text := Value;
  end;
end;

procedure TIdPropEdBindingEntry.SetList(const Arg: string);
begin
  lstBindings.Items.CommaText := Arg;
end;

procedure TIdPropEdBindingEntry.SetPort(const Value: integer);
var
  s: string;
begin
  if Value = 0 then
  begin
    cmbPort.Text := RSBindingAny;
  end
  else
  begin
    s := PortDescription(Value);
    if s <> '' then begin    {Do not Localize}
      cmbPort.Text := s
    end else begin
      cmbPort.Text := IntToStr(Value);
    end;
  end;
end;

procedure TIdPropEdBindingEntry.ValidateAddBinding;
begin
  btnAdd.Enabled := (Length(cmbPort.Text) <> 0) and 
    (lstBindings.Items.IndexOf(Binding) = -1);
end;

procedure TIdPropEdBindingEntry.actAddExecute(Sender: TObject);
begin
  lstBindings.Items.Add(Binding);  
end;

procedure TIdPropEdBindingEntry.actAddUpdate(Sender: TObject);
begin
  ValidateAddBinding;
end;

procedure TIdPropEdBindingEntry.actRemoveExecute(Sender: TObject);
begin
  with lstBindings do
  begin
    Binding := Items[ItemIndex];
    Items.Delete(ItemIndex);
    {Unselect the item and disable this button until the user selects a binding}
  end;
end;

procedure TIdPropEdBindingEntry.actRemoveUpdate(Sender: TObject);
begin
  actRemove.Enabled := lstBindings.ItemIndex <> -1;
end;

procedure TIdPropEdBindingEntry.cmbPortKeyPress(Sender: TObject;
  var Key: Char);
begin
  if (Key > #31) and (Key <  #128) then
    if not (Key in ['0'..'9']) then    {Do not Localize}
      Key := #0;
end;

procedure TIdPropEdBindingEntry.cmbHostnameKeyPress(Sender: TObject;
  var Key: Char);

begin
  if (Key > #31) and (Key <  #128) and (Key <> '.') then    {Do not Localize}
    if not (Key in ['0'..'9']) then    {Do not Localize}
      Key := #0;
end;


end.
