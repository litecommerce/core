// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRawFunctions.pas' rev: 5.00

#ifndef IdRawFunctionsHPP
#define IdRawFunctionsHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdRawHeaders.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idrawfunctions
{
//-- type declarations -------------------------------------------------------
//-- var, const, procedure ---------------------------------------------------
extern PACKAGE bool __fastcall IdRawBuildArp(Word AHwAddressFormat, Word AProtocolFormat, Byte AHwAddressLen
	, Byte AProtocolLen, Word AnOpType, const Idrawheaders::TIdEtherAddr &ASenderHw, Idstack::TIdInAddr 
	ASenderPr, const Idrawheaders::TIdEtherAddr &ATargetHw, Idstack::TIdInAddr ATargetPr, const void *APayload
	, int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildDns(Word AnId, Word AFlags, Word ANumQuestions, Word ANumAnswerRecs
	, Word ANumAuthRecs, Word ANumAddRecs, const void *APayload, int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildEthernet(const Idrawheaders::TIdEtherAddr &ADest, const Idrawheaders::TIdEtherAddr 
	&ASource, Word AType, const void *APayload, int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildIp(Word ALen, Byte ATos, Word AnId, Word AFrag, Byte ATtl, 
	Byte AProtocol, Idstack::TIdInAddr ASource, Idstack::TIdInAddr ADest, const void *APayload, int APayloadSize
	, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildIcmpEcho(Byte AType, Byte ACode, Word AnId, Word ASeq, const 
	void *APayload, int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildIcmpMask(Byte AType, Byte ACode, Word AnId, Word ASeq, unsigned 
	AMask, const void *APayload, int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildIcmpUnreach(Byte AType, Byte ACode, Word AnOrigLen, Byte AnOrigTos
	, Word AnOrigId, Word AnOrigFrag, Byte AnOrigTtl, Byte AnOrigProtocol, Idstack::TIdInAddr AnOrigSource
	, Idstack::TIdInAddr AnOrigDest, const int AnOrigPayload, const int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildIcmpTimeExceed(Byte AType, Byte ACode, Word AnOrigLen, Byte 
	AnOrigTos, Word AnOrigId, Word AnOrigFrag, Byte AnOrigTtl, Byte AnOrigProtocol, Idstack::TIdInAddr 
	AnOrigSource, Idstack::TIdInAddr AnOrigDest, const void *AnOrigPayload, int APayloadSize, void *ABuffer
	);
extern PACKAGE bool __fastcall IdRawBuildIcmpTimestamp(Byte AType, Byte ACode, Word AnId, Word ASeq, 
	unsigned AnOtime, unsigned AnRtime, unsigned ATtime, const void *APayload, int APayloadSize, void *
	ABuffer);
extern PACKAGE bool __fastcall IdRawBuildIcmpRedirect(Byte AType, Byte ACode, Idstack::TIdInAddr AGateway
	, Word AnOrigLen, Byte AnOrigTos, Word AnOrigId, Word AnOrigFrag, Byte AnOrigTtl, Byte AnOrigProtocol
	, Idstack::TIdInAddr AnOrigSource, Idstack::TIdInAddr AnOrigDest, const void *AnOrigPayload, int APayloadSize
	, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildIgmp(Byte AType, Byte ACode, Idstack::TIdInAddr AnIp, const 
	int APayload, const int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildRip(Byte ACommand, Byte AVersion, Word ARoutingDomain, Word 
	AnAddressFamily, Word ARoutingTag, unsigned AnAddr, unsigned AMask, unsigned ANextHop, unsigned AMetric
	, const void *APayload, int APayloadSize, void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildTcp(Word ASourcePort, Word ADestPort, unsigned ASeq, unsigned 
	AnAck, Byte AControl, Word AWindowSize, Word AnUrgent, const int APayload, const int APayloadSize, 
	void *ABuffer);
extern PACKAGE bool __fastcall IdRawBuildUdp(Word ASourcePort, Word ADestPort, const void *APayload, 
	int APayloadSize, void *ABuffer);

}	/* namespace Idrawfunctions */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idrawfunctions;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRawFunctions
