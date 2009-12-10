// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIPMCastClient.pas' rev: 5.00

#ifndef IdIPMCastClientHPP
#define IdIPMCastClientHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdException.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdIPMCastBase.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idipmcastclient
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TIPMCastReadEvent)(System::TObject* Sender, Classes::TStream* AData
	, Idsockethandle::TIdSocketHandle* ABinding);

class DELPHICLASS TIdIPMCastListenerThread;
class DELPHICLASS TIdIPMCastClient;
class PASCALIMPLEMENTATION TIdIPMCastClient : public Idipmcastbase::TIdIPMCastBase 
{
	typedef Idipmcastbase::TIdIPMCastBase inherited;
	
protected:
	Idsockethandle::TIdSocketHandles* FBindings;
	int FBufferSize;
	Idsockethandle::TIdSocketHandle* FCurrentBinding;
	TIdIPMCastListenerThread* FListenerThread;
	TIPMCastReadEvent FOnIPMCastRead;
	bool FThreadedEvent;
	virtual void __fastcall CloseBinding(void);
	virtual void __fastcall DoIPMCastRead(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding
		);
	virtual bool __fastcall GetActive(void);
	virtual Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void);
	int __fastcall GetDefaultPort(void);
	void __fastcall PacketReceived(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding);
		
	void __fastcall SetBindings(const Idsockethandle::TIdSocketHandles* Value);
	void __fastcall SetDefaultPort(const int AValue);
	
public:
	__fastcall virtual TIdIPMCastClient(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdIPMCastClient(void);
	
__published:
	__property Active ;
	__property Idsockethandle::TIdSocketHandles* Bindings = {read=FBindings, write=SetBindings};
	__property int BufferSize = {read=FBufferSize, write=FBufferSize, default=8192};
	__property int DefaultPort = {read=GetDefaultPort, write=SetDefaultPort, nodefault};
	__property MulticastGroup ;
	__property TIPMCastReadEvent OnIPMCastRead = {read=FOnIPMCastRead, write=FOnIPMCastRead};
	__property bool ThreadedEvent = {read=FThreadedEvent, write=FThreadedEvent, default=0};
};


class PASCALIMPLEMENTATION TIdIPMCastListenerThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	Idsockethandle::TIdSocketHandle* IncomingData;
	int FAcceptWait;
	Classes::TMemoryStream* FBuffer;
	int FBufferSize;
	
public:
	TIdIPMCastClient* FServer;
	__fastcall TIdIPMCastListenerThread(TIdIPMCastClient* Owner);
	__fastcall virtual ~TIdIPMCastListenerThread(void);
	virtual void __fastcall Run(void);
	void __fastcall IPMCastRead(void);
	__property int AcceptWait = {read=FAcceptWait, write=FAcceptWait, nodefault};
};


//-- var, const, procedure ---------------------------------------------------
static const bool DEF_IMP_THREADEDEVENT = false;

}	/* namespace Idipmcastclient */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idipmcastclient;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIPMCastClient
