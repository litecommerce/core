// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMessageCoder.pas' rev: 5.00

#ifndef IdMessageCoderHPP
#define IdMessageCoderHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdMessage.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmessagecoder
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdMessageCoderPartType { mcptUnknown, mcptText, mcptAttachment };
#pragma option pop

class DELPHICLASS TIdMessageDecoder;
class PASCALIMPLEMENTATION TIdMessageDecoder : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	AnsiString FFilename;
	Classes::TStrings* FHeaders;
	TIdMessageCoderPartType FPartType;
	Classes::TStream* FSourceStream;
	
public:
	virtual TIdMessageDecoder* __fastcall ReadBody(Classes::TStream* ADestStream, bool &AMsgEnd) = 0 ;
	virtual void __fastcall ReadHeader(void);
	AnsiString __fastcall ReadLn();
	__fastcall virtual TIdMessageDecoder(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdMessageDecoder(void);
	__property AnsiString Filename = {read=FFilename};
	__property Classes::TStream* SourceStream = {read=FSourceStream, write=FSourceStream};
	__property Classes::TStrings* Headers = {read=FHeaders};
	__property TIdMessageCoderPartType PartType = {read=FPartType, nodefault};
};


class DELPHICLASS TIdMessageDecoderInfo;
class PASCALIMPLEMENTATION TIdMessageDecoderInfo : public System::TObject 
{
	typedef System::TObject inherited;
	
public:
	virtual TIdMessageDecoder* __fastcall CheckForStart(Idmessage::TIdMessage* ASender, AnsiString ALine
		) = 0 ;
	__fastcall virtual TIdMessageDecoderInfo(void);
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdMessageDecoderInfo(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMessageDecoderList;
class PASCALIMPLEMENTATION TIdMessageDecoderList : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Classes::TStringList* FMessageCoders;
	
public:
	/*         class method */ static TIdMessageDecoderInfo* __fastcall ByName(TMetaClass* vmt, const AnsiString 
		AName);
	/*         class method */ static TIdMessageDecoder* __fastcall CheckForStart(TMetaClass* vmt, Idmessage::TIdMessage* 
		ASender, const AnsiString ALine);
	__fastcall TIdMessageDecoderList(void);
	__fastcall virtual ~TIdMessageDecoderList(void);
	/*         class method */ static void __fastcall RegisterDecoder(TMetaClass* vmt, const AnsiString 
		AMessageCoderName, TIdMessageDecoderInfo* AMessageCoderInfo);
};


class DELPHICLASS TIdMessageEncoder;
class PASCALIMPLEMENTATION TIdMessageEncoder : public Idcomponent::TIdComponent 
{
	typedef Idcomponent::TIdComponent inherited;
	
protected:
	AnsiString FFilename;
	int FPermissionCode;
	
public:
	__fastcall virtual TIdMessageEncoder(Classes::TComponent* AOwner);
	void __fastcall Encode(const AnsiString AFilename, Classes::TStream* ADest)/* overload */;
	virtual void __fastcall Encode(Classes::TStream* ASrc, Classes::TStream* ADest) = 0 /* overload */;
		
	
__published:
	__property AnsiString Filename = {read=FFilename, write=FFilename};
	__property int PermissionCode = {read=FPermissionCode, write=FPermissionCode, nodefault};
public:
	#pragma option push -w-inl
	/* TIdComponent.Destroy */ inline __fastcall virtual ~TIdMessageEncoder(void) { }
	#pragma option pop
	
};


typedef TMetaClass*TIdMessageEncoderClass;

class DELPHICLASS TIdMessageEncoderInfo;
class PASCALIMPLEMENTATION TIdMessageEncoderInfo : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	TMetaClass*FMessageEncoderClass;
	
public:
	__fastcall virtual TIdMessageEncoderInfo(void);
	virtual void __fastcall InitializeHeaders(Idmessage::TIdMessage* AMsg);
	__property TMetaClass* MessageEncoderClass = {read=FMessageEncoderClass};
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdMessageEncoderInfo(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMessageEncoderList;
class PASCALIMPLEMENTATION TIdMessageEncoderList : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Classes::TStringList* FMessageCoders;
	
public:
	/*         class method */ static TIdMessageEncoderInfo* __fastcall ByName(TMetaClass* vmt, const AnsiString 
		AName);
	__fastcall TIdMessageEncoderList(void);
	__fastcall virtual ~TIdMessageEncoderList(void);
	/*         class method */ static void __fastcall RegisterEncoder(TMetaClass* vmt, const AnsiString 
		AMessageEncoderName, TIdMessageEncoderInfo* AMessageEncoderInfo);
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idmessagecoder */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmessagecoder;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMessageCoder
