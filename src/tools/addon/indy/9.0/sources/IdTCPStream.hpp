// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdTCPStream.pas' rev: 5.00

#ifndef IdTCPStreamHPP
#define IdTCPStreamHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdTCPConnection.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idtcpstream
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdTCPStream;
class PASCALIMPLEMENTATION TIdTCPStream : public Classes::TStream 
{
	typedef Classes::TStream inherited;
	
protected:
	Idtcpconnection::TIdTCPConnection* FConnection;
	
public:
	__fastcall TIdTCPStream(Idtcpconnection::TIdTCPConnection* AConnection);
	virtual int __fastcall Read(void *ABuffer, int ACount);
	virtual int __fastcall Write(const void *ABuffer, int ACount);
	virtual int __fastcall Seek(int AOffset, Word AOrigin);
	__property Idtcpconnection::TIdTCPConnection* Connection = {read=FConnection};
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~TIdTCPStream(void) { }
	#pragma option pop
	
};


//-- var, const, procedure ---------------------------------------------------

}	/* namespace Idtcpstream */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idtcpstream;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdTCPStream
