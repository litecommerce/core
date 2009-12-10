// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIOHandlerStream.pas' rev: 5.00

#ifndef IdIOHandlerStreamHPP
#define IdIOHandlerStreamHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdIOHandler.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idiohandlerstream
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdIOHandlerStream;
class PASCALIMPLEMENTATION TIdIOHandlerStream : public Idiohandler::TIdIOHandler 
{
	typedef Idiohandler::TIdIOHandler inherited;
	
protected:
	bool FFreeStreams;
	Classes::TStream* FInputStream;
	Classes::TStream* FOutputStream;
	void __fastcall SetInputStream(const Classes::TStream* AValue);
	void __fastcall SetOutputStream(const Classes::TStream* AValue);
	
public:
	virtual void __fastcall Close(void);
	__fastcall virtual TIdIOHandlerStream(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdIOHandlerStream(void);
	virtual void __fastcall Open(void);
	virtual bool __fastcall Readable(int AMSec);
	virtual int __fastcall Recv(void *ABuf, int ALen);
	virtual int __fastcall Send(void *ABuf, int ALen);
	__property Classes::TStream* InputStream = {read=FInputStream, write=SetInputStream};
	__property Classes::TStream* OutputStream = {read=FOutputStream, write=SetOutputStream};
	
__published:
	__property bool FreeStreams = {read=FFreeStreams, write=FFreeStreams, nodefault};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idiohandlerstream */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idiohandlerstream;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIOHandlerStream
