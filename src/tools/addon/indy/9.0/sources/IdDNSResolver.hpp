// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdDNSResolver.pas' rev: 5.00

#ifndef IdDNSResolverHPP
#define IdDNSResolverHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdUDPClient.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Iddnsresolver
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TQueryRecordTypes { qtA, qtNS, qtMD, qtMF, qtName, qtSOA, qtMB, qtMG, qtMR, qtNull, qtWKS, qtPTR, 
	qtHINFO, qtMINFO, qtMX, qtTXT, qtSTAR };
#pragma option pop

typedef Set<TQueryRecordTypes, qtA, qtSTAR>  TQueryType;

class DELPHICLASS TResultRecord;
class PASCALIMPLEMENTATION TResultRecord : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
private:
	TQueryRecordTypes FRecType;
	Word FRecClass;
	AnsiString FName;
	unsigned FTTL;
	AnsiString FRData;
	int FRDataLength;
	
public:
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__property TQueryRecordTypes RecType = {read=FRecType, nodefault};
	__property Word RecClass = {read=FRecClass, nodefault};
	__property AnsiString Name = {read=FName};
	__property unsigned TTL = {read=FTTL, nodefault};
	__property int RDataLength = {read=FRDataLength, nodefault};
	__property AnsiString RData = {read=FRData};
	__fastcall virtual ~TResultRecord(void);
public:
	#pragma option push -w-inl
	/* TCollectionItem.Create */ inline __fastcall virtual TResultRecord(Classes::TCollection* Collection
		) : Classes::TCollectionItem(Collection) { }
	#pragma option pop
	
};


class DELPHICLASS TRDATARecord;
class PASCALIMPLEMENTATION TRDATARecord : public TResultRecord 
{
	typedef TResultRecord inherited;
	
private:
	AnsiString FIPAddress;
	
public:
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__fastcall virtual TRDATARecord(Classes::TCollection* Collection);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__property AnsiString IPAddress = {read=FIPAddress};
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TRDATARecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TARecord;
class PASCALIMPLEMENTATION TARecord : public TRDATARecord 
{
	typedef TRDATARecord inherited;
	
public:
	#pragma option push -w-inl
	/* TRDATARecord.Create */ inline __fastcall virtual TARecord(Classes::TCollection* Collection) : TRDATARecord(
		Collection) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TARecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TWKSRecord;
class PASCALIMPLEMENTATION TWKSRecord : public TResultRecord 
{
	typedef TResultRecord inherited;
	
private:
	int FByteCount;
	AnsiString FAddress;
	Word FProtocol;
	Byte *FData;
	Byte __fastcall GetABit(int index);
	
public:
	__fastcall virtual TWKSRecord(Classes::TCollection* Collection);
	__fastcall virtual ~TWKSRecord(void);
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__property AnsiString Address = {read=FAddress};
	__property Word Protocol = {read=FProtocol, nodefault};
	__property Byte BitMap[int index] = {read=GetABit};
	__property int ByteCount = {read=FByteCount, nodefault};
};


class DELPHICLASS TMXRecord;
class PASCALIMPLEMENTATION TMXRecord : public TResultRecord 
{
	typedef TResultRecord inherited;
	
private:
	AnsiString FExchangeServer;
	Word FPreference;
	
public:
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__fastcall virtual TMXRecord(Classes::TCollection* Collection);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__property AnsiString ExchangeServer = {read=FExchangeServer};
	__property Word Preference = {read=FPreference, nodefault};
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TMXRecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TTextRecord;
class PASCALIMPLEMENTATION TTextRecord : public TResultRecord 
{
	typedef TResultRecord inherited;
	
private:
	Classes::TStrings* FText;
	
public:
	__fastcall virtual TTextRecord(Classes::TCollection* Collection);
	__fastcall virtual ~TTextRecord(void);
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__property Classes::TStrings* Text = {read=FText};
};


class DELPHICLASS THINFORecord;
class PASCALIMPLEMENTATION THINFORecord : public TTextRecord 
{
	typedef TTextRecord inherited;
	
private:
	AnsiString FCPU;
	AnsiString FOS;
	
public:
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__property AnsiString CPU = {read=FCPU};
	__property AnsiString OS = {read=FOS};
public:
	#pragma option push -w-inl
	/* TTextRecord.Create */ inline __fastcall virtual THINFORecord(Classes::TCollection* Collection) : 
		TTextRecord(Collection) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TTextRecord.Destroy */ inline __fastcall virtual ~THINFORecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TMINFORecord;
class PASCALIMPLEMENTATION TMINFORecord : public TResultRecord 
{
	typedef TResultRecord inherited;
	
private:
	AnsiString FResponsiblePerson;
	AnsiString FErrorMailbox;
	
public:
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__property AnsiString ResponsiblePersonMailbox = {read=FResponsiblePerson};
	__property AnsiString ErrorMailbox = {read=FErrorMailbox};
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TMINFORecord(void) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TCollectionItem.Create */ inline __fastcall virtual TMINFORecord(Classes::TCollection* Collection
		) : TResultRecord(Collection) { }
	#pragma option pop
	
};


class DELPHICLASS TSOARecord;
class PASCALIMPLEMENTATION TSOARecord : public TResultRecord 
{
	typedef TResultRecord inherited;
	
private:
	unsigned FSerial;
	unsigned FMinimumTTL;
	unsigned FRefresh;
	unsigned FRetry;
	AnsiString FMNAME;
	AnsiString FRNAME;
	unsigned FExpire;
	
public:
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__property AnsiString Primary = {read=FMNAME};
	__property AnsiString ResponsiblePerson = {read=FRNAME};
	__property unsigned Serial = {read=FSerial, nodefault};
	__property unsigned Refresh = {read=FRefresh, nodefault};
	__property unsigned Retry = {read=FRetry, nodefault};
	__property unsigned Expire = {read=FExpire, nodefault};
	__property unsigned MinimumTTL = {read=FMinimumTTL, nodefault};
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TSOARecord(void) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TCollectionItem.Create */ inline __fastcall virtual TSOARecord(Classes::TCollection* Collection)
		 : TResultRecord(Collection) { }
	#pragma option pop
	
};


class DELPHICLASS TNAMERecord;
class PASCALIMPLEMENTATION TNAMERecord : public TResultRecord 
{
	typedef TResultRecord inherited;
	
private:
	AnsiString FHostName;
	
public:
	virtual void __fastcall Parse(AnsiString CompleteMessage, int APos);
	__fastcall virtual TNAMERecord(Classes::TCollection* Collection);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__property AnsiString HostName = {read=FHostName};
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TNAMERecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TNSRecord;
class PASCALIMPLEMENTATION TNSRecord : public TNAMERecord 
{
	typedef TNAMERecord inherited;
	
public:
	#pragma option push -w-inl
	/* TNAMERecord.Create */ inline __fastcall virtual TNSRecord(Classes::TCollection* Collection) : TNAMERecord(
		Collection) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TNSRecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TCNRecord;
class PASCALIMPLEMENTATION TCNRecord : public TNAMERecord 
{
	typedef TNAMERecord inherited;
	
public:
	#pragma option push -w-inl
	/* TNAMERecord.Create */ inline __fastcall virtual TCNRecord(Classes::TCollection* Collection) : TNAMERecord(
		Collection) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TCNRecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TQueryResult;
class PASCALIMPLEMENTATION TQueryResult : public Classes::TCollection 
{
	typedef Classes::TCollection inherited;
	
private:
	TResultRecord* FRec;
	AnsiString FDomainName;
	Word FQueryClass;
	Word FQueryType;
	Classes::TStringList* FQueryPointerList;
	AnsiString __fastcall DNSStrToDomain(AnsiString SrcStr, int &Idx);
	AnsiString __fastcall NextDNSLabel(AnsiString DNSStr, int &APos);
	HIDESBASE void __fastcall SetItem(int Index, TResultRecord* Value);
	HIDESBASE TResultRecord* __fastcall GetItem(int Index);
	
protected:
	DYNAMIC Classes::TPersistent* __fastcall GetOwner(void);
	
public:
	__fastcall TQueryResult(TResultRecord* AResultRecord);
	__fastcall virtual ~TQueryResult(void);
	HIDESBASE TResultRecord* __fastcall Add(AnsiString Answer, int &APos);
	HIDESBASE void __fastcall Clear(void);
	__property Word QueryClass = {read=FQueryClass, nodefault};
	__property Word QueryType = {read=FQueryType, nodefault};
	__property AnsiString DomainName = {read=FDomainName};
	__property TResultRecord* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
};


class DELPHICLASS TPTRRecord;
class PASCALIMPLEMENTATION TPTRRecord : public TNAMERecord 
{
	typedef TNAMERecord inherited;
	
public:
	#pragma option push -w-inl
	/* TNAMERecord.Create */ inline __fastcall virtual TPTRRecord(Classes::TCollection* Collection) : TNAMERecord(
		Collection) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TResultRecord.Destroy */ inline __fastcall virtual ~TPTRRecord(void) { }
	#pragma option pop
	
};


class DELPHICLASS TDNSHeader;
class PASCALIMPLEMENTATION TDNSHeader : public System::TObject 
{
	typedef System::TObject inherited;
	
private:
	Word FID;
	Word FBitCode;
	Word FQDCount;
	Word FANCount;
	Word FNSCount;
	Word FARCount;
	Word __fastcall GetAA(void);
	Word __fastcall GetOpCode(void);
	Word __fastcall GetQr(void);
	Word __fastcall GetRA(void);
	Word __fastcall GetRCode(void);
	Word __fastcall GetRD(void);
	Word __fastcall GetTC(void);
	void __fastcall SetAA(const Word Value);
	void __fastcall SetOpCode(const Word Value);
	void __fastcall SetQr(const Word Value);
	void __fastcall SetRA(const Word Value);
	void __fastcall SetRCode(const Word Value);
	void __fastcall SetRD(const Word Value);
	void __fastcall SetTC(const Word Value);
	
public:
	__fastcall TDNSHeader(void);
	void __fastcall ClearByteCode(void);
	__property Word ID = {read=FID, write=FID, nodefault};
	__property Word Qr = {read=GetQr, write=SetQr, nodefault};
	__property Word OpCode = {read=GetOpCode, write=SetOpCode, nodefault};
	__property Word AA = {read=GetAA, write=SetAA, nodefault};
	__property Word TC = {read=GetTC, write=SetTC, nodefault};
	__property Word RD = {read=GetRD, write=SetRD, nodefault};
	__property Word RA = {read=GetRA, write=SetRA, nodefault};
	__property Word RCode = {read=GetRCode, write=SetRCode, nodefault};
	__property Word BitCode = {read=FBitCode, nodefault};
	__property Word QDCount = {read=FQDCount, write=FQDCount, nodefault};
	__property Word ANCount = {read=FANCount, write=FANCount, nodefault};
	__property Word NSCount = {read=FNSCount, write=FNSCount, nodefault};
	__property Word ARCount = {read=FARCount, write=FARCount, nodefault};
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TDNSHeader(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdDNSResolver;
class PASCALIMPLEMENTATION TIdDNSResolver : public Idudpclient::TIdUDPClient 
{
	typedef Idudpclient::TIdUDPClient inherited;
	
private:
	TDNSHeader* FDNSHeader;
	TQueryResult* FQueryResult;
	AnsiString FInternalQuery;
	int FQuestionLength;
	bool FAllowRecursiveQueries;
	void __fastcall SetAllowRecursiveQueries(const bool Value);
	
protected:
	TQueryType FQueryRecords;
	void __fastcall ParseAnswers(AnsiString Answer, unsigned AnswerNum);
	void __fastcall CreateQuery(AnsiString ADomain);
	void __fastcall FillResult(AnsiString AResult);
	
public:
	__fastcall virtual TIdDNSResolver(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdDNSResolver(void);
	void __fastcall Resolve(AnsiString ADomain);
	__property TQueryResult* QueryResult = {read=FQueryResult};
	
__published:
	__property TQueryType QueryRecords = {read=FQueryRecords, write=FQueryRecords, nodefault};
	__property bool AllowRecursiveQueries = {read=FAllowRecursiveQueries, write=SetAllowRecursiveQueries
		, default=1};
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE Word QueryRecordValues[17];
extern PACKAGE TQueryRecordTypes QueryRecordTypes[17];

}	/* namespace Iddnsresolver */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Iddnsresolver;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdDNSResolver
