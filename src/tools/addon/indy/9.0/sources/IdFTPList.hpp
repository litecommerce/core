// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdFTPList.pas' rev: 5.00

#ifndef IdFTPListHPP
#define IdFTPListHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idftplist
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EIdInvalidFTPListingFormat;
class PASCALIMPLEMENTATION EIdInvalidFTPListingFormat : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdInvalidFTPListingFormat(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdInvalidFTPListingFormat(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdInvalidFTPListingFormat(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdInvalidFTPListingFormat(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdInvalidFTPListingFormat(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdInvalidFTPListingFormat(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdInvalidFTPListingFormat(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdInvalidFTPListingFormat(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		Idexception::EIdException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdInvalidFTPListingFormat(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdFTPListFormat { flfNone, flfDos, flfUnix, flfVax, flfNoDetails, flfUnknown, flfCustom };
#pragma option pop

#pragma option push -b-
enum TIdDirItemType { ditDirectory, ditFile, ditSymbolicLink };
#pragma option pop

class DELPHICLASS TIdFTPListItem;
class PASCALIMPLEMENTATION TIdFTPListItem : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	__int64 FSize;
	int FItemCount;
	AnsiString FData;
	AnsiString FFileName;
	AnsiString FGroupPermissions;
	AnsiString FGroupName;
	AnsiString FOwnerPermissions;
	AnsiString FOwnerName;
	AnsiString FUserPermissions;
	System::TDateTime FModifiedDate;
	AnsiString FLinkedItemName;
	TIdDirItemType FItemType;
	AnsiString __fastcall DoGetCustomListFormat();
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__fastcall virtual TIdFTPListItem(Classes::TCollection* AOwner);
	AnsiString __fastcall Text();
	__property AnsiString Data = {read=FData, write=FData};
	__property AnsiString OwnerPermissions = {read=FOwnerPermissions, write=FOwnerPermissions};
	__property AnsiString GroupPermissions = {read=FGroupPermissions, write=FGroupPermissions};
	__property AnsiString UserPermissions = {read=FUserPermissions, write=FUserPermissions};
	__property int ItemCount = {read=FItemCount, write=FItemCount, nodefault};
	__property AnsiString OwnerName = {read=FOwnerName, write=FOwnerName};
	__property AnsiString GroupName = {read=FGroupName, write=FGroupName};
	__property __int64 Size = {read=FSize, write=FSize};
	__property System::TDateTime ModifiedDate = {read=FModifiedDate, write=FModifiedDate};
	__property AnsiString FileName = {read=FFileName, write=FFileName};
	__property TIdDirItemType ItemType = {read=FItemType, write=FItemType, nodefault};
	__property AnsiString LinkedItemName = {read=FLinkedItemName, write=FLinkedItemName};
public:
	#pragma option push -w-inl
	/* TCollectionItem.Destroy */ inline __fastcall virtual ~TIdFTPListItem(void) { }
	#pragma option pop
	
};


typedef void __fastcall (__closure *TIdOnGetCustomListFormat)(TIdFTPListItem* AItem, AnsiString &VText
	);

typedef void __fastcall (__closure *TIdOnParseCustomListFormat)(TIdFTPListItem* AItem);

class DELPHICLASS TIdFTPListItems;
class PASCALIMPLEMENTATION TIdFTPListItems : public Classes::TCollection 
{
	typedef Classes::TCollection inherited;
	
protected:
	AnsiString FDirectoryName;
	void __fastcall SetDirectoryName(const AnsiString AValue);
	TIdOnGetCustomListFormat FOnGetCustomListFormat;
	TIdOnParseCustomListFormat FOnParseCustomListFormat;
	TIdFTPListFormat FListFormat;
	TIdFTPListItem* __fastcall GetItems(int AIndex);
	void __fastcall ParseDOS(TIdFTPListItem* AItem);
	void __fastcall ParseUnix(TIdFTPListItem* AItem);
	void __fastcall ParseVax(TIdFTPListItem* AItem);
	void __fastcall SetItems(int AIndex, const TIdFTPListItem* Value);
	
public:
	HIDESBASE TIdFTPListItem* __fastcall Add(void);
	virtual TIdFTPListFormat __fastcall CheckListFormat(AnsiString Data, const bool ADetails);
	__fastcall TIdFTPListItems(void)/* overload */;
	int __fastcall IndexOf(TIdFTPListItem* AItem);
	void __fastcall LoadList(Classes::TStrings* AData);
	void __fastcall Parse(TIdFTPListFormat ListFormat, TIdFTPListItem* AItem);
	void __fastcall ParseUnknown(TIdFTPListItem* AItem);
	virtual void __fastcall ParseCustom(TIdFTPListItem* AItem);
	__property AnsiString DirectoryName = {read=FDirectoryName, write=SetDirectoryName};
	__property TIdFTPListItem* Items[int AIndex] = {read=GetItems, write=SetItems/*, default*/};
	__property TIdFTPListFormat ListFormat = {read=FListFormat, write=FListFormat, nodefault};
	__property TIdOnGetCustomListFormat OnGetCustomListFormat = {read=FOnGetCustomListFormat, write=FOnGetCustomListFormat
		};
	__property TIdOnParseCustomListFormat OnParseCustomListFormat = {read=FOnParseCustomListFormat, write=
		FOnParseCustomListFormat};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdFTPListItems(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idftplist */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idftplist;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdFTPList
