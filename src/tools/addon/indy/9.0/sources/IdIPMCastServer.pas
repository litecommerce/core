{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10223: IdIPMCastServer.pas 
{
{   Rev 1.0    2002.11.12 10:43:20 PM  czhower
}
unit IdIPMCastServer;

{

  History:

  Date         By         Description
  ----------  ----------  --------------------------------------------------
  2001-10-16  DSiders     Modified TIdIPMCastServer.MulticastBuffer to
                          validate the AHost argument to the method instead
                          of the MulticastGroup property.

  ???   Dr. Harley J. Mackenzie    Initial revision.

}

interface

uses
  Classes,
  IdIPMCastBase, IdComponent, IdSocketHandle;

const
  DEF_IMP_LOOPBACK = True;
  DEF_IMP_TTL = 1;

type
  TIdIPMCastServer = class(TIdIPMCastBase)
  protected
    FBinding: TIdSocketHandle;
    FLoopback: Boolean;
    FTimeToLive: Byte;
    //
    procedure CloseBinding; override;
    function GetActive: Boolean; override;
    function GetBinding: TIdSocketHandle; override;
    procedure Loaded; override;
    procedure MulticastBuffer(AHost: string; const APort: Integer; var ABuffer; const AByteCount: integer);
    procedure SetLoopback(const AValue: Boolean); virtual;
    procedure SetTTL(const Value: Byte); virtual;
    procedure SetTTLOption(InBinding: TIdSocketHandle; const Value: Byte); virtual;
  public
    constructor Create(AOwner: TComponent); override;
    procedure Send(AData: string);
    procedure SendBuffer(var ABuffer; const AByteCount: integer);
    destructor Destroy; override;
    //
    property Binding: TIdSocketHandle read GetBinding;
  published
    property Active;
    property Loopback: Boolean read FLoopback write SetLoopback default DEF_IMP_LOOPBACK;
    property MulticastGroup;
    property Port;
    property TimeToLive: Byte read FTimeToLive write SetTTL default DEF_IMP_TTL;
  end;

implementation

{ TIdIPMCastServer }

uses
  IdResourceStrings, IdStack, IdStackConsts, IdGlobal, SysUtils;

constructor TIdIPMCastServer.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FLoopback := DEF_IMP_LOOPBACK;
  FTimeToLive := DEF_IMP_TTL;
end;

procedure TIdIPMCastServer.CloseBinding;
var
  Multicast: TMultiCast;
begin
  Multicast.IMRMultiAddr :=  GStack.StringToTInAddr(FMulticastGroup);
  Multicast.IMRInterface.S_addr :=  Id_INADDR_ANY;
  FBinding.SetSockOpt(Id_IPPROTO_IP, Id_IP_DROP_MEMBERSHIP, pchar(@Multicast), SizeOf(Multicast));
  FreeAndNil(FBinding);
end;

function TIdIPMCastServer.GetActive: Boolean;
begin
  Result := inherited GetActive or (Assigned(FBinding) and FBinding.HandleAllocated);
end;

function TIdIPMCastServer.GetBinding: TIdSocketHandle;
var
  Multicast  : TMultiCast;
begin
  if not Assigned(FBinding) then begin
    FBinding := TIdSocketHandle.Create(nil);
  end;
  if not FBinding.HandleAllocated then begin
    FBinding.AllocateSocket(Id_SOCK_DGRAM);
    FBinding.Bind;
    Multicast.IMRMultiAddr :=  GStack.StringToTInAddr(FMulticastGroup);
    Multicast.IMRInterface.S_addr :=  Id_INADDR_ANY;
    FBinding.SetSockOpt(Id_IPPROTO_IP, Id_IP_ADD_MEMBERSHIP, pchar(@Multicast), SizeOf(Multicast));
    SetTTLOption(FBinding, FTimeToLive);
    Loopback := True;
  end;
  Result := FBinding;
end;

procedure TIdIPMCastServer.Loaded;
var
  b: Boolean;
begin
  inherited Loaded;
  b := FDsgnActive;
  FDsgnActive := False;
  Active := b;
end;

procedure TIdIPMCastServer.MulticastBuffer(AHost: string; const APort: Integer; var ABuffer; const AByteCount: integer);
begin
  // DS - if not IsValidMulticastGroup(FMulticastGroup) then
  if not IsValidMulticastGroup(AHost) then
    raise EIdMCastNotValidAddress.Create(RSIPMCastInvalidMulticastAddress);
  Binding.SendTo(AHost, APort, ABuffer, AByteCount);
end;

procedure TIdIPMCastServer.Send(AData: string);
begin
  MulticastBuffer(FMulticastGroup, FPort, PChar(AData)^, Length(AData));
end;

procedure TIdIPMCastServer.SendBuffer(var ABuffer; const AByteCount: integer);
begin
  MulticastBuffer(FMulticastGroup, FPort, ABuffer, AByteCount);
end;

procedure TIdIPMCastServer.SetLoopback(const AValue: Boolean);
var
  LThisLoopback: Integer;
begin
  if FLoopback <> AValue then begin
    if FDsgnActive or (Assigned(Binding) and Binding.HandleAllocated) then begin
      if AValue then begin
        LThisLoopback := 1;
      end else begin
        LThisLoopback := 0;
      end;
      Binding.SetSockOpt(Id_IPPROTO_IP, Id_IP_MULTICAST_LOOP, PChar(@LThisLoopback)
       , SizeOf(LThisLoopback));
    end;
    FLoopback := AValue;
  end;
end;

procedure TIdIPMCastServer.SetTTL(const Value: Byte);
begin
  if (FTimeToLive <> Value) then begin
    SetTTLOption(FBinding, Value);
    FTimeToLive := Value;
  end;
end;

procedure TIdIPMCastServer.SetTTLOption(InBinding: TIdSocketHandle; const Value: Byte);
var
  ThisTTL: Integer;
begin
  if (FDsgnActive or (Assigned(InBinding) and InBinding.HandleAllocated)) then begin
    ThisTTL := Value;
    InBinding.SetSockOpt(Id_IPPROTO_IP, Id_IP_MULTICAST_TTL, pchar(@ThisTTL), SizeOf(ThisTTL));
  end;
end;

destructor TIdIPMCastServer.Destroy;
begin
	Active := False;
  inherited Destroy;
end;

end.

