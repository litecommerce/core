// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIdentServer.pas' rev: 5.00

#ifndef IdIdentServerHPP
#define IdIdentServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Ididentserver
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TIdIdentQueryEvent)(Idtcpserver::TIdPeerThread* AThread, int AServerPort
	, int AClientPort);

#pragma option push -b-
enum TIdIdentErrorType { ieInvalidPort, ieNoUser, ieHiddenUser, ieUnknownError };
#pragma option pop

class DELPHICLASS TIdIdentServer;
class PASCALIMPLEMENTATION TIdIdentServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	TIdIdentQueryEvent FOnIdentQuery;
	int FQueryTimeOut;
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* AThread);
	
public:
	__fastcall virtual TIdIdentServer(Classes::TComponent* AOwner);
	void __fastcall ReplyError(Idtcpserver::TIdPeerThread* AThread, int AServerPort, int AClientPort, TIdIdentErrorType 
		AErr);
	void __fastcall ReplyIdent(Idtcpserver::TIdPeerThread* AThread, int AServerPort, int AClientPort, AnsiString 
		AOS, AnsiString AUserName, const AnsiString ACharset);
	void __fastcall ReplyOther(Idtcpserver::TIdPeerThread* AThread, int AServerPort, int AClientPort, AnsiString 
		AOther);
	
__published:
	__property int QueryTimeOut = {read=FQueryTimeOut, write=FQueryTimeOut, default=60000};
	__property TIdIdentQueryEvent OnIdentQuery = {read=FOnIdentQuery, write=FOnIdentQuery};
	__property DefaultPort ;
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdIdentServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Word IdDefIdentQueryTimeOut = 0xea60;

}	/* namespace Ididentserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Ididentserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIdentServer
