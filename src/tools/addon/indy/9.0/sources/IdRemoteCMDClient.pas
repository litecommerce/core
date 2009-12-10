{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10301: IdRemoteCMDClient.pas 
{
{   Rev 1.0    2002.11.12 10:50:02 PM  czhower
}
unit IdRemoteCMDClient;

{
-2001.02.15 - J. Peter Mugaas
              Started this unit with code originally in
              TIdRexec

}              
(*******************************************************}
{                                                       }
{       Indy Rexec Client TIdRexec                      }
{                                                       }
{       Copyright (C) 2001 Indy Pit Crew                }
{       Author J. Peter Mugaas                          }
{       Based partly on code authored by Laurence LIew  }
{       2001-February-15                                }
{                                                       }
{*******************************************************)

interface

uses
  Classes,
  IdException, IdTCPClient,
  SyncObjs;

const
  IDRemoteUseStdErr = True;
  {for IdRSH, we set this to.  IdRexec will override this}
  IDRemoteFixPort = True;

type
  EIdCanNotBindRang = class(EIdException);

  TIdRemoteCMDClient = class(TIdTCPClient)
  protected
    FUseReservedPorts: Boolean;
    FUseStdError : Boolean;
    FErrorMessage : String;
    FErrorReply : Boolean;
    //
    function InternalExec(AParam1, AParam2, ACommand : String) : String; virtual;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    Function Execute(ACommand: String): String; virtual;
    property ErrorReply : Boolean read FErrorReply;
    property ErrorMessage : String read FErrorMessage;
  published
    property UseStdError : Boolean read FUseStdError write FUseStdError default IDRemoteUseStdErr;
  end;

implementation

uses
  IdComponent, IdGlobal, IdSimpleServer, IdTCPConnection, IdThread, IdIOHandlerSocket,
  SysUtils;

type
  TIdStdErrThread = class(TIdThread)
   protected
     FStdErr : TIdSimpleServer;
     FOutput : String;
   public
     Constructor Create(AStdErr : TIdSimpleServer; ALock : TCriticalSection); reintroduce;
     Procedure Run; override;
     property Output : String read FOutput;
   end;

{ TIdRemoteCMDClient }

constructor TIdRemoteCMDClient.Create(AOwner: TComponent);
begin
  inherited;
  FUseReservedPorts := IDRemoteFixPort;
  FUseStdError := IDRemoteUseStdErr;
end;

destructor TIdRemoteCMDClient.Destroy;
begin
  inherited;
end;

function TIdRemoteCMDClient.Execute(ACommand: String): String;
begin
  Result := '';    {Do not Localize}
end;

function TIdRemoteCMDClient.InternalExec(AParam1, AParam2, ACommand: String) : String;
var
  stdErr : TIdSimpleServer;
  thr : TIdStdErrThread;

      procedure SendAuthentication(APort : Integer);
      begin
           // Send authentication and commands
        Write(IntToStr( APort )+#0);  //stdErr Port Number - none for this session
        Write(AParam1 + #0);
        Write(AParam2 + #0);
        Write(ACommand + #0);
      end;
begin
  Result := '';    {Do not Localize}
  if FUseReservedPorts then begin
    BoundPortMin := 512;
    BoundPortMax := 1023;
  end else begin
    BoundPortMin := 0;
    BoundPortMax := 0;
  end;
  {For RSH, we have to set the port the client to connect.  I don't    
   think it is required to this in Rexec.}
   Connect; try
    if FUseStdError then begin
      StdErr := TIdSimpleServer.Create(nil);
      try
        StdErr.BoundIP := (IOHandler as TIdIOHandlerSocket).Binding.IP;
        StdErr.CreateBinding;
        StdErr.Binding.ClientPortMin := BoundPortMin;
        StdErr.Binding.ClientPortMax := BoundPortMax;
        StdErr.BeginListen;
        thr := TIdStdErrThread.Create(StdErr, nil{, FLock});
        SendAuthentication(StdErr.Binding.Port);
        Thr.Start;
        try
          FErrorReply := (ReadString(1) <> #0);
          {Receive answers}
          BeginWork(wmRead);
          try
            Result := AllData;
          finally
            EndWork(wmRead);
            FErrorMessage := thr.Output;
          end;
        finally
          StdErr.Abort;
          thr.Terminate;
          thr.WaitFor;
        end;
      finally
        FreeAndNil(StdErr);
        FreeAndNil(thr);
      end;
    end
    else
    begin
      SendAuthentication(0);
      FErrorReply := (ReadString(1) <> #0);
      {Receive answers}
      BeginWork(wmRead);
      try
        if FErrorReply then
        begin
          FErrorMessage := AllData;
        end
        else
          Result := AllData;
      finally
        EndWork(wmRead);
      end;
    end;
  finally Disconnect; end;
end;

{ TIdStdErrThread }

constructor TIdStdErrThread.Create(AStdErr: TIdSimpleServer;
  ALock: TCriticalSection);
begin
  inherited Create(True);
  FStdErr := AStdErr;
  FreeOnTerminate := False;
  StopMode := smTerminate;
  FStdErr.BeginListen;
end;

procedure TIdStdErrThread.Run;
begin
  if FStdErr.Listen then begin
    FOutput := FStdErr.AllData;
  end;
end;

end.
