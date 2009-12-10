// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRSH.pas' rev: 5.00

#ifndef IdRSHHPP
#define IdRSHHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdRemoteCMDClient.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idrsh
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdRSH;
class PASCALIMPLEMENTATION TIdRSH : public Idremotecmdclient::TIdRemoteCMDClient 
{
	typedef Idremotecmdclient::TIdRemoteCMDClient inherited;
	
protected:
	AnsiString FClientUserName;
	AnsiString FHostUserName;
	
public:
	__fastcall virtual TIdRSH(Classes::TComponent* AOwner);
	virtual AnsiString __fastcall Execute(AnsiString ACommand);
	
__published:
	__property AnsiString ClientUserName = {read=FClientUserName, write=FClientUserName};
	__property AnsiString HostUserName = {read=FHostUserName, write=FHostUserName};
	__property Port ;
	__property bool UseReservedPorts = {read=FUseReservedPorts, write=FUseReservedPorts, default=1};
public:
		
	#pragma option push -w-inl
	/* TIdRemoteCMDClient.Destroy */ inline __fastcall virtual ~TIdRSH(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idrsh */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idrsh;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRSH
