// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTelnetServer.pas' rev: 5.00

#ifndef IdTelnetServerHPP
#define IdTelnetServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdThreadMgrDefault.hpp>	// Pascal unit
#include <IdThreadMgr.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtelnetserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TTelnetData;
class PASCALIMPLEMENTATION TTelnetData : public System::TObject 
{
	typedef System::TObject inherited;
	
public:
	AnsiString Username;
	AnsiString Password;
	unsigned HUserToken;
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TTelnetData(void) : System::TObject() { }
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TTelnetData(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdTelnetPeerThread;
class PASCALIMPLEMENTATION TIdTelnetPeerThread : public Idtcpserver::TIdPeerThread 
{
	typedef Idtcpserver::TIdPeerThread inherited;
	
private:
	TTelnetData* FTelnetData;
	
public:
	__fastcall virtual TIdTelnetPeerThread(bool ACreateSuspended);
	__fastcall virtual ~TIdTelnetPeerThread(void);
	__property TTelnetData* TelnetData = {read=FTelnetData};
};


typedef void __fastcall (__closure *TIdTelnetNegotiateEvent)(Idtcpserver::TIdPeerThread* AThread);

typedef void __fastcall (__closure *TAuthenticationEvent)(Idtcpserver::TIdPeerThread* AThread, const 
	AnsiString AUsername, const AnsiString APassword, bool &AAuthenticated);

class DELPHICLASS TIdTelnetServer;
class PASCALIMPLEMENTATION TIdTelnetServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	int FLoginAttempts;
	TAuthenticationEvent FOnAuthentication;
	AnsiString FLoginMessage;
	TIdTelnetNegotiateEvent FOnNegotiate;
	
public:
	__fastcall virtual TIdTelnetServer(Classes::TComponent* AOwner);
	virtual bool __fastcall DoAuthenticate(Idtcpserver::TIdPeerThread* AThread, const AnsiString AUsername
		, const AnsiString APassword);
	virtual void __fastcall DoNegotiate(Idtcpserver::TIdPeerThread* AThread);
	virtual void __fastcall DoConnect(Idtcpserver::TIdPeerThread* AThread);
	
__published:
	__property DefaultPort ;
	__property int LoginAttempts = {read=FLoginAttempts, write=FLoginAttempts, default=3};
	__property AnsiString LoginMessage = {read=FLoginMessage, write=FLoginMessage};
	__property TAuthenticationEvent OnAuthentication = {read=FOnAuthentication, write=FOnAuthentication
		};
	__property TIdTelnetNegotiateEvent OnNegotiate = {read=FOnNegotiate, write=FOnNegotiate};
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdTelnetServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Shortint GLoginAttempts = 0x3;

}	/* namespace Idtelnetserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtelnetserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTelnetServer
