// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMappedFTP.pas' rev: 5.00

#ifndef IdMappedFTPHPP
#define IdMappedFTPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <IdMappedPortTCP.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmappedftp
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdMappedFtpThread;
class DELPHICLASS TIdMappedFtpDataThread;
class PASCALIMPLEMENTATION TIdMappedFtpDataThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	TIdMappedFtpThread* FMappedFtpThread;
	Idtcpconnection::TIdTCPConnection* FConnection;
	Idtcpconnection::TIdTCPConnection* FOutboundClient;
	Classes::TList* FReadList;
	AnsiString FNetData;
	virtual void __fastcall BeforeRun(void);
	virtual void __fastcall Run(void);
	
public:
	__fastcall TIdMappedFtpDataThread(TIdMappedFtpThread* AMappedFtpThread);
	__fastcall virtual ~TIdMappedFtpDataThread(void);
	__property TIdMappedFtpThread* MappedFtpThread = {read=FMappedFtpThread};
	__property Idtcpconnection::TIdTCPConnection* Connection = {read=FConnection};
	__property Idtcpconnection::TIdTCPConnection* OutboundClient = {read=FOutboundClient};
	__property AnsiString NetData = {read=FNetData, write=FNetData};
};


class PASCALIMPLEMENTATION TIdMappedFtpThread : public Idmappedporttcp::TIdMappedPortThread 
{
	typedef Idmappedporttcp::TIdMappedPortThread inherited;
	
protected:
	AnsiString FFtpCommand;
	AnsiString FFtpParams;
	AnsiString FHost;
	AnsiString FoutboundHost;
	int FPort;
	int FoutboundPort;
	TIdMappedFtpDataThread* FDataChannelThread;
	AnsiString __fastcall GetFtpCmdLine();
	void __fastcall CreateDataChannelThread(void);
	virtual bool __fastcall ProcessFtpCommand(void);
	virtual void __fastcall ProcessOutboundDc(const bool APASV);
	virtual void __fastcall ProcessDataCommand(void);
	
public:
	__fastcall virtual TIdMappedFtpThread(bool ACreateSuspended);
	__property AnsiString FtpCommand = {read=FFtpCommand, write=FFtpCommand};
	__property AnsiString FtpParams = {read=FFtpParams, write=FFtpParams};
	__property AnsiString FtpCmdLine = {read=GetFtpCmdLine};
	__property AnsiString Host = {read=FHost, write=FHost};
	__property AnsiString OutboundHost = {read=FoutboundHost, write=FoutboundHost};
	__property int Port = {read=FPort, write=FPort, nodefault};
	__property int OutboundPort = {read=FoutboundPort, write=FoutboundPort, nodefault};
	__property TIdMappedFtpDataThread* DataChannelThread = {read=FDataChannelThread};
public:
	#pragma option push -w-inl
	/* TIdMappedPortThread.Destroy */ inline __fastcall virtual ~TIdMappedFtpThread(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdMappedFtpOutboundDcMode { fdcmClient, fdcmPort, fdcmPasv };
#pragma option pop

class DELPHICLASS TIdMappedFTP;
class PASCALIMPLEMENTATION TIdMappedFTP : public Idmappedporttcp::TIdMappedPortTCP 
{
	typedef Idmappedporttcp::TIdMappedPortTCP inherited;
	
protected:
	TIdMappedFtpOutboundDcMode FOutboundDcMode;
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* AThread);
	
public:
	__fastcall virtual TIdMappedFTP(Classes::TComponent* AOwner);
	
__published:
	__property DefaultPort ;
	__property MappedPort ;
	__property TIdMappedFtpOutboundDcMode OutboundDcMode = {read=FOutboundDcMode, write=FOutboundDcMode
		, default=0};
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdMappedFTP(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idmappedftp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmappedftp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMappedFTP
