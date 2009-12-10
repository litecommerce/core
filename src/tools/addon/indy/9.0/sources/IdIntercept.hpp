// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIntercept.pas' rev: 5.00

#ifndef IdInterceptHPP
#define IdInterceptHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdException.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idintercept
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EIdInterceptCircularLink;
class PASCALIMPLEMENTATION EIdInterceptCircularLink : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdInterceptCircularLink(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdInterceptCircularLink(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdInterceptCircularLink(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdInterceptCircularLink(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdInterceptCircularLink(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdInterceptCircularLink(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdInterceptCircularLink(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdInterceptCircularLink(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdInterceptCircularLink(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdConnectionIntercept;
typedef void __fastcall (__closure *TIdInterceptNotifyEvent)(TIdConnectionIntercept* ASender);

typedef void __fastcall (__closure *TIdInterceptStreamEvent)(TIdConnectionIntercept* ASender, Classes::TStream* 
	AStream);

class PASCALIMPLEMENTATION TIdConnectionIntercept : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	Classes::TComponent* FConnection;
	TIdConnectionIntercept* FIntercept;
	bool FIsClient;
	TIdInterceptNotifyEvent FOnConnect;
	TIdInterceptNotifyEvent FOnDisconnect;
	TIdInterceptStreamEvent FOnReceive;
	TIdInterceptStreamEvent FOnSend;
	virtual void __fastcall NestedConnect(Classes::TComponent* AConnection);
	virtual void __fastcall NestedDisconnect(void);
	virtual void __fastcall NestedReceive(Classes::TStream* ABuffer);
	virtual void __fastcall NestedSend(Classes::TStream* ABuffer);
	virtual void __fastcall Notification(Classes::TComponent* AComponent, Classes::TOperation Operation
		);
	void __fastcall SetIntercept(TIdConnectionIntercept* AValue);
	__property TIdConnectionIntercept* Intercept = {read=FIntercept, write=SetIntercept};
	
public:
	virtual void __fastcall Connect(Classes::TComponent* AConnection);
	__fastcall virtual TIdConnectionIntercept(Classes::TComponent* AOwner);
	virtual void __fastcall Disconnect(void);
	virtual void __fastcall Receive(Classes::TStream* ABuffer);
	virtual void __fastcall Send(Classes::TStream* ABuffer);
	__property Classes::TComponent* Connection = {read=FConnection};
	__property bool IsClient = {read=FIsClient, nodefault};
	
__published:
	__property TIdInterceptNotifyEvent OnConnect = {read=FOnConnect, write=FOnConnect};
	__property TIdInterceptNotifyEvent OnDisconnect = {read=FOnDisconnect, write=FOnDisconnect};
	__property TIdInterceptStreamEvent OnReceive = {read=FOnReceive, write=FOnReceive};
	__property TIdInterceptStreamEvent OnSend = {read=FOnSend, write=FOnSend};
public:
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdConnectionIntercept(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdServerIntercept;
class PASCALIMPLEMENTATION TIdServerIntercept : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
public:
	virtual void __fastcall Init(void) = 0 ;
	virtual TIdConnectionIntercept* __fastcall Accept(Classes::TComponent* AConnection) = 0 ;
public:
	#pragma option push -w-inl
	/* TComponent.Create */ inline __fastcall virtual TIdServerIntercept(Classes::TComponent* AOwner) : 
		Idbasecomponent::TIdBaseComponent(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TComponent.Destroy */ inline __fastcall virtual ~TIdServerIntercept(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idintercept */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idintercept;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIntercept
