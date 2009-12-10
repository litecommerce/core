// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSMTPServer.pas' rev: 5.00

#ifndef IdSMTPServerHPP
#define IdSMTPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdThread.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdIOHandlerSocket.hpp>	// Pascal unit
#include <IdMessageClient.hpp>	// Pascal unit
#include <IdCoderMIME.hpp>	// Pascal unit
#include <IdEMailAddress.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsmtpserver
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TOnReceiveRaw)(Idtcpserver::TIdCommand* ASender, Classes::TStream* 
	&VStream, Idemailaddress::TIdEMailAddressList* RCPT, AnsiString &CustomError);

typedef void __fastcall (__closure *TOnReceiveMessage)(Idtcpserver::TIdCommand* ASender, Idmessage::TIdMessage* 
	&AMsg, Idemailaddress::TIdEMailAddressList* RCPT, AnsiString &CustomError);

typedef void __fastcall (__closure *TBasicHandler)(Idtcpserver::TIdCommand* ASender);

typedef void __fastcall (__closure *TUserHandler)(Idtcpserver::TIdCommand* ASender, bool &Accept, AnsiString 
	Username, AnsiString Password);

typedef void __fastcall (__closure *THasAddress)(const Idtcpserver::TIdCommand* ASender, bool &Accept
	, bool &ToForward, AnsiString EMailAddress, AnsiString &CustomError);

typedef void __fastcall (__closure *THasAddress2)(const Idtcpserver::TIdCommand* ASender, bool &Accept
	, AnsiString EMailAddress);

#pragma option push -b-
enum TIdSMTPReceiveMode { rmRaw, rmMessage, rmMessageParsed };
#pragma option pop

#pragma option push -b-
enum TIdStreamType { stFileStream, stMemoryStream };
#pragma option pop

class DELPHICLASS TIdSMTPGreeting;
class PASCALIMPLEMENTATION TIdSMTPGreeting : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString fHelloReply;
	AnsiString fNoHello;
	AnsiString fAuthFailed;
	Classes::TStrings* fEHLOReply;
	AnsiString fEHLONotSupported;
	void __fastcall SetEHLOReply(const Classes::TStrings* AValue);
	
public:
	__fastcall TIdSMTPGreeting(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__fastcall virtual ~TIdSMTPGreeting(void);
	
__published:
	__property AnsiString EHLONotSupported = {read=fEHLONotSupported, write=fEHLONotSupported};
	__property AnsiString HelloReply = {read=fHelloReply, write=fHelloReply};
	__property AnsiString NoHello = {read=fNoHello, write=fNoHello};
	__property AnsiString AuthFailed = {read=fAuthFailed, write=fAuthFailed};
	__property Classes::TStrings* EHLOReply = {read=fEHLOReply, write=SetEHLOReply};
};


class DELPHICLASS TIdSMTPRcpReplies;
class PASCALIMPLEMENTATION TIdSMTPRcpReplies : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString fAddressOkReply;
	AnsiString FAddressErrorReply;
	AnsiString FAddressWillForwardReply;
	
public:
	__fastcall TIdSMTPRcpReplies(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property AnsiString AddressOkReply = {read=fAddressOkReply, write=fAddressOkReply};
	__property AnsiString AddressErrorReply = {read=FAddressErrorReply, write=FAddressErrorReply};
	__property AnsiString AddressWillForwardReply = {read=FAddressWillForwardReply, write=FAddressWillForwardReply
		};
public:
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdSMTPRcpReplies(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdSMTPDataReplies;
class PASCALIMPLEMENTATION TIdSMTPDataReplies : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
public:
	AnsiString fStartDataReply;
	AnsiString fEndDataReply;
	__fastcall TIdSMTPDataReplies(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property AnsiString StartDataReply = {read=fStartDataReply, write=fStartDataReply};
	__property AnsiString EndDataReply = {read=fEndDataReply, write=fEndDataReply};
public:
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdSMTPDataReplies(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdSMTPMessages;
class PASCALIMPLEMENTATION TIdSMTPMessages : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FNoopReply;
	AnsiString FRSetReply;
	AnsiString FQuitReply;
	AnsiString FErrorReply;
	AnsiString FSequenceError;
	AnsiString FNotLoggedIn;
	AnsiString fReceived;
	AnsiString fXServer;
	AnsiString FSyntaxErrorReply;
	TIdSMTPDataReplies* FDataReplies;
	TIdSMTPGreeting* FGreeting;
	TIdSMTPRcpReplies* FRcpReplies;
	void __fastcall SetDataReplies(const TIdSMTPDataReplies* AValue);
	void __fastcall SetGreeting(const TIdSMTPGreeting* AValue);
	void __fastcall SetRcpReplies(const TIdSMTPRcpReplies* AValue);
	
public:
	__fastcall TIdSMTPMessages(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__fastcall virtual ~TIdSMTPMessages(void);
	
__published:
	__property AnsiString NoopReply = {read=FNoopReply, write=FNoopReply};
	__property AnsiString RSetReply = {read=FRSetReply, write=FRSetReply};
	__property AnsiString QuitReply = {read=FQuitReply, write=FQuitReply};
	__property AnsiString ErrorReply = {read=FErrorReply, write=FErrorReply};
	__property AnsiString SequenceError = {read=FSequenceError, write=FSequenceError};
	__property AnsiString NotLoggedIn = {read=FNotLoggedIn, write=FNotLoggedIn};
	__property AnsiString XServer = {read=fXServer, write=fXServer};
	__property AnsiString ReceivedHeader = {read=fReceived, write=fReceived};
	__property AnsiString SyntaxErrorReply = {read=FSyntaxErrorReply, write=FSyntaxErrorReply};
	__property TIdSMTPGreeting* Greeting = {read=FGreeting, write=SetGreeting};
	__property TIdSMTPRcpReplies* RcpReplies = {read=FRcpReplies, write=SetRcpReplies};
	__property TIdSMTPDataReplies* DataReplies = {read=FDataReplies, write=SetDataReplies};
};


class DELPHICLASS TIdSMTPServer;
class PASCALIMPLEMENTATION TIdSMTPServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	TOnReceiveRaw FOnReceiveRaw;
	TOnReceiveMessage FOnReceiveMessage;
	TOnReceiveMessage FOnReceiveMessageParsed;
	bool fAllowEHLO;
	bool fAuthMode;
	bool fNoDecode;
	bool fNoEncode;
	THasAddress fOnCommandRCPT;
	THasAddress2 fOnCommandMAIL;
	TIdSMTPReceiveMode fReceiveMode;
	TIdSMTPMessages* fMessages;
	TBasicHandler fOnCommandHELP;
	TBasicHandler fOnCommandSOML;
	TBasicHandler fOnCommandSEND;
	TBasicHandler fOnCommandSAML;
	TBasicHandler fOnCommandVRFY;
	TBasicHandler fOnCommandEXPN;
	TBasicHandler fOnCommandTURN;
	TBasicHandler fOnCommandAUTH;
	TUserHandler fCheckUser;
	void __fastcall CommandData(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandMail(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRcpt(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRSET(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandHELO(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandEHLO(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandAUTH(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandHELP(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSOML(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSEND(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSAML(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandVRFY(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandEXPN(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandTURN(Idtcpserver::TIdCommand* ASender);
	virtual void __fastcall InitializeCommandHandlers(void);
	
private:
	TIdStreamType FRawStreamType;
	bool __fastcall DoAuthLogin(Idtcpserver::TIdCommand* ASender, const AnsiString Login);
	void __fastcall SetRawStreamType(const TIdStreamType Value);
	
public:
	__fastcall virtual TIdSMTPServer(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdSMTPServer(void);
	void __fastcall SetMessages(TIdSMTPMessages* AValue);
	
__published:
	__property bool AuthMode = {read=fAuthMode, write=fAuthMode, nodefault};
	__property TIdSMTPMessages* Messages = {read=fMessages, write=SetMessages};
	__property TOnReceiveRaw OnReceiveRaw = {read=FOnReceiveRaw, write=FOnReceiveRaw};
	__property TOnReceiveMessage OnReceiveMessage = {read=FOnReceiveMessage, write=FOnReceiveMessage};
	__property TOnReceiveMessage OnReceiveMessageParsed = {read=FOnReceiveMessageParsed, write=FOnReceiveMessageParsed
		};
	__property TIdSMTPReceiveMode ReceiveMode = {read=fReceiveMode, write=fReceiveMode, nodefault};
	__property bool AllowEHLO = {read=fAllowEHLO, write=fAllowEHLO, nodefault};
	__property bool NoDecode = {read=fNoDecode, write=fNoDecode, nodefault};
	__property bool NoEncode = {read=fNoEncode, write=fNoEncode, nodefault};
	__property THasAddress OnCommandRCPT = {read=fOnCommandRCPT, write=fOnCommandRCPT};
	__property THasAddress2 OnCommandMAIL = {read=fOnCommandMAIL, write=fOnCommandMAIL};
	__property TBasicHandler OnCommandAUTH = {read=fOnCommandAUTH, write=fOnCommandAUTH};
	__property TUserHandler CheckUser = {read=fCheckUser, write=fCheckUser};
	__property TIdStreamType RawStreamType = {read=FRawStreamType, write=SetRawStreamType, nodefault};
	__property TBasicHandler OnCommandHELP = {read=fOnCommandHELP, write=fOnCommandHELP};
	__property TBasicHandler OnCommandSOML = {read=fOnCommandSOML, write=fOnCommandSOML};
	__property TBasicHandler OnCommandSEND = {read=fOnCommandSEND, write=fOnCommandSEND};
	__property TBasicHandler OnCommandSAML = {read=fOnCommandSAML, write=fOnCommandSAML};
	__property TBasicHandler OnCommandVRFY = {read=fOnCommandVRFY, write=fOnCommandVRFY};
	__property TBasicHandler OnCommandEXPN = {read=fOnCommandEXPN, write=fOnCommandEXPN};
	__property TBasicHandler OnCommandTURN = {read=fOnCommandTURN, write=fOnCommandTURN};
};


#pragma option push -b-
enum TIdSMTPState { idSMTPNone, idSMTPHelo, idSMTPMail, idSMTPRcpt, idSMTPData };
#pragma option pop

class DELPHICLASS TIdSMTPServerThread;
class PASCALIMPLEMENTATION TIdSMTPServerThread : public Idtcpserver::TIdPeerThread 
{
	typedef Idtcpserver::TIdPeerThread inherited;
	
protected:
	virtual void __fastcall BeforeRun(void);
	
public:
	TIdSMTPState SMTPState;
	AnsiString From;
	Idemailaddress::TIdEMailAddressList* RCPTList;
	bool HELO;
	bool EHLO;
	AnsiString Username;
	AnsiString Password;
	bool LoggedIn;
	__fastcall virtual TIdSMTPServerThread(bool ACreateSuspended);
	__fastcall virtual ~TIdSMTPServerThread(void);
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idsmtpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsmtpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSMTPServer
