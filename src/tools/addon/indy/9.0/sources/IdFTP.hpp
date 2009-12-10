// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdFTP.pas' rev: 5.00

#ifndef IdFTPHPP
#define IdFTPHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdComponent.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdFTPCommon.hpp>	// Pascal unit
#include <IdFTPList.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdRFCReply.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idftp
{
//-- type declarations -------------------------------------------------------
typedef void __fastcall (__closure *TIdCreateFTPList)(System::TObject* ASender, Idftplist::TIdFTPListItems* 
	&VFTPList);

typedef void __fastcall (__closure *TIdCheckListFormat)(System::TObject* ASender, const AnsiString ALine
	, Idftplist::TIdFTPListFormat &VListFormat);

typedef void __fastcall (__closure *TOnAfterClientLogin)(System::TObject* Sender);

typedef void __fastcall (__closure *TIdFtpAfterGet)(System::TObject* ASender, Classes::TStream* VStream
	);

#pragma option push -b-
enum TIdFtpProxyType { fpcmNone, fpcmUserSite, fpcmSite, fpcmOpen, fpcmUserPass, fpcmTransparent, fpcmHttpProxyWithFtp 
	};
#pragma option pop

class DELPHICLASS TIdFtpProxySettings;
class PASCALIMPLEMENTATION TIdFtpProxySettings : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FHost;
	AnsiString FUserName;
	AnsiString FPassword;
	TIdFtpProxyType FProxyType;
	int FPort;
	
public:
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property TIdFtpProxyType ProxyType = {read=FProxyType, write=FProxyType, nodefault};
	__property AnsiString Host = {read=FHost, write=FHost};
	__property AnsiString UserName = {read=FUserName, write=FUserName};
	__property AnsiString Password = {read=FPassword, write=FPassword};
	__property int Port = {read=FPort, write=FPort, nodefault};
public:
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdFtpProxySettings(void) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Create */ inline __fastcall TIdFtpProxySettings(void) : Classes::TPersistent() { }
	#pragma option pop
	
};


class DELPHICLASS TIdFTP;
class PASCALIMPLEMENTATION TIdFTP : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	bool FCanResume;
	Classes::TStrings* FListResult;
	Idrfcreply::TIdRFCReply* FLoginMsg;
	bool FPassive;
	bool FResumeTested;
	AnsiString FSystemDesc;
	Idftpcommon::TIdFTPTransferType FTransferType;
	Idtcpconnection::TIdTCPConnection* FDataChannel;
	Idftplist::TIdFTPListItems* FDirectoryListing;
	Classes::TNotifyEvent FOnAfterClientLogin;
	TIdCreateFTPList FOnCreateFTPList;
	TIdCheckListFormat FOnCheckListFormat;
	TIdFtpAfterGet FOnAfterGet;
	TIdFtpProxySettings* FProxySettings;
	void __fastcall ConstructDirListing(void);
	void __fastcall DoAfterLogin(void);
	void __fastcall DoFTPList(void);
	void __fastcall DoCheckListFormat(const AnsiString ALine);
	Idftplist::TIdFTPListItems* __fastcall GetDirectoryListing(void);
	Idftplist::TIdOnParseCustomListFormat __fastcall GetOnParseCustomListFormat();
	void __fastcall InitDataChannel(void);
	void __fastcall InternalGet(const AnsiString ACommand, Classes::TStream* ADest, bool AResume);
	void __fastcall InternalPut(const AnsiString ACommand, Classes::TStream* ASource, bool AFromBeginning
		);
	void __fastcall SetOnParseCustomListFormat(const Idftplist::TIdOnParseCustomListFormat AValue);
	void __fastcall SendPassive(AnsiString &VIP, int &VPort);
	void __fastcall SendPort(Idsockethandle::TIdSocketHandle* AHandle);
	void __fastcall SetProxySettings(const TIdFtpProxySettings* Value);
	void __fastcall SendTransferType(void);
	void __fastcall SetTransferType(Idftpcommon::TIdFTPTransferType AValue);
	virtual void __fastcall DoAfterGet(Classes::TStream* AStream);
	
public:
	virtual void __fastcall Abort(void);
	void __fastcall Account(AnsiString AInfo);
	void __fastcall Allocate(int AAllocateBytes);
	void __fastcall ChangeDir(const AnsiString ADirName);
	void __fastcall ChangeDirUp(void);
	HIDESBASE void __fastcall Connect(bool AAutoLogin, const int ATimeout);
	__fastcall virtual TIdFTP(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdFTP(void);
	void __fastcall Delete(const AnsiString AFilename);
	void __fastcall FileStructure(Idftpcommon::TIdFTPDataStructure AStructure);
	void __fastcall Get(const AnsiString ASourceFile, Classes::TStream* ADest, bool AResume)/* overload */
		;
	void __fastcall Get(const AnsiString ASourceFile, const AnsiString ADestFile, const bool ACanOverwrite
		, bool AResume)/* overload */;
	void __fastcall Help(Classes::TStringList* &AHelpContents, AnsiString ACommand);
	virtual void __fastcall KillDataChannel(void);
	void __fastcall List(Classes::TStrings* ADest, const AnsiString ASpecifier, const bool ADetails);
	void __fastcall Login(void);
	void __fastcall MakeDir(const AnsiString ADirName);
	void __fastcall Noop(void);
	void __fastcall Put(const Classes::TStream* ASource, const AnsiString ADestFile, const bool AAppend
		)/* overload */;
	void __fastcall Put(const AnsiString ASourceFile, const AnsiString ADestFile, const bool AAppend)/* overload */
		;
	void __fastcall Quit(void);
	short __fastcall Quote(const AnsiString ACommand);
	void __fastcall RemoveDir(const AnsiString ADirName);
	void __fastcall Rename(const AnsiString ASourceFile, const AnsiString ADestFile);
	bool __fastcall ResumeSupported(void);
	AnsiString __fastcall RetrieveCurrentDir();
	void __fastcall Site(const AnsiString ACommand);
	int __fastcall Size(const AnsiString AFileName);
	void __fastcall Status(Classes::TStringList* &AStatusList);
	void __fastcall StructureMount(AnsiString APath);
	void __fastcall TransferMode(Idftpcommon::TIdFTPTransferMode ATransferMode);
	void __fastcall ReInitialize(unsigned ADelay);
	__property bool CanResume = {read=ResumeSupported, nodefault};
	__property Idftplist::TIdFTPListItems* DirectoryListing = {read=GetDirectoryListing};
	__property Idrfcreply::TIdRFCReply* LoginMsg = {read=FLoginMsg};
	__property AnsiString SystemDesc = {read=FSystemDesc};
	__property Classes::TStrings* ListResult = {read=FListResult};
	
__published:
	__property bool Passive = {read=FPassive, write=FPassive, default=0};
	__property Password ;
	__property Idftpcommon::TIdFTPTransferType TransferType = {read=FTransferType, write=SetTransferType
		, default=1};
	__property Username ;
	__property Port ;
	__property TIdFtpProxySettings* ProxySettings = {read=FProxySettings, write=SetProxySettings};
	__property Classes::TNotifyEvent OnAfterClientLogin = {read=FOnAfterClientLogin, write=FOnAfterClientLogin
		};
	__property TIdCheckListFormat OnCheckListFormat = {read=FOnCheckListFormat, write=FOnCheckListFormat
		};
	__property TIdCreateFTPList OnCreateFTPList = {read=FOnCreateFTPList, write=FOnCreateFTPList};
	__property Idftplist::TIdOnParseCustomListFormat OnParseCustomListFormat = {read=GetOnParseCustomListFormat
		, write=SetOnParseCustomListFormat};
	__property TIdFtpAfterGet OnAfterGet = {read=FOnAfterGet, write=FOnAfterGet};
};


class DELPHICLASS EIdFTPFileAlreadyExists;
class PASCALIMPLEMENTATION EIdFTPFileAlreadyExists : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdFTPFileAlreadyExists(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdFTPFileAlreadyExists(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdFTPFileAlreadyExists(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdFTPFileAlreadyExists(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdFTPFileAlreadyExists(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdFTPFileAlreadyExists(const AnsiString Msg, const 
		System::TVarRec * Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args
		, Args_Size, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdFTPFileAlreadyExists(int Ident, int AHelpContext
		)/* overload */ : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdFTPFileAlreadyExists(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdFTPFileAlreadyExists(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
#define Id_TIdFTP_TransferType (Idftpcommon::TIdFTPTransferType)(1)
static const bool Id_TIdFTP_Passive = false;

}	/* namespace Idftp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idftp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdFTP
