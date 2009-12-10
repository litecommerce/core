// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdURI.pas' rev: 5.00

#ifndef IdURIHPP
#define IdURIHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdException.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Iduri
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdURIOptionalFields { ofAuthInfo, ofBookmark };
#pragma option pop

typedef Set<TIdURIOptionalFields, ofAuthInfo, ofBookmark>  TIdURIOptionalFieldsSet;

class DELPHICLASS TIdURI;
class PASCALIMPLEMENTATION TIdURI : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	AnsiString FDocument;
	AnsiString FProtocol;
	AnsiString FURI;
	AnsiString FPort;
	AnsiString Fpath;
	AnsiString FHost;
	AnsiString FBookmark;
	AnsiString FUserName;
	AnsiString FPassword;
	AnsiString FParams;
	void __fastcall SetURI(const AnsiString Value);
	AnsiString __fastcall GetURI();
	
public:
	__fastcall virtual TIdURI(const AnsiString AURI);
	AnsiString __fastcall GetFullURI(const TIdURIOptionalFieldsSet AOptionalFileds);
	/*         class method */ static void __fastcall NormalizePath(TMetaClass* vmt, AnsiString &APath)
		;
	/*         class method */ static AnsiString __fastcall URLDecode(TMetaClass* vmt, AnsiString ASrc)
		;
	/*         class method */ static AnsiString __fastcall URLEncode(TMetaClass* vmt, const AnsiString 
		ASrc);
	/*         class method */ static AnsiString __fastcall ParamsEncode(TMetaClass* vmt, const AnsiString 
		ASrc);
	/*         class method */ static AnsiString __fastcall PathEncode(TMetaClass* vmt, const AnsiString 
		ASrc);
	__property AnsiString Bookmark = {read=FBookmark, write=FBookmark};
	__property AnsiString Document = {read=FDocument, write=FDocument};
	__property AnsiString Host = {read=FHost, write=FHost};
	__property AnsiString Password = {read=FPassword, write=FPassword};
	__property AnsiString Path = {read=Fpath, write=Fpath};
	__property AnsiString Params = {read=FParams, write=FParams};
	__property AnsiString Port = {read=FPort, write=FPort};
	__property AnsiString Protocol = {read=FProtocol, write=FProtocol};
	__property AnsiString URI = {read=GetURI, write=SetURI};
	__property AnsiString Username = {read=FUserName, write=FUserName};
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdURI(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdURIException;
class PASCALIMPLEMENTATION EIdURIException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdURIException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdURIException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdURIException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdURIException(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdURIException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdURIException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdURIException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdURIException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdURIException(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Iduri */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Iduri;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdURI
