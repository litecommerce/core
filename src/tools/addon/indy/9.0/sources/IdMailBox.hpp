// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMailBox.pas' rev: 5.00

#ifndef IdMailBoxHPP
#define IdMailBoxHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <IdMessageCollection.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmailbox
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdMailBoxState { msReadWrite, msReadOnly };
#pragma option pop

#pragma option push -b-
enum TIdMailBoxAttributes { maNoinferiors, maNoselect, maMarked, maUnmarked };
#pragma option pop

typedef Set<TIdMailBoxAttributes, maNoinferiors, maUnmarked>  TIdMailBoxAttributesSet;

typedef DynamicArray<int >  TLongIntArray;

class DELPHICLASS TIdMailBox;
class PASCALIMPLEMENTATION TIdMailBox : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	TIdMailBoxAttributes FAttributes;
	Idmessage::TIdMessageFlagsSet FChangeableFlags;
	int FFirstUnseenMsg;
	Idmessage::TIdMessageFlagsSet FFlags;
	AnsiString FName;
	Idmessagecollection::TIdMessageCollection* FMessageList;
	int FRecentMsgs;
	TIdMailBoxState FState;
	int FTotalMsgs;
	AnsiString FUIDNext;
	AnsiString FUIDValidity;
	int FUnseenMsgs;
	void __fastcall SetMessageList(const Idmessagecollection::TIdMessageCollection* Value);
	
public:
	DynamicArray<int >  DeletedMsgs;
	DynamicArray<int >  SearchResult;
	__property TIdMailBoxAttributes Attributes = {read=FAttributes, write=FAttributes, nodefault};
	__property Idmessage::TIdMessageFlagsSet ChangeableFlags = {read=FChangeableFlags, write=FChangeableFlags
		, nodefault};
	__property int FirstUnseenMsg = {read=FFirstUnseenMsg, write=FFirstUnseenMsg, nodefault};
	__property Idmessage::TIdMessageFlagsSet Flags = {read=FFlags, write=FFlags, nodefault};
	__property AnsiString Name = {read=FName, write=FName};
	__property Idmessagecollection::TIdMessageCollection* MessageList = {read=FMessageList, write=SetMessageList
		};
	__property int RecentMsgs = {read=FRecentMsgs, write=FRecentMsgs, nodefault};
	__property TIdMailBoxState State = {read=FState, write=FState, nodefault};
	__property int TotalMsgs = {read=FTotalMsgs, write=FTotalMsgs, nodefault};
	__property AnsiString UIDNext = {read=FUIDNext, write=FUIDNext};
	__property AnsiString UIDValidity = {read=FUIDValidity, write=FUIDValidity};
	__property int UnseenMsgs = {read=FUnseenMsgs, write=FUnseenMsgs, nodefault};
	virtual void __fastcall Clear(void);
	__fastcall virtual TIdMailBox(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdMailBox(void);
};


typedef AnsiString IdMailBox__2[4];

//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString MailBoxAttributes[4];

}	/* namespace Idmailbox */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmailbox;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMailBox
