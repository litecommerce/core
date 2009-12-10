// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSync.pas' rev: 5.00

#ifndef IdSyncHPP
#define IdSyncHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdThread.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsync
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdSync;
class PASCALIMPLEMENTATION TIdSync : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Idthread::TIdBaseThread* FThread;
	
public:
	__fastcall virtual TIdSync(void)/* overload */;
	__fastcall virtual TIdSync(Idthread::TIdBaseThread* AThread)/* overload */;
	virtual void __fastcall DoSynchronize(void) = 0 ;
	void __fastcall Synchronize(void);
	__property Idthread::TIdBaseThread* Thread = {read=FThread};
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdSync(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdNotify;
class PASCALIMPLEMENTATION TIdNotify : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	bool FMainThreadUsesNotify;
	
public:
	__fastcall virtual TIdNotify(void);
	virtual void __fastcall DoNotify(void) = 0 ;
	void __fastcall Notify(void);
	void __fastcall WaitFor(void);
	/*         class method */ static void __fastcall NotifyMethod(TMetaClass* vmt, Classes::TThreadMethod 
		AMethod);
	__property bool MainThreadUsesNotify = {read=FMainThreadUsesNotify, write=FMainThreadUsesNotify, nodefault
		};
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdNotify(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdNotifyMethod;
class PASCALIMPLEMENTATION TIdNotifyMethod : public TIdNotify 
{
	typedef TIdNotify inherited;
	
protected:
	Classes::TThreadMethod FMethod;
	
public:
	__fastcall TIdNotifyMethod(Classes::TThreadMethod AMethod);
	virtual void __fastcall DoNotify(void);
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdNotifyMethod(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idsync */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsync;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSync
