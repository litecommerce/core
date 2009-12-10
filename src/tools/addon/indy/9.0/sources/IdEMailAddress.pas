{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10153: IdEMailAddress.pas 
{
{   Rev 1.0    2002.11.12 10:37:40 PM  czhower
}
unit IdEMailAddress;

{
 ToDo: look into alterations required for TIdEMailAddressItem.GetText.
}
{
2001-Aug-30 - Jim Gunkel
  - Fixed bugs that would occur with group names containing spaces (box test 19)
    and content being located after the email address (box test 33)
2001-Jul-11 - Allen O'Neill
  - Added hack to not allow recipient entries being added that are blank
2001-Jul-11 - Allen O'Neill    
  - Added hack to accomodate a PERIOD (#46) in an email address - this whole area needs to be looked at.
2001-Feb-03 - Peter Mee
  - Overhauled TIdEMailAddressItem.GetText to support non-standard textual
    elements.
2001-Jan-29 - Peter Mee
  - Overhauled TIdEMailAddressList.SetEMailAddresses to support comments
    and escaped characters and to ignore groups.
2001-Jan-28 - Peter Mee
  - Overhauled TIdEMailAddressItem.SetText to support comments and escaped
    characters.
2000-Jun-10 - J. Peter Mugaas
  - started this unit to facilitate some Indy work including the
    TIdEMailAddressItem and TIdEMailAddressList classes
  - The GetText and SetText were originally the ToArpa and FromArpa functions in
    the TIdMessage component}

interface

uses
  Classes,
  IdException;

type
   EIdEmailParseError = class(EIdException);

   TIdEMailAddressItem = class (TCollectionItem)
   protected
     FAddress : String;
     FName : String;
     Function GetText : String;
     Procedure SetText(AText : String);
     function ConvertAddress : String;
   public
     procedure Assign(Source: TPersistent); override;
   published
     {This is the E-Mail address itself }
     property Address: string read FAddress write FAddress;
     {This is the person's name}    {Do not Localize}
     property Name: string read FName write FName;
     {This is the combined person's name and E-Mail address}    {Do not Localize}
     property Text: String read GetText write SetText;
   end;

   TIdEMailAddressList = class (TOwnedCollection)
   protected
     function GetItem ( Index: Integer ) : TIdEMailAddressItem;
     procedure SetItem ( Index: Integer; const Value: TIdEMailAddressItem );
     function GetEMailAddresses : String;
     procedure SetEMailAddresses( AList : String);
   public
     constructor Create ( AOwner : TPersistent ); reintroduce;

     {This returns formatted list of formated
     addresses including the names from the collection }
     procedure FillTStrings(AStrings : TStrings);
     function Add: TIdEMailAddressItem;
     property Items [ Index: Integer ] : TIdEMailAddressItem read GetItem write SetItem; default;
     {This is a comma separated list of formated
     addresses including the names from the collection }
     property EMailAddresses : String read GetEMailAddresses
       write SetEMailAddresses;
   end;

implementation

uses
  IdGlobal,
  IdResourceStrings,
  SysUtils;

const
  // This is actually the ATEXT without the '"' and space characters...    {Do not Localize}
  IETF_ATEXT: string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'   {Do not Localize}
   + '1234567890!#$%&''*+-/=?_`{}|~';    {Do not Localize}
  // ATEXT without the '"'    {Do not Localize}
  IETF_ATEXT_SPACE: string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'   {Do not Localize}
   + '1234567890!#$%&''*+-/=?_`{}|~ ';   {Do not Localize}
  IETF_QUOTABLE: string = '\"';  {Do not Localize}

// Three functions for easier manipulating of strings.
// Don't know of any system functions to perform these actions.    {Do not Localize}
// If there aren't & someone can find an optimised way of performing    {Do not Localize}
// then please implement...
function FindFirstOf(AFind, AText: string): Integer;
var
  nCount, nPos: Integer;
begin
  Result := 0;
  for nCount := 1 to Length(AFind) do begin
    nPos := IndyPos(AFind[nCount], AText);
    if nPos > 0 then begin
      if Result = 0 then begin
        Result := nPos;
      end else if Result > nPos then begin
        Result := nPos;
      end;
    end;
  end;
end;

function FindFirstNotOf(AFind, AText : String) : Integer;
var
  i : Integer;
begin
  result := 0;
  if length(AFind) = 0 then
  begin
    result := 1;
    exit;
  end;

  if length(AText) = 0 then
  begin
    exit;
  end;

  for i := 1 to length(AText) do
  begin
    if IndyPos(AText[i], AFind) = 0 then
    begin
      result := i;
      exit;
    end;
  end;
end;

function TrimAllOf(ATrim, AText : String) : String;
begin
  while Length(AText) > 0 do
  begin
    if Pos(AText[1], ATrim) > 0 then
    begin
      System.Delete(AText, 1, 1);
    end else break;
  end;
  while Length(AText) > 0 do begin
    if Pos(AText[length(AText)], ATrim) > 0 then
    begin
      System.Delete(AText, Length(AText), 1);
    end else break;
  end;
  result := AText;
end;

{ TIdEMailAddressItem }

procedure TIdEMailAddressItem.Assign(Source: TPersistent);
var Addr : TIdEMailAddressItem;
begin
  if ClassType <> Source.ClassType then
  begin
    inherited
  end
  else
  begin
    Addr := TIdEMailAddressItem(Source);
    Address := Addr.Address;
    Name := Addr.Name;
  end;
end;

function TIdEMailAddressItem.ConvertAddress : String;
var
  i : Integer;
  domainPart, tempAddress, localPart : String;
begin
  if length(FAddress) = 0 then
  begin
    if Length(FName) > 0 then
    begin
      result := '<>';   {Do not Localize}
    end else
    begin
      result := ''; {Do not Localize}
    end;
    exit;
  end;

  // First work backwards to the @ sign.
  for i := length(FAddress) downto 1 do
  begin
    if FAddress[i] = '@' then  {Do not Localize}
    begin
      domainPart := Copy(FAddress, i, length(FAddress));
      tempAddress := Copy(FAddress, 1, i - 1);
      break;
    end;
  end;

  i := FindFirstNotOf(IETF_ATEXT, tempAddress);
  if (i = 0) or (tempAddress[i] = #46) then //hack to accomodate periods in emailaddress
//  if i = 0 then
  begin
    if length(FName) > 0 then
    begin
      result := '<' + tempAddress + domainPart + '>';   {Do not Localize}
    end else
    begin
      result := tempAddress + domainPart;
    end;
  end else
  begin
    localPart := '"';      {Do not Localize}
    while i > 0 do
    begin
      localPart := localPart + Copy(tempAddress, 1, i - 1);
      if IndyPos(tempAddress[i], IETF_QUOTABLE) > 0 then
      begin
        localPart := localPart + '\';   {Do not Localize}
      end;
      localPart := localPart + tempAddress[i];
      tempAddress := Copy(tempAddress, i + 1, length(tempAddress));
      i := FindFirstNotOf(IETF_ATEXT, tempAddress);
    end;
    result := '<' + localPart + tempAddress + '"' + domainPart + '>';   {Do not Localize}
  end;
end;

function TIdEMailAddressItem.GetText: String;
var
  i : Integer;
  tempName, resName : String;
begin
  if ( Length ( FName ) > 0 ) and ( UpperCase ( FAddress ) <> FName ) then
  begin
    i := FindFirstNotOf(IETF_ATEXT_SPACE, FName);
    if i > 0 then
    begin
      // Need to quote the FName.
      resName := '"' + Copy(FName, 1, i - 1);    {Do not Localize}
      if IndyPos(FName[i], IETF_QUOTABLE) > 0 then
      begin
        resName := resName + '\';   {Do not Localize}
      end;
      resName := resName + FName[i];

      tempName := Copy(FName, i + 1, length(FName));
      while length(tempName) <> 0 do
      begin
        i := FindFirstNotOf(IETF_ATEXT_SPACE, tempName);
        if i = 0 then
        begin
          Result := resName + tempName + '" ' + ConvertAddress;  {Do not Localize}
          exit;
        end;
        resName := resName + Copy(tempName, 1, i-1);
        if IndyPos(tempName[i], IETF_QUOTABLE) > 0 then
        begin
          resName := resName + '\';     {Do not Localize}
        end;
        resName := resName + tempName[i];
        tempName := Copy(tempName, i + 1, length(tempName));
      end;
      Result := resName + '" ' + ConvertAddress;   {Do not Localize}
    end else
    begin
      Result := FName + ' ' + ConvertAddress;  {Do not Localize}
    end;
  end //  if
  else
  begin
    Result := ConvertAddress;
  end; // else .. if
end;

procedure TIdEMailAddressItem.SetText(AText: String);
var
  nFirst,
  nBracketCount : Integer;
  bInAddress,
  bAddressInLT,
  bAfterAt,
  bInQuote : Boolean;
begin
  FAddress := '';    {Do not Localize}
  FName := '';        {Do not Localize}

  AText := Trim(AText);
  if Length(AText) = 0 then exit;

  // Find the first known character type.
  nFirst := FindFirstOf('("< @' + TAB, AText);   {Do not Localize}
  if nFirst <> 0 then
  begin
    nBracketCount := 0;
    bInAddress := False;
    bAddressInLT := False;
    bInQuote := False;
    bAfterAt := False;
    repeat
      case AText[nFirst] of
        ' ', TAB :             {Do not Localize}
        begin
          if nFirst = 1 then
          begin
            System.Delete(AText, 1, 1);
          end else
          begin
            // Only valid if in a name not contained in quotes - keep the space.
            if bAfterAt then begin
              FAddress := FAddress + Trim(Copy(AText, 1, nFirst - 1));
            end else begin
              FName := FName + Copy(AText, 1, nFirst);
            end;
            AText := Copy(AText, nFirst + 1, Length(AText));
          end;
        end;
        '(' :                                {Do not Localize}
        begin
          Inc(nBracketCount);
          if (nFirst > 1) then
          begin
            // There's at least one character to the name    {Do not Localize}
            if bInAddress then
            begin
              FAddress := FAddress + Trim(Copy(AText, 1, nFirst - 1));
            end else
            begin
              if nBracketCount = 1 then
              begin
                FName := FName + Copy(AText, 1, nFirst - 1);
              end;
            end;
            AText := Copy(AText, nFirst + 1, length(AText));
          end else
          begin
            System.Delete(AText, 1, 1);
          end;
        end;
        ')' :                                {Do not Localize}
        begin
          Dec(nBracketCount);
          AText := Copy(AText, nFirst + 1, Length(AText));
        end;
        '"' :                              {Do not Localize}
        begin
          if bInQuote then
          begin
            if bAddressInLT then
            begin
              FAddress := FAddress + Trim(Copy(AText, 1, nFirst - 1));
            end else
            begin
              FName := FName + Trim(Copy(AText, 1, nFirst - 1));
            end;
            AText := Copy(AText, nFirst + 1, length(AText));
            bInQuote := False;
          end else
          begin
            bInQuote := True;
            System.Delete(AText, 1, 1);
          end;
        end;
        '<' :                             {Do not Localize}
        begin
          if nFirst > 1 then
          begin
            FName := FName + Copy(AText,1,nFirst - 1);
          end;
          FName := TrimAllOf(' ' + TAB, Trim(FName));    {Do not Localize}
          bAddressInLT := True;
          bInAddress := True;
          System.Delete(AText, 1, nFirst);
        end;
        '>' :                            {Do not Localize}
        begin
          // Only searched for if the address starts with '<'    {Do not Localize}
          bInAddress := False;
          bAfterAt := False;
          FAddress := FAddress +
            TrimAllOf(' ' + TAB, Trim(Copy(AText, 1, nFirst -1)));  {Do not Localize}
          AText := Copy(AText, nFirst + 1, length(AText));
        end;
        '@' :                 {Do not Localize}
        begin
          bAfterAt := True;
          if bInAddress then
          begin
            FAddress := FAddress + Copy(AText, 1, nFirst);
            AText := Copy(AText, nFirst + 1, Length(AText));
          end else
          begin
            if bAddressInLT then
            begin
              // Strange use. For now raise an exception until a real-world
              // example can be found.
              // Basically, it's formatted as follows:    {Do not Localize}
              //    <someguy@domain.example> some-text @ some-text
              // or:
              //    some-text <someguy@domain.example> some-text @ some-text
              // where some text may be blank.
              raise EIdEmailParseError.Create(RSEMailSymbolOutsideAddress);
            end else
            begin
              // If at this point, we're either supporting an e-mail address    {Do not Localize}
              // on it's own, or the old-style valid format:    {Do not Localize}
              //    "Name" name@domain.example
              bInAddress := true;
              FAddress := FAddress + Copy(AText, 1, nFirst);
              AText := Copy(AText, nFirst + 1, length(AText));
            end;
          end;
        end;
        '.' :                {Do not Localize}
        begin
          // Must now be a part of the domain part of the address.
          if bAddressInLT then
          begin
            // Whitespace is possible around the parts of the domain.
            FAddress := FAddress +
              TrimAllOf(' ' + TAB, Trim(Copy(AText, 1, nFirst - 1))) + '.'; {Do not Localize}
            AText := TrimLeft(Copy(AText, nFirst + 1, length(AText)));
          end else
          begin
            // No whitespace is allowed if no wrapping <> characters.
            FAddress := FAddress + Copy(AText, 1, nFirst);
            AText := Copy(AText, nFirst + 1, length(AText));
          end;
        end;
        '\' :                   {Do not Localize}
        begin
          // This will only be discovered in a bracketted or quoted section.
          // It's an escape character indicating the next cahracter is    {Do not Localize}
          // a literal.
          if bInQuote then
          begin
            // Need to retain the second character
            if bInAddress then
            begin
              FAddress := FAddress + Copy(AText, 1, nFirst - 1);
              FAddress := FAddress + AText[nFirst + 1];
            end else
            begin
              FName := FName + Copy(AText, 1, nFirst - 1);
              FName := FName + AText[nFirst + 1];
            end;
          end;
          AText := Copy(AText, nFirst + 2, length(AText));
        end;
      end;


      // Check for bracketted sections first: ("<>" <> "" <"">) - all is ignored
      if nBracketCount > 0 then
      begin
        // Inside a bracket, only three charatcers are special.
        // '(' Opens a nested bracket: (One (Two (Three )))    {Do not Localize}
        // ')' Closes a bracket    {Do not Localize}
        // '/' Escape character: (One /) /( // (Two /) ))    {Do not Localize}
        nFirst := FindFirstOf('()\', AText);     {Do not Localize}

      // Check if in quote before address: <"My Name"@domain.example> is valid
      end else if bInQuote then
      begin
      // Inside quotes, only the end quote and escape character are special.
        nFirst := FindFirstOf('"\', AText);   {Do not Localize}

      // Check if after the @ of the address: domain.example>
      end else if bAfterAt then
      begin
        if bAddressInLT then
        begin
          // If the address is enclosed, then only the '(', '.' & '>' need be    {Do not Localize}
          // looked for, trimming all content when found: domain  .  example >
          nFirst := FindFirstOf('.>(', AText);       {Do not Localize}
        end else
        begin
          nFirst := FindFirstOf('.( ', AText);  {Do not Localize}
        end;

      // Check if in address: <name@domain.example>
      end else if bInAddress then
      begin
        nFirst := FindFirstOf('"(@>', AText);   {Do not Localize}

      // Not in anything - check for opening charactere
      end else
      begin
        // Outside brackets
        nFirst := FindFirstOf('("< @' + TAB, AText);    {Do not Localize}
      end;
    until nFirst = 0;
    if bInAddress and not bAddressInLT then
    begin
      FAddress := FAddress + TrimAllOf(' ' + TAB, Trim(AText));   {Do not Localize}
    end;
  end else
  begin
    // No special characters, so assume a simple address
    FAddress := AText;
  end;
end;



{ TIdEMailAddressList }

function TIdEMailAddressList.Add: TIdEMailAddressItem;
begin
  Result := TIdEMailAddressItem ( inherited Add );
end;

constructor TIdEMailAddressList.Create(AOwner: TPersistent);
begin
  inherited Create(AOwner, TIdEMailAddressItem);
end;

procedure TIdEMailAddressList.FillTStrings(AStrings: TStrings);
var idx : Integer;
begin
  idx := 0;
  while ( idx < Count ) do
  begin
    AStrings.Add ( GetItem ( idx ).Text );
    Inc ( idx );
  end; // while ( idx < Count ) do
end;

function TIdEMailAddressList.GetItem(Index: Integer): TIdEMailAddressItem;
begin
  Result := TIdEMailAddressItem ( inherited Items [ Index ] );
end;

function TIdEMailAddressList.GetEMailAddresses: String;
var idx : Integer;
begin
  Result := '';   {Do not Localize}
  idx := 0;
  while ( idx < Count ) do
  begin
    Result := Result + ', ' + GetItem ( idx ).Text;   {Do not Localize}
    Inc ( idx );
  end; // while ( idx < Count ) do
  {Remove the first comma and the following space ', ' }    {Do not Localize}
  System.Delete ( Result, 1, 2 );
end;

procedure TIdEMailAddressList.SetItem(Index: Integer;
  const Value: TIdEMailAddressItem);
begin
  inherited SetItem(Index, Value );
end;

procedure TIdEMailAddressList.SetEMailAddresses(AList: String);
var
  EMail : TIdEMailAddressItem;
  iStart : integer ;
  sTemp : string ;
  nInBracket : Integer;
  bInQuote : Boolean;
begin
  Clear;

  if (trim(Alist) = '') then exit;   {Do not Localize}

  iStart := FindFirstOf(':;(", ' + TAB, AList); {Do not Localize}
  if iStart = 0 then begin
    EMail := Add;
    EMail.Text := TrimLeft(AList);
  end else begin
    sTemp := '';                   {Do not Localize}
    nInBracket := 0;
    bInQuote := False;
    repeat
      case AList[iStart] of
        ' ', TAB: begin                 {Do not Localize}
          if iStart = 1 then begin
            sTemp := sTemp + AList[iStart];
            System.Delete(AList, 1, 1);
          end else begin
            sTemp := sTemp + Copy(AList, 1, iStart);
            AList := Copy(AList, iStart + 1, Length(AList));
          end;
        end;
        ':' :                           {Do not Localize}
        begin
          // The start of a group - ignore the lot.
          AList := Copy(AList, iStart + 1, Length(AList));
          sTemp := '';                 {Do not Localize}
        end;
        ';' :                          {Do not Localize}
        begin
          // End of a group.  If we have something (groups can be empty),
          // then process it.
          sTemp := sTemp + Copy(AList, 1, iStart - 1);
          if Length(Trim(sTemp)) > 0 then begin
            EMail := Add;
            EMail.Text := TrimLeft(sTemp);
            sTemp := '';                     {Do not Localize}
          end;
          // Now simply remove the end of the group.
          AList := Copy(AList, iStart + 1, length(AList));
        end;
        '(': begin                     {Do not Localize}
          Inc(nInBracket);
          sTemp := sTemp + Copy(AList, 1, iStart);
          AList := Copy(AList, iStart + 1, length(AList));
        end;
        ')': begin                     {Do not Localize}
          Dec(nInBracket);
          sTemp := sTemp + Copy(AList, 1, iStart);
          AList := Copy(AList, iStart + 1, length(AList));
        end;
        '"': begin                     {Do not Localize}
          sTemp := sTemp + Copy(AList, 1, iStart);
          AList := Copy(AList, iStart + 1, Length(AList));
          bInQuote := not bInQuote;
        end;
        ',': begin                       {Do not Localize}
          sTemp := sTemp + Copy(AList, 1, iStart - 1);
          EMail := Add;
          EMail.Text := sTemp;
          // added - Allen .. saves blank entries being added
          if (trim(Email.Text) = '') or (trim(Email.Text) = '<>') then   {Do not Localize}
            begin
            FreeAndNil(Email);
            end;
          sTemp := '';    {Do not Localize}
          AList := Copy(AList, iStart + 1, length(AList));
        end;
        '\': begin                                     {Do not Localize}
          // Escape character - simply copy this char and the next to the buffer.
          sTemp := sTemp + Copy(AList, 1, iStart + 1);
          AList := Copy(AList, iStart + 2, length(AList));
        end;
      end;

      if nInBracket > 0 then begin
        iStart := FindFirstOf('(\)', AList);    {Do not Localize}
      end else if bInQuote then begin
        iStart := FindFirstOf('"\', AList);    {Do not Localize}
      end else begin
        iStart := FindFirstOf(':;(", ' + TAB, AList);  {Do not Localize}
      end;
    until iStart = 0;

    // Clean up the content in sTemp
    if (Length(Trim(sTemp)) > 0) or (Length(Trim(AList)) > 0) then begin
      sTemp := sTemp + AList;
      EMail := Add;
      EMail.Text := TrimLeft(sTemp);
      // added - Allen .. saves blank entries being added
      if (trim(Email.Text) = '') or (trim(Email.Text) = '<>') then   {Do not Localize}
      begin
        FreeAndNil(Email);
      end;
    end;
  end;
end;

end.
