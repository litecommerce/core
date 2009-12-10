{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10221: IdIPMCastClient.pas 
{
{   Rev 1.0    2002.11.12 10:43:14 PM  czhower
}
unit IdIPMCastClient;

interface

uses
  Classes,
  IdIPMCastBase, IdUDPBase, IdComponent, IdSocketHandle, IdThread, IdException;

const
  DEF_IMP_THREADEDEVENT = False;
type
	TIPMCastReadEvent = procedure(Sender: TObject; AData: TStream; ABinding: TIdSocketHandle) of object;

  TIdIPMCastClient = class;

  TIdIPMCastListenerThread = class(TIdThread)
  protected
    IncomingData: TIdSocketHandle;
    FAcceptWait: integer;
    FBuffer: TMemoryStream;
    FBufferSize: integer;
  public
    FServer: TIdIPMCastClient;
    //
    constructor Create(Owner: TIdIPMCastClient); reintroduce;
    destructor Destroy; override;
    procedure Run; override;
    procedure IPMCastRead;
    //
    property AcceptWait: integer read FAcceptWait write FAcceptWait;
  published
  end;

  TIdIPMCastClient = class(TIdIPMCastBase)
  protected
    FBindings: TIdSocketHandles;
    FBufferSize: Integer;
    FCurrentBinding: TIdSocketHandle;
    FListenerThread: TIdIPMCastListenerThread;
    FOnIPMCastRead: TIPMCastReadEvent;
    FThreadedEvent: boolean;
    //
    procedure CloseBinding; override;
    procedure DoIPMCastRead(AData: TStream; ABinding: TIdSocketHandle); virtual;
    function GetActive: Boolean; override;
    function GetBinding: TIdSocketHandle; override;
    function GetDefaultPort: integer;
    procedure PacketReceived(AData: TStream; ABinding: TIdSocketHandle);
    procedure SetBindings(const Value: TIdSocketHandles);
    procedure SetDefaultPort(const AValue: integer);
  public
  	constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    //
  published
    property Active;
    property Bindings: TIdSocketHandles read FBindings write SetBindings;
    property BufferSize: Integer read FBufferSize write FBufferSize default ID_UDP_BUFFERSIZE;
    property DefaultPort: integer read GetDefaultPort write SetDefaultPort;
    property MulticastGroup;
    property OnIPMCastRead: TIPMCastReadEvent read FOnIPMCastRead write FOnIPMCastRead;
    property ThreadedEvent: boolean read FThreadedEvent write FThreadedEvent default DEF_IMP_THREADEDEVENT;
  end;

implementation

uses
  IdResourceStrings, IdStack, IdStackConsts, IdGlobal, SysUtils;

{ TIdIPMCastClient }

constructor TIdIPMCastClient.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  BufferSize := ID_UDP_BUFFERSIZE;
  FThreadedEvent := DEF_IMP_THREADEDEVENT;
  FBindings := TIdSocketHandles.Create(Self);
end;

procedure TIdIPMCastClient.CloseBinding;
var
  i: integer;
  Multicast  : TMultiCast;
begin
  if Assigned(FCurrentBinding) then begin
    // Necessary here - cancels the recvfrom in the listener thread
    FListenerThread.Stop;
    for i := 0 to Bindings.Count - 1 do begin
      Multicast.IMRMultiAddr :=  GStack.StringToTInAddr(FMulticastGroup);
      Multicast.IMRInterface.S_addr :=  Id_INADDR_ANY;
      Bindings[i].SetSockOpt(Id_IPPROTO_IP, Id_IP_DROP_MEMBERSHIP, pchar(@Multicast), SizeOf(Multicast));
      Bindings[i].CloseSocket;
    end;
    FListenerThread.WaitFor;
    FreeAndNil(FListenerThread);
    FCurrentBinding := nil;
  end;
end;

procedure TIdIPMCastClient.DoIPMCastRead(AData: TStream; ABinding: TIdSocketHandle);
begin
  if Assigned(OnIPMCastRead) then begin
    OnIPMCastRead(Self, AData, ABinding);
  end;
end;

function TIdIPMCastClient.GetActive: Boolean;
begin
  // inherited GetActive keeps track of design-time Active property
  Result := inherited GetActive or
            (Assigned(FCurrentBinding) and FCurrentBinding.HandleAllocated);
end;

function TIdIPMCastClient.GetBinding: TIdSocketHandle;
var
  i: integer;
  Multicast  : TMultiCast;
begin
  if not Assigned(FCurrentBinding) then
  begin
    if Bindings.Count < 1 then begin
      if DefaultPort > 0 then begin
        Bindings.Add;
      end else begin
        raise EIdMCastNoBindings.Create(RSNoBindingsSpecified);
      end;
    end;
    for i := 0 to Bindings.Count - 1 do begin
      Bindings[i].AllocateSocket(Id_SOCK_DGRAM);
      Bindings[i].Bind;
      Multicast.IMRMultiAddr :=  GStack.StringToTInAddr(FMulticastGroup);
      Multicast.IMRInterface.S_addr :=  Id_INADDR_ANY;
      Bindings[i].SetSockOpt(Id_IPPROTO_IP, Id_IP_ADD_MEMBERSHIP, pchar(@Multicast), SizeOf(Multicast));
    end;
    FCurrentBinding := Bindings[0];
    FListenerThread := TIdIPMCastListenerThread.Create(Self);
    FListenerThread.Start;
  end;
  Result := FCurrentBinding;
end;

function TIdIPMCastClient.GetDefaultPort: integer;
begin
  result := FBindings.DefaultPort;
end;

procedure TIdIPMCastClient.PacketReceived(AData: TStream; ABinding: TIdSocketHandle);
begin
  FCurrentBinding := ABinding;
  DoIPMCastRead(AData, ABinding);
end;

procedure TIdIPMCastClient.SetBindings(const Value: TIdSocketHandles);
begin
  FBindings.Assign(Value);
end;

procedure TIdIPMCastClient.SetDefaultPort(const AValue: integer);
begin
  if (FBindings.DefaultPort <> AValue) then begin
    FBindings.DefaultPort := AValue;
    FPort := AValue;
  end;
end;

destructor TIdIPMCastClient.Destroy;
begin
  Active := False;
  FreeAndNil(FBindings);
  inherited Destroy;
end;

{ TIdIPMCastListenerThread }

constructor TIdIPMCastListenerThread.Create(Owner: TIdIPMCastClient);
begin
  inherited Create(True);
  FAcceptWait := 1000;
  FBuffer := TMemoryStream.Create;
  FBufferSize := Owner.BufferSize;
  FServer := Owner;
end;

destructor TIdIPMCastListenerThread.Destroy;
begin
  FreeAndNil(FBuffer);
  inherited Destroy;
end;

procedure TIdIPMCastListenerThread.Run;
var
  PeerIP: string;
  i: Integer;
  PeerPort: Integer;
  ByteCount: Integer;
  LReadList: TList;
begin
  // fill list of socket handles
  LReadList := TList.Create;
  try
    LReadList.Capacity := FServer.Bindings.Count;
    for i := 0 to FServer.Bindings.Count - 1 do begin
      LReadList.Add(Pointer(FServer.Bindings[i].Handle));
    end;
    GStack.WSSelect(LReadList, nil, nil, AcceptWait);
    for i := 0 to LReadList.Count - 1 do
      // Doublecheck to see if we've been stopped    {Do not Localize}
      // Depending on timing - may not reach here if it is in ancestor run when thread is stopped
      if not Stopped then
      begin
        IncomingData := FServer.Bindings.BindingByHandle(TIdStackSocketHandle(LReadList[i]));
        FBuffer.SetSize(FBufferSize);
        ByteCount := GStack.WSRecvFrom(IncomingData.Handle, FBuffer.Memory^, FBufferSize, 0
         , PeerIP, PeerPort);
        GStack.CheckForSocketError(ByteCount);
        if ByteCount = 0 then
        begin
          raise EIdUDPReceiveErrorZeroBytes.Create(RSUDPReceiveError0);
        end;
        FBuffer.SetSize(ByteCount);
        //Some streams alter their position on SetSize
        FBuffer.Position := 0;
        IncomingData.SetPeer(PeerIP, PeerPort);
        if FServer.ThreadedEvent then
        begin
          IPMCastRead;
        end
        else begin
          Synchronize(IPMCastRead);
        end;
      end;
  finally
    LReadList.Free;
  end;
end;

procedure TIdIPMCastListenerThread.IPMCastRead;
begin
  FServer.PacketReceived(FBuffer, IncomingData);
end;

end.
