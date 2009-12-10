{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10311: IdRFCReply.pas 
{
{   Rev 1.2    11/05/2003 23:21:56  CCostelloe
{ IMAP-specific code moved from here to TIdIMAP4.pas
}
{
{   Rev 1.1    3/23/2003 05:29:34 PM  JPMugaas
{ Updated TIdRFCReply so it can display things in the TCollection editor
{ better.  This is a backport from Indy 10.
}
{
{   Rev 1.0    2002.11.12 10:50:40 PM  czhower
}
unit IdRFCReply;

interface

uses
  Classes;

type
  TIdRFCReply = class(TCollectionItem)
  protected
    FNumericCode: integer;
    FText: TStrings;
    FTextCode: string;
    //
    procedure AssignTo(ADest: TPersistent); override;
    function GetDisplayName: string; override;
    procedure SetNumericCode(const AValue: Integer);
    procedure SetText(const AValue: TStrings);
    procedure SetTextCode(const AValue: string);
  public
    procedure Clear;
    constructor Create(Collection: TCollection); override;
    destructor Destroy; override;
    function GenerateReply: string;
    procedure ParseResponse(const AStrings: TStrings); overload;
    function ReplyExists: Boolean;
    procedure SetReply(const ANumericCode: Integer; const AText: string);
  published
    property NumericCode: Integer read FNumericCode write SetNumericCode;
    property Text: TStrings read FText write SetText;
    property TextCode: string read FTextCode write SetTextCode;
  end;

  TIdRFCReplies = class(TOwnedCollection)
  protected
    function GetItem(Index: Integer): TIdRFCReply;
    procedure SetItem(Index: Integer; const Value: TIdRFCReply);
  public
    function Add: TIdRFCReply; overload;
    function Add(const ANumericCode: Integer; const AText: string): TIdRFCReply; overload;
    constructor Create(AOwner: TPersistent); reintroduce;
    function FindByNumber(const ANo: Integer): TIdRFCReply; virtual;
    function UpdateReply(const ANumericCode: Integer; const AText: string): TIdRFCReply;
    procedure UpdateText(AReply: TIdRFCReply);
    //
    property Items[Index: Integer]: TIdRFCReply read GetItem write SetItem; default;
  end;

implementation

uses
  IdGlobal,
  SysUtils;

{ TIdRFCReply }

procedure TIdRFCReply.AssignTo(ADest: TPersistent);
begin
  if ADest is TIdRFCReply then begin
    with TIdRFCReply(ADest) do begin
      Clear;
      // Bypass as this and TextCode mutually exclude each other
      FNumericCode := Self.NumericCode;
      Text.Assign(Self.Text);
      // Bypass as this and NumericCode mutually exclude each other
      FTextCode := Self.TextCode;
    end;
  end else begin
    inherited;
  end;
end;

procedure TIdRFCReply.Clear;
begin
  FNumericCode := 0;
  FText.Clear;
  FTextCode := '';
end;

constructor TIdRFCReply.Create(Collection: TCollection);
begin
  inherited;
  FText := TStringList.Create;
  Clear;
end;

destructor TIdRFCReply.Destroy;
begin
  FreeAndNil(FText);
  inherited;
end;

function TIdRFCReply.GenerateReply: string;
var
  i: Integer;
begin
  // TODO: Account for TextCode <> '' when integrated into POP3
  Result := '';
  if NumericCode > 0 then begin
    Result := '';
    if FText.Count > 0 then begin
      for i := 0 to FText.Count - 1 do begin
        if i < FText.Count - 1 then begin
          Result := Result + IntToStr(NumericCode) + '-' + FText[i] + EOL;
        end else begin
          Result := Result + IntToStr(NumericCode) + ' ' + FText[i] + EOL;
        end;
      end;
    end else begin
      Result := Result + IntToStr(NumericCode) + EOL;
    end;
  end else if FText.Count > 0 then begin
    Result := FText.Text;
  end;
end;

procedure TIdRFCReply.ParseResponse(const AStrings: TStrings);
var
  i: Integer;
  s: string;
begin
  Clear;
  if AStrings.Count > 0 then begin
    // Get 4 chars - for POP3
    s := Trim(Copy(AStrings[0], 1, 4));
    if Length(s) = 4 then begin
      if s[4] = '-' then begin
        SetLength(s, 3);
      end;
    end;
    TextCode := s;
    for i := 0 to AStrings.Count - 1 do begin
      Text.Add(Copy(AStrings[i], 5, MaxInt));
    end;
  end;
end;

function TIdRFCReply.ReplyExists: Boolean;
begin
  Result := (NumericCode > 0) or (FText.Count > 0);
end;

procedure TIdRFCReply.SetNumericCode(const AValue: Integer);
begin
  FNumericCode := AValue;
  // Dont reset the text if 0 otherwise there are streaming and assign problems
  if AValue > 0 then begin
    FTextCode := IntToStr(AValue);
  end;
end;

procedure TIdRFCReply.SetReply(const ANumericCode: Integer; const AText: string);
begin
  FNumericCode := ANumericCode;
  FText.Text := AText;
end;

procedure TIdRFCReply.SetText(const AValue: TStrings);
begin
  FText.Assign(AValue);
end;

procedure TIdRFCReply.SetTextCode(const AValue: string);
begin
  FTextCode := AValue;
  // Dont reset the numeric if '' otherwise there are streaming and assign problems
  if Length(AValue) > 0 then begin
    // StrToIntDef is necessary for POP3
    FNumericCode := StrToIntDef(AValue, 0);
  end;
end;

function TIdRFCReply.GetDisplayName: string;
begin
  if Text.Count > 0 then begin
    Result := TextCode + ' ' + Text[0];
  end else begin
    Result := TextCode;
  end;
end;
{ TIdRFCReplies }

function TIdRFCReplies.Add: TIdRFCReply;
begin
  Result := TIdRFCReply(inherited Add);
end;

function TIdRFCReplies.Add(const ANumericCode: Integer; const AText: string): TIdRFCReply;
begin
  Result := nil;
  if FindByNumber(ANumericCode) = nil then begin
    Result := Add;
    Result.SetReply(ANumericCode, AText);
  end;
end;

constructor TIdRFCReplies.Create(AOwner: TPersistent);
begin
  inherited Create(AOwner, TIdRFCReply);
end;

function TIdRFCReplies.FindByNumber(const ANo: Integer): TIdRFCReply;
var
  i: Integer;
begin
  Result := nil;
  for i := 0 to Count - 1 do begin
    if Items[i].FNumericCode = ANo then begin
      Result := Items[i];
      Break;
    end;
  end;
end;

function TIdRFCReplies.GetItem(Index: Integer): TIdRFCReply;
begin
  Result := TIdRFCReply(inherited Items[Index]);
end;

procedure TIdRFCReplies.SetItem(Index: Integer; const Value: TIdRFCReply);
begin
  inherited SetItem(Index, Value);
end;

function TIdRFCReplies.UpdateReply(const ANumericCode: Integer; const AText: string): TIdRFCReply;
begin
  Result := FindByNumber(ANumericCode);
  if Result = nil then begin
    Result := Add;
  end;
  Result.SetReply(ANumericCode, AText);
end;

procedure TIdRFCReplies.UpdateText(AReply: TIdRFCReply);
var
  LReply: TIdRFCReply;
begin
  // Reply text is blank, get it from the ReplyTexts
  if AReply.Text.Count = 0 then begin
    LReply := FindByNumber(AReply.NumericCode);
    if LReply <> nil then begin
      AReply.Text.Assign(LReply.Text);
    end;
  end;
end;

end.
