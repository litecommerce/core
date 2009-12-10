// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdStream.pas' rev: 5.00

#ifndef IdStreamHPP
#define IdStreamHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idstream
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EIdEndOfStream;
class PASCALIMPLEMENTATION EIdEndOfStream : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdEndOfStream(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdEndOfStream(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdEndOfStream(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdEndOfStream(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdEndOfStream(const AnsiString Msg, int AHelpContext)
		 : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdEndOfStream(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdEndOfStream(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdEndOfStream(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdEndOfStream(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdStream;
class PASCALIMPLEMENTATION TIdStream : public Classes::TStream 
{
	typedef Classes::TStream inherited;
	
public:
	AnsiString __fastcall ReadLn(int AMaxLineLength, bool AExceptionIfEOF);
	/*         class method */ static int __fastcall FindEOL(TMetaClass* vmt, char * ABuf, int &VLineBufSize
		, bool &VCrEncountered);
	HIDESBASE void __fastcall Write(const AnsiString AData)/* overload */;
	void __fastcall WriteLn(const AnsiString AData)/* overload */;
	void __fastcall WriteLn(const AnsiString AData, const System::TVarRec * AArgs, const int AArgs_Size
		)/* overload */;
	TIdStream* __fastcall This(void);
	bool __fastcall BOF(void);
	bool __fastcall EOF(void);
	void __fastcall Skip(int ASize);
	int __fastcall ReadInteger(const bool AConvert);
	void __fastcall WriteInteger(int AValue, const bool AConvert);
	AnsiString __fastcall ReadString(const bool AConvert);
	void __fastcall WriteString(const AnsiString AStr, const bool AConvert);
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdStream(void) : Classes::TStream() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdStream(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idstream */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idstream;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdStream
