// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdFTPCommon.pas' rev: 5.00

#ifndef IdFTPCommonHPP
#define IdFTPCommonHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idftpcommon
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdFTPTransferType { ftASCII, ftBinary };
#pragma option pop

#pragma option push -b-
enum TIdFTPDataStructure { dsFile, dsRecord, dsPage };
#pragma option pop

#pragma option push -b-
enum TIdFTPTransferMode { dmBlock, dmCompressed, dmStream };
#pragma option pop

//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idftpcommon */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idftpcommon;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdFTPCommon
