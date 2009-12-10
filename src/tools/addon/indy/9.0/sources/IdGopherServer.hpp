// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdGopherServer.pas' rev: 5.00

#ifndef IdGopherServerHPP
#define IdGopherServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idgopherserver
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TRequestEvent)(Idtcpserver::TIdPeerThread* AThread, AnsiString ARequest
	);

typedef void __fastcall (__closure *TPlusRequestEvent)(Idtcpserver::TIdPeerThread* AThread, AnsiString 
	ARequest, AnsiString APlusData);

class DELPHICLASS TIdGopherServer;
class PASCALIMPLEMENTATION TIdGopherServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
private:
	AnsiString fAdminEmail;
	TRequestEvent fOnRequest;
	TPlusRequestEvent fOnPlusRequest;
	bool fTruncateUserFriendly;
	int fTruncateLength;
	
protected:
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* Thread);
	
public:
	__fastcall virtual TIdGopherServer(Classes::TComponent* AOwner);
	AnsiString __fastcall ReturnGopherItem(char ItemType, AnsiString UserFriendlyName, AnsiString RealResourceName
		, AnsiString HostServer, int HostPort);
	void __fastcall SendDirectoryEntry(Idtcpserver::TIdPeerThread* Thread, char ItemType, AnsiString UserFriendlyName
		, AnsiString RealResourceName, AnsiString HostServer, int HostPort);
	void __fastcall SetTruncateUserFriendlyName(bool truncate);
	void __fastcall SetTruncateLength(int length);
	
__published:
	__property AnsiString AdminEmail = {read=fAdminEmail, write=fAdminEmail};
	__property TRequestEvent OnRequest = {read=fOnRequest, write=fOnRequest};
	__property TPlusRequestEvent OnPlusRequest = {read=fOnPlusRequest, write=fOnPlusRequest};
	__property bool TruncateUserFriendlyName = {read=fTruncateUserFriendly, write=SetTruncateUserFriendlyName
		, default=1};
	__property int TruncateLength = {read=fTruncateLength, write=SetTruncateLength, default=70};
public:
		
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdGopherServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idgopherserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idgopherserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdGopherServer
