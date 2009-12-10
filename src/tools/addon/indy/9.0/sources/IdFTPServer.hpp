// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdFTPServer.pas' rev: 5.00

#ifndef IdFTPServerHPP
#define IdFTPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdRFCReply.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdFTPCommon.hpp>	// Pascal unit
#include <IdUserAccounts.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdFTPList.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idftpserver
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdFTPUserType { utNone, utAnonymousUser, utNormalUser };
#pragma option pop

#pragma option push -b-
enum TIdFTPSystems { ftpsOther, ftpsDOS, ftpsUNIX, ftpsVAX };
#pragma option pop

#pragma option push -b-
enum TIdFTPOperation { ftpRetr, ftpStor };
#pragma option pop

class DELPHICLASS TIdFTPServerThread;
typedef void __fastcall (__closure *TOnUserLoginEvent)(TIdFTPServerThread* ASender, const AnsiString 
	AUsername, const AnsiString APassword, bool &AAuthenticated);

typedef void __fastcall (__closure *TOnAfterUserLoginEvent)(TIdFTPServerThread* ASender);

typedef void __fastcall (__closure *TOnDirectoryEvent)(TIdFTPServerThread* ASender, AnsiString &VDirectory
	);

typedef void __fastcall (__closure *TOnGetFileSizeEvent)(TIdFTPServerThread* ASender, const AnsiString 
	AFilename, __int64 &VFileSize);

typedef void __fastcall (__closure *TOnListDirectoryEvent)(TIdFTPServerThread* ASender, const AnsiString 
	APath, Idftplist::TIdFTPListItems* ADirectoryListing);

typedef void __fastcall (__closure *TOnFileEvent)(TIdFTPServerThread* ASender, const AnsiString APathName
	);

typedef void __fastcall (__closure *TOnRenameFileEvent)(TIdFTPServerThread* ASender, const AnsiString 
	ARenameFromFile, const AnsiString ARenameToFile);

typedef void __fastcall (__closure *TOnRetrieveFileEvent)(TIdFTPServerThread* ASender, const AnsiString 
	AFileName, Classes::TStream* &VStream);

typedef void __fastcall (__closure *TOnStoreFileEvent)(TIdFTPServerThread* ASender, const AnsiString 
	AFileName, bool AAppend, Classes::TStream* &VStream);

class DELPHICLASS EIdFTPServerException;
class PASCALIMPLEMENTATION EIdFTPServerException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdFTPServerException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdFTPServerException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdFTPServerException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdFTPServerException(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdFTPServerException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdFTPServerException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdFTPServerException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdFTPServerException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdFTPServerException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdFTPServerNoOnListDirectory;
class PASCALIMPLEMENTATION EIdFTPServerNoOnListDirectory : public EIdFTPServerException 
{
	typedef EIdFTPServerException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdFTPServerNoOnListDirectory(const AnsiString Msg) : EIdFTPServerException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdFTPServerNoOnListDirectory(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdFTPServerException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdFTPServerNoOnListDirectory(int Ident)/* overload */ : 
		EIdFTPServerException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdFTPServerNoOnListDirectory(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdFTPServerException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdFTPServerNoOnListDirectory(const AnsiString Msg, int 
		AHelpContext) : EIdFTPServerException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdFTPServerNoOnListDirectory(const AnsiString Msg, 
		const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdFTPServerException(Msg, 
		Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdFTPServerNoOnListDirectory(int Ident, int AHelpContext
		)/* overload */ : EIdFTPServerException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdFTPServerNoOnListDirectory(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdFTPServerException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdFTPServerNoOnListDirectory(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdDataChannelThread;
class PASCALIMPLEMENTATION TIdDataChannelThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	Idtcpserver::TIdTCPServerConnection* FControlChannel;
	Idtcpconnection::TIdTCPConnection* FDataChannel;
	Idrfcreply::TIdRFCReply* FErrorReply;
	TIdFTPOperation FFtpOperation;
	Idrfcreply::TIdRFCReply* FOKReply;
	virtual void __fastcall Run(void);
	void __fastcall SetErrorReply(const Idrfcreply::TIdRFCReply* AValue);
	void __fastcall SetOKReply(const Idrfcreply::TIdRFCReply* AValue);
	
public:
	__fastcall TIdDataChannelThread(bool APASV, Idtcpserver::TIdTCPServerConnection* AControlConnection
		, const int ADefaultDataPort);
	__fastcall virtual ~TIdDataChannelThread(void);
	void __fastcall StartThread(TIdFTPOperation AOperation);
	void __fastcall SetupDataChannel(const AnsiString AIP, int APort);
	__property Idrfcreply::TIdRFCReply* OKReply = {read=FOKReply, write=SetOKReply};
	__property Idrfcreply::TIdRFCReply* ErrorReply = {read=FErrorReply, write=SetErrorReply};
};


class PASCALIMPLEMENTATION TIdFTPServerThread : public Idtcpserver::TIdPeerThread 
{
	typedef Idtcpserver::TIdPeerThread inherited;
	
protected:
	TIdFTPUserType FUserType;
	bool FAuthenticated;
	int FALLOSize;
	AnsiString FCurrentDir;
	Idftpcommon::TIdFTPTransferType FDataType;
	Idftpcommon::TIdFTPTransferMode FDataMode;
	int FDefaultDataPort;
	int FDataPort;
	Idftpcommon::TIdFTPDataStructure FDataStruct;
	TIdDataChannelThread* FDataChannelThread;
	AnsiString FHomeDir;
	AnsiString FUsername;
	AnsiString FPassword;
	bool FPASV;
	int FRESTPos;
	AnsiString FRNFR;
	void __fastcall CreateDataChannel(bool APASV);
	bool __fastcall IsAuthenticated(Idtcpserver::TIdCommand* ASender);
	void __fastcall KillDataChannel(void);
	void __fastcall TerminateAndFreeDataChannel(void);
	void __fastcall ReInitialize(void);
	
public:
	__fastcall TIdFTPServerThread(bool ACreateSuspended, const int ADefaultDataPort);
	__fastcall virtual ~TIdFTPServerThread(void);
	__property bool Authenticated = {read=FAuthenticated, write=FAuthenticated, nodefault};
	__property int ALLOSize = {read=FALLOSize, write=FALLOSize, nodefault};
	__property AnsiString CurrentDir = {read=FCurrentDir, write=FCurrentDir};
	__property TIdDataChannelThread* DataChannelThread = {read=FDataChannelThread, write=FDataChannelThread
		};
	__property Idftpcommon::TIdFTPTransferType DataType = {read=FDataType, write=FDataType, nodefault};
		
	__property Idftpcommon::TIdFTPTransferMode DataMode = {read=FDataMode, write=FDataMode, nodefault};
		
	__property int DataPort = {read=FDataPort, write=FDataPort, nodefault};
	__property Idftpcommon::TIdFTPDataStructure DataStruct = {read=FDataStruct, write=FDataStruct, nodefault
		};
	__property AnsiString HomeDir = {read=FHomeDir, write=FHomeDir};
	__property AnsiString Password = {read=FPassword, write=FPassword};
	__property bool PASV = {read=FPASV, write=FPASV, nodefault};
	__property int RESTPos = {read=FRESTPos, write=FRESTPos, nodefault};
	__property AnsiString Username = {read=FUsername, write=FUsername};
	__property TIdFTPUserType UserType = {read=FUserType, write=FUserType, nodefault};
};


class DELPHICLASS TIdFTPServer;
typedef void __fastcall (__closure *TIdOnGetCustomListFormat)(TIdFTPServer* ASender, Idftplist::TIdFTPListItem* 
	AItem, AnsiString &VText);

class PASCALIMPLEMENTATION TIdFTPServer : public Idtcpserver::TIdTCPServer 
{
	typedef Idtcpserver::TIdTCPServer inherited;
	
protected:
	Classes::TStringList* FAnonymousAccounts;
	bool FAllowAnonymousLogin;
	bool FAnonymousPassStrictCheck;
	Idtcpserver::TIdCommandHandler* FCmdHandlerList;
	Idtcpserver::TIdCommandHandler* FCmdHandlerNlst;
	TIdFTPSystems FEmulateSystem;
	Classes::TStrings* FHelpReply;
	AnsiString FSystemType;
	int FDefaultDataPort;
	Iduseraccounts::TIdUserManager* FUserAccounts;
	TOnAfterUserLoginEvent FOnAfterUserLogin;
	TIdOnGetCustomListFormat FOnGetCustomListFormat;
	TOnUserLoginEvent FOnUserLogin;
	TOnDirectoryEvent FOnChangeDirectory;
	TOnGetFileSizeEvent FOnGetFileSize;
	TOnListDirectoryEvent FOnListDirectory;
	TOnRenameFileEvent FOnRenameFile;
	TOnFileEvent FOnDeleteFile;
	TOnRetrieveFileEvent FOnRetrieveFile;
	TOnStoreFileEvent FOnStoreFile;
	TOnDirectoryEvent FOnMakeDirectory;
	TOnDirectoryEvent FOnRemoveDirectory;
	void __fastcall CommandUSER(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandPASS(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandCWD(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandCDUP(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandREIN(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandPORT(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandPASV(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandTYPE(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSTRU(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandMODE(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRETR(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSSAP(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandALLO(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandREST(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRNFR(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRNTO(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandABOR(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandDELE(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandRMD(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandMKD(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandPWD(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandLIST(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSITE(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSYST(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSTAT(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandSIZE(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandFEAT(Idtcpserver::TIdCommand* ASender);
	void __fastcall CommandOPTS(Idtcpserver::TIdCommand* ASender);
	void __fastcall DoChangeDirectory(TIdFTPServerThread* AThread, AnsiString &VDirectory);
	void __fastcall DoMakeDirectory(TIdFTPServerThread* AThread, AnsiString &VDirectory);
	void __fastcall DoRemoveDirectory(TIdFTPServerThread* AThread, AnsiString &VDirectory);
	void __fastcall DoGetCustomListFormat(Idftplist::TIdFTPListItem* AItem, AnsiString &VText);
	virtual void __fastcall InitializeCommandHandlers(void);
	void __fastcall ListDirectory(TIdFTPServerThread* ASender, AnsiString ADirectory, Classes::TStringList* 
		&ADirContents, bool ADetails);
	virtual void __fastcall Notification(Classes::TComponent* AComponent, Classes::TOperation Operation
		);
	void __fastcall SetAnonymousAccounts(const Classes::TStringList* AValue);
	void __fastcall SetHelpReply(const Classes::TStrings* AValue);
	void __fastcall SetUserAccounts(const Iduseraccounts::TIdUserManager* AValue);
	void __fastcall SetEmulateSystem(const TIdFTPSystems AValue);
	void __fastcall ThreadException(Idthread::TIdThread* AThread, Sysutils::Exception* AException);
	
public:
	__fastcall virtual TIdFTPServer(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdFTPServer(void);
	
__published:
	__property bool AllowAnonymousLogin = {read=FAllowAnonymousLogin, write=FAllowAnonymousLogin, default=0
		};
	__property Classes::TStringList* AnonymousAccounts = {read=FAnonymousAccounts, write=SetAnonymousAccounts
		};
	__property bool AnonymousPassStrictCheck = {read=FAnonymousPassStrictCheck, write=FAnonymousPassStrictCheck
		, default=1};
	__property int DefaultDataPort = {read=FDefaultDataPort, write=FDefaultDataPort, default=20};
	__property TIdFTPSystems EmulateSystem = {read=FEmulateSystem, write=SetEmulateSystem, default=1};
	__property Classes::TStrings* HelpReply = {read=FHelpReply, write=SetHelpReply};
	__property Iduseraccounts::TIdUserManager* UserAccounts = {read=FUserAccounts, write=SetUserAccounts
		};
	__property AnsiString SystemType = {read=FSystemType, write=FSystemType};
	__property TOnAfterUserLoginEvent OnAfterUserLogin = {read=FOnAfterUserLogin, write=FOnAfterUserLogin
		};
	__property TOnDirectoryEvent OnChangeDirectory = {read=FOnChangeDirectory, write=FOnChangeDirectory
		};
	__property TIdOnGetCustomListFormat OnGetCustomListFormat = {read=FOnGetCustomListFormat, write=FOnGetCustomListFormat
		};
	__property TOnGetFileSizeEvent OnGetFileSize = {read=FOnGetFileSize, write=FOnGetFileSize};
	__property TOnUserLoginEvent OnUserLogin = {read=FOnUserLogin, write=FOnUserLogin};
	__property TOnListDirectoryEvent OnListDirectory = {read=FOnListDirectory, write=FOnListDirectory};
		
	__property TOnRenameFileEvent OnRenameFile = {read=FOnRenameFile, write=FOnRenameFile};
	__property TOnFileEvent OnDeleteFile = {read=FOnDeleteFile, write=FOnDeleteFile};
	__property TOnRetrieveFileEvent OnRetrieveFile = {read=FOnRetrieveFile, write=FOnRetrieveFile};
	__property TOnStoreFileEvent OnStoreFile = {read=FOnStoreFile, write=FOnStoreFile};
	__property TOnDirectoryEvent OnMakeDirectory = {read=FOnMakeDirectory, write=FOnMakeDirectory};
	__property TOnDirectoryEvent OnRemoveDirectory = {read=FOnRemoveDirectory, write=FOnRemoveDirectory
		};
};


//-- var, const, procedure ---------------------------------------------------
static const bool Id_DEF_AllowAnon = false;
static const bool Id_DEF_PassStrictCheck = true;
#define Id_DEF_SystemType (TIdFTPSystems)(1)

}	/* namespace Idftpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idftpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdFTPServer
