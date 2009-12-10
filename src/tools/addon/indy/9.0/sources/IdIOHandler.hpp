// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIOHandler.pas' rev: 5.00

#ifndef IdIOHandlerHPP
#define IdIOHandlerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdGlobal.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idiohandler
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdIOHandler;
class PASCALIMPLEMENTATION TIdIOHandler : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	bool FActive;
	
public:
	virtual void __fastcall AfterAccept(void);
	virtual void __fastcall Close(void);
	virtual void __fastcall ConnectClient(const AnsiString AHost, const int APort, const AnsiString ABoundIP
		, const int ABoundPort, const int ABoundPortMin, const int ABoundPortMax, const int ATimeout);
	virtual bool __fastcall Connected(void);
	__fastcall virtual ~TIdIOHandler(void);
	virtual void __fastcall Open(void);
	virtual bool __fastcall Readable(int AMSec) = 0 ;
	virtual int __fastcall Recv(void *ABuf, int ALen) = 0 ;
	virtual int __fastcall Send(void *ABuf, int ALen) = 0 ;
	__property bool Active = {read=FActive, nodefault};
public:
	#pragma option push -w-inl
	/* TIdComponent.Create */ inline __fastcall virtual TIdIOHandler(Classes::TComponent* axOwner) : Idcomponent::TIdComponent(
		axOwner) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idiohandler */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idiohandler;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIOHandler
