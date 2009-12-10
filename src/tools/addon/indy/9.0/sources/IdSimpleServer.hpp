// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSimpleServer.pas' rev: 5.00

#ifndef IdSimpleServerHPP
#define IdSimpleServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsimpleserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdSimpleServer;
class PASCALIMPLEMENTATION TIdSimpleServer : public Idtcpconnection::TIdTCPConnection 
{
	typedef Idtcpconnection::TIdTCPConnection inherited;
	
protected:
	bool FAbortedRequested;
	int FAcceptWait;
	AnsiString FBoundIP;
	int FBoundPort;
	int FListenHandle;
	bool FListening;
	Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void);
	
public:
	virtual void __fastcall Abort(void);
	virtual void __fastcall BeginListen(void);
	virtual void __fastcall Bind(void);
	__fastcall virtual TIdSimpleServer(Classes::TComponent* AOwner);
	void __fastcall CreateBinding(void);
	virtual void __fastcall EndListen(void);
	virtual bool __fastcall Listen(void);
	virtual void __fastcall ResetConnection(void);
	__property int AcceptWait = {read=FAcceptWait, write=FAcceptWait, default=1000};
	__property Idsockethandle::TIdSocketHandle* Binding = {read=GetBinding};
	__property int ListenHandle = {read=FListenHandle, nodefault};
	
__published:
	__property AnsiString BoundIP = {read=FBoundIP, write=FBoundIP};
	__property int BoundPort = {read=FBoundPort, write=FBoundPort, nodefault};
public:
	#pragma option push -w-inl
	/* TIdTCPConnection.Destroy */ inline __fastcall virtual ~TIdSimpleServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Word ID_ACCEPT_WAIT = 0x3e8;

}	/* namespace Idsimpleserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsimpleserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSimpleServer
