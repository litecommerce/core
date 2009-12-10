// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdLogEvent.pas' rev: 5.00

#ifndef IdLogEventHPP
#define IdLogEventHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdIntercept.hpp>	// Pascal unit
#include <IdLogBase.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idlogevent
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TLogItemStatusEvent)(Classes::TComponent* ASender, const AnsiString 
	AText);

typedef void __fastcall (__closure *TLogItemDataEvent)(Classes::TComponent* ASender, const AnsiString 
	AText, const AnsiString AData);

class DELPHICLASS TIdLogEvent;
class PASCALIMPLEMENTATION TIdLogEvent : public Idlogbase::TIdLogBase 
{
	typedef Idlogbase::TIdLogBase inherited;
	
protected:
	TLogItemDataEvent FOnReceived;
	TLogItemDataEvent FOnSent;
	TLogItemStatusEvent FOnStatus;
	virtual void __fastcall LogStatus(const AnsiString AText);
	virtual void __fastcall LogReceivedData(const AnsiString AText, const AnsiString AData);
	virtual void __fastcall LogSentData(const AnsiString AText, const AnsiString AData);
	
__published:
	__property TLogItemDataEvent OnReceived = {read=FOnReceived, write=FOnReceived};
	__property TLogItemDataEvent OnSent = {read=FOnSent, write=FOnSent};
	__property TLogItemStatusEvent OnStatus = {read=FOnStatus, write=FOnStatus};
public:
	#pragma option push -w-inl
	/* TIdLogBase.Create */ inline __fastcall virtual TIdLogEvent(Classes::TComponent* AOwner) : Idlogbase::TIdLogBase(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdLogBase.Destroy */ inline __fastcall virtual ~TIdLogEvent(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idlogevent */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idlogevent;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdLogEvent
