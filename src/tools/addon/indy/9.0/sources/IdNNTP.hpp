// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdNNTP.pas' rev: 5.00

#ifndef IdNNTPHPP
#define IdNNTPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdMessageClient.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idnntp
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TModeType { mtStream, mtIHAVE, mtReader };
#pragma option pop

#pragma option push -b-
enum TIdNNTPPermission { crCanPost, crNoPost, crAuthRequired, crTempUnavailable };
#pragma option pop

#pragma option push -b-
enum TModeSetResult { mrCanStream, mrNoStream, mrCanIHAVE, mrNoIHAVE, mrCanPost, mrNoPost };
#pragma option pop

typedef void __fastcall (__closure *TEventStreaming)(const AnsiString AMesgID, bool &AAccepted);

typedef void __fastcall (__closure *TNewsTransportEvent)(Classes::TStringList* AMsg);

typedef void __fastcall (__closure *TEventNewsgroupList)(const AnsiString ANewsgroup, const int ALow
	, const int AHigh, const AnsiString AType, bool &ACanContinue);

typedef void __fastcall (__closure *TEventNewNewsList)(const AnsiString AMsgID, bool &ACanContinue);
	

class DELPHICLASS TIdNNTP;
class PASCALIMPLEMENTATION TIdNNTP : public Idmessageclient::TIdMessageClient 
{
	typedef Idmessageclient::TIdMessageClient inherited;
	
protected:
	int FlMsgHigh;
	int FlMsgLow;
	int FlMsgCount;
	AnsiString FNewsAgent;
	TEventNewsgroupList FOnNewsgroupList;
	TEventNewsgroupList FOnNewGroupsList;
	TEventNewNewsList FOnNewNewsList;
	TModeType FModeType;
	TModeSetResult FModeResult;
	TIdNNTPPermission FPermission;
	AnsiString __fastcall ConvertDateTimeDist(System::TDateTime ADate, bool AGMT, const AnsiString ADistributions
		);
	void __fastcall ProcessGroupList(const AnsiString ACmd, const int AResponse, const TEventNewsgroupList 
		AListEvent);
	
public:
	void __fastcall Check(Classes::TStringList* AMsgIDs, Classes::TStringList* &AResponses);
	virtual void __fastcall Connect(const int ATimeout);
	__fastcall virtual TIdNNTP(Classes::TComponent* AOwner);
	virtual void __fastcall Disconnect(void);
	bool __fastcall GetArticle(Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetArticle(const int AMsgNo, Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetArticle(const AnsiString AMsgID, Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetArticle(Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetArticle(const int AMsgNo, Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetArticle(const AnsiString AMsgID, Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetArticle(Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetArticle(const int AMsgNo, Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetArticle(const AnsiString AMsgID, Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetBody(Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetBody(const int AMsgNo, Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetBody(const AnsiString AMsgID, Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetBody(Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetBody(const int AMsgNo, Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetBody(const AnsiString AMsgID, Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetBody(Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetBody(const int AMsgNo, Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetBody(const AnsiString AMsgID, Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetHeader(Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetHeader(const int AMsgNo, Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetHeader(const AnsiString AMsgID, Idmessage::TIdMessage* AMsg)/* overload */;
	bool __fastcall GetHeader(Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetHeader(const int AMsgNo, Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetHeader(const AnsiString AMsgID, Classes::TStrings* AMsg)/* overload */;
	bool __fastcall GetHeader(Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetHeader(const int AMsgNo, Classes::TStream* AMsg)/* overload */;
	bool __fastcall GetHeader(const AnsiString AMsgID, Classes::TStream* AMsg)/* overload */;
	void __fastcall GetNewsgroupList(void)/* overload */;
	void __fastcall GetNewsgroupList(Classes::TStrings* AList)/* overload */;
	void __fastcall GetNewsgroupList(Classes::TStream* AStream)/* overload */;
	void __fastcall GetNewGroupsList(const System::TDateTime ADate, const bool AGMT, const AnsiString ADistributions
		)/* overload */;
	void __fastcall GetNewGroupsList(const System::TDateTime ADate, const bool AGMT, const AnsiString ADistributions
		, Classes::TStrings* AList)/* overload */;
	void __fastcall GetNewNewsList(const AnsiString ANewsgroups, const System::TDateTime ADate, const bool 
		AGMT, AnsiString ADistributions)/* overload */;
	void __fastcall GetNewNewsList(const AnsiString ANewsgroups, const System::TDateTime ADate, const bool 
		AGMT, AnsiString ADistributions, Classes::TStrings* AList)/* overload */;
	void __fastcall GetOverviewFMT(Classes::TStringList* &AResponse);
	void __fastcall IHAVE(Classes::TStringList* AMsg);
	bool __fastcall Next(void);
	bool __fastcall Previous(void);
	void __fastcall ParseXOVER(AnsiString Aline, int &AArticleIndex, AnsiString &ASubject, AnsiString &
		AFrom, System::TDateTime &ADate, AnsiString &AMsgId, AnsiString &AReferences, int &AByteCount, int 
		&ALineCount, AnsiString &AExtraData);
	void __fastcall ParseNewsGroup(AnsiString ALine, AnsiString &ANewsGroup, int &AHi, int &ALo, AnsiString 
		&AStatus);
	void __fastcall Post(Idmessage::TIdMessage* AMsg)/* overload */;
	void __fastcall Post(Classes::TStream* AStream)/* overload */;
	virtual short __fastcall SendCmd(const AnsiString AOut, const short * AResponse, const int AResponse_Size
		)/* overload */;
	bool __fastcall SelectArticle(const int AMsgNo);
	void __fastcall SelectGroup(const AnsiString AGroup);
	AnsiString __fastcall TakeThis(const AnsiString AMsgID, Classes::TStream* AMsg);
	void __fastcall XHDR(const AnsiString AHeader, const AnsiString AParam, Classes::TStrings* AResponse
		);
	void __fastcall XOVER(const AnsiString AParam, Classes::TStrings* AResponse)/* overload */;
	void __fastcall XOVER(const AnsiString AParam, Classes::TStream* AResponse)/* overload */;
	__property TModeSetResult ModeResult = {read=FModeResult, write=FModeResult, nodefault};
	__property int MsgCount = {read=FlMsgCount, nodefault};
	__property int MsgHigh = {read=FlMsgHigh, nodefault};
	__property int MsgLow = {read=FlMsgLow, nodefault};
	__property TIdNNTPPermission Permission = {read=FPermission, nodefault};
	
__published:
	__property AnsiString NewsAgent = {read=FNewsAgent, write=FNewsAgent};
	__property TModeType Mode = {read=FModeType, write=FModeType, default=2};
	__property Password ;
	__property Username ;
	__property TEventNewsgroupList OnNewsgroupList = {read=FOnNewsgroupList, write=FOnNewsgroupList};
	__property TEventNewsgroupList OnNewGroupsList = {read=FOnNewGroupsList, write=FOnNewGroupsList};
	__property TEventNewNewsList OnNewNewsList = {read=FOnNewNewsList, write=FOnNewNewsList};
	__property Port ;
public:
	#pragma option push -w-inl
	/* TIdTCPClient.Destroy */ inline __fastcall virtual ~TIdNNTP(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNNTPException;
class PASCALIMPLEMENTATION EIdNNTPException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNNTPException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNNTPException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNNTPException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNNTPException(int Ident, const System::TVarRec * 
		Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNNTPException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNNTPException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNNTPException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNNTPException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNNTPException(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNNTPNoOnNewGroupsList;
class PASCALIMPLEMENTATION EIdNNTPNoOnNewGroupsList : public EIdNNTPException 
{
	typedef EIdNNTPException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNNTPNoOnNewGroupsList(const AnsiString Msg) : EIdNNTPException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNNTPNoOnNewGroupsList(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdNNTPException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNNTPNoOnNewGroupsList(int Ident)/* overload */ : EIdNNTPException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNNTPNoOnNewGroupsList(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdNNTPException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNNTPNoOnNewGroupsList(const AnsiString Msg, int AHelpContext
		) : EIdNNTPException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNNTPNoOnNewGroupsList(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdNNTPException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNNTPNoOnNewGroupsList(int Ident, int AHelpContext
		)/* overload */ : EIdNNTPException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNNTPNoOnNewGroupsList(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdNNTPException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNNTPNoOnNewGroupsList(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNNTPNoOnNewNewsList;
class PASCALIMPLEMENTATION EIdNNTPNoOnNewNewsList : public EIdNNTPException 
{
	typedef EIdNNTPException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNNTPNoOnNewNewsList(const AnsiString Msg) : EIdNNTPException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNNTPNoOnNewNewsList(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdNNTPException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNNTPNoOnNewNewsList(int Ident)/* overload */ : EIdNNTPException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNNTPNoOnNewNewsList(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdNNTPException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNNTPNoOnNewNewsList(const AnsiString Msg, int AHelpContext
		) : EIdNNTPException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNNTPNoOnNewNewsList(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdNNTPException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNNTPNoOnNewNewsList(int Ident, int AHelpContext)
		/* overload */ : EIdNNTPException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNNTPNoOnNewNewsList(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdNNTPException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNNTPNoOnNewNewsList(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNNTPNoOnNewsgroupList;
class PASCALIMPLEMENTATION EIdNNTPNoOnNewsgroupList : public EIdNNTPException 
{
	typedef EIdNNTPException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNNTPNoOnNewsgroupList(const AnsiString Msg) : EIdNNTPException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNNTPNoOnNewsgroupList(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : EIdNNTPException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNNTPNoOnNewsgroupList(int Ident)/* overload */ : EIdNNTPException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNNTPNoOnNewsgroupList(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdNNTPException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNNTPNoOnNewsgroupList(const AnsiString Msg, int AHelpContext
		) : EIdNNTPException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNNTPNoOnNewsgroupList(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdNNTPException(Msg, Args, Args_Size
		, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNNTPNoOnNewsgroupList(int Ident, int AHelpContext
		)/* overload */ : EIdNNTPException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNNTPNoOnNewsgroupList(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : EIdNNTPException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNNTPNoOnNewsgroupList(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNNTPStringListNotInitialized;
class PASCALIMPLEMENTATION EIdNNTPStringListNotInitialized : public EIdNNTPException 
{
	typedef EIdNNTPException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNNTPStringListNotInitialized(const AnsiString Msg) : EIdNNTPException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNNTPStringListNotInitialized(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : EIdNNTPException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNNTPStringListNotInitialized(int Ident)/* overload */
		 : EIdNNTPException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNNTPStringListNotInitialized(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : EIdNNTPException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNNTPStringListNotInitialized(const AnsiString Msg, 
		int AHelpContext) : EIdNNTPException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNNTPStringListNotInitialized(const AnsiString Msg
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : EIdNNTPException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNNTPStringListNotInitialized(int Ident, int AHelpContext
		)/* overload */ : EIdNNTPException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNNTPStringListNotInitialized(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		EIdNNTPException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNNTPStringListNotInitialized(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdNNTPConnectionRefused;
class PASCALIMPLEMENTATION EIdNNTPConnectionRefused : public Idexception::EIdProtocolReplyError 
{
	typedef Idexception::EIdProtocolReplyError inherited;
	
public:
	#pragma option push -w-inl
	/* EIdProtocolReplyError.CreateError */ inline __fastcall virtual EIdNNTPConnectionRefused(const int 
		AErrCode, const AnsiString AReplyMessage) : Idexception::EIdProtocolReplyError(AErrCode, AReplyMessage
		) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdNNTPConnectionRefused(const AnsiString Msg) : Idexception::EIdProtocolReplyError(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdNNTPConnectionRefused(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdProtocolReplyError(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdNNTPConnectionRefused(int Ident)/* overload */ : Idexception::EIdProtocolReplyError(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdNNTPConnectionRefused(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdProtocolReplyError(Ident, Args, Args_Size
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdNNTPConnectionRefused(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdProtocolReplyError(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdNNTPConnectionRefused(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdProtocolReplyError(
		Msg, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdNNTPConnectionRefused(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdProtocolReplyError(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdNNTPConnectionRefused(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdProtocolReplyError(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdNNTPConnectionRefused(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idnntp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idnntp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdNNTP
