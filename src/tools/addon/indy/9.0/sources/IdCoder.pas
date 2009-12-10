{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10089: IdCoder.pas 
{
{   Rev 1.0    2002.11.12 10:32:16 PM  czhower
}
unit IdCoder;

interface

uses
  Classes,
  IdBaseComponent, IdGlobal;

type
  TIdEncoder = class(TIdBaseComponent)
  public
    function Encode(const ASrc: string): string; overload;
    function Encode(ASrcStream: TStream; const ABytes: integer = MaxInt): string; overload; virtual;
     abstract;
    class function EncodeString(const AIn: string): string;
  end;

  TIdDecoder = class(TIdBaseComponent)
  public
    class function DecodeString(AIn: string): string;
    function DecodeToString(const AIn: string): string;
    procedure DecodeToStream(AIn: string; ADest: TStream); virtual; abstract;
  end;

implementation

uses
  SysUtils;

{ TIdDecoder }

class function TIdDecoder.DecodeString(AIn: string): string;
begin
  with Create(nil) do try
    Result := DecodeToString(AIn);
  finally Free; end;
end;

function TIdDecoder.DecodeToString(const AIn: string): string;
{var
  LDestStream: TIdReadMemoryStream;
begin }
var
  LDestStream: TStringStream;
begin
  LDestStream := TStringStream.Create(''); try  {Do not Localize}
    DecodeToStream(AIn, LDestStream);
    Result := LDestStream.DataString;
  finally FreeAndNil(LDestStream); end;
end;

{ TIdEncoder }

function TIdEncoder.Encode(const ASrc: string): string;
var
  LSrcStream: TIdReadMemoryStream;
begin
  LSrcStream := TIdReadMemoryStream.Create; try
    LSrcStream.SetPointer(Pointer(ASrc),Length(ASrc));
    Result := Encode(LSrcStream);
  finally FreeAndNil(LSrcStream); end;
end;

class function TIdEncoder.EncodeString(const AIn: string): string;
begin
  with Create(nil) do try
    Result := Encode(AIn);
  finally Free; end;
end;

end.


