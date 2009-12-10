// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdAuthenticationNTLM.pas' rev: 5.00

#ifndef IdAuthenticationNTLMHPP
#define IdAuthenticationNTLMHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdAuthentication.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idauthenticationntlm
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdNTLMAuthentication;
class PASCALIMPLEMENTATION TIdNTLMAuthentication : public Idauthentication::TIdAuthentication 
{
	typedef Idauthentication::TIdAuthentication inherited;
	
protected:
	AnsiString FNTLMInfo;
	AnsiString LDomain;
	AnsiString LUser;
	virtual Idauthentication::TIdAuthWhatsNext __fastcall DoNext(void);
	virtual int __fastcall GetSteps(void);
	virtual void __fastcall SetUserName(const AnsiString Value);
	
public:
	__fastcall virtual TIdNTLMAuthentication(void);
	virtual AnsiString __fastcall Authentication();
	virtual bool __fastcall KeepAlive(void);
	virtual void __fastcall Reset(void);
public:
	#pragma option push -w-inl
	/* TIdAuthentication.Destroy */ inline __fastcall virtual ~TIdNTLMAuthentication(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idauthenticationntlm */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idauthenticationntlm;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdAuthenticationNTLM
