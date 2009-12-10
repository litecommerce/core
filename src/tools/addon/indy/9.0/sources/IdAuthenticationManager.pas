{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10075: IdAuthenticationManager.pas 
{
{   Rev 1.0    2002.11.12 10:30:58 PM  czhower
}
unit IdAuthenticationManager;

interface

Uses
  Classes, SysUtils, IdAuthentication, IdURI, IdGlobal, IdBaseComponent;

Type
  TIdAuthenticationItem = class(TCollectionItem)
  protected
    FURI: TIdURI;
    FParams: TStringList;
    procedure SetParams(const Value: TStringList);
    procedure SetURI(const Value: TIdURI);
  public
    constructor Create(ACollection: TCOllection); override;
    destructor Destroy; override;

    property URL: TIdURI read FURI write SetURI;
    property Params: TStringList read FParams write SetParams;
  end;

  TIdAuthenticationCollection = class(TOwnedCollection)
  protected
    function GetAuthItem(AIndex: Integer): TIdAuthenticationItem;
    procedure SetAuthItem(AIndex: Integer;
      const Value: TIdAuthenticationItem);
  public
    constructor Create(AOwner: Tpersistent);

    function Add: TIdAuthenticationItem;

    property Items[AIndex: Integer]: TIdAuthenticationItem read GetAuthItem write SetAuthItem;
  end;

  TIdAuthenticationManager = class(TIdBaseComponent)
  protected
    FAuthentications: TIdAuthenticationCollection;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure AddAuthentication(AAuthtetication: TIdAuthentication; AURL: TIdURI);
    property Authentications: TIdAuthenticationCollection read FAuthentications;
  end;

implementation

{ TIdAuthenticationManager }

function TIdAuthenticationCollection.Add: TIdAuthenticationItem;
begin
  result := TIdAuthenticationItem.Create(self);
end;

constructor TIdAuthenticationCollection.Create(AOwner: Tpersistent);
begin
  inherited Create(AOwner, TIdAuthenticationItem);
end;

function TIdAuthenticationCollection.GetAuthItem(
  AIndex: Integer): TIdAuthenticationItem;
begin
  result := TIdAuthenticationItem(inherited Items[AIndex]);
end;

procedure TIdAuthenticationCollection.SetAuthItem(AIndex: Integer;
  const Value: TIdAuthenticationItem);
begin
  if Items[AIndex] <> nil then begin
    Items[AIndex].Assign(Value);
  end;
end;

{ TIdAuthenticationManager }

procedure TIdAuthenticationManager.AddAuthentication(
  AAuthtetication: TIdAuthentication; AURL: TIdURI);
begin
  with Authentications.Add do begin
    URL.URI := AURL.URI;
    Params.Assign(AAuthtetication.Params);
  end;
end;

constructor TIdAuthenticationManager.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  FAuthentications := TIdAuthenticationCollection.Create(self);
end;

destructor TIdAuthenticationManager.Destroy;
begin
  FreeAndNil(FAuthentications);
  inherited Destroy;
end;

{ TIdAuthenticationItem }

constructor TIdAuthenticationItem.Create(ACollection: TCOllection);
begin
  inherited Create(ACollection);

  FURI := TIdURI.Create;
  FParams := TStringList.Create;
end;

destructor TIdAuthenticationItem.Destroy;
begin
  FreeAndNil(FURI);
  FreeAndNil(FParams);
  inherited Destroy;
end;

procedure TIdAuthenticationItem.SetParams(const Value: TStringList);
begin
  FParams.Assign(Value);
end;

procedure TIdAuthenticationItem.SetURI(const Value: TIdURI);
begin
  FURI.URI := Value.URI;
end;

end.
