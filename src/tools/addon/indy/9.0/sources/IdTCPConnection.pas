{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10365: IdTCPConnection.pas 
{
    Rev 1.1    4/17/2003 4:58:38 PM  BGooijen
  cleaned up CheckForDisconnect a little
}
{
{   Rev 1.0    2002.11.12 10:55:02 PM  czhower
}
unit IdTCPConnection;

interface

{
2002-04-12 - Andrew P.Rybin
  - ReadLn bugfix and optimization
2002-01-20 - Chad Z. Hower a.k.a Kudzu
  -WriteBuffer change was not correct. Removed. Need info on original problem to fix properly.
  -Modified ReadLnWait
2002-01-19 - Grahame Grieve
  - Fix to WriteBuffer to accept -1 from the stack.
  Also fixed to clean up FWriteBuffer if connection lost.
2002-01-19 - Chad Z. Hower a.k.a Kudzu
  -Fix to ReadLn
2002-01-16 - Andrew P.Rybin
  -ReadStream optimization, TIdManagedBuffer new
2002-01-03 - Chad Z. Hower a.k.a Kudzu
  -Added MaxLineAction
  -Added ReadLnSplit
2001-12-27 - Chad Z. Hower a.k.a Kudzu
  -Changes and bug fixes to InputLn
  -Modifed how buffering works
    -Added property InputBuffer
    -Moved some things to TIdBuffer
  -Modified ReadLn
  -Added LineCount to Capture
2001-12-25 - Andrew P.Rybin
  -MaxLineLength,ReadLn,InputLn and Merry Christmas!
Original Author and Maintainer:
  -Chad Z. Hower a.k.a Kudzu
}

uses
  Classes,
  IdException, IdComponent, IdGlobal, IdSocketHandle, IdIntercept, IdIOHandler, IdRFCReply,
  IdIOHandlerSocket;

const
  GRecvBufferSizeDefault = 32 * 1024;
  GSendBufferSizeDefault = 32 * 1024;
  IdMaxLineLengthDefault = 16 * 1024;
  IdInBufCacheSizeDefault= 32 * 1024; //TIdManagedBuffer.PackReadedSize
  IdDefTimeout = 0;

type
  TIdBufferBytesRemoved = procedure(ASender: TObject; const ABytes: Integer) of object;
  //DONE 5 -cBeta!!! -oAPR: Make this a buffered stream for more efficiency.
  TIdSimpleBuffer = class(TMemoryStream)
  protected
    FOnBytesRemoved: TIdBufferBytesRemoved;
  public
    constructor Create(AOnBytesRemoved: TIdBufferBytesRemoved = nil); reintroduce;
    function  Extract(const AByteCount: Integer): string; virtual;
    procedure Remove (const AByteCount: integer); virtual;
  End;//TIdSimpleBuffer

  TIdManagedBuffer = class(TIdSimpleBuffer)
  protected
    FPackReadedSize: Integer;
    FReadedSize: Integer;
    procedure SetPackReadedSize(const Value: Integer);
  public
    constructor Create(AOnBytesRemoved: TIdBufferBytesRemoved = nil);
    procedure Clear; //also clear "Readed"
    function  Extract(const AByteCount: Integer): string; override; //since Memory is not virtual
    function  Memory: Pointer; //ptr to not readed data
    procedure PackBuffer; //clear "Readed"
    procedure Remove (const AByteCount: integer); override;
    function  Seek(Offset: Longint; Origin: Word): Longint; override;
    //
    property  PackReadedSize: Integer read FPackReadedSize write SetPackReadedSize default IdInBufCacheSizeDefault;
  End;//TIdManagedBuffer

  TIdTCPConnection = class(TIdComponent)
  protected
    FASCIIFilter: boolean;
    // TODO - Change the "move" functions to read write functinos. Get as much as possible down
    // to just TStream so we can replace it easily
    FClosedGracefully: Boolean;
    FGreeting: TIdRFCReply;
    FFreeIOHandlerOnDisconnect: Boolean;
    FInputBuffer: TIdManagedBuffer;
    FIntercept: TIdConnectionIntercept;
    FIOHandler: TIdIOHandler;
    FLastCmdResult: TIdRFCReply;
    FMaxLineAction: TIdMaxLineAction;
    FMaxLineLength: Integer;
    FOnDisconnected: TNotifyEvent;
    FReadLnSplit: Boolean;
    FReadLnTimedOut: Boolean;
    FReadTimeout: Integer;
    FRecvBufferSize: Integer;
    FRecvBuffer: TIdSimpleBuffer; // To be used by ReadFromStack only
    FSendBufferSize: Integer;
    FSocket: TIdIOHandlerSocket;
    FWriteBuffer: TIdSimpleBuffer;
    FWriteBufferThreshhold: Integer;
    //
    procedure BufferRemoveNotify(ASender: TObject; const ABytes: Integer);
    procedure DoOnDisconnected; virtual;
    procedure Notification(AComponent: TComponent; Operation: TOperation); override;
    procedure PerformCapture(ADest: TObject; out VLineCount: Integer; const ADelim: string;
     const AIsRFCMessage: Boolean);
    procedure ResetConnection; virtual;
    procedure SetIntercept(AValue: TIdConnectionIntercept);
    procedure SetIOHandler(AValue: TIdIOHandler);
  public
    function AllData: string; virtual;
    procedure CancelWriteBuffer;
    procedure Capture(ADest: TStream; const ADelim: string = '.';
     const AIsRFCMessage: Boolean = True); overload;
    procedure Capture(ADest: TStream; out VLineCount: Integer; const ADelim: string = '.';
     const AIsRFCMessage: Boolean = True); overload;
    procedure Capture(ADest: TStrings; const ADelim: string = '.';
     const AIsRFCMessage: Boolean = True); overload;
    procedure Capture(ADest: TStrings; out VLineCount: Integer; const ADelim: string = '.';
     const AIsRFCMessage: Boolean = True); overload;
    procedure CheckForDisconnect(const ARaiseExceptionIfDisconnected: boolean = true;
     const AIgnoreBuffer: boolean = false); virtual;
    procedure CheckForGracefulDisconnect(const ARaiseExceptionIfDisconnected: Boolean = True);
     virtual;
    function CheckResponse(const AResponse: SmallInt; const AAllowedResponses: array of SmallInt)
     : SmallInt; virtual;
    procedure ClearWriteBuffer;
    procedure CloseWriteBuffer;
    function Connected: Boolean; virtual;
    constructor Create(AOwner: TComponent); override;
    function CurrentReadBuffer: string;
    destructor Destroy; override;
    procedure Disconnect; virtual;
    procedure DisconnectSocket; virtual;
    procedure FlushWriteBuffer(const AByteCount: Integer = -1);
    procedure GetInternalResponse;
    function GetResponse(const AAllowedResponses: array of SmallInt): SmallInt; overload; virtual;
    function GetResponse(const AAllowedResponse: SmallInt): SmallInt; overload;
    property Greeting: TIdRFCReply read FGreeting write FGreeting;
    function InputLn(const AMask: String = ''; AEcho: Boolean = True; ATabWidth: Integer = 8;
     AMaxLineLength: Integer = -1): String;
    procedure OpenWriteBuffer(const AThreshhold: Integer = -1);
    // RaiseExceptionForCmdResult - Overload necesary as a exception as a default param doesnt work
    procedure RaiseExceptionForLastCmdResult; overload; virtual;
    procedure RaiseExceptionForLastCmdResult(AException: TClassIdException); overload; virtual;
    procedure ReadBuffer(var ABuffer; const AByteCount: Longint);
    function ReadCardinal(const AConvert: boolean = true): Cardinal;
    function ReadChar: Char;
    // ReadFromStack must be only call to Recv
    function ReadFromStack(const ARaiseExceptionIfDisconnected: Boolean = True;
      ATimeout: Integer = IdTimeoutDefault;
      const ARaiseExceptionOnTimeout: Boolean = True): Integer; virtual;
    function ReadInteger(const AConvert: boolean = true): Integer;
    function ReadLn(ATerminator: string = LF;
      const ATimeout: Integer = IdTimeoutDefault; AMaxLineLength: Integer = -1): string; virtual;
    function ReadLnWait(AFailCount: Integer = MaxInt): string;
    function ReadSmallInt(const AConvert: boolean = true): SmallInt;
    procedure ReadStream(AStream: TStream; AByteCount: LongInt = -1;
     const AReadUntilDisconnect: boolean = false);
    function ReadString(const ABytes: Integer): string;
    procedure ReadStrings(var AValue: TStrings; AReadLinesCount: Integer = -1);
    function SendCmd(const AOut: string; const AResponse: SmallInt = -1): SmallInt; overload;
    function SendCmd(const AOut: string; const AResponse: Array of SmallInt): SmallInt; overload; virtual;
    function WaitFor(const AString: string): string;
    procedure Write(const AOut: string); virtual;
    // WriteBuffer must be the ONLY call to SEND - all data goes thru this method
    procedure WriteBuffer(const ABuffer; AByteCount: Longint; const AWriteNow: Boolean = False);
    procedure WriteCardinal(AValue: Cardinal; const AConvert: Boolean = True);
    procedure WriteHeader(AHeader: TStrings);
    procedure WriteInteger(AValue: Integer; const AConvert: Boolean = True);
    procedure WriteLn(const AOut: string = ''); virtual;
    procedure WriteRFCReply(AReply: TIdRFCReply);
    procedure WriteRFCStrings(AStrings: TStrings);
    procedure WriteSmallInt(AValue: SmallInt; const AConvert: Boolean = True);
    procedure WriteStream(AStream: TStream; const AAll: Boolean = True;
     const AWriteByteCount: Boolean = False; const ASize: Integer = 0); virtual;
    procedure WriteStrings(AValue: TStrings; const AWriteLinesCount: Boolean = False);
    function WriteFile(const AFile: String; const AEnableTransferFile: Boolean = False): Cardinal; virtual;
    //
    property ClosedGracefully: Boolean read FClosedGracefully;
    property InputBuffer: TIdManagedBuffer read FInputBuffer;
    property LastCmdResult: TIdRFCReply read FLastCmdResult;
    property ReadLnSplit: Boolean read FReadLnSplit;
    property ReadLnTimedOut: Boolean read FReadLnTimedOut;
    property Socket: TIdIOHandlerSocket read FSocket;
  published
    property ASCIIFilter: boolean read FASCIIFilter write FASCIIFilter default False;
    property Intercept: TIdConnectionIntercept read FIntercept write SetIntercept;
    property IOHandler: TIdIOHandler read FIOHandler write SetIOHandler;
    property MaxLineLength: Integer read FMaxLineLength write FMaxLineLength default IdMaxLineLengthDefault;
    property MaxLineAction: TIdMaxLineAction read FMaxLineAction write FMaxLineAction;
    property ReadTimeout: Integer read FReadTimeout write FReadTimeout default IdDefTimeout;
    property RecvBufferSize: Integer read FRecvBufferSize write FRecvBufferSize
     default GRecvBufferSizeDefault;
    property SendBufferSize: Integer read FSendBufferSize write FSendBufferSize
     default GSendBufferSizeDefault;
    // Events
    property OnDisconnected: TNotifyEvent read FOnDisconnected write FOnDisconnected;
    property OnWork;
    property OnWorkBegin;
    property OnWorkEnd;
  end;

  EIdTCPConnectionError = class(EIdException);
  EIdObjectTypeNotSupported = class(EIdTCPConnectionError);
  EIdNotEnoughDataInBuffer = class(EIdTCPConnectionError);
  EIdInterceptPropIsNil = class(EIdTCPConnectionError);
  EIdInterceptPropInvalid = class(EIdTCPConnectionError);
  EIdIOHandlerPropInvalid = class(EIdTCPConnectionError);
  EIdNoDataToRead = class(EIdTCPConnectionError);
  EIdNotConnected = class(EIdTCPConnectionError);
  EIdFileNotFound = class(EIdTCPConnectionError);

implementation

uses
  IdAntiFreezeBase, IdStack, IdStackConsts, IdStream, IdResourceStrings,
  SysUtils;

function TIdTCPConnection.AllData: string;
begin
  BeginWork(wmRead); try
    Result := '';
    while Connected do begin
      Result := Result + CurrentReadBuffer;
    end;
  finally EndWork(wmRead); end;
end;

procedure TIdTCPConnection.PerformCapture(ADest: TObject; out VLineCount: Integer;
 const ADelim: string; const AIsRFCMessage: Boolean);
const
  wDoublePoint = ord('.') shl 8 + ord('.');
type
  PWord = ^Word;
var
  s: string;
begin
  VLineCount := 0;
  BeginWork(wmRead); try
    repeat
      s := ReadLn;
      if s = ADelim then begin
        Exit;
      end;
      // For RFC 822 retrieves
      // No length check necessary, if only one byte it will be byte x + #0.
      if AIsRFCMessage and (PWord(PChar(S))^ = wDoublePoint) then begin
        Delete(s, 1, 1);
      end;
      // Write to output
      Inc(VLineCount);
      if ADest is TStrings then begin
        TStrings(ADest).Add(s);
      end else if ADest is TStream then begin
        TIdStream(ADest).WriteLn(s);
      end else if ADest <> nil then begin
        raise EIdObjectTypeNotSupported.Create(RSObjectTypeNotSupported);
      end;
    until False;
  finally EndWork(wmRead); end;
end;

procedure TIdTCPConnection.CheckForDisconnect(const ARaiseExceptionIfDisconnected: Boolean = True;
 const AIgnoreBuffer: Boolean = False);
var
  LDisconnected: Boolean;
begin
  LDisconnected := False;
  // ClosedGracefully // Server disconnected
  // IOHandler = nil // Client disconnected
  if (IOHandler <> nil) then begin
    if ClosedGracefully then begin
      if IOHandler.Connected then begin
      	DisconnectSocket;
        // Call event handlers to inform the user program that we were disconnected
        DoStatus(hsDisconnected);
        DoOnDisconnected;
      end;
      LDisconnected := True;
    end else begin
      LDisconnected := not IOHandler.Connected;
    end;
  end;
  if LDisconnected then begin
    // Do not raise unless all data has been read by the user
    if ((InputBuffer.Size = 0) or AIgnoreBuffer) and ARaiseExceptionIfDisconnected then begin
      (* ************************************************************* //
      ------ If you receive an exception here, please read. ----------

      If this is a SERVER
      -------------------
      The client has disconnected the socket normally and this exception is used to notify the
      server handling code. This exception is normal and will only happen from within the IDE, not
      while your program is running as an EXE. If you do not want to see this, add this exception
      or EIdSilentException to the IDE options as exceptions not to break on.

      From the IDE just hit F9 again and Indy will catch and handle the exception.

      Please see the FAQ and help file for possible further information.
      The FAQ is at http://www.nevrona.com/Indy/FAQ.html

      If this is a CLIENT
      -------------------
      The server side of this connection has disconnected normaly but your client has attempted
      to read or write to the connection. You should trap this error using a try..except.
      Please see the help file for possible further information.

      // ************************************************************* *)
      raise EIdConnClosedGracefully.Create(RSConnectionClosedGracefully);
    end;
  end;
end;

function TIdTCPConnection.Connected: Boolean;
begin
  CheckForDisconnect(False);
  Result := IOHandler <> nil;
  if Result then begin
    Result := IOHandler.Connected;
  end;
end;

constructor TIdTCPConnection.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FReadTimeout := IdDefTimeout;
  FGreeting := TIdRFCReply.Create(nil);
  FLastCmdResult := TIdRFCReply.Create(nil);
  FRecvBuffer := TIdSimpleBuffer.Create;

  RecvBufferSize := GRecvBufferSizeDefault;
  FSendBufferSize := GSendBufferSizeDefault;
  FInputBuffer := TIdManagedBuffer.Create(BufferRemoveNotify);
  FMaxLineLength := IdMaxLineLengthDefault;
end;

function TIdTCPConnection.CurrentReadBuffer: string;
begin
  Result := '';
  if Connected then begin
    ReadFromStack(False);
  end;
  Result := InputBuffer.Extract(InputBuffer.Size);
end;

destructor TIdTCPConnection.Destroy;
begin
  // DisconnectSocket closes IOHandler etc. Dont call Disconnect - Disconnect may be override and
  // try to read/write to the socket.
  DisconnectSocket;

  // Because DisconnectSocket does not free the IOHandler we have to do it here.
  if FFreeIOHandlerOnDisconnect then begin
    FreeAndNil(FIOHandler);
    FFreeIOHandlerOnDisconnect := False;
  end;

  FreeAndNil(FInputBuffer);
  FreeAndNil(FRecvBuffer);
  FreeAndNil(FLastCmdResult);
  FreeAndNil(FGreeting);
  inherited Destroy;
end;

procedure TIdTCPConnection.Disconnect;
var
  LConnected: boolean;
begin
  {
   there are a few possible situations here:
   1) we are still connected, then everything works as before,
      status disconnecting, then disconnect, status disconnected
   2) we are not connected, and this is just some "rogue" call to
      disconnect(), then nothing happens
   3) we are not connected, because ClosedGracefully, then
      LConnected will be false, but the implicit call to
      CheckForDisconnect (inside Connected) will call the events
  }
  LConnected := Connected;
  if LConnected then begin
    DoStatus(hsDisconnecting);
    DisconnectSocket;
  end;
  // NOT in DisconnectSocket. DisconnectSocket is used to kick ReadFromStack and others
  // out of their blocking calls and they rely on the binding after that
  if FFreeIOHandlerOnDisconnect then begin
    FreeAndNil(FIOHandler);
    FFreeIOHandlerOnDisconnect := False;
  end;
  if LConnected then begin
    DoOnDisconnected;
    DoStatus(hsDisconnected);
  end;
end;

procedure TIdTCPConnection.DoOnDisconnected;
begin
  if Assigned(OnDisconnected) then begin
    OnDisconnected(Self);
  end;
end;

function TIdTCPConnection.GetResponse(const AAllowedResponses: array of SmallInt): SmallInt;
begin
  GetInternalResponse;
  Result := CheckResponse(LastCmdResult.NumericCode, AAllowedResponses);
end;

procedure TIdTCPConnection.RaiseExceptionForLastCmdResult(AException: TClassIdException);
begin
  raise AException.Create(LastCmdResult.Text.Text);
end;

procedure TIdTCPConnection.RaiseExceptionForLastCmdResult;
begin
  raise EIdProtocolReplyError.CreateError(LastCmdResult.NumericCode, LastCmdResult.Text.Text);
end;

procedure TIdTCPConnection.ReadBuffer(var ABuffer; const AByteCount: Integer);
begin
  if (AByteCount > 0) and (@ABuffer <> nil) then begin
    // Read from stack until we have enough data
    while (InputBuffer.Size < AByteCount) do begin
      ReadFromStack;
      CheckForDisconnect(True, True);
    end;
    // Copy it to the callers buffer
    Move(InputBuffer.Memory^, ABuffer, AByteCount);
    // Remove used data from buffer
    InputBuffer.Remove(AByteCount);
  end;
