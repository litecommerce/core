// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdThreadMgrPool.pas' rev: 5.00

#ifndef IdThreadMgrPoolHPP
#define IdThreadMgrPoolHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdThreadMgr.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idthreadmgrpool
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdThreadMgrPool;
class PASCALIMPLEMENTATION TIdThreadMgrPool : public Idthreadmgr::TIdThreadMgr 
{
	typedef Idthreadmgr::TIdThreadMgr inherited;
	
protected:
	int FPoolSize;
	Classes::TThreadList* FThreadPool;
	void __fastcall ThreadStopped(Idthread::TIdThread* AThread);
	
public:
	__fastcall virtual TIdThreadMgrPool(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdThreadMgrPool(void);
	virtual Idthread::TIdThread* __fastcall GetThread(void);
	virtual void __fastcall ReleaseThread(Idthread::TIdThread* AThread);
	virtual void __fastcall TerminateThreads(void);
	
__published:
	__property int PoolSize = {read=FPoolSize, write=FPoolSize, default=0};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idthreadmgrpool */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idthreadmgrpool;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdThreadMgrPool
