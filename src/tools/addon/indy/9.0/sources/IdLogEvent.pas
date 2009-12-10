{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10235: IdLogEvent.pas 
{
{   Rev 1.0    2002.11.12 10:44:18 PM  czhower
}
unit IdLogEvent;

interface

uses
  Classes,
  IdLogBase;

type
  TLogItemStatusEvent = procedure(ASender: TComponent; const AText: string) of object;
  TLogItemDataEvent = procedure(ASender: TComponent; const AText: string; const AData: string)
   of object;

  TIdLogEvent = class(TIdLogBase)
  protected
    FOnReceived: TLogItemDataEvent;
    FOnSent: TLogItemDataEvent;
    FOnStatus: TLogItemStatusEvent;
    //
    procedure LogStatus(const AText: string); override;
    procedure LogReceivedData(const AText: string; const AData: string); override;
    procedure LogSentData(const AText: string; const AData: string); override;
  public
  published
    property OnReceived: TLogItemDataEvent read FOnReceived write FOnReceived;
    property OnSent: TLogItemDataEvent read FOnSent write FOnSent;
    property OnStatus: TLogItemStatusEvent read FOnStatus write FOnStatus;
  end;

implementation

{ TIdLogEvent }

{procedure TIdLogEvent.Log(AText: string);
var
  s: string;
begin
  if assigned(OnLogItem) then begin
    OnLogItem(Self, AText);
  end;
  case Target of
    ltFile: begin
      FFileStream.WriteBuffer(PChar(AText)^, Length(AText));
      s := EOL;
      FFileStream.WriteBuffer(PChar(s)^, Length(s));
    end;
    ltDebugOutput: begin
      DebugOutput(AText + EOL);
    end;
  end;
end;}

{ TIdLogEvent }

procedure TIdLogEvent.LogReceivedData(const AText, AData: string);
begin
  if Assigned(OnReceived) then begin
    OnReceived(Self, AText, AData);
  end;
end;

procedure TIdLogEvent.LogSentData(const AText, AData: string);
begin
  if Assigned(OnSent) then begin
    OnSent(Self, AText, AData);
  end;
end;

procedure TIdLogEvent.LogStatus(const AText: string);
begin
  if Assigned(OnStatus) then begin
    OnStatus(Self, AText);
  end;
end;

end.