end;

function TIdTCPConnection.ReadFromStack(const ARaiseExceptionIfDisconnected: Boolean = True;
 ATimeout: Integer = IdTimeoutDefault; const ARaiseExceptionOnTimeout: Boolean = True): Integer;
// Reads any data in tcp/ip buffer and puts it into Indy buffer
// This must be the ONLY raw read from Winsock routine
// This must be the ONLY call to RECV - all data goes thru this method
var
  i: Integer;
  LByteCount: Integer;
begin
  if ATimeout = IdTimeoutDefault then begin
    if ReadTimeOut = 0 then begin
      ATimeout := IdTimeoutInfinite;
    end else begin
      ATimeout := FReadTimeout;
    end;
  end;
  Result := 0;
  // Check here as this side may have closed the socket
  CheckForDisconnect(ARaiseExceptionIfDisconnected);
  if Connected then begin
    LByteCount := 0;
    repeat
      if IOHandler.Readable(ATimeout) then begin
        if Assigned(FRecvBuffer) and Assigned(IOHandler) then begin //APR: disconnect from other thread
          FRecvBuffer.Size := RecvBufferSize;
        // No need to call AntiFreeze, the Readable does that.
          LByteCount := IOHandler.Recv(FRecvBuffer.Memory^, FRecvBuffer.Size);
        end else begin
          LByteCount := 0;
          if ARaiseExceptionIfDisconnected then
            raise EIdNotConnected.Create(RSNotConnected);
        end;
        FClosedGracefully := LByteCount = 0;
        if not ClosedGracefully then begin
          if GStack.CheckForSocketError(LByteCount, [Id_WSAESHUTDOWN, Id_WSAECONNABORTED]) then begin
            LByteCount := 0;
            if IOHandler <> nil then begin
              DisconnectSocket;
            end;
            // Do not raise unless all data has been read by the user
            if InputBuffer.Size = 0 then begin
              GStack.RaiseSocketError(GStack.LastError);
            end;
          end;
          // InputBuffer.Size is modified above
          if LByteCount > 0 then begin
            FRecvBuffer.Size := LByteCount;
            if Assigned(Intercept) then begin
              FRecvBuffer.Position := 0;
              Intercept.Receive(FRecvBuffer);
              LByteCount := FRecvBuffer.Size;
            end;
            if ASCIIFilter then begin
              for i := 1 to FRecvBuffer.Size do begin
                PChar(FRecvBuffer.Memory)[i] := Chr(Ord(PChar(FRecvBuffer.Memory)[i]) and $7F);
              end;
            end;
            FInputBuffer.Seek(0, soFromEnd);
            FInputBuffer.WriteBuffer(FRecvBuffer.Memory^, FRecvBuffer.Size);
          end;
        end;
        // Check here as other side may have closed connection
        CheckForDisconnect(ARaiseExceptionIfDisconnected);
        Result := LByteCount;
      end else begin
        // Timeout
        if ARaiseExceptionOnTimeout then begin
          raise EIdReadTimeout.Create(RSReadTimeout);
        end;
        Result := -1;
        Break;
      end;
    until (LByteCount <> 0) or (Connected = False);
  end else begin
    if ARaiseExceptionIfDisconnected then begin
      raise EIdNotConnected.Create(RSNotConnected);
    end;
  end;
end;

function TIdTCPConnection.ReadInteger(const AConvert: boolean = true): Integer;
begin
  ReadBuffer(Result, SizeOf(Result));
  if AConvert then begin
    Result := Integer(GStack.WSNToHL(LongWord(Result)));
  end;
end;

function TIdTCPConnection.ReadLn(ATerminator: string = LF;
 const ATimeout: Integer = IdTimeoutDefault; AMaxLineLength: Integer = -1): string;
var
  LInputBufferSize: Integer;
  LSize: Integer;
  LTermPos: Integer;
begin
  if AMaxLineLength = -1 then begin
    AMaxLineLength := MaxLineLength;
  end;
  // User may pass '' if they need to pass arguments beyond the first.
  if Length(ATerminator) = 0 then begin
    ATerminator := LF;
  end;
  FReadLnSplit := False;
  FReadLnTimedOut := False;
  LTermPos := 0;
  LSize := 0;
  repeat
    LInputBufferSize := InputBuffer.Size;
    if LInputBufferSize > 0 then begin
      LTermPos :=
        MemoryPos(ATerminator, PChar(InputBuffer.Memory) + LSize, LInputBufferSize - LSize);
      if LTermPos > 0 then begin
        LTermPos := LTermPos + LSize;
      end;
      LSize := LInputBufferSize;
    end;//if
    if (LTermPos - 1 > AMaxLineLength) and (AMaxLineLength <> 0) then begin
      if MaxLineAction = maException then begin
        raise EIdReadLnMaxLineLengthExceeded.Create(RSReadLnMaxLineLengthExceeded);
      end else begin
        FReadLnSplit := True;
        Result := InputBuffer.Extract(AMaxLineLength);
        Exit;
      end;
    // ReadFromStack blocks - do not call unless we need to
    end else if LTermPos = 0 then begin
      if (LSize > AMaxLineLength) and (AMaxLineLength <> 0) then begin
        if MaxLineAction = maException then begin
          raise EIdReadLnMaxLineLengthExceeded.Create(RSReadLnMaxLineLengthExceeded);
        end else begin
          FReadLnSplit := True;
          Result := InputBuffer.Extract(AMaxLineLength);
          Exit;
        end;
      end;
      // ReadLn needs to call this as data may exist in the buffer, but no EOL yet disconnected
      CheckForDisconnect(True, True);
      // Can only return -1 if timeout
      FReadLnTimedOut := ReadFromStack(True, ATimeout, ATimeout = IdTimeoutDefault) = -1;
      if ReadLnTimedout then begin
        Result := '';
        Exit;
      end;
    end;
  until LTermPos > 0;
  dec(LTermPos);// Strip terminators (string len w/o first terminator char)
  Result := InputBuffer.Extract(LTermPos + Length(ATerminator));// Extract actual data
  if (ATerminator = LF) and (LTermPos > 0) and (Result[LTermPos] = CR) then begin
    SetLength(Result, LTermPos - 1);
  end else begin
    SetLength(Result, LTermPos);
  end;
end;//ReadLn

function TIdTCPConnection.ReadLnWait(AFailCount: Integer = MaxInt): string;
var
  LAttempts: Integer;
begin
  Result := '';
  LAttempts := 0;
  while (Length(Result) = 0) and (LAttempts < AFailCount) do begin
    Inc(LAttempts);
    Result := Trim(ReadLn);
  end;
end; //ReadLnWait

procedure TIdTCPConnection.ReadStream(AStream: TStream; AByteCount: Integer = -1;
 const AReadUntilDisconnect: Boolean = False);
var
  i: Integer;
  LBuf: packed array of Byte;
  LBufSize: Integer;
  LWorkCount: Integer;

  procedure AdjustStreamSize(AStream: TStream; const ASize: integer);
  var
    LStreamPos: LongInt;
  begin
    LStreamPos := AStream.Position;
    AStream.Size := ASize;
    // Must reset to original size as in some cases size changes position
    if AStream.Position <> LStreamPos then begin
      AStream.Position := LStreamPos;
    end;
  end;

begin
  if (AByteCount = -1) and (AReadUntilDisconnect = False) then begin
    // Read size from connection
    AByteCount := ReadInteger;
  end;
  // Presize stream if we know the size - this reduces memory/disk allocations to one time
  if AByteCount > -1 then begin
    AdjustStreamSize(AStream, AStream.Position + AByteCount);
  end;

  if AReadUntilDisconnect then begin
    LWorkCount := High(LWorkCount);
    BeginWork(wmRead);
  end else begin
    LWorkCount := AByteCount;
    BeginWork(wmRead, LWorkCount);
  end;

  try
    // If data already exists in the buffer, write it out first.
    if InputBuffer.Size > 0 then begin
      i := Min(InputBuffer.Size, LWorkCount);
      InputBuffer.Position := 0;
      AStream.CopyFrom(InputBuffer, i);
      InputBuffer.Remove(i);
      Dec(LWorkCount, i);
    end;

    LBufSize := Min(LWorkCount, RecvBufferSize);
    SetLength(LBuf, LBufSize);

    while Connected and (LWorkCount > 0) do begin
      i := Min(LWorkCount, LBufSize);
      //TODO: Improve this - dont like the use of the exception handler
      //DONE -oAPR: Dont use a string, use a memory buffer or better yet the buffer itself.
      try
        try
          ReadBuffer(LBuf[0], i);
        except
          on E: EIdConnClosedGracefully do begin
            if AReadUntilDisconnect then begin
              i := InputBuffer.Size;
              Move(InputBuffer.Memory^, LBuf[0], i);
              InputBuffer.Clear; //InputBuffer.Remove(InputBuffer.Size);
            end else begin
              i := 0;
              raise;
            end;
          end;
        end;
      finally
        if i > 0 then begin
          AStream.WriteBuffer(LBuf[0], i);
          Dec(LWorkCount, i);
        end;
      end;
    end;
  finally
    EndWork(wmRead);
    if AStream.Size > AStream.Position then begin
      AStream.Size := AStream.Position;
    end;
    LBuf := NIL;
  end;
end;

procedure TIdTCPConnection.ResetConnection;
begin
  InputBuffer.Clear;
  FClosedGracefully := False;
end;

function TIdTCPConnection.SendCmd(const AOut: string; const AResponse: Array of SmallInt): SmallInt;
begin
  WriteLn(AOut);
  Result := GetResponse(AResponse);
end;

procedure TIdTCPConnection.Notification(AComponent: TComponent; Operation: TOperation);
begin
  inherited Notification(AComponent, OPeration);

  if (Operation = opRemove) then begin
    if (AComponent = FIntercept) then begin
      FIntercept := nil;
    end;

    if (AComponent = FIOHandler) then begin
      FIOHandler := nil;
    end;
  end;
end;

procedure TIdTCPConnection.SetIntercept(AValue: TIdConnectionIntercept);
begin
  FIntercept := AValue;
  // add self to the Intercept's free notification list
  if Assigned(FIntercept) then begin
    FIntercept.FreeNotification(Self);
  end;
end;

procedure TIdTCPConnection.SetIOHandler(AValue: TIdIOHandler);
begin
  if Assigned(FIOHandler) and FFreeIOHandlerOnDisconnect then begin
    FreeAndNil(FIOHandler); // Clear the existing IOHandler
    FFreeIOHandlerOnDisconnect := false;
  end;
  if AValue = nil then begin
    FSocket := nil;
  end else if AValue is TIdIOHandlerSocket then begin
    FSocket := TIdIOHandlerSocket(AValue);
  end;
  FIOHandler := AValue;
  // add self to the IOHandler's free notification list
  if Assigned(FIOHandler) then begin
    FIOHandler.FreeNotification(Self);
  end;
end;

procedure TIdTCPConnection.Write(const AOut: string);
var
  LOutLen: Integer;
Begin
  LOutLen := Length(AOut);
  if LOutLen > 0 then begin
    WriteBuffer(Pointer(AOut)^, LOutLen);    
  end;
End;//Write

procedure TIdTCPConnection.WriteBuffer(const ABuffer; AByteCount: Integer;
 const AWriteNow: boolean = false);
var
  LBuffer: TIdSimpleBuffer;
  nPos, nByteCount: Integer;
begin
  if (AByteCount > 0) and (@ABuffer <> nil) then begin
    // Check if disconnected
    CheckForDisconnect(True, True);
    if connected then begin
      if (FWriteBuffer = nil) or AWriteNow then begin
        LBuffer := TIdSimpleBuffer.Create; try
          LBuffer.WriteBuffer(ABuffer, AByteCount);
          if Assigned(Intercept) then begin
            LBuffer.Position := 0;
            Intercept.Send(LBuffer);
            AByteCount := LBuffer.Size;
          end;
          nPos := 1;
          repeat
            nByteCount := IOHandler.Send(PChar(LBuffer.Memory)[nPos - 1], LBuffer.Size - nPos + 1);
            // Write always does someting - never retuns 0
            // TODO - Have a AntiFreeze param which allows the send to be split up so that process
            // can be called more. Maybe a prop of the connection, MaxSendSize?
            TIdAntiFreezeBase.DoProcess(False);
            FClosedGracefully := nByteCount = 0;

            // Check if other side disconnected
            CheckForDisconnect;
            // Check to see if the error signifies disconnection
            if GStack.CheckForSocketError(nByteCount
             , [ID_WSAESHUTDOWN, Id_WSAECONNABORTED, Id_WSAECONNRESET]) then begin
              DisconnectSocket;
              GStack.RaiseSocketError(GStack.WSGetLastError);
            end;
            DoWork(wmWrite, nByteCount);
            nPos := nPos + nByteCount;
          until nPos > AByteCount;
        finally FreeAndNil(LBuffer); end;
    // Write Buffering is enabled
      end else begin
        FWriteBuffer.WriteBuffer(ABuffer, AByteCount);
        if (FWriteBuffer.Size >= FWriteBufferThreshhold) and (FWriteBufferThreshhold > 0) then begin
          // TODO: Maybe? instead of flushing - Write until buffer is smaller than Threshold.
          // That is do at least one physical send.
          FlushWriteBuffer(FWriteBufferThreshhold);
        end;
      end;
    end
    else
    begin
      Raise EIdNotConnected.Create(RSNotConnected);
    end;
  end;
end;

function TIdTCPConnection.WriteFile(const AFile: String; const AEnableTransferFile: boolean = False): Cardinal;
var
//TODO: There is a way in linux to dump a file to a socket as well. use it.
  LFileStream: TFileStream;
begin
  if FileExists(AFile) then begin
    if Assigned(GServeFileProc) and (Intercept = nil) and AEnableTransferFile
      and (Socket <> nil) then begin
      Result := GServeFileProc(Socket.Binding.Handle, AFile);
    end else begin
      LFileStream := TFileStream.Create(AFile, fmOpenRead or fmShareDenyWrite);
      try
        WriteStream(LFileStream); //ALL Stream, no bcnt
        Result := LFileStream.Size;
      finally LFileStream.free; end;
    end;
  end else begin
    raise EIdFileNotFound.Create(Format(RSFileNotFound,[AFile]));
  end;
end;


procedure TIdTCPConnection.WriteHeader(AHeader: TStrings);
var
  i: Integer;
begin
  for i := 0 to AHeader.Count -1 do begin
    // No ReplaceAll flag - we only want to replace the first one
    WriteLn(StringReplace(AHeader[i], '=', ': ', []));
  end;
  WriteLn('');
end;

procedure TIdTCPConnection.WriteInteger(AValue: Integer; const AConvert: Boolean = True);
begin
  if AConvert then begin
    AValue := Integer(GStack.WSHToNl(LongWord(AValue)));
  end;
  WriteBuffer(AValue, SizeOf(AValue));
end;

procedure TIdTCPConnection.WriteLn(const AOut: string = '');
begin
  Write(AOut + EOL);
end;

procedure TIdTCPConnection.WriteStream(AStream: TStream; const AAll: boolean = true;
 const AWriteByteCount: Boolean = False; const ASize: Integer = 0);
var
  LBuffer: TMemoryStream;
  LSize: Integer;
  LStreamEnd: Integer;
begin
  if AAll then begin
    AStream.Position := 0;
  end;
  // This is copied to a local var because accessing .Size is very inefficient
  if ASize = 0 then begin
    LStreamEnd := AStream.Size;
  end else begin
    LStreamEnd := ASize + AStream.Position;
  end;
  LSize := LStreamEnd - AStream.Position;
  if AWriteByteCount then begin
  	WriteInteger(LSize);
  end;
  BeginWork(wmWrite, LSize); try
    LBuffer := TMemoryStream.Create; try
      LBuffer.SetSize(FSendBufferSize);
      while True do begin
        LSize := Min(LStreamEnd - AStream.Position, FSendBufferSize);
        if LSize = 0 then begin
          Break;
        end;
        // Do not use ReadBuffer. Some source streams are real time and will not
        // return as much data as we request. Kind of like recv()
        // NOTE: We use .Size - size must be supported even if real time
        LSize := AStream.Read(LBuffer.Memory^, LSize);
        if LSize = 0 then begin
          raise EIdNoDataToRead.Create(RSIdNoDataToRead);
        end;
        WriteBuffer(LBuffer.Memory^, LSize);
      end;
    finally FreeAndNil(LBuffer); end;
  finally EndWork(wmWrite); end;
end;

procedure TIdTCPConnection.WriteStrings(AValue: TStrings; const AWriteLinesCount: Boolean = False);
var
  i: Integer;
begin
  if AWriteLinesCount then begin
    WriteInteger(AValue.Count);
  end;
  for i := 0 to AValue.Count - 1 do begin
    WriteLn(AValue.Strings[i]);
  end;
end;

function TIdTCPConnection.SendCmd(const AOut: string; const AResponse: SmallInt): SmallInt;
begin
  if AResponse = -1 then begin
    Result := SendCmd(AOut, []);
  end else begin
    Result := SendCmd(AOut, [AResponse]);
  end;
end;

procedure TIdTCPConnection.DisconnectSocket;
begin
  if IOHandler <> nil then begin
    FClosedGracefully := True;
    // In design time don't use propertyes which point to other compoenents
    if not (csDesigning in ComponentState) then begin
      if Assigned(Intercept) then begin
        Intercept.Disconnect;
      end;
      IOHandler.Close;
    end;
  end;
end;

procedure TIdTCPConnection.OpenWriteBuffer(const AThreshhold: Integer = -1);
begin
  FWriteBuffer := TIdSimpleBuffer.Create;
  FWriteBufferThreshhold := AThreshhold;
end;

procedure TIdTCPConnection.CloseWriteBuffer;
begin
  try
    FlushWriteBuffer;
  finally
    FreeAndNil(FWriteBuffer);
  end;
end;

procedure TIdTCPConnection.FlushWriteBuffer(const AByteCount: Integer = -1);
begin
  if FWriteBuffer.Size > 0 then begin
    if (AByteCount = -1) or (FWriteBuffer.Size < AByteCount) then begin
      WriteBuffer(PChar(FWriteBuffer.Memory)[0], FWriteBuffer.Size, True);
      ClearWriteBuffer;
    end else begin
      WriteBuffer(PChar(FWriteBuffer.Memory)[0], AByteCount, True);
      FWriteBuffer.Remove(AByteCount);
    end;
  end;
end;

procedure TIdTCPConnection.ClearWriteBuffer;
begin
  FWriteBuffer.Clear;
end;

function TIdTCPConnection.InputLn(const AMask: string = ''; AEcho: Boolean = True;
 ATabWidth: Integer = 8; AMaxLineLength: Integer = -1): string;
var
  i: Integer;
  LChar: Char;
  LTmp: string;
Begin
  if AMaxLineLength = -1 then begin
    AMaxLineLength := MaxLineLength;
  end;
  Result := '';
  repeat
    LChar := ReadChar;
    i := Length(Result);
    if i <= AMaxLineLength then begin
      case LChar of
        BACKSPACE:
          begin
            if i > 0 then begin
              SetLength(Result, i - 1);
              if AEcho then begin
                Write(BACKSPACE + ' ' + BACKSPACE);
              end;
            end;
          end;
        TAB:
          begin
            if ATabWidth > 0 then begin
              i := ATabWidth - (i mod ATabWidth);
              LTmp := StringOfChar(' ', i);
              Result := Result + LTmp;
              if AEcho then begin
                Write(LTmp);
              end;
            end else begin
              Result := Result + LChar;
              if AEcho then begin
                Write(LChar);
              end;
            end;
          end;
        LF: ;
        CR: ;
        #27: ; //ESC - currently not supported
      else
        Result := Result + LChar;
        if AEcho then begin
          if Length(AMask) = 0 then begin
            Write(LChar);
          end else begin
            Write(AMask);
          end;
        end;
      end;
    end;
  until LChar = LF;
  // Remove CR trail
  i := Length(Result);
  while (i > 0) and (Result[i] in [CR, LF]) do begin
    Dec(i);
  end;
  SetLength(Result, i);
  if AEcho then begin
    WriteLn;
  end;
end;

function TIdTCPConnection.ReadString(const ABytes: Integer): string;
begin
  SetLength(result, ABytes);
  if ABytes > 0 then begin
    ReadBuffer(result[1], Length(result));
  end;
end;

procedure TIdTCPConnection.ReadStrings(var AValue: TStrings; AReadLinesCount: Integer = -1);
Var
  i: Integer;
begin
  if AReadLinesCount <= 0 then begin
    AReadLinesCount := ReadInteger;
  end;
  for i := 0 to AReadLinesCount - 1 do begin
    AValue.Add(ReadLn);
  end;
end;

procedure TIdTCPConnection.CancelWriteBuffer;
begin
  ClearWriteBuffer;
  CloseWriteBuffer;
end;

function TIdTCPConnection.ReadSmallInt(const AConvert: boolean = true): SmallInt;
begin
  ReadBuffer(Result, SizeOf(Result));
  if AConvert then begin
    Result := SmallInt(GStack.WSNToHs(Word(Result)));
  end;
end;

procedure TIdTCPConnection.WriteSmallInt(AValue: SmallInt; const AConvert: boolean = true);
begin
  if AConvert then begin
    AValue := SmallInt(GStack.WSHToNs(Word(AValue)));
  end;
  WriteBuffer(AValue, SizeOf(AValue));
end;

procedure TIdTCPConnection.CheckForGracefulDisconnect(const ARaiseExceptionIfDisconnected: boolean);
begin
  ReadFromStack(ARaiseExceptionIfDisconnected, 1, False);
end;

{ TIdBuffer }

constructor TIdSimpleBuffer.Create(AOnBytesRemoved: TIdBufferBytesRemoved);
begin
  inherited Create;
  FOnBytesRemoved := AOnBytesRemoved;
end;

function TIdSimpleBuffer.Extract(const AByteCount: Integer): string;
begin
  if AByteCount > Size then begin
    raise EIdNotEnoughDataInBuffer.Create(RSNotEnoughDataInBuffer);
  end;
  SetString(Result, PChar(Memory), AByteCount);
  Remove(AByteCount);
end;

procedure TIdSimpleBuffer.Remove(const AByteCount: integer);
begin
  if AByteCount > Size then begin
    raise EIdNotEnoughDataInBuffer.Create(RSNotEnoughDataInBuffer);
  end;
  if AByteCount = Size then begin
    Clear;
  end else begin
    Move(PChar(Memory)[AByteCount], PChar(Memory)[0], Size - AByteCount);
    SetSize(Size - AByteCount);
  end;
  if Assigned(FOnBytesRemoved) then begin
    FOnBytesRemoved(Self, AByteCount);
  end;
end;

function TIdTCPConnection.WaitFor(const AString: string): string;
//TODO: Add a time out (default to infinite) and event to pass data
//TODO: Add a max size argument as well.
//TODO: Add a case insensitive option
//TODO: Bug - returns too much data. Should only return up to search string adn not including
//      and leave the rest in the buffer.
begin
  Result := '';
  // NOTE: AnsiPos should be used here, but AnsiPos has problems if result has any #0 in it,
  // which is often the case. So currently this function is not MBCS compliant and should
  // not be used in MBCS environments. However this function should only be used on incoming
  // TCP text data as it is 7 bit.
  while Pos(AString, Result) = 0 do begin
    Result := Result + CurrentReadBuffer;
    CheckForDisconnect;
  end;
end;

function TIdTCPConnection.ReadCardinal(const AConvert: boolean): Cardinal;
begin
  ReadBuffer(Result, SizeOf(Result));
  if AConvert then begin
    Result := GStack.WSNToHL(Result);
  end;
end;

procedure TIdTCPConnection.WriteCardinal(AValue: Cardinal; const AConvert: boolean);
begin
  if AConvert then begin
    AValue := GStack.WSHToNl(AValue);
  end;
	WriteBuffer(AValue, SizeOf(AValue));
end;

function TIdTCPConnection.CheckResponse(const AResponse: SmallInt;
 const AAllowedResponses: array of SmallInt): SmallInt;
var
  i: Integer;
  LResponseFound: Boolean;
begin
  if High(AAllowedResponses) > -1 then begin
    LResponseFound := False;
    for i := Low(AAllowedResponses) to High(AAllowedResponses) do begin
      if AResponse = AAllowedResponses[i] then begin
        LResponseFound := True;
        Break;
      end;
    end;
    if not LResponseFound then begin
      RaiseExceptionForLastCmdResult;
    end;
  end;
  Result := AResponse;
end;

procedure TIdTCPConnection.GetInternalResponse;
var
  LLine: string;
  LResponse: TStringList;
  LTerm: string;
begin
  LResponse := TStringList.Create; try
    LLine := ReadLnWait;
    LResponse.Add(LLine);
    if Length(LLine) > 3 then begin
      if LLine[4] = '-' then begin // Multi line response coming
        LTerm := Copy(LLine, 1, 3) + ' ';
        {We keep reading lines until we encounter either a line such as "250" or "250 Read"}
        repeat
          LLine := ReadLnWait;
          LResponse.Add(LLine);
        until (Length(LLine) < 4) or (AnsiSameText(Copy(LLine, 1, 4), LTerm));
      end;
    end;
    FLastCmdResult.ParseResponse(LResponse);
  finally FreeAndNil(LResponse); end;
end;

procedure TIdTCPConnection.WriteRFCReply(AReply: TIdRFCReply);
begin
  if AReply.ReplyExists then begin
    Write(AReply.GenerateReply);
  end;
end;

procedure TIdTCPConnection.WriteRFCStrings(AStrings: TStrings);
var
  i: Integer;
begin
  for i := 0 to AStrings.Count - 1 do begin
    if AStrings[i] = '.' then begin
      WriteLn('..');
    end else begin
      WriteLn(AStrings[i]);
    end;
  end;
  WriteLn('.');
end;

function TIdTCPConnection.GetResponse(const AAllowedResponse: SmallInt): SmallInt;
begin
  Result := GetResponse([AAllowedResponse]);
end;

procedure TIdTCPConnection.Capture(ADest: TStream; const ADelim: string;
  const AIsRFCMessage: Boolean);
var
  LLineCount: Integer;
begin
  PerformCapture(ADest, LLineCount, ADelim, AIsRFCMessage);
end;

procedure TIdTCPConnection.Capture(ADest: TStrings; const ADelim: string;
  const AIsRFCMessage: Boolean);
var
  LLineCount: Integer;
begin
  PerformCapture(ADest, LLineCount, ADelim, AIsRFCMessage);
end;

function TIdTCPConnection.ReadChar: Char;
begin
  ReadBuffer(Result, SizeOf(Result));
end;

procedure TIdTCPConnection.Capture(ADest: TStream; out VLineCount: Integer;
 const ADelim: string; const AIsRFCMessage: Boolean);
begin
  PerformCapture(ADest, VLineCount, ADelim, AIsRFCMessage);
end;

procedure TIdTCPConnection.Capture(ADest: TStrings; out VLineCount: Integer; const ADelim: string;
 const AIsRFCMessage: Boolean);
begin
  PerformCapture(ADest, VLineCount, ADelim, AIsRFCMessage);
end;

procedure TIdTCPConnection.BufferRemoveNotify(ASender: TObject; const ABytes: Integer);
begin
  DoWork(wmRead, ABytes);
end;

{ TIdManagedBuffer }

procedure TIdManagedBuffer.Clear;
Begin
  inherited Clear;
  FReadedSize:= 0;
End;//

constructor TIdManagedBuffer.Create(AOnBytesRemoved: TIdBufferBytesRemoved);
Begin
  inherited;
  FPackReadedSize := IdInBufCacheSizeDefault;
End;//

function TIdManagedBuffer.Extract(const AByteCount: Integer): string;
Begin
  if AByteCount > Size then begin
    raise EIdNotEnoughDataInBuffer.Create(RSNotEnoughDataInBuffer);
  end;
  SetString(Result, PChar(Memory), AByteCount);
  Remove(AByteCount);
End;//TIdManagedBuffer.Extract

function TIdManagedBuffer.Memory: Pointer;
Begin
  Result:=Pointer(Integer(inherited Memory) + FReadedSize);
End;//Memory

procedure TIdManagedBuffer.PackBuffer;
Begin
  if FReadedSize > 0 then begin
    Move(Pointer(Integer(inherited Memory) + FReadedSize)^,inherited Memory^,Size);
    SetSize(Size); //set REAL size to fresh size
    FReadedSize := 0;
  end;
End;//TIdManagedBuffer.PackBuffer

procedure TIdManagedBuffer.Remove(const AByteCount: integer);
Begin
  if AByteCount > Size then begin
    raise EIdNotEnoughDataInBuffer.Create(RSNotEnoughDataInBuffer);
  end else if AByteCount = Size then begin
    Clear;
  end else begin
    FReadedSize := FReadedSize + AByteCount;

    if FReadedSize >= PackReadedSize then begin
      PackBuffer;
    end;
  end;

  if Assigned(FOnBytesRemoved) then begin
    FOnBytesRemoved(Self, AByteCount);
  end;
End;

function TIdManagedBuffer.Seek(Offset: Integer; Origin: Word): Longint;
Begin //note: FPosition is TRUE, FSize is TRUE
  case Origin of
    soFromBeginning:
      begin
        Result:=inherited Seek(Offset + FReadedSize,soFromBeginning) - FReadedSize;
      end;
  else //soFromCurrent,soFromEnd:
    Result:=inherited Seek(Offset,Origin) - FReadedSize;
  end;
End;//TIdManagedBuffer.Seek

procedure TIdManagedBuffer.SetPackReadedSize(const Value: Integer);
Begin
  if Value>0 then begin
    FPackReadedSize := Value;
  end
  else begin
    FPackReadedSize := IdInBufCacheSizeDefault;
  end;
End;//

end.
