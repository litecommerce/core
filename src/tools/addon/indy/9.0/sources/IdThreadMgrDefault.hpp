// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdThreadMgrDefault.pas' rev: 5.00

#ifndef IdThreadMgrDefaultHPP
#define IdThreadMgrDefaultHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <IdThreadMgr.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idthreadmgrdefault
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdThreadMgrDefault;
class PASCALIMPLEMENTATION TIdThreadMgrDefault : public Idthreadmgr::TIdThreadMgr 
{
	typedef Idthreadmgr::TIdThreadMgr inherited;
	
public:
	virtual Idthread::TIdThread* __fastcall GetThread(void);
	virtual void __fastcall ReleaseThread(Idthread::TIdThread* AThread);
public:
	#pragma option push -w-inl
	/* TIdThreadMgr.Create */ inline __fastcall virtual TIdThreadMgrDefault(Classes::TComponent* AOwner
		) : Idthreadmgr::TIdThreadMgr(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdThreadMgr.Destroy */ inline __fastcall virtual ~TIdThreadMgrDefault(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idthreadmgrdefault */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idthreadmgrdefault;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdThreadMgrDefault
