// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTCPServer.pas' rev: 5.00

#ifndef IdTCPServerHPP
#define IdTCPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdServerIOHandlerSocket.hpp>	// Pascal unit
#include <IdServerIOHandler.hpp>	// Pascal unit
#include <IdRFCReply.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdIntercept.hpp>	// Pascal unit
#include <IdThreadMgrDefault.hpp>	// Pascal unit
#include <IdIOHandler.hpp>	// Pascal unit
#include <IdIOHandlerSocket.hpp>	// Pascal unit
#include <IdThreadMgr.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtcpserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdTCPServer;
class DELPHICLASS TIdPeerThread;
typedef void __fastcall (__closure *TIdAfterCommandHandlerEvent)(TIdTCPServer* ASender, TIdPeerThread* 
	AThread);

typedef void __fastcall (__closure *TIdBeforeCommandHandlerEvent)(TIdTCPServer* ASender, const AnsiString 
	AData, TIdPeerThread* AThread);

class DELPHICLASS TIdCommand;
typedef void __fastcall (__closure *TIdCommandEvent)(TIdCommand* ASender);

typedef void __fastcall (__closure *TIdNoCommandHandlerEvent)(TIdTCPServer* ASender, const AnsiString 
	AData, TIdPeerThread* AThread);

class DELPHICLASS TIdCommandHandler;
class PASCALIMPLEMENTATION TIdCommandHandler : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	char FCmdDelimiter;
	AnsiString FCommand;
	System::TObject* FData;
	bool FDisconnect;
	bool FEnabled;
	AnsiString FName;
	TIdCommandEvent FOnCommand;
	char FParamDelimiter;
	bool FParseParams;
	int FReplyExceptionCode;
	Idrfcreply::TIdRFCReply* FReplyNormal;
	Classes::TStrings* FResponse;
	int FTag;
	virtual AnsiString __fastcall GetDisplayName();
	virtual void __fastcall SetDisplayName(const AnsiString AValue);
	void __fastcall SetResponse(Classes::TStrings* AValue);
	
public:
	virtual bool __fastcall Check(const AnsiString AData, TIdPeerThread* AThread);
	__fastcall virtual TIdCommandHandler(Classes::TCollection* ACollection);
	__fastcall virtual ~TIdCommandHandler(void);
	DYNAMIC AnsiString __fastcall GetNamePath();
	bool __fastcall NameIs(AnsiString ACommand);
	__property System::TObject* Data = {read=FData, write=FData};
	
__published:
	__property char CmdDelimiter = {read=FCmdDelimiter, write=FCmdDelimiter, nodefault};
	__property AnsiString Command = {read=FCommand, write=FCommand};
	__property bool Disconnect = {read=FDisconnect, write=FDisconnect, nodefault};
	__property bool Enabled = {read=FEnabled, write=FEnabled, default=1};
	__property AnsiString Name = {read=FName, write=FName};
	__property TIdCommandEvent OnCommand = {read=FOnCommand, write=FOnCommand};
	__property char ParamDelimiter = {read=FParamDelimiter, write=FParamDelimiter, nodefault};
	__property bool ParseParams = {read=FParseParams, write=FParseParams, default=1};
	__property int ReplyExceptionCode = {read=FReplyExceptionCode, write=FReplyExceptionCode, nodefault
		};
	__property Idrfcreply::TIdRFCReply* ReplyNormal = {read=FReplyNormal, write=FReplyNormal};
	__property Classes::TStrings* Response = {read=FResponse, write=SetResponse};
	__property int Tag = {read=FTag, write=FTag, nodefault};
};


class DELPHICLASS TIdCommandHandlers;
class PASCALIMPLEMENTATION TIdCommandHandlers : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	TIdTCPServer* FServer;
	HIDESBASE TIdCommandHandler* __fastcall GetItem(int AIndex);
	Classes::TPersistent* __fastcall GetOwnedBy(void);
	HIDESBASE void __fastcall SetItem(int AIndex, const TIdCommandHandler* AValue);
	
public:
	HIDESBASE TIdCommandHandler* __fastcall Add(void);
	__fastcall TIdCommandHandlers(TIdTCPServer* AServer);
	__property TIdCommandHandler* Items[int AIndex] = {read=GetItem, write=SetItem};
	__property Classes::TPersistent* OwnedBy = {read=GetOwnedBy};
	__property TIdTCPServer* Server = {read=FServer};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdCommandHandlers(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdCommand : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	TIdCommandHandler* FCommandHandler;
	Classes::TStrings* FParams;
	bool FPerformReply;
	AnsiString FRawLine;
	Idrfcreply::TIdRFCReply* FReply;
	Classes::TStrings* FResponse;
	TIdPeerThread* FThread;
	AnsiString FUnparsedParams;
	virtual void __fastcall DoCommand(void);
	
public:
	__fastcall virtual TIdCommand(void);
	__fastcall virtual ~TIdCommand(void);
	void __fastcall SendReply(void);
	void __fastcall SetResponse(Classes::TStrings* AValue);
	__property TIdCommandHandler* CommandHandler = {read=FCommandHandler};
	__property bool PerformReply = {read=FPerformReply, write=FPerformReply, nodefault};
	__property Classes::TStrings* Params = {read=FParams};
	__property AnsiString RawLine = {read=FRawLine};
	__property Idrfcreply::TIdRFCReply* Reply = {read=FReply, write=FReply};
	__property Classes::TStrings* Response = {read=FResponse, write=SetResponse};
	__property TIdPeerThread* Thread = {read=FThread};
	__property AnsiString UnparsedParams = {read=FUnparsedParams};
};


class DELPHICLASS TIdListenerThread;
class PASCALIMPLEMENTATION TIdListenerThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	Idsockethandle::TIdSocketHandle* FBinding;
	TIdTCPServer* FServer;
	virtual void __fastcall AfterRun(void);
	virtual void __fastcall Run(void);
	
public:
	__fastcall TIdListenerThread(TIdTCPServer* AServer, Idsockethandle::TIdSocketHandle* ABinding);
	__property Idsockethandle::TIdSocketHandle* Binding = {read=FBinding, write=FBinding};
	__property TIdTCPServer* Server = {read=FServer};
public:
	#pragma option push -w-inl
	/* TIdThread.Destroy */ inline __fastcall virtual ~TIdListenerThread(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdTCPServerConnection;
class PASCALIMPLEMENTATION TIdTCPServerConnection : public Idtcpconnection::TIdTCPConnection 
{
	typedef Idtcpconnection::TIdTCPConnection inherited;
	
protected:
	TIdTCPServer* FServer;
	
public:
	__fastcall TIdTCPServerConnection(TIdTCPServer* AServer);
	
__published:
	__property TIdTCPServer* Server = {read=FServer};
public:
	#pragma option push -w-inl
	/* TIdTCPConnection.Destroy */ inline __fastcall virtual ~TIdTCPServerConnection(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdPeerThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	TIdTCPServerConnection* FConnection;
	virtual void __fastcall AfterRun(void);
	virtual void __fastcall BeforeRun(void);
	virtual void __fastcall Cleanup(void);
	virtual void __fastcall Run(void);
	
public:
	__property TIdTCPServerConnection* Connection = {read=FConnection};
public:
	#pragma option push -w-inl
	/* TIdThread.Create */ inline __fastcall virtual TIdPeerThread(bool ACreateSuspended) : Idthread::TIdThread(
		ACreateSuspended) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdThread.Destroy */ inline __fastcall virtual ~TIdPeerThread(void) { }
	#pragma option pop
	
};


typedef void __fastcall (__closure *TIdListenExceptionEvent)(TIdListenerThread* AThread, Sysutils::Exception* 
	AException);

typedef void __fastcall (__closure *TIdServerThreadExceptionEvent)(TIdPeerThread* AThread, Sysutils::Exception* 
	AException);

typedef void __fastcall (__closure *TIdServerThreadEvent)(TIdPeerThread* AThread);

class PASCALIMPLEMENTATION TIdTCPServer : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	bool FActive;
	Idthreadmgr::TIdThreadMgr* FThreadMgr;
	Idsockethandle::TIdSocketHandles* FBindings;
	TIdCommandHandlers* FCommandHandlers;
	bool FCommandHandlersEnabled;
	bool FCommandHandlersInitialized;
	Idrfcreply::TIdRFCReply* FGreeting;
	bool FImplicitThreadMgr;
	bool FImplicitIOHandler;
	Idintercept::TIdServerIntercept* FIntercept;
	Idserveriohandler::TIdServerIOHandler* FIOHandler;
	Classes::TThreadList* FListenerThreads;
	int FListenQueue;
	Idrfcreply::TIdRFCReply* FMaxConnectionReply;
	int FMaxConnections;
	Idrfcreply::TIdRFCReplies* FReplyTexts;
	Idglobal::TIdReuseSocket FReuseSocket;
	int FTerminateWaitTime;
	TMetaClass*FThreadClass;
	Classes::TThreadList* FThreads;
	TIdAfterCommandHandlerEvent FOnAfterCommandHandler;
	TIdBeforeCommandHandlerEvent FOnBeforeCommandHandler;
	TIdServerThreadEvent FOnConnect;
	TIdServerThreadEvent FOnDisconnect;
	TIdServerThreadExceptionEvent FOnException;
	TIdServerThreadEvent FOnExecute;
	TIdListenExceptionEvent FOnListenException;
	TIdNoCommandHandlerEvent FOnNoCommandHandler;
	int FReplyExceptionCode;
	Idrfcreply::TIdRFCReply* FReplyUnknownCommand;
	void __fastcall CheckActive(void);
	void __fastcall DoAfterCommandHandler(TIdPeerThread* AThread);
	void __fastcall DoBeforeCommandHandler(TIdPeerThread* AThread, const AnsiString ALine);
	virtual void __fastcall DoConnect(TIdPeerThread* AThread);
	virtual void __fastcall DoDisconnect(TIdPeerThread* AThread);
	void __fastcall DoException(TIdPeerThread* AThread, Sysutils::Exception* AException);
	virtual bool __fastcall DoExecute(TIdPeerThread* AThread);
	void __fastcall DoListenException(TIdListenerThread* AThread, Sysutils::Exception* AException);
	void __fastcall DoOnNoCommandHandler(const AnsiString AData, TIdPeerThread* AThread);
	int __fastcall GetDefaultPort(void);
	Idthreadmgr::TIdThreadMgr* __fastcall GetThreadMgr(void);
	virtual void __fastcall InitializeCommandHandlers(void);
	virtual void __fastcall Notification(Classes::TComponent* AComponent, Classes::TOperation Operation
		);
	virtual void __fastcall SetActive(bool AValue);
	virtual void __fastcall SetBindings(const Idsockethandle::TIdSocketHandles* AValue);
	virtual void __fastcall SetDefaultPort(const int AValue);
	virtual void __fastcall SetIntercept(const Idintercept::TIdServerIntercept* AValue);
	virtual void __fastcall SetIOHandler(const Idserveriohandler::TIdServerIOHandler* AValue);
	virtual void __fastcall SetThreadMgr(const Idthreadmgr::TIdThreadMgr* AValue);
	void __fastcall TerminateAllThreads(void);
	void __fastcall TerminateListenerThreads(void);
	
public:
	__fastcall virtual TIdTCPServer(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdTCPServer(void);
	virtual void __fastcall Loaded(void);
	__property bool ImplicitIOHandler = {read=FImplicitIOHandler, nodefault};
	__property bool ImplicitThreadMgr = {read=FImplicitThreadMgr, nodefault};
	__property TMetaClass* ThreadClass = {read=FThreadClass, write=FThreadClass};
	__property Classes::TThreadList* Threads = {read=FThreads};
	
__published:
	__property bool Active = {read=FActive, write=SetActive, default=0};
	__property Idsockethandle::TIdSocketHandles* Bindings = {read=FBindings, write=SetBindings};
	__property TIdCommandHandlers* CommandHandlers = {read=FCommandHandlers, write=FCommandHandlers};
	__property bool CommandHandlersEnabled = {read=FCommandHandlersEnabled, write=FCommandHandlersEnabled
		, default=1};
	__property int DefaultPort = {read=GetDefaultPort, write=SetDefaultPort, nodefault};
	__property Idrfcreply::TIdRFCReply* Greeting = {read=FGreeting, write=FGreeting};
	__property Idintercept::TIdServerIntercept* Intercept = {read=FIntercept, write=SetIntercept};
	__property Idserveriohandler::TIdServerIOHandler* IOHandler = {read=FIOHandler, write=SetIOHandler}
		;
	__property int ListenQueue = {read=FListenQueue, write=FListenQueue, default=15};
	__property Idrfcreply::TIdRFCReply* MaxConnectionReply = {read=FMaxConnectionReply, write=FMaxConnectionReply
		};
	__property int MaxConnections = {read=FMaxConnections, write=FMaxConnections, default=0};
	__property TIdAfterCommandHandlerEvent OnAfterCommandHandler = {read=FOnAfterCommandHandler, write=
		FOnAfterCommandHandler};
	__property TIdBeforeCommandHandlerEvent OnBeforeCommandHandler = {read=FOnBeforeCommandHandler, write=
		FOnBeforeCommandHandler};
	__property TIdServerThreadEvent OnConnect = {read=FOnConnect, write=FOnConnect};
	__property TIdServerThreadEvent OnExecute = {read=FOnExecute, write=FOnExecute};
	__property TIdServerThreadEvent OnDisconnect = {read=FOnDisconnect, write=FOnDisconnect};
	__property TIdServerThreadExceptionEvent OnException = {read=FOnException, write=FOnException};
	__property TIdListenExceptionEvent OnListenException = {read=FOnListenException, write=FOnListenException
		};
	__property TIdNoCommandHandlerEvent OnNoCommandHandler = {read=FOnNoCommandHandler, write=FOnNoCommandHandler
		};
	__property int ReplyExceptionCode = {read=FReplyExceptionCode, write=FReplyExceptionCode, nodefault
		};
	__property Idrfcreply::TIdRFCReplies* ReplyTexts = {read=FReplyTexts, write=FReplyTexts};
	__property Idrfcreply::TIdRFCReply* ReplyUnknownCommand = {read=FReplyUnknownCommand, write=FReplyUnknownCommand
		};
	__property Idglobal::TIdReuseSocket ReuseSocket = {read=FReuseSocket, write=FReuseSocket, default=0
		};
	__property int TerminateWaitTime = {read=FTerminateWaitTime, write=FTerminateWaitTime, default=5000
		};
	__property Idthreadmgr::TIdThreadMgr* ThreadMgr = {read=GetThreadMgr, write=SetThreadMgr};
};


class DELPHICLASS EIdTCPServerError;
class PASCALIMPLEMENTATION EIdTCPServerError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTCPServerError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTCPServerError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTCPServerError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTCPServerError(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTCPServerError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTCPServerError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTCPServerError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTCPServerError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTCPServerError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNoExecuteSpecified;
class PASCALIMPLEMENTATION EIdNoExecuteSpecified : public EIdTCPServerError 
{
	typedef EIdTCPServerError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNoExecuteSpecified(const AnsiString Msg) : EIdTCPServerError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNoExecuteSpecified(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPServerError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNoExecuteSpecified(int Ident)/* overload */ : EIdTCPServerError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNoExecuteSpecified(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTCPServerError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNoExecuteSpecified(const AnsiString Msg, int AHelpContext
		) : EIdTCPServerError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNoExecuteSpecified(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdTCPServerError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNoExecuteSpecified(int Ident, int AHelpContext)/* overload */
		 : EIdTCPServerError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNoExecuteSpecified(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdTCPServerError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNoExecuteSpecified(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTerminateThreadTimeout;
class PASCALIMPLEMENTATION EIdTerminateThreadTimeout : public EIdTCPServerError 
{
	typedef EIdTCPServerError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTerminateThreadTimeout(const AnsiString Msg) : EIdTCPServerError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTerminateThreadTimeout(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdTCPServerError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTerminateThreadTimeout(int Ident)/* overload */ : EIdTCPServerError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTerminateThreadTimeout(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTCPServerError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTerminateThreadTimeout(const AnsiString Msg, int AHelpContext
		) : EIdTCPServerError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTerminateThreadTimeout(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTCPServerError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTerminateThreadTimeout(int Ident, int AHelpContext
		)/* overload */ : EIdTCPServerError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTerminateThreadTimeout(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTCPServerError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTerminateThreadTimeout(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const bool IdEnabledDefault = true;
static const bool IdParseParamsDefault = true;
static const bool IdCommandHandlersEnabledDefault = true;
static const Shortint IdListenQueueDefault = 0xf;

}	/* namespace Idtcpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtcpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTCPServer
