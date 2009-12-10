{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10087: IdChargenUDPServer.pas 
{
{   Rev 1.0    2002.11.12 10:32:08 PM  czhower
}
unit IdChargenUDPServer;
{
2001 - Sep 17
  J. Peter Mugaas
    Started this with code from Rune Moburg's UDP Chargen Server  
}
interface
uses IdAssignedNumbers, IdSocketHandle, IdUDPBase, IdUDPServer, Classes;
type
   TIdChargenUDPServer = class(TIdUDPServer)
   protected
     procedure DoUDPRead(AData: TStream; ABinding: TIdSocketHandle); override;
   public
     constructor Create(axOwner: TComponent); override;
   published
     property DefaultPort default IdPORT_CHARGEN;
   end;

implementation

{ TIdChargenUDPServer }

constructor TIdChargenUDPServer.Create(axOwner: TComponent);
begin
  inherited Create(axOwner);
  DefaultPort := IdPORT_CHARGEN;
end;

procedure TIdChargenUDPServer.DoUDPRead(AData: TStream;
  ABinding: TIdSocketHandle);
const
  rowlength = 75;
var
  s: string;
  i, row, ln : integer;
  c: Char;
begin
  inherited DoUDPRead(AData,ABinding);
  SetLength(s, AData.Size);
  AData.Read(s[1], AData.Size);
  i := 1;
  c := '0';     {Do not Localize}
  s := '';       {Do not Localize}
  ln := Random(512);
  Row := 0;
        while i <= ln do
        begin
          if c > #95 then
          begin
            c := '0';   {Do not Localize}
          end;
          if i mod (rowlength + 1) = 0 then
          begin
            s := s + #13;
            c := chr(ord('0') + row mod (95 - ord('0')));   {Do not Localize}
            inc(row);
          end
          else
          begin
            s := s + c;
          end;
          inc(i);
          inc(c);
        end;
  with ABinding do
  begin
    SendTo(PeerIP, PeerPort, s[1], Length(s));
  end;
end;

end.
