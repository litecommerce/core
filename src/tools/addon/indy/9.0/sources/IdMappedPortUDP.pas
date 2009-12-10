{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10249: IdMappedPortUDP.pas 
{
{   Rev 1.0    2002.11.12 10:45:18 PM  czhower
}
unit IdMappedPortUDP;

interface

{
  - Syncronized with Indy standards by Gregor Ibic
  - Original DNS mapped port by Gregor Ibic
}

uses
  Classes,
  IdUDPServer, IdSocketHandle, IdGlobal;

type
  TIdMappedPortUDP = class(TIdUDPServer)
  protected
    FMappedPort: Integer;
    FMappedHost: String;
    FOnRequest: TNotifyEvent;
    //
    procedure DoRequestNotify; virtual;
  public
    constructor Create(AOwner: TComponent); override;
    procedure DoUDPRead(AData: TStream; ABinding: TIdSocketHandle); override;
  published
    property MappedHost: string read FMappedHost write FMappedHost;
    property OnRequest: TNotifyEvent read fOnRequest write fOnRequest;
  end;

implementation

uses
  IdAssignedNumbers,
  IdUDPClient;

constructor TIdMappedPortUDP.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_DOMAIN;
end;

procedure TIdMappedPortUDP.DoRequestNotify;
begin
  if Assigned(OnRequest) then begin
    OnRequest(Self);
  end;
end;

procedure TIdMappedPortUDP.DoUDPRead(AData: TStream; ABinding: TIdSocketHandle);
var
  OutboundClient: TIdUDPClient;
  rcvData: String;
  Buffer : String;
begin
  inherited;
  DoRequestNotify;
  OutboundClient := TIdUDPClient.Create(nil); try
    OutboundClient.Host := FMappedHost;
    OutboundClient.Port := FMappedPort;
    SetLength(Buffer, AData.Size);
    AData.Read(Buffer, AData.Size);
    OutboundClient.Send(Buffer);
    rcvData := OutboundClient.ReceiveString;
    if rcvData <> '' then begin    {Do not Localize}
      Send (ABinding.PeerIP, ABinding.PeerPort, rcvData);
    end;
  finally OutboundClient.Destroy; end;
end;

end.
