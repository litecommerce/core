// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdStrings.pas' rev: 5.00

#ifndef IdStringsHPP
#define IdStringsHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idstrings
{
//-- type declarations -------------------------------------------------------
//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString __fastcall StrHtmlEncode(const AnsiString AStr);
extern PACKAGE AnsiString __fastcall StrHtmlDecode(const AnsiString AStr);
extern PACKAGE int __fastcall FindFirstOf(AnsiString AFind, AnsiString AText);
extern PACKAGE int __fastcall FindFirstNotOf(AnsiString AFind, AnsiString AText);
extern PACKAGE AnsiString __fastcall TrimAllOf(AnsiString ATrim, AnsiString AText);
extern PACKAGE AnsiString __fastcall BinToHexStr(void * AData, int ADataLen);
extern PACKAGE void __fastcall SplitColumnsNoTrim(const AnsiString AData, Classes::TStrings* AStrings
	, const AnsiString ADelim);
extern PACKAGE void __fastcall SplitColumns(const AnsiString AData, Classes::TStrings* AStrings, const 
	AnsiString ADelim);
extern PACKAGE bool __fastcall IsWhiteString(const AnsiString AStr);
extern PACKAGE void __fastcall SplitString(const AnsiString AStr, const AnsiString AToken, AnsiString 
	&VLeft, AnsiString &VRight);
extern PACKAGE AnsiString __fastcall CommaAdd(const AnsiString AStr1, const AnsiString AStr2);
extern PACKAGE void __fastcall SplitLines(char * AData, int ADataSize, Classes::TStrings* AStrings);
	

}	/* namespace Idstrings */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idstrings;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdStrings
