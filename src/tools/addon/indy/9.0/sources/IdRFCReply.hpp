// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRFCReply.pas' rev: 5.00

#ifndef IdRFCReplyHPP
#define IdRFCReplyHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idrfcreply
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdRFCReply;
class PASCALIMPLEMENTATION TIdRFCReply : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	int FNumericCode;
	Classes::TStrings* FText;
	AnsiString FTextCode;
	virtual void __fastcall AssignTo(Classes::TPersistent* ADest);
	virtual AnsiString __fastcall GetDisplayName();
	void __fastcall SetNumericCode(const int AValue);
	void __fastcall SetText(const Classes::TStrings* AValue);
	void __fastcall SetTextCode(const AnsiString AValue);
	
public:
	void __fastcall Clear(void);
	__fastcall virtual TIdRFCReply(Classes::TCollection* Collection);
	__fastcall virtual ~TIdRFCReply(void);
	AnsiString __fastcall GenerateReply();
	void __fastcall ParseResponse(const Classes::TStrings* AStrings)/* overload */;
	bool __fastcall ReplyExists(void);
	void __fastcall SetReply(const int ANumericCode, const AnsiString AText);
	
__published:
	__property int NumericCode = {read=FNumericCode, write=SetNumericCode, nodefault};
	__property Classes::TStrings* Text = {read=FText, write=SetText};
	__property AnsiString TextCode = {read=FTextCode, write=SetTextCode};
};


class DELPHICLASS TIdRFCReplies;
class PASCALIMPLEMENTATION TIdRFCReplies : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	HIDESBASE TIdRFCReply* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdRFCReply* Value);
	
public:
	HIDESBASE TIdRFCReply* __fastcall Add(void)/* overload */;
	HIDESBASE TIdRFCReply* __fastcall Add(const int ANumericCode, const AnsiString AText)/* overload */
		;
	__fastcall TIdRFCReplies(Classes::TPersistent* AOwner);
	virtual TIdRFCReply* __fastcall FindByNumber(const int ANo);
	TIdRFCReply* __fastcall UpdateReply(const int ANumericCode, const AnsiString AText);
	void __fastcall UpdateText(TIdRFCReply* AReply);
	__property TIdRFCReply* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdRFCReplies(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idrfcreply */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idrfcreply;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRFCReply
