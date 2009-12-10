// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSysLogServer.pas' rev: 5.00

#ifndef IdSysLogServerHPP
#define IdSysLogServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdSysLog.hpp>	// Pascal unit
#include <IdSysLogMessage.hpp>	// Pascal unit
#include <IdUDPServer.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsyslogserver
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TOnSyslogEvent)(System::TObject* Sender, Idsyslogmessage::TIdSysLogMessage* 
	ASysLogMessage, Idsockethandle::TIdSocketHandle* ABinding);

class DELPHICLASS TIdSyslogServer;
class PASCALIMPLEMENTATION TIdSyslogServer : public Idudpserver::TIdUDPServer 
{
	typedef Idudpserver::TIdUDPServer inherited;
	
protected:
	TOnSyslogEvent FOnSyslog;
	virtual void __fastcall DoSyslogEvent(Idsyslogmessage::TIdSysLogMessage* AMsg, Idsockethandle::TIdSocketHandle* 
		ABinding);
	virtual void __fastcall DoUDPRead(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding
		);
	
public:
	__fastcall virtual TIdSyslogServer(Classes::TComponent* AOwner);
	
__published:
	__property DefaultPort ;
	__property TOnSyslogEvent OnSyslog = {read=FOnSyslog, write=FOnSyslog};
public:
	#pragma option push -w-inl
	/* TIdUDPServer.Destroy */ inline __fastcall virtual ~TIdSyslogServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idsyslogserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsyslogserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSysLogServer
