{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10273: IdNNTPServer.pas 
{
{   Rev 1.2    3/17/2003 08:54:04 AM  JPMugaas
{ Missing reply texts.
}
{
{   Rev 1.1    2002.11.15 10:44:50 PM  czhower
{ Fixed some issues with authentication.
}
{
{   Rev 1.0    2002.11.12 10:47:24 PM  czhower
}
unit IdNNTPServer;

interface
   
{
Sept 2002
  - Colin Wilson - fixes.

  - GROUP sets the current article pointer to the first message in the group.
  - LAST & NEXT now return '412 no newsgroup has been selected' if no newsgroup
    has been selected
  - LAST & NEXT now return the correct article number as well as the correct
    message ID
  - NEXT now goes to the next article instead of the previous one
  - LAST now returns '422 no previous article in this group' if there's no
    previous article
  - NEXT now returns '421 no next article in this group' is there's no next
    article.

  - ARTICLE, HEAD, BODY & STAT now use the same 'LookupMessage' code.

    - If the current group isn't set '412 no newsgroup has been selected' is
      returned
    - If there is no parameter the current article pointer is used.  If this has
      not been set, '420 no current article has been selected' is returned
    - If the parameter is numeric, the ID is looked up using OnCheckMsgNo.  If
      this fails, the '423 no such article number in this group' is returned
    - If the parameter starts with '<' the article number is looked up using the
      (new) OnCheckMsgId event.  If this fails, or the parameter didn't start
      with '<' '430 no such article found' is returned.

    - HEAD, BODY & STAT now return the appropriate success codes (221, 222, 223)
      instead of the generic '220'
    - STAT has been brought into line with ARTICLE, HEAD & BODY - the OnStatMsgNo
      callback is now called once the 'success' code has been sent.  I've left the
      parameters as 'var' even though they should be 'const' for compatibilty with
      previous versions.

    - New 'OnAuthRequired' event allows the user to select whether a particular
      command/parameter combination needs authentication.
    - Authentication accepted now returns '281 authentication accepted' rather
      than just a bald '281'
    - Password required now returns '381 more authentication information required'
      rather than just '381'


July 2002
  -Kudzu - Fixes to Authorization and other parts
Oct/Nov 2001
  -Kudzu - Rebuild from scratch for proper use of command handlers and around new
  architecture.
2001-Jul-31 Jim Gunkel
  Reorganized for command handlers
2001-Jun-28 Pete Mee
  Begun transformation to TIdCommandHandler
2000-Apr-22 Mark L. Holmes
  Ported to Indy
2000-Mar-27
  Final Version
2000-Jan-13 MTL
  Moved to new Palette Scheme (Winshoes Servers)
Original Author: Ozz Nixon (Winshoes 7)
}

uses
  Classes,
  IdAssignedNumbers, IdGlobal,
  IdTCPServer;

(*
 For more information on NNTP visit http://www.faqs.org/rfcs/

 RFC 977 - A Proposed Standard for the Stream-Based Transmission of News
 RFC 2980 - Common NNTP Extensions
 RFC 1036 - Standard for Interchange of USENET Messages
 RFC 822 - Standard for the Format of ARPA Internet Text
*)

(*
Responses

   100 help text follows
   199 debug output

   200 server ready - posting allowed
   201 server ready - no posting allowed
   202 slave status noted
   205 closing connection - goodbye!
   211 n f l s group selected
   215 list of newsgroups follows
   220 n <a> article retrieved - head and body follow 221 n <a> article
   retrieved - head follows
   222 n <a> article retrieved - body follows
   223 n <a> article retrieved - request text separately 230 list of new
   articles by message-id follows
   231 list of new newsgroups follows
   235 article transferred ok
   240 article posted ok
   281 Authentication accepted



   335 send article to be transferred.  End with <CR-LF>.<CR-LF>
   340 send article to be posted. End with <CR-LF>.<CR-LF>
   381 More authentication information required
   400 service discontinued
   411 no such news group
   412 no newsgroup has been selected
   420 no current article has been selected
   421 no next article in this group
   422 no previous article in this group
   423 no such article number in this group
   430 no such article found
   435 article not wanted - do not send it
   436 transfer failed - try again later
   437 article rejected - do not try again.
   440 posting not allowed
   441 posting failed
   480 Authentication required
   482 Authentication rejected
   500 command not recognized
   501 command syntax error
   502 access restriction or permission denied
   503 program fault - command not performed
*)

type
  TIdNNTPThread = class(TIdPeerThread)
  protected
    FCurrentArticle: Integer;
    FCurrentGroup: string;
    FUserName: string;
    FPassword: string;
    FAuthenticated : Boolean;
    FModeReader: Boolean;
  public
    constructor Create(ACreateSuspended: Boolean = True); override;
    //
    property CurrentArticle: Integer read FCurrentArticle;
    property CurrentGroup: string read FCurrentGroup;
    property ModeReader: Boolean read FModeReader;
    property UserName: string read FUserName;
    property Password: string read FPassword;
    property Authenticated: Boolean read FAuthenticated;
  end;

  TIdNNTPOnAuth = procedure(AThread: TIdNNTPThread; var VAccept: Boolean) of object;
  TIdNNTPOnNewGroupsList = procedure ( AThread: TIdNNTPThread; const ADateStamp : TDateTime; const ADistributions : String) of object;
  TIdNNTPOnNewNews = procedure ( AThread: TIdNNTPThread; const Newsgroups : String; const ADateStamp : TDateTime; const ADistributions : String) of object;
  TIdNNTPOnIHaveCheck = procedure(AThread: TIdNNTPThread; const AMsgID : String; VAccept : Boolean) of object;
  TIdNNTPOnArticleByNo = procedure(AThread: TIdNNTPThread; const AMsgNo: Integer) of object;
  TIdNNTPOnArticleByID = procedure(AThread: TIdNNTPThread; const AMsgID: string) of object;
  TIdNNTPOnCheckMsgNo = procedure(AThread: TIdNNTPThread; const AMsgNo: Integer;var VMsgID: string) of object;
  TIdNNTPOnCheckMsgID = procedure(AThread: TIdNNTPThread; const AMsgId : string; var VMsgNo : Integer) of object;
  //this has to be a separate event type in case a NNTP client selects a message
  //by Message ID instead of Index number.  If that happens, the user has to
  //to return the index number.  NNTP Clients setting STAT by Message ID is not
  //a good idea but is valid.
  TIdNNTPOnMovePointer = procedure(AThread: TIdNNTPThread; var AMsgNo: Integer;
   var VMsgID: string) of object;
  TIdNNTPOnPost = procedure(AThread: TIdNNTPThread; var VPostOk: Boolean;
   var VErrorText: string) of object;
  TIdNNTPOnSelectGroup = procedure(AThread: TIdNNTPThread; const AGroup: string;
   var VMsgCount: Integer; var VMsgFirst: Integer; var VMsgLast: Integer;
   var VGroupExists: Boolean) of object;
  TIdNNTPOnCheckListGroup = procedure(AThread: TIdNNTPThread; const AGroup: string;
   var VCanJoin : Boolean; var VFirstArticle : Integer) of object;
  TIdNNTPOnXOver = procedure(AThread: TIdNNTPThread; const AMsgFirst: Integer;
   const AMsgLast: Integer) of object;
  TIdNNTPOnXHdr = procedure(AThread: TIdNNTPThread; const AHeaderName : String; const AMsgFirst: Integer;
   const AMsgLast: Integer) of object;
  TIdNNTPOnAuthRequired = procedure(AThread: TIdNNTPThread; const ACommand, AParams : string; var VRequired: Boolean) of object;

  TIdNNTPServer = class(TIdTCPServer)
  private
  protected
    FHelp: TStrings;
    FOverviewFormat: TStrings;
    FOnArticleByNo: TIdNNTPOnArticleByNo;
    FOnBodyByNo: TIdNNTPOnArticleByNo;
    FOnHeadByNo: TIdNNTPOnArticleByNo;
    FOnCheckMsgNo: TIdNNTPOnCheckMsgNo;
    FOnCheckMsgId: TidNNTPOnCheckMsgId;
    FOnStatMsgNo : TIdNNTPOnMovePointer;
    FOnNextArticle : TIdNNTPOnMovePointer;
    FOnPrevArticle : TIdNNTPOnMovePointer;
    //LISTGROUP events - Gravity uses these
    FOnCheckListGroup : TIdNNTPOnCheckListGroup;
    FOnListGroup : TIdServerThreadEvent;

    FOnListGroups: TIdServerThreadEvent;
    FOnListNewGroups : TIdNNTPOnNewGroupsList;
    FOnPost: TIdNNTPOnPost;
    FOnSelectGroup: TIdNNTPOnSelectGroup;
    FOnXOver: TIdNNTPOnXOver;
    FOnXHdr: TIdNNTPOnXHdr;
    FOnNewNews : TIdNNTPOnNewNews;
    FOnIHaveCheck : TIdNNTPOnIHaveCheck;
    FOnIHavePost: TIdNNTPOnPost;
    FOnAuth: TIdNNTPOnAuth;
    FOnAuthRequired: TIdNNTPOnAuthRequired;

    function AuthRequired(ASender: TIdCommand): Boolean;
    //return MsgID - AThread.CurrentArticlePointer already set
    function RawNavigate(AThread: TIdNNTPThread; AEvent : TIdNNTPOnMovePointer) : String;
    procedure CommandArticle(ASender: TIdCommand);
    procedure CommandAuthInfoUser(ASender: TIdCommand);
    procedure CommandAuthInfoPassword(ASender: TIdCommand);
    procedure CommandBody(ASender: TIdCommand);
    procedure CommandDate(ASender: TIdCommand);
    procedure CommandHead(ASender: TIdCommand);
    procedure CommandGroup(ASender: TIdCommand);
    procedure CommandIHave(ASender: TIdCommand);
    procedure CommandLast(ASender: TIdCommand);
    procedure CommandList(ASender: TIdCommand);
    procedure CommandListGroup(ASender: TIdCommand);
    procedure CommandModeReader(ASender: TIdCommand);
    procedure CommandNewGroups(ASender: TIdCommand);
    procedure CommandNewNews(ASender: TIdCommand);
    procedure CommandNext(ASender: TIdCommand);
    procedure CommandPost(ASender: TIdCommand);
    procedure CommandSlave(ASender: TIdCommand);
    procedure CommandStat(ASender: TIdCommand);
    procedure CommandXHdr(ASender: TIdCommand);
    procedure CommandXOver(ASender: TIdCommand);
    procedure DoListGroups(AThread: TIdNNTPThread);
    procedure DoSelectGroup(AThread: TIdNNTPThread; const AGroup: string; var VMsgCount: Integer;
     var VMsgFirst: Integer; var VMsgLast: Integer; var VGroupExists: Boolean);
    procedure InitializeCommandHandlers; override;
    procedure SetHelp(AValue: TStrings);
    procedure SetOverviewFormat(AValue: TStrings);
    function LookupMessage (ASender : TidCommand; var VNo : Integer; var VId : string) : boolean;
  public
    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    class function NNTPTimeToTime(const ATimeStamp : String): TDateTime;
    class function NNTPDateTimeToDateTime(const ATimeStamp: string): TDateTime;
  published
    property DefaultPort default IdPORT_NNTP;
    property Help: TStrings read FHelp write SetHelp;
    property OnArticleByNo: TIdNNTPOnArticleByNo read FOnArticleByNo write FOnArticleByNo;
    property OnAuth: TIdNNTPOnAuth read FOnAuth write FOnAuth;
    property OnAuthRequired : TIdNNTPOnAuthRequired read FOnAuthRequired write FOnAuthRequired;
    property OnBodyByNo: TIdNNTPOnArticleByNo read FOnBodyByNo write FOnBodyByNo;
    property OnHeadByNo: TIdNNTPOnArticleByNo read FOnHeadByNo write FOnHeadByNo;
    property OnCheckMsgNo: TIdNNTPOnCheckMsgNo read FOnCheckMsgNo write FOnCheckMsgNo;
    property OnCheckMsgID: TidNNTPOnCheckMsgId read FOnCheckMsgId write FOnCheckMsgId;
    property OnStatMsgNo : TIdNNTPOnMovePointer read FOnStatMsgNo write FOnStatMsgNo;
    //You are responsible for writing event handlers for these instead of us incrementing
    //and decrimenting the pointer.  This design permits you to implement article expirity,
    //cancels, and supercedes
    property OnNextArticle : TIdNNTPOnMovePointer read FOnNextArticle write FOnNextArticle;
    property OnPrevArticle : TIdNNTPOnMovePointer read FOnPrevArticle write FOnPrevArticle;
    property OnCheckListGroup : TIdNNTPOnCheckListGroup read FOnCheckListGroup write FOnCheckListGroup;
    property OnListGroups: TIdServerThreadEvent read FOnListGroups write FOnListGroups;
    property OnListGroup : TIdServerThreadEvent read FOnListGroup write FOnListGroup;
    property OnListNewGroups : TIdNNTPOnNewGroupsList read FOnListNewGroups write FOnListNewGroups;
    property OnSelectGroup: TIdNNTPOnSelectGroup read FOnSelectGroup write FOnSelectGroup;
    property OnPost: TIdNNTPOnPost read FOnPost write FOnPost;
    property OnXOver: TIdNNTPOnXOver read FOnXOver write FOnXOver;
    property OverviewFormat: TStrings read FOverviewFormat write SetOverviewFormat;
    property OnXHdr: TIdNNTPOnXHdr read FOnXHdr write FOnXHdr;
    property OnNewNews : TIdNNTPOnNewNews read FOnNewNews write FOnNewNews;
    property OnIHaveCheck : TIdNNTPOnIHaveCheck read FOnIHaveCheck write FOnIHaveCheck;
    property OnIHavePost: TIdNNTPOnPost read FOnIHavePost write FOnIHavePost;
  end;

implementation

uses
  IdResourceStrings, IdRFCReply,
  SysUtils;

Const
  AuthTypes: array [1..2] of string = ('USER', 'PASS'); {Do not localize}

class function TIdNNTPServer.NNTPTimeToTime(const ATimeStamp : String): TDateTime;
var
  LHr, LMn, LSec : Word;
  LTimeStr : String;
begin
  LTimeStr := ATimeStamp;
  if LTimeStr <> '' then
  begin
    LHr := StrToIntDef(Copy(LTimeStr,1,2),1);
    Delete(LTimeStr,1,2);
    LMn := StrToIntDef(Copy(LTimeStr,1,2),1);
    Delete(LTimeStr,1,2);
    LSec := StrToIntDef(Copy(LTimeStr,1,2),1);
    Delete(LTimeStr,1,2);
    Result := EncodeTime(LHr, LMn, LSec,0);
    LTimeStr := Trim(LTimeStr);
    if UpperCase(LTimeStr)='GMT' then
    begin
      // Apply local offset
      Result := Result + OffSetFromUTC;
    end;
  end else begin
    Result := 0;
  end;
end;

class function TIdNNTPServer.NNTPDateTimeToDateTime(const ATimeStamp : String): TDateTime;
var
  LYr, LMo, LDay : Word;
    LTimeStr : String;
    LDateStr : String;
begin
  Result := 0;
  if ATimeStamp <> '' then begin
    LTimeStr := ATimeStamp;
    LDateStr := Fetch(LTimeStr);
    if (Length(LDateStr) > 6) then begin
      //four digit year, good idea - IMAO
      LYr := StrToIntDef(Copy(LDateStr,1,4),1969);
      Delete(LDateStr,1,4);
    end else begin
      LYr := StrToIntDef(Copy(LDateStr,1,2),69);
      Delete(LDateStr,1,2);
      LYr := LYr + 2000;
    end;
    LMo := StrToIntDef(Copy(LDateStr,1,2),1);
    Delete(LDateStr,1,2);
    LDay := StrToIntDef(Copy(LDateStr,1,2),1);
    Delete(LDateStr,1,2);
    Result := EncodeDate(LYr, LMo, LDay) + NNTPTimeToTime(LTimeStr);
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

procedure TIdNNTPServer.CommandArticle(ASender: TIdCommand);
var
  LMsgID: string;
  LMsgNo: Integer;
  LThread: TIdNNTPThread;
begin
  if not Assigned (OnArticleByNo) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if not AuthRequired (ASender) then
  begin
    if LookupMessage (ASender, LMsgNo, LMsgID) then
    begin
      LThread := TidNNTPThread (ASender.Thread);
      ASender.Reply.SetReply(220, IntToStr(LMsgNo) + ' ' + LMsgID + ' article retrieved - head and body follows');
      ASender.SendReply;
      OnArticleByNo(LThread, LMsgNo)
    end;
  end
end;


procedure TIdNNTPServer.CommandBody(ASender: TIdCommand);
var
  LMsgID: string;
  LMsgNo: Integer;
  LThread: TIdNNTPThread;
begin
  if not Assigned (OnBodyByNo) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if not AuthRequired (ASender) then
  begin
    if LookupMessage (ASender, LMsgNo, LMsgID) then
    begin
      LThread := TidNNTPThread (ASender.Thread);
      ASender.Reply.SetReply(222, IntToStr(LMsgNo) + ' ' + LMsgID + ' article retrieved - body follows');
      ASender.SendReply;
      OnBodyByNo(LThread, LMsgNo)
    end;
  end
end;

procedure TIdNNTPServer.CommandDate(ASender: TIdCommand);
begin
  ASender.Reply.SetReply(111,FormatDateTime('yyyymmddhhnnss',Now + IdGlobal.TimeZoneBias));
end;

(*
3.2.  The GROUP command

3.2.1.  GROUP

   GROUP ggg

   The required parameter ggg is the name of the newsgroup to be
   selected (e.g. "net.news").  A list of valid newsgroups may be
   obtained from the LIST command.

   The successful selection response will return the article numbers of
   the first and last articles in the group, and an estimate of the
   number of articles on file in the group.  It is not necessary that
   the estimate be correct, although that is helpful; it must only be
   equal to or larger than the actual number of articles on file.  (Some
   implementations will actually count the number of articles on file.
   Others will just subtract first article number from last to get an
   estimate.)

   When a valid group is selected by means of this command, the
   internally maintained "current article pointer" is set to the first
   article in the group.  If an invalid group is specified, the
   previously selected group and article remain selected.  If an empty
   newsgroup is selected, the "current article pointer" is in an
   indeterminate state and should not be used.

   Note that the name of the newsgroup is not case-dependent.  It must
   otherwise match a newsgroup obtained from the LIST command or an
   error will result.

3.2.2.  Responses

   211 n f l s group selected
           (n = estimated number of articles in group,
           f = first article number in the group,
           l = last article number in the group,
           s = name of the group.)
   411 no such news group
*)
procedure TIdNNTPServer.CommandGroup(ASender: TIdCommand);
var
  LGroup: string;
  LGroupExists: Boolean;
  LMsgCount: Integer;
  LMsgFirst: Integer;
  LMsgLast: Integer;
  LThread: TIdNNTPThread;
begin
  if not AuthRequired(ASender) then begin
    LGroup := Trim(ASender.UnparsedParams);
    LThread := TIdNNTPThread(ASender.Thread);
    DoSelectGroup(TIdNNTPThread(ASender.Thread), LGroup, LMsgCount, LMsgFirst, LMsgLast
     , LGroupExists);
    if LGroupExists then begin
      TIdNNTPThread(ASender.Thread).FCurrentGroup := LGroup;
      ASender.Reply.SetReply(211, Format('%d %d %d %s', [LMsgCount, LMsgFirst, LMsgLast, LGroup]));
      LThread.FCurrentArticle := LMsgFirst;
    end;
  end;
end;

procedure TIdNNTPServer.CommandHead(ASender: TIdCommand);
var
  LMsgID: string;
  LMsgNo: Integer;
  LThread: TIdNNTPThread;
begin
  if not Assigned (OnHeadByNo) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if not AuthRequired (ASender) then
  begin
    if LookupMessage (ASender, LMsgNo, LMsgID) then
    begin
      LThread := TidNNTPThread (ASender.Thread);
      ASender.Reply.SetReply (221, IntToStr(LMsgNo) + ' ' + LMsgID + ' article retrieved - head follows');
      ASender.SendReply;
      OnHeadByNo(LThread, LMsgNo)
    end;
  end;
end;

procedure TIdNNTPServer.CommandIHave(ASender: TIdCommand);
var LThread : TIdNNTPThread;
    LMsgID : String;
    LAccept:Boolean;
    LErrorText : String;
begin
  if not Assigned (OnIHaveCheck) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if AuthRequired(ASender) then begin
    Exit;
  end;
  LThread := TIdNNTPThread(ASender.Thread);
  LMsgID := Trim(ASender.UnparsedParams);
  if (Copy(LMsgID, 1, 1) = '<') then begin
    FOnIHaveCheck(LThread,LMsgID,LAccept);
    if LAccept then
    begin
      ASender.Reply.SetReply(335,'News to me!  <CRLF.CRLF> to end.');
      ASender.SendReply;
      LErrorText := '';
      OnPost(TIdNNTPThread(ASender.Thread), LAccept, LErrorText);
      ASender.Reply.SetReply(iif(LAccept, 235, 436), LErrorText);
    end
    else
    begin
      ASender.Reply.NumericCode := 435;
    end;
  end;
end;

procedure TIdNNTPServer.CommandLast(ASender: TIdCommand);
var
  LMsgNo: Integer;
  LThread: TIdNNTPThread;
  LMsgID : String;
begin
  if not Assigned (OnPrevArticle) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if Assigned(OnPrevArticle) then
  begin
    if not AuthRequired(ASender) then
    begin
      LThread := TIdNNTPThread(ASender.Thread);
      if Length(LThread.CurrentGroup) = 0 then
        ASender.Reply.NumericCode := 412
      else
      begin
        LMsgID := RawNavigate(LThread,OnPrevArticle);
        if LMsgID<>'' then
        begin
          LMsgNo := LThread.CurrentArticle;
          ASender.Reply.SetReply(223, IntToStr(LMsgNo) + ' ' + LMsgID + ' article retrieved - request text separately')
        end
        else
          ASender.Reply.NumericCode := 422;
      end;
    end;
  end;
end;

(*
3.6.  The LIST command

3.6.1.  LIST

   LIST

   Returns a list of valid newsgroups and associated information.  Each
   newsgroup is sent as a line of text in the following format:

      group last first p

   where <group> is the name of the newsgroup, <last> is the number of
   the last known article currently in that newsgroup, <first> is the
   number of the first article currently in the newsgroup, and <p> is
   either 'y' or 'n' indicating whether posting to this newsgroup is
   allowed ('y') or prohibited ('n').

   The <first> and <last> fields will always be numeric.  They may have
   leading zeros.  If the <last> field evaluates to less than the
   <first> field, there are no articles currently on file in the
   newsgroup.

   Note that posting may still be prohibited to a client even though the
   LIST command indicates that posting is permitted to a particular
   newsgroup. See the POST command for an explanation of client
   prohibitions.  The posting flag exists for each newsgroup because
   some newsgroups are moderated or are digests, and therefore cannot be
   posted to; that is, articles posted to them must be mailed to a
   moderator who will post them for the submitter.  This is independent
   of the posting permission granted to a client by the NNTP server.

   Please note that an empty list (i.e., the text body returned by this
   command consists only of the terminating period) is a possible valid
   response, and indicates that there are currently no valid newsgroups.

3.6.2.  Responses

   215 list of newsgroups follows
*)
procedure TIdNNTPServer.CommandList(ASender: TIdCommand);
begin
  if not Assigned(OnListGroups) then begin
    ASender.Reply.NumericCode := 500;
  end else if not AuthRequired(ASender) then begin
    ASender.SendReply;
    DoListGroups(TIdNNTPThread(ASender.Thread));
    ASender.Thread.Connection.WriteLn('.');
  end;
end;

procedure TIdNNTPServer.CommandListGroup(ASender: TIdCommand);
var LThrd : TIdNNTPThread;
    LGroup : String;
    LFirstIdx : Integer;
    LCanJoin : Boolean;
begin
  if not Assigned (OnCheckListGroup) or not Assigned (FOnListGroup) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if AuthRequired(ASender) then begin
    Exit;
  end;
  LThrd := TIdNNTPThread ( ASender.Thread );
  LGroup := Trim(ASender.UnparsedParams);
  if Length(LGroup)>0 then
  begin
    LGroup := LThrd.CurrentGroup;
  end;
  FOnCheckListGroup(LThrd,LGroup,LCanJoin,LFirstIdx);
  if LCanJoin then
  begin
    LThrd.FCurrentGroup := LGroup;
    LThrd.FCurrentArticle := LFirstIdx;
    ASender.SendReply;
    FOnListGroup(LThrd);
    ASender.Thread.Connection.WriteLn('.');
  end
  else
  begin
    ASender.Reply.SetReply(412,'Not currently in newsgroup');
  end;
end;

procedure TIdNNTPServer.CommandModeReader(ASender: TIdCommand);
(*
2.3 MODE READER

   MODE READER is used by the client to indicate to the server that it
   is a news reading client.  Some implementations make use of this
   information to reconfigure themselves for better performance in
   responding to news reader commands.  This command can be contrasted
   with the SLAVE command in RFC 977, which was not widely implemented.
   MODE READER was first available in INN.

2.3.1 Responses

      200 Hello, you can post
      201 Hello, you can't post
*)
begin
  TIdNNTPThread(ASender.Thread).FModeReader := True;
  ASender.Reply.NumericCode := 200;
end;

(*
3.7.  The NEWGROUPS command

3.7.1.  NEWGROUPS

   NEWGROUPS date time [GMT] [<distributions>]

   A list of newsgroups created since <date and time> will be listed in
   the same format as the LIST command.

   The date is sent as 6 digits in the format YYMMDD, where YY is the
   last two digits of the year, MM is the two digits of the month (with
   leading zero, if appropriate), and DD is the day of the month (with
   leading zero, if appropriate).  The closest century is assumed as
   part of the year (i.e., 86 specifies 1986, 30 specifies 2030, 99 is
   1999, 00 is 2000).

   Time must also be specified.  It must be as 6 digits HHMMSS with HH
   being hours on the 24-hour clock, MM minutes 00-59, and SS seconds
   00-59.  The time is assumed to be in the server's timezone unless the
   token "GMT" appears, in which case both time and date are evaluated
   at the 0 meridian.

   The optional parameter "distributions" is a list of distribution
   groups, enclosed in angle brackets.  If specified, the distribution
   portion of a new newsgroup (e.g, 'net' in 'net.wombat') will be
   examined for a match with the distribution categories listed, and
   only those new newsgroups which match will be listed.  If more than
   one distribution group is to be listed, they must be separated by
   commas within the angle brackets.

   Please note that an empty list (i.e., the text body returned by this
   command consists only of the terminating period) is a possible valid
   response, and indicates that there are currently no new newsgroups.

3.7.2.  Responses

   231 list of new newsgroups follows
*)
procedure TIdNNTPServer.CommandNewGroups(ASender: TIdCommand);
var LDate : TDateTime;
    LDist : String;
begin
  if not Assigned (OnListNewGroups) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if AuthRequired(ASender) then begin
    Exit;
  end;
  if (ASender.Params.Count > 1) then
  begin
    LDist := '';
    LDate := NNTPDateTimeToDateTime( ASender.Params[0] );
    LDate := LDate + NNTPTimeToTime( ASender.Params[1] );
    if ASender.Params.Count > 2 then
    begin
      if (UpperCase(ASender.Params[2]) = 'GMT') then {Do not translate}
      begin
        LDate := LDate + OffSetFromUTC;
        if (ASender.Params.Count > 3) then
        begin
          LDist := ASender.Params[3];
        end;
      end
      else
      begin
        LDist := ASender.Params[2];
      end;
    end;
    ASender.SendReply;
    FOnListNewGroups(TIdNNTPThread(ASender.Thread), LDate, LDist);
    ASender.Thread.Connection.WriteLn('.');
  end;
end;

procedure TIdNNTPServer.CommandNewNews(ASender: TIdCommand);
var LDate : TDateTime;
    LDist : String;
begin
  if not Assigned (OnNewNews) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if AuthRequired(ASender) then begin
    Exit;
  end;
  if (ASender.Params.Count > 3) then
  begin
    //0 - newsgroup
    //1 - date
    //2 - time
    //3 - GMT or distributions
    //4 - distributions if 3 was GMT
    LDist := '';
    LDate := NNTPDateTimeToDateTime( ASender.Params[1] );
    LDate := LDate + NNTPTimeToTime( ASender.Params[2] );
    if ASender.Params.Count > 4 then
    begin
      if (UpperCase(ASender.Params[3]) = 'GMT') then {Do not translate}
      begin
        LDate := LDate + OffSetFromUTC;
        if (ASender.Params.Count > 4) then
        begin
          LDist := ASender.Params[4];
        end;
      end
      else
      begin
        LDist := ASender.Params[3];
      end;
    end;
    ASender.SendReply;
    FOnNewNews( TIdNNTPThread(ASender.Thread), ASender.Params[0], LDate, LDist );
    ASender.Thread.Connection.WriteLn('.');
  end;
end;

procedure TIdNNTPServer.CommandNext(ASender: TIdCommand);
var
  LMsgNo: Integer;
  LThread: TIdNNTPThread;
  LMsgID : String;
begin
  if not Assigned (OnNextArticle) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if not AuthRequired(ASender) then
  begin
    LThread := TIdNNTPThread(ASender.Thread);
    if Length(LThread.CurrentGroup) = 0 then
      ASender.Reply.NumericCode := 412
    else
    begin
      LMsgID := RawNavigate(LThread,OnNextArticle);
      if LMsgID<>'' then
      begin
        LMsgNo := LThread.CurrentArticle;
        ASender.Reply.SetReply(223, IntToStr(LMsgNo) + ' ' + LMsgID + ' article retrieved - request text separately')
      end
      else
        ASender.Reply.NumericCode := 421;
    end;
  end
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
procedure TIdNNTPServer.CommandPost(ASender: TIdCommand);
var
  LCanPost: Boolean;
  LErrorText: string;
  LPostOk: Boolean;
  LReply: TIdRFCReply;
begin
  if AuthRequired(ASender) then begin
    Exit;
  end;
  LCanPost := Assigned(OnPost);
  LReply := TIdRFCReply.Create(nil);
  LReply.NumericCode := iif(LCanPost, 340, 440);
  ReplyTexts.UpdateText(LReply);
  ASender.Thread.Connection.WriteRFCReply(LReply);
  if LCanPost then begin
    LPostOk := False;
    LErrorText := '';
    OnPost(TIdNNTPThread(ASender.Thread), LPostOk, LErrorText);
    ASender.Reply.SetReply(iif(LPostOk, 240, 441), LErrorText);
  end;
end;

procedure TIdNNTPServer.CommandSlave(ASender: TIdCommand);
begin
  TIdNNTPThread(ASender.Thread).FModeReader := False;
  ASender.Reply.NumericCode := 220;
end;

procedure TIdNNTPServer.CommandStat(ASender: TIdCommand);
var
  LMsgID: string;
  LMsgNo: Integer;
  LThread: TIdNNTPThread;
begin
  if not AuthRequired (ASender) then
  begin
    if LookupMessage (ASender, LMsgNo, LMsgID) then
    begin
      LThread := TidNNTPThread (ASender.Thread);
      ASender.Reply.SetReply (223, IntToStr(LMsgNo) + ' ' + LMsgID + ' article retrieved - request text separately');
      ASender.SendReply;
      if Assigned (OnStatMsgNo) then
        OnStatMsgNo(LThread, LMsgNo, LMsgID)
    end;
  end;
end;

procedure TIdNNTPServer.CommandXHdr(ASender: TIdCommand);
var
  i: Integer;
  s: String;
  LFirstMsg: Integer;
  LLastMsg : Integer;
begin
  if not Assigned (OnXHdr) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if not AuthRequired(ASender) then begin
    if Length(TIdNNTPThread(ASender.Thread).CurrentGroup) = 0 then begin
      ASender.Reply.NumericCode := 412;
    end else begin
      if ASender.Params.Count > 0 then begin
        s := '';
        for i := 1 to ASender.Params.Count-1 do begin
          s := s + ASender.Params[i] + ' ';
        end;
        s := Trim(s);
        LFirstMsg := StrToIntDef(Trim(Fetch(s, '-')), 0);
        if LFirstMsg = 0 then begin
          LFirstMsg := TIdNNTPThread(ASender.Thread).CurrentArticle;
          LLastMsg := LFirstMsg;
        end else begin
          if Pos('-', ASender.UnparsedParams) > 0 then begin
            LLastMsg := StrToIntDef(Trim(s), 0);
          end else begin
            LLastMsg := LFirstMsg;
          end;
        end;
        if LFirstMsg = 0 then begin
          ASender.Reply.NumericCode := 420;
        end else begin
          ASender.Reply.NumericCode := 221;
          ASender.SendReply;
          // No need for DoOnXhdr - only this proc can call it and it already checks for nil
          FOnXhdr(TIdNNTPThread(ASender.Thread), ASender.Params[0], LFirstMsg, LLastMsg);
          ASender.Thread.Connection.WriteLn('.');
        end;
      end;
    end;
  end;
end;

(*
2.8 XOVER

   XOVER [range]

   The XOVER command returns information from the overview database for
   the article(s) specified.  This command was originally suggested as
   part of the OVERVIEW work described in "The Design of a Common
   Newsgroup Overview Database for Newsreaders" by Geoff Collyer.  This
   document is distributed in the Cnews distribution.  The optional
   range argument may be any of the following:

               an article number
               an article number followed by a dash to indicate
                  all following
               an article number followed by a dash followed by
                  another article number

   If no argument is specified, then information from the current
   article is displayed.  Successful responses start with a 224 response
   followed by the overview information for all matched messages.  Once
   the output is complete, a period is sent on a line by itself.  If no
   argument is specified, the information for the current article is
   returned.  A news group must have been selected earlier, else a 412
   error response is returned.  If no articles are in the range
   specified, a 420 error response is returned by the server.  A 502
   response will be returned if the client only has permission to
   transfer articles.

   Each line of output will be formatted with the article number,
   followed by each of the headers in the overview database or the
   article itself (when the data is not available in the overview
   database) for that article separated by a tab character.  The
   sequence of fields must be in this order: subject, author, date,
   message-id, references, byte count, and line count.  Other optional
   fields may follow line count.  Other optional fields may follow line
   count.  These fields are specified by examining the response to the
   LIST OVERVIEW.FMT command.  Where no data exists, a null field must
   be provided (i.e. the output will have two tab characters adjacent to
   each other).  Servers should not output fields for articles that have
   been removed since the XOVER database was created.

   The LIST OVERVIEW.FMT command should be implemented if XOVER is
   implemented.  A client can use LIST OVERVIEW.FMT to determine what
   optional fields  and in which order all fields will be supplied by
   the XOVER command.  See Section 2.1.7 for more details about the LIST
   OVERVIEW.FMT command.

   Note that any tab and end-of-line characters in any header data that
   is returned will be converted to a space character.

2.8.1 Responses

      224 Overview information follows
      412 No news group current selected
      420 No article(s) selected
      502 no permission
*)
procedure TIdNNTPServer.CommandXOver(ASender: TIdCommand);
var
  s: string;
  LFirstMsg: Integer;
  LLastMsg: Integer;
begin
  if not Assigned (OnXOver) then
  begin
    ASender.Reply.NumericCode := 500;
    exit
  end;

  if AuthRequired(ASender) then begin
    Exit;
  end;
  if Length(TIdNNTPThread(ASender.Thread).CurrentGroup) = 0 then begin
    ASender.Reply.NumericCode := 412;
  end else begin
    s := ASender.UnparsedParams;
    LFirstMsg := StrToIntDef(Trim(Fetch(s, '-')), -1);
    if LFirstMsg = -1 then begin
      LFirstMsg := TIdNNTPThread(ASender.Thread).CurrentArticle;
      LLastMsg := LFirstMsg;
    end else begin
      LLastMsg := StrToIntDef(Trim(s), -1);
    end;
    if LFirstMsg = -1 then begin
      ASender.Reply.NumericCode := 420;
    end else begin
      ASender.Reply.NumericCode := 224;
      ASender.SendReply;
      // No need for DoOnXover - only this proc can call it and it already checks for nil
      OnXOver(TIdNNTPThread(ASender.Thread), LFirstMsg, LLastMsg);
      ASender.Thread.Connection.WriteLn('.');
    end;
  end;
end;

constructor TIdNNTPServer.Create(AOwner: TComponent);
begin
  inherited;
  FHelp := TStringList.Create;

  FOverviewFormat := TStringList.Create;
  with FOverviewFormat do begin
    Add('Subject:');
    Add('From:');
    Add('Date:');
    Add('Message-ID:');
    Add('References:');
    Add('Bytes:');
    Add('Lines:');
  end;

  ThreadClass := TIdNNTPThread;
  DefaultPort := IdPORT_NNTP;
  (*
  In general, 1xx codes may be ignored or displayed as desired;  code
  200 or 201 is sent upon initial connection to the NNTP server
  depending upon posting permission;  *)
  // TODO: Account for 201 as well. Right now the user can override this if they wish
  Greeting.NumericCode := 200;
  //
  ReplyExceptionCode := 503;
  ReplyUnknownCommand.NumericCode := 500;
  ReplyUnknownCommand.Text.Text := RSNNTPServerNotRecognized;
end;

destructor TIdNNTPServer.Destroy;
begin
  FreeAndNil(FOverviewFormat);
  FreeAndNil(FHelp);
  inherited;
end;

procedure TIdNNTPServer.DoListGroups(AThread: TIdNNTPThread);
begin
  if Assigned(OnListGroups) then
    OnListGroups(AThread)
end;

procedure TIdNNTPServer.DoSelectGroup(AThread: TIdNNTPThread; const AGroup: string; var VMsgCount,
  VMsgFirst, VMsgLast: Integer; var VGroupExists: Boolean);
begin
  VMsgCount := 0;
  VMsgFirst := 0;
  VMsgLast := 0;
  VGroupExists := False;
  if Assigned(OnSelectGroup) then begin
    OnSelectGroup(TIdNNTPThread(AThread), AGroup, VMsgCount, VMsgFirst, VMsgLast, VGroupExists);
  end;
end;

procedure TIdNNTPServer.InitializeCommandHandlers;
begin
  inherited;
  with CommandHandlers.Add do begin
    Command := 'ARTICLE';
    OnCommand := CommandArticle;
    ReplyNormal.NumericCode := 500;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'AUTHINFO USER';
    OnCommand := CommandAuthInfoUser;
    ReplyNormal.NumericCode := 502;
  end;
  with CommandHandlers.Add do begin
    Command := 'AUTHINFO PASS';
    OnCommand := CommandAuthInfoPassword;
    ReplyNormal.NumericCode := 502;
  end;
  // TODO: Add AUTHINFO SIMPLE and AUTHINFO GENERIC
  with CommandHandlers.Add do begin
    Command := 'AUTHINFO SIMPLE';
    ReplyNormal.NumericCode := 452;
  end;
  with CommandHandlers.Add do begin
    Command := 'AUTHINFO GENERIC';
    ReplyNormal.NumericCode := 501;
  end;
  with CommandHandlers.Add do begin
    Command := 'BODY';
    OnCommand := CommandBody;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'DATE';
    OnCommand := CommandDate;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'HEAD';
    OnCommand := CommandHead;
    ParseParams := False;
  end;
  (*
  3.3.  The HELP command

  3.3.1.  HELP

     HELP

     Provides a short summary of commands that are understood by this
     implementation of the server. The help text will be presented as a
     textual response, terminated by a single period on a line by itself.

     3.3.2.  Responses

     100 help text follows
  *)
  with CommandHandlers.Add do begin
    Command := 'HELP';
    ReplyNormal.NumericCode := 100;
    if FHelp.Count = 0 then begin
      Response.Add('No help available.');
    end else begin
      Response.Assign(FHelp);
    end;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'GROUP';
    OnCommand := CommandGroup;
    ReplyNormal.NumericCode := 411;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'IHAVE';
    OnCommand := CommandIHave;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'LAST';
    OnCommand := CommandLast;
    ParseParams := False;
  end;
  (*
  2.1.7 LIST OVERVIEW.FMT

     LIST OVERVIEW.FMT

     The overview.fmt file is maintained by some news transport systems to
     contain the order in which header information is stored in the
     overview databases for each news group.  When executed, news article
     header fields are displayed one line at a time in the order in which
     they are stored in the overview database [5] following the 215
     response.  When display is completed, the server will send a period
     on a line by itself.  If the information is not available, the server
     will return the 503 response.

     Please note that if the header has the word "full" (without quotes)
     after the colon, the header's name is prepended to its field in the
     output returned by the server.

     Many newsreaders work better if Xref: is one of the optional fields.

     It is STRONGLY recommended that this command be implemented in any
     server that implements the XOVER command.  See section 2.8 for more
     details about the XOVER command.

  2.1.7.1 Responses

        215 information follows
        503 program error, function not performed
  *)
  // Before LIST
  with CommandHandlers.Add do begin
    Command := 'LIST Overview.fmt';
    if OverviewFormat.Count > 0 then begin
      ReplyNormal.NumericCode := 215;
      Response.Assign(OverviewFormat);
    end else begin
      ReplyNormal.NumericCode := 503;
    end;
    ParseParams := False;
  end;
  // Before LIST
  //TODO: This needs implemented as events to allow return data
  // RFC 2980 - NNTP Extension
  with CommandHandlers.Add do begin
    Command := 'LIST NEWSGROUPS';
    ReplyNormal.NumericCode := 215;
    Response.Add('.');
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'LIST';
    OnCommand := CommandList;
    ReplyNormal.NumericCode := 215;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'LISTGROUP';
    OnCommand := CommandListGroup;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'MODE READER';
    OnCommand := CommandModeReader;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'NEWGROUPS';
    OnCommand := CommandNewGroups;
    ReplyNormal.NumericCode := 231;
  end;
  with CommandHandlers.Add do begin
    Command := 'NEWNEWS';
    OnCommand := CommandNewNews;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'NEXT';
    OnCommand := CommandNext;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'POST';
    OnCommand := CommandPost;
    ParseParams := False;
  end;
  (*
  3.11.  The QUIT command

  3.11.1.  QUIT

     QUIT

     The server process acknowledges the QUIT command and then closes the
     connection to the client.  This is the preferred method for a client
     to indicate that it has finished all its transactions with the NNTP
     server.

     If a client simply disconnects (or the connection times out, or some
     other fault occurs), the server should gracefully cease its attempts
     to service the client.

  3.11.2.  Responses

     205 closing connection - goodbye!
  *)
  with CommandHandlers.Add do begin
    Command := 'QUIT';
    Disconnect := True;
    ReplyNormal.NumericCode := 205;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'SLAVE';
    OnCommand := CommandSlave;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'STAT';
    OnCommand := CommandStat;
    ParseParams := False;
  end;
  with CommandHandlers.Add do begin
    Command := 'XHDR';
    OnCommand := CommandXHdr;
    ParseParams := True;
  end;
  with CommandHandlers.Add do begin
    Command := 'XOVER';
    OnCommand := CommandXOver;
    ReplyNormal.NumericCode := 500;
    ParseParams := False;
  end;
  with ReplyTexts do begin
    // 100s
    Add(100, 'help text follows');
    Add(199, 'debug output');
    // 200s
    Add(200, 'server ready - posting allowed');
    Add(201, 'server ready - no posting allowed');
    Add(202, 'slave status noted');
    Add(205, 'closing connection - goodbye!');
    Add(215, 'list of newsgroups follows');
    Add(231, 'list of new newsgroups follows');
    Add(235, 'article transferred ok');
    Add(240, 'article posted ok');
    
    Add(281, 'authentication accepted');
    // 300s
    Add(335, 'send article to be transferred. End with <CR-LF>.<CR-LF>');
    Add(340, 'send article to be posted. End with <CR-LF>.<CR-LF>');
    Add(381, 'more authentication information required');
    // 400s
    Add(400, 'service discontinued');
    Add(411, 'no such news group');
    Add(412, 'no newsgroup has been selected');
    Add(420, 'no current article has been selected');
    Add(421, 'no next article in this group');
    Add(422, 'no previous article in this group');
    Add(423, 'no such article number in this group');
    Add(430, 'no such article found');
    Add(435, 'article not wanted - do not send it');
    Add(436, 'transfer failed - try again later');
    Add(437, 'article rejected - do not try again.');
    Add(440, 'posting not allowed');
    Add(441, 'posting failed');
    Add(450, 'Authorization required for this command');
    Add(452, 'Authorization rejected');
    Add(480, 'Authentication required');
    Add(482, 'Authentication rejected');
    // 500s
    Add(500, 'command not recognized');
    Add(501, 'command syntax error');
    Add(502, 'access restriction or permission denied');
    Add(503, 'program fault - command not performed');
  end;
end;

function TIdNNTPServer.AuthRequired(ASender: TIdCommand): Boolean;
var
  LRequired : boolean;
begin
  Result := False;
  if Assigned(FOnAuth) and (TIdNNTPThread(ASender.Thread).Authenticated = False)
   then begin
    LRequired := True;
    if Assigned(OnAuthRequired) then begin
      OnAuthRequired(TIdNNTPThread(ASender.Thread)
       , ASender.CommandHandler.Command, ASender.UnparsedParams, LRequired);
    end;
    Result := LRequired;
    if Result then begin
      ASender.Reply.NumericCode := 450
    end;
  end;
end;

function TIdNNTPServer.RawNavigate(AThread: TIdNNTPThread;
  AEvent: TIdNNTPOnMovePointer): String;
var LMsgNo : Integer;
begin
  Result := '';
  LMsgNo := AThread.CurrentArticle;
  if (AThread.CurrentArticle > 0) then
  begin
    AEvent(AThread,LMsgNo,Result);
    if (LMsgNo > 0) and (LMsgNo <> AThread.CurrentArticle) and (Result <> '') then
    begin
      AThread.FCurrentArticle := LMsgNo;
    end;
  end;
end;

procedure TIdNNTPServer.SetHelp(AValue: TStrings);
begin
  FHelp.Assign(AValue);
end;

{ TIdNNTPThread }

constructor TIdNNTPThread.Create(ACreateSuspended: Boolean);
begin
  inherited;
  FCurrentArticle := 0;
end;

procedure TIdNNTPServer.SetOverviewFormat(AValue: TStrings);
begin
  FOverviewFormat.Assign(AValue);
end;

(*
3.1 AUTHINFO

   AUTHINFO is used to inform a server about the identity of a user of
   the server.  In all cases, clients must provide this information when
   requested by the server.  Servers are not required to accept
   authentication information that is volunteered by the client.
   Clients must accommodate servers that reject any authentication
   information volunteered by the client.

   There are three forms of AUTHINFO in use.  The original version, an
   NNTP v2 revision called AUTHINFO SIMPLE and a more recent version
   which is called AUTHINFO GENERIC.

3.1.1 Original AUTHINFO

   AUTHINFO USER username
   AUTHINFO PASS password

   The original AUTHINFO is used to identify a specific entity to the
   server using a simple username/password combination.  It first
   appeared in the UNIX reference implementation.

   When authorization is required, the server will send a 480 response
   requesting authorization from the client.  The client must enter
   AUTHINFO USER followed by the username.  Once sent, the server will
   cache the username and may send a 381 response requesting the
   password associated with that username.  Should the server request a
   password using the 381 response, the client must enter AUTHINFO PASS
   followed by a password and the server will then check the
   authentication database to see if the username/password combination
   is valid.  If the combination is valid or if no password is required,
   the server will return a 281 response.  The client should then retry
   the original command to which the server responded with the 480
   response.  The command should then be processed by the server
   normally.  If the combination is not valid, the server will return a
   502 response.

   Clients must provide authentication when requested by the server.  It
   is possible that some implementations will accept authentication
   information at the beginning of a session, but this was not the
   original intent of the specification.  If a client attempts to
   reauthenticate, the server may return 482 response indicating that
   the new authentication data is rejected by the server.  The 482 code
   will also be returned when the AUTHINFO commands are not entered in
   the correct sequence (like two AUTHINFO USERs in a row, or AUTHINFO
   PASS preceding AUTHINFO USER).

   All information is passed in cleartext.

   When authentication succeeds, the server will create an email address
   for the client from the user name supplied in the AUTHINFO USER
   command and the hostname generated by a reverse lookup on the IP
   address of the client.  If the reverse lookup fails, the IP address,
   represented in dotted-quad format, will be used.  Once authenticated,
   the server shall generate a Sender:  line using the email address
   provided by authentication if it does not match the client-supplied
   From: line.  Additionally, the server should log the event, including
   the email address.  This will provide a means by which subsequent
   statistics generation can associate newsgroup references with unique
   entities - not necessarily by name.

3.1.1.1 Responses

      281 Authentication accepted
      381 More authentication information required
      480 Authentication required
      482 Authentication rejected
      502 No permission
*)
procedure TIdNNTPServer.CommandAuthInfoPassword(ASender: TIdCommand);
var
  LThread: TIdNNTPThread;
begin
  if Assigned(FOnAuth) then begin
    if ASender.Params.Count = 1 then begin
      LThread := TIdNNTPThread(ASender.Thread);
      LThread.FPassword := ASender.Params[0];
      FOnAuth(LThread, LThread.FAuthenticated);
      if LThread.FAuthenticated then begin
        ASender.Reply.NumericCode := 281;
      end;
    end;
  end else ASender.Reply.NumericCode := 500; 
end;

procedure TIdNNTPServer.CommandAuthInfoUser(ASender: TIdCommand);
var
  LThread: TIdNNTPThread;
begin
  if Assigned(FOnAuth) then begin
    if ASender.Params.Count = 1 then begin
      LThread := TIdNNTPThread(ASender.Thread);
      LThread.FUsername := ASender.Params[0];
      FOnAuth(LThread, LThread.FAuthenticated);
      if LThread.FAuthenticated then begin
        ASender.Reply.NumericCode := 281;
      end else begin
        ASender.Reply.NumericCode := 381;
      end;
    end;
  end else ASender.Reply.NumericCode := 500; 
end;

function TIdNNTPServer.LookupMessage(ASender : TidCommand;
  var VNo: Integer; var VId: string): boolean;
var
  s : string;
  LThread : TidNNTPThread;
begin
  result := False;
  LThread := TidNNTPThread (ASender.Thread);

  if LThread.CurrentGroup = '' then
    ASender.Reply.NumericCode := 412
  else
  begin
    s := Trim(ASender.UnparsedParams);
    VId := '';

    if s = '' then
      VNo := LThread.CurrentArticle
    else
      VNo := StrToIntDef (s, 0);

    if VNo = 0 then
    begin
      if s = '' then
        ASender.Reply.NumericCode := 420  // Current article no not set.
      else
      begin
        if Copy (s, 1, 1) = '<' then
          if Assigned (OnCheckMsgID) then
            OnCheckMsgID (LThread, s, VNo);

        if VNo <> 0 then
          VId := s
        else
          ASender.Reply.NumericCode := 430 // Article not found
      end
    end
    else
    begin

      if Assigned (OnCheckMsgNo) then
        OnCheckMsgNo (LThread, VNo, VId);

      if VId <> '' then
        LThread.FCurrentArticle := VNo
      else
        ASender.Reply.NumericCode := 423  // Article no does not exist
    end;
    result := (vNo > 0) and (vId <> '');
  end
end;

end.
