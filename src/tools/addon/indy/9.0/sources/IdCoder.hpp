// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCoder.pas' rev: 5.00

#ifndef IdCoderHPP
#define IdCoderHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdGlobal.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcoder
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdEncoder;
class PASCALIMPLEMENTATION TIdEncoder : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
public:
	AnsiString __fastcall Encode(const AnsiString ASrc)/* overload */;
	virtual AnsiString __fastcall Encode(Classes::TStream* ASrcStream, const int ABytes) = 0 /* overload */
		;
	/*         class method */ static AnsiString __fastcall EncodeString(TMetaClass* vmt, const AnsiString 
		AIn);
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdEncoder(Classes::TComponent* AOwner) : Idbasecomponent::TIdBaseComponent(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdEncoder(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdDecoder;
class PASCALIMPLEMENTATION TIdDecoder : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
public:
	/*         class method */ static AnsiString __fastcall DecodeString(TMetaClass* vmt, AnsiString AIn
		);
	AnsiString __fastcall DecodeToString(const AnsiString AIn);
	virtual void __fastcall DecodeToStream(AnsiString AIn, Classes::TStream* ADest) = 0 ;
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdDecoder(Classes::TComponent* AOwner) : Idbasecomponent::TIdBaseComponent(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdDecoder(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idcoder */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcoder;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCoder
