// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCoderMIME.pas' rev: 5.00

#ifndef IdCoderMIMEHPP
#define IdCoderMIMEHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdCoder3to4.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcodermime
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdEncoderMIME;
class PASCALIMPLEMENTATION TIdEncoderMIME : public Idcoder3to4::TIdEncoder3to4 
{
	typedef Idcoder3to4::TIdEncoder3to4 inherited;
	
public:
	__fastcall virtual TIdEncoderMIME(Classes::TComponent* AOwner);
public:
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdEncoderMIME(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdDecoderMIME;
class PASCALIMPLEMENTATION TIdDecoderMIME : public Idcoder3to4::TIdDecoder4to3 
{
	typedef Idcoder3to4::TIdDecoder4to3 inherited;
	
public:
	__fastcall virtual TIdDecoderMIME(Classes::TComponent* AOwner);
public:
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdDecoderMIME(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString GBase64CodeTable;
extern PACKAGE Byte GBase64DecodeTable[127];

}	/* namespace Idcodermime */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcodermime;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCoderMIME
