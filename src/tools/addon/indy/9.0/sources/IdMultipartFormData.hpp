// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdMultipartFormData.pas' rev: 5.00

#ifndef IdMultipartFormDataHPP
#define IdMultipartFormDataHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdResourceStrings.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idmultipartformdata
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdFormDataField;
class PASCALIMPLEMENTATION TIdFormDataField : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	int FFieldSize;
	AnsiString FFieldValue;
	AnsiString FFileName;
	AnsiString FContentType;
	AnsiString FFieldName;
	System::TObject* FFieldObject;
	bool FInternallyAssigned;
	void __fastcall SetFieldStream(const Classes::TStream* Value);
	int __fastcall GetFieldSize(void);
	void __fastcall SetContentType(const AnsiString Value);
	void __fastcall SetFieldName(const AnsiString Value);
	void __fastcall SetFieldValue(const AnsiString Value);
	Classes::TStream* __fastcall GetFieldStream(void);
	void __fastcall SetFieldObject(const System::TObject* Value);
	void __fastcall SetFileName(const AnsiString Value);
	
public:
	__fastcall virtual TIdFormDataField(Classes::TCollection* Collection);
	__fastcall virtual ~TIdFormDataField(void);
	__property AnsiString ContentType = {read=FContentType, write=SetContentType};
	__property AnsiString FieldName = {read=FFieldName, write=SetFieldName};
	__property Classes::TStream* FieldStream = {read=GetFieldStream, write=SetFieldStream};
	__property System::TObject* FieldObject = {read=FFieldObject, write=SetFieldObject};
	__property AnsiString FileName = {read=FFileName, write=SetFileName};
	__property AnsiString FieldValue = {read=FFieldValue, write=SetFieldValue};
	__property int FieldSize = {read=GetFieldSize, write=FFieldSize, nodefault};
};


class DELPHICLASS TIdFormDataFields;
class DELPHICLASS TIdMultiPartFormDataStream;
class PASCALIMPLEMENTATION TIdMultiPartFormDataStream : public Classes::TStream 
{
	typedef Classes::TStream inherited;
	
protected:
	Classes::TStream* FInputStream;
	AnsiString FBoundary;
	AnsiString FRequestContentType;
	int FItem;
	bool FInitialized;
	AnsiString FInternalBuffer;
	__int64 FPosition;
	__int64 FSize;
	TIdFormDataFields* FFields;
	AnsiString __fastcall GenerateUniqueBoundary();
	AnsiString __fastcall FormatField(int AIndex);
	AnsiString __fastcall PrepareStreamForDispatch();
	
public:
	__fastcall TIdMultiPartFormDataStream(void);
	__fastcall virtual ~TIdMultiPartFormDataStream(void);
	virtual int __fastcall Read(void *Buffer, int Count);
	virtual int __fastcall Write(const void *Buffer, int Count);
	virtual int __fastcall Seek(int Offset, Word Origin)/* overload */;
	void __fastcall AddFormField(const AnsiString AFieldName, const AnsiString AFieldValue);
	void __fastcall AddObject(const AnsiString AFieldName, const AnsiString AContentType, System::TObject* 
		AFileData, const AnsiString AFileName);
	void __fastcall AddFile(const AnsiString AFieldName, const AnsiString AFileName, const AnsiString AContentType
		);
	__property AnsiString Boundary = {read=FBoundary};
	__property AnsiString RequestContentType = {read=FRequestContentType};
};


class PASCALIMPLEMENTATION TIdFormDataFields : public Classes::TCollection 
{
	typedef Classes::TCollection inherited;
	
protected:
	TIdMultiPartFormDataStream* FParentStream;
	TIdFormDataField* __fastcall GetFormDataField(int AIndex);
	
public:
	__fastcall TIdFormDataFields(TIdMultiPartFormDataStream* AMPStream);
	HIDESBASE TIdFormDataField* __fastcall Add(void);
	__property TIdMultiPartFormDataStream* MultipartFormDataStream = {read=FParentStream};
	__property TIdFormDataField* Items[int AIndex] = {read=GetFormDataField};
public:
	#pragma option push -w-inl
	/* TCollection.Destroy */ inline __fastcall virtual ~TIdFormDataFields(void) { }
	#pragma option pop
	
};


class DELPHICLASS EIdInvalidObjectType;
class PASCALIMPLEMENTATION EIdInvalidObjectType : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdInvalidObjectType(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdInvalidObjectType(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdInvalidObjectType(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdInvalidObjectType(int Ident, const System::TVarRec 
		* Args, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
		
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdInvalidObjectType(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdInvalidObjectType(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdInvalidObjectType(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdInvalidObjectType(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdInvalidObjectType(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------
#define sContentType "multipart/form-data; boundary="
#define crlf "\r\n"
#define sContentDisposition "Content-Disposition: form-data; name=\"%s\""
#define sFileNamePlaceHolder "; filename=\"%s\""
#define sContentTypePlaceHolder "Content-Type: %s\r\n\r\n"

}	/* namespace Idmultipartformdata */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idmultipartformdata;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdMultipartFormData
