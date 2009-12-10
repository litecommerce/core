// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSysLog.pas' rev: 5.00

#ifndef IdSysLogHPP
#define IdSysLogHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdUDPClient.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdSysLogMessage.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsyslog
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdSysLog;
class PASCALIMPLEMENTATION TIdSysLog : public Idudpclient::TIdUDPClient 
{
	typedef Idudpclient::TIdUDPClient inherited;
	
protected:
	virtual Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void);
	
public:
	__fastcall virtual TIdSysLog(Classes::TComponent* AOwner);
	void __fastcall SendMessage(const Idsyslogmessage::TIdSysLogMessage* AMsg, const bool AAutoTimeStamp
		)/* overload */;
	void __fastcall SendMessage(const AnsiString AMsg, const Idsyslogmessage::TIdSyslogFacility AFacility
		, const Idsyslogmessage::TIdSyslogSeverity ASeverity)/* overload */;
	void __fastcall SendMessage(const AnsiString AProcess, const AnsiString AText, const Idsyslogmessage::TIdSyslogFacility 
		AFacility, const Idsyslogmessage::TIdSyslogSeverity ASeverity, const bool AUsePID, const int APID)
		/* overload */;
	
__published:
	__property Port ;
public:
	#pragma option push -w-inl
	/* TIdUDPBase.Destroy */ inline __fastcall virtual ~TIdSysLog(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idsyslog */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsyslog;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSysLog
