// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdLogDebug.pas' rev: 5.00

#ifndef IdLogDebugHPP
#define IdLogDebugHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdIntercept.hpp>	// Pascal unit
#include <IdLogBase.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idlogdebug
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdLogDebugTarget { ltFile, ltDebugOutput, ltEvent };
#pragma option pop

class DELPHICLASS TIdLogDebug;
class PASCALIMPLEMENTATION TIdLogDebug : public Idlogbase::TIdLogBase 
{
	typedef Idlogbase::TIdLogBase inherited;
	
protected:
	virtual void __fastcall LogStatus(const AnsiString AText);
	virtual void __fastcall LogReceivedData(const AnsiString AText, const AnsiString AData);
	virtual void __fastcall LogSentData(const AnsiString AText, const AnsiString AData);
public:
	#pragma option push -w-inl
	/* TIdLogBase.Create */ inline __fastcall virtual TIdLogDebug(Classes::TComponent* AOwner) : Idlogbase::TIdLogBase(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdLogBase.Destroy */ inline __fastcall virtual ~TIdLogDebug(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idlogdebug */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idlogdebug;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdLogDebug
