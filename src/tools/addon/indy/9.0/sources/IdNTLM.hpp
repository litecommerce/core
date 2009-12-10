// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdNTLM.pas' rev: 5.00

#ifndef IdNTLMHPP
#define IdNTLMHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdSSLOpenSSLHeaders.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idntlm
{
//-- type declarations -------------------------------------------------------
#pragma pack(push, 1)
struct type_1_message_header
{
	char protocol[8];
	unsigned _type;
	unsigned flags;
	Word dom_len1;
	Word dom_len2;
	unsigned dom_off;
	Word host_len1;
	Word host_len2;
	unsigned host_off;
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct type_2_message_header
{
	char protocol[8];
	unsigned _type;
	Word host_len1;
	Word host_len2;
	unsigned host_off;
	unsigned flags;
	char nonce[8];
	Word some_len1;
	Word some_len2;
	unsigned some_off;
	Word some1_len1;
	Word some1_len2;
	unsigned some1_off;
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct type_3_message_header
{
	char protocol[8];
	unsigned _type;
	Word lm_resp_len1;
	Word lm_resp_len2;
	unsigned lm_resp_off;
	Word nt_resp_len1;
	Word nt_resp_len2;
	unsigned nt_resp_off;
	Word dom_len1;
	Word dom_len2;
	unsigned dom_off;
	Word user_len1;
	Word user_len2;
	unsigned user_off;
	Word host_len1;
	Word host_len2;
	unsigned host_off;
	unsigned zero;
	unsigned msg_len;
	unsigned flags;
} ;
#pragma pack(pop)

typedef Idsslopensslheaders::des_ks_struct *Pdes_key_schedule;

//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString __fastcall BuildType1Message(AnsiString ADomain, AnsiString AHost);
extern PACKAGE AnsiString __fastcall BuildType3Message(WideString ADomain, WideString AHost, WideString 
	AUsername, AnsiString APassword, AnsiString ANonce);

}	/* namespace Idntlm */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idntlm;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdNTLM
