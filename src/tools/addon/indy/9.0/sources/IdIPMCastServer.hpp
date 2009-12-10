// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIPMCastServer.pas' rev: 5.00

#ifndef IdIPMCastServerHPP
#define IdIPMCastServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdIPMCastBase.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idipmcastserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdIPMCastServer;
class PASCALIMPLEMENTATION TIdIPMCastServer : public Idipmcastbase::TIdIPMCastBase 
{
	typedef Idipmcastbase::TIdIPMCastBase inherited;
	
protected:
	Idsockethandle::TIdSocketHandle* FBinding;
	bool FLoopback;
	Byte FTimeToLive;
	virtual void __fastcall CloseBinding(void);
	virtual bool __fastcall GetActive(void);
	virtual Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void);
	virtual void __fastcall Loaded(void);
	void __fastcall MulticastBuffer(AnsiString AHost, const int APort, void *ABuffer, const int AByteCount
		);
	virtual void __fastcall SetLoopback(const bool AValue);
	virtual void __fastcall SetTTL(const Byte Value);
	virtual void __fastcall SetTTLOption(Idsockethandle::TIdSocketHandle* InBinding, const Byte Value);
		
	
public:
	__fastcall virtual TIdIPMCastServer(Classes::TComponent* AOwner);
	void __fastcall Send(AnsiString AData);
	void __fastcall SendBuffer(void *ABuffer, const int AByteCount);
	__fastcall virtual ~TIdIPMCastServer(void);
	__property Idsockethandle::TIdSocketHandle* Binding = {read=GetBinding};
	
__published:
	__property Active ;
	__property bool Loopback = {read=FLoopback, write=SetLoopback, default=1};
	__property MulticastGroup ;
	__property Port ;
	__property Byte TimeToLive = {read=FTimeToLive, write=SetTTL, default=1};
};


//-- var, const, procedure ---------------------------------------------------
static const bool DEF_IMP_LOOPBACK = true;
static const Shortint DEF_IMP_TTL = 0x1;

}	/* namespace Idipmcastserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idipmcastserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIPMCastServer
