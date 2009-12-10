// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdAntiFreezeBase.pas' rev: 5.00

#ifndef IdAntiFreezeBaseHPP
#define IdAntiFreezeBaseHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idantifreezebase
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdAntiFreezeBase;
class PASCALIMPLEMENTATION TIdAntiFreezeBase : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	bool FActive;
	bool FApplicationHasPriority;
	int FIdleTimeOut;
	bool FOnlyWhenIdle;
	
public:
	__fastcall virtual TIdAntiFreezeBase(Classes::TComponent* AOwner);
	/*         class method */ static void __fastcall DoProcess(TMetaClass* vmt, const bool AIdle, const 
		bool AOverride);
	__fastcall virtual ~TIdAntiFreezeBase(void);
	virtual void __fastcall Process(void) = 0 ;
	/*         class method */ static bool __fastcall ShouldUse(TMetaClass* vmt);
	/*         class method */ static void __fastcall Sleep(TMetaClass* vmt, int ATimeout);
	
__published:
	__property bool Active = {read=FActive, write=FActive, default=1};
	__property bool ApplicationHasPriority = {read=FApplicationHasPriority, write=FApplicationHasPriority
		, default=1};
	__property int IdleTimeOut = {read=FIdleTimeOut, write=FIdleTimeOut, default=250};
	__property bool OnlyWhenIdle = {read=FOnlyWhenIdle, write=FOnlyWhenIdle, default=1};
};


//-- var, const, procedure ---------------------------------------------------
static const bool ID_Default_TIdAntiFreezeBase_Active = true;
static const bool ID_Default_TIdAntiFreezeBase_ApplicationHasPriority = true;
static const System::Byte ID_Default_TIdAntiFreezeBase_IdleTimeOut = 0xfa;
static const bool ID_Default_TIdAntiFreezeBase_OnlyWhenIdle = true;
extern PACKAGE TIdAntiFreezeBase* GAntiFreeze;

}	/* namespace Idantifreezebase */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idantifreezebase;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdAntiFreezeBase
