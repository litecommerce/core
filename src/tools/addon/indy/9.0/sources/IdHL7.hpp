// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdHL7.pas' rev: 5.00

#ifndef IdHL7HPP
#define IdHL7HPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdThread.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idhl7
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EHL7CommunicationError;
class PASCALIMPLEMENTATION EHL7CommunicationError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
protected:
	AnsiString FInterfaceName;
	
public:
	__fastcall EHL7CommunicationError(AnsiString AnInterfaceName, AnsiString AMessage);
	__property AnsiString InterfaceName = {read=FInterfaceName};
public:
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EHL7CommunicationError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EHL7CommunicationError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EHL7CommunicationError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EHL7CommunicationError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EHL7CommunicationError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EHL7CommunicationError(int Ident, int AHelpContext)
		/* overload */ : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EHL7CommunicationError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EHL7CommunicationError(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum THL7CommunicationMode { cmUnknown, cmAsynchronous, cmSynchronous, cmSingleThread };
#pragma option pop

#pragma option push -b-
enum TSendResponse { srNone, srError, srNoConnection, srSent, srOK, srTimeout };
#pragma option pop

#pragma option push -b-
enum TIdHL7Status { isStopped, isNotConnected, isConnecting, isWaitReconnect, isConnected, isUnusable 
	};
#pragma option pop

typedef void __fastcall (__closure *TMessageArriveEvent)(System::TObject* ASender, Idtcpconnection::TIdTCPConnection* 
	AConnection, AnsiString AMsg);

typedef void __fastcall (__closure *TMessageReceiveEvent)(System::TObject* ASender, Idtcpconnection::TIdTCPConnection* 
	AConnection, AnsiString AMsg, bool &VHandled, AnsiString &VReply);

typedef void __fastcall (__closure *TReceiveErrorEvent)(System::TObject* ASender, Idtcpconnection::TIdTCPConnection* 
	AConnection, AnsiString AMsg, Sysutils::Exception* AException, AnsiString &VReply, bool &VDropConnection
	);

class DELPHICLASS TIdHL7;
typedef void __fastcall (__closure *TIdHL7ConnCountEvent)(TIdHL7* ASender, int AConnCount);

class DELPHICLASS TIdHL7PeerThread;
class PASCALIMPLEMENTATION TIdHL7PeerThread : public Idtcpserver::TIdPeerThread 
{
	typedef Idtcpserver::TIdPeerThread inherited;
	
protected:
	AnsiString FBuffer;
	
public:
	__fastcall virtual TIdHL7PeerThread(bool ACreateSuspended);
	__fastcall virtual ~TIdHL7PeerThread(void);
};


class DELPHICLASS TIdHL7ClientThread;
class PASCALIMPLEMENTATION TIdHL7ClientThread : public Classes::TThread 
{
	typedef Classes::TThread inherited;
	
protected:
	Idtcpclient::TIdTCPClient* FClient;
	Idglobal::TIdLocalEvent* FCloseEvent;
	TIdHL7* FOwner;
	virtual void __fastcall Execute(void);
	void __fastcall PollStack(void);
	
public:
	__fastcall TIdHL7ClientThread(TIdHL7* aOwner);
	__fastcall virtual ~TIdHL7ClientThread(void);
};


class PASCALIMPLEMENTATION TIdHL7 : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	Syncobjs::TCriticalSection* FLock;
	TIdHL7Status FStatus;
	AnsiString FStatusDesc;
	Classes::TList* FMsgQueue;
	Classes::TList* FHndMsgQueue;
	AnsiString FAddress;
	THL7CommunicationMode FCommunicationMode;
	Word FConnectionLimit;
	AnsiString FIPMask;
	AnsiString FIPRestriction;
	bool FIsListener;
	System::TObject* FObject;
	bool FPreStopped;
	Word FPort;
	unsigned FReconnectDelay;
	unsigned FTimeOut;
	unsigned FReceiveTimeout;
	Classes::TNotifyEvent FOnConnect;
	Classes::TNotifyEvent FOnDisconnect;
	TIdHL7ConnCountEvent FOnConnCountChange;
	TMessageArriveEvent FOnMessageArrive;
	TMessageReceiveEvent FOnReceiveMessage;
	TReceiveErrorEvent FOnReceiveError;
	bool FIsServer;
	int FConnCount;
	Idtcpserver::TIdTCPServer* FServer;
	Idtcpserver::TIdTCPServerConnection* FServerConn;
	TIdHL7ClientThread* FClientThread;
	Idtcpclient::TIdTCPClient* FClient;
	bool FWaitingForAnswer;
	System::TDateTime FWaitStop;
	AnsiString FMsgReply;
	TSendResponse FReplyResponse;
	Idglobal::TIdLocalEvent* FWaitEvent;
	void __fastcall SetAddress(const AnsiString AValue);
	void __fastcall SetConnectionLimit(const Word AValue);
	void __fastcall SetIPMask(const AnsiString AValue);
	void __fastcall SetIPRestriction(const AnsiString AValue);
	void __fastcall SetPort(const Word AValue);
	void __fastcall SetReconnectDelay(const unsigned AValue);
	void __fastcall SetTimeOut(const unsigned AValue);
	void __fastcall SetCommunicationMode(const THL7CommunicationMode AValue);
	void __fastcall SetIsListener(const bool AValue);
	TIdHL7Status __fastcall GetStatus(void);
	AnsiString __fastcall GetStatusDesc();
	void __fastcall InternalSetStatus(const TIdHL7Status AStatus, AnsiString ADesc);
	void __fastcall CheckServerParameters(void);
	void __fastcall StartServer(void);
	void __fastcall StopServer(void);
	void __fastcall DropServerConnection(void);
	void __fastcall ServerConnect(Idtcpserver::TIdPeerThread* AThread);
	void __fastcall ServerExecute(Idtcpserver::TIdPeerThread* AThread);
	void __fastcall ServerDisconnect(Idtcpserver::TIdPeerThread* AThread);
	void __fastcall CheckClientParameters(void);
	void __fastcall StartClient(void);
	void __fastcall StopClient(void);
	void __fastcall DropClientConnection(void);
	void __fastcall HandleIncoming(AnsiString &VBuffer, Idtcpconnection::TIdTCPConnection* AConnection)
		;
	bool __fastcall HandleMessage(const AnsiString AMsg, Idtcpconnection::TIdTCPConnection* AConn, AnsiString 
		&VReply);
	
public:
	__fastcall virtual TIdHL7(Classes::TComponent* Component);
	__fastcall virtual ~TIdHL7(void);
	void __fastcall EnforceWaitReplyTimeout(void);
	bool __fastcall Going(void);
	__property System::TObject* ObjTag = {read=FObject, write=FObject};
	__property TIdHL7Status Status = {read=GetStatus, nodefault};
	__property AnsiString StatusDesc = {read=GetStatusDesc};
	bool __fastcall Connected(void);
	__property bool IsServer = {read=FIsServer, nodefault};
	void __fastcall Start(void);
	void __fastcall PreStop(void);
	void __fastcall Stop(void);
	void __fastcall WaitForConnection(int AMaxLength);
	TSendResponse __fastcall AsynchronousSend(AnsiString AMsg);
	__property TMessageArriveEvent OnMessageArrive = {read=FOnMessageArrive, write=FOnMessageArrive};
	TSendResponse __fastcall SynchronousSend(AnsiString AMsg, AnsiString &VReply);
	__property TMessageReceiveEvent OnReceiveMessage = {read=FOnReceiveMessage, write=FOnReceiveMessage
		};
	void __fastcall CheckSynchronousSendResult(TSendResponse AResult, AnsiString AMsg);
	void __fastcall SendMessage(AnsiString AMsg);
	TSendResponse __fastcall GetReply(AnsiString &VReply);
	void * __fastcall GetMessage(AnsiString &VMsg);
	void __fastcall SendReply(void * AMsgHnd, AnsiString AReply);
	
__published:
	__property AnsiString Address = {read=FAddress, write=SetAddress};
	__property Word Port = {read=FPort, write=SetPort, default=0};
	__property unsigned TimeOut = {read=FTimeOut, write=SetTimeOut, default=30000};
	__property unsigned ReceiveTimeout = {read=FReceiveTimeout, write=FReceiveTimeout, default=30000};
	__property Word ConnectionLimit = {read=FConnectionLimit, write=SetConnectionLimit, default=1};
	__property AnsiString IPRestriction = {read=FIPRestriction, write=SetIPRestriction};
	__property AnsiString IPMask = {read=FIPMask, write=SetIPMask};
	__property unsigned ReconnectDelay = {read=FReconnectDelay, write=SetReconnectDelay, default=15000}
		;
	__property THL7CommunicationMode CommunicationMode = {read=FCommunicationMode, write=SetCommunicationMode
		, default=0};
	__property bool IsListener = {read=FIsListener, write=SetIsListener, default=1};
	__property Classes::TNotifyEvent OnConnect = {read=FOnConnect, write=FOnConnect};
	__property Classes::TNotifyEvent OnDisconnect = {read=FOnDisconnect, write=FOnDisconnect};
	__property TIdHL7ConnCountEvent OnConnCountChange = {read=FOnConnCountChange, write=FOnConnCountChange
		};
	__property TReceiveErrorEvent OnReceiveError = {read=FOnReceiveError, write=FOnReceiveError};
};


//-- var, const, procedure ---------------------------------------------------
static const char MSG_START = '\xb';
#define MSG_END "\x1c\r"
static const int BUFFER_SIZE_LIMIT = 0x100000;
static const Word WAIT_STOP = 0x1388;
#define DEFAULT_ADDRESS ""
static const Shortint DEFAULT_PORT = 0x0;
static const Word DEFAULT_TIMEOUT = 0x7530;
static const Word DEFAULT_RECEIVE_TIMEOUT = 0x7530;
#define NULL_IP "0.0.0.0"
static const Shortint DEFAULT_CONN_LIMIT = 0x1;
static const Word DEFAULT_RECONNECT_DELAY = 0x3a98;
#define DEFAULT_COMM_MODE (THL7CommunicationMode)(0)
static const bool DEFAULT_IS_LISTENER = true;
static const Extended MILLISECOND_LENGTH = 1.157407E-08;
extern PACKAGE System::ResourceString _KdeVersionMark;
#define Idhl7_KdeVersionMark System::LoadResourceString(&Idhl7::_KdeVersionMark)

}	/* namespace Idhl7 */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idhl7;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdHL7
