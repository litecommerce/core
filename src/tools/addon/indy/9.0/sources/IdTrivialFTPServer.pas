{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10399: IdTrivialFTPServer.pas 
{
{   Rev 1.0    2002.11.12 10:58:14 PM  czhower
}
unit IdTrivialFTPServer;

interface

uses
  Classes,
  IdAssignedNumbers,
  IdTrivialFTPBase,
  IdSocketHandle,
  IdUDPServer;

type
  TPeerInfo = record
    PeerIP: string;
    PeerPort: Integer;
  end;

  TAccessFileEvent = procedure (Sender: TObject; var FileName: String; const PeerInfo: TPeerInfo;
    var GrantAccess: Boolean; var AStream: TStream; var FreeStreamOnComplete: Boolean) of object;
  TTransferCompleteEvent = procedure (Sender: TObject; const Success: Boolean;
    const PeerInfo: TPeerInfo; AStream: TStream; const WriteOperation: Boolean) of object;

  TIdTrivialFTPServer = class(TIdUDPServer)
  protected
    FOnTransferComplete: TTransferCompleteEvent;
    FOnReadFile,
    FOnWriteFile: TAccessFileEvent;
    function StrToMode(mode: string): TIdTFTPMode;
  protected
    procedure DoReadFile(FileName: String; const Mode: TIdTFTPMode;
      const PeerInfo: TPeerInfo; RequestedBlockSize: Integer = 0); virtual;
    procedure DoWriteFile(FileName: String; const Mode: TIdTFTPMode;
      const PeerInfo: TPeerInfo; RequestedBlockSize: Integer = 0); virtual;
    procedure DoTransferComplete(const Success: Boolean; const PeerInfo: TPeerInfo; SourceStream: TStream; const WriteOperation: Boolean); virtual;
    procedure DoUDPRead(AData: TStream; ABinding: TIdSocketHandle); override;
  public
    constructor Create(axOwner: TComponent); override;
  published
    property OnReadFile: TAccessFileEvent read FOnReadFile write FOnReadFile;
    property OnWriteFile: TAccessFileEvent read FOnWriteFile write FOnWriteFile;
    property OnTransferComplete: TTransferCompleteEvent read FOnTransferComplete write FOnTransferComplete;
  end;

implementation

uses
  IdException,
  IdGlobal,
  IdResourceStrings,
  IdStack,
  IdUDPClient,
  SysUtils;

type
  TIdTFTPServerThread = class(TThread)
  private
    FStream: TStream;
    FUDPClient: TIdUDPClient;
    FRequestedBlkSize: Integer;
    EOT,
    FFreeStrm: Boolean;
    FOwner: TIdTrivialFTPServer;
    procedure TransferComplete;
  protected
    procedure Execute; override;
  public
    constructor Create(AnOwner: TIdTrivialFTPServer; const Mode: TIdTFTPMode; const PeerInfo: TPeerInfo;
      AStream: TStream; const FreeStreamOnTerminate: boolean; const RequestedBlockSize: Integer = 0); virtual;
    destructor Destroy; override;
  end;

  TIdTFTPServerSendFileThread = class(TIdTFTPServerThread);
  TIdTFTPServerReceiveFileThread = class(TIdTFTPServerThread);

{ TIdTrivialFTPServer }

constructor TIdTrivialFTPServer.Create(axOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_TFTP;
end;

procedure TIdTrivialFTPServer.DoReadFile(FileName: String; const Mode: TIdTFTPMode;
  const PeerInfo: TPeerInfo; RequestedBlockSize: Integer = 0);
var
  CanRead,
  FreeOnComplete: Boolean;
  SourceStream: TStream;
begin
  CanRead := True;
  SourceStream := nil;
  FreeOnComplete := True;
  try
    if Assigned(FOnReadFile) then
      FOnReadFile(Self, FileName, PeerInfo, CanRead, SourceStream, FreeOnComplete);
    if CanRead then
    begin
      if SourceStream = nil then
      begin
        SourceStream := TFileStream.Create(FileName, fmOpenRead or fmShareDenyWrite);
        FreeOnComplete := True;
      end;
      TIdTFTPServerSendFileThread.Create(self, Mode, PeerInfo, SourceStream,
        FreeOnComplete, RequestedBlockSize);
    end
    else
      raise EIdTFTPAccessViolation.CreateFmt(RSTFTPAccessDenied, [FileName]);
  except
    on E: EFOpenError do
      raise EIdTFTPFileNotFound.Create(E.Message);
  end;
end;

procedure TIdTrivialFTPServer.DoTransferComplete(const Success: Boolean;
  const PeerInfo: TPeerInfo; SourceStream: TStream; const WriteOperation: Boolean);
begin
  if Assigned(FOnTransferComplete) then
    FOnTransferComplete(Self, Success, PeerInfo, SourceStream, WriteOperation)
  else
    SourceStream.Free; // free the stream regardless, unless the component user steps up to the plate
end;

procedure TIdTrivialFTPServer.DoUDPRead(AData: TStream; ABinding: TIdSocketHandle);
var
  wOp: Word;
  s,
  FileName: String;
  RequestedBlkSize: integer;
  Mode: TIdTFTPMode;
  PeerInfo: TPeerInfo;
begin
  inherited;
  try
    AData.ReadBuffer(wOp, SizeOf(wOp));
    wOp := GStack.WSNToHs(wOp);
    if wOp IN [TFTP_RRQ, TFTP_WRQ] then
    begin
      SetLength(s, AData.Size - AData.Position);
      AData.ReadBuffer(s[1], Length(s));
      FileName := Fetch(s, #0);
      Mode := StrToMode(Fetch(s, #0));
      RequestedBlkSize := 0;
      if StrLComp(pchar(s), sBlockSize, Length(sBlockSize)) = 0 then
      begin
        Fetch(s, #0);
        RequestedBlkSize := StrToInt(Fetch(s, #0));
      end;
      PeerInfo.PeerIP := ABinding.PeerIP;
      PeerInfo.PeerPort := ABinding.PeerPort;
      if wOp = TFTP_RRQ then
        DoReadFile(FileName, Mode, PeerInfo, RequestedBlkSize)
      else
        DoWriteFile(FileName, Mode, PeerInfo, RequestedBlkSize);
    end
    else
      raise EIdTFTPIllegalOperation.CreateFmt(RSTFTPUnexpectedOp, [ABinding.PeerIP, ABinding.PeerPort]);
  except
    on E: EIdTFTPException do
      SendError(self, ABinding.PeerIP, ABinding.PeerPort, E);
    on E: Exception do
    begin
      SendError(self, ABinding.PeerIP, ABinding.PeerPort, E);
      raise;
    end;
  end;  { try..except }
end;

procedure TIdTrivialFTPServer.DoWriteFile(FileName: String;
  const Mode: TIdTFTPMode; const PeerInfo: TPeerInfo;
  RequestedBlockSize: Integer);
var
  CanWrite,
  FreeOnComplete: Boolean;
  DestinationStream: TStream;
begin
  CanWrite := True;
  DestinationStream := nil;
  FreeOnComplete := True;
  try
    if Assigned(FOnWriteFile) then
      FOnWriteFile(Self, FileName, PeerInfo, CanWrite, DestinationStream, FreeOnComplete);
    if CanWrite then
    begin
      if DestinationStream = nil then
      begin
        DestinationStream := TFileStream.Create(FileName, fmCreate or fmShareDenyWrite);
        FreeOnComplete := True;
      end;
      TIdTFTPServerReceiveFileThread.Create(self, Mode, PeerInfo,
        DestinationStream, FreeOnComplete, RequestedBlockSize);
    end
    else
      raise EIdTFTPAccessViolation.CreateFmt(RSTFTPAccessDenied, [FileName]);
  except
    on E: EFCreateError do
      raise EIdTFTPAllocationExceeded.Create(E.Message);
  end;
end;

function TIdTrivialFTPServer.StrToMode(mode: string): TIdTFTPMode;
begin
  case PosInStrArray(mode, ['octet', 'binary', 'netascii'], False) of    {Do not Localize}
    0, 1: Result := tfOctet;
    2: Result := tfNetAscii;
    else
      raise EIdTFTPIllegalOperation.CreateFmt(RSTFTPUnsupportedTrxMode, [mode]); // unknown mode
  end;
end;

{ TIdTFTPServerThread }

constructor TIdTFTPServerThread.Create(AnOwner: TIdTrivialFTPServer;
  const Mode: TIdTFTPMode; const PeerInfo: TPeerInfo;
  AStream: TStream; const FreeStreamOnTerminate: boolean; const RequestedBlockSize: Integer);
begin
  inherited Create(True);
  FStream := AStream;
  FUDPClient := TIdUDPClient.Create(nil);
  with FUDPClient do
  begin
    ReceiveTimeout := 1500;
    Host := PeerInfo.PeerIP;
    Port := PeerInfo.PeerPort;
    BufferSize := 516;
    FRequestedBlkSize := RequestedBlockSize;
  end;
  FFreeStrm := FreeStreamOnTerminate;
  FOwner := AnOwner;
  FreeOnTerminate := True;
  Resume;
end;

destructor TIdTFTPServerThread.Destroy;
begin
  if FFreeStrm then
    FreeAndNil(FStream);
  Synchronize(TransferComplete);
  FUDPClient.Free;
  inherited;
end;

procedure TIdTFTPServerThread.Execute;
var
  Response,
  Buffer: string;
  BlkCounter: integer;
  i,
  RetryCtr: integer;
begin
  Response := '';    {Do not Localize}
  BlkCounter := 0;
  RetryCtr := 0;
  EOT := False;
  SetLength(Response, Max(FRequestedBlkSize + hdrsize, FUDPClient.BufferSize));
  if FRequestedBlkSize > 0 then
  begin
    FUDPClient.BufferSize := Max(FRequestedBlkSize + hdrsize, FUDPClient.BufferSize);
    Response := WordToStr(GStack.WSHToNs(TFTP_OACK)) + 'blksize'#0    {Do not Localize}
     + IntToStr(FUDPClient.BufferSize - hdrsize) + #0#0;
  end
  else
    Response := '';    {Do not Localize}
  SetLength(Buffer, FUDPClient.BufferSize);
  try
    while true do
    begin
      if Response = '' then  // generate a new response packet for client    {Do not Localize}
      begin
        if (self is TIdTFTPServerReceiveFileThread) then
          Response := MakeAckPkt(BlkCounter)
        else
        begin
          BlkCounter := Word(succ(BlkCounter));
          Response := WordToStr(GStack.WSHToNs(TFTP_DATA)) +
                      WordToStr(GStack.WSHToNs(Word(BlkCounter)));
          SetLength(Response, FUDPClient.BufferSize);
          i := FStream.Read(Response[hdrsize+1], Length(Response) - hdrsize);
          SetLength(Response, i + hdrsize);
          EOT := i < FUDPClient.BufferSize - hdrsize;
        end;
        RetryCtr := 0;
      end;
      if RetryCtr = 3 then
        raise EIdTFTPIllegalOperation.Create(RSTimeOut); // Timeout
      FUDPClient.Send(Response);
      Buffer := FUDPClient.ReceiveString;
      if Buffer = '' then    {Do not Localize}
      begin
        if EOT then
          break;
        inc(RetryCtr);
        Continue;
      end;
      case GStack.WSNToHs(StrToWord(Buffer)) of
        TFTP_ACK:
        begin
          if not (self is TIdTFTPServerSendFileThread) then
          begin
            raise EIdTFTPIllegalOperation.CreateFmt(RSTFTPUnexpectedOp,
              [FUDPClient.Host, FUDPClient.Port]);
          end;
          i := GStack.WSNToHs(StrToWord(Copy(Buffer, 3, 2)));
          if i = BlkCounter then
            Response := '';    {Do not Localize}
          if EOT then break;
        end;
        TFTP_DATA:
        begin
          if not (self is TIdTFTPServerReceiveFileThread) then
          begin
            raise EIdTFTPIllegalOperation.CreateFmt(RSTFTPUnexpectedOp,
              [FUDPClient.Host, FUDPClient.Port]);
          end;
          i := GStack.WSNToHs(StrToWord(Copy(Buffer, 3, 2)));
          if i = Word(BlkCounter + 1) then
          begin
            FStream.WriteBuffer(Buffer[hdrsize+1], Length(Buffer)-hdrsize);
            Response := '';    {Do not Localize}
            BlkCounter := Word(succ(BlkCounter));
          end;
          EOT := Length(Buffer) < FUDPClient.BufferSize;
        end;
        TFTP_ERROR: Abort;
        else
          raise EIdTFTPIllegalOperation.CreateFmt(RSTFTPUnexpectedOp,
              [FUDPClient.Host, FUDPClient.Port]);
      end;  { case }
    end;
  except
    on E: EIdTFTPException do
      SendError(FUDPClient, E);
    on E: EWriteError do
      SendError(FUDPClient, ErrAllocationExceeded, Format(RSTFTPDiskFull, [FStream.Position]));
    on E: Exception do
      SendError(FUDPClient, E);
  end;
end;

procedure TIdTFTPServerThread.TransferComplete;
var
  PeerInfo: TPeerInfo;
begin
  PeerInfo.PeerIP := FUDPClient.Host;
  PeerInfo.PeerPort := FUDPClient.Port;
  FOwner.DoTransferComplete(EOT, PeerInfo, FStream, self is TIdTFTPServerReceiveFileThread);
end;

end.
