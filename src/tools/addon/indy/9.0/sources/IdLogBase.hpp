// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdLogBase.pas' rev: 5.00

#ifndef IdLogBaseHPP
#define IdLogBaseHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdIntercept.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idlogbase
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdLogBase;
class PASCALIMPLEMENTATION TIdLogBase : public Idintercept::TIdConnectionIntercept 
{
	typedef Idintercept::TIdConnectionIntercept inherited;
	
protected:
	bool FActive;
	bool FLogTime;
	bool FReplaceCRLF;
	bool FStreamedActive;
	virtual void __fastcall Close(void);
	virtual void __fastcall LogStatus(const AnsiString AText) = 0 ;
	virtual void __fastcall LogReceivedData(const AnsiString AText, const AnsiString AData) = 0 ;
	virtual void __fastcall LogSentData(const AnsiString AText, const AnsiString AData) = 0 ;
	virtual void __fastcall Open(void);
	virtual void __fastcall SetActive(const bool AValue);
	virtual void __fastcall Loaded(void);
	
public:
	virtual void __fastcall Connect(Classes::TComponent* AConnection);
	__fastcall virtual TIdLogBase(Classes::TComponent* AOwner);
	virtual void __fastcall Receive(Classes::TStream* ABuffer);
	virtual void __fastcall Send(Classes::TStream* ABuffer);
	__fastcall virtual ~TIdLogBase(void);
	virtual void __fastcall Disconnect(void);
	
__published:
	__property bool Active = {read=FActive, write=SetActive, default=0};
	__property bool LogTime = {read=FLogTime, write=FLogTime, default=1};
	__property bool ReplaceCRLF = {read=FReplaceCRLF, write=FReplaceCRLF, default=1};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idlogbase */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idlogbase;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdLogBase
