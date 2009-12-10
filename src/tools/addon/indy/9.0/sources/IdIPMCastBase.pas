{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10219: IdIPMCastBase.pas 
{
{   Rev 1.1    4/20/03 1:45:18 PM  RLebeau
{ Updated IsValidMulticastGroup() to use new TIdStack::GetIPInfo() method.
}
{
{   Rev 1.0    2002.11.12 10:43:06 PM  czhower
}
unit IdIPMCastBase;

interface

uses
  Classes,
  IdComponent, IdException, IdGlobal, IdSocketHandle, IdStack {$IFDEF LINUX} ,Libc {$ENDIF};

const
  IPMCastLo = 224;
  IPMCastHi = 239;

type
  TMultiCast = record
    IMRMultiAddr : TIdInAddr;   // IP multicast address of group */
    IMRInterface : TIdInAddr;   // local IP address of interface */
  end;

  TIdIPMCastBase = class(TIdComponent)
  protected
    FDsgnActive: Boolean;
    FMulticastGroup: String;
    FPort: Integer;
    //
    procedure CloseBinding; virtual; abstract;
    function GetActive: Boolean; virtual;
    function GetBinding: TIdSocketHandle; virtual; abstract;
    procedure Loaded; override;
    procedure SetActive(const Value: Boolean); virtual;
    procedure SetMulticastGroup(const Value: string); virtual;
    procedure SetPort(const Value: integer); virtual;
    //
    property Active: Boolean read GetActive write SetActive Default False;
    property MulticastGroup: string read FMulticastGroup write SetMulticastGroup;
    property Port: Integer read FPort write SetPort;
  public
    constructor Create(AOwner: TComponent); override;
    function IsValidMulticastGroup(Value: string): Boolean;
  published
  end;

  EIdMCastException = Class(EIdException);
  EIdMCastNoBindings = class(EIdMCastException);
  EIdMCastNotValidAddress = class(EIdMCastException);

implementation

uses
  IdAssignedNumbers,
  IdResourceStrings, IdStackConsts,
  SysUtils;

{ TIdIPMCastBase }

constructor TIdIPMCastBase.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FMultiCastGroup := Id_IPMC_All_Systems;
end;

function TIdIPMCastBase.GetActive: Boolean;
begin
  Result := FDsgnActive;
end;

function TIdIPMCastBase.IsValidMulticastGroup(Value: string): Boolean;
var
  ip1: Byte;
begin
  Result := false;

  if not GStack.GetIPInfo(Value, @ip1) then
    Exit;

  if ((ip1 < IPMCastLo) or (ip1 > IPMCastHi)) then
    Exit;

  Result := true;
end;

procedure TIdIPMCastBase.Loaded;
var
  b: Boolean;
begin
  inherited Loaded;
  b := FDsgnActive;
  FDsgnActive := False;
  Active := b;
end;

procedure TIdIPMCastBase.SetActive(const Value: Boolean);
begin
  if Active <> Value then begin
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

procedure TIdIPMCastBase.SetMulticastGroup(const Value: string);
begin
  if (FMulticastGroup <> Value) then begin
    if IsValidMulticastGroup(Value) then
    begin
      Active := False;
      FMulticastGroup := Value;
    end
    else
    begin
      Raise EIdMCastNotValidAddress.Create(RSIPMCastInvalidMulticastAddress);
    end;
  end;
end;

procedure TIdIPMCastBase.SetPort(const Value: integer);
begin
  if FPort <> Value then begin
    Active := False;
    FPort := Value;
  end;
end;

end.
