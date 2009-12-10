{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10267: IdMultipartFormData.pas 
{
{   Rev 1.1    01.2.2003 ã. 12:00:00  DBondzhev
}
{
{   Rev 1.0    2002.11.12 10:46:56 PM  czhower
}
unit IdMultipartFormData;

{
  Implementation of the Multipart From data

  Author: Shiv Kumar
  Copyright: (c) Chad Z. Hower and The Winshoes Working Group.

Details of implementation
-------------------------
2001-Nov Doychin Bondzhev
 - Now it descends from TStream and does not do buffering.
 - Changes in the way the form parts are added to the stream.

 2001-Nov-23
  - changed spelling error from XxxDataFiled to XxxDataField
}


interface

uses
  SysUtils, Classes, IdGlobal, IdException, IdResourceStrings;

const
  sContentType = 'multipart/form-data; boundary=';
  crlf = #13#10;
  sContentDisposition = 'Content-Disposition: form-data; name="%s"';
  sFileNamePlaceHolder = '; filename="%s"';
  sContentTypePlaceHolder = 'Content-Type: %s' + crlf + crlf;

type
  TIdMultiPartFormDataStream = class;

  TIdFormDataField = class(TCollectionItem)
  protected
    FFieldSize: LongInt;

    FFieldValue: string;
    FFileName: string;
    FContentType: string;
    FFieldName: string;
    FFieldObject: TObject;
    FInternallyAssigned: Boolean;

    procedure SetFieldStream(const Value: TStream);
    function GetFieldSize: LongInt;
    procedure SetContentType(const Value: string);
    procedure SetFieldName(const Value: string);
    procedure SetFieldValue(const Value: string);
    function GetFieldStream: TStream;
    procedure SetFieldObject(const Value: TObject);
    procedure SetFileName(const Value: string);
  public
    constructor Create(Collection: TCollection); override;
    destructor Destroy; override;
    // procedure Assign(Source: TPersistent); override;
    property ContentType: string read FContentType write SetContentType;
    property FieldName: string read FFieldName write SetFieldName;
    property FieldStream: TStream read GetFieldStream write SetFieldStream;
    property FieldObject: TObject read FFieldObject write SetFieldObject;
    property FileName: string read FFileName write SetFileName;
    property FieldValue: string read FFieldValue write SetFieldValue;

    property FieldSize: LongInt read GetFieldSize write FFieldSize;
  end;

  TIdFormDataFields = class(TCollection)
  protected
    FParentStream: TIdMultiPartFormDataStream;

    function GetFormDataField(AIndex: Integer): TIdFormDataField;
    {procedure SetFormDataField(AIndex: Integer;
      const Value: TIdFormDataField);}
  public
    constructor Create(AMPStream: TIdMultiPartFormDataStream);

    function Add: TIdFormDataField;

    property MultipartFormDataStream: TIdMultiPartFormDataStream read FParentStream;
    property Items[AIndex: Integer]: TIdFormDataField read GetFormDataField { write SetFormDataField};
  end;

  TIdMultiPartFormDataStream = class(TStream)
  protected
    FInputStream: TStream;
    FBoundary: string;
    FRequestContentType: string;
    FItem: integer;
    FInitialized: Boolean;
    FInternalBuffer: string;

    FPosition: Int64;
    FSize: Int64;

    FFields: TIdFormDataFields;

    function GenerateUniqueBoundary: string;
    function FormatField(AIndex: Integer): string;
    function PrepareStreamForDispatch: string;
  public
    constructor Create;
    destructor Destroy; override;

    function Read(var Buffer; Count: Longint): Longint; override;
    function Write(const Buffer; Count: Longint): Longint; override;
    function Seek(Offset: Longint; Origin: Word): Longint; overload; override;

    procedure AddFormField(const AFieldName, AFieldValue: string);
    procedure AddObject(const AFieldName, AContentType: string; AFileData: TObject; const AFileName: string = '');
    procedure AddFile(const AFieldName, AFileName, AContentType: string);

    property Boundary: string read FBoundary;
    property RequestContentType: string read FRequestContentType;
  end;

  EIdInvalidObjectType = class(EIdException);

implementation

{ TIdMultiPartFormDataStream }

constructor TIdMultiPartFormDataStream.Create;
begin
  inherited Create;

  FSize := 0;
  FInitialized := false;
  FBoundary := GenerateUniqueBoundary;
  FRequestContentType := sContentType + FBoundary;
  FFields := TIdFormDataFields.Create(Self);
end;

procedure TIdMultiPartFormDataStream.AddObject(const AFieldName,
  AContentType: string; AFileData: TObject; const AFileName: string = '');
var
  FItem: TIdFormDataField;
begin
  FItem := FFields.Add;

  with FItem do begin
    FieldName := AFieldName;
    FileName := AFileName;
    FFieldObject := AFileData;
    ContentType := AContentType;
  end;

  FSize := FSize + FItem.FieldSize;
end;

procedure TIdMultiPartFormDataStream.AddFile(const AFieldName, AFileName,
  AContentType: string);
var
  FileStream: TFileStream;
  FItem: TIdFormDataField;
begin
  FItem := FFields.Add;
  FileStream := TFileStream.Create(AFileName, fmOpenRead or fmShareDenyWrite);

  with FItem do begin
    FieldName := AFieldName;
    FileName := AFileName;
    FFieldObject := FileStream;
    ContentType := AContentType;
    FInternallyAssigned := true;
  end;

  FSize := FSize + FItem.FieldSize;
end;

procedure TIdMultiPartFormDataStream.AddFormField(const AFieldName,
  AFieldValue: string);
var
  FItem: TIdFormDataField;
begin
  FItem := FFields.Add;

  with FItem do begin
    FieldName := AFieldName;
    FieldValue := AFieldValue;
  end;
  FSize := FSize + FItem.FieldSize;
end;

function TIdMultiPartFormDataStream.FormatField(AIndex: Integer): string;
  function FileField(AItem: TIdFormDataField): string;
  begin
    with AItem do begin
      result := '--' + Boundary + crlf + sContentDisposition + FieldName + '"' +
        sFileNamePlaceHolder + FileName + '"' + crlf +
        sContentTypePlaceHolder + ContentType;
    end;
  end;

  function NormalField(AItem: TIdFormDataField): string;
  begin
    with AItem do begin
      result := '--' + Boundary + crlf + sContentDisposition + FieldName + '"' + crlf + crlf +
        FieldValue + crlf;
    end;
  end;

begin
  with FFields.Items[AIndex] do begin
    if Assigned(FieldObject) then begin
      if Length(FileName) > 0 then begin
        result := FileField(FFields.Items[AIndex]);
      end
      else begin
        result := NormalField(FFields.Items[AIndex]);
      end;
    end
    else begin
      result := NormalField(FFields.Items[AIndex]);
    end;
  end;
end;


function TIdMultiPartFormDataStream.GenerateUniqueBoundary: string;
begin
  Result := '--------' + FormatDateTime('mmddyyhhnnsszzz', Now);
end;

function TIdMultiPartFormDataStream.PrepareStreamForDispatch: string;
begin
  result := crlf + '--' + Boundary + '--' + crlf;
end;

function TIdMultiPartFormDataStream.Read(var Buffer;
  Count: Integer): Longint;
type
  PByteArray = ^TByteArray;
  TByteArray = array[0..High(Integer) - 1] of Byte; // 2GB size
var
  LTotalRead: Integer;
  LCount: Integer;
  LBufferCount: Integer;
begin
  if not FInitialized then begin
    FInitialized := true;
    FItem := 0;
    SetLength(FInternalBuffer, 0);
  end;

  LTotalRead := 0;
  LBufferCount := 0;

  while (LTotalRead < Count) and ((FItem < FFields.Count) or (Length(FInternalBuffer) > 0)) do begin
    if (Length(FInternalBuffer) = 0) and not Assigned(FInputStream) then begin
      FInternalBuffer := FormatField(FItem);

      if Assigned(FFields.Items[FItem].FieldObject) then begin
        if (FFields.Items[FItem].FieldObject is TStream) then begin
          FInputStream := FFields.Items[FItem].FieldObject as TStream;
          FInputStream.Seek(0, soFromBeginning);
        end
        else
          FInputStream := nil;

        if (FFields.Items[FItem].FieldObject is TStrings) then begin
          FInternalBuffer := FInternalBuffer + (FFields.Items[FItem].FieldObject as TStrings).Text;
          Inc(FItem);
        end;
      end
      else begin
        Inc(FItem);
      end;
    end;

    if Length(FInternalBuffer) > 0 then begin
      if Length(FInternalBuffer) > Count - LBufferCount then begin
        LCount := Count - LBufferCount;
      end
      else begin
        LCount := Length(FInternalBuffer);
      end;

      Move(FInternalBuffer[1], TByteArray(Buffer)[LBufferCount], LCount);
      Delete(FInternalBuffer, 1, LCount);

      LBufferCount := LBufferCount + LCount;
      FPosition := FPosition + LCount;
      LTotalRead := LTotalRead + LCount;
    end;

    if Assigned(FInputStream) and (LTotalRead < Count) then begin
      LCount := FInputStream.Read(TByteArray(Buffer)[LBufferCount], Count - LTotalRead);
      if LCount < Count - LTotalRead then begin
        FInputStream.Seek(0, soFromBeginning);
        FInputStream := nil;
        Inc(FItem);
        FInternalBuffer := #13#10;
      end;

      LBufferCount := LBufferCount + LCount;
      LTotalRead := LTotalRead + LCount;
      FPosition := FPosition + LCount;
    end;
    if FItem = FFields.Count then begin
      FInternalBuffer := FInternalBuffer + PrepareStreamForDispatch;
      Inc(FItem);
    end;
  end;
  result := LTotalRead;
end;

destructor TIdMultiPartFormDataStream.Destroy;
begin
  FreeAndNil(FFields);
  inherited Destroy;
end;

function TIdMultiPartFormDataStream.Seek(Offset: Integer;
  Origin: Word): Longint;
begin
  result := 0;
  case Origin of
    soFromBeginning: begin
        if (Offset = 0) then begin
          FInitialized := false;
          FPosition := 0;
          result := 0;
        end
        else
          result := FPosition;
      end;
    soFromCurrent: begin
        result := FPosition;
      end;
    soFromEnd: begin
        result := FSize + Length(PrepareStreamForDispatch);
      end;
  end;
end;

function TIdMultiPartFormDataStream.Write(const Buffer;
  Count: Integer): Longint;
begin
  raise Exception.Create('Unsupported operation.');
end;

{ TIdFormDataFields }

function TIdFormDataFields.Add: TIdFormDataField;
begin
  result := TIdFormDataField(inherited Add);
end;

constructor TIdFormDataFields.Create(AMPStream: TIdMultiPartFormDataStream);
begin
  inherited Create(TIdFormDataField);

  FParentStream := AMPStream;
end;

function TIdFormDataFields.GetFormDataField(
  AIndex: Integer): TIdFormDataField;
begin
  result := TIdFormDataField(inherited Items[AIndex]);
end;

{procedure TIdFormDataFields.SetFormDataField(AIndex: Integer;
  const Value: TIdFormDataField);
begin
  Items[AIndex].Assign(Value);
end;}

{ TIdFormDataField }

{procedure TIdFormDataField.Assign(Source: TPersistent);
begin
  if Source is TIdFormDataField then begin
    (Source as TIdFormDataField).FFileName := FFileName;
    (Source as TIdFormDataField).FContentType := FContentType;
    (Source as TIdFormDataField).FFieldObject := FFieldObject;
    (Source as TIdFormDataField).FieldName := FieldName;
  end
  else begin
    inherited Assign(Source);
  end;
end;}

constructor TIdFormDataField.Create(Collection: TCollection);
begin
  inherited Create(Collection);

  FFieldObject := nil;
  FFileName := '';
  FFieldName := '';
  FContentType := '';
  FInternallyAssigned := false;
end;

destructor TIdFormDataField.Destroy;
begin
  if Assigned(FFieldObject) and FInternallyAssigned then
    FFieldObject.Free;
  inherited Destroy;
end;

function TIdFormDataField.GetFieldSize: LongInt;
begin
  if Length(FFileName) > 0 then begin
    FFieldSize := Length('--' + (Collection as TIdFormDataFields).FParentStream.Boundary
      + crlf + sContentDisposition + FieldName + '"' + sFileNamePlaceHolder + FileName + '"' + crlf
      + sContentTypePlaceHolder + ContentType);
  end
  else begin
    FFieldSize := Length('--' + (Collection as TIdFormDataFields).FParentStream.Boundary +
      crlf + sContentDisposition + FieldName + '"' + crlf + crlf + FFieldValue + crlf);
  end;

  if Assigned(FFieldObject) then begin
    if FieldObject is TStrings then
      FFieldSize := FFieldSize + Length((FieldObject as TStrings).Text) + 2;
    if FieldObject is TStream then
      FFieldSize := FFieldSize + FieldStream.Size + 2;
  end;

  Result := FFieldSize;
end;

function TIdFormDataField.GetFieldStream: TStream;
begin
  result := nil;
  if Assigned(FFieldObject) then begin
    if (FFieldObject is TStream) then begin
      result := TStream(FFieldObject);
    end
    else begin
      raise EIdInvalidObjectType.Create(RSMFDIvalidObjectType);
    end;
  end;
end;

procedure TIdFormDataField.SetContentType(const Value: string);
begin
  FContentType := Value;
  GetFieldSize;
end;

procedure TIdFormDataField.SetFieldName(const Value: string);
begin
  FFieldName := Value;
  GetFieldSize;
end;

procedure TIdFormDataField.SetFieldObject(const Value: TObject);
begin
  if Assigned(Value) then begin
    if (Value is TStream) or (Value is TStrings) then begin
      FFieldObject := Value;
      GetFieldSize;
    end
    else begin
      raise EIdInvalidObjectType.Create(RSMFDIvalidObjectType);
    end;
  end
  else
    FFieldObject := Value;
end;

procedure TIdFormDataField.SetFieldStream(const Value: TStream);
begin
  FieldObject := Value;
end;

procedure TIdFormDataField.SetFieldValue(const Value: string);
begin
  FFieldValue := Value;
  GetFieldSize;
end;

procedure TIdFormDataField.SetFileName(const Value: string);
begin
  FFileName := Value;
  GetFieldSize;
end;

end.

