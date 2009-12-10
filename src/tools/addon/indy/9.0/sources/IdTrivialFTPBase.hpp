// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTrivialFTPBase.pas' rev: 5.00

#ifndef IdTrivialFTPBaseHPP
#define IdTrivialFTPBaseHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <IdUDPClient.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtrivialftpbase
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdTFTPMode { tfNetAscii, tfOctet };
#pragma option pop

typedef SmallString<2>  WordStr;

//-- var, const, procedure ---------------------------------------------------
static const Shortint TFTP_RRQ = 0x1;
static const Shortint TFTP_WRQ = 0x2;
static const Shortint TFTP_DATA = 0x3;
static const Shortint TFTP_ACK = 0x4;
static const Shortint TFTP_ERROR = 0x5;
static const Shortint TFTP_OACK = 0x6;
static const Word MaxWord = 0xffff;
static const Shortint hdrsize = 0x4;
#define sBlockSize "blksize"
static const Shortint ErrUndefined = 0x0;
static const Shortint ErrFileNotFound = 0x1;
static const Shortint ErrAccessViolation = 0x2;
static const Shortint ErrAllocationExceeded = 0x3;
static const Shortint ErrIllegalOperation = 0x4;
static const Shortint ErrUnknownTransferID = 0x5;
static const Shortint ErrFileAlreadyExists = 0x6;
static const Shortint ErrNoSuchUser = 0x7;
static const Shortint ErrOptionNegotiationFailed = 0x8;
extern PACKAGE Word __fastcall StrToWord(const AnsiString Value);
extern PACKAGE WordStr __fastcall WordToStr(const Word Value);
extern PACKAGE AnsiString __fastcall MakeAckPkt(const Word BlockNumber);
extern PACKAGE void __fastcall SendError(Idudpbase::TIdUDPBase* UDPBase, AnsiString APeerIP, const int 
	APort, const Word ErrNumber, AnsiString ErrorString)/* overload */;
extern PACKAGE void __fastcall SendError(Idudpclient::TIdUDPClient* UDPClient, const Word ErrNumber, 
	AnsiString ErrorString)/* overload */;
extern PACKAGE void __fastcall SendError(Idudpbase::TIdUDPBase* UDPBase, AnsiString APeerIP, const int 
	APort, Sysutils::Exception* E)/* overload */;
extern PACKAGE void __fastcall SendError(Idudpclient::TIdUDPClient* UDPClient, Sysutils::Exception* 
	E)/* overload */;

}	/* namespace Idtrivialftpbase */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtrivialftpbase;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTrivialFTPBase
