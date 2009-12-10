// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCoder3to4.pas' rev: 5.00

#ifndef IdCoder3to4HPP
#define IdCoder3to4HPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdCoder.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcoder3to4
{
//-- type declarations -------------------------------------------------------
typedef Byte TIdDecodeTable[127];

class DELPHICLASS TIdEncoder3to4;
class PASCALIMPLEMENTATION TIdEncoder3to4 : public Idcoder::TIdEncoder 
{
	typedef Idcoder::TIdEncoder inherited;
	
protected:
	AnsiString FCodingTable;
	char FFillChar;
	
public:
	virtual AnsiString __fastcall Encode(Classes::TStream* ASrcStream, const int ABytes)/* overload */;
		
	void __fastcall EncodeUnit(const Byte AIn1, const Byte AIn2, const Byte AIn3, unsigned &VOut);
	
__published:
	__property AnsiString CodingTable = {read=FCodingTable};
	__property char FillChar = {read=FFillChar, write=FFillChar, nodefault};
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdEncoder3to4(Classes::TComponent* AOwner) : Idcoder::TIdEncoder(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdEncoder3to4(void) { }
	#pragma option pop
	
};


typedef TMetaClass*TIdEncoder3to4Class;

class DELPHICLASS TIdDecoder4to3;
class PASCALIMPLEMENTATION TIdDecoder4to3 : public Idcoder::TIdDecoder 
{
	typedef Idcoder::TIdDecoder inherited;
	
protected:
	Byte FDecodeTable[127];
	char FFillChar;
	
public:
	/*         class method */ static void __fastcall ConstructDecodeTable(TMetaClass* vmt, const AnsiString 
		ACodingTable, Byte * ADecodeArray);
	virtual void __fastcall DecodeToStream(AnsiString AIn, Classes::TStream* ADest);
	void __fastcall DecodeUnit(unsigned AIn, Byte &VOut1, Byte &VOut2, Byte &VOut3);
	
__published:
	__property char FillChar = {read=FFillChar, write=FFillChar, nodefault};
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdDecoder4to3(Classes::TComponent* AOwner) : Idcoder::TIdDecoder(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdDecoder4to3(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idcoder3to4 */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcoder3to4;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCoder3to4
