// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSNPP.pas' rev: 5.00

#ifndef IdSNPPHPP
#define IdSNPPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsnpp
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TConnectionResult { crCanPost, crNoPost, crAuthRequired, crTempUnavailable };
#pragma option pop

struct TCheckResp
{
	short Code;
	AnsiString Resp;
} ;

class DELPHICLASS TIdSNPP;
class PASCALIMPLEMENTATION TIdSNPP : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
private:
	bool __fastcall Pager(AnsiString APagerId);
	bool __fastcall SNPPMsg(AnsiString AMsg);
	
public:
	__fastcall virtual TIdSNPP(Classes::TComponent* AOwner);
	virtual void __fastcall Connect(const int ATimeout);
	virtual void __fastcall Disconnect(void);
	void __fastcall Reset(void);
	void __fastcall SendMessage(AnsiString APagerId, AnsiString AMsg);
	
__published:
	__property Port ;
public:
	#pragma option push -w-inl
	/* TIdTCPClient.Destroy */ inline __fastcall virtual ~TIdSNPP(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdSNPPException;
class PASCALIMPLEMENTATION EIdSNPPException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdSNPPException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdSNPPException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdSNPPException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdSNPPException(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdSNPPException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdSNPPException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdSNPPException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdSNPPException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdSNPPException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdSNPPConnectionRefused;
class PASCALIMPLEMENTATION EIdSNPPConnectionRefused : public Idexception::EIdProtocolReplyError 
{
	typedef Idexception::EIdProtocolReplyError inherited;
	
public:
	#pragma option push -w-inl
	/* EIdProtocolReplyError.CreateError */ inline __fastcall virtual EIdSNPPConnectionRefused(const int 
		AErrCode, const AnsiString AReplyMessage) : Idexception::EIdProtocolReplyError(AErrCode, AReplyMessage
		) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdSNPPConnectionRefused(const AnsiString Msg) : Idexception::EIdProtocolReplyError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdSNPPConnectionRefused(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdProtocolReplyError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdSNPPConnectionRefused(int Ident)/* overload */ : Idexception::EIdProtocolReplyError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdSNPPConnectionRefused(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdProtocolReplyError(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdSNPPConnectionRefused(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdProtocolReplyError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdSNPPConnectionRefused(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdProtocolReplyError(
		Msg, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdSNPPConnectionRefused(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdProtocolReplyError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdSNPPConnectionRefused(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdProtocolReplyError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdSNPPConnectionRefused(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdSNPPProtocolError;
class PASCALIMPLEMENTATION EIdSNPPProtocolError : public Idexception::EIdProtocolReplyError 
{
	typedef Idexception::EIdProtocolReplyError inherited;
	
public:
	#pragma option push -w-inl
	/* EIdProtocolReplyError.CreateError */ inline __fastcall virtual EIdSNPPProtocolError(const int AErrCode
		, const AnsiString AReplyMessage) : Idexception::EIdProtocolReplyError(AErrCode, AReplyMessage) { }
		
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdSNPPProtocolError(const AnsiString Msg) : Idexception::EIdProtocolReplyError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdSNPPProtocolError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdProtocolReplyError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdSNPPProtocolError(int Ident)/* overload */ : Idexception::EIdProtocolReplyError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdSNPPProtocolError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdProtocolReplyError(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdSNPPProtocolError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdProtocolReplyError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdSNPPProtocolError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdProtocolReplyError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdSNPPProtocolError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdProtocolReplyError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdSNPPProtocolError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdProtocolReplyError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdSNPPProtocolError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdSNPPNoMultiLineMessages;
class PASCALIMPLEMENTATION EIdSNPPNoMultiLineMessages : public EIdSNPPException 
{
	typedef EIdSNPPException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdSNPPNoMultiLineMessages(const AnsiString Msg) : EIdSNPPException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdSNPPNoMultiLineMessages(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdSNPPException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdSNPPNoMultiLineMessages(int Ident)/* overload */ : EIdSNPPException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdSNPPNoMultiLineMessages(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdSNPPException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdSNPPNoMultiLineMessages(const AnsiString Msg, int AHelpContext
		) : EIdSNPPException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdSNPPNoMultiLineMessages(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdSNPPException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdSNPPNoMultiLineMessages(int Ident, int AHelpContext
		)/* overload */ : EIdSNPPException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdSNPPNoMultiLineMessages(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdSNPPException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdSNPPNoMultiLineMessages(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idsnpp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsnpp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSNPP
