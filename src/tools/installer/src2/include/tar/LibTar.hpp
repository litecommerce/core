// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'LibTar.pas' rev: 5.00

#ifndef LibTarHPP
#define LibTarHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <Classes.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Libtar
{
//-- type declarations -------------------------------------------------------
#pragma option push -b-
enum TTarPermission { tpReadByOwner, tpWriteByOwner, tpExecuteByOwner, tpReadByGroup, tpWriteByGroup, 
	tpExecuteByGroup, tpReadByOther, tpWriteByOther, tpExecuteByOther };
#pragma option pop

typedef Set<TTarPermission, tpReadByOwner, tpExecuteByOther>  TTarPermissions;

#pragma option push -b-
enum TFileType { ftNormal, ftLink, ftSymbolicLink, ftCharacter, ftBlock, ftDirectory, ftFifo, ftContiguous, 
	ftDumpDir, ftMultiVolume, ftVolumeHeader };
#pragma option pop

#pragma option push -b-
enum TTarMode { tmSetUid, tmSetGid, tmSaveText };
#pragma option pop

typedef Set<TTarMode, tmSetUid, tmSaveText>  TTarModes;

struct TTarDirRec
{
	AnsiString Name;
	__int64 Size;
	System::TDateTime DateTime;
	TTarPermissions Permissions;
	TFileType FileType;
	AnsiString LinkName;
	int UID;
	int GID;
	AnsiString UserName;
	AnsiString GroupName;
	bool ChecksumOK;
	TTarModes Mode;
	AnsiString Magic;
	int MajorDevNo;
	int MinorDevNo;
	__int64 FilePos;
} ;

class DELPHICLASS TTarArchive;
class PASCALIMPLEMENTATION TTarArchive : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Classes::TStream* FStream;
	bool FOwnsStream;
	__int64 FBytesToGo;
	
public:
	__fastcall TTarArchive(Classes::TStream* Stream)/* overload */;
	__fastcall TTarArchive(AnsiString Filename, Word FileMode)/* overload */;
	__fastcall virtual ~TTarArchive(void);
	void __fastcall Reset(void);
	bool __fastcall FindNext(TTarDirRec &DirRec);
	void __fastcall ReadFile(void * Buffer)/* overload */;
	void __fastcall ReadFile(Classes::TStream* Stream)/* overload */;
	void __fastcall ReadFile(AnsiString Filename)/* overload */;
	AnsiString __fastcall ReadFile()/* overload */;
	void __fastcall GetFilePos(__int64 &Current, __int64 &Size);
	void __fastcall SetFilePos(__int64 NewPos);
};


class DELPHICLASS TTarWriter;
class PASCALIMPLEMENTATION TTarWriter : public System::TObject 
{
	typedef System::TObject inherited;
	
protected:
	Classes::TStream* FStream;
	bool FOwnsStream;
	bool FFinalized;
	TTarPermissions FPermissions;
	int FUID;
	int FGID;
	AnsiString FUserName;
	AnsiString FGroupName;
	TTarModes FMode;
	AnsiString FMagic;
	__fastcall TTarWriter(void);
	
public:
	__fastcall TTarWriter(Classes::TStream* TargetStream)/* overload */;
	__fastcall TTarWriter(AnsiString TargetFilename, int Mode)/* overload */;
	__fastcall virtual ~TTarWriter(void);
	void __fastcall AddFile(AnsiString Filename, AnsiString TarFilename);
	void __fastcall AddStream(Classes::TStream* Stream, AnsiString TarFilename, System::TDateTime FileDateGmt
		);
	void __fastcall AddString(AnsiString Contents, AnsiString TarFilename, System::TDateTime FileDateGmt
		);
	void __fastcall AddDir(AnsiString Dirname, System::TDateTime DateGmt, __int64 MaxDirSize);
	void __fastcall AddSymbolicLink(AnsiString Filename, AnsiString Linkname, System::TDateTime DateGmt
		);
	void __fastcall AddLink(AnsiString Filename, AnsiString Linkname, System::TDateTime DateGmt);
	void __fastcall AddVolumeHeader(AnsiString VolumeId, System::TDateTime DateGmt);
	void __fastcall Finalize(void);
	__property TTarPermissions Permissions = {read=FPermissions, write=FPermissions, nodefault};
	__property int UID = {read=FUID, write=FUID, nodefault};
	__property int GID = {read=FGID, write=FGID, nodefault};
	__property AnsiString UserName = {read=FUserName, write=FUserName};
	__property AnsiString GroupName = {read=FGroupName, write=FGroupName};
	__property TTarModes Mode = {read=FMode, write=FMode, nodefault};
	__property AnsiString Magic = {read=FMagic, write=FMagic};
};


typedef AnsiString LibTar__3[11];

//-- var, const, procedure ---------------------------------------------------
extern PACKAGE AnsiString FILETYPE_NAME[11];
#define ALL_PERMISSIONS (System::Set<TTarPermission, tpReadByOwner, tpExecuteByOther> () )
#define READ_PERMISSIONS (System::Set<TTarPermission, tpReadByOwner, tpExecuteByOther> () )
#define WRITE_PERMISSIONS (System::Set<TTarPermission, tpReadByOwner, tpExecuteByOther> () )
#define EXECUTE_PERMISSIONS (System::Set<TTarPermission, tpReadByOwner, tpExecuteByOther> () )
extern PACKAGE AnsiString __fastcall PermissionString(TTarPermissions Permissions);
extern PACKAGE AnsiString __fastcall ConvertFilename(AnsiString Filename);
extern PACKAGE System::TDateTime __fastcall FileTimeGMT(AnsiString FileName)/* overload */;
extern PACKAGE System::TDateTime __fastcall FileTimeGMT(const Sysutils::TSearchRec &SearchRec)/* overload */
	;
extern PACKAGE void __fastcall ClearDirRec(TTarDirRec &DirRec);

}	/* namespace Libtar */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Libtar;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// LibTar
