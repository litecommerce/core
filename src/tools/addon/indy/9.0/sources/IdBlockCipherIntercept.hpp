// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdBlockCipherIntercept.pas' rev: 5.00

#ifndef IdBlockCipherInterceptHPP
#define IdBlockCipherInterceptHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdException.hpp>	// Pascal unit
#include <IdIntercept.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idblockcipherintercept
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdBlockCipherIntercept;
typedef void __fastcall (__closure *TIdBlockCipherInterceptDataEvent)(TIdBlockCipherIntercept* ASender
	, void * ASrcData, void * ADstData);

class PASCALIMPLEMENTATION TIdBlockCipherIntercept : public Idintercept::TIdConnectionIntercept 
{
	typedef Idintercept::TIdConnectionIntercept inherited;
	
protected:
	int FBlockSize;
	System::TObject* FData;
	Classes::TMemoryStream* FRecvStream;
	Classes::TMemoryStream* FSendStream;
	virtual void __fastcall Decrypt(const void *ASrcData, void *ADstData);
	virtual void __fastcall Encrypt(const void *ASrcData, void *ADstData);
	TIdBlockCipherInterceptDataEvent __fastcall GetOnReceive();
	TIdBlockCipherInterceptDataEvent __fastcall GetOnSend();
	void __fastcall SetOnReceive(const TIdBlockCipherInterceptDataEvent Value);
	void __fastcall SetOnSend(const TIdBlockCipherInterceptDataEvent Value);
	void __fastcall SetBlockSize(const int Value);
	
public:
	__fastcall virtual TIdBlockCipherIntercept(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdBlockCipherIntercept(void);
	virtual void __fastcall Receive(Classes::TStream* ABuffer);
	virtual void __fastcall Send(Classes::TStream* ABuffer);
	void __fastcall CopySettingsFrom(TIdBlockCipherIntercept* ASrcBlockCipherIntercept);
	__property System::TObject* Data = {read=FData, write=FData};
	
__published:
	__property int BlockSize = {read=FBlockSize, write=SetBlockSize, default=16};
	__property TIdBlockCipherInterceptDataEvent OnReceive = {read=GetOnReceive, write=SetOnReceive};
	__property TIdBlockCipherInterceptDataEvent OnSend = {read=GetOnSend, write=SetOnSend};
};


typedef EIdException EIdBlockCipherInterceptException;
;

//-- var, const, procedure ---------------------------------------------------
static const Shortint IdBlockCipherBlockSizeDefault = 0x10;
static const Word IdBlockCipherBlockSizeMax = 0x100;

}	/* namespace Idblockcipherintercept */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idblockcipherintercept;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdBlockCipherIntercept
