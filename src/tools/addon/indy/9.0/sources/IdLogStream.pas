{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10239: IdLogStream.pas 
{
{   Rev 1.0    2002.11.12 10:44:38 PM  czhower
}
unit IdLogStream;

interface
uses classes, IdLogBase;

type
  TIdLogStream = class(TIdLogBase)
  protected
    FInputStream : TStream;
    FOutputStream : TStream;
    procedure LogStatus(const AText: string); override;
    procedure LogReceivedData(const AText: string; const AData: string); override;
    procedure LogSentData(const AText: string; const AData: string); override;
  public
    property InputStream : TStream read FInputStream write FInputStream;
    property OutputStream : TStream read FOutputStream write FOutputStream;
  end;

implementation

{ TIdLogStream }

procedure TIdLogStream.LogReceivedData(const AText, AData: string);
begin
  if (Assigned(FInputStream)) and (Length(AData)>0) then
  begin
    FInputStream.Write(AData[1],Length(AData));
  end;
end;

procedure TIdLogStream.LogSentData(const AText, AData: string);
begin
  if (Assigned(FOutputStream)) and (Length(AData)>0) then
  begin
    FOutputStream.Write(AData[1],Length(AData));
  end;
end;

procedure TIdLogStream.LogStatus(const AText: string);
begin
  //we just leave this empty because the AText is not part of the stream and we don't    {Do not Localize}
  //want to raise an abstract method exception.
end;

end.
