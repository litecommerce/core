{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10237: IdLogFile.pas 
{
{   Rev 1.0    2002.11.12 10:44:30 PM  czhower
}
unit IdLogFile;

{
  Revision History:
  19-Aug-2001 DSiders Fixed bug in Open.  Use file mode fmCreate when Filename
                      does *not* exist.

  19-Aug-2001 DSiders Added protected method TIdLogFile.LogWriteString.

  19-Aug-2001 DSiders Changed implementation of TIdLogFile methods LogStatus,
                      LogReceivedData, and LogSentData to use LogWriteString.

  19-Aug-2001 DSiders Added class TIdLogFileEx with the LogFormat method.
}

interface

uses
  Classes,
  IdLogBase,
  SysUtils;

type
  TIdLogFile = class(TIdLogBase)
  protected
    FFilename: TFilename;
    FFileStream: TFileStream;
    //
    procedure Close; override;
    procedure LogFormat(const AFormat: string; const AArgs: array of const); virtual;
    procedure LogReceivedData(const AText: string; const AData: string); override;
    procedure LogSentData(const AText: string; const AData: string); override;
    procedure LogStatus(const AText: string); override;
    procedure LogWriteString(const AText: string); virtual;
    procedure Open; override;
  public
  published
    property Filename: TFilename read FFilename write FFilename;
  end;

implementation

uses
  IdGlobal,
  IdResourceStrings;

{ TIdLogFile }

procedure TIdLogFile.Close;
begin
  FreeAndNil(FFileStream);
end;

procedure TIdLogFile.LogReceivedData(const AText, AData: string);
begin
  LogWriteString(RSLogRecv + AText + ': ' + AData + EOL);  {Do not translate}
end;

procedure TIdLogFile.LogSentData(const AText, AData: string);
begin
  LogWriteString(RSLogSent + AText + ': ' + AData + EOL);  {Do not translate}
end;

procedure TIdLogFile.LogStatus(const AText: string);
begin
  LogWriteString(RSLogStat + AText + EOL);
end;

procedure TIdLogFile.Open;
begin
  if not (csDesigning in ComponentState) then begin
    if not FileExists(Filename) then begin
      FFileStream := TFileStream.Create(Filename, fmCreate or fmShareDenyWrite);
    end else begin
      FFileStream := TFileStream.Create(Filename, fmOpenReadWrite or fmShareDenyWrite);
      FFileStream.Position := FFileStream.Size;
    end;
  end;
end;

procedure TIdLogFile.LogWriteString(const AText: string);
begin
  if Length(AText) > 0 then begin
    FFileStream.WriteBuffer(AText[1], Length(AText));
  end;
end;

procedure TIdLogFile.LogFormat(const AFormat: string; const AArgs: array of const);
var
  sPre: string;
  sMsg: string;
  sData: string;
begin
  // forces Open to be called prior to Connect
  if not Active then
  begin
    Active := True;
  end;

  sPre := '';   {Do not translate}
  sMsg := '';   {Do not translate}

  if LogTime then
  begin
    sPre := DateTimeToStr(Now) + ' ' ;      {Do not translate}
  end;

  sData := Format(AFormat, AArgs);
  if FReplaceCRLF then begin
    sData := StringReplace(sData, EOL, RSLogEOL, [rfReplaceAll]);
    sData := StringReplace(sData, CR, RSLogCR,  [rfReplaceAll]);
    sData := StringReplace(sData, LF,  RSLogLF,  [rfReplaceAll]);
  end;
  sMsg := sPre + sData + EOL;

  LogWriteString(sMsg);
end;

end.

