// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIcmpClient.pas' rev: 5.00

#ifndef IdIcmpClientHPP
#define IdIcmpClientHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdRawClient.hpp>	// Pascal unit
#include <IdRawBase.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idicmpclient
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TReplyStatusTypes { rsEcho, rsError, rsTimeOut, rsErrorUnreachable, rsErrorTTLExceeded };
#pragma option pop

struct TReplyStatus
{
	int BytesReceived;
	AnsiString FromIpAddress;
	Byte MsgType;
	Word SequenceId;
	unsigned MsRoundTripTime;
	Byte TimeToLive;
	TReplyStatusTypes ReplyStatusType;
} ;

typedef char TCharBuf[1024];

typedef Byte TICMPDataBuffer[128];

typedef void __fastcall (__closure *TOnReplyEvent)(Classes::TComponent* ASender, const TReplyStatus 
	&AReplyStatus);

class DELPHICLASS TIdIcmpClient;
class PASCALIMPLEMENTATION TIdIcmpClient : public Idrawclient::TIdRawClient 
{
	typedef Idrawclient::TIdRawClient inherited;
	
protected:
	char bufReceive[1024];
	char bufIcmp[1024];
	Word wSeqNo;
	int iDataSize;
	TReplyStatus FReplyStatus;
	TOnReplyEvent FOnReply;
	AnsiString FReplydata;
	Word __fastcall CalcCheckSum(void);
	bool __fastcall DecodeResponse(unsigned BytesRead, TReplyStatus &AReplyStatus);
	void __fastcall DoReply(const TReplyStatus &AReplyStatus);
	void __fastcall GetEchoReply(void);
	void __fastcall PrepareEchoRequest(AnsiString Buffer);
	void __fastcall SendEchoRequest(void);
	
public:
	__fastcall virtual TIdIcmpClient(Classes::TComponent* AOwner);
	void __fastcall Ping(AnsiString ABuffer, Word SequenceID);
	TReplyStatus __fastcall Receive(int ATimeOut);
	__property TReplyStatus ReplyStatus = {read=FReplyStatus};
	__property AnsiString ReplyData = {read=FReplydata};
	
__published:
	__property ReceiveTimeout ;
	__property Host ;
	__property Port ;
	__property Protocol ;
	__property TOnReplyEvent OnReply = {read=FOnReply, write=FOnReply};
public:
	#pragma option push -w-inl
	/* TIdRawBase.Destroy */ inline __fastcall virtual ~TIdIcmpClient(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
static const Shortint DEF_PACKET_SIZE = 0x20;
static const Word MAX_PACKET_SIZE = 0x400;
static const Shortint ICMP_MIN = 0x8;
static const Byte iDEFAULTPACKETSIZE = 0x80;
static const Word iDEFAULTREPLYBUFSIZE = 0x400;
static const Word Id_TIDICMP_ReceiveTimeout = 0x1388;

}	/* namespace Idicmpclient */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idicmpclient;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIcmpClient
