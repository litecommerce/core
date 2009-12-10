// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdUDPClient.pas' rev: 5.00

#ifndef IdUDPClientHPP
#define IdUDPClientHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idudpclient
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdUDPClient;
class PASCALIMPLEMENTATION TIdUDPClient : public Idudpbase::TIdUDPBase 
{
	typedef Idudpbase::TIdUDPBase inherited;
	
public:
	HIDESBASE void __fastcall Send(AnsiString AData)/* overload */;
	HIDESBASE void __fastcall SendBuffer(void *ABuffer, const int AByteCount)/* overload */;
	
__published:
	__property AnsiString Host = {read=FHost, write=FHost};
	__property int Port = {read=FPort, write=FPort, nodefault};
	__property ReceiveTimeout ;
public:
	#pragma option push -w-inl
	/* TIdUDPBase.Create */ inline __fastcall virtual TIdUDPClient(Classes::TComponent* AOwner) : Idudpbase::TIdUDPBase(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdUDPBase.Destroy */ inline __fastcall virtual ~TIdUDPClient(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idudpclient */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idudpclient;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdUDPClient
