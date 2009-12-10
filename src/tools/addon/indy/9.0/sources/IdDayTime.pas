{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10121: IdDayTime.pas 
{
{   Rev 1.0    2002.11.12 10:35:02 PM  czhower
}
unit IdDayTime;

{*******************************************************}
{                                                       }
{       Indy QUOTD Client TIdDayTime                    }
{                                                       }
{       Copyright (C) 2000 Winshoes WOrking Group       }
{       Started by J. Peter Mugaas                      }
{       2000-April-23                                   }
{                                                       }
{*******************************************************}
{2000-April-30  J. Peter Mugaas
  changed to drop control charactors and spaces from result to ease
  parsing}
interface

uses
  Classes,
  IdAssignedNumbers,
  IdTCPClient;

type
  TIdDayTime = class(TIdTCPClient)
  protected
    Function GetDayTimeStr : String;
  public
    constructor Create(AOwner: TComponent); override;
    Property DayTimeStr : String read GetDayTimeStr;
  published
    property Port default IdPORT_DAYTIME;
  end;

implementation

uses
  SysUtils;

{ TIdDayTime }

constructor TIdDayTime.Create(AOwner: TComponent);
begin
  inherited;
  Port := IdPORT_DAYTIME;
end;

function TIdDayTime.GetDayTimeStr: String;
begin
  Result := Trim ( ConnectAndGetAll );
end;

end.
