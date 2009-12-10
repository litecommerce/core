// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCoderXXE.pas' rev: 5.00

#ifndef IdCoderXXEHPP
#define IdCoderXXEHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdCoderUUE.hpp>	// Pascal unit
#include <IdCoder3to4.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcoderxxe
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdDecoderXXE;
class PASCALIMPLEMENTATION TIdDecoderXXE : public Idcoderuue::TIdDecoderUUEBase 
{
	typedef Idcoderuue::TIdDecoderUUEBase inherited;
	
public:
	__fastcall virtual TIdDecoderXXE(Classes::TComponent* AOwner);
public:
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdDecoderXXE(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdEncoderXXE;
class PASCALIMPLEMENTATION TIdEncoderXXE : public Idcoderuue::TIdEncoderUUEBase 
{
	typedef Idcoderuue::TIdEncoderUUEBase inherited;
	
public:
	__fastcall virtual TIdEncoderXXE(Classes::TComponent* AOwner);
public:
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdEncoderXXE(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString GXXECodeTable;
extern PACKAGE Byte GXXEDecodeTable[127];

}	/* namespace Idcoderxxe */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcoderxxe;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCoderXXE
