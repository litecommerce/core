// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdAuthenticationManager.pas' rev: 5.00

#ifndef IdAuthenticationManagerHPP
#define IdAuthenticationManagerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdBaseComponent.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdURI.hpp>	// Pascal unit
#include <IdAuthentication.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idauthenticationmanager
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdAuthenticationItem;
class PASCALIMPLEMENTATION TIdAuthenticationItem : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	Iduri::TIdURI* FURI;
	Classes::TStringList* FParams;
	void __fastcall SetParams(const Classes::TStringList* Value);
	void __fastcall SetURI(const Iduri::TIdURI* Value);
	
public:
	__fastcall virtual TIdAuthenticationItem(Classes::TCollection* ACollection);
	__fastcall virtual ~TIdAuthenticationItem(void);
	__property Iduri::TIdURI* URL = {read=FURI, write=SetURI};
	__property Classes::TStringList* Params = {read=FParams, write=SetParams};
};


class DELPHICLASS TIdAuthenticationCollection;
class PASCALIMPLEMENTATION TIdAuthenticationCollection : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	TIdAuthenticationItem* __fastcall GetAuthItem(int AIndex);
	void __fastcall SetAuthItem(int AIndex, const TIdAuthenticationItem* Value);
	
public:
	__fastcall TIdAuthenticationCollection(Classes::TPersistent* AOwner);
	HIDESBASE TIdAuthenticationItem* __fastcall Add(void);
	__property TIdAuthenticationItem* Items[int AIndex] = {read=GetAuthItem, write=SetAuthItem};
public:
		
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdAuthenticationCollection(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdAuthenticationManager;
class PASCALIMPLEMENTATION TIdAuthenticationManager : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	TIdAuthenticationCollection* FAuthentications;
	
public:
	__fastcall virtual TIdAuthenticationManager(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdAuthenticationManager(void);
	void __fastcall AddAuthentication(Idauthentication::TIdAuthentication* AAuthtetication, Iduri::TIdURI* 
		AURL);
	__property TIdAuthenticationCollection* Authentications = {read=FAuthentications};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idauthenticationmanager */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idauthenticationmanager;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdAuthenticationManager
