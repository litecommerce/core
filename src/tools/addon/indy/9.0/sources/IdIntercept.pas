{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10209: IdIntercept.pas 
{
{   Rev 1.0    2002.11.12 10:42:16 PM  czhower
}
unit IdIntercept;
{
2002-03-01 - Andrew P.Rybin
  - Nested Intercept support (ex: ->logging->compression->encryption)
2002-04-09 - Chuck Smith
  - set ABuffer.Position := 0; in OnSend/OnReceive for Nested Stream send/receive
}
interface

uses
  Classes,
  IdBaseComponent,
  IdException;

type
  EIdInterceptCircularLink = class(EIdException);
  TIdConnectionIntercept = class;
  TIdInterceptNotifyEvent = procedure(ASender: TIdConnectionIntercept) of object;
  TIdInterceptStreamEvent = procedure(ASender: TIdConnectionIntercept; AStream: TStream) of object;

  TIdConnectionIntercept = class(TIdBaseComponent)
  protected
    FConnection: TComponent;
    FIntercept: TIdConnectionIntercept;
    FIsClient: Boolean;

    FOnConnect: TIdInterceptNotifyEvent;
    FOnDisconnect: TIdInterceptNotifyEvent;
    FOnReceive: TIdInterceptStreamEvent;
    FOnSend: TIdInterceptStreamEvent;
    //
    procedure NestedConnect(AConnection: TComponent); virtual;
    procedure NestedDisconnect; virtual;
    procedure NestedReceive(ABuffer: TStream); virtual;
    procedure NestedSend(ABuffer: TStream); virtual;
    procedure Notification(AComponent: TComponent; Operation: TOperation); override;
    procedure SetIntercept(AValue: TIdConnectionIntercept);
    //
    property  Intercept: TIdConnectionIntercept read FIntercept write SetIntercept;
  public
    procedure Connect(AConnection: TComponent); virtual;
    constructor Create(AOwner: TComponent); override;
    procedure Disconnect; virtual;
    procedure Receive(ABuffer: TStream); virtual;
    procedure Send(ABuffer: TStream); virtual;
    //
    property Connection: TComponent read FConnection;
    property IsClient: Boolean read FIsClient;
  published
    property OnConnect: TIdInterceptNotifyEvent read FOnConnect write FOnConnect;
    property OnDisconnect: TIdInterceptNotifyEvent read FOnDisconnect write FOnDisconnect;
    property OnReceive: TIdInterceptStreamEvent read FOnReceive write FOnReceive;
    property OnSend: TIdInterceptStreamEvent read FOnSend write FOnSend;
  end;

  TIdServerIntercept = class(TIdBaseComponent)
  public
    procedure Init; virtual; abstract;
    function Accept(AConnection: TComponent): TIdConnectionIntercept; virtual; abstract;
  end;

implementation
uses SysUtils, IdResourceStrings;

{ TIdIntercept }

procedure TIdConnectionIntercept.Disconnect;
begin
  NestedDisconnect;
  if Assigned(OnDisconnect) then begin
    OnDisconnect(Self);
  end;
  FConnection := NIL;
end;

procedure TIdConnectionIntercept.Connect(AConnection: TComponent);
begin
  FConnection := AConnection;
  if Assigned(OnConnect) then begin
    OnConnect(Self);
  end;
  NestedConnect(AConnection);
end;

constructor TIdConnectionIntercept.Create(AOwner: TComponent);
begin
  inherited;
  FIsClient := True;
end;

procedure TIdConnectionIntercept.Receive(ABuffer: TStream);
begin
  NestedReceive(ABuffer);
  if Assigned(OnReceive) then begin
    OnReceive(Self, ABuffer);
    ABuffer.Position := 0;
  end;
end;

procedure TIdConnectionIntercept.Send(ABuffer: TStream);
begin
  if Assigned(OnSend) then begin
    OnSend(Self, ABuffer);
    ABuffer.Position := 0;
  end;
  NestedSend(ABuffer);
end;

procedure TIdConnectionIntercept.SetIntercept(AValue: TIdConnectionIntercept);
var
  LIntercept: TIdConnectionIntercept;
Begin
  LIntercept := AValue;
  while Assigned(LIntercept) do begin
    if LIntercept = SELF then begin //recursion
      raise EIdInterceptCircularLink.Create(Format(RSInterceptCircularLink,[ClassName])); // TODO: Resource string and more english
    end;
    LIntercept := LIntercept.FIntercept;
  end;

  FIntercept := AValue;
  // add self to the Intercept's free notification list    {Do not Localize}
  if Assigned(FIntercept) then begin
    FIntercept.FreeNotification(Self);
  end;
End;

procedure TIdConnectionIntercept.Notification(AComponent: TComponent;
  Operation: TOperation);
Begin
  inherited Notification(AComponent, OPeration);

  if (Operation = opRemove) then begin
    if (AComponent = FIntercept) then begin
      FIntercept := NIL;
    end;
  end;
End;//

procedure TIdConnectionIntercept.NestedConnect(AConnection: TComponent);
begin
  if Assigned(FIntercept) then begin
    FIntercept.Connect(AConnection);
  end;
end;

procedure TIdConnectionIntercept.NestedDisconnect;
begin
  if Assigned(FIntercept) then begin
    FIntercept.Disconnect;
  end;
end;

procedure TIdConnectionIntercept.NestedReceive(ABuffer: TStream);
begin
  if Assigned(FIntercept) then begin
    FIntercept.Receive(ABuffer);
  end;
end;

procedure TIdConnectionIntercept.NestedSend(ABuffer: TStream);
begin
  if Assigned(FIntercept) then begin
    FIntercept.Send(ABuffer);
  end;
end;

end.
