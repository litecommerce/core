// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMessage.pas' rev: 5.00

#ifndef IdMessageHPP
#define IdMessageHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <IdCoderHeader.hpp>	// Pascal unit
#include <IdHeaderList.hpp>	// Pascal unit
#include <IdEMailAddress.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmessage
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdMessagePriority { mpHighest, mpHigh, mpNormal, mpLow, mpLowest };
#pragma option pop

typedef void __fastcall (__closure *TOnGetMessagePartStream)(Classes::TStream* AStream);

class DELPHICLASS TIdMIMEBoundary;
class PASCALIMPLEMENTATION TIdMIMEBoundary : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Classes::TStrings* FBoundaryList;
	bool FNewBoundary;
	AnsiString __fastcall GetBoundary();
	
public:
	__fastcall TIdMIMEBoundary(void);
	__fastcall virtual ~TIdMIMEBoundary(void);
	/*         class method */ static AnsiString __fastcall FindBoundary(TMetaClass* vmt, AnsiString AContentType
		);
	void __fastcall Push(AnsiString ABoundary);
	void __fastcall Pop(void);
	void __fastcall Clear(void);
	__property AnsiString Boundary = {read=GetBoundary};
	__property bool NewBoundary = {read=FNewBoundary, write=FNewBoundary, nodefault};
};


#pragma option push -b-
enum TIdMessageFlags { mfAnswered, mfFlagged, mfDeleted, mfDraft, mfSeen, mfRecent };
#pragma option pop

typedef Set<TIdMessageFlags, mfAnswered, mfRecent>  TIdMessageFlagsSet;

class DELPHICLASS TIdMessagePart;
class PASCALIMPLEMENTATION TIdMessagePart : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	AnsiString FBoundary;
	bool FBoundaryBegin;
	bool FBoundaryEnd;
	AnsiString FContentMD5;
	AnsiString FContentTransfer;
	AnsiString FContentType;
	AnsiString FEndBoundary;
	Idheaderlist::TIdHeaderList* FExtraHeaders;
	Idheaderlist::TIdHeaderList* FHeaders;
	bool FIsEncoded;
	TOnGetMessagePartStream FOnGetMessagePartStream;
	AnsiString FStoredPathName;
	AnsiString __fastcall GetContentType();
	AnsiString __fastcall GetContentTransfer();
	void __fastcall SetContentType(const AnsiString Value);
	void __fastcall SetContentTransfer(const AnsiString Value);
	void __fastcall SetExtraHeaders(const Idheaderlist::TIdHeaderList* Value);
	
public:
	__fastcall virtual TIdMessagePart(Classes::TCollection* Collection);
	__fastcall virtual ~TIdMessagePart(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__property AnsiString Boundary = {read=FBoundary, write=FBoundary};
	__property bool BoundaryBegin = {read=FBoundaryBegin, write=FBoundaryBegin, nodefault};
	__property bool BoundaryEnd = {read=FBoundaryEnd, write=FBoundaryEnd, nodefault};
	__property bool IsEncoded = {read=FIsEncoded, nodefault};
	__property TOnGetMessagePartStream OnGetMessagePartStream = {read=FOnGetMessagePartStream, write=FOnGetMessagePartStream
		};
	__property AnsiString StoredPathName = {read=FStoredPathName, write=FStoredPathName};
	__property Idheaderlist::TIdHeaderList* Headers = {read=FHeaders};
	
__published:
	__property AnsiString ContentTransfer = {read=GetContentTransfer, write=SetContentTransfer};
	__property AnsiString ContentType = {read=GetContentType, write=SetContentType};
	__property Idheaderlist::TIdHeaderList* ExtraHeaders = {read=FExtraHeaders, write=SetExtraHeaders};
		
};


typedef TMetaClass*TIdMessagePartClass;

class DELPHICLASS TIdAttachment;
class DELPHICLASS TIdMessageParts;
class PASCALIMPLEMENTATION TIdAttachment : public TIdMessagePart 
{
	typedef TIdMessagePart inherited;
	
protected:
	AnsiString FContentDisposition;
	bool FFileIsTempFile;
	AnsiString FFileName;
	AnsiString __fastcall GetContentDisposition();
	void __fastcall SetContentDisposition(const AnsiString Value);
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__fastcall TIdAttachment(TIdMessageParts* Collection, const AnsiString AFileName);
	__fastcall virtual ~TIdAttachment(void);
	void __fastcall Encode(Classes::TStream* ADest);
	bool __fastcall SaveToFile(const AnsiString FileName);
	__property AnsiString ContentDisposition = {read=GetContentDisposition, write=SetContentDisposition
		};
	__property bool FileIsTempFile = {read=FFileIsTempFile, write=FFileIsTempFile, nodefault};
	__property AnsiString FileName = {read=FFileName, write=FFileName};
};


class DELPHICLASS TIdText;
class PASCALIMPLEMENTATION TIdText : public TIdMessagePart 
{
	typedef TIdMessagePart inherited;
	
protected:
	Classes::TStrings* FBody;
	void __fastcall SetBody(const Classes::TStrings* AStrs);
	
public:
	__fastcall TIdText(TIdMessageParts* Collection, Classes::TStrings* ABody);
	__fastcall virtual ~TIdText(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__property Classes::TStrings* Body = {read=FBody, write=SetBody};
};


class PASCALIMPLEMENTATION TIdMessageParts : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	AnsiString FAttachmentEncoding;
	int FAttachmentCount;
	System::TObject* FMessageEncoderInfo;
	int FRelatedPartCount;
	int FTextPartCount;
	HIDESBASE TIdMessagePart* __fastcall GetItem(int Index);
	void __fastcall SetAttachmentEncoding(const AnsiString AValue);
	HIDESBASE void __fastcall SetItem(int Index, const TIdMessagePart* Value);
	
public:
	HIDESBASE TIdMessagePart* __fastcall Add(void);
	void __fastcall CountParts(void);
	__fastcall TIdMessageParts(Classes::TPersistent* AOwner);
	__property int AttachmentCount = {read=FAttachmentCount, nodefault};
	__property AnsiString AttachmentEncoding = {read=FAttachmentEncoding, write=SetAttachmentEncoding};
		
	__property TIdMessagePart* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
	__property System::TObject* MessageEncoderInfo = {read=FMessageEncoderInfo};
	__property int RelatedPartCount = {read=FRelatedPartCount, nodefault};
	__property int TextPartCount = {read=FTextPartCount, nodefault};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdMessageParts(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdMessageEncoding { meMIME, meUU };
#pragma option pop

typedef void __fastcall (__closure *TIdInitializeIsoEvent)(Idcoderheader::TTransfer &VTransferHeader
	, char &VHeaderEncoding, AnsiString &VCharSet);

class DELPHICLASS TIdMessage;
class PASCALIMPLEMENTATION TIdMessage : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	Idemailaddress::TIdEMailAddressList* FBccList;
	Classes::TStrings* FBody;
	AnsiString FCharSet;
	Idemailaddress::TIdEMailAddressList* FCcList;
	AnsiString FContentType;
	AnsiString FContentTransferEncoding;
	AnsiString FContentDisposition;
	System::TDateTime FDate;
	bool FIsEncoded;
	Idheaderlist::TIdHeaderList* FExtraHeaders;
	TIdMessageEncoding FEncoding;
	TIdMessageFlagsSet FFlags;
	Idemailaddress::TIdEMailAddressItem* FFrom;
	Idheaderlist::TIdHeaderList* FHeaders;
	TIdMessageParts* FMessageParts;
	TIdMIMEBoundary* FMIMEBoundary;
	AnsiString FMsgId;
	Classes::TStrings* FNewsGroups;
	bool FNoEncode;
	bool FNoDecode;
	TIdInitializeIsoEvent FOnInitializeISO;
	AnsiString FOrganization;
	TIdMessagePriority FPriority;
	AnsiString FSubject;
	Idemailaddress::TIdEMailAddressItem* FReceiptRecipient;
	Idemailaddress::TIdEMailAddressList* FRecipients;
	AnsiString FReferences;
	Idemailaddress::TIdEMailAddressList* FReplyTo;
	Idemailaddress::TIdEMailAddressItem* FSender;
	AnsiString FUID;
	AnsiString FXProgram;
	virtual void __fastcall DoInitializeISO(Idcoderheader::TTransfer &VTransferHeader, char &VHeaderEncoding
		, AnsiString &VCharSet);
	AnsiString __fastcall GetAttachmentEncoding();
	void __fastcall SetAttachmentEncoding(const AnsiString AValue);
	void __fastcall SetEncoding(const TIdMessageEncoding AValue);
	
public:
	__fastcall virtual TIdMessage(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdMessage(void);
	void __fastcall AddHeader(const AnsiString Value);
	virtual void __fastcall Clear(void);
	void __fastcall ClearBody(void);
	void __fastcall ClearHeader(void);
	Idheaderlist::TIdHeaderList* __fastcall GenerateHeader(void);
	bool __fastcall GetUseNowForDate(void);
	void __fastcall LoadFromFile(const AnsiString AFileName, const bool AHeadersOnly);
	void __fastcall LoadFromStream(Classes::TStream* AStream, const bool AHeadersOnly);
	void __fastcall ProcessHeaders(void);
	void __fastcall SaveToFile(const AnsiString AFileName, const bool AHeadersOnly);
	void __fastcall SaveToStream(Classes::TStream* AStream, const bool AHeadersOnly);
	void __fastcall SetBody(const Classes::TStrings* Value);
	void __fastcall SetNewsGroups(const Classes::TStrings* Value);
	void __fastcall SetExtraHeaders(const Idheaderlist::TIdHeaderList* Value);
	void __fastcall SetUseNowForDate(const bool Value);
	__property TIdMessageFlagsSet Flags = {read=FFlags, write=FFlags, nodefault};
	__property bool IsEncoded = {read=FIsEncoded, write=FIsEncoded, nodefault};
	__property AnsiString MsgId = {read=FMsgId, write=FMsgId};
	__property Idheaderlist::TIdHeaderList* Headers = {read=FHeaders};
	__property TIdMessageParts* MessageParts = {read=FMessageParts};
	__property TIdMIMEBoundary* MIMEBoundary = {read=FMIMEBoundary, write=FMIMEBoundary};
	__property AnsiString UID = {read=FUID, write=FUID};
	
__published:
	__property AnsiString AttachmentEncoding = {read=GetAttachmentEncoding, write=SetAttachmentEncoding
		};
	__property Classes::TStrings* Body = {read=FBody, write=SetBody};
	__property Idemailaddress::TIdEMailAddressList* BccList = {read=FBccList, write=FBccList};
	__property AnsiString CharSet = {read=FCharSet, write=FCharSet};
	__property Idemailaddress::TIdEMailAddressList* CCList = {read=FCcList, write=FCcList};
	__property AnsiString ContentType = {read=FContentType, write=FContentType};
	__property AnsiString ContentTransferEncoding = {read=FContentTransferEncoding, write=FContentTransferEncoding
		};
	__property AnsiString ContentDisposition = {read=FContentDisposition, write=FContentDisposition};
	__property System::TDateTime Date = {read=FDate, write=FDate};
	__property TIdMessageEncoding Encoding = {read=FEncoding, write=SetEncoding, nodefault};
	__property Idheaderlist::TIdHeaderList* ExtraHeaders = {read=FExtraHeaders, write=SetExtraHeaders};
		
	__property Idemailaddress::TIdEMailAddressItem* From = {read=FFrom, write=FFrom};
	__property Classes::TStrings* NewsGroups = {read=FNewsGroups, write=SetNewsGroups};
	__property bool NoEncode = {read=FNoEncode, write=FNoEncode, default=0};
	__property bool NoDecode = {read=FNoDecode, write=FNoDecode, default=0};
	__property AnsiString Organization = {read=FOrganization, write=FOrganization};
	__property TIdMessagePriority Priority = {read=FPriority, write=FPriority, default=2};
	__property Idemailaddress::TIdEMailAddressItem* ReceiptRecipient = {read=FReceiptRecipient, write=FReceiptRecipient
		};
	__property Idemailaddress::TIdEMailAddressList* Recipients = {read=FRecipients, write=FRecipients};
		
	__property AnsiString References = {read=FReferences, write=FReferences};
	__property Idemailaddress::TIdEMailAddressList* ReplyTo = {read=FReplyTo, write=FReplyTo};
	__property AnsiString Subject = {read=FSubject, write=FSubject};
	__property Idemailaddress::TIdEMailAddressItem* Sender = {read=FSender, write=FSender};
	__property bool UseNowForDate = {read=GetUseNowForDate, write=SetUseNowForDate, default=1};
	__property TIdInitializeIsoEvent OnInitializeISO = {read=FOnInitializeISO, write=FOnInitializeISO};
		
};


typedef void __fastcall (__closure *TIdMessageEvent)(Classes::TComponent* ASender, TIdMessage* &AMsg
	);

typedef void __fastcall (__closure *TIdStringMessageEvent)(Classes::TComponent* ASender, const AnsiString 
	AString, TIdMessage* &AMsg);

class DELPHICLASS EIdMessageException;
class PASCALIMPLEMENTATION EIdMessageException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdMessageException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdMessageException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdMessageException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdMessageException(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdMessageException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdMessageException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdMessageException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdMessageException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdMessageException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdCanNotCreateMessagePart;
class PASCALIMPLEMENTATION EIdCanNotCreateMessagePart : public EIdMessageException 
{
	typedef EIdMessageException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdCanNotCreateMessagePart(const AnsiString Msg) : EIdMessageException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdCanNotCreateMessagePart(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdMessageException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdCanNotCreateMessagePart(int Ident)/* overload */ : EIdMessageException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdCanNotCreateMessagePart(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdMessageException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdCanNotCreateMessagePart(const AnsiString Msg, int AHelpContext
		) : EIdMessageException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdCanNotCreateMessagePart(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdMessageException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdCanNotCreateMessagePart(int Ident, int AHelpContext
		)/* overload */ : EIdMessageException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdCanNotCreateMessagePart(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdMessageException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdCanNotCreateMessagePart(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdTextInvalidCount;
class PASCALIMPLEMENTATION EIdTextInvalidCount : public EIdMessageException 
{
	typedef EIdMessageException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdTextInvalidCount(const AnsiString Msg) : EIdMessageException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdTextInvalidCount(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdMessageException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdTextInvalidCount(int Ident)/* overload */ : EIdMessageException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdTextInvalidCount(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdMessageException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdTextInvalidCount(const AnsiString Msg, int AHelpContext
		) : EIdMessageException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdTextInvalidCount(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdMessageException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdTextInvalidCount(int Ident, int AHelpContext)/* overload */
		 : EIdMessageException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdTextInvalidCount(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdMessageException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdTextInvalidCount(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdMessageCannotLoad;
class PASCALIMPLEMENTATION EIdMessageCannotLoad : public EIdMessageException 
{
	typedef EIdMessageException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdMessageCannotLoad(const AnsiString Msg) : EIdMessageException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdMessageCannotLoad(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdMessageException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdMessageCannotLoad(int Ident)/* overload */ : EIdMessageException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdMessageCannotLoad(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdMessageException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdMessageCannotLoad(const AnsiString Msg, int AHelpContext
		) : EIdMessageException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdMessageCannotLoad(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : EIdMessageException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdMessageCannotLoad(int Ident, int AHelpContext)/* overload */
		 : EIdMessageException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdMessageCannotLoad(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdMessageException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdMessageCannotLoad(void) { }
	#pragma option pop
	
};


typedef AnsiString IdMessage__11[6];

//-- var, const, procedure ---------------------------------------------------
static const bool ID_MSG_NODECODE = false;
static const bool ID_MSG_USENOWFORDATE = true;
#define ID_MSG_PRIORITY (TIdMessagePriority)(2)
#define RSIdMessageCannotLoad "Cannot load message from file %s"
extern PACKAGE AnsiString MessageFlags[6];

}	/* namespace Idmessage */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmessage;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMessage
