// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdGlobal.pas' rev: 5.00

#ifndef IdGlobalHPP
#define IdGlobalHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <Windows.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idglobal
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TIdOSType { otUnknown, otLinux, otWindows };
#pragma option pop

typedef AnsiString IdGlobal__1[7];

typedef AnsiString IdGlobal__2[12];

typedef unsigned THandle;

typedef Classes::TThreadPriority TIdThreadPriority;

#pragma option push -b-
enum TIdMaxLineAction { maException, maSplit };
#pragma option pop

typedef AnsiString __fastcall (__closure *TIdReadLnFunction)(void);

typedef void __fastcall (*TStringEvent)(Classes::TComponent* ASender, const AnsiString AString);

typedef int __fastcall (*TPosProc)(const AnsiString Substr, const AnsiString S);

#pragma option push -b-
enum TIdReuseSocket { rsOSDependent, rsTrue, rsFalse };
#pragma option pop

struct TIdCardinalBytes
{
	
	union
	{
		struct 
		{
			char CharArray[4];
			
		};
		struct 
		{
			unsigned Whole;
			
		};
		struct 
		{
			System::Byte Byte1;
			System::Byte Byte2;
			System::Byte Byte3;
			System::Byte Byte4;
			
		};
		
	};
} ;

class DELPHICLASS TIdLocalEvent;
class PASCALIMPLEMENTATION TIdLocalEvent : public Syncobjs::TEvent 
{
	typedef Syncobjs::TEvent inherited;
	
public:
	__fastcall TIdLocalEvent(const bool AInitialState, const bool AManualReset);
	HIDESBASE Syncobjs::TWaitResult __fastcall WaitFor(void)/* overload */;
public:
	#pragma option push -w-inl
	/* THandleObject.Destroy */ inline __fastcall virtual ~TIdLocalEvent(void) { }
	#pragma option pop
	
};


class DELPHICLASS TIdMimeTable;
class PASCALIMPLEMENTATION TIdMimeTable : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Classes::TNotifyEvent FOnBuildCache;
	Classes::TStringList* FMIMEList;
	Classes::TStringList* FFileExt;
	virtual void __fastcall BuildDefaultCache(void);
	
public:
	virtual void __fastcall BuildCache(void);
	void __fastcall AddMimeType(const AnsiString Ext, const AnsiString MIMEType);
	AnsiString __fastcall GetFileMIMEType(const AnsiString AFileName);
	AnsiString __fastcall GetDefaultFileExt(const AnsiString MIMEType);
	void __fastcall LoadFromStrings(Classes::TStrings* AStrings, const char MimeSeparator);
	void __fastcall SaveToStrings(Classes::TStrings* AStrings, const char MimeSeparator);
	__fastcall virtual TIdMimeTable(bool Autofill);
	__fastcall virtual ~TIdMimeTable(void);
	__property Classes::TNotifyEvent OnBuildCache = {read=FOnBuildCache, write=FOnBuildCache};
};


class DELPHICLASS TIdReadMemoryStream;
class PASCALIMPLEMENTATION TIdReadMemoryStream : public Classes::TCustomMemoryStream 
{
	typedef Classes::TCustomMemoryStream inherited;
	
public:
	HIDESBASE void __fastcall SetPointer(void * Ptr, int Size);
	virtual int __fastcall Write(const void *Buffer, int Count);
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdReadMemoryStream(void) : Classes::TCustomMemoryStream() { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdReadMemoryStream(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdCharSet { csGB2312, csBig5, csIso2022jp, csEucKR, csIso88591 };
#pragma option pop

typedef System::Byte *PByte;

typedef Word *PWord;

typedef unsigned TIdPID;

#pragma option push -b-
enum TIdWin32Type { Win32s, WindowsNT40, Windows95, Windows95OSR2, Windows98, Windows98SE, Windows2000, 
	WindowsMe, WindowsXP };
#pragma option pop

class DELPHICLASS EIdFailedToRetreiveTimeZoneInfo;
class PASCALIMPLEMENTATION EIdFailedToRetreiveTimeZoneInfo : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(int Ident)/* overload */
		 : Idexception::EIdException(Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(const AnsiString Msg, 
		int AHelpContext) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(const AnsiString Msg
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(
		Msg, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdFailedToRetreiveTimeZoneInfo(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		Idexception::EIdException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdFailedToRetreiveTimeZoneInfo(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdCorruptServicesFile;
class PASCALIMPLEMENTATION EIdCorruptServicesFile : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdCorruptServicesFile(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdCorruptServicesFile(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdCorruptServicesFile(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdCorruptServicesFile(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdCorruptServicesFile(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdCorruptServicesFile(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdCorruptServicesFile(int Ident, int AHelpContext)
		/* overload */ : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdCorruptServicesFile(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdCorruptServicesFile(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdExtensionAlreadyExists;
class PASCALIMPLEMENTATION EIdExtensionAlreadyExists : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdExtensionAlreadyExists(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdExtensionAlreadyExists(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdExtensionAlreadyExists(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdExtensionAlreadyExists(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdExtensionAlreadyExists(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdExtensionAlreadyExists(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdExtensionAlreadyExists(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdExtensionAlreadyExists(System::PResStringRec 
		ResStringRec, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : 
		Idexception::EIdException(ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdExtensionAlreadyExists(void) { }
	#pragma option pop
	
};


typedef DynamicArray<AnsiString >  IdGlobal__9;

typedef DynamicArray<AnsiString >  IdGlobal__01;

//-- var, const, procedure ---------------------------------------------------
static const Shortint IdTimeoutDefault = 0xffffffff;
static const Shortint IdTimeoutInfinite = 0xfffffffe;
static const char IdFetchDelimDefault = '\x20';
static const bool IdFetchDeleteDefault = true;
static const bool IdFetchCaseSensitiveDefault = true;
#define gsIdProductName "Indy"
#define gsIdVersion "9.0.14"
static const char CHAR0 = '\x0';
static const char BACKSPACE = '\x8';
static const char LF = '\xa';
static const char CR = '\xd';
#define EOL "\r\n"
static const char TAB = '\x9';
static const char CHAR32 = '\x20';
#define sLineBreak "\r\n"
#define LWS (System::Set<char, 0, 255> () << '\x9' << '\x20' )
extern PACKAGE AnsiString wdays[7];
extern PACKAGE AnsiString monthnames[12];
extern PACKAGE char IdHexDigits[16];
static const char GPathDelim = '\x5c';
#define GOSType (TIdOSType)(2)
static const unsigned infinite = 0xffffffff;
extern PACKAGE TPosProc IndyPos;
extern PACKAGE DynamicArray<AnsiString >  IndyFalseBoolStrs;
extern PACKAGE DynamicArray<AnsiString >  IndyTrueBoolStrs;
extern PACKAGE TIdWin32Type __fastcall Win32Type(void);
extern PACKAGE unsigned __fastcall GetThreadHandle(Classes::TThread* AThread);
extern PACKAGE AnsiString __fastcall IncludeTrailingSlash(const AnsiString APath);
extern PACKAGE int __fastcall Max(int AValueOne, int AValueTwo);
extern PACKAGE int __fastcall Min(int AValueOne, int AValueTwo);
extern PACKAGE AnsiString __fastcall DateTimeGMTToHttpStr(const System::TDateTime GMTValue);
extern PACKAGE AnsiString __fastcall DateTimeToInternetStr(const System::TDateTime Value, const bool 
	AIsGMT);
extern PACKAGE System::TDateTime __fastcall StrInternetToDateTime(AnsiString Value);
extern PACKAGE AnsiString __fastcall GetInternetFormattedFileTimeStamp(const AnsiString AFilename);
extern PACKAGE System::TDateTime __fastcall GetFileCreationTime(const AnsiString Filename);
extern PACKAGE Classes::TStrings* __fastcall BreakApart(AnsiString BaseString, AnsiString BreakString
	, Classes::TStrings* StringList);
extern PACKAGE void __fastcall CommaSeparatedToStringList(Classes::TStrings* AList, const AnsiString 
	Value);
extern PACKAGE bool __fastcall CopyFileTo(const AnsiString Source, const AnsiString Destination);
extern PACKAGE AnsiString __fastcall MakeTempFilename(const AnsiString APath);
extern PACKAGE int __fastcall RPos(const AnsiString ASub, const AnsiString AIn, int AStart);
extern PACKAGE TIdCharSet __fastcall GetSystemLocale(void);
extern PACKAGE __int64 __fastcall FileSizeByName(const AnsiString AFilename);
extern PACKAGE AnsiString __fastcall RightStr(const AnsiString AStr, int Len);
extern PACKAGE System::TDateTime __fastcall OffsetFromUTC(void);
extern PACKAGE unsigned __fastcall StrToCard(const AnsiString AStr);
extern PACKAGE System::TDateTime __fastcall TimeZoneBias(void);
extern PACKAGE unsigned __fastcall GetTickCount(void);
extern PACKAGE unsigned __fastcall GetTickDiff(const unsigned AOldTickCount, const unsigned ANewTickCount
	);
extern PACKAGE bool __fastcall IndyStrToBool(const AnsiString AString);
extern PACKAGE bool __fastcall SetLocalTime(System::TDateTime Value);
extern PACKAGE Classes::TList* __fastcall IdPorts(void);
extern PACKAGE AnsiString __fastcall FetchCaseInsensitive(AnsiString &AInput, const AnsiString ADelim
	, const bool ADelete);
extern PACKAGE AnsiString __fastcall Fetch(AnsiString &AInput, const AnsiString ADelim, const bool ADelete
	, const bool ACaseSensitive);
extern PACKAGE int __fastcall PosInStrArray(const AnsiString SearchStr, const AnsiString * Contents, 
	const int Contents_Size, const bool CaseSensitive);
extern PACKAGE bool __fastcall IsCurrentThread(Classes::TThread* AThread);
extern PACKAGE bool __fastcall IsNumeric(char AChar)/* overload */;
extern PACKAGE bool __fastcall IsNumeric(const AnsiString AString)/* overload */;
extern PACKAGE System::Byte __fastcall StrToDay(const AnsiString ADay);
extern PACKAGE System::Byte __fastcall StrToMonth(const AnsiString AMonth);
extern PACKAGE AnsiString __fastcall UpCaseFirst(const AnsiString AStr);
extern PACKAGE AnsiString __fastcall DateTimeToGmtOffSetStr(System::TDateTime ADateTime, bool SubGMT
	);
extern PACKAGE AnsiString __fastcall GetMIMETypeFromFile(const AnsiString AFile);
extern PACKAGE System::TDateTime __fastcall GmtOffsetStrToDateTime(AnsiString S);
extern PACKAGE System::TDateTime __fastcall GMTToLocalDateTime(AnsiString S);
extern PACKAGE void __fastcall Sleep(unsigned ATime);
extern PACKAGE AnsiString __fastcall IntToBin(unsigned Value);
extern PACKAGE unsigned __fastcall CurrentProcessId(void);
extern PACKAGE unsigned __fastcall ROL(unsigned AVal, System::Byte AShift);
extern PACKAGE unsigned __fastcall ROR(unsigned AVal, System::Byte AShift);
extern PACKAGE void __fastcall DebugOutput(const AnsiString AText);
extern PACKAGE bool __fastcall InMainThread(void);
extern PACKAGE void __fastcall SetThreadPriority(Classes::TThread* AThread, const Classes::TThreadPriority 
	APriority, const int APolicy);
extern PACKAGE int __fastcall MemoryPos(const AnsiString ASubStr, char * MemBuff, int MemorySize);
extern PACKAGE int __fastcall IndyInterlockedIncrement(int &I);
extern PACKAGE int __fastcall IndyInterlockedDecrement(int &I);
extern PACKAGE int __fastcall IndyInterlockedExchange(int &A, int B);
extern PACKAGE int __fastcall IndyInterlockedExchangeAdd(int &A, int B);
extern PACKAGE AnsiString __fastcall IndyGetHostName();
extern PACKAGE bool __fastcall IsValidIP(const AnsiString S);
extern PACKAGE bool __fastcall IsHostname(const AnsiString S);
extern PACKAGE bool __fastcall IsTopDomain(const AnsiString AStr);
extern PACKAGE bool __fastcall IsDomain(const AnsiString S);
extern PACKAGE AnsiString __fastcall DomainName(const AnsiString AHost);
extern PACKAGE bool __fastcall IsFQDN(const AnsiString S);
extern PACKAGE AnsiString __fastcall ProcessPath(const AnsiString ABasePath, const AnsiString APath, 
	const AnsiString APathDelim);
extern PACKAGE int __fastcall iif(bool ATest, const int ATrue, const int AFalse)/* overload */;
extern PACKAGE AnsiString __fastcall iif(bool ATest, const AnsiString ATrue, const AnsiString AFalse
	)/* overload */;
extern PACKAGE bool __fastcall iif(bool ATest, const bool ATrue, const bool AFalse)/* overload */;
extern PACKAGE unsigned __fastcall AnsiPosIdx(const AnsiString ASubStr, const AnsiString AStr, unsigned 
	AStartPos);
extern PACKAGE int __fastcall AnsiMemoryPos(const AnsiString ASubStr, char * MemBuff, int MemorySize
	);
extern PACKAGE unsigned __fastcall PosIdx(const AnsiString ASubStr, const AnsiString AStr, unsigned 
	AStartPos);
extern PACKAGE Sysutils::TMethod __fastcall MakeMethod(void * DataSelf, void * Code);

}	/* namespace Idglobal */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idglobal;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdGlobal
