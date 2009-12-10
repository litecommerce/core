// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdFingerServer.pas' rev: 5.00

#ifndef IdFingerServerHPP
#define IdFingerServerHPP

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

namespace Idfingerserver
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TIdFingerGetEvent)(Idtcpserver::TIdPeerThread* AThread, const AnsiString 
	AUserName);

class DELPHICLASS TIdFingerServer;
class PASCALIMPLEMENTATION TIdFingerServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	TIdFingerGetEvent FOnCommandFinger;
	TIdFingerGetEvent FOnCommandVerboseFinger;
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* AThread);
	
public:
	__fastcall virtual TIdFingerServer(Classes::TComponent* AOwner);
	
__published:
	__property TIdFingerGetEvent OnCommandFinger = {read=FOnCommandFinger, write=FOnCommandFinger};
	__property TIdFingerGetEvent OnCommandVerboseFinger = {read=FOnCommandVerboseFinger, write=FOnCommandVerboseFinger
		};
	__property DefaultPort ;
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdFingerServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idfingerserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idfingerserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdFingerServer
