{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10137: IdDsnBaseCmpEdt.pas 
{
{   Rev 1.0    2002.11.12 10:36:30 PM  czhower
}
unit IdDsnBaseCmpEdt;

{$I IdCompilerDefines.inc}

interface

uses
  {$IFDEF VCL6ORABOVE}DesignIntf, DesignEditors;{$ELSE}Dsgnintf;{$ENDIF}

type
  TIdBaseComponentEditor = class(TDefaultEditor)
  public
    procedure ExecuteVerb(Index: Integer); override;
    function GetVerb(Index: Integer): string; override;
    function GetVerbCount: Integer; override;
  end;

implementation

uses
  IdAbout,
  IdGlobal,
  IdResourceStrings,
  SysUtils;

{ TIdBaseComponentEditor }

procedure TIdBaseComponentEditor.ExecuteVerb(Index: Integer);
begin
  case Index of
    0 : ShowAboutBox(RSAAboutBoxCompName, gsIdVersion);
  end;
end;

function TIdBaseComponentEditor.GetVerb(Index: Integer): string;
begin
  case Index of
    0: Result := Format(RSAAboutMenuItemName, [gsIdVersion]);
  end;
end;

function TIdBaseComponentEditor.GetVerbCount: Integer;
begin
  Result := 1;
end;

end.
 
