// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdUDPBase.pas' rev: 5.00

#ifndef IdUDPBaseHPP
#define IdUDPBaseHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idudpbase
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdUDPBase;
class PASCALIMPLEMENTATION TIdUDPBase : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	Idsockethandle::TIdSocketHandle* FBinding;
	int FBufferSize;
	bool FDsgnActive;
	AnsiString FHost;
	int FPort;
	int FReceiveTimeout;
	bool FBroadcastEnabled;
	DYNAMIC void __fastcall BroadcastEnabledChanged(void);
	virtual void __fastcall CloseBinding(void);
	virtual bool __fastcall GetActive(void);
	void __fastcall SetActive(const bool Value);
	void __fastcall SetBroadcastFlag(const bool Enabled, Idsockethandle::TIdSocketHandle* ABinding);
	void __fastcall SetBroadcastEnabled(const bool Value);
	virtual Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void);
	virtual void __fastcall Loaded(void);
	
public:
	__fastcall virtual TIdUDPBase(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdUDPBase(void);
	__property Idsockethandle::TIdSocketHandle* Binding = {read=GetBinding};
	void __fastcall Broadcast(const AnsiString AData, const int APort);
	int __fastcall ReceiveBuffer(void *ABuffer, const int ABufferSize, const int AMSec)/* overload */;
	int __fastcall ReceiveBuffer(void *ABuffer, const int ABufferSize, AnsiString &VPeerIP, int &VPeerPort
		, int AMSec)/* overload */;
	AnsiString __fastcall ReceiveString(const int AMSec)/* overload */;
	AnsiString __fastcall ReceiveString(AnsiString &VPeerIP, int &VPeerPort, const int AMSec)/* overload */
		;
	void __fastcall Send(AnsiString AHost, const int APort, const AnsiString AData);
	void __fastcall SendBuffer(AnsiString AHost, const int APort, void *ABuffer, const int AByteCount);
		
	__property int ReceiveTimeout = {read=FReceiveTimeout, write=FReceiveTimeout, default=-2};
	
__published:
	__property bool Active = {read=GetActive, write=SetActive, default=0};
	__property int BufferSize = {read=FBufferSize, write=FBufferSize, default=8192};
	__property bool BroadcastEnabled = {read=FBroadcastEnabled, write=SetBroadcastEnabled, default=0};
};


class DELPHICLASS EIdUDPException;
class PASCALIMPLEMENTATION EIdUDPException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdUDPException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdUDPException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdUDPException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdUDPException(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdUDPException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdUDPException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdUDPException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdUDPException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdUDPException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdUDPReceiveErrorZeroBytes;
class PASCALIMPLEMENTATION EIdUDPReceiveErrorZeroBytes : public EIdUDPException 
{
	typedef EIdUDPException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdUDPReceiveErrorZeroBytes(const AnsiString Msg) : EIdUDPException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdUDPReceiveErrorZeroBytes(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdUDPException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdUDPReceiveErrorZeroBytes(int Ident)/* overload */ : 
		EIdUDPException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdUDPReceiveErrorZeroBytes(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdUDPException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdUDPReceiveErrorZeroBytes(const AnsiString Msg, int 
		AHelpContext) : EIdUDPException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdUDPReceiveErrorZeroBytes(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdUDPException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdUDPReceiveErrorZeroBytes(int Ident, int AHelpContext
		)/* overload */ : EIdUDPException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdUDPReceiveErrorZeroBytes(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdUDPException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdUDPReceiveErrorZeroBytes(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Word ID_UDP_BUFFERSIZE = 0x2000;

}	/* namespace Idudpbase */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idudpbase;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdUDPBase
