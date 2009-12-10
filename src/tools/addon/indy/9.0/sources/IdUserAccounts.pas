{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10415: IdUserAccounts.pas 
{
{   Rev 1.0    2002.11.12 10:59:32 PM  czhower
}
unit IdUserAccounts;
{
 Original Author: Sergio Perry
 Date: 24/04/2001
}

interface

uses
  Classes,
  IdException,
  IdGlobal,
  IdBaseComponent,
  IdComponent,
  IdStrings,
  SysUtils;

type
  TIdUserManager = class;

  TIdUserAccount = class(TCollectionItem)
  protected
    FAttributes: Tstrings;
    FData: TObject;
    FUserName: string;
    FPassword: string;
    FRealName: string;
    //
    procedure SetAttributes(const AValue: TStrings);
  public
    constructor Create(ACollection: TCollection); override;
    destructor Destroy; override;
    //
    function CheckPassword(const APassword: String): Boolean;
    //
    property Data: TObject read FData write FData;
  published
    property Attributes: Tstrings read FAttributes write SetAttributes;
    property UserName: string read FUserName write FUserName;
    property Password: string read FPassword write FPassword;
    property RealName: string read FRealName write FRealName;
  end;

  TIdUserAccounts = class(TOwnedCollection)
  protected
    FCaseSensitiveUsernames: Boolean;
    FCaseSensitivePasswords: Boolean;
    //
    function GetAccount(const AIndex: Integer): TIdUserAccount;
    function GetByUsername(const AUsername: String): TIdUserAccount;
    procedure SetAccount(const AIndex: Integer; AAccountValue: TIdUserAccount);
  public
    function Add: TIdUserAccount; reintroduce;
    constructor Create(AOwner: TIdUserManager);
    //
    property CaseSensitiveUsernames: Boolean read FCaseSensitiveUsernames
      write FCaseSensitiveUsernames;
    property CaseSensitivePasswords: Boolean read FCaseSensitivePasswords
      write FCaseSensitivePasswords;
    property UserNames[const AUserName: String]: TIdUserAccount read GetByUsername; default;
    property Items[const AIndex: Integer]: TIdUserAccount read GetAccount write SetAccount;
  end;

  TOnAfterAuthentication = procedure(const AUsername: String; const APassword: String;
    AAuthenticationResult: Boolean) of object;

  TIdUserManager = class(TIdBaseComponent)
  protected
    FAccounts: TIdUserAccounts;
    FOnAfterAuthentication: TOnAfterAuthentication;
    //
    procedure DoAfterAuthentication(const AUsername, APassword: String;
      AAuthenticationResult: Boolean);
    function GetCaseSensitivePasswords: Boolean;
    function GetCaseSensitiveUsernames: Boolean;
    procedure SetAccounts(AValue: TIdUserAccounts);
    procedure SetCaseSensitivePasswords(const AValue: Boolean);
    procedure SetCaseSensitiveUsernames(const AValue: Boolean);
  public
    function AuthenticateUser(const AUsername, APassword: String): Boolean;
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
  published
    property Accounts: TIdUserAccounts read FAccounts write SetAccounts;
    property CaseSensitiveUsernames: Boolean read GetCaseSensitiveUsernames
      write SetCaseSensitiveUsernames;
    property CaseSensitivePasswords: Boolean read GetCaseSensitivePasswords
      write SetCaseSensitivePasswords;
    property OnAfterAuthentication: TOnAfterAuthentication read FOnAfterAuthentication
      write FOnAfterAuthentication;
  end;

implementation

{ TIdUserAccount }

function TIdUserAccount.CheckPassword(const APassword: String): Boolean;
begin
  if (Collection as TIdUserAccounts).CaseSensitivePasswords then
  begin
    Result := Password = APassword;
  end
  else
  begin
    Result := AnsiSameText(Password, APassword);
  end;
end;

constructor TIdUserAccount.Create(ACollection: TCollection);
begin
  inherited Create(ACollection);
  FAttributes := TStringList.Create;
end;

destructor TIdUserAccount.Destroy;
begin
  FreeAndNil(FAttributes);
  inherited Destroy;
end;

procedure TIdUserAccount.SetAttributes(const AValue: TStrings);
begin
  FAttributes.Assign(AValue);
end;

{ TIdUserAccounts }

constructor TIdUserAccounts.Create(AOwner: TIdUserManager);
begin
  inherited Create(AOwner, TIdUserAccount);
end;

function TIdUserAccounts.GetAccount(const AIndex: Integer): TIdUserAccount;
begin
  Result := TIdUserAccount(inherited Items[AIndex]);
end;

function TIdUserAccounts.GetByUsername(const AUsername: String): TIdUserAccount;
var
  i: Integer;
begin
  Result := nil;
  if CaseSensitiveUsernames then
  begin
    for i := 0 to Count - 1 do
    begin
      if AUsername = Items[i].UserName then
      begin
        Result := Items[i];
        Break;
      end;
    end;
  end
  else
  begin
    for i := 0 to Count - 1 do
    begin
      if AnsiSameText(AUsername, Items[i].UserName) then
      begin
        Result := Items[i];
        Break;
      end;
    end;
  end;
end;

procedure TIdUserAccounts.SetAccount(const AIndex: Integer; AAccountValue: TIdUserAccount);
begin
  inherited SetItem(AIndex, AAccountValue);
end;

function TIdUserAccounts.Add: TIdUserAccount;
begin
  Result := inherited Add as TIdUserAccount;
end;

{ IdUserAccounts - Main Component }

constructor TIdUserManager.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FAccounts := TIdUserAccounts.Create(Self);
end;

destructor TIdUserManager.Destroy;
begin
  FreeAndNil(FAccounts);
  inherited Destroy;
end;

function TIdUserManager.AuthenticateUser(const AUsername, APassword: String): Boolean;
var
  LUser: TIdUserAccount;
begin
  Result := False;
  LUser := Accounts[AUsername];
  if LUser = nil then
  begin
    Exit; //Result := False;
  end
  else
  begin
    if LUser.CheckPassword(APassword) = True then
    begin
      Result := True;
    end;
  end;
  DoAfterAuthentication(AUsername, APassword, Result);
end;

procedure TIdUserManager.SetAccounts(AValue: TIdUserAccounts);
begin
  FAccounts.Assign(AValue);
end;

procedure TIdUserManager.DoAfterAuthentication(const AUsername, APassword: String;
  AAuthenticationResult: Boolean);
begin
  if Assigned(FOnAfterAuthentication) then
  begin
    FOnAfterAuthentication(AUsername, APassword, AAuthenticationResult);
  end;
end;

function TIdUserManager.GetCaseSensitivePasswords: Boolean;
begin
  Result := FAccounts.CaseSensitivePasswords;
end;

function TIdUserManager.GetCaseSensitiveUsernames: Boolean;
begin
  Result := FAccounts.CaseSensitiveUsernames;
end;

procedure TIdUserManager.SetCaseSensitivePasswords(const AValue: Boolean);
begin
  FAccounts.CaseSensitivePasswords := AValue;
end;

procedure TIdUserManager.SetCaseSensitiveUsernames(const AValue: Boolean);
begin
  FAccounts.CaseSensitiveUsernames := AValue;
end;

end.
