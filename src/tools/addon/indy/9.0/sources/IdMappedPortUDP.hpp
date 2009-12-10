// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMappedPortUDP.pas' rev: 5.00

#ifndef IdMappedPortUDPHPP
#define IdMappedPortUDPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdUDPServer.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmappedportudp
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdMappedPortUDP;
class PASCALIMPLEMENTATION TIdMappedPortUDP : public Idudpserver::TIdUDPServer 
{
	typedef Idudpserver::TIdUDPServer inherited;
	
protected:
	int FMappedPort;
	AnsiString FMappedHost;
	Classes::TNotifyEvent FOnRequest;
	virtual void __fastcall DoRequestNotify(void);
	
public:
	__fastcall virtual TIdMappedPortUDP(Classes::TComponent* AOwner);
	virtual void __fastcall DoUDPRead(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding
		);
	
__published:
	__property AnsiString MappedHost = {read=FMappedHost, write=FMappedHost};
	__property Classes::TNotifyEvent OnRequest = {read=FOnRequest, write=FOnRequest};
public:
	#pragma option push -w-inl
	/* TIdUDPServer.Destroy */ inline __fastcall virtual ~TIdMappedPortUDP(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idmappedportudp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmappedportudp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMappedPortUDP
