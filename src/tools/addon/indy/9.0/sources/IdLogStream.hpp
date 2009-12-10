// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdLogStream.pas' rev: 5.00

#ifndef IdLogStreamHPP
#define IdLogStreamHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdIntercept.hpp>	// Pascal unit
#include <IdLogBase.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idlogstream
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdLogStream;
class PASCALIMPLEMENTATION TIdLogStream : public Idlogbase::TIdLogBase 
{
	typedef Idlogbase::TIdLogBase inherited;
	
protected:
	Classes::TStream* FInputStream;
	Classes::TStream* FOutputStream;
	virtual void __fastcall LogStatus(const AnsiString AText);
	virtual void __fastcall LogReceivedData(const AnsiString AText, const AnsiString AData);
	virtual void __fastcall LogSentData(const AnsiString AText, const AnsiString AData);
	
public:
	__property Classes::TStream* InputStream = {read=FInputStream, write=FInputStream};
	__property Classes::TStream* OutputStream = {read=FOutputStream, write=FOutputStream};
public:
	#pragma option push -w-inl
	/* TIdLogBase.Create */ inline __fastcall virtual TIdLogStream(Classes::TComponent* AOwner) : Idlogbase::TIdLogBase(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdLogBase.Destroy */ inline __fastcall virtual ~TIdLogStream(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idlogstream */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idlogstream;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdLogStream
