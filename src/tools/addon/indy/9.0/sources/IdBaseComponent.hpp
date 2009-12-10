// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdBaseComponent.pas' rev: 5.00

#ifndef IdBaseComponentHPP
#define IdBaseComponentHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idbasecomponent
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdBaseComponent;
class PASCALIMPLEMENTATION TIdBaseComponent : public Classes::TComponent 
{
	typedef Classes::TComponent inherited;
	
public:
	AnsiString __fastcall GetVersion();
	__property AnsiString Version = {read=GetVersion};
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdBaseComponent(Classes::TComponent* AOwner) : Classes::TComponent(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdBaseComponent(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idbasecomponent */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idbasecomponent;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdBaseComponent
