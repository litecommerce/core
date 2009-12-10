{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10171: IdGopher.pas 
{
{   Rev 1.0    2002.11.12 10:39:30 PM  czhower
}

{*******************************************************}
{                                                       }
{       Indy Gopher Client TIdGopher                    }
{                                                       }
{       Copyright (C) 2000 Winshoes Working Group       }
{       Started by J. Peter Mugaas                      }
{       April 20, 2000                                  }
{                                                       }
{*******************************************************}
{
 2000-June- 9  J. Peter Mugaas
  -adjusted the Gopher+ support so that line-unfolding is disabled in
   FGopherBlock.  Many headers we use start with spaces
  -made the ASK block into a TIdHeaderList to facilitate use better.  This does
   unfold lines
 2000-May -24  J. Peter Mugaas
  -changed interface of file retrieval routines to so DestStream property does
   not have to even exist now.
 2000-May -17  J. Peter Mugaas
  -Optimized the DoneSettingInfoBlock method in the TIdGopherMenuItem object
  -Added Ask property to the TIdGopherMenuItem 
 2000-May -13  J. Peter Mugaas
  -Chanded the event types and classes to be prefixed with Id.
 2000-Apr.-28  J. Peter Mugaas
  -Added built in Gopher+ support
 2000-Apr.-21  J. Peter Mugaas
  -Added the ability to receive a file
  -Restructured this component to make the code more reabible,
   facilitate processing, and improve object orientation
 2000-Apr.-20  J. Peter Mugaas
  -Started this unit
}

unit IdGopher;

interface

uses
  Classes,
  IdAssignedNumbers,
  IdEMailAddress,
  IdHeaderList, IdTCPClient;

type
  TIdGopherMenuItem = class ( TCollectionItem )
  protected
    FTitle : String;
    FItemType : Char;
    FSelector : String;
    FServer : String;
    FPort : Integer;
    FGopherPlusItem : Boolean;
    FGopherBlock : TIdHeaderList;
    FViews : TStringlist;
    FURL : String;
    FAbstract : TStringList;
    FAsk : TIdHeaderList;
    fAdminEmail : TIdEMailAddressItem;
    function GetLastModified : String;
    function GetOrganization : String;
    function GetLocation : String;
    function GetGeog : String;
  public
    constructor Create(ACollection: TCollection); override;
    destructor Destroy; override;
    {This procedure updates several internal variables and should be done when
    all data has been added}
    procedure DoneSettingInfoBlock; virtual;
    {This is the title for the gopher Menu item and should be displayed to the
    user}
    property Title : String read FTitle write FTitle;
    {This charactor indicates the type of Item that this is.
    Use this to determine what methods to call to get the item}
    property ItemType : Char read FItemType write FItemType;
    {This is the Selector you use to retreive the item}
    property Selector : String read FSelector write FSelector;
    {This is the server you connect to and request the item from.  Set the host
    property to this when retrieving it}
    property Server : String read FServer write FServer;
    {This indicates the port you connect to in order to request the item.  Set
    the port property to this value to get an item.}
    property Port : Integer read FPort write FPort;
    {This indicates if the item is on a Gopher+ server - you can use
    GetExtended Menues for menus}
    property GopherPlusItem : Boolean read FGopherPlusItem
      write FGopherPlusItem;
    {These items are only available if you use the GetExtendedMenu method}
    {This is the complete information block for this gopher+ item}
    property GopherBlock : TIdHeaderList read FGopherBlock;
    {URL listed at +URL: Section }
    property URL : String read FURL;
    {This is the Gopher Views available for the item.  You can include this
    when requesting it}
    property Views : TStringList read FViews;
    {abstract of Gopher item - had to be AAbstract due to Pascal reserved word}
    {this is a summery of a particular item - e.g. "Read about our greate
     products"}
    property AAbstract : TStringList read FAbstract;
    {This is the date that the item was last modified}
    property LastModified : String read GetLastModified;
    {This is contact information for the adminst}
    property AdminEMail : TIdEMailAddressItem read fAdminEmail;
    {This is the organization running the server and
    is usually only found in the Root item}
    property Organization : String read GetOrganization;
    {This is the location where the Gopher is
    and is usually only found in the Root item}
    property Location : String read GetLocation;
    {This is the latitude longitude and longitude of the Gopher server
    and is usually only found in the Root item}
    property Geog : String read GetGeog;
    {This Gopher+ information is used for prmoting users for Query data}
    property Ask : TIdHeaderList read FAsk;
  end;
  TIdGopherMenu = class ( TCollection )
  protected
    function GetItem ( Index: Integer ) : TIdGopherMenuItem;
    procedure SetItem ( Index: Integer; const Value: TIdGopherMenuItem );
  public
    constructor Create; reintroduce;
    function Add: TIdGopherMenuItem;
    property Items [ Index: Integer ] : TIdGopherMenuItem read GetItem
      write SetItem; default;
  end;
  TIdGopherMenuEvent = procedure ( Sender : TObject;
    MenuItem : TIdGopherMenuItem ) of object;

  TIdGopher = class ( TIdTCPClient )
  private
    { Private declarations }
  protected
    { Protected declarations }
    FOnMenuItem : TIdGopherMenuEvent;
    {This triggers the menu item event}
    Procedure DoMenu ( MenuItem : TIdGopherMenuItem  );
    {This fires an exception for Gopher+ errors}
    Procedure ProcessGopherError;
    {This takes parses a string and makes a Menu Item for it}
    Function MenuItemFromString ( stLine : String; Menu : TIdGopherMenu)
      : TIdGopherMenuItem;
    {Process the menu while we retreive it}
    Function ProcessDirectory ( PreviousData : String = '';    {Do not Localize}
      const ExpectedLength: Integer = 0) : TIdGopherMenu;
    {This processes extended Gopher Menues}
    Function LoadExtendedDirectory ( PreviousData : String = '';    {Do not Localize}
     const ExpectedLength: Integer = 0) : TIdGopherMenu;
    {This processes the file when we retreive it and puts it in ADestStream. }
    procedure ProcessFile ( ADestStream : TStream; APreviousData : String = '';    {Do not Localize}
      const ExpectedLength : Integer = 0);
    {For Gopher +, we call this routine when we get a -2 length which means,
    read until you see EOL+.+EOL}
    Procedure ProcessTextFile ( ADestStream : TStream;
      APreviousData: String = ''; const ExpectedLength: Integer = 0);    {Do not Localize}
  public
    { Public declarations }
    constructor Create ( AOwner: TComponent ); override;
    Function GetMenu (ASelector : String; IsGopherPlus : Boolean = False; AView : String = '' ) :    {Do not Localize}
      TIdGopherMenu;
    Function Search(ASelector, AQuery : String) : TIdGopherMenu;
    procedure GetFile (ASelector : String; ADestStream : TStream; IsGopherPlus : Boolean = False; AView: String = '');    {Do not Localize}
    procedure GetTextFile(ASelector : String; ADestStream : TStream; IsGopherPlus : Boolean = False; AView: String = '');    {Do not Localize}
    Function GetExtendedMenu (ASelector : String; AView: String = '' ) : TIdGopherMenu;    {Do not Localize}
  published
    { Published declarations }
    property OnMenuItem : TIdGopherMenuEvent read FOnMenuItem write FOnMenuItem;
    property Port default IdPORT_Gopher;
  end;

implementation

uses
  IdComponent, IdException,
  IdGlobal, IdGopherConsts,
  IdTCPConnection,
  SysUtils;

Procedure WriteToStream(AStream : TStream; AString : String);
begin
  if Length(AString) > 0 then
    AStream.Write( AString [ 1 ], Length ( AString ) );
end;

{ TIdGopher }

constructor TIdGopher.Create ( AOwner: TComponent );
begin
  inherited;
  Port := IdPORT_GOPHER;
end;

procedure TIdGopher.DoMenu(MenuItem: TIdGopherMenuItem);
begin
  if Assigned( FOnMenuItem ) then
    FOnMenuItem( Self, MenuItem );
end;

procedure TIdGopher.ProcessGopherError;
var ErrorNo : Integer;
    ErrMsg : String;
begin
  ErrMsg := AllData;
  {Get the error number from the error reply line}
  ErrorNo := StrToInt ( Fetch ( ErrMsg ) );
  {we want to drop the CRLF+'.'+CRLF}    {Do not Localize}
  raise EIdProtocolReplyError.CreateError(ErrorNo, Copy(ErrMsg, 1, Length(ErrMsg) - 5));
end;

function TIdGopher.MenuItemFromString(stLine: String;
  Menu: TIdGopherMenu): TIdGopherMenuItem;
begin
  {just in case a space thows things off}
  stLine := Trim(stLine);
  if Assigned ( Menu ) then
  begin
    Result := Menu.Add;
  end  // if Assigned ( Menu ) then
  else
  begin
    Result := TIdGopherMenuItem.Create( nil );
  end; // else .. if Assigned ( Menu ) then
  {title and Item Type}
  Result.Title := IdGlobal.Fetch ( stLine, TAB );
  if Length ( Result.Title ) > 0 then
  begin
    Result.ItemType := Result.Title [ 1 ];
  end  //if Length.Result.Title > 0 then
  else
  begin
    Result.ItemType := IdGopherItem_Error;
  end; //else..if Length.Result.Title > 0 then
  {drop first charactor because that was the item type indicator}
  Result.Title := Copy ( Result.Title, 2, Length ( Result.Title ) );
  {selector string}
  Result.Selector := Fetch ( stLine, TAB );
  {server}
  Result.Server  := Fetch ( stLine, TAB );
  {port}
  Result.Port    := StrToInt ( Fetch ( stLine, TAB ) );
  {is Gopher + Item}
  stLine := Fetch ( stLine, TAB );
  Result.GopherPlusItem := ( (Length ( stLine) > 0 ) and
     ( stLine [ 1 ] = '+' ) );    {Do not Localize}
end;

Function TIdGopher.LoadExtendedDirectory ( PreviousData : String = '';    {Do not Localize}
  const ExpectedLength: Integer = 0) : TIdGopherMenu;
var
  stLine : String;
  gmnu : TIdGopherMenuItem;
begin
  BeginWork(wmRead, ExpectedLength); try
    Result := TIdGopherMenu.Create;
    gmnu := nil;
    repeat
      stLine := PreviousData + ReadLn;
      {we use the Previous data only ONCE}
      PreviousData := '';    {Do not Localize}
      {we process each line only if it is not the last and the
      OnMenuItem is assigned}
      if ( stLine <> '.' ) then    {Do not Localize}
      begin
        {This is a new Extended Gopher menu so lets start it}
        if ( Copy (stLine, 1, Length ( IdGopherPlusInfo ) ) = IdGopherPlusInfo ) then
        begin
          {fire event for previous item}
          if (gmnu <> nil) then
          begin
            gmnu.DoneSettingInfoBlock;
            DoMenu ( gmnu );
          end;  //if (gmnu <> nil) then
          gmnu := MenuItemFromString ( RightStr( stLine,
            Length ( stLine ) - Length ( IdGopherPlusInfo ) ) , Result );
          gmnu.GopherBlock.Add ( stLine);
        end //if (Pos(IdGopherGPlusInfo, stLine) = 0) then
        else
        begin
          if Assigned( gmnu ) and (stLine <> '') then    {Do not Localize}
          begin
            gmnu.GopherBlock.Add ( stLine );
          end;
        end;  //else...if (Pos(IdGopherGPlusInfo, stLine) = 0) then
      end //if not stLine = '.' then    {Do not Localize}
      else
      begin
        {fire event for the last line}
        if (gmnu <> nil) then
        begin
          DoMenu ( gmnu );
        end;  //if (gmnu <> nil) then
      end; //if ( stLine <> '.' ) then    {Do not Localize}
    until (stLine = '.') or not Connected;    {Do not Localize}
  finally EndWork(wmRead); end;
end;

Function TIdGopher.ProcessDirectory ( PreviousData : String = '';    {Do not Localize}
  const ExpectedLength: Integer = 0) : TIdGopherMenu;
var stLine : String;

begin
  BeginWork(wmRead,ExpectedLength); try
    Result := TIdGopherMenu.Create;
    repeat
      stLine := PreviousData + ReadLn;
      {we use the Previous data only ONCE}
      PreviousData := '';    {Do not Localize}
      {we process each line only if it is not the last and the OnMenuItem
      is assigned}
      if ( stLine <> '.' ) then    {Do not Localize}
      begin
      //add Gopher Menu item and fire event
        DoMenu ( MenuItemFromString ( stLine, Result ) );
      end; //if not stLine = '.' then    {Do not Localize}
    until (stLine = '.') or not Connected;    {Do not Localize}
  finally
    EndWork(wmRead);
  end; //try..finally
end;

procedure TIdGopher.ProcessTextFile(ADestStream : TStream; APreviousData: String = '';    {Do not Localize}
  const ExpectedLength: Integer = 0);
begin
  WriteToStream(ADestStream, APreviousData);
  BeginWork(wmRead,ExpectedLength);
  try
    Capture(ADestStream,'.',True);    {Do not Localize}
  finally
    EndWork(wmRead);
  end;  //try..finally
end;

procedure TIdGopher.ProcessFile ( ADestStream : TStream; APreviousData : String = '';    {Do not Localize}
  const ExpectedLength : Integer = 0);
begin
  BeginWork(wmRead,ExpectedLength);
  try
    WriteToStream(ADestStream, APreviousData);
    ReadStream(ADestStream,-1,True);
    ADestStream.Position := 0;
  finally
    EndWork(wmRead);
  end;
end;

Function TIdGopher.Search(ASelector, AQuery : String) : TIdGopherMenu;
begin
  Connect;
  try
    {Gopher does not give a greating}
    WriteLn ( ASelector + TAB + AQuery );
    Result := ProcessDirectory;
  finally
    Disconnect;
  end; {try .. finally .. end }
end;

procedure TIdGopher.GetFile (ASelector : String; ADestStream : TStream;
  IsGopherPlus : Boolean = False;
  AView: String = '');    {Do not Localize}
var Reply : Char;
    LengthBytes : Integer;  {legnth of the gopher items}

begin
  Connect;
  try
    if not IsGopherPlus then
    begin
      WriteLn ( ASelector );
      ProcessFile ( ADestStream );
    end  // if not IsGopherPlus then
    else
    begin
      {I hope that this drops the size attribute and that this will cause the
       Views to work, I'm not sure}    {Do not Localize}
      AView := Trim ( Fetch ( AView, ':' ) );    {Do not Localize}
      WriteLn ( ASelector + TAB +'+'+ AView );    {Do not Localize}
      {We read only one byte from the peer}
      ReadBuffer( Reply, 1 );
      {Get the additonal reply code for error or success}
      case Reply of
        '-' : begin    {Do not Localize}
                {Get the length byte}
                ReadLn;
                ProcessGopherError;
              end; {-}
              {success - read file}
        '+' : begin    {Do not Localize}
                {Get the length byte}
                LengthBytes := StrToInt ( ReadLn );
                case LengthBytes of
                 {dot terminated - probably a text file}
                  -1 : ProcessTextFile ( ADestStream );
                  {just read until I disconnect you}
                  -2 : ProcessFile ( ADestStream );
                else
                  ProcessFile ( ADestStream, '', LengthBytes);    {Do not Localize}
                end; //case LengthBytes of
              end; {+}
        else
        begin
          ProcessFile ( ADestStream, Reply );
        end;  //else ..case Reply of
      end;  //case Reply of
    end; //else..if IsGopherPlus then
  finally
    Disconnect;
  end; {try .. finally .. end }
end;

function TIdGopher.GetMenu ( ASelector : String; IsGopherPlus : Boolean = False; AView : String = '' ) :    {Do not Localize}
      TIdGopherMenu;
var Reply : Char;
    LengthBytes : Integer;  {legnth of the gopher items}
begin
  Result := nil;
  Connect;
  try
    if not IsGopherPlus then
    begin
      WriteLn ( ASelector );
      Result := ProcessDirectory;
    end  // if not IsGopherPlus then
    else
    begin
      {Gopher does not give a greating}
      WriteLn ( ASelector + TAB+'+' + AView );    {Do not Localize}
      {We read only one byte from the peer}
      ReadBuffer( Reply, 1 );
      {Get the additonal reply code for error or success}
      case Reply of
        '-' : begin    {Do not Localize}
                ReadLn;
                ProcessGopherError;
              end;  {-}
        '+' : begin    {Do not Localize}
                {Get the length byte}
                LengthBytes := StrToInt ( ReadLn );
                Result := ProcessDirectory ('', LengthBytes );    {Do not Localize}
              end;  {+}
        else
        begin
          Result := ProcessDirectory ( Reply );
        end; //else..case Reply of
      end; //case Reply of
    end; //if not IsGopherPlus then
  finally
    Disconnect;
  end;  {try .. finally .. end }
end;

Function TIdGopher.GetExtendedMenu(ASelector, AView: String) : TIdGopherMenu;
var
  Reply : Char;
  LengthBytes : Integer;  {legnth of the gopher items}
begin
  Result := nil;
  Connect; try
    {Gopher does not give a greating}
    WriteLn(ASelector + TAB + '$' + AView);    {Do not Localize}
    {We read only one byte from the peer}
    ReadBuffer(Reply, 1);
    {Get the additonal reply code for error or success}
    case Reply of
      '-' : begin    {Do not Localize}
              ReadLn;
              ProcessGopherError;
            end;  {-}
      '+' : begin    {Do not Localize}
              {Get the length byte}
              LengthBytes := StrToInt ( ReadLn );
              Result := LoadExtendedDirectory( '', LengthBytes);    {Do not Localize}
            end;  {+}
    else
      Result := ProcessDirectory ( Reply );
    end; //case Reply of
  finally
    Disconnect;
  end;  {try .. finally .. end }
end;

procedure TIdGopher.GetTextFile(ASelector: String; ADestStream: TStream;
  IsGopherPlus: Boolean; AView: String);
var Reply : Char;
    LengthBytes : Integer;  {legnth of the gopher items}

begin
  Connect;
  try
    if not IsGopherPlus then
    begin
      WriteLn ( ASelector );
      ProcessTextFile ( ADestStream );
    end  // if not IsGopherPlus then
    else
    begin
      {I hope that this drops the size attribute and that this will cause the
       Views to work, I'm not sure}    {Do not Localize}
      AView := Trim ( Fetch ( AView, ':' ) );    {Do not Localize}
      WriteLn ( ASelector + TAB +'+'+ AView );    {Do not Localize}
      {We read only one byte from the peer}
      ReadBuffer( Reply, 1 );
      {Get the additonal reply code for error or success}
      case Reply of
        '-' : begin    {Do not Localize}
                {Get the length byte}
                ReadLn;
                ProcessGopherError;
              end; {-}
              {success - read file}
        '+' : begin    {Do not Localize}
                {Get the length byte}
                LengthBytes := StrToInt ( ReadLn );
                case LengthBytes of
                 {dot terminated - probably a text file}
                  -1 : ProcessTextFile ( ADestStream );
                  {just read until I disconnect you}
                  -2 : ProcessFile ( ADestStream );
                else
                  ProcessTextFile ( ADestStream, '', LengthBytes);    {Do not Localize}
                end; //case LengthBytes of
              end; {+}
        else
        begin
          ProcessTextFile ( ADestStream, Reply );
        end;  //else ..case Reply of
      end;  //case Reply of
    end; //else..if IsGopherPlus then
  finally
    Disconnect;
  end; {try .. finally .. end }
end;

{ TIdGopherMenu }

function TIdGopherMenu.Add: TIdGopherMenuItem;
begin
  Result := TIdGopherMenuItem ( inherited Add );
end;

constructor TIdGopherMenu.Create;
begin
  inherited Create ( TIdGopherMenuItem );
end;

function TIdGopherMenu.GetItem(Index: Integer): TIdGopherMenuItem;
begin
  result := TIdGopherMenuItem( inherited Items [ index ] );
end;

procedure TIdGopherMenu.SetItem( Index: Integer;
  const Value: TIdGopherMenuItem );
begin
  inherited SetItem ( Index, Value );
end;

{ TIdGopherMenuItem }

constructor TIdGopherMenuItem.Create(ACollection: TCollection);
begin
  inherited;
  FGopherBlock := TIdHeaderList.Create;
  FGopherBlock.Sorted := False;
  FGopherBlock.Duplicates := dupAccept;
  {we don't unfold or fold lines as headers in that block start with a space}    {Do not Localize}
  FGopherBlock.UnfoldLines := False;
  FGopherBlock.FoldLines := False;
  FViews := TStringList.Create;
  FAbstract := TStringList.Create;
  FAsk := TIdHeaderList.Create;
  fAdminEmail := TIdEMailAddressItem.Create ( nil );
  FAbstract.Sorted := False;
end;

destructor TIdGopherMenuItem.Destroy;
begin
  FreeAndNil ( fAdminEmail );
  FreeAndNil ( FAsk );
  FreeAndNil ( FAbstract );
  FreeAndNil ( FGopherBlock );
  FreeAndNil ( FViews );
  inherited;
end;

procedure TIdGopherMenuItem.DoneSettingInfoBlock;
{These constants are for blocks we wish to obtain - don't change as they are   
 part of Gopher+ protocol}
const
  BlockTypes : Array [1..3] of String = ('+VIEWS', '+ABSTRACT', '+ASK');    {Do not Localize}
var
  idx : Integer;
  line : String;

    Procedure ParseBlock ( Block : TStringList);
    {Put our the sublock in the Block TStrings and increment
    the pointer appropriatriately}
    begin
      Inc ( idx );
      while ( idx < FGopherBlock.Count ) and
        ( FGopherBlock [ idx ] [ 1 ] = ' ' ) do    {Do not Localize}
      begin
         Block.Add ( TrimLeft ( FGopherBlock [ idx ] ) );
         Inc ( idx );
      end;  //while
      {correct for incrementation in the main while loop}
      Dec ( idx );
    end;

begin
  idx := 0;
  while ( idx < FGopherBlock.Count ) do
  begin
    Line := FGopherBlock [ idx ];
    Line := UpperCase ( Fetch( Line, ':' ) );    {Do not Localize}
    case PosInStrArray ( Line, BlockTypes ) of
      {+VIEWS:}
      0 : ParseBlock ( FViews );
      {+ABSTRACT:}
      1 : ParseBlock ( FAbstract );
      {+ASK:}
      2 : ParseBlock ( FAsk );
    end; //case PosInStrArray ( Line, BlockTypes ) of
    Inc ( idx );
  end;  //while ( idx < FGopherBlock.Count ) do
  fAdminEmail.Text := FGopherBlock.Values [ ' Admin' ];    {Do not Localize}
end;

function TIdGopherMenuItem.GetGeog: String;
begin
  Result := FGopherBlock.Values [ ' Geog' ];    {Do not Localize}
end;

function TIdGopherMenuItem.GetLastModified: String;
begin
  Result := FGopherBlock.Values [ ' Mod-Date' ];    {Do not Localize}
end;

function TIdGopherMenuItem.GetLocation: String;
begin
  Result := FGopherBlock.Values [ ' Loc' ];    {Do not Localize}
end;

function TIdGopherMenuItem.GetOrganization: String;
begin
  Result := FGopherBlock.Values [ ' Org' ];    {Do not Localize}
end;

end.
