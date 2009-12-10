// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCookieManager.pas' rev: 5.00

#ifndef IdCookieManagerHPP
#define IdCookieManagerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdURI.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdCookie.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcookiemanager
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TOnNewCookieEvent)(System::TObject* ASender, Idcookie::TIdCookieRFC2109* 
	ACookie, bool &VAccept);

typedef void __fastcall (__closure *TOnManagerEvent)(System::TObject* ASender, Idcookie::TIdCookies* 
	ACookieCollection);

typedef void __fastcall (__closure *TOnCreateEvent)(System::TObject* ASender, Idcookie::TIdCookies* 
	ACookieCollection);

typedef void __fastcall (__closure *TOnDestroyEvent)(System::TObject* ASender, Idcookie::TIdCookies* 
	ACookieCollection);

class DELPHICLASS TIdCookieManager;
class PASCALIMPLEMENTATION TIdCookieManager : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	TOnManagerEvent FOnCreate;
	TOnManagerEvent FOnDestroy;
	TOnNewCookieEvent FOnNewCookie;
	Idcookie::TIdCookies* FCookieCollection;
	void __fastcall DoAdd(Idcookie::TIdCookieRFC2109* ACookie, AnsiString ACookieText, AnsiString AHost
		);
	virtual void __fastcall DoOnCreate(void);
	virtual void __fastcall DoOnDestroy(void);
	void __fastcall CleanupCookieList(void);
	virtual bool __fastcall DoOnNewCookie(Idcookie::TIdCookieRFC2109* ACookie);
	
public:
	__fastcall virtual TIdCookieManager(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdCookieManager(void);
	void __fastcall AddCookie(AnsiString ACookie, AnsiString AHost);
	void __fastcall AddCookie2(AnsiString ACookie, AnsiString AHost);
	AnsiString __fastcall GenerateCookieList(Iduri::TIdURI* URL, bool SecureConnection);
	__property Idcookie::TIdCookies* CookieCollection = {read=FCookieCollection};
	
__published:
	__property TOnManagerEvent OnCreate = {read=FOnCreate, write=FOnCreate};
	__property TOnManagerEvent OnDestroy = {read=FOnDestroy, write=FOnDestroy};
	__property TOnNewCookieEvent OnNewCookie = {read=FOnNewCookie, write=FOnNewCookie};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idcookiemanager */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcookiemanager;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCookieManager
