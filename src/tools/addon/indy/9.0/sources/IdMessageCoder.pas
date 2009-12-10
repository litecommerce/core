{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10255: IdMessageCoder.pas 
{
{   Rev 1.0    2002.11.12 10:45:56 PM  czhower
}
unit IdMessageCoder;

interface

uses
  Classes,
  IdComponent, IdGlobal, IdMessage;

type
  TIdMessageCoderPartType = (mcptUnknown, mcptText, mcptAttachment);

  TIdMessageDecoder = class(TIdComponent)
  protected
    FFilename: string;
    // Dont use TIdHeaderList for FHeaders - we dont know that they will all be like MIME.
    FHeaders: TStrings;
    FPartType: TIdMessageCoderPartType;
    FSourceStream: TStream;
  public
    function ReadBody(ADestStream: TStream; var AMsgEnd: Boolean): TIdMessageDecoder; virtual; abstract;
    procedure ReadHeader; virtual;
    function ReadLn: string;
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    //
    property Filename: string read FFilename;
    property SourceStream: TStream read FSourceStream write FSourceStream;
    property Headers: TStrings read FHeaders;
    property PartType: TIdMessageCoderPartType read FPartType;
  end;

  TIdMessageDecoderInfo = class(TObject)
  public
    function CheckForStart(ASender: TIdMessage; ALine: string): TIdMessageDecoder; virtual;
     abstract;
    constructor Create; virtual;
  end;

  TIdMessageDecoderList = class(TObject)
  protected
    FMessageCoders: TStringList;
  public
    class function ByName(const AName: string): TIdMessageDecoderInfo;
    class function CheckForStart(ASender: TIdMessage; const ALine: string): TIdMessageDecoder;
    constructor Create;
    destructor Destroy; override;
    class procedure RegisterDecoder(const AMessageCoderName: string;
     AMessageCoderInfo: TIdMessageDecoderInfo);
  end;

  TIdMessageEncoder = class(TIdComponent)
  protected
    FFilename: string;
    FPermissionCode: integer;
  public
    constructor Create(AOwner: TComponent); override;
    procedure Encode(const AFilename: string; ADest: TStream); overload;
    procedure Encode(ASrc: TStream; ADest: TStream); overload; virtual; abstract;
  published
    property Filename: string read FFilename write FFilename;
    property PermissionCode: integer read FPermissionCode write FPermissionCode;
  end;

  TIdMessageEncoderClass = class of TIdMessageEncoder;

  TIdMessageEncoderInfo = class(TObject)
  protected
    FMessageEncoderClass: TIdMessageEncoderClass;
  public
    constructor Create; virtual;
    procedure InitializeHeaders(AMsg: TIdMessage); virtual;
    //
    property MessageEncoderClass: TIdMessageEncoderClass read FMessageEncoderClass;
  end;

  TIdMessageEncoderList = class(TObject)
  protected
    FMessageCoders: TStringList;
  public
    class function ByName(const AName: string): TIdMessageEncoderInfo;
    constructor Create;
    destructor Destroy; override;
    class procedure RegisterEncoder(const AMessageEncoderName: string;
     AMessageEncoderInfo: TIdMessageEncoderInfo);
  end;

implementation

uses
  IdException, IdResourceStrings, IdStream,
  SysUtils;

var
  GMessageDecoderList: TIdMessageDecoderList = nil;
  GMessageEncoderList: TIdMessageEncoderList = nil;

{ TIdMessageDecoderList }

class function TIdMessageDecoderList.ByName(const AName: string): TIdMessageDecoderInfo;
begin
  with GMessageDecoderList.FMessageCoders do begin
    Result := TIdMessageDecoderInfo(Objects[IndexOf(AName)]);
  end;
  if Result = nil then begin
    raise EIdException.Create(RSMessageDecoderNotFound + ': ' + AName);    {Do not Localize}
  end;
end;

class function TIdMessageDecoderList.CheckForStart(ASender: TIdMessage; const ALine: string): TIdMessageDecoder;
var
  i: integer;
begin
  Result := nil;
  for i := 0 to GMessageDecoderList.FMessageCoders.Count - 1 do begin
    Result := TIdMessageDecoderInfo(GMessageDecoderList.FMessageCoders.Objects[i]).CheckForStart(ASender
     , ALine);
    if Result <> nil then begin
      Break;
    end;
  end;
end;

constructor TIdMessageDecoderList.Create;
begin
  inherited;
  FMessageCoders := TStringList.Create;
end;

destructor TIdMessageDecoderList.Destroy;
var
  i: integer;
begin
  for i := 0 to FMessageCoders.Count - 1 do begin
    TIdMessageDecoderInfo(FMessageCoders.Objects[i]).Free;
  end;
  FreeAndNil(FMessageCoders);
  inherited;
end;

class procedure TIdMessageDecoderList.RegisterDecoder(const AMessageCoderName: string;
 AMessageCoderInfo: TIdMessageDecoderInfo);
begin
  if GMessageDecoderList = nil then begin
    GMessageDecoderList := TIdMessageDecoderList.Create;
  end;
  GMessageDecoderList.FMessageCoders.AddObject(AMessageCoderName, AMessageCoderInfo);
end;

{ TIdMessageDecoderInfo }

constructor TIdMessageDecoderInfo.Create;
begin
//
end;

{ TIdMessageDecoder }

constructor TIdMessageDecoder.Create(AOwner: TComponent);
begin
  inherited;
  FHeaders := TStringList.Create;
end;

destructor TIdMessageDecoder.Destroy;
begin
  FreeAndNil(FHeaders);
  FreeAndNil(FSourceStream);
  inherited;
end;

procedure TIdMessageDecoder.ReadHeader;
begin
end;

function TIdMessageDecoder.ReadLn: string;
begin
  Result := TIdStream(SourceStream).ReadLn;
end;

{ TIdMessageEncoderInfo }

constructor TIdMessageEncoderInfo.Create;
begin
//
end;

procedure TIdMessageEncoderInfo.InitializeHeaders(AMsg: TIdMessage);
begin
//
end;

{ TIdMessageEncoderList }

class function TIdMessageEncoderList.ByName(const AName: string): TIdMessageEncoderInfo;
begin
  with GMessageEncoderList.FMessageCoders do begin
    Result := TIdMessageEncoderInfo(Objects[IndexOf(AName)]);
  end;
  if Result = nil then begin
    raise EIdException.Create(RSMessageEncoderNotFound + ': ' + AName);    {Do not Localize}
  end;
end;

constructor TIdMessageEncoderList.Create;
begin
  inherited;
  FMessageCoders := TStringList.Create;
end;

destructor TIdMessageEncoderList.Destroy;
var
  i: integer;
begin
  for i := 0 to FMessageCoders.Count - 1 do begin
    TIdMessageEncoderInfo(FMessageCoders.Objects[i]).Free;
  end;
  FreeAndNil(FMessageCoders);
  inherited;
end;

class procedure TIdMessageEncoderList.RegisterEncoder(const AMessageEncoderName: string;
 AMessageEncoderInfo: TIdMessageEncoderInfo);
begin
  if GMessageEncoderList = nil then begin
    GMessageEncoderList := TIdMessageEncoderList.Create;
  end;
  GMessageEncoderList.FMessageCoders.AddObject(AMessageEncoderName, AMessageEncoderInfo);
end;

{ TIdMessageEncoder }

procedure TIdMessageEncoder.Encode(const AFilename: string; ADest: TStream);
var
  LSrcStream: TFileStream;
begin
  LSrcStream := TFileStream.Create(AFileName, fmShareDenyNone); try
    Encode(LSrcStream, ADest);
  finally FreeAndNil(LSrcStream); end;
end;

constructor TIdMessageEncoder.Create(AOwner: TComponent);
begin
  inherited;
  FPermissionCode := 660;
end;

initialization
finalization
  FreeAndNil(GMessageDecoderList);
  FreeAndNil(GMessageEncoderList);
end.
