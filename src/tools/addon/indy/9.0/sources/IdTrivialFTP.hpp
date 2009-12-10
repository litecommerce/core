// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTrivialFTP.pas' rev: 5.00

#ifndef IdTrivialFTPHPP
#define IdTrivialFTPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdUDPClient.hpp>	// Pascal unit
#include <IdTrivialFTPBase.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtrivialftp
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdTrivialFTP;
class PASCALIMPLEMENTATION TIdTrivialFTP : public Idudpclient::TIdUDPClient 
{
	typedef Idudpclient::TIdUDPClient inherited;
	
protected:
	Idtrivialftpbase::TIdTFTPMode FMode;
	int FRequestedBlockSize;
	int FPeerPort;
	AnsiString FPeerIP;
	AnsiString __fastcall ModeToStr();
	void __fastcall CheckOptionAck(const AnsiString optionpacket);
	void __fastcall SendAck(const Word BlockNumber);
	void __fastcall RaiseError(const AnsiString errorpacket);
	
public:
	__fastcall virtual TIdTrivialFTP(Classes::TComponent* AnOwner);
	void __fastcall Get(const AnsiString ServerFile, Classes::TStream* DestinationStream)/* overload */
		;
	void __fastcall Get(const AnsiString ServerFile, const AnsiString LocalFile)/* overload */;
	void __fastcall Put(Classes::TStream* SourceStream, const AnsiString ServerFile)/* overload */;
	void __fastcall Put(const AnsiString LocalFile, const AnsiString ServerFile)/* overload */;
	
__published:
	__property Idtrivialftpbase::TIdTFTPMode TransferMode = {read=FMode, write=FMode, default=1};
	__property int RequestedBlockSize = {read=FRequestedBlockSize, write=FRequestedBlockSize, default=1500
		};
	__property OnWork ;
	__property OnWorkBegin ;
	__property OnWorkEnd ;
public:
	#pragma option push -w-inl
	/* TIdUDPBase.Destroy */ inline __fastcall virtual ~TIdTrivialFTP(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
#define GTransferMode (Idtrivialftpbase::TIdTFTPMode)(1)
static const Word GFRequestedBlockSize = 0x5dc;
static const Word GReceiveTimeout = 0xfa0;

}	/* namespace Idtrivialftp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtrivialftp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTrivialFTP
