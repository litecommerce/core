// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIrcServer.pas' rev: 5.00

#ifndef IdIrcServerHPP
#define IdIrcServerHPP

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

namespace Idircserver
{
//-- type declarations -------------------------------------------------------
typedef AnsiString IdIrcServer__1[40];

typedef void __fastcall (__closure *TIdIrcGetEvent)(Idtcpserver::TIdPeerThread* Thread);

typedef void __fastcall (__closure *TIdIrcOtherEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Command, AnsiString Parm);

typedef void __fastcall (__closure *TIdIrcOneParmEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Parm);

typedef void __fastcall (__closure *TIdIrcTwoParmEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Parm1, AnsiString Parm2);

typedef void __fastcall (__closure *TIdIrcThreeParmEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Parm1, AnsiString Parm2, AnsiString Parm3);

typedef void __fastcall (__closure *TIdIrcFiveParmEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	Parm1, AnsiString Parm2, AnsiString Parm3, AnsiString Parm4, AnsiString Parm5);

typedef void __fastcall (__closure *TIdIrcUserEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	UserName, AnsiString HostName, AnsiString ServerName, AnsiString RealName);

typedef void __fastcall (__closure *TIdIrcServerEvent)(Idtcpserver::TIdPeerThread* Thread, AnsiString 
	ServerName, AnsiString Hopcount, AnsiString Info);

class DELPHICLASS TIdIRCServer;
class PASCALIMPLEMENTATION TIdIRCServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	TIdIrcOtherEvent fOnCommandOther;
	TIdIrcOneParmEvent fOnCommandPass;
	TIdIrcTwoParmEvent fOnCommandNick;
	TIdIrcUserEvent fOnCommandUser;
	TIdIrcServerEvent fOnCommandServer;
	TIdIrcTwoParmEvent fOnCommandOper;
	TIdIrcOneParmEvent fOnCommandQuit;
	TIdIrcTwoParmEvent fOnCommandSQuit;
	TIdIrcTwoParmEvent fOnCommandJoin;
	TIdIrcOneParmEvent fOnCommandPart;
	TIdIrcFiveParmEvent fOnCommandMode;
	TIdIrcTwoParmEvent fOnCommandTopic;
	TIdIrcOneParmEvent fOnCommandNames;
	TIdIrcTwoParmEvent fOnCommandList;
	TIdIrcTwoParmEvent fOnCommandInvite;
	TIdIrcThreeParmEvent fOnCommandKick;
	TIdIrcOneParmEvent fOnCommandVersion;
	TIdIrcTwoParmEvent fOnCommandStats;
	TIdIrcTwoParmEvent fOnCommandLinks;
	TIdIrcOneParmEvent fOnCommandTime;
	TIdIrcThreeParmEvent fOnCommandConnect;
	TIdIrcOneParmEvent fOnCommandTrace;
	TIdIrcOneParmEvent fOnCommandAdmin;
	TIdIrcOneParmEvent fOnCommandInfo;
	TIdIrcTwoParmEvent fOnCommandPrivMsg;
	TIdIrcTwoParmEvent fOnCommandNotice;
	TIdIrcTwoParmEvent fOnCommandWho;
	TIdIrcTwoParmEvent fOnCommandWhoIs;
	TIdIrcThreeParmEvent fOnCommandWhoWas;
	TIdIrcTwoParmEvent fOnCommandKill;
	TIdIrcTwoParmEvent fOnCommandPing;
	TIdIrcTwoParmEvent fOnCommandPong;
	TIdIrcOneParmEvent fOnCommandError;
	TIdIrcOneParmEvent fOnCommandAway;
	TIdIrcGetEvent fOnCommandRehash;
	TIdIrcGetEvent fOnCommandRestart;
	TIdIrcTwoParmEvent fOnCommandSummon;
	TIdIrcOneParmEvent fOnCommandUsers;
	TIdIrcOneParmEvent fOnCommandWallops;
	TIdIrcOneParmEvent fOnCommandUserHost;
	TIdIrcOneParmEvent fOnCommandIsOn;
	virtual bool __fastcall DoExecute(Idtcpserver::TIdPeerThread* Thread);
	
public:
	__fastcall virtual TIdIRCServer(Classes::TComponent* AOwner);
	
__published:
	__property TIdIrcOneParmEvent OnCommandPass = {read=fOnCommandPass, write=fOnCommandPass};
	__property TIdIrcTwoParmEvent OnCommandNick = {read=fOnCommandNick, write=fOnCommandNick};
	__property TIdIrcUserEvent OnCommandUser = {read=fOnCommandUser, write=fOnCommandUser};
	__property TIdIrcServerEvent OnCommandServer = {read=fOnCommandServer, write=fOnCommandServer};
	__property TIdIrcTwoParmEvent OnCommandOper = {read=fOnCommandOper, write=fOnCommandOper};
	__property TIdIrcOneParmEvent OnCommandQuit = {read=fOnCommandQuit, write=fOnCommandQuit};
	__property TIdIrcTwoParmEvent OnCommandSQuit = {read=fOnCommandSQuit, write=fOnCommandSQuit};
	__property TIdIrcTwoParmEvent OnCommandJoin = {read=fOnCommandJoin, write=fOnCommandJoin};
	__property TIdIrcOneParmEvent OnCommandPart = {read=fOnCommandPart, write=fOnCommandPart};
	__property TIdIrcFiveParmEvent OnCommandMode = {read=fOnCommandMode, write=fOnCommandMode};
	__property TIdIrcTwoParmEvent OnCommandTopic = {read=fOnCommandTopic, write=fOnCommandTopic};
	__property TIdIrcOneParmEvent OnCommandNames = {read=fOnCommandNames, write=fOnCommandNames};
	__property TIdIrcTwoParmEvent OnCommandList = {read=fOnCommandList, write=fOnCommandList};
	__property TIdIrcTwoParmEvent OnCommandInvite = {read=fOnCommandInvite, write=fOnCommandInvite};
	__property TIdIrcThreeParmEvent OnCommandKick = {read=fOnCommandKick, write=fOnCommandKick};
	__property TIdIrcOneParmEvent OnCommandVersion = {read=fOnCommandVersion, write=fOnCommandVersion};
		
	__property TIdIrcTwoParmEvent OnCommandStats = {read=fOnCommandStats, write=fOnCommandStats};
	__property TIdIrcTwoParmEvent OnCommandLinks = {read=fOnCommandLinks, write=fOnCommandLinks};
	__property TIdIrcOneParmEvent OnCommandTime = {read=fOnCommandTime, write=fOnCommandTime};
	__property TIdIrcThreeParmEvent OnCommandConnect = {read=fOnCommandConnect, write=fOnCommandConnect
		};
	__property TIdIrcOneParmEvent OnCommandTrace = {read=fOnCommandTrace, write=fOnCommandTrace};
	__property TIdIrcOneParmEvent OnCommandAdmin = {read=fOnCommandAdmin, write=fOnCommandAdmin};
	__property TIdIrcOneParmEvent OnCommandInfo = {read=fOnCommandInfo, write=fOnCommandInfo};
	__property TIdIrcTwoParmEvent OnCommandPrivMsg = {read=fOnCommandPrivMsg, write=fOnCommandPrivMsg};
		
	__property TIdIrcTwoParmEvent OnCommandNotice = {read=fOnCommandNotice, write=fOnCommandNotice};
	__property TIdIrcTwoParmEvent OnCommandWho = {read=fOnCommandWho, write=fOnCommandWho};
	__property TIdIrcTwoParmEvent OnCommandWhoIs = {read=fOnCommandWhoIs, write=fOnCommandWhoIs};
	__property TIdIrcThreeParmEvent OnCommandWhoWas = {read=fOnCommandWhoWas, write=fOnCommandWhoWas};
	__property TIdIrcTwoParmEvent OnCommandKill = {read=fOnCommandKill, write=fOnCommandKill};
	__property TIdIrcTwoParmEvent OnCommandPing = {read=fOnCommandPing, write=fOnCommandPing};
	__property TIdIrcTwoParmEvent OnCommandPong = {read=fOnCommandPong, write=fOnCommandPong};
	__property TIdIrcOneParmEvent OnCommandError = {read=fOnCommandError, write=fOnCommandError};
	__property TIdIrcOneParmEvent OnCommandAway = {read=fOnCommandAway, write=fOnCommandAway};
	__property TIdIrcGetEvent OnCommandRehash = {read=fOnCommandRehash, write=fOnCommandRehash};
	__property TIdIrcGetEvent OnCommandRestart = {read=fOnCommandRestart, write=fOnCommandRestart};
	__property TIdIrcTwoParmEvent OnCommandSummon = {read=fOnCommandSummon, write=fOnCommandSummon};
	__property TIdIrcOneParmEvent OnCommandUsers = {read=fOnCommandUsers, write=fOnCommandUsers};
	__property TIdIrcOneParmEvent OnCommandWallops = {read=fOnCommandWallops, write=fOnCommandWallops};
		
	__property TIdIrcOneParmEvent OnCommandUserHost = {read=fOnCommandUserHost, write=fOnCommandUserHost
		};
	__property TIdIrcOneParmEvent OnCommandIsOn = {read=fOnCommandIsOn, write=fOnCommandIsOn};
	__property TIdIrcOtherEvent OnCommandOther = {read=fOnCommandOther, write=fOnCommandOther};
public:
	#pragma option push -w-inl
	/* TIdTCPServer.Destroy */ inline __fastcall virtual ~TIdIRCServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString KnownCommands[40];

}	/* namespace Idircserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idircserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIrcServer
