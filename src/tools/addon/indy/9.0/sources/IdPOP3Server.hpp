// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdPOP3Server.pas' rev: 5.00

#ifndef IdPOP3ServerHPP
#define IdPOP3ServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdMailBox.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idpop3server
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdPOP3ServerState { Auth, Trans, Update };
#pragma option pop

class DELPHICLASS TIdPOP3ServerThread;
class PASCALIMPLEMENTATION TIdPOP3ServerThread : public Idtcpserver::TIdPeerThread 
{
	typedef Idtcpserver::TIdPeerThread inherited;
	
protected:
	AnsiString fUser;
	AnsiString fPassword;
	TIdPOP3ServerState fState;
	virtual void __fastcall BeforeRun(void);
	
public:
	__fastcall virtual TIdPOP3ServerThread(bool ACreateSuspended);
	__fastcall virtual ~TIdPOP3ServerThread(void);
	__property AnsiString Username = {read=fUser, write=fUser};
	__property AnsiString Password = {read=fPassword, write=fPassword};
	__property TIdPOP3ServerState State = {read=fState, write=fState, nodefault};
};


typedef void __fastcall (__closure *TIdPOP3ServerNoParamEvent)(Idtcpserver::TIdCommand* ASender);

typedef void __fastcall (__closure *TIdPOP3ServerMessageNumberEvent)(Idtcpserver::TIdCommand* ASender
	, int AMessageNum);

typedef void __fastcall (__closure *TIdPOP3ServerLogin)(Idtcpserver::TIdPeerThread* AThread, TIdPOP3ServerThread* 
	LThread);

typedef void __fastcall (__closure *TIdPOP3ServerAPOPCommandEvent)(Idtcpserver::TIdCommand* ASender, 
	AnsiString AMailboxID, AnsiString ADigest);

typedef void __fastcall (__closure *TIdPOP3ServerTOPCommandEvent)(Idtcpserver::TIdCommand* ASender, 
	int AMessageNum, int ANumLines);

class DELPHICLASS TIdPOP3Server;
class PASCALIMPLEMENTATION TIdPOP3Server : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	TIdPOP3ServerLogin fCommandLogin;
	TIdPOP3ServerMessageNumberEvent fCommandList;
	TIdPOP3ServerMessageNumberEvent fCommandRetr;
	TIdPOP3ServerMessageNumberEvent fCommandDele;
	TIdPOP3ServerMessageNumberEvent fCommandUIDL;
	TIdPOP3ServerTOPCommandEvent fCommandTop;
	TIdPOP3ServerNoParamEvent fCommandQuit;
	TIdPOP3ServerNoParamEvent fCommandStat;
	TIdPOP3ServerNoParamEvent fCommandRset;
	TIdPOP3ServerAPOPCommandEvent fCommandAPOP;
	void __fastcall CommandUser(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandPass(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandList(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRetr(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandDele(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandQuit(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandAPOP(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandStat(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRset(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandTop(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandUIDL(Idtcpserver::TIdCommand* ASender);
	virtual void __fastcall InitializeCommandHandlers(void);
	
public:
	__fastcall virtual TIdPOP3Server(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdPOP3Server(void);
	
__published:
	__property DefaultPort ;
	__property TIdPOP3ServerLogin CheckUser = {read=fCommandLogin, write=fCommandLogin};
	__property TIdPOP3ServerMessageNumberEvent OnLIST = {read=fCommandList, write=fCommandList};
	__property TIdPOP3ServerMessageNumberEvent OnRETR = {read=fCommandRetr, write=fCommandRetr};
	__property TIdPOP3ServerMessageNumberEvent OnDELE = {read=fCommandDele, write=fCommandDele};
	__property TIdPOP3ServerMessageNumberEvent OnUIDL = {read=fCommandUIDL, write=fCommandUIDL};
	__property TIdPOP3ServerNoParamEvent OnSTAT = {read=fCommandStat, write=fCommandStat};
	__property TIdPOP3ServerTOPCommandEvent OnTOP = {read=fCommandTop, write=fCommandTop};
	__property TIdPOP3ServerNoParamEvent OnRSET = {read=fCommandRset, write=fCommandRset};
	__property TIdPOP3ServerNoParamEvent OnQUIT = {read=fCommandQuit, write=fCommandQuit};
	__property TIdPOP3ServerAPOPCommandEvent OnAPOP = {read=fCommandAPOP, write=fCommandAPOP};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idpop3server */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idpop3server;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdPOP3Server
