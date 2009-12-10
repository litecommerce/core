// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMessageCoderUUE.pas' rev: 5.00

#ifndef IdMessageCoderUUEHPP
#define IdMessageCoderUUEHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdMessageCoder.hpp>	// Pascal unit
#include <IdCoder3to4.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmessagecoderuue
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdMessageDecoderUUE;
class PASCALIMPLEMENTATION TIdMessageDecoderUUE : public Idmessagecoder::TIdMessageDecoder 
{
	typedef Idmessagecoder::TIdMessageDecoder inherited;
	
public:
	virtual Idmessagecoder::TIdMessageDecoder* __fastcall ReadBody(Classes::TStream* ADestStream, bool 
		&AMsgEnd);
public:
	#pragma option push -w-inl
	/* TIdMessageDecoder.Create */ inline __fastcall virtual TIdMessageDecoderUUE(Classes::TComponent* 
		AOwner) : Idmessagecoder::TIdMessageDecoder(AOwner) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TIdMessageDecoder.Destroy */ inline __fastcall virtual ~TIdMessageDecoderUUE(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMessageDecoderInfoUUE;
class PASCALIMPLEMENTATION TIdMessageDecoderInfoUUE : public Idmessagecoder::TIdMessageDecoderInfo 
{
	typedef Idmessagecoder::TIdMessageDecoderInfo inherited;
	
public:
	virtual Idmessagecoder::TIdMessageDecoder* __fastcall CheckForStart(Idmessage::TIdMessage* ASender, 
		AnsiString ALine);
public:
	#pragma option push -w-inl
	/* TIdMessageDecoderInfo.Create */ inline __fastcall virtual TIdMessageDecoderInfoUUE(void) : Idmessagecoder::TIdMessageDecoderInfo(
		) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdMessageDecoderInfoUUE(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMessageEncoderUUEBase;
class PASCALIMPLEMENTATION TIdMessageEncoderUUEBase : public Idmessagecoder::TIdMessageEncoder 
{
	typedef Idmessagecoder::TIdMessageEncoder inherited;
	
protected:
	TMetaClass*FEncoderClass;
	
public:
	virtual void __fastcall Encode(Classes::TStream* ASrc, Classes::TStream* ADest)/* overload */;
public:
		
	#pragma option push -w-inl
	/* TIdMessageEncoder.Create */ inline __fastcall virtual TIdMessageEncoderUUEBase(Classes::TComponent* 
		AOwner) : Idmessagecoder::TIdMessageEncoder(AOwner) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TIdComponent.Destroy */ inline __fastcall virtual ~TIdMessageEncoderUUEBase(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMessageEncoderUUE;
class PASCALIMPLEMENTATION TIdMessageEncoderUUE : public TIdMessageEncoderUUEBase 
{
	typedef TIdMessageEncoderUUEBase inherited;
	
public:
	__fastcall virtual TIdMessageEncoderUUE(Classes::TComponent* AOwner);
public:
	#pragma option push -w-inl
	/* TIdComponent.Destroy */ inline __fastcall virtual ~TIdMessageEncoderUUE(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMessageEncoderInfoUUE;
class PASCALIMPLEMENTATION TIdMessageEncoderInfoUUE : public Idmessagecoder::TIdMessageEncoderInfo 
{
	typedef Idmessagecoder::TIdMessageEncoderInfo inherited;
	
public:
	__fastcall virtual TIdMessageEncoderInfoUUE(void);
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdMessageEncoderInfoUUE(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idmessagecoderuue */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmessagecoderuue;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMessageCoderUUE
