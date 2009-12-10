// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdLPR.pas' rev: 5.00

#ifndef IdLPRHPP
#define IdLPRHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdTCPConnection.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idlpr
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdLPRFileFormat { ffCIF, ffDVI, ffFormattedText, ffPlot, ffControlCharText, ffDitroff, ffPostScript, 
	ffPR, ffFORTRAM, ffTroff, ffSunRaster };
#pragma option pop

class DELPHICLASS TIdLPRControlFile;
class PASCALIMPLEMENTATION TIdLPRControlFile : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FBannerClass;
	AnsiString FHostName;
	int FIndentCount;
	AnsiString FJobName;
	bool FBannerPage;
	AnsiString FUserName;
	int FOutputWidth;
	TIdLPRFileFormat FFileFormat;
	AnsiString FTroffRomanFont;
	AnsiString FTroffItalicFont;
	AnsiString FTroffBoldFont;
	AnsiString FTroffSpecialFont;
	bool FMailWhenPrinted;
	
public:
	__fastcall TIdLPRControlFile(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	__property AnsiString HostName = {read=FHostName, write=FHostName};
	
__published:
	__property AnsiString BannerClass = {read=FBannerClass, write=FBannerClass};
	__property int IndentCount = {read=FIndentCount, write=FIndentCount, default=0};
	__property AnsiString JobName = {read=FJobName, write=FJobName};
	__property bool BannerPage = {read=FBannerPage, write=FBannerPage, default=0};
	__property AnsiString UserName = {read=FUserName, write=FUserName};
	__property int OutputWidth = {read=FOutputWidth, write=FOutputWidth, default=0};
	__property TIdLPRFileFormat FileFormat = {read=FFileFormat, write=FFileFormat, default=4};
	__property AnsiString TroffRomanFont = {read=FTroffRomanFont, write=FTroffRomanFont};
	__property AnsiString TroffItalicFont = {read=FTroffItalicFont, write=FTroffItalicFont};
	__property AnsiString TroffBoldFont = {read=FTroffBoldFont, write=FTroffBoldFont};
	__property AnsiString TroffSpecialFont = {read=FTroffSpecialFont, write=FTroffSpecialFont};
	__property bool MailWhenPrinted = {read=FMailWhenPrinted, write=FMailWhenPrinted, default=0};
public:
		
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdLPRControlFile(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdLPRStatus { psPrinting, psJobCompleted, psError, psGettingQueueState, psGotQueueState, psDeletingJobs, 
	psJobsDeleted, psPrintingWaitingJobs, psPrintedWaitingJobs };
#pragma option pop

typedef void __fastcall (__closure *TIdLPRStatusEvent)(System::TObject* ASender, const TIdLPRStatus 
	AStatus, const AnsiString AStatusText);

class DELPHICLASS TIdLPR;
class PASCALIMPLEMENTATION TIdLPR : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	TIdLPRStatusEvent FOnLPRStatus;
	AnsiString FQueue;
	int FJobId;
	TIdLPRControlFile* FControlFile;
	void __fastcall DoOnLPRStatus(const TIdLPRStatus AStatus, const AnsiString AStatusText);
	void __fastcall SeTIdLPRControlFile(const TIdLPRControlFile* Value);
	void __fastcall CheckReply(void);
	AnsiString __fastcall GetJobId();
	void __fastcall SetJobId(AnsiString JobId);
	void __fastcall InternalPrint(Classes::TStream* Data);
	AnsiString __fastcall GetControlData();
	
public:
	__fastcall virtual TIdLPR(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdLPR(void);
	void __fastcall Print(AnsiString AText)/* overload */;
	void __fastcall Print(const Byte * ABuffer, const int ABuffer_Size)/* overload */;
	void __fastcall PrintFile(AnsiString AFileName);
	AnsiString __fastcall GetQueueState(const bool AShortFormat, const AnsiString AList);
	void __fastcall PrintWaitingJobs(void);
	void __fastcall RemoveJobList(AnsiString AList, const bool AAsRoot);
	__property AnsiString JobId = {read=GetJobId, write=SetJobId};
	
__published:
	__property AnsiString Queue = {read=FQueue, write=FQueue};
	__property TIdLPRControlFile* ControlFile = {read=FControlFile, write=SeTIdLPRControlFile};
	__property TIdLPRStatusEvent OnLPRStatus = {read=FOnLPRStatus, write=FOnLPRStatus};
};


class DELPHICLASS EIdLPRErrorException;
class PASCALIMPLEMENTATION EIdLPRErrorException : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdLPRErrorException(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdLPRErrorException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdLPRErrorException(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdLPRErrorException(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdLPRErrorException(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdLPRErrorException(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdLPRErrorException(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdLPRErrorException(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdLPRErrorException(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
#define DEF_FILEFORMAT (TIdLPRFileFormat)(4)
static const Shortint DEF_INDENTCOUNT = 0x0;
static const bool DEF_BANNERPAGE = false;
static const Shortint DEF_OUTPUTWIDTH = 0x0;
static const bool DEF_MAILWHENPRINTED = false;

}	/* namespace Idlpr */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idlpr;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdLPR
