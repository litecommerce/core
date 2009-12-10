// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMappedPortTCP.pas' rev: 5.00

#ifndef IdMappedPortTCPHPP
#define IdMappedPortTCPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmappedporttcp
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdMappedPortThread;
class PASCALIMPLEMENTATION TIdMappedPortThread : public Idtcpserver::TIdPeerThread 
{
	typedef Idtcpserver::TIdPeerThread inherited;
	
protected:
	Idtcpconnection::TIdTCPConnection* FOutboundClient;
	Classes::TList* FReadList;
	AnsiString FNetData;
	int FConnectTimeOut;
	virtual void __fastcall Cleanup(void);
	virtual void __fastcall OutboundConnect(void);
	
public:
	__fastcall virtual TIdMappedPortThread(bool ACreateSuspended);
	__fastcall virtual ~TIdMappedPortThread(void);
	__property int ConnectTimeOut = {read=FConnectTimeOut, write=FConnectTimeOut, default=-1};
	__property AnsiString NetData = {read=FNetData, write=FNetData};
	__property Idtcpconnection::TIdTCPConnection* OutboundClient = {read=FOutboundClient, write=FOutboundClient
		};
	__property Classes::TList* ReadList = {read=FReadList};
};


typedef void __fastcall (__closure *TIdMappedPortThreadEvent)(TIdMappedPortThread* AThread);

typedef void __fastcall (__closure *TIdMappedPortOutboundConnectEvent)(TIdMappedPortThread* AThread, 
	Sysutils::Exception* AException);

class DELPHICLASS TIdMappedPortTCP;
class PASCALIMPLEMENTATION TIdMappedPortTCP : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	AnsiString FMappedHost;
	int FMappedPort;
	TIdMappedPortOutboundConnectEvent FOnOutboundConnect;
	TIdMappedPortThreadEvent FOnOutboundData;
	TIdMappedPortThreadEvent FOnOutboundDisConnect;
	virtual void __fastcall DoConnect(Idtcpserver::TIdPeerThread* AThread);
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* AThread);
	virtual void __fastcall DoDisconnect(Idtcpserver::TIdPeerThread* AThread);
	virtual void __fastcall DoLocalClientConnect(TIdMappedPortThread* AThread);
	virtual void __fastcall DoLocalClientData(TIdMappedPortThread* AThread);
	virtual void __fastcall DoOutboundClientConnect(TIdMappedPortThread* AThread, const Sysutils::Exception* 
		AException);
	virtual void __fastcall DoOutboundClientData(TIdMappedPortThread* AThread);
	virtual void __fastcall DoOutboundDisconnect(TIdMappedPortThread* AThread);
	TIdMappedPortThreadEvent __fastcall GetOnConnect();
	TIdMappedPortThreadEvent __fastcall GetOnExecute();
	void __fastcall SetOnConnect(const TIdMappedPortThreadEvent Value);
	void __fastcall SetOnExecute(const TIdMappedPortThreadEvent Value);
	TIdMappedPortThreadEvent __fastcall GetOnDisconnect();
	void __fastcall SetOnDisconnect(const TIdMappedPortThreadEvent Value);
	__property OnBeforeCommandHandler ;
	__property OnAfterCommandHandler ;
	__property OnNoCommandHandler ;
	
public:
	__fastcall virtual TIdMappedPortTCP(Classes::TComponent* AOwner);
	
__published:
	__property AnsiString MappedHost = {read=FMappedHost, write=FMappedHost};
	__property int MappedPort = {read=FMappedPort, write=FMappedPort, nodefault};
	__property TIdMappedPortThreadEvent OnConnect = {read=GetOnConnect, write=SetOnConnect};
	__property TIdMappedPortOutboundConnectEvent OnOutboundConnect = {read=FOnOutboundConnect, write=FOnOutboundConnect
		};
	__property TIdMappedPortThreadEvent OnExecute = {read=GetOnExecute, write=SetOnExecute};
	__property TIdMappedPortThreadEvent OnOutboundData = {read=FOnOutboundData, write=FOnOutboundData};
		
	__property TIdMappedPortThreadEvent OnDisconnect = {read=GetOnDisconnect, write=SetOnDisconnect};
	__property TIdMappedPortThreadEvent OnOutboundDisconnect = {read=FOnOutboundDisConnect, write=FOnOutboundDisConnect
		};
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdMappedPortTCP(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMappedTelnetThread;
class PASCALIMPLEMENTATION TIdMappedTelnetThread : public TIdMappedPortThread 
{
	typedef TIdMappedPortThread inherited;
	
protected:
	int FAllowedConnectAttempts;
	virtual void __fastcall OutboundConnect(void);
	
public:
	__property int AllowedConnectAttempts = {read=FAllowedConnectAttempts, nodefault};
public:
	#pragma option push -w-inl
	/* TIdMappedPortThread.Create */ inline __fastcall virtual TIdMappedTelnetThread(bool ACreateSuspended
		) : TIdMappedPortThread(ACreateSuspended) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdMappedPortThread.Destroy */ inline __fastcall virtual ~TIdMappedTelnetThread(void) { }
	#pragma option pop
	
};


typedef void __fastcall (__closure *TIdMappedTelnetCheckHostPort)(TIdMappedPortThread* AThread, const 
	AnsiString AHostPort, AnsiString &VHost, AnsiString &VPort);

class DELPHICLASS TIdCustomMappedTelnet;
class PASCALIMPLEMENTATION TIdCustomMappedTelnet : public TIdMappedPortTCP 
{
	typedef TIdMappedPortTCP inherited;
	
protected:
	int FAllowedConnectAttempts;
	TIdMappedTelnetCheckHostPort FOnCheckHostPort;
	virtual void __fastcall DoCheckHostPort(TIdMappedPortThread* AThread, const AnsiString AHostPort, AnsiString 
		&VHost, AnsiString &VPort);
	void __fastcall SetAllowedConnectAttempts(const int Value);
	void __fastcall ExtractHostAndPortFromLine(TIdMappedPortThread* AThread, const AnsiString AHostPort
		);
	
public:
	__fastcall virtual TIdCustomMappedTelnet(Classes::TComponent* AOwner);
	__property int AllowedConnectAttempts = {read=FAllowedConnectAttempts, write=SetAllowedConnectAttempts
		, default=-1};
	__property TIdMappedTelnetCheckHostPort OnCheckHostPort = {read=FOnCheckHostPort, write=FOnCheckHostPort
		};
	
__published:
	__property DefaultPort ;
	__property MappedPort ;
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdCustomMappedTelnet(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMappedTelnet;
class PASCALIMPLEMENTATION TIdMappedTelnet : public TIdCustomMappedTelnet 
{
	typedef TIdCustomMappedTelnet inherited;
	
__published:
	__property int AllowedConnectAttempts = {read=FAllowedConnectAttempts, write=SetAllowedConnectAttempts
		, default=-1};
	__property TIdMappedTelnetCheckHostPort OnCheckHostPort = {read=FOnCheckHostPort, write=FOnCheckHostPort
		};
public:
	#pragma option push -w-inl
	/* TIdCustomMappedTelnet.Create */ inline __fastcall virtual TIdMappedTelnet(Classes::TComponent* AOwner
		) : TIdCustomMappedTelnet(AOwner) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdMappedTelnet(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMappedPop3Thread;
class PASCALIMPLEMENTATION TIdMappedPop3Thread : public TIdMappedTelnetThread 
{
	typedef TIdMappedTelnetThread inherited;
	
protected:
	virtual void __fastcall OutboundConnect(void);
public:
	#pragma option push -w-inl
	/* TIdMappedPortThread.Create */ inline __fastcall virtual TIdMappedPop3Thread(bool ACreateSuspended
		) : TIdMappedTelnetThread(ACreateSuspended) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdMappedPortThread.Destroy */ inline __fastcall virtual ~TIdMappedPop3Thread(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMappedPop3;
class PASCALIMPLEMENTATION TIdMappedPop3 : public TIdMappedTelnet 
{
	typedef TIdMappedTelnet inherited;
	
protected:
	AnsiString FUserHostDelimiter;
	
public:
	__fastcall virtual TIdMappedPop3(Classes::TComponent* AOwner);
	
__published:
	__property DefaultPort ;
	__property MappedPort ;
	__property AnsiString UserHostDelimiter = {read=FUserHostDelimiter, write=FUserHostDelimiter};
public:
		
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdMappedPop3(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idmappedporttcp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmappedporttcp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMappedPortTCP
