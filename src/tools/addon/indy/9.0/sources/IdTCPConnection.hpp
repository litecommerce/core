// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTCPConnection.pas' rev: 5.00

#ifndef IdTCPConnectionHPP
#define IdTCPConnectionHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdIOHandlerSocket.hpp>	// Pascal unit
#include <IdRFCReply.hpp>	// Pascal unit
#include <IdIOHandler.hpp>	// Pascal unit
#include <IdIntercept.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtcpconnection
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TIdBufferBytesRemoved)(System::TObject* ASender, const int ABytes
	);

class DELPHICLASS TIdSimpleBuffer;
class PASCALIMPLEMENTATION TIdSimpleBuffer : public Classes::TMemoryStream 
{
	typedef Classes::TMemoryStream inherited;
	
protected:
	TIdBufferBytesRemoved FOnBytesRemoved;
	
public:
	__fastcall TIdSimpleBuffer(TIdBufferBytesRemoved AOnBytesRemoved);
	virtual AnsiString __fastcall Extract(const int AByteCount);
	virtual void __fastcall Remove(const int AByteCount);
public:
	#pragma option push -w-inl
	/* TMemoryStream.Destroy */ inline __fastcall virtual ~TIdSimpleBuffer(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdManagedBuffer;
class PASCALIMPLEMENTATION TIdManagedBuffer : public TIdSimpleBuffer 
{
	typedef TIdSimpleBuffer inherited;
	
protected:
	int FPackReadedSize;
	int FReadedSize;
	void __fastcall SetPackReadedSize(const int Value);
	
public:
	__fastcall TIdManagedBuffer(TIdBufferBytesRemoved AOnBytesRemoved);
	HIDESBASE void __fastcall Clear(void);
	virtual AnsiString __fastcall Extract(const int AByteCount);
	HIDESBASE void * __fastcall Memory(void);
	void __fastcall PackBuffer(void);
	virtual void __fastcall Remove(const int AByteCount);
	virtual int __fastcall Seek(int Offset, Word Origin);
	__property int PackReadedSize = {read=FPackReadedSize, write=SetPackReadedSize, default=32768};
public:
		
	#pragma option push -w-inl
	/* TMemoryStream.Destroy */ inline __fastcall virtual ~TIdManagedBuffer(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdTCPConnection;
class PASCALIMPLEMENTATION TIdTCPConnection : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	bool FASCIIFilter;
	bool FClosedGracefully;
	Idrfcreply::TIdRFCReply* FGreeting;
	bool FFreeIOHandlerOnDisconnect;
	TIdManagedBuffer* FInputBuffer;
	Idintercept::TIdConnectionIntercept* FIntercept;
	Idiohandler::TIdIOHandler* FIOHandler;
	Idrfcreply::TIdRFCReply* FLastCmdResult;
	Idglobal::TIdMaxLineAction FMaxLineAction;
	int FMaxLineLength;
	Classes::TNotifyEvent FOnDisconnected;
	bool FReadLnSplit;
	bool FReadLnTimedOut;
	int FReadTimeout;
	int FRecvBufferSize;
	TIdSimpleBuffer* FRecvBuffer;
	int FSendBufferSize;
	Idiohandlersocket::TIdIOHandlerSocket* FSocket;
	TIdSimpleBuffer* FWriteBuffer;
	int FWriteBufferThreshhold;
	void __fastcall BufferRemoveNotify(System::TObject* ASender, const int ABytes);
	virtual void __fastcall DoOnDisconnected(void);
	virtual void __fastcall Notification(Classes::TComponent* AComponent, Classes::TOperation Operation
		);
	void __fastcall PerformCapture(System::TObject* ADest, /* out */ int &VLineCount, const AnsiString 
		ADelim, const bool AIsRFCMessage);
	virtual void __fastcall ResetConnection(void);
	void __fastcall SetIntercept(Idintercept::TIdConnectionIntercept* AValue);
	void __fastcall SetIOHandler(Idiohandler::TIdIOHandler* AValue);
	
public:
	virtual AnsiString __fastcall AllData();
	void __fastcall CancelWriteBuffer(void);
	void __fastcall Capture(Classes::TStream* ADest, const AnsiString ADelim, const bool AIsRFCMessage)
		/* overload */;
	void __fastcall Capture(Classes::TStream* ADest, /* out */ int &VLineCount, const AnsiString ADelim
		, const bool AIsRFCMessage)/* overload */;
	void __fastcall Capture(Classes::TStrings* ADest, const AnsiString ADelim, const bool AIsRFCMessage
		)/* overload */;
	void __fastcall Capture(Classes::TStrings* ADest, /* out */ int &VLineCount, const AnsiString ADelim
		, const bool AIsRFCMessage)/* overload */;
	virtual void __fastcall CheckForDisconnect(const bool ARaiseExceptionIfDisconnected, const bool AIgnoreBuffer
		);
	virtual void __fastcall CheckForGracefulDisconnect(const bool ARaiseExceptionIfDisconnected);
	virtual short __fastcall CheckResponse(const short AResponse, const short * AAllowedResponses, const 
		int AAllowedResponses_Size);
	void __fastcall ClearWriteBuffer(void);
	void __fastcall CloseWriteBuffer(void);
	virtual bool __fastcall Connected(void);
	__fastcall virtual TIdTCPConnection(Classes::TComponent* AOwner);
	AnsiString __fastcall CurrentReadBuffer();
	__fastcall virtual ~TIdTCPConnection(void);
	virtual void __fastcall Disconnect(void);
	virtual void __fastcall DisconnectSocket(void);
	void __fastcall FlushWriteBuffer(const int AByteCount);
	void __fastcall GetInternalResponse(void);
	virtual short __fastcall GetResponse(const short * AAllowedResponses, const int AAllowedResponses_Size
		)/* overload */;
	short __fastcall GetResponse(const short AAllowedResponse)/* overload */;
	__property Idrfcreply::TIdRFCReply* Greeting = {read=FGreeting, write=FGreeting};
	AnsiString __fastcall InputLn(const AnsiString AMask, bool AEcho, int ATabWidth, int AMaxLineLength
		);
	void __fastcall OpenWriteBuffer(const int AThreshhold);
	virtual void __fastcall RaiseExceptionForLastCmdResult(void)/* overload */;
	virtual void __fastcall RaiseExceptionForLastCmdResult(TMetaClass* AException)/* overload */;
	void __fastcall ReadBuffer(void *ABuffer, const int AByteCount);
	unsigned __fastcall ReadCardinal(const bool AConvert);
	char __fastcall ReadChar(void);
	virtual int __fastcall ReadFromStack(const bool ARaiseExceptionIfDisconnected, int ATimeout, const 
		bool ARaiseExceptionOnTimeout);
	int __fastcall ReadInteger(const bool AConvert);
	virtual AnsiString __fastcall ReadLn(AnsiString ATerminator, const int ATimeout, int AMaxLineLength
		);
	AnsiString __fastcall ReadLnWait(int AFailCount);
	short __fastcall ReadSmallInt(const bool AConvert);
	void __fastcall ReadStream(Classes::TStream* AStream, int AByteCount, const bool AReadUntilDisconnect
		);
	AnsiString __fastcall ReadString(const int ABytes);
	void __fastcall ReadStrings(Classes::TStrings* &AValue, int AReadLinesCount);
	short __fastcall SendCmd(const AnsiString AOut, const short AResponse)/* overload */;
	virtual short __fastcall SendCmd(const AnsiString AOut, const short * AResponse, const int AResponse_Size
		)/* overload */;
	AnsiString __fastcall WaitFor(const AnsiString AString);
	virtual void __fastcall Write(const AnsiString AOut);
	void __fastcall WriteBuffer(const void *ABuffer, int AByteCount, const bool AWriteNow);
	void __fastcall WriteCardinal(unsigned AValue, const bool AConvert);
	void __fastcall WriteHeader(Classes::TStrings* AHeader);
	void __fastcall WriteInteger(int AValue, const bool AConvert);
	virtual void __fastcall WriteLn(const AnsiString AOut);
	void __fastcall WriteRFCReply(Idrfcreply::TIdRFCReply* AReply);
	void __fastcall WriteRFCStrings(Classes::TStrings* AStrings);
	void __fastcall WriteSmallInt(short AValue, const bool AConvert);
	virtual void __fastcall WriteStream(Classes::TStream* AStream, const bool AAll, const bool AWriteByteCount
		, const int ASize);
	void __fastcall WriteStrings(Classes::TStrings* AValue, const bool AWriteLinesCount);
	virtual unsigned __fastcall WriteFile(const AnsiString AFile, const bool AEnableTransferFile);
	__property bool ClosedGracefully = {read=FClosedGracefully, nodefault};
	__property TIdManagedBuffer* InputBuffer = {read=FInputBuffer};
	__property Idrfcreply::TIdRFCReply* LastCmdResult = {read=FLastCmdResult};
	__property bool ReadLnSplit = {read=FReadLnSplit, nodefault};
	__property bool ReadLnTimedOut = {read=FReadLnTimedOut, nodefault};
	__property Idiohandlersocket::TIdIOHandlerSocket* Socket = {read=FSocket};
	
__published:
	__property bool ASCIIFilter = {read=FASCIIFilter, write=FASCIIFilter, default=0};
	__property Idintercept::TIdConnectionIntercept* Intercept = {read=FIntercept, write=SetIntercept};
	__property Idiohandler::TIdIOHandler* IOHandler = {read=FIOHandler, write=SetIOHandler};
	__property int MaxLineLength = {read=FMaxLineLength, write=FMaxLineLength, default=16384};
	__property Idglobal::TIdMaxLineAction MaxLineAction = {read=FMaxLineAction, write=FMaxLineAction, nodefault
		};
	__property int ReadTimeout = {read=FReadTimeout, write=FReadTimeout, default=0};
	__property int RecvBufferSize = {read=FRecvBufferSize, write=FRecvBufferSize, default=32768};
	__property int SendBufferSize = {read=FSendBufferSize, write=FSendBufferSize, default=32768};
	__property Classes::TNotifyEvent OnDisconnected = {read=FOnDisconnected, write=FOnDisconnected};
	__property OnWork ;
	__property OnWorkBegin ;
	__property OnWorkEnd ;
};


class DELPHICLASS EIdTCPConnectionError;
class PASCALIMPLEMENTATION EIdTCPConnectionError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTCPConnectionError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTCPConnectionError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTCPConnectionError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTCPConnectionError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTCPConnectionError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTCPConnectionError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTCPConnectionError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTCPConnectionError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTCPConnectionError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdObjectTypeNotSupported;
class PASCALIMPLEMENTATION EIdObjectTypeNotSupported : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdObjectTypeNotSupported(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdObjectTypeNotSupported(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdObjectTypeNotSupported(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdObjectTypeNotSupported(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdObjectTypeNotSupported(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdObjectTypeNotSupported(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, 
		Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdObjectTypeNotSupported(int Ident, int AHelpContext
		)/* overload */ : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdObjectTypeNotSupported(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTCPConnectionError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdObjectTypeNotSupported(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNotEnoughDataInBuffer;
class PASCALIMPLEMENTATION EIdNotEnoughDataInBuffer : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNotEnoughDataInBuffer(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNotEnoughDataInBuffer(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNotEnoughDataInBuffer(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNotEnoughDataInBuffer(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNotEnoughDataInBuffer(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNotEnoughDataInBuffer(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, 
		Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNotEnoughDataInBuffer(int Ident, int AHelpContext
		)/* overload */ : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNotEnoughDataInBuffer(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPConnectionError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNotEnoughDataInBuffer(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdInterceptPropIsNil;
class PASCALIMPLEMENTATION EIdInterceptPropIsNil : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdInterceptPropIsNil(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdInterceptPropIsNil(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdInterceptPropIsNil(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdInterceptPropIsNil(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdInterceptPropIsNil(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdInterceptPropIsNil(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdInterceptPropIsNil(int Ident, int AHelpContext)/* overload */
		 : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdInterceptPropIsNil(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPConnectionError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdInterceptPropIsNil(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdInterceptPropInvalid;
class PASCALIMPLEMENTATION EIdInterceptPropInvalid : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdInterceptPropInvalid(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdInterceptPropInvalid(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdInterceptPropInvalid(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdInterceptPropInvalid(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdInterceptPropInvalid(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdInterceptPropInvalid(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, 
		Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdInterceptPropInvalid(int Ident, int AHelpContext
		)/* overload */ : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdInterceptPropInvalid(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPConnectionError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdInterceptPropInvalid(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdIOHandlerPropInvalid;
class PASCALIMPLEMENTATION EIdIOHandlerPropInvalid : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIOHandlerPropInvalid(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIOHandlerPropInvalid(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIOHandlerPropInvalid(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIOHandlerPropInvalid(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIOHandlerPropInvalid(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIOHandlerPropInvalid(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, 
		Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIOHandlerPropInvalid(int Ident, int AHelpContext
		)/* overload */ : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIOHandlerPropInvalid(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPConnectionError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIOHandlerPropInvalid(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNoDataToRead;
class PASCALIMPLEMENTATION EIdNoDataToRead : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNoDataToRead(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNoDataToRead(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNoDataToRead(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNoDataToRead(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNoDataToRead(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNoDataToRead(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNoDataToRead(int Ident, int AHelpContext)/* overload */
		 : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNoDataToRead(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPConnectionError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNoDataToRead(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNotConnected;
class PASCALIMPLEMENTATION EIdNotConnected : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNotConnected(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNotConnected(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNotConnected(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNotConnected(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNotConnected(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNotConnected(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNotConnected(int Ident, int AHelpContext)/* overload */
		 : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNotConnected(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPConnectionError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNotConnected(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdFileNotFound;
class PASCALIMPLEMENTATION EIdFileNotFound : public EIdTCPConnectionError 
{
	typedef EIdTCPConnectionError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdFileNotFound(const AnsiString Msg) : EIdTCPConnectionError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdFileNotFound(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPConnectionError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdFileNotFound(int Ident)/* overload */ : EIdTCPConnectionError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdFileNotFound(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : EIdTCPConnectionError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdFileNotFound(const AnsiString Msg, int AHelpContext
		) : EIdTCPConnectionError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdFileNotFound(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdTCPConnectionError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdFileNotFound(int Ident, int AHelpContext)/* overload */
		 : EIdTCPConnectionError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdFileNotFound(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPConnectionError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdFileNotFound(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Word GRecvBufferSizeDefault = 0x8000;
static const Word GSendBufferSizeDefault = 0x8000;
static const Word IdMaxLineLengthDefault = 0x4000;
static const Word IdInBufCacheSizeDefault = 0x8000;
static const Shortint IdDefTimeout = 0x0;

}	/* namespace Idtcpconnection */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtcpconnection;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTCPConnection
