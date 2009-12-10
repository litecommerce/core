// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdDICTServer.pas' rev: 5.00

#ifndef IdDICTServerHPP
#define IdDICTServerHPP

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

namespace Iddictserver
{
//-- type declarations -------------------------------------------------------
typedef AnsiString IdDICTServer__1[10];

typedef void __fastcall (__closure *TIdDICTGetEvent)(Idtcpserver::TIdPeerThread* Thread);

typedef void __fastcall (__closure *TIdDICTOtherEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Command, AnsiString Parm);

typedef void __fastcall (__closure *TIdDICTDefineEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Database, AnsiString WordToFind);

typedef void __fastcall (__closure *TIdDICTMatchEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Database, AnsiString Strategy, AnsiString WordToFind);

typedef void __fastcall (__closure *TIdDICTShowEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Command);

typedef void __fastcall (__closure *TIdDICTAuthEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Username, AnsiString authstring);

class DELPHICLASS TIdDICTServer;
class PASCALIMPLEMENTATION TIdDICTServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	TIdDICTGetEvent fOnCommandHELP;
	TIdDICTDefineEvent fOnCommandDEFINE;
	TIdDICTMatchEvent fOnCommandMATCH;
	TIdDICTGetEvent fOnCommandQUIT;
	TIdDICTShowEvent fOnCommandSHOW;
	TIdDICTAuthEvent fOnCommandAUTH;
	TIdDICTAuthEvent fOnCommandSASLAuth;
	TIdDICTOtherEvent fOnCommandOption;
	TIdDICTGetEvent fOnCommandSTAT;
	TIdDICTShowEvent fOnCommandCLIENT;
	TIdDICTOtherEvent fOnCommandOther;
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* Thread);
	
public:
	__fastcall virtual TIdDICTServer(Classes::TComponent* AOwner);
	
__published:
	__property DefaultPort ;
	__property TIdDICTGetEvent OnCommandHelp = {read=fOnCommandHELP, write=fOnCommandHELP};
	__property TIdDICTDefineEvent OnCommandDefine = {read=fOnCommandDEFINE, write=fOnCommandDEFINE};
	__property TIdDICTMatchEvent OnCommandMatch = {read=fOnCommandMATCH, write=fOnCommandMATCH};
	__property TIdDICTGetEvent OnCommandQuit = {read=fOnCommandQUIT, write=fOnCommandQUIT};
	__property TIdDICTShowEvent OnCommandShow = {read=fOnCommandSHOW, write=fOnCommandSHOW};
	__property TIdDICTAuthEvent OnCommandAuth = {read=fOnCommandAUTH, write=fOnCommandAUTH};
	__property TIdDICTAuthEvent OnCommandSASLAuth = {read=fOnCommandSASLAuth, write=fOnCommandSASLAuth}
		;
	__property TIdDICTOtherEvent OnCommandOption = {read=fOnCommandOption, write=fOnCommandOption};
	__property TIdDICTGetEvent OnCommandStatus = {read=fOnCommandSTAT, write=fOnCommandSTAT};
	__property TIdDICTShowEvent OnCommandClient = {read=fOnCommandCLIENT, write=fOnCommandCLIENT};
	__property TIdDICTOtherEvent OnCommandOther = {read=fOnCommandOther, write=fOnCommandOther};
public:
		
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdDICTServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString KnownCommands[10];

}	/* namespace Iddictserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Iddictserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdDICTServer
