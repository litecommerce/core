{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10203: IdIdentServer.pas 
{
{   Rev 1.0    2002.11.12 10:41:52 PM  czhower
}
unit IdIdentServer;

{2001 - Feb 11 - J. Peter Mugaas
        Started this component.

        This is based on RFC 1413  - Identification Protocol

        Note that the default port is assigned to IdPORT_AUTH

        The reason for this is that the RFC specifies port 113 and the old protocol name was
        Authentication Server Protocol.  This was renamed Ident to better reflect what it does.
        }

interface
uses IdAssignedNumbers, IdTCPServer, Classes;

const IdDefIdentQueryTimeOut = 60000; // 1 minute
type  TIdIdentQueryEvent = procedure (AThread: TIdPeerThread; AServerPort, AClientPort : Integer) of object;
      TIdIdentErrorType = (ieInvalidPort, ieNoUser, ieHiddenUser, ieUnknownError);
      TIdIdentServer = class(TIdTCPServer)
      protected
        FOnIdentQuery : TIdIdentQueryEvent;
        FQueryTimeOut : Integer;
        function DoExecute(AThread: TIdPeerThread): boolean; override;
      public
        Constructor Create(AOwner : TComponent); override;
        Procedure ReplyError(AThread : TIdPeerThread; AServerPort, AClientPort : Integer; AErr : TIdIdentErrorType);
        Procedure ReplyIdent(AThread : TIdPeerThread; AServerPort, AClientPort : Integer; AOS, AUserName : String; const ACharset : String = '');    {Do not Localize}
        Procedure ReplyOther(AThread : TIdPeerThread; AServerPort, AClientPort : Integer; AOther : String);
      published
        property QueryTimeOut : Integer read FQueryTimeOut write FQueryTimeOut default IdDefIdentQueryTimeOut;
        Property OnIdentQuery : TIdIdentQueryEvent read FOnIdentQuery write FOnIdentQuery;
        Property DefaultPort default IdPORT_AUTH;
      end;

implementation
uses IdGlobal, SysUtils;

{ TIdIdentServer }

constructor TIdIdentServer.Create(AOwner: TComponent);
begin
  inherited;
  DefaultPort := IdPORT_AUTH;
  FQueryTimeOut := IdDefIdentQueryTimeOut;
end;

function TIdIdentServer.DoExecute(AThread: TIdPeerThread): boolean;
var s : String;
    ServerPort, ClientPort : Integer;
begin
  Result := True;
  s := AThread.Connection.ReadLn('',FQueryTimeOut);    {Do not Localize}
  if AThread.Connection.ReadLnTimedOut then
  begin
    AThread.Connection.Disconnect;
  end
  else
  begin
    ServerPort := StrToInt(Trim(Fetch(s,',')));    {Do not Localize}
    ClientPort := StrToInt(Trim(s));
    If Assigned(FOnIdentQuery) then
      FOnIdentQuery(AThread,ServerPort,ClientPort)
    else
    begin
      ReplyError(AThread,ServerPort,ClientPort,ieUnknownError);
      AThread.Connection.Disconnect;
    end;
  end;
end;

procedure TIdIdentServer.ReplyError(AThread: TIdPeerThread; AServerPort,
  AClientPort: Integer;  AErr : TIdIdentErrorType);
var s : String;
begin
  s := IntToStr(AServerPort)+', '+IntToStr(AClientPort) + ' : ERROR : ';    {Do not Localize}
  case AErr of
    ieInvalidPort : s := s + 'INVALID-PORT';    {Do not Localize}
    ieNoUser : s := s + 'NO-USER';    {Do not Localize}
    ieHiddenUser : s := s + 'HIDDEN-USER';    {Do not Localize}
    ieUnknownError : s := s + 'UNKNOWN-ERROR';    {Do not Localize}
  end;
  AThread.Connection.WriteLn(s);
end;

procedure TIdIdentServer.ReplyIdent(AThread: TIdPeerThread; AServerPort,
  AClientPort: Integer; AOS, AUserName: String; const ACharset: String);
var s : String;
begin
  s := IntToStr(AServerPort)+', '+IntToStr(AClientPort) + ' : USERID : ';    {Do not Localize}
  s := s + AOS;
  if Length(ACharset) > 0 then
    s := s + ','+ACharset;    {Do not Localize}
  s := s + ' : '+AUserName;    {Do not Localize}
  AThread.Connection.WriteLn(s);
end;

procedure TIdIdentServer.ReplyOther(AThread: TIdPeerThread; AServerPort,
  AClientPort: Integer; AOther: String);
begin
  AThread.Connection.WriteLn(IntToStr(AServerPort)+', '+IntToStr(AClientPort) + ' : USERID : OTHER : '+AOther);    {Do not Localize}
end;

end.
