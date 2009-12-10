{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10073: IdAuthenticationDigest.pas 
{
{   Rev 1.0    2002.11.12 10:30:44 PM  czhower
}
{
  Implementation of the digest authentication as specified in
  RFC2617

 (See NOTE below for details of what is exactly implemented)

  Author: Doychin Bondzhev (doychin@dsoft-bg.com)
  Copyright: (c) Chad Z. Hower and The Winshoes Working Group.

NOTE:
  This is compleatly untested authtentication. Use it on your own risk.
  I'm sure it won't work from the first time like all normal programs wich
  have never been tested in real life ;-))))

  I'm still looking for web server that I could use to make these tests.
  If you have or know such server and wish to help me in this, just send
  me an e-mail with account informationa (login and password) and the server URL.<G>

  Doychin Bondzhev (doychin@dsoft-bg.com)
}

unit IdAuthenticationDigest;

interface

Uses
  Classes,
  SysUtils,
  IdException,
  IdGlobal,
  IdAuthentication,
  IdHashMessageDigest,
  IdHeaderList;

Type
  EIdInvalidAlgorithm = class(EIdException);

  TIdDigestAuthentication = class(TIDAuthentication)
  protected
    FRealm: String;
    FStale: Boolean;
    FOpaque: String;
    FDomain: TStringList;
    Fnonce: String;
    FAlgorithm: String;
    FQopOptions: TStringList;
    FOther: TStringList;
    function DoNext: TIdAuthWhatsNext; override;
  public
    destructor Destroy; override;
    function Authentication: String; override;
  end;

implementation

uses
  IdHash, IdResourceStrings;

{ TIdDigestAuthentication }

destructor TIdDigestAuthentication.Destroy;
begin
  if Assigned(FDomain) then
    FDomain.Free;
  if Assigned(FQopOptions) then 
    FQopOptions.Free;
  inherited Destroy;
end;

function TIdDigestAuthentication.Authentication: String;
  function ResultString(s: String): String;
  Var
    MDValue: T4x4LongWordRecord;
    PC: PChar;
    i: Integer;
    S1: String;
  begin
    with TIdHashMessageDigest5.Create do begin
      MDValue := HashValue(S);
      Free;
    end;
    PC := PChar(@MDValue[0]);
    for i := 0 to 15 do begin
      S1 := S1 + Format('%02x', [Byte(PC[i])]);
    end;
    while Pos(' ', S1) > 0 do S1[Pos(' ', S1)] := '0';
    result := S1;
  end;

begin
  result := 'Digest ' + {do not localize}
    'username="' + Username + '" ' + {do not localize}
    'realm="' + FRealm + '" ' + {do not localize}
    'result="' + ResultString('') + '"';
end;

function TIdDigestAuthentication.DoNext: TIdAuthWhatsNext;
Var
  S: String;
  Params: TStringList;
begin
  result := wnAskTheProgram;
  case FCurrentStep of
    0: begin
      if not Assigned(FDomain) then begin
        FDomain := TStringList.Create;
      end
      else FDomain.Clear;

      if not Assigned(FQopOptions) then begin
        FQopOptions := TStringList.Create;
      end
      else
        FQopOptions.Clear;

      S := ReadAuthInfo('Digest');

      Fetch(S);

      Params := TStringList.Create;

      while Length(S) > 0 do begin
        Params.Add(Fetch(S, ', '));
      end;

      FRealm := Copy(Params.Values['realm'], 2, Length(Params.Values['realm']) - 2);
      Fnonce := Copy(Params.Values['nonce'], 2, Length(Params.Values['nonce']) - 2);
      S := Copy(Params.Values['domain'], 2, Length(Params.Values['domain']) - 2);
      while Length(S) > 0 do
        FDomain.Add(Fetch(S));
      Fopaque := Copy(Params.Values['opaque'], 2, Length(Params.Values['opaque']) - 2);
      FStale := (Copy(Params.Values['stale'], 2, Length(Params.Values['stale']) - 2) = 'true');
      FAlgorithm := Params.Values['algorithm'];

      FQopOptions.CommaText := Copy(Params.Values['qop'], 2, Length(Params.Values['qop']) - 2);

      if not AnsiSameText(FAlgorithm, 'MD5') then begin
        raise EIdInvalidAlgorithm.Create(RSHTTPAuthInvalidHash);
      end;

      Params.Free;

      result := wnAskTheProgram;
      FCurrentStep := 1;
    end;
    1: begin
      result := wnDoRequest;
      FCurrentStep := 0;
    end;
  end;
end;

initialization
  // This comment will be removed when the Digest authentication is ready
  // RegisterAuthenticationMethod('Digest', TIdDigestAuthentication);
end.
