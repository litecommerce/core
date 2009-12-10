{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10397: IdTrivialFTPBase.pas 
{
{   Rev 1.0    2002.11.12 10:57:58 PM  czhower
}
unit IdTrivialFTPBase;

interface

uses
  IdGlobal,
  IdUDPBase, IdUDPClient,
  SysUtils;

type
  TIdTFTPMode = (tfNetAscii, tfOctet);

type
  WordStr = string[2];

// Procs
  function MakeAckPkt(const BlockNumber: Word): string;
  procedure SendError(UDPBase: TIdUDPBase; APeerIP: string; const APort: Integer; const ErrNumber: Word; ErrorString: string); overload;
  procedure SendError(UDPClient: TIdUDPClient; const ErrNumber: Word; ErrorString: string); overload;
  procedure SendError(UDPBase: TIdUDPBase; APeerIP: string; const APort: Integer;  E: Exception); overload;
  procedure SendError(UDPClient: TIdUDPClient; E: Exception); overload;
  function StrToWord(const Value: String): Word;
  function WordToStr(const Value: Word): WordStr;

const  // TFTP opcodes
  TFTP_RRQ   = 1;
  TFTP_WRQ   = 2;
  TFTP_DATA  = 3;
  TFTP_ACK   = 4;
  TFTP_ERROR = 5;
  TFTP_OACK  = 6;  // see RFC 1782 and 1783

const  // various
  MaxWord = High(Word);
  hdrsize = 4;           // TFTP Headersize on DATA packets (opcode + block#)
  sBlockSize = 'blksize'#0;    {Do not Localize}
  // TFTP RFC 1782/1783 allows an optional blocksize to be specified
  // A blocksize of 8192 bytes generates far less ACK packets than 512 bytes blocks

const  // tftp error codes
  ErrUndefined               = 0;
  ErrFileNotFound            = 1;
  ErrAccessViolation         = 2;
  ErrAllocationExceeded      = 3;
  ErrIllegalOperation        = 4;
  ErrUnknownTransferID       = 5;
  ErrFileAlreadyExists       = 6;
  ErrNoSuchUser              = 7;
  ErrOptionNegotiationFailed = 8;

implementation

uses
  IdException,
  IdStack;

function StrToWord(const Value: String): Word;
begin
  Result := Word(pointer(@Value[1])^);
end;

function WordToStr(const Value: Word): WordStr;
begin
  SetLength(Result, SizeOf(Value));
  Move(Value, Result[1], SizeOf(Value));
end;

function MakeAckPkt(const BlockNumber: Word): string;
begin
  Result := WordToStr(GStack.WSHToNs(TFTP_ACK)) + WordToStr(GStack.WSHToNs(BlockNumber));
end;

procedure SendError(UDPBase: TIdUDPBase; APeerIP: string; const APort: Integer; const ErrNumber: Word; ErrorString: string);
begin
  UDPBase.Send(APeerIP, APort, WordToStr(GStack.WSHToNs(TFTP_ERROR)) + WordToStr(ErrNumber) + ErrorString + #0);
end;

procedure SendError(UDPClient: TIdUDPClient; const ErrNumber: Word; ErrorString: string);
begin
  SendError(UDPClient, UDPClient.Host, UDPClient.Port, ErrNumber, ErrorString);
end;

procedure SendError(UDPBase: TIdUDPBase; APeerIP: string; const APort: Integer;  E: Exception);
var
  ErrNumber: Word;
begin
  ErrNumber := ErrUndefined;
  if E is EIdTFTPFileNotFound then ErrNumber := ErrFileNotFound;
  if E is EIdTFTPAccessViolation then ErrNumber := ErrAccessViolation;
  if E is EIdTFTPAllocationExceeded then ErrNumber := ErrAllocationExceeded;
  if E is EIdTFTPIllegalOperation then ErrNumber := ErrIllegalOperation;
  if E is EIdTFTPUnknownTransferID then ErrNumber := ErrUnknownTransferID;
  if E is EIdTFTPFileAlreadyExists then ErrNumber := ErrFileAlreadyExists;
  if E is EIdTFTPNoSuchUser then ErrNumber := ErrNoSuchUser;
  if E is EIdTFTPOptionNegotiationFailed then ErrNumber := ErrOptionNegotiationFailed;
  SendError(UDPBase, APeerIP, APort, ErrNumber, E.Message);
end;

procedure SendError(UDPClient: TIdUDPClient; E: Exception);
begin
  SendError(UDPClient, UDPClient.Host, UDPClient.Port, E);
end;


end.
