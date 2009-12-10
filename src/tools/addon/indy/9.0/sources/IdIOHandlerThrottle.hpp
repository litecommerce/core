// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIOHandlerThrottle.pas' rev: 5.00

#ifndef IdIOHandlerThrottleHPP
#define IdIOHandlerThrottleHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdIOHandler.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idiohandlerthrottle
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdIOHandlerThrottle;
class PASCALIMPLEMENTATION TIdIOHandlerThrottle : public Idiohandler::TIdIOHandler 
{
	typedef Idiohandler::TIdIOHandler inherited;
	
protected:
	Idiohandler::TIdIOHandler* FChainedHandler;
	unsigned FBytesPerSec;
	unsigned __fastcall GetBitsPerSec(void);
	void __fastcall SetBitsPerSec(unsigned AValue);
	virtual void __fastcall Notification(Classes::TComponent* AComponent, Classes::TOperation Operation
		);
	
public:
	virtual void __fastcall Close(void);
	virtual void __fastcall ConnectClient(const AnsiString AHost, const int APort, const AnsiString ABoundIP
		, const int ABoundPort, const int ABoundPortMin, const int ABoundPortMax, const int ATimeout);
	virtual bool __fastcall Connected(void);
	__fastcall virtual TIdIOHandlerThrottle(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdIOHandlerThrottle(void);
	virtual void __fastcall Open(void);
	virtual bool __fastcall Readable(int AMSec);
	virtual int __fastcall Recv(void *ABuf, int ALen);
	virtual int __fastcall Send(void *ABuf, int ALen);
	
__published:
	__property unsigned BytesPerSec = {read=FBytesPerSec, write=FBytesPerSec, nodefault};
	__property unsigned BitsPerSec = {read=GetBitsPerSec, write=SetBitsPerSec, nodefault};
	__property Idiohandler::TIdIOHandler* ChainedHandler = {read=FChainedHandler, write=FChainedHandler
		};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idiohandlerthrottle */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idiohandlerthrottle;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIOHandlerThrottle
