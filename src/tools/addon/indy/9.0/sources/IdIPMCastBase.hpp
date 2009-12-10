// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIPMCastBase.pas' rev: 5.00

#ifndef IdIPMCastBaseHPP
#define IdIPMCastBaseHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdStack.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idipmcastbase
{
//-- type declarations -------------------------------------------------------
struct TMultiCast
{
	Idstack::TIdInAddr IMRMultiAddr;
	Idstack::TIdInAddr IMRInterface;
} ;

class DELPHICLASS TIdIPMCastBase;
class PASCALIMPLEMENTATION TIdIPMCastBase : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	bool FDsgnActive;
	AnsiString FMulticastGroup;
	int FPort;
	virtual void __fastcall CloseBinding(void) = 0 ;
	virtual bool __fastcall GetActive(void);
	virtual Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void) = 0 ;
	virtual void __fastcall Loaded(void);
	virtual void __fastcall SetActive(const bool Value);
	virtual void __fastcall SetMulticastGroup(const AnsiString Value);
	virtual void __fastcall SetPort(const int Value);
	__property bool Active = {read=GetActive, write=SetActive, default=0};
	__property AnsiString MulticastGroup = {read=FMulticastGroup, write=SetMulticastGroup};
	__property int Port = {read=FPort, write=SetPort, nodefault};
	
public:
	__fastcall virtual TIdIPMCastBase(Classes::TComponent* AOwner);
	bool __fastcall IsValidMulticastGroup(AnsiString Value);
public:
	#pragma option push -w-inl
	/* TIdComponent.Destroy */ inline __fastcall virtual ~TIdIPMCastBase(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdMCastException;
class PASCALIMPLEMENTATION EIdMCastException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdMCastException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdMCastException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdMCastException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdMCastException(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdMCastException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdMCastException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdMCastException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdMCastException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdMCastException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdMCastNoBindings;
class PASCALIMPLEMENTATION EIdMCastNoBindings : public EIdMCastException 
{
	typedef EIdMCastException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdMCastNoBindings(const AnsiString Msg) : EIdMCastException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdMCastNoBindings(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdMCastException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdMCastNoBindings(int Ident)/* overload */ : EIdMCastException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdMCastNoBindings(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdMCastException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdMCastNoBindings(const AnsiString Msg, int AHelpContext
		) : EIdMCastException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdMCastNoBindings(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdMCastException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdMCastNoBindings(int Ident, int AHelpContext)/* overload */
		 : EIdMCastException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdMCastNoBindings(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdMCastException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdMCastNoBindings(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdMCastNotValidAddress;
class PASCALIMPLEMENTATION EIdMCastNotValidAddress : public EIdMCastException 
{
	typedef EIdMCastException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdMCastNotValidAddress(const AnsiString Msg) : EIdMCastException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdMCastNotValidAddress(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdMCastException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdMCastNotValidAddress(int Ident)/* overload */ : EIdMCastException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdMCastNotValidAddress(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdMCastException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdMCastNotValidAddress(const AnsiString Msg, int AHelpContext
		) : EIdMCastException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdMCastNotValidAddress(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdMCastException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdMCastNotValidAddress(int Ident, int AHelpContext
		)/* overload */ : EIdMCastException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdMCastNotValidAddress(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdMCastException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdMCastNotValidAddress(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Byte IPMCastLo = 0xe0;
static const Byte IPMCastHi = 0xef;

}	/* namespace Idipmcastbase */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idipmcastbase;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIPMCastBase
