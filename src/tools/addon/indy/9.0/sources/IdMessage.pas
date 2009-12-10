{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10251: IdMessage.pas
{
    Rev 1.5    6/17/2003 2:07:08 AM  DSiders
  Modified TIdMessage.SaveToStream to call Connect / Disconnect for the message
  client.  Required due to the new Active property in TIdIOHandler.
}
{
{   Rev 1.4    2003.06.15 3:00:32 PM  czhower
{ -Fixed IdIOHandlerStream to function as originally designed and needed.
{ -Change ReadStream, WriteStream to Input/Output to be consistent with other
{ areas.
}
{
    Rev 1.3    1/27/2003 10:04:52 PM  DSiders
  Corrected error setting file stream permissions in LoadFromFile.  Bug Report
  649502.
}
{
{   Rev 1.2    27/1/2003 2:33:16 PM  SGrobety
{ Only issue X-Priority header if priority is <> mpNormal
}
{
{   Rev 1.1    09/12/2002 18:23:14  ANeillans
{ Removed X-Library Line
}
{
{   Rev 1.0    2002.11.12 10:45:26 PM  czhower
}
{//////////////////////////////////////////////////////////////////
2001-Jul-11 Hadi Hariri
  TODO: Make checks for encoding and content-type later on.
  TODO: Add TIdHTML, TIdRelated
  TODO: CountParts on the fly
  TODO: Merge Encoding and AttachmentEncoding
  TODO: Make encoding plugable
  TODO: Clean up ISO header coding
/////////////////////////////////////////////////////////////////}

unit IdMessage;

{
2002-12-09 Andrew Neillans
  Removed X-Library Line
2001-12-27 Andrew P.Rybin
  Custom InitializeISO, ExtractCharSet
2001-Oct-29 Don Siders
  Added EIdMessageCannotLoad exception.
  Added RSIdMessageCannotLoad constant.
  Added TIdMessage.LoadFromStream.
  Modified TIdMessage.LoadFromFile to call LoadFromStream.
  Added TIdMessage.SaveToStream.
  Modified TIdMessage.SaveToFile to call SaveToStream.
  Modified TIdMessage.GenerateHeader to include headers received but not used in properties.
2001-Sep-14 Andrew Neillans
  Added LoadFromFile Header only
2001-Sep-12 Johannes Berg
  Fixed upper/lowercase in uses clause for Kylix
2001-Aug-09 Allen O'Neill
  Added line to check for valid charset value before adding second ';' after content-type boundry
2001-Aug-07 Allen O'Neill
  Added SaveToFile & LoadFromFile ... Doychin fixed
2001-Jul-11 Hadi Hariri
  Added Encoding for both MIME and UU.
2000-Jul-25 Hadi Hariri
 - Added support for MBCS
2000-Jun-10 Pete Mee
 - Fixed some minor but annoying bugs.
2000-May-06 Pete Mee
 - Added coder support directly into TIdMessage.
}

{ TODO : Moved Decode/Encode out and will add later,. Maybe TIdMessageEncode, Decode?? }

{ TODO : Support any header in TMessagePart }

{ DESIGN NOTE: The TIdMessage has an fBody which should only ever be the
    raw message.  TIdMessage.fBody is only raw if TIdMessage.fIsEncoded = true

    The component parts are thus possibly made up of the following
    order of TMessagePart entries:

    MP[0] : Possible prologue text (fBoundary is '')

    MP[0 or 1 - depending on prologue existence] :
      fBoundary = boundary parameter from Content-Type

    MP[next...] : various parts with or without fBoundary = ''

    MP[MP.Count - 1] : Possible epilogue text (fBoundary is '')
    }

{ DESIGN NOTE: If TMessagePart.fIsEncoded = True, then TMessagePart.fBody
    is the encoded raw message part.  Otherwise, it is the (decoded) text.
    }

interface

uses
	Classes,
  IdBaseComponent, IdException, IdEMailAddress, IdHeaderList,
  IdCoderHeader, SysUtils;

type
  TIdMessagePriority = (mpHighest, mpHigh, mpNormal, mpLow, mpLowest);

const
  ID_MSG_NODECODE = False;
  ID_MSG_USENOWFORDATE = True;
  ID_MSG_PRIORITY = mpNormal;

type
  TOnGetMessagePartStream = procedure(AStream: TStream) of object;


  TIdMIMEBoundary = class
  protected
    FBoundaryList: TStrings;
    FNewBoundary: Boolean;

    function GetBoundary: string;
  public
    constructor Create;
    destructor Destroy; override;
    class function FindBoundary(AContentType: string): string;
    procedure Push(ABoundary: string);
    procedure Pop;
    procedure Clear;

    property Boundary: string read GetBoundary;
    property NewBoundary: Boolean read FNewBoundary write FNewBoundary;
  end;

  TIdMessageFlags =
  ( mfAnswered, //Message has been answered.
    mfFlagged, //Message is "flagged" for urgent/special attention.
    mfDeleted, //Message is "deleted" for removal by later EXPUNGE.
    mfDraft, //Message has not completed composition (marked as a draft).
    mfSeen, //Message has been read.
    mfRecent ); //Message is "recently" arrived in this mailbox.

  TIdMessageFlagsSet = set of TIdMessageFlags;

  TIdMessagePart = class(TCollectionItem)
  protected
    FBoundary: string;
    FBoundaryBegin: Boolean;
    FBoundaryEnd: Boolean;
    FContentMD5: string;
    FContentTransfer: string;
    FContentType: string;
    FEndBoundary: string;
    FExtraHeaders: TIdHeaderList;
    FHeaders: TIdHeaderList;
    FIsEncoded: Boolean;
    FOnGetMessagePartStream: TOnGetMessagePartStream;
    FStoredPathName: TFileName;
    //
    function GetContentType: string;
    function GetContentTransfer: string;
    procedure SetContentType(const Value: string);
    procedure SetContentTransfer(const Value: string);
    procedure SetExtraHeaders(const Value: TIdHeaderList);
  public
    constructor Create(Collection: TCollection); override;
    destructor Destroy; override;
    procedure Assign(Source: TPersistent); override;
    //
    property Boundary : String read FBoundary write FBoundary;
    property BoundaryBegin : Boolean read FBoundaryBegin write FBoundaryBegin;
    property BoundaryEnd : Boolean read FBoundaryEnd write FBoundaryEnd;
    property IsEncoded : Boolean read fIsEncoded;
    property OnGetMessagePartStream: TOnGetMessagePartStream read FOnGetMessagePartStream
     write FOnGetMessagePartStream;
    property StoredPathName: TFileName read FStoredPathName write FStoredPathName;
    property Headers: TIdHeaderList read FHeaders;
  published
    property ContentTransfer: string read GetContentTransfer write SetContentTransfer;
    property ContentType: string read GetContentType write SetContentType;
    property ExtraHeaders: TIdHeaderList read FExtraHeaders write SetExtraHeaders;
  end;

  TIdMessagePartClass = class of TIdMessagePart;

  TIdMessageParts = class;

  TIdAttachment = class(TIdMessagePart)
  protected
    FContentDisposition: string;
    FFileIsTempFile: boolean;
    FFileName: TFileName;
    //
    function GetContentDisposition: string;
    procedure SetContentDisposition(const Value: string);
  public
    procedure Assign(Source: TPersistent); override;
    constructor Create(Collection: TIdMessageParts; const AFileName: TFileName = ''); reintroduce;
    destructor Destroy; override;
    procedure Encode(ADest: TStream);
    function SaveToFile(const FileName: TFileName): Boolean;
    //
    property ContentDisposition: string read GetContentDisposition write SetContentDisposition;
    property FileIsTempFile: boolean read FFileIsTempFile write FFileIsTempFile;
    property FileName: TFileName read FFileName write FFileName;
  end;

  TIdText = class(TIdMessagePart)
  protected
    FBody: TStrings;
    procedure SetBody(const AStrs : TStrings);
  public
    constructor Create(Collection: TIdMessageParts; ABody: TStrings = nil); reintroduce;
    destructor Destroy; override;
    procedure Assign(Source: TPersistent); override;
    //
    property Body: TStrings read FBody write SetBody;
  end;

  TIdMessageParts = class(TOwnedCollection)
  protected
    FAttachmentEncoding: string;
    FAttachmentCount: integer;
    FMessageEncoderInfo: TObject;
    FRelatedPartCount: integer;
    FTextPartCount: integer;
    //
    function GetItem(Index: Integer): TIdMessagePart;
    procedure SetAttachmentEncoding(const AValue: string);
    procedure SetItem(Index: Integer; const Value: TIdMessagePart);
  public
    function Add: TIdMessagePart;
    procedure CountParts;
    constructor Create(AOwner: TPersistent); reintroduce;
    //
    property AttachmentCount: integer read FAttachmentCount;
    property AttachmentEncoding: string read FAttachmentEncoding write SetAttachmentEncoding;
    property Items[Index: Integer]: TIdMessagePart read GetItem write SetItem; default;
    property MessageEncoderInfo: TObject read FMessageEncoderInfo;
    property RelatedPartCount: integer read FRelatedPartCount;
    property TextPartCount: integer read FTextPartCount;
  end;

  TIdMessageEncoding = (meMIME, meUU);
  TIdInitializeIsoEvent = procedure (var VTransferHeader: TTransfer; var VHeaderEncoding: Char;
    var VCharSet: string) of object;

  TIdMessage = class(TIdBaseComponent)
  protected
    FBccList: TIdEmailAddressList;
    FBody: TStrings;
    FCharSet: string;
    FCcList: TIdEmailAddressList;
    FContentType: string;
    FContentTransferEncoding: string;
    FContentDisposition: string;
    FDate: TDateTime;
    FIsEncoded : Boolean;
    FExtraHeaders: TIdHeaderList;
    FEncoding: TIdMessageEncoding;
    FFlags: TIdMessageFlagsSet;
    FFrom: TIdEmailAddressItem;
    FHeaders: TIdHeaderList;
    FMessageParts: TIdMessageParts;
    FMIMEBoundary: TIdMIMEBoundary;
    FMsgId: string;
    FNewsGroups: TStrings;
    FNoEncode: Boolean;
    FNoDecode: Boolean;
    FOnInitializeISO: TIdInitializeISOEvent;
    FOrganization: string;
    FPriority: TIdMessagePriority;
    FSubject: string;
    FReceiptRecipient: TIdEmailAddressItem;
    FRecipients: TIdEmailAddressList;
    FReferences: string;
    FReplyTo: TIdEmailAddressList;
    FSender: TIdEMailAddressItem;
    FUID: String;
    FXProgram: string;
    //
    procedure DoInitializeISO(var VTransferHeader: TTransfer; var VHeaderEncoding: Char; var VCharSet: string); virtual;
    function GetAttachmentEncoding: string;
    procedure SetAttachmentEncoding(const AValue: string);
    procedure SetEncoding(const AValue: TIdMessageEncoding);
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;

    procedure AddHeader(const Value: string);
    procedure Clear; virtual;
    procedure ClearBody;
    procedure ClearHeader;
    function GenerateHeader: TIdHeaderList;
    function GetUseNowForDate: Boolean;

    // 2001-Oct-29 Don Siders
    procedure LoadFromFile(const AFileName: string; const AHeadersOnly: Boolean = False);
    procedure LoadFromStream(AStream: TStream; const AHeadersOnly: Boolean = False);

    procedure ProcessHeaders;

    // 2001-Oct-29 Don Siders
    procedure SaveToFile(const AFileName : string; const AHeadersOnly: Boolean = False);
    procedure SaveToStream(AStream: TStream; const AHeadersOnly: Boolean = False);

    procedure SetBody(const Value: TStrings);
    procedure SetNewsGroups(const Value: TStrings);
    procedure SetExtraHeaders(const Value: TIdHeaderList);
    procedure SetUseNowForDate(const Value: Boolean);
    //
    property Flags: TIdMessageFlagsSet read FFlags write FFlags;
    property IsEncoded : Boolean read fIsEncoded write fIsEncoded;
    property MsgId: string read FMsgId write FMsgId;
    property Headers: TIdHeaderList read FHeaders;
    property MessageParts: TIdMessageParts read FMessageParts;
    property MIMEBoundary: TIdMIMEBoundary read FMIMEBoundary write FMIMEBoundary;
    property UID: String read FUID write FUID;
  published
    //TODO: Make a property editor which drops down the registered coder types
    property AttachmentEncoding: string read GetAttachmentEncoding write SetAttachmentEncoding;
    property Body: TStrings read FBody write SetBody;
    property BccList: TIdEmailAddressList read FBccList write FBccList;
    property CharSet: string read FCharSet write FCharSet;
    property CCList: TIdEmailAddressList read FCcList write FCcList;
    property ContentType: string read FContentType write FContentType;
    property ContentTransferEncoding: string read FContentTransferEncoding
     write FContentTransferEncoding;
    property ContentDisposition: string read FContentDisposition write FContentDisposition;
    property Date: TDateTime read FDate write FDate;
    //
    property Encoding: TIdMessageEncoding read FEncoding write SetEncoding;
    property ExtraHeaders: TIdHeaderList read FExtraHeaders write SetExtraHeaders;
    property From: TIdEmailAddressItem read FFrom write FFrom;
    property NewsGroups: TStrings read FNewsGroups write SetNewsGroups;
    property NoEncode: Boolean read FNoEncode write FNoEncode default ID_MSG_NODECODE;
    property NoDecode: Boolean read FNoDecode write FNoDecode default ID_MSG_NODECODE;
    property Organization: string read FOrganization write FOrganization;
    property Priority: TIdMessagePriority read FPriority write FPriority default ID_MSG_PRIORITY;
    property ReceiptRecipient: TIdEmailAddressItem read FReceiptRecipient write FReceiptRecipient;
    property Recipients: TIdEmailAddressList read FRecipients write FRecipients;
    property References: string read FReferences write FReferences;
    property ReplyTo: TIdEmailAddressList read FReplyTo write FReplyTo;
    property Subject: string read FSubject write FSubject;
    property Sender: TIdEmailAddressItem read FSender write FSender;
    property UseNowForDate: Boolean read GetUseNowForDate write SetUseNowForDate default ID_MSG_USENOWFORDATE;
    // Events
    property OnInitializeISO: TIdInitializeIsoEvent read FOnInitializeISO write FOnInitializeISO;
  End;

  TIdMessageEvent = procedure(ASender : TComponent; var AMsg : TIdMessage) of object;

  TIdStringMessageEvent = procedure(ASender : TComponent; const AString : String; var AMsg : TIdMessage) of object;

  EIdMessageException = class(EIdException);
  EIdCanNotCreateMessagePart = class(EIdMessageException);
  EIdTextInvalidCount = class(EIdMessageException);

  // 2001-Oct-29 Don Siders
  EIdMessageCannotLoad = class(EIdMessageException);

const
  // 2001-Oct-29 Don Siders
  // TODO: Move to IdResourceStrings.pas
  RSIdMessageCannotLoad = 'Cannot load message from file %s'; {Do not Localize}

const
  MessageFlags : array [mfAnswered..mfRecent] of String =
  ( '\Answered', {Do not Localize} //Message has been answered.
    '\Flagged', {Do not Localize} //Message is "flagged" for urgent/special attention.
    '\Deleted', {Do not Localize} //Message is "deleted" for removal by later EXPUNGE.
    '\Draft', {Do not Localize} //Message has not completed composition (marked as a draft).
    '\Seen', {Do not Localize} //Message has been read.
    '\Recent' ); {Do not Localize} //Message is "recently" arrived in this mailbox.

implementation

uses
  IdMessageCoderMIME, // Here so the 'MIME' in create will always suceed
  IdGlobal, IdMessageCoder, IdResourceStrings, IdStream,
  IdMessageClient, IdIOHandlerStream, IdStrings;

{ TIdMIMEBoundary }

procedure TIdMIMEBoundary.Clear;
begin
  FBoundaryList.Clear;
end;

constructor TIdMIMEBoundary.Create;
begin
  FBoundaryList := TStringList.Create;
end;

destructor TIdMIMEBoundary.Destroy;
begin
  FBoundaryList.Free;
  inherited Destroy;
end;

class function TIdMIMEBoundary.FindBoundary(AContentType: string): string;
var
  s: string;
begin
  // Store in s and not Result because of Fetch semantics
  s := UpperCase(AContentType);
  Fetch(s, 'BOUNDARY='); {do not localize}
  if (Length(s) > 0) and (s[1] = '"') then begin {do not localize}
    Delete(s, 1, 1);
    Result := Fetch(s, '"'); {do not localize}
  // Should never occur, and if so bigger problems but just in case we'll try
  end else begin
    Result := s;
  end;
end;

function TIdMIMEBoundary.GetBoundary: string;
begin
  if FBoundaryList.Count > 0 then begin
    Result := FBoundaryList.Strings[0];
  end else begin
    Result := '';
  end;
end;

procedure TIdMIMEBoundary.Pop;
begin
  FBoundaryList.Delete(0);
end;

procedure TIdMIMEBoundary.Push(ABoundary: string);
begin
  if (FBoundaryList.Count > 0) and (AnsiSameText(ABoundary, FBoundaryList.Strings[0])) then begin
    FNewBoundary := True;
  end else begin
    if Length(ABoundary) > 0 then begin
      FBoundaryList.Insert(0, ABoundary);
      FNewBoundary := False;
    end;
  end;
end;

{ TIdMessagePart }

procedure TIdMessagePart.Assign(Source: TPersistent);
var
  mp: TIdMessagePart;
begin
  if ClassType <> Source.ClassType then begin
    inherited;
  end else begin
    mp := TIdMessagePart(Source);
    ContentTransfer := mp.ContentTransfer;
    ContentType := mp.ContentType;
    ExtraHeaders.Assign(mp.ExtraHeaders);
  end;
end;

constructor TIdMessagePart.Create(Collection: TCollection);
begin
  if ClassType = TIdMessagePart then begin
    raise EIdCanNotCreateMessagePart.Create(RSTIdMessagePartCreate);
  end;
  inherited;
  FIsEncoded := False;
  FHeaders := TIdHeaderList.Create;
  FExtraHeaders := TIdHeaderList.Create;
end;

destructor TIdMessagePart.Destroy;
begin
  FHeaders.Free;
  FExtraHeaders.Free;
  inherited;
end;

function TIdMessagePart.GetContentTransfer: string;
begin
  Result := Headers.Values['Content-Transfer-Encoding']; {do not localize}
end;

function TIdMessagePart.GetContentType: string;
begin
  Result := Headers.Values['Content-Type']; {do not localize}
end;

procedure TIdMessagePart.SetContentTransfer(const Value: string);
begin
  Headers.Values['Content-Transfer-Encoding'] := Value; {do not localize}
end;

procedure TIdMessagePart.SetContentType(const Value: string);
begin
  Headers.Values['Content-Type'] := Value; {do not localize}
end;

procedure TIdMessagePart.SetExtraHeaders(const Value: TIdHeaderList);
begin
  FExtraHeaders.Assign(Value);
end;


{ TIdAttachment }

procedure TIdAttachment.Assign(Source: TPersistent);
var
  mp: TIdAttachment;
begin
  if ClassType <> Source.ClassType then begin
    inherited;
  end else begin
    mp := TIdAttachment(Source);
    ContentTransfer := mp.ContentTransfer;
    ContentType := mp.ContentType;
    ExtraHeaders.Assign(mp.ExtraHeaders);
    ContentDisposition := mp.ContentDisposition;
    FileName := mp.FileName;
  end;
end;

constructor TIdAttachment.Create(Collection: TIdMessageParts; const AFileName: TFileName = '');
begin
  inherited Create(Collection);
  FStoredPathname := AFileName;
  FFilename := ExtractFilename(AFilename);
end;

destructor TIdAttachment.Destroy;
begin
  if FileIsTempFile then begin
    DeleteFile(Filename);
  end;
  inherited;
end;

procedure TIdAttachment.Encode(ADest: TStream);
begin
  with TIdMessageEncoderInfo(TIdMessageParts(Collection).MessageEncoderInfo).MessageEncoderClass
   .Create(nil) do try
    Filename := Self.Filename;
    Encode(Self.StoredPathname, ADest);
  finally Free; end;
end;

function TIdAttachment.GetContentDisposition: string;
begin
  Result := Headers.Values['Content-Disposition']; {do not localize}
end;

function TIdAttachment.SaveToFile(const FileName: TFileName): Boolean;
begin
  Result := CopyFileTo(StoredPathname, FileName);
  if not Result then begin
    raise EIdException.Create(RSTIdMessageErrorSavingAttachment);
  end;
end;

procedure TIdAttachment.SetContentDisposition(const Value: string);
begin
  Headers.Values['Content-Disposition'] := Value; {do not localize}
end;

{ TIdText }

procedure TIdText.Assign(Source: TPersistent);
var mp : TIdText;
begin
  if ClassType <> Source.ClassType then
  begin
    inherited;
  end
  else
  begin
    mp := TIdText(Source);
    ContentTransfer := mp.ContentTransfer;
    ContentType := mp.ContentType;
    ExtraHeaders.Assign(mp.ExtraHeaders);
    Body.Assign(mp.Body);
  end;
end;

constructor TIdText.Create(Collection: TIdMessageParts; ABody: TStrings = nil);
begin
  inherited Create(Collection);
  FBody := TStringList.Create;
  if ABody <> nil then begin
    FBody.Assign(ABody);
  end;
end;

destructor TIdText.Destroy;
begin
  FBody.Free;
  inherited;
end;

procedure TIdText.SetBody(const AStrs: TStrings);
begin
  FBody.Assign(AStrs);
end;

{ TMessageParts }

function TIdMessageParts.Add: TIdMessagePart;
begin
  // This helps prevent TIdMessagePart from being added
  Result := nil;
end;

procedure TIdMessageParts.CountParts;
//TODO: Make AttCount, etc maintained on the fly
var
  i: integer;
begin
  FAttachmentCount := 0;
  FRelatedPartCount := 0;
  FTextPartCount := 0;
  for i := 0 to Count - 1 do begin
    if Items[i] is TIdText then begin
      Inc(FTextPartCount)
    end else if Items[i] is TIdAttachment then begin
      if Length(Items[i].ExtraHeaders.Values['Content-ID']) > 0 then begin
        Inc(FRelatedPartCount);
      end;
      Inc(FAttachmentCount);
    end;
  end;
//  if TextPartCount = 1 then begin
//    raise EIdTextInvalidCount.Create(RSTIdTextInvalidCount);
//  end;
end;

constructor TIdMessageParts.Create(AOwner: TPersistent);
begin
  inherited Create(AOwner, TIdMessagePart);
  // Must set prop and not variable so it will initialize it
  AttachmentEncoding := 'MIME';
end;

function TIdMessageParts.GetItem(Index: Integer): TIdMessagePart;
begin
  Result := TIdMessagePart(inherited GetItem(Index));
end;

procedure TIdMessageParts.SetAttachmentEncoding(const AValue: string);
begin
  FMessageEncoderInfo := TIdMessageEncoderList.ByName(AValue);
  FAttachmentEncoding := AValue;
end;

procedure TIdMessageParts.SetItem(Index: Integer; const Value: TIdMessagePart);
begin
  inherited SetItem(Index, Value);
end;

{ TIdMessage }

procedure TIdMessage.AddHeader(const Value: string);
begin
  FHeaders.Add(Value);
end;

procedure TIdMessage.Clear;
begin
  ClearHeader;
  ClearBody;
end;

procedure TIdMessage.ClearBody;
begin
  MessageParts.Clear ;
  Body.Clear;
end;

procedure TIdMessage.ClearHeader;
begin
  CcList.Clear;
  BccList.Clear;
  Date := 0;
  From.Text := '';
  NewsGroups.Clear;
  Organization := '';
  References := '';
  ReplyTo.Clear;
  Subject := '';
  Recipients.Clear;
  Priority := ID_MSG_PRIORITY;
  ReceiptRecipient.Text := '';
  ContentType := '';
  CharSet := '';
  ContentTransferEncoding := '';
  ContentDisposition := '';
  FSender.Text := '';
  Headers.Clear;
  ExtraHeaders.Clear;
  FMIMEBoundary.Clear;
  UseNowForDate := ID_MSG_USENOWFORDATE;
  Flags := [];
end;

constructor TIdMessage.Create(AOwner: TComponent);
begin
  inherited;
  FBody := TStringList.Create;
  FRecipients := TIdEmailAddressList.Create(Self);
  FBccList := TIdEmailAddressList.Create(Self);
  FCcList := TIdEmailAddressList.Create(Self);
  FMessageParts := TIdMessageParts.Create(Self);
  FNewsGroups := TStringList.Create;
  FHeaders := TIdHeaderList.Create;
  FFrom := TIdEmailAddressItem.Create(nil);
  FReplyTo := TIdEmailAddressList.Create(Self);
  FSender := TIdEmailAddressItem.Create(nil);
  FExtraHeaders := TIdHeaderList.Create;
  FReceiptRecipient := TIdEmailAddressItem.Create(nil);
  NoDecode := ID_MSG_NODECODE;
  FMIMEBoundary := TIdMIMEBoundary.Create;
  Clear;
  FEncoding := meMIME;
end;

destructor TIdMessage.Destroy;
begin
  FBody.Free;
  FRecipients.Free;
  FBccList.Free;
  FCcList.Free;
  FMessageParts.Free;
  FNewsGroups.Free;
  FHeaders.Free;
  FExtraHeaders.Free;
  FFrom.Free;
  FReplyTo.Free;
  FSender.Free;
  FReceiptRecipient.Free;
  FMIMEBoundary.Free;
  inherited destroy;
end;


procedure TIdMessage.SetBody(const Value: TStrings);
begin
  FBody.Assign(Value);
end;

procedure TIdMessage.SetNewsGroups(const Value: TStrings);
begin
  FNewsgroups.Assign(Value);
end;

function TIdMessage.GenerateHeader: TIdHeaderList;
var
  ISOCharset: string;
  HeaderEncoding: Char;
  TransferHeader: TTransfer;
begin
  // TODO: Clean up
  MessageParts.CountParts;
  if Encoding = meMIME then begin
    TIdMessageEncoderInfo(MessageParts.MessageEncoderInfo).InitializeHeaders(Self);
    if Length(CharSet) > 0 then begin
      if Length(ContentType) = 0 then begin
        ContentType := 'charset="' + CharSet + '"';
      end else begin
        ContentType := ContentType + ';' + EOL + TAB + 'charset="' + CharSet + '"';
      end;
    end;
  end else begin
    // Check message parts
    with MessageParts do begin
      if (FRelatedPartCount > 0) or (FTextPartCount > 0) then begin
        raise EIdMessageException.Create(RSMsgClientInvalidEncoding);
      end;
    end;
  end;

  InitializeISO(TransferHeader, HeaderEncoding, ISOCharSet);
  DoInitializeISO(TransferHeader, HeaderEncoding, ISOCharSet);//APR
  Result := TIdHeaderList.Create;

  // added 2001-Oct-29 Don Siders insures use of headers received but not used in properties
  if (FHeaders.Count > 0) then begin
    Result.Assign(FHeaders);
  end;

  try
    with Result do
    begin
      Values['From'] := EncodeAddressItem(From, HeaderEncoding, TransferHeader, ISOCharSet); {do not localize}
      Values['Subject'] := EncodeHeader(Subject, [], HeaderEncoding, TransferHeader, ISOCharSet); {do not localize}
      Values['To'] := EncodeAddress(Recipients, HeaderEncoding, TransferHeader, ISOCharSet); {do not localize}
      Values['Cc'] := EncodeAddress(CCList, HeaderEncoding, TransferHeader, ISOCharSet); {do not localize}
      Values['Newsgroups'] := NewsGroups.CommaText; {do not localize}

      if Encoding = meMIME then
      begin
        Values['Content-Type'] := ContentType; {do not localize}
        if MessageParts.Count > 0 then begin
          Values['MIME-Version'] := '1.0'; {do not localize}
        end;
        Values['Content-Transfer-Encoding'] := ContentTransferEncoding; {do not localize}
      end;
      Values['Sender'] := Sender.Text; {do not localize}
      Values['Reply-To'] := EncodeAddress(ReplyTo, HeaderEncoding, TransferHeader, ISOCharSet); {do not localize}
      Values['Organization'] := EncodeHeader(Organization, [], HeaderEncoding, TransferHeader, ISOCharSet); {do not localize}

      Values['Disposition-Notification-To'] := EncodeAddressItem(ReceiptRecipient, {do not localize}
        HeaderEncoding, TransferHeader, ISOCharSet);

      Values['References'] := References; {do not localize}

      if UseNowForDate then
      begin
        Values['Date'] := DateTimeToInternetStr(Now); {do not localize}
      end
      else begin
        Values['Date'] := DateTimeToInternetStr(Self.Date); {do not localize}
      end;

      // S.G. 27/1/2003: Only fill the priority header if it's different from normal
      if Priority <> mpNormal then
        Values['X-Priority'] := IntToStr(Ord(Priority) + 1); {do not localize}

      // Add extra headers created by UA - allows duplicates
      if (FExtraHeaders.Count > 0) then
      begin
        AddStrings(FExtraHeaders);
      end;
    end;
  except
    FreeAndNil(Result);
    raise;
  end;
end;

procedure TIdMessage.ProcessHeaders;
var
  ABoundary: string;

  // Some mailers send priority as text, number or combination of both
  function GetMsgPriority(Priority:string): TIdMessagePriority;
  var
    s: string;
    Num: integer;
  begin
    // This is for Pegasus.
    if IndyPos('urgent', LowerCase(Priority)) <> 0 then begin {do not localize}
      Result := mpHigh;
    end else if IndyPos('non-priority', LowerCase(Priority)) <> 0 then begin {do not localize}
      Result := mpLow;
    end else begin
      s := Trim(Priority);
      s := Trim(Fetch(s, ' '));
      Num := StrToIntDef(s, 3);
      Result := TIdMessagePriority(Num - 1);
    end;
  end;

  procedure ExtractCharSet;
  var
    s: string;
  begin
    s := UpperCase(ContentType);
    Fetch(s, 'CHARSET='); {do not localize}
    if Copy(s, 1, 1) = '"' then begin {do not localize}
      Delete(s, 1, 1);
      FCharset := Fetch(s, '"'); {do not localize}
    // Sometimes its not in quotes
    end else begin
      FCharset := Fetch(s, ';');
    end;
  end;

begin
  ContentType := Headers.Values['Content-Type']; {do not localize}
  ExtractCharSet;

  ContentTransferEncoding := Headers.Values['Content-Transfer-Encoding']; {do not localize}
  ContentDisposition := Headers.Values['Content-Disposition'];
  Subject := DecodeHeader(Headers.Values['Subject']); {do not localize}
  From.Text := DecodeHeader(Headers.Values['From']); {do not localize}
  MsgId := Headers.Values['Message-Id']; {do not localize}
  CommaSeparatedToStringList(Newsgroups, Headers.Values['Newsgroups']); {do not localize}
  Recipients.EMailAddresses := DecodeHeader(Headers.Values['To']); {do not localize}
  CCList.EMailAddresses := DecodeHeader(Headers.Values['Cc']); {do not localize}
  Organization := Headers.Values['Organization']; {do not localize}
  ReceiptRecipient.Text := Headers.Values['Disposition-Notification-To']; {do not localize}

  if Length(ReceiptRecipient.Text) = 0 then begin
    ReceiptRecipient.Text := Headers.Values['Return-Receipt-To']; {do not localize}
  end;

  References := Headers.Values['References']; {do not localize}
  ReplyTo.EmailAddresses := Headers.Values['Reply-To']; {do not localize}
  Date := GMTToLocalDateTime(Headers.Values['Date']); {do not localize}
  Sender.Text := Headers.Values['Sender']; {do not localize}

  if Length(Headers.Values['Priority']) = 0 then begin {do not localize}
    Priority := GetMsgPriority(Headers.Values['X-Priority']) {do not localize}
  end else begin
    Priority := GetMsgPriority(Headers.Values['Priority']); {do not localize}
  end;
  ABoundary := MIMEBoundary.FindBoundary(ContentType);
  MIMEBoundary.Push(ABoundary);
end;

procedure TIdMessage.SetExtraHeaders(const Value: TIdHeaderList);
begin
  FExtraHeaders.Assign(Value);
end;

function TIdMessage.GetUseNowForDate: Boolean;
begin
  Result := (FDate = 0);
end;

procedure TIdMessage.SetUseNowForDate(const Value: Boolean);
begin
    Date := 0;
end;

procedure TIdMessage.SetAttachmentEncoding(const AValue: string);
begin
  MessageParts.AttachmentEncoding := AValue;
end;

function TIdMessage.GetAttachmentEncoding: string;
begin
  Result := MessageParts.AttachmentEncoding;
end;

procedure TIdMessage.SetEncoding(const AValue: TIdMessageEncoding);
begin
  FEncoding := AValue;
  if AValue = meMIME then begin
    AttachmentEncoding := 'MIME';
  end else begin
    AttachmentEncoding := 'UUE';
  end;
end;

{ procedure TIdMessage.LoadFromFile(const AFileName: string; const AHeaderOnly: Boolean = False);
  var
    LMsgClient : TIdMessageClient;
  begin
    LMsgClient := TIdMessageClient.Create(self);
    try
      LMsgClient.ProcessMessage(Self, AFileName, AHeaderOnly);
    finally
      FreeAndNil(LMsgClient);
    end;
  end;  }

{ procedure TIdMessage.SaveToFile(AFileName: string);
  var
    LMsgClient : TIdMessageClient;
    LS : TFileStream;
    IOHandler : TIdIOHandlerStream;
  begin
    if FileExists(AFileName) then begin
      DeleteFile(AFileName);
    end;

    LS := TFileStream.create(AFileName, fmCreate);

    IOHandler := TIdIOHandlerStream.Create(nil);
    IOHandler.StreamType := stWrite;
    IOHandler.WriteStream := LS;

    try
      LMsgClient := TIdMessageClient.Create(nil);
      LMsgClient.IOHandler := IOHandler;
      LMsgClient.OpenWriteBuffer(32768);
      LMsgClient.SendMsg(Self);
      LMsgClient.WriteLn('.');
      LMsgClient.CloseWriteBuffer;
    finally
      FreeAndNil(LMsgClient);
      IOHandler.WriteStream.Free;
      FreeAndNil(IOHandler);
    end;
  end;  }

procedure TIdMessage.LoadFromFile(const AFileName: string; const AHeadersOnly: Boolean = False);
var
  vStream: TFileStream;
begin
  if (not FileExists(AFilename)) then
  begin
    raise EIdMessageCannotLoad.CreateFmt(RSIdMessageCannotLoad, [AFilename]);
  end;

  vStream := TFileStream.Create(AFilename, fmOpenRead or fmShareDenyWrite);
  try
    LoadFromStream(vStream, AHeadersOnly);
  finally
    vStream.Free;
  end;
end;

procedure TIdMessage.LoadFromStream(AStream: TStream; const AHeadersOnly: Boolean = False);
var
  vMsgClient : TIdMessageClient;
begin
  // clear message properties, headers before loading
  Clear;
  vMsgClient := TIdMessageClient.Create(nil);
  try
    vMsgClient.ProcessMessage(Self, AStream, AHeadersOnly);
  finally
    FreeAndNil(vMsgClient);
  end;
end;

procedure TIdMessage.SaveToFile(const AFileName: string; const AHeadersOnly: Boolean = False);
var
  vStream : TFileStream;
begin
  if FileExists(AFileName) then
  begin
    DeleteFile(AFileName);
  end;

  vStream := TFileStream.create(AFileName, fmCreate);
  try
    SaveToStream(vStream, AHeadersOnly);
  finally
    vStream.Free;
  end;
end;

// TODO: Override TIdMessageClient.SendMsg to provide socket, stream, and file
// versions like TIdMessageClient.ProcessMessage?
procedure TIdMessage.SaveToStream(AStream: TStream;
 const AHeadersOnly: Boolean = False);
var
  LMsgClient: TIdMessageClient;
  LIOHS: TIdIOHandlerStream;
begin
  LMsgClient := TIdMessageClient.Create(nil); 
  try
    LIOHS := TIdIOHandlerStream.Create(nil); 
    try
      LIOHS.OutputStream := AStream;
      LMsgClient.IOHandler := LIOHS;
      LMsgClient.OpenWriteBuffer(32768); 

      {
        ds - the following is required with new Active property in IOHandler.
        
        Without Connect, IOHandler.Open is never called and a false 
        ConnectionClosedGracefully is raised when trying to write to the 
        Output stream.  This uses the same logic as used in 
        TIdMessageClient.ProcessMessage.

        For stream IOHandlers, perhaps Open could be called in Create just like 
        Close is called in the Destroy.
      }
      LMsgClient.Connect;
      try
        LMsgClient.SendMsg(Self, AHeadersOnly);
        // Add the end of message marker when body is included
        if AHeadersOnly = False then 
        begin
          LMsgClient.WriteLn('.');
        end;
      finally 
        LMsgClient.CloseWriteBuffer; 
        {
          ds - the following is required with new Active property in IOHandler.
        }
        LMsgClient.Disconnect;
      end;
    finally 
      FreeAndNil(LIOHS); 
    end;
  finally 
    FreeAndNil(LMsgClient); 
  end;
end;

procedure TIdMessage.DoInitializeISO(var VTransferHeader: TTransfer;
  var VHeaderEncoding: Char; var VCharSet: string);
Begin
  if Assigned(FOnInitializeISO) then FOnInitializeISO(VTransferHeader, VHeaderEncoding, VCharSet);//APR
End;//

initialization
  RegisterClasses([TIdAttachment, TIdText]);
end.

