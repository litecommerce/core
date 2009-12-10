// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRSHServer.pas' rev: 5.00

#ifndef IdRSHServerHPP
#define IdRSHServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdRemoteCMDServer.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idrshserver
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TIdRSHCommandEvent)(Idtcpserver::TIdPeerThread* AThread, Idtcpclient::TIdTCPClient* 
	AStdError, AnsiString AClientUserName, AnsiString AHostUserName, AnsiString ACommand);

class DELPHICLASS TIdRSHServer;
class PASCALIMPLEMENTATION TIdRSHServer : public Idremotecmdserver::TIdRemoteCMDServer 
{
	typedef Idremotecmdserver::TIdRemoteCMDServer inherited;
	
protected:
	TIdRSHCommandEvent FOnCommand;
	virtual void __fastcall DoCMD(Idtcpserver::TIdPeerThread* AThread, Idtcpclient::TIdTCPClient* AStdError
		, AnsiString AParam1, AnsiString AParam2, AnsiString ACommand);
	
public:
	__fastcall virtual TIdRSHServer(Classes::TComponent* AOwner);
	
__published:
	__property TIdRSHCommandEvent OnCommand = {read=FOnCommand, write=FOnCommand};
	__property DefaultPort ;
	__property bool ForcePortsInRange = {read=FForcePortsInRange, write=FForcePortsInRange, default=1};
		
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdRSHServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const bool RSH_FORCEPORTSINRANGE = true;

}	/* namespace Idrshserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idrshserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRSHServer
