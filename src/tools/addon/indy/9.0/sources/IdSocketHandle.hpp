// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSocketHandle.pas' rev: 5.00

#ifndef IdSocketHandleHPP
#define IdSocketHandleHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsockethandle
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdSocketHandles;
class DELPHICLASS TIdSocketHandle;
class PASCALIMPLEMENTATION TIdSocketHandles : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	int FDefaultPort;
	HIDESBASE TIdSocketHandle* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdSocketHandle* Value);
	
public:
	__fastcall TIdSocketHandles(Classes::TComponent* AOwner);
	HIDESBASE TIdSocketHandle* __fastcall Add(void);
	TIdSocketHandle* __fastcall BindingByHandle(const int AHandle);
	__property TIdSocketHandle* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
	__property int DefaultPort = {read=FDefaultPort, write=FDefaultPort, nodefault};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdSocketHandles(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdSocketHandle : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	int FHandle;
	bool FHandleAllocated;
	AnsiString FIP;
	AnsiString FPeerIP;
	int FPort;
	int FPeerPort;
	int FClientPortMin;
	int FClientPortMax;
	bool __fastcall TryBind(void);
	bool __fastcall BindPortReserved(void);
	
public:
	bool __fastcall Accept(int ASocket);
	void __fastcall AllocateSocket(const int ASocketType, const int AProtocol);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	void __fastcall Bind(void);
	virtual void __fastcall CloseSocket(const bool AResetLocal);
	virtual int __fastcall Connect(const int AFamily);
	__fastcall virtual TIdSocketHandle(Classes::TCollection* ACollection);
	__fastcall virtual ~TIdSocketHandle(void);
	void __fastcall GetSockOpt(int level, int optname, char * optval, int optlen);
	void __fastcall Listen(const int anQueueCount);
	bool __fastcall Readable(int AMSec);
	int __fastcall Recv(void *ABuf, int ALen, int AFlags);
	virtual int __fastcall RecvFrom(void *ABuffer, const int ALength, const int AFlags, AnsiString &VIP
		, int &VPort);
	void __fastcall Reset(const bool AResetLocal);
	int __fastcall Send(void *Buf, int len, int flags);
	void __fastcall SendTo(const AnsiString AIP, const int APort, void *ABuffer, const int ABufferSize)
		;
	void __fastcall SetPeer(const AnsiString asIP, int anPort);
	void __fastcall SetSockOpt(int level, int optname, char * optval, int optlen);
	bool __fastcall Select(int ASocket, int ATimeOut);
	void __fastcall UpdateBindingLocal(void);
	void __fastcall UpdateBindingPeer(void);
	__property bool HandleAllocated = {read=FHandleAllocated, nodefault};
	__property int Handle = {read=FHandle, nodefault};
	__property AnsiString PeerIP = {read=FPeerIP};
	__property int PeerPort = {read=FPeerPort, nodefault};
	
__published:
	__property int ClientPortMin = {read=FClientPortMin, write=FClientPortMin, default=0};
	__property int ClientPortMax = {read=FClientPortMax, write=FClientPortMax, default=0};
	__property AnsiString IP = {read=FIP, write=FIP};
	__property int Port = {read=FPort, write=FPort, nodefault};
};


class DELPHICLASS EIdSocketHandleError;
class PASCALIMPLEMENTATION EIdSocketHandleError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdSocketHandleError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdSocketHandleError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdSocketHandleError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdSocketHandleError(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdSocketHandleError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdSocketHandleError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdSocketHandleError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdSocketHandleError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdSocketHandleError(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdPackageSizeTooBig;
class PASCALIMPLEMENTATION EIdPackageSizeTooBig : public EIdSocketHandleError 
{
	typedef EIdSocketHandleError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdPackageSizeTooBig(const AnsiString Msg) : EIdSocketHandleError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdPackageSizeTooBig(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdSocketHandleError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdPackageSizeTooBig(int Ident)/* overload */ : EIdSocketHandleError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdPackageSizeTooBig(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdSocketHandleError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdPackageSizeTooBig(const AnsiString Msg, int AHelpContext
		) : EIdSocketHandleError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdPackageSizeTooBig(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdSocketHandleError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdPackageSizeTooBig(int Ident, int AHelpContext)/* overload */
		 : EIdSocketHandleError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdPackageSizeTooBig(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdSocketHandleError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdPackageSizeTooBig(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNotAllBytesSent;
class PASCALIMPLEMENTATION EIdNotAllBytesSent : public EIdSocketHandleError 
{
	typedef EIdSocketHandleError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNotAllBytesSent(const AnsiString Msg) : EIdSocketHandleError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNotAllBytesSent(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdSocketHandleError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNotAllBytesSent(int Ident)/* overload */ : EIdSocketHandleError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNotAllBytesSent(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdSocketHandleError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNotAllBytesSent(const AnsiString Msg, int AHelpContext
		) : EIdSocketHandleError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNotAllBytesSent(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdSocketHandleError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNotAllBytesSent(int Ident, int AHelpContext)/* overload */
		 : EIdSocketHandleError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNotAllBytesSent(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdSocketHandleError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNotAllBytesSent(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdCouldNotBindSocket;
class PASCALIMPLEMENTATION EIdCouldNotBindSocket : public EIdSocketHandleError 
{
	typedef EIdSocketHandleError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdCouldNotBindSocket(const AnsiString Msg) : EIdSocketHandleError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdCouldNotBindSocket(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdSocketHandleError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdCouldNotBindSocket(int Ident)/* overload */ : EIdSocketHandleError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdCouldNotBindSocket(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdSocketHandleError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdCouldNotBindSocket(const AnsiString Msg, int AHelpContext
		) : EIdSocketHandleError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdCouldNotBindSocket(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdSocketHandleError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdCouldNotBindSocket(int Ident, int AHelpContext)/* overload */
		 : EIdSocketHandleError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdCouldNotBindSocket(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdSocketHandleError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdCouldNotBindSocket(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdCanNotBindPortInRange;
class PASCALIMPLEMENTATION EIdCanNotBindPortInRange : public EIdSocketHandleError 
{
	typedef EIdSocketHandleError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdCanNotBindPortInRange(const AnsiString Msg) : EIdSocketHandleError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdCanNotBindPortInRange(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdSocketHandleError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdCanNotBindPortInRange(int Ident)/* overload */ : EIdSocketHandleError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdCanNotBindPortInRange(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdSocketHandleError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdCanNotBindPortInRange(const AnsiString Msg, int AHelpContext
		) : EIdSocketHandleError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdCanNotBindPortInRange(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdSocketHandleError(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdCanNotBindPortInRange(int Ident, int AHelpContext
		)/* overload */ : EIdSocketHandleError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdCanNotBindPortInRange(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdSocketHandleError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdCanNotBindPortInRange(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdInvalidPortRange;
class PASCALIMPLEMENTATION EIdInvalidPortRange : public EIdSocketHandleError 
{
	typedef EIdSocketHandleError inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdInvalidPortRange(const AnsiString Msg) : EIdSocketHandleError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdInvalidPortRange(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdSocketHandleError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdInvalidPortRange(int Ident)/* overload */ : EIdSocketHandleError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdInvalidPortRange(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdSocketHandleError(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdInvalidPortRange(const AnsiString Msg, int AHelpContext
		) : EIdSocketHandleError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdInvalidPortRange(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdSocketHandleError(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdInvalidPortRange(int Ident, int AHelpContext)/* overload */
		 : EIdSocketHandleError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdInvalidPortRange(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdSocketHandleError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdInvalidPortRange(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idsockethandle */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsockethandle;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSocketHandle
