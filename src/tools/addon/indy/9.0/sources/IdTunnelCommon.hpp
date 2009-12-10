// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTunnelCommon.pas' rev: 5.00

#ifndef IdTunnelCommonHPP
#define IdTunnelCommonHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdResourceStrings.hpp>	// Pascal unit
#include <IdCoder.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdHashCRC.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtunnelcommon
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdStatisticsOperation { soIncrease, soDecrease };
#pragma option pop

struct TIdHeader
{
	Word CRC16;
	Word MsgType;
	Word MsgLen;
	Word UserId;
	Word Port;
	Idstack::TIdInAddr IpAddr;
} ;

class DELPHICLASS TReceiver;
class PASCALIMPLEMENTATION TReceiver : public System::TObject 
{
	typedef System::TObject inherited;
	
private:
	int fiPrenosLen;
	int fiMsgLen;
	AnsiString fsData;
	bool fbNewMessage;
	bool fCRCFailed;
	Syncobjs::TCriticalSection* Locker;
	Idhashcrc::TIdHashCRC16* CRC16Calculator;
	bool __fastcall FNewMessage(void);
	void __fastcall SetData(const AnsiString Value);
	
public:
	char *pBuffer;
	int HeaderLen;
	TIdHeader Header;
	Word MsgLen;
	bool TypeDetected;
	char *Msg;
	__property AnsiString Data = {read=fsData, write=SetData};
	__property bool NewMessage = {read=FNewMessage, nodefault};
	__property bool CRCFailed = {read=fCRCFailed, nodefault};
	void __fastcall ShiftData(void);
	__fastcall TReceiver(void);
	__fastcall virtual ~TReceiver(void);
};


class DELPHICLASS TSender;
class PASCALIMPLEMENTATION TSender : public System::TObject 
{
	typedef System::TObject inherited;
	
public:
	TIdHeader Header;
	Word DataLen;
	int HeaderLen;
	char *pMsg;
	Syncobjs::TCriticalSection* Locker;
	Idhashcrc::TIdHashCRC16* CRC16Calculator;
	AnsiString Msg;
	void __fastcall PrepareMsg(TIdHeader &Header, char * buffer, int buflen);
	__fastcall TSender(void);
	__fastcall virtual ~TSender(void);
};


class DELPHICLASS TLogger;
class PASCALIMPLEMENTATION TLogger : public System::TObject 
{
	typedef System::TObject inherited;
	
private:
	Syncobjs::TCriticalSection* OnlyOneThread;
	TextFile fLogFile;
	bool fbActive;
	
public:
	__property bool Active = {read=fbActive, default=0};
	void __fastcall LogEvent(AnsiString Msg);
	__fastcall TLogger(AnsiString LogFileName);
	__fastcall virtual ~TLogger(void);
};


typedef void __fastcall (__closure *TSendMsgEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString &CustomMsg
	);

typedef void __fastcall (__closure *TSendTrnEvent)(Idtcpserver::TIdPeerThread* Thread, TIdHeader &Header
	, AnsiString &CustomMsg);

typedef void __fastcall (__closure *TSendTrnEventC)(TIdHeader &Header, AnsiString &CustomMsg);

typedef void __fastcall (__closure *TTunnelEventC)(TReceiver* Receiver);

typedef void __fastcall (__closure *TSendMsgEventC)(AnsiString &CustomMsg);

class DELPHICLASS EIdTunnelException;
class PASCALIMPLEMENTATION EIdTunnelException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelException(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelTransformErrorBeforeSend;
class PASCALIMPLEMENTATION EIdTunnelTransformErrorBeforeSend : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelTransformErrorBeforeSend(const AnsiString Msg) : 
		EIdTunnelException(Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelTransformErrorBeforeSend(const AnsiString Msg, 
		const System::TVarRec * Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelTransformErrorBeforeSend(int Ident)/* overload */
		 : EIdTunnelException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelTransformErrorBeforeSend(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelTransformErrorBeforeSend(const AnsiString Msg
		, int AHelpContext) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelTransformErrorBeforeSend(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg
		, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelTransformErrorBeforeSend(int Ident, int AHelpContext
		)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelTransformErrorBeforeSend(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTunnelException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelTransformErrorBeforeSend(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelTransformError;
class PASCALIMPLEMENTATION EIdTunnelTransformError : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelTransformError(const AnsiString Msg) : EIdTunnelException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelTransformError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelTransformError(int Ident)/* overload */ : EIdTunnelException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelTransformError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelTransformError(const AnsiString Msg, int AHelpContext
		) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelTransformError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelTransformError(int Ident, int AHelpContext
		)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelTransformError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTunnelException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelTransformError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelConnectToMasterFailed;
class PASCALIMPLEMENTATION EIdTunnelConnectToMasterFailed : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelConnectToMasterFailed(const AnsiString Msg) : EIdTunnelException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelConnectToMasterFailed(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelConnectToMasterFailed(int Ident)/* overload */
		 : EIdTunnelException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelConnectToMasterFailed(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelConnectToMasterFailed(const AnsiString Msg, int 
		AHelpContext) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelConnectToMasterFailed(const AnsiString Msg
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelConnectToMasterFailed(int Ident, int AHelpContext
		)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelConnectToMasterFailed(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTunnelException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelConnectToMasterFailed(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelDontAllowConnections;
class PASCALIMPLEMENTATION EIdTunnelDontAllowConnections : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelDontAllowConnections(const AnsiString Msg) : EIdTunnelException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelDontAllowConnections(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelDontAllowConnections(int Ident)/* overload */ : 
		EIdTunnelException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelDontAllowConnections(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelDontAllowConnections(const AnsiString Msg, int 
		AHelpContext) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelDontAllowConnections(const AnsiString Msg, 
		const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelDontAllowConnections(int Ident, int AHelpContext
		)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelDontAllowConnections(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTunnelException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelDontAllowConnections(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelCRCFailed;
class PASCALIMPLEMENTATION EIdTunnelCRCFailed : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelCRCFailed(const AnsiString Msg) : EIdTunnelException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelCRCFailed(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelCRCFailed(int Ident)/* overload */ : EIdTunnelException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelCRCFailed(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelCRCFailed(const AnsiString Msg, int AHelpContext
		) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelCRCFailed(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelCRCFailed(int Ident, int AHelpContext)/* overload */
		 : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelCRCFailed(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTunnelException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelCRCFailed(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelMessageTypeRecognitionError;
class PASCALIMPLEMENTATION EIdTunnelMessageTypeRecognitionError : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelMessageTypeRecognitionError(const AnsiString Msg)
		 : EIdTunnelException(Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelMessageTypeRecognitionError(const AnsiString Msg
		, const System::TVarRec * Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelMessageTypeRecognitionError(int Ident)/* overload */
		 : EIdTunnelException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelMessageTypeRecognitionError(int Ident, const 
		System::TVarRec * Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelMessageTypeRecognitionError(const AnsiString 
		Msg, int AHelpContext) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelMessageTypeRecognitionError(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg
		, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelMessageTypeRecognitionError(int Ident, int 
		AHelpContext)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelMessageTypeRecognitionError(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTunnelException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelMessageTypeRecognitionError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelMessageHandlingFailed;
class PASCALIMPLEMENTATION EIdTunnelMessageHandlingFailed : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelMessageHandlingFailed(const AnsiString Msg) : EIdTunnelException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelMessageHandlingFailed(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelMessageHandlingFailed(int Ident)/* overload */
		 : EIdTunnelException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelMessageHandlingFailed(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelMessageHandlingFailed(const AnsiString Msg, int 
		AHelpContext) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelMessageHandlingFailed(const AnsiString Msg
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelMessageHandlingFailed(int Ident, int AHelpContext
		)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelMessageHandlingFailed(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTunnelException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelMessageHandlingFailed(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelInterpretationOfMessageFailed;
class PASCALIMPLEMENTATION EIdTunnelInterpretationOfMessageFailed : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(const AnsiString Msg
		) : EIdTunnelException(Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(int Ident)/* overload */
		 : EIdTunnelException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(int Ident, const 
		System::TVarRec * Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(const AnsiString 
		Msg, int AHelpContext) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg
		, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(int Ident, int 
		AHelpContext)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelInterpretationOfMessageFailed(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTunnelException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelInterpretationOfMessageFailed(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTunnelCustomMessageInterpretationFailure;
class PASCALIMPLEMENTATION EIdTunnelCustomMessageInterpretationFailure : public EIdTunnelException 
{
	typedef EIdTunnelException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(const AnsiString 
		Msg) : EIdTunnelException(Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size) : EIdTunnelException(Msg, Args, Args_Size)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(int Ident)/* overload */
		 : EIdTunnelException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(int Ident
		, const System::TVarRec * Args, const int Args_Size)/* overload */ : EIdTunnelException(Ident, Args
		, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(const AnsiString 
		Msg, int AHelpContext) : EIdTunnelException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTunnelException(Msg
		, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(int Ident
		, int AHelpContext)/* overload */ : EIdTunnelException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTunnelCustomMessageInterpretationFailure(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTunnelException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTunnelCustomMessageInterpretationFailure(void) { }
		
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Word BUFFERLEN = 0x4000;
static const Shortint NumberOfConnectionsType = 0x1;
static const Shortint NumberOfPacketsType = 0x2;
static const Shortint CompressionRatioType = 0x3;
static const Shortint CompressedBytesType = 0x4;
static const Shortint BytesReadType = 0x5;
static const Shortint BytesWriteType = 0x6;
static const Shortint NumberOfClientsType = 0x7;
static const Shortint NumberOfSlavesType = 0x8;
static const Shortint NumberOfServicesType = 0x9;
static const Shortint tmError = 0x0;
static const Shortint tmData = 0x1;
static const Shortint tmDisconnect = 0x2;
static const Shortint tmConnect = 0x3;
static const Shortint tmCustom = 0x63;

}	/* namespace Idtunnelcommon */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtunnelcommon;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTunnelCommon
