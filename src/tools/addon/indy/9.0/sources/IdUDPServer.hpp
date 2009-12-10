// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdUDPServer.pas' rev: 5.00

#ifndef IdUDPServerHPP
#define IdUDPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idudpserver
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TUDPReadEvent)(System::TObject* Sender, Classes::TStream* AData, 
	Idsockethandle::TIdSocketHandle* ABinding);

class DELPHICLASS TIdUDPListenerThread;
class DELPHICLASS TIdUDPServer;
class PASCALIMPLEMENTATION TIdUDPServer : public Idudpbase::TIdUDPBase 
{
	typedef Idudpbase::TIdUDPBase inherited;
	
protected:
	Idsockethandle::TIdSocketHandles* FBindings;
	Idsockethandle::TIdSocketHandle* FCurrentBinding;
	TIdUDPListenerThread* FListenerThread;
	TUDPReadEvent FOnUDPRead;
	bool FThreadedEvent;
	int __fastcall GetDefaultPort(void);
	void __fastcall SetBindings(const Idsockethandle::TIdSocketHandles* Value);
	void __fastcall SetDefaultPort(const int AValue);
	void __fastcall PacketReceived(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding);
		
	virtual void __fastcall DoUDPRead(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding
		);
	virtual Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void);
	virtual void __fastcall CloseBinding(void);
	DYNAMIC void __fastcall BroadcastEnabledChanged(void);
	virtual bool __fastcall GetActive(void);
	
public:
	__fastcall virtual TIdUDPServer(Classes::TComponent* axOwner);
	__fastcall virtual ~TIdUDPServer(void);
	
__published:
	__property Idsockethandle::TIdSocketHandles* Bindings = {read=FBindings, write=SetBindings};
	__property int DefaultPort = {read=GetDefaultPort, write=SetDefaultPort, nodefault};
	__property TUDPReadEvent OnUDPRead = {read=FOnUDPRead, write=FOnUDPRead};
	__property bool ThreadedEvent = {read=FThreadedEvent, write=FThreadedEvent, default=0};
};


class PASCALIMPLEMENTATION TIdUDPListenerThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	Idsockethandle::TIdSocketHandle* IncomingData;
	int FAcceptWait;
	Classes::TMemoryStream* FBuffer;
	int FBufferSize;
	
public:
	TIdUDPServer* FServer;
	__fastcall TIdUDPListenerThread(const int ABufferSize, TIdUDPServer* Owner);
	__fastcall virtual ~TIdUDPListenerThread(void);
	virtual void __fastcall Run(void);
	void __fastcall UDPRead(void);
	__property int AcceptWait = {read=FAcceptWait, write=FAcceptWait, nodefault};
};


class DELPHICLASS EIdUDPServerException;
class PASCALIMPLEMENTATION EIdUDPServerException : public Idudpbase::EIdUDPException 
{
	typedef Idudpbase::EIdUDPException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdUDPServerException(const AnsiString Msg) : Idudpbase::EIdUDPException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdUDPServerException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idudpbase::EIdUDPException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdUDPServerException(int Ident)/* overload */ : Idudpbase::EIdUDPException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdUDPServerException(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idudpbase::EIdUDPException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdUDPServerException(const AnsiString Msg, int AHelpContext
		) : Idudpbase::EIdUDPException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdUDPServerException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idudpbase::EIdUDPException(Msg, Args, Args_Size, 
		AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdUDPServerException(int Ident, int AHelpContext)/* overload */
		 : Idudpbase::EIdUDPException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdUDPServerException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idudpbase::EIdUDPException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdUDPServerException(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idudpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idudpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdUDPServer
