// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdThreadComponent.pas' rev: 5.00

#ifndef IdThreadComponentHPP
#define IdThreadComponentHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idthreadcomponent
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdCustomThreadComponent;
typedef void __fastcall (__closure *TIdExceptionThreadComponentEvent)(TIdCustomThreadComponent* Sender
	, Sysutils::Exception* AException);

typedef void __fastcall (__closure *TIdNotifyThreadComponentEvent)(TIdCustomThreadComponent* Sender)
	;

class PASCALIMPLEMENTATION TIdCustomThreadComponent : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	bool FActive;
	Classes::TThreadPriority FPriority;
	Idthread::TIdThreadStopMode FStopMode;
	Idthread::TIdThread* FThread;
	TIdNotifyThreadComponentEvent FOnAfterExecute;
	TIdNotifyThreadComponentEvent FOnAfterRun;
	TIdNotifyThreadComponentEvent FOnBeforeExecute;
	TIdNotifyThreadComponentEvent FOnBeforeRun;
	TIdNotifyThreadComponentEvent FOnCleanup;
	TIdExceptionThreadComponentEvent FOnException;
	TIdNotifyThreadComponentEvent FOnRun;
	TIdNotifyThreadComponentEvent FOnStopped;
	TIdNotifyThreadComponentEvent FOnTerminate;
	bool __fastcall GetActive(void);
	System::TObject* __fastcall GetData(void);
	unsigned __fastcall GetHandle(void);
	Classes::TThreadPriority __fastcall GetPriority(void);
	int __fastcall GetReturnValue(void);
	Idthread::TIdThreadStopMode __fastcall GetStopMode(void);
	bool __fastcall GetStopped(void);
	bool __fastcall GetSuspended(void);
	AnsiString __fastcall GetTerminatingException();
	TMetaClass* __fastcall GetTerminatingExceptionClass(void);
	bool __fastcall GetTerminated(void);
	virtual void __fastcall Loaded(void);
	virtual void __fastcall SetActive(const bool AValue);
	void __fastcall SetData(const System::TObject* AValue);
	void __fastcall SetOnTerminate(const TIdNotifyThreadComponentEvent AValue);
	void __fastcall SetPriority(const Classes::TThreadPriority AValue);
	void __fastcall SetReturnValue(const int AValue);
	void __fastcall SetStopMode(const Idthread::TIdThreadStopMode AValue);
	virtual void __fastcall DoAfterExecute(void);
	virtual void __fastcall DoAfterRun(void);
	virtual void __fastcall DoBeforeExecute(void);
	virtual void __fastcall DoBeforeRun(void);
	virtual void __fastcall DoCleanup(void);
	virtual void __fastcall DoException(Idthread::TIdThread* AThread, Sysutils::Exception* AException);
		
	virtual void __fastcall DoRun(void);
	virtual void __fastcall DoStopped(Idthread::TIdThread* AThread);
	virtual void __fastcall DoTerminate(System::TObject* Sender);
	__property bool Active = {read=GetActive, write=SetActive, default=0};
	__property Classes::TThreadPriority Priority = {read=GetPriority, write=SetPriority, nodefault};
	__property Idthread::TIdThreadStopMode StopMode = {read=GetStopMode, write=SetStopMode, nodefault};
		
	__property TIdNotifyThreadComponentEvent OnAfterExecute = {read=FOnAfterExecute, write=FOnAfterExecute
		};
	__property TIdNotifyThreadComponentEvent OnAfterRun = {read=FOnAfterRun, write=FOnAfterRun};
	__property TIdNotifyThreadComponentEvent OnBeforeExecute = {read=FOnBeforeExecute, write=FOnBeforeExecute
		};
	__property TIdNotifyThreadComponentEvent OnBeforeRun = {read=FOnBeforeRun, write=FOnBeforeRun};
	__property TIdNotifyThreadComponentEvent OnCleanup = {read=FOnCleanup, write=FOnCleanup};
	__property TIdExceptionThreadComponentEvent OnException = {read=FOnException, write=FOnException};
	__property TIdNotifyThreadComponentEvent OnRun = {read=FOnRun, write=FOnRun};
	__property TIdNotifyThreadComponentEvent OnStopped = {read=FOnStopped, write=FOnStopped};
	__property TIdNotifyThreadComponentEvent OnTerminate = {read=FOnTerminate, write=SetOnTerminate};
	
public:
	__fastcall virtual TIdCustomThreadComponent(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdCustomThreadComponent(void);
	virtual void __fastcall Start(void);
	virtual void __fastcall Stop(void);
	void __fastcall Synchronize(Classes::TThreadMethod AMethod)/* overload */;
	void __fastcall Synchronize(const Sysutils::TMethod &AMethod)/* overload */;
	virtual void __fastcall Terminate(void);
	virtual void __fastcall TerminateAndWaitFor(void);
	unsigned __fastcall WaitFor(void);
	__property System::TObject* Data = {read=GetData, write=SetData};
	__property unsigned Handle = {read=GetHandle, nodefault};
	__property int ReturnValue = {read=GetReturnValue, write=SetReturnValue, nodefault};
	__property bool Stopped = {read=GetStopped, nodefault};
	__property bool Suspended = {read=GetSuspended, nodefault};
	__property Idthread::TIdThread* Thread = {read=FThread};
	__property AnsiString TerminatingException = {read=GetTerminatingException};
	__property TMetaClass* TerminatingExceptionClass = {read=GetTerminatingExceptionClass};
	__property bool Terminated = {read=GetTerminated, nodefault};
};


class DELPHICLASS TIdThreadComponent;
class PASCALIMPLEMENTATION TIdThreadComponent : public TIdCustomThreadComponent 
{
	typedef TIdCustomThreadComponent inherited;
	
__published:
	__property Active ;
	__property Priority ;
	__property StopMode ;
	__property OnAfterExecute ;
	__property OnAfterRun ;
	__property OnBeforeExecute ;
	__property OnBeforeRun ;
	__property OnCleanup ;
	__property OnException ;
	__property OnRun ;
	__property OnStopped ;
	__property OnTerminate ;
public:
	#pragma option push -w-inl
	/* TIdCustomThreadComponent.Create */ inline __fastcall virtual TIdThreadComponent(Classes::TComponent* 
		AOwner) : TIdCustomThreadComponent(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdCustomThreadComponent.Destroy */ inline __fastcall virtual ~TIdThreadComponent(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdThreadEx;
class PASCALIMPLEMENTATION TIdThreadEx : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	TIdCustomThreadComponent* FThreadComponent;
	virtual void __fastcall AfterRun(void);
	virtual void __fastcall AfterExecute(void);
	virtual void __fastcall BeforeExecute(void);
	virtual void __fastcall BeforeRun(void);
	virtual void __fastcall Cleanup(void);
	virtual void __fastcall Run(void);
	
public:
	__fastcall TIdThreadEx(TIdCustomThreadComponent* AThreadComponent);
public:
	#pragma option push -w-inl
	/* TIdThread.Destroy */ inline __fastcall virtual ~TIdThreadEx(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
#define IdThreadComponentDefaultPriority (Classes::TThreadPriority)(3)
#define IdThreadComponentDefaultStopMode (Idthread::TIdThreadStopMode)(0)

}	/* namespace Idthreadcomponent */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idthreadcomponent;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdThreadComponent
