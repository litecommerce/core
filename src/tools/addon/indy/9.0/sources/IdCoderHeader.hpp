// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdCoderHeader.pas' rev: 5.00

#ifndef IdCoderHeaderHPP
#define IdCoderHeaderHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdEMailAddress.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idcoderheader
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TTransfer { bit7, bit8, iso2022jp };
#pragma option pop

typedef Set<char, 0, 255>  CSET;

//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString __fastcall EncodeAddressItem(Idemailaddress::TIdEMailAddressItem* EmailAddr
	, const char HeaderEncoding, TTransfer TransferHeader, AnsiString MimeCharSet);
extern PACKAGE AnsiString __fastcall DecodeHeader(AnsiString Header);
extern PACKAGE AnsiString __fastcall Decode2022JP(const AnsiString S);
extern PACKAGE void __fastcall InitializeISO(TTransfer &TransferHeader, char &HeaderEncoding, AnsiString 
	&CharSet);
extern PACKAGE void __fastcall DecodeAddress(Idemailaddress::TIdEMailAddressItem* EMailAddr);
extern PACKAGE void __fastcall DecodeAddresses(AnsiString AEMails, Idemailaddress::TIdEMailAddressList* 
	EMailAddr);
extern PACKAGE AnsiString __fastcall EncodeAddress(Idemailaddress::TIdEMailAddressList* EmailAddr, const 
	char HeaderEncoding, TTransfer TransferHeader, AnsiString MimeCharSet);
extern PACKAGE AnsiString __fastcall Encode2022JP(const AnsiString S);
extern PACKAGE AnsiString __fastcall EncodeHeader(const AnsiString Header, const CSET &specials, const 
	char HeaderEncoding, TTransfer TransferHeader, AnsiString MimeCharSet);

}	/* namespace Idcoderheader */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idcoderheader;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdCoderHeader
