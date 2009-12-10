{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10357: IdSysLog.pas 
{
{   Rev 1.0    2002.11.12 10:54:32 PM  czhower
}
unit IdSysLog;
//  Copyright the Indy pit crew
//  Original Author: Stephane Grobety (grobety@fulgan.com)
//  Release history:
//
//  09/19/01;  J. Peter Mugaas
//    devided SysLogMessage into this unit
//  08/09/01: Dev started

{ToDo:  Somehow figure out how to make a bound port and bound IP property
in UDP Client.  This will probably require some changes to the Indy core units
though.
}
interface

uses Classes, IdAssignedNumbers, IdSocketHandle, IdSysLogMessage, IdUDPBase, IdUDPClient;

type
  TIdSysLog = class(TIdUDPClient)
  protected
    function GetBinding: TIdSocketHandle; override;
  public
    constructor Create(AOwner: TComponent); override;
    procedure SendMessage(const AMsg: TIdSysLogMessage;
      const AAutoTimeStamp: Boolean = true); overload;
    procedure SendMessage(const AMsg: String;
      const AFacility : TidSyslogFacility;
      const ASeverity: TIdSyslogSeverity); overload;
    procedure SendMessage(const AProcess: String; const AText : String;
      const AFacility : TidSyslogFacility;
      const ASeverity: TIdSyslogSeverity;
      const AUsePID : Boolean = False;
      const APID : Integer = -1); overload;
  published
    property Port default IdPORT_syslog;
  end;
  
implementation
uses IdGlobal, SysUtils, IdStackConsts;

{ TIdSysLog }

constructor TIdSysLog.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Port := IdPORT_syslog;
end;


procedure TIdSysLog.SendMessage(const AMsg: TIdSyslogMessage; const AAutoTimeStamp: Boolean = true);
begin
  if AAutoTimeStamp then
  begin
    AMsg.TimeStamp := now;
  end;
  Send( AMsg.EncodeMessage );
end;


function TIdSysLog.GetBinding: TIdSocketHandle;
const FromPort = 514;
begin
  Result := inherited GetBinding;
//  if Result.Port <> FromPort then
//  begin
//    {Recommened by RFC 3164 - Use 514 as to connect to the SysLog server}
//    Result.Port := FromPort;
//    Result.SetSockOpt(Id_SOL_SOCKET, Id_SO_REUSEADDR, PChar(@Id_SO_True), SizeOf(Id_SO_True));
//    Result.Bind;
//  end;
end;

procedure TIdSysLog.SendMessage(const AMsg: String;
  const AFacility: TidSyslogFacility;
  const ASeverity: TIdSyslogSeverity);
var LMsg : TIdSyslogMessage;
begin
  LMsg := TIdSyslogMessage.Create(nil);
  try
    LMsg.Msg.Text := AMsg;
    LMsg.Facility := AFacility;
    LMsg.Severity := ASeverity;
    SendMessage(LMsg);
  finally
    FreeAndNil(LMsg);
  end;
end;

procedure TIdSysLog.SendMessage(const AProcess, AText: String;
  const AFacility: TidSyslogFacility;
  const ASeverity: TIdSyslogSeverity;
  const AUsePID: Boolean;
  const APID: Integer);
var LMsg : TIdSyslogMessage;
begin
  LMsg := TIdSyslogMessage.Create(nil);
  try
    LMsg.Msg.PIDAvailable := AUsePID;
    LMsg.Msg.Process := AProcess;
    LMsg.Msg.PID := APID;
    LMsg.Msg.Text := AText;

    LMsg.Facility := AFacility;
    LMsg.Severity := ASeverity;
    SendMessage(LMsg);
  finally
    FreeAndNil(LMsg);
  end;
end;

end.
