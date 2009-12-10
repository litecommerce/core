{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10395: IdTrivialFTP.pas 
{
{   Rev 1.0    2002.11.12 10:57:50 PM  czhower
}
unit IdTrivialFTP;

interface

uses
  Classes,
  IdAssignedNumbers,
  IdTrivialFTPBase,
  IdUDPClient;

const
  GTransferMode = tfOctet;
  GFRequestedBlockSize = 1500;
  GReceiveTimeout = 4000;

type
  TIdTrivialFTP = class(TIdUDPClient)
  protected
    FMode: TIdTFTPMode;
    FRequestedBlockSize: Integer;
    FPeerPort: Integer;
    FPeerIP: String;
    function ModeToStr: string;
    procedure CheckOptionAck(const optionpacket: string);
  protected
    procedure SendAck(const BlockNumber: Word);
    procedure RaiseError(const errorpacket: string);
  public
    constructor Create(AnOwner: TComponent); override;
    procedure Get(const ServerFile: String; DestinationStream: TStream); overload;
    procedure Get(const ServerFile, LocalFile: String); overload;
    procedure Put(SourceStream: TStream; const ServerFile: String); overload;
    procedure Put(const LocalFile, ServerFile: String); overload;
  published
    property TransferMode: TIdTFTPMode read FMode write FMode Default GTransferMode;
    property RequestedBlockSize: Integer read FRequestedBlockSize write FRequestedBlockSize default 1500;
    property OnWork;
    property OnWorkBegin;
    property OnWorkEnd;
  end;

implementation

uses
  IdComponent,
  IdException,
  IdGlobal,
  IdResourceStrings,
  IdStack,
  SysUtils;

procedure TIdTrivialFTP.CheckOptionAck(const optionpacket: string);
var
  optionname: string;
begin
  optionname := pchar(optionpacket) + 2;
  if AnsiSameText(optionname, 'blksize') then begin    {Do not Localize}
    BufferSize := StrToInt(pchar(optionpacket) + 10) + hdrsize;
  end;
end;

constructor TIdTrivialFTP.Create(AnOwner: TComponent);
begin
  inherited;
  TransferMode := GTransferMode;
  Port := IdPORT_TFTP;
  FRequestedBlockSize := GFRequestedBlockSize;
  ReceiveTimeout := GReceiveTimeout;
end;

procedure TIdTrivialFTP.Get(const ServerFile: String; DestinationStream: TStream);
var
  s: string;
  RcvTimeout,
  DataLen: Integer;
  PrevBlockCtr,
  BlockCtr: Integer;
  TerminateTransfer: Boolean;
begin
  BeginWork(wmRead);
  try
    BufferSize := 516;   // BufferSize as specified by RFC 1350
    Send(WordToStr(GStack.WSHToNs(TFTP_RRQ)) + ServerFile + #0 + ModeToStr + #0 +
      sBlockSize + IntToStr(FRequestedBlockSize) + #0);
    PrevBlockCtr := -1;
    BlockCtr := 0;
    TerminateTransfer := False;
    RcvTimeout := ReceiveTimeout;
    while true do
    begin
      if TerminateTransfer then begin
        RcvTimeout := Min(500, ReceiveTimeout);
      end;
      s := ReceiveString(FPeerIP, FPeerPort, RcvTimeout);
      if (s = '') then begin    {Do not Localize}
        if TerminateTransfer then begin
          break;
        end
        else begin
          raise EIdTFTPException.Create(RSTimeOut);
        end;
      end;
      case GStack.WSNToHs(StrToWord(s)) of
        TFTP_DATA:
          begin
            BlockCtr := GStack.WSNToHs(StrToWord(Copy(s, 3, 2)));
            if TerminateTransfer then  // hang around just once more
            begin
              SendAck(BlockCtr);
              Break;
            end;
            if (BlockCtr <= 1) and (PrevBlockCtr = MaxWord) then begin
              PrevBlockCtr := -1; // counter wrapped around (1-65535 blocks)
            end;
            if BlockCtr > PrevBlockCtr then
            begin
              DataLen := Length(s) - 4;
              try
                DestinationStream.WriteBuffer(s[5], DataLen);
                DoWork(wmRead, DataLen)
              except
                on E:Exception do
                begin
                  SendError(self, FPeerIP, FPeerPort, E);
                  raise;
                end;
              end;
              TerminateTransfer := DataLen < BufferSize - hdrsize;
              PrevBlockCtr := BlockCtr;
            end;  { if }
          end;
        TFTP_ERROR: RaiseError(s);
        TFTP_OACK:
        begin
          CheckOptionAck(s);
          BlockCtr := 0;
        end;
        else
          raise EIdTFTPException.CreateFmt(RSTFTPUnexpectedOp, [Host, Port]);
      end;  { case }
      SendAck(BlockCtr);
    end;  { while }
  finally
    EndWork(wmRead);
    Binding.CloseSocket;
  end;
end;

procedure TIdTrivialFTP.Get(const ServerFile, LocalFile: String);
var
  fs: TFileStream;
begin
  fs := TFileStream.Create(LocalFile, fmCreate);
  try
    Get(ServerFile, fs);
  finally
    fs.Free;
  end;
end;

function TIdTrivialFTP.ModeToStr: string;
begin
  case TransferMode of
    tfNetAscii: result := 'netascii';    {Do not Localize}
    tfOctet:    result := 'octet';    {Do not Localize}
  end;
end;

procedure TIdTrivialFTP.Put(SourceStream: TStream; const ServerFile: String);
var
  CurrentDataBlk,
  s: string;
  DataLen: Integer;
  PrevBlockCtr,
  BlockCtr: Integer;
  TerminateTransfer: Boolean;
begin
  BeginWork(wmWrite, SourceStream.Size - SourceStream.Position);
  try
    BufferSize := 516;   // BufferSize as specified by RFC 1350
    Send(WordToStr(GStack.WSHToNs(TFTP_WRQ)) + ServerFile + #0 + ModeToStr + #0 +
      sBlockSize + IntToStr(FRequestedBlockSize) + #0);
    PrevBlockCtr := 0;
    BlockCtr := 1;
    TerminateTransfer := False;
    while true do
    begin
      s := ReceiveString(FPeerIP, FPeerPort);
      if (s = '') then begin    {Do not Localize}
        if TerminateTransfer then begin
          Break;
        end
        else begin
          raise EIdTFTPException.Create(RSTimeOut);
        end;
      end;
      case GStack.WSNToHs(StrToWord(s)) of
        TFTP_ACK:
          begin
            BlockCtr := GStack.WSNToHs(StrToWord(Copy(s, 3, 2)));
            inc(BlockCtr);
            if Word(BlockCtr) = 0 then begin
              BlockCtr := 0;
              PrevBlockCtr := -1; // counter wrapped around (1-65535 blocks)
            end;
            if TerminateTransfer then begin
              Break;
            end;
          end;
        TFTP_ERROR: RaiseError(s);
        TFTP_OACK: CheckOptionAck(s);
      end;  { case }
      if BlockCtr > PrevBlockCtr then
      begin
        DataLen := Min(BufferSize - hdrsize, SourceStream.Size - SourceStream.Position);
        SetLength(CurrentDataBlk, DataLen + hdrsize);
        CurrentDataBlk := WordToStr(GStack.WSHToNs(TFTP_DATA)) + WordToStr(GStack.WSHToNs(BlockCtr));
        SetLength(CurrentDataBlk, DataLen + hdrsize);
        SourceStream.ReadBuffer(CurrentDataBlk[hdrsize+1], DataLen);
        DoWork(wmWrite, DataLen);
        TerminateTransfer := DataLen < BufferSize - hdrsize;
        PrevBlockCtr := BlockCtr;
      end;
      Send(FPeerIP, FPeerPort, CurrentDataBlk);
    end;  { while }
  finally
    EndWork(wmWrite);
    Binding.CloseSocket;
  end;
end;

procedure TIdTrivialFTP.Put(const LocalFile, ServerFile: String);
var
  fs: TFileStream;
begin
  fs := TFileStream.Create(LocalFile, fmOpenRead);
  try
    Put(fs, ServerFile);
  finally
    fs.Free;
  end;
end;

procedure TIdTrivialFTP.RaiseError(const errorpacket: string);
var
  errmsg: string;
begin
  errmsg := pchar(errorpacket)+4;
  case GStack.WSNToHs(StrToWord(Copy(errorpacket, 3, 2))) of
    ErrFileNotFound:            raise EIdTFTPFileNotFound.Create(errmsg);
    ErrAccessViolation:         raise EIdTFTPAccessViolation.Create(errmsg);
    ErrAllocationExceeded:      raise EIdTFTPAllocationExceeded.Create(errmsg);
    ErrIllegalOperation:        raise EIdTFTPIllegalOperation.Create(errmsg);
    ErrUnknownTransferID:       raise EIdTFTPUnknownTransferID.Create(errmsg);
    ErrFileAlreadyExists:       raise EIdTFTPFileAlreadyExists.Create(errmsg);
    ErrNoSuchUser:              raise EIdTFTPNoSuchUser.Create(errmsg);
    ErrOptionNegotiationFailed: raise EIdTFTPOptionNegotiationFailed.Create(errmsg);
    else  // usually ErrUndefined (see EIdTFTPException.Message if any)
      raise EIdTFTPException.Create(errmsg);
  end;
end;

procedure TIdTrivialFTP.SendAck(const BlockNumber: Word);
begin
  Send(FPeerIP, FPeerPort, MakeAckPkt(BlockNumber));
end;


end.
