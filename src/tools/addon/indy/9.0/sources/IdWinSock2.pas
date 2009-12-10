{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10425: IdWinSock2.pas 
{
{   Rev 1.2    4/20/03 1:54:08 PM  RLebeau
{ Updated to better support C++Builder by adding $EXTERNSYM defines to most of
{ the interface declarations, so that they won't be included in the
{ auto-generated HPP file.  The native winsock2.h header file is used instead.
{ 
{ Updated with more of the latest WinSock2 defines and declarations.
}
{
{   Rev 1.1    3/22/2003 09:55:58 PM  JPMugaas
{ Commented out definition for TGUID.  It is not needed because that is in D4,
{ D5, D6, and D7.  It could cause conflicts with other code.
{ Fixed bug where a space would cause the WSACreateEvent not to load.
}
{
{   Rev 1.0    2002.11.12 11:00:26 PM  czhower
}
//-------------------------------------------------------------
//
//       Borland Delphi Runtime Library
//       <API> interface unit
//
// Portions created by Microsoft are
// Copyright (C) 1995-1999 Microsoft Corporation.
// All Rights Reserved.
//
// The original file is: Winsock2.h from CBuilder5 distribution.
// The original Pascal code is: winsock2.pas, released 03 Mar 2001.
// The initial developer of the Pascal code is Alex Konshin
// (alexk@mtgroup.ru).
//-------------------------------------------------------------


{ Winsock2.h -- definitions to be used with the WinSock 2 DLL and WinSock 2 applications.
  This header file corresponds to version 2.2.x of the WinSock API specification.
  This file includes parts which are Copyright (c) 1982-1986 Regents
  of the University of California. All rights reserved.
  The Berkeley Software License Agreement specifies the terms and
  conditions for redistribution. }

// Note that the original unit is copyrighted by the original author and I did obtain his
// permission to port and use this as part of Indy - J. Peter Mugaas

// 2002-01-28 - Hadi Hariri. Fixes for C++ Builder. Thanks to Chuck Smith.
// 2001 - Oct -25  J. Peter Mugaas
//    Made adjustments for Indy usage by
//    1) including removing Trace logging
//    2) renaming and consolidating some .INC files as appropriate
//    3) modifying the unit to follow Indy conventions
//    4) Adding TransmitFile support for the HTTP Server
//    5) Removing all static loading code that was IFDEF'ed.    {Do not Localize}
// 2001 - Mar - 1  Alex Konshin
// Revision 3
// converted by Alex Konshin, mailto:alexk@mtgroup.ru
// revision 3, March,1 2001


unit IdWinSock2;

interface

{$ALIGN OFF}
{$RANGECHECKS OFF}
{$WRITEABLECONST OFF}

uses SysUtils, Windows, IdException;

type
  EIdWS2StubError = class(EIdException)
  protected
    FWin32Error : DWORD;
    FWin32ErrorMessage : String;
    FTitle : String;
  public
    constructor Build( const ATitle : String; AWin32Error : DWORD );
    property Win32Error : DWORD read FWin32Error;
    property Win32ErrorMessage : String read FWin32ErrorMessage;
    property Title : String read FTitle;
  end;


{$DEFINE WS2_DLL_FUNC_VARS}
{$DEFINE INCL_WINSOCK_API_PROTOTYPES}
{$DEFINE INCL_WINSOCK_API_TYPEDEFS}


//  Define the current Winsock version. To build an earlier Winsock version
//  application redefine this value prior to including Winsock2.h
const
  {$EXTERNALSYM WINSOCK_VERSION}
  WINSOCK_VERSION = $0202;
  WINSOCK2_DLL = 'WS2_32.DLL';    {Do not Localize}

type
  {$EXTERNALSYM u_char}
  u_char  = Byte;
  {$EXTERNALSYM u_short}
  u_short = Word;
  {$EXTERNALSYM u_int}
  //u_int   = DWORD;
  u_int   = Integer;
  {$EXTERNALSYM u_long}
  u_long  = DWORD;
// The new type to be used in all instances which refer to sockets.
  {$EXTERNALSYM TSocket}
  TSocket = u_int;

  {$EXTERNALSYM WSAEVENT}
  WSAEVENT = THandle;
  PWSAEVENT = ^WSAEVENT;
  {$EXTERNALSYM LPWSAEVENT}
  LPWSAEVENT = PWSAEVENT;
{$IFDEF UNICODE}
  PMBChar = PWideChar;
{$ELSE}
  PMBChar = PChar;
{$ENDIF}

const
  {$EXTERNALSYM FD_SETSIZE}
  FD_SETSIZE     =   64;

type
// the following emits are a workaround to the name conflicts
// with the winsock2 header files
(*$HPPEMIT '#include <winsock2.h>'*)
(*$HPPEMIT '#include <ws2tcpip.h>'*)
(*$HPPEMIT '#include <wsipx.h>'*)
(*$HPPEMIT '#include <wsnwlink.h>'*)
(*$HPPEMIT '#include <wsnetbs.h>'*)
(*$HPPEMIT '#include <ws2atm.h>'*)
(*$HPPEMIT ''*)
(*$HPPEMIT 'namespace Idwinsock2'*)
(*$HPPEMIT '{'*)
(*$HPPEMIT '    typedef fd_set *PFDSet;'*) // due to name conflict with procedure FD_SET
(*$HPPEMIT '    typedef fd_set TFDSet;'*)  // due to name conflict with procedure FD_SET
(*$HPPEMIT '}'*)
(*$HPPEMIT ''*)

// the following emits are to ensure all supported versions
// of C++Builder know about the latest winsock2 structures
(*$HPPEMIT '// versions of Builder prior to 6.0 are missing some of the newer WinSock2 defines'*)
(*$HPPEMIT '#ifdef __BORLANDC__'*)
(*$HPPEMIT '#if (__BORLANDC__ < 0x560)    // prior to BCB6'*)
(*$HPPEMIT 'typedef struct in_pktinfo {'*)
(*$HPPEMIT '    IN_ADDR ipi_addr;    // destination IPv4 address'*)
(*$HPPEMIT '    UINT    ipi_ifindex; // received interface index'*)
(*$HPPEMIT '} IN_PKTINFO;'*)
(*$HPPEMIT ''*)
(*$HPPEMIT 'typedef struct in6_pktinfo {'*)
(*$HPPEMIT '    IN6_ADDR ipi6_addr;    // destination IPv6 address'*)
(*$HPPEMIT '    UINT     ipi6_ifindex; // received interface index'*)
(*$HPPEMIT '} IN6_PKTINFO;'*)
(*$HPPEMIT ''*)
(*$HPPEMIT 'typedef struct addrinfo {'*)
(*$HPPEMIT '    int ai_flags;              /* AI_PASSIVE, AI_CANONNAME, AI_NUMERICHOST */'*)
(*$HPPEMIT '    int ai_family;             /* PF_xxx */'*)
(*$HPPEMIT '    int ai_socktype;           /* SOCK_xxx */'*)
(*$HPPEMIT '    int ai_protocol;           /* 0 or IPPROTO_xxx for IPv4 and IPv6 */'*)
(*$HPPEMIT '    size_t ai_addrlen;         /* Length of ai_addr */'*)
(*$HPPEMIT '    char *ai_canonname;        /* Canonical name for nodename */'*)
(*$HPPEMIT '    struct sockaddr *ai_addr;  /* Binary address */'*)
(*$HPPEMIT '    struct addrinfo *ai_next;  /* Next structure in linked list */'*)
(*$HPPEMIT '} ADDRINFO, FAR * LPADDRINFO;'*)
(*$HPPEMIT '#endif'*)
(*$HPPEMIT '#if (__BORLANDC__ < 0x550)    // prior to BCB5'*)
(*$HPPEMIT 'typedef struct _INTERFACE_INFO_EX'*)
(*$HPPEMIT '{'*)
(*$HPPEMIT '    u_long          iiFlags;            /* Interface flags */'*)
(*$HPPEMIT '    SOCKET_ADDRESS  iiAddress;          /* Interface address */'*)
(*$HPPEMIT '    SOCKET_ADDRESS  iiBroadcastAddress; /* Broadcast address */'*)
(*$HPPEMIT '    SOCKET_ADDRESS  iiNetmask;          /* Network mask */'*)
(*$HPPEMIT '} INTERFACE_INFO_EX, FAR * LPINTERFACE_INFO_EX;'*)
(*$HPPEMIT '#endif'*)
(*$HPPEMIT '#endif'*)

  {$NODEFINE PFDSet}
  PFDSet = ^TFDSet;
  {$NODEFINE TFDSet}
  TFDSet = packed record
    fd_count: u_int;
    fd_array: array[0..FD_SETSIZE-1] of TSocket;
  end;

  {$EXTERNALSYM timeval}
  timeval = record
    tv_sec: Longint;
    tv_usec: Longint;
  end;
  TTimeVal = timeval;
  PTimeVal = ^TTimeVal;

const
  {$EXTERNALSYM IOCPARM_MASK}
  IOCPARM_MASK = $7F;
  {$EXTERNALSYM IOC_VOID}
  IOC_VOID     = $20000000;
  {$EXTERNALSYM IOC_OUT}
  IOC_OUT      = $40000000;
  {$EXTERNALSYM IOC_IN}
  IOC_IN       = $80000000;
  {$EXTERNALSYM IOC_INOUT}
  IOC_INOUT    = (IOC_IN or IOC_OUT);

// get # bytes to read
  {$EXTERNALSYM FIONREAD}
  FIONREAD     = IOC_OUT or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('f') shl 8) or 127;    {Do not Localize}
// set/clear non-blocking i/o
  {$EXTERNALSYM FIONBIO}
  FIONBIO      = IOC_IN  or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('f') shl 8) or 126;    {Do not Localize}
// set/clear async i/o
  {$EXTERNALSYM FIOASYNC}
  FIOASYNC     = IOC_IN  or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('f') shl 8) or 125;    {Do not Localize}

//  Socket I/O Controls

// set high watermark
  {$EXTERNALSYM SIOCSHIWAT}
  SIOCSHIWAT   = IOC_IN  or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('s') shl 8) or 0;    {Do not Localize}
// get high watermark
  {$EXTERNALSYM SIOCGHIWAT}
  SIOCGHIWAT   = IOC_OUT or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('s') shl 8) or 1;    {Do not Localize}
// set low watermark
  {$EXTERNALSYM SIOCSLOWAT}
  SIOCSLOWAT   = IOC_IN or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('s') shl 8) or 2;    {Do not Localize}
// get low watermark
  {$EXTERNALSYM SIOCGLOWAT}
  SIOCGLOWAT   = IOC_OUT or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('s') shl 8) or 3;    {Do not Localize}
// at oob mark?
  {$EXTERNALSYM SIOCATMARK}
  SIOCATMARK   = IOC_OUT or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('s') shl 8) or 7;    {Do not Localize}


//  Structures returned by network data base library, taken from the
//  BSD file netdb.h.  All addresses are supplied in host order, and
//  returned in network order (suitable for use in system calls).
type
  {$EXTERNALSYM hostent}
  hostent = packed record
    h_name: PChar;                 // official name of host
    h_aliases: ^PChar;             // alias list
    h_addrtype: Smallint;          // host address type
    h_length: Smallint;            // length of address
    case Byte of
      0: (h_address_list: ^PChar);
      1: (h_addr: PChar);         // address, for backward compat
  end;
  THostEnt = hostent;
  PHostEnt = ^THostEnt;

//  It is assumed here that a network number
//  fits in 32 bits.
  {$EXTERNALSYM netent}
  netent = packed record
    n_name: PChar;                 // official name of net
    n_aliases: ^PChar;             // alias list
    n_addrtype: Smallint;          // net address type
    n_net: u_long;                 // network #
  end;
  TNetEnt = netent;
  PNetEnt = ^TNetEnt;

  {$EXTERNALSYM servent}
  servent = packed record
    s_name: PChar;                 // official service name
    s_aliases: ^PChar;             // alias list
{$IFDEF _WIN64}
    s_proto: PChar;                // protocol to use
    s_port: Smallint;              // port #
{$ELSE}
    s_port: Smallint;              // port #
    s_proto: PChar;                // protocol to use
{$ENDIF}
  end;
  TServEnt = servent;
  PServEnt = ^TServEnt;

  {$EXTERNALSYM protoent}
  protoent = packed record
    p_name: PChar;                 // official protocol name
    p_aliases: ^PChar;             // alias list
    p_proto: Smallint;             // protocol #
  end;
  TProtoEnt = protoent;
  PProtoEnt = ^TProtoEnt;

// Constants and structures defined by the internet system,
// Per RFC 790, September 1981, taken from the BSD file netinet/in.h.
const

// Protocols
  {$EXTERNALSYM IPPROTO_IP}
  IPPROTO_IP     =   0;             // dummy for IP
  {$EXTERNALSYM IPPROTO_ICMP}
  IPPROTO_ICMP   =   1;             // control message protocol
  {$EXTERNALSYM IPPROTO_IGMP}
  IPPROTO_IGMP   =   2;             // group management protocol
  {$EXTERNALSYM IPPROTO_GGP}
  IPPROTO_GGP    =   3;             // gateway^2 (deprecated)
  {$EXTERNALSYM IPPROTO_TCP}
  IPPROTO_TCP    =   6;             // TCP
  {$EXTERNALSYM IPPROTO_PUP}
  IPPROTO_PUP    =  12;             // pup
  {$EXTERNALSYM IPPROTO_UDP}
  IPPROTO_UDP    =  17;             // UDP - user datagram protocol
  {$EXTERNALSYM IPPROTO_IDP}
  IPPROTO_IDP    =  22;             // xns idp
  {$EXTERNALSYM IPPROTO_ND}
  IPPROTO_ND     =  77;             // UNOFFICIAL net disk proto

  {$EXTERNALSYM IPPROTO_IPV6}
  IPPROTO_IPV6   =  41;             // IPv6
  {$EXTERNALSYM IPPROTO_ICLFXBM}
  IPPROTO_ICLFXBM = 78;

  {$EXTERNALSYM IPPROTO_RAW}
  IPPROTO_RAW    = 255;             // raw IP packet
  {$EXTERNALSYM IPPROTO_MAX}
  IPPROTO_MAX    = 256;

// Port/socket numbers: network standard functions
  {$EXTERNALSYM IPPORT_ECHO}
  IPPORT_ECHO        =   7;
  {$EXTERNALSYM IPPORT_DISCARD}
  IPPORT_DISCARD     =   9;
  {$EXTERNALSYM IPPORT_SYSTAT}
  IPPORT_SYSTAT      =  11;
  {$EXTERNALSYM IPPORT_DAYTIME}
  IPPORT_DAYTIME     =  13;
  {$EXTERNALSYM IPPORT_NETSTAT}
  IPPORT_NETSTAT     =  15;
  {$EXTERNALSYM IPPORT_FTP}
  IPPORT_FTP         =  21;
  {$EXTERNALSYM IPPORT_TELNET}
  IPPORT_TELNET      =  23;
  {$EXTERNALSYM IPPORT_SMTP}
  IPPORT_SMTP        =  25;
  {$EXTERNALSYM IPPORT_TIMESERVER}
  IPPORT_TIMESERVER  =  37;
  {$EXTERNALSYM IPPORT_NAMESERVER}
  IPPORT_NAMESERVER  =  42;
  {$EXTERNALSYM IPPORT_WHOIS}
  IPPORT_WHOIS       =  43;
  {$EXTERNALSYM IPPORT_MTP}
  IPPORT_MTP         =  57;

// Port/socket numbers: host specific functions
  {$EXTERNALSYM IPPORT_TFTP}
  IPPORT_TFTP        =  69;
  {$EXTERNALSYM IPPORT_RJE}
  IPPORT_RJE         =  77;
  {$EXTERNALSYM IPPORT_FINGER}
  IPPORT_FINGER      =  79;
  {$EXTERNALSYM ipport_ttylink}
  IPPORT_TTYLINK     =  87;
  {$EXTERNALSYM IPPORT_SUPDUP}
  IPPORT_SUPDUP      =  95;

// UNIX TCP sockets
  {$EXTERNALSYM IPPORT_EXECSERVER}
  IPPORT_EXECSERVER  = 512;
  {$EXTERNALSYM IPPORT_LOGINSERVER}
  IPPORT_LOGINSERVER = 513;
  {$EXTERNALSYM IPPORT_CMDSERVER}
  IPPORT_CMDSERVER   = 514;
  {$EXTERNALSYM IPPORT_EFSSERVER}
  IPPORT_EFSSERVER   = 520;

// UNIX UDP sockets
  {$EXTERNALSYM IPPORT_BIFFUDP}
  IPPORT_BIFFUDP     = 512;
  {$EXTERNALSYM IPPORT_WHOSERVER}
  IPPORT_WHOSERVER   = 513;
  {$EXTERNALSYM IPPORT_ROUTESERVER}
  IPPORT_ROUTESERVER = 520;

// Ports < IPPORT_RESERVED are reserved for privileged processes (e.g. root).
  {$EXTERNALSYM ipport_reserved}
  IPPORT_RESERVED    =1024;

// Link numbers
  {$EXTERNALSYM IMPLINK_IP}
  IMPLINK_IP         = 155;
  {$EXTERNALSYM IMPLINK_LOWEXPER}
  IMPLINK_LOWEXPER   = 156;
  {$EXTERNALSYM IMPLINK_HIGHEXPER}
  IMPLINK_HIGHEXPER  = 158;

  {$EXTERNALSYM TF_DISCONNECT}
  TF_DISCONNECT      = $01;
  {$EXTERNALSYM TF_REUSE_SOCKET}
  TF_REUSE_SOCKET    = $02;
  {$EXTERNALSYM tf_write_behind}
  TF_WRITE_BEHIND    = $04;

// This is used instead of -1, since the TSocket type is unsigned.
  {$EXTERNALSYM INVALID_SOCKET}
  INVALID_SOCKET     = TSocket(not(0));
  {$EXTERNALSYM SOCKET_ERROR}
  SOCKET_ERROR       = -1;

//  The following may be used in place of the address family, socket type, or
//  protocol in a call to WSASocket to indicate that the corresponding value
//  should be taken from the supplied WSAPROTOCOL_INFO structure instead of the
//  parameter itself.
  {$EXTERNALSYM FROM_PROTOCOL_INFO}
  FROM_PROTOCOL_INFO = -1;


// Types
  {$EXTERNALSYM SOCK_STREAM}
  SOCK_STREAM     = 1;               { stream socket }
  {$EXTERNALSYM SOCK_DGRAM}
  SOCK_DGRAM      = 2;               { datagram socket }
  {$EXTERNALSYM SOCK_RAW}
  SOCK_RAW        = 3;               { raw-protocol interface }
  {$EXTERNALSYM SOCK_RDM}
  SOCK_RDM        = 4;               { reliably-delivered message }
  {$EXTERNALSYM SOCK_SEQPACKET}
  SOCK_SEQPACKET  = 5;               { sequenced packet stream }

// option flags per-socket.
  {$EXTERNALSYM SO_DEBUG}
  SO_DEBUG            = $0001;            // turn on debugging info recording
  {$EXTERNALSYM SO_ACCEPTCONN}
  SO_ACCEPTCONN       = $0002;            // socket has had listen()
  {$EXTERNALSYM SO_REUSEADDR}
  SO_REUSEADDR        = $0004;            // allow local address reuse
  {$EXTERNALSYM SO_KEEPALIVE}
  SO_KEEPALIVE        = $0008;            // keep connections alive
  {$EXTERNALSYM SO_DONTROUTE}
  SO_DONTROUTE        = $0010;            // just use interface addresses
  {$EXTERNALSYM SO_BROADCAST}
  SO_BROADCAST        = $0020;            // permit sending of broadcast msgs
  {$EXTERNALSYM SO_USELOOPBACK}
  SO_USELOOPBACK      = $0040;            // bypass hardware when possible
  {$EXTERNALSYM SO_LINGER}
  SO_LINGER           = $0080;            // linger on close if data present
  {$EXTERNALSYM SO_OOBINLINE}
  SO_OOBINLINE        = $0100;            // leave received OOB data in line

  {$EXTERNALSYM SO_DONTLINGER}
  SO_DONTLINGER       = not SO_LINGER;
  {$EXTERNALSYM SO_EXCLUSIVEADDRUSE}
  SO_EXCLUSIVEADDRUSE = not SO_REUSEADDR; // disallow local address reuse

// additional options.

  {$EXTERNALSYM SO_SNDBUF}
  SO_SNDBUF           = $1001;      // send buffer size
  {$EXTERNALSYM SO_RCVBUF}
  SO_RCVBUF           = $1002;      // receive buffer size
  {$EXTERNALSYM SO_SNDLOWAT}
  SO_SNDLOWAT         = $1003;      // send low-water mark
  {$EXTERNALSYM SO_RCVLOWAT}
  SO_RCVLOWAT         = $1004;      // receive low-water mark
  {$EXTERNALSYM SO_SNDTIMEO}
  SO_SNDTIMEO         = $1005;      // send timeout
  {$EXTERNALSYM SO_RCVTIMEO}
  SO_RCVTIMEO         = $1006;      // receive timeout
  {$EXTERNALSYM SO_ERROR}
  SO_ERROR            = $1007;      // get error status and clear
  {$EXTERNALSYM SO_TYPE}
  SO_TYPE             = $1008;      // get socket type

// options for connect and disconnect data and options.
// used only by non-tcp/ip transports such as DECNet, OSI TP4, etc.
  {$EXTERNALSYM SO_CONNDATA}
  SO_CONNDATA         = $7000;
  {$EXTERNALSYM SO_CONNOPT}
  SO_CONNOPT          = $7001;
  {$EXTERNALSYM SO_DISCDATA}
  SO_DISCDATA         = $7002;
  {$EXTERNALSYM SO_DISCOPT}
  SO_DISCOPT          = $7003;
  {$EXTERNALSYM SO_CONNDATALEN}
  SO_CONNDATALEN      = $7004;
  {$EXTERNALSYM SO_CONNOPTLEN}
  SO_CONNOPTLEN       = $7005;
  {$EXTERNALSYM SO_DISCDATALEN}
  SO_DISCDATALEN      = $7006;
  {$EXTERNALSYM SO_DISCOPTLEN}
  SO_DISCOPTLEN       = $7007;

// option for opening sockets for synchronous access.
  {$EXTERNALSYM SO_OPENTYPE}
  SO_OPENTYPE         = $7008;
  {$EXTERNALSYM SO_SYNCHRONOUS_ALERT}
  SO_SYNCHRONOUS_ALERT    = $10;
  {$EXTERNALSYM SO_SYNCHRONOUS_NONALERT}
  SO_SYNCHRONOUS_NONALERT = $20;

// other nt-specific options.
  {$EXTERNALSYM SO_MAXDG}
  SO_MAXDG                 = $7009;
  {$EXTERNALSYM SO_MAXPATHDG}
  SO_MAXPATHDG             = $700A;
  {$EXTERNALSYM SO_UPDATE_ACCEPT_CONTEXT}
  SO_UPDATE_ACCEPT_CONTEXT = $700B;
  {$EXTERNALSYM SO_CONNECT_TIME}
  SO_CONNECT_TIME          = $700C;

// tcp options.
  {$EXTERNALSYM TCP_NODELAY}
  TCP_NODELAY              = $0001;
  {$EXTERNALSYM TCP_BSDURGENT}
  TCP_BSDURGENT            = $7000;

// winsock 2 extension -- new options
  {$EXTERNALSYM SO_GROUP_ID}
  SO_GROUP_ID              = $2001; // ID of a socket group
  {$EXTERNALSYM SO_GROUP_PRIORITY}
  SO_GROUP_PRIORITY        = $2002; // the relative priority within a group
  {$EXTERNALSYM SO_MAX_MSG_SIZE}
  SO_MAX_MSG_SIZE          = $2003; // maximum message size
  {$EXTERNALSYM SO_PROTOCOL_INFOA}
  SO_PROTOCOL_INFOA        = $2004; // WSAPROTOCOL_INFOA structure
  {$EXTERNALSYM SO_PROTOCOL_INFOW}
  SO_PROTOCOL_INFOW        = $2005; // WSAPROTOCOL_INFOW structure
  {$EXTERNALSYM SO_PROTOCOL_INFO}
{$IFDEF UNICODE}
  SO_PROTOCOL_INFO         = SO_PROTOCOL_INFOW;
{$ELSE}
  SO_PROTOCOL_INFO         = SO_PROTOCOL_INFOA;
{$ENDIF}
  {$EXTERNALSYM PVD_CONFIG}
  PVD_CONFIG               = $3001; // configuration info for service provider
  {$EXTERNALSYM SO_CONDITIONAL_ACCEPT}
  SO_CONDITIONAL_ACCEPT    = $3002; // enable true conditional accept:
                                    // connection is not ack-ed to the
                                    // other side until conditional
                                    // function returns CF_ACCEPT

// Address families.
  {$EXTERNALSYM AF_UNSPEC}
  AF_UNSPEC       = 0;               // unspecified
  {$EXTERNALSYM AF_UNIX}
  AF_UNIX         = 1;               // local to host (pipes, portals)
  {$EXTERNALSYM AF_INET}
  AF_INET         = 2;               // internetwork: UDP, TCP, etc.
  {$EXTERNALSYM AF_IMPLINK}
  AF_IMPLINK      = 3;               // arpanet imp addresses
  {$EXTERNALSYM AF_PUP}
  AF_PUP          = 4;               // pup protocols: e.g. BSP
  {$EXTERNALSYM AF_CHAOS}
  AF_CHAOS        = 5;               // mit CHAOS protocols
  {$EXTERNALSYM AF_IPX}
  AF_IPX          = 6;               // ipx and SPX
  {$EXTERNALSYM AF_NS}
  AF_NS           = AF_IPX;          // xerOX NS protocols
  {$EXTERNALSYM AF_ISO}
  AF_ISO          = 7;               // iso protocols
  {$EXTERNALSYM AF_OSI}
  AF_OSI          = AF_ISO;          // osi is ISO
  {$EXTERNALSYM AF_ECMA}
  AF_ECMA         = 8;               // european computer manufacturers
  {$EXTERNALSYM AF_DATAKIT}
  AF_DATAKIT      = 9;               // datakit protocols
  {$EXTERNALSYM AF_CCITT}
  AF_CCITT        = 10;              // cciTT protocols, X.25 etc
  {$EXTERNALSYM AF_SNA}
  AF_SNA          = 11;              // ibm SNA
  {$EXTERNALSYM AF_DECNET}
  AF_DECNET       = 12;              // decnet
  {$EXTERNALSYM AF_DLI}
  AF_DLI          = 13;              // direct data link interface
  {$EXTERNALSYM AF_LAT}
  AF_LAT          = 14;              // lat
  {$EXTERNALSYM AF_HYLINK}
  AF_HYLINK       = 15;              // nsc Hyperchannel
  {$EXTERNALSYM AF_APPLETALK}
  AF_APPLETALK    = 16;              // appleTalk
  {$EXTERNALSYM AF_NETBIOS}
  AF_NETBIOS      = 17;              // netBios-style addresses
  {$EXTERNALSYM AF_VOICEVIEW}
  AF_VOICEVIEW    = 18;              // voiceView
  {$EXTERNALSYM AF_FIREFOX}
  AF_FIREFOX      = 19;              // fireFox
  {$EXTERNALSYM AF_UNKNOWN1}
  AF_UNKNOWN1     = 20;              // somebody is using this!
  {$EXTERNALSYM AF_BAN}
  AF_BAN          = 21;              // banyan
  {$EXTERNALSYM AF_ATM}
  AF_ATM          = 22;              // native ATM Services
  {$EXTERNALSYM AF_INET6}
  AF_INET6        = 23;              // internetwork Version 6
  {$EXTERNALSYM AF_CLUSTER}
  AF_CLUSTER      = 24;              // microsoft Wolfpack
  {$EXTERNALSYM AF_12844}
  AF_12844        = 25;              // ieeE 1284.4 WG AF
  {$EXTERNALSYM AF_IRDA}
  AF_IRDA         = 26;              // irdA
  {$EXTERNALSYM AF_NETDES}
  AF_NETDES       = 28;              // network Designers OSI & gateway enabled protocols
  {$EXTERNALSYM AF_TCNPROCESS}
  AF_TCNPROCESS   = 29;
  {$EXTERNALSYM AF_TCNMESSAGE}
  AF_TCNMESSAGE   = 30;
  {$EXTERNALSYM AF_ICLFXBM}
  AF_ICLFXBM      = 31;

  {$EXTERNALSYM AF_MAX}
  AF_MAX          = 32;


// protocol families, same as address families for now.

  {$EXTERNALSYM PF_UNSPEC}
  PF_UNSPEC       = AF_UNSPEC;
  {$EXTERNALSYM PF_UNIX}
  PF_UNIX         = AF_UNIX;
  {$EXTERNALSYM PF_INET}
  PF_INET         = AF_INET;
  {$EXTERNALSYM PF_IMPLINK}
  PF_IMPLINK      = AF_IMPLINK;
  {$EXTERNALSYM PF_PUP}
  PF_PUP          = AF_PUP;
  {$EXTERNALSYM PF_CHAOS}
  PF_CHAOS        = AF_CHAOS;
  {$EXTERNALSYM PF_NS}
  PF_NS           = AF_NS;
  {$EXTERNALSYM PF_IPX}
  PF_IPX          = AF_IPX;
  {$EXTERNALSYM PF_ISO}
  PF_ISO          = AF_ISO;
  {$EXTERNALSYM PF_OSI}
  PF_OSI          = AF_OSI;
  {$EXTERNALSYM PF_ECMA}
  PF_ECMA         = AF_ECMA;
  {$EXTERNALSYM PF_DATAKIT}
  PF_DATAKIT      = AF_DATAKIT;
  {$EXTERNALSYM PF_CCITT}
  PF_CCITT        = AF_CCITT;
  {$EXTERNALSYM PF_SNA}
  PF_SNA          = AF_SNA;
  {$EXTERNALSYM PF_DECNET}
  PF_DECNET       = AF_DECNET;
  {$EXTERNALSYM PF_DLI}
  PF_DLI          = AF_DLI;
  {$EXTERNALSYM PF_LAT}
  PF_LAT          = AF_LAT;
  {$EXTERNALSYM PF_HYLINK}
  PF_HYLINK       = AF_HYLINK;
  {$EXTERNALSYM PF_APPLETALK}
  PF_APPLETALK   = AF_APPLETALK;
  {$EXTERNALSYM PF_VOICEVIEW}
  PF_VOICEVIEW    = AF_VOICEVIEW;
  {$EXTERNALSYM PF_FIREFOX}
  PF_FIREFOX      = AF_FIREFOX;
  {$EXTERNALSYM PF_UNKNOWN1}
  PF_UNKNOWN1     = AF_UNKNOWN1;
  {$EXTERNALSYM pf_ban}
  PF_BAN          = AF_BAN;
  {$EXTERNALSYM PF_ATM}
  PF_ATM          = AF_ATM;
  {$EXTERNALSYM PF_INET6}
  PF_INET6        = AF_INET6;

  {$EXTERNALSYM PF_MAX}
  PF_MAX          = AF_MAX;

type

  {$NODEFINE SunB}
  SunB = packed record
    s_b1, s_b2, s_b3, s_b4: u_char;
  end;

  {$NODEFINE SunW}
  SunW = packed record
    s_w1, s_w2: u_short;
  end;

  {$EXTERNALSYM in_addr}
  in_addr = packed record
    case integer of
      0: (S_un_b: SunB);
      1: (S_un_w: SunW);
      2: (S_addr: u_long);
  end;
  TInAddr = in_addr;
  PInAddr = ^TInAddr;

  // Structure used by kernel to store most addresses.

  {$EXTERNALSYM sockaddr_in}
  sockaddr_in = packed record
    case Integer of
      0: (sin_family : u_short;
          sin_port   : u_short;
          sin_addr   : TInAddr;
          sin_zero   : array[0..7] of Char);
      1: (sa_family  : u_short;
          sa_data    : array[0..13] of Char)
  end;
  TSockAddrIn = sockaddr_in;
  PSockAddrIn = ^TSockAddrIn;

  TSockAddr   = TSockAddrIn;
  {$EXTERNALSYM SOCKADDR}
  SOCKADDR    = TSockAddr;
  {$EXTERNALSYM PSOCKADDR}
  PSOCKADDR   = ^TSockAddr;

  // Structure used by kernel to pass protocol information in raw sockets.
  {$EXTERNALSYM sockproto}
  sockproto = packed record
    sp_family   : u_short;
    sp_protocol : u_short;
  end;
  TSockProto = sockproto;
  PSockProto = ^TSockProto;

// Structure used for manipulating linger option.
  {$EXTERNALSYM linger}
  linger = packed record
    l_onoff: u_short;
    l_linger: u_short;
  end;
  TLinger = linger;
  {$EXTERNALSYM PLINGER}
  PLINGER = ^TLinger;
  {$EXTERNALSYM LPLINGER}
  LPLINGER = PLINGER;

const
  {$EXTERNALSYM INADDR_ANY}
  INADDR_ANY       = $00000000;
  {$EXTERNALSYM INADDR_LOOPBACK}
  INADDR_LOOPBACK  = $7F000001;
  {$EXTERNALSYM INADDR_BROADCAST}
  INADDR_BROADCAST = $FFFFFFFF;
  {$EXTERNALSYM INADDR_NONE}
  INADDR_NONE      = $FFFFFFFF;

  {$EXTERNALSYM ADDR_ANY}
  ADDR_ANY         = INADDR_ANY;

  {$EXTERNALSYM SOL_SOCKET}
  SOL_SOCKET       = $FFFF;          // options for socket level

  {$EXTERNALSYM MSG_OOB}
  MSG_OOB          = $1;             // process out-of-band data
  {$EXTERNALSYM MSG_PEEK}
  MSG_PEEK         = $2;             // peek at incoming message
  {$EXTERNALSYM MSG_DONTROUTE}
  MSG_DONTROUTE    = $4;             // send without using routing tables

  {$EXTERNALSYM MSG_PARTIAL}
  MSG_PARTIAL      = $8000;          // partial send or recv for message xport

// WinSock 2 extension -- new flags for WSASend(), WSASendTo(), WSARecv() and WSARecvFrom()
  {$EXTERNALSYM MSG_INTERRUPT}
  MSG_INTERRUPT    = $10;    // send/recv in the interrupt context
  {$EXTERNALSYM MSG_MAXIOVLEN}
  MSG_MAXIOVLEN    = 16;


// Define constant based on rfc883, used by gethostbyxxxx() calls.

  {$EXTERNALSYM MAXGETHOSTSTRUCT}
  MAXGETHOSTSTRUCT = 1024;

// Maximum queue length specifiable by listen.
  {$EXTERNALSYM SOMAXCONN}
  SOMAXCONN        = $7FFFFFFF;

// WinSock 2 extension -- bit values and indices for FD_XXX network events
  {$EXTERNALSYM FD_READ_BIT}
  FD_READ_BIT                     = 0;
  {$EXTERNALSYM FD_WRITE_BIT}
  FD_WRITE_BIT                    = 1;
  {$EXTERNALSYM FD_OOB_BIT}
  FD_OOB_BIT                      = 2;
  {$EXTERNALSYM FD_ACCEPT_BIT}
  FD_ACCEPT_BIT                   = 3;
  {$EXTERNALSYM FD_CONNECT_BIT}
  FD_CONNECT_BIT                  = 4;
  {$EXTERNALSYM FD_CLOSE_BIT}
  FD_CLOSE_BIT                    = 5;
  {$EXTERNALSYM fd_qos_bit}
  FD_QOS_BIT                      = 6;
  {$EXTERNALSYM FD_GROUP_QOS_BIT}
  FD_GROUP_QOS_BIT                = 7;
  {$EXTERNALSYM FD_ROUTING_INTERFACE_CHANGE_BIT}
  FD_ROUTING_INTERFACE_CHANGE_BIT = 8;
  {$EXTERNALSYM FD_ADDRESS_LIST_CHANGE_BIT}
  FD_ADDRESS_LIST_CHANGE_BIT      = 9;

  {$EXTERNALSYM FD_MAX_EVENTS}
  FD_MAX_EVENTS    = 10;

  {$EXTERNALSYM FD_READ}
  FD_READ       = (1 shl FD_READ_BIT);
  {$EXTERNALSYM FD_WRITE}
  FD_WRITE      = (1 shl FD_WRITE_BIT);
  {$EXTERNALSYM FD_OOB}
  FD_OOB        = (1 shl FD_OOB_BIT);
  {$EXTERNALSYM FD_ACCEPT}
  FD_ACCEPT     = (1 shl FD_ACCEPT_BIT);
  {$EXTERNALSYM FD_CONNECT}
  FD_CONNECT    = (1 shl FD_CONNECT_BIT);
  {$EXTERNALSYM FD_CLOSE}
  FD_CLOSE      = (1 shl FD_CLOSE_BIT);
  {$EXTERNALSYM FD_QOS}
  FD_QOS        = (1 shl FD_QOS_BIT);
  {$EXTERNALSYM FD_GROUP_QOS}
  FD_GROUP_QOS  = (1 shl FD_GROUP_QOS_BIT);
  {$EXTERNALSYM FD_ROUTING_INTERFACE_CHANGE}
  FD_ROUTING_INTERFACE_CHANGE = (1 shl FD_ROUTING_INTERFACE_CHANGE_BIT);
  {$EXTERNALSYM FD_ADDRESS_LIST_CHANGE}
  FD_ADDRESS_LIST_CHANGE      = (1 shl FD_ADDRESS_LIST_CHANGE_BIT);

  {$EXTERNALSYM FD_ALL_EVENTS}
  FD_ALL_EVENTS = (1 shl FD_MAX_EVENTS) - 1;

// All Windows Sockets error constants are biased by WSABASEERR from the "normal"

  {$EXTERNALSYM WSABASEERR}
  WSABASEERR              = 10000;

// Windows Sockets definitions of regular Microsoft C error constants

  {$EXTERNALSYM WSAEINTR}
  WSAEINTR                = WSABASEERR+  4;
  {$EXTERNALSYM WSAEBADF}
  WSAEBADF                = WSABASEERR+  9;
  {$EXTERNALSYM WSAEACCES}
  WSAEACCES               = WSABASEERR+ 13;
  {$EXTERNALSYM WSAEFAULT}
  WSAEFAULT               = WSABASEERR+ 14;
  {$EXTERNALSYM WSAEINVAL}
  WSAEINVAL               = WSABASEERR+ 22;
  {$EXTERNALSYM wsaemfile}
  WSAEMFILE               = WSABASEERR+ 24;

// Windows Sockets definitions of regular Berkeley error constants

  {$EXTERNALSYM WSAEWOULDBLOCK}
  WSAEWOULDBLOCK          = WSABASEERR+ 35;
  {$EXTERNALSYM WSAEINPROGRESS}
  WSAEINPROGRESS          = WSABASEERR+ 36;
  {$EXTERNALSYM WSAEALREADY}
  WSAEALREADY             = WSABASEERR+ 37;
  {$EXTERNALSYM WSAENOTSOCK}
  WSAENOTSOCK             = WSABASEERR+ 38;
  {$EXTERNALSYM WSAEDESTADDRREQ}
  WSAEDESTADDRREQ         = WSABASEERR+ 39;
  {$EXTERNALSYM WSAEMSGSIZE}
  WSAEMSGSIZE             = WSABASEERR+ 40;
  {$EXTERNALSYM WSAEPROTOTYPE}
  WSAEPROTOTYPE           = WSABASEERR+ 41;
  {$EXTERNALSYM WSAENOPROTOOPT}
  WSAENOPROTOOPT          = WSABASEERR+ 42;
  {$EXTERNALSYM WSAEPROTONOSUPPORT}
  WSAEPROTONOSUPPORT      = WSABASEERR+ 43;
  {$EXTERNALSYM WSAESOCKTNOSUPPORT}
  WSAESOCKTNOSUPPORT      = WSABASEERR+ 44;
  {$EXTERNALSYM WSAEOPNOTSUPP}
  WSAEOPNOTSUPP           = WSABASEERR+ 45;
  {$EXTERNALSYM WSAEPFNOSUPPORT}
  WSAEPFNOSUPPORT         = WSABASEERR+ 46;
  {$EXTERNALSYM WSAEAFNOSUPPORT}
  WSAEAFNOSUPPORT         = WSABASEERR+ 47;
  {$EXTERNALSYM WSAEADDRINUSE}
  WSAEADDRINUSE           = WSABASEERR+ 48;
  {$EXTERNALSYM WSAEADDRNOTAVAIL}
  WSAEADDRNOTAVAIL        = WSABASEERR+ 49;
  {$EXTERNALSYM WSAENETDOWN}
  WSAENETDOWN             = WSABASEERR+ 50;
  {$EXTERNALSYM WSAENETUNREACH}
  WSAENETUNREACH          = WSABASEERR+ 51;
  {$EXTERNALSYM WSAENETRESET}
  WSAENETRESET            = WSABASEERR+ 52;
  {$EXTERNALSYM WSAECONNABORTED}
  WSAECONNABORTED         = WSABASEERR+ 53;
  {$EXTERNALSYM wsaeconnreset}
  WSAECONNRESET           = WSABASEERR+ 54;
  {$EXTERNALSYM WSAENOBUFS}
  WSAENOBUFS              = WSABASEERR+ 55;
  {$EXTERNALSYM WSAEISCONN}
  WSAEISCONN              = WSABASEERR+ 56;
  {$EXTERNALSYM WSAENOTCONN}
  WSAENOTCONN             = WSABASEERR+ 57;
  {$EXTERNALSYM WSAESHUTDOWN}
  WSAESHUTDOWN            = WSABASEERR+ 58;
  {$EXTERNALSYM WSAETOOMANYREFS}
  WSAETOOMANYREFS         = WSABASEERR+ 59;
  {$EXTERNALSYM WSAETIMEDOUT}
  WSAETIMEDOUT            = WSABASEERR+ 60;
  {$EXTERNALSYM WSAECONNREFUSED}
  WSAECONNREFUSED         = WSABASEERR+ 61;
  {$EXTERNALSYM WSAELOOP}
  WSAELOOP                = WSABASEERR+ 62;
  {$EXTERNALSYM WSAENAMETOOLONG}
  WSAENAMETOOLONG         = WSABASEERR+ 63;
  {$EXTERNALSYM WSAEHOSTDOWN}
  WSAEHOSTDOWN            = WSABASEERR+ 64;
  {$EXTERNALSYM WSAEHOSTUNREACH}
  WSAEHOSTUNREACH         = WSABASEERR+ 65;
  {$EXTERNALSYM wsaenotempty}
  WSAENOTEMPTY            = WSABASEERR+ 66;
  {$EXTERNALSYM WSAEPROCLIM}
  WSAEPROCLIM             = WSABASEERR+ 67;
  {$EXTERNALSYM WSAEUSERS}
  WSAEUSERS               = WSABASEERR+ 68;
  {$EXTERNALSYM WSAEDQUOT}
  WSAEDQUOT               = WSABASEERR+ 69;
  {$EXTERNALSYM WSAESTALE}
  WSAESTALE               = WSABASEERR+ 70;
  {$EXTERNALSYM WSAEREMOTE}
  WSAEREMOTE              = WSABASEERR+ 71;

// Extended Windows Sockets error constant definitions

  {$EXTERNALSYM WSASYSNOTREADY}
  WSASYSNOTREADY          = WSABASEERR+ 91;
  {$EXTERNALSYM WSAVERNOTSUPPORTED}
  WSAVERNOTSUPPORTED      = WSABASEERR+ 92;
  {$EXTERNALSYM WSANOTINITIALISED}
  WSANOTINITIALISED       = WSABASEERR+ 93;
  {$EXTERNALSYM WSAEDISCON}
  WSAEDISCON              = WSABASEERR+101;
  {$EXTERNALSYM WSAENOMORE}
  WSAENOMORE              = WSABASEERR+102;
  {$EXTERNALSYM wsaecancelled}
  WSAECANCELLED           = WSABASEERR+103;
  {$EXTERNALSYM WSAEINVALIDPROCTABLE}
  WSAEINVALIDPROCTABLE    = WSABASEERR+104;
  {$EXTERNALSYM WSAEINVALIDPROVIDER}
  WSAEINVALIDPROVIDER     = WSABASEERR+105;
  {$EXTERNALSYM WSAEPROVIDERFAILEDINIT}
  WSAEPROVIDERFAILEDINIT  = WSABASEERR+106;
  {$EXTERNALSYM WSASYSCALLFAILURE}
  WSASYSCALLFAILURE       = WSABASEERR+107;
  {$EXTERNALSYM WSASERVICE_NOT_FOUND}
  WSASERVICE_NOT_FOUND    = WSABASEERR+108;
  {$EXTERNALSYM WSATYPE_NOT_FOUND}
  WSATYPE_NOT_FOUND       = WSABASEERR+109;
  {$EXTERNALSYM wsa_e_no_more}
  WSA_E_NO_MORE           = WSABASEERR+110;
  {$EXTERNALSYM WSA_E_CANCELLED}
  WSA_E_CANCELLED         = WSABASEERR+111;
  {$EXTERNALSYM WSAEREFUSED}
  WSAEREFUSED             = WSABASEERR+112;


{ Error return codes from gethostbyname() and gethostbyaddr()
  (when using the resolver). Note that these errors are
  retrieved via WSAGetLastError() and must therefore follow
  the rules for avoiding clashes with error numbers from
  specific implementations or language run-time systems.
  For this reason the codes are based at WSABASEERR+1001.
  Note also that [WSA]NO_ADDRESS is defined only for
  compatibility purposes. }

// Authoritative Answer: Host not found
  {$EXTERNALSYM WSAHOST_NOT_FOUND}
  WSAHOST_NOT_FOUND        = WSABASEERR+1001;
  {$EXTERNALSYM HOST_NOT_FOUND}
  HOST_NOT_FOUND           = WSAHOST_NOT_FOUND;

// Non-Authoritative: Host not found, or SERVERFAIL
  {$EXTERNALSYM WSATRY_AGAIN}
  WSATRY_AGAIN             = WSABASEERR+1002;
  {$EXTERNALSYM TRY_AGAIN}
  TRY_AGAIN                = WSATRY_AGAIN;

// Non recoverable errors, FORMERR, REFUSED, NOTIMP
  {$EXTERNALSYM WSANO_RECOVERY}
  WSANO_RECOVERY           = WSABASEERR+1003;
  {$EXTERNALSYM NO_RECOVERY}
  NO_RECOVERY              = WSANO_RECOVERY;

// Valid name, no data record of requested type
  {$EXTERNALSYM WSANO_DATA}
  WSANO_DATA               = WSABASEERR+1004;
  {$EXTERNALSYM no_data}
  NO_DATA                  = WSANO_DATA;

// no address, look for MX record
  {$EXTERNALSYM WSANO_ADDRESS}
  WSANO_ADDRESS            = WSANO_DATA;
  {$EXTERNALSYM no_address}
  NO_ADDRESS               = WSANO_ADDRESS;

// Define QOS related error return codes

  {$EXTERNALSYM WSA_QOS_RECEIVERS}
  WSA_QOS_RECEIVERS          = WSABASEERR+1005; // at least one reserve has arrived
  {$EXTERNALSYM WSA_QOS_SENDERS}
  WSA_QOS_SENDERS            = WSABASEERR+1006; // at least one path has arrived
  {$EXTERNALSYM WSA_QOS_NO_SENDERS}
  WSA_QOS_NO_SENDERS         = WSABASEERR+1007; // there are no senders
  {$EXTERNALSYM WSA_QOS_NO_RECEIVERS}
  WSA_QOS_NO_RECEIVERS       = WSABASEERR+1008; // there are no receivers
  {$EXTERNALSYM WSA_QOS_REQUEST_CONFIRMED}
  WSA_QOS_REQUEST_CONFIRMED  = WSABASEERR+1009; // reserve has been confirmed
  {$EXTERNALSYM WSA_QOS_ADMISSION_FAILURE}
  WSA_QOS_ADMISSION_FAILURE  = WSABASEERR+1010; // error due to lack of resources
  {$EXTERNALSYM WSA_QOS_POLICY_FAILURE}
  WSA_QOS_POLICY_FAILURE     = WSABASEERR+1011; // rejected for administrative reasons - bad credentials
  {$EXTERNALSYM WSA_QOS_BAD_STYLE}
  WSA_QOS_BAD_STYLE          = WSABASEERR+1012; // unknown or conflicting style
  {$EXTERNALSYM WSA_QOS_BAD_OBJECT}
  WSA_QOS_BAD_OBJECT         = WSABASEERR+1013; // problem with some part of the filterspec or providerspecific buffer in general
  {$EXTERNALSYM WSA_QOS_TRAFFIC_CTRL_ERROR}
  WSA_QOS_TRAFFIC_CTRL_ERROR = WSABASEERR+1014; // problem with some part of the flowspec
  {$EXTERNALSYM WSA_QOS_GENERIC_ERROR}
  WSA_QOS_GENERIC_ERROR      = WSABASEERR+1015; // general error
  {$EXTERNALSYM WSA_QOS_ESERVICETYPE}
  WSA_QOS_ESERVICETYPE       = WSABASEERR+1016; // invalid service type in flowspec
  {$EXTERNALSYM WSA_QOS_EFLOWSPEC}
  WSA_QOS_EFLOWSPEC          = WSABASEERR+1017; // invalid flowspec
  {$EXTERNALSYM WSA_QOS_EPROVSPECBUF}
  WSA_QOS_EPROVSPECBUF       = WSABASEERR+1018; // invalid provider specific buffer
  {$EXTERNALSYM WSA_QOS_EFILTERSTYLE}
  WSA_QOS_EFILTERSTYLE       = WSABASEERR+1019; // invalid filter style
  {$EXTERNALSYM WSA_QOS_EFILTERTYPE}
  WSA_QOS_EFILTERTYPE        = WSABASEERR+1020; // invalid filter type
  {$EXTERNALSYM WSA_QOS_EFILTERCOUNT}
  WSA_QOS_EFILTERCOUNT       = WSABASEERR+1021; // incorrect number of filters
  {$EXTERNALSYM WSA_QOS_EOBJLENGTH}
  WSA_QOS_EOBJLENGTH         = WSABASEERR+1022; // invalid object length
  {$EXTERNALSYM WSA_QOS_EFLOWCOUNT}
  WSA_QOS_EFLOWCOUNT         = WSABASEERR+1023; // incorrect number of flows
  {$EXTERNALSYM WSA_QOS_EUNKOWNPSOBJ}
  WSA_QOS_EUNKOWNPSOBJ       = WSABASEERR+1024; // unknown object in provider specific buffer
  {$EXTERNALSYM WSA_QOS_EPOLICYOBJ}
  WSA_QOS_EPOLICYOBJ         = WSABASEERR+1025; // invalid policy object in provider specific buffer
  {$EXTERNALSYM WSA_QOS_EFLOWDESC}
  WSA_QOS_EFLOWDESC          = WSABASEERR+1026; // invalid flow descriptor in the list
  {$EXTERNALSYM WSA_QOS_EPSFLOWSPEC}
  WSA_QOS_EPSFLOWSPEC        = WSABASEERR+1027; // inconsistent flow spec in provider specific buffer
  {$EXTERNALSYM WSA_QOS_EPSFILTERSPEC}
  WSA_QOS_EPSFILTERSPEC      = WSABASEERR+1028; // invalid filter spec in provider specific buffer
  {$EXTERNALSYM WSA_QOS_ESDMODEOBJ}
  WSA_QOS_ESDMODEOBJ         = WSABASEERR+1029; // invalid shape discard mode object in provider specific buffer
  {$EXTERNALSYM WSA_QOS_ESHAPERATEOBJ}
  WSA_QOS_ESHAPERATEOBJ      = WSABASEERR+1030; // invalid shaping rate object in provider specific buffer
  {$EXTERNALSYM WSA_QOS_RESERVED_PETYPE}
  WSA_QOS_RESERVED_PETYPE    = WSABASEERR+1031; // reserved policy element in provider specific buffer


{ WinSock 2 extension -- new error codes and type definition }
  {$EXTERNALSYM WSA_IO_PENDING}
  WSA_IO_PENDING          = ERROR_IO_PENDING;
  {$EXTERNALSYM WSA_IO_INCOMPLETE}
  WSA_IO_INCOMPLETE       = ERROR_IO_INCOMPLETE;
  {$EXTERNALSYM WSA_INVALID_HANDLE}
  WSA_INVALID_HANDLE      = ERROR_INVALID_HANDLE;
  {$EXTERNALSYM WSA_INVALID_PARAMETER}
  WSA_INVALID_PARAMETER   = ERROR_INVALID_PARAMETER;
  {$EXTERNALSYM WSA_NOT_ENOUGH_MEMORY}
  WSA_NOT_ENOUGH_MEMORY   = ERROR_NOT_ENOUGH_MEMORY;
  {$EXTERNALSYM WSA_OPERATION_ABORTED}
  WSA_OPERATION_ABORTED   = ERROR_OPERATION_ABORTED;
  {$EXTERNALSYM WSA_INVALID_EVENT}
  WSA_INVALID_EVENT       = WSAEVENT(nil);
  {$EXTERNALSYM WSA_MAXIMUM_WAIT_EVENTS}
  WSA_MAXIMUM_WAIT_EVENTS = MAXIMUM_WAIT_OBJECTS;
  {$EXTERNALSYM WSA_WAIT_FAILED}
  WSA_WAIT_FAILED         = $FFFFFFFF;
  {$EXTERNALSYM WSA_WAIT_EVENT_0}
  WSA_WAIT_EVENT_0        = WAIT_OBJECT_0;
  {$EXTERNALSYM WSA_WAIT_IO_COMPLETION}
  WSA_WAIT_IO_COMPLETION  = WAIT_IO_COMPLETION;
  {$EXTERNALSYM WSA_WAIT_TIMEOUT}
  WSA_WAIT_TIMEOUT        = WAIT_TIMEOUT;
  {$EXTERNALSYM WSA_INFINITE}
  WSA_INFINITE            = INFINITE;

{ Windows Sockets errors redefined as regular Berkeley error constants.
  These are commented out in Windows NT to avoid conflicts with errno.h.
  Use the WSA constants instead. }

  {$EXTERNALSYM EWOULDBLOCK}
  EWOULDBLOCK        =  WSAEWOULDBLOCK;
  {$EXTERNALSYM EINPROGRESS}
  EINPROGRESS        =  WSAEINPROGRESS;
  {$EXTERNALSYM EALREADY}
  EALREADY           =  WSAEALREADY;
  {$EXTERNALSYM ENOTSOCK}
  ENOTSOCK           =  WSAENOTSOCK;
  {$EXTERNALSYM EDESTADDRREQ}
  EDESTADDRREQ       =  WSAEDESTADDRREQ;
  {$EXTERNALSYM EMSGSIZE}
  EMSGSIZE           =  WSAEMSGSIZE;
  {$EXTERNALSYM EPROTOTYPE}
  EPROTOTYPE         =  WSAEPROTOTYPE;
  {$EXTERNALSYM ENOPROTOOPT}
  ENOPROTOOPT        =  WSAENOPROTOOPT;
  {$EXTERNALSYM EPROTONOSUPPORT}
  EPROTONOSUPPORT    =  WSAEPROTONOSUPPORT;
  {$EXTERNALSYM ESOCKTNOSUPPORT}
  ESOCKTNOSUPPORT    =  WSAESOCKTNOSUPPORT;
  {$EXTERNALSYM EOPNOTSUPP}
  EOPNOTSUPP         =  WSAEOPNOTSUPP;
  {$EXTERNALSYM EPFNOSUPPORT}
  EPFNOSUPPORT       =  WSAEPFNOSUPPORT;
  {$EXTERNALSYM EAFNOSUPPORT}
  EAFNOSUPPORT       =  WSAEAFNOSUPPORT;
  {$EXTERNALSYM EADDRINUSE}
  EADDRINUSE         =  WSAEADDRINUSE;
  {$EXTERNALSYM EADDRNOTAVAIL}
  EADDRNOTAVAIL      =  WSAEADDRNOTAVAIL;
  {$EXTERNALSYM ENETDOWN}
  ENETDOWN           =  WSAENETDOWN;
  {$EXTERNALSYM ENETUNREACH}
  ENETUNREACH        =  WSAENETUNREACH;
  {$EXTERNALSYM ENETRESET}
  ENETRESET          =  WSAENETRESET;
  {$EXTERNALSYM ECONNABORTED}
  ECONNABORTED       =  WSAECONNABORTED;
  {$EXTERNALSYM ECONNRESET}
  ECONNRESET         =  WSAECONNRESET;
  {$EXTERNALSYM ENOBUFS}
  ENOBUFS            =  WSAENOBUFS;
  {$EXTERNALSYM EISCONN}
  EISCONN            =  WSAEISCONN;
  {$EXTERNALSYM ENOTCONN}
  ENOTCONN           =  WSAENOTCONN;
  {$EXTERNALSYM ESHUTDOWN}
  ESHUTDOWN          =  WSAESHUTDOWN;
  {$EXTERNALSYM ETOOMANYREFS}
  ETOOMANYREFS       =  WSAETOOMANYREFS;
  {$EXTERNALSYM ETIMEDOUT}
  ETIMEDOUT          =  WSAETIMEDOUT;
  {$EXTERNALSYM ECONNREFUSED}
  ECONNREFUSED       =  WSAECONNREFUSED;
  {$EXTERNALSYM ELOOP}
  ELOOP              =  WSAELOOP;
  {$EXTERNALSYM ENAMETOOLONG}
  ENAMETOOLONG       =  WSAENAMETOOLONG;
  {$EXTERNALSYM EHOSTDOWN}
  EHOSTDOWN          =  WSAEHOSTDOWN;
  {$EXTERNALSYM EHOSTUNREACH}
  EHOSTUNREACH       =  WSAEHOSTUNREACH;
  {$EXTERNALSYM ENOTEMPTY}
  ENOTEMPTY          =  WSAENOTEMPTY;
  {$EXTERNALSYM EPROCLIM}
  EPROCLIM           =  WSAEPROCLIM;
  {$EXTERNALSYM EUSERS}
  EUSERS             =  WSAEUSERS;
  {$EXTERNALSYM EDQUOT}
  EDQUOT             =  WSAEDQUOT;
  {$EXTERNALSYM ESTALE}
  ESTALE             =  WSAESTALE;
  {$EXTERNALSYM EREMOTE}
  EREMOTE            =  WSAEREMOTE;

  {$EXTERNALSYM WSADESCRIPTION_LEN}
  WSADESCRIPTION_LEN     =   256;
  {$EXTERNALSYM WSASYS_STATUS_LEN}
  WSASYS_STATUS_LEN      =   128;

type
  {$EXTERNALSYM WSADATA}
  WSADATA = packed record
    wVersion       : Word;
    wHighVersion   : Word;
{$IFDEF _WIN64}
    iMaxSockets    : Word;
    iMaxUdpDg      : Word;
    lpVendorInfo   : PChar;
    szDescription  : Array[0..wsadescription_len] of Char;
    szSystemStatus : Array[0..wsasys_status_len] of Char;
{$ELSE}
    szDescription  : Array[0..wsadescription_len] of Char;
    szSystemStatus : Array[0..wsasys_status_len] of Char;
    iMaxSockets    : Word;
    iMaxUdpDg      : Word;
    lpVendorInfo   : PChar;
{$ENDIF}
  end;
  TWSAData = WSADATA;
  PWSAData = ^TWSAData;
  {$EXTERNALSYM LPWSADATA}
  LPWSADATA = PWSAData;

{ wsaoverlapped = Record
    Internal: LongInt;
    InternalHigh: LongInt;
    Offset: LongInt;
    OffsetHigh: LongInt;
	hEvent: wsaevent;
  end;}
  {$EXTERNALSYM WSAOVERLAPPED}
  WSAOVERLAPPED   = TOverlapped;
  TWSAOverlapped  = WSAOVERLAPPED;
  PWSAOverlapped  = ^TWSAOverlapped;
  {$EXTERNALSYM WSAOVERLAPPED}
  LPWSAOVERLAPPED = PWSAOverlapped;

{ WinSock 2 extension -- WSABUF and QOS struct, include qos.h }
{ to pull in FLOWSPEC and related definitions }


  {$EXTERNALSYM WSABUF}
  WSABUF = packed record
    len: u_long;{ the length of the buffer }
    buf: PChar; { the pointer to the buffer }
  end;
  TWSABuf = WSABUF;
  PWSABuf = ^TWSABuf;
  {$EXTERNALSYM LPWSABUF}
  LPWSABUF = PWSABUF;

  {$EXTERNALSYM SERVICETYPE}
  SERVICETYPE = LongInt;
  TServiceType = SERVICETYPE;

  {$EXTERNALSYM FLOWSPEC}
  FLOWSPEC = packed record
    TokenRate,               // In Bytes/sec
    TokenBucketSize,         // In Bytes
    PeakBandwidth,           // In Bytes/sec
    Latency,                 // In microseconds
    DelayVariation : LongInt;// In microseconds
    ServiceType : TServiceType;
    MaxSduSize, MinimumPolicedSize : LongInt;// In Bytes
  end;
  TFlowSpec = FLOWSPEC;
  {$EXTERNALSYM PFLOWSPEC}
  PFLOWSPEC = ^TFlowSpec;
  {$EXTERNALSYM LPFLOWSPEC}
  LPFLOWSPEC = PFLOWSPEC;

  {$EXTERNALSYM QOS}
  QOS = packed record
    SendingFlowspec: TFlowSpec; { the flow spec for data sending }
    ReceivingFlowspec: TFlowSpec; { the flow spec for data receiving }
    ProviderSpecific: TWSABuf; { additional provider specific stuff }
  end;
  TQualityOfService = QOS;
  PQOS = ^QOS;
  {$EXTERNALSYM LPQOS}
  LPQOS = PQOS;

const
  {$EXTERNALSYM SERVICETYPE_NOTRAFFIC}
  SERVICETYPE_NOTRAFFIC             =  $00000000;  // No data in this direction
  {$EXTERNALSYM SERVICETYPE_BESTEFFORT}
  SERVICETYPE_BESTEFFORT            =  $00000001;  // Best Effort
  {$EXTERNALSYM SERVICETYPE_CONTROLLEDLOAD}
  SERVICETYPE_CONTROLLEDLOAD        =  $00000002;  // Controlled Load
  {$EXTERNALSYM SERVICETYPE_GUARANTEED}
  SERVICETYPE_GUARANTEED            =  $00000003;  // Guaranteed
  {$EXTERNALSYM SERVICETYPE_NETWORK_UNAVAILABLE}
  SERVICETYPE_NETWORK_UNAVAILABLE   =  $00000004;  // Used to notify change to user
  {$EXTERNALSYM SERVICETYPE_GENERAL_INFORMATION}
  SERVICETYPE_GENERAL_INFORMATION   =  $00000005;  // corresponds to "General Parameters" defined by IntServ
  {$EXTERNALSYM SERVICETYPE_NOCHANGE}
  SERVICETYPE_NOCHANGE              =  $00000006;  // used to indicate that the flow spec contains no change from any previous one
// to turn on immediate traffic control, OR this flag with the ServiceType field in the FLOWSPEC
  {$EXTERNALSYM SERVICE_IMMEDIATE_TRAFFIC_CONTROL}
  SERVICE_IMMEDIATE_TRAFFIC_CONTROL =  $80000000;

//  WinSock 2 extension -- manifest constants for return values of the condition function
  {$EXTERNALSYM CF_ACCEPT}
  CF_ACCEPT = $0000;
  {$EXTERNALSYM CF_REJECT}
  CF_REJECT = $0001;
  {$EXTERNALSYM CF_DEFER}
  CF_DEFER  = $0002;

//  WinSock 2 extension -- manifest constants for shutdown()
  {$EXTERNALSYM SD_RECEIVE}
  SD_RECEIVE = $00;
  {$EXTERNALSYM SD_SEND}
  SD_SEND    = $01;
  {$EXTERNALSYM SD_BOTH}
  SD_BOTH    = $02;

//  WinSock 2 extension -- data type and manifest constants for socket groups
  {$EXTERNALSYM SG_UNCONSTRAINED_GROUP}
  SG_UNCONSTRAINED_GROUP = $01;
  {$EXTERNALSYM SG_CONSTRAINED_GROUP}
  SG_CONSTRAINED_GROUP   = $02;

type
  {$EXTERNALSYM GROUP}
  GROUP = DWORD;

//  WinSock 2 extension -- data type for WSAEnumNetworkEvents()
  {$EXTERNALSYM wsanetworkevents}
  WSANETWORKEVENTS = record
    lNetworkEvents: LongInt;
    iErrorCode: Array[0..FD_MAX_EVENTS-1] of Integer;
  end;
  TWSANetworkEvents = WSANETWORKEVENTS;
  PWSANetworkEvents = ^TWSANetworkEvents;
  {$EXTERNALSYM LPWSANETWORKEVENTS}
  LPWSANETWORKEVENTS = PWSANetworkEvents;

//TransmitFile types used for the TransmitFile API function in WinNT/2000/XP

  {$NODEFINE _TRANSMIT_FILE_BUFFERS}
  _TRANSMIT_FILE_BUFFERS = record
      Head: Pointer;
      HeadLength: DWORD;
      Tail: Pointer;
      TailLength: DWORD;
  end;
  {$NODEFINE TTransmitFileBuffers}
  TTransmitFileBuffers = _TRANSMIT_FILE_BUFFERS;
  {$NODEFINE PTransmitFileBuffers}
  PTransmitFileBuffers = ^TTransmitFileBuffers;
  {$NODEFINE TRANSMIT_FILE_BUFFERS}
  TRANSMIT_FILE_BUFFERS = _TRANSMIT_FILE_BUFFERS;

//  WinSock 2 extension -- WSAPROTOCOL_INFO structure

{
  TGUID = packed record
    D1: LongInt;
    D2: Word;
    D3: Word;
    D4: Array[0..7] of Byte;
  end;  }
  PGUID = ^TGUID;

  {$EXTERNALSYM LPGUID}
  LPGUID = PGUID;

//  WinSock 2 extension -- WSAPROTOCOL_INFO manifest constants
const
  {$EXTERNALSYM MAX_PROTOCOL_CHAIN}
  MAX_PROTOCOL_CHAIN = 7;
  {$EXTERNALSYM BASE_PROTOCOL}
  BASE_PROTOCOL      = 1;
  {$EXTERNALSYM LAYERED_PROTOCOL}
  LAYERED_PROTOCOL   = 0;
  {$EXTERNALSYM WSAPROTOCOL_LEN}
  WSAPROTOCOL_LEN    = 255;

type
  {$EXTERNALSYM WSAPROTOCOLCHAIN}
  WSAPROTOCOLCHAIN = record
    ChainLen: Integer;  // the length of the chain,
    // length = 0 means layered protocol,
    // length = 1 means base protocol,
    // length > 1 means protocol chain
    ChainEntries: Array[0..MAX_PROTOCOL_CHAIN-1] of LongInt; // a list of dwCatalogEntryIds
  end;
  TWSAProtocolChain = WSAPROTOCOLCHAIN;
  {$EXTERNALSYM LPWSAPROTOCOLCHAIN}
  LPWSAPROTOCOLCHAIN = ^TWSAProtocolChain;

type
  {$EXTERNALSYM WSAPROTOCOL_INFOA}
  WSAPROTOCOL_INFOA = record
    dwServiceFlags1: DWORD;
    dwServiceFlags2: DWORD;
    dwServiceFlags3: DWORD;
    dwServiceFlags4: DWORD;
    dwProviderFlags: DWORD;
    ProviderId: TGUID;
    dwCatalogEntryId: DWORD;
    ProtocolChain: TWSAProtocolChain;
    iVersion: Integer;
    iAddressFamily: Integer;
    iMaxSockAddr: Integer;
    iMinSockAddr: Integer;
    iSocketType: Integer;
    iProtocol: Integer;
    iProtocolMaxOffset: Integer;
    iNetworkByteOrder: Integer;
    iSecurityScheme: Integer;
    dwMessageSize: DWORD;
    dwProviderReserved: DWORD;
    szProtocol: Array[0..WSAPROTOCOL_LEN+1-1] of Char;
  end;
  TWSAProtocol_InfoA = WSAPROTOCOL_INFOA;
  PWSAProtocol_InfoA = ^WSAPROTOCOL_INFOA;
  {$EXTERNALSYM LPWSAPROTOCOL_INFOA}
  LPWSAPROTOCOL_INFOA = PWSAProtocol_InfoA;

  {$EXTERNALSYM WSAPROTOCOL_INFOW}
  WSAPROTOCOL_INFOW = record
    dwServiceFlags1: DWORD;
    dwServiceFlags2: DWORD;
    dwServiceFlags3: DWORD;
    dwServiceFlags4: DWORD;
    dwProviderFlags: DWORD;
    ProviderId: TGUID;
    dwCatalogEntryId: DWORD;
    ProtocolChain: TWSAProtocolChain;
    iVersion: Integer;
    iAddressFamily: Integer;
    iMaxSockAddr: Integer;
    iMinSockAddr: Integer;
    iSocketType: Integer;
    iProtocol: Integer;
    iProtocolMaxOffset: Integer;
    iNetworkByteOrder: Integer;
    iSecurityScheme: Integer;
    dwMessageSize: DWORD;
    dwProviderReserved: DWORD;
    szProtocol: Array[0..WSAPROTOCOL_LEN+1-1] of WideChar;
  end {TWSAProtocol_InfoW};
  TWSAProtocol_InfoW = WSAPROTOCOL_INFOW;
  PWSAProtocol_InfoW = ^TWSAProtocol_InfoW;
  {$EXTERNALSYM LPWSAPROTOCOL_INFOW}
  LPWSAPROTOCOL_INFOW = PWSAProtocol_InfoW;

  {$EXTERNALSYM WSAPROTOCOL_INFO}
  {$EXTERNALSYM LPWSAPROTOCOL_INFO}
{$IFDEF UNICODE}
  WSAPROTOCOL_INFO = TWSAProtocol_InfoW;
  TWSAProtocol_Info = TWSAProtocol_InfoW;
  PWSAProtocol_Info = PWSAProtocol_InfoW;
  LPWSAPROTOCOL_INFO = PWSAProtocol_InfoW;
{$ELSE}
  WSAPROTOCOL_INFO = TWSAProtocol_InfoA;
  TWSAProtocol_Info = TWSAProtocol_InfoA;
  PWSAProtocol_Info = PWSAProtocol_InfoA;
  LPWSAPROTOCOL_INFO = PWSAProtocol_InfoA;
{$ENDIF}

const
//  flag bit definitions for dwProviderFlags
  {$EXTERNALSYM PFL_MULTIPLE_PROTO_ENTRIES}
  PFL_MULTIPLE_PROTO_ENTRIES   = $00000001;
  {$EXTERNALSYM PFL_RECOMMENTED_PROTO_ENTRY}
  PFL_RECOMMENTED_PROTO_ENTRY  = $00000002;
  {$EXTERNALSYM PFL_HIDDEN}
  PFL_HIDDEN                   = $00000004;
  {$EXTERNALSYM PFL_MATCHES_PROTOCOL_ZERO}
  PFL_MATCHES_PROTOCOL_ZERO    = $00000008;

//  flag bit definitions for dwServiceFlags1
  {$EXTERNALSYM XP1_CONNECTIONLESS}
  XP1_CONNECTIONLESS           = $00000001;
  {$EXTERNALSYM XP1_GUARANTEED_DELIVERY}
  XP1_GUARANTEED_DELIVERY      = $00000002;
  {$EXTERNALSYM XP1_GUARANTEED_ORDER}
  XP1_GUARANTEED_ORDER         = $00000004;
  {$EXTERNALSYM XP1_MESSAGE_ORIENTED}
  XP1_MESSAGE_ORIENTED         = $00000008;
  {$EXTERNALSYM XP1_PSEUDO_STREAM}
  XP1_PSEUDO_STREAM            = $00000010;
  {$EXTERNALSYM XP1_GRACEFUL_CLOSE}
  XP1_GRACEFUL_CLOSE           = $00000020;
  {$EXTERNALSYM XP1_EXPEDITED_DATA}
  XP1_EXPEDITED_DATA           = $00000040;
  {$EXTERNALSYM XP1_CONNECT_DATA}
  XP1_CONNECT_DATA             = $00000080;
  {$EXTERNALSYM XP1_DISCONNECT_DATA}
  XP1_DISCONNECT_DATA          = $00000100;
  {$EXTERNALSYM XP1_SUPPORT_BROADCAST}
  XP1_SUPPORT_BROADCAST        = $00000200;
  {$EXTERNALSYM XP1_SUPPORT_MULTIPOINT}
  XP1_SUPPORT_MULTIPOINT       = $00000400;
  {$EXTERNALSYM XP1_MULTIPOINT_CONTROL_PLANE}
  XP1_MULTIPOINT_CONTROL_PLANE = $00000800;
  {$EXTERNALSYM XP1_MULTIPOINT_DATA_PLANE}
  XP1_MULTIPOINT_DATA_PLANE    = $00001000;
  {$EXTERNALSYM XP1_QOS_SUPPORTED}
  XP1_QOS_SUPPORTED            = $00002000;
  {$EXTERNALSYM XP1_INTERRUPT}
  XP1_INTERRUPT                = $00004000;
  {$EXTERNALSYM XP1_UNI_SEND}
  XP1_UNI_SEND                 = $00008000;
  {$EXTERNALSYM XP1_UNI_RECV}
  XP1_UNI_RECV                 = $00010000;
  {$EXTERNALSYM XP1_IFS_HANDLES}
  XP1_IFS_HANDLES              = $00020000;
  {$EXTERNALSYM XP1_PARTIAL_MESSAGE}
  XP1_PARTIAL_MESSAGE          = $00040000;

  {$EXTERNALSYM BIGENDIAN}
  BIGENDIAN    = $0000;
  {$EXTERNALSYM LITTLEENDIAN}
  LITTLEENDIAN = $0001;

  {$EXTERNALSYM SECURITY_PROTOCOL_NONE}
  SECURITY_PROTOCOL_NONE = $0000;

//  WinSock 2 extension -- manifest constants for WSAJoinLeaf()
  {$EXTERNALSYM JL_SENDER_ONLY}
  JL_SENDER_ONLY   = $01;
  {$EXTERNALSYM JL_RECEIVER_ONLY}
  JL_RECEIVER_ONLY = $02;
  {$EXTERNALSYM JL_BOTH}
  JL_BOTH          = $04;

//  WinSock 2 extension -- manifest constants for WSASocket()
  {$EXTERNALSYM WSA_FLAG_OVERLAPPED}
  WSA_FLAG_OVERLAPPED        = $01;
  {$EXTERNALSYM WSA_FLAG_MULTIPOINT_C_ROOT}
  WSA_FLAG_MULTIPOINT_C_ROOT = $02;
  {$EXTERNALSYM WSA_FLAG_MULTIPOINT_C_LEAF}
  WSA_FLAG_MULTIPOINT_C_LEAF = $04;
  {$EXTERNALSYM WSA_FLAG_MULTIPOINT_D_ROOT}
  WSA_FLAG_MULTIPOINT_D_ROOT = $08;
  {$EXTERNALSYM WSA_FLAG_MULTIPOINT_D_LEAF}
  WSA_FLAG_MULTIPOINT_D_LEAF = $10;

//  WinSock 2 extension -- manifest constants for WSAIoctl()
  {$EXTERNALSYM IOC_UNIX}
  IOC_UNIX      = $00000000;
  {$EXTERNALSYM IOC_WS2}
  IOC_WS2       = $08000000;
  {$EXTERNALSYM IOC_PROTOCOL}
  IOC_PROTOCOL  = $10000000;
  {$EXTERNALSYM IOC_VENDOR}
  IOC_VENDOR    = $18000000;

  {$EXTERNALSYM SIO_ASSOCIATE_HANDLE}
  SIO_ASSOCIATE_HANDLE                =  IOC_IN or IOC_WS2 or 1;
  {$EXTERNALSYM SIO_ENABLE_CIRCULAR_QUEUEING}
  SIO_ENABLE_CIRCULAR_QUEUEING        =  IOC_VOID or IOC_WS2 or 2;
  {$EXTERNALSYM SIO_FIND_ROUTE}
  SIO_FIND_ROUTE                      =  IOC_OUT or IOC_WS2 or 3;
  {$EXTERNALSYM SIO_FLUSH}
  SIO_FLUSH                           =  IOC_VOID or IOC_WS2 or 4;
  {$EXTERNALSYM SIO_GET_BROADCAST_ADDRESS}
  SIO_GET_BROADCAST_ADDRESS           =  IOC_OUT or IOC_WS2 or 5;
  {$EXTERNALSYM SIO_GET_EXTENSION_FUNCTION_POINTER}
  SIO_GET_EXTENSION_FUNCTION_POINTER  =  IOC_INOUT or IOC_WS2 or 6;
  {$EXTERNALSYM SIO_GET_QOS}
  SIO_GET_QOS                         =  IOC_INOUT or IOC_WS2 or 7;
  {$EXTERNALSYM SIO_GET_GROUP_QOS}
  SIO_GET_GROUP_QOS                   =  IOC_INOUT or IOC_WS2 or 8;
  {$EXTERNALSYM SIO_MULTIPOINT_LOOPBACK}
  SIO_MULTIPOINT_LOOPBACK             =  IOC_IN or IOC_WS2 or 9;
  {$EXTERNALSYM SIO_MULTICAST_SCOPE}
  SIO_MULTICAST_SCOPE                 = IOC_IN or IOC_WS2 or 10;
  {$EXTERNALSYM SIO_SET_QOS}
  SIO_SET_QOS                         = IOC_IN or IOC_WS2 or 11;
  {$EXTERNALSYM SIO_SET_GROUP_QOS}
  SIO_SET_GROUP_QOS                   = IOC_IN or IOC_WS2 or 12;
  {$EXTERNALSYM SIO_TRANSLATE_HANDLE}
  SIO_TRANSLATE_HANDLE                = IOC_INOUT or IOC_WS2 or 13;
  {$EXTERNALSYM SIO_ROUTING_INTERFACE_QUERY}
  SIO_ROUTING_INTERFACE_QUERY         = IOC_INOUT or IOC_WS2 or 20;
  {$EXTERNALSYM SIO_ROUTING_INTERFACE_CHANGE}
  SIO_ROUTING_INTERFACE_CHANGE        = IOC_IN or IOC_WS2 or 21;
  {$EXTERNALSYM SIO_ADDRESS_LIST_QUERY}
  SIO_ADDRESS_LIST_QUERY              = IOC_OUT or IOC_WS2 or 22; // see below SOCKET_ADDRESS_LIST
  {$EXTERNALSYM SIO_ADDRESS_LIST_CHANGE}
  SIO_ADDRESS_LIST_CHANGE             = IOC_VOID or IOC_WS2 or 23;
  {$EXTERNALSYM SIO_QUERY_TARGET_PNP_HANDLE}
  SIO_QUERY_TARGET_PNP_HANDLE         = IOC_OUT or IOC_WS2 or 24;
  {$EXTERNALSYM SIO_ADDRESS_LIST_SORT}
  SIO_ADDRESS_LIST_SORT               = IOC_INOUT or IOC_WS2 or 25;

//  WinSock 2 extension -- manifest constants for SIO_TRANSLATE_HANDLE ioctl
  {$EXTERNALSYM TH_NETDEV}
  TH_NETDEV = $00000001;
  {$EXTERNALSYM TH_TAPI}
  TH_TAPI   = $00000002;

type
//  Manifest constants and type definitions related to name resolution and
//  registration (RNR) API
  {$EXTERNALSYM BLOB}
  BLOB = packed record
    cbSize : ULONG;
    pBlobData : PBYTE;
  end;
  TBLOB = BLOB;
  PBLOB = ^TBLOB;
  {$EXTERNALSYM LPBLOB}
  LPBLOB = PBLOB;

//  Service Install Flags

const
  {$EXTERNALSYM SERVICE_MULTIPLE}
  SERVICE_MULTIPLE = $00000001;

// & name spaces
  {$EXTERNALSYM NS_ALL}
  NS_ALL         =  0;

  {$EXTERNALSYM NS_SAP}
  NS_SAP         =  1;
  {$EXTERNALSYM NS_NDS}
  NS_NDS         =  2;
  {$EXTERNALSYM NS_PEER_BROWSE}
  NS_PEER_BROWSE =  3;
  {$EXTERNALSYM NS_SLP}
  NS_SLP         =  5;
  {$EXTERNALSYM NS_DHCP}
  NS_DHCP        =  6;

  {$EXTERNALSYM NS_TCPIP_LOCAL}
  NS_TCPIP_LOCAL = 10;
  {$EXTERNALSYM NS_TCPIP_HOSTS}
  NS_TCPIP_HOSTS = 11;
  {$EXTERNALSYM NS_DNS}
  NS_DNS         = 12;
  {$EXTERNALSYM NS_NETBT}
  NS_NETBT       = 13;
  {$EXTERNALSYM NS_WINS}
  NS_WINS        = 14;
  {$EXTERNALSYM NS_NLA}
  NS_NLA         = 15;  // Network Location Awareness

  {$EXTERNALSYM NS_NBP}
  NS_NBP         = 20;

  {$EXTERNALSYM NS_MS}
  NS_MS          = 30;
  {$EXTERNALSYM NS_STDA}
  NS_STDA        = 31;
  {$EXTERNALSYM NS_NTDS}
  NS_NTDS        = 32;

  {$EXTERNALSYM NS_X500}
  NS_X500        = 40;
  {$EXTERNALSYM NS_NIS}
  NS_NIS         = 41;
  {$EXTERNALSYM NS_NISPLUS}
  NS_NISPLUS     = 42;

  {$EXTERNALSYM NS_WRQ}
  NS_WRQ         = 50;

  {$EXTERNALSYM NS_NETDES}
  NS_NETDES      = 60;  // Network Designers Limited

{ Resolution flags for WSAGetAddressByName().
  Note these are also used by the 1.1 API GetAddressByName, so leave them around. }
  {$EXTERNALSYM RES_UNUSED_1}
  RES_UNUSED_1    = $00000001;
  {$EXTERNALSYM RES_FLUSH_CACHE}
  RES_FLUSH_CACHE = $00000002;
  {$EXTERNALSYM RES_SERVICE}
  RES_SERVICE     = $00000004;

{ Well known value names for Service Types }
  {$EXTERNALSYM SERVICE_TYPE_VALUE_IPXPORTA}
  SERVICE_TYPE_VALUE_IPXPORTA              = 'IpxSocket';    {Do not Localize}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_IPXPORTW}
  SERVICE_TYPE_VALUE_IPXPORTW : PWideChar  = 'IpxSocket';    {Do not Localize}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_SAPIDA}
  SERVICE_TYPE_VALUE_SAPIDA                = 'SapId';    {Do not Localize}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_SAPIDW}
  SERVICE_TYPE_VALUE_SAPIDW : PWideChar    = 'SapId';    {Do not Localize}

  {$EXTERNALSYM SERVICE_TYPE_VALUE_TCPPORTA}
  SERVICE_TYPE_VALUE_TCPPORTA              = 'TcpPort';    {Do not Localize}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_TCPPORTW}
  SERVICE_TYPE_VALUE_TCPPORTW : PWideChar  = 'TcpPort';    {Do not Localize}

  {$EXTERNALSYM SERVICE_TYPE_VALUE_UDPPORTA}
  SERVICE_TYPE_VALUE_UDPPORTA              = 'UdpPort';    {Do not Localize}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_UDPPORTW}
  SERVICE_TYPE_VALUE_UDPPORTW : PWideChar  = 'UdpPort';    {Do not Localize}

  {$EXTERNALSYM SERVICE_TYPE_VALUE_OBJECTIDA}
  SERVICE_TYPE_VALUE_OBJECTIDA             = 'ObjectId';    {Do not Localize}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_OBJECTIDW}
  SERVICE_TYPE_VALUE_OBJECTIDW : PWideChar = 'ObjectId';    {Do not Localize}

  {$EXTERNALSYM SERVICE_TYPE_VALUE_SAPID}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_TCPPORT}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_UDPPORT}
  {$EXTERNALSYM SERVICE_TYPE_VALUE_OBJECTID}
{$IFDEF UNICODE}
  SERVICE_TYPE_VALUE_SAPID : PWideChar = SERVICE_TYPE_VALUE_SAPIDW;
  SERVICE_TYPE_VALUE_TCPPORT : PWideChar = SERVICE_TYPE_VALUE_TCPPORTW;
  SERVICE_TYPE_VALUE_UDPPORT : PWideChar = SERVICE_TYPE_VALUE_UDPPORTW;
  SERVICE_TYPE_VALUE_OBJECTID : PWideChar = SERVICE_TYPE_VALUE_OBJECTIDW
{$ELSE}
  SERVICE_TYPE_VALUE_SAPID    = SERVICE_TYPE_VALUE_SAPIDA;
  SERVICE_TYPE_VALUE_TCPPORT  = SERVICE_TYPE_VALUE_TCPPORTA;
  SERVICE_TYPE_VALUE_UDPPORT  = SERVICE_TYPE_VALUE_UDPPORTA;
  SERVICE_TYPE_VALUE_OBJECTID = SERVICE_TYPE_VALUE_OBJECTIDA;
{$ENDIF}

// SockAddr Information
type
  {$EXTERNALSYM SOCKET_ADDRESS}
  SOCKET_ADDRESS = packed record
    lpSockaddr : PSockAddr;
    iSockaddrLength : Integer;
  end;
  TSocket_Address = SOCKET_ADDRESS;
  {$EXTERNALSYM PSOCKET_ADDRESS}
  PSOCKET_ADDRESS = ^TSocket_Address;

// CSAddr Information
  {$EXTERNALSYM CSADDR_INFO}
  CSADDR_INFO = packed record
    LocalAddr, RemoteAddr  : TSocket_Address;
    iSocketType, iProtocol : Integer;
  end;
  TCSAddr_Info = CSADDR_INFO;
  {$EXTERNALSYM PCSADDR_INFO}
  PCSADDR_INFO = ^TCSAddr_Info;
  {$EXTERNALSYM LPCSADDR_INFO}
  LPCSADDR_INFO = PCSADDR_INFO;

// Address list returned via WSAIoctl( SIO_ADDRESS_LIST_QUERY )
  {$EXTERNALSYM SOCKET_ADDRESS_LIST}
  SOCKET_ADDRESS_LIST = packed record
    iAddressCount : Integer;
    Address       : Array [0..0] of TSocket_Address;
  end;
  TSocket_Address_List = SOCKET_ADDRESS_LIST;
  {$EXTERNALSYM LPSOCKET_ADDRESS_LIST}
  LPSOCKET_ADDRESS_LIST = ^TSocket_Address_List;

// Address Family/Protocol Tuples
  {$EXTERNALSYM AFPROTOCOLS}
  AFPROTOCOLS = record
    iAddressFamily : Integer;
    iProtocol      : Integer;
  end;
  TAFProtocols = AFPROTOCOLS;
  {$EXTERNALSYM PAFPROTOCOLS}
  PAFPROTOCOLS = ^TAFProtocols;
  {$EXTERNALSYM LPAFPROTOCOLS}
  LPAFPROTOCOLS = PAFPROTOCOLS;

//  Client Query API Typedefs

// The comparators
  {$EXTERNALSYM WSAECOMPARATOR}
  WSAECOMPARATOR = (COMP_EQUAL {= 0}, COMP_NOTLESS );
  TWSAEComparator = WSAECOMPARATOR;

  {$EXTERNALSYM WSAVERSION}
  WSAVERSION = record
    dwVersion : DWORD;
    ecHow     : TWSAEComparator;
  end;
  TWSAVersion = WSAVERSION;
  {$EXTERNALSYM PWSAVERSION}
  PWSAVERSION = ^TWSAVersion;
  {$EXTERNALSYM LPWSAVERSION}
  LPWSAVERSION = PWSAVERSION;

  {$EXTERNALSYM WSAQUERYSETA}
  WSAQUERYSETA = packed record
    dwSize                  : DWORD;
    lpszServiceInstanceName : PChar;
    lpServiceClassId        : PGUID;
    lpVersion               : LPWSAVERSION;
    lpszComment             : PChar;
    dwNameSpace             : DWORD;
    lpNSProviderId          : PGUID;
    lpszContext             : PChar;
    dwNumberOfProtocols     : DWORD;
    lpafpProtocols          : LPAFPROTOCOLS;
    lpszQueryString         : PChar;
    dwNumberOfCsAddrs       : DWORD;
    lpcsaBuffer             : LPCSADDR_INFO;
    dwOutputFlags           : DWORD;
    lpBlob                  : LPBLOB;
  end;
  TWSAQuerySetA = WSAQUERYSETA;
  {$EXTERNALSYM PWSAQUERYSETA}
  PWSAQUERYSETA = ^TWSAQuerySetA;
  {$EXTERNALSYM LPWSAQUERYSETA}
  LPWSAQUERYSETA = PWSAQUERYSETA;

  {$EXTERNALSYM WSAQUERYSETW}
  WSAQUERYSETW = packed record
    dwSize                  : DWORD;
    lpszServiceInstanceName : PWideChar;
    lpServiceClassId        : PGUID;
    lpVersion               : LPWSAVERSION;
    lpszComment             : PWideChar;
    dwNameSpace             : DWORD;
    lpNSProviderId          : PGUID;
    lpszContext             : PWideChar;
    dwNumberOfProtocols     : DWORD;
    lpafpProtocols          : LPAFPROTOCOLS;
    lpszQueryString         : PWideChar;
    dwNumberOfCsAddrs       : DWORD;
    lpcsaBuffer             : LPCSADDR_INFO;
    dwOutputFlags           : DWORD;
    lpBlob                  : LPBLOB;
  end;
  TWSAQuerySetW = WSAQUERYSETW;
  {$EXTERNALSYM PWSAQUERYSETW}
  PWSAQUERYSETW = ^TWSAQuerySetW;
  {$EXTERNALSYM LPWSAQUERYSETW}
  LPWSAQUERYSETW = PWSAQUERYSETW;

  {$EXTERNALSYM LPWSAQUERYSET}
  {$EXTERNALSYM PWSAQUERYSET}
{$IFDEF UNICODE}
  TWSAQuerySet  = TWSAQuerySetA;
  PWSAQUERYSET  = PWSAQUERYSETW;
  LPWSAQUERYSET = LPWSAQUERYSETW;
{$ELSE}
  TWSAQuerySet  = TWSAQuerySetA;
  PWSAQUERYSET  = PWSAQUERYSETA;
  LPWSAQUERYSET = LPWSAQUERYSETA;
{$ENDIF}

const
  {$EXTERNALSYM LUP_DEEP}
  LUP_DEEP                = $0001;
  {$EXTERNALSYM LUP_CONTAINERS}
  LUP_CONTAINERS          = $0002;
  {$EXTERNALSYM LUP_NOCONTAINERS}
  LUP_NOCONTAINERS        = $0004;
  {$EXTERNALSYM LUP_NEAREST}
  LUP_NEAREST             = $0008;
  {$EXTERNALSYM LUP_RETURN_NAME}
  LUP_RETURN_NAME         = $0010;
  {$EXTERNALSYM LUP_RETURN_TYPE}
  LUP_RETURN_TYPE         = $0020;
  {$EXTERNALSYM LUP_RETURN_VERSION}
  LUP_RETURN_VERSION      = $0040;
  {$EXTERNALSYM LUP_RETURN_COMMENT}
  LUP_RETURN_COMMENT      = $0080;
  {$EXTERNALSYM LUP_RETURN_ADDR}
  LUP_RETURN_ADDR         = $0100;
  {$EXTERNALSYM LUP_RETURN_BLOB}
  LUP_RETURN_BLOB         = $0200;
  {$EXTERNALSYM LUP_RETURN_ALIASES}
  LUP_RETURN_ALIASES      = $0400;
  {$EXTERNALSYM LUP_RETURN_QUERY_STRING}
  LUP_RETURN_QUERY_STRING = $0800;
  {$EXTERNALSYM LUP_RETURN_ALL}
  LUP_RETURN_ALL          = $0FF0;
  {$EXTERNALSYM LUP_RES_SERVICE}
  LUP_RES_SERVICE         = $8000;

  {$EXTERNALSYM LUP_FLUSHCACHE}
  LUP_FLUSHCACHE          = $1000;
  {$EXTERNALSYM LUP_FLUSHPREVIOUS}
  LUP_FLUSHPREVIOUS       = $2000;

// Return flags
  {$EXTERNALSYM RESULT_IS_ALIAS}
  RESULT_IS_ALIAS         = $0001;
  {$EXTERNALSYM RESULT_IS_ADDED}
  RESULT_IS_ADDED         = $0010;
  {$EXTERNALSYM RESULT_IS_CHANGED}
  RESULT_IS_CHANGED       = $0020;
  {$EXTERNALSYM RESULT_IS_DELETED}
  RESULT_IS_DELETED       = $0040;

type
// Service Address Registration and Deregistration Data Types.
  {$EXTERNALSYM WSAESETSERVICEOP}
  WSAESETSERVICEOP = ( RNRSERVICE_REGISTER{=0}, RNRSERVICE_DEREGISTER, RNRSERVICE_DELETE );
  TWSAESetServiceOp = WSAESETSERVICEOP;

{ Service Installation/Removal Data Types. }
  {$EXTERNALSYM WSANSCLASSINFOA}
  WSANSCLASSINFOA = packed record
    lpszName    : PChar;
    dwNameSpace : DWORD;
    dwValueType : DWORD;
    dwValueSize : DWORD;
    lpValue     : Pointer;
  end;
  TWSANSClassInfoA = WSANSCLASSINFOA;
  {$EXTERNALSYM PWSANSClassInfoA}
  PWSANSCLASSINFOA = ^TWSANSClassInfoA;
  {$EXTERNALSYM LPWSANSCLASSINFOA}
  LPWSANSCLASSINFOA = PWSANSCLASSINFOA;

  {$EXTERNALSYM WSANSCLASSINFOW}
  WSANSCLASSINFOW = packed record
    lpszName    : PWideChar;
    dwNameSpace : DWORD;
    dwValueType : DWORD;
    dwValueSize : DWORD;
    lpValue     : Pointer;
  end {TWSANSClassInfoW};
  TWSANSClassInfoW = WSANSCLASSINFOW;
  {$EXTERNALSYM PWSANSClassInfoW}
  PWSANSCLASSINFOW = ^TWSANSClassInfoW;
  {$EXTERNALSYM LPWSANSCLASSINFOW}
  LPWSANSCLASSINFOW = PWSANSCLASSINFOW;

  {$EXTERNALSYM WSANSCLASSINFO}
  {$EXTERNALSYM PWSANSCLASSINFO}
  {$EXTERNALSYM LPWSANSCLASSINFO}
{$IFDEF UNICODE}
  TWSANSClassInfo  = TWSANSClassInfoW;
  WSANSCLASSINFO   = TWSANSClassInfoW;
  PWSANSCLASSINFO  = PWSANSCLASSINFOW;
  LPWSANSCLASSINFO = LPWSANSCLASSINFOW;
{$ELSE}
  TWSANSClassInfo  = TWSANSClassInfoA;
  WSANSCLASSINFO   = TWSANSClassInfoA;
  PWSANSCLASSINFO  = PWSANSCLASSINFOA;
  LPWSANSCLASSINFO = LPWSANSCLASSINFOA;
{$ENDIF // UNICODE}

  {$EXTERNALSYM WSASERVICECLASSINFOA}
  WSASERVICECLASSINFOA = packed record
    lpServiceClassId     : PGUID;
    lpszServiceClassName : PChar;
    dwCount              : DWORD;
    lpClassInfos         : LPWSANSCLASSINFOA;
  end;
  TWSAServiceClassInfoA = WSASERVICECLASSINFOA;
  {$EXTERNALSYM PWSASERVICECLASSINFOA}
  PWSASERVICECLASSINFOA  = ^TWSAServiceClassInfoA;
  {$EXTERNALSYM LPWSASERVICECLASSINFOA}
  LPWSASERVICECLASSINFOA = PWSASERVICECLASSINFOA;

  {$EXTERNALSYM WSASERVICECLASSINFOW}
  WSASERVICECLASSINFOW = packed record
    lpServiceClassId     : PGUID;
    lpszServiceClassName : PWideChar;
    dwCount              : DWORD;
    lpClassInfos         : LPWSANSCLASSINFOW;
  end;
  TWSAServiceClassInfoW = WSASERVICECLASSINFOW;
  {$EXTERNALSYM PWSASERVICECLASSINFOW}
  PWSASERVICECLASSINFOW  = ^TWSAServiceClassInfoW;
  {$EXTERNALSYM LPWSASERVICECLASSINFOW}
  LPWSASERVICECLASSINFOW = PWSASERVICECLASSINFOW;

  {$EXTERNALSYM WSASERVICECLASSINFO}
  {$EXTERNALSYM PWSASERVICECLASSINFO}
  {$EXTERNALSYM LPWSASERVICECLASSINFO}
{$IFDEF UNICODE}
  TWSAServiceClassInfo  = TWSAServiceClassInfoW;
  WSASERVICECLASSINFO   = TWSAServiceClassInfoW;
  PWSASERVICECLASSINFO  = PWSASERVICECLASSINFOW;
  LPWSASERVICECLASSINFO = LPWSASERVICECLASSINFOW;
{$ELSE}
  TWSAServiceClassInfo  = TWSAServiceClassInfoA;
  WSASERVICECLASSINFO   = TWSAServiceClassInfoA;
  PWSASERVICECLASSINFO  = PWSASERVICECLASSINFOA;
  LPWSASERVICECLASSINFO = LPWSASERVICECLASSINFOA;
{$ENDIF}

  {$EXTERNALSYM WSANAMESPACE_INFOA}
  WSANAMESPACE_INFOA = packed record
    NSProviderId   : TGUID;
    dwNameSpace    : DWORD;
    fActive        : DWORD{Bool};
    dwVersion      : DWORD;
    lpszIdentifier : PChar;
  end;
  TWSANameSpace_InfoA = WSANAMESPACE_INFOA;
  {$EXTERNALSYM PWSANAMESPACE_INFOA}
  PWSANAMESPACE_INFOA = ^TWSANameSpace_InfoA;
  {$EXTERNALSYM LPWSANAMESPACE_INFOA}
  LPWSANAMESPACE_INFOA = PWSANAMESPACE_INFOA;

  {$EXTERNALSYM WSANAMESPACE_INFOW}
  WSANAMESPACE_INFOW = packed record
    NSProviderId   : TGUID;
    dwNameSpace    : DWORD;
    fActive        : DWORD{Bool};
    dwVersion      : DWORD;
    lpszIdentifier : PWideChar;
  end {TWSANameSpace_InfoW};
  TWSANameSpace_InfoW = WSANAMESPACE_INFOW;
  {$EXTERNALSYM PWSANAMESPACE_INFOW}
  PWSANAMESPACE_INFOW = ^TWSANameSpace_InfoW;
  {$EXTERNALSYM LPWSANAMESPACE_INFOW}
  LPWSANAMESPACE_INFOW = PWSANAMESPACE_INFOW;

  {$EXTERNALSYM WSANAMESPACE_INFO}
  {$EXTERNALSYM PWSANAMESPACE_INFO}
  {$EXTERNALSYM LPWSANAMESPACE_INFO}
{$IFDEF UNICODE}
  TWSANameSpace_Info  = TWSANameSpace_InfoW;
  WSANAMESPACE_INFO   = TWSANameSpace_InfoW;
  PWSANAMESPACE_INFO  = PWSANAMESPACE_INFOW;
  LPWSANAMESPACE_INFO = LPWSANAMESPACE_INFOW;
{$ELSE}
  TWSANameSpace_Info  = TWSANameSpace_InfoA;
  WSANAMESPACE_INFO   = TWSANameSpace_InfoA;
  PWSANAMESPACE_INFO  = PWSANAMESPACE_INFOA;
  LPWSANAMESPACE_INFO = LPWSANAMESPACE_INFOA;
{$ENDIF}

{ WinSock 2 extensions -- data types for the condition function in }
{ WSAAccept() and overlapped I/O completion routine. }
type
  {$EXTERNALSYM LPCONDITIONPROC}
  LPCONDITIONPROC = function (lpCallerId: LPWSABUF; lpCallerData: LPWSABUF; lpSQOS, pGQOS: LPQOS; lpCalleeId,lpCalleeData: LPWSABUF;
    g: GROUP; dwCallbackData: DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPWSAOVERLAPPED_COMPLETION_ROUTINE}
  LPWSAOVERLAPPED_COMPLETION_ROUTINE = procedure ( const dwError, cbTransferred: DWORD; const lpOverlapped : LPWSAOVERLAPPED; const dwFlags: DWORD ); stdcall;

  {$EXTERNALSYM WSAStartup}
function WSAStartup( const wVersionRequired: word; var WSData: TWSAData ): Integer; stdcall;

type
{$IFDEF INCL_WINSOCK_API_TYPEDEFS}
  {$EXTERNALSYM LPFN_WSASTARTUP}
  LPFN_WSASTARTUP = function ( const wVersionRequired: word; var WSData: TWSAData ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSACLEANUP}
  LPFN_WSACLEANUP = function : Integer; stdcall;
  {$EXTERNALSYM LPFN_ACCEPT}
  LPFN_ACCEPT = function ( const s: TSocket; addr: PSockAddr; addrlen: PInteger ): TSocket; stdcall;
  {$EXTERNALSYM LPFN_BIND}
  LPFN_BIND = function ( const s: TSocket; const name: PSockAddr; const namelen: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_CLOSESOCKET}
  LPFN_CLOSESOCKET = function ( const s: TSocket ): Integer; stdcall;
  {$EXTERNALSYM LPFN_CONNECT}
  LPFN_CONNECT = function ( const s: TSocket; const name: PSockAddr; const namelen: Integer): Integer; stdcall;
  {$EXTERNALSYM lpfn_IOCTLSOCKET}
  LPFN_IOCTLSOCKET = function ( const s: TSocket; const cmd: DWORD; var arg: u_long ): Integer; stdcall;
  {$EXTERNALSYM LPFN_GETPEERNAME}
  LPFN_GETPEERNAME = function ( const s: TSocket; const name: PSockAddr; var namelen: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_GETSOCKNAME}
  LPFN_GETSOCKNAME = function ( const s: TSocket; const name: PSockAddr; var namelen: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_GETSOCKOPT}
  LPFN_GETSOCKOPT = function ( const s: TSocket; const level, optname: Integer; optval: PChar; var optlen: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_HTONL}
  LPFN_HTONL = function (hostlong: u_long): u_long; stdcall;
  {$EXTERNALSYM LPFN_HTONS}
  LPFN_HTONS = function (hostshort: u_short): u_short; stdcall;
  {$EXTERNALSYM LPFN_INET_ADDR}
  LPFN_INET_ADDR = function (cp: PChar): u_long; stdcall;
  {$EXTERNALSYM LPFN_INET_NTOA}
  LPFN_INET_NTOA = function (inaddr: TInAddr): PChar; stdcall;
  {$EXTERNALSYM LPFN_LISTEN}
  LPFN_LISTEN = function ( const s: TSocket; backlog: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_NTOHL}
  LPFN_NTOHL = function (netlong: u_long): u_long; stdcall;
  {$EXTERNALSYM LPFN_NTOHS}
  LPFN_NTOHS = function (netshort: u_short): u_short; stdcall;
  {$EXTERNALSYM LPFN_RECV}
  LPFN_RECV = function ( const s: TSocket; var Buf; len, flags: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_RECVFROM}
  lpfn_RECVFROM = function ( const s: TSocket; var Buf; len, flags: Integer; from: PSockAddr; fromlen: PInteger ): Integer; stdcall;
  {$EXTERNALSYM LPFN_SELECT}
  LPFN_SELECT = function (nfds: Integer; readfds, writefds, exceptfds: PFDSet; timeout: PTimeVal ): Integer; stdcall;
  {$EXTERNALSYM LPFN_SEND}
  LPFN_SEND = function ( const s: TSocket; var Buf; len, flags: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_SENDTO}
  LPFN_SENDTO = function ( const s: TSocket; var Buf; const len, flags: Integer; const addrto: PSockAddr; const tolen: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_SETSOCKOPT}
  LPFN_SETSOCKOPT = function ( const s: TSocket; const level, optname: Integer; optval: PChar; const optlen: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_SHUTDOWN}
  LPFN_SHUTDOWN = function ( const s: TSocket; const how: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_SOCKET}
  LPFN_SOCKET = function ( const af, istruct, protocol: Integer ): TSocket; stdcall;
  {$EXTERNALSYM LPFN_GETHOSTBYADDR}
  LPFN_GETHOSTBYADDR = function ( addr: Pointer; const len, addrtype: Integer ): PHostEnt; stdcall;
  {$EXTERNALSYM LPFN_GETHOSTBYNAME}
  LPFN_GETHOSTBYNAME = function ( name: PChar ): PHostEnt; stdcall;
  {$EXTERNALSYM LPFN_GETHOSTNAME}
  LPFN_GETHOSTNAME = function ( name: PChar; len: Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_GETSERVBYPORT}
  LPFN_GETSERVBYPORT = function ( const port: Integer; const proto: PChar ): PServEnt; stdcall;
  {$EXTERNALSYM LPFN_GETSERVBYNAME}
  LPFN_GETSERVBYNAME = function ( const name, proto: PChar ): PServEnt; stdcall;
  {$EXTERNALSYM LPFN_GETPROTOBYNUMBER}
  LPFN_GETPROTOBYNUMBER = function ( const proto: Integer ): PProtoEnt; stdcall;
  {$EXTERNALSYM LPFN_GETPROTOBYNAME}
  LPFN_GETPROTOBYNAME = function ( const name: PChar ): PProtoEnt; stdcall;
  {$EXTERNALSYM LPFN_WSASETLASTERROR}
  LPFN_WSASETLASTERROR = procedure ( const iError: Integer ); stdcall;
  {$EXTERNALSYM LPFN_WSAGETLASTERROR}
  LPFN_WSAGETLASTERROR = function : Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAISBLOCKING}
  LPFN_WSAISBLOCKING = function : BOOL; stdcall;
  {$EXTERNALSYM LPFN_WSAUNHOOKBLOCKINGHOOK}
  LPFN_WSAUNHOOKBLOCKINGHOOK = function : Integer; stdcall;
  {$EXTERNALSYM LPFN_WSASETBLOCKINGHOOK}
  LPFN_WSASETBLOCKINGHOOK = function ( lpBlockFunc: TFarProc ): TFarProc; stdcall;
  {$EXTERNALSYM LPFN_WSACANCELBLOCKINGCALL}
  LPFN_WSACANCELBLOCKINGCALL = function : Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAASYNCGETSERVBYNAME}
  LPFN_WSAASYNCGETSERVBYNAME = function ( HWindow: HWND; wMsg: u_int; name, proto, buf: PChar; buflen: Integer ): THandle; stdcall;
  {$EXTERNALSYM LPFN_WSAASYNCGETSERVBYPORT}
  LPFN_WSAASYNCGETSERVBYPORT = function ( HWindow: HWND; wMsg, port: u_int; proto, buf: PChar; buflen: Integer ): THandle; stdcall;
  {$EXTERNALSYM LPFN_WSAASYNCGETPROTOBYNAME}
  LPFN_WSAASYNCGETPROTOBYNAME = function ( HWindow: HWND; wMsg: u_int; name, buf: PChar; buflen: Integer ): THandle; stdcall;
  {$EXTERNALSYM LPFN_WSAASYNCGETPROTOBYNUMBER}
  LPFN_WSAASYNCGETPROTOBYNUMBER = function ( HWindow: HWND; wMsg: u_int; number: Integer; buf: PChar; buflen: Integer ): THandle; stdcall;
  {$EXTERNALSYM LPFN_WSAASYNCGETHOSTBYNAME}
  LPFN_WSAASYNCGETHOSTBYNAME = function ( HWindow: HWND; wMsg: u_int; name, buf: PChar; buflen: Integer ): THandle; stdcall;
  {$EXTERNALSYM LPFN_WSAASYNCGETHOSTBYADDR}
  LPFN_WSAASYNCGETHOSTBYADDR = function ( HWindow: HWND; wMsg: u_int; addr: PChar; len, istruct: Integer; buf: PChar; buflen: Integer ): THandle; stdcall;
  {$EXTERNALSYM LPFN_WSACANCELASYNCREQUEST}
  LPFN_WSACANCELASYNCREQUEST = function ( hAsyncTaskHandle: THandle ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAASYNCSELECT}
  LPFN_WSAASYNCSELECT = function ( const s: TSocket; HWindow: HWND; wMsg: u_int; lEvent: Longint ): Integer; stdcall;
  {$EXTERNALSYM LPFN___WSAFDISSET}
  LPFN___WSAFDISSET = function ( const s: TSocket; var FDSet: TFDSet ): Bool; stdcall;

// WinSock 2 API new function prototypes
  {$EXTERNALSYM LPFN_WSAACCEPT}
  LPFN_WSAACCEPT = function ( const s : TSocket; addr : PSockAddr; addrlen : PInteger; lpfnCondition : LPCONDITIONPROC; const dwCallbackData : DWORD ): TSocket; stdcall;
  {$EXTERNALSYM LPFN_WSACLOSEEVENT}
  LPFN_WSACLOSEEVENT = function ( const hEvent : WSAEVENT ) : WordBool; stdcall;
  {$EXTERNALSYM LPFN_WSACONNECT}
  LPFN_WSACONNECT = function ( const s : TSocket; const name : PSockAddr; const namelen : Integer; lpCallerData,lpCalleeData : LPWSABUF; lpSQOS,lpGQOS : LPQOS ) : Integer; stdcall;
  {$EXTERNALSYM LPFN_WSACREATEEVENT}
  LPFN_WSACREATEEVENT  = function : WSAEVENT; stdcall;

  {$EXTERNALSYM LPFN_WSADUPLICATESOCKETA}
  LPFN_WSADUPLICATESOCKETA = function ( const s : TSocket; const dwProcessId : DWORD; lpProtocolInfo : LPWSAPROTOCOL_INFOA ) : Integer; stdcall;
  {$EXTERNALSYM LPFN_WSADUPLICATESOCKETW}
  LPFN_WSADUPLICATESOCKETW = function ( const s : TSocket; const dwProcessId : DWORD; lpProtocolInfo : LPWSAPROTOCOL_INFOW ) : Integer; stdcall;
  {$EXTERNALSYM LPFN_WSADUPLICATESOCKET}
{$IFDEF UNICODE}
  LPFN_WSADUPLICATESOCKET = LPFN_WSADUPLICATESOCKETW;
{$ELSE}
  LPFN_WSADUPLICATESOCKET = LPFN_WSADUPLICATESOCKETA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSAENUMNETWORKEVENTS}
  LPFN_WSAENUMNETWORKEVENTS = function ( const s : TSocket; const hEventObject : WSAEVENT; lpNetworkEvents : LPWSANETWORKEVENTS ) :Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAENUMPROTOCOLSA}
  LPFN_WSAENUMPROTOCOLSA = function ( lpiProtocols : PInteger; lpProtocolBuffer : LPWSAPROTOCOL_INFOA; var lpdwBufferLength : DWORD ) : Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAENUMPROTOCOLSW}
  LPFN_WSAENUMPROTOCOLSW = function ( lpiProtocols : PInteger; lpProtocolBuffer : LPWSAPROTOCOL_INFOW; var lpdwBufferLength : DWORD ) : Integer; stdcall;
  {$EXTERNALSYM lpfn_WSAENUMPROTOCOLS}
{$IFDEF UNICODE}
  LPFN_WSAENUMPROTOCOLS = LPFN_WSAENUMPROTOCOLSW;
{$ELSE}
  LPFN_WSAENUMPROTOCOLS = LPFN_WSAENUMPROTOCOLSA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSAEVENTSELECT}
  LPFN_WSAEVENTSELECT = function ( const s : TSocket; const hEventObject : WSAEVENT; lNetworkEvents : LongInt ): Integer; stdcall;

  {$EXTERNALSYM LPFN_WSAGETOVERLAPPEDRESULT}
  LPFN_WSAGETOVERLAPPEDRESULT = function ( const s : TSocket; lpOverlapped : LPWSAOVERLAPPED; lpcbTransfer : LPDWORD; fWait : BOOL; var lpdwFlags : DWORD ) : WordBool; stdcall;

  {$EXTERNALSYM LPFN_WSAGETQOSBYNAME}
  LPFN_WSAGETQOSBYNAME = function ( const s : TSocket; lpQOSName : LPWSABUF; lpQOS : LPQOS ): WordBool; stdcall;

  {$EXTERNALSYM LPFN_WSAHTONL}
  LPFN_WSAHTONL = function ( const s : TSocket; hostlong : u_long; var lpnetlong : DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAHTONS}
  LPFN_WSAHTONS = function ( const s : TSocket; hostshort : u_short; var lpnetshort : WORD ): Integer; stdcall;

  {$EXTERNALSYM LPFN_WSAIOCTL}
  LPFN_WSAIOCTL = function ( const s : TSocket; dwIoControlCode : DWORD; lpvInBuffer : Pointer; cbInBuffer : DWORD; lpvOutBuffer : Pointer; cbOutBuffer : DWORD;
    lpcbBytesReturned : LPDWORD; lpOverlapped : LPWSAOVERLAPPED; lpCompletionRoutine : LPWSAOVERLAPPED_COMPLETION_ROUTINE ) : Integer; stdcall;

  {$EXTERNALSYM LPFN_WSAJOINLEAF}
  LPFN_WSAJOINLEAF = function ( const s : TSocket; name : PSockAddr; namelen : Integer; lpCallerData,lpCalleeData : LPWSABUF;
      lpSQOS,lpGQOS : LPQOS; dwFlags : DWORD ) : TSocket; stdcall;

  {$EXTERNALSYM LPFN_WSANTOHL}
  LPFN_WSANTOHL = function ( const s : TSocket; netlong : u_long; var lphostlong : DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSANTOHS}
  LPFN_WSANTOHS = function ( const s : TSocket; netshort : u_short; var lphostshort : WORD ): Integer; stdcall;

  {$EXTERNALSYM LPFN_WSARECV}
  LPFN_WSARECV = function ( const s : TSocket; lpBuffers : LPWSABUF; dwBufferCount : DWORD; var lpNumberOfBytesRecvd : DWORD; var lpFlags : DWORD;
    lpOverlapped : LPWSAOVERLAPPED; lpCompletionRoutine : LPWSAOVERLAPPED_COMPLETION_ROUTINE ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSARECVDISCONNECT}
  LPFN_WSARECVDISCONNECT = function ( const s : TSocket; lpInboundDisconnectData : LPWSABUF ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSARECVFROM}
  LPFN_WSARECVFROM = function ( const s : TSocket; lpBuffers : LPWSABUF; dwBufferCount : DWORD; var lpNumberOfBytesRecvd : DWORD; var lpFlags : DWORD;
    lpFrom : PSockAddr; lpFromlen : PInteger; lpOverlapped : LPWSAOVERLAPPED; lpCompletionRoutine : LPWSAOVERLAPPED_COMPLETION_ROUTINE ): Integer; stdcall;

  {$EXTERNALSYM LPFN_WSARESETEVENT}
  LPFN_WSARESETEVENT = function ( hEvent : WSAEVENT ): WordBool; stdcall;

  {$EXTERNALSYM LPFN_WSASEND}
  LPFN_WSASEND = function ( const s : TSocket; lpBuffers : LPWSABUF; dwBufferCount : DWORD; var lpNumberOfBytesSent : DWORD; dwFlags : DWORD;
    lpOverlapped : LPWSAOVERLAPPED; lpCompletionRoutine : LPWSAOVERLAPPED_COMPLETION_ROUTINE ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSASENDDISCONNECT}
  LPFN_WSASENDDISCONNECT = function ( const s : TSocket; lpOutboundDisconnectData : LPWSABUF ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSASENDTO}
  LPFN_WSASENDTO = function ( const s : TSocket; lpBuffers : LPWSABUF; dwBufferCount : DWORD; var lpNumberOfBytesSent : DWORD; dwFlags : DWORD;
    lpTo : PSockAddr; iTolen : Integer; lpOverlapped : LPWSAOVERLAPPED; lpCompletionRoutine : LPWSAOVERLAPPED_COMPLETION_ROUTINE ): Integer; stdcall;

  {$EXTERNALSYM LPFN_WSASETEVENT}
  LPFN_WSASETEVENT = function ( hEvent : WSAEVENT ): WordBool; stdcall;

  {$EXTERNALSYM LPFN_WSASOCKETA}
  LPFN_WSASOCKETA = function ( af, iType, protocol : Integer; lpProtocolInfo : LPWSAPROTOCOL_INFOA; g : GROUP; dwFlags : DWORD ): TSocket; stdcall;
  {$EXTERNALSYM LPFN_WSASOCKETW}
  LPFN_WSASOCKETW = function ( af, iType, protocol : Integer; lpProtocolInfo : LPWSAPROTOCOL_INFOW; g : GROUP; dwFlags : DWORD ): TSocket; stdcall;
  {$EXTERNALSYM lpfn_WSASOCKET}
{$IFDEF UNICODE}
  LPFN_WSASOCKET = LPFN_WSASOCKETW;
{$ELSE}
  LPFN_WSASOCKET = LPFN_WSASOCKETA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSAWAITFORMULTIPLEEVENTS}
  LPFN_WSAWAITFORMULTIPLEEVENTS = function ( cEvents : DWORD; lphEvents : PWSAEVENT; fWaitAll : LongBool;
      dwTimeout : DWORD; fAlertable : LongBool ): DWORD; stdcall;

  {$EXTERNALSYM LPFN_WSAADDRESSTOSTRINGA}
  LPFN_WSAADDRESSTOSTRINGA = function ( lpsaAddress : PSockAddr; const dwAddressLength : DWORD; const lpProtocolInfo : LPWSAPROTOCOL_INFOA;
      const lpszAddressString : PChar; var lpdwAddressStringLength : DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAADDRESSTOSTRINGW}
  LPFN_WSAADDRESSTOSTRINGW = function ( lpsaAddress : PSockAddr; const dwAddressLength : DWORD; const lpProtocolInfo : LPWSAPROTOCOL_INFOW;
      const lpszAddressString : PWideChar; var lpdwAddressStringLength : DWORD ): Integer; stdcall;
  {$EXTERNALSYM lpfn_WSAADDRESSTOSTRING}
{$IFDEF UNICODE}
  LPFN_WSAADDRESSTOSTRING = LPFN_WSAADDRESSTOSTRINGW;
{$ELSE}
  LPFN_WSAADDRESSTOSTRING = LPFN_WSAADDRESSTOSTRINGA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSASTRINGTOADDRESSA}
  LPFN_WSASTRINGTOADDRESSA = function ( const AddressString : PChar; const AddressFamily: Integer; const lpProtocolInfo : LPWSAPROTOCOL_INFOA;
      var lpAddress : TSockAddr; var lpAddressLength : Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSASTRINGTOADDRESSW}
  LPFN_WSASTRINGTOADDRESSW = function ( const AddressString : PWideChar; const AddressFamily: Integer; const lpProtocolInfo : LPWSAPROTOCOL_INFOW;
      var lpAddress : TSockAddr; var lpAddressLength : Integer ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSASTRINGTOADDRESS}
{$IFDEF UNICODE}
  LPFN_WSASTRINGTOADDRESS = LPFN_WSASTRINGTOADDRESSW;
{$ELSE}
  LPFN_WSASTRINGTOADDRESS = LPFN_WSASTRINGTOADDRESSA;
{$ENDIF}

// Registration and Name Resolution API functions
  {$EXTERNALSYM LPFN_WSALOOKUPSERVICEBEGINA}
  LPFN_WSALOOKUPSERVICEBEGINA = function ( var qsRestrictions : TWSAQuerySetA; const dwControlFlags : DWORD; var hLookup : THandle ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSALOOKUPSERVICEBEGINw}
  LPFN_WSALOOKUPSERVICEBEGINW = function ( var qsRestrictions : TWSAQuerySetW; const dwControlFlags : DWORD; var hLookup : THandle ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSALOOKUPSERVICEBEGIN}
{$IFDEF UNICODE}
  LPFN_WSALOOKUPSERVICEBEGIN = LPFN_WSALOOKUPSERVICEBEGINW;
{$ELSE}
  LPFN_WSALOOKUPSERVICEBEGIN = LPFN_WSALOOKUPSERVICEBEGINA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSALOOKUPSERVICENEXTA}
  LPFN_WSALOOKUPSERVICENEXTA = function ( const hLookup : THandle; const dwControlFlags : DWORD; var dwBufferLength : DWORD; lpqsResults : PWSAQUERYSETA ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSALOOKUPSERVICENEXTW}
  LPFN_WSALOOKUPSERVICENEXTW = function ( const hLookup : THandle; const dwControlFlags : DWORD; var dwBufferLength : DWORD; lpqsResults : PWSAQUERYSETW ): Integer; stdcall;
  {$EXTERNALSYM lpfn_WSALOOKUPSERVICENEXT}
{$IFDEF UNICODE}
  LPFN_WSALOOKUPSERVICENEXT = LPFN_WSALOOKUPSERVICENEXTW;
{$ELSE}
  LPFN_WSALOOKUPSERVICENEXT = LPFN_WSALOOKUPSERVICENEXTA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSALOOKUPSERVICEEND}
  LPFN_WSALOOKUPSERVICEEND = function ( const hLookup : THandle ): Integer; stdcall;

  {$EXTERNALSYM LPFN_WSAINSTALLSERVICECLASSA}
  LPFN_WSAINSTALLSERVICECLASSA = function ( const lpServiceClassInfo : LPWSASERVICECLASSINFOA ) : Integer; stdcall;
  {$EXTERNALSYM lpfn_WSAINSTALLSERVICECLASSW}
  LPFN_WSAINSTALLSERVICECLASSW = function ( const lpServiceClassInfo : LPWSASERVICECLASSINFOW ) : Integer; stdcall;
  {$EXTERNALSYM lpfn_WSAINSTALLSERVICECLASS}
{$IFDEF UNICODE}
  LPFN_WSAINSTALLSERVICECLASS = LPFN_WSAINSTALLSERVICECLASSW;
{$ELSE}
  LPFN_WSAINSTALLSERVICECLASS = LPFN_WSAINSTALLSERVICECLASSA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSAREMOVESERVICECLASS}
  LPFN_WSAREMOVESERVICECLASS = function ( const lpServiceClassId : LPGUID ) : Integer; stdcall;

  {$EXTERNALSYM LPFN_WSAGETSERVICECLASSINFOA}
  LPFN_WSAGETSERVICECLASSINFOA = function ( const lpProviderId : LPGUID; const lpServiceClassId : LPGUID; var lpdwBufSize : DWORD;
      lpServiceClassInfo : LPWSASERVICECLASSINFOA ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAGETSERVICECLASSINFOW}
  LPFN_WSAGETSERVICECLASSINFOW = function ( const lpProviderId : LPGUID; const lpServiceClassId : LPGUID; var lpdwBufSize : DWORD;
      lpServiceClassInfo : LPWSASERVICECLASSINFOW ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAGETSERVICECLASSINFO}
{$IFDEF UNICODE}
  LPFN_WSAGETSERVICECLASSINFO = LPFN_WSAGETSERVICECLASSINFOW;
{$ELSE}
  LPFN_WSAGETSERVICECLASSINFO = LPFN_WSAGETSERVICECLASSINFOA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSAENUMNAMESPACEPROVIDERSA}
  LPFN_WSAENUMNAMESPACEPROVIDERSA = function ( var lpdwBufferLength: DWORD; const lpnspBuffer: LPWSANAMESPACE_INFOA ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAENUMNAMESPACEPROVIDERSW}
  LPFN_WSAENUMNAMESPACEPROVIDERSW = function ( var lpdwBufferLength: DWORD; const lpnspBuffer: LPWSANAMESPACE_INFOW ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAENUMNAMESPACEPROVIDERS}
{$IFDEF UNICODE}
  LPFN_WSAENUMNAMESPACEPROVIDERS = LPFN_WSAENUMNAMESPACEPROVIDERSW;
{$ELSE}
  LPFN_WSAENUMNAMESPACEPROVIDERS = LPFN_WSAENUMNAMESPACEPROVIDERSA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDA}
  LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDA = function ( const lpServiceClassId: LPGUID; lpszServiceClassName: PChar; var lpdwBufferLength: DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDW}
  LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDW = function ( const lpServiceClassId: LPGUID; lpszServiceClassName: PWideChar; var lpdwBufferLength: DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSAGETSERVICECLASSNAMEBYCLASSID}
{$IFDEF UNICODE}
  LPFN_WSAGETSERVICECLASSNAMEBYCLASSID = LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDW;
{$ELSE}
  LPFN_WSAGETSERVICECLASSNAMEBYCLASSID = LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSASETSERVICEA}
  LPFN_WSASETSERVICEA = function ( const lpqsRegInfo: LPWSAQUERYSETA; const essoperation: TWSAESetServiceOp; const dwControlFlags: DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSASETSERVICEW}
  LPFN_WSASETSERVICEW = function ( const lpqsRegInfo: LPWSAQUERYSETW; const essoperation: TWSAESetServiceOp; const dwControlFlags: DWORD ): Integer; stdcall;
  {$EXTERNALSYM LPFN_WSASETSERVICE}
{$IFDEF UNICODE}
  LPFN_WSASETSERVICE = LPFN_WSASETSERVICEW;
{$ELSE}
  LPFN_WSASETSERVICE = LPFN_WSASETSERVICEA;
{$ENDIF}

  {$EXTERNALSYM LPFN_WSAPROVIDERCONFIGCHANGE}
  LPFN_WSAPROVIDERCONFIGCHANGE = function ( var lpNotificationHandle : THandle; lpOverlapped : LPWSAOVERLAPPED; lpCompletionRoutine : LPWSAOVERLAPPED_COMPLETION_ROUTINE ) : Integer; stdcall;

{$ENDIF} // $IFDEF INCL_WINSOCK_API_TYPEDEFS

  //microsoft specific extension
  {$NODEFINE TTransmitFileProc}
  TTransmitFileProc = function (hSocket: TSocket; hFile: THandle; nNumberOfBytesToWrite: DWORD;
    nNumberOfBytesPerSend: DWORD; lpOverlapped: POVERLAPPED;
    lpTransmitBuffers: PTransmitFileBuffers; dwReserved: DWORD): BOOL; stdcall;
  {$NODEFINE TAcceptExProc}
  TAcceptExProc = function (sListenSocket, sAcceptSocket: TSocket;
    lpOutputBuffer: Pointer; dwReceiveDataLength, dwLocalAddressLength,
    dwRemoteAddressLength: DWORD; var lpdwBytesReceived: DWORD;
    lpOverlapped: POVERLAPPED): BOOL; stdcall;
  {$NODEFINE TGetAcceptExSockaddrsProc}
  TGetAcceptExSockaddrsProc = procedure (lpOutputBuffer: Pointer;
    dwReceiveDataLength, dwLocalAddressLength, dwRemoteAddressLength: DWORD;
    var LocalSockaddr: TSockAddr; var LocalSockaddrLength: Integer;
    var RemoteSockaddr: TSockAddr; var RemoteSockaddrLength: Integer); stdcall;
  {$NODEFINE TWSARecvExProc}
  TWSARecvExProc = function (s: TSocket; var buf; len: Integer; var flags: Integer): Integer; stdcall;

{$IFDEF WS2_DLL_FUNC_VARS}
var
  {$EXTERNALSYM WSACleanup}
  WSACleanup : LPFN_WSACLEANUP;
  {$EXTERNALSYM accept}
  accept : LPFN_ACCEPT;
  {$EXTERNALSYM bind}
  bind : LPFN_BIND;
  {$EXTERNALSYM closesocket}
  closesocket : LPFN_CLOSESOCKET;
  {$EXTERNALSYM connect}
  connect : LPFN_CONNECT;
  {$EXTERNALSYM ioctlsocket}
  ioctlsocket : LPFN_IOCTLSOCKET;
  {$EXTERNALSYM getpeername}
  getpeername : LPFN_GETPEERNAME;
  {$EXTERNALSYM getsockname}
  getsockname : LPFN_GETSOCKNAME;
  {$EXTERNALSYM getsockopt}
  getsockopt : LPFN_GETSOCKOPT;
  {$EXTERNALSYM htonl}
  htonl : LPFN_HTONL;
  {$EXTERNALSYM htons}
  htons : LPFN_HTONS;
  {$EXTERNALSYM inet_addr}
  inet_addr : LPFN_INET_ADDR;
  {$EXTERNALSYM inet_ntoa}
  inet_ntoa : LPFN_INET_NTOA;
  {$EXTERNALSYM listen}
  listen : LPFN_LISTEN;
  {$EXTERNALSYM ntohl}
  ntohl : LPFN_NTOHL;
  {$EXTERNALSYM ntohs}
  ntohs : LPFN_NTOHS;
  {$EXTERNALSYM recv}
  recv : LPFN_RECV;
  {$EXTERNALSYM recvfrom}
  recvfrom : LPFN_RECVFROM;
  {$EXTERNALSYM select}
  select : LPFN_SELECT;
  {$EXTERNALSYM send}
  send : LPFN_SEND;
  {$EXTERNALSYM sendto}
  sendto : LPFN_SENDTO;
  {$EXTERNALSYM setsockopt}
  setsockopt : LPFN_SETSOCKOPT;
  {$EXTERNALSYM shutdown}
  shutdown : LPFN_SHUTDOWN;
  {$EXTERNALSYM socket}
  socket : LPFN_SOCKET;
  {$EXTERNALSYM gethostbyaddr}
  gethostbyaddr : LPFN_GETHOSTBYADDR;
  {$EXTERNALSYM gethostbyname}
  gethostbyname : LPFN_GETHOSTBYNAME;
  {$EXTERNALSYM gethostname}
  gethostname : LPFN_GETHOSTNAME;
  {$EXTERNALSYM getservbyport}
  getservbyport : LPFN_GETSERVBYPORT;
  {$EXTERNALSYM getservbyname}
  getservbyname : LPFN_GETSERVBYNAME;
  {$EXTERNALSYM getprotobynumber}
  getprotobynumber : LPFN_GETPROTOBYNUMBER;
  {$EXTERNALSYM getprotobyname}
  getprotobyname : LPFN_GETPROTOBYNAME;
  {$EXTERNALSYM WSASetLastError}
  WSASetLastError : LPFN_WSASETLASTERROR;
  {$EXTERNALSYM WSAGetLastError}
  WSAGetLastError : LPFN_WSAGETLASTERROR;
  {$EXTERNALSYM WSAIsblocking}
  WSAIsBlocking : LPFN_WSAISBLOCKING;
  {$EXTERNALSYM WSAUnhookBlockingHook}
  WSAUnhookBlockingHook : LPFN_WSAUNHOOKBLOCKINGHOOK;
  {$EXTERNALSYM WSASetBlockingHook}
  WSASetBlockingHook : LPFN_WSASETBLOCKINGHOOK;
  {$EXTERNALSYM WSACancelBlockingCall}
  WSACancelBlockingCall : LPFN_WSACANCELBLOCKINGCALL;
  {$EXTERNALSYM WSAAsyncGetServByName}
  WSAAsyncGetServByName : LPFN_WSAASYNCGETSERVBYNAME;
  {$EXTERNALSYM WSAAsyncGetServByPort}
  WSAAsyncGetServByPort : LPFN_WSAASYNCGETSERVBYPORT;
  {$EXTERNALSYM WSAAsyncGetProtoByName}
  WSAAsyncGetProtoByName : LPFN_WSAASYNCGETPROTOBYNAME;
  {$EXTERNALSYM WSAAsyncGetProtoByNumber}
  WSAAsyncGetProtoByNumber : LPFN_WSAASYNCGETPROTOBYNUMBER;
  {$EXTERNALSYM WSAAsyncGetHostByName}
  WSAAsyncGetHostByName : LPFN_WSAASYNCGETHOSTBYNAME;
  {$EXTERNALSYM WSAAsyncGetHostByAddr}
  WSAAsyncGetHostByAddr : LPFN_WSAASYNCGETHOSTBYADDR;
  {$EXTERNALSYM WSACancelAsyncRequest}
  WSACancelAsyncRequest : LPFN_WSACANCELASYNCREQUEST;
  {$EXTERNALSYM WSAAsyncSelect}
  WSAAsyncSelect : LPFN_WSAASYNCSELECT;
  {$EXTERNALSYM __WSAFDIsSet}
  __WSAFDIsSet : LPFN___WSAFDISSET;
  {$EXTERNALSYM WSAAccept}
  WSAAccept : LPFN_WSAACCEPT;
  {$EXTERNALSYM WSACloseEvent}
  WSACloseEvent : LPFN_WSACLOSEEVENT;
  {$EXTERNALSYM WSAConnect}
  WSAConnect : LPFN_WSACONNECT;
  {$EXTERNALSYM WSACreateEvent}
  WSACreateEvent  : LPFN_WSACREATEEVENT ;
  {$EXTERNALSYM WSADuplicateSocketA}
  WSADuplicateSocketA : LPFN_WSADUPLICATESOCKETA;
  {$EXTERNALSYM WSADuplicateSocketW}
  WSADuplicateSocketW : LPFN_WSADUPLICATESOCKETW;
  {$EXTERNALSYM WSADuplicateSocket}
  WSADuplicateSocket : LPFN_WSADUPLICATESOCKET;
  {$EXTERNALSYM WSAEnumNetworkEvents}
  WSAEnumNetworkEvents : LPFN_WSAENUMNETWORKEVENTS;
  {$EXTERNALSYM WSAEnumProtocolsA}
  WSAEnumProtocolsA : LPFN_WSAENUMPROTOCOLSA;
  {$EXTERNALSYM WSAEnumProtocolsW}
  WSAEnumProtocolsW : LPFN_WSAENUMPROTOCOLSW;
  {$EXTERNALSYM WSAEnumProtocols}
  WSAEnumProtocols : LPFN_WSAENUMPROTOCOLS;
  {$EXTERNALSYM WSAEventSelect}
  WSAEventSelect : LPFN_WSAEVENTSELECT;
  {$EXTERNALSYM WSAGetOverlappedResult}
  WSAGetOverlappedResult : LPFN_WSAGETOVERLAPPEDRESULT;
  {$EXTERNALSYM WSAGetQosByName}
  WSAGetQosByName : LPFN_WSAGETQOSBYNAME;
  {$EXTERNALSYM WSAHtonl}
  WSAHtonl : LPFN_WSAHTONL;
  {$EXTERNALSYM WSAHtons}
  WSAHtons : LPFN_WSAHTONS;
  {$EXTERNALSYM WSAIoctl}
  WSAIoctl : LPFN_WSAIOCTL;
  {$EXTERNALSYM WSAJoinLeaf}
  WSAJoinLeaf : LPFN_WSAJOINLEAF;
  {$EXTERNALSYM WSANtohl}
  WSANtohl : LPFN_WSANTOHL;
  {$EXTERNALSYM WSANtohs}
  WSANtohs : LPFN_WSANTOHS;
  {$EXTERNALSYM WSARecv}
  WSARecv : LPFN_WSARECV;
  {$EXTERNALSYM WSARecvDisconnect}
  WSARecvDisconnect : LPFN_WSARECVDISCONNECT;
  {$EXTERNALSYM WSARecvFrom}
  WSARecvFrom : LPFN_WSARECVFROM;
  {$EXTERNALSYM WSAResetEvent}
  WSAResetEvent : LPFN_WSARESETEVENT;
  {$EXTERNALSYM WSASend}
  WSASend : LPFN_WSASEND;
  {$EXTERNALSYM WSASendDisconnect}
  WSASendDisconnect : LPFN_WSASENDDISCONNECT;
  {$EXTERNALSYM WSASendTo}
  WSASendTo : LPFN_WSASENDTO;
  {$EXTERNALSYM WSASetEvent}
  WSASetEvent : LPFN_WSASETEVENT;
  {$EXTERNALSYM WSASocketA}
  WSASocketA : LPFN_WSASOCKETA;
  {$EXTERNALSYM WSASocketW}
  WSASocketW : LPFN_WSASOCKETW;
  {$EXTERNALSYM WSASocket}
  WSASocket : LPFN_WSASOCKET;
  {$EXTERNALSYM WSAWaitForMultipleEvents}
  WSAWaitForMultipleEvents : LPFN_WSAWAITFORMULTIPLEEVENTS;
  {$EXTERNALSYM WSAAddressToStringA}
  WSAAddressToStringA : LPFN_WSAADDRESSTOSTRINGA;
  {$EXTERNALSYM WSAAddressToStringW}
  WSAAddressToStringW : LPFN_WSAADDRESSTOSTRINGW;
  {$EXTERNALSYM WSAAddressToString}
  WSAAddressToString : LPFN_WSAADDRESSTOSTRING;
  {$EXTERNALSYM WSAStringToAddressA}
  WSAStringToAddressA : LPFN_WSASTRINGTOADDRESSA;
  {$EXTERNALSYM WSAStringToAddressW}
  WSAStringToAddressW : LPFN_WSASTRINGTOADDRESSW;
  {$EXTERNALSYM WSAStringToAddress}
  WSAStringToAddress : LPFN_WSASTRINGTOADDRESS;
  {$EXTERNALSYM WSALookupServiceBeginA}
  WSALookupServiceBeginA : LPFN_WSALOOKUPSERVICEBEGINA;
  {$EXTERNALSYM WSALookupServiceBeginW}
  WSALookupServiceBeginW : LPFN_WSALOOKUPSERVICEBEGINW;
  {$EXTERNALSYM WSALookupServiceBegin}
  WSALookupServiceBegin : LPFN_WSALOOKUPSERVICEBEGIN;
  {$EXTERNALSYM WSALookupServiceNextA}
  WSALookupServiceNextA : LPFN_WSALOOKUPSERVICENEXTA;
  {$EXTERNALSYM WSALookupServiceNextW}
  WSALookupServiceNextW : LPFN_WSALOOKUPSERVICENEXTW;
  {$EXTERNALSYM WSALookupServiceNext}
  WSALookupServiceNext : LPFN_WSALOOKUPSERVICENEXT;
  {$EXTERNALSYM WSALookupServiceEnd}
  WSALookupServiceEnd : LPFN_WSALOOKUPSERVICEEND;
  {$EXTERNALSYM WSAInstallServiceClassA}
  WSAInstallServiceClassA : LPFN_WSAINSTALLSERVICECLASSA;
  {$EXTERNALSYM WSAInstallServiceClassW}
  WSAInstallServiceClassW : LPFN_WSAINSTALLSERVICECLASSW;
  {$EXTERNALSYM WSAInstallServiceClass}
  WSAInstallServiceClass : LPFN_WSAINSTALLSERVICECLASS;
  {$EXTERNALSYM WSARemoveServiceClass}
  WSARemoveServiceClass : LPFN_WSAREMOVESERVICECLASS;
  {$EXTERNALSYM WSAGetServiceClassInfoA}
  WSAGetServiceClassInfoA : LPFN_WSAGETSERVICECLASSINFOA;
  {$EXTERNALSYM WSAGetServiceClassInfoW}
  WSAGetServiceClassInfoW : LPFN_WSAGETSERVICECLASSINFOW;
  {$EXTERNALSYM WSAGetServiceClassInfo}
  WSAGetServiceClassInfo : LPFN_WSAGETSERVICECLASSINFO;
  {$EXTERNALSYM WSAEnumNameSpaceProvidersA}
  WSAEnumNameSpaceProvidersA : LPFN_WSAENUMNAMESPACEPROVIDERSA;
  {$EXTERNALSYM WSAEnumNameSpaceProvidersW}
  WSAEnumNameSpaceProvidersW : LPFN_WSAENUMNAMESPACEPROVIDERSW;
  {$EXTERNALSYM WSAEnumNameSpaceProviders}
  WSAEnumNameSpaceProviders : LPFN_WSAENUMNAMESPACEPROVIDERS;
  {$EXTERNALSYM WSAGetServiceClassNameByClassIdA}
  WSAGetServiceClassNameByClassIdA : LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDA;
  {$EXTERNALSYM WSAGetServiceClassNameByClassIdW}
  WSAGetServiceClassNameByClassIdW : LPFN_WSAGETSERVICECLASSNAMEBYCLASSIDW;
  {$EXTERNALSYM WSAGetServiceClassNameByClassId}
  WSAGetServiceClassNameByClassId : LPFN_WSAGETSERVICECLASSNAMEBYCLASSID;
  {$EXTERNALSYM WSASetServiceA}
  WSASetServiceA : LPFN_WSASETSERVICEA;
  {$EXTERNALSYM WSASetServiceW}
  WSASetServiceW : LPFN_WSASETSERVICEW;
  {$EXTERNALSYM WSASetService}
  WSASetService : LPFN_WSASETSERVICE;
  {$EXTERNALSYM WSAProviderConfigChange}
  WSAProviderConfigChange : LPFN_WSAPROVIDERCONFIGCHANGE;

  {$NODEFINE TransmitFile}
  TransmitFile :  TTransmitFileProc;
  {$NODEFINE AcceptEx}
  AcceptEx : TAcceptExProc;
  {$NODEFINE GetAcceptExSockaddrs}
  GetAcceptExSockaddrs : TGetAcceptExSockaddrsProc;
  {$NODEFINE WSARecvEx}
  WSARecvEx  : TWSARecvExProc;

{$ENDIF} // $IFDEF WS2_DLL_FUNC_VARS

{ Macros }
{$EXTERNALSYM WSAMakeSyncReply}
function WSAMakeSyncReply(Buflen, Error: Word): Longint;
{$EXTERNALSYM WSAMakeSelectReply}
function WSAMakeSelectReply(Event, Error: Word): Longint;
{$EXTERNALSYM WSAGetAsyncBuflen}
function WSAGetAsyncBuflen(Param: Longint): Word;
{$EXTERNALSYM WSAGetAsyncError}
function WSAGetAsyncError(Param: Longint): Word;
{$EXTERNALSYM WSAGetSelectEvent}
function WSAGetSelectEvent(Param: Longint): Word;
{$EXTERNALSYM WSAGetSelectError}
function WSAGetSelectError(Param: Longint): Word;

{$EXTERNALSYM FD_CLR}
procedure FD_CLR(Socket: TSocket; var FDSet: TFDSet);
{$EXTERNALSYM FD_ISSET}
function FD_ISSET(Socket: TSocket; var FDSet: TFDSet): Boolean;
{$EXTERNALSYM FD_SET}
procedure FD_SET(Socket: TSocket; var FDSet: TFDSet);
{$EXTERNALSYM FD_ZERO}
procedure FD_ZERO(var FDSet: TFDSet);

//=============================================================

{
	WS2TCPIP.H - WinSock2 Extension for TCP/IP protocols

	This file contains TCP/IP specific information for use
	by WinSock2 compatible applications.

	Copyright (c) 1995-1999  Microsoft Corporation

	To provide the backward compatibility, all the TCP/IP
	specific definitions that were included in the WINSOCK.H
	file are now included in WINSOCK2.H file. WS2TCPIP.H
	file includes only the definitions  introduced in the
	"WinSock 2 Protocol-Specific Annex" document.

	Rev 0.3	Nov 13, 1995
	Rev 0.4	Dec 15, 1996
}

type
// Argument structure for IP_ADD_MEMBERSHIP and IP_DROP_MEMBERSHIP
  {$EXTERNALSYM ip_mreq}
  ip_mreq = packed record
    imr_multiaddr : TInAddr; // IP multicast address of group
    imr_interface : TInAddr; // local IP address of interface
  end;

// Argument structure for IP_ADD_SOURCE_MEMBERSHIP, IP_DROP_SOURCE_MEMBERSHIP,
// IP_BLOCK_SOURCE, and IP_UNBLOCK_SOURCE
  {$EXTERNALSYM ip_mreq_source}
  ip_mreq_source = packed record
    imr_multiaddr: TInAddr;     // IP multicast address of group
    imr_sourceaddr: TInAddr;    // IP address of source
    imr_interface: TInAddr;     // local IP address of interface
  end;

// Argument structure for SIO_{GET,SET}_MULTICAST_FILTER
  {$EXTERNALSYM ip_msfilter}
  ip_msfilter = packed record
    imsf_multiaddr: TInAddr;    // IP multicast address of group
    imsf_interface: TInAddr;    // local IP address of interface
    imsf_fmode: u_long;         // filter mode - INCLUDE or EXCLUDE
    imsf_numsrc: u_long;        // number of sources in src_list
    imsf_slist: Array[0..0] of TInAddr;
  end;

  {$EXTERNALSYM IP_MSFILTER_SIZE}
function IP_MSFILTER_SIZE(numsrc: DWORD): DWORD;

// TCP/IP specific Ioctl codes
const
  {$EXTERNALSYM MCAST_INCLUDE}
  MCAST_INCLUDE             = 0;
  {$EXTERNALSYM MCAST_EXCLUDE}
  MCAST_EXCLUDE             = 1;

  {$EXTERNALSYM SIO_GET_INTERFACE_LIST}
  SIO_GET_INTERFACE_LIST    = IOC_OUT or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('t') shl 8) or 127;    {Do not Localize}
// New IOCTL with address size independent address array
  {$EXTERNALSYM SIO_GET_INTERFACE_LIST_EX}
  SIO_GET_INTERFACE_LIST_EX = IOC_OUT or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('t') shl 8) or 126;    {Do not Localize}
  {$EXTERNALSYM SIO_SET_MULTICAST_FILTER}
  SIO_SET_MULTICAST_FILTER  = IOC_IN or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('t') shl 8) or 125;    {Do not Localize}
  {$EXTERNALSYM SIO_GET_MULTICAST_FILTER}
  SIO_GET_MULTICAST_FILTER  = IOC_IN or ((SizeOf(u_long) and IOCPARM_MASK) shl 16) or (Ord('t') shl 8) or (124 or IOC_IN);    {Do not Localize}

// Options for use with [gs]etsockopt at the IP level.
  {$EXTERNALSYM IP_OPTIONS}
  IP_OPTIONS                =  1; // set/get IP options
  {$EXTERNALSYM IP_HDRINCL}
  IP_HDRINCL                =  2; // header is included with data
  {$EXTERNALSYM IP_TOS}
  IP_TOS                    =  3; // IP type of service and preced
  {$EXTERNALSYM IP_TTL}
  IP_TTL                    =  4; // IP time to live
  {$EXTERNALSYM IP_MULTICAST_IF}
  IP_MULTICAST_IF           =  9; // set/get IP multicast i/f
  {$EXTERNALSYM IP_MULTICAST_TTL}
  IP_MULTICAST_TTL          = 10; // set/get IP multicast ttl
  {$EXTERNALSYM IP_MULTICAST_LOOP}
  IP_MULTICAST_LOOP         = 11; // set/get IP multicast loopback
  {$EXTERNALSYM IP_ADD_MEMBERSHIP}
  IP_ADD_MEMBERSHIP         = 12; // add an IP group membership
  {$EXTERNALSYM IP_DROP_MEMBERSHIP}
  IP_DROP_MEMBERSHIP        = 13; // drop an IP group membership
  {$EXTERNALSYM IP_DONTFRAGMENT}
  IP_DONTFRAGMENT           = 14; // don't fragment IP datagrams    {Do not Localize}
  {$EXTERNALSYM IP_ADD_SOURCE_MEMBERSHIP}
  IP_ADD_SOURCE_MEMBERSHIP  = 15; // join IP group/source
  {$EXTERNALSYM IP_DROP_SOURCE_MEMBERSHIP}
  IP_DROP_SOURCE_MEMBERSHIP = 16; // leave IP group/source
  {$EXTERNALSYM IP_BLOCK_SOURCE}
  IP_BLOCK_SOURCE           = 17; // block IP group/source
  {$EXTERNALSYM IP_UNBLOCK_SOURCE}
  IP_UNBLOCK_SOURCE         = 18; // unblock IP group/source
  {$EXTERNALSYM IP_PKTINFO}
  IP_PKTINFO                = 19; // receive packet information for ipv4

  {$EXTERNALSYM IP_DEFAULT_MULTICAST_TTL}
  IP_DEFAULT_MULTICAST_TTL   = 1;    // normally limit m'casts to 1 hop    {Do not Localize}
  {$EXTERNALSYM IP_DEFAULT_MULTICAST_LOOP}
  IP_DEFAULT_MULTICAST_LOOP  = 1;    // normally hear sends if a member
  {$EXTERNALSYM IP_MAX_MEMBERSHIPS}
  IP_MAX_MEMBERSHIPS         = 20;   // per socket; must fit in one mbuf

  // Option to use with [gs]etsockopt at the IPPROTO_IPV6 level
  {$EXTERNALSYM IPV6_HDRINCL}
  IPV6_HDRINCL               = 2; // Header is included with data
  {$EXTERNALSYM IPV6_UNICAST_HOPS}
  IPV6_UNICAST_HOPS          = 4; // Set/get IP unicast hop limit
  {$EXTERNALSYM IPV6_MULTICAST_IF}
  IPV6_MULTICAST_IF          = 9; // Set/get IP multicast interface
  {$EXTERNALSYM IPV6_MULTICAST_HOPS}
  IPV6_MULTICAST_HOPS        = 10; // Set/get IP multicast ttl
  {$EXTERNALSYM IPV6_MULTICAST_LOOP}
  IPV6_MULTICAST_LOOP        = 11; // Set/get IP multicast loopback
  {$EXTERNALSYM IPV6_ADD_MEMBERSHIP}
  IPV6_ADD_MEMBERSHIP        = 12; // Add an IP group membership
  {$EXTERNALSYM IPV6_DROP_MEMBERSHIP}
  IPV6_DROP_MEMBERSHIP       = 13; // Drop an IP group membership
  {$EXTERNALSYM IPV6_JOIN_GROUP}
  IPV6_JOIN_GROUP            = IPV6_ADD_MEMBERSHIP;
  {$EXTERNALSYM IPV6_LEAVE_GROUP}
  IPV6_LEAVE_GROUP           = IPV6_DROP_MEMBERSHIP;
  {$EXTERNALSYM IPV6_PKTINFO}
  IPV6_PKTINFO               = 19; // Receive packet information for ipv6

  // Option to use with [gs]etsockopt at the IPPROTO_UDP level
  {$EXTERNALSYM UDP_NOCHECKSUM}
  UDP_NOCHECKSUM             = 1;
  {$EXTERNALSYM UDP_CHECKSUM_COVERAGE}
  UDP_CHECKSUM_COVERAGE      = 20; // Set/get UDP-Lite checksum coverage

// Option to use with [gs]etsockopt at the IPPROTO_TCP level
  {$EXTERNALSYM TCP_EXPEDITED_1122}
  TCP_EXPEDITED_1122         = $0002;

// IPv6 definitions
type
  {$EXTERNALSYM IN6_ADDR}
  IN6_ADDR = packed record
    case Integer of
      0: (s6_addr: array[0..15] of u_char);
      1: (word: array[0..7] of u_short);
  end;
  TIn6Addr   = IN6_ADDR;
  PIn6Addr   = ^TIn6Addr;
  {$EXTERNALSYM PIN6_ADDR}
  PIN6_ADDR  = ^PIn6Addr;
  {$EXTERNALSYM LPIN6_ADDR}
  LPIN6_ADDR = PIN6_ADDR;

  // Argument structure for IPV6_JOIN_GROUP and IPV6_LEAVE_GROUP
  {$EXTERNALSYM ipv6_mreq}
  ipv6_mreq = packed record
    ipv6mr_multiaddr: TIn6Addr; // IPv6 multicast address
    ipv6mr_interface: u_int; // Interface index
  end;

  // Old IPv6 socket address structure (retained for sockaddr_gen definition below)
  {$EXTERNALSYM sockaddr_in6_old}
  sockaddr_in6_old = packed record
    sin6_family   : Smallint;         // AF_INET6
    sin6_port     : u_short;          // Transport level port number
    sin6_flowinfo : u_long;           // IPv6 flow information
    sin6_addr     : TIn6Addr;         // IPv6 address
  end;

// IPv6 socket address structure, RFC 2553
  {$EXTERNALSYM SOCKADDR_IN6}
  SOCKADDR_IN6 = packed record
    sin6_family   : Smallint;         // AF_INET6
    sin6_port     : u_short;          // Transport level port number
    sin6_flowinfo : u_long;           // IPv6 flow information
    sin6_addr     : TIn6Addr;         // IPv6 address
    sin6_scope_id : u_long;           // set of interfaces for a scope
  end;

  TSockAddrIn6   = SOCKADDR_IN6;
  PSockAddrIn6   = ^TSockAddrIn6;
  {$EXTERNALSYM PSOCKADDR_IN6}
  PSOCKADDR_IN6  = PSockAddrIn6;
  {$EXTERNALSYM LPSOCKADDR_IN6}
  LPSOCKADDR_IN6 = PSOCKADDR_IN6;

  {$EXTERNALSYM sockaddr_gen}
  sockaddr_gen = packed record
    case Integer of
      1 : ( Address : TSockAddr; );
      2 : ( AddressIn : TSockAddrIn; );
      3 : ( AddressIn6 : sockaddr_in6_old; );
  end;
  TSockAddrGen = sockaddr_gen;

// Structure to keep interface specific information
  {$EXTERNALSYM INTERFACE_INFO}
  INTERFACE_INFO = packed record
    iiFlags            : u_long;       // Interface flags
    iiAddress          : TSockAddrGen; // Interface address
    iiBroadcastAddress : TSockAddrGen; // Broadcast address
    iiNetmask          : TSockAddrGen; // Network mask
  end;
  TInterface_Info  = INTERFACE_INFO;
  PINTERFACE_INFO = ^TInterface_Info;
  {$EXTERNALSYM LPINTERFACE_INFO}
  LPINTERFACE_INFO = PINTERFACE_INFO;

// New structure that does not have dependency on the address size
  {$EXTERNALSYM INTERFACE_INFO_EX}
  INTERFACE_INFO_EX = packed record
    iiFlags            : u_long;          // Interface flags
    iiAddress          : TSocket_Address; // Interface address
    iiBroadcastAddress : TSocket_Address; // Broadcast address
    iiNetmask          : TSocket_Address; // Network mask
  end;
  TInterface_Info_Ex  = INTERFACE_INFO_EX;
  PINTERFACE_INFO_EX = ^TInterface_Info_Ex;
  {$EXTERNALSYM LPINTERFACE_INFO_EX}
  LPINTERFACE_INFO_EX = PINTERFACE_INFO_EX;

// Macro that works for both IPv4 and IPv6
{$EXTERNALSYM SS_PORT}
function SS_PORT(ssp: PSockAddrIn): u_short;

{$EXTERNALSYM IN6ADDR_ANY_INIT}
function IN6ADDR_ANY_INIT: TIn6Addr;
{$EXTERNALSYM IN6ADDR_LOOPBACK_INIT}
function IN6ADDR_LOOPBACK_INIT: TIn6Addr;

{$EXTERNALSYM IN6ADDR_SETANY}
procedure IN6ADDR_SETANY(sa: PSockAddrIn6);
{$EXTERNALSYM IN6ADDR_SETLOOPBACK}
procedure IN6ADDR_SETLOOPBACK(sa: PSockAddrIn6);
{$EXTERNALSYM IN6ADDR_ISANY}
function IN6ADDR_ISANY(sa: PSockAddrIn6): Boolean;
{$EXTERNALSYM IN6ADDR_ISLOOPBACK}
function IN6ADDR_ISLOOPBACK(sa: PSockAddrIn6): Boolean;

{$EXTERNALSYM IN6_ADDR_EQUAL}
function IN6_ADDR_EQUAL(const a: PIn6Addr; const b: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_UNSPECIFIED}
function IN6_IS_ADDR_UNSPECIFIED(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_LOOPBACK}
function IN6_IS_ADDR_LOOPBACK(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_MULTICAST}
function IN6_IS_ADDR_MULTICAST(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_LINKLOCAL}
function IN6_IS_ADDR_LINKLOCAL(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_SITELOCAL}
function IN6_IS_ADDR_SITELOCAL(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_V4MAPPED}
function IN6_IS_ADDR_V4MAPPED(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_V4COMPAT}
function IN6_IS_ADDR_V4COMPAT(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_MC_NODELOCAL}
function IN6_IS_ADDR_MC_NODELOCAL(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_MC_LINKLOCAL}
function IN6_IS_ADDR_MC_LINKLOCAL(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_MC_SITELOCAL}
function IN6_IS_ADDR_MC_SITELOCAL(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_MC_ORGLOCAL}
function IN6_IS_ADDR_MC_ORGLOCAL(const a: PIn6Addr): Boolean;
{$EXTERNALSYM IN6_IS_ADDR_MC_GLOBAL}
function IN6_IS_ADDR_MC_GLOBAL(const a: PIn6Addr): Boolean;

// Possible flags for the  iiFlags - bitmask
const
  {$EXTERNALSYM IFF_UP}
  IFF_UP           = $00000001;  // Interface is up
  {$EXTERNALSYM IFF_BROADCAST}
  IFF_BROADCAST    = $00000002;  // Broadcast is  supported
  {$EXTERNALSYM IFF_LOOPBACK}
  IFF_LOOPBACK     = $00000004;  // this is loopback interface
  {$EXTERNALSYM IFF_POINTTOPOINT}
  IFF_POINTTOPOINT = $00000008;  // this is point-to-point interface
  {$EXTERNALSYM IFF_MULTICAST}
  IFF_MULTICAST    = $00000010;  // multicast is supported

type
// structure for IP_PKTINFO option
  {$EXTERNALSYM IN_PKTINFO}
  IN_PKTINFO = packed record
    ipi_addr    : TInAddr;  // destination IPv4 address
    ipi_ifindex : UINT;    // received interface index
  end;
  TInPktInfo = IN_PKTINFO;

// structure for IPV6_PKTINFO option
  {$EXTERNALSYM IN6_PKTINFO}
  IN6_PKTINFO = packed record
    ipi6_addr       : TIn6Addr; // destination IPv6 address
    ipi6_ifindex    : UINT;     // received interface index
  end;
  TIn6PktInfo = IN6_PKTINFO;

// Error codes from getaddrinfo()
const
  {$EXTERNALSYM EAI_AGAIN}
  EAI_AGAIN             = WSATRY_AGAIN;
  {$EXTERNALSYM EAI_BADFLAGS}
  EAI_BADFLAGS          = WSAEINVAL;
  {$EXTERNALSYM EAI_FAIL}
  EAI_FAIL              = WSANO_RECOVERY;
  {$EXTERNALSYM EAI_FAMILY}
  EAI_FAMILY            = WSAEAFNOSUPPORT;
  {$EXTERNALSYM EAI_MEMORY}
  EAI_MEMORY            = WSA_NOT_ENOUGH_MEMORY;
//  {$EXTERNALSYM EAI_NODATA}
//  EAI_NODATA           = WSANO_DATA;
  {$EXTERNALSYM EAI_NONAME}
  EAI_NONAME            = WSAHOST_NOT_FOUND;
  {$EXTERNALSYM EAI_SERVICE}
  EAI_SERVICE           = WSATYPE_NOT_FOUND;
  {$EXTERNALSYM EAI_SOCKTYPE}
  EAI_SOCKTYPE          = WSAESOCKTNOSUPPORT;

//  DCR_FIX:  EAI_NODATA remove or fix
//
//  EAI_NODATA was removed from rfc2553bis
//  need to find out from the authors why and
//  determine the error for "no records of this type"
//  temporarily, we'll keep #define to avoid changing
//  code that could change back;  use NONAME
  {$EXTERNALSYM EAI_NODATA}
  EAI_NODATA            = EAI_NONAME;

// Structure used in getaddrinfo() call
type
  PAddrInfo = ^TAddrInfo;
  {$EXTERNALSYM ADDRINFO}
  ADDRINFO = packed record
    ai_flags        : Integer;      // AI_PASSIVE, AI_CANONNAME, AI_NUMERICHOST
    ai_family       : Integer;      // PF_xxx
    ai_socktype     : Integer;      // SOCK_xxx
    ai_protocol     : Integer;      // 0 or IPPROTO_xxx for IPv4 and IPv6
    ai_addrlen      : ULONG;        // Length of ai_addr
    ai_canonname    : PChar;        // Canonical name for nodename
    ai_addr         : PSockAddr;    // Binary address
    ai_next         : PAddrInfo;    // Next structure in linked list
  end;
  TAddrInfo = ADDRINFO;
  {$EXTERNALSYM LPADDRINFO}
  LPADDRINFO = PAddrInfo;

// Flags used in "hints" argument to getaddrinfo()
const
  {$EXTERNALSYM AI_PASSIVE}
  AI_PASSIVE            = $1;   // Socket address will be used in bind() call
  {$EXTERNALSYM AI_CANONNAME}
  AI_CANONNAME          = $2;   // Return canonical name in first ai_canonname
  {$EXTERNALSYM AI_NUMERICHOST}
  AI_NUMERICHOST        = $4;   // Nodename must be a numeric address string

var
  {$EXTERNALSYM in6addr_any}
  in6addr_any: TIn6Addr;
  {$EXTERNALSYM in6addr_loopback}
  in6addr_loopback: TIn6Addr;

//=============================================================

{
	wsipx.h

	Microsoft Windows
	Copyright (C) Microsoft Corporation, 1992-1999.

	Windows Sockets include file for IPX/SPX.  This file contains all
	standardized IPX/SPX information.  Include this header file after
	winsock.h.

	To open an IPX socket, call socket() with an address family of
	AF_IPX, a socket type of SOCK_DGRAM, and protocol NSPROTO_IPX.
	Note that the protocol value must be specified, it cannot be 0.
	All IPX packets are sent with the packet type field of the IPX
	header set to 0.

	To open an SPX or SPXII socket, call socket() with an address
	family of AF_IPX, socket type of SOCK_SEQPACKET or SOCK_STREAM,
	and protocol of NSPROTO_SPX or NSPROTO_SPXII.  If SOCK_SEQPACKET
	is specified, then the end of message bit is respected, and
	recv() calls are not completed until a packet is received with
	the end of message bit set.  If SOCK_STREAM is specified, then
	the end of message bit is not respected, and recv() completes
	as soon as any data is received, regardless of the setting of the
	end of message bit.  Send coalescing is never performed, and sends
	smaller than a single packet are always sent with the end of
	message bit set.  Sends larger than a single packet are packetized
	with the end of message bit set on only the last packet of the
	send.
}


// This is the structure of the SOCKADDR structure for IPX and SPX.
type
  {$EXTERNALSYM SOCKADDR_IPX}
  SOCKADDR_IPX = packed record
    sa_family : u_short;
    sa_netnum : Array [0..3] of Char;
    sa_nodenum : Array [0..5] of Char;
    sa_socket : u_short;
  end;
  TSockAddr_IPX = SOCKADDR_IPX;
  TSockAddrIPX = SOCKADDR_IPX;
  PSockAddrIPX = ^TSockAddrIPX;
  {$EXTERNALSYM PSOCKADDR_IPX}
  PSOCKADDR_IPX = PSockAddrIPX;
  {$EXTERNALSYM LPSOCKADDR_IPX}
  LPSOCKADDR_IPX = PSOCKADDR_IPX;

//  Protocol families used in the "protocol" parameter of the socket() API.
const
  {$EXTERNALSYM NSPROTO_IPX}
  NSPROTO_IPX   = 1000;
  {$EXTERNALSYM NSPROTO_SPX}
  NSPROTO_SPX   = 1256;
  {$EXTERNALSYM NSPROTO_SPXII}
  NSPROTO_SPXII = 1257;


//=============================================================

{
	wsnwlink.h

	Microsoft Windows
	Copyright (C) Microsoft Corporation, 1992-1999.
		Microsoft-specific extensions to the Windows NT IPX/SPX Windows
		Sockets interface.  These extensions are provided for use as
		necessary for compatibility with existing applications.  They are
		otherwise not recommended for use, as they are only guaranteed to
		work     over the Microsoft IPX/SPX stack.  An application which
		uses these     extensions may not work over other IPX/SPX
		implementations.  Include this header file after winsock.h and
		wsipx.h.

		To open an IPX socket where a particular packet type is sent in
		the IPX header, specify NSPROTO_IPX + n as the protocol parameter
		of the socket() API.  For example, to open an IPX socket that
		sets the packet type to 34, use the following socket() call:

			s = socket(AF_IPX, SOCK_DGRAM, NSPROTO_IPX + 34);
}

// Below are socket option that may be set or retrieved by specifying
// the appropriate manifest in the "optname" parameter of getsockopt()
// or setsockopt().  Use NSPROTO_IPX as the "level" argument for the
// call.
const

//	Set/get the IPX packet type.  The value specified in the
//	optval argument will be set as the packet type on every IPX
//	packet sent from this socket.  The optval parameter of
//	getsockopt()/setsockopt() points to an int.
  {$EXTERNALSYM IPX_PTYPE}
  IPX_PTYPE = $4000;

//	Set/get the receive filter packet type.  Only IPX packets with
//	a packet type equal to the value specified in the optval
//	argument will be returned; packets with a packet type that
//	does not match are discarded.  optval points to an int.
  {$EXTERNALSYM IPX_FILTERPTYPE}
  IPX_FILTERPTYPE = $4001;

//	Stop filtering on packet type set with IPX_FILTERPTYPE.
  {$EXTERNALSYM IPX_STOPFILTERPTYPE}
  IPX_STOPFILTERPTYPE = $4003;

//	Set/get the value of the datastream field in the SPX header on
//	every packet sent.  optval points to an int.
  {$EXTERNALSYM IPX_DSTYPE}
  IPX_DSTYPE = $4002;

//	Enable extended addressing.  On sends, adds the element
//	"unsigned char sa_ptype" to the SOCKADDR_IPX structure,
//	making the total length 15 bytes.  On receives, add both
//	the sa_ptype and "unsigned char sa_flags" to the SOCKADDR_IPX
//	structure, making the total length 16 bytes.  The current
//	bits defined in sa_flags are:
//		0x01 - the received frame was sent as a broadcast
//		0x02 - the received frame was sent from this machine
//	optval points to a BOOL.
  {$EXTERNALSYM IPX_EXTENDED_ADDRESS}
  IPX_EXTENDED_ADDRESS = $4004;

//	Send protocol header up on all receive packets.  optval points
//	to a BOOL.
  {$EXTERNALSYM IPX_RECVHDR}
  IPX_RECVHDR = $4005;

//	Get the maximum data size that can be sent.  Not valid with
//	setsockopt().  optval points to an int where the value is
//	returned.
  {$EXTERNALSYM IPX_MAXSIZE}
  IPX_MAXSIZE = $4006;

//	Query information about a specific adapter that IPX is bound
//	to.  In a system with n adapters they are numbered 0 through n-1.
//	Callers can issue the IPX_MAX_ADAPTER_NUM getsockopt() to find
//	out the number of adapters present, or call IPX_ADDRESS with
//	increasing values of adapternum until it fails.  Not valid
//	with setsockopt().  optval points to an instance of the
//	IPX_ADDRESS_DATA structure with the adapternum filled in.
  {$EXTERNALSYM IPX_ADDRESS}
  IPX_ADDRESS = $4007;

type
  {$EXTERNALSYM IPX_ADDRESS_DATA}
  IPX_ADDRESS_DATA = packed record
    adapternum : Integer;                 // input: 0-based adapter number
    netnum     : Array [0..3] of Byte;    // output: IPX network number
    nodenum    : Array [0..5] of Byte;    // output: IPX node address
    wan        : Boolean;                 // output: TRUE = adapter is on a wan link
    status     : Boolean;                 // output: TRUE = wan link is up (or adapter is not wan)
    maxpkt     : Integer;                 // output: max packet size, not including IPX header
    linkspeed  : ULONG;                   // output: link speed in 100 bytes/sec (i.e. 96 == 9600 bps)
  end;
  TIPXAddressData = IPX_ADDRESS_DATA;
  PIPXAddressData = ^TIPXAddressData;
  {$EXTERNALSYM PIPX_ADDRESS_DATA}
  PIPX_ADDRESS_DATA = PIPXAddressData;

const
//	Query information about a specific IPX network number.  If the
//	network is in IPX's cache it will return the information directly,    {Do not Localize}
//	otherwise it will issue RIP requests to find it.  Not valid with
//	setsockopt().  optval points to an instance of the IPX_NETNUM_DATA
//	structure with the netnum filled in.
  {$EXTERNALSYM IPX_GETNETINFO}
  IPX_GETNETINFO = $4008;

type
  {$EXTERNALSYM IPX_NETNUM_DATA}
  IPX_NETNUM_DATA = packed record
    netnum   : Array [0..3] of Byte;  // input: IPX network number
    hopcount : Word;                  // output: hop count to this network, in machine order
    netdelay : Word;                  // output: tick count to this network, in machine order
    cardnum  : Integer;               // output: 0-based adapter number used to route to this net;
                                      //         can be used as adapternum input to IPX_ADDRESS
    router   : Array [0..5] of Byte;  // output: MAC address of the next hop router, zeroed if
                                      //         the network is directly attached
  end;
  TIPXNetNumData = IPX_NETNUM_DATA;
  PIPXNetNumData = ^TIPXNetNumData;
  {$EXTERNALSYM PIPX_NETNUM_DATA}
  PIPX_NETNUM_DATA = PIPXNetNumData;

const
//	Like IPX_GETNETINFO except it  does not  issue RIP requests. If the
//	network is in IPX's cache it will return the information, otherwise    {Do not Localize}
//	it will fail (see also IPX_RERIPNETNUMBER which  always  forces a
//	re-RIP). Not valid with setsockopt().  optval points to an instance of
//	the IPX_NETNUM_DATA structure with the netnum filled in.
  {$EXTERNALSYM IPX_GETNETINFO_NORIP}
  IPX_GETNETINFO_NORIP = $4009;

//	Get information on a connected SPX socket.  optval points
//	to an instance of the IPX_SPXCONNSTATUS_DATA structure.
//  *** All numbers are in Novell (high-low) order. ***
  {$EXTERNALSYM IPX_SPXGETCONNECTIONSTATUS}
  IPX_SPXGETCONNECTIONSTATUS = $400B;

type
  {$EXTERNALSYM IPX_SPXCONNSTATUS_DATA}
  IPX_SPXCONNSTATUS_DATA = packed record
    ConnectionState         : Byte;
    WatchDogActive          : Byte;
    LocalConnectionId       : Word;
    RemoteConnectionId      : Word;
    LocalSequenceNumber     : Word;
    LocalAckNumber          : Word;
    LocalAllocNumber        : Word;
    RemoteAckNumber         : Word;
    RemoteAllocNumber       : Word;
    LocalSocket             : Word;
    ImmediateAddress        : Array [0..5] of Byte;
    RemoteNetwork           : Array [0..3] of Byte;
    RemoteNode              : Array [0..5] of Byte;
    RemoteSocket            : Word;
    RetransmissionCount     : Word;
    EstimatedRoundTripDelay : Word;                 // In milliseconds
    RetransmittedPackets    : Word;
    SuppressedPacket        : Word;
  end;
  TIPXSPXConnStatusData = IPX_SPXCONNSTATUS_DATA;
  PIPXSPXConnStatusData = ^TIPXSPXConnStatusData;
  {$EXTERNALSYM PIPX_SPXCONNSTATUS_DATA}
  PIPX_SPXCONNSTATUS_DATA = PIPXSPXConnStatusData;

const
//	Get notification when the status of an adapter that IPX is
//	bound to changes.  Typically this will happen when a wan line
//	goes up or down.  Not valid with setsockopt().  optval points
//	to a buffer which contains an IPX_ADDRESS_DATA structure
//	followed immediately by a HANDLE to an unsignaled event.
//
//	When the getsockopt() query is submitted, it will complete
//	successfully.  However, the IPX_ADDRESS_DATA pointed to by
//	optval will not be updated at that point.  Instead the
//	request is queued internally inside the transport.
//
//	When the status of an adapter changes, IPX will locate a
//	queued getsockopt() query and fill in all the fields in the
//	IPX_ADDRESS_DATA structure.  It will then signal the event
//	pointed to by the HANDLE in the optval buffer.  This handle
//	should be obtained before calling getsockopt() by calling
//	CreateEvent().  If multiple getsockopts() are submitted at
//	once, different events must be used.
//
//	The event is used because the call needs to be asynchronous
//	but currently getsockopt() does not support this.
//
//	WARNING: In the current implementation, the transport will
//	only signal one queued query for each status change.  Therefore
//	only one service which uses this query should be running at
//	once.
  {$EXTERNALSYM IPX_ADDRESS_NOTIFY}
  IPX_ADDRESS_NOTIFY = $400C;

//	Get the maximum number of adapters present.  If this call returns
//	n then the adapters are numbered 0 through n-1.  Not valid
//	with setsockopt().  optval points to an int where the value
//	is returned.
  {$EXTERNALSYM IPX_MAX_ADAPTER_NUM}
  IPX_MAX_ADAPTER_NUM = $400D;

//	Like IPX_GETNETINFO except it forces IPX to re-RIP even if the
//	network is in its cache (but not if it is directly attached to).
//	Not valid with setsockopt().  optval points to an instance of
//	the IPX_NETNUM_DATA structure with the netnum filled in.
  {$EXTERNALSYM IPX_RERIPNETNUMBER}
  IPX_RERIPNETNUMBER = $400E;

//	A hint that broadcast packets may be received.  The default is
//	TRUE.  Applications that do not need to receive broadcast packets
//	should set this sockopt to FALSE which may cause better system
//	performance (note that it does not necessarily cause broadcasts
//	to be filtered for the application).  Not valid with getsockopt().
//	optval points to a BOOL.
  {$EXTERNALSYM IPX_RECEIVE_BROADCAST}
  IPX_RECEIVE_BROADCAST = $400F;

//	On SPX connections, don't delay before sending ack.  Applications    {Do not Localize}
//	that do not tend to have back-and-forth traffic over SPX should
//	set this; it will increase the number of acks sent but will remove
//	delays in sending acks.  optval points to a BOOL.
  {$EXTERNALSYM IPX_IMMEDIATESPXACK}
  IPX_IMMEDIATESPXACK = $4010;


//=============================================================

//	wsnetbs.h
//	Copyright (c) 1994-1999, Microsoft Corp. All rights reserved.
//
//	Windows Sockets include file for NETBIOS.  This file contains all
//	standardized NETBIOS information.  Include this header file after
//	winsock.h.

//	To open a NetBIOS socket, call the socket() function as follows:
//
//		s = socket( AF_NETBIOS, {SOCK_SEQPACKET|SOCK_DGRAM}, -Lana );
//
//	where Lana is the NetBIOS Lana number of interest.  For example, to
//	open a socket for Lana 2, specify -2 as the "protocol" parameter
//	to the socket() function.


//	This is the structure of the SOCKADDR structure for NETBIOS.

const
  {$EXTERNALSYM NETBIOS_NAME_LENGTH}
  NETBIOS_NAME_LENGTH = 16;

type
  {$EXTERNALSYM SOCKADDR_NB}
  SOCKADDR_NB = packed record
    snb_family : Smallint;
    snb_type   : u_short;
    snb_name   : array[0..NETBIOS_NAME_LENGTH-1] of Char;
  end;
  TSockAddrNB  = SOCKADDR_NB;
  PSockAddrNB  = ^TSockAddrNB;
  {$EXTERNALSYM PSOCKADDR_NB}
  PSOCKADDR_NB = PSockAddrNB;
  {$EXTERNALSYM LPSOCKADDR_NB}
  LPSOCKADDR_NB = PSOCKADDR_NB;

//	Bit values for the snb_type field of SOCKADDR_NB.
const
  {$EXTERNALSYM NETBIOS_UNIQUE_NAME}
  NETBIOS_UNIQUE_NAME       = $0000;
  {$EXTERNALSYM NETBIOS_GROUP_NAME}
  NETBIOS_GROUP_NAME        = $0001;
  {$EXTERNALSYM NETBIOS_TYPE_QUICK_UNIQUE}
  NETBIOS_TYPE_QUICK_UNIQUE = $0002;
  {$EXTERNALSYM NETBIOS_TYPE_QUICK_GROUP}
  NETBIOS_TYPE_QUICK_GROUP  = $0003;

//	A macro convenient for setting up NETBIOS SOCKADDRs.
{$EXTERNALSYM SET_NETBIOS_SOCKADDR}
procedure SET_NETBIOS_SOCKADDR( snb : PSockAddrNB; const SnbType : Word; const Name : PChar; const Port : Char );



//=============================================================

//  Copyright 1997 - 1998 Microsoft Corporation
//
//  Module Name:
//
//  	ws2atm.h
//
//  Abstract:
//
//  	Winsock 2 ATM Annex definitions.

const
  {$EXTERNALSYM ATMPROTO_AALUSER}
  ATMPROTO_AALUSER = $00; // User-defined AAL
  {$EXTERNALSYM ATMPROTO_AAL1}
  ATMPROTO_AAL1    = $01; // AAL 1
  {$EXTERNALSYM ATMPROTO_AAL2}
  ATMPROTO_AAL2    = $02; // AAL 2
  {$EXTERNALSYM ATMPROTO_AAL34}
  ATMPROTO_AAL34   = $03; // AAL 3/4
  {$EXTERNALSYM ATMPROTO_AAL5}
  ATMPROTO_AAL5    = $05; // AAL 5

  {$EXTERNALSYM SAP_FIELD_ABSENT}
  SAP_FIELD_ABSENT        = $FFFFFFFE;
  {$EXTERNALSYM SAP_FIELD_ANY}
  SAP_FIELD_ANY           = $FFFFFFFF;
  {$EXTERNALSYM SAP_FIELD_ANY_AESA_SEL}
  SAP_FIELD_ANY_AESA_SEL  = $FFFFFFFA;
  {$EXTERNALSYM SAP_FIELD_ANY_AESA_REST}
  SAP_FIELD_ANY_AESA_REST = $FFFFFFFB;

  // values used for AddressType in struct ATM_ADDRESS
  {$EXTERNALSYM ATM_E164}
  ATM_E164 = $01; // E.164 addressing scheme
  {$EXTERNALSYM ATM_NSAP}
  ATM_NSAP = $02; // NSAP-style ATM Endsystem Address scheme
  {$EXTERNALSYM ATM_AESA}
  ATM_AESA = $02; // NSAP-style ATM Endsystem Address scheme

  {$EXTERNALSYM ATM_ADDR_SIZE}
  ATM_ADDR_SIZE = 20;

type
  {$EXTERNALSYM ATM_ADDRESS}
  ATM_ADDRESS = packed record
    AddressType : DWORD;                        // E.164 or NSAP-style ATM Endsystem Address
    NumofDigits : DWORD;                        // number of digits;
    Addr : Array[0..ATM_ADDR_SIZE-1] of Byte; // IA5 digits for E164, BCD encoding for NSAP
                                                // format as defined in the ATM Forum UNI 3.1
  end;

// values used for Layer2Protocol in B-LLI
const
  {$EXTERNALSYM BLLI_L2_ISO_1745}
  BLLI_L2_ISO_1745       = $01; // Basic mode ISO 1745
  {$EXTERNALSYM BLLI_L2_Q921}
  BLLI_L2_Q921           = $02; // CCITT Rec. Q.921
  {$EXTERNALSYM BLLI_L2_X25L}
  BLLI_L2_X25L           = $06; // CCITT Rec. X.25, link layer
  {$EXTERNALSYM BLLI_L2_X25M}
  BLLI_L2_X25M           = $07; // CCITT Rec. X.25, multilink
  {$EXTERNALSYM BLLI_L2_ELAPB}
  BLLI_L2_ELAPB          = $08; // Extended LAPB; for half duplex operation
  {$EXTERNALSYM BLLI_L2_HDLC_NRM}
  BLLI_L2_HDLC_NRM       = $09; // HDLC NRM (ISO 4335)
  {$EXTERNALSYM BLLI_L2_HDLC_ABM}
  BLLI_L2_HDLC_ABM       = $0A; // HDLC ABM (ISO 4335)
  {$EXTERNALSYM BLLI_L2_HDLC_ARM}
  BLLI_L2_HDLC_ARM       = $0B; // HDLC ARM (ISO 4335)
  {$EXTERNALSYM BLLI_L2_LLC}
  BLLI_L2_LLC            = $0C; // LAN logical link control (ISO 8802/2)
  {$EXTERNALSYM BLLI_L2_X75}
  BLLI_L2_X75            = $0D; // CCITT Rec. X.75, single link procedure
  {$EXTERNALSYM BLLI_L2_Q922}
  BLLI_L2_Q922           = $0E; // CCITT Rec. Q.922
  {$EXTERNALSYM BLLI_L2_USER_SPECIFIED}
  BLLI_L2_USER_SPECIFIED = $10; // User Specified
  {$EXTERNALSYM BLLI_L2_ISO_7776}
  BLLI_L2_ISO_7776       = $11; // ISO 7776 DTE-DTE operation

// values used for Layer3Protocol in B-LLI
  {$EXTERNALSYM BLLI_L3_X25}
  BLLI_L3_X25            = $06; // CCITT Rec. X.25, packet layer
  {$EXTERNALSYM BLLI_L3_ISO_8208}
  BLLI_L3_ISO_8208       = $07; // ISO/IEC 8208 (X.25 packet layer for DTE
  {$EXTERNALSYM BLLI_L3_X223}
  BLLI_L3_X223           = $08; // X.223/ISO 8878
  {$EXTERNALSYM BLLI_L3_SIO_8473}
  BLLI_L3_SIO_8473       = $09; // ISO/IEC 8473 (OSI connectionless)
  {$EXTERNALSYM BLLI_L3_T70}
  BLLI_L3_T70            = $0A; // CCITT Rec. T.70 min. network layer
  {$EXTERNALSYM BLLI_L3_ISO_TR9577}
  BLLI_L3_ISO_TR9577     = $0B; // ISO/IEC TR 9577 Network Layer Protocol ID
  {$EXTERNALSYM BLLI_L3_USER_SPECIFIED}
  BLLI_L3_USER_SPECIFIED = $10; // User Specified

// values used for Layer3IPI in B-LLI
  {$EXTERNALSYM BLLI_L3_IPI_SNAP}
  BLLI_L3_IPI_SNAP = $80; // IEEE 802.1 SNAP identifier
  {$EXTERNALSYM BLLI_L3_IPI_IP}
  BLLI_L3_IPI_IP   = $CC; // Internet Protocol (IP) identifier

type
  {$EXTERNALSYM ATM_BLLI}
  ATM_BLLI = packed record
    // Identifies the layer-two protocol.
    // Corresponds to the User information layer 2 protocol field in the B-LLI information element.
    // A value of SAP_FIELD_ABSENT indicates that this field is not used, and a value of SAP_FIELD_ANY means wildcard.
    Layer2Protocol              : DWORD; // User information layer 2 protocol
    // Identifies the user-specified layer-two protocol.
    // Only used if the Layer2Protocol parameter is set to BLLI_L2_USER_SPECIFIED.
    // The valid values range from zero–127.
    // Corresponds to the User specified layer 2 protocol information field in the B-LLI information element.
    Layer2UserSpecifiedProtocol : DWORD; // User specified layer 2 protocol information
    // Identifies the layer-three protocol.
    // Corresponds to the User information layer 3 protocol field in the B-LLI information element.
    // A value of SAP_FIELD_ABSENT indicates that this field is not used, and a value of SAP_FIELD_ANY means wildcard.
    Layer3Protocol              : DWORD; // User information layer 3 protocol
    // Identifies the user-specified layer-three protocol.
    // Only used if the Layer3Protocol parameter is set to BLLI_L3_USER_SPECIFIED.
    // The valid values range from zero–127.
    // Corresponds to the User specified layer 3 protocol information field in the B-LLI information element.
    Layer3UserSpecifiedProtocol : DWORD; // User specified layer 3 protocol information
    // Identifies the layer-three Initial Protocol Identifier.
    // Only used if the Layer3Protocol parameter is set to BLLI_L3_ISO_TR9577.
    // Corresponds to the ISO/IEC TR 9577 Initial Protocol Identifier field in the B-LLI information element.
    Layer3IPI                   : DWORD; // ISO/IEC TR 9577 Initial Protocol Identifier
    // Identifies the 802.1 SNAP identifier.
    // Only used if the Layer3Protocol parameter is set to BLLI_L3_ISO_TR9577 and Layer3IPI is set to BLLI_L3_IPI_SNAP,
    // indicating an IEEE 802.1 SNAP identifier. Corresponds to the OUI and PID fields in the B-LLI information element.
    SnapID                      : Array[0..4] of Byte; // SNAP ID consisting of OUI and PID
  end;

// values used for the HighLayerInfoType field in ATM_BHLI
const
  {$EXTERNALSYM BHLI_ISO}
  BHLI_ISO                 = $00; // ISO
  {$EXTERNALSYM BHLI_UserSpecific}
  BHLI_UserSpecific        = $01; // User Specific
  {$EXTERNALSYM BHLI_HighLayerProfile}
  BHLI_HighLayerProfile    = $02; // High layer profile (only in UNI3.0)
  {$EXTERNALSYM BHLI_VendorSpecificAppId}
  BHLI_VendorSpecificAppId = $03; // Vendor-Specific Application ID

type
  {$EXTERNALSYM ATM_BHLI}
  ATM_BHLI = packed record
    // Identifies the high layer information type field in the B-LLI information element.
    // Note that the type BHLI_HighLayerProfile has been eliminated in UNI 3.1.
    // A value of SAP_FIELD_ABSENT indicates that B-HLI is not present, and a value of SAP_FIELD_ANY means wildcard.
    HighLayerInfoType   : DWORD; // High Layer Information Type
    // Identifies the number of bytes from one to eight in the HighLayerInfo array.
    // Valid values include eight for the cases of BHLI_ISO and BHLI_UserSpecific,
    // four for BHLI_HighLayerProfile, and seven for BHLI_VendorSpecificAppId.
    HighLayerInfoLength : DWORD; // number of bytes in HighLayerInfo
    // Identifies the high layer information field in the B-LLI information element.
    // In the case of HighLayerInfoType being BHLI_VendorSpecificAppId,
    // the first 3 bytes consist of a globally-administered Organizationally Unique Identifier (OUI)
    // (as per IEEE standard 802-1990), followed by a 4-byte application identifier,
    // which is administered by the vendor identified by the OUI.
    // Value for the case of BHLI_UserSpecific is user defined and requires bilateral agreement between two end users.
    HighLayerInfo       : Array[0..7] of Byte; // the value dependent on the HighLayerInfoType field
  end;

// A new address family, AF_ATM, is introduced for native ATM services,
// and the corresponding SOCKADDR structure, sockaddr_atm, is defined in the following.
// To open a socket for native ATM services, parameters in socket should contain
// AF_ATM, SOCK_RAW, and ATMPROTO_AAL5 or ATMPROTO_AALUSER, respectively.
  {$EXTERNALSYM SOCKADDR_ATM}
  SOCKADDR_ATM = packed record
    // Identifies the address family, which is AF_ATM in this case.
    satm_family : u_short;
    // Identifies the ATM address that could be either in E.164 or NSAP-style ATM End Systems Address format.
    // This field will be mapped to the Called Party Number IE (Information Element)
    // if it is specified in bind and WSPBind for a listening socket, or in connect, WSAConnect, WSPConnect,
    // WSAJoinLeaf, or WSPJoinLeaf for a connecting socket.
    // It will be mapped to the Calling Party Number IE if specified in bind and WSPBind for a connecting socket.
    satm_number : ATM_ADDRESS;
    // Identifies the fields in the B-LLI Information Element that are used along with satm_bhli to identify an application.
    // Note that the B-LLI layer two information is treated as not present
    // if its Layer2Protocol field contains SAP_FIELD_ABSENT, or as a wildcard if it contains SAP_FIELD_ANY.
    // Similarly, the B-LLI layer three information is treated as not present
    // if its Layer3Protocol field contains SAP_FIELD_ABSENT, or as a wildcard if it contains SAP_FIELD_ANY.
    satm_blli   : ATM_BLLI;    // B-LLI
    // Identifies the fields in the B-HLI Information Element that are used along with satm_blli to identify an application.
    satm_bhli   : ATM_BHLI;    // B-HLI
  end;
  TSockAddrATM = SOCKADDR_ATM;
  PSockAddrATM = ^TSockAddrATM;
  LPSockAddrATM = PSockAddrATM;
  {$EXTERNALSYM PSOCKADDR_ATM}
  PSOCKADDR_ATM = ^SOCKADDR_ATM;
  {$EXTERNALSYM LPSOCKADDR_ATM}
  LPSOCKADDR_ATM = ^SOCKADDR_ATM;

  {$EXTERNALSYM Q2931_IE_TYPE}
  Q2931_IE_TYPE = ( IE_AALParameters, IE_TrafficDescriptor,
    IE_BroadbandBearerCapability, IE_BHLI, IE_BLLI,IE_CalledPartyNumber,
    IE_CalledPartySubaddress, IE_CallingPartyNumber, IE_CallingPartySubaddress,
    IE_Cause, IE_QOSClass, IE_TransitNetworkSelection
  );

  {$EXTERNALSYM Q2931_IE}
  Q2931_IE = record
    IEType   : Q2931_IE_TYPE;
    IELength : ULONG;
    IE       : Array[0..0] of Byte;
  end;

// manifest constants for the AALType field in struct AAL_PARAMETERS_IE
  {$EXTERNALSYM AAL_TYPE}
  AAL_TYPE = LongInt;

const
  {$EXTERNALSYM AALTYPE_5}
  AALTYPE_5    =  5; // AAL 5
  {$EXTERNALSYM AALTYPE_USER}
  AALTYPE_USER = 16; // user-defined AAL

  // values used for the Mode field in struct AAL5_PARAMETERS
  {$EXTERNALSYM AAL5_MODE_MESSAGE}
  AAL5_MODE_MESSAGE   = $01;
  {$EXTERNALSYM AAL5_MODE_STREAMING}
  AAL5_MODE_STREAMING = $02;

// values used for the SSCSType field in struct AAL5_PARAMETERS
  {$EXTERNALSYM AAL5_SSCS_NULL}
  AAL5_SSCS_NULL              = $00;
  {$EXTERNALSYM AAL5_SSCS_SSCOP_ASSURED}
  AAL5_SSCS_SSCOP_ASSURED     = $01;
  {$EXTERNALSYM AAL5_SSCS_SSCOP_NON_ASSURED}
  AAL5_SSCS_SSCOP_NON_ASSURED = $02;
  {$EXTERNALSYM AAL5_SSCS_FRAME_RELAY}
  AAL5_SSCS_FRAME_RELAY       = $04;

type
  {$EXTERNALSYM AAL5_PARAMETERS}
  AAL5_PARAMETERS = packed record
    ForwardMaxCPCSSDUSize  : ULONG;
    BackwardMaxCPCSSDUSize : ULONG;
    Mode     : Byte; // only available in UNI 3.0
    SSCSType : Byte;
  end;

  {$EXTERNALSYM AALUSER_PARAMETERS}
  AALUSER_PARAMETERS = packed record
    UserDefined : ULONG;
  end;

  {$EXTERNALSYM AAL_PARAMETERS_IE}
  AAL_PARAMETERS_IE = packed record
    AALType : AAL_TYPE;
    case Byte of
      0: ( AAL5Parameters    : AAL5_PARAMETERS );
      1: ( AALUserParameters : AALUSER_PARAMETERS );
  end;

  {$EXTERNALSYM ATM_TD}
  ATM_TD = packed record
    PeakCellRate_CLP0         : ULONG;
    PeakCellRate_CLP01        : ULONG;
    SustainableCellRate_CLP0  : ULONG;
    SustainableCellRate_CLP01 : ULONG;
    MaxBurstSize_CLP0         : ULONG;
    MaxBurstSize_CLP01        : ULONG;
    Tagging                   : LongBool;
  end;

  {$EXTERNALSYM ATM_TRAFFIC_DESCRIPTOR_IE}
  ATM_TRAFFIC_DESCRIPTOR_IE = packed record
    Forward    : ATM_TD;
    Backward   : ATM_TD;
    BestEffort : LongBool;
  end;

// values used for the BearerClass field in struct ATM_BROADBAND_BEARER_CAPABILITY_IE
const
  {$EXTERNALSYM BCOB_A}
  BCOB_A = $01; // Bearer class A
  {$EXTERNALSYM BCOB_C}
  BCOB_C = $03; // Bearer class C
  {$EXTERNALSYM BCOB_X}
  BCOB_X = $10; // Bearer class X

// values used for the TrafficType field in struct ATM_BROADBAND_BEARER_CAPABILITY_IE
  {$EXTERNALSYM TT_NOIND}
  TT_NOIND = $00; // No indication of traffic type
  {$EXTERNALSYM TT_CBR}
  TT_CBR   = $04; // Constant bit rate
  {$EXTERNALSYM TT_VBR}
  TT_VBR   = $06; // Variable bit rate

// values used for the TimingRequirements field in struct ATM_BROADBAND_BEARER_CAPABILITY_IE
  {$EXTERNALSYM TR_NOIND}
  TR_NOIND         = $00; // No timing requirement indication
  {$EXTERNALSYM TR_END_TO_END}
  TR_END_TO_END    = $01; // End-to-end timing required
  {$EXTERNALSYM TR_NO_END_TO_END}
  TR_NO_END_TO_END = $02; // End-to-end timing not required

// values used for the ClippingSusceptability field in struct ATM_BROADBAND_BEARER_CAPABILITY_IE
  {$EXTERNALSYM CLIP_NOT}
  CLIP_NOT = $00; // Not susceptible to clipping
  {$EXTERNALSYM CLIP_SUS}
  CLIP_SUS = $20; // Susceptible to clipping

// values used for the UserPlaneConnectionConfig field in struct ATM_BROADBAND_BEARER_CAPABILITY_IE
  {$EXTERNALSYM UP_P2P}
  UP_P2P  = $00; // Point-to-point connection
  {$EXTERNALSYM UP_P2MP}
  UP_P2MP = $01; // Point-to-multipoint connection

type
  {$EXTERNALSYM ATM_BROADBAND_BEARER_CAPABILITY_IE}
  ATM_BROADBAND_BEARER_CAPABILITY_IE = packed record
    BearerClass : Byte;
    TrafficType : Byte;
    TimingRequirements        : Byte;
    ClippingSusceptability    : Byte;
    UserPlaneConnectionConfig : Byte;
  end;
  {$EXTERNALSYM ATM_BHLI_IE}
  ATM_BHLI_IE = ATM_BHLI;

// values used for the Layer2Mode field in struct ATM_BLLI_IE
const
  {$EXTERNALSYM BLLI_L2_MODE_NORMAL}
  BLLI_L2_MODE_NORMAL = $40;
  {$EXTERNALSYM BLLI_L2_MODE_EXT}
  BLLI_L2_MODE_EXT    = $80;

// values used for the Layer3Mode field in struct ATM_BLLI_IE
  {$EXTERNALSYM BLLI_L3_MODE_NORMAL}
  BLLI_L3_MODE_NORMAL = $40;
  {$EXTERNALSYM BLLI_L3_MODE_EXT}
  BLLI_L3_MODE_EXT    = $80;

// values used for the Layer3DefaultPacketSize field in struct ATM_BLLI_IE
  {$EXTERNALSYM BLLI_L3_PACKET_16}
  BLLI_L3_PACKET_16   = $04;
  {$EXTERNALSYM BLLI_L3_PACKET_32}
  BLLI_L3_PACKET_32   = $05;
  {$EXTERNALSYM BLLI_L3_PACKET_64}
  BLLI_L3_PACKET_64   = $06;
  {$EXTERNALSYM BLLI_L3_PACKET_128}
  BLLI_L3_PACKET_128  = $07;
  {$EXTERNALSYM BLLI_L3_PACKET_256}
  BLLI_L3_PACKET_256  = $08;
  {$EXTERNALSYM BLLI_L3_PACKET_512}
  BLLI_L3_PACKET_512  = $09;
  {$EXTERNALSYM BLLI_L3_PACKET_1024}
  BLLI_L3_PACKET_1024 = $0A;
  {$EXTERNALSYM BLLI_L3_PACKET_2048}
  BLLI_L3_PACKET_2048 = $0B;
  {$EXTERNALSYM BLLI_L3_PACKET_4096}
  BLLI_L3_PACKET_4096 = $0C;

type
  {$EXTERNALSYM ATM_BLLI_IE}
  ATM_BLLI_IE = record
    Layer2Protocol              : DWORD; // User information layer 2 protocol
    Layer2Mode                  : Byte;
    Layer2WindowSize            : Byte;
    Layer2UserSpecifiedProtocol : DWORD; // User specified layer 2 protocol information
    Layer3Protocol              : DWORD; // User information layer 3 protocol
    Layer3Mode                  : Byte;
    Layer3DefaultPacketSize     : Byte;
    Layer3PacketWindowSize      : Byte;
    Layer3UserSpecifiedProtocol : DWORD; // User specified layer 3 protocol information
    Layer3IPI                   : DWORD; // ISO/IEC TR 9577 Initial Protocol Identifier
    SnapID       : Array[0..4] of Byte;  // SNAP ID consisting of OUI and PID
  end;
  {$EXTERNALSYM ATM_CALLED_PARTY_NUMBER_IE}
  ATM_CALLED_PARTY_NUMBER_IE = ATM_ADDRESS;
  {$EXTERNALSYM ATM_CALLED_PARTY_SUBADDRESS_IE}
  ATM_CALLED_PARTY_SUBADDRESS_IE = ATM_ADDRESS;

// values used for the Presentation_Indication field in struct ATM_CALLING_PARTY_NUMBER_IE
const
  {$EXTERNALSYM PI_ALLOWED}
  PI_ALLOWED              = $00;
  {$EXTERNALSYM PI_RESTRICTED}
  PI_RESTRICTED           = $40;
  {$EXTERNALSYM PI_NUMBER_NOT_AVAILABLE}
  PI_NUMBER_NOT_AVAILABLE = $80;

// values used for the Screening_Indicator field in struct ATM_CALLING_PARTY_NUMBER_IE
  {$EXTERNALSYM SI_USER_NOT_SCREENED}
  SI_USER_NOT_SCREENED = $00;
  {$EXTERNALSYM SI_USER_PASSED}
  SI_USER_PASSED       = $01;
  {$EXTERNALSYM SI_USER_FAILED}
  SI_USER_FAILED       = $02;
  {$EXTERNALSYM SI_NETWORK}
  SI_NETWORK           = $03;

type
  {$EXTERNALSYM ATM_CALLING_PARTY_NUMBER_IE}
  ATM_CALLING_PARTY_NUMBER_IE = record
    ATM_Number              : ATM_ADDRESS;
    Presentation_Indication : Byte;
    Screening_Indicator     : Byte;
  end;
  {$EXTERNALSYM ATM_CALLING_PARTY_SUBADDRESS_IE}
  ATM_CALLING_PARTY_SUBADDRESS_IE = ATM_ADDRESS;

// values used for the Location field in struct ATM_CAUSE_IE
const
  {$EXTERNALSYM CAUSE_LOC_USER}
  CAUSE_LOC_USER                  = $00;
  {$EXTERNALSYM CAUSE_LOC_PRIVATE_LOCAL}
  CAUSE_LOC_PRIVATE_LOCAL         = $01;
  {$EXTERNALSYM CAUSE_LOC_PUBLIC_LOCAL}
  CAUSE_LOC_PUBLIC_LOCAL          = $02;
  {$EXTERNALSYM CAUSE_LOC_TRANSIT_NETWORK}
  CAUSE_LOC_TRANSIT_NETWORK       = $03;
  {$EXTERNALSYM CAUSE_LOC_PUBLIC_REMOTE}
  CAUSE_LOC_PUBLIC_REMOTE         = $04;
  {$EXTERNALSYM CAUSE_LOC_PRIVATE_REMOTE}
  CAUSE_LOC_PRIVATE_REMOTE        = $05;
  {$EXTERNALSYM CAUSE_LOC_INTERNATIONAL_NETWORK}
  CAUSE_LOC_INTERNATIONAL_NETWORK = $06;
  {$EXTERNALSYM CAUSE_LOC_BEYOND_INTERWORKING}
  CAUSE_LOC_BEYOND_INTERWORKING   = $0A;

// values used for the Cause field in struct ATM_CAUSE_IE
  {$EXTERNALSYM CAUSE_UNALLOCATED_NUMBER}
  CAUSE_UNALLOCATED_NUMBER                = $01;
  {$EXTERNALSYM CAUSE_NO_ROUTE_TO_TRANSIT_NETWORK}
  CAUSE_NO_ROUTE_TO_TRANSIT_NETWORK       = $02;
  {$EXTERNALSYM CAUSE_NO_ROUTE_TO_DESTINATION}
  CAUSE_NO_ROUTE_TO_DESTINATION           = $03;
  {$EXTERNALSYM CAUSE_VPI_VCI_UNACCEPTABLE}
  CAUSE_VPI_VCI_UNACCEPTABLE              = $0A;
  {$EXTERNALSYM CAUSE_NORMAL_CALL_CLEARING}
  CAUSE_NORMAL_CALL_CLEARING              = $10;
  {$EXTERNALSYM CAUSE_USER_BUSY}
  CAUSE_USER_BUSY                         = $11;
  {$EXTERNALSYM CAUSE_NO_USER_RESPONDING}
  CAUSE_NO_USER_RESPONDING                = $12;
  {$EXTERNALSYM CAUSE_CALL_REJECTED}
  CAUSE_CALL_REJECTED                     = $15;
  {$EXTERNALSYM CAUSE_NUMBER_CHANGED}
  CAUSE_NUMBER_CHANGED                    = $16;
  {$EXTERNALSYM CAUSE_USER_REJECTS_CLIR}
  CAUSE_USER_REJECTS_CLIR                 = $17;
  {$EXTERNALSYM CAUSE_DESTINATION_OUT_OF_ORDER}
  CAUSE_DESTINATION_OUT_OF_ORDER          = $1B;
  {$EXTERNALSYM CAUSE_INVALID_NUMBER_FORMAT}
  CAUSE_INVALID_NUMBER_FORMAT             = $1C;
  {$EXTERNALSYM CAUSE_STATUS_ENQUIRY_RESPONSE}
  CAUSE_STATUS_ENQUIRY_RESPONSE           = $1E;
  {$EXTERNALSYM CAUSE_NORMAL_UNSPECIFIED}
  CAUSE_NORMAL_UNSPECIFIED                = $1F;
  {$EXTERNALSYM CAUSE_VPI_VCI_UNAVAILABLE}
  CAUSE_VPI_VCI_UNAVAILABLE               = $23;
  {$EXTERNALSYM CAUSE_NETWORK_OUT_OF_ORDER}
  CAUSE_NETWORK_OUT_OF_ORDER              = $26;
  {$EXTERNALSYM CAUSE_TEMPORARY_FAILURE}
  CAUSE_TEMPORARY_FAILURE                 = $29;
  {$EXTERNALSYM CAUSE_ACCESS_INFORMAION_DISCARDED}
  CAUSE_ACCESS_INFORMAION_DISCARDED       = $2B;
  {$EXTERNALSYM CAUSE_NO_VPI_VCI_AVAILABLE}
  CAUSE_NO_VPI_VCI_AVAILABLE              = $2D;
  {$EXTERNALSYM CAUSE_RESOURCE_UNAVAILABLE}
  CAUSE_RESOURCE_UNAVAILABLE              = $2F;
  {$EXTERNALSYM CAUSE_QOS_UNAVAILABLE}
  CAUSE_QOS_UNAVAILABLE                   = $31;
  {$EXTERNALSYM CAUSE_USER_CELL_RATE_UNAVAILABLE}
  CAUSE_USER_CELL_RATE_UNAVAILABLE        = $33;
  {$EXTERNALSYM CAUSE_BEARER_CAPABILITY_UNAUTHORIZED}
  CAUSE_BEARER_CAPABILITY_UNAUTHORIZED    = $39;
  {$EXTERNALSYM CAUSE_BEARER_CAPABILITY_UNAVAILABLE}
  CAUSE_BEARER_CAPABILITY_UNAVAILABLE     = $3A;
  {$EXTERNALSYM CAUSE_OPTION_UNAVAILABLE}
  CAUSE_OPTION_UNAVAILABLE                = $3F;
  {$EXTERNALSYM CAUSE_BEARER_CAPABILITY_UNIMPLEMENTED}
  CAUSE_BEARER_CAPABILITY_UNIMPLEMENTED   = $41;
  {$EXTERNALSYM CAUSE_UNSUPPORTED_TRAFFIC_PARAMETERS}
  CAUSE_UNSUPPORTED_TRAFFIC_PARAMETERS    = $49;
  {$EXTERNALSYM CAUSE_INVALID_CALL_REFERENCE}
  CAUSE_INVALID_CALL_REFERENCE            = $51;
  {$EXTERNALSYM CAUSE_CHANNEL_NONEXISTENT}
  CAUSE_CHANNEL_NONEXISTENT               = $52;
  {$EXTERNALSYM CAUSE_INCOMPATIBLE_DESTINATION}
  CAUSE_INCOMPATIBLE_DESTINATION          = $58;
  {$EXTERNALSYM CAUSE_INVALID_ENDPOINT_REFERENCE}
  CAUSE_INVALID_ENDPOINT_REFERENCE        = $59;
  {$EXTERNALSYM CAUSE_INVALID_TRANSIT_NETWORK_SELECTION}
  CAUSE_INVALID_TRANSIT_NETWORK_SELECTION = $5B;
  {$EXTERNALSYM CAUSE_TOO_MANY_PENDING_ADD_PARTY}
  CAUSE_TOO_MANY_PENDING_ADD_PARTY        = $5C;
  {$EXTERNALSYM CAUSE_AAL_PARAMETERS_UNSUPPORTED}
  CAUSE_AAL_PARAMETERS_UNSUPPORTED        = $5D;
  {$EXTERNALSYM CAUSE_MANDATORY_IE_MISSING}
  CAUSE_MANDATORY_IE_MISSING              = $60;
  {$EXTERNALSYM CAUSE_UNIMPLEMENTED_MESSAGE_TYPE}
  CAUSE_UNIMPLEMENTED_MESSAGE_TYPE        = $61;
  {$EXTERNALSYM CAUSE_UNIMPLEMENTED_IE}
  CAUSE_UNIMPLEMENTED_IE                  = $63;
  {$EXTERNALSYM CAUSE_INVALID_IE_CONTENTS}
  CAUSE_INVALID_IE_CONTENTS               = $64;
  {$EXTERNALSYM CAUSE_INVALID_STATE_FOR_MESSAGE}
  CAUSE_INVALID_STATE_FOR_MESSAGE         = $65;
  {$EXTERNALSYM CAUSE_RECOVERY_ON_TIMEOUT}
  CAUSE_RECOVERY_ON_TIMEOUT               = $66;
  {$EXTERNALSYM CAUSE_INCORRECT_MESSAGE_LENGTH}
  CAUSE_INCORRECT_MESSAGE_LENGTH          = $68;
  {$EXTERNALSYM CAUSE_PROTOCOL_ERROR}
  CAUSE_PROTOCOL_ERROR                    = $6F;

// values used for the Condition portion of the Diagnostics field
// in struct ATM_CAUSE_IE, for certain Cause values
  {$EXTERNALSYM CAUSE_COND_UNKNOWN}
  CAUSE_COND_UNKNOWN   = $00;
  {$EXTERNALSYM CAUSE_COND_PERMANENT}
  CAUSE_COND_PERMANENT = $01;
  {$EXTERNALSYM CAUSE_COND_TRANSIENT}
  CAUSE_COND_TRANSIENT = $02;

// values used for the Rejection Reason portion of the Diagnostics field
// in struct ATM_CAUSE_IE, for certain Cause values
  {$EXTERNALSYM CAUSE_REASON_USER}
  CAUSE_REASON_USER            = $00;
  {$EXTERNALSYM CAUSE_REASON_IE_MISSING}
  CAUSE_REASON_IE_MISSING      = $04;
  {$EXTERNALSYM CAUSE_REASON_IE_INSUFFICIENT}
  CAUSE_REASON_IE_INSUFFICIENT = $08;

// values used for the P-U flag of the Diagnostics field
// in struct ATM_CAUSE_IE, for certain Cause values
  {$EXTERNALSYM CAUSE_PU_PROVIDER}
  CAUSE_PU_PROVIDER = $00;
  {$EXTERNALSYM CAUSE_PU_USER}
  CAUSE_PU_USER     = $08;

// values used for the N-A flag of the Diagnostics field
// in struct ATM_CAUSE_IE, for certain Cause values
  {$EXTERNALSYM CAUSE_NA_NORMAL}
  CAUSE_NA_NORMAL = $00;
  {$EXTERNALSYM CAUSE_NA_ABNORMAL}
  CAUSE_NA_ABNORMAL = $04;

type
  {$EXTERNALSYM ATM_CAUSE_IE}
  ATM_CAUSE_IE = record
    Location          : Byte;
    Cause             : Byte;
    DiagnosticsLength : Byte;
    Diagnostics       : Array[0..3] of Byte;
  end;

// values used for the QOSClassForward and QOSClassBackward
// field in struct ATM_QOS_CLASS_IE
const
  {$EXTERNALSYM QOS_CLASS0}
  QOS_CLASS0 = $00;
  {$EXTERNALSYM QOS_CLASS1}
  QOS_CLASS1 = $01;
  {$EXTERNALSYM QOS_CLASS2}
  QOS_CLASS2 = $02;
  {$EXTERNALSYM QOS_CLASS3}
  QOS_CLASS3 = $03;
  {$EXTERNALSYM QOS_CLASS4}
  QOS_CLASS4 = $04;

type
  {$EXTERNALSYM ATM_QOS_CLASS_IE}
  ATM_QOS_CLASS_IE = packed record
    QOSClassForward  : Byte;
    QOSClassBackward : Byte;
  end;

// values used for the TypeOfNetworkId field in struct ATM_TRANSIT_NETWORK_SELECTION_IE
const
  {$EXTERNALSYM TNS_TYPE_NATIONAL}
  TNS_TYPE_NATIONAL = $40;

// values used for the NetworkIdPlan field in struct ATM_TRANSIT_NETWORK_SELECTION_IE
  {$EXTERNALSYM TNS_PLAN_CARRIER_ID_CODE}
  TNS_PLAN_CARRIER_ID_CODE = $01;

type
  {$EXTERNALSYM ATM_TRANSIT_NETWORK_SELECTION_IE}
  ATM_TRANSIT_NETWORK_SELECTION_IE = record
    TypeOfNetworkId : Byte;
    NetworkIdPlan   : Byte;
    NetworkIdLength : Byte;
    NetworkId : Array[0..0] of Byte;
  end;

// ATM specific Ioctl codes
const
  {$EXTERNALSYM SIO_GET_NUMBER_OF_ATM_DEVICES}
  SIO_GET_NUMBER_OF_ATM_DEVICES = $50160001;
  {$EXTERNALSYM SIO_GET_ATM_ADDRESS}
  SIO_GET_ATM_ADDRESS           = $d0160002;
  {$EXTERNALSYM SIO_ASSOCIATE_PVC}
  SIO_ASSOCIATE_PVC             = $90160003;
  {$EXTERNALSYM SIO_GET_ATM_CONNECTION_ID}
  SIO_GET_ATM_CONNECTION_ID     = $50160004;

// ATM Connection Identifier
type
  {$EXTERNALSYM ATM_CONNECTION_ID}
  ATM_CONNECTION_ID = packed record
    DeviceNumber : DWORD;
    VPI          : DWORD;
    VCI          : DWORD;
  end;

// Input buffer format for SIO_ASSOCIATE_PVC
  {$EXTERNALSYM ATM_PVC_PARAMS}
  ATM_PVC_PARAMS = packed record
    PvcConnectionId : ATM_CONNECTION_ID;
    PvcQos          : QOS;
  end;

function Winsock2Loaded : Boolean;

//=============================================================
implementation
//=============================================================
uses IdResourceStrings;
// (c) March 2001,  "Alex Konshin"<alexk@mtgroup.ru>

type
  PPointer = ^Pointer;

var
  hWS2Dll : THandle = 0; // WS2.DLL handle
  WS2_WSAStartup : LPFN_WSASTARTUP;

function Winsock2Loaded : Boolean;
begin
  Result := hWS2Dll <> 0;
end;

constructor EIdWS2StubError.Build( const ATitle : String; AWin32Error : DWORD );
begin
  FTitle := ATitle;
  FWin32Error := AWin32Error;
  if AWin32Error = 0 then
  begin
    inherited Create( ATitle )
  end
  else
  begin
    FWin32ErrorMessage := SysUtils.SysErrorMessage(AWin32Error);
    inherited Create( ATitle + ': ' + FWin32ErrorMessage );    {Do not Localize}
  end;
end;

procedure WS2StubInit; forward;

procedure WS2Unload;
var h : THandle;
begin
  h := InterlockedExchange(Integer(hWS2Dll), 0);
  if h <> 0 then
  begin
    Windows.FreeLibrary(h);
    WS2StubInit;
  end;
end;

type
  WS2StubEntry = record
    StubProc : Pointer;
    ProcVar : PPointer;
    Name : PChar;
  end;

function WS2Call( AStubEntryIndex : DWORD ) : Pointer; forward;

procedure WS2Stub_WSACleanup;                       asm  mov eax,  0; call WS2Call; jmp eax; end;
procedure WS2Stub_accept;                           asm  mov eax,  1; call WS2Call; jmp eax; end;
procedure WS2Stub_bind;                             asm  mov eax,  2; call WS2Call; jmp eax; end;
procedure WS2Stub_closesocket;                      asm  mov eax,  3; call WS2Call; jmp eax; end;
procedure WS2Stub_connect;                          asm  mov eax,  4; call WS2Call; jmp eax; end;
procedure WS2Stub_ioctlsocket;                      asm  mov eax,  5; call WS2Call; jmp eax; end;
procedure WS2Stub_getpeername;                      asm  mov eax,  6; call WS2Call; jmp eax; end;
procedure WS2Stub_getsockname;                      asm  mov eax,  7; call WS2Call; jmp eax; end;
procedure WS2Stub_getsockopt;                       asm  mov eax,  8; call WS2Call; jmp eax; end;
procedure WS2Stub_htonl;                            asm  mov eax,  9; call WS2Call; jmp eax; end;
procedure WS2Stub_htons;                            asm  mov eax, 10; call WS2Call; jmp eax; end;
procedure WS2Stub_inet_addr;                        asm  mov eax, 11; call WS2Call; jmp eax; end;
procedure WS2Stub_inet_ntoa;                        asm  mov eax, 12; call WS2Call; jmp eax; end;
procedure WS2Stub_listen;                           asm  mov eax, 13; call WS2Call; jmp eax; end;
procedure WS2Stub_ntohl;                            asm  mov eax, 14; call WS2Call; jmp eax; end;
procedure WS2Stub_ntohs;                            asm  mov eax, 15; call WS2Call; jmp eax; end;
procedure WS2Stub_recv;                             asm  mov eax, 16; call WS2Call; jmp eax; end;
procedure WS2Stub_recvfrom;                         asm  mov eax, 17; call WS2Call; jmp eax; end;
procedure WS2Stub_select;                           asm  mov eax, 18; call WS2Call; jmp eax; end;
procedure WS2Stub_send;                             asm  mov eax, 19; call WS2Call; jmp eax; end;
procedure WS2Stub_sendto;                           asm  mov eax, 20; call WS2Call; jmp eax; end;
procedure WS2Stub_setsockopt;                       asm  mov eax, 21; call WS2Call; jmp eax; end;
procedure WS2Stub_shutdown;                         asm  mov eax, 22; call WS2Call; jmp eax; end;
procedure WS2Stub_socket;                           asm  mov eax, 23; call WS2Call; jmp eax; end;
procedure WS2Stub_gethostbyaddr;                    asm  mov eax, 24; call WS2Call; jmp eax; end;
procedure WS2Stub_gethostbyname;                    asm  mov eax, 25; call WS2Call; jmp eax; end;
procedure WS2Stub_gethostname;                      asm  mov eax, 26; call WS2Call; jmp eax; end;
procedure WS2Stub_getservbyport;                    asm  mov eax, 27; call WS2Call; jmp eax; end;
procedure WS2Stub_getservbyname;                    asm  mov eax, 28; call WS2Call; jmp eax; end;
procedure WS2Stub_getprotobynumber;                 asm  mov eax, 29; call WS2Call; jmp eax; end;
procedure WS2Stub_getprotobyname;                   asm  mov eax, 30; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASetLastError;                  asm  mov eax, 31; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetLastError;                  asm  mov eax, 32; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAIsBlocking;                    asm  mov eax, 33; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAUnhookBlockingHook;            asm  mov eax, 34; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASetBlockingHook;               asm  mov eax, 35; call WS2Call; jmp eax; end;
procedure WS2Stub_WSACancelBlockingCall;            asm  mov eax, 36; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAsyncGetServByName;            asm  mov eax, 37; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAsyncGetServByPort;            asm  mov eax, 38; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAsyncGetProtoByName;           asm  mov eax, 39; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAsyncGetProtoByNumber;         asm  mov eax, 40; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAsyncGetHostByName;            asm  mov eax, 41; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAsyncGetHostByAddr;            asm  mov eax, 42; call WS2Call; jmp eax; end;
procedure WS2Stub_WSACancelAsyncRequest;            asm  mov eax, 43; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAsyncSelect;                   asm  mov eax, 44; call WS2Call; jmp eax; end;
procedure WS2Stub___WSAFDIsSet;                     asm  mov eax, 45; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAccept;                        asm  mov eax, 46; call WS2Call; jmp eax; end;
procedure WS2Stub_WSACloseEvent;                    asm  mov eax, 47; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAConnect;                       asm  mov eax, 48; call WS2Call; jmp eax; end;
procedure WS2Stub_WSACreateEvent;                  asm  mov eax, 49; call WS2Call; jmp eax; end;
procedure WS2Stub_WSADuplicateSocketA;              asm  mov eax, 50; call WS2Call; jmp eax; end;
procedure WS2Stub_WSADuplicateSocketW;              asm  mov eax, 51; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEnumNetworkEvents;             asm  mov eax, 52; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEnumProtocolsA;                asm  mov eax, 53; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEnumProtocolsW;                asm  mov eax, 54; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEventSelect;                   asm  mov eax, 55; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetOverlappedResult;           asm  mov eax, 56; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetQosByName;                  asm  mov eax, 57; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAHtonl;                         asm  mov eax, 58; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAHtons;                         asm  mov eax, 59; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAIoctl;                         asm  mov eax, 60; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAJoinLeaf;                      asm  mov eax, 61; call WS2Call; jmp eax; end;
procedure WS2Stub_WSANtohl;                         asm  mov eax, 62; call WS2Call; jmp eax; end;
procedure WS2Stub_WSANtohs;                         asm  mov eax, 63; call WS2Call; jmp eax; end;
procedure WS2Stub_WSARecv;                          asm  mov eax, 64; call WS2Call; jmp eax; end;
procedure WS2Stub_WSARecvDisconnect;                asm  mov eax, 65; call WS2Call; jmp eax; end;
procedure WS2Stub_WSARecvFrom;                      asm  mov eax, 66; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAResetEvent;                    asm  mov eax, 67; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASend;                          asm  mov eax, 68; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASendDisconnect;                asm  mov eax, 69; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASendTo;                        asm  mov eax, 70; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASetEvent;                      asm  mov eax, 71; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASocketA;                       asm  mov eax, 72; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASocketW;                       asm  mov eax, 73; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAWaitForMultipleEvents;         asm  mov eax, 74; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAddressToStringA;              asm  mov eax, 75; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAddressToStringW;              asm  mov eax, 76; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAStringToAddressA;              asm  mov eax, 77; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAStringToAddressW;              asm  mov eax, 78; call WS2Call; jmp eax; end;
procedure WS2Stub_WSALookupServiceBeginA;           asm  mov eax, 79; call WS2Call; jmp eax; end;
procedure WS2Stub_WSALookupServiceBeginW;           asm  mov eax, 80; call WS2Call; jmp eax; end;
procedure WS2Stub_WSALookupServiceNextA;            asm  mov eax, 81; call WS2Call; jmp eax; end;
procedure WS2Stub_WSALookupServiceNextW;            asm  mov eax, 82; call WS2Call; jmp eax; end;
procedure WS2Stub_WSALookupServiceEnd;              asm  mov eax, 83; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAInstallServiceClassA;          asm  mov eax, 84; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAInstallServiceClassW;          asm  mov eax, 85; call WS2Call; jmp eax; end;
procedure WS2Stub_WSARemoveServiceClass;            asm  mov eax, 86; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetServiceClassInfoA;          asm  mov eax, 87; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetServiceClassInfoW;          asm  mov eax, 88; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEnumNameSpaceProvidersA;       asm  mov eax, 89; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEnumNameSpaceProvidersW;       asm  mov eax, 90; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetServiceClassNameByClassIdA; asm  mov eax, 91; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetServiceClassNameByClassIdW; asm  mov eax, 92; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASetServiceA;                   asm  mov eax, 93; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASetServiceW;                   asm  mov eax, 94; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAProviderConfigChange;          asm  mov eax, 95; call WS2Call; jmp eax; end;
procedure WS2Stub_WSADuplicateSocket;               asm  mov eax, 96; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEnumProtocols;                 asm  mov eax, 97; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASocket;                        asm  mov eax, 98; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAAddressToString;               asm  mov eax, 99; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAStringToAddress;               asm  mov eax,100; call WS2Call; jmp eax; end;
procedure WS2Stub_WSALookupServiceBegin;            asm  mov eax,101; call WS2Call; jmp eax; end;
procedure WS2Stub_WSALookupServiceNext;             asm  mov eax,102; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAInstallServiceClass;           asm  mov eax,103; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetServiceClassInfo;           asm  mov eax,104; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAEnumNameSpaceProviders;        asm  mov eax,105; call WS2Call; jmp eax; end;
procedure WS2Stub_WSAGetServiceClassNameByClassId;  asm  mov eax,106; call WS2Call; jmp eax; end;
procedure WS2Stub_WSASetService;                    asm  mov eax,107; call WS2Call; jmp eax; end;
procedure WS2Stub_TransmitFile;                     asm  mov eax,108; call WS2Call; jmp eax; end;

procedure WS2Stub_AcceptEx;                         asm  mov eax,109; call WS2Call; jmp eax; end;
procedure WS2Stub_GetAcceptExSockaddrs;             asm  mov eax,110; call WS2Call; jmp eax; end;
procedure WS2Stub_WSARecvEx;                        asm  mov eax,111; call WS2Call; jmp eax; end;

const
  WS2StubEntryCount = 112;
  WS2StubTable : Array [0..WS2StubEntryCount-1] of WS2StubEntry = (
    (StubProc: @WS2Stub_WSACleanup; ProcVar: @@WSACleanup; Name: 'WSACleanup'),
    (StubProc: @WS2Stub_accept; ProcVar: @@accept; Name: 'accept'),
    (StubProc: @WS2Stub_bind; ProcVar: @@bind; Name: 'bind'),
    (StubProc: @WS2Stub_closesocket; ProcVar: @@closesocket; Name: 'closesocket'),
    (StubProc: @WS2Stub_connect; ProcVar: @@connect; Name: 'connect'),
    (StubProc: @WS2Stub_ioctlsocket; ProcVar: @@ioctlsocket; Name: 'ioctlsocket'),
    (StubProc: @WS2Stub_getpeername; ProcVar: @@getpeername; Name: 'getpeername'),
    (StubProc: @WS2Stub_getsockname; ProcVar: @@getsockname; Name: 'getsockname'),
    (StubProc: @WS2Stub_getsockopt; ProcVar: @@getsockopt; Name: 'getsockopt'),
    (StubProc: @WS2Stub_htonl; ProcVar: @@htonl; Name: 'htonl'),
    (StubProc: @WS2Stub_htons; ProcVar: @@htons; Name: 'htons'),
    (StubProc: @WS2Stub_inet_addr; ProcVar: @@inet_addr; Name: 'inet_addr'),
    (StubProc: @WS2Stub_inet_ntoa; ProcVar: @@inet_ntoa; Name: 'inet_ntoa'),
    (StubProc: @WS2Stub_listen; ProcVar: @@listen; Name: 'listen'),
    (StubProc: @WS2Stub_ntohl; ProcVar: @@ntohl; Name: 'ntohl'),
    (StubProc: @WS2Stub_ntohs; ProcVar: @@ntohs; Name: 'ntohs'),
    (StubProc: @WS2Stub_recv; ProcVar: @@recv; Name: 'recv'),
    (StubProc: @WS2Stub_recvfrom; ProcVar: @@recvfrom; Name: 'recvfrom'),
    (StubProc: @WS2Stub_select; ProcVar: @@select; Name: 'select'),
    (StubProc: @WS2Stub_send; ProcVar: @@send; Name: 'send'),
    (StubProc: @WS2Stub_sendto; ProcVar: @@sendto; Name: 'sendto'),
    (StubProc: @WS2Stub_setsockopt; ProcVar: @@setsockopt; Name: 'setsockopt'),
    (StubProc: @WS2Stub_shutdown; ProcVar: @@shutdown; Name: 'shutdown'),
    (StubProc: @WS2Stub_socket; ProcVar: @@socket; Name: 'socket'),
    (StubProc: @WS2Stub_gethostbyaddr; ProcVar: @@gethostbyaddr; Name: 'gethostbyaddr'),
    (StubProc: @WS2Stub_gethostbyname; ProcVar: @@gethostbyname; Name: 'gethostbyname'),
    (StubProc: @WS2Stub_gethostname; ProcVar: @@gethostname; Name: 'gethostname'),
    (StubProc: @WS2Stub_getservbyport; ProcVar: @@getservbyport; Name: 'getservbyport'),
    (StubProc: @WS2Stub_getservbyname; ProcVar: @@getservbyname; Name: 'getservbyname'),
    (StubProc: @WS2Stub_getprotobynumber; ProcVar: @@getprotobynumber; Name: 'getprotobynumber'),
    (StubProc: @WS2Stub_getprotobyname; ProcVar: @@getprotobyname; Name: 'getprotobyname'),
    (StubProc: @WS2Stub_WSASetLastError; ProcVar: @@WSASetLastError; Name: 'WSASetLastError'),
    (StubProc: @WS2Stub_WSAGetLastError; ProcVar: @@WSAGetLastError; Name: 'WSAGetLastError'),
    (StubProc: @WS2Stub_WSAIsBlocking; ProcVar: @@WSAIsBlocking; Name: 'WSAIsBlocking'),
    (StubProc: @WS2Stub_WSAUnhookBlockingHook; ProcVar: @@WSAUnhookBlockingHook; Name: 'WSAUnhookBlockingHook'),
    (StubProc: @WS2Stub_WSASetBlockingHook; ProcVar: @@WSASetBlockingHook; Name: 'WSASetBlockingHook'),
    (StubProc: @WS2Stub_WSACancelBlockingCall; ProcVar: @@WSACancelBlockingCall; Name: 'WSACancelBlockingCall'),
    (StubProc: @WS2Stub_WSAAsyncGetServByName; ProcVar: @@WSAAsyncGetServByName; Name: 'WSAAsyncGetServByName'),
    (StubProc: @WS2Stub_WSAAsyncGetServByPort; ProcVar: @@WSAAsyncGetServByPort; Name: 'WSAAsyncGetServByPort'),
    (StubProc: @WS2Stub_WSAAsyncGetProtoByName; ProcVar: @@WSAAsyncGetProtoByName; Name: 'WSAAsyncGetProtoByName'),
    (StubProc: @WS2Stub_WSAAsyncGetProtoByNumber; ProcVar: @@WSAAsyncGetProtoByNumber; Name: 'WSAAsyncGetProtoByNumber'),
    (StubProc: @WS2Stub_WSAAsyncGetHostByName; ProcVar: @@WSAAsyncGetHostByName; Name: 'WSAAsyncGetHostByName'),
    (StubProc: @WS2Stub_WSAAsyncGetHostByAddr; ProcVar: @@WSAAsyncGetHostByAddr; Name: 'WSAAsyncGetHostByAddr'),
    (StubProc: @WS2Stub_WSACancelAsyncRequest; ProcVar: @@WSACancelAsyncRequest; Name: 'WSACancelAsyncRequest'),
    (StubProc: @WS2Stub_WSAAsyncSelect; ProcVar: @@WSAAsyncSelect; Name: 'WSAAsyncSelect'),
    (StubProc: @WS2Stub___WSAFDIsSet; ProcVar: @@__WSAFDIsSet; Name: '__WSAFDIsSet'),
    (StubProc: @WS2Stub_WSAAccept; ProcVar: @@WSAAccept; Name: 'WSAAccept'),
    (StubProc: @WS2Stub_WSACloseEvent; ProcVar: @@WSACloseEvent; Name: 'WSACloseEvent'),
    (StubProc: @WS2Stub_WSAConnect; ProcVar: @@WSAConnect; Name: 'WSAConnect'),
    (StubProc: @WS2Stub_WSACreateEvent ; ProcVar: @@WSACreateEvent ; Name: 'WSACreateEvent'),
    (StubProc: @WS2Stub_WSADuplicateSocketA; ProcVar: @@WSADuplicateSocketA; Name: 'WSADuplicateSocketA'),
    (StubProc: @WS2Stub_WSADuplicateSocketW; ProcVar: @@WSADuplicateSocketW; Name: 'WSADuplicateSocketW'),
    (StubProc: @WS2Stub_WSAEnumNetworkEvents; ProcVar: @@WSAEnumNetworkEvents; Name: 'WSAEnumNetworkEvents'),
    (StubProc: @WS2Stub_WSAEnumProtocolsA; ProcVar: @@WSAEnumProtocolsA; Name: 'WSAEnumProtocolsA'),
    (StubProc: @WS2Stub_WSAEnumProtocolsW; ProcVar: @@WSAEnumProtocolsW; Name: 'WSAEnumProtocolsW'),
    (StubProc: @WS2Stub_WSAEventSelect; ProcVar: @@WSAEventSelect; Name: 'WSAEventSelect'),
    (StubProc: @WS2Stub_WSAGetOverlappedResult; ProcVar: @@WSAGetOverlappedResult; Name: 'WSAGetOverlappedResult'),
    (StubProc: @WS2Stub_WSAGetQosByName; ProcVar: @@WSAGetQosByName; Name: 'WSAGetQosByName'),
    (StubProc: @WS2Stub_WSAHtonl; ProcVar: @@WSAHtonl; Name: 'WSAHtonl'),
    (StubProc: @WS2Stub_WSAHtons; ProcVar: @@WSAHtons; Name: 'WSAHtons'),
    (StubProc: @WS2Stub_WSAIoctl; ProcVar: @@WSAIoctl; Name: 'WSAIoctl'),
    (StubProc: @WS2Stub_WSAJoinLeaf; ProcVar: @@WSAJoinLeaf; Name: 'WSAJoinLeaf'),
    (StubProc: @WS2Stub_WSANtohl; ProcVar: @@WSANtohl; Name: 'WSANtohl'),
    (StubProc: @WS2Stub_WSANtohs; ProcVar: @@WSANtohs; Name: 'WSANtohs'),
    (StubProc: @WS2Stub_WSARecv; ProcVar: @@WSARecv; Name: 'WSARecv'),
    (StubProc: @WS2Stub_WSARecvDisconnect; ProcVar: @@WSARecvDisconnect; Name: 'WSARecvDisconnect'),
    (StubProc: @WS2Stub_WSARecvFrom; ProcVar: @@WSARecvFrom; Name: 'WSARecvFrom'),
    (StubProc: @WS2Stub_WSAResetEvent; ProcVar: @@WSAResetEvent; Name: 'WSAResetEvent'),
    (StubProc: @WS2Stub_WSASend; ProcVar: @@WSASend; Name: 'WSASend'),
    (StubProc: @WS2Stub_WSASendDisconnect; ProcVar: @@WSASendDisconnect; Name: 'WSASendDisconnect'),
    (StubProc: @WS2Stub_WSASendTo; ProcVar: @@WSASendTo; Name: 'WSASendTo'),
    (StubProc: @WS2Stub_WSASetEvent; ProcVar: @@WSASetEvent; Name: 'WSASetEvent'),
    (StubProc: @WS2Stub_WSASocketA; ProcVar: @@WSASocketA; Name: 'WSASocketA'),
    (StubProc: @WS2Stub_WSASocketW; ProcVar: @@WSASocketW; Name: 'WSASocketW'),
    (StubProc: @WS2Stub_WSAWaitForMultipleEvents; ProcVar: @@WSAWaitForMultipleEvents; Name: 'WSAWaitForMultipleEvents'),
    (StubProc: @WS2Stub_WSAAddressToStringA; ProcVar: @@WSAAddressToStringA; Name: 'WSAAddressToStringA'),
    (StubProc: @WS2Stub_WSAAddressToStringW; ProcVar: @@WSAAddressToStringW; Name: 'WSAAddressToStringW'),
    (StubProc: @WS2Stub_WSAStringToAddressA; ProcVar: @@WSAStringToAddressA; Name: 'WSAStringToAddressA'),
    (StubProc: @WS2Stub_WSAStringToAddressW; ProcVar: @@WSAStringToAddressW; Name: 'WSAStringToAddressW'),
    (StubProc: @WS2Stub_WSALookupServiceBeginA; ProcVar: @@WSALookupServiceBeginA; Name: 'WSALookupServiceBeginA'),
    (StubProc: @WS2Stub_WSALookupServiceBeginW; ProcVar: @@WSALookupServiceBeginW; Name: 'WSALookupServiceBeginW'),
    (StubProc: @WS2Stub_WSALookupServiceNextA; ProcVar: @@WSALookupServiceNextA; Name: 'WSALookupServiceNextA'),
    (StubProc: @WS2Stub_WSALookupServiceNextW; ProcVar: @@WSALookupServiceNextW; Name: 'WSALookupServiceNextW'),
    (StubProc: @WS2Stub_WSALookupServiceEnd; ProcVar: @@WSALookupServiceEnd; Name: 'WSALookupServiceEnd'),
    (StubProc: @WS2Stub_WSAInstallServiceClassA; ProcVar: @@WSAInstallServiceClassA; Name: 'WSAInstallServiceClassA'),
    (StubProc: @WS2Stub_WSAInstallServiceClassW; ProcVar: @@WSAInstallServiceClassW; Name: 'WSAInstallServiceClassW'),
    (StubProc: @WS2Stub_WSARemoveServiceClass; ProcVar: @@WSARemoveServiceClass; Name: 'WSARemoveServiceClass'),
    (StubProc: @WS2Stub_WSAGetServiceClassInfoA; ProcVar: @@WSAGetServiceClassInfoA; Name: 'WSAGetServiceClassInfoA'),
    (StubProc: @WS2Stub_WSAGetServiceClassInfoW; ProcVar: @@WSAGetServiceClassInfoW; Name: 'WSAGetServiceClassInfoW'),
    (StubProc: @WS2Stub_WSAEnumNameSpaceProvidersA; ProcVar: @@WSAEnumNameSpaceProvidersA; Name: 'WSAEnumNameSpaceProvidersA'),
    (StubProc: @WS2Stub_WSAEnumNameSpaceProvidersW; ProcVar: @@WSAEnumNameSpaceProvidersW; Name: 'WSAEnumNameSpaceProvidersW'),
    (StubProc: @WS2Stub_WSAGetServiceClassNameByClassIdA; ProcVar: @@WSAGetServiceClassNameByClassIdA; Name: 'WSAGetServiceClassNameByClassIdA'),
    (StubProc: @WS2Stub_WSAGetServiceClassNameByClassIdW; ProcVar: @@WSAGetServiceClassNameByClassIdW; Name: 'WSAGetServiceClassNameByClassIdW'),
    (StubProc: @WS2Stub_WSASetServiceA; ProcVar: @@WSASetServiceA; Name: 'WSASetServiceA'),
    (StubProc: @WS2Stub_WSASetServiceW; ProcVar: @@WSASetServiceW; Name: 'WSASetServiceW'),
    (StubProc: @WS2Stub_WSAProviderConfigChange; ProcVar: @@WSAProviderConfigChange; Name: 'WSAProviderConfigChange'),
{$IFDEF UNICODE}
    (StubProc: @WS2Stub_WSADuplicateSocket; ProcVar: @@WSADuplicateSocket; Name: 'WSADuplicateSocketW'),
    (StubProc: @WS2Stub_WSAEnumProtocols; ProcVar: @@WSAEnumProtocols; Name: 'WSAEnumProtocolsW'),
    (StubProc: @WS2Stub_WSASocket; ProcVar: @@WSASocket; Name: 'WSASocketW'),
    (StubProc: @WS2Stub_WSAAddressToString; ProcVar: @@WSAAddressToString; Name: 'WSAAddressToStringW'),
    (StubProc: @WS2Stub_WSAStringToAddress; ProcVar: @@WSAStringToAddress; Name: 'WSAStringToAddressW'),
    (StubProc: @WS2Stub_WSALookupServiceBegin; ProcVar: @@WSALookupServiceBegin; Name: 'WSALookupServiceBeginW'),
    (StubProc: @WS2Stub_WSALookupServiceNext; ProcVar: @@WSALookupServiceNext; Name: 'WSALookupServiceNextW'),
    (StubProc: @WS2Stub_WSAInstallServiceClass; ProcVar: @@WSAInstallServiceClass; Name: 'WSAInstallServiceClassW'),
    (StubProc: @WS2Stub_WSAGetServiceClassInfo; ProcVar: @@WSAGetServiceClassInfo; Name: 'WSAGetServiceClassInfoW'),
    (StubProc: @WS2Stub_WSAEnumNameSpaceProviders; ProcVar: @@WSAEnumNameSpaceProviders; Name: 'WSAEnumNameSpaceProvidersW'),
    (StubProc: @WS2Stub_WSAGetServiceClassNameByClassId; ProcVar: @@WSAGetServiceClassNameByClassId; Name: 'WSAGetServiceClassNameByClassIdW'),
    (StubProc: @WS2Stub_WSASetService; ProcVar: @@WSASetService; Name: 'WSASetServiceW'),
{$ELSE}
    (StubProc: @WS2Stub_WSADuplicateSocket; ProcVar: @@WSADuplicateSocket; Name: 'WSADuplicateSocketA'),
    (StubProc: @WS2Stub_WSAEnumProtocols; ProcVar: @@WSAEnumProtocols; Name: 'WSAEnumProtocolsA'),
    (StubProc: @WS2Stub_WSASocket; ProcVar: @@WSASocket; Name: 'WSASocketA'),
    (StubProc: @WS2Stub_WSAAddressToString; ProcVar: @@WSAAddressToString; Name: 'WSAAddressToStringA'),
    (StubProc: @WS2Stub_WSAStringToAddress; ProcVar: @@WSAStringToAddress; Name: 'WSAStringToAddressA'),
    (StubProc: @WS2Stub_WSALookupServiceBegin; ProcVar: @@WSALookupServiceBegin; Name: 'WSALookupServiceBeginA'),
    (StubProc: @WS2Stub_WSALookupServiceNext; ProcVar: @@WSALookupServiceNext; Name: 'WSALookupServiceNextA'),
    (StubProc: @WS2Stub_WSAInstallServiceClass; ProcVar: @@WSAInstallServiceClass; Name: 'WSAInstallServiceClassA'),
    (StubProc: @WS2Stub_WSAGetServiceClassInfo; ProcVar: @@WSAGetServiceClassInfo; Name: 'WSAGetServiceClassInfoA'),
    (StubProc: @WS2Stub_WSAEnumNameSpaceProviders; ProcVar: @@WSAEnumNameSpaceProviders; Name: 'WSAEnumNameSpaceProvidersA'),
    (StubProc: @WS2Stub_WSAGetServiceClassNameByClassId; ProcVar: @@WSAGetServiceClassNameByClassId; Name: 'WSAGetServiceClassNameByClassIdA'),
    (StubProc: @WS2Stub_WSASetService; ProcVar: @@WSASetService; Name: 'WSASetServiceA'),
{$ENDIF}
    (StubProc: @WS2Stub_TransmitFile; ProcVar: @@TransmitFile; Name: 'TransmitFile'),
    (StubProc: @WS2Stub_AcceptEx; ProcVar: @@AcceptEx; Name: 'AcceptEx'),
    (StubProc: @WS2Stub_GetAcceptExSockaddrs; ProcVar: @@GetAcceptExSockaddrs; Name: 'GetAcceptExSockaddrs'),
    (StubProc: @WS2Stub_WSARecvEx; ProcVar: @@WSARecvEx; Name: 'WSARecvEx')
  );

function WS2Call( AStubEntryIndex : DWORD ) : Pointer;
begin
  with WS2StubTable[AStubEntryIndex] do
  begin
    if hWS2Dll = 0 then
    begin
      raise EIdWS2StubError.Build( Format(RSWS2CallError,[Name]), WSANOTINITIALISED );
    end;
    Result := Windows.GetProcAddress( hWS2Dll, Name );
    ProcVar^ := Result;
  end;
end;

procedure WS2StubInit;
var i : Integer;
begin
  hWS2Dll := 0;
  for i := 0 to WS2StubEntryCount-1 do
    with WS2StubTable[i] do
      ProcVar^ := StubProc;
end;


function WSAStartup( const wVersionRequired: word; var WSData: TWSAData ): Integer;
begin
  if hWS2Dll = 0 then
  begin
    hWS2Dll := LoadLibrary( WINSOCK2_DLL );
    if hWS2Dll = 0 then
    begin
	  raise EIdWS2StubError.Build( Format(RSWS2LoadError,[WINSOCK2_DLL]), Windows.GetLastError );
    end;
    WS2_WSAStartup := LPFN_WSASTARTUP( Windows.GetProcAddress( hWS2Dll, 'WSAStartup' ) );    {Do not Localize}
    Result := WS2_WSAStartup( wVersionRequired, WSData );
  end
  else
  begin
    //actually, this not really be called if the lib is already loaded.
    Result:= 0; ///<<<<<<<<< if loaded then all ok
  end;
end;

function WSAMakeSyncReply;
begin
  WSAMakeSyncReply:= MakeLong(Buflen, Error);
end;

function WSAMakeSelectReply;
begin
  WSAMakeSelectReply:= MakeLong(Event, Error);
end;

function WSAGetAsyncBuflen;
begin
  WSAGetAsyncBuflen:= LOWORD(Param);
end;

function WSAGetAsyncError;
begin
  WSAGetAsyncError:= HIWORD(Param);
end;

function WSAGetSelectEvent;
begin
  WSAGetSelectEvent:= LOWORD(Param);
end;

function WSAGetSelectError;
begin
  WSAGetSelectError:= HIWORD(Param);
end;

procedure FD_CLR(Socket: TSocket; var FDSet: TFDSet);
//var i: DWord;
var
  i: Integer;
begin
  i := 0;
  while i < FDSet.fd_count do
  begin
    if FDSet.fd_array[i] = Socket then
    begin
      while i < FDSet.fd_count - 1 do
      begin
        FDSet.fd_array[i] := FDSet.fd_array[i+1];
        Inc(i);
      end;
      Dec(FDSet.fd_count);
      Break;
    end;
    Inc(i);
  end;
end;

function FD_ISSET(Socket: TSocket; var FDSet: TFDSet): Boolean;
begin
  Result := __WSAFDIsSet(Socket, FDSet);
end;

procedure FD_SET(Socket: TSocket; var FDSet: TFDSet);
begin
  if FDSet.fd_count < fd_setsize then
  begin
    FDSet.fd_array[FDSet.fd_count] := Socket;
    Inc(FDSet.fd_count);
  end;
end;

procedure FD_ZERO(var FDSet: TFDSet);
begin
  FDSet.fd_count := 0;
end;

function IP_MSFILTER_SIZE(numsrc: DWORD): DWORD;
begin
  Result := SizeOf(ip_msfilter) - SizeOf(TInAddr) + (numsrc*SizeOf(TInAddr));
end;

function SS_PORT(ssp: PSockAddrIn): u_short;
begin
  Result := 0;
  if ssp <> nil then Result := ssp^.sin_port;
end;

function IN6ADDR_ANY_INIT: TIn6Addr;
begin
  with Result do
  begin
    System.FillChar( s6_addr, SizeOf(s6_addr), 0 );    {Do not Localize}
  end;
end;

function IN6ADDR_LOOPBACK_INIT: TIn6Addr;
begin
  with Result do
  begin
    System.FillChar( s6_addr, SizeOf(s6_addr), 0 );    {Do not Localize}
    s6_addr[15] := 1;
  end;
end;

procedure IN6ADDR_SETANY(sa: PSockAddrIn6);
begin
  if sa <> nil then with sa^ do
  begin
    sin6_family := AF_INET6;
    sin6_port := 0;
    sin6_flowinfo := 0;
    PULONG(@sin6_addr.s6_addr[0])^ := 0;
    PULONG(@sin6_addr.s6_addr[4])^ := 0;
    PULONG(@sin6_addr.s6_addr[8])^ := 0;
    PULONG(@sin6_addr.s6_addr[12])^ := 0;
  end;
end;

procedure IN6ADDR_SETLOOPBACK(sa: PSockAddrIn6);
begin
  if sa <> nil then with sa^ do
  begin
    sin6_family := AF_INET6;
    sin6_port := 0;
    sin6_flowinfo := 0;
    PULONG(@sin6_addr.s6_addr[0])^ := 0;
    PULONG(@sin6_addr.s6_addr[4])^ := 0;
    PULONG(@sin6_addr.s6_addr[8])^ := 0;
    PULONG(@sin6_addr.s6_addr[12])^ := 1;
  end;
end;

function IN6ADDR_ISANY(sa: PSockAddrIn6): Boolean;
begin
  Result := False;
  if sa <> nil then with sa^ do
  begin
    Result := (sin6_family = AF_INET6) and
                (PULONG(@sin6_addr.s6_addr[0])^ = 0) and
                (PULONG(@sin6_addr.s6_addr[4])^ = 0) and
                (PULONG(@sin6_addr.s6_addr[8])^ = 0) and
                (PULONG(@sin6_addr.s6_addr[12])^ = 0);
  end;
end;

function IN6ADDR_ISLOOPBACK(sa: PSockAddrIn6): Boolean;
begin
  Result := False;
  if sa <> nil then with sa^ do
  begin
    Result := (sin6_family = AF_INET6) and
                (PULONG(@sin6_addr.s6_addr[0])^ = 0) and
                (PULONG(@sin6_addr.s6_addr[4])^ = 0) and
                (PULONG(@sin6_addr.s6_addr[8])^ = 0) and
                (PULONG(@sin6_addr.s6_addr[12])^ = 1);
  end;
end;

function IN6_ADDR_EQUAL(const a: PIn6Addr; const b: PIn6Addr): Boolean;
begin
  Result := SysUtils.CompareMem(a, b, SizeOf(TIn6Addr));
end;

function IN6_IS_ADDR_UNSPECIFIED(const a: PIn6Addr): Boolean;
begin
  Result := IN6_ADDR_EQUAL(a, @in6addr_any);
end;

function IN6_IS_ADDR_LOOPBACK(const a: PIn6Addr): Boolean;
begin
  Result := IN6_ADDR_EQUAL(a, @in6addr_loopback);
end;

function IN6_IS_ADDR_MULTICAST(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then Result := (a^.s6_addr[0] = $FF);
end;

function IN6_IS_ADDR_LINKLOCAL(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then with a^ do Result := (s6_addr[0] = $FE) and ((s6_addr[1] and $C0) = $80);
end;

function IN6_IS_ADDR_SITELOCAL(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then with a^ do Result := (s6_addr[0] = $FE) and ((s6_addr[1] and $C0) = $C0);
end;

function IN6_IS_ADDR_V4MAPPED(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then with a^ do
  begin
    Result := (word[0] = 0) and
              (word[1] = 0) and
              (word[2] = 0) and
              (word[3] = 0) and
              (word[4] = 0) and
              (word[5] = $FFFF);
  end;
end;

function IN6_IS_ADDR_V4COMPAT(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then with a^ do
  begin
    Result := (word[0] = 0) and
              (word[1] = 0) and
              (word[2] = 0) and
              (word[3] = 0) and
              (word[4] = 0) and
              (word[5] = 0) and
              not ((word[6] = 0) and (s6_addr[14] = 0) and
              ((s6_addr[15] = 0) or (s6_addr[15] = 1)));
  end;
end;

function IN6_IS_ADDR_MC_NODELOCAL(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then Result := IN6_IS_ADDR_MULTICAST(a) and ((a^.s6_addr[1] and $F) = 1);
end;

function IN6_IS_ADDR_MC_LINKLOCAL(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then Result := IN6_IS_ADDR_MULTICAST(a) and ((a^.s6_addr[1] and $F) = 2);
end;

function IN6_IS_ADDR_MC_SITELOCAL(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then Result := IN6_IS_ADDR_MULTICAST(a) and ((a^.s6_addr[1] and $F) = 5);
end;

function IN6_IS_ADDR_MC_ORGLOCAL(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then Result := IN6_IS_ADDR_MULTICAST(a) and ((a^.s6_addr[1] and $F) = 8);
end;

function IN6_IS_ADDR_MC_GLOBAL(const a: PIn6Addr): Boolean;
begin
  Result := False;
  if a <> nil then Result := IN6_IS_ADDR_MULTICAST(a) and ((a^.s6_addr[1] and $F) = $E);
end;

//  A macro convenient for setting up NETBIOS SOCKADDRs.
procedure SET_NETBIOS_SOCKADDR( snb : PSockAddrNB; const SnbType : Word; const Name : PChar; const Port : Char );
var len : Integer;
begin
  if snb <> nil then with snb^ do
  begin
    snb_family := AF_NETBIOS;
    snb_type := SnbType;
    len := StrLen(Name);
    if len >= NETBIOS_NAME_LENGTH-1 then
    begin
      System.Move(Name^, snb_name, NETBIOS_NAME_LENGTH-1)
    end
    else
    begin
      if len > 0 then
      begin
        System.Move(Name^, snb_name, len);
      end;
      System.FillChar( (PChar(@snb_name)+len)^, NETBIOS_NAME_LENGTH-1-len, ' ' );    {Do not Localize}
    end;
    snb_name[NETBIOS_NAME_LENGTH-1] := Port;
  end;
end;

initialization
  in6addr_any := IN6ADDR_ANY_INIT;
  in6addr_loopback :=  IN6ADDR_LOOPBACK_INIT;
  WS2StubInit;
finalization
  WS2Unload;
end.
