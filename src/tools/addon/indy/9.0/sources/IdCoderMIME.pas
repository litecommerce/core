{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10095: IdCoderMIME.pas 
{
{   Rev 1.0    2002.11.12 10:32:42 PM  czhower
}
unit IdCoderMIME;

interface

uses
  Classes,
  IdCoder3to4;

type
  TIdEncoderMIME = class(TIdEncoder3to4)
  public
    constructor Create(AOwner: TComponent); override;
  end;

  TIdDecoderMIME = class(TIdDecoder4to3)
  public
    constructor Create(AOwner: TComponent); override;
  end;

const
  GBase64CodeTable: string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';    {Do not Localize}

var
  GBase64DecodeTable: TIdDecodeTable;

implementation

uses
  IdGlobal,
  SysUtils;

{ TIdCoderMIME }

constructor TIdDecoderMIME.Create(AOwner: TComponent);
begin
  inherited;
  FDecodeTable := GBase64DecodeTable;
  FFillChar := '=';  {Do not Localize}
end;

{ TIdEncoderMIME }

constructor TIdEncoderMIME.Create(AOwner: TComponent);
begin
  inherited;
  FCodingTable := GBase64CodeTable;
  FFillChar := '=';   {Do not Localize}
end;

initialization
  TIdDecoder4to3.ConstructDecodeTable(GBase64CodeTable, GBase64DecodeTable);
end.
