// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIMAP4Server.pas' rev: 5.00

#ifndef IdIMAP4ServerHPP
#define IdIMAP4ServerHPP

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

namespace Idimap4server
{
//-- type declarations -------------------------------------------------------
typedef AnsiString IdIMAP4Server__1[25];

typedef void __fastcall (__closure *TCommandEvent)(Idtcpserver::TIdPeerThread* Thread, const AnsiString 
	Tag, const AnsiString CmdStr, bool &Handled);

class DELPHICLASS TIdIMAP4Server;
class PASCALIMPLEMENTATION TIdIMAP4Server : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	TCommandEvent fOnCommandCAPABILITY;
	TCommandEvent fONCommandNOOP;
	TCommandEvent fONCommandLOGOUT;
	TCommandEvent fONCommandAUTHENTICATE;
	TCommandEvent fONCommandLOGIN;
	TCommandEvent fONCommandSELECT;
	TCommandEvent fONCommandEXAMINE;
	TCommandEvent fONCommandCREATE;
	TCommandEvent fONCommandDELETE;
	TCommandEvent fONCommandRENAME;
	TCommandEvent fONCommandSUBSCRIBE;
	TCommandEvent fONCommandUNSUBSCRIBE;
	TCommandEvent fONCommandLIST;
	TCommandEvent fONCommandLSUB;
	TCommandEvent fONCommandSTATUS;
	TCommandEvent fONCommandAPPEND;
	TCommandEvent fONCommandCHECK;
	TCommandEvent fONCommandCLOSE;
	TCommandEvent fONCommandEXPUNGE;
	TCommandEvent fONCommandSEARCH;
	TCommandEvent fONCommandFETCH;
	TCommandEvent fONCommandSTORE;
	TCommandEvent fONCommandCOPY;
	TCommandEvent fONCommandUID;
	TCommandEvent fONCommandX;
	TCommandEvent fOnCommandError;
	void __fastcall DoCommandCAPABILITY(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const 
		AnsiString CmdStr, bool &Handled);
	void __fastcall DoCommandNOOP(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandLOGOUT(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandAUTHENTICATE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const 
		AnsiString CmdStr, bool &Handled);
	void __fastcall DoCommandLOGIN(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandSELECT(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandEXAMINE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandCREATE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandDELETE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandRENAME(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandSUBSCRIBE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const 
		AnsiString CmdStr, bool &Handled);
	void __fastcall DoCommandUNSUBSCRIBE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const 
		AnsiString CmdStr, bool &Handled);
	void __fastcall DoCommandLIST(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandLSUB(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandSTATUS(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandAPPEND(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandCHECK(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandCLOSE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandEXPUNGE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandSEARCH(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandFETCH(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandSTORE(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandCOPY(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandUID(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandX(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	void __fastcall DoCommandError(Idtcpserver::TIdPeerThread* Thread, const AnsiString Tag, const AnsiString 
		CmdStr, bool &Handled);
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* Thread);
	
public:
	__fastcall virtual TIdIMAP4Server(Classes::TComponent* AOwner);
	
__published:
	__property TCommandEvent ONCommandCAPABILITY = {read=fOnCommandCAPABILITY, write=fOnCommandCAPABILITY
		};
	__property TCommandEvent ONCommandNOOP = {read=fONCommandNOOP, write=fONCommandNOOP};
	__property TCommandEvent ONCommandLOGOUT = {read=fONCommandLOGOUT, write=fONCommandLOGOUT};
	__property TCommandEvent ONCommandAUTHENTICATE = {read=fONCommandAUTHENTICATE, write=fONCommandAUTHENTICATE
		};
	__property TCommandEvent ONCommandLOGIN = {read=fONCommandLOGIN, write=fONCommandLOGIN};
	__property TCommandEvent ONCommandSELECT = {read=fONCommandSELECT, write=fONCommandSELECT};
	__property TCommandEvent OnCommandEXAMINE = {read=fONCommandEXAMINE, write=fONCommandEXAMINE};
	__property TCommandEvent ONCommandCREATE = {read=fONCommandCREATE, write=fONCommandCREATE};
	__property TCommandEvent ONCommandDELETE = {read=fONCommandDELETE, write=fONCommandDELETE};
	__property TCommandEvent OnCommandRENAME = {read=fONCommandRENAME, write=fONCommandRENAME};
	__property TCommandEvent ONCommandSUBSCRIBE = {read=fONCommandSUBSCRIBE, write=fONCommandSUBSCRIBE}
		;
	__property TCommandEvent ONCommandUNSUBSCRIBE = {read=fONCommandUNSUBSCRIBE, write=fONCommandUNSUBSCRIBE
		};
	__property TCommandEvent ONCommandLIST = {read=fONCommandLIST, write=fONCommandLIST};
	__property TCommandEvent OnCommandLSUB = {read=fONCommandLSUB, write=fONCommandLSUB};
	__property TCommandEvent ONCommandSTATUS = {read=fONCommandSTATUS, write=fONCommandSTATUS};
	__property TCommandEvent OnCommandAPPEND = {read=fONCommandAPPEND, write=fONCommandAPPEND};
	__property TCommandEvent ONCommandCHECK = {read=fONCommandCHECK, write=fONCommandCHECK};
	__property TCommandEvent OnCommandCLOSE = {read=fONCommandCLOSE, write=fONCommandCLOSE};
	__property TCommandEvent ONCommandEXPUNGE = {read=fONCommandEXPUNGE, write=fONCommandEXPUNGE};
	__property TCommandEvent OnCommandSEARCH = {read=fONCommandSEARCH, write=fONCommandSEARCH};
	__property TCommandEvent ONCommandFETCH = {read=fONCommandFETCH, write=fONCommandFETCH};
	__property TCommandEvent OnCommandSTORE = {read=fONCommandSTORE, write=fONCommandSTORE};
	__property TCommandEvent OnCommandCOPY = {read=fONCommandCOPY, write=fONCommandCOPY};
	__property TCommandEvent ONCommandUID = {read=fONCommandUID, write=fONCommandUID};
	__property TCommandEvent OnCommandX = {read=fONCommandX, write=fONCommandX};
	__property TCommandEvent OnCommandError = {read=fOnCommandError, write=fOnCommandError};
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdIMAP4Server(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString IMAPCommands[25];

}	/* namespace Idimap4server */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idimap4server;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIMAP4Server
