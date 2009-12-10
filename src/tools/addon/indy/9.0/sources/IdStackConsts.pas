{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10345: IdStackConsts.pas 
{
{   Rev 1.0    2002.11.12 10:53:22 PM  czhower
}
unit IdStackConsts;

interface

{This should be the only unit except OS Stack units that reference
Winsock or lnxsock}

uses
  {$IFDEF LINUX}
  Libc;
  {$ELSE}
  IdWinsock2;
  {$ENDIF}

type
  TIdStackSocketHandle = TSocket;

var
  Id_SO_True: Integer = 1;
  Id_SO_False: Integer = 0;

const
  {$IFDEF LINUX}
    Id_IP_MULTICAST_TTL = IP_MULTICAST_TTL; // TODO integrate into IdStackConsts
    Id_IP_MULTICAST_LOOP = IP_MULTICAST_LOOP; // TODO integrate into IdStackConsts
    Id_IP_ADD_MEMBERSHIP = IP_ADD_MEMBERSHIP; // TODO integrate into IdStackConsts
    Id_IP_DROP_MEMBERSHIP = IP_DROP_MEMBERSHIP; // TODO integrate into IdStackConsts
  {$ELSE}
    Id_IP_MULTICAST_TTL = 10; // TODO integrate into IdStackConsts FIX ERROR in IdWinsock
    Id_IP_MULTICAST_LOOP = 11; // TODO integrate into IdStackConsts FIX ERROR in IdWinsock
    Id_IP_ADD_MEMBERSHIP = 12; // TODO integrate into IdStackConsts FIX ERROR in IdWinsock
    Id_IP_DROP_MEMBERSHIP = 13; // TODO integrate into IdStackConsts FIX ERROR in IdWinsock
  {$ENDIF}
  

(*
  There seems to be an error in the correct values of multicast values in IdWinsock
  The values should be:

  ip_options          = 1;  //* set/get IP options */
  ip_hdrincl          = 2;  //* header is included with data */
  ip_tos              = 3;  //* IP type of service and preced*/
  ip_ttl              = 4;  //* IP time to live */
  ip_multicast_if     = 9;  //* set/get IP multicast i/f  */
  ip_multicast_ttl    = 10; //* set/get IP multicast ttl */
  ip_multicast_loop   = 11; //*set/get IP multicast loopback */
  ip_add_membership   = 12; //* add an IP group membership */
  ip_drop_membership  = 13; //* drop an IP group membership */
  ip_dontfragment     = 14; //* don't fragment IP datagrams */    {Do not Localize}
*)
  {$IFDEF LINUX}
  TCP_NODELAY = 1;
  {$ENDIF}

  // Protocol Family
  Id_PF_INET = PF_INET;

	// Socket Type
  Id_SOCK_STREAM = Integer(SOCK_STREAM);
  Id_SOCK_DGRAM = Integer(SOCK_DGRAM);
  Id_SOCK_RAW = Integer(SOCK_RAW);

  // IP Protocol type
  Id_IPPROTO_IP = IPPROTO_IP;
  Id_IPPROTO_ICMP = IPPROTO_ICMP;
  Id_IPPROTO_IGMP = IPPROTO_IGMP;
  Id_IPPROTO_TCP = IPPROTO_TCP;
  Id_IPPROTO_UDP = IPPROTO_UDP;
  Id_IPPROTO_RAW = IPPROTO_RAW;
  Id_IPPROTO_MAX = IPPROTO_MAX;

  // Socket Option level
  Id_SOL_SOCKET = SOL_SOCKET;

  // Socket options
  Id_SO_BROADCAST        =  SO_BROADCAST;
  Id_SO_DEBUG            =  SO_DEBUG;
  Id_SO_DONTROUTE        =  SO_DONTROUTE;
  Id_SO_KEEPALIVE        =  SO_KEEPALIVE;
  Id_SO_LINGER	         =  SO_LINGER;
  Id_SO_OOBINLINE        =  SO_OOBINLINE;
  Id_SO_RCVBUF           =  SO_RCVBUF;
  Id_SO_REUSEADDR        =  SO_REUSEADDR;
  Id_SO_SNDBUF           =  SO_SNDBUF;

  // Additional socket options
  Id_SO_RCVTIMEO         = SO_RCVTIMEO;
  Id_SO_SNDTIMEO         = SO_SNDTIMEO;

  Id_IP_TTL              = IP_TTL;

  //
  Id_INADDR_ANY = INADDR_ANY;
  Id_INADDR_NONE = INADDR_NONE;
  // TCP Options
  Id_TCP_NODELAY = TCP_NODELAY;
  Id_INVALID_SOCKET = INVALID_SOCKET;
  Id_SOCKET_ERROR = SOCKET_ERROR;
  //
  {$IFDEF LINUX}
  // Shutdown Options
  Id_SD_Recv = SHUT_RD;
  Id_SD_Send = SHUT_WR;
  Id_SD_Both = SHUT_RDWR;
  Id_SD_Default = Id_SD_Both;
  //
  Id_WSAEINTR = EINTR;
  Id_WSAEBADF = EBADF;
  Id_WSAEACCES = EACCES;
  Id_WSAEFAULT = EFAULT;
  Id_WSAEINVAL = EINVAL;
  Id_WSAEMFILE = EMFILE;
  Id_WSAEWOULDBLOCK = EWOULDBLOCK;
  Id_WSAEINPROGRESS = EINPROGRESS;
  Id_WSAEALREADY = EALREADY;
  Id_WSAENOTSOCK = ENOTSOCK;
  Id_WSAEDESTADDRREQ = EDESTADDRREQ;
  Id_WSAEMSGSIZE = EMSGSIZE;
  Id_WSAEPROTOTYPE = EPROTOTYPE;
  Id_WSAENOPROTOOPT = ENOPROTOOPT;
  Id_WSAEPROTONOSUPPORT = EPROTONOSUPPORT;
  Id_WSAESOCKTNOSUPPORT = ESOCKTNOSUPPORT;

  Id_WSAEOPNOTSUPP = EOPNOTSUPP;
  Id_WSAEPFNOSUPPORT = EPFNOSUPPORT;
  Id_WSAEAFNOSUPPORT = EAFNOSUPPORT;
  Id_WSAEADDRINUSE = EADDRINUSE;
  Id_WSAEADDRNOTAVAIL = EADDRNOTAVAIL;
  Id_WSAENETDOWN = ENETDOWN;
  Id_WSAENETUNREACH = ENETUNREACH;
  Id_WSAENETRESET = ENETRESET;
  Id_WSAECONNABORTED = ECONNABORTED;
  Id_WSAECONNRESET = ECONNRESET;
  Id_WSAENOBUFS = ENOBUFS;
  Id_WSAEISCONN = EISCONN;
  Id_WSAENOTCONN = ENOTCONN;
  Id_WSAESHUTDOWN = ESHUTDOWN;
  Id_WSAETOOMANYREFS = ETOOMANYREFS;
  Id_WSAETIMEDOUT = ETIMEDOUT;
  Id_WSAECONNREFUSED = ECONNREFUSED;
  Id_WSAELOOP = ELOOP;
  Id_WSAENAMETOOLONG = ENAMETOOLONG;
  Id_WSAEHOSTDOWN = EHOSTDOWN;
  Id_WSAEHOSTUNREACH = EHOSTUNREACH;
  Id_WSAENOTEMPTY = ENOTEMPTY;
  {$ELSE}
  // Shutdown Options
  Id_SD_Recv = 0;
  Id_SD_Send = 1;
  Id_SD_Both = 2;
  Id_SD_Default = Id_SD_Send;
  //
  Id_WSAEINTR = WSAEINTR;
  Id_WSAEBADF = WSAEBADF;
  Id_WSAEACCES = WSAEACCES;
  Id_WSAEFAULT = WSAEFAULT;
  Id_WSAEINVAL = WSAEINVAL;
  Id_WSAEMFILE = WSAEMFILE;
  Id_WSAEWOULDBLOCK = WSAEWOULDBLOCK;
  Id_WSAEINPROGRESS = WSAEINPROGRESS;
  Id_WSAEALREADY = WSAEALREADY;
  Id_WSAENOTSOCK = WSAENOTSOCK;
  Id_WSAEDESTADDRREQ = WSAEDESTADDRREQ;
  Id_WSAEMSGSIZE = WSAEMSGSIZE;
  Id_WSAEPROTOTYPE = WSAEPROTOTYPE;
  Id_WSAENOPROTOOPT = WSAENOPROTOOPT;
  Id_WSAEPROTONOSUPPORT = WSAEPROTONOSUPPORT;
  Id_WSAESOCKTNOSUPPORT = WSAESOCKTNOSUPPORT;

  Id_WSAEOPNOTSUPP = WSAEOPNOTSUPP;
  Id_WSAEPFNOSUPPORT = WSAEPFNOSUPPORT;
  Id_WSAEAFNOSUPPORT = WSAEAFNOSUPPORT;
  Id_WSAEADDRINUSE = WSAEADDRINUSE;
  Id_WSAEADDRNOTAVAIL = WSAEADDRNOTAVAIL;
  Id_WSAENETDOWN = WSAENETDOWN;
  Id_WSAENETUNREACH = WSAENETUNREACH;
  Id_WSAENETRESET = WSAENETRESET;
  Id_WSAECONNABORTED = WSAECONNABORTED;
  Id_WSAECONNRESET = WSAECONNRESET;
  Id_WSAENOBUFS = WSAENOBUFS;
  Id_WSAEISCONN = WSAEISCONN;
  Id_WSAENOTCONN = WSAENOTCONN;
  Id_WSAESHUTDOWN = WSAESHUTDOWN;
  Id_WSAETOOMANYREFS = WSAETOOMANYREFS;
  Id_WSAETIMEDOUT = WSAETIMEDOUT;
  Id_WSAECONNREFUSED = WSAECONNREFUSED;
  Id_WSAELOOP = WSAELOOP;
  Id_WSAENAMETOOLONG = WSAENAMETOOLONG;
  Id_WSAEHOSTDOWN = WSAEHOSTDOWN;
  Id_WSAEHOSTUNREACH = WSAEHOSTUNREACH;
  Id_WSAENOTEMPTY = WSAENOTEMPTY;
  {$ENDIF}

implementation

end.
