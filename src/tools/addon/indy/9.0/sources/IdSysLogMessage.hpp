// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSysLogMessage.pas' rev: 5.00

#ifndef IdSysLogMessageHPP
#define IdSysLogMessageHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsyslogmessage
{
//-- type declarations -------------------------------------------------------
typedef Byte TIdSyslogPRI;

#pragma option push -b-
enum TIdSyslogFacility { sfKernel, sfUserLevel, sfMailSystem, sfSystemDaemon, sfSecurityOne, sfSysLogInternal, 
	sfLPR, sfNNTP, sfClockDaemonOne, sfUUCP, sfSecurityTwo, sfFTPDaemon, sfNTP, sfLogAudit, sfLogAlert, 
	sfClockDaemonTwo, sfLocalUseZero, sfLocalUseOne, sfLocalUseTwo, sfLocalUseThree, sfLocalUseFour, sfLocalUseFive, 
	sfLocalUseSix, sfLocalUseSeven };
#pragma option pop

#pragma option push -b-
enum TIdSyslogSeverity { slEmergency, slAlert, slCritical, slError, slWarning, slNotice, slInformational, 
	slDebug };
#pragma option pop

class DELPHICLASS TIdSysLogMsgPart;
class PASCALIMPLEMENTATION TIdSysLogMsgPart : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	bool FPIDAvailable;
	AnsiString FText;
	bool FMsgPIDAvailable;
	void __fastcall SetPIDAvailable(const bool AValue);
	AnsiString __fastcall GetContent();
	AnsiString __fastcall GetProcess();
	void __fastcall SetContent(const AnsiString AValue);
	void __fastcall SetProcess(const AnsiString AValue);
	void __fastcall SetText(const AnsiString AValue);
	int __fastcall GetPID(void);
	void __fastcall SetPID(const int AValue);
	int __fastcall GetMaxTagLength(void);
	virtual AnsiString __fastcall PIDToStr(int APID);
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property AnsiString Text = {read=FText, write=SetText};
	__property bool PIDAvailable = {read=FPIDAvailable, write=SetPIDAvailable, stored=false, nodefault}
		;
	__property AnsiString Process = {read=GetProcess, write=SetProcess, stored=false};
	__property int PID = {read=GetPID, write=SetPID, stored=false, nodefault};
	__property AnsiString Content = {read=GetContent, write=SetContent, stored=false};
public:
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdSysLogMsgPart(void) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdSysLogMsgPart(void) : Classes::TPersistent() { }
	#pragma option pop
	
};


class DELPHICLASS TIdSysLogMessage;
class PASCALIMPLEMENTATION TIdSysLogMessage : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	TIdSysLogMsgPart* FMsg;
	TIdSyslogFacility FFacility;
	TIdSyslogSeverity FSeverity;
	AnsiString FHostname;
	AnsiString FMessage;
	System::TDateTime FTimeStamp;
	AnsiString FRawMessage;
	AnsiString FPeer;
	TIdSyslogPRI FPri;
	Idbasecomponent::TIdBaseComponent* FUDPCliComp;
	void __fastcall SetFacility(const TIdSyslogFacility AValue);
	void __fastcall SetSeverity(const TIdSyslogSeverity AValue);
	void __fastcall SetHostname(const AnsiString AValue);
	void __fastcall SetRawMessage(const AnsiString Value);
	void __fastcall SetTimeStamp(const System::TDateTime AValue);
	void __fastcall SetMsg(const TIdSysLogMsgPart* AValue);
	void __fastcall SetPri(const TIdSyslogPRI Value);
	AnsiString __fastcall GetHeader();
	virtual void __fastcall CheckASCIIRange(AnsiString &Data);
	virtual void __fastcall ReadPRI(int &StartPos);
	virtual void __fastcall ReadHeader(int &StartPos);
	virtual void __fastcall ReadMSG(int &StartPos);
	virtual void __fastcall parse(void);
	virtual void __fastcall UpdatePRI(void);
	virtual System::TDateTime __fastcall DecodeTimeStamp(AnsiString TimeStampString);
	
public:
	__property AnsiString RawMessage = {read=FRawMessage, write=SetRawMessage};
	virtual AnsiString __fastcall EncodeMessage();
	virtual void __fastcall ReadFromStream(Classes::TStream* Src, int Size, AnsiString APeer);
	virtual void __fastcall assign(Classes::TPersistent* Source);
	__property System::TDateTime TimeStamp = {read=FTimeStamp, write=SetTimeStamp};
	__fastcall virtual TIdSysLogMessage(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdSysLogMessage(void);
	void __fastcall SendToHost(const AnsiString Dest);
	__property AnsiString Peer = {read=FPeer, write=FPeer};
	
__published:
	__property TIdSyslogPRI Pri = {read=FPri, write=SetPri, default=13};
	__property TIdSyslogFacility Facility = {read=FFacility, write=SetFacility, stored=false, nodefault
		};
	__property TIdSyslogSeverity Severity = {read=FSeverity, write=SetSeverity, stored=false, nodefault
		};
	__property AnsiString Hostname = {read=FHostname, write=SetHostname, stored=false};
	__property TIdSysLogMsgPart* Msg = {read=FMsg, write=SetMsg};
};


//-- var, const, procedure ---------------------------------------------------
extern PACKAGE Word __fastcall logFacilityToNo(TIdSyslogFacility AFac);
extern PACKAGE TIdSyslogFacility __fastcall NoToFacility(Word AFac);
extern PACKAGE Word __fastcall logSeverityToNo(TIdSyslogSeverity ASev);
extern PACKAGE TIdSyslogSeverity __fastcall NoToSeverity(Word ASev);
extern PACKAGE AnsiString __fastcall SeverityToString(TIdSyslogSeverity ASec);
extern PACKAGE AnsiString __fastcall FacilityToString(TIdSyslogFacility AFac);

}	/* namespace Idsyslogmessage */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsyslogmessage;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSysLogMessage
