{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10393: IdTimeUDPServer.pas 
{
{   Rev 1.0    2002.11.12 10:57:42 PM  czhower
}
unit IdTimeUDPServer;

interface
uses IdAssignedNumbers, IdSocketHandle, IdUDPBase, IdUDPServer, Classes;

type
   TIdTimeUDPServer = class(TIdUDPServer)
   protected
     FBaseDate : TDateTime;
     procedure DoUDPRead(AData: TStream; ABinding: TIdSocketHandle); override;
   public
     constructor Create(axOwner: TComponent); override;
   published
     property DefaultPort default IdPORT_TIME;
     {This property is used to set the Date the Time server bases it's   
     calculations from.  If both the server and client are based from the same
     date which is higher than the original date, you can extend it beyond the
     year 2035}
    property BaseDate : TDateTime read FBaseDate write FBaseDate;
   end;

implementation
uses IdGlobal, IdStack, SysUtils;

const
  {This indicates that the default date is Jan 1, 1900 which was specified
   by RFC 868.}
   TIMEUDP_DEFBASEDATE = 2;
{ TIdTimeUDPServer }

constructor TIdTimeUDPServer.Create(axOwner: TComponent);
begin
  inherited Create(axOwner);
  DefaultPort := IdPORT_TIME;
  {This indicates that the default date is Jan 1, 1900 which was specified
   by RFC 868.}
  FBaseDate := TIMEUDP_DEFBASEDATE;
end;

procedure TIdTimeUDPServer.DoUDPRead(AData: TStream;
  ABinding: TIdSocketHandle);
var s : String;
   LTime : Cardinal;
begin
  inherited DoUDPRead(AData, ABinding);
  SetLength(s, AData.Size);
  AData.Read(s[1], AData.Size);
  LTime := Trunc(extended(Now + IdGlobal.TimeZoneBias - Int(FBaseDate)) * 24 * 60 * 60);
  LTime := GStack.WSHToNl(LTime);
  SendBuffer(ABinding.PeerIP,ABinding.PeerPort,LTime,SizeOf(LTime));
end;

end.
 
