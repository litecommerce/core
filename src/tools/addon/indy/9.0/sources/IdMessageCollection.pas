{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10263: IdMessageCollection.pas 
{
{   Rev 1.0    2002.11.12 10:46:32 PM  czhower
}
unit IdMessageCollection;

{*

  TIdMessageCollection: Contains a collection of IdMessages.

  2000-APR-14 Peter Mee: Converted to Indy.
  2001-MAY-03 Idan Cohen: Added Create and Destroy of TIdMessage.

  Originally by Peter Mee.
*}
interface

uses
  Classes,
  IdMessage;

type
  TIdMessageItems = class of TIdMessageItem;

  TIdMessageItem = class(TCollectionItem)
  protected
    FAttempt: Integer;
    FQueued: Boolean;
  public
    IdMessage: TIdMessage;
    property Attempt: Integer read FAttempt write FAttempt;
    property Queued: Boolean read FQueued write FQueued;
    constructor Create(Collection: TCollection); override;
    destructor Destroy; override;
  end;

  TIdMessageCollection = class(TCollection)
  private
    function GetMessage(index: Integer): TIdMessage;
    procedure SetMessage(index: Integer; const Value: TIdMessage);
  public
    function Add: TIdMessageItem;
    property Messages[index: Integer]: TIdMessage read GetMessage write SetMessage; Default;
  end;

implementation

function TIdMessageCollection.Add;
begin
  Result := TIdMessageItem(inherited Add);
end;

{ TIdMessageItem }

constructor TIdMessageItem.Create;
begin
  inherited;
  IdMessage := TIdMessage.Create(nil);
end;

destructor TIdMessageItem.Destroy;
begin
  IdMessage.Free;
  inherited;
end;

function TIdMessageCollection.GetMessage(index: Integer): TIdMessage;
begin
     Result := TIdMessageItem(Items[index]).IdMessage;
end;

procedure TIdMessageCollection.SetMessage(index: Integer;
  const Value: TIdMessage);
begin
     //I think it should be freed before the new value is assigned or else the
     //pointer will be lost.
     TIdMessageItem(Items[index]).IdMessage.Free;
     TIdMessageItem(Items[index]).IdMessage := Value;
end;

end.
