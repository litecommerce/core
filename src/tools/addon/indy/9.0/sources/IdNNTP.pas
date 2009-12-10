{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10271: IdNNTP.pas 
{
{   Rev 1.0    2002.11.12 10:47:18 PM  czhower
}
unit IdNNTP;

interface

uses
  Classes,
  IdAssignedNumbers,
  IdException,
  IdGlobal,
  IdMessage, IdMessageClient,
  IdTCPServer, IdTCPConnection;

{
  2001-Dec - Chad Z. Hower a.k.a. Kudzu
    -Continued modifications
  2001-Oct - Chad Z. Hower a.k.a. Kudzu
    -Massive reworking to fit the Indy 9 model and update a lot of outdated code
     that was left over from Delphi 4 days. Updates now use overloaded functins. There were also
     several problems with message number accounting.
  2000-Jun-23 J. Peter Mugaas
    -GetNewGroupsList, GetNewGroupsList, and GetNewNewsList No longer require
     an Event handler if you provide a TStrings to those procedures
    -ParseXOVER was added so that you could parse XOVER data
    -ParseNewsGroup was ripped from GetNewGroupsList so that newsgroups can
     be parsed while not downloading newsgroups
    -Moved some duplicate code into a separate procedure
    -The IdNNTP now uses the Indy exceptions and IdResourceStrings to facilitate
     internationalization
  2000-Apr=28 Mark L. Holmes
    -Ported to Indy
  2000-Apr-28
    -Final Version
  1999-Dec-29 MTL
    -Moved to new Palette Scheme (Winshoes Servers)
  Ammended and modified by: AHeid, Mark Holmes
  Original Author: Chad Z. Hower a.k.a. Kudzu
}

type
  // Most users of this component should use "mtReader"
  TModeType = (mtStream, mtIHAVE, mtReader);
  TIdNNTPPermission = (crCanPost, crNoPost, crAuthRequired, crTempUnavailable);
  TModeSetResult = (mrCanStream, mrNoStream, mrCanIHAVE, mrNoIHAVE, mrCanPost, mrNoPost);
  TEventStreaming = procedure (const AMesgID: string; var AAccepted: Boolean)of object;
  TNewsTransportEvent = procedure (AMsg: TStringList) of object;
  TEventNewsgroupList = procedure(const ANewsgroup: string; const ALow, AHigh: Integer;
		const AType: string; var ACanContinue: Boolean) of object;

  TEventNewNewsList = procedure(const AMsgID: string; var ACanContinue: Boolean) of object;

  TIdNNTP = class(TIdMessageClient)
  protected
    FlMsgHigh: Integer;
    FlMsgLow: Integer;
    FlMsgCount: Integer;
    FNewsAgent: string;
    FOnNewsgroupList,
    FOnNewGroupsList: TEventNewsgroupList;
    FOnNewNewsList: TEventNewNewsList;
    FModeType: TModeType;
    FModeResult: TModeSetResult;
    FPermission: TIdNNTPPermission;
    //
    function ConvertDateTimeDist(ADate: TDateTime; AGMT: boolean;
     const ADistributions: string): string;
    procedure ProcessGroupList(const ACmd: string; const AResponse: integer;
     const AListEvent: TEventNewsgroupList);
  public
    procedure Check(AMsgIDs: TStringList; var AResponses: TStringList);
    procedure Connect(const ATimeout: Integer = IdTimeoutDefault); override;
    constructor Create(AOwner: TComponent); override;
    procedure Disconnect; override;
    function GetArticle(AMsg: TIdMessage): Boolean; overload;
    function GetArticle(const AMsgNo: Integer; AMsg: TIdMessage): Boolean; overload;
    function GetArticle(const AMsgID: string; AMsg: TIdMessage): Boolean; overload;
    function GetArticle(AMsg: TStrings): Boolean; overload;
    function GetArticle(const AMsgNo: Integer; AMsg: TStrings): Boolean; overload;
    function GetArticle(const AMsgID: string; AMsg: TStrings): Boolean; overload;
    function GetArticle(AMsg: TStream): Boolean; overload;
    function GetArticle(const AMsgNo: Integer; AMsg: TStream): Boolean; overload;
    function GetArticle(const AMsgID: string; AMsg: TStream): Boolean; overload;
    function GetBody(AMsg: TIdMessage): Boolean; overload;
    function GetBody(const AMsgNo: Integer; AMsg: TIdMessage): Boolean; overload;
    function GetBody(const AMsgID: string; AMsg: TIdMessage): Boolean; overload;
    function GetBody(AMsg: TStrings): Boolean; overload;
    function GetBody(const AMsgNo: Integer; AMsg: TStrings): Boolean; overload;
    function GetBody(const AMsgID: string; AMsg: TStrings): Boolean; overload;
    function GetBody(AMsg: TStream): Boolean; overload;
    function GetBody(const AMsgNo: Integer; AMsg: TStream): Boolean; overload;
    function GetBody(const AMsgID: string; AMsg: TStream): Boolean; overload;
    function GetHeader(AMsg: TIdMessage): Boolean; overload;
    function GetHeader(const AMsgNo: Integer; AMsg: TIdMessage): Boolean; overload;
    function GetHeader(const AMsgID: string; AMsg: TIdMessage): Boolean; overload;
    function GetHeader(AMsg: TStrings): Boolean; overload;
    function GetHeader(const AMsgNo: Integer; AMsg: TStrings): Boolean; overload;
    function GetHeader(const AMsgID: string; AMsg: TStrings): Boolean; overload;
    function GetHeader(AMsg: TStream): Boolean; overload;
    function GetHeader(const AMsgNo: Integer; AMsg: TStream): Boolean; overload;
    function GetHeader(const AMsgID: string; AMsg: TStream): Boolean; overload;
    procedure GetNewsgroupList; overload;
    procedure GetNewsgroupList(AList: TStrings); overload;
    procedure GetNewsgroupList(AStream: TStream); overload;
    procedure GetNewGroupsList(const ADate: TDateTime; const AGMT: boolean;
     const ADistributions: string); overload;
    procedure GetNewGroupsList(const ADate: TDateTime; const AGMT: boolean;
     const ADistributions: string; AList : TStrings); overload;
    procedure GetNewNewsList(const ANewsgroups: string;
      const ADate: TDateTime; const AGMT: boolean; ADistributions: string); overload;
    procedure GetNewNewsList(const ANewsgroups: string; const ADate: TDateTime;
      const AGMT: boolean; ADistributions: string; AList : TStrings); overload;
    procedure GetOverviewFMT(var AResponse: TStringList);
    procedure IHAVE(AMsg: TStringList);
    function Next: Boolean;
    function Previous: Boolean;
    procedure ParseXOVER(Aline: String; var AArticleIndex : Integer; var ASubject,
     AFrom : String; var ADate : TDateTime; var AMsgId, AReferences : String; var AByteCount,
     ALineCount : Integer; var AExtraData : String);
    procedure ParseNewsGroup(ALine : String; var ANewsGroup : String; var AHi, ALo : Integer;
     var AStatus : String);
    procedure Post(AMsg: TIdMessage); overload;
    procedure Post(AStream: TStream); overload;
    function SendCmd(const AOut: string; const AResponse: Array of SmallInt): SmallInt; override;
    function SelectArticle(const AMsgNo: Integer): Boolean;
    procedure SelectGroup(const AGroup: string);
    function TakeThis(const AMsgID: string; AMsg: TStream): string;
    procedure XHDR(const AHeader: string; const AParam: string; AResponse: TStrings);
    procedure XOVER(const AParam: string; AResponse: TStrings); overload;
    procedure XOVER(const AParam: string; AResponse: TStream); overload;
    //
    property ModeResult: TModeSetResult read FModeResult write FModeResult;
    property MsgCount: Integer read flMsgCount;
    property MsgHigh: Integer read FlMsgHigh;
    property MsgLow: Integer read FlMsgLow;
    property Permission: TIdNNTPPermission read FPermission;
  published
    property NewsAgent: string read FNewsAgent write FNewsAgent;
    property Mode: TModeType read FModeType write FModeType default mtReader;
    property Password;
    property Username;
    property OnNewsgroupList: TEventNewsgroupList read FOnNewsgroupList write FOnNewsgroupList;
    property OnNewGroupsList: TEventNewsGroupList read FOnNewGroupsList write FOnNewGroupsList;
    property OnNewNewsList: TEventNewNewsList read FOnNewNewsList write FOnNewNewsList;
    property Port default IdPORT_NNTP;
  end;

  EIdNNTPException = class(EIdException);
  EIdNNTPNoOnNewGroupsList = class(EIdNNTPException);
  EIdNNTPNoOnNewNewsList = class(EIdNNTPException);
  EIdNNTPNoOnNewsgroupList = class(EIdNNTPException);
  EIdNNTPStringListNotInitialized = class(EIdNNTPException);
  EIdNNTPConnectionRefused = class (EIdProtocolReplyError);

implementation

uses
  IdComponent,
  IdResourceStrings,
  SysUtils;

Procedure TIdNNTP.ParseXOVER(Aline : String; var AArticleIndex : Integer;
  var ASubject,
      AFrom : String;
  var ADate : TDateTime;
  var AMsgId,
      AReferences : String;
  var AByteCount,
      ALineCount : Integer;
  var AExtraData : String);

begin
  {Strip backspace and tab junk sequences which occur after a tab separator so they don't throw off any code}
  ALine := StringReplace(ALine,#9#8#9,#9,[rfReplaceAll]);
  {Article Index}
  AArticleIndex := StrToCard ( Fetch( ALine, #9 ) );
  {Subject}
  ASubject := Fetch ( ALine, #9 );
  {From}
  AFrom := Fetch ( ALine, #9 );
  {Date}
  ADate := GMTToLocalDateTime ( Fetch ( Aline, #9 ) );
  {Message ID}
  AMsgId := Fetch ( Aline, #9 );
  {References}
  AReferences := Fetch( ALine, #9);
  {Byte Count}
  AByteCount := StrToCard(Fetch(ALine,#9));
  {Line Count}
  ALineCount := StrToCard(Fetch(ALine,#9));
  {Extra data}
  AExtraData := ALine;
end;

Procedure TIdNNTP.ParseNewsGroup(ALine : String; var ANewsGroup : String;
            var AHi, ALo : Integer;
            var AStatus : String);
begin
  ANewsgroup := Fetch(ALine, ' ');
  AHi := StrToCard(Fetch(Aline, ' '));
  ALo := StrToCard(Fetch(ALine, ' '));
  AStatus := ALine;
end;

constructor TIdNNTP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Mode := mtReader;
  Port := IdPORT_NNTP;
end;

function TIdNNTP.SendCmd(const AOut: string; const AResponse: Array of SmallInt): SmallInt;
begin
  // NOTE: Responses must be passed as arrays so that the proper inherited SendCmd is called
  // and a stack overflow is not caused.
  Result := inherited SendCmd(AOut, []);
  if (Result = 480) or (Result = 450) then begin
    inherited SendCmd('AUTHINFO USER ' + Username, 381);
    inherited SendCmd('AUTHINFO PASS ' + Password, 281);
    Result := inherited SendCmd(AOut, AResponse);
  end else begin
    CheckResponse(Result, AResponse);
  end;
end;

procedure TIdNNTP.Connect(const ATimeout: Integer = IdTimeoutDefault);
begin
  inherited;
  try
    GetResponse([]);
    // Here lets check to see what condition we are in after being greeted by
    // the server. The application utilizing NNTPWinshoe should check the value
    // of GreetingResult to determine if further action is warranted.

    case LastCmdResult.NumericCode of
      200: FPermission := crCanPost;
      201: FPermission := crNoPost;
      400: FPermission := crTempUnavailable;
      // This should never happen because the server should immediately close
      // the connection but just in case ....
      // Kudzu: Changed this to an exception, otherwise it produces non-standard usage by the
      // users code
      502: raise EIdNNTPConnectionRefused.CreateError(502, RSNNTPConnectionRefused);
    end;
    // here we call Setmode on the value stored in mode to make sure we can
    // use the mode we have selected
    case Mode of
      mtStream: begin
        SendCmd('MODE STREAM');
        if LastCmdResult.NumericCode <> 203 then begin
          ModeResult := mrNoStream
        end else begin
          ModeResult := mrCanStream;
        end;
      end;
      mtReader: begin
        // We should get the same info we got in the greeting
        // result but we set mode to reader anyway since the
        // server may want to do some internal reconfiguration
        // if it knows that a reader has connected
        SendCmd('MODE READER');
        if LastCmdResult.NumericCode <> 200 then begin
          ModeResult := mrNoPost;
        end else begin
          ModeResult := mrCanPost;
        end;
      end;
    end;
  except
    Disconnect;
    Raise;
  end;
end;

procedure TIdNNTP.Disconnect;
begin
  try
    if Connected then begin
      WriteLn('Quit');
    end;
  finally
    inherited;
  end;
end;

{ This procedure gets the overview format as suported by the server }
procedure TIdNNTP.GetOverviewFMT(var AResponse: TStringList);
begin
  SendCmd('LIST OVERVIEW.FMT', 215);
  Capture(AResponse);
end;

{ Send the XOVER Command.  XOVER [Range]
  Range can be of the form: Article Number i.e. 1
                            Article Number followed by a dash
                            Article Number followed by a dash and aother number
  Remember to select a group first and to issue a GetOverviewFMT so that you
  can interpret the information sent by the server corectly. }
procedure TIdNNTP.XOVER(const AParam: string; AResponse: TStrings);
begin
  SendCmd('XOVER ' + AParam, 224);
  Capture(AResponse);
end;

procedure TIdNNTP.XOVER(const AParam: string; AResponse: TStream);
begin
  SendCmd('XOVER ' + AParam, 224);
  Capture(AResponse);
end;

{ Send the XHDR Command.  XHDR Header (Range | Message-ID)
  Range can be of the form: Article Number i.e. 1
                            Article Number followed by a dash
                            Article Number followed by a dash and aother number
  Parm is either the Range or the MessageID of the articles you want. They
  are Mutually Exclusive}
procedure TIdNNTP.XHDR(const AHeader: string; const AParam: String; AResponse: TStrings);
begin
  { This method will send the XHDR command.
  The programmer is responsible for choosing the correct header. Headers
  that should always work as per RFC 1036 are:

      From
      Date
      Newsgroups
      Subject
      Message-ID
      Path

    These Headers may work... They are optional per RFC1036 and new headers can
    be added at any time as server implementation changes

      Reply-To
      Sender
      Followup-To
      Expires
      References
      Control
      Distribution
      Organization
      Keywords
      Summary
      Approved
      Lines
      Xref
    }
  SendCmd('XHDR ' + AHeader + ' ' + AParam, 221);
  Capture(AResponse);
end;

procedure TIdNNTP.SelectGroup(const AGroup: string);
var
  s: string;
begin
  SendCmd('Group ' + AGroup, [211]);
  s := LastCmdResult.Text[0];
  FlMsgCount := StrToCard(Fetch(s));
  FlMsgLow := StrToCard(Fetch(s));
  FlMsgHigh := StrToCard(Fetch(s));
end;

{ This method will send messages via the IHAVE command.
The IHAVE command first sends the message ID and waits for a response from the
server prior to sending the header and body. This command is of no practical
use for NNTP client readers as readers are generally denied the privelege
to execute the IHAVE command. this is a news transport command. So use this
when you are implementing a NNTP server send unit }

procedure TIdNNTP.IHAVE(AMsg: TStringList);
var
  i     : Integer;
  MsgID : string;
begin
//TODO: Im not sure this fucntion works properly - needs checked
// Why is it not using a TIdMessage?
  // Since we are merely forwarding messages we have already received
  // it is assumed that the required header fields and body are already in place

  // We need to get the message ID from the stringlist because it's required
  // that we send it s part of the IHAVE command
  for i := 0 to AMsg.Count - 1 do
    if IndyPos('Message-ID',AMsg.Strings[i]) > 0 then begin
      MsgID := AMsg.Strings[i];
      Fetch(MsgID,':');
      Break;
    end;
  SendCmd('IHAVE ' + MsgID, 335);
  WriteRFCStrings(AMsg);
  // Why is the response ignored? What is it?
  Readln;
end;

(*
1.1.1  The CHECK command

   CHECK <message-id>

   CHECK is used by a peer to discover if the article with the specified
   message-id should be sent to the server using the TAKETHIS command.
   The peer does not have to wait for a response from the server before
   sending the next command.

   From using the responses to the sequence of CHECK commands, a list of
   articles to be sent can be constructed for subsequent use by the
   TAKETHIS command.

   The use of the CHECK command for streaming is optional.  Some
   implementations will directly use the TAKETHIS command and send all
   articles in the send queue on that peer for the server.

   On some implementations, the use of the CHECK command is not
   permitted when the server is in slave mode (via the SLAVE command).

   Responses that are of the form X3X must specify the message-id in the
   response.

1.1.2.  Responses

      238 no such article found, please send it to me
      400 not accepting articles
      431 try sending it again later
      438 already have it, please don't send it to me
      480 Transfer permission denied
      500 Command not understood
*)
procedure TIdNNTP.Check(AMsgIDs: TStringList; var AResponses: TStringList);
var
  i: Integer;
begin
  if not Assigned(AResponses) then begin
    raise EIdNNTPStringListNotInitialized.Create(RSNNTPStringListNotInitialized);
  end;
  for i := 0 to AMsgIDs.Count - 1 do begin
    WriteLn('CHECK '+ AMsgIDs.Strings[i]);
  end;
  for i := 0 to AMsgIDs.Count - 1 do begin
    AResponses.Add(ReadLn)
  end;
end;

(*
1.3.1  The TAKETHIS command

   TAKETHIS <message-id>

   TAKETHIS is used to send articles to a server when in streaming mode.
   The entire article (header and body, in that sequence) is sent
   immediately after the peer sends the TAKETHIS command.  The peer does
   not have to wait for a response from the server before sending the
   next command and the associated article.

   During transmission of the article, the peer should send the entire
   article, including header and body, in the manner specified for text
   transmission from the server.  See RFC 977, Section 2.4.1 for
   details.

   Responses that are of the form X3X must specify the message-id in the
   response.

1.3.2.  Responses

      239 article transferred ok
      400 not accepting articles
      439 article transfer failed
      480 Transfer permission denied
      500 Command not understood
*)
function TIdNNTP.TakeThis(const AMsgID: string; AMsg: TStream): string;
// This message assumes AMsg is "raw" and has already taken care of . to ..
begin
  SendCmd('TAKETHIS ' + AMsgID, 239);
  WriteStream(AMsg);
  WriteLn('.');
end;

(*
3.10.  The POST command

3.10.1.  POST

   POST

   If posting is allowed, response code 340 is returned to indicate that
   the article to be posted should be sent. Response code 440 indicates
   that posting is prohibited for some installation-dependent reason.

   If posting is permitted, the article should be presented in the
   format specified by RFC850, and should include all required header
   lines. After the article's header and body have been completely sent
   by the client to the server, a further response code will be returned
   to indicate success or failure of the posting attempt.

   The text forming the header and body of the message to be posted
   should be sent by the client using the conventions for text received
   from the news server:  A single period (".") on a line indicates the
   end of the text, with lines starting with a period in the original
   text having that period doubled during transmission.

   No attempt shall be made by the server to filter characters, fold or
   limit lines, or otherwise process incoming text.  It is our intent
   that the server just pass the incoming message to be posted to the
   server installation's news posting software, which is separate from
   this specification.  See RFC850 for more details.

   Since most installations will want the client news program to allow
   the user to prepare his message using some sort of text editor, and
   transmit it to the server for posting only after it is composed, the
   client program should take note of the herald message that greeted it
   when the connection was first established. This message indicates
   whether postings from that client are permitted or not, and can be
   used to caution the user that his access is read-only if that is the
   case. This will prevent the user from wasting a good deal of time
   composing a message only to find posting of the message was denied.
   The method and determination of which clients and hosts may post is
   installation dependent and is not covered by this specification.

3.10.2.  Responses

   240 article posted ok
   340 send article to be posted. End with <CR-LF>.<CR-LF>
   440 posting not allowed
   441 posting failed

   (for reference, one of the following codes will be sent upon initial
   connection; the client program should determine whether posting is
   generally permitted from these:) 200 server ready - posting allowed
   201 server ready - no posting allowed
*)
procedure TIdNNTP.Post(AMsg: TIdMessage);
begin
  SendCmd('POST', 340);
  //Header
  if Length(NewsAgent) > 0 then begin
    AMsg.ExtraHeaders.Values['X-Newsreader'] := NewsAgent;
  end;
  SendMsg(AMsg);
  SendCmd('.', 240);
end;

procedure TIdNNTP.Post(AStream: TStream);
begin
  SendCmd('POST', 340);
  WriteStream(AStream);
  GetResponse(240);
end;

procedure TIdNNTP.ProcessGroupList(const ACmd: string; const AResponse: integer;
 const AListEvent: TEventNewsgroupList);
var
  s1, sNewsgroup: string;
  lLo, lHi: Integer;
  sStatus: string;
  LCanContinue: Boolean;
begin
  BeginWork(wmRead, 0); try
    SendCmd(ACmd, AResponse);
    s1 := ReadLn;
    LCanContinue := True;
    while (s1 <> '.') and LCanContinue do
    begin
      ParseNewsGroup(s1, sNewsgroup, lHi, lLo, sStatus);
      AListEvent(sNewsgroup, lLo, lHi, sStatus, LCanContinue);
      s1 := ReadLn;
    end;
  finally
    EndWork(wmRead);
  end;
end;

procedure TIdNNTP.GetNewsgroupList;
begin
  if not Assigned(FOnNewsgroupList) then begin
    raise EIdNNTPNoOnNewsgroupList.Create(RSNNTPNoOnNewsgroupList);
  end;
  ProcessGroupList('LIST', 215, FOnNewsgroupList);
end;

procedure TIdNNTP.GetNewGroupsList(const ADate: TDateTime; const AGMT: boolean;
 const ADistributions: string);
begin
  if not Assigned(FOnNewGroupsList) then begin
    raise EIdNNTPNoOnNewGroupsList.Create(RSNNTPNoOnNewGroupsList);
  end;
  ProcessGroupList('NEWGROUPS ' + ConvertDateTimeDist(ADate, AGMT, ADistributions), 231
   , FOnNewGroupsList);
end;

procedure TIdNNTP.GetNewNewsList(const ANewsgroups: string;
 const ADate: TDateTime; const AGMT: boolean; ADistributions: string);
var
  s1: string;
  CanContinue: Boolean;
begin
  if not Assigned(FOnNewNewsList) then begin
    raise EIdNNTPNoOnNewNewsList.Create(RSNNTPNoOnNewNewsList);
  end;

  BeginWork(wmRead,0); try
    SendCmd('NEWNEWS ' + ANewsgroups + ' ' + ConvertDateTimeDist(ADate, AGMT, ADistributions), 230);
    s1 := ReadLn;
    CanContinue := True;
    while (s1 <> '.') and CanContinue do begin
      FOnNewNewsList(s1, CanContinue);
      s1 := ReadLn;
    end;
  finally
    EndWork(wmRead);
  end;
end;

(*
3.9.  The NEXT command

3.9.1.  NEXT

   NEXT

   The internally maintained "current article pointer" is advanced to
   the next article in the current newsgroup.  If no more articles
   remain in the current group, an error message is returned and the
   current article remains selected.

   The internally-maintained "current article pointer" is set by this
   command.

   A response indicating the current article number, and the message-id
   string will be returned.  No text is sent in response to this
   command.

3.9.2.  Responses

   223 n a article retrieved - request text separately
           (n = article number, a = unique article id)
   412 no newsgroup selected
   420 no current article has been selected
   421 no next article in this group
*)
function TIdNNTP.Next: Boolean;
begin
  Result := SendCmd('NEXT', [223, 421]) = 223;
end;

(*
3.5.  The LAST command

3.5.1.  LAST

   LAST

   The internally maintained "current article pointer" is set to the
   previous article in the current newsgroup.  If already positioned at
   the first article of the newsgroup, an error message is returned and
   the current article remains selected.

   The internally-maintained "current article pointer" is set by this
   command.

   A response indicating the current article number, and a message-id
   string will be returned.  No text is sent in response to this
   command.

3.5.2.  Responses

   223 n a article retrieved - request text separately
           (n = article number, a = unique article id)
   412 no newsgroup selected
   420 no current article has been selected
   422 no previous article in this group
*)
function TIdNNTP.Previous: Boolean;
begin
  Result := SendCmd('LAST', [223, 422]) = 223;
end;

function TIdNNTP.SelectArticle(const AMsgNo: Integer): Boolean;
begin
  Result := SendCmd('STAT ' + IntToStr(AMsgNo), [223, 423]) = 223;
end;

procedure TIdNNTP.GetNewsgroupList(AList: TStrings);
begin
  SendCmd('LIST', 215);
  Capture(AList);
end;

procedure TIdNNTP.GetNewGroupsList(const ADate: TDateTime; const AGMT: boolean;
 const ADistributions: string; AList: TStrings);
begin
  SendCmd('NEWGROUPS ' + ConvertDateTimeDist(ADate, AGMT, ADistributions), 231);
  Capture(AList);
end;

procedure TIdNNTP.GetNewNewsList(const ANewsgroups: string; const ADate: TDateTime;
 const AGMT: boolean; ADistributions: string; AList: TStrings);
begin
  SendCmd('NEWNEWS ' + ANewsgroups + ' ' + ConvertDateTimeDist(ADate, AGMT, ADistributions), 230);
  Capture(AList);
end;

function TIdNNTP.ConvertDateTimeDist(ADate: TDateTime; AGMT: boolean;
 const ADistributions: string): string;
begin
  Result := FormatDateTime('yymmdd hhnnss', ADate);
  if AGMT then begin
    Result:= Result + ' GMT';
  end;
  if Length(ADistributions) > 0 then begin
    Result := ' <' + ADistributions + '>';
  end;
end;

(*
3.1.  The ARTICLE, BODY, HEAD, and STAT commands

   There are two forms to the ARTICLE command (and the related BODY,
   HEAD, and STAT commands), each using a different method of specifying
   which article is to be retrieved.  When the ARTICLE command is
   followed by a message-id in angle brackets ("<" and ">"), the first
   form of the command is used; when a numeric parameter or no parameter
   is supplied, the second form is invoked.

   The text of the article is returned as a textual response, as
   described earlier in this document.

   The HEAD and BODY commands are identical to the ARTICLE command
   except that they respectively return only the header lines or text
   body of the article.

   The STAT command is similar to the ARTICLE command except that no
   text is returned.  When selecting by message number within a group,
   the STAT command serves to set the current article pointer without
   sending text. The returned acknowledgement response will contain the
   message-id, which may be of some value.  Using the STAT command to
   select by message-id is valid but of questionable value, since a
   selection by message-id does NOT alter the "current article pointer".

3.1.1.  ARTICLE (selection by message-id)

   ARTICLE <message-id>

   Display the header, a blank line, then the body (text) of the
   specified article.  Message-id is the message id of an article as
   shown in that article's header.  It is anticipated that the client
   will obtain the message-id from a list provided by the NEWNEWS
   command, from references contained within another article, or from
   the message-id provided in the response to some other commands.

   Please note that the internally-maintained "current article pointer"
   is NOT ALTERED by this command. This is both to facilitate the
   presentation of articles that may be referenced within an article
   being read, and because of the semantic difficulties of determining
   the proper sequence and membership of an article which may have been
   posted to more than one newsgroup.

3.1.2.  ARTICLE (selection by number)

   ARTICLE [nnn]

   Displays the header, a blank line, then the body (text) of the
   current or specified article.  The optional parameter nnn is the

   numeric id of an article in the current newsgroup and must be chosen
   from the range of articles provided when the newsgroup was selected.
   If it is omitted, the current article is assumed.

   The internally-maintained "current article pointer" is set by this
   command if a valid article number is specified.

   [the following applies to both forms of the article command.] A
   response indicating the current article number, a message-id string,
   and that text is to follow will be returned.

   The message-id string returned is an identification string contained
   within angle brackets ("<" and ">"), which is derived from the header
   of the article itself.  The Message-ID header line (required by
   RFC850) from the article must be used to supply this information. If
   the message-id header line is missing from the article, a single
   digit "0" (zero) should be supplied within the angle brackets.

   Since the message-id field is unique with each article, it may be
   used by a news reading program to skip duplicate displays of articles
   that have been posted more than once, or to more than one newsgroup.

3.1.3.  Responses

   220 n <a> article retrieved - head and body follow
           (n = article number, <a> = message-id)
   221 n <a> article retrieved - head follows
   222 n <a> article retrieved - body follows
   223 n <a> article retrieved - request text separately
   412 no newsgroup has been selected
   420 no current article has been selected
   423 no such article number in this group
   430 no such article found
*)
function TIdNNTP.GetArticle(AMsg: TIdMessage): Boolean;
begin
  Result := True;
  SendCmd('ARTICLE', 220);
  AMsg.Clear;
  ReceiveHeader(AMsg);
  ReceiveBody(AMsg);
end;

function TIdNNTP.GetArticle(const AMsgNo: Integer; AMsg: TIdMessage): Boolean;
begin
  Result := SendCmd('ARTICLE ' + IntToStr(AMsgNo), [220, 423]) = 220;
  if Result then begin
    AMsg.Clear;
    ReceiveHeader(AMsg);
    ReceiveBody(AMsg);
  end;
end;

function TIdNNTP.GetArticle(const AMsgID: string; AMsg: TIdMessage): Boolean;
begin
  Result := SendCmd('ARTICLE <' + AMsgID + '>', [220, 430]) = 220;
  if Result then begin
    AMsg.Clear;
    ReceiveHeader(AMsg);
    ReceiveBody(AMsg);
  end;
end;

function TIdNNTP.GetArticle(AMsg: TStrings): Boolean;
begin
  Result := True;
  SendCmd('ARTICLE', 220);
  AMsg.Clear;
  Capture(AMsg);
end;

function TIdNNTP.GetArticle(const AMsgNo: Integer; AMsg: TStrings): Boolean;
begin
  Result := SendCmd('ARTICLE ' + IntToStr(AMsgNo), [220, 423]) = 220;
  if Result then begin
    AMsg.Clear;
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetArticle(const AMsgID: string; AMsg: TStrings): Boolean;
begin
  Result := SendCmd('ARTICLE <' + AMsgID + '>', [220, 430]) = 220;
  if Result then begin
    AMsg.Clear;
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetArticle(AMsg: TStream): Boolean;
begin
  Result := True;
  SendCmd('ARTICLE', 220);
  Capture(AMsg);
end;

function TIdNNTP.GetArticle(const AMsgNo: Integer; AMsg: TStream): Boolean;
begin
  Result := SendCmd('ARTICLE ' + IntToStr(AMsgNo), [220, 423]) = 220;
  if Result then begin
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetArticle(const AMsgID: string; AMsg: TStream): Boolean;
begin
  Result := SendCmd('ARTICLE <' + AMsgID + '>', [220, 430]) = 220;
  if Result then begin
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetBody(AMsg: TIdMessage): Boolean;
begin
  Result := True;
  if Result then begin
    SendCmd('BODY', 222);
    AMsg.Clear;
    ReceiveBody(AMsg);
  end;
end;

function TIdNNTP.GetBody(const AMsgNo: Integer; AMsg: TIdMessage): Boolean;
begin
  Result := SendCmd('BODY ' + IntToStr(AMsgNo), [222, 423]) = 222;
  if Result then begin
    AMsg.Clear;
    ReceiveBody(AMsg);
  end;
end;

function TIdNNTP.GetBody(const AMsgID: string; AMsg: TIdMessage): Boolean;
begin
  Result := SendCmd('BODY <' + AMsgID + '>', [222, 430]) = 222;
  if Result then begin
    AMsg.Clear;
    ReceiveBody(AMsg);
  end;
end;

function TIdNNTP.GetBody(AMsg: TStrings): Boolean;
begin
  Result := True;
  SendCmd('BODY', 222);
  AMsg.Clear;
  Capture(AMsg);
end;

function TIdNNTP.GetBody(const AMsgNo: Integer; AMsg: TStrings): Boolean;
begin
  Result := SendCmd('BODY ' + IntToStr(AMsgNo), [222, 423]) = 222;
  if Result then begin
    AMsg.Clear;
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetBody(const AMsgID: string; AMsg: TStrings): Boolean;
begin
  Result := SendCmd('BODY <' + AMsgID + '>', [222, 430]) = 222;
  if Result then begin
    AMsg.Clear;
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetBody(AMsg: TStream): Boolean;
begin
  Result := True;
  SendCmd('BODY', 222);
  Capture(AMsg);
end;

function TIdNNTP.GetBody(const AMsgNo: Integer; AMsg: TStream): Boolean;
begin
  Result := SendCmd('BODY ' + IntToStr(AMsgNo), [222, 423]) = 222;
  if Result then begin
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetBody(const AMsgID: string; AMsg: TStream): Boolean;
begin
  Result := SendCmd('BODY <' + AMsgID + '>', [222, 430]) = 222;
  if Result then begin
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetHeader(AMsg: TIdMessage): Boolean;
begin
  Result := True;
  SendCmd('HEAD', 221);
  AMsg.Clear;
  ReceiveHeader(AMsg);
end;

function TIdNNTP.GetHeader(const AMsgNo: Integer; AMsg: TIdMessage): Boolean;
begin
  Result := SendCmd('HEAD ' + IntToStr(AMsgNo), [221, 423]) = 221;
  if Result then begin
    AMsg.Clear;
    ReceiveHeader(AMsg);
  end;
end;

function TIdNNTP.GetHeader(const AMsgID: string; AMsg: TIdMessage): Boolean;
begin
  Result := SendCmd('HEAD <' + AMsgID + '>', [221, 430]) = 221;
  if Result then begin
    AMsg.Clear;
    ReceiveHeader(AMsg);
  end;
end;

function TIdNNTP.GetHeader(AMsg: TStrings): Boolean;
begin
  Result := True;
  SendCmd('HEAD', 221);
  AMsg.Clear;
  Capture(AMsg);
end;

function TIdNNTP.GetHeader(const AMsgNo: Integer; AMsg: TStrings): Boolean;
begin
  Result := SendCmd('HEAD ' + IntToStr(AMsgNo), [221, 423]) = 221;
  if Result then begin
    AMsg.Clear;
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetHeader(const AMsgID: string; AMsg: TStrings): Boolean;
begin
  Result := SendCmd('HEAD <' + AMsgID + '>', [221, 430]) = 221;
  if Result then begin
    AMsg.Clear;
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetHeader(AMsg: TStream): Boolean;
begin
  Result := True;
  SendCmd('HEAD', 221);
  Capture(AMsg);
end;

function TIdNNTP.GetHeader(const AMsgNo: Integer; AMsg: TStream): Boolean;
begin
  Result := SendCmd('HEAD ' + IntToStr(AMsgNo), [221, 423]) = 221;
  if Result then begin
    Capture(AMsg);
  end;
end;

function TIdNNTP.GetHeader(const AMsgID: string; AMsg: TStream): Boolean;
begin
  Result := SendCmd('HEAD <' + AMsgID + '>', [221, 430]) = 221;
  if Result then begin
    Capture(AMsg);
  end;
end;

procedure TIdNNTP.GetNewsgroupList(AStream: TStream);
begin
  SendCmd('LIST', 215);
  Capture(AStream);
end;

end.
