// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdGopher.pas' rev: 5.00

#ifndef IdGopherHPP
#define IdGopherHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdHeaderList.hpp>	// Pascal unit
#include <IdEMailAddress.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idgopher
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdGopherMenuItem;
class PASCALIMPLEMENTATION TIdGopherMenuItem : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	AnsiString FTitle;
	char FItemType;
	AnsiString FSelector;
	AnsiString FServer;
	int FPort;
	bool FGopherPlusItem;
	Idheaderlist::TIdHeaderList* FGopherBlock;
	Classes::TStringList* FViews;
	AnsiString FURL;
	Classes::TStringList* FAbstract;
	Idheaderlist::TIdHeaderList* FAsk;
	Idemailaddress::TIdEMailAddressItem* fAdminEmail;
	AnsiString __fastcall GetLastModified();
	AnsiString __fastcall GetOrganization();
	AnsiString __fastcall GetLocation();
	AnsiString __fastcall GetGeog();
	
public:
	__fastcall virtual TIdGopherMenuItem(Classes::TCollection* ACollection);
	__fastcall virtual ~TIdGopherMenuItem(void);
	virtual void __fastcall DoneSettingInfoBlock(void);
	__property AnsiString Title = {read=FTitle, write=FTitle};
	__property char ItemType = {read=FItemType, write=FItemType, nodefault};
	__property AnsiString Selector = {read=FSelector, write=FSelector};
	__property AnsiString Server = {read=FServer, write=FServer};
	__property int Port = {read=FPort, write=FPort, nodefault};
	__property bool GopherPlusItem = {read=FGopherPlusItem, write=FGopherPlusItem, nodefault};
	__property Idheaderlist::TIdHeaderList* GopherBlock = {read=FGopherBlock};
	__property AnsiString URL = {read=FURL};
	__property Classes::TStringList* Views = {read=FViews};
	__property Classes::TStringList* AAbstract = {read=FAbstract};
	__property AnsiString LastModified = {read=GetLastModified};
	__property Idemailaddress::TIdEMailAddressItem* AdminEMail = {read=fAdminEmail};
	__property AnsiString Organization = {read=GetOrganization};
	__property AnsiString Location = {read=GetLocation};
	__property AnsiString Geog = {read=GetGeog};
	__property Idheaderlist::TIdHeaderList* Ask = {read=FAsk};
};


class DELPHICLASS TIdGopherMenu;
class PASCALIMPLEMENTATION TIdGopherMenu : public Classes::TCollection 
{
	typedef Classes::TCollection inherited;
	
protected:
	HIDESBASE TIdGopherMenuItem* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdGopherMenuItem* Value);
	
public:
	__fastcall TIdGopherMenu(void);
	HIDESBASE TIdGopherMenuItem* __fastcall Add(void);
	__property TIdGopherMenuItem* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
public:
		
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdGopherMenu(void) { }
	#pragma option pop
	
};


typedef void __fastcall (__closure *TIdGopherMenuEvent)(System::TObject* Sender, TIdGopherMenuItem* 
	MenuItem);

class DELPHICLASS TIdGopher;
class PASCALIMPLEMENTATION TIdGopher : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	TIdGopherMenuEvent FOnMenuItem;
	void __fastcall DoMenu(TIdGopherMenuItem* MenuItem);
	void __fastcall ProcessGopherError(void);
	TIdGopherMenuItem* __fastcall MenuItemFromString(AnsiString stLine, TIdGopherMenu* Menu);
	TIdGopherMenu* __fastcall ProcessDirectory(AnsiString PreviousData, const int ExpectedLength);
	TIdGopherMenu* __fastcall LoadExtendedDirectory(AnsiString PreviousData, const int ExpectedLength);
		
	void __fastcall ProcessFile(Classes::TStream* ADestStream, AnsiString APreviousData, const int ExpectedLength
		);
	void __fastcall ProcessTextFile(Classes::TStream* ADestStream, AnsiString APreviousData, const int 
		ExpectedLength);
	
public:
	__fastcall virtual TIdGopher(Classes::TComponent* AOwner);
	TIdGopherMenu* __fastcall GetMenu(AnsiString ASelector, bool IsGopherPlus, AnsiString AView);
	TIdGopherMenu* __fastcall Search(AnsiString ASelector, AnsiString AQuery);
	void __fastcall GetFile(AnsiString ASelector, Classes::TStream* ADestStream, bool IsGopherPlus, AnsiString 
		AView);
	void __fastcall GetTextFile(AnsiString ASelector, Classes::TStream* ADestStream, bool IsGopherPlus, 
		AnsiString AView);
	TIdGopherMenu* __fastcall GetExtendedMenu(AnsiString ASelector, AnsiString AView);
	
__published:
	__property TIdGopherMenuEvent OnMenuItem = {read=FOnMenuItem, write=FOnMenuItem};
	__property Port ;
public:
	#pragma option push -w-inl
	/* TIdTCPClient.Destroy */ inline __fastcall virtual ~TIdGopher(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idgopher */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idgopher;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdGopher
