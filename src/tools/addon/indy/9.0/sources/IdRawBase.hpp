// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRawBase.pas' rev: 5.00

#ifndef IdRawBaseHPP
#define IdRawBaseHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idrawbase
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdRawBase;
class PASCALIMPLEMENTATION TIdRawBase : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	Idsockethandle::TIdSocketHandle* FBinding;
	Classes::TMemoryStream* FBuffer;
	AnsiString FHost;
	int FPort;
	int FReceiveTimeout;
	int FProtocol;
	int FTTL;
	Idsockethandle::TIdSocketHandle* __fastcall GetBinding(void);
	int __fastcall GetBufferSize(void);
	void __fastcall SetBufferSize(const int AValue);
	void __fastcall SetTTL(const int Value);
	
public:
	__fastcall virtual TIdRawBase(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdRawBase(void);
	__property int TTL = {read=FTTL, write=SetTTL, default=128};
	__property Idsockethandle::TIdSocketHandle* Binding = {read=GetBinding};
	__property int ReceiveTimeout = {read=FReceiveTimeout, write=FReceiveTimeout, default=0};
	int __fastcall ReceiveBuffer(void *ABuffer, const int AByteCount, int ATimeOut);
	void __fastcall Send(AnsiString AData)/* overload */;
	void __fastcall Send(AnsiString AHost, const int APort, AnsiString AData)/* overload */;
	void __fastcall Send(AnsiString AHost, const int APort, void *ABuffer, const int ABufferSize)/* overload */
		;
	
__published:
	__property int BufferSize = {read=GetBufferSize, write=SetBufferSize, default=8192};
	__property AnsiString Host = {read=FHost, write=FHost};
	__property int Port = {read=FPort, write=FPort, default=0};
	__property int Protocol = {read=FProtocol, write=FProtocol, default=255};
};


//-- var, const, procedure ---------------------------------------------------
static const Shortint Id_TIdRawBase_Port = 0x0;
static const Word Id_TIdRawBase_BufferSize = 0x2000;
static const Shortint GReceiveTimeout = 0x0;
static const Byte GFTTL = 0x80;

}	/* namespace Idrawbase */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idrawbase;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRawBase
