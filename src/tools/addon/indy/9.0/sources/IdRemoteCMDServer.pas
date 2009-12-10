{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10303: IdRemoteCMDServer.pas 
{
{   Rev 1.0    2002.11.12 10:50:10 PM  czhower
}
unit IdRemoteCMDServer;

{2001, Feb 17
  started this unit with code from TIdRexecServer}

interface

uses
  Classes,
  IdAssignedNumbers, IdTCPClient, IdTCPServer;

type
  TIdRemoteCMDServer = class(TIdTCPServer)
  protected
    FForcePortsInRange : Boolean;
    FStdErrorPortsInRange : Boolean;
    function DoExecute(AThread: TIdPeerThread): boolean; override;
    procedure DoCMD(AThread: TIdPeerThread;
        AStdError : TIdTCPClient; AParam1, AParam2, ACommand : String); virtual; abstract;
  public
    procedure SendError(AThread : TIdPeerThread; AStdErr : TIdTCPClient; AMsg : String);
    procedure SendResults(AThread : TIdPeerThread; AStdErr : TIdTCPClient; AMsg : String);
  end;

implementation

uses
  IdSocketHandle, IdException, IdGlobal, IdIOHandlerSocket,
  SysUtils;

{ TIdRemoteCMDServer }

function TIdRemoteCMDServer.DoExecute(AThread: TIdPeerThread): boolean;
var
  StdError : TIdTCPClient;
  ErrorPort : Integer;
  Param1, Param2, Command : String;

  procedure ExecuteCMD;
  begin
    try
     Result := True;
     StdError := nil;
     ErrorPort := StrToIntDef(Trim(AThread.Connection.ReadLn(#0)),0);

     if ErrorPort <> 0 then
     begin
       StdError := TIdTCPClient.Create(nil);
       if FStdErrorPortsInRange then
       begin
         StdError.BoundPortMax := 1023;
         StdError.BoundPortMin := 512;
       end;
       StdError.BoundIP := (AThread.Connection.IOHandler as TIdIOHandlerSocket).Binding.IP;
       StdError.Host := (AThread.Connection.IOHandler as TIdIOHandlerSocket).Binding.PeerIP;
       StdError.Port := ErrorPort;

       repeat
         try
           StdError.Connect;
           break;
         except
           on E: EIdSocketError do begin
             // This will be uncommented after we have the fix into TIdTCPClient.Connect metod
             // There is one extra line that has to be added in order to run this code
             //
             // except
             //   // This will free IOHandler
             //   BoundPort := TIdIOHandlerSocket(IOHandler).Binding.Port;    // The extra line
             //   DisconnectSocket;
             //   raise;
             // end;
             //
             // After we have this code we will know the exact Port on wich the exception has occured
             
             {if E.LastError = 10048 then begin
               StdError.BoundPortMax := StdError.BoundPort - 1;
               StdError.BoundPort := 0;
               StdError.Disconnect;
             end
             else}
               raise;
           end;
         end;
       until false;
     end;

     Param1 := AThread.Connection.ReadLn(#0);
     Param2 := AThread.Connection.ReadLn(#0);
     Command  := AThread.Connection.ReadLn(#0);

     DoCMD(AThread, StdError, Param1, Param2, Command);
     if Assigned(StdError) then
     begin
       StdError.Disconnect;
     end;
    finally
      FreeAndNil(StdError);
    end;
  end;
begin
  if FForcePortsInRange then begin
    if ((AThread.Connection.IOHandler as TIdIOHandlerSocket).Binding.Port >= 512) or
       ((AThread.Connection.IOHandler as TIdIOHandlerSocket).Binding.Port <= 1023) then
    begin
      ExecuteCMD;
    end;
  end
  else begin
    ExecuteCMD;
  end;
  AThread.Connection.Disconnect;
end;

procedure TIdRemoteCMDServer.SendError(AThread: TIdPeerThread;
  AStdErr: TIdTCPClient; AMsg: String);
begin
  AThread.Connection.Write(#1);
  if Assigned(AStdErr) then
    AStdErr.Write(AMsg)
  else
    AThread.Connection.Write(AMsg);
end;

procedure TIdRemoteCMDServer.SendResults(AThread: TIdPeerThread;
  AStdErr: TIdTCPClient; AMsg: String);
begin
  AThread.Connection.Write(#0 + AMsg)
end;

end.
