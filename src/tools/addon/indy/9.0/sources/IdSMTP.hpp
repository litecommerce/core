// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSMTP.pas' rev: 5.00

#ifndef IdSMTPHPP
#define IdSMTPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdMessageClient.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdHeaderList.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdEMailAddress.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsmtp
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TAuthenticationType { atNone, atLogin };
#pragma option pop

class DELPHICLASS TIdSMTP;
class PASCALIMPLEMENTATION TIdSMTP : public Idmessageclient::TIdMessageClient 
{
	typedef Idmessageclient::TIdMessageClient inherited;
	
protected:
	bool FDidAuthenticate;
	TAuthenticationType FAuthenticationType;
	Classes::TStringList* FAuthSchemesSupported;
	AnsiString FMailAgent;
	AnsiString FHeloName;
	bool FUseEhlo;
	void __fastcall GetAuthTypes(void);
	virtual bool __fastcall IsAuthProtocolAvailable(TAuthenticationType Auth);
	void __fastcall SetAuthenticationType(const TAuthenticationType Value);
	void __fastcall SetUseEhlo(const bool Value);
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	virtual bool __fastcall Authenticate(void);
	virtual void __fastcall Connect(const int ATimeout);
	__fastcall virtual TIdSMTP(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdSMTP(void);
	virtual void __fastcall Disconnect(void);
	/*         class method */ static void __fastcall QuickSend(TMetaClass* vmt, const AnsiString AHost
		, const AnsiString ASubject, const AnsiString ATo, const AnsiString AFrom, const AnsiString AText)
		;
	virtual void __fastcall Send(Idmessage::TIdMessage* AMsg);
	virtual void __fastcall Expand(AnsiString AUserName, Classes::TStrings* AResults);
	virtual AnsiString __fastcall Verify(AnsiString AUserName);
	__property Classes::TStringList* AuthSchemesSupported = {read=FAuthSchemesSupported};
	
__published:
	__property TAuthenticationType AuthenticationType = {read=FAuthenticationType, write=SetAuthenticationType
		, nodefault};
	__property AnsiString MailAgent = {read=FMailAgent, write=FMailAgent};
	__property AnsiString HeloName = {read=FHeloName, write=FHeloName};
	__property bool UseEhlo = {read=FUseEhlo, write=SetUseEhlo, default=1};
	__property Password ;
	__property Username ;
};


//-- var, const, procedure ---------------------------------------------------
static const bool IdDEF_UseEhlo = true;

}	/* namespace Idsmtp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsmtp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSMTP
