{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10411: IdUDPServer.pas 
{
{   Rev 1.0    2002.11.12 10:59:10 PM  czhower
}
unit IdUDPServer;

interface

uses
  Classes,
  IdComponent, IdException, IdGlobal, IdSocketHandle, IdStackConsts, IdThread, IdUDPBase;

type
  TUDPReadEvent = procedure(Sender: TObject; AData: TStream; ABinding: TIdSocketHandle) of object;

  TIdUDPServer = class;

  TIdUDPListenerThread = class(TIdThread)
  protected
    IncomingData: TIdSocketHandle;
    FAcceptWait: integer;
    FBuffer: TMemoryStream;
    FBufferSize: integer;
  public
    FServer: TIdUDPServer;
    //
    constructor Create(const ABufferSize: integer; Owner: TIdUDPServer); reintroduce;
    destructor Destroy; override;
    procedure Run; override;
    procedure UDPRead;
    //
    property AcceptWait: integer read FAcceptWait write FAcceptWait;
  published
  end;

  TIdUDPServer = class(TIdUDPBase)
  protected
    FBindings: TIdSocketHandles;
    FCurrentBinding: TIdSocketHandle;
    FListenerThread: TIdUDPListenerThread;
    FOnUDPRead: TUDPReadEvent;
    FThreadedEvent: boolean;
    //
    function GetDefaultPort: integer;
    procedure SetBindings(const Value: TIdSocketHandles);
    procedure SetDefaultPort(const AValue: integer);
    procedure PacketReceived(AData: TStream; ABinding: TIdSocketHandle);
    procedure DoUDPRead(AData: TStream; ABinding: TIdSocketHandle); virtual;
    function GetBinding: TIdSocketHandle; override;
    procedure CloseBinding; override;
    procedure BroadcastEnabledChanged; override;
    function GetActive: Boolean; override;
  public
    constructor Create(axOwner: TComponent); override;
    destructor Destroy; override;
    //
  published
    property Bindings: TIdSocketHandles read FBindings write SetBindings;
    property DefaultPort: integer read GetDefaultPort write SetDefaultPort;
    property OnUDPRead: TUDPReadEvent read FOnUDPRead write FOnUDPRead;
    property ThreadedEvent: boolean read FThreadedEvent write FThreadedEvent default False;
  end;
  EIdUDPServerException = class(EIdUDPException);

implementation

uses
  IdResourceStrings, IdStack,
  SysUtils;

{ TIdUDPServer }

procedure TIdUDPServer.BroadcastEnabledChanged;
var
  i: integer;
begin
  if Assigned(FCurrentBinding) then begin
    for i := 0 to Bindings.Count - 1 do begin
      SetBroadcastFlag(BroadcastEnabled, Bindings[i]);
    end;
  end;
end;

procedure TIdUDPServer.CloseBinding;
var
  i: integer;
begin
  if Assigned(FCurrentBinding) then begin
    // Necessary here - cancels the recvfrom in the listener thread
    FListenerThread.Stop;
    for i := 0 to Bindings.Count - 1 do begin
      Bindings[i].CloseSocket;
    end;
    FListenerThread.WaitFor;
    FreeAndNil(FListenerThread);
    FCurrentBinding := nil;
  end;
end;

constructor TIdUDPServer.Create(axOwner: TComponent);
begin
  inherited;
  FBindings := TIdSocketHandles.Create(Self);
end;

destructor TIdUDPServer.Destroy;
begin
  Active := False;
  FreeAndNil(FBindings);
  inherited;
end;

procedure TIdUDPServer.DoUDPRead(AData: TStream; ABinding: TIdSocketHandle);
begin
  if assigned(OnUDPRead) then begin
    OnUDPRead(Self, AData, ABinding);
  end;
end;

function TIdUDPServer.GetActive: Boolean;
begin
  // inherited GetActive keeps track of design-time Active property
  Result := inherited GetActive or
            (Assigned(FCurrentBinding) and FCurrentBinding.HandleAllocated);
end;

function TIdUDPServer.GetBinding: TIdSocketHandle;
var
  i: integer;
begin
  if not Assigned(FCurrentBinding) then
  begin
    if Bindings.Count < 1 then begin
      Bindings.Add;
    end;
    for i := 0 to Bindings.Count - 1 do begin
      Bindings[i].AllocateSocket(Id_SOCK_DGRAM);
      Bindings[i].Bind;
    end;
    FCurrentBinding := Bindings[0];
    FListenerThread := TIdUDPListenerThread.Create(BufferSize, Self);
    FListenerThread.Start;
    BroadcastEnabledChanged;
  end;
  Result := FCurrentBinding;
end;

function TIdUDPServer.GetDefaultPort: integer;
begin
  result := FBindings.DefaultPort;
end;

procedure TIdUDPServer.PacketReceived(AData: TStream;
  ABinding: TIdSocketHandle);
begin
  FCurrentBinding := ABinding;
  DoUDPRead(AData, ABinding);
end;

procedure TIdUDPServer.SetBindings(const Value: TIdSocketHandles);
begin
  FBindings.Assign(Value);
end;

procedure TIdUDPServer.SetDefaultPort(const AValue: integer);
begin
  FBindings.DefaultPort := AValue;
end;

{ TIdUDPListenerThread }

// TODO: get rid of buffersize arg... there's no reason why this thread can't simply check its owner's buffersize property    {Do not Localize}
constructor TIdUDPListenerThread.Create(const ABufferSize: integer; Owner: TIdUDPServer);
begin
  inherited Create(True);
  FAcceptWait := 1000;
  FBuffer := TMemoryStream.Create;
  FBufferSize := ABufferSize;
  FServer := Owner;
end;

destructor TIdUDPListenerThread.Destroy;
begin
  FBuffer.Free;
  inherited;
end;

procedure TIdUDPListenerThread.Run;
var
  PeerIP: string;
  i, PeerPort, ByteCount: Integer;
  FReadList: TList;
begin
  // fill list of socket handles
  FReadList := TList.Create;
  try
    FReadList.Capacity := FServer.Bindings.Count;
    for i := 0 to FServer.Bindings.Count - 1 do begin
      FReadList.Add(Pointer(FServer.Bindings[i].Handle));
    end;
    GStack.WSSelect(FReadList, nil, nil, AcceptWait);
    for i := 0 to FReadList.Count - 1 do try
      // Doublecheck to see if we've been stopped    {Do not Localize}
      // Depending on timing - may not reach here if it is in ancestor run when thread is stopped
      if not Stopped then begin
        IncomingData := FServer.Bindings.BindingByHandle(TIdStackSocketHandle(FReadList[i]));
        FBuffer.SetSize(FBufferSize);
        ByteCount := GStack.WSRecvFrom(IncomingData.Handle, FBuffer.Memory^, FBufferSize,
                                       0, PeerIP, PeerPort);
        GStack.CheckForSocketError(ByteCount);
        FBuffer.SetSize(ByteCount);
        //Some streams alter their position on SetSize
        FBuffer.Position := 0;
        IncomingData.SetPeer(PeerIP, PeerPort);
        if FServer.ThreadedEvent then begin
          UDPRead;
        end else begin
          Synchronize(UDPRead);
        end;
      end;
    except  // exceptions should be ignored so that other clients can be served in case of a DOS attack
    end;
  finally
    FReadList.Free;
  end;
end;

procedure TIdUDPListenerThread.UDPRead;
begin
  FServer.PacketReceived(FBuffer, IncomingData);
end;

end.
