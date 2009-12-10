// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdRawHeaders.pas' rev: 5.00

#ifndef IdRawHeadersHPP
#define IdRawHeadersHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdStack.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idrawheaders
{
//-- type declarations -------------------------------------------------------
typedef unsigned TIdNetTime;

#pragma pack(push, 1)
struct TIdIpOptions
{
	char ipopt_list[40];
} ;
#pragma pack(pop)

struct TIdIpHdr;
typedef TIdIpHdr *PIdIpHdr;

#pragma pack(push, 1)
struct TIdIpHdr
{
	Byte ip_verlen;
	Byte ip_tos;
	Word ip_len;
	Word ip_id;
	Word ip_off;
	Byte ip_ttl;
	Byte ip_p;
	Word ip_sum;
	Idstack::TIdInAddr ip_src;
	Idstack::TIdInAddr ip_dst;
	unsigned ip_options;
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct TIdTcpOptions
{
	Byte tcpopt_list[40];
} ;
#pragma pack(pop)

struct TIdTcpHdr;
typedef TIdTcpHdr *PIdTcpHdr;

#pragma pack(push, 1)
struct TIdTcpHdr
{
	Word tcp_sport;
	Word tcp_dport;
	unsigned tcp_seq;
	unsigned tcp_ack;
	Byte tcp_x2off;
	Byte tcp_flags;
	Word tcp_win;
	Word tcp_sum;
	Word tcp_urp;
} ;
#pragma pack(pop)

struct TIdUdpHdr;
typedef TIdUdpHdr *PIdUdpHdr;

#pragma pack(push, 1)
struct TIdUdpHdr
{
	Word udp_sport;
	Word udp_dport;
	Word udp_ulen;
	Word udp_sum;
} ;
#pragma pack(pop)

struct TIdIcmpEcho;
typedef TIdIcmpEcho *PIdIcmpEcho;

#pragma pack(push, 1)
struct TIdIcmpEcho
{
	Word id;
	Word seq;
} ;
#pragma pack(pop)

struct TIdIcmpFrag;
typedef TIdIcmpFrag *PIdIcmpFrag;

#pragma pack(push, 1)
struct TIdIcmpFrag
{
	Word pad;
	Word mtu;
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct TIdIcmpTs
{
	unsigned otime;
	unsigned rtime;
	unsigned ttime;
} ;
#pragma pack(pop)

typedef TIdIcmpTs *PIdIcmpTs;

struct TIdIcmpHdr;
typedef TIdIcmpHdr *PIdIcmpHdr;

#pragma pack(push, 1)
struct IdRawHeaders__1
{
	
	union
	{
		struct 
		{
			TIdIcmpFrag frag;
			
		};
		struct 
		{
			Idstack::TIdInAddr gateway;
			
		};
		struct 
		{
			TIdIcmpEcho echo;
			
		};
		
	};
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct IdRawHeaders__2
{
	
	union
	{
		struct 
		{
			char data;
			
		};
		struct 
		{
			unsigned mask;
			
		};
		struct 
		{
			TIdIcmpTs ts;
			
		};
		
	};
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct TIdIcmpHdr
{
	Byte icmp_type;
	Byte icmp_code;
	Word icmp_sum;
	IdRawHeaders__1 icmp_hun;
	IdRawHeaders__2 icmp_dun;
} ;
#pragma pack(pop)

struct TIdIgmpHdr;
typedef TIdIgmpHdr *PIdIgmpHdr;

#pragma pack(push, 1)
struct TIdIgmpHdr
{
	Byte igmp_type;
	Byte igmp_code;
	Word igmp_sum;
	Idstack::TIdInAddr igmp_group;
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct TIdEtherAddr
{
	Byte ether_addr_octet[6];
} ;
#pragma pack(pop)

struct TIdEthernetHdr;
typedef TIdEthernetHdr *PIdEthernetHdr;

#pragma pack(push, 1)
struct TIdEthernetHdr
{
	TIdEtherAddr ether_dhost;
	TIdEtherAddr ether_shost;
	Word ether_type;
} ;
#pragma pack(pop)

struct TIdArpHdr;
typedef TIdArpHdr *PIdArpHdr;

#pragma pack(push, 1)
struct TIdArpHdr
{
	Word arp_hrd;
	Word arp_pro;
	Byte arp_hln;
	Byte arp_pln;
	Word arp_op;
	TIdEtherAddr arp_sha;
	Idstack::TIdInAddr arp_spa;
	TIdEtherAddr arp_tha;
	Idstack::TIdInAddr arp_tpa;
} ;
#pragma pack(pop)

struct TIdDnsHdr;
typedef TIdDnsHdr *PIdDnsHdr;

#pragma pack(push, 1)
struct TIdDnsHdr
{
	Word dns_id;
	Word dns_flags;
	Word dns_num_q;
	Word dns_num_answ_rr;
	Word dns_num_auth_rr;
	Word dns_num_addi_rr;
} ;
#pragma pack(pop)

struct TIdRipHdr;
typedef TIdRipHdr *PIdRipHdr;

#pragma pack(push, 1)
struct TIdRipHdr
{
	Byte rip_cmd;
	Byte rip_ver;
	Word rip_rd;
	Word rip_af;
	Word rip_rt;
	unsigned rip_addr;
	unsigned rip_mask;
	unsigned rip_next_hop;
	unsigned rip_metric;
} ;
#pragma pack(pop)

//-- var, const, procedure ---------------------------------------------------
static const Shortint Id_ARP_HSIZE = 0x1c;
static const Shortint Id_DNS_HSIZE = 0xc;
static const Shortint Id_ETH_HSIZE = 0xe;
static const Shortint Id_ICMP_HSIZE = 0x4;
static const Shortint Id_ICMP_ECHO_HSIZE = 0x8;
static const Shortint Id_ICMP_MASK_HSIZE = 0xc;
static const Shortint Id_ICMP_UNREACH_HSIZE = 0x8;
static const Shortint Id_ICMP_TIMEXCEED_HSIZE = 0x8;
static const Shortint Id_ICMP_REDIRECT_HSIZE = 0x8;
static const Shortint Id_ICMP_TS_HSIZE = 0x14;
static const Shortint Id_IGMP_HSIZE = 0x8;
static const Shortint Id_IP_HSIZE = 0x14;
static const Shortint Id_RIP_HSIZE = 0x18;
static const Shortint Id_TCP_HSIZE = 0x14;
static const Shortint Id_UDP_HSIZE = 0x8;
static const Shortint Id_MAX_IPOPTLEN = 0x28;
static const Word Id_IP_RF = 0x8000;
static const Word Id_IP_DF = 0x4000;
static const Word Id_IP_MF = 0x2000;
static const Word Id_IP_OFFMASK = 0x1fff;
static const Word Id_IP_MAXPACKET = 0xffff;
static const Shortint Id_TCP_FIN = 0x1;
static const Shortint Id_TCP_SYN = 0x2;
static const Shortint Id_TCP_RST = 0x4;
static const Shortint Id_TCP_PUSH = 0x8;
static const Shortint Id_TCP_ACK = 0x10;
static const Shortint Id_TCP_URG = 0x20;
static const Shortint Id_ICMP_ECHOREPLY = 0x0;
static const Shortint Id_ICMP_UNREACH = 0x3;
static const Shortint Id_ICMP_SOURCEQUENCH = 0x4;
static const Shortint Id_ICMP_REDIRECT = 0x5;
static const Shortint Id_ICMP_ECHO = 0x8;
static const Shortint Id_ICMP_ROUTERADVERT = 0x9;
static const Shortint Id_ICMP_ROUTERSOLICIT = 0xa;
static const Shortint Id_ICMP_TIMXCEED = 0xb;
static const Shortint Id_ICMP_PARAMPROB = 0xc;
static const Shortint Id_ICMP_TSTAMP = 0xd;
static const Shortint Id_ICMP_TSTAMPREPLY = 0xe;
static const Shortint Id_ICMP_IREQ = 0xf;
static const Shortint Id_ICMP_IREQREPLY = 0x10;
static const Shortint Id_ICMP_MASKREQ = 0x11;
static const Shortint Id_ICMP_MASKREPLY = 0x12;
static const Shortint Id_ICMP_UNREACH_NET = 0x0;
static const Shortint Id_ICMP_UNREACH_HOST = 0x1;
static const Shortint Id_ICMP_UNREACH_PROTOCOL = 0x2;
static const Shortint Id_ICMP_UNREACH_PORT = 0x3;
static const Shortint Id_ICMP_UNREACH_NEEDFRAG = 0x4;
static const Shortint Id_ICMP_UNREACH_SRCFAIL = 0x5;
static const Shortint Id_ICMP_UNREACH_NET_UNKNOWN = 0x6;
static const Shortint Id_ICMP_UNREACH_HOST_UNKNOWN = 0x7;
static const Shortint Id_ICMP_UNREACH_ISOLATED = 0x8;
static const Shortint Id_ICMP_UNREACH_NET_PROHIB = 0x9;
static const Shortint Id_ICMP_UNREACH_HOST_PROHIB = 0xa;
static const Shortint Id_ICMP_UNREACH_TOSNET = 0xb;
static const Shortint Id_ICMP_UNREACH_TOSHOST = 0xc;
static const Shortint Id_ICMP_UNREACH_FILTER_PROHIB = 0xd;
static const Shortint Id_ICMP_UNREACH_HOST_PRECEDENCE = 0xe;
static const Shortint Id_ICMP_UNREACH_PRECEDENCE_CUTOFF = 0xf;
static const Shortint Id_ICMP_REDIRECT_NET = 0x0;
static const Shortint Id_ICMP_REDIRECT_HOST = 0x1;
static const Shortint Id_ICMP_REDIRECT_TOSNET = 0x2;
static const Shortint Id_ICMP_REDIRECT_TOSHOST = 0x3;
static const Shortint Id_ICMP_TIMXCEED_INTRANS = 0x0;
static const Shortint Id_ICMP_TIMXCEED_REASS = 0x1;
static const Shortint Id_ICMP_PARAMPROB_OPTABSENT = 0x1;
static const Shortint Id_IGMP_MEMBERSHIP_QUERY = 0x11;
static const Shortint Id_IGMP_V1_MEMBERSHIP_REPORT = 0x12;
static const Shortint Id_IGMP_V2_MEMBERSHIP_REPORT = 0x16;
static const Shortint Id_IGMP_LEAVE_GROUP = 0x17;
static const Shortint Id_ETHER_ADDR_LEN = 0x6;
static const Word Id_ETHERTYPE_PUP = 0x200;
static const Word Id_ETHERTYPE_IP = 0x800;
static const Word Id_ETHERTYPE_ARP = 0x806;
static const Word Id_ETHERTYPE_REVARP = 0x8035;
static const Word Id_ETHERTYPE_VLAN = 0x8100;
static const Word Id_ETHERTYPE_LOOPBACK = 0x9000;
static const Shortint Id_ARPHRD_ETHER = 0x1;
static const Shortint Id_ARPOP_REQUEST = 0x1;
static const Shortint Id_ARPOP_REPLY = 0x2;
static const Shortint Id_ARPOP_REVREQUEST = 0x3;
static const Shortint Id_ARPOP_REVREPLY = 0x4;
static const Shortint Id_ARPOP_INVREQUEST = 0x8;
static const Shortint Id_ARPOP_INVREPLY = 0x9;
static const Shortint Id_RIPCMD_REQUEST = 0x1;
static const Shortint Id_RIPCMD_RESPONSE = 0x2;
static const Shortint Id_RIPCMD_TRACEON = 0x3;
static const Shortint Id_RIPCMD_TRACEOFF = 0x4;
static const Shortint Id_RIPCMD_POLL = 0x5;
static const Shortint Id_RIPCMD_POLLENTRY = 0x6;
static const Shortint Id_RIPCMD_MAX = 0x7;
static const Shortint Id_RIPVER_0 = 0x0;
static const Shortint Id_RIPVER_1 = 0x1;
static const Shortint Id_RIPVER_2 = 0x2;

}	/* namespace Idrawheaders */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idrawheaders;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdRawHeaders
