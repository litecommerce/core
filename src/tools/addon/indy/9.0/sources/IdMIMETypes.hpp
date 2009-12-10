// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMIMETypes.pas' rev: 5.00

#ifndef IdMIMETypesHPP
#define IdMIMETypesHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmimetypes
{
//-- type declarations -------------------------------------------------------
typedef AnsiString IdMIMETypes__1[7];

//-- var, const, procedure ---------------------------------------------------
static const char MIMESplit = '\x2f';
#define MIMEXVal "x-"
#define MIMETypeApplication "application/"
#define MIMETypeAudio "audio/"
#define MIMETypeImage "image/"
#define MIMETypeMessage "message/"
#define MIMETypeMultipart "multipart/"
#define MIMETypeText "text/"
#define MIMETypeVideo "video/"
static const Shortint MaxMIMEType = 0x6;
#define MIMESubOctetStream "octet-stream"
#define MIMESubMacBinHex40 "mac-binhex40"
static const Shortint MaxMIMESubTypes = 0x1;
#define MIMEEncBase64 "base64"
#define MIMEEncUUEncode "x-uu"
#define MIMEEncXXEncode "x-xx"
static const Shortint MaxMIMEBinToASCIIType = 0x2;
#define MIMEEncRSAMD2 "x-rsa-md2"
#define MIMEEncRSAMD4 "x-rsa-md4"
#define MIMEEncRSAMD5 "x-rsa-md5"
#define MIMEEncNISTSHA "x-nist-sha"
static const Shortint MaxMIMEMessageDigestType = 0x3;
#define MIMEEncRLECompress "x-rle-compress"
static const Shortint MaxMIMECompressType = 0x0;
static const Shortint MaxMIMEEncType = 0x7;
#define MIMEFullApplicationOctetStream "application/octet-stream"
extern PACKAGE AnsiString MIMEMediaType[7];
extern PACKAGE bool __fastcall ReturnMIMEType(AnsiString &MediaType, AnsiString &EncType);

}	/* namespace Idmimetypes */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmimetypes;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMIMETypes
