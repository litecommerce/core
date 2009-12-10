// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTrivialFTPServer.pas' rev: 5.00

#ifndef IdTrivialFTPServerHPP
#define IdTrivialFTPServerHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdUDPServer.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdTrivialFTPBase.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtrivialftpserver
{
//-- type declarations -------------------------------------------------------
struct TPeerInfo
{
	AnsiString PeerIP;
	int PeerPort;
} ;

typedef void __fastcall (__closure *TAccessFileEvent)(System::TObject* Sender, AnsiString &FileName, 
	const TPeerInfo &PeerInfo, bool &GrantAccess, Classes::TStream* &AStream, bool &FreeStreamOnComplete
	);

typedef void __fastcall (__closure *TTransferCompleteEvent)(System::TObject* Sender, const bool Success
	, const TPeerInfo &PeerInfo, Classes::TStream* AStream, const bool WriteOperation);

class DELPHICLASS TIdTrivialFTPServer;
class PASCALIMPLEMENTATION TIdTrivialFTPServer : public Idudpserver::TIdUDPServer 
{
	typedef Idudpserver::TIdUDPServer inherited;
	
protected:
	TTransferCompleteEvent FOnTransferComplete;
	TAccessFileEvent FOnReadFile;
	TAccessFileEvent FOnWriteFile;
	Idtrivialftpbase::TIdTFTPMode __fastcall StrToMode(AnsiString mode);
	virtual void __fastcall DoReadFile(AnsiString FileName, const Idtrivialftpbase::TIdTFTPMode Mode, const 
		TPeerInfo &PeerInfo, int RequestedBlockSize);
	virtual void __fastcall DoWriteFile(AnsiString FileName, const Idtrivialftpbase::TIdTFTPMode Mode, 
		const TPeerInfo &PeerInfo, int RequestedBlockSize);
	virtual void __fastcall DoTransferComplete(const bool Success, const TPeerInfo &PeerInfo, Classes::TStream* 
		SourceStream, const bool WriteOperation);
	virtual void __fastcall DoUDPRead(Classes::TStream* AData, Idsockethandle::TIdSocketHandle* ABinding
		);
	
public:
	__fastcall virtual TIdTrivialFTPServer(Classes::TComponent* axOwner);
	
__published:
	__property TAccessFileEvent OnReadFile = {read=FOnReadFile, write=FOnReadFile};
	__property TAccessFileEvent OnWriteFile = {read=FOnWriteFile, write=FOnWriteFile};
	__property TTransferCompleteEvent OnTransferComplete = {read=FOnTransferComplete, write=FOnTransferComplete
		};
public:
	#pragma option push -w-inl
	/* TIdUDPServer.Destroy */ inline __fastcall virtual ~TIdTrivialFTPServer(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idtrivialftpserver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtrivialftpserver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTrivialFTPServer
