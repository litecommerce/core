// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdHTTP.pas' rev: 5.00

#ifndef IdHTTPHPP
#define IdHTTPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdMultipartFormData.hpp>	// Pascal unit
#include <IdAuthenticationManager.hpp>	// Pascal unit
#include <IdAuthentication.hpp>	// Pascal unit
#include <IdCookieManager.hpp>	// Pascal unit
#include <IdCookie.hpp>	// Pascal unit
#include <IdURI.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdSSLOpenSSL.hpp>	// Pascal unit
#include <IdHTTPHeaderInfo.hpp>	// Pascal unit
#include <IdHeaderList.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idhttp
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdHTTPMethod { hmHead, hmGet, hmPost, hmOptions, hmTrace, hmPut, hmDelete, hmConnect };
#pragma option pop

#pragma option push -b-
enum TIdHTTPWhatsNext { wnGoToURL, wnJustExit, wnDontKnow, wnReadAndGo, wnAuthRequest };
#pragma option pop

#pragma option push -b-
enum TIdHTTPConnectionType { ctNormal, ctSSL, ctProxy, ctSSLProxy };
#pragma option pop

#pragma option push -b-
enum TIdHTTPOption { hoInProcessAuth, hoKeepOrigProtocol, hoForceEncodeParams };
#pragma option pop

typedef Set<TIdHTTPOption, hoInProcessAuth, hoForceEncodeParams>  TIdHTTPOptions;

#pragma option push -b-
enum TIdHTTPProtocolVersion { pv1_0, pv1_1 };
#pragma option pop

typedef void __fastcall (__closure *TIdHTTPOnRedirectEvent)(System::TObject* Sender, AnsiString &dest
	, int &NumRedirect, bool &Handled, TIdHTTPMethod &VMethod);

typedef void __fastcall (__closure *TIdOnSelectAuthorization)(System::TObject* Sender, TMetaClass* &
	AuthenticationClass, Idheaderlist::TIdHeaderList* AuthInfo);

typedef void __fastcall (__closure *TIdOnAuthorization)(System::TObject* Sender, Idauthentication::TIdAuthentication* 
	Authentication, bool &Handled);

class DELPHICLASS TIdHTTPResponse;
class DELPHICLASS TIdCustomHTTP;
class DELPHICLASS TIdHTTPProtocol;
class DELPHICLASS TIdHTTPRequest;
class PASCALIMPLEMENTATION TIdHTTPRequest : public Idhttpheaderinfo::TIdRequestHeaderInfo 
{
	typedef Idhttpheaderinfo::TIdRequestHeaderInfo inherited;
	
protected:
	TIdCustomHTTP* FHTTP;
	AnsiString FURL;
	TIdHTTPMethod FMethod;
	Classes::TStream* FSourceStream;
	TIdHTTPConnectionType FUseProxy;
	
public:
	__fastcall virtual TIdHTTPRequest(TIdCustomHTTP* AHTTP);
	__property AnsiString URL = {read=FURL, write=FURL};
	__property TIdHTTPMethod Method = {read=FMethod, write=FMethod, nodefault};
	__property Classes::TStream* Source = {read=FSourceStream, write=FSourceStream};
	__property TIdHTTPConnectionType UseProxy = {read=FUseProxy, nodefault};
public:
	#pragma option push -w-inl
	/* TIdEntityHeaderInfo.Destroy */ inline __fastcall virtual ~TIdHTTPRequest(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdHTTPProtocol : public System::TObject 
{
	typedef System::TObject inherited;
	
public:
	TIdCustomHTTP* FHTTP;
	TIdHTTPRequest* FRequest;
	TIdHTTPResponse* FResponse;
	__fastcall TIdHTTPProtocol(TIdCustomHTTP* AConnection);
	__fastcall virtual ~TIdHTTPProtocol(void);
	TIdHTTPWhatsNext __fastcall ProcessResponse(void);
	void __fastcall BuildAndSendRequest(Iduri::TIdURI* AURI);
	void __fastcall RetrieveHeaders(void);
	__property TIdHTTPRequest* Request = {read=FRequest};
	__property TIdHTTPResponse* Response = {read=FResponse};
};


class PASCALIMPLEMENTATION TIdCustomHTTP : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	Idcookiemanager::TIdCookieManager* FCookieManager;
	bool FFreeOnDestroy;
	int FMaxAuthRetries;
	bool FAllowCookies;
	Idauthenticationmanager::TIdAuthenticationManager* FAuthenticationManager;
	TIdHTTPProtocolVersion FProtocolVersion;
	int FRedirectCount;
	int FRedirectMax;
	bool FHandleRedirects;
	TIdHTTPOptions FOptions;
	Iduri::TIdURI* FURI;
	TIdHTTPProtocol* FHTTPProto;
	Idhttpheaderinfo::TIdProxyConnectionInfo* FProxyParameters;
	TIdHTTPOnRedirectEvent FOnRedirect;
	TIdOnSelectAuthorization FOnSelectAuthorization;
	TIdOnSelectAuthorization FOnSelectProxyAuthorization;
	TIdOnAuthorization FOnAuthorization;
	TIdOnAuthorization FOnProxyAuthorization;
	int FConnectTimeout;
	virtual void __fastcall SetHost(const AnsiString Value);
	virtual void __fastcall SetPort(const int Value);
	void __fastcall SetAuthenticationManager(const Idauthenticationmanager::TIdAuthenticationManager* Value
		);
	void __fastcall SetCookieManager(Idcookiemanager::TIdCookieManager* ACookieManager);
	void __fastcall SetAllowCookies(bool AValue);
	int __fastcall GetResponseCode(void);
	AnsiString __fastcall GetResponseText();
	virtual bool __fastcall DoOnAuthorization(TIdHTTPRequest* ARequest, TIdHTTPResponse* AResponse);
	virtual bool __fastcall DoOnProxyAuthorization(TIdHTTPRequest* ARequest, TIdHTTPResponse* AResponse
		);
	virtual bool __fastcall DoOnRedirect(AnsiString &Location, TIdHTTPMethod &VMethod, int RedirectCount
		);
	virtual void __fastcall Notification(Classes::TComponent* AComponent, Classes::TOperation Operation
		);
	void __fastcall ProcessCookies(TIdHTTPRequest* ARequest, TIdHTTPResponse* AResponse);
	TIdHTTPConnectionType __fastcall SetHostAndPort(void);
	void __fastcall SetCookies(Iduri::TIdURI* AURL, TIdHTTPRequest* ARequest);
	void __fastcall ReadResult(TIdHTTPResponse* AResponse);
	void __fastcall PrepareRequest(TIdHTTPRequest* ARequest);
	void __fastcall ConnectToHost(TIdHTTPRequest* ARequest, TIdHTTPResponse* AResponse);
	TIdHTTPResponse* __fastcall GetResponseHeaders(void);
	TIdHTTPRequest* __fastcall GetRequestHeaders(void);
	void __fastcall SetRequestHeaders(const TIdHTTPRequest* Value);
	void __fastcall EncodeRequestParams(const Classes::TStrings* AStrings);
	AnsiString __fastcall SetRequestParams(const Classes::TStrings* AStrings);
	void __fastcall CheckAndConnect(TIdHTTPResponse* AResponse);
	virtual void __fastcall DoOnDisconnected(void);
	int __fastcall GetAuthRetries(void);
	int __fastcall GetProxyAuthRetries(void);
	__property int InternalAuthRetries = {read=GetAuthRetries, nodefault};
	__property int InternalProxyAuthRetries = {read=GetProxyAuthRetries, nodefault};
	
public:
	__fastcall virtual TIdCustomHTTP(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdCustomHTTP(void);
	virtual void __fastcall DoRequest(const TIdHTTPMethod AMethod, AnsiString AURL, const Classes::TStream* 
		ASource, const Classes::TStream* AResponseContent);
	void __fastcall Options(AnsiString AURL)/* overload */;
	void __fastcall Get(AnsiString AURL, const Classes::TStream* AResponseContent)/* overload */;
	AnsiString __fastcall Get(AnsiString AURL)/* overload */;
	void __fastcall Trace(AnsiString AURL, const Classes::TStream* AResponseContent)/* overload */;
	AnsiString __fastcall Trace(AnsiString AURL)/* overload */;
	void __fastcall Head(AnsiString AURL);
	AnsiString __fastcall Post(AnsiString AURL, const Classes::TStrings* ASource)/* overload */;
	AnsiString __fastcall Post(AnsiString AURL, const Classes::TStream* ASource)/* overload */;
	AnsiString __fastcall Post(AnsiString AURL, const Idmultipartformdata::TIdMultiPartFormDataStream* 
		ASource)/* overload */;
	void __fastcall Post(AnsiString AURL, const Classes::TStrings* ASource, const Classes::TStream* AResponseContent
		)/* overload */;
	void __fastcall Post(AnsiString AURL, const Classes::TStream* ASource, const Classes::TStream* AResponseContent
		)/* overload */;
	void __fastcall Post(AnsiString AURL, const Idmultipartformdata::TIdMultiPartFormDataStream* ASource
		, Classes::TStream* AResponseContent)/* overload */;
	AnsiString __fastcall Put(AnsiString AURL, const Classes::TStream* ASource)/* overload */;
	void __fastcall Put(AnsiString AURL, const Classes::TStream* ASource, const Classes::TStream* AResponseContent
		)/* overload */;
	__property int ResponseCode = {read=GetResponseCode, nodefault};
	__property AnsiString ResponseText = {read=GetResponseText};
	__property TIdHTTPResponse* Response = {read=GetResponseHeaders};
	__property Iduri::TIdURI* URL = {read=FURI};
	__property int AuthRetries = {read=FMaxAuthRetries, write=FMaxAuthRetries, default=3};
	__property bool AllowCookies = {read=FAllowCookies, write=SetAllowCookies, nodefault};
	__property bool HandleRedirects = {read=FHandleRedirects, write=FHandleRedirects, default=0};
	__property TIdHTTPProtocolVersion ProtocolVersion = {read=FProtocolVersion, write=FProtocolVersion, 
		default=1};
	__property int RedirectMaximum = {read=FRedirectMax, write=FRedirectMax, default=15};
	__property Idhttpheaderinfo::TIdProxyConnectionInfo* ProxyParams = {read=FProxyParameters, write=FProxyParameters
		};
	__property TIdHTTPRequest* Request = {read=GetRequestHeaders, write=SetRequestHeaders};
	__property TIdHTTPOptions HTTPOptions = {read=FOptions, write=FOptions, nodefault};
	__property TIdHTTPOnRedirectEvent OnRedirect = {read=FOnRedirect, write=FOnRedirect};
	__property TIdOnSelectAuthorization OnSelectAuthorization = {read=FOnSelectAuthorization, write=FOnSelectAuthorization
		};
	__property TIdOnSelectAuthorization OnSelectProxyAuthorization = {read=FOnSelectProxyAuthorization, 
		write=FOnSelectProxyAuthorization};
	__property TIdOnAuthorization OnAuthorization = {read=FOnAuthorization, write=FOnAuthorization};
	__property TIdOnAuthorization OnProxyAuthorization = {read=FOnProxyAuthorization, write=FOnProxyAuthorization
		};
	__property Idcookiemanager::TIdCookieManager* CookieManager = {read=FCookieManager, write=SetCookieManager
		};
	__property Idauthenticationmanager::TIdAuthenticationManager* AuthenticationManager = {read=FAuthenticationManager
		, write=SetAuthenticationManager};
	__property int ConnectTimeout = {read=FConnectTimeout, write=FConnectTimeout, default=0};
};


class PASCALIMPLEMENTATION TIdHTTPResponse : public Idhttpheaderinfo::TIdResponseHeaderInfo 
{
	typedef Idhttpheaderinfo::TIdResponseHeaderInfo inherited;
	
protected:
	TIdCustomHTTP* FHTTP;
	int FResponseCode;
	AnsiString FResponseText;
	bool FKeepAlive;
	Classes::TStream* FContentStream;
	TIdHTTPProtocolVersion FResponseVersion;
	bool __fastcall GetKeepAlive(void);
	int __fastcall GetResponseCode(void);
	
public:
	__fastcall virtual TIdHTTPResponse(TIdCustomHTTP* AParent);
	__property bool KeepAlive = {read=GetKeepAlive, write=FKeepAlive, nodefault};
	__property AnsiString ResponseText = {read=FResponseText, write=FResponseText};
	__property int ResponseCode = {read=GetResponseCode, write=FResponseCode, nodefault};
	__property TIdHTTPProtocolVersion ResponseVersion = {read=FResponseVersion, write=FResponseVersion, 
		nodefault};
	__property Classes::TStream* ContentStream = {read=FContentStream, write=FContentStream};
public:
	#pragma option push -w-inl
	/* TIdResponseHeaderInfo.Destroy */ inline __fastcall virtual ~TIdHTTPResponse(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdHTTP;
class PASCALIMPLEMENTATION TIdHTTP : public TIdCustomHTTP 
{
	typedef TIdCustomHTTP inherited;
	
__published:
	__property AuthRetries ;
	__property AllowCookies ;
	__property HandleRedirects ;
	__property ProtocolVersion ;
	__property RedirectMaximum ;
	__property ProxyParams ;
	__property Request ;
	__property HTTPOptions ;
	__property OnRedirect ;
	__property OnSelectAuthorization ;
	__property OnSelectProxyAuthorization ;
	__property OnAuthorization ;
	__property OnProxyAuthorization ;
	__property Host ;
	__property Port ;
	__property CookieManager ;
	__property ConnectTimeout ;
public:
	#pragma option push -w-inl
	/* TIdCustomHTTP.Create */ inline __fastcall virtual TIdHTTP(Classes::TComponent* AOwner) : TIdCustomHTTP(
		AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdCustomHTTP.Destroy */ inline __fastcall virtual ~TIdHTTP(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdUnknownProtocol;
class PASCALIMPLEMENTATION EIdUnknownProtocol : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdUnknownProtocol(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdUnknownProtocol(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdUnknownProtocol(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdUnknownProtocol(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdUnknownProtocol(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdUnknownProtocol(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdUnknownProtocol(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdUnknownProtocol(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdUnknownProtocol(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdHTTPProtocolException;
class PASCALIMPLEMENTATION EIdHTTPProtocolException : public Idexception::EIdProtocolReplyError 
{
	typedef Idexception::EIdProtocolReplyError inherited;
	
protected:
	AnsiString FErrorMessage;
	
public:
	__fastcall virtual EIdHTTPProtocolException(const int anErrCode, const AnsiString asReplyMessage, const 
		AnsiString asErrorMessage);
	__property AnsiString ErrorMessage = {read=FErrorMessage};
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdHTTPProtocolException(const AnsiString Msg) : Idexception::EIdProtocolReplyError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdHTTPProtocolException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdProtocolReplyError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdHTTPProtocolException(int Ident)/* overload */ : Idexception::EIdProtocolReplyError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdHTTPProtocolException(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdProtocolReplyError(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdHTTPProtocolException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdProtocolReplyError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdHTTPProtocolException(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdProtocolReplyError(
		Msg, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdHTTPProtocolException(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdProtocolReplyError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdHTTPProtocolException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdProtocolReplyError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdHTTPProtocolException(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
#define Id_TIdHTTP_ProtocolVersion (TIdHTTPProtocolVersion)(1)
static const Shortint Id_TIdHTTP_RedirectMax = 0xf;
static const bool Id_TIdHTTP_HandleRedirects = false;

}	/* namespace Idhttp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idhttp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdHTTP
