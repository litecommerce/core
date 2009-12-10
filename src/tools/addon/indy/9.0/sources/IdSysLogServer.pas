{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10361: IdSysLogServer.pas 
{
{   Rev 1.0    2002.11.12 10:54:44 PM  czhower
}
////////////////////////////////////////////////////////////////////////////////
//  IdSyslogServer component
//  Server-side implementation of the RFC 3164 "The BSD syslog Protocol"
//  Original Author: Stephane Grobety (grobety@fulgan.com)
//  Copyright the Indy pit crew
//  Release history:
//  08/09/01: Dev started

unit IdSysLogServer;

interface

uses
  Classes,
  IdAssignedNumbers,
  IdBaseComponent,
  IdComponent,
  IdException,
  IdGlobal,
  IdSocketHandle,
  IdStackConsts,
  IdThread,
  IdUDPBase,
  IdUDPServer,
  IdSysLogMessage,
  IdSysLog;

type
  TOnSyslogEvent = procedure(Sender: TObject; ASysLogMessage: TIdSysLogMessage;
    ABinding: TIdSocketHandle) of object;

  TIdSyslogServer = class(TIdUDPServer)
  protected
    FOnSyslog: TOnSyslogEvent;
    //
    procedure DoSyslogEvent(AMsg: TIdSysLogMessage; ABinding: TIdSocketHandle); virtual;
    procedure DoUDPRead(AData: TStream; ABinding: TIdSocketHandle); override;
  public
    constructor Create(AOwner: TComponent); override;
  published
    property DefaultPort default IdPORT_syslog;
    property OnSyslog: TOnSyslogEvent read FOnSyslog write FOnSysLog;
  end;

implementation

uses
  SysUtils;

{ TIdSyslogServer }

procedure TIdSyslogServer.DoUDPRead(AData: TStream; ABinding: TIdSocketHandle);
var
  LMsg: TIdSysLogMessage;
begin
  inherited DoUDPRead(AData,ABinding);
  LMsg := TIdSysLogMessage.Create(Self);
  try
    LMsg.ReadFromStream(AData, (AData as TMemoryStream).Size, ABinding.PeerIP);
    DoSyslogEvent(LMsg, ABinding);
  finally
    FreeAndNil(LMsg)
  end;
end;

constructor TIdSyslogServer.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  DefaultPort := IdPORT_syslog;
end;

procedure TIdSyslogServer.DoSyslogEvent(AMsg: TIdSysLogMessage; ABinding: TIdSocketHandle);
begin
  if Assigned(FOnSyslog)  and assigned(AMsg)then begin
    FOnSyslog(Self, AMsg, ABinding);
  end;
end;

end.
