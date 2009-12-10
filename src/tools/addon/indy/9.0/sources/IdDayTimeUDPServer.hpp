// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdDayTimeUDPServer.pas' rev: 5.00

#ifndef IdDayTimeUDPServerHPP
#define IdDayTimeUDPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <IdUDPServer.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Iddaytimeudpserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdDayTimeUDPServer;
class PASCALIMPLEMENTATION TIdDayTimeUDPServer : public Idudpserver::TIdUDPServer 
{
	typedef Idudpserver::TIdUDPServer inherited;
	
protected:
	AnsiString FTimeZone;
	virtual void __fastcall DoUDPRead(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding
		);
	
public:
	__fastcall virtual TIdDayTimeUDPServer(Classes::TComponent* axOwner);
	
__published:
	__property AnsiString TimeZone = {read=FTimeZone, write=FTimeZone};
	__property DefaultPort ;
public:
	#pragma option push -w-inl
	/* TIdUDPServer.Destroy */ inline __fastcall virtual ~TIdDayTimeUDPServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Iddaytimeudpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Iddaytimeudpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdDayTimeUDPServer
