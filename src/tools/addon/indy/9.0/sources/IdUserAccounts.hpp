// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdUserAccounts.pas' rev: 5.00

#ifndef IdUserAccountsHPP
#define IdUserAccountsHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <IdStrings.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Iduseraccounts
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdUserAccount;
class PASCALIMPLEMENTATION TIdUserAccount : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	Classes::TStrings* FAttributes;
	System::TObject* FData;
	AnsiString FUserName;
	AnsiString FPassword;
	AnsiString FRealName;
	void __fastcall SetAttributes(const Classes::TStrings* AValue);
	
public:
	__fastcall virtual TIdUserAccount(Classes::TCollection* ACollection);
	__fastcall virtual ~TIdUserAccount(void);
	bool __fastcall CheckPassword(const AnsiString APassword);
	__property System::TObject* Data = {read=FData, write=FData};
	
__published:
	__property Classes::TStrings* Attributes = {read=FAttributes, write=SetAttributes};
	__property AnsiString UserName = {read=FUserName, write=FUserName};
	__property AnsiString Password = {read=FPassword, write=FPassword};
	__property AnsiString RealName = {read=FRealName, write=FRealName};
};


class DELPHICLASS TIdUserAccounts;
class DELPHICLASS TIdUserManager;
class PASCALIMPLEMENTATION TIdUserAccounts : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	bool FCaseSensitiveUsernames;
	bool FCaseSensitivePasswords;
	TIdUserAccount* __fastcall GetAccount(const int AIndex);
	TIdUserAccount* __fastcall GetByUsername(const AnsiString AUsername);
	void __fastcall SetAccount(const int AIndex, TIdUserAccount* AAccountValue);
	
public:
	HIDESBASE TIdUserAccount* __fastcall Add(void);
	__fastcall TIdUserAccounts(TIdUserManager* AOwner);
	__property bool CaseSensitiveUsernames = {read=FCaseSensitiveUsernames, write=FCaseSensitiveUsernames
		, nodefault};
	__property bool CaseSensitivePasswords = {read=FCaseSensitivePasswords, write=FCaseSensitivePasswords
		, nodefault};
	__property TIdUserAccount* UserNames[AnsiString AUserName] = {read=GetByUsername/*, default*/};
	__property TIdUserAccount* Items[int AIndex] = {read=GetAccount, write=SetAccount};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdUserAccounts(void) { }
	#pragma option pop
	
};


typedef void __fastcall (__closure *TOnAfterAuthentication)(const AnsiString AUsername, const AnsiString 
	APassword, bool AAuthenticationResult);

class PASCALIMPLEMENTATION TIdUserManager : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	TIdUserAccounts* FAccounts;
	TOnAfterAuthentication FOnAfterAuthentication;
	void __fastcall DoAfterAuthentication(const AnsiString AUsername, const AnsiString APassword, bool 
		AAuthenticationResult);
	bool __fastcall GetCaseSensitivePasswords(void);
	bool __fastcall GetCaseSensitiveUsernames(void);
	void __fastcall SetAccounts(TIdUserAccounts* AValue);
	void __fastcall SetCaseSensitivePasswords(const bool AValue);
	void __fastcall SetCaseSensitiveUsernames(const bool AValue);
	
public:
	bool __fastcall AuthenticateUser(const AnsiString AUsername, const AnsiString APassword);
	__fastcall virtual TIdUserManager(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdUserManager(void);
	
__published:
	__property TIdUserAccounts* Accounts = {read=FAccounts, write=SetAccounts};
	__property bool CaseSensitiveUsernames = {read=GetCaseSensitiveUsernames, write=SetCaseSensitiveUsernames
		, nodefault};
	__property bool CaseSensitivePasswords = {read=GetCaseSensitivePasswords, write=SetCaseSensitivePasswords
		, nodefault};
	__property TOnAfterAuthentication OnAfterAuthentication = {read=FOnAfterAuthentication, write=FOnAfterAuthentication
		};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Iduseraccounts */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Iduseraccounts;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdUserAccounts
