// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdEMailAddress.pas' rev: 5.00

#ifndef IdEMailAddressHPP
#define IdEMailAddressHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idemailaddress
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EIdEmailParseError;
class PASCALIMPLEMENTATION EIdEmailParseError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdEmailParseError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdEmailParseError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdEmailParseError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdEmailParseError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdEmailParseError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdEmailParseError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdEmailParseError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdEmailParseError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdEmailParseError(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdEMailAddressItem;
class PASCALIMPLEMENTATION TIdEMailAddressItem : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	AnsiString FAddress;
	AnsiString FName;
	AnsiString __fastcall GetText();
	void __fastcall SetText(AnsiString AText);
	AnsiString __fastcall ConvertAddress();
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property AnsiString Address = {read=FAddress, write=FAddress};
	__property AnsiString Name = {read=FName, write=FName};
	__property AnsiString Text = {read=GetText, write=SetText};
public:
	#pragma option push -w-inl
	/* TCollectionItem.Create */ inline __fastcall virtual TIdEMailAddressItem(Classes::TCollection* Collection
		) : Classes::TCollectionItem(Collection) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TCollectionItem.Destroy */ inline __fastcall virtual ~TIdEMailAddressItem(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdEMailAddressList;
class PASCALIMPLEMENTATION TIdEMailAddressList : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	HIDESBASE TIdEMailAddressItem* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdEMailAddressItem* Value);
	AnsiString __fastcall GetEMailAddresses();
	void __fastcall SetEMailAddresses(AnsiString AList);
	
public:
	__fastcall TIdEMailAddressList(Classes::TPersistent* AOwner);
	void __fastcall FillTStrings(Classes::TStrings* AStrings);
	HIDESBASE TIdEMailAddressItem* __fastcall Add(void);
	__property TIdEMailAddressItem* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
	__property AnsiString EMailAddresses = {read=GetEMailAddresses, write=SetEMailAddresses};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdEMailAddressList(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idemailaddress */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idemailaddress;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdEMailAddress
