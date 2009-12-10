// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdHash.pas' rev: 5.00

#ifndef IdHashHPP
#define IdHashHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idhash
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdHash;
class PASCALIMPLEMENTATION TIdHash : public System::TObject 
{
	typedef System::TObject inherited;
	
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHash(void) : System::TObject() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHash(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdHash16;
class PASCALIMPLEMENTATION TIdHash16 : public TIdHash 
{
	typedef TIdHash inherited;
	
public:
	Word __fastcall HashValue(const AnsiString ASrc)/* overload */;
	virtual Word __fastcall HashValue(Classes::TStream* AStream) = 0 /* overload */;
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHash16(void) : TIdHash() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHash16(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdHash32;
class PASCALIMPLEMENTATION TIdHash32 : public TIdHash 
{
	typedef TIdHash inherited;
	
public:
	unsigned __fastcall HashValue(const AnsiString ASrc)/* overload */;
	virtual unsigned __fastcall HashValue(Classes::TStream* AStream) = 0 /* overload */;
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHash32(void) : TIdHash() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHash32(void) { }
	#pragma option pop
	
};


typedef unsigned T4x4LongWordRecord[4];

class DELPHICLASS TIdHash128;
class PASCALIMPLEMENTATION TIdHash128 : public TIdHash 
{
	typedef TIdHash inherited;
	
public:
	/*         class method */ static AnsiString __fastcall AsHex(TMetaClass* vmt, const unsigned * AValue
		);
	unsigned __fastcall HashValue(const AnsiString ASrc)/* overload */;
	virtual unsigned __fastcall HashValue(Classes::TStream* AStream) = 0 /* overload */;
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHash128(void) : TIdHash() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHash128(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idhash */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idhash;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdHash
