{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10083: IdBlockCipherIntercept.pas 
{
{   Rev 1.0    2002.11.12 10:31:44 PM  czhower
}
unit IdBlockCipherIntercept;

{-----------------------------------------------------------------------------
 UnitName: IdBlockCipherIntercept
 Author:   Andrew P.Rybin [magicode@mail.ru]
 Creation: 27.02.2002
 Version:  0.9.0b
 Purpose:  Secure communications
 History:
-----------------------------------------------------------------------------}

{$I IdCompilerDefines.inc}

interface

uses
  Classes,
  IdIntercept, IdException;

const
  IdBlockCipherBlockSizeDefault = 16;
  IdBlockCipherBlockSizeMax     = 256;

type
  TIdBlockCipherIntercept = class;

  //OneBlock event
  TIdBlockCipherInterceptDataEvent = procedure (ASender: TIdBlockCipherIntercept; ASrcData, ADstData: Pointer) of object;

  TIdBlockCipherIntercept = class(TIdConnectionIntercept)
  protected
    FBlockSize: Integer;
    FData: TObject; //commonly password
    FRecvStream: TMemoryStream;
    FSendStream: TMemoryStream;
    //
    procedure Decrypt (const ASrcData; var ADstData); virtual;
    procedure Encrypt (const ASrcData; var ADstData); virtual;
    function  GetOnReceive: TIdBlockCipherInterceptDataEvent;
    function  GetOnSend: TIdBlockCipherInterceptDataEvent;
    procedure SetOnReceive(const Value: TIdBlockCipherInterceptDataEvent);
    procedure SetOnSend(const Value: TIdBlockCipherInterceptDataEvent);
    procedure SetBlockSize(const Value: Integer);
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Receive(ABuffer: TStream); override; //Decrypt
    procedure Send(ABuffer: TStream); override; //Encrypt
    procedure CopySettingsFrom (ASrcBlockCipherIntercept: TIdBlockCipherIntercept);
    //
    property  Data: TObject read FData write FData;
  published
    property  BlockSize: Integer read FBlockSize write SetBlockSize default IdBlockCipherBlockSizeDefault;
    // events
    property  OnReceive: TIdBlockCipherInterceptDataEvent read GetOnReceive write SetOnReceive;
    property  OnSend: TIdBlockCipherInterceptDataEvent read GetOnSend write SetOnSend;
  End;//TIdBlockCipherIntercept

  EIdBlockCipherInterceptException = EIdException; {block length}

IMPLEMENTATION

Uses
  IdGlobal,
  IdResourceStrings,
  SysUtils;

{ TIdBlockCipherIntercept }

const
  bitLongTail = $80; //future: for IdBlockCipherBlockSizeMax>256

constructor TIdBlockCipherIntercept.Create(AOwner: TComponent);
Begin
  inherited Create(AOwner);
  FBlockSize := IdBlockCipherBlockSizeDefault;
  FRecvStream:= TMemoryStream.Create;
  FSendStream:= TMemoryStream.Create;
End;//Create

destructor TIdBlockCipherIntercept.Destroy;
Begin
  FreeAndNIL(FSendStream);
  FreeAndNIL(FRecvStream);
  inherited Destroy;
End;//Destroy

procedure TIdBlockCipherIntercept.Encrypt(const ASrcData; var ADstData);
Begin
  if Assigned(FOnSend) then begin
    TIdBlockCipherInterceptDataEvent(FOnSend)(SELF, @ASrcData, @ADstData);
  end;//ex: EncryptAES(LTempIn, ExpandedKey, LTempOut);
End;//Encrypt

procedure TIdBlockCipherIntercept.Decrypt(const ASrcData; var ADstData);
Begin
  if Assigned(FOnReceive) then begin
    TIdBlockCipherInterceptDataEvent(FOnReceive)(SELF, @ASrcData, @ADstData);
  end;//ex: DecryptAES(LTempIn, ExpandedKey, LTempOut);
End;//Decrypt

procedure TIdBlockCipherIntercept.Send(ABuffer: TStream);
var
  LTempIn, LTempOut: array [0..IdBlockCipherBlockSizeMax] of Byte;
  LCount: Integer;
  LBS: Integer; //block size-1
Begin
  FSendStream.LoadFromStream(ABuffer);
  LCount := FSendStream.Seek(0,soFromEnd);//size
  ABuffer.Seek(0,0); //bof
  FSendStream.Seek(0,0);
  if LCount <= 0 then begin
    EXIT;
  end;

  LBS := FBlockSize-1;
  while LCount >= LBS do begin
    FSendStream.Read(LTempIn, LBS); //?ReadBuffer
    LTempIn[LBS]:= LBS;

    Encrypt(LTempIn,LTempOut);
    ABuffer.Write(LTempOut, FBlockSize);//? WriteBuffer

    Dec(LCount, LBS);
  end;//while

  if LCount > 0 then begin
    FSendStream.Read(LTempIn, LCount);//? ReadBuffer
    FillChar(LTempIn[LCount], FBlockSize - LCount, 0); //SizeOf(LTempIn)-Cnt
    LTempIn[LBS]:= LCount;

    Encrypt(LTempIn, LTempOut);
    ABuffer.Write(LTempOut, FBlockSize); //?WriteBuffer
  end;//if
End;//Send

procedure TIdBlockCipherIntercept.Receive(ABuffer: TStream);
var
  LTempIn, LTempOut: array [0..IdBlockCipherBlockSizeMax] of Byte;
  LCount: Integer;
  LBS: Integer;
  LRcvBlkSize: Integer; //received block data length
Begin
  FRecvStream.CopyFrom(ABuffer,0);//append
  LCount := FRecvStream.Seek(0,soFromEnd);//size
  ABuffer.Seek(0,0); //bof
  FRecvStream.Seek(0,0);
  if LCount <= 0 then begin
    exit;
  end;

  LBS := FBlockSize-1;
  while LCount >= FBlockSize do begin
    FRecvStream.Read(LTempIn, FBlockSize); //?ReadBuffer
    Decrypt(LTempIn, LTempOut);

    LRcvBlkSize := LTempOut[LBS]; //real data_in_block length
    if LRcvBlkSize > 0 then begin
      if LRcvBlkSize < FBlockSize then begin
        ABuffer.Write(LTempOut, LRcvBlkSize);
      end else begin
        raise EIdBlockCipherInterceptException.Create(RSBlockIncorrectLength);
      end;
    end;//if block with data
    Dec(LCount, FBlockSize);
  end;//while

  // cache for round block
  if LCount >0 then begin
    FRecvStream.Read(LTempIn, LCount);
    FRecvStream.Seek(0,0);//bof
    FRecvStream.Write(LTempIn, LCount);
    FRecvStream.SetSize(LCount);
  end else begin
    FRecvStream.Clear;
  end;

  ABuffer.Size := ABuffer.Position;//truncate
End;//Receive


function TIdBlockCipherIntercept.GetOnReceive: TIdBlockCipherInterceptDataEvent;
Begin
  Result := TIdBlockCipherInterceptDataEvent(FOnReceive);
End;

function TIdBlockCipherIntercept.GetOnSend: TIdBlockCipherInterceptDataEvent;
Begin
  Result := TIdBlockCipherInterceptDataEvent(FOnSend);
End;

procedure TIdBlockCipherIntercept.SetOnReceive(const Value: TIdBlockCipherInterceptDataEvent);
Begin
  TIdBlockCipherInterceptDataEvent(FOnReceive):= Value;
End;

procedure TIdBlockCipherIntercept.SetOnSend(const Value: TIdBlockCipherInterceptDataEvent);
Begin
  TIdBlockCipherInterceptDataEvent(FOnSend):= Value;
End;

procedure TIdBlockCipherIntercept.CopySettingsFrom(
  ASrcBlockCipherIntercept: TIdBlockCipherIntercept);
Begin
  with ASrcBlockCipherIntercept do begin
    SELF.FBlockSize := FBlockSize;
    SELF.FData:= FData;
    SELF.FOnConnect := FOnConnect;
    SELF.FOnDisconnect:= FOnDisconnect;
    SELF.FOnReceive := FOnReceive;
    SELF.FOnSend := FOnSend; 
  end;
End;//

procedure TIdBlockCipherIntercept.SetBlockSize(const Value: Integer);
Begin
  if (Value>0) and (Value<=IdBlockCipherBlockSizeMax) then begin
    FBlockSize := Value;
  end;
End;//

END.
