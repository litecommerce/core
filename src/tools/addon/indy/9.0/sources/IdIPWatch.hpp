// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIPWatch.pas' rev: 5.00

#ifndef IdIPWatchHPP
#define IdIPWatchHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdThread.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idipwatch
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdIPWatchThread;
class PASCALIMPLEMENTATION TIdIPWatchThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	int FInterval;
	System::TObject* FSender;
	Classes::TNotifyEvent FTimerEvent;
	virtual void __fastcall Run(void);
	void __fastcall TimerEvent(void);
public:
	#pragma option push -w-inl
	/* TIdThread.Create */ inline __fastcall virtual TIdIPWatchThread(bool ACreateSuspended) : Idthread::TIdThread(
		ACreateSuspended) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdThread.Destroy */ inline __fastcall virtual ~TIdIPWatchThread(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdIPWatch;
class PASCALIMPLEMENTATION TIdIPWatch : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	bool FActive;
	AnsiString FCurrentIP;
	bool FHistoryEnabled;
	AnsiString FHistoryFilename;
	Classes::TStringList* FIPHistoryList;
	bool FIsOnline;
	bool FLocalIPHuntBusy;
	int FMaxHistoryEntries;
	int FOnLineCount;
	Classes::TNotifyEvent FOnStatusChanged;
	AnsiString FPreviousIP;
	TIdIPWatchThread* FThread;
	unsigned FWatchInterval;
	void __fastcall AddToIPHistoryList(AnsiString Value);
	void __fastcall CheckStatus(System::TObject* Sender);
	void __fastcall SetActive(bool Value);
	void __fastcall SetMaxHistoryEntries(int Value);
	void __fastcall SetWatchInterval(unsigned Value);
	
public:
	__fastcall virtual TIdIPWatch(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdIPWatch(void);
	bool __fastcall ForceCheck(void);
	void __fastcall LoadHistory(void);
	AnsiString __fastcall LocalIP();
	void __fastcall SaveHistory(void);
	__property AnsiString CurrentIP = {read=FCurrentIP};
	__property Classes::TStringList* IPHistoryList = {read=FIPHistoryList};
	__property bool IsOnline = {read=FIsOnline, nodefault};
	__property AnsiString PreviousIP = {read=FPreviousIP};
	
__published:
	__property bool Active = {read=FActive, write=SetActive, nodefault};
	__property bool HistoryEnabled = {read=FHistoryEnabled, write=FHistoryEnabled, default=1};
	__property AnsiString HistoryFilename = {read=FHistoryFilename, write=FHistoryFilename};
	__property int MaxHistoryEntries = {read=FMaxHistoryEntries, write=SetMaxHistoryEntries, default=25
		};
	__property Classes::TNotifyEvent OnStatusChanged = {read=FOnStatusChanged, write=FOnStatusChanged};
		
	__property unsigned WatchInterval = {read=FWatchInterval, write=SetWatchInterval, default=1000};
};


//-- var, const, procedure ---------------------------------------------------
static const Shortint IP_WATCH_HIST_MAX = 0x19;
#define IP_WATCH_HIST_FILENAME "iphist.dat"
static const Word IP_WATCH_INTERVAL = 0x3e8;

}	/* namespace Idipwatch */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idipwatch;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIPWatch
