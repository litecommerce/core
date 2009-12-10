// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSSLOpenSSL.pas' rev: 5.00

#ifndef IdSSLOpenSSLHPP
#define IdSSLOpenSSLHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdSocks.hpp>	// Pascal unit
#include <IdServerIOHandler.hpp>	// Pascal unit
#include <IdIOHandlerSocket.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdIntercept.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdIOHandler.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdSSLOpenSSLHeaders.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsslopenssl
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdSSLVersion { sslvSSLv2, sslvSSLv23, sslvSSLv3, sslvTLSv1 };
#pragma option pop

#pragma option push -b-
enum TIdSSLMode { sslmUnassigned, sslmClient, sslmServer, sslmBoth };
#pragma option pop

#pragma option push -b-
enum TIdSSLVerifyMode { sslvrfPeer, sslvrfFailIfNoPeerCert, sslvrfClientOnce };
#pragma option pop

typedef Set<TIdSSLVerifyMode, sslvrfPeer, sslvrfClientOnce>  TIdSSLVerifyModeSet;

#pragma option push -b-
enum TIdSSLCtxMode { sslCtxClient, sslCtxServer };
#pragma option pop

#pragma option push -b-
enum TIdSSLAction { sslRead, sslWrite };
#pragma option pop

#pragma pack(push, 1)
struct TULong
{
	
	union
	{
		struct 
		{
			unsigned C1;
			
		};
		struct 
		{
			int L1;
			
		};
		struct 
		{
			Word W1;
			Word W2;
			
		};
		struct 
		{
			System::Byte B1;
			System::Byte B2;
			System::Byte B3;
			System::Byte B4;
			
		};
		
	};
} ;
#pragma pack(pop)

struct TEVP_MD
{
	int Length;
	char MD[36];
} ;

struct TByteArray
{
	int Length;
	char *Data;
} ;

typedef void __fastcall (__closure *TCallbackEvent)(AnsiString Msg);

typedef void __fastcall (__closure *TPasswordEvent)(AnsiString &Password);

class DELPHICLASS TIdX509;
typedef bool __fastcall (__closure *TVerifyPeerEvent)(TIdX509* Certificate);

class DELPHICLASS TIdSSLIOHandlerSocket;
typedef void __fastcall (__closure *TIOHandlerNotify)(TIdSSLIOHandlerSocket* ASender);

class DELPHICLASS TIdSSLOptions;
class PASCALIMPLEMENTATION TIdSSLOptions : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString fsRootCertFile;
	AnsiString fsCertFile;
	AnsiString fsKeyFile;
	TIdSSLVersion fMethod;
	TIdSSLMode fMode;
	int fVerifyDepth;
	TIdSSLVerifyModeSet fVerifyMode;
	AnsiString fVerifyDirs;
	AnsiString fCipherList;
	virtual void __fastcall AssignTo(Classes::TPersistent* ASource);
	
__published:
	__property AnsiString RootCertFile = {read=fsRootCertFile, write=fsRootCertFile};
	__property AnsiString CertFile = {read=fsCertFile, write=fsCertFile};
	__property AnsiString KeyFile = {read=fsKeyFile, write=fsKeyFile};
	__property TIdSSLVersion Method = {read=fMethod, write=fMethod, nodefault};
	__property TIdSSLMode Mode = {read=fMode, write=fMode, nodefault};
	__property TIdSSLVerifyModeSet VerifyMode = {read=fVerifyMode, write=fVerifyMode, nodefault};
	__property int VerifyDepth = {read=fVerifyDepth, write=fVerifyDepth, nodefault};
	__property AnsiString VerifyDirs = {read=fVerifyDirs, write=fVerifyDirs};
	__property AnsiString CipherList = {read=fCipherList, write=fCipherList};
public:
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdSSLOptions(void) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdSSLOptions(void) : Classes::TPersistent() { }
	#pragma option pop
	
};


class DELPHICLASS TIdSSLContext;
class PASCALIMPLEMENTATION TIdSSLContext : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	TIdSSLVersion fMethod;
	TIdSSLMode fMode;
	AnsiString fsRootCertFile;
	AnsiString fsCertFile;
	AnsiString fsKeyFile;
	int fVerifyDepth;
	TIdSSLVerifyModeSet fVerifyMode;
	AnsiString fVerifyDirs;
	AnsiString fCipherList;
	void *fContext;
	bool fStatusInfoOn;
	bool fVerifyOn;
	int fSessionId;
	TIdSSLCtxMode fCtxMode;
	void __fastcall DestroyContext(void);
	void * __fastcall SetSSLMethod(void);
	void __fastcall SetVerifyMode(TIdSSLVerifyModeSet Mode, bool CheckRoutine);
	TIdSSLVerifyModeSet __fastcall GetVerifyMode(void);
	void __fastcall InitContext(TIdSSLCtxMode CtxMode);
	
public:
	System::TObject* Parent;
	__fastcall TIdSSLContext(void);
	__fastcall virtual ~TIdSSLContext(void);
	bool __fastcall LoadRootCert(void);
	bool __fastcall LoadCert(void);
	bool __fastcall LoadKey(void);
	__property bool StatusInfoOn = {read=fStatusInfoOn, write=fStatusInfoOn, nodefault};
	__property bool VerifyOn = {read=fVerifyOn, write=fVerifyOn, nodefault};
	
__published:
	__property TIdSSLVersion Method = {read=fMethod, write=fMethod, nodefault};
	__property TIdSSLMode Mode = {read=fMode, write=fMode, nodefault};
	__property AnsiString RootCertFile = {read=fsRootCertFile, write=fsRootCertFile};
	__property AnsiString CertFile = {read=fsCertFile, write=fsCertFile};
	__property AnsiString KeyFile = {read=fsKeyFile, write=fsKeyFile};
	__property TIdSSLVerifyModeSet VerifyMode = {read=fVerifyMode, write=fVerifyMode, nodefault};
	__property int VerifyDepth = {read=fVerifyDepth, write=fVerifyDepth, nodefault};
};


class DELPHICLASS TIdSSLSocket;
class DELPHICLASS TIdSSLCipher;
class PASCALIMPLEMENTATION TIdSSLCipher : public System::TObject 
{
	typedef System::TObject inherited;
	
private:
	TIdSSLSocket* FSSLSocket;
	AnsiString __fastcall GetDescription();
	AnsiString __fastcall GetName();
	int __fastcall GetBits(void);
	AnsiString __fastcall GetVersion();
	
public:
	__fastcall TIdSSLCipher(TIdSSLSocket* AOwner);
	__fastcall virtual ~TIdSSLCipher(void);
	
__published:
	__property AnsiString Description = {read=GetDescription};
	__property AnsiString Name = {read=GetName};
	__property int Bits = {read=GetBits, nodefault};
	__property AnsiString Version = {read=GetVersion};
};


class PASCALIMPLEMENTATION TIdSSLSocket : public System::TObject 
{
	typedef System::TObject inherited;
	
private:
	TIdX509* fPeerCert;
	TIdSSLCipher* fSSLCipher;
	System::TObject* fParent;
	TIdSSLContext* fSSLContext;
	TIdX509* __fastcall GetPeerCert(void);
	int __fastcall GetSSLError(int retCode);
	TIdSSLCipher* __fastcall GetSSLCipher(void);
	
public:
	void *fSSL;
	__fastcall TIdSSLSocket(System::TObject* Parent);
	void __fastcall Accept(const int pHandle, TIdSSLContext* fSSLContext);
	void __fastcall Connect(const int pHandle, TIdSSLContext* fSSLContext);
	int __fastcall Send(void *ABuf, int ALen);
	int __fastcall Recv(void *ABuf, int ALen);
	__fastcall virtual ~TIdSSLSocket(void);
	TByteArray __fastcall GetSessionID();
	AnsiString __fastcall GetSessionIDAsString();
	void __fastcall SetCipherList(AnsiString CipherList);
	__property TIdX509* PeerCert = {read=GetPeerCert};
	__property TIdSSLCipher* Cipher = {read=GetSSLCipher};
};


class PASCALIMPLEMENTATION TIdSSLIOHandlerSocket : public Idiohandlersocket::TIdIOHandlerSocket 
{
	typedef Idiohandlersocket::TIdIOHandlerSocket inherited;
	
private:
	TIdSSLContext* fSSLContext;
	TIdSSLOptions* fxSSLOptions;
	TIdSSLSocket* fSSLSocket;
	bool fIsPeer;
	TCallbackEvent fOnStatusInfo;
	TPasswordEvent fOnGetPassword;
	TVerifyPeerEvent fOnVerifyPeer;
	bool fSSLLayerClosed;
	TIOHandlerNotify fOnBeforeConnect;
	bool fPassThrough;
	void __fastcall SetPassThrough(const bool Value);
	void __fastcall Init(void);
	
protected:
	virtual void __fastcall DoBeforeConnect(TIdSSLIOHandlerSocket* ASender);
	virtual void __fastcall DoStatusInfo(AnsiString Msg);
	virtual void __fastcall DoGetPassword(AnsiString &Password);
	virtual bool __fastcall DoVerifyPeer(TIdX509* Certificate);
	virtual int __fastcall RecvEnc(void *ABuf, int ALen);
	virtual int __fastcall SendEnc(void *ABuf, int ALen);
	virtual void __fastcall OpenEncodedConnection(void);
	
public:
	__fastcall virtual TIdSSLIOHandlerSocket(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdSSLIOHandlerSocket(void);
	virtual void __fastcall AfterAccept(void);
	virtual void __fastcall ConnectClient(const AnsiString AHost, const int APort, const AnsiString ABoundIP
		, const int ABoundPort, const int ABoundPortMin, const int ABoundPortMax, const int ATimeout);
	virtual void __fastcall Close(void);
	virtual void __fastcall Open(void);
	virtual int __fastcall Recv(void *ABuf, int ALen);
	virtual int __fastcall Send(void *ABuf, int ALen);
	__property TIdSSLSocket* SSLSocket = {read=fSSLSocket, write=fSSLSocket};
	__property bool PassThrough = {read=fPassThrough, write=SetPassThrough, nodefault};
	__property TIOHandlerNotify OnBeforeConnect = {read=fOnBeforeConnect, write=fOnBeforeConnect};
	
__published:
	__property TIdSSLOptions* SSLOptions = {read=fxSSLOptions, write=fxSSLOptions};
	__property TCallbackEvent OnStatusInfo = {read=fOnStatusInfo, write=fOnStatusInfo};
	__property TPasswordEvent OnGetPassword = {read=fOnGetPassword, write=fOnGetPassword};
	__property TVerifyPeerEvent OnVerifyPeer = {read=fOnVerifyPeer, write=fOnVerifyPeer};
};


class DELPHICLASS TIdServerIOHandlerSSL;
class PASCALIMPLEMENTATION TIdServerIOHandlerSSL : public Idserveriohandler::TIdServerIOHandler 
{
	typedef Idserveriohandler::TIdServerIOHandler inherited;
	
private:
	TIdSSLContext* fSSLContext;
	TIdSSLOptions* fxSSLOptions;
	bool fIsInitialized;
	TCallbackEvent fOnStatusInfo;
	TPasswordEvent fOnGetPassword;
	TVerifyPeerEvent fOnVerifyPeer;
	
protected:
	virtual void __fastcall DoStatusInfo(AnsiString Msg);
	virtual void __fastcall DoGetPassword(AnsiString &Password);
	virtual bool __fastcall DoVerifyPeer(TIdX509* Certificate);
	
public:
	virtual void __fastcall Init(void);
	virtual Idiohandler::TIdIOHandler* __fastcall Accept(int ASocket, Idthread::TIdThread* AThread);
	__fastcall virtual TIdServerIOHandlerSSL(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdServerIOHandlerSSL(void);
	
__published:
	__property TIdSSLOptions* SSLOptions = {read=fxSSLOptions, write=fxSSLOptions};
	__property TCallbackEvent OnStatusInfo = {read=fOnStatusInfo, write=fOnStatusInfo};
	__property TPasswordEvent OnGetPassword = {read=fOnGetPassword, write=fOnGetPassword};
	__property TVerifyPeerEvent OnVerifyPeer = {read=fOnVerifyPeer, write=fOnVerifyPeer};
};


class DELPHICLASS TIdX509Name;
class PASCALIMPLEMENTATION TIdX509Name : public System::TObject 
{
	typedef System::TObject inherited;
	
private:
	void *fX509Name;
	AnsiString __fastcall CertInOneLine();
	TULong __fastcall GetHash(void);
	AnsiString __fastcall GetHashAsString();
	
public:
	__fastcall TIdX509Name(void * aX509Name);
	__property TULong Hash = {read=GetHash};
	__property AnsiString HashAsString = {read=GetHashAsString};
	__property AnsiString OneLine = {read=CertInOneLine};
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdX509Name(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdX509 : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	void *FX509;
	TIdX509Name* FSubject;
	TIdX509Name* FIssuer;
	TIdX509Name* __fastcall RSubject(void);
	TIdX509Name* __fastcall RIssuer(void);
	System::TDateTime __fastcall RnotBefore(void);
	System::TDateTime __fastcall RnotAfter(void);
	TEVP_MD __fastcall RFingerprint();
	AnsiString __fastcall RFingerprintAsString();
	
public:
	__fastcall virtual TIdX509(void * aX509);
	__fastcall virtual ~TIdX509(void);
	__property TEVP_MD Fingerprint = {read=RFingerprint};
	__property AnsiString FingerprintAsString = {read=RFingerprintAsString};
	__property TIdX509Name* Subject = {read=RSubject};
	__property TIdX509Name* Issuer = {read=RIssuer};
	__property System::TDateTime notBefore = {read=RnotBefore};
	__property System::TDateTime notAfter = {read=RnotAfter};
};


class DELPHICLASS EIdOpenSSLError;
class PASCALIMPLEMENTATION EIdOpenSSLError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOpenSSLError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOpenSSLError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOpenSSLError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOpenSSLError(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOpenSSLError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOpenSSLError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOpenSSLError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOpenSSLError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOpenSSLError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOpenSSLLoadError;
class PASCALIMPLEMENTATION EIdOpenSSLLoadError : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOpenSSLLoadError(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOpenSSLLoadError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOpenSSLLoadError(int Ident)/* overload */ : EIdOpenSSLError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOpenSSLLoadError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOpenSSLLoadError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOpenSSLLoadError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOpenSSLLoadError(int Ident, int AHelpContext)/* overload */
		 : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOpenSSLLoadError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOpenSSLLoadError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLCouldNotLoadSSLLibrary;
class PASCALIMPLEMENTATION EIdOSSLCouldNotLoadSSLLibrary : public EIdOpenSSLLoadError 
{
	typedef EIdOpenSSLLoadError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(const AnsiString Msg) : EIdOpenSSLLoadError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdOpenSSLLoadError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(int Ident)/* overload */ : 
		EIdOpenSSLLoadError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLLoadError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(const AnsiString Msg, int 
		AHelpContext) : EIdOpenSSLLoadError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(const AnsiString Msg, 
		const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdOpenSSLLoadError(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(int Ident, int AHelpContext
		)/* overload */ : EIdOpenSSLLoadError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLCouldNotLoadSSLLibrary(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdOpenSSLLoadError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLCouldNotLoadSSLLibrary(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLModeNotSet;
class PASCALIMPLEMENTATION EIdOSSLModeNotSet : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLModeNotSet(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLModeNotSet(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLModeNotSet(int Ident)/* overload */ : EIdOpenSSLError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLModeNotSet(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLModeNotSet(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLModeNotSet(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLModeNotSet(int Ident, int AHelpContext)/* overload */
		 : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLModeNotSet(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLModeNotSet(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLGetMethodError;
class PASCALIMPLEMENTATION EIdOSSLGetMethodError : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLGetMethodError(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLGetMethodError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLGetMethodError(int Ident)/* overload */ : EIdOpenSSLError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLGetMethodError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLGetMethodError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLGetMethodError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLGetMethodError(int Ident, int AHelpContext)/* overload */
		 : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLGetMethodError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLGetMethodError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLCreatingContextError;
class PASCALIMPLEMENTATION EIdOSSLCreatingContextError : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLCreatingContextError(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLCreatingContextError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLCreatingContextError(int Ident)/* overload */ : 
		EIdOpenSSLError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLCreatingContextError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLCreatingContextError(const AnsiString Msg, int 
		AHelpContext) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLCreatingContextError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLCreatingContextError(int Ident, int AHelpContext
		)/* overload */ : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLCreatingContextError(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdOpenSSLError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLCreatingContextError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLLoadingRootCertError;
class PASCALIMPLEMENTATION EIdOSSLLoadingRootCertError : public EIdOpenSSLLoadError 
{
	typedef EIdOpenSSLLoadError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLLoadingRootCertError(const AnsiString Msg) : EIdOpenSSLLoadError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLLoadingRootCertError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdOpenSSLLoadError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLLoadingRootCertError(int Ident)/* overload */ : 
		EIdOpenSSLLoadError(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLLoadingRootCertError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLLoadError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLLoadingRootCertError(const AnsiString Msg, int 
		AHelpContext) : EIdOpenSSLLoadError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLLoadingRootCertError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdOpenSSLLoadError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLLoadingRootCertError(int Ident, int AHelpContext
		)/* overload */ : EIdOpenSSLLoadError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLLoadingRootCertError(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdOpenSSLLoadError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLLoadingRootCertError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLLoadingCertError;
class PASCALIMPLEMENTATION EIdOSSLLoadingCertError : public EIdOpenSSLLoadError 
{
	typedef EIdOpenSSLLoadError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLLoadingCertError(const AnsiString Msg) : EIdOpenSSLLoadError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLLoadingCertError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLLoadError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLLoadingCertError(int Ident)/* overload */ : EIdOpenSSLLoadError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLLoadingCertError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLLoadError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLLoadingCertError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLLoadError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLLoadingCertError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdOpenSSLLoadError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLLoadingCertError(int Ident, int AHelpContext
		)/* overload */ : EIdOpenSSLLoadError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLLoadingCertError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLLoadError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLLoadingCertError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLLoadingKeyError;
class PASCALIMPLEMENTATION EIdOSSLLoadingKeyError : public EIdOpenSSLLoadError 
{
	typedef EIdOpenSSLLoadError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLLoadingKeyError(const AnsiString Msg) : EIdOpenSSLLoadError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLLoadingKeyError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLLoadError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLLoadingKeyError(int Ident)/* overload */ : EIdOpenSSLLoadError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLLoadingKeyError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLLoadError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLLoadingKeyError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLLoadError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLLoadingKeyError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdOpenSSLLoadError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLLoadingKeyError(int Ident, int AHelpContext)
		/* overload */ : EIdOpenSSLLoadError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLLoadingKeyError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLLoadError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLLoadingKeyError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLSettingCipherError;
class PASCALIMPLEMENTATION EIdOSSLSettingCipherError : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLSettingCipherError(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLSettingCipherError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLSettingCipherError(int Ident)/* overload */ : EIdOpenSSLError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLSettingCipherError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLSettingCipherError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLSettingCipherError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLSettingCipherError(int Ident, int AHelpContext
		)/* overload */ : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLSettingCipherError(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdOpenSSLError(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLSettingCipherError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLDataBindingError;
class PASCALIMPLEMENTATION EIdOSSLDataBindingError : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLDataBindingError(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLDataBindingError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLDataBindingError(int Ident)/* overload */ : EIdOpenSSLError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLDataBindingError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLDataBindingError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLDataBindingError(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLDataBindingError(int Ident, int AHelpContext
		)/* overload */ : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLDataBindingError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLDataBindingError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLAcceptError;
class PASCALIMPLEMENTATION EIdOSSLAcceptError : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLAcceptError(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLAcceptError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLAcceptError(int Ident)/* overload */ : EIdOpenSSLError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLAcceptError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLAcceptError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLAcceptError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLAcceptError(int Ident, int AHelpContext)/* overload */
		 : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLAcceptError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLAcceptError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdOSSLConnectError;
class PASCALIMPLEMENTATION EIdOSSLConnectError : public EIdOpenSSLError 
{
	typedef EIdOpenSSLError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdOSSLConnectError(const AnsiString Msg) : EIdOpenSSLError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdOSSLConnectError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdOpenSSLError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdOSSLConnectError(int Ident)/* overload */ : EIdOpenSSLError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdOSSLConnectError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdOpenSSLError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdOSSLConnectError(const AnsiString Msg, int AHelpContext
		) : EIdOpenSSLError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdOSSLConnectError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdOpenSSLError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdOSSLConnectError(int Ident, int AHelpContext)/* overload */
		 : EIdOpenSSLError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdOSSLConnectError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdOpenSSLError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdOSSLConnectError(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE int __cdecl PasswordCallback(char * buf, int size, int rwflag, void * userdata);
extern PACKAGE void __cdecl InfoCallback(void * sslSocket, int where, int ret);
extern PACKAGE bool __fastcall LogicalAnd(int A, int B);
extern PACKAGE int __cdecl VerifyCallback(int Ok, void * ctx);

}	/* namespace Idsslopenssl */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsslopenssl;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSSLOpenSSL
