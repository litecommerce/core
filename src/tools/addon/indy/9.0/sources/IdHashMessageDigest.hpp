// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdHashMessageDigest.pas' rev: 5.00

#ifndef IdHashMessageDigestHPP
#define IdHashMessageDigestHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdHash.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idhashmessagedigest
{
//-- type declarations -------------------------------------------------------
typedef unsigned T16x4LongWordRecord[16];

typedef unsigned T4x4x4LongWordRecord[4][4];

typedef Byte T384BitRecord[48];

typedef Byte T128BitRecord[16];

class DELPHICLASS TIdHashMessageDigest;
class PASCALIMPLEMENTATION TIdHashMessageDigest : public Idhash::TIdHash128 
{
	typedef Idhash::TIdHash128 inherited;
	
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHashMessageDigest(void) : Idhash::TIdHash128() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHashMessageDigest(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdHashMessageDigest2;
class PASCALIMPLEMENTATION TIdHashMessageDigest2 : public TIdHashMessageDigest 
{
	typedef TIdHashMessageDigest inherited;
	
protected:
	Byte FX[48];
	Byte FCBuffer[16];
	Byte FCheckSum[16];
	void __fastcall MDCoder(void);
	void __fastcall Reset(void);
	
public:
	virtual unsigned __fastcall HashValue(Classes::TStream* AStream)/* overload */;
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHashMessageDigest2(void) : TIdHashMessageDigest() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHashMessageDigest2(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdHashMessageDigest4;
class PASCALIMPLEMENTATION TIdHashMessageDigest4 : public TIdHashMessageDigest 
{
	typedef TIdHashMessageDigest inherited;
	
protected:
	unsigned FBuffer[4];
	unsigned FCBuffer[16];
	virtual void __fastcall MDCoder(void);
	virtual unsigned __fastcall func_f(unsigned x, unsigned y, unsigned z);
	virtual unsigned __fastcall func_g(unsigned x, unsigned y, unsigned z);
	virtual unsigned __fastcall func_h(unsigned x, unsigned y, unsigned z);
	
public:
	virtual unsigned __fastcall HashValue(Classes::TStream* AStream)/* overload */;
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHashMessageDigest4(void) : TIdHashMessageDigest() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHashMessageDigest4(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdHashMessageDigest5;
class PASCALIMPLEMENTATION TIdHashMessageDigest5 : public TIdHashMessageDigest4 
{
	typedef TIdHashMessageDigest4 inherited;
	
protected:
	virtual void __fastcall MDCoder(void);
	virtual unsigned __fastcall func_g(unsigned x, unsigned y, unsigned z);
	virtual unsigned __fastcall func_i(unsigned x, unsigned y, unsigned z);
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdHashMessageDigest5(void) : TIdHashMessageDigest4() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdHashMessageDigest5(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idhashmessagedigest */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idhashmessagedigest;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdHashMessageDigest
