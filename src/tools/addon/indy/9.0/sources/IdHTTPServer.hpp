// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdHTTPServer.pas' rev: 5.00

#ifndef IdHTTPServerHPP
#define IdHTTPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdCustomHTTPServer.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idhttpserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdHTTPServer;
class PASCALIMPLEMENTATION TIdHTTPServer : public Idcustomhttpserver::TIdCustomHTTPServer 
{
	typedef Idcustomhttpserver::TIdCustomHTTPServer inherited;
	
__published:
	__property OnCreatePostStream ;
	__property OnCommandGet ;
public:
	#pragma option push -w-inl
	/* TIdCustomHTTPServer.Create */ inline __fastcall virtual TIdHTTPServer(Classes::TComponent* AOwner
		) : Idcustomhttpserver::TIdCustomHTTPServer(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdCustomHTTPServer.Destroy */ inline __fastcall virtual ~TIdHTTPServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idhttpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idhttpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdHTTPServer
