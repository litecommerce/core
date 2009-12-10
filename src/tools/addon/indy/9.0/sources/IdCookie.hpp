// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCookie.pas' rev: 5.00

#ifndef IdCookieHPP
#define IdCookieHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdException.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcookie
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdCookieVersion { cvNetscape, cvRFC2109, cvRFC2965 };
#pragma option pop

class DELPHICLASS TIdCookieList;
class DELPHICLASS TIdNetscapeCookie;
class PASCALIMPLEMENTATION TIdCookieList : public Classes::TStringList 
{
	typedef Classes::TStringList inherited;
	
protected:
	TIdNetscapeCookie* __fastcall GetCookie(int Index);
	
public:
	__property TIdNetscapeCookie* Cookies[int Index] = {read=GetCookie};
public:
	#pragma option push -w-inl
	/* TStringList.Destroy */ inline __fastcall virtual ~TIdCookieList(void) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdCookieList(void) : Classes::TStringList() { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdNetscapeCookie : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	AnsiString FCookieText;
	AnsiString FDomain;
	AnsiString FExpires;
	AnsiString FName;
	AnsiString FPath;
	bool FSecure;
	AnsiString FValue;
	TIdCookieVersion FInternalVersion;
	virtual AnsiString __fastcall GetCookie();
	virtual void __fastcall SetExpires(AnsiString AValue);
	void __fastcall SetCookie(AnsiString AValue);
	virtual AnsiString __fastcall GetServerCookie();
	virtual AnsiString __fastcall GetClientCookie();
	virtual void __fastcall LoadProperties(Classes::TStringList* APropertyList);
	
public:
	__fastcall virtual TIdNetscapeCookie(Classes::TCollection* ACollection);
	__fastcall virtual ~TIdNetscapeCookie(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	virtual bool __fastcall IsValidCookie(AnsiString AServerHost);
	__property AnsiString CookieText = {read=GetCookie, write=SetCookie};
	__property AnsiString ServerCookie = {read=GetServerCookie};
	__property AnsiString ClientCookie = {read=GetClientCookie};
	__property AnsiString Domain = {read=FDomain, write=FDomain};
	__property AnsiString Expires = {read=FExpires, write=SetExpires};
	__property AnsiString CookieName = {read=FName, write=FName};
	__property AnsiString Path = {read=FPath, write=FPath};
	__property bool Secure = {read=FSecure, write=FSecure, nodefault};
	__property AnsiString Value = {read=FValue, write=FValue};
};


class DELPHICLASS TIdCookieRFC2109;
class PASCALIMPLEMENTATION TIdCookieRFC2109 : public TIdNetscapeCookie 
{
	typedef TIdNetscapeCookie inherited;
	
protected:
	__int64 FMax_Age;
	AnsiString FVersion;
	AnsiString FComment;
	virtual AnsiString __fastcall GetClientCookie();
	virtual AnsiString __fastcall GetCookie();
	virtual void __fastcall SetExpires(AnsiString AValue);
	virtual void __fastcall LoadProperties(Classes::TStringList* APropertyList);
	
public:
	__fastcall virtual TIdCookieRFC2109(Classes::TCollection* ACollection);
	__property AnsiString Comment = {read=FComment, write=FComment};
	__property __int64 MaxAge = {read=FMax_Age, write=FMax_Age};
	__property AnsiString Version = {read=FVersion, write=FVersion};
public:
	#pragma option push -w-inl
	/* TIdNetscapeCookie.Destroy */ inline __fastcall virtual ~TIdCookieRFC2109(void) { }
	#pragma option pop
	
};


typedef DynamicArray<int >  IdCookie__5;

class DELPHICLASS TIdCookieRFC2965;
class PASCALIMPLEMENTATION TIdCookieRFC2965 : public TIdCookieRFC2109 
{
	typedef TIdCookieRFC2109 inherited;
	
protected:
	AnsiString FCommentURL;
	bool FDiscard;
	DynamicArray<int >  FPortList;
	virtual AnsiString __fastcall GetCookie();
	virtual void __fastcall LoadProperties(Classes::TStringList* APropertyList);
	void __fastcall SetPort(int AIndex, int AValue);
	int __fastcall GetPort(int AIndex);
	
public:
	__fastcall virtual TIdCookieRFC2965(Classes::TCollection* ACollection);
	__property AnsiString CommentURL = {read=FCommentURL, write=FCommentURL};
	__property bool Discard = {read=FDiscard, write=FDiscard, nodefault};
	__property int PortList[int AIndex] = {read=GetPort, write=SetPort};
public:
	#pragma option push -w-inl
	/* TIdNetscapeCookie.Destroy */ inline __fastcall virtual ~TIdCookieRFC2965(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdServerCookie;
class PASCALIMPLEMENTATION TIdServerCookie : public TIdCookieRFC2109 
{
	typedef TIdCookieRFC2109 inherited;
	
protected:
	virtual AnsiString __fastcall GetCookie();
	
public:
	__fastcall virtual TIdServerCookie(Classes::TCollection* ACollection);
	void __fastcall AddAttribute(const AnsiString Attribute, const AnsiString Value);
public:
	#pragma option push -w-inl
	/* TIdNetscapeCookie.Destroy */ inline __fastcall virtual ~TIdServerCookie(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdCookieAccess { caRead, caReadWrite };
#pragma option pop

class DELPHICLASS TIdCookies;
class PASCALIMPLEMENTATION TIdCookies : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	TIdCookieList* FCookieListByDomain;
	Sysutils::TMultiReadExclusiveWriteSynchronizer* FRWLock;
	TIdCookieRFC2109* __fastcall GetCookie(const AnsiString AName, const AnsiString ADomain);
	HIDESBASE TIdCookieRFC2109* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdCookieRFC2109* Value);
	
public:
	__fastcall TIdCookies(Classes::TPersistent* AOwner);
	__fastcall virtual ~TIdCookies(void);
	HIDESBASE TIdCookieRFC2109* __fastcall Add(void);
	TIdCookieRFC2965* __fastcall Add2(void);
	void __fastcall AddCookie(TIdCookieRFC2109* ACookie);
	void __fastcall AddSrcCookie(const AnsiString sCookie);
	HIDESBASE void __fastcall Delete(int Index);
	int __fastcall GetCookieIndex(int FirstIndex, const AnsiString AName)/* overload */;
	int __fastcall GetCookieIndex(int FirstIndex, const AnsiString AName, const AnsiString ADomain)/* overload */
		;
	TIdCookieList* __fastcall LockCookieListByDomain(TIdCookieAccess AAccessType);
	void __fastcall UnlockCookieListByDomain(TIdCookieAccess AAccessType);
	__property TIdCookieRFC2109* Cookie[AnsiString AName][AnsiString ADomain] = {read=GetCookie};
	__property TIdCookieRFC2109* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
};


class DELPHICLASS TIdServerCookies;
class PASCALIMPLEMENTATION TIdServerCookies : public TIdCookies 
{
	typedef TIdCookies inherited;
	
protected:
	HIDESBASE TIdCookieRFC2109* __fastcall GetCookie(const AnsiString AName);
	
public:
	HIDESBASE TIdServerCookie* __fastcall Add(void);
	__property TIdCookieRFC2109* Cookie[AnsiString AName] = {read=GetCookie};
public:
	#pragma option push -w-inl
	/* TIdCookies.Create */ inline __fastcall TIdServerCookies(Classes::TPersistent* AOwner) : TIdCookies(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdCookies.Destroy */ inline __fastcall virtual ~TIdServerCookies(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Shortint GFMaxAge = 0xffffffff;

}	/* namespace Idcookie */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcookie;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCookie
