// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdStack.pas' rev: 5.00

#ifndef IdStackHPP
#define IdStackHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdGlobal.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idstack
{
//-- type declarations -------------------------------------------------------
typedef unsigned __fastcall (*TIdServeFile)(int ASocket, AnsiString AFileName);

#pragma pack(push, 1)
struct TIdSunB
{
	System::Byte s_b1;
	System::Byte s_b2;
	System::Byte s_b3;
	System::Byte s_b4;
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct TIdSunW
{
	Word s_w1;
	Word s_w2;
} ;
#pragma pack(pop)

struct TIdInAddr;
typedef TIdInAddr *PIdInAddr;

struct TIdInAddr
{
	
	union
	{
		struct 
		{
			unsigned S_addr;
			
		};
		struct 
		{
			TIdSunW S_un_w;
			
		};
		struct 
		{
			TIdSunB S_un_b;
			
		};
		
	};
} ;

#pragma option push -b-
enum TIdIPType { Id_IPInvalid, Id_IPDotted, Id_IPNumeric };
#pragma option pop

typedef TIdIPType *PIdIPType;

#pragma option push -b-
enum TIdIPClass { Id_IPClassUnkn, Id_IPClassA, Id_IPClassB, Id_IPClassC, Id_IPClassD, Id_IPClassE };
	
#pragma option pop

typedef TIdIPClass *PIdIPClass;

typedef TMetaClass*TIdSocketListClass;

class DELPHICLASS TIdSocketList;
class PASCALIMPLEMENTATION TIdSocketList : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	virtual int __fastcall GetItem(int AIndex) = 0 ;
	
public:
	virtual void __fastcall Add(int AHandle) = 0 ;
	/*         class method */ static TIdSocketList* __fastcall CreateSocketList(TMetaClass* vmt);
	virtual void __fastcall Remove(int AHandle) = 0 ;
	virtual int __fastcall Count(void) = 0 ;
	__property int Items[int AIndex] = {read=GetItem/*, default*/};
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdSocketList(void) : System::TObject() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdSocketList(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdStack;
class PASCALIMPLEMENTATION TIdStack : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	int FLastError;
	AnsiString FLocalAddress;
	Classes::TStrings* FLocalAddresses;
	virtual void __fastcall PopulateLocalAddresses(void) = 0 ;
	virtual AnsiString __fastcall WSGetLocalAddress(void) = 0 ;
	virtual Classes::TStrings* __fastcall WSGetLocalAddresses(void) = 0 ;
	
public:
	bool __fastcall CheckForSocketError(const int AResult)/* overload */;
	bool __fastcall CheckForSocketError(const int AResult, const int * AIgnore, const int AIgnore_Size)
		/* overload */;
	__fastcall virtual TIdStack(void);
	__fastcall virtual ~TIdStack(void);
	/*         class method */ static TIdStack* __fastcall CreateStack(TMetaClass* vmt);
	int __fastcall CreateSocketHandle(const int ASocketType, const int AProtocol);
	bool __fastcall GetIPInfo(const AnsiString AIP, Idglobal::PByte VB1, Idglobal::PByte VB2, Idglobal::PByte 
		VB3, Idglobal::PByte VB4, PIdIPType VType, PIdIPClass VClass);
	TIdIPType __fastcall GetIPType(const AnsiString AIP);
	TIdIPClass __fastcall GetIPClass(const AnsiString AIP);
	bool __fastcall IsIP(const AnsiString AIP);
	bool __fastcall IPIsType(const AnsiString AIP, const TIdIPType AType)/* overload */;
	bool __fastcall IPIsType(const AnsiString AIP, const TIdIPType * ATypes, const int ATypes_Size)/* overload */
		;
	bool __fastcall IPIsClass(const AnsiString AIP, const TIdIPClass AClass)/* overload */;
	bool __fastcall IPIsClass(const AnsiString AIP, const TIdIPClass * AClasses, const int AClasses_Size
		)/* overload */;
	bool __fastcall IsDottedIP(const AnsiString AIP);
	bool __fastcall IsNumericIP(const AnsiString AIP);
	void __fastcall RaiseSocketError(const int AErr);
	AnsiString __fastcall ResolveHost(const AnsiString AHost);
	virtual int __fastcall WSAccept(int ASocket, AnsiString &VIP, int &VPort) = 0 ;
	virtual int __fastcall WSBind(int ASocket, const int AFamily, const AnsiString AIP, const int APort
		) = 0 ;
	virtual int __fastcall WSCloseSocket(int ASocket) = 0 ;
	virtual int __fastcall WSConnect(const int ASocket, const int AFamily, const AnsiString AIP, const 
		int APort) = 0 ;
	virtual AnsiString __fastcall WSGetHostByName(const AnsiString AHostName) = 0 ;
	virtual AnsiString __fastcall WSGetHostName(void) = 0 ;
	virtual AnsiString __fastcall WSGetHostByAddr(const AnsiString AAddress) = 0 ;
	virtual int __fastcall WSGetServByName(const AnsiString AServiceName) = 0 ;
	virtual Classes::TStrings* __fastcall WSGetServByPort(const int APortNumber) = 0 ;
	virtual Word __fastcall WSHToNs(Word AHostShort) = 0 ;
	virtual int __fastcall WSListen(int ASocket, int ABackLog) = 0 ;
	virtual Word __fastcall WSNToHs(Word ANetShort) = 0 ;
	virtual unsigned __fastcall WSHToNL(unsigned AHostLong) = 0 ;
	virtual unsigned __fastcall WSNToHL(unsigned ANetLong) = 0 ;
	virtual int __fastcall WSRecv(int ASocket, void *ABuffer, const int ABufferLength, const int AFlags
		) = 0 ;
	virtual int __fastcall WSRecvFrom(const int ASocket, void *ABuffer, const int ALength, const int AFlags
		, AnsiString &VIP, int &VPort) = 0 ;
	virtual int __fastcall WSSelect(Classes::TList* ARead, Classes::TList* AWrite, Classes::TList* AErrors
		, int ATimeout) = 0 ;
	virtual int __fastcall WSSend(int ASocket, void *ABuffer, const int ABufferLength, const int AFlags
		) = 0 ;
	virtual int __fastcall WSSendTo(int ASocket, void *ABuffer, const int ABufferLength, const int AFlags
		, const AnsiString AIP, const int APort) = 0 ;
	virtual int __fastcall WSSetSockOpt(int ASocket, int ALevel, int AOptName, char * AOptVal, int AOptLen
		) = 0 ;
	virtual int __fastcall WSSocket(int AFamily, int AStruct, int AProtocol) = 0 ;
	virtual int __fastcall WSShutdown(int ASocket, int AHow) = 0 ;
	virtual AnsiString __fastcall WSTranslateSocketErrorMsg(const int AErr);
	virtual int __fastcall WSGetLastError(void) = 0 ;
	virtual void __fastcall WSGetPeerName(int ASocket, int &AFamily, AnsiString &AIP, int &APort) = 0 ;
		
	virtual void __fastcall WSGetSockName(int ASocket, int &AFamily, AnsiString &AIP, int &APort) = 0 ;
		
	virtual int __fastcall WSGetSockOpt(int ASocket, int Alevel, int AOptname, char * AOptval, int &AOptlen
		) = 0 ;
	TIdInAddr __fastcall StringToTInAddr(AnsiString AIP);
	virtual AnsiString __fastcall TInAddrToString(void *AInAddr) = 0 ;
	virtual void __fastcall TranslateStringToTInAddr(AnsiString AIP, void *AInAddr) = 0 ;
	__property int LastError = {read=FLastError, nodefault};
	__property AnsiString LocalAddress = {read=WSGetLocalAddress};
	__property Classes::TStrings* LocalAddresses = {read=WSGetLocalAddresses};
};


typedef TMetaClass*TIdStackClass;

class DELPHICLASS EIdStackError;
class PASCALIMPLEMENTATION EIdStackError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdStackError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdStackError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdStackError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdStackError(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdStackError(const AnsiString Msg, int AHelpContext) : 
		Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdStackError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdStackError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdStackError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdStackError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdStackInitializationFailed;
class PASCALIMPLEMENTATION EIdStackInitializationFailed : public EIdStackError 
{
	typedef EIdStackError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdStackInitializationFailed(const AnsiString Msg) : EIdStackError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdStackInitializationFailed(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdStackError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdStackInitializationFailed(int Ident)/* overload */ : 
		EIdStackError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdStackInitializationFailed(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdStackError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdStackInitializationFailed(const AnsiString Msg, int 
		AHelpContext) : EIdStackError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdStackInitializationFailed(const AnsiString Msg, 
		const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdStackError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdStackInitializationFailed(int Ident, int AHelpContext
		)/* overload */ : EIdStackError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdStackInitializationFailed(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdStackError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdStackInitializationFailed(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdStackSetSizeExceeded;
class PASCALIMPLEMENTATION EIdStackSetSizeExceeded : public EIdStackError 
{
	typedef EIdStackError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdStackSetSizeExceeded(const AnsiString Msg) : EIdStackError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdStackSetSizeExceeded(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdStackError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdStackSetSizeExceeded(int Ident)/* overload */ : EIdStackError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdStackSetSizeExceeded(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdStackError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdStackSetSizeExceeded(const AnsiString Msg, int AHelpContext
		) : EIdStackError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdStackSetSizeExceeded(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdStackError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdStackSetSizeExceeded(int Ident, int AHelpContext
		)/* overload */ : EIdStackError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdStackSetSizeExceeded(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdStackError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdStackSetSizeExceeded(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdInvalidIPAddress;
class PASCALIMPLEMENTATION EIdInvalidIPAddress : public EIdStackError 
{
	typedef EIdStackError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdInvalidIPAddress(const AnsiString Msg) : EIdStackError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdInvalidIPAddress(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdStackError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdInvalidIPAddress(int Ident)/* overload */ : EIdStackError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdInvalidIPAddress(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdStackError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdInvalidIPAddress(const AnsiString Msg, int AHelpContext
		) : EIdStackError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdInvalidIPAddress(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdStackError(Msg, Args, Args_Size, AHelpContext)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdInvalidIPAddress(int Ident, int AHelpContext)/* overload */
		 : EIdStackError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdInvalidIPAddress(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdStackError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdInvalidIPAddress(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE TIdStack* GStack;
extern PACKAGE TMetaClass*GStackClass;
extern PACKAGE TIdServeFile GServeFileProc;
extern PACKAGE TMetaClass*GSocketListClass;

}	/* namespace Idstack */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idstack;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdStack
