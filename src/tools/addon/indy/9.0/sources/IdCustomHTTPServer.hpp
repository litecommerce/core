// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCustomHTTPServer.pas' rev: 5.00

#ifndef IdCustomHTTPServerHPP
#define IdCustomHTTPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdHTTPHeaderInfo.hpp>	// Pascal unit
#include <IdCookie.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdHeaderList.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcustomhttpserver
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdHTTPSession;
typedef void __fastcall (__closure *TOnSessionEndEvent)(TIdHTTPSession* Sender);

typedef void __fastcall (__closure *TOnSessionStartEvent)(TIdHTTPSession* Sender);

typedef void __fastcall (__closure *TOnCreateSession)(Idtcpserver::TIdPeerThread* ASender, TIdHTTPSession* 
	&VHTTPSession);

typedef void __fastcall (__closure *TOnCreatePostStream)(Idtcpserver::TIdPeerThread* ASender, Classes::TStream* 
	&VPostStream);

class DELPHICLASS TIdHTTPRequestInfo;
class DELPHICLASS TIdHTTPResponseInfo;
typedef void __fastcall (__closure *TIdHTTPGetEvent)(Idtcpserver::TIdPeerThread* AThread, TIdHTTPRequestInfo* 
	ARequestInfo, TIdHTTPResponseInfo* AResponseInfo);

typedef void __fastcall (__closure *TIdHTTPOtherEvent)(Idtcpserver::TIdPeerThread* Thread, const AnsiString 
	asCommand, const AnsiString asData, const AnsiString asVersion);

typedef void __fastcall (__closure *TIdHTTPInvalidSessionEvent)(Idtcpserver::TIdPeerThread* Thread, 
	TIdHTTPRequestInfo* ARequestInfo, TIdHTTPResponseInfo* AResponseInfo, bool &VContinueProcessing, const 
	AnsiString AInvalidSessionID);

class DELPHICLASS EIdHTTPServerError;
class PASCALIMPLEMENTATION EIdHTTPServerError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdHTTPServerError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdHTTPServerError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdHTTPServerError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdHTTPServerError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdHTTPServerError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdHTTPServerError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdHTTPServerError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdHTTPServerError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdHTTPServerError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdHTTPHeaderAlreadyWritten;
class PASCALIMPLEMENTATION EIdHTTPHeaderAlreadyWritten : public EIdHTTPServerError 
{
	typedef EIdHTTPServerError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdHTTPHeaderAlreadyWritten(const AnsiString Msg) : EIdHTTPServerError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdHTTPHeaderAlreadyWritten(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdHTTPServerError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdHTTPHeaderAlreadyWritten(int Ident)/* overload */ : 
		EIdHTTPServerError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdHTTPHeaderAlreadyWritten(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdHTTPServerError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdHTTPHeaderAlreadyWritten(const AnsiString Msg, int 
		AHelpContext) : EIdHTTPServerError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdHTTPHeaderAlreadyWritten(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdHTTPServerError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdHTTPHeaderAlreadyWritten(int Ident, int AHelpContext
		)/* overload */ : EIdHTTPServerError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdHTTPHeaderAlreadyWritten(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdHTTPServerError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdHTTPHeaderAlreadyWritten(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdHTTPErrorParsingCommand;
class PASCALIMPLEMENTATION EIdHTTPErrorParsingCommand : public EIdHTTPServerError 
{
	typedef EIdHTTPServerError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdHTTPErrorParsingCommand(const AnsiString Msg) : EIdHTTPServerError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdHTTPErrorParsingCommand(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdHTTPServerError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdHTTPErrorParsingCommand(int Ident)/* overload */ : EIdHTTPServerError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdHTTPErrorParsingCommand(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdHTTPServerError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdHTTPErrorParsingCommand(const AnsiString Msg, int AHelpContext
		) : EIdHTTPServerError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdHTTPErrorParsingCommand(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdHTTPServerError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdHTTPErrorParsingCommand(int Ident, int AHelpContext
		)/* overload */ : EIdHTTPServerError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdHTTPErrorParsingCommand(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdHTTPServerError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdHTTPErrorParsingCommand(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdHTTPUnsupportedAuthorisationScheme;
class PASCALIMPLEMENTATION EIdHTTPUnsupportedAuthorisationScheme : public EIdHTTPServerError 
{
	typedef EIdHTTPServerError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(const AnsiString Msg
		) : EIdHTTPServerError(Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size) : EIdHTTPServerError(Msg, Args, Args_Size)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(int Ident)/* overload */
		 : EIdHTTPServerError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(int Ident, const 
		System::TVarRec * Args, const int Args_Size)/* overload */ : EIdHTTPServerError(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(const AnsiString 
		Msg, int AHelpContext) : EIdHTTPServerError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdHTTPServerError(Msg
		, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(int Ident, int 
		AHelpContext)/* overload */ : EIdHTTPServerError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdHTTPUnsupportedAuthorisationScheme(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdHTTPServerError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdHTTPUnsupportedAuthorisationScheme(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdHTTPCannotSwitchSessionStateWhenActive;
class PASCALIMPLEMENTATION EIdHTTPCannotSwitchSessionStateWhenActive : public EIdHTTPServerError 
{
	typedef EIdHTTPServerError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(const AnsiString 
		Msg) : EIdHTTPServerError(Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size) : EIdHTTPServerError(Msg, Args, Args_Size)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(int Ident)/* overload */
		 : EIdHTTPServerError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(int Ident, 
		const System::TVarRec * Args, const int Args_Size)/* overload */ : EIdHTTPServerError(Ident, Args, 
		Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(const AnsiString 
		Msg, int AHelpContext) : EIdHTTPServerError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdHTTPServerError(Msg
		, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(int Ident
		, int AHelpContext)/* overload */ : EIdHTTPServerError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdHTTPCannotSwitchSessionStateWhenActive(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdHTTPServerError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdHTTPCannotSwitchSessionStateWhenActive(void) { }
		
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdHTTPRequestInfo : public Idhttpheaderinfo::TIdRequestHeaderInfo 
{
	typedef Idhttpheaderinfo::TIdRequestHeaderInfo inherited;
	
protected:
	bool FAuthExists;
	Idcookie::TIdServerCookies* FCookies;
	Classes::TStrings* FParams;
	Classes::TStream* FPostStream;
	AnsiString FRawHTTPCommand;
	AnsiString FRemoteIP;
	TIdHTTPSession* FSession;
	AnsiString FDocument;
	AnsiString FCommand;
	AnsiString FVersion;
	AnsiString FAuthUsername;
	AnsiString FAuthPassword;
	AnsiString FUnparsedParams;
	AnsiString FQueryParams;
	AnsiString FFormParams;
	void __fastcall DecodeAndSetParams(const AnsiString AValue);
	
public:
	__fastcall virtual TIdHTTPRequestInfo(void);
	__fastcall virtual ~TIdHTTPRequestInfo(void);
	__property TIdHTTPSession* Session = {read=FSession};
	__property bool AuthExists = {read=FAuthExists, nodefault};
	__property AnsiString AuthPassword = {read=FAuthPassword};
	__property AnsiString AuthUsername = {read=FAuthUsername};
	__property AnsiString Command = {read=FCommand};
	__property Idcookie::TIdServerCookies* Cookies = {read=FCookies};
	__property AnsiString Document = {read=FDocument, write=FDocument};
	__property Classes::TStrings* Params = {read=FParams};
	__property Classes::TStream* PostStream = {read=FPostStream, write=FPostStream};
	__property AnsiString RawHTTPCommand = {read=FRawHTTPCommand};
	__property AnsiString RemoteIP = {read=FRemoteIP};
	__property AnsiString UnparsedParams = {read=FUnparsedParams, write=FUnparsedParams};
	__property AnsiString FormParams = {read=FFormParams, write=FFormParams};
	__property AnsiString QueryParams = {read=FQueryParams, write=FQueryParams};
	__property AnsiString Version = {read=FVersion};
};


class PASCALIMPLEMENTATION TIdHTTPResponseInfo : public Idhttpheaderinfo::TIdResponseHeaderInfo 
{
	typedef Idhttpheaderinfo::TIdResponseHeaderInfo inherited;
	
protected:
	AnsiString FAuthRealm;
	AnsiString FContentType;
	Idtcpserver::TIdTCPServerConnection* FConnection;
	int FResponseNo;
	Idcookie::TIdServerCookies* FCookies;
	Classes::TStream* FContentStream;
	AnsiString FContentText;
	bool FCloseConnection;
	bool FFreeContentStream;
	bool FHeaderHasBeenWritten;
	AnsiString FResponseText;
	TIdHTTPSession* FSession;
	void __fastcall ReleaseContentStream(void);
	void __fastcall SetCookies(const Idcookie::TIdServerCookies* AValue);
	virtual void __fastcall SetHeaders(void);
	void __fastcall SetResponseNo(const int AValue);
	void __fastcall SetCloseConnection(const bool Value);
	
public:
	void __fastcall CloseSession(void);
	__fastcall TIdHTTPResponseInfo(Idtcpserver::TIdTCPServerConnection* AConnection);
	__fastcall virtual ~TIdHTTPResponseInfo(void);
	void __fastcall Redirect(const AnsiString AURL);
	void __fastcall WriteHeader(void);
	void __fastcall WriteContent(void);
	__property AnsiString AuthRealm = {read=FAuthRealm, write=FAuthRealm};
	__property bool CloseConnection = {read=FCloseConnection, write=SetCloseConnection, nodefault};
	__property Classes::TStream* ContentStream = {read=FContentStream, write=FContentStream};
	__property AnsiString ContentText = {read=FContentText, write=FContentText};
	__property Idcookie::TIdServerCookies* Cookies = {read=FCookies, write=SetCookies};
	__property bool FreeContentStream = {read=FFreeContentStream, write=FFreeContentStream, nodefault};
		
	__property bool HeaderHasBeenWritten = {read=FHeaderHasBeenWritten, write=FHeaderHasBeenWritten, nodefault
		};
	__property int ResponseNo = {read=FResponseNo, write=SetResponseNo, nodefault};
	__property AnsiString ResponseText = {read=FResponseText, write=FResponseText};
	__property AnsiString ServerSoftware = {read=FServer, write=FServer};
	__property TIdHTTPSession* Session = {read=FSession};
};


class DELPHICLASS TIdHTTPCustomSessionList;
class PASCALIMPLEMENTATION TIdHTTPCustomSessionList : public Classes::TComponent 
{
	typedef Classes::TComponent inherited;
	
private:
	int FSessionTimeout;
	TOnSessionEndEvent FOnSessionEnd;
	TOnSessionStartEvent FOnSessionStart;
	
protected:
	virtual void __fastcall RemoveSession(TIdHTTPSession* Session) = 0 ;
	
public:
	virtual void __fastcall Clear(void) = 0 ;
	virtual void __fastcall PurgeStaleSessions(bool PurgeAll) = 0 ;
	virtual TIdHTTPSession* __fastcall CreateUniqueSession(const AnsiString RemoteIP) = 0 ;
	virtual TIdHTTPSession* __fastcall CreateSession(const AnsiString RemoteIP, const AnsiString SessionID
		) = 0 ;
	virtual TIdHTTPSession* __fastcall GetSession(const AnsiString SessionID, const AnsiString RemoteIP
		) = 0 ;
	virtual void __fastcall Add(TIdHTTPSession* ASession) = 0 ;
	
__published:
	__property int SessionTimeout = {read=FSessionTimeout, write=FSessionTimeout, nodefault};
	__property TOnSessionEndEvent OnSessionEnd = {read=FOnSessionEnd, write=FOnSessionEnd};
	__property TOnSessionStartEvent OnSessionStart = {read=FOnSessionStart, write=FOnSessionStart};
public:
		
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdHTTPCustomSessionList(Classes::TComponent* AOwner
		) : Classes::TComponent(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdHTTPCustomSessionList(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdHTTPSession : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Classes::TStrings* FContent;
	System::TDateTime FLastTimeStamp;
	Syncobjs::TCriticalSection* FLock;
	TIdHTTPCustomSessionList* FOwner;
	AnsiString FSessionID;
	AnsiString FRemoteHost;
	void __fastcall SetContent(const Classes::TStrings* Value);
	Classes::TStrings* __fastcall GetContent(void);
	virtual bool __fastcall IsSessionStale(void);
	virtual void __fastcall DoSessionEnd(void);
	
public:
	__fastcall virtual TIdHTTPSession(TIdHTTPCustomSessionList* AOwner);
	__fastcall virtual TIdHTTPSession(TIdHTTPCustomSessionList* AOwner, const AnsiString SessionID, const 
		AnsiString RemoteIP);
	__fastcall virtual ~TIdHTTPSession(void);
	void __fastcall Lock(void);
	void __fastcall Unlock(void);
	__property Classes::TStrings* Content = {read=GetContent, write=SetContent};
	__property System::TDateTime LastTimeStamp = {read=FLastTimeStamp};
	__property AnsiString RemoteHost = {read=FRemoteHost};
	__property AnsiString SessionID = {read=FSessionID};
};


class DELPHICLASS TIdCustomHTTPServer;
class PASCALIMPLEMENTATION TIdCustomHTTPServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	bool FAutoStartSession;
	bool FKeepAlive;
	bool FParseParams;
	AnsiString FServerSoftware;
	Idglobal::TIdMimeTable* FMIMETable;
	TIdHTTPCustomSessionList* FSessionList;
	bool FSessionState;
	int FSessionTimeOut;
	bool FOkToProcessCommand;
	TOnCreatePostStream FOnCreatePostStream;
	TOnCreateSession FOnCreateSession;
	TIdHTTPInvalidSessionEvent FOnInvalidSession;
	TOnSessionEndEvent FOnSessionEnd;
	TOnSessionStartEvent FOnSessionStart;
	TIdHTTPGetEvent FOnCommandGet;
	TIdHTTPOtherEvent FOnCommandOther;
	Idthread::TIdThread* FSessionCleanupThread;
	virtual void __fastcall DoOnCreateSession(Idtcpserver::TIdPeerThread* AThread, TIdHTTPSession* &VNewSession
		);
	virtual void __fastcall DoInvalidSession(Idtcpserver::TIdPeerThread* AThread, TIdHTTPRequestInfo* ARequestInfo
		, TIdHTTPResponseInfo* AResponseInfo, bool &VContinueProcessing, const AnsiString AInvalidSessionID
		);
	virtual void __fastcall DoCommandOther(Idtcpserver::TIdPeerThread* AThread, const AnsiString asCommand
		, const AnsiString asData, const AnsiString asVersion);
	virtual void __fastcall DoCommandGet(Idtcpserver::TIdPeerThread* AThread, TIdHTTPRequestInfo* ARequestInfo
		, TIdHTTPResponseInfo* AResponseInfo);
	virtual void __fastcall CreatePostStream(Idtcpserver::TIdPeerThread* ASender, Classes::TStream* &VPostStream
		);
	void __fastcall DoCreatePostStream(Idtcpserver::TIdPeerThread* ASender, Classes::TStream* &VPostStream
		);
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* AThread);
	virtual void __fastcall SetActive(bool AValue);
	void __fastcall SetSessionState(const bool Value);
	TIdHTTPSession* __fastcall GetSessionFromCookie(Idtcpserver::TIdPeerThread* AThread, TIdHTTPRequestInfo* 
		AHTTPrequest, TIdHTTPResponseInfo* AHTTPResponse, bool &VContinueProcessing);
	__property TOnCreatePostStream OnCreatePostStream = {read=FOnCreatePostStream, write=FOnCreatePostStream
		};
	__property TIdHTTPGetEvent OnCommandGet = {read=FOnCommandGet, write=FOnCommandGet};
	
public:
	__fastcall virtual TIdCustomHTTPServer(Classes::TComponent* AOwner);
	TIdHTTPSession* __fastcall CreateSession(Idtcpserver::TIdPeerThread* AThread, TIdHTTPResponseInfo* 
		HTTPResponse, TIdHTTPRequestInfo* HTTPRequest);
	__fastcall virtual ~TIdCustomHTTPServer(void);
	bool __fastcall EndSession(const AnsiString SessionName);
	virtual unsigned __fastcall ServeFile(Idtcpserver::TIdPeerThread* AThread, TIdHTTPResponseInfo* ResponseInfo
		, AnsiString aFile);
	__property Idglobal::TIdMimeTable* MIMETable = {read=FMIMETable};
	__property TIdHTTPCustomSessionList* SessionList = {read=FSessionList};
	
__published:
	__property bool AutoStartSession = {read=FAutoStartSession, write=FAutoStartSession, default=0};
	__property DefaultPort ;
	__property TIdHTTPInvalidSessionEvent OnInvalidSession = {read=FOnInvalidSession, write=FOnInvalidSession
		};
	__property TOnSessionStartEvent OnSessionStart = {read=FOnSessionStart, write=FOnSessionStart};
	__property TOnSessionEndEvent OnSessionEnd = {read=FOnSessionEnd, write=FOnSessionEnd};
	__property TOnCreateSession OnCreateSession = {read=FOnCreateSession, write=FOnCreateSession};
	__property bool KeepAlive = {read=FKeepAlive, write=FKeepAlive, default=0};
	__property bool ParseParams = {read=FParseParams, write=FParseParams, default=1};
	__property AnsiString ServerSoftware = {read=FServerSoftware, write=FServerSoftware};
	__property bool SessionState = {read=FSessionState, write=SetSessionState, default=0};
	__property int SessionTimeOut = {read=FSessionTimeOut, write=FSessionTimeOut, default=0};
	__property TIdHTTPOtherEvent OnCommandOther = {read=FOnCommandOther, write=FOnCommandOther};
};


class DELPHICLASS TIdHTTPDefaultSessionList;
class PASCALIMPLEMENTATION TIdHTTPDefaultSessionList : public TIdHTTPCustomSessionList 
{
	typedef TIdHTTPCustomSessionList inherited;
	
protected:
	Classes::TThreadList* SessionList;
	virtual void __fastcall RemoveSession(TIdHTTPSession* Session);
	void __fastcall RemoveSessionFromLockedList(int AIndex, Classes::TList* ALockedSessionList);
	
public:
	__fastcall virtual TIdHTTPDefaultSessionList(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdHTTPDefaultSessionList(void);
	virtual void __fastcall Clear(void);
	virtual void __fastcall Add(TIdHTTPSession* ASession);
	virtual void __fastcall PurgeStaleSessions(bool PurgeAll);
	virtual TIdHTTPSession* __fastcall CreateUniqueSession(const AnsiString RemoteIP);
	virtual TIdHTTPSession* __fastcall CreateSession(const AnsiString RemoteIP, const AnsiString SessionID
		);
	virtual TIdHTTPSession* __fastcall GetSession(const AnsiString SessionID, const AnsiString RemoteIP
		);
};


//-- var, const, procedure ---------------------------------------------------
static const bool Id_TId_HTTPServer_KeepAlive = false;
static const bool Id_TId_HTTPServer_ParseParams = true;
static const bool Id_TId_HTTPServer_SessionState = false;
static const Shortint Id_TId_HTTPSessionTimeOut = 0x0;
static const bool Id_TId_HTTPAutoStartSession = false;
static const Byte GResponseNo = 0xc8;
static const Shortint GFContentLength = 0xffffffff;
#define GServerSoftware "Indy/9.0.14"
#define GContentType "text/html"
#define GSessionIDCookie "IDHTTPSESSIONID"

}	/* namespace Idcustomhttpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcustomhttpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCustomHTTPServer
