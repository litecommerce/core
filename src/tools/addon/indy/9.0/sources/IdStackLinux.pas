{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10347: IdStackLinux.pas 
{
{   Rev 1.1    4/20/03 1:50:42 PM  RLebeau
{ Updated TranslateStringToTInAddr() to use new TIdSTack::GetIPInfo() method.
}
{
{   Rev 1.0    2002.11.12 10:53:30 PM  czhower
}
unit IdStackLinux;
interface

uses
  Classes,
  Libc,
  IdStack, IdStackConsts;

type
  TIdSocketListLinux = class (TIdSocketList)
  protected
    FFDSet: TFDSet;
    FMaxHandle: TIdStackSocketHandle;
    //
    function GetItem(AIndex: Integer): TIdStackSocketHandle; override;
  public
    procedure Add(AHandle: TIdStackSocketHandle); override;
    procedure Remove(AHandle: TIdStackSocketHandle); override;
    function  Count: Integer; override;
  End;//TIdSocketList

  TIdStackLinux = class(TIdStack)
  protected
    procedure PopulateLocalAddresses; override;
    function WSGetLocalAddress: string; override;
    function WSGetLocalAddresses: TStrings; override;
  public
    function TInAddrToString(var AInAddr): string; override;
    procedure TranslateStringToTInAddr(AIP: string; var AInAddr); override;
    function WSTranslateSocketErrorMsg(const AErr: integer): string; override;
    //
    function WSAccept(ASocket: TIdStackSocketHandle; var VIP: string; var VPort: Integer)
     : TIdStackSocketHandle; override;
    function WSBind(ASocket: TIdStackSocketHandle; const AFamily: Integer;
     const AIP: string; const APort: Integer): Integer; override;
    function WSCloseSocket(ASocket: TIdStackSocketHandle): Integer; override;
    function WSConnect(const ASocket: TIdStackSocketHandle; const AFamily: Integer;
     const AIP: string; const APort: Integer): Integer; override;
    function WSGetHostByAddr(const AAddress: string): string; override;
    function WSGetHostByName(const AHostName: string): string; override;
    function WSGetHostName: string; override;
    function WSGetLastError: Integer; override;
    function WSGetServByName(const AServiceName: string): Integer; override;
    function WSGetServByPort(const APortNumber: Integer): TStrings; override;
    function WSGetSockOpt(ASocket: TIdStackSocketHandle; Alevel, AOptname: Integer; AOptval: PChar;
     var AOptlen: Integer): Integer; override;
    procedure WSGetPeerName(ASocket: TIdStackSocketHandle;
      var VFamily: Integer; var VIP: string; var VPort: Integer); override;
    procedure WSGetSockName(ASocket: TIdStackSocketHandle;
     var VFamily: Integer; var VIP: string; var VPort: Integer); override;
    function WSHToNs(AHostShort: Word): Word; override;
    function WSListen(ASocket: TIdStackSocketHandle; ABackLog: Integer): Integer; override;
    function WSNToHs(ANetShort: Word): Word; override;
    function WSHToNL(AHostLong: LongWord): LongWord; override;
    function WSNToHL(ANetLong: LongWord): LongWord; override;
    function WSRecv(ASocket: TIdStackSocketHandle; var ABuffer; const ABufferLength, AFlags: Integer)
     : integer; override;
    function WSRecvFrom(const ASocket: TIdStackSocketHandle; var ABuffer;
     const ALength, AFlags: Integer; var VIP: string; var VPort: Integer): Integer; override;
    function WSSelect(ARead, AWrite, AErrors: TList; ATimeout: Integer): Integer; override;
    function WSSend(ASocket: TIdStackSocketHandle; var ABuffer;
     const ABufferLength, AFlags: Integer): Integer; override;
    function WSSendTo(ASocket: TIdStackSocketHandle; var ABuffer;
     const ABufferLength, AFlags: Integer; const AIP: string; const APort: integer): Integer;
     override;
    function WSSetSockOpt(ASocket: TIdStackSocketHandle; ALevel, AOptName: Integer; AOptVal: PChar;
     AOptLen: Integer): Integer; override;
    function WSSocket(AFamily, AStruct, AProtocol: Integer): TIdStackSocketHandle; override;
    function WSShutdown(ASocket: TIdStackSocketHandle; AHow: Integer): Integer; override;
  end;
  TLinger = record
    l_onoff: Word;
    l_linger: Word;
  end;
  TIdLinger = TLinger;

implementation

uses
  IdException,
  IdGlobal, IdResourceStrings,
  SysUtils;

const
  Id_MSG_NOSIGNAL = MSG_NOSIGNAL;
  Id_WSAEPIPE = EPIPE;

function TIdStackLinux.TInAddrToString(var AInAddr): string;
begin
  with TInAddr(AInAddr).S_un_b do begin
    Result := IntToStr(Ord(s_b1)) + '.' + IntToStr(Ord(s_b2)) + '.' + IntToStr(Ord(s_b3)) + '.'    {Do not Localize}
     + IntToStr(Ord(s_b4));
  end;
end;

function TIdStackLinux.WSAccept(ASocket: TIdStackSocketHandle;
 var VIP: string; var VPort: Integer): TIdStackSocketHandle;

var
  i: Cardinal;
  LAddr: SockAddr;

begin
  i := SizeOf(LAddr);
  Result := Accept(ASocket, @LAddr, @i);
  if Result <> SOCKET_ERROR then begin
    VIP := TInAddrToString(LAddr.sin_addr);
    VPort := NToHs(LAddr.sin_port);
  end else begin
    if GetLastError = EBADF then begin
      SetLastError(EINTR);
    end;
  end;
end;

function TIdStackLinux.WSBind(ASocket: TIdStackSocketHandle;
  const AFamily: Integer; const AIP: string;
  const APort: Integer): Integer;

var
  Addr: SockAddr;

begin
  Addr.sin_family := AFamily;
  if length(AIP) = 0 then begin
    Addr.sin_addr.s_addr := INADDR_ANY;
  end else begin
    TranslateStringToTInAddr(AIP, Addr.sin_addr);
  end;
  Addr.sin_port := HToNs(APort);
  Result := Bind(ASocket, addr, SizeOf(Addr));
end;

function TIdStackLinux.WSCloseSocket(ASocket: TIdStackSocketHandle): Integer;
begin
  Result := Libc.__close(ASocket);
end;

function TIdStackLinux.WSConnect(const ASocket: TIdStackSocketHandle;
  const AFamily: Integer; const AIP: string;
  const APort: Integer): Integer;

var
  Addr: SockAddr;

begin
  Addr.sin_family := AFamily;
  TranslateStringToTInAddr(AIP, Addr.sin_addr);
  Addr.sin_port := HToNs(APort);
  Result := Connect(ASocket, Addr, SizeOf(Addr));
end;

function TIdStackLinux.WSGetHostByName(const AHostName: string): string;
var
  pa: PChar;
  sa: TInAddr;
  Host: PHostEnt;

begin
  //we don't use _r functions because they are depreciated and the non-r's are safe in Linux.
  //They could be problematic in Sun Solorus and BSD.
  Host := GethostByName(PChar(AHostName));
  if (Host <> nil) then
  begin
    pa := Host^.h_addr_list^;
    sa.S_un_b.s_b1 := Ord(pa[0]);
    sa.S_un_b.s_b2 := Ord(pa[1]);
    sa.S_un_b.s_b3 := Ord(pa[2]);
    sa.S_un_b.s_b4 := Ord(pa[3]);
    Result := TInAddrToString(sa);
  end
  else
  begin
    RaiseSocketError(h_errno);
  end;
end;

function TIdStackLinux.WSGetHostName: string;
begin
  SetLength(Result, 250);
  GetHostName(PChar(Result), Length(Result));
  Result := String(PChar(Result));
end;

function TIdStackLinux.WSListen(ASocket: TIdStackSocketHandle;
  ABackLog: Integer): Integer;

begin
  Result := Listen(ASocket, ABacklog);
end;

function TIdStackLinux.WSRecv(ASocket: TIdStackSocketHandle; var ABuffer;
  const ABufferLength, AFlags: Integer): integer;

begin
  Result := Recv(ASocket, ABuffer, ABufferLength, AFlags or Id_MSG_NOSIGNAL);
end;

function TIdStackLinux.WSRecvFrom(const ASocket: TIdStackSocketHandle;
  var ABuffer; const ALength, AFlags: Integer; var VIP: string;
  var VPort: Integer): Integer;
var
  iSize: Cardinal;
  Addr: sockaddr;
begin
  iSize := SizeOf(Addr);
  Result := RecvFrom(ASocket, ABuffer, ALength, AFlags or Id_MSG_NOSIGNAL, @Addr, @iSize);
  VIP := TInAddrToString(Addr.sin_addr);
  VPort := NToHs(Addr.sin_port);
end;

function TIdStackLinux.WSSelect(ARead, AWrite, AErrors: TList; ATimeout: Integer): Integer;
var
  tmTo: TTimeVal;
  FDRead, FDWrite, FDError: TFDSet;
  LMaxHandle: TIdStackSocketHandle;

  { TODO : Optimize and cache these routines }

  procedure GetFDSet(AList: TList; var ASet: TFDSet);
  var
    i: Integer;

  begin
    if assigned( AList ) then
    begin
      AList.Clear;
      for i := 0 to __FD_SETSIZE - 1 do
        begin
          if FD_ISSET(i, ASet) then
          begin
            AList.Add(TObject(i));
          end;
        end;
    end;
  end;

  procedure SetFDSet(AList: TList; var ASet: TFDSet);
  var
    i: integer;

  begin
    if AList <> nil then begin
      if AList.Count > __FD_SETSIZE then begin
        raise EIdSetSizeExceeded.Create(RSSetSizeExceeded);
      end;
      for i := 0 to AList.Count - 1 do begin
        FD_SET(TIdStackSocketHandle(AList[i]), ASet);
        LMaxHandle := Max(LMaxHandle, TIdStackSocketHandle(AList[i]) + 1);
      end;
    end;
  end;

begin
  LMaxHandle := 0;
  FD_ZERO(FDRead);
  FD_ZERO(FDWrite);
  FD_ZERO(FDError);
  SetFDSet(ARead, FDRead);
  SetFDSet(AWrite, FDWrite);
  SetFDSet(AErrors, FDError);
  if ATimeout = IdTimeoutInfinite then begin
    Result := Select(LMaxHandle, @FDRead, @FDWrite, @FDError, nil);
  end else begin
    tmTo.tv_sec := ATimeout div 1000;
    tmTo.tv_usec := (ATimeout mod 1000) * 1000;
    Result := Select(LMaxHandle, @FDRead, @FDWrite, @FDError, @tmTO);
  end;
  GetFDSet(ARead, FDRead);
  GetFDSet(AWrite, FDWrite);
  GetFDSet(AErrors, FDError);
end;

function TIdStackLinux.WSSend(ASocket: TIdStackSocketHandle;
  var ABuffer; const ABufferLength, AFlags: Integer): Integer;
begin
  Result := Send(ASocket, ABuffer, ABufferLength, AFlags or Id_MSG_NOSIGNAL);
end;

function TIdStackLinux.WSSendTo(ASocket: TIdStackSocketHandle;
  var ABuffer; const ABufferLength, AFlags: Integer; const AIP: string;
  const APort: integer): Integer;

var
  Addr: SockAddr;

begin
  FillChar(Addr, SizeOf(Addr), 0);
  with Addr do
  begin
    sin_family := Id_PF_INET;
    TranslateStringToTInAddr(AIP, sin_addr);
    sin_port := HToNs(APort);
  end;
  Result := SendTo(ASocket, ABuffer, ABufferLength, AFlags or Id_MSG_NOSIGNAL, Addr, SizeOf(Addr));
end;

function TIdStackLinux.WSSetSockOpt(ASocket: TIdStackSocketHandle;
  ALevel, AOptName: Integer; AOptVal: PChar; AOptLen: Integer): Integer;

begin
  Result := SetSockOpt(ASocket, ALevel, AOptName, AOptVal, AOptLen);
end;

function TIdStackLinux.WSGetLastError: Integer;
begin
  Result := System.GetLastError;
  if Result = Id_WSAEPIPE then
  begin
    Result := Id_WSAECONNRESET;
  end;
end;

function TIdStackLinux.WSSocket(AFamily, AStruct, AProtocol: Integer): TIdStackSocketHandle;
begin
  Result := Socket(AFamily, AStruct, AProtocol);
end;

function TIdStackLinux.WSHToNs(AHostShort: Word): Word;
begin
  Result := HToNs(AHostShort);
end;

function TIdStackLinux.WSNToHs(ANetShort: Word): Word;
begin
  Result := NToHs(ANetShort);
end;

function TIdStackLinux.WSGetLocalAddresses: TStrings;
begin
  if FLocalAddresses = nil then
  begin
    FLocalAddresses := TStringList.Create;
  end;
  PopulateLocalAddresses;
  Result := FLocalAddresses;
end;

function TIdStackLinux.WSGetServByName(const AServiceName: string): Integer;
var
  ps: PServEnt;

begin
  ps := GetServByName(PChar(AServiceName), nil);
  if ps <> nil then begin
    Result := Ntohs(ps^.s_port);
  end else begin
    try
      Result := StrToInt(AServiceName);
    except
      on EConvertError do raise EIdInvalidServiceName.CreateFmt(RSInvalidServiceName, [AServiceName]);
    end;
  end;
end;

function TIdStackLinux.WSGetServByPort(const APortNumber: Integer): TStrings;
var
  ps: PServEnt;
  i: integer;
  p: array of PChar;

begin
  Result := TStringList.Create;
  p := nil;
  try
    ps := GetServByPort(HToNs(APortNumber), nil);
    if ps <> nil then
    begin
      Result.Add(ps^.s_name);
      i := 0;
      p := pointer(ps^.s_aliases);
      while p[i] <> nil do
      begin
        Result.Add(PChar(p[i]));
        inc(i);
      end;
    end;
  except
    Result.Free;
  end;
end;

function TIdStackLinux.WSHToNL(AHostLong: LongWord): LongWord;
begin
  Result := HToNL(AHostLong);
end;

function TIdStackLinux.WSNToHL(ANetLong: LongWord): LongWord;
begin
  Result := NToHL(ANetLong);
end;

procedure TIdStackLinux.PopulateLocalAddresses;
type
  TaPInAddr = Array[0..250] of PInAddr;
  PaPInAddr = ^TaPInAddr;

var
  i: integer;
  AHost: PHostEnt;
  PAdrPtr: PaPInAddr;

begin
  FLocalAddresses.Clear ;
  AHost := GetHostByName(PChar(WSGetHostName));
  if AHost = nil then
  begin
    CheckForSocketError(SOCKET_ERROR);
  end
  else
  begin
    PAdrPtr := PAPInAddr(AHost^.h_addr_list);
    i := 0;
    while PAdrPtr^[i] <> nil do
    begin
      FLocalAddresses.Add(TInAddrToString(PAdrPtr^[I]^));
      Inc(I);
    end;
  end;
end;

function TIdStackLinux.WSGetLocalAddress: string;
begin
  Result := LocalAddresses[0];
end;

procedure TIdStackLinux.TranslateStringToTInAddr(AIP: string; var AInAddr);
begin
  with TInAddr(AInAddr).S_un_b do
  begin
    if not GetIPInfo(AIP, @s_b1, @s_b2, @s_b3, @s_b4) then
    begin
      raise EIdInvalidIPAddress.CreateFmt(RSStackInvalidIP, [AIP]);
    end;
  end;
end;

function TIdStackLinux.WSGetHostByAddr(const AAddress: string): string;
//GetHostByAddr is thread-safe in Linux.  It might not be safe in Solorus or BSD Unix
var
  Host: PHostEnt;
  LAddr: u_long;

begin
  LAddr := inet_addr(PChar(AAddress));
  Host := GetHostByAddr(@LAddr,SizeOf(LAddr),AF_INET);
  if (Host <> nil) then
  begin
    Result := Host^.h_name;
  end
  else
  begin
    RaiseSocketError(h_errno);
  end;
end;

function TIdStackLinux.WSShutdown(ASocket: TIdStackSocketHandle; AHow: Integer): Integer;
begin
  Result := Shutdown(ASocket, AHow);
end;

procedure TIdStackLinux.WSGetPeerName(ASocket: TIdStackSocketHandle;
  var VFamily: Integer; var VIP: string; var VPort: Integer);
var
  i: Cardinal;
  LAddr: TSockAddrIn;

begin
  i := SizeOf(LAddr);
  CheckForSocketError(GetPeerName(ASocket, LAddr, i));
  VFamily := LAddr.sin_family;
  VIP := TInAddrToString(LAddr.sin_addr);
  VPort := Ntohs(LAddr.sin_port);
end;

procedure TIdStackLinux.WSGetSockName(ASocket: TIdStackSocketHandle;
  var VFamily: Integer; var VIP: string; var VPort: Integer);
var
  i: Cardinal;
  LAddr: TSockAddrIn;

begin
  i := SizeOf(LAddr);
  CheckForSocketError(GetSockName(ASocket, LAddr, i));
  VFamily := LAddr.sin_family;
  VIP := TInAddrToString(LAddr.sin_addr);
  VPort := Ntohs(LAddr.sin_port);
end;

function TIdStackLinux.WSGetSockOpt(ASocket: TIdStackSocketHandle; Alevel, AOptname: Integer; AOptval: PChar; var AOptlen: Integer): Integer;
begin
  Result := libc.GetSockOpt(ASocket, ALevel, AOptname, AOptval, Cardinal(AOptlen));
end;

{ TIdSocketListLinux }

procedure TIdSocketListLinux.Add(AHandle: TIdStackSocketHandle);
Begin
  FD_SET(AHandle, FFDSet);
  FMaxHandle := Max(FMaxHandle, AHandle + 1);
End;//

function TIdSocketListLinux.Count: Integer;
var
  I: Integer;

Begin
  Result := 0;
  for i:= 0 to __FD_SETSIZE - 1 do begin //? use FMaxHandle div x
    if FD_ISSET(i, FFDSet) then begin
      inc(Result);
    end;
  end;
End;//



function TIdSocketListLinux.GetItem(AIndex: Integer): TIdStackSocketHandle;
var
  LIndex, i: Integer;

Begin
  Result := 0;
  LIndex := 0;
  for i:= 0 to __FD_SETSIZE - 1 do begin //? use FMaxHandle div x
   if FD_ISSET(i, FFDSet) then begin
      if LIndex = AIndex then begin
        Result := i;
        Break;
      end else begin
        inc(LIndex);
      end;
    end;//if item
  end;
End;//

procedure TIdSocketListLinux.Remove(AHandle: TIdStackSocketHandle);
var
  i: Integer;

Begin
  FD_CLR(AHandle, FFDSet);
  if AHandle+1 >= FMaxHandle then begin
    for i:=__FD_SETSIZE - 1 downto 0 do begin
      if FD_ISSET(i, FFDSet) then begin
        FMaxHandle := i + 1;
        Break;
      end;
    end;
  end;
End;//

function TIdStackLinux.WSTranslateSocketErrorMsg(
  const AErr: integer): string;
//we override this function for the herr constants that
//are returned by the DNS functions
begin
  case AErr of
    libc.HOST_NOT_FOUND : Result := RSStackHOST_NOT_FOUND;
    libc.TRY_AGAIN : Result := RSStackTRY_AGAIN;
    libc.NO_RECOVERY : Result := RSStackNO_RECOVERY;
    libc.NO_DATA : Result := RSStackNO_DATA;
  else
    Result := inherited WSTranslateSocketErrorMsg(AErr);
  end;
end;

INITIALIZATION
  GSocketListClass := TIdSocketListLinux;
end.

