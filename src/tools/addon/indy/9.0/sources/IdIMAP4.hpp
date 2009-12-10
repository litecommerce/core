// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIMAP4.pas' rev: 5.00

#ifndef IdIMAP4HPP
#define IdIMAP4HPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdMessageCollection.hpp>	// Pascal unit
#include <IdCoderQuotedPrintable.hpp>	// Pascal unit
#include <IdCoderMIME.hpp>	// Pascal unit
#include <IdMessageCoder.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdMessageClient.hpp>	// Pascal unit
#include <IdTCPStream.hpp>	// Pascal unit
#include <IdMailBox.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idimap4
{
//-- type declarations -------------------------------------------------------
typedef AnsiString IdIMAP4__1[6];

typedef AnsiString IdIMAP4__2[6];

class DELPHICLASS TIdImapMessagePart;
class PASCALIMPLEMENTATION TIdImapMessagePart : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	AnsiString FBodyType;
	AnsiString FBodySubType;
	AnsiString FFileName;
	AnsiString FDescription;
	AnsiString FEncoding;
	int FSize;
	
public:
	__property AnsiString BodyType = {read=FBodyType, write=FBodyType};
	__property AnsiString BodySubType = {read=FBodySubType, write=FBodySubType};
	__property AnsiString FileName = {read=FFileName, write=FFileName};
	__property AnsiString Description = {read=FDescription, write=FDescription};
	__property AnsiString Encoding = {read=FEncoding, write=FEncoding};
	__property int Size = {read=FSize, write=FSize, nodefault};
public:
	#pragma option push -w-inl
	/* TCollectionItem.Create */ inline __fastcall virtual TIdImapMessagePart(Classes::TCollection* Collection
		) : Classes::TCollectionItem(Collection) { }
	#pragma option pop
	#pragma option push -w-inl
	/* TCollectionItem.Destroy */ inline __fastcall virtual ~TIdImapMessagePart(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNumberInvalid;
class PASCALIMPLEMENTATION EIdNumberInvalid : public Sysutils::Exception 
{
	typedef Sysutils::Exception inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNumberInvalid(const AnsiString Msg) : Sysutils::Exception(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNumberInvalid(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Sysutils::Exception(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNumberInvalid(int Ident)/* overload */ : Sysutils::Exception(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNumberInvalid(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : Sysutils::Exception(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNumberInvalid(const AnsiString Msg, int AHelpContext
		) : Sysutils::Exception(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNumberInvalid(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Sysutils::Exception(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNumberInvalid(int Ident, int AHelpContext)/* overload */
		 : Sysutils::Exception(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNumberInvalid(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Sysutils::Exception(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNumberInvalid(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdImapMessageParts;
class PASCALIMPLEMENTATION TIdImapMessageParts : public Classes::TOwnedCollection 
{
	typedef Classes::TOwnedCollection inherited;
	
protected:
	HIDESBASE TIdImapMessagePart* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdImapMessagePart* Value);
	
public:
	HIDESBASE TIdImapMessagePart* __fastcall Add(void);
	__property TIdImapMessagePart* Items[int Index] = {read=GetItem, write=SetItem/*, default*/};
public:
		
	#pragma option push -w-inl
	/* TOwnedCollection.Create */ inline __fastcall TIdImapMessageParts(Classes::TPersistent* AOwner, TMetaClass* 
		ItemClass) : Classes::TOwnedCollection(AOwner, ItemClass) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdImapMessageParts(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdIMAP4Commands { cmdCAPABILITY, cmdNOOP, cmdLOGOUT, cmdAUTHENTICATE, cmdLOGIN, cmdSELECT, cmdEXAMINE, 
	cmdCREATE, cmdDELETE, cmdRENAME, cmdSUBSCRIBE, cmdUNSUBSCRIBE, cmdLIST, cmdLSUB, cmdSTATUS, cmdAPPEND, 
	cmdCHECK, cmdCLOSE, cmdEXPUNGE, cmdSEARCH, cmdFETCH, cmdSTORE, cmdCOPY, cmdUID, cmdXCmd };
#pragma option pop

#pragma option push -b-
enum TIdIMAP4ConnectionState { csAny, csNonAuthenticated, csAuthenticated, csSelected, csUnexpectedlyDisconnected 
	};
#pragma option pop

#pragma option push -b-
enum TIdIMAP4SearchKey { skAll, skAnswered, skBcc, skBefore, skBody, skCc, skDeleted, skDraft, skFlagged, 
	skFrom, skHeader, skKeyword, skLarger, skNew, skNot, skOld, skOn, skOr, skRecent, skSeen, skSentBefore, 
	skSentOn, skSentSince, skSince, skSmaller, skSubject, skText, skTo, skUID, skUnanswered, skUndeleted, 
	skUndraft, skUnflagged, skUnKeyWord, skUnseen };
#pragma option pop

typedef DynamicArray<TIdIMAP4SearchKey >  TIdIMAP4SearchKeyArray;

struct TIdIMAP4SearchRec
{
	System::TDateTime Date;
	int Size;
	AnsiString Text;
	TIdIMAP4SearchKey SearchKey;
} ;

typedef DynamicArray<TIdIMAP4SearchRec >  TIdIMAP4SearchRecArray;

#pragma option push -b-
enum TIdIMAP4StatusDataItem { mdMessages, mdRecent, mdUIDNext, mdUIDValidity, mdUnseen };
#pragma option pop

#pragma option push -b-
enum TIdIMAP4StoreDataItem { sdReplace, sdReplaceSilent, sdAdd, sdAddSilent, sdRemove, sdRemoveSilent 
	};
#pragma option pop

#pragma option push -b-
enum TIdRetrieveOnSelect { rsDisabled, rsHeaders, rsMessages };
#pragma option pop

typedef void __fastcall (__closure *TIdAlertEvent)(System::TObject* ASender, const AnsiString AAlertMsg
	);

class DELPHICLASS TIdIMAP4;
class PASCALIMPLEMENTATION TIdIMAP4 : public Idmessageclient::TIdMessageClient 
{
	typedef Idmessageclient::TIdMessageClient inherited;
	
private:
	void __fastcall SetMailBox(const Idmailbox::TIdMailBox* Value);
	
protected:
	int FCmdCounter;
	TIdIMAP4ConnectionState FConnectionState;
	Idmailbox::TIdMailBox* FMailBox;
	char FMailBoxSeparator;
	TIdAlertEvent FOnAlert;
	TIdRetrieveOnSelect FRetrieveOnSelect;
	void __fastcall TaggedReplyConvertToConst(void);
	AnsiString __fastcall GetCmdCounter();
	AnsiString __fastcall GetConnectionStateName();
	AnsiString __fastcall GetNewCmdCounter();
	__property AnsiString LastCmdCounter = {read=GetCmdCounter};
	__property AnsiString NewCmdCounter = {read=GetNewCmdCounter};
	AnsiString __fastcall ArrayToNumberStr(const int * AMsgNumList, const int AMsgNumList_Size);
	AnsiString __fastcall MessageFlagSetToStr(const Idmessage::TIdMessageFlagsSet AFlags);
	AnsiString __fastcall DateToIMAPDateStr(const System::TDateTime ADate);
	void __fastcall StripCRLFs(AnsiString &AText);
	void __fastcall ParseBodyStructureResult(AnsiString ABodyStructure, Idmessage::TIdMessageParts* ATheParts
		, TIdImapMessageParts* AImapParts);
	void __fastcall ParseBodyStructurePart(AnsiString APartString, Idmessage::TIdMessagePart* AThePart, 
		TIdImapMessagePart* AImapPart);
	void __fastcall BreakApartParamsInQuotes(const AnsiString AParam, Classes::TStringList* &AParsedList
		);
	AnsiString __fastcall GetNextQuotedParam(AnsiString AParam);
	void __fastcall ParseExpungeResult(Idmailbox::TIdMailBox* AMB, Classes::TStrings* CmdResultDetails)
		;
	void __fastcall ParseListResult(Classes::TStringList* AMBList, Classes::TStrings* CmdResultDetails)
		;
	void __fastcall ParseLSubResult(Classes::TStringList* AMBList, Classes::TStrings* CmdResultDetails)
		;
	void __fastcall ParseMailBoxAttributeString(AnsiString AAttributesList, Idmailbox::TIdMailBoxAttributesSet 
		&AAttributes);
	void __fastcall ParseMessageFlagString(AnsiString AFlagsList, Idmessage::TIdMessageFlagsSet &AFlags
		);
	void __fastcall ParseSelectResult(Idmailbox::TIdMailBox* AMB, Classes::TStrings* CmdResultDetails);
		
	void __fastcall ParseStatusResult(Idmailbox::TIdMailBox* AMB, Classes::TStrings* CmdResultDetails);
		
	void __fastcall ParseSearchResult(Idmailbox::TIdMailBox* AMB, Classes::TStrings* CmdResultDetails);
		
	void __fastcall ParseEnvelopeResult(Idmessage::TIdMessage* AMsg, AnsiString ACmdResultStr);
	void __fastcall ParseResponse(const Classes::TStrings* AStrings)/* overload */;
	void __fastcall ParseResponse(const AnsiString ATag, const Classes::TStrings* AStrings)/* overload */
		;
	void __fastcall ParseLineResponse(const AnsiString ATag, const Classes::TStrings* AStrings);
	bool __fastcall InternalRetrievePart(const int AMsgNum, const int APartNum, bool AUseUID, bool AUsePeek
		, char * &ABuffer, int &ABufferLength, AnsiString ADestFileNameAndPath, AnsiString AEncoding);
	AnsiString __fastcall ParseBodyStructureSectionAsEquates(AnsiString AParam);
	AnsiString __fastcall ParseBodyStructureSectionAsEquates2(AnsiString AParam);
	bool __fastcall InternalRetrieveText(const int AMsgNum, AnsiString &AText, bool AUseUID, bool AUsePeek
		, bool AUseFirstPartInsteadOfText);
	bool __fastcall IsCapabilityListed(AnsiString ACapability);
	bool __fastcall InternalRetrieveEnvelope(const int AMsgNum, Idmessage::TIdMessage* AMsg, Classes::TStringList* 
		ADestList);
	bool __fastcall UIDInternalRetrieveEnvelope(const AnsiString AMsgUID, Idmessage::TIdMessage* AMsg, 
		Classes::TStringList* ADestList);
	bool __fastcall IsNumberValid(const int ANumber);
	bool __fastcall IsUIDValid(const AnsiString AUID);
	virtual void __fastcall ReceiveBody(Idmessage::TIdMessage* AMsg, const AnsiString ADelim);
	
public:
	bool __fastcall Capability(Classes::TStrings* ASlCapability);
	void __fastcall DoAlert(const AnsiString AMsg);
	__property TIdIMAP4ConnectionState ConnectionState = {read=FConnectionState, nodefault};
	__property Idmailbox::TIdMailBox* MailBox = {read=FMailBox, write=SetMailBox};
	bool __fastcall AppendMsg(const AnsiString AMBName, Idmessage::TIdMessage* AMsg, const Idmessage::TIdMessageFlagsSet 
		AFlags);
	bool __fastcall CheckMailBox(void);
	bool __fastcall CheckMsgSeen(const int AMsgNum);
	virtual void __fastcall Connect(const int ATimeout);
	bool __fastcall CloseMailBox(void);
	__fastcall virtual TIdIMAP4(Classes::TComponent* AOwner);
	bool __fastcall CreateMailBox(const AnsiString AMBName);
	bool __fastcall DeleteMailBox(const AnsiString AMBName);
	bool __fastcall DeleteMsgs(const int * AMsgNumList, const int AMsgNumList_Size);
	__fastcall virtual ~TIdIMAP4(void);
	virtual void __fastcall Disconnect(void);
	bool __fastcall ExamineMailBox(const AnsiString AMBName, Idmailbox::TIdMailBox* AMB);
	bool __fastcall ExpungeMailBox(void);
	void __fastcall KeepAlive(void);
	bool __fastcall ListInferiorMailBoxes(Classes::TStringList* AMailBoxList, Classes::TStringList* AInferiorMailBoxList
		);
	bool __fastcall ListMailBoxes(Classes::TStringList* AMailBoxList);
	bool __fastcall ListSubscribedMailBoxes(Classes::TStringList* AMailBoxList);
	bool __fastcall RenameMailBox(const AnsiString AOldMBName, const AnsiString ANewMBName);
	bool __fastcall SearchMailBox(const TIdIMAP4SearchRec * ASearchInfo, const int ASearchInfo_Size);
	bool __fastcall SelectMailBox(const AnsiString AMBName);
	bool __fastcall StatusMailBox(const AnsiString AMBName, Idmailbox::TIdMailBox* AMB)/* overload */;
	bool __fastcall StatusMailBox(const AnsiString AMBName, Idmailbox::TIdMailBox* AMB, const TIdIMAP4StatusDataItem 
		* AStatusDataItems, const int AStatusDataItems_Size)/* overload */;
	bool __fastcall StoreFlags(const int * AMsgNumList, const int AMsgNumList_Size, const TIdIMAP4StoreDataItem 
		AStoreMethod, const Idmessage::TIdMessageFlagsSet AFlags);
	bool __fastcall SubscribeMailBox(const AnsiString AMBName);
	bool __fastcall CopyMsgs(const int * AMsgNumList, const int AMsgNumList_Size, const AnsiString AMBName
		);
	bool __fastcall Retrieve(const int AMsgNum, Idmessage::TIdMessage* AMsg);
	bool __fastcall RetrieveAllEnvelopes(Idmessagecollection::TIdMessageCollection* AMsgList);
	bool __fastcall RetrieveAllHeaders(Idmessagecollection::TIdMessageCollection* AMsgList);
	bool __fastcall RetrieveAllMsgs(Idmessagecollection::TIdMessageCollection* AMsgList);
	bool __fastcall RetrieveEnvelope(const int AMsgNum, Idmessage::TIdMessage* AMsg);
	bool __fastcall RetrieveEnvelopeRaw(const int AMsgNum, Classes::TStringList* ADestList);
	bool __fastcall RetrieveFlags(const int AMsgNum, Idmessage::TIdMessageFlagsSet &AFlags);
	bool __fastcall InternalRetrieveStructure(const int AMsgNum, Idmessage::TIdMessage* AMsg, TIdImapMessageParts* 
		AParts);
	bool __fastcall RetrieveStructure(const int AMsgNum, Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall RetrieveStructure(const int AMsgNum, TIdImapMessageParts* AParts)/* overload */;
	bool __fastcall RetrievePart(const int AMsgNum, const int APartNum, char * &ABuffer, int &ABufferLength
		, AnsiString AEncoding);
	bool __fastcall RetrievePartPeek(const int AMsgNum, const int APartNum, char * &ABuffer, int &ABufferLength
		, AnsiString AEncoding);
	bool __fastcall RetrievePartToFile(const int AMsgNum, const int APartNum, int ALength, AnsiString ADestFileNameAndPath
		, AnsiString AEncoding);
	bool __fastcall RetrievePartToFilePeek(const int AMsgNum, const int APartNum, int ALength, AnsiString 
		ADestFileNameAndPath, AnsiString AEncoding);
	bool __fastcall RetrieveText(const int AMsgNum, AnsiString &AText);
	bool __fastcall RetrieveText2(const int AMsgNum, AnsiString &AText);
	bool __fastcall RetrieveTextPeek(const int AMsgNum, AnsiString &AText);
	bool __fastcall RetrieveTextPeek2(const int AMsgNum, AnsiString &AText);
	bool __fastcall RetrieveHeader(const int AMsgNum, Idmessage::TIdMessage* AMsg);
	int __fastcall RetrieveMailBoxSize(void);
	int __fastcall RetrieveMsgSize(const int AMsgNum);
	bool __fastcall RetrievePeek(const int AMsgNum, Idmessage::TIdMessage* AMsg);
	bool __fastcall GetUID(const int AMsgNum, AnsiString &AUID);
	bool __fastcall UIDCopyMsg(const AnsiString AMsgUID, const AnsiString AMBName);
	bool __fastcall UIDCheckMsgSeen(const AnsiString AMsgUID);
	bool __fastcall UIDDeleteMsg(const AnsiString AMsgUID);
	bool __fastcall UIDRetrieveAllHeaders(Idmessagecollection::TIdMessageCollection* AMsgList);
	bool __fastcall UIDRetrieveAllEnvelopes(Idmessagecollection::TIdMessageCollection* AMsgList);
	bool __fastcall UIDRetrieve(const AnsiString AMsgUID, Idmessage::TIdMessage* AMsg);
	bool __fastcall UIDRetrieveEnvelope(const AnsiString AMsgUID, Idmessage::TIdMessage* AMsg);
	bool __fastcall UIDRetrieveEnvelopeRaw(const AnsiString AMsgUID, Classes::TStringList* ADestList);
	bool __fastcall UIDRetrieveFlags(const AnsiString AMsgUID, Idmessage::TIdMessageFlagsSet &AFlags);
	bool __fastcall UIDInternalRetrieveStructure(const AnsiString AMsgUID, Idmessage::TIdMessage* AMsg, 
		TIdImapMessageParts* AParts);
	bool __fastcall UIDRetrieveStructure(const AnsiString AMsgUID, Idmessage::TIdMessage* AMsg)/* overload */
		;
	bool __fastcall UIDRetrieveStructure(const AnsiString AMsgUID, TIdImapMessageParts* AParts)/* overload */
		;
	bool __fastcall UIDRetrievePart(const AnsiString AMsgUID, const int APartNum, char * &ABuffer, int 
		&ABufferLength, AnsiString AEncoding);
	bool __fastcall UIDRetrievePartPeek(const AnsiString AMsgUID, const int APartNum, char * &ABuffer, 
		int &ABufferLength, AnsiString AEncoding);
	bool __fastcall UIDRetrievePartToFile(const AnsiString AMsgUID, const int APartNum, int ALength, AnsiString 
		ADestFileNameAndPath, AnsiString AEncoding);
	bool __fastcall UIDRetrievePartToFilePeek(const AnsiString AMsgUID, const int APartNum, int ALength
		, AnsiString ADestFileNameAndPath, AnsiString AEncoding);
	bool __fastcall UIDRetrieveText(const AnsiString AMsgUID, AnsiString &AText);
	bool __fastcall UIDRetrieveText2(const AnsiString AMsgUID, AnsiString &AText);
	bool __fastcall UIDRetrieveTextPeek(const AnsiString AMsgUID, AnsiString &AText);
	bool __fastcall UIDRetrieveTextPeek2(const AnsiString AMsgUID, AnsiString &AText);
	bool __fastcall UIDRetrieveHeader(const AnsiString AMsgUID, Idmessage::TIdMessage* AMsg);
	int __fastcall UIDRetrieveMailBoxSize(void);
	int __fastcall UIDRetrieveMsgSize(const AnsiString AMsgUID);
	bool __fastcall UIDRetrievePeek(const AnsiString AMsgUID, Idmessage::TIdMessage* AMsg);
	bool __fastcall UIDSearchMailBox(const TIdIMAP4SearchRec * ASearchInfo, const int ASearchInfo_Size)
		;
	bool __fastcall UIDStoreFlags(const AnsiString AMsgUID, const TIdIMAP4StoreDataItem AStoreMethod, const 
		Idmessage::TIdMessageFlagsSet AFlags);
	bool __fastcall UnsubscribeMailBox(const AnsiString AMBName);
	HIDESBASE void __fastcall GetInternalResponse(const AnsiString ATag)/* overload */;
	HIDESBASE void __fastcall GetInternalResponse(void)/* overload */;
	void __fastcall GetInternalLineResponse(const AnsiString ATag);
	HIDESBASE short __fastcall GetResponse(const AnsiString ATag, const short * AAllowedResponses, const 
		int AAllowedResponses_Size)/* overload */;
	HIDESBASE short __fastcall GetResponse(const short * AAllowedResponses, const int AAllowedResponses_Size
		)/* overload */;
	short __fastcall GetLineResponse(const AnsiString ATag, const short * AAllowedResponses, const int 
		AAllowedResponses_Size);
	HIDESBASE short __fastcall SendCmd(const AnsiString ATag, const AnsiString AOut, const short AResponse
		)/* overload */;
	HIDESBASE short __fastcall SendCmd(const AnsiString ATag, const AnsiString AOut, const short * AResponse
		, const int AResponse_Size)/* overload */;
	HIDESBASE AnsiString __fastcall ReadLnWait();
	HIDESBASE void __fastcall WriteLn(AnsiString AOut);
	
__published:
	__property TIdAlertEvent OnAlert = {read=FOnAlert, write=FOnAlert};
	__property Password ;
	__property TIdRetrieveOnSelect RetrieveOnSelect = {read=FRetrieveOnSelect, write=FRetrieveOnSelect, 
		default=0};
	__property Port ;
	__property Username ;
	__property char MailBoxSeparator = {read=FMailBoxSeparator, write=FMailBoxSeparator, default=47};
};


//-- var, const, procedure ---------------------------------------------------
static const Shortint wsOk = 0x1;
static const Shortint wsNo = 0x2;
static const Shortint wsBad = 0x3;
static const Shortint wsPreAuth = 0x4;
static const Shortint wsBye = 0x5;
static const Shortint wsContinue = 0x6;
extern PACKAGE AnsiString VALID_TAGGEDREPLIES[6];
extern PACKAGE AnsiString VALID_UNTAGGEDREPLIES[6];

}	/* namespace Idimap4 */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idimap4;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIMAP4
