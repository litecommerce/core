// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdWinSock2.pas' rev: 5.00

#ifndef IdWinSock2HPP
#define IdWinSock2HPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdException.hpp>	// Pascal unit
#include <Windows.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------
#include <winsock2.h>
#include <ws2tcpip.h>
#include <wsipx.h>
#include <wsnwlink.h>
#include <wsnetbs.h>
#include <ws2atm.h>

namespace Idwinsock2
{
    typedef fd_set *PFDSet;
    typedef fd_set TFDSet;
}

// versions of Builder prior to 6.0 are missing some of the newer WinSock2 defines
#ifdef __BORLANDC__
#if (__BORLANDC__ < 0x560)    // prior to BCB6
typedef struct in_pktinfo {
    IN_ADDR ipi_addr;    // destination IPv4 address
    UINT    ipi_ifindex; // received interface index
} IN_PKTINFO;

typedef struct in6_pktinfo {
    IN6_ADDR ipi6_addr;    // destination IPv6 address
    UINT     ipi6_ifindex; // received interface index
} IN6_PKTINFO;

typedef struct addrinfo {
    int ai_flags;              /* AI_PASSIVE, AI_CANONNAME, AI_NUMERICHOST */
    int ai_family;             /* PF_xxx */
    int ai_socktype;           /* SOCK_xxx */
    int ai_protocol;           /* 0 or IPPROTO_xxx for IPv4 and IPv6 */
    size_t ai_addrlen;         /* Length of ai_addr */
    char *ai_canonname;        /* Canonical name for nodename */
    struct sockaddr *ai_addr;  /* Binary address */
    struct addrinfo *ai_next;  /* Next structure in linked list */
} ADDRINFO, FAR * LPADDRINFO;
#endif
#if (__BORLANDC__ < 0x550)    // prior to BCB5
typedef struct _INTERFACE_INFO_EX
{
    u_long          iiFlags;            /* Interface flags */
    SOCKET_ADDRESS  iiAddress;          /* Interface address */
    SOCKET_ADDRESS  iiBroadcastAddress; /* Broadcast address */
    SOCKET_ADDRESS  iiNetmask;          /* Network mask */
} INTERFACE_INFO_EX, FAR * LPINTERFACE_INFO_EX;
#endif
#endif

namespace Idwinsock2
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS EIdWS2StubError;
#pragma pack(push, 1)
class PASCALIMPLEMENTATION EIdWS2StubError : public Idexception::EIdException 
{
	typedef Idexception::EIdException inherited;
	
protected:
	unsigned FWin32Error;
	AnsiString FWin32ErrorMessage;
	AnsiString FTitle;
	
public:
	__fastcall EIdWS2StubError(const AnsiString ATitle, unsigned AWin32Error);
	__property unsigned Win32Error = {read=FWin32Error, nodefault};
	__property AnsiString Win32ErrorMessage = {read=FWin32ErrorMessage};
	__property AnsiString Title = {read=FTitle};
public:
	#pragma option push -w-inl
	/* Exception.Create */ inline __fastcall EIdWS2StubError(const AnsiString Msg) : Idexception::EIdException(
		Msg) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmt */ inline __fastcall EIdWS2StubError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size) : Idexception::EIdException(Msg, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateRes */ inline __fastcall EIdWS2StubError(int Ident)/* overload */ : Idexception::EIdException(
		Ident) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmt */ inline __fastcall EIdWS2StubError(int Ident, const System::TVarRec * Args
		, const int Args_Size)/* overload */ : Idexception::EIdException(Ident, Args, Args_Size) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateHelp */ inline __fastcall EIdWS2StubError(const AnsiString Msg, int AHelpContext
		) : Idexception::EIdException(Msg, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateFmtHelp */ inline __fastcall EIdWS2StubError(const AnsiString Msg, const System::TVarRec 
		* Args, const int Args_Size, int AHelpContext) : Idexception::EIdException(Msg, Args, Args_Size, AHelpContext
		) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResHelp */ inline __fastcall EIdWS2StubError(int Ident, int AHelpContext)/* overload */
		 : Idexception::EIdException(Ident, AHelpContext) { }
	#pragma option pop
	#pragma option push -w-inl
	/* Exception.CreateResFmtHelp */ inline __fastcall EIdWS2StubError(System::PResStringRec ResStringRec
		, const System::TVarRec * Args, const int Args_Size, int AHelpContext)/* overload */ : Idexception::EIdException(
		ResStringRec, Args, Args_Size, AHelpContext) { }
	#pragma option pop
	
public:
	#pragma option push -w-inl
	/* TObject.Destroy */ inline __fastcall virtual ~EIdWS2StubError(void) { }
	#pragma option pop
	
};

#pragma pack(pop)

typedef unsigned *PWSAEVENT;

typedef char *PMBChar;

typedef timeval  TTimeVal;

typedef timeval *PTimeVal;

typedef hostent  THostEnt;

typedef hostent *PHostEnt;

typedef netent  TNetEnt;

typedef netent *PNetEnt;

typedef servent  TServEnt;

typedef servent *PServEnt;

typedef protoent  TProtoEnt;

typedef protoent *PProtoEnt;

typedef in_addr  TInAddr;

typedef in_addr *PInAddr;

typedef sockaddr_in  TSockAddrIn;

typedef sockaddr_in *PSockAddrIn;

typedef sockaddr_in  TSockAddr;

typedef sockproto  TSockProto;

typedef sockproto *PSockProto;

typedef linger  TLinger;

typedef WSADATA  TWSAData;

typedef WSADATA *PWSAData;

typedef _OVERLAPPED  TWSAOverlapped;

typedef _OVERLAPPED *PWSAOverlapped;

typedef _OVERLAPPED *LPWSAOVERLAPPED;

typedef WSABUF  TWSABuf;

typedef WSABUF *PWSABuf;

typedef int TServiceType;

typedef FLOWSPEC  TFlowSpec;

typedef QOS  TQualityOfService;

typedef QOS *PQOS;

typedef WSANETWORKEVENTS  TWSANetworkEvents;

typedef WSANETWORKEVENTS *PWSANetworkEvents;

typedef GUID *PGUID;

typedef WSAPROTOCOLCHAIN  TWSAProtocolChain;

typedef WSAPROTOCOL_INFOA  TWSAProtocol_InfoA;

typedef WSAPROTOCOL_INFOA *PWSAProtocol_InfoA;

typedef WSAPROTOCOL_INFOW  TWSAProtocol_InfoW;

typedef WSAPROTOCOL_INFOW *PWSAProtocol_InfoW;

typedef WSAPROTOCOL_INFOA  TWSAProtocol_Info;

typedef WSAPROTOCOL_INFOA *PWSAProtocol_Info;

typedef BLOB  TBLOB;

typedef BLOB *PBLOB;

typedef SOCKET_ADDRESS  TSocket_Address;

typedef CSADDR_INFO  TCSAddr_Info;

typedef SOCKET_ADDRESS_LIST  TSocket_Address_List;

typedef AFPROTOCOLS  TAFProtocols;

typedef WSAECOMPARATOR TWSAEComparator;

typedef WSAVERSION  TWSAVersion;

typedef WSAQUERYSETA  TWSAQuerySetA;

typedef WSAQUERYSETW  TWSAQuerySetW;

typedef WSAQUERYSETA  TWSAQuerySet;

typedef WSAESETSERVICEOP TWSAESetServiceOp;

typedef WSANSCLASSINFOA  TWSANSClassInfoA;

typedef WSANSCLASSINFOW  TWSANSClassInfoW;

typedef WSANSCLASSINFOA  TWSANSClassInfo;

typedef WSASERVICECLASSINFOA  TWSAServiceClassInfoA;

typedef WSASERVICECLASSINFOW  TWSAServiceClassInfoW;

typedef WSASERVICECLASSINFOA  TWSAServiceClassInfo;

typedef WSANAMESPACE_INFOA  TWSANameSpace_InfoA;

typedef WSANAMESPACE_INFOW  TWSANameSpace_InfoW;

typedef WSANAMESPACE_INFOA  TWSANameSpace_Info;

typedef IN6_ADDR  TIn6Addr;

typedef IN6_ADDR *PIn6Addr;

typedef SOCKADDR_IN6  TSockAddrIn6;

typedef SOCKADDR_IN6 *PSockAddrIn6;

typedef sockaddr_gen  TSockAddrGen;

typedef INTERFACE_INFO  TInterface_Info;

typedef INTERFACE_INFO *PINTERFACE_INFO;

typedef INTERFACE_INFO_EX  TInterface_Info_Ex;

typedef INTERFACE_INFO_EX *PINTERFACE_INFO_EX;

typedef IN_PKTINFO  TInPktInfo;

typedef IN6_PKTINFO  TIn6PktInfo;

typedef ADDRINFO *PAddrInfo;

typedef ADDRINFO  TAddrInfo;

typedef SOCKADDR_IPX  TSockAddr_IPX;

typedef SOCKADDR_IPX  TSockAddrIPX;

typedef SOCKADDR_IPX *PSockAddrIPX;

typedef IPX_ADDRESS_DATA  TIPXAddressData;

typedef IPX_ADDRESS_DATA *PIPXAddressData;

typedef IPX_NETNUM_DATA  TIPXNetNumData;

typedef IPX_NETNUM_DATA *PIPXNetNumData;

typedef IPX_SPXCONNSTATUS_DATA  TIPXSPXConnStatusData;

typedef IPX_SPXCONNSTATUS_DATA *PIPXSPXConnStatusData;

typedef SOCKADDR_NB  TSockAddrNB;

typedef SOCKADDR_NB *PSockAddrNB;

typedef SOCKADDR_ATM  TSockAddrATM;

typedef SOCKADDR_ATM *PSockAddrATM;

typedef SOCKADDR_ATM *LPSockAddrATM;

//-- var, const, procedure ---------------------------------------------------
#define WINSOCK2_DLL "WS2_32.DLL"
extern PACKAGE bool __fastcall Winsock2Loaded(void);

}	/* namespace Idwinsock2 */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idwinsock2;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdWinSock2
