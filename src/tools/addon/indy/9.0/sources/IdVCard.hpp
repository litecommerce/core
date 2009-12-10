// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdVCard.pas' rev: 5.00

#ifndef IdVCardHPP
#define IdVCardHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdGlobal.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idvcard
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdVCardEmbeddedObject;
class PASCALIMPLEMENTATION TIdVCardEmbeddedObject : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FObjectType;
	AnsiString FObjectURL;
	bool FBase64Encoded;
	Classes::TStrings* FEmbeddedData;
	void __fastcall SetEmbeddedData(const Classes::TStrings* Value);
	
public:
	__fastcall TIdVCardEmbeddedObject(void);
	__fastcall virtual ~TIdVCardEmbeddedObject(void);
	
__published:
	__property AnsiString ObjectType = {read=FObjectType, write=FObjectType};
	__property AnsiString ObjectURL = {read=FObjectURL, write=FObjectURL};
	__property bool Base64Encoded = {read=FBase64Encoded, write=FBase64Encoded, nodefault};
	__property Classes::TStrings* EmbeddedData = {read=FEmbeddedData, write=SetEmbeddedData};
};


class DELPHICLASS TIdVCardBusinessInfo;
class PASCALIMPLEMENTATION TIdVCardBusinessInfo : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FTitle;
	AnsiString FRole;
	AnsiString FOrganization;
	Classes::TStrings* FDivisions;
	void __fastcall SetDivisions(Classes::TStrings* Value);
	
public:
	__fastcall TIdVCardBusinessInfo(void);
	__fastcall virtual ~TIdVCardBusinessInfo(void);
	
__published:
	__property AnsiString Organization = {read=FOrganization, write=FOrganization};
	__property Classes::TStrings* Divisions = {read=FDivisions, write=SetDivisions};
	__property AnsiString Title = {read=FTitle, write=FTitle};
	__property AnsiString Role = {read=FRole, write=FRole};
};


class DELPHICLASS TIdVCardGeog;
class PASCALIMPLEMENTATION TIdVCardGeog : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	double FLatitude;
	double FLongitude;
	AnsiString FTimeZoneStr;
	
__published:
	__property double Latitude = {read=FLatitude, write=FLatitude};
	__property double Longitude = {read=FLongitude, write=FLongitude};
	__property AnsiString TimeZoneStr = {read=FTimeZoneStr, write=FTimeZoneStr};
public:
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdVCardGeog(void) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdVCardGeog(void) : Classes::TPersistent() { }
	#pragma option pop
	
};


#pragma option push -b-
enum IdVCard__4 { tpaHome, tpaVoiceMessaging, tpaWork, tpaPreferred, tpaVoice, tpaFax, tpaCellular, 
	tpaVideo, tpaBBS, tpaModem, tpaCar, tpaISDN, tpaPCS, tpaPager };
#pragma option pop

typedef Set<IdVCard__4, tpaHome, tpaPager>  TIdPhoneAttributes;

class DELPHICLASS TIdCardPhoneNumber;
class PASCALIMPLEMENTATION TIdCardPhoneNumber : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	TIdPhoneAttributes FPhoneAttributes;
	AnsiString FNumber;
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property TIdPhoneAttributes PhoneAttributes = {read=FPhoneAttributes, write=FPhoneAttributes, nodefault
		};
	__property AnsiString Number = {read=FNumber, write=FNumber};
public:
	#pragma option push -w-inl
	/* TCollectionItem.Create */ inline __fastcall virtual TIdCardPhoneNumber(Classes::TCollection* Collection
		) : Classes::TCollectionItem(Collection) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TCollectionItem.Destroy */ inline __fastcall virtual ~TIdCardPhoneNumber(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdVCardTelephones;
class PASCALIMPLEMENTATION TIdVCardTelephones : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	HIDESBASE TIdCardPhoneNumber* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdCardPhoneNumber* Value);
	
public:
	__fastcall TIdVCardTelephones(Classes::TPersistent* AOwner);
	HIDESBASE TIdCardPhoneNumber* __fastcall Add(void);
	__property TIdCardPhoneNumber* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
public:
		
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdVCardTelephones(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum IdVCard__7 { tatHome, tatDomestic, tatInternational, tatPostal, tatParcel, tatWork, tatPreferred 
	};
#pragma option pop

typedef Set<IdVCard__7, tatHome, tatPreferred>  TIdCardAddressAttributes;

class DELPHICLASS TIdCardAddressItem;
class PASCALIMPLEMENTATION TIdCardAddressItem : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	TIdCardAddressAttributes FAddressAttributes;
	AnsiString FPOBox;
	AnsiString FExtendedAddress;
	AnsiString FStreetAddress;
	AnsiString FLocality;
	AnsiString FRegion;
	AnsiString FPostalCode;
	AnsiString FNation;
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property TIdCardAddressAttributes AddressAttributes = {read=FAddressAttributes, write=FAddressAttributes
		, nodefault};
	__property AnsiString POBox = {read=FPOBox, write=FPOBox};
	__property AnsiString ExtendedAddress = {read=FExtendedAddress, write=FExtendedAddress};
	__property AnsiString StreetAddress = {read=FStreetAddress, write=FStreetAddress};
	__property AnsiString Locality = {read=FLocality, write=FLocality};
	__property AnsiString Region = {read=FRegion, write=FRegion};
	__property AnsiString PostalCode = {read=FPostalCode, write=FPostalCode};
	__property AnsiString Nation = {read=FNation, write=FNation};
public:
	#pragma option push -w-inl
	/* TCollectionItem.Create */ inline __fastcall virtual TIdCardAddressItem(Classes::TCollection* Collection
		) : Classes::TCollectionItem(Collection) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TCollectionItem.Destroy */ inline __fastcall virtual ~TIdCardAddressItem(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdVCardAddresses;
class PASCALIMPLEMENTATION TIdVCardAddresses : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	HIDESBASE TIdCardAddressItem* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdCardAddressItem* Value);
	
public:
	__fastcall TIdVCardAddresses(Classes::TPersistent* AOwner);
	HIDESBASE TIdCardAddressItem* __fastcall Add(void);
	__property TIdCardAddressItem* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
public:
		
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdVCardAddresses(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdVCardMailingLabelItem;
class PASCALIMPLEMENTATION TIdVCardMailingLabelItem : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
private:
	TIdCardAddressAttributes FAddressAttributes;
	Classes::TStrings* FMailingLabel;
	void __fastcall SetMailingLabel(Classes::TStrings* Value);
	
public:
	__fastcall virtual TIdVCardMailingLabelItem(Classes::TCollection* Collection);
	__fastcall virtual ~TIdVCardMailingLabelItem(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property TIdCardAddressAttributes AddressAttributes = {read=FAddressAttributes, write=FAddressAttributes
		, nodefault};
	__property Classes::TStrings* MailingLabel = {read=FMailingLabel, write=SetMailingLabel};
};


class DELPHICLASS TIdVCardMailingLabels;
class PASCALIMPLEMENTATION TIdVCardMailingLabels : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	HIDESBASE TIdVCardMailingLabelItem* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdVCardMailingLabelItem* Value);
	
public:
	__fastcall TIdVCardMailingLabels(Classes::TPersistent* AOwner);
	HIDESBASE TIdVCardMailingLabelItem* __fastcall Add(void);
	__property TIdVCardMailingLabelItem* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
		
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdVCardMailingLabels(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdVCardEMailType { ematAOL, ematAppleLink, ematATT, ematCIS, emateWorld, ematInternet, ematIBMMail, 
	ematMCIMail, ematPowerShare, ematProdigy, ematTelex, ematX400 };
#pragma option pop

class DELPHICLASS TIdVCardEMailItem;
class PASCALIMPLEMENTATION TIdVCardEMailItem : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	TIdVCardEMailType FEMailType;
	bool FPreferred;
	AnsiString FAddress;
	
public:
	__fastcall virtual TIdVCardEMailItem(Classes::TCollection* Collection);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property TIdVCardEMailType EMailType = {read=FEMailType, write=FEMailType, nodefault};
	__property bool Preferred = {read=FPreferred, write=FPreferred, nodefault};
	__property AnsiString Address = {read=FAddress, write=FAddress};
public:
	#pragma option push -w-inl
	/* TCollectionItem.Destroy */ inline __fastcall virtual ~TIdVCardEMailItem(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdVCardEMailAddresses;
class PASCALIMPLEMENTATION TIdVCardEMailAddresses : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	HIDESBASE TIdVCardEMailItem* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdVCardEMailItem* Value);
	
public:
	__fastcall TIdVCardEMailAddresses(Classes::TPersistent* AOwner);
	HIDESBASE TIdVCardEMailItem* __fastcall Add(void);
	__property TIdVCardEMailItem* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
public:
		
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdVCardEMailAddresses(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdVCardName;
class PASCALIMPLEMENTATION TIdVCardName : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FFirstName;
	AnsiString FSurName;
	Classes::TStrings* FOtherNames;
	AnsiString FPrefix;
	AnsiString FSuffix;
	AnsiString FFormattedName;
	AnsiString FSortName;
	Classes::TStrings* FNickNames;
	void __fastcall SetOtherNames(Classes::TStrings* Value);
	void __fastcall SetNickNames(Classes::TStrings* Value);
	
public:
	__fastcall TIdVCardName(void);
	__fastcall virtual ~TIdVCardName(void);
	
__published:
	__property AnsiString FirstName = {read=FFirstName, write=FFirstName};
	__property AnsiString SurName = {read=FSurName, write=FSurName};
	__property Classes::TStrings* OtherNames = {read=FOtherNames, write=SetOtherNames};
	__property AnsiString FormattedName = {read=FFormattedName, write=FFormattedName};
	__property AnsiString Prefix = {read=FPrefix, write=FPrefix};
	__property AnsiString Suffix = {read=FSuffix, write=FSuffix};
	__property AnsiString SortName = {read=FSortName, write=FSortName};
	__property Classes::TStrings* NickNames = {read=FNickNames, write=SetNickNames};
};


class DELPHICLASS TIdVCard;
class PASCALIMPLEMENTATION TIdVCard : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	Classes::TStrings* FComments;
	Classes::TStrings* FCategories;
	TIdVCardBusinessInfo* FBusinessInfo;
	TIdVCardGeog* FGeography;
	TIdVCardName* FFullName;
	Classes::TStrings* FRawForm;
	Classes::TStrings* FURLs;
	AnsiString FEMailProgram;
	TIdVCardEMailAddresses* FEMailAddresses;
	TIdVCardAddresses* FAddresses;
	TIdVCardMailingLabels* FMailingLabels;
	TIdVCardTelephones* FTelephones;
	double FVCardVersion;
	AnsiString FProductID;
	AnsiString FUniqueID;
	AnsiString FClassification;
	System::TDateTime FLastRevised;
	System::TDateTime FBirthDay;
	TIdVCardEmbeddedObject* FPhoto;
	TIdVCardEmbeddedObject* FLogo;
	TIdVCardEmbeddedObject* FSound;
	TIdVCardEmbeddedObject* FKey;
	void __fastcall SetComments(Classes::TStrings* Value);
	void __fastcall SetCategories(Classes::TStrings* Value);
	void __fastcall SetURLs(Classes::TStrings* Value);
	void __fastcall SetVariablesAfterRead(void);
	
public:
	__fastcall virtual TIdVCard(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdVCard(void);
	void __fastcall ReadFromTStrings(Classes::TStrings* s);
	__property Classes::TStrings* RawForm = {read=FRawForm};
	
__published:
	__property double VCardVersion = {read=FVCardVersion};
	__property Classes::TStrings* URLs = {read=FURLs, write=SetURLs};
	__property AnsiString ProductID = {read=FProductID, write=FProductID};
	__property AnsiString UniqueID = {read=FUniqueID, write=FUniqueID};
	__property AnsiString Classification = {read=FClassification, write=FClassification};
	__property System::TDateTime BirthDay = {read=FBirthDay, write=FBirthDay};
	__property TIdVCardName* FullName = {read=FFullName, write=FFullName};
	__property AnsiString EMailProgram = {read=FEMailProgram, write=FEMailProgram};
	__property TIdVCardEMailAddresses* EMailAddresses = {read=FEMailAddresses};
	__property TIdVCardTelephones* Telephones = {read=FTelephones};
	__property TIdVCardBusinessInfo* BusinessInfo = {read=FBusinessInfo};
	__property Classes::TStrings* Categories = {read=FCategories, write=SetCategories};
	__property TIdVCardAddresses* Addresses = {read=FAddresses};
	__property TIdVCardMailingLabels* MailingLabels = {read=FMailingLabels};
	__property Classes::TStrings* Comments = {read=FComments, write=SetComments};
	__property TIdVCardEmbeddedObject* Photo = {read=FPhoto};
	__property TIdVCardEmbeddedObject* Logo = {read=FLogo};
	__property TIdVCardEmbeddedObject* Sound = {read=FSound};
	__property TIdVCardEmbeddedObject* Key = {read=FKey};
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idvcard */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idvcard;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdVCard
