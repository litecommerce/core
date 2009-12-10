// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSNMP.pas' rev: 5.00

#ifndef IdSNMPHPP
#define IdSNMPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdASN1Util.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdUDPClient.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsnmp
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TSNMPInfo;
class DELPHICLASS TIdSNMP;
class PASCALIMPLEMENTATION TIdSNMP : public Idudpclient::TIdUDPClient 
{
	typedef Idudpclient::TIdUDPClient inherited;
	
private:
	AnsiString fCommunity;
	int fTrapPort;
	void __fastcall SetCommunity(const AnsiString Value);
	
public:
	TSNMPInfo* Query;
	TSNMPInfo* Reply;
	TSNMPInfo* Trap;
	__fastcall virtual TIdSNMP(Classes::TComponent* aOwner);
	__fastcall virtual ~TIdSNMP(void);
	bool __fastcall SendQuery(void);
	bool __fastcall QuickSend(const AnsiString Mib, const AnsiString Community, const AnsiString Host, 
		AnsiString &Value);
	int __fastcall QuickSendTrap(const AnsiString Dest, const AnsiString Enterprise, const AnsiString Community
		, int Port, int Generic, int Specific, Classes::TStringList* MIBName, Classes::TStringList* MIBValue
		);
	int __fastcall QuickReceiveTrap(AnsiString &Source, AnsiString &Enterprise, AnsiString &Community, 
		int &Port, int &Generic, int &Specific, int &Seconds, Classes::TStringList* &MIBName, Classes::TStringList* 
		&MIBValue);
	int __fastcall SendTrap(void);
	int __fastcall ReceiveTrap(void);
	
__published:
	__property Port ;
	__property int TrapPort = {read=fTrapPort, write=fTrapPort, default=162};
	__property AnsiString Community = {read=fCommunity, write=SetCommunity};
};


class PASCALIMPLEMENTATION TSNMPInfo : public System::TObject 
{
	typedef System::TObject inherited;
	
private:
	TIdSNMP* fOwner;
	AnsiString fCommunity;
	AnsiString __fastcall GetValue(int idx);
	int __fastcall GetValueCount(void);
	int __fastcall GetValueType(int idx);
	AnsiString __fastcall GetValueOID(int idx);
	void __fastcall SetCommunity(const AnsiString Value);
	
protected:
	AnsiString Buffer;
	void __fastcall SyncMIB(void);
	
public:
	AnsiString Host;
	int Port;
	AnsiString Enterprise;
	int GenTrap;
	int SpecTrap;
	int Version;
	int PDUType;
	int TimeTicks;
	int ID;
	int ErrorStatus;
	int ErrorIndex;
	Classes::TStringList* MIBOID;
	Classes::TStringList* MIBValue;
	__fastcall TSNMPInfo(TIdSNMP* AOwner);
	__fastcall virtual ~TSNMPInfo(void);
	int __fastcall EncodeTrap(void);
	int __fastcall DecodeTrap(void);
	void __fastcall DecodeBuf(AnsiString Buffer);
	AnsiString __fastcall EncodeBuf();
	void __fastcall Clear(void);
	void __fastcall MIBAdd(AnsiString MIB, AnsiString Value, int valueType);
	void __fastcall MIBDelete(int Index);
	AnsiString __fastcall MIBGet(AnsiString MIB);
	__property TIdSNMP* Owner = {read=fOwner};
	__property AnsiString Community = {read=fCommunity, write=SetCommunity};
	__property int ValueCount = {read=GetValueCount, nodefault};
	__property AnsiString Value[int idx] = {read=GetValue};
	__property AnsiString ValueOID[int idx] = {read=GetValueOID};
	__property int ValueType[int idx] = {read=GetValueType};
};


//-- var, const, procedure ---------------------------------------------------
static const Byte PDUGetRequest = 0xa0;
static const Byte PDUGetNextRequest = 0xa1;
static const Byte PDUGetResponse = 0xa2;
static const Byte PDUSetRequest = 0xa3;
static const Byte PDUTrap = 0xa4;
static const Shortint ENoError = 0x0;
static const Shortint ETooBig = 0x1;
static const Shortint ENoSuchName = 0x2;
static const Shortint EBadValue = 0x3;
static const Shortint EReadOnly = 0x4;
static const Shortint EGenErr = 0x5;

}	/* namespace Idsnmp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsnmp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSNMP
