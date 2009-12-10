// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdFinger.pas' rev: 5.00

#ifndef IdFingerHPP
#define IdFingerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idfinger
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdFinger;
class PASCALIMPLEMENTATION TIdFinger : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	AnsiString FQuery;
	bool FVerboseOutput;
	void __fastcall SetCompleteQuery(AnsiString AQuery);
	AnsiString __fastcall GetCompleteQuery();
	
public:
	__fastcall virtual TIdFinger(Classes::TComponent* AOwner);
	AnsiString __fastcall Finger();
	
__published:
	__property AnsiString Query = {read=FQuery, write=FQuery};
	__property AnsiString CompleteQuery = {read=GetCompleteQuery, write=SetCompleteQuery};
	__property bool VerboseOutput = {read=FVerboseOutput, write=FVerboseOutput, default=0};
public:
	#pragma option push -w-inl
	/* TIdTCPClient.Destroy */ inline __fastcall virtual ~TIdFinger(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idfinger */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idfinger;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdFinger
