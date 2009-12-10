// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTelnet.pas' rev: 5.00

#ifndef IdTelnetHPP
#define IdTelnetHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtelnet
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdTelnetState { tnsDATA, tnsIAC, tnsIAC_SB, tnsIAC_WILL, tnsIAC_DO, tnsIAC_WONT, tnsIAC_DONT, 
	tnsIAC_SBIAC, tnsIAC_SBDATA, tnsSBDATA_IAC };
#pragma option pop

#pragma option push -b-
enum TIdTelnetCommand { tncNoLocalEcho, tncLocalEcho, tncEcho };
#pragma option pop

class DELPHICLASS TIdTelnet;
typedef void __fastcall (__closure *TIdTelnetDataAvailEvent)(TIdTelnet* Sender, const AnsiString Buffer
	);

typedef void __fastcall (__closure *TIdTelnetCommandEvent)(TIdTelnet* Sender, TIdTelnetCommand Status
	);

class DELPHICLASS TIdTelnetReadThread;
class PASCALIMPLEMENTATION TIdTelnetReadThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	TIdTelnet* FClient;
	AnsiString FRecvData;
	virtual void __fastcall Run(void);
	
public:
	__fastcall TIdTelnetReadThread(TIdTelnet* AClient);
	__property TIdTelnet* Client = {read=FClient};
	__property AnsiString RecvData = {read=FRecvData, write=FRecvData};
public:
	#pragma option push -w-inl
	/* TIdThread.Destroy */ inline __fastcall virtual ~TIdTelnetReadThread(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdTelnet : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	TIdTelnetState fState;
	char fReply;
	AnsiString fSentDoDont;
	AnsiString fSentWillWont;
	AnsiString fReceivedDoDont;
	AnsiString fReceivedWillWont;
	AnsiString fTerminal;
	TIdTelnetDataAvailEvent FOnDataAvailable;
	bool fIamTelnet;
	Classes::TNotifyEvent FOnDisconnect;
	Classes::TNotifyEvent FOnConnect;
	TIdTelnetCommandEvent FOnTelnetCommand;
	TIdTelnetReadThread* FTelnetThread;
	void __fastcall DoOnDataAvailable(void);
	void __fastcall SetOnTelnetCommand(const TIdTelnetCommandEvent Value);
	__property TIdTelnetState State = {read=fState, write=fState, nodefault};
	__property char Reply = {read=fReply, write=fReply, nodefault};
	__property AnsiString SentDoDont = {read=fSentDoDont, write=fSentDoDont};
	__property AnsiString SentWillWont = {read=fSentWillWont, write=fSentWillWont};
	__property AnsiString ReceivedDoDont = {read=fReceivedDoDont, write=fReceivedDoDont};
	__property AnsiString ReceivedWillWont = {read=fReceivedWillWont, write=fReceivedWillWont};
	__property bool IamTelnet = {read=fIamTelnet, write=fIamTelnet, nodefault};
	AnsiString __fastcall Negotiate(const AnsiString Buf);
	void __fastcall Handle_SB(Byte CurrentSb, AnsiString sbData, int sbCount);
	void __fastcall SendNegotiationResp(AnsiString &Resp);
	void __fastcall DoTelnetCommand(TIdTelnetCommand Status);
	
public:
	__fastcall virtual TIdTelnet(Classes::TComponent* AOwner);
	virtual void __fastcall Connect(const int ATimeout);
	virtual void __fastcall Disconnect(void);
	void __fastcall SendCh(char Ch);
	__property TIdTelnetReadThread* TelnetThread = {read=FTelnetThread};
	
__published:
	__property TIdTelnetCommandEvent OnTelnetCommand = {read=FOnTelnetCommand, write=SetOnTelnetCommand
		};
	__property TIdTelnetDataAvailEvent OnDataAvailable = {read=FOnDataAvailable, write=FOnDataAvailable
		};
	__property AnsiString Terminal = {read=fTerminal, write=fTerminal};
	__property Classes::TNotifyEvent OnConnect = {read=FOnConnect, write=FOnConnect};
	__property Classes::TNotifyEvent OnDisconnect = {read=FOnDisconnect, write=FOnDisconnect};
public:
	#pragma option push -w-inl
	/* TIdTCPClient.Destroy */ inline __fastcall virtual ~TIdTelnet(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTelnetError;
class PASCALIMPLEMENTATION EIdTelnetError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTelnetError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTelnetError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTelnetError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTelnetError(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTelnetError(const AnsiString Msg, int AHelpContext)
		 : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTelnetError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTelnetError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTelnetError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTelnetError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTelnetClientConnectError;
class PASCALIMPLEMENTATION EIdTelnetClientConnectError : public EIdTelnetError 
{
	typedef EIdTelnetError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTelnetClientConnectError(const AnsiString Msg) : EIdTelnetError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTelnetClientConnectError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdTelnetError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTelnetClientConnectError(int Ident)/* overload */ : 
		EIdTelnetError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTelnetClientConnectError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdTelnetError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTelnetClientConnectError(const AnsiString Msg, int 
		AHelpContext) : EIdTelnetError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTelnetClientConnectError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTelnetError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTelnetClientConnectError(int Ident, int AHelpContext
		)/* overload */ : EIdTelnetError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTelnetClientConnectError(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTelnetError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTelnetClientConnectError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTelnetServerOnDataAvailableIsNil;
class PASCALIMPLEMENTATION EIdTelnetServerOnDataAvailableIsNil : public EIdTelnetError 
{
	typedef EIdTelnetError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(const AnsiString Msg) : 
		EIdTelnetError(Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(const AnsiString Msg
		, const System::TVarRec * Args, const int Args_Size) : EIdTelnetError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(int Ident)/* overload */
		 : EIdTelnetError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(int Ident, const 
		System::TVarRec * Args, const int Args_Size)/* overload */ : EIdTelnetError(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(const AnsiString Msg
		, int AHelpContext) : EIdTelnetError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(const AnsiString 
		Msg, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdTelnetError(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(int Ident, int 
		AHelpContext)/* overload */ : EIdTelnetError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTelnetServerOnDataAvailableIsNil(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdTelnetError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTelnetServerOnDataAvailableIsNil(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const char TNC_EOR = '\xef';
static const char TNC_SE = '\xf0';
static const char TNC_NOP = '\xf1';
static const char TNC_DATA_MARK = '\xf2';
static const char TNC_BREAK = '\xf3';
static const char TNC_IP = '\xf4';
static const char TNC_AO = '\xf5';
static const char TNC_AYT = '\xf6';
static const char TNC_EC = '\xf7';
static const char TNC_EL = '\xf8';
static const char TNC_GA = '\xf9';
static const char TNC_SB = '\xfa';
static const char TNC_WILL = '\xfb';
static const char TNC_WONT = '\xfc';
static const char TNC_DO = '\xfd';
static const char TNC_DONT = '\xfe';
static const char TNC_IAC = '\xff';
static const char TNO_BINARY = '\x0';
static const char TNO_ECHO = '\x1';
static const char TNO_RECONNECT = '\x2';
static const char TNO_SGA = '\x3';
static const char TNO_AMSN = '\x4';
static const char TNO_STATUS = '\x5';
static const char TNO_TIMING_MARK = '\x6';
static const char TNO_RCTE = '\x7';
static const char TNO_OLW = '\x8';
static const char TNO_OPS = '\x9';
static const char TNO_OCRD = '\xa';
static const char TNO_OHTS = '\xb';
static const char TNO_OHTD = '\xc';
static const char TNO_OFD = '\xd';
static const char TNO_OVT = '\xe';
static const char TNO_OVTD = '\xf';
static const char TNO_OLD = '\x10';
static const char TNO_EA = '\x11';
static const char TNO_LOGOUT = '\x12';
static const char TNO_BYTE_MACRO = '\x13';
static const char TNO_DET = '\x14';
static const char TNO_SUPDUP = '\x15';
static const char TNO_SUPDUP_OUTPUT = '\x16';
static const char TNO_SL = '\x17';
static const char TNO_TERMTYPE = '\x18';
static const char TNO_EOR = '\x19';
static const char TNO_TACACS_ID = '\x1a';
static const char TNO_OM = '\x1b';
static const char TNO_TLN = '\x1c';
static const char TNO_3270REGIME = '\x1d';
static const char TNO_X3PAD = '\x1e';
static const char TNO_NAWS = '\x1f';
static const char TNO_TERM_SPEED = '\x20';
static const char TNO_RFLOW = '\x21';
static const char TNO_LINEMODE = '\x22';
static const char TNO_XDISPLOC = '\x23';
static const char TNO_AUTH = '\x25';
static const char TNO_ENCRYPT = '\x26';
static const char TNO_EOL = '\xff';
static const char TNOS_TERM_IS = '\x0';
static const char TNOS_TERMTYPE_SEND = '\x1';
static const char TNOS_REPLY = '\x2';
static const char TNOS_NAME = '\x3';

}	/* namespace Idtelnet */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtelnet;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTelnet
