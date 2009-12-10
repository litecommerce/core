// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRawClient.pas' rev: 5.00

#ifndef IdRawClientHPP
#define IdRawClientHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdRawBase.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idrawclient
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdRawClient;
class PASCALIMPLEMENTATION TIdRawClient : public Idrawbase::TIdRawBase 
{
	typedef Idrawbase::TIdRawBase inherited;
	
__published:
	__property ReceiveTimeout ;
	__property Host ;
	__property Port ;
	__property Protocol ;
public:
	#pragma option push -w-inl
	/* TIdRawBase.Create */ inline __fastcall virtual TIdRawClient(Classes::TComponent* AOwner) : Idrawbase::TIdRawBase(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdRawBase.Destroy */ inline __fastcall virtual ~TIdRawClient(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idrawclient */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idrawclient;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRawClient
