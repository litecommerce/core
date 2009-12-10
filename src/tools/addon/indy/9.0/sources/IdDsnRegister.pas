{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10141: IdDsnRegister.pas 
{
{   Rev 1.0    2002.11.12 10:36:42 PM  czhower
}
unit IdDsnRegister;

{$I IdCompilerDefines.inc}

interface

uses
  {$IFDEF VCL6ORABOVE}DesignIntf, DesignEditors;{$ELSE}Dsgnintf;{$ENDIF}

type
  TIdPropEdBinding = class(TClassProperty)
  public
    procedure Edit; override;
    function GetAttributes: TPropertyAttributes; override;
    function GetValue: string; override;
    procedure SetValue(const Value: string); override;
  end;

// Procs
  procedure Register;

implementation

uses
  Classes,
  IdDsnBaseCmpEdt,
  IdBaseComponent,
  IdDsnPropEdBinding, IdGlobal,
  IdComponent,
  IdMessage,
  {Since we are removing New Design-Time part, we remove the "New Message Part Editor"}
  {IdDsnNewMessagePart, }
  IdStack,
  IdSocketHandle,
  IdTCPServer,
  IdUDPServer,
    {$IFDEF Linux}
  QControls, QForms, QStdCtrls, QButtons, QExtCtrls, QActnList
  {$ELSE}
  Controls, Forms, StdCtrls, Buttons, ExtCtrls, ActnList
  {$ENDIF}
  ;

const
  MessagePartsType : array[0..1] of String = ('TIdAttachment', 'TIdText');    {Do not Localize}

procedure TIdPropEdBinding.Edit;
begin
  inherited;
  with TIdPropEdBindingEntry.Create(nil) do
  try
    SetList(Value);
    if ShowModal = mrOk then
      Value := GetList;
  finally
    Free;
  end;
end;

function TIdPropEdBinding.GetAttributes: TPropertyAttributes;
begin
  result := [paDialog];
end;

function TIdPropEdBinding.GetValue: string;
var
  IdSockets: TIdSocketHandles;
  i: integer;
  sep: string;
begin
  IdSockets := TIdSocketHandles(GetOrdValue);
  result := ''; sep := '';    {Do not Localize}
  for i := 0 to IdSockets.Count - 1 do
  begin
    result := result + sep + MakeBindingStr(IdSockets[i].IP, IdSockets[i].Port);
    sep := ',';    {Do not Localize}
  end;
end;

procedure TIdPropEdBinding.SetValue(const Value: string);
var
  IdSockets: TIdSocketHandles;
  s: string;
  sl: TStringList;
  i, j: integer;
begin
  inherited;
  IdSockets := TIdSocketHandles(GetOrdValue);
  IdSockets.BeginUpdate;
  IdSockets.Clear;
  sl := TStringList.Create;
  try
    sl.CommaText := Value;
    for i := 0 to sl.Count - 1 do
      with TIdSocketHandle.Create(IdSockets) do
      begin
        s := sl[i];
        j := IndyPos(':', s);    {Do not Localize}
        IP := Copy(s, 1, j - 1);
        Port := GStack.WSGetServByName(Copy(s, j+1, Length(s)));
      end;
  finally
    sl.Free;
    IdSockets.EndUpdate;
  end;
end;

procedure Register;
begin
  RegisterPropertyEditor(TypeInfo(TIdSocketHandles), TIdTCPServer, '', TIdPropEdBinding);    {Do not Localize}
   RegisterPropertyEditor(TypeInfo(TIdSocketHandles), TIdUDPServer, '', TIdPropEdBinding);    {Do not Localize}
  RegisterComponentEditor(TIdBaseComponent, TIdBaseComponentEditor);
  //  RegisterComponentEditor ( TIdMessage, TIdMessageComponentEdit);  }
end;

end.
