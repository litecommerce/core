// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdPOP3.pas' rev: 5.00

#ifndef IdPOP3HPP
#define IdPOP3HPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdMessageClient.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idpop3
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdPOP3;
class PASCALIMPLEMENTATION TIdPOP3 : public Idmessageclient::TIdMessageClient 
{
	typedef Idmessageclient::TIdMessageClient inherited;
	
protected:
	bool FAPOP;
	
public:
	int __fastcall CheckMessages(void);
	virtual void __fastcall Connect(const int ATimeout);
	__fastcall virtual TIdPOP3(Classes::TComponent* AOwner);
	bool __fastcall Delete(const int MsgNum);
	virtual void __fastcall Disconnect(void);
	virtual short __fastcall GetResponse(const short * AAllowedResponses, const int AAllowedResponses_Size
		)/* overload */;
	void __fastcall KeepAlive(void);
	bool __fastcall Reset(void);
	bool __fastcall Retrieve(const int MsgNum, Idmessage::TIdMessage* AMsg);
	bool __fastcall RetrieveHeader(const int MsgNum, Idmessage::TIdMessage* AMsg);
	int __fastcall RetrieveMsgSize(const int MsgNum);
	int __fastcall RetrieveMailBoxSize(void);
	bool __fastcall RetrieveRaw(const int MsgNum, const Classes::TStrings* Dest);
	bool __fastcall UIDL(const Classes::TStrings* ADest, const int AMsgNum);
	
__published:
	__property bool APOP = {read=FAPOP, write=FAPOP, default=0};
	__property Password ;
	__property Username ;
	__property Port ;
public:
	#pragma option push -w-inl
	/* TIdTCPClient.Destroy */ inline __fastcall virtual ~TIdPOP3(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const bool DEF_APOP = false;
static const Shortint wsOk = 0x1;
static const Shortint wsErr = 0x2;

}	/* namespace Idpop3 */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idpop3;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdPOP3
