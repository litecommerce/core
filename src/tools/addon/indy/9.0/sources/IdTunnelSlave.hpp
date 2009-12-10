// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTunnelSlave.pas' rev: 5.00

#ifndef IdTunnelSlaveHPP
#define IdTunnelSlaveHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdResourceStrings.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdTunnelCommon.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtunnelslave
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TSlaveThread;
typedef void __fastcall (__closure *TTunnelEvent)(TSlaveThread* Thread);

class DELPHICLASS TClientData;
class PASCALIMPLEMENTATION TClientData : public System::TObject 
{
	typedef System::TObject inherited;
	
public:
	int Id;
	System::TDateTime TimeOfConnection;
	bool DisconnectedOnRequest;
	bool SelfDisconnected;
	bool ClientAuthorised;
	Syncobjs::TCriticalSection* Locker;
	Word Port;
	Idstack::TIdInAddr IpAddr;
	__fastcall TClientData(void);
	__fastcall virtual ~TClientData(void);
};


class DELPHICLASS TIdTunnelSlave;
class PASCALIMPLEMENTATION TIdTunnelSlave : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
private:
	int fiMasterPort;
	AnsiString fsMasterHost;
	Idtcpclient::TIdTCPClient* SClient;
	Idtcpserver::TIdServerThreadEvent fOnDisconnect;
	Idcomponent::TIdStatusEvent fOnStatus;
	Idtunnelcommon::TSendTrnEventC fOnBeforeTunnelConnect;
	Idtunnelcommon::TTunnelEventC fOnTransformRead;
	Idtunnelcommon::TSendMsgEventC fOnInterpretMsg;
	Idtunnelcommon::TSendTrnEventC fOnTransformSend;
	TTunnelEvent fOnTunnelDisconnect;
	Idtunnelcommon::TSender* Sender;
	Syncobjs::TCriticalSection* OnlyOneThread;
	Syncobjs::TCriticalSection* SendThroughTunnelLock;
	Syncobjs::TCriticalSection* GetClientThreadLock;
	Syncobjs::TCriticalSection* StatisticsLocker;
	bool ManualDisconnected;
	bool StopTransmiting;
	bool fbActive;
	bool fbSocketize;
	TSlaveThread* SlaveThread;
	Idtunnelcommon::TLogger* fLogger;
	int flConnectedClients;
	int fNumberOfConnectionsValue;
	int fNumberOfPacketsValue;
	int fCompressionRatioValue;
	int fCompressedBytes;
	int fBytesRead;
	int fBytesWrite;
	bool SlaveThreadTerminated;
	void __fastcall SendMsg(Idtunnelcommon::TIdHeader &Header, AnsiString s);
	void __fastcall ClientOperation(int Operation, int UserId, AnsiString s);
	void __fastcall DisconectAllUsers(void);
	int __fastcall GetNumClients(void);
	void __fastcall TerminateTunnelThread(void);
	Idtcpserver::TIdPeerThread* __fastcall GetClientThread(int UserID);
	void __fastcall OnTunnelThreadTerminate(System::TObject* Sender);
	
protected:
	bool fbAcceptConnections;
	virtual void __fastcall DoConnect(Idtcpserver::TIdPeerThread* Thread);
	virtual void __fastcall DoDisconnect(Idtcpserver::TIdPeerThread* Thread);
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* Thread);
	virtual void __fastcall DoBeforeTunnelConnect(Idtunnelcommon::TIdHeader &Header, AnsiString &CustomMsg
		);
	virtual void __fastcall DoTransformRead(Idtunnelcommon::TReceiver* Receiver);
	virtual void __fastcall DoInterpretMsg(AnsiString &CustomMsg);
	virtual void __fastcall DoTransformSend(Idtunnelcommon::TIdHeader &Header, AnsiString &CustomMsg);
	HIDESBASE virtual void __fastcall DoStatus(Classes::TComponent* Sender, const AnsiString sMsg);
	virtual void __fastcall DoTunnelDisconnect(TSlaveThread* Thread);
	void __fastcall LogEvent(AnsiString Msg);
	virtual void __fastcall SetActive(bool pbValue);
	
public:
	void __fastcall SetStatistics(int Module, int Value);
	void __fastcall GetStatistics(int Module, int &Value);
	__fastcall virtual TIdTunnelSlave(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdTunnelSlave(void);
	__property bool Active = {read=fbActive, write=SetActive, nodefault};
	__property Idtunnelcommon::TLogger* Logger = {read=fLogger, write=fLogger};
	__property int NumClients = {read=GetNumClients, nodefault};
	
__published:
	__property AnsiString MasterHost = {read=fsMasterHost, write=fsMasterHost};
	__property int MasterPort = {read=fiMasterPort, write=fiMasterPort, nodefault};
	__property bool Socks4 = {read=fbSocketize, write=fbSocketize, default=0};
	__property Idtcpserver::TIdServerThreadEvent OnDisconnect = {read=fOnDisconnect, write=fOnDisconnect
		};
	__property Idtunnelcommon::TSendTrnEventC OnBeforeTunnelConnect = {read=fOnBeforeTunnelConnect, write=
		fOnBeforeTunnelConnect};
	__property Idtunnelcommon::TTunnelEventC OnTransformRead = {read=fOnTransformRead, write=fOnTransformRead
		};
	__property Idtunnelcommon::TSendMsgEventC OnInterpretMsg = {read=fOnInterpretMsg, write=fOnInterpretMsg
		};
	__property Idtunnelcommon::TSendTrnEventC OnTransformSend = {read=fOnTransformSend, write=fOnTransformSend
		};
	__property Idcomponent::TIdStatusEvent OnStatus = {read=fOnStatus, write=fOnStatus};
	__property TTunnelEvent OnTunnelDisconnect = {read=fOnTunnelDisconnect, write=fOnTunnelDisconnect};
		
};


class PASCALIMPLEMENTATION TSlaveThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
private:
	Syncobjs::TCriticalSection* FLock;
	bool FExecuted;
	Idtcpclient::TIdTCPClient* FConnection;
	
protected:
	void __fastcall SetExecuted(bool Value);
	bool __fastcall GetExecuted(void);
	virtual void __fastcall AfterRun(void);
	virtual void __fastcall BeforeRun(void);
	
public:
	TIdTunnelSlave* SlaveParent;
	Idtunnelcommon::TReceiver* Receiver;
	__property bool Executed = {read=GetExecuted, write=SetExecuted, nodefault};
	__property Idtcpclient::TIdTCPClient* Connection = {read=FConnection};
	__fastcall TSlaveThread(TIdTunnelSlave* Slave);
	__fastcall virtual ~TSlaveThread(void);
	virtual void __fastcall Execute(void);
	virtual void __fastcall Run(void);
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idtunnelslave */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtunnelslave;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTunnelSlave
