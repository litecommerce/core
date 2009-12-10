{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  13890: IdHTTPProxyServer.pas 
{
{   Rev 1.0    2002.11.22 8:37:16 PM  czhower
}
unit IdHTTPProxyServer;

interface

{
 Indy HTTP proxy Server

 Original Programmer: Bas Gooijen (bas_gooijen@yahoo.com)
 Current Maintainer:  Bas Gooijen
   Code is given to the Indy Pit Crew.

 Modifications by Chad Z. Hower (Kudzu)

 Quick Notes:

 Revision History:
 10-May-2002: Created Unit.
}

uses
  Classes,
  IdAssignedNumbers,
  IdGlobal,
  IdTCPConnection,
  IdTCPServer;

const
  IdPORT_HTTPProxy = 8080;

type
{ not needed (yet)
  TIdHTTPProxyServerThread = class( TIdPeerThread )
  protected
    // what needs to be stored...
    fUser: string;
    fPassword: string;
  public
    constructor Create( ACreateSuspended: Boolean = True ) ; override;
    destructor Destroy; override;
    // Any functions for vars
    property Username: string read fUser write fUser;
    property Password: string read fPassword write fPassword;
  end;
}
  TIdHTTPProxyServer = class;
  TOnHTTPDocument = procedure(ASender: TIdHTTPProxyServer; const ADocument: string;
   var VStream: TStream) of object;

  TIdHTTPProxyServer = class(TIdTcpServer)
  protected
    FOnHTTPDocument: TOnHTTPDocument;
    // CommandHandlers
    procedure CommandGET(ASender: TIdCommand);
    procedure CommandPOST(ASender: TIdCommand);
    procedure CommandHEAD(ASender: TIdCommand);
    procedure CommandConnect(ASender: TIdCommand); // for ssl
    procedure DoHTTPDocument(const ADocument: string; var VStream: TStream);
    procedure InitializeCommandHandlers; override;
    procedure TransferData(ASrc: TIdTCPConnection; ADest: TIdTCPConnection; const ADocument: string;
     const ASize: Integer);
  public
    constructor Create( AOwner: TComponent ) ; override;
  published
    property DefaultPort default IdPORT_HTTPProxy;
    property OnHTTPDocument: TOnHTTPDocument read FOnHTTPDocument write FOnHTTPDocument;
  end;

// Procs
  procedure Register;

implementation

uses
  IdResourceStrings,
  IdRFCReply,
  IdHeaderList,
  IdTCPClient,
  IdURI,
  SysUtils;

procedure Register;
begin
  RegisterComponents('Indy 10', [TIdHTTPProxyServer]);
end;

procedure TIdHTTPProxyServer.InitializeCommandHandlers;
begin
  inherited;
  with CommandHandlers.Add do begin
    Command := 'GET';
    OnCommand := CommandGet;
    ParseParams := True;
  end;
  with CommandHandlers.Add do
  begin
    Command := 'POST';
    OnCommand := CommandPOST;
    ParseParams := True;
  end;
  with CommandHandlers.Add do
  begin
    Command := 'HEAD';
    OnCommand := CommandHEAD;
    ParseParams := True;
  end;
  with CommandHandlers.Add do
  begin
    Command := 'CONNECT';
    OnCommand := Commandconnect;
    ParseParams := True;
  end;
end;

procedure TIdHTTPProxyServer.TransferData(ASrc: TIdTCPConnection; ADest: TIdTCPConnection;
 const ADocument: string; const ASize: Integer);
//TODO: This captures then sends. This is great and we need this as an option for proxies that
// modify data. However we also need another option that writes as it captures.
// Two modes? Intercept and not?
var
  LStream: TStream;
begin
  //TODO: Have an event to let the user perform stream creation
  LStream := TMemoryStream.Create; try
      ASrc.ReadStream(LStream, ASize, ASize = -1);
    LStream.Position := 0;
    DoHTTPDocument(ADocument, LStream);
    ADest.WriteStream(LStream);
  finally FreeAndNil(LStream); end;
end;

procedure TIdHTTPProxyServer.CommandGET( ASender: TIdCommand ) ;
var
  LClient: TIdTCPClient;
  LDocument: string;
  LHeaders: TIdHeaderList;
  LRemoteHeaders: TIdHeaderList;
  LURI: TIdURI;
  LPageSize: Integer;
begin
  try
    LHeaders := TIdHeaderList.Create; try
      LHeaders.Add(ASender.RawLine) ;
      ASender.Thread.Connection.Capture(LHeaders, '');
      LClient := TIdTCPClient.Create(nil); try
        LURI := TIdURI.Create(ASender.Params.Strings[0]); try
          LClient.Port := StrToIntDef(LURI.Port, 80);
          LClient.Host := LURI.Host;
          //We have to remove the host and port from the request
          LDocument := LURI.Path + LURI.Document + LURI.Params;
        finally FreeAndNil(LURI); end;
        LClient.Connect; try
          LClient.WriteLn('GET ' + LDocument + ' HTTP/1.0'); {Do not Localize}
          LClient.WriteHeader(LHeaders);
          LRemoteHeaders := TIdHeaderList.Create; try
            LClient.Capture(LRemoteHeaders, '');
            ASender.Thread.Connection.WriteHeader(LRemoteHeaders);
            LPageSize := StrToIntDef(LRemoteHeaders.Values['Content-Length'], -1) ; {Do not Localize}
          finally FreeAndNil(LRemoteHeaders); end;
          TransferData(LClient, ASender.Thread.Connection, LDocument, LPageSize);
        finally LClient.Disconnect; end;
      finally FreeAndNil(LClient); end;
    finally FreeAndNil(LHeaders); end;
  finally ASender.Thread.Connection.Disconnect; end;
end;

procedure TIdHTTPProxyServer.CommandPOST( ASender: TIdCommand ) ; {works not 100%}
var
  Headers: tidheaderlist;
  RemoteHeaders: tidheaderlist;
  IdtcpClient: tidtcpclient;
  IdURI: TIdURI;
  PageSize: integer;
  postStream:tmemorystream;
begin
  Headers := tidheaderlist.create;
  try
    Headers.Add( ASender.RawLine ) ;
    ASender.Thread.Connection.Capture( Headers, '' ) ; {Do not Localize}
    PostStream:=tmemorystream.create;
PostStream.size:=      strtointdef( Headers.Values['Content-Length'], 0 ); {Do not Localize}

    ASender.Thread.Connection.ReadStream(PostStream,PostStream.size,false);
    IdtcpClient := tIdtcpClient.create( nil ) ;
    IdURI := TIdURI.create( ASender.Params.strings[0] ) ;
    try
      IdtcpClient.Port := strtointdef( IdURI.port, 80 ) ;
      IdtcpClient.host := IdURI.Host;
      headers.strings[0] := 'POST ' + IdURI.Path + IdURI.Document + IdURI.Params + ' '+ASender.Params.strings[1]; {Do not Localize}
    finally
      IdURI.free;
    end;
    IdtcpClient.Connect;
    IdtcpClient.WriteLn(headers.Strings[0]);
    headers.Delete(0);
    IdtcpClient.WriteHeader( headers ) ;
    IdtcpClient.WriteStream(PostStream,true,false);
    PostStream.free;

    RemoteHeaders := TIdHeaderList.create;
    try
      IdtcpClient.Capture( RemoteHeaders, '' ) ; {Do not Localize}
      ASender.Thread.Connection.WriteHeader( RemoteHeaders ) ;
      pageSize := strtointdef( RemoteHeaders.Values['Content-Length'], -1 ) ; {Do not Localize}
    finally
      RemoteHeaders.free;
    end;

    TransferData( IdtcpClient, ASender.Thread.Connection, '', pageSize ) ;

    IdtcpClient.Disconnect;
    IdtcpClient.free;
  finally
    headers.free;
    ASender.Thread.Connection.Disconnect;
  end;
end;

procedure TIdHTTPProxyServer.CommandConnect( ASender: TIdCommand ) ;
begin
end;

procedure TIdHTTPProxyServer.CommandHEAD( ASender: TIdCommand ) ;
begin
end;

constructor TIdHTTPProxyServer.Create( AOwner: TComponent ) ;
begin
  inherited;
  DefaultPort := IdPORT_HTTPProxy;
  Greeting.Text.Text := ''; // RS
  ReplyUnknownCommand.Text.Text := ''; // RS
end;

procedure TIdHTTPProxyServer.DoHTTPDocument(const ADocument: string; var VStream: TStream);
begin
  if Assigned(OnHTTPDocument) then begin
    OnHTTPDocument(Self, ADocument, VStream);
  end;
end;

end.


