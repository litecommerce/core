{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10231: IdLogBase.pas 
{
{   Rev 1.0    2002.11.12 10:44:00 PM  czhower
}
unit IdLogBase;

interface

uses
  Classes,
  IdIntercept,
  IdSocketHandle;

type
  TIdLogBase = class(TIdConnectionIntercept)
  protected
    FActive: Boolean;
    FLogTime: Boolean;
    FReplaceCRLF: Boolean;
    FStreamedActive: Boolean;
    //
    procedure Close; virtual;
    procedure LogStatus(const AText: string); virtual; abstract;
    procedure LogReceivedData(const AText: string; const AData: string); virtual; abstract;
    procedure LogSentData(const AText: string; const AData: string); virtual; abstract;
    procedure Open; virtual;
    procedure SetActive(const AValue: Boolean); virtual;
    procedure Loaded; override;
  public
    procedure Connect(AConnection: TComponent); override;
    constructor Create(AOwner: TComponent); override;
    procedure Receive(ABuffer: TStream); override;
    procedure Send(ABuffer: TStream); override;
    destructor Destroy; override;
    procedure Disconnect; override;
  published
    property Active: Boolean read FActive write SetActive default False;
    property LogTime: Boolean read FLogTime write FLogTime default True;
    property ReplaceCRLF: Boolean read FReplaceCRLF write FReplaceCRLF default true;
  end;

implementation

uses
  IdGlobal,
  IdResourceStrings,
  SysUtils;

{ TIdLogBase }

procedure TIdLogBase.Close;
begin
end;

procedure TIdLogBase.Connect(AConnection: TComponent);
begin
  if FActive then
  begin
    inherited Connect(AConnection);
    LogStatus(RSLogConnected);
  end;
end;

constructor TIdLogBase.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FLogTime := True;
  ReplaceCRLF := True;
end;

destructor TIdLogBase.Destroy;
begin
  Active := False;
  inherited Destroy;
end;

procedure TIdLogBase.Disconnect;
begin
  if FActive then
  begin
    LogStatus(RSLogDisconnected);
    inherited Disconnect;
  end;
end;

procedure TIdLogBase.Loaded;
begin
    Active := FStreamedActive;
end;

procedure TIdLogBase.Open;
begin
end;

procedure TIdLogBase.Receive(ABuffer: TStream);
var
  s: string;
  LMsg: string;
begin
  if FActive then
  begin
    inherited Receive(ABuffer);
    with TStringStream.Create('') do try  {Do not translate}
      CopyFrom(ABuffer, ABuffer.Size);
      LMsg := ''; {Do not translate}
      if LogTime then begin
        LMsg := DateTimeToStr(Now);
      end;
      s := DataString;
      if FReplaceCRLF then begin
        s := StringReplace(s, EOL, RSLogEOL, [rfReplaceAll]);
        s := StringReplace(s, CR, RSLogCR, [rfReplaceAll]);
        s := StringReplace(s, LF, RSLogLF, [rfReplaceAll]);
      end;
      LogReceivedData(LMsg, s);
    finally
      Free;
    end;
  end;
end;

procedure TIdLogBase.Send(ABuffer: TStream);
var
  s: string;
  LMsg: string;
begin
  if FActive then
  begin
    inherited Send(ABuffer);
    with TStringStream.Create('') do try
      CopyFrom(ABuffer, ABuffer.Size);
      LMsg := '';
      if LogTime then begin
        LMsg := DateTimeToStr(Now);
      end;
      s := DataString;
      if FReplaceCRLF then begin
        s := StringReplace(s, EOL, RSLogEOL, [rfReplaceAll]);
        s := StringReplace(s, CR, RSLogCR, [rfReplaceAll]);
        s := StringReplace(s, LF, RSLogLF, [rfReplaceAll]);
      end;
      LogSentData(LMsg, s);
    finally Free; end;
  end;
end;

procedure TIdLogBase.SetActive(const AValue: Boolean);
begin
  if (csReading in ComponentState) then
    FStreamedActive := AValue
  else
    if FActive <> AValue then
    begin
      FActive := AValue;
      if FActive then
        Open
      else
        Close;
    end;
end;

end.


