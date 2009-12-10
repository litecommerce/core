{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10349: IdStackWindows.pas 
{
    Rev 1.3    5/19/2003 5:58:06 PM  BGooijen
  TIdStackWindows.WSGetHostByAddr raised an ERangeError when the last number in
  the ip>127
}
{
    Rev 1.2    4/25/2003 7:01:18 PM  BGooijen
  changed TIdStackWindows.TInAddrToString back
}
{
{   Rev 1.1    4/20/03 1:51:46 PM  RLebeau
{ Updated TInAddrToString() to use inet_ntoa() instead of parsing the values
{ manually.
{ 
{ Updated TranslateStringToTInAddr() to use new TIdSTack::GetIPInfo() method.
}
{
{   Rev 1.0    2002.11.12 10:53:40 PM  czhower
}
unit IdStackWindows;

interface

uses
  Classes,
  IdStack, IdStackConsts, IdWinsock2, Windows;

type
  TIdSocketListWindows = class (TIdSocketList)
  protected
    FFDSet: TFDSet;
    //
    function GetItem(AIndex: Integer): TIdStackSocketHandle; override;
  public
    procedure Add(AHandle: TIdStackSocketHandle); override;
    procedure Remove(AHandle: TIdStackSocketHandle); override;
    function  Count: Integer; override;
  End;//TIdSocketList


  TIdStackWindows = class(TIdStack)
  protected
    procedure PopulateLocalAddresses; override;
    function WSGetLocalAddress: string; override;
    function WSGetLocalAddresses: TStrings; override;
  public
    constructor Create; override;
    destructor Destroy; override;
    function TInAddrToString(var AInAddr): string; override;
    procedure TranslateStringToTInAddr(AIP: string; var AInAddr); override;
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
    function WSGetServByName(const AServiceName: string): Integer; override;
    function WSGetServByPort(const APortNumber: Integer): TStrings; override;
    procedure WSGetPeerName(ASocket: TIdStackSocketHandle; var VFamily: Integer;
     var VIP: string; var VPort: Integer); override;
    procedure WSGetSockName(ASocket: TIdStackSocketHandle; var VFamily: Integer;
     var VIP: string; var VPort: Integer); override;
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
    function WSTranslateSocketErrorMsg(const AErr: integer): string; override;
    function WSGetLastError: Integer; override;
    function WSGetSockOpt(ASocket: TIdStackSocketHandle; Alevel, AOptname: Integer; AOptval: PChar; var AOptlen: Integer): Integer; override;
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

var
  GStarted: Boolean = False;

constructor TIdStackWindows.Create;
var
  sData: TWSAData;
begin
  inherited Create;
  if not GStarted then
  begin
    if WSAStartup($202, sData) = SOCKET_ERROR then begin
      raise  EIdStackInitializationFailed.Create(RSWinsockInitializationError);
    end;
    GStarted := True;
  end;
end;

destructor TIdStackWindows.Destroy;
begin
  //DLL Unloading and Cleanup is done at finalization
  inherited Destroy;
end;

//function TIdStackWindows.TInAddrToString(AInAddr: TInAddr): string;
function TIdStackWindows.TInAddrToString(var AInAddr): string;
begin
  with TInAddr(AInAddr).S_un_b do begin
    result := IntToStr(s_b1) + '.' + IntToStr(s_b2) + '.' + IntToStr(s_b3) + '.'    {Do not Localize}
     + IntToStr(s_b4);
  end;
  
// RL: 4/13/2003
//  Result := inet_ntoa(TInAddr(AInAddr)); //BGO: Causes socket error 0
end;

function TIdStackWindows.WSAccept(ASocket: TIdStackSocketHandle;
  var VIP: string; var VPort: Integer): TIdStackSocketHandle;
var
  i: Integer;
  Addr: TSockAddr;
begin
  i := SizeOf(addr);
  result := Accept(ASocket, @addr, @i);
  VIP := TInAddrToString(Addr.sin_addr);
  VPort := NToHs(Addr.sin_port);
end;

function TIdStackWindows.WSBind(ASocket: TIdStackSocketHandle;
  const AFamily: Integer; const AIP: string;
  const APort: Integer): Integer;
var
  Addr: TSockAddrIn;
begin
  Addr.sin_family := AFamily;
  if length(AIP) = 0 then begin
    Addr.sin_addr.s_addr := INADDR_ANY;
  end else begin
    Addr.sin_addr := TInAddr(StringToTInAddr(AIP));
  end;
  Addr.sin_port := HToNS(APort);
  result := Bind(ASocket, @addr, SizeOf(Addr));
end;

function TIdStackWindows.WSCloseSocket(ASocket: TIdStackSocketHandle): Integer;
begin
  result := CloseSocket(ASocket);
end;

function TIdStackWindows.WSConnect(const ASocket: TIdStackSocketHandle;
  const AFamily: Integer; const AIP: string;
  const APort: Integer): Integer;
var
  Addr: TSockAddrIn;
begin
  Addr.sin_family := AFamily;
  Addr.sin_addr := TInAddr(StringToTInAddr(AIP));
  Addr.sin_port := HToNS(APort);
  result := Connect(ASocket, @Addr, SizeOf(Addr));
end;

function TIdStackWindows.WSGetHostByName(const AHostName: string): string;
var
  pa: PChar;
  sa: TInAddr;
  Host: PHostEnt;
begin
  Host := GetHostByName(PChar(AHostName));
  if Host = nil then begin
    CheckForSocketError(SOCKET_ERROR);
  end else begin
    pa := Host^.h_address_list^;
    sa.S_un_b.s_b1 := Ord(pa[0]);
    sa.S_un_b.s_b2 := Ord(pa[1]);
    sa.S_un_b.s_b3 := Ord(pa[2]);
    sa.S_un_b.s_b4 := Ord(pa[3]);
    result := TInAddrToString(sa);
  end;
end;

function TIdStackWindows.WSGetHostByAddr(const AAddress: string): string;
var
  Host: PHostEnt;
  LAddr: u_long;
begin
  LAddr := inet_addr(PChar(AAddress));
  Host := GetHostByAddr(@LAddr, SizeOf(LAddr), AF_INET);
  if Host = nil then begin
    CheckForSocketError(SOCKET_ERROR);
  end else begin
    result := Host^.h_name;
  end;
end;

function TIdStackWindows.WSGetHostName: string;
begin
  SetLength(result, 250);
  GetHostName(PChar(result), Length(result));
  Result := String(PChar(result));
end;

function TIdStackWindows.WSListen(ASocket: TIdStackSocketHandle;
  ABackLog: Integer): Integer;
begin
  result := Listen(ASocket, ABacklog);
end;

function TIdStackWindows.WSRecv(ASocket: TIdStackSocketHandle; var ABuffer;
  const ABufferLength, AFlags: Integer) : Integer;
begin
  result := Recv(ASocket, ABuffer, ABufferLength, AFlags);
end;

function TIdStackWindows.WSRecvFrom(const ASocket: TIdStackSocketHandle;
  var ABuffer; const ALength, AFlags: Integer; var VIP: string;
  var VPort: Integer): Integer;
var
  iSize: integer;
  Addr: TSockAddrIn;
begin
  iSize := SizeOf(Addr);
  result := RecvFrom(ASocket, ABuffer, ALength, AFlags, @Addr, @iSize);
  VIP := TInAddrToString(Addr.sin_addr);
  VPort := NToHs(Addr.sin_port);
end;

function TIdStackWindows.WSSelect(ARead, AWrite, AErrors: TList; ATimeout: Integer): Integer;
var
  tmTo: TTimeVal;
  FDRead, FDWrite, FDError: TFDSet;

  procedure GetFDSet(AList: TList; var ASet: TFDSet);
  var
    i: Integer;
  begin
    if assigned( AList ) then begin
      AList.Clear; // SG 18/10/00: ALWAYS clear the result list
      AList.Capacity := ASet.fd_count;
      for i := 0 to ASet.fd_count - 1 do begin
        AList.Add(TObject(ASet.fd_array[i]));
      end;
    end;
  end;

  procedure SetFDSet(AList: TList; var ASet: TFDSet);
  var
    i: integer;
  begin
    if AList <> nil then begin
      if AList.Count > FD_SETSIZE then begin
        raise EIdStackSetSizeExceeded.Create(RSSetSizeExceeded);
      end;
      for i := 0 to AList.Count - 1 do begin
        ASet.fd_array[i] := TIdStackSocketHandle(AList[i]);
      end;
      ASet.fd_count := AList.Count;
    end;
  end;

begin
  FillChar(FDRead, SizeOf(FDRead), 0);
  FillChar(FDWrite, SizeOf(FDWrite), 0);
  FillChar(FDError, SizeOf(FDError), 0);
  SetFDSet(ARead, FDRead);
  SetFDSet(AWrite, FDWrite);
  SetFDSet(AErrors, FDError);
  if ATimeout = IdTimeoutInfinite then begin
    Result := Select(0, @FDRead, @FDWrite, @FDError, nil);
  end else begin
    tmTo.tv_sec := ATimeout div 1000;
    tmTo.tv_usec := (ATimeout mod 1000) * 1000;
    Result := Select(0, @FDRead, @FDWrite, @FDError, @tmTO);
  end;
  GetFDSet(ARead, FDRead);
  GetFDSet(AWrite, FDWrite);
  GetFDSet(AErrors, FDError);
end;

function TIdStackWindows.WSSend(ASocket: TIdStackSocketHandle;
  var ABuffer; const ABufferLength, AFlags: Integer): Integer;
begin
  result := Send(ASocket, ABuffer, ABufferLength, AFlags);
end;

function TIdStackWindows.WSSendTo(ASocket: TIdStackSocketHandle;
  var ABuffer; const ABufferLength, AFlags: Integer; const AIP: string;
  const APort: integer): Integer;
var
  Addr: TSockAddrIn;
begin
  FillChar(Addr, SizeOf(Addr), 0);
  with Addr do
  begin
    sin_family := Id_PF_INET;
    sin_addr := TInAddr(StringToTInAddr(AIP));
    sin_port := HToNs(APort);
  end;
  result := SendTo(ASocket, ABuffer, ABufferLength, AFlags, @Addr, SizeOf(Addr));
end;

function TIdStackWindows.WSSetSockOpt(ASocket: TIdStackSocketHandle;
  ALevel, AOptName: Integer; AOptVal: PChar; AOptLen: Integer): Integer;
begin
  result := SetSockOpt(ASocket, ALevel, AOptName, AOptVal, AOptLen);
end;

function TIdStackWindows.WSGetLocalAddresses: TStrings;
begin
  if FLocalAddresses = nil then
  begin
    FLocalAddresses := TStringList.Create;
  end;
  PopulateLocalAddresses;
  Result := FLocalAddresses;
end;

function TIdStackWindows.WSGetLastError: Integer;
begin
  result := WSAGetLastError;
end;

function TIdStackWindows.WSSocket(AFamily, AStruct, AProtocol: Integer): TIdStackSocketHandle;
begin
  result := Socket(AFamily, AStruct, AProtocol);
end;

function TIdStackWindows.WSHToNs(AHostShort: Word): Word;
begin
  result := HToNs(AHostShort);
end;

function TIdStackWindows.WSNToHs(ANetShort: Word): Word;
begin
  result := NToHs(ANetShort);
end;


function TIdStackWindows.WSGetServByName(const AServiceName: string): Integer;
var
  ps: PServEnt;
begin
  ps := GetServByName(PChar(AServiceName), nil);
  if ps <> nil then
  begin
    Result := Ntohs(ps^.s_port);
  end
  else
  begin
    try
      Result := StrToInt(AServiceName);
    except
      on EConvertError do raise EIdInvalidServiceName.CreateFmt(RSInvalidServiceName, [AServiceName]);
    end;
  end;
end;

function TIdStackWindows.WSGetServByPort(
  const APortNumber: Integer): TStrings;
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

function TIdStackWindows.WSHToNL(AHostLong: LongWord): LongWord;
begin
  Result := HToNL(AHostLong);
end;

function TIdStackWindows.WSNToHL(ANetLong: LongWord): LongWord;
begin
  Result := NToHL(ANetLong);
end;

procedure TIdStackWindows.PopulateLocalAddresses;
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
    PAdrPtr := PAPInAddr(AHost^.h_address_list);
    i := 0;
    while PAdrPtr^[i] <> nil do
    begin
      FLocalAddresses.Add(TInAddrToString(PAdrPtr^[I]^));
      Inc(I);
    end;
  end;
end;

function TIdStackWindows.WSGetLocalAddress: string;
begin
  Result := LocalAddresses[0];
end;

{ TIdStackVersionWinsock }

function ServeFile(ASocket: TIdStackSocketHandle; AFileName: string): cardinal;
var
  LFileHandle: THandle;
begin
  result := 0;
  LFileHandle := CreateFile(PChar(AFileName), GENERIC_READ, FILE_SHARE_READ, nil, OPEN_EXISTING
   , FILE_ATTRIBUTE_NORMAL or FILE_FLAG_SEQUENTIAL_SCAN, 0); try
    if TransmitFile(ASocket, LFileHandle, 0, 0, nil, nil, 0) then begin
      result := getFileSize(LFileHandle, nil);
    end;
  finally CloseHandle(LFileHandle); end;
end;

procedure TIdStackWindows.TranslateStringToTInAddr(AIP: string; var AInAddr);
begin
  with TInAddr(AInAddr).S_un_b do
  begin
    if not GetIPInfo(AIP, @s_b1, @s_b2, @s_b3, @s_b4) then
    begin
      raise EIdInvalidIPAddress.CreateFmt(RSStackInvalidIP, [AIP]);
    end;
  end;
end;

function TIdStackWindows.WSShutdown(ASocket: TIdStackSocketHandle; AHow: Integer): Integer;
begin
  result := Shutdown(ASocket, AHow);
end;

procedure TIdStackWindows.WSGetPeerName(ASocket: TIdStackSocketHandle;
  var VFamily: Integer; var VIP: string; var VPort: Integer);
var
  i: Integer;
  LAddr: TSockAddrIn;
begin
  i := SizeOf(LAddr);
  CheckForSocketError(GetPeerName(ASocket, @LAddr, i));
  VFamily := LAddr.sin_family;
  VIP := TInAddrToString(LAddr.sin_addr);
  VPort := Ntohs(LAddr.sin_port);
end;

procedure TIdStackWindows.WSGetSockName(ASocket: TIdStackSocketHandle;
  var VFamily: Integer; var VIP: string; var VPort: Integer);
var
  i: Integer;
  LAddr: TSockAddrIn;
begin
  i := SizeOf(LAddr);
  CheckForSocketError(GetSockName(ASocket, @LAddr, i));
  VFamily := LAddr.sin_family;
  VIP := TInAddrToString(LAddr.sin_addr);
  VPort := Ntohs(LAddr.sin_port);
end;

function TIdStackWindows.WSGetSockOpt(ASocket: TIdStackSocketHandle; Alevel, AOptname: Integer; AOptval: PChar; var AOptlen: Integer): Integer;
begin
  Result := GetSockOpt(ASocket, ALevel, AOptname, AOptval, AOptlen);
end;

{ TIdSocketListWindows }

procedure TIdSocketListWindows.Add(AHandle: TIdStackSocketHandle);
Begin
  if FFDSet.fd_count >= FD_SETSIZE then begin
    raise EIdStackSetSizeExceeded.Create(RSSetSizeExceeded);
  end;
  FFDSet.fd_array[FFDSet.fd_count] := AHandle;
  inc(FFDSet.fd_count);
End;//

function TIdSocketListWindows.Count: Integer;
Begin
  Result := FFDSet.fd_count;
End;

function TIdSocketListWindows.GetItem(AIndex: Integer): TIdStackSocketHandle;
Begin
  if (AIndex>=0) and (AIndex<FFDSet.fd_count) then begin
    Result := FFDSet.fd_array[AIndex];
  end else begin
    raise EIdStackSetSizeExceeded.Create(RSSetSizeExceeded);
  end;
End;//

procedure TIdSocketListWindows.Remove(AHandle: TIdStackSocketHandle);
var
  i: Integer;
Begin
  for i:=0 to FFDSet.fd_count-1 do begin
    if FFDSet.fd_array[i] = AHandle then begin
      dec(FFDSet.fd_count);
      FFDSet.fd_array[i] := FFDSet.fd_array[FFDSet.fd_count];
      FFDSet.fd_array[FFDSet.fd_count] := 0; //extra purity
      Break;
    end;//if found
  end;
End;//

function TIdStackWindows.WSTranslateSocketErrorMsg(const AErr: integer): string;
Begin
  case AErr of
    wsahost_not_found: Result := RSStackHOST_NOT_FOUND;
  else
    Result :=  inherited WSTranslateSocketErrorMsg(AErr);
    EXIT;
  end;
  Result := Format(RSStackError, [AErr, Result]);
End;//

initialization
  GSocketListClass := TIdSocketListWindows;
  // Check if we are running under windows NT
  if (SysUtils.Win32Platform = VER_PLATFORM_WIN32_NT) then begin
    GServeFileProc := ServeFile;
  end;
finalization
  if GStarted then begin
    WSACleanup;
  end;
end.
