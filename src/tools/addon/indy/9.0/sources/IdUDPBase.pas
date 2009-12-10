{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10407: IdUDPBase.pas 
{
{   Rev 1.0    2002.11.12 10:58:48 PM  czhower
}
unit IdUDPBase;

interface

uses
  Classes,
  IdComponent, IdException, IdGlobal, IdSocketHandle;

const
  ID_UDP_BUFFERSIZE = 8192;

type
  TIdUDPBase = class(TIdComponent)
  protected
    FBinding: TIdSocketHandle;
    FBufferSize: Integer;
    FDsgnActive: Boolean;
    FHost: String;
    FPort: Integer;
    FReceiveTimeout: Integer;
    //
    FBroadcastEnabled: Boolean;
    procedure BroadcastEnabledChanged; dynamic;
    procedure CloseBinding; virtual;
    function GetActive: Boolean; virtual;
    procedure SetActive(const Value: Boolean);
    procedure SetBroadcastFlag(const Enabled: Boolean; ABinding: TIdSocketHandle = nil);
    procedure SetBroadcastEnabled(const Value: Boolean);
    function GetBinding: TIdSocketHandle; virtual;
    procedure Loaded; override;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    //
    property Binding: TIdSocketHandle read GetBinding;
    procedure Broadcast(const AData: string; const APort: integer);
    function ReceiveBuffer(var ABuffer; const ABufferSize: Integer;
      const AMSec: Integer = IdTimeoutDefault): integer; overload;
    function ReceiveBuffer(var ABuffer; const ABufferSize: Integer; var VPeerIP: string;
     var VPeerPort: integer; AMSec: Integer = IdTimeoutDefault): integer; overload;
    function ReceiveString(const AMSec: Integer = IdTimeoutDefault): string; overload;
    function ReceiveString(var VPeerIP: string; var VPeerPort: integer;
     const AMSec: Integer = IdTimeoutDefault): string; overload;
    procedure Send(AHost: string; const APort: Integer; const AData: string);
    procedure SendBuffer(AHost: string; const APort: Integer; var ABuffer;
     const AByteCount: integer);
    //
    property ReceiveTimeout: Integer read FReceiveTimeout write FReceiveTimeout default IdTimeoutInfinite;
  published
    property Active: Boolean read GetActive write SetActive Default False;
    property BufferSize: Integer read FBufferSize write FBufferSize default ID_UDP_BUFFERSIZE;
    property BroadcastEnabled: Boolean read FBroadcastEnabled write SetBroadcastEnabled Default False;
  end;
  EIdUDPException = Class(EIdException);
  EIdUDPReceiveErrorZeroBytes = class(EIdUDPException);

implementation

uses
  IdResourceStrings, IdStack, IdStackConsts, SysUtils;

{ TIdUDPBase }

procedure TIdUDPBase.Broadcast(const AData: string; const APort: integer);
begin
  SetBroadcastFlag(True);
  Send('255.255.255.255', APort, AData);    {Do not Localize}
  BroadcastEnabledChanged;
end;

procedure TIdUDPBase.BroadcastEnabledChanged;
begin
  SetBroadcastFlag(BroadcastEnabled);
end;

procedure TIdUDPBase.CloseBinding;
begin
  FreeAndNil(FBinding);
end;

constructor TIdUDPBase.Create(AOwner: TComponent);
begin
  inherited;
  BufferSize := ID_UDP_BUFFERSIZE;
  FReceiveTimeout := IdTimeoutInfinite;
end;

destructor TIdUDPBase.Destroy;
begin
	Active := False;
  inherited;
end;

function TIdUDPBase.GetActive: Boolean;
begin
  Result := FDsgnActive or (Assigned(FBinding) and FBinding.HandleAllocated);
end;

function TIdUDPBase.GetBinding: TIdSocketHandle;
begin
  if not Assigned(FBinding)then begin
    FBinding := TIdSocketHandle.Create(nil);
  end;
  if not FBinding.HandleAllocated then begin
    FBinding.AllocateSocket(Id_SOCK_DGRAM);
    BroadcastEnabledChanged;
  end;
  result := FBinding;
end;

procedure TIdUDPBase.Loaded;
var
  b: Boolean;
begin
  inherited;
  b := FDsgnActive;
  FDsgnActive := False;
  Active := b;
end;

function TIdUDPBase.ReceiveBuffer(var ABuffer; const ABufferSize: Integer;
  const AMSec: Integer): Integer;
var
  VoidIP: string;
  VoidPort: Integer;
begin
  Result := ReceiveBuffer(ABuffer, ABufferSize, VoidIP, VoidPort, AMSec);
end;

function TIdUDPBase.ReceiveBuffer(var ABuffer; const ABufferSize: Integer;
  var VPeerIP: string; var VPeerPort: integer;
  AMSec: Integer = IdTimeoutDefault): integer;
begin
  if AMSec = IdTimeoutDefault then begin
    AMSec := ReceiveTimeOut;
  end;
  if not Binding.Readable(AMSec) then begin
    Result := 0;
    VPeerIP := '';    {Do not Localize}
    VPeerPort := 0;
    Exit;
  end;
  Result := Binding.RecvFrom(ABuffer, ABufferSize, 0, VPeerIP, VPeerPort);
  GStack.CheckForSocketError(Result);
end;

function TIdUDPBase.ReceiveString(var VPeerIP: string; var VPeerPort: integer;
 const AMSec: Integer = IdTimeoutDefault): string;
var
  i: integer;
begin
  SetLength(Result, BufferSize);
  i := ReceiveBuffer(Result[1], Length(Result), VPeerIP, VPeerPort, AMSec);
  SetLength(Result, i);
end;

function TIdUDPBase.ReceiveString(const AMSec: Integer): string;
var
  VoidIP: string;
  VoidPort: Integer;
begin
  result := ReceiveString(VoidIP, VoidPort, AMSec);
end;

procedure TIdUDPBase.Send(AHost: string; const APort: Integer; const AData: string);
begin
  SendBuffer(AHost, APort, PChar(AData)^, Length(AData));
end;

procedure TIdUDPBase.SendBuffer(AHost: string; const APort: Integer; var ABuffer;
 const AByteCount: integer);
begin
  AHost := GStack.ResolveHost(AHost);
  Binding.SendTo(AHost, APort, ABuffer, AByteCount);
end;

procedure TIdUDPBase.SetActive(const Value: Boolean);
begin
  if (Active <> Value) then begin
    if not ((csDesigning in ComponentState) or (csLoading in ComponentState)) then begin
      if Value then begin
        GetBinding;
      end
      else begin
        CloseBinding;
      end;
    end
    else begin  // don't activate at designtime (or during loading of properties)    {Do not Localize}
      FDsgnActive := Value;
    end;
  end;
end;

procedure TIdUDPBase.SetBroadcastEnabled(const Value: Boolean);
begin
  if FBroadCastEnabled <> Value then begin
    FBroadcastEnabled := Value;
    if Active then begin
      BroadcastEnabledChanged;
    end;
  end;
end;

procedure TIdUDPBase.SetBroadcastFlag(const Enabled: Boolean; ABinding: TIdSocketHandle = nil);
var
  BroadCastData: LongBool;
begin
  BroadCastData := Enabled;
  if ABinding = nil then begin
    ABinding := Binding;
  end;
  ABinding.SetSockOpt(Id_SOL_SOCKET, Id_SO_BROADCAST, PChar(@BroadCastData), SizeOf(BroadCastData));
end;



end.
