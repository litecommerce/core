// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTCPClient.pas' rev: 5.00

#ifndef IdTCPClientHPP
#define IdTCPClientHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtcpclient
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdTCPClient;
class PASCALIMPLEMENTATION TIdTCPClient : public Idtcpconnection::TIdTCPConnection 
{
	typedef Idtcpconnection::TIdTCPConnection inherited;
	
protected:
	AnsiString FBoundIP;
	int FBoundPort;
	int FBoundPortMax;
	int FBoundPortMin;
	AnsiString FHost;
	Classes::TNotifyEvent FOnConnected;
	AnsiString FPassword;
	int FPort;
	AnsiString FUsername;
	virtual void __fastcall SetHost(const AnsiString Value);
	virtual void __fastcall SetPort(const int Value);
	virtual void __fastcall DoOnConnected(void);
	__property AnsiString Username = {read=FUsername, write=FUsername};
	__property AnsiString Password = {read=FPassword, write=FPassword};
	
public:
	virtual void __fastcall Connect(const int ATimeout);
	virtual AnsiString __fastcall ConnectAndGetAll();
	__fastcall virtual TIdTCPClient(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdTCPClient(void);
	__property int BoundPortMax = {read=FBoundPortMax, write=FBoundPortMax, nodefault};
	__property int BoundPortMin = {read=FBoundPortMin, write=FBoundPortMin, nodefault};
	
__published:
	__property AnsiString BoundIP = {read=FBoundIP, write=FBoundIP};
	__property int BoundPort = {read=FBoundPort, write=FBoundPort, default=0};
	__property AnsiString Host = {read=FHost, write=SetHost};
	__property Classes::TNotifyEvent OnConnected = {read=FOnConnected, write=FOnConnected};
	__property int Port = {read=FPort, write=SetPort, nodefault};
};


//-- var, const, procedure ---------------------------------------------------
static const Shortint BoundPortDefault = 0x0;

}	/* namespace Idtcpclient */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtcpclient;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTCPClient
