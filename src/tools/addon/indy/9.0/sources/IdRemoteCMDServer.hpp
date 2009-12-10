// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRemoteCMDServer.pas' rev: 5.00

#ifndef IdRemoteCMDServerHPP
#define IdRemoteCMDServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idremotecmdserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdRemoteCMDServer;
class PASCALIMPLEMENTATION TIdRemoteCMDServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	bool FForcePortsInRange;
	bool FStdErrorPortsInRange;
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* AThread);
	virtual void __fastcall DoCMD(Idtcpserver::TIdPeerThread* AThread, Idtcpclient::TIdTCPClient* AStdError
		, AnsiString AParam1, AnsiString AParam2, AnsiString ACommand) = 0 ;
	
public:
	void __fastcall SendError(Idtcpserver::TIdPeerThread* AThread, Idtcpclient::TIdTCPClient* AStdErr, 
		AnsiString AMsg);
	void __fastcall SendResults(Idtcpserver::TIdPeerThread* AThread, Idtcpclient::TIdTCPClient* AStdErr
		, AnsiString AMsg);
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Create */ inline __fastcall virtual TIdRemoteCMDServer(Classes::TComponent* AOwner)
		 : Idtcpserver::TIdTCPServer(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdRemoteCMDServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idremotecmdserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idremotecmdserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRemoteCMDServer
