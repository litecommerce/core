// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRemoteCMDClient.pas' rev: 5.00

#ifndef IdRemoteCMDClientHPP
#define IdRemoteCMDClientHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idremotecmdclient
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EIdCanNotBindRang;
class PASCALIMPLEMENTATION EIdCanNotBindRang : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdCanNotBindRang(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdCanNotBindRang(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdCanNotBindRang(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdCanNotBindRang(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdCanNotBindRang(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdCanNotBindRang(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdCanNotBindRang(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdCanNotBindRang(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdCanNotBindRang(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdRemoteCMDClient;
class PASCALIMPLEMENTATION TIdRemoteCMDClient : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	bool FUseReservedPorts;
	bool FUseStdError;
	AnsiString FErrorMessage;
	bool FErrorReply;
	virtual AnsiString __fastcall InternalExec(AnsiString AParam1, AnsiString AParam2, AnsiString ACommand
		);
	
public:
	__fastcall virtual TIdRemoteCMDClient(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdRemoteCMDClient(void);
	virtual AnsiString __fastcall Execute(AnsiString ACommand);
	__property bool ErrorReply = {read=FErrorReply, nodefault};
	__property AnsiString ErrorMessage = {read=FErrorMessage};
	
__published:
	__property bool UseStdError = {read=FUseStdError, write=FUseStdError, default=1};
};


//-- var, const, procedure ---------------------------------------------------
static const bool IDRemoteUseStdErr = true;
static const bool IDRemoteFixPort = true;

}	/* namespace Idremotecmdclient */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idremotecmdclient;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRemoteCMDClient
