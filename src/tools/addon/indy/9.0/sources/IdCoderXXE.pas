{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10101: IdCoderXXE.pas 
{
{   Rev 1.0    2002.11.12 10:33:20 PM  czhower
}
unit IdCoderXXE;



interface



uses

  Classes,

  IdCoder3to4, IdCoderUUE;

                

type

  TIdDecoderXXE = class(TIdDecoderUUEBase)

  public

    constructor Create(AOwner: TComponent); override;

  end;



  TIdEncoderXXE = class(TIdEncoderUUEBase)

  public

    constructor Create(AOwner: TComponent); override;

  end;



const

  GXXECodeTable: string = '+-0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; {Do not Localize}



var

  GXXEDecodeTable: TIdDecodeTable;



implementation



uses

  IdGlobal,

  SysUtils;



{ TIdEncoderXXE }



constructor TIdEncoderXXE.Create(AOwner: TComponent);

begin

  inherited;

  FCodingTable := GXXECodeTable;

  FFillChar := FCodingTable[1];

end;



{ TIdDecoderXXE }



constructor TIdDecoderXXE.Create(AOwner: TComponent);

begin

  inherited;

  FDecodeTable := GXXEDecodeTable;

  FFillChar := '~';  {Do not Localize}

end;



initialization

  TIdDecoder4to3.ConstructDecodeTable(GXXECodeTable, GXXEDecodeTable);

end.

