// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdASN1Util.pas' rev: 5.00

#ifndef IdASN1UtilHPP
#define IdASN1UtilHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idasn1util
{
//-- type declarations -------------------------------------------------------
//-- var, const, procedure ---------------------------------------------------
static const Shortint ASN1_INT = 0x2;
static const Shortint ASN1_OCTSTR = 0x4;
static const Shortint ASN1_NULL = 0x5;
static const Shortint ASN1_OBJID = 0x6;
static const Shortint ASN1_SEQ = 0x30;
static const Shortint ASN1_IPADDR = 0x40;
static const Shortint ASN1_COUNTER = 0x41;
static const Shortint ASN1_GAUGE = 0x42;
static const Shortint ASN1_TIMETICKS = 0x43;
static const Shortint ASN1_OPAQUE = 0x44;
extern PACKAGE AnsiString __fastcall ASNEncOIDItem(int Value);
extern PACKAGE int __fastcall ASNDecOIDItem(int &Start, const AnsiString Buffer);
extern PACKAGE AnsiString __fastcall ASNEncLen(int Len);
extern PACKAGE int __fastcall ASNDecLen(int &Start, const AnsiString Buffer);
extern PACKAGE AnsiString __fastcall ASNEncInt(int Value);
extern PACKAGE AnsiString __fastcall ASNEncUInt(int Value);
extern PACKAGE AnsiString __fastcall ASNObject(const AnsiString Data, int ASNType);
extern PACKAGE AnsiString __fastcall ASNItem(int &Start, const AnsiString Buffer, int &ValueType);
extern PACKAGE AnsiString __fastcall MibToId(AnsiString Mib);
extern PACKAGE AnsiString __fastcall IdToMib(const AnsiString Id);
extern PACKAGE AnsiString __fastcall IntMibToStr(const AnsiString Value);

}	/* namespace Idasn1util */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idasn1util;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdASN1Util
