// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdHTTPHeaderInfo.pas' rev: 5.00

#ifndef IdHTTPHeaderInfoHPP
#define IdHTTPHeaderInfoHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdHeaderList.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdAuthentication.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idhttpheaderinfo
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdEntityHeaderInfo;
class PASCALIMPLEMENTATION TIdEntityHeaderInfo : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FCacheControl;
	Idheaderlist::TIdHeaderList* FRawHeaders;
	AnsiString FConnection;
	AnsiString FContentEncoding;
	AnsiString FContentLanguage;
	int FContentLength;
	unsigned FContentRangeEnd;
	unsigned FContentRangeStart;
	AnsiString FContentType;
	AnsiString FContentVersion;
	Idheaderlist::TIdHeaderList* FCustomHeaders;
	System::TDateTime FDate;
	System::TDateTime FExpires;
	System::TDateTime FLastModified;
	AnsiString FPragma;
	bool FHasContentLength;
	virtual void __fastcall AssignTo(Classes::TPersistent* Destination);
	virtual void __fastcall ProcessHeaders(void);
	virtual void __fastcall SetHeaders(void);
	void __fastcall SetContentLength(const int AValue);
	void __fastcall SetCustomHeaders(const Idheaderlist::TIdHeaderList* AValue);
	
public:
	virtual void __fastcall Clear(void);
	__fastcall virtual TIdEntityHeaderInfo(void);
	__fastcall virtual ~TIdEntityHeaderInfo(void);
	__property bool HasContentLength = {read=FHasContentLength, nodefault};
	__property Idheaderlist::TIdHeaderList* RawHeaders = {read=FRawHeaders};
	
__published:
	__property AnsiString CacheControl = {read=FCacheControl, write=FCacheControl};
	__property AnsiString Connection = {read=FConnection, write=FConnection};
	__property AnsiString ContentEncoding = {read=FContentEncoding, write=FContentEncoding};
	__property AnsiString ContentLanguage = {read=FContentLanguage, write=FContentLanguage};
	__property int ContentLength = {read=FContentLength, write=SetContentLength, nodefault};
	__property unsigned ContentRangeEnd = {read=FContentRangeEnd, write=FContentRangeEnd, nodefault};
	__property unsigned ContentRangeStart = {read=FContentRangeStart, write=FContentRangeStart, nodefault
		};
	__property AnsiString ContentType = {read=FContentType, write=FContentType};
	__property AnsiString ContentVersion = {read=FContentVersion, write=FContentVersion};
	__property Idheaderlist::TIdHeaderList* CustomHeaders = {read=FCustomHeaders, write=SetCustomHeaders
		};
	__property System::TDateTime Date = {read=FDate, write=FDate};
	__property System::TDateTime Expires = {read=FExpires, write=FExpires};
	__property System::TDateTime LastModified = {read=FLastModified, write=FLastModified};
	__property AnsiString Pragma = {read=FPragma, write=FPragma};
};


class DELPHICLASS TIdProxyConnectionInfo;
class PASCALIMPLEMENTATION TIdProxyConnectionInfo : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	Idauthentication::TIdAuthentication* FAuthentication;
	AnsiString FPassword;
	int FPort;
	AnsiString FServer;
	AnsiString FUsername;
	bool FBasicByDefault;
	virtual void __fastcall AssignTo(Classes::TPersistent* Destination);
	void __fastcall SetProxyPort(const int Value);
	void __fastcall SetProxyServer(const AnsiString Value);
	
public:
	__fastcall TIdProxyConnectionInfo(void);
	void __fastcall Clear(void);
	__fastcall virtual ~TIdProxyConnectionInfo(void);
	void __fastcall SetHeaders(Idheaderlist::TIdHeaderList* Headers);
	__property Idauthentication::TIdAuthentication* Authentication = {read=FAuthentication, write=FAuthentication
		};
	
__published:
	__property bool BasicAuthentication = {read=FBasicByDefault, write=FBasicByDefault, nodefault};
	__property AnsiString ProxyPassword = {read=FPassword, write=FPassword};
	__property int ProxyPort = {read=FPort, write=SetProxyPort, nodefault};
	__property AnsiString ProxyServer = {read=FServer, write=SetProxyServer};
	__property AnsiString ProxyUsername = {read=FUsername, write=FUsername};
};


class DELPHICLASS TIdRequestHeaderInfo;
class PASCALIMPLEMENTATION TIdRequestHeaderInfo : public TIdEntityHeaderInfo 
{
	typedef TIdEntityHeaderInfo inherited;
	
protected:
	AnsiString FAccept;
	AnsiString FAcceptCharSet;
	AnsiString FAcceptEncoding;
	AnsiString FAcceptLanguage;
	AnsiString FExpect;
	AnsiString FFrom;
	AnsiString FPassword;
	AnsiString FReferer;
	AnsiString FUserAgent;
	AnsiString FUserName;
	AnsiString FHost;
	bool FBasicByDefault;
	AnsiString FProxyConnection;
	virtual void __fastcall AssignTo(Classes::TPersistent* Destination);
	
public:
	Idauthentication::TIdAuthentication* Authentication;
	virtual void __fastcall Clear(void);
	virtual void __fastcall ProcessHeaders(void);
	virtual void __fastcall SetHeaders(void);
	
__published:
	__property AnsiString Accept = {read=FAccept, write=FAccept};
	__property AnsiString AcceptCharSet = {read=FAcceptCharSet, write=FAcceptCharSet};
	__property AnsiString AcceptEncoding = {read=FAcceptEncoding, write=FAcceptEncoding};
	__property AnsiString AcceptLanguage = {read=FAcceptLanguage, write=FAcceptLanguage};
	__property bool BasicAuthentication = {read=FBasicByDefault, write=FBasicByDefault, nodefault};
	__property AnsiString Host = {read=FHost, write=FHost};
	__property AnsiString From = {read=FFrom, write=FFrom};
	__property AnsiString Password = {read=FPassword, write=FPassword};
	__property AnsiString Referer = {read=FReferer, write=FReferer};
	__property AnsiString UserAgent = {read=FUserAgent, write=FUserAgent};
	__property AnsiString Username = {read=FUserName, write=FUserName};
	__property AnsiString ProxyConnection = {read=FProxyConnection, write=FProxyConnection};
public:
	#pragma option push -w-inl
	/* TIdEntityHeaderInfo.Create */ inline __fastcall virtual TIdRequestHeaderInfo(void) : TIdEntityHeaderInfo(
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdEntityHeaderInfo.Destroy */ inline __fastcall virtual ~TIdRequestHeaderInfo(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdResponseHeaderInfo;
class PASCALIMPLEMENTATION TIdResponseHeaderInfo : public TIdEntityHeaderInfo 
{
	typedef TIdEntityHeaderInfo inherited;
	
protected:
	AnsiString FLocation;
	AnsiString FServer;
	AnsiString FProxyConnection;
	Idheaderlist::TIdHeaderList* FProxyAuthenticate;
	Idheaderlist::TIdHeaderList* FWWWAuthenticate;
	void __fastcall SetProxyAuthenticate(const Idheaderlist::TIdHeaderList* Value);
	void __fastcall SetWWWAuthenticate(const Idheaderlist::TIdHeaderList* Value);
	
public:
	virtual void __fastcall Clear(void);
	__fastcall virtual TIdResponseHeaderInfo(void);
	__fastcall virtual ~TIdResponseHeaderInfo(void);
	virtual void __fastcall ProcessHeaders(void);
	
__published:
	__property AnsiString Location = {read=FLocation, write=FLocation};
	__property AnsiString ProxyConnection = {read=FProxyConnection, write=FProxyConnection};
	__property Idheaderlist::TIdHeaderList* ProxyAuthenticate = {read=FProxyAuthenticate, write=SetProxyAuthenticate
		};
	__property AnsiString Server = {read=FServer, write=FServer};
	__property Idheaderlist::TIdHeaderList* WWWAuthenticate = {read=FWWWAuthenticate, write=SetWWWAuthenticate
		};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idhttpheaderinfo */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idhttpheaderinfo;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdHTTPHeaderInfo
