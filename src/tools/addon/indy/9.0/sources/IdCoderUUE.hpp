// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCoderUUE.pas' rev: 5.00

#ifndef IdCoderUUEHPP
#define IdCoderUUEHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdCoder3to4.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcoderuue
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdDecoderUUEBase;
class PASCALIMPLEMENTATION TIdDecoderUUEBase : public Idcoder3to4::TIdDecoder4to3 
{
	typedef Idcoder3to4::TIdDecoder4to3 inherited;
	
public:
	virtual void __fastcall DecodeToStream(AnsiString AIn, Classes::TStream* ADest);
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdDecoderUUEBase(Classes::TComponent* AOwner) : 
		Idcoder3to4::TIdDecoder4to3(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdDecoderUUEBase(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdDecoderUUE;
class PASCALIMPLEMENTATION TIdDecoderUUE : public TIdDecoderUUEBase 
{
	typedef TIdDecoderUUEBase inherited;
	
public:
	__fastcall virtual TIdDecoderUUE(Classes::TComponent* AOwner);
	virtual void __fastcall DecodeToStream(AnsiString AIn, Classes::TStream* ADest);
public:
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdDecoderUUE(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdEncoderUUEBase;
class PASCALIMPLEMENTATION TIdEncoderUUEBase : public Idcoder3to4::TIdEncoder3to4 
{
	typedef Idcoder3to4::TIdEncoder3to4 inherited;
	
public:
	virtual AnsiString __fastcall Encode(Classes::TStream* ASrcStream, const int ABytes)/* overload */;
		
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdEncoderUUEBase(Classes::TComponent* AOwner) : 
		Idcoder3to4::TIdEncoder3to4(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdEncoderUUEBase(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdEncoderUUE;
class PASCALIMPLEMENTATION TIdEncoderUUE : public TIdEncoderUUEBase 
{
	typedef TIdEncoderUUEBase inherited;
	
public:
	__fastcall virtual TIdEncoderUUE(Classes::TComponent* AOwner);
public:
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdEncoderUUE(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString GUUECodeTable;
extern PACKAGE Byte GUUEDecodeTable[127];

}	/* namespace Idcoderuue */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcoderuue;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCoderUUE
