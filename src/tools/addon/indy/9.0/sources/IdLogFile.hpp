// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdLogFile.pas' rev: 5.00

#ifndef IdLogFileHPP
#define IdLogFileHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdIntercept.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdLogBase.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idlogfile
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdLogFile;
class PASCALIMPLEMENTATION TIdLogFile : public Idlogbase::TIdLogBase 
{
	typedef Idlogbase::TIdLogBase inherited;
	
protected:
	AnsiString FFilename;
	Classes::TFileStream* FFileStream;
	virtual void __fastcall Close(void);
	virtual void __fastcall LogFormat(const AnsiString AFormat, const System::TVarRec * AArgs, const int 
		AArgs_Size);
	virtual void __fastcall LogReceivedData(const AnsiString AText, const AnsiString AData);
	virtual void __fastcall LogSentData(const AnsiString AText, const AnsiString AData);
	virtual void __fastcall LogStatus(const AnsiString AText);
	virtual void __fastcall LogWriteString(const AnsiString AText);
	virtual void __fastcall Open(void);
	
__published:
	__property AnsiString Filename = {read=FFilename, write=FFilename};
public:
	#pragma option push -w-inl
	/* TIdLogBase.Create */ inline __fastcall virtual TIdLogFile(Classes::TComponent* AOwner) : Idlogbase::TIdLogBase(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdLogBase.Destroy */ inline __fastcall virtual ~TIdLogFile(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idlogfile */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idlogfile;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdLogFile
