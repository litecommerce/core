// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIdent.pas' rev: 5.00

#ifndef IdIdentHPP
#define IdIdentHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idident
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EIdIdentException;
class PASCALIMPLEMENTATION EIdIdentException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIdentException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIdentException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIdentException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIdentException(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIdentException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIdentException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIdentException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIdentException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIdentException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdIdentReply;
class PASCALIMPLEMENTATION EIdIdentReply : public EIdIdentException 
{
	typedef EIdIdentException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIdentReply(const AnsiString Msg) : EIdIdentException(Msg
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIdentReply(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdIdentException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIdentReply(int Ident)/* overload */ : EIdIdentException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIdentReply(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : EIdIdentException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIdentReply(const AnsiString Msg, int AHelpContext) : 
		EIdIdentException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIdentReply(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdIdentException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIdentReply(int Ident, int AHelpContext)/* overload */
		 : EIdIdentException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIdentReply(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdIdentException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIdentReply(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdIdentInvalidPort;
class PASCALIMPLEMENTATION EIdIdentInvalidPort : public EIdIdentReply 
{
	typedef EIdIdentReply inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIdentInvalidPort(const AnsiString Msg) : EIdIdentReply(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIdentInvalidPort(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdIdentReply(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIdentInvalidPort(int Ident)/* overload */ : EIdIdentReply(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIdentInvalidPort(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdIdentReply(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIdentInvalidPort(const AnsiString Msg, int AHelpContext
		) : EIdIdentReply(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIdentInvalidPort(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdIdentReply(Msg, Args, Args_Size, AHelpContext)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIdentInvalidPort(int Ident, int AHelpContext)/* overload */
		 : EIdIdentReply(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIdentInvalidPort(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdIdentReply(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIdentInvalidPort(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdIdentNoUser;
class PASCALIMPLEMENTATION EIdIdentNoUser : public EIdIdentReply 
{
	typedef EIdIdentReply inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIdentNoUser(const AnsiString Msg) : EIdIdentReply(Msg) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIdentNoUser(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdIdentReply(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIdentNoUser(int Ident)/* overload */ : EIdIdentReply(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIdentNoUser(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : EIdIdentReply(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIdentNoUser(const AnsiString Msg, int AHelpContext)
		 : EIdIdentReply(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIdentNoUser(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdIdentReply(Msg, Args, Args_Size, AHelpContext)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIdentNoUser(int Ident, int AHelpContext)/* overload */
		 : EIdIdentReply(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIdentNoUser(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdIdentReply(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIdentNoUser(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdIdentHiddenUser;
class PASCALIMPLEMENTATION EIdIdentHiddenUser : public EIdIdentReply 
{
	typedef EIdIdentReply inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIdentHiddenUser(const AnsiString Msg) : EIdIdentReply(Msg
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIdentHiddenUser(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdIdentReply(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIdentHiddenUser(int Ident)/* overload */ : EIdIdentReply(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIdentHiddenUser(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdIdentReply(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIdentHiddenUser(const AnsiString Msg, int AHelpContext
		) : EIdIdentReply(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIdentHiddenUser(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdIdentReply(Msg, Args, Args_Size, AHelpContext)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIdentHiddenUser(int Ident, int AHelpContext)/* overload */
		 : EIdIdentReply(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIdentHiddenUser(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdIdentReply(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIdentHiddenUser(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdIdentUnknownError;
class PASCALIMPLEMENTATION EIdIdentUnknownError : public EIdIdentReply 
{
	typedef EIdIdentReply inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIdentUnknownError(const AnsiString Msg) : EIdIdentReply(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIdentUnknownError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdIdentReply(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIdentUnknownError(int Ident)/* overload */ : EIdIdentReply(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIdentUnknownError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdIdentReply(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIdentUnknownError(const AnsiString Msg, int AHelpContext
		) : EIdIdentReply(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIdentUnknownError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdIdentReply(Msg, Args, Args_Size, AHelpContext)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIdentUnknownError(int Ident, int AHelpContext)/* overload */
		 : EIdIdentReply(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIdentUnknownError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdIdentReply(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIdentUnknownError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdIdentQueryTimeOut;
class PASCALIMPLEMENTATION EIdIdentQueryTimeOut : public EIdIdentReply 
{
	typedef EIdIdentReply inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdIdentQueryTimeOut(const AnsiString Msg) : EIdIdentReply(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdIdentQueryTimeOut(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdIdentReply(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdIdentQueryTimeOut(int Ident)/* overload */ : EIdIdentReply(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdIdentQueryTimeOut(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdIdentReply(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdIdentQueryTimeOut(const AnsiString Msg, int AHelpContext
		) : EIdIdentReply(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdIdentQueryTimeOut(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdIdentReply(Msg, Args, Args_Size, AHelpContext)
		 { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdIdentQueryTimeOut(int Ident, int AHelpContext)/* overload */
		 : EIdIdentReply(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdIdentQueryTimeOut(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdIdentReply(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdIdentQueryTimeOut(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdIdent;
class PASCALIMPLEMENTATION TIdIdent : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	int FQueryTimeOut;
	AnsiString FReplyString;
	AnsiString __fastcall GetReplyCharset();
	AnsiString __fastcall GetReplyOS();
	AnsiString __fastcall GetReplyOther();
	AnsiString __fastcall GetReplyUserName();
	AnsiString __fastcall FetchUserReply();
	AnsiString __fastcall FetchOS();
	void __fastcall ParseError(void);
	
public:
	__fastcall virtual TIdIdent(Classes::TComponent* AOwner);
	void __fastcall Query(Word APortOnServer, Word APortOnClient);
	__property AnsiString Reply = {read=FReplyString};
	__property AnsiString ReplyCharset = {read=GetReplyCharset};
	__property AnsiString ReplyOS = {read=GetReplyOS};
	__property AnsiString ReplyOther = {read=GetReplyOther};
	__property AnsiString ReplyUserName = {read=GetReplyUserName};
	
__published:
	__property int QueryTimeOut = {read=FQueryTimeOut, write=FQueryTimeOut, default=60000};
	__property Port ;
public:
	#pragma option push -w-inl
	/* TIdTCPClient.Destroy */ inline __fastcall virtual ~TIdIdent(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Word IdIdentQryTimeout = 0xea60;

}	/* namespace Idident */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idident;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIdent
