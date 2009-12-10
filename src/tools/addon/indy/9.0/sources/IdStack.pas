{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10343: IdStack.pas 
{
{   Rev 1.2    2003.06.04 10:49:12 PM  czhower
{ Fixed bug which caused IsIP to fail on successive calls because of
{ unitialized values and therefore caused connect errors.
}
{
{   Rev 1.1    4/20/03 1:49:26 PM  RLebeau
{ Added new methods: GetIPInfo(), GetIPType(), GetIPClass(), IPIsType(),
{ IPIsClass(), IsDottedIP(), and IsNumericIP().
{ 
{ Added EIdInvalidIPAddress exception class.
}
{
{   Rev 1.0    2002.11.12 10:53:10 PM  czhower
}
unit IdStack;

interface

uses
  Classes,
  IdException,
  IdStackConsts, IdGlobal;

type
  TIdServeFile = function(ASocket: TIdStackSocketHandle; AFileName: string): cardinal;

  // Abstract IdStack class

  TIdSunB = packed record
    s_b1, s_b2, s_b3, s_b4: byte;
  end;

  TIdSunW = packed record
    s_w1, s_w2: word;
  end;

  PIdInAddr = ^TIdInAddr;
  TIdInAddr = record
    case integer of
      0: (S_un_b: TIdSunB);
      1: (S_un_w: TIdSunW);
      2: (S_addr: longword);
  end;

  TIdIPType = (Id_IPInvalid, Id_IPDotted, Id_IPNumeric);
  PIdIPType = ^TIdIPType;

  TIdIPClass = (Id_IPClassUnkn, Id_IPClassA, Id_IPClassB, Id_IPClassC, Id_IPClassD, Id_IPClassE);
  PIdIPClass = ^TIdIPClass;

  TIdSocketListClass = class of TIdSocketList;
  TIdSocketList = class
  protected
    function GetItem(AIndex: Integer): TIdStackSocketHandle; virtual; abstract;
  public
    procedure Add(AHandle: TIdStackSocketHandle); virtual; abstract;
    class function CreateSocketList: TIdSocketList;
    procedure Remove(AHandle: TIdStackSocketHandle); virtual; abstract;
    function  Count: Integer; virtual; abstract;
    property  Items[AIndex: Integer]: TIdStackSocketHandle read GetItem; default;
  End;//TIdSocketList

  TIdStack = class
  protected
    FLastError: Integer;
    FLocalAddress: string;
    FLocalAddresses: TStrings;
    //
    procedure PopulateLocalAddresses; virtual; abstract;
    function WSGetLocalAddress: string; virtual; abstract;
    function WSGetLocalAddresses: TStrings; virtual; abstract;
  public
    function CheckForSocketError(const AResult: integer = Id_SOCKET_ERROR): boolean; overload;
    function CheckForSocketError(const AResult: integer; const AIgnore: array of integer)
     : boolean; overload;
    constructor Create; reintroduce; virtual;
    destructor Destroy; override;
    class function CreateStack: TIdStack;
    function CreateSocketHandle(const ASocketType: Integer;
      const AProtocol: Integer = Id_IPPROTO_IP): TIdStackSocketHandle;
    function GetIPInfo(const AIP: string; VB1: PByte = nil; VB2: PByte = nil;
      VB3: PByte = nil; VB4: PByte = nil; VType: PIdIPType = nil; VClass: PIdIPClass = nil): Boolean;
    function GetIPType(const AIP: string): TIdIPType;
    function GetIPClass(const AIP: string): TIdIPClass;
    function IsIP(const AIP: string): boolean;
    function IPIsType(const AIP: string; const AType: TIdIPType): boolean; overload;
    function IPIsType(const AIP: string; const ATypes: array of TIdIPType): boolean; overload;
    function IPIsClass(const AIP: string; const AClass: TIdIPClass): boolean; overload;
    function IPIsClass(const AIP: string; const AClasses: array of TIdIPClass): boolean; overload;
    function IsDottedIP(const AIP: string): boolean;
    function IsNumericIP(const AIP: string): boolean;
    procedure RaiseSocketError(const AErr: integer);
    function ResolveHost(const AHost: string): string;
    // Resolves host passed in sHost. sHost may be an IP or a HostName.
    // sIP returns string version of the IP
    function WSAccept(ASocket: TIdStackSocketHandle; var VIP: string; var VPort: Integer)
     : TIdStackSocketHandle; virtual; abstract;
    function WSBind(ASocket: TIdStackSocketHandle; const AFamily: Integer;
     const AIP: string; const APort: Integer): Integer; virtual; abstract;
    function WSCloseSocket(ASocket: TIdStackSocketHandle): Integer; virtual; abstract;
    function WSConnect(const ASocket: TIdStackSocketHandle; const AFamily: Integer;
     const AIP: string; const APort: Integer): Integer; virtual; abstract;
    function WSGetHostByName(const AHostName: string): string; virtual; abstract;
    function WSGetHostName: string; virtual; abstract;
    function WSGetHostByAddr(const AAddress: string): string; virtual; abstract;
    function WSGetServByName(const AServiceName: string): Integer; virtual; abstract;
    function WSGetServByPort(const APortNumber: Integer): TStrings; virtual; abstract;
    function WSHToNs(AHostShort: Word): Word; virtual; abstract;
    function WSListen(ASocket: TIdStackSocketHandle; ABackLog: Integer): Integer; virtual; abstract;
    function WSNToHs(ANetShort: Word): Word; virtual; abstract;
    function WSHToNL(AHostLong: LongWord): LongWord; virtual; abstract;
    function WSNToHL(ANetLong: LongWord): LongWord; virtual; abstract;
    function WSRecv(ASocket: TIdStackSocketHandle; var ABuffer; const ABufferLength, AFlags: Integer)
     : Integer; virtual; abstract;
    function WSRecvFrom(const ASocket: TIdStackSocketHandle; var ABuffer;
     const ALength, AFlags: Integer; var VIP: string; var VPort: Integer): Integer; virtual;
     abstract;
    function WSSelect(ARead, AWrite, AErrors: TList; ATimeout: Integer): Integer; virtual; abstract;
    function WSSend(ASocket: TIdStackSocketHandle; var ABuffer;
     const ABufferLength, AFlags: Integer): Integer; virtual; abstract;
    function WSSendTo(ASocket: TIdStackSocketHandle; var ABuffer;
     const ABufferLength, AFlags: Integer; const AIP: string; const APort: integer): Integer;
      virtual; abstract;
    function WSSetSockOpt(ASocket: TIdStackSocketHandle; ALevel, AOptName: Integer; AOptVal: PChar;
     AOptLen: Integer): Integer; virtual; abstract;
    function WSSocket(AFamily, AStruct, AProtocol: Integer): TIdStackSocketHandle; virtual; abstract;
    function WSShutdown(ASocket: TIdStackSocketHandle; AHow: Integer): Integer; virtual; abstract;
    function WSTranslateSocketErrorMsg(const AErr: integer): string; virtual;
    function WSGetLastError: Integer; virtual; abstract;
    procedure WSGetPeerName(ASocket: TIdStackSocketHandle; var AFamily: Integer;
     var AIP: string; var APort: Integer); virtual; abstract;
    procedure WSGetSockName(ASocket: TIdStackSocketHandle; var AFamily: Integer;
     var AIP: string; var APort: Integer); virtual; abstract;
    function WSGetSockOpt(ASocket: TIdStackSocketHandle; Alevel, AOptname: Integer; AOptval: PChar; var AOptlen: Integer) : Integer; virtual; abstract;
    function StringToTInAddr(AIP: string): TIdInAddr;
    function TInAddrToString(var AInAddr): string; virtual; abstract;
    procedure TranslateStringToTInAddr(AIP: string; var AInAddr); virtual; abstract;
    //
    property LastError: Integer read FLastError;
    property LocalAddress: string read WSGetLocalAddress;
    property LocalAddresses: TStrings read WSGetLocalAddresses;
  end;

  TIdStackClass = class of TIdStack;
  EIdStackError = class (EIdException);
  EIdStackInitializationFailed = class (EIdStackError);
  EIdStackSetSizeExceeded = class (EIdStackError);
  EIdInvalidIPAddress = class (EIdStackError);

var
  GStack: TIdStack = nil;
  GStackClass: TIdStackClass = nil;
  GServeFileProc: TIdServeFile = nil;
  GSocketListClass: TIdSocketListClass;

implementation

uses
  IdResourceStrings,
  SysUtils;

{ TIdStack }

function TIdStack.CheckForSocketError(const AResult: integer): boolean;
begin
  Result := CheckForSocketError(AResult, []);
end;

function TIdStack.CheckForSocketError(const AResult: integer;
  const AIgnore: array of integer): boolean;
var
  i: integer;
begin
  Result := false;
  if AResult = Id_SOCKET_ERROR then begin
    FLastError := WSGetLastError;
    for i := Low(AIgnore) to High(AIgnore) do begin
      if LastError = AIgnore[i] then begin
        Result := True;
        exit;
      end;
    end;
    RaiseSocketError(LastError);
  end;
end;

function TIdStack.CreateSocketHandle(const ASocketType: Integer;
  const AProtocol: Integer = Id_IPPROTO_IP): TIdStackSocketHandle;
begin
  result := WSSocket(Id_PF_INET, ASocketType, AProtocol);
  if result = Id_INVALID_SOCKET then begin
    raise EIdInvalidSocket.Create(RSCannotAllocateSocket);
  end;
end;

procedure TIdStack.RaiseSocketError(const AErr: integer);
begin
  (*
    RRRRR    EEEEEE   AAAA   DDDDD         MM     MM  EEEEEE    !!  !!  !!
    RR  RR   EE      AA  AA  DD  DD        MMMM MMMM  EE        !!  !!  !!
    RRRRR    EEEE    AAAAAA  DD   DD       MM MMM MM  EEEE      !!  !!  !!
    RR  RR   EE      AA  AA  DD  DD        MM     MM  EE
    RR   RR  EEEEEE  AA  AA  DDDDD         MM     MM  EEEEEE    ..  ..  ..

    Please read the note in the next comment.

  *)
  raise EIdSocketError.CreateError(AErr, WSTranslateSocketErrorMsg(AErr));
  (*
    It is normal to receive a 10038 exception (10038, NOT others!) here when
    *shutting down* (NOT at other times!) servers (NOT clients!).

    If you receive a 10038 exception here please see the FAQ at:
    http://www.nevrona.com/Indy/FAQ.html

    If you get a 10038 exception here, and HAVE NOT read the FAQ and ask about this in the public
    forums
    you will be publicly flogged, tarred and feathered and your name added to every chain
    letter in existence today.

    If you insist upon requesting help via our email boxes on the 10038 error that is already
    answered in the FAQ and you are simply too slothful to search for your answer and ask your
    question in the public forums you may be publicly flogged, tarred and feathered and your name
    may be added to every chain letter / EMail in existence today."

    Otherwise, if you DID read the FAQ and have further questions, please feel free to ask using
    one of the methods (Carefullly note that these methods do not list email) listed on the Tech
    Support link at http://www.nevrona.com/Indy/

    RRRRR    EEEEEE   AAAA   DDDDD         MM     MM  EEEEEE    !!  !!  !!
    RR  RR   EE      AA  AA  DD  DD        MMMM MMMM  EE        !!  !!  !!
    RRRRR    EEEE    AAAAAA  DD   DD       MM MMM MM  EEEE      !!  !!  !!
    RR  RR   EE      AA  AA  DD  DD        MM     MM  EE
    RR   RR  EEEEEE  AA  AA  DDDDD         MM     MM  EEEEEE    ..  ..  ..
  *)
end;

constructor TIdStack.Create;
begin
  // Here so descendants can override and call inherited for future exp since TObject's Create    {Do not Localize}
  // is not virtual
end;

class function TIdStack.CreateStack: TIdStack;
begin
  Result := GStackClass.Create;
end;

function TIdStack.ResolveHost(const AHost: string): string;
begin
  // Sometimes 95 forgets who localhost is
  if AnsiSameText(AHost, 'LOCALHOST') then begin    {Do not Localize}
    result := '127.0.0.1';    {Do not Localize}
  end else if IsIP(AHost) then begin
    result := AHost;
  end else begin
    result := WSGetHostByName(AHost);
  end;
end;

function TIdStack.WSTranslateSocketErrorMsg(const AErr: integer): string;
begin
  Result := '';    {Do not Localize}
  case AErr of
    Id_WSAEINTR: Result           := RSStackEINTR;
    Id_WSAEBADF: Result           := RSStackEBADF;
    Id_WSAEACCES: Result          := RSStackEACCES;
    Id_WSAEFAULT: Result          := RSStackEFAULT;
    Id_WSAEINVAL: Result          := RSStackEINVAL;
    Id_WSAEMFILE: Result          := RSStackEMFILE;

    Id_WSAEWOULDBLOCK: Result     := RSStackEWOULDBLOCK;
    Id_WSAEINPROGRESS: Result     := RSStackEINPROGRESS;
    Id_WSAEALREADY: Result        := RSStackEALREADY;
    Id_WSAENOTSOCK: Result        := RSStackENOTSOCK;
    Id_WSAEDESTADDRREQ: Result    := RSStackEDESTADDRREQ;
    Id_WSAEMSGSIZE: Result        := RSStackEMSGSIZE;
    Id_WSAEPROTOTYPE: Result      := RSStackEPROTOTYPE;
    Id_WSAENOPROTOOPT: Result     := RSStackENOPROTOOPT;
    Id_WSAEPROTONOSUPPORT: Result := RSStackEPROTONOSUPPORT;
    Id_WSAESOCKTNOSUPPORT: Result := RSStackESOCKTNOSUPPORT;
    Id_WSAEOPNOTSUPP: Result      := RSStackEOPNOTSUPP;
    Id_WSAEPFNOSUPPORT: Result    := RSStackEPFNOSUPPORT;
    Id_WSAEAFNOSUPPORT: Result    := RSStackEAFNOSUPPORT;
    Id_WSAEADDRINUSE: Result      := RSStackEADDRINUSE;
    Id_WSAEADDRNOTAVAIL: Result   := RSStackEADDRNOTAVAIL;
    Id_WSAENETDOWN: Result        := RSStackENETDOWN;
    Id_WSAENETUNREACH: Result     := RSStackENETUNREACH;
    Id_WSAENETRESET: Result       := RSStackENETRESET;
    Id_WSAECONNABORTED: Result    := RSStackECONNABORTED;
    Id_WSAECONNRESET: Result      := RSStackECONNRESET;
    Id_WSAENOBUFS: Result         := RSStackENOBUFS;
    Id_WSAEISCONN: Result         := RSStackEISCONN;
    Id_WSAENOTCONN: Result        := RSStackENOTCONN;
    Id_WSAESHUTDOWN: Result       := RSStackESHUTDOWN;
    Id_WSAETOOMANYREFS: Result    := RSStackETOOMANYREFS;
    Id_WSAETIMEDOUT: Result       := RSStackETIMEDOUT;
    Id_WSAECONNREFUSED: Result    := RSStackECONNREFUSED;
    Id_WSAELOOP: Result           := RSStackELOOP;
    Id_WSAENAMETOOLONG: Result    := RSStackENAMETOOLONG;
    Id_WSAEHOSTDOWN: Result       := RSStackEHOSTDOWN;
    Id_WSAEHOSTUNREACH: Result    := RSStackEHOSTUNREACH;
    Id_WSAENOTEMPTY: Result       := RSStackENOTEMPTY;
  end;
  Result := Format(RSStackError, [AErr, Result]);
end;

function TIdStack.GetIPInfo(const AIP: string; VB1: PByte = nil;
  VB2: PByte = nil; VB3: PByte = nil; VB4: PByte = nil; VType: PIdIPType = nil;
  VClass: PIdIPClass = nil): Boolean;
var
  sTemp, s1, s2, s3, s4: string;
  b1, b2, b3, b4: Byte;
  LType: TIdIPType;
  LClass: TIdIPClass;
  i: Integer;
  w: Word;
  c: Cardinal;

  function ByteIsOk(const AByte: string; var VB: Byte): boolean;
  var
    i: Integer;
  begin
    i := StrToIntDef(AByte, -1);
    Result := (i > -1) and (i < 256);
    if Result then VB := Byte(i);
  end;

  function WordIsOk(const AWord: string; var VW: Word): boolean;
  var
    i: Integer;
  begin
    i := StrToIntDef(AWord, -1);
    Result := (i > -1) and (i < 65536);
    if Result then VW := Word(i);
  end;

  function TwentyFourBitValueIsOk(const AValue: string; var VI: Integer): boolean;
  var
    i: Integer;
  begin
    i := StrToIntDef(AValue, -1);
    Result := (i > -1) and (i < 16777216);
    if Result then VI := i;
  end;

  function LongIsOk(const ALong: string; var VC: Cardinal): boolean;
  var
    i: Int64;
  begin
    i := StrToInt64Def(ALong, -1);
    Result := (i > -1) and (i < 4294967296);
    if Result then VC := Cardinal(i);
  end;

begin
  Result := False;
  LType := Id_IPInvalid;
  LClass := Id_IPClassUnkn;

  sTemp := AIP;
  s1 := Fetch(sTemp, '.');    {Do not Localize}
  s2 := Fetch(sTemp, '.');    {Do not Localize}
  s3 := Fetch(sTemp, '.');    {Do not Localize}
  s4 := sTemp;

  if s2 = '' then
  begin
    // RL: 4/13/2003: this probably needs to be tweaked better
    if LongIsOk(s1, c) then
    begin
      b1 := (c and $FF000000) shr 24;
      b2 := (c and $00FF0000) shr 16;
      b3 := (c and $0000FF00) shr 8;
      b4 := (c and $000000FF);
      LType := Id_IPNumeric;
    end;
  end
  else if s3 = '' then
  begin
    // class A address
    if ByteIsOk(s1, b1) and TwentyFourBitValueIsOk(s2, i) then
    begin
      b2 := (i and $00FF0000) shr 16;
      b3 := (i and $0000FF00) shr 8;
      b4 := (i and $000000FF);
      LType := Id_IPDotted;
      LClass := Id_IPClassA;
    end
  end
  else if s4 = '' then
  begin
    // class B address
    if ByteIsOk(s1, b1) and ByteIsOk(s2, b2) and WordIsOk(s3, w) then
    begin
      b3 := (w and $FF00) shr 8;
      b4 := (w and $00FF);
      LType := Id_IPDotted;
      LClass := Id_IPClassB;
    end
  end
  else
  begin
    // class C-E address
    if ByteIsOk(s1, b1) and ByteIsOk(s2, b2) and
      ByteIsOk(s3, b3) and ByteIsOk(s4, b4) then
    begin
      LType := Id_IPDotted;
      Case b1 of
        0..127:   LClass := Id_IPClassA;
        128..191: LClass := Id_IPClassB;
        192..223: LClass := Id_IPClassC;
        224..239: LClass := Id_IPClassD;
      else
        LClass := Id_IPClassE;
      end
    end
  end;

  if LType <> Id_IPInvalid then
  begin
    if (VB1 <> nil) then begin
      VB1^ := b1;
    end;
    if (VB2 <> nil) then begin
      VB2^ := b2;
    end;
    if (VB3 <> nil) then begin
      VB3^ := b3;
    end;
    if (VB4 <> nil) then begin
      VB4^ := b4;
    end;
    Result := True;
  end;
  if (VType <> nil) then begin
    VType^ := LType;
  end;
  if (VClass <> nil) then begin
    VClass^ := LClass;
  end;
end;

function TIdStack.GetIPType(const AIP: string): TIdIPType;
begin
  GetIPInfo(AIP, nil, nil, nil, nil, @Result);
end;

function TIdStack.GetIPClass(const AIP: string): TIdIPClass;
begin
  GetIPInfo(AIP, nil, nil, nil, nil, nil, @Result);
end;

function TIdStack.IsIP(const AIP: string): boolean;
begin
  Result := not IPIsType(AIP, Id_IPInvalid);
end;

function TIdStack.IPIsType(const AIP: string; const AType: TIdIPType): boolean;
begin
  Result := GetIPType(AIP) = AType;
end;

function TIdStack.IPIsType(const AIP: string; const ATypes: array of TIdIPType): boolean;
var
  i: Integer;
  LType: TIdIPType;
begin
  Result := False;
  LType := GetIPType(AIP);
  for i := Low(ATypes) to High(ATypes) do begin
    if LType = ATypes[i] then begin
        Result := True;
        Break;
    end;
  end;
end;

function TIdStack.IPIsClass(const AIP: string; const AClass: TIdIPClass): boolean;
begin
  Result := GetIPClass(AIP) = AClass;
end;

function TIdStack.IPIsClass(const AIP: string; const AClasses: array of TIdIPClass): boolean;
var
  i: Integer;
  LClass: TIdIPClass;
begin
  Result := False;
  LClass := GetIPClass(AIP);
  for i := Low(AClasses) to High(AClasses) do begin
    if LClass = AClasses[i] then begin
        Result := True;
        Break;
    end;
  end;
end;

function TIdStack.IsDottedIP(const AIP: string): boolean;
begin
  Result := IPIsType(AIP, Id_IPDotted);
end;

function TIdStack.IsNumericIP(const AIP: string): boolean;
begin
  Result := IPIsType(AIP, Id_IPNumeric);
end;

destructor TIdStack.Destroy;
begin
  FLocalAddresses.Free;
  inherited;
end;

function TIdStack.StringToTInAddr(AIP: string): TIdInAddr;
begin
  TranslateStringToTInAddr(AIP, result);
end;


{ TIdSocketList }

class function TIdSocketList.CreateSocketList: TIdSocketList;
Begin
  Result := GSocketListClass.Create;
End;//

end.
