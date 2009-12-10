// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIOHandlerSocket.pas' rev: 5.00

#ifndef IdIOHandlerSocketHPP
#define IdIOHandlerSocketHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdIOHandler.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdSocks.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idiohandlersocket
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdIOHandlerSocket;
class PASCALIMPLEMENTATION TIdIOHandlerSocket : public Idiohandler::TIdIOHandler 
{
	typedef Idiohandler::TIdIOHandler inherited;
	
protected:
	Idsockethandle::TIdSocketHandle* FBinding;
	bool FUseNagle;
	Idsocks::TIdSocksInfo* FSocksInfo;
	void __fastcall SetSocksInfo(Idsocks::TIdSocksInfo* ASocks);
	Idsocks::TIdSocksInfo* __fastcall GetSocksInfo(void);
	virtual void __fastcall Notification(Classes::TComponent* AComponent, Classes::TOperation Operation
		);
	
public:
	virtual void __fastcall Close(void);
	virtual void __fastcall ConnectClient(const AnsiString AHost, const int APort, const AnsiString ABoundIP
		, const int ABoundPort, const int ABoundPortMin, const int ABoundPortMax, const int ATimeout);
	virtual bool __fastcall Connected(void);
	__fastcall virtual TIdIOHandlerSocket(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdIOHandlerSocket(void);
	virtual void __fastcall Open(void);
	virtual bool __fastcall Readable(int AMSec);
	virtual int __fastcall Recv(void *ABuf, int ALen);
	virtual int __fastcall Send(void *ABuf, int ALen);
	__property Idsockethandle::TIdSocketHandle* Binding = {read=FBinding};
	
__published:
	__property Idsocks::TIdSocksInfo* SocksInfo = {read=GetSocksInfo, write=SetSocksInfo};
	__property bool UseNagle = {read=FUseNagle, write=FUseNagle, default=1};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idiohandlersocket */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idiohandlersocket;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIOHandlerSocket
