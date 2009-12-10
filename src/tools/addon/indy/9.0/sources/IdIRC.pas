{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10227: IdIRC.pas 
{
{   Rev 1.0    2002.11.12 10:43:38 PM  czhower
}
unit IdIRC;

{
  TIRCClient component for Borland Delphi 5.0 or above
  by Steve 'Sly' Williams (stevewilliams@kromestudios.com)    
  ported to Indy by Daaron Dwyer (ddwyer@ncic.com)

  File:       IRCClient.pas
  Version:    1.06
  Objects:    TIRCClient, TUser, TUsers, TChannel, TChannels, TIdIRCReplies
  Requires:   Indy9

  Provides a simple, high-level interface to the IRC network.  Set the
  properties and write event handlers for the events you wish to respond to.
  Any events that do not have a specific event handler will continue normal
  processing (ie. reply to a request, ignore a message, etc).

  I have tried to keep the TIRCClient object as independent as possible from
  the user interface implementation.  This is so the user interface is not
  constrained by any inherent limitations placed upon it by the implementation
  of the TIRCClient object itself, thus leaving the user interface to be as
  standard or as non-standard as the designer wishes.

  This is a non-visual component, and should be placed either on the main form
  or in a data module, where all units can easily access the component.

  The command numerics and server operation are based on RFC1459, the original
  specification for IRC.  Any change from the specification is due to the
  differences that have been noted from practical experience.  There may be
  some IRC networks that operate differently and therefore do not operate with
  this TIRCClient object in the correct manner.  If you do have any information
  that would make the TIRCClient object more compatible with current or planned
  IRC networks, then e-mail me so that I may add these features to the next
  release.

  History:
  1.00    Initial release
  1.01    1/03/1999
          - Changed SocketDataAvailable to handle lines ending with either CRLF
            (within spec) or a single LF (breaks spec).  It seems a few servers
            break the rules.  Also added check for a non-zero Error parameter.
  1.02    5/04/1999
          - Added SocksServer and SocksPort properties as requested by Joe
            (cybawiz@softhome.net).
  1.03    13/05/1999
          - Moved the creation of the TWSocket to the overridden Loaded
            procedure to get rid of the annoying "A device attached to the
            system is not functioning" error when recompiling the package.
  1.04    28/11/1999
          - If Suppress in the OnRaw event was set to True, the User object
            would not have been released.
          - Uncommented the OnJoin and OnPart events.  Not sure why I had them
            commented out.
  1.05    02/12/1999
          - Fixed the Replies property and made it published.  The TIdIRCReplies
            object had to descend from TPersistent, not TObject.  Oops.
          - Fixed the Client property of TUser, TUsers, TChannel and TChannels.
            Made a forward declaration of TIRCClient and used that as the type.
  1.06    25/05/2000
          - Fixed TUsers.Destroy and TChannels.Destroy.  The list items were not
            being deleted after the objects were freed.  Silly error on my part.
  1.061   27/07/2001 - Daaron Dwyer (ddwyer@ncic.com)
          - Modified component to use Indy TCPClient control rather than ICS
  1.062   10/11/2001 - J. Peter Mugaas
          - added ACmd Integer parameter to TIdIRCOnSystem as suggested by Andrew P.Rybin
          - added Channel to OnNames event as suggested by Sven Orro

  TUser object
  -------------------------------------

  Properties:
    Nick                  The user's nickname.
    Address               The full address of the user.
    Count                 Count of the number of objects referencing this
                          user.  This user is removed when the reference
                          count drops to zero.
    Data                  A reference to an object defined by the client.
                          Usually references the message window for this user.

  Methods:
    Say                   Send a message to this user.


  TUsers object
  -------------------------------------

  Properties:
    none

  Methods:
    Add                   Increments the reference count for this user.  If the
                          user does not exist, then a new user is created with
                          a reference count of one.  Returns the TUser object
                          for the user.
    Remove                Decrement the reference count for this user.  The
                          user is only deleted if the reference count becomes
                          zero.
    Address               Return the address of a specified nick.
    Find                  Returns the index of the user if the nick is found.
    Get                   Returns the user object if the nick is found.
    Nick                  Change the nick of an existing user.


  TChannel object
  -------------------------------------

  Properties:
    Name                  Channel name.
    Topic                 Current topic of the channel (if set).
    Mode                  Set of channel modes.
    Limit                 Set if a limit is set to the number of users in a
                          channel.
    Key                   Set if a password key is set for the channel.
    ModeChange            True if mode changes are being compiled.
    Data                  A reference to an object defined by the client.
                          Usually references the message window for this
                          channel.

  Methods:
    Say                   Send a message to the channel.
    Part                  Part the channel.
    Kick                  Kick a nick from the channel.
    Topic                 Set the channel topic.
    BeginMode             Compile but do not send mode changes until the
                          EndMode method is called.
    EndMode               Compile all mode changes since BeginMode and send to
                          the server.
    Op                    Give a user channel operator status.
    Deop                  Remove channel operator status from a user.
    Voice                 Give a voice to a user in a moderated channel.
    Devoice               Remove the voice from a user in a moderated channel.
    Ban                   Ban a user from the channel.
    Unban                 Remove a ban from the channel.
    TopicChanged          Call to change the topic without sending a topic
                          command (ie. when another user changes the topic).
    ModeChanged           Call to change the channel mode without sending a
                          mode command (ie. when another user changes the
                          mode).
    LimitChanged          Call to change the channel limit without sending a
                          mode command (ie. when another user changes the
                          limit).
    KeyChanged            Call to change the channel key without sending a
                          mode command (ie. when another user changes the
                          key).
    AddUser               Add a user to the channel.
    RemoveUser            Remove a user from the channel.

  TChannels object
  -------------------------------------

  Properties:
    none

  Methods:
    Add                   Add a new channel to the list.
    Remove                Remove a channel from the list.
    Find                  Find a channel name, if it exists.
    Get                   Returns the channel object for the name given.


  TIRCClient component
  -------------------------------------

  Design-time properties:
    Nick                  The primary nick to be used.  Defaults to 'Nick'.
    AltNick               Another nick to use if the primary nick is already
                          in use.  Defaults to 'OtherNick'.
    UserName              Your username (for the system you are using).
    RealName              The information you want displayed in your whois
                          response.
    Server                Address of the IRC server to connect to.
    Port                  Server port number to connect to.  Defaults to
                          '6667'.
    Password              Password to connect (if required).
    UserMode              Set of user modes.  Defaults to an empty set.
                          umInvisible, umOperator, umServerNotices, umWallops.
    SocksServer           Address of the SOCKS server to connect through.
    SocksPort             Port number of the SOCKS server to connect through.

  Run-time properties:
    Connected             Returns True if currently connected to the IRC
                          network.
    Away                  Set to True if you are marked as away.
    Notify                List of nicknames/addresses to be notified of when
                          they join/leave IRC.
    State                 The current connection state.
    Channels              The list of channel objects.
    Replies
      Finger              Standard CTCP reply for FINGER requests.
      Version             Standard CTCP reply for VERSION requests.
      UserInfo            Standard CTCP reply for USERINFO requests.
      ClientInfo          Standard CTCP reply for CLIENTINFO requests.

  Events:
    OnConnect             Connected to the IRC network.
    OnDisconnect          Disconnected from the IRC network.
    OnChannelMessage      Received a channel message.
    OnChannelNotice       Received a channel notice.
    OnChannelAction       Received a channel action.
    OnPrivateMessage      Received a private message.
    OnPrivateNotice       Received a private notice.
    OnPrivateAction       Received a private action.
    OnJoin                A user joined a channel.
    OnJoined              You joined a channel.
    OnPart                A user parted a channel.
    OnParted              You parted a channel.
    OnKick                A user kicked another user from a channel.
    OnKicked              You were kicked from a channel by a user.
    OnNickChange          A user changed their nick.
    OnNickChanged         Your nick was changed.
    OnTopic               The topic of the channel was changed.
    OnQuit                A user quit IRC.
    OnNames               Received a list of names of people in a channel.
    OnInvite              A user has invited you to a channel.
    OnInviting            You invited a user to a channel.
    OnPingPong            Received a server ping (PONG response sent
                          automatically).
    OnError               Error message from server.
    OnAway                Received an away message for a user.
    OnNowAway             You are marked as being away.
    OnUnAway              You are no longer marked as being away.
    OnWallops             Received a wallops message.
    OnSystem              Any response from the server not handled by a
                          specific event handler.
    OnRaw                 Every command from the IRC server goes through this
                          handler first.  Normal processing can be suppressed
                          by setting the Suppress parameter to True.
    OnOp                  A user was oped in a channel.
    OnDeop                A user was deoped in a channel.
    OnBan                 A user was banned in a channel.
    OnUnban               A user was unbanned in a channel.
    OnVoice               A user was given a voice in a channel.
    OnDevoice             A user's voice was taken away in a channel.
    OnChannelMode         The channel mode was changed.
    OnChannelModeChanged  Called after the channel mode change has been parsed
                          and the mode was changed.
    OnUserMode            Your user mode was changed.
    OnUserModeChanged     Called after the user mode change has been parsed
                          and the mode was changed.
    OnKill                A user was killed.
    OnUnknownCommand      An unknown command was received from the server.
    OnStateChange         Called when the current state of the IRC connection
                          changes.
    OnSend                Called for every command sent to the IRC server.
                          Useful for displaying in a raw output window.
    OnReceive             Called for every command is received from the IRC
                          server.  Useful for displaying in a raw output
                          window.
    OnNicksInUse          Called during the registration process when both Nick
                          and AltNick are in use.
    OnSocketError         An error occurred in the TCP/IP socket.
    OnNoTopic             There is no topic for this channel.
    OnChannelMode         The channel mode is now set.
    OnLinks               Results from a /LINK command
    OnList                Results from a /LIST command

  The following CTCP query event handlers can suppress the standard response by
  setting the Suppress parameter to True.
    OnCTCPQuery           A user sent you a CTCP query.
    OnCTCPReply           Received a reply from a CTCP query.

  Events to be added later:
    OnOped                You were oped in a channel.
    OnDeoped              You were deoped in a channel.
    OnBanned              You were banned in a channel.
    OnUnbanned            You were unbanned in a channel.
    OnVoiced              You were given a voice in a channel.
    OnDevoiced            Your voice was taken away in a channel.
    OnKilled              You were killed.
    OnNotify              A person on your notify list has joined IRC.
    OnDenotify            A person on your notify list has left IRC.
    OnLag                 Update on the current lag time.

  DCC events to be added later
    OnChat                Someone wants to initiate a DCC chat.
    OnChatClosed          The DCC chat was closed.
    OnFileReceive         Someone wants to send you a file.
    OnFileReceived        The file was received successfully.
    OnFileSend            A file is offered to someone.
    OnFileSent            The file was sent successfully.
    OnFileError           There was an error during file transfer.
    OnDCC                 General DCC event handler.

  *TEMPDCC EVENTS UNTIL ABOVE ARE DONE*:
    OnDCCChat             Someone wants to DCC Chat
    OnDCCSend             Someone wants to Send you a File Via DCC
    OnDCCResume           Someone is requesting a DCC File RESUME
    OnDCCAccept           Someone has ACCEPTED your DCC File RESUME request


  Set the Accept parameter to True to accept the DCC.  Set the Resume
  parameter to True to resume a DCC file transfer.  Set the Filename parameter
  to the full path and name of the place to store the received file.

  Methods:
    Connect               Connect to the IRC network.
    Disconnect            Force a disconnect from the IRC network.
    Raw                   Send the command directly to the IRC server.
    Say                   Send a message to a user/channel.
    Notice                Send a notice to a user/channel.
    Join                  Join channel/s with given key/s.
    Part                  Part channel/s with an optional reason (if supported
                          by the IRC server).
    Kick                  Kick a person from a channel.
    Quit                  Quit the IRC network.
    CTCP                  Send a CTCP command to a user/channel.
    CTCPReply             Send a reply to a CTCP command.
    IsChannel             Returns True if the name is a channel name.
    IsOp                  Returns True if the user has operator status.
    IsVoice               Returns True if the user has a voice.
    MatchHostmask         Returns True if the address matches the hostmask.
    GetTopic              Get the topic for the specified channel.
    SetTopic              Set the topic for the specifiec channel.

  Methods to be added later:
    Ban                   Ban hostmask/s from a channel.
    Unban                 Unban hostmask/s from a channel.
    Op                    Op nick/s in a channel.
    Deop                  Deop nick/s in a channel.
    Voice                 Give a voice to nick/s.
    Devoice               Take voice from nick/s.
    Invite                Invite someone to a channel.
    DCCChat               Initiate a DCC chat.
    DCCSend               Initiate a DCC send of a file.
}

interface

uses
  Classes, IdAssignedNumbers, IdBaseComponent, IdComponent, IdTCPConnection,
  IdException, IdTCPClient, IdThread, IdStack, IdGlobal;

const
  { Numerics as defined in RFC1459. }
  RPL_TRACELINK	        =	200	;	{	Link <version & debug level> <destination> <next server> }
  RPL_TRACECONNECTING	  =	201	;	{	Try. <class> <server> }
  RPL_TRACEHANDSHAKE	  =	202	;	{	H.S. <class> <server> }
  RPL_TRACEUNKNOWN	    =	203	;	{	???? <class> [<client IP address in dot form>] }
  RPL_TRACEOPERATOR	    =	204	;	{	Oper <class> <nick> }
  RPL_TRACEUSER	        =	205	;	{	User <class> <nick> }
  RPL_TRACESERVER	      =	206	;	{	Serv <class> <int>S <int>C <server> <nick!user|*!*>@<host|server> }
  RPL_TRACENEWTYPE	    =	208	;	{	<newtype> 0 <client name> }
  RPL_STATSLINKINFO	    =	211	;	{	<linkname> <sendq> <sent messages> <sent bytes> <received messages> <received bytes> <time open> }
  RPL_STATSCOMMANDS	    =	212	;	{	<command> <count> }
  RPL_STATSCLINE	      =	213	;	{	C <host> * <name> <port> <class> }
  RPL_STATSNLINE	      =	214	;	{	N <host> * <name> <port> <class> }
  RPL_STATSILINE	      =	215	;	{	I <host> * <host> <port> <class> }
  RPL_STATSKLINE	      =	216	;	{	K <host> * <username> <port> <class> }
  RPL_STATSYLINE	      =	218	;	{	Y <class> <ping frequency> <connect frequency> <max sendq> }
  RPL_ENDOFSTATS	      =	219	;	{	<stats letter> :End of /STATS report }
  RPL_UMODEIS	          =	221	;	{	<user mode string> }
  RPL_STATSLLINE	      =	241	;	{	L <hostmask> * <servername> <maxdepth> }
  RPL_STATSUPTIME	      =	242	;	{	:Server Up %d days %d:%02d:%02d }
  RPL_STATSOLINE	      =	243	;	{	O <hostmask> * <name> }
  RPL_STATSHLINE	      =	244	;	{	H <hostmask> * <servername> }
  RPL_LUSERCLIENT	      =	251	;	{	:There are <integer> users and <integer> invisible on <integer> servers }
  RPL_LUSEROP	          =	252	;	{	<integer> :operator(s) online }
  RPL_LUSERUNKNOWN	    =	253	;	{	<integer> :unknown connection(s) }
  RPL_LUSERCHANNELS	    =	254	;	{	<integer> :channels formed }
  RPL_LUSERME	          =	255	;	{	:I have <integer> clients and <integer> servers }
  RPL_ADMINME	          =	256	;	{	<server> :Administrative info }
  RPL_ADMINLOC1	        =	257	;	{	:<admin info> }
  RPL_ADMINLOC2	        =	258	;	{	:<admin info> }
  RPL_ADMINEMAIL	      =	259	;	{	:<admin info> }
  RPL_TRACELOG	        =	261	;	{	File <logfile> <debug level> }
  RPL_NONE	            =	300	;	{	Dummy reply number. Not used. }
  RPL_AWAY	            =	301	;	{	<nick> :<away message> }
  RPL_USERHOST	        =	302	;	{	:[<reply><space><reply>] }
  RPL_ISON	            =	303	;	{	:[<nick> <space><nick>] }
  RPL_UNAWAY	          =	305	;	{	:You are no longer marked as being away }
  RPL_NOWAWAY	          =	306	;	{	:You have been marked as being away }
  RPL_WHOISUSER	        =	311	;	{	<nick> <user> <host> * :<real name> }
  RPL_WHOISSERVER	      =	312	;	{	<nick> <server> :<server info> }
  RPL_WHOISOPERATOR	    =	313	;	{	<nick> :is an IRC operator }
  RPL_WHOWASUSER	      =	314	;	{	<nick> <user> <host> * :<real name> }
  RPL_ENDOFWHO	        =	315	;	{	<name> :End of /WHO list }
  RPL_WHOISIDLE	        =	317	;	{	<nick> <integer> :seconds idle }
  RPL_ENDOFWHOIS	      =	318	;	{	<nick> :End of /WHOIS list }
  RPL_WHOISCHANNELS	    =	319	;	{	<nick> :[@|+]<channel><space> }
  RPL_LISTSTART	        =	321	;	{	Channel :Users  Name }
  RPL_LIST	            =	322	;	{	<channel> <# visible> :<topic> }
  RPL_LISTEND	          =	323	;	{	:End of /LIST }
  RPL_CHANNELMODEIS	    =	324	;	{	<channel> <mode> <mode params> }
  RPL_NOTOPIC	          =	331	;	{	<channel> :No topic is set }
  RPL_TOPIC	            =	332	;	{	<channel> :<topic> }
  RPL_INVITING	        =	341	;	{	<channel> <nick> }
  RPL_SUMMONING	        =	342	;	{	<user> :Summoning user to IRC }
  RPL_VERSION	          =	351	;	{	<version>.<debuglevel> <server> :<comments> }
  RPL_WHOREPLY	        =	352	;	{	<channel> <user> <host> <server> <nick> <H|G>[*][@|+] :<hopcount> <real name> }
  RPL_NAMREPLY	        =	353	;	{	<channel> :[[@|+]<nick> [[@|+]<nick> [...]]] }
  RPL_LINKS	            =	364	;	{	<mask> <server> :<hopcount> <server info> }
  RPL_ENDOFLINKS	      =	365	;	{	<mask> :End of /LINKS list }
  RPL_ENDOFNAMES	      =	366	;	{	<channel> :End of /NAMES list }
  RPL_BANLIST	          =	367	;	{	<channel> <banid> }
  RPL_ENDOFBANLIST	    =	368	;	{	<channel> :End of channel ban list }
  RPL_ENDOFWHOWAS	      =	369	;	{	<nick> :End of WHOWAS }
  RPL_INFO	            =	371	;	{	:<string> }
  RPL_MOTD	            =	372	;	{	:- <text> }
  RPL_ENDOFINFO	        =	374	;	{	:End of /INFO list }
  RPL_MOTDSTART	        =	375	;	{	":- <server> Message of the day -," }
  RPL_ENDOFMOTD	        =	376	;	{	:End of /MOTD command }
  RPL_YOUREOPER	        =	381	;	{	:You are now an IRC operator }
  RPL_REHASHING	        =	382	;	{	<config file> :Rehashing }
  RPL_TIME	            =	391	;	{ }
  RPL_USERSSTART	      =	392	;	{	:UserID   Terminal  Host }
  RPL_USERS	            =	393	;	{	:%-8s %-9s %-8s }
  RPL_ENDOFUSERS	      =	394	;	{	:End of users }
  RPL_NOUSERS	          =	395	;	{	:Nobody logged in }
  ERR_NOSUCHNICK	      =	401	;	{	<nickname> :No such nick/channel }
  ERR_NOSUCHSERVER	    =	402	;	{	<server name> :No such server }
  ERR_NOSUCHCHANNEL	    =	403	;	{	<channel name> :No such channel }
  ERR_CANNOTSENDTOCHAN	=	404	;	{	<channel name> :Cannot send to channel }
  ERR_TOOMANYCHANNELS	  =	405	;	{	<channel name> :You have joined too many channels }
  ERR_WASNOSUCHNICK	    =	406	;	{	<nickname> :There was no such nickname }
  ERR_TOOMANYTARGETS	  =	407	;	{	<target> :Duplicate recipients. No message delivered }
  ERR_NOORIGIN	        =	409	;	{	:No origin specified }
  ERR_NORECIPIENT	      =	411	;	{	:No recipient given (<command>) }
  ERR_NOTEXTTOSEND	    =	412	;	{	:No text to send }
  ERR_NOTOPLEVEL	      =	413	;	{	<mask> :No toplevel domain specified }
  ERR_WILDTOPLEVEL	    =	414	;	{	<mask> :Wildcard in toplevel domain }
  ERR_UNKNOWNCOMMAND	  =	421	;	{	<command> :Unknown command }
  ERR_NOMOTD	          =	422	;	{	:MOTD File is missing }
  ERR_NOADMININFO	      =	423	;	{	<server> :No administrative info available }
  ERR_FILEERROR	        =	424	;	{	:File error doing <file op> on <file> }
  ERR_NONICKNAMEGIVEN	  =	431	;	{	:No nickname given }
  ERR_ERRONEUSNICKNAME	=	432	;	{	<nick> :Erroneus nickname }
  ERR_NICKNAMEINUSE	    =	433	;	{	<nick> :Nickname is already in use }
  ERR_NICKCOLLISION	    =	436	;	{	<nick> :Nickname collision KILL }
  ERR_USERNOTINCHANNEL	=	441	;	{	<nick> <channel> :They aren't on that channel }    {Do not Localize}
  ERR_NOTONCHANNEL	    =	442	;	{	<channel> :You're not on that channel }    {Do not Localize}
  ERR_USERONCHANNEL	    =	443	;	{	<user> <channel> :is already on channel }
  ERR_NOLOGIN	          =	444	;	{	<user> :User not logged in }
  ERR_SUMMONDISABLED	  =	445	;	{	:SUMMON has been disabled }
  ERR_USERSDISABLED	    =	446	;	{	:USERS has been disabled }
  ERR_NOTREGISTERED	    =	451	;	{	:You have not registered }
  ERR_NEEDMOREPARAMS	  =	461	;	{	<command> :Not enough parameters }
  ERR_ALREADYREGISTRED	=	462	;	{	:You may not reregister }
  ERR_NOPERMFORHOST	    =	463	;	{	:Your host isn't among the privileged }    {Do not Localize}
  ERR_PASSWDMISMATCH	  =	464	;	{	:Password incorrect }
  ERR_YOUREBANNEDCREEP	=	465	;	{	:You are banned from this server }
  ERR_KEYSET	          =	467	;	{	<channel> :Channel key already set }
  ERR_CHANNELISFULL	    =	471	;	{	<channel> :Cannot join channel (+l) }
  ERR_UNKNOWNMODE	      =	472	;	{	<char> :is unknown mode char to me }
  ERR_INVITEONLYCHAN	  =	473	;	{	<channel> :Cannot join channel (+i) }
  ERR_BANNEDFROMCHAN	  =	474	;	{	<channel> :Cannot join channel (+b) }
  ERR_BADCHANNELKEY	    =	475	;	{	<channel> :Cannot join channel (+k) }
  ERR_NOPRIVILEGES	    =	481	;	{	:Permission Denied- You're not an IRC operator }    {Do not Localize}
  ERR_CHANOPRIVSNEEDED	=	482	;	{	<channel> :You're not channel operator }    {Do not Localize}
  ERR_CANTKILLSERVER	  =	483	;	{	:You cant kill a server! }
  ERR_NOOPERHOST	      =	491	;	{	:No O-lines for your host }
  ERR_UMODEUNKNOWNFLAG	=	501	;	{	:Unknown MODE flag }
  ERR_USERSDONTMATCH	  =	502	;	{	:Cant change mode for other users }

type
  { TIdIRCUser }

  TIdIRC = class;

  //TODO: This needs to be a TCollecitonItem
  TIdIRCUser = class(TCollectionItem)
  protected
    FClient: TIdIRC;
    FNick: String;
    FAddress: String;
    FData: TObject;
    FReason: String;
  public
    Count: Integer;
    constructor Create(AClient: TIdIRC; ANick, AAddress: String); reintroduce;
    destructor Destroy; override;
    procedure Say(AMsg: String);
    property Nick: String read FNick write FNick;
    property Address: String read FAddress write FAddress;
    property Data: TObject read FData write FData;
    property Reason: String read FReason write FReason;
  end;

  { TIdIRCUsers }
  TIdIRCSortCompareUsers = procedure (Sender :TObject;
    AItem1, AItem2 : TIdIRCUser; var AResult : Integer);
  //TODO: This needs to be a TCollection
  TIdIRCUsers = class(TCollection)
  protected
    FClient: TIdIRC;
    FOnSortCompareUsers : TIdIRCSortCompareUsers;
    procedure SetItem ( Index: Integer; const Value: TIdIRCUser );
    function GetItem(Index: Integer): TIdIRCUser;

  public
    constructor Create(AClient: TIdIRC);
    destructor Destroy; override;
    function Add(ANick, AAddress: String): TIdIRCUser;
    procedure Remove(AUser: TIdIRCUser);
    function Address(ANick: String): String;
    function Find(ANick: String; var AIndex: Integer): Boolean;
    function Get(ANick: String): TIdIRCUser;
    procedure Nick(AFromNick, AToNick: String);
    procedure Sort;
    property Items[Index: Integer] : TIdIRCUser read GetItem write SetItem;
    property OnSortCompareUsers : TIdIRCSortCompareUsers
      read FOnSortCompareUsers write FOnSortCompareUsers;
  end;

  { TChannel }

  TIdIRCChangeType = (ctNone, ctAdd, ctSubtract);

  TIdIRCChannelMode = (cmPrivate, cmSecret, cmInviteOnly, cmOpsSetTopic,
    cmNoExternalMessages, cmModerated, cmUserLimit, cmKey);
  TIdIRCChannelModes = Set of TIdIRCChannelMode;

  TIdIRCCloseType = (ctReset, ctPart, ctKick);

  TIdIRCChannelUpdateType = (cuMode, cuTopic, cuUser, cuNames, cuNick, cuJoin,
    cuPart, cuKick, cuQuit);
  TIdIRCOnChannelUpdate = procedure (Sender: TObject; AUpdateType:
    TIdIRCChannelUpdateType; AUser: TIdIRCUser; AInfo: Integer) of object;

  //TODO: This needs to be a TCollectionItem
  TIdIRCChannel = class(TCollectionItem)
  protected
    FClient: TIdIRC;
    FName: String;
    FTopic: String;
    FMode: TIdIRCChannelModes;
    FLimit: Integer;
    FKey: String;
    FNames: TStringList;
    FBans: TStringList;
    FActive: Boolean;
    FData: TObject;
    FModeChange: Boolean;
    ModeOptions: String;
    ModeParams: String;
    ChangeType: TIdIRCChangeType;
    FCloseType: TIdIRCCloseType;
    FOnChannelUpdate: TIdIRCOnChannelUpdate;
    procedure SetTopic(AValue: String);
    procedure SetMode(AValue: TIdIRCChannelModes);
    procedure SetLimit(AValue: Integer);
    procedure SetKey(AValue: String);
    function GetModeString: String;
  public
    constructor Create(AClient: TIdIRC; AName: String); reintroduce;
    destructor Destroy; override;
    procedure Say(AMsg: String);
    procedure Part(AReason: String);
    procedure Kick(ANick, AReason: String);
    procedure BeginMode;
    procedure EndMode;
    procedure Op(ANick: String);
    procedure Deop(ANick: String);
    procedure Voice(ANick: String);
    procedure Devoice(ANick: String);
    procedure Ban(AHostmask: String);
    procedure Unban(AHostmask: String);
    procedure TopicChanged(ATopic: String);
    procedure ModeChanged(AMode: TIdIRCChannelModes);
    procedure LimitChanged(ALimit: Integer);
    procedure KeyChanged(AKey: String);
    function AddUser(ANick, AAddress: String): TIdIRCUser;
    procedure RemoveUser(AUser: TIdIRCUser);
    function HasUser(ANick: String): Boolean;
    function Find(ANick: String; var AIndex: Integer): Boolean;
    procedure GotOp(AUser: TIdIRCUser);
    procedure GotDeop(AUser: TIdIRCUser);
    procedure GotVoice(AUser: TIdIRCUser);
    procedure GotDevoice(AUser: TIdIRCUser);
    procedure ChangedNick(AUser: TIdIRCUser; ANewNick: String);
    procedure Joined(AUser: TIdIRCUser);
    procedure Parted(AUser: TIdIRCUser);
    procedure Kicked(AUser: TIdIRCUser);
    procedure Quit(AUser: TIdIRCUser);
    property Name: String read FName;
    property Topic: String read FTopic write SetTopic;
    property Mode: TIdIRCChannelModes read FMode write SetMode;
    property Limit: Integer read FLimit write SetLimit;
    property Key: String read FKey write SetKey;
    property ModeChange: Boolean read FModeChange;
    property ModeString: String read GetModeString;
    property Names: TStringList read FNames;
    property Bans: TStringList read FBans;
    property Active: Boolean read FActive write FActive;
    property CloseType: TIdIRCCloseType read FCloseType write FCloseType;
    property Data: TObject read FData write FData;
    property OnChannelUpdate: TIdIRCOnChannelUpdate read FOnChannelUpdate write FOnChannelUpdate;
  end;
  
  { TIdIRCChannels }

  TIdIRCSortCompareChanels = procedure (Sender :TObject; AItem1, AItem2 : TIdIRCChannel; var AResult : Integer);

  //TODO: This needs to be a TCollection
  TIdIRCChannels = class(TCollection)
  protected
    FClient: TIdIRC;
    FOnSortCompareChanels : TIdIRCSortCompareChanels;
    function GetItem(Index:Integer): TIdIRCChannel;
    procedure SetItem ( Index: Integer; const Value: TIdIRCChannel );
  public
    constructor Create(AClient: TIdIRC); reintroduce;
    destructor Destroy; override;
    function Add(AName: String): TIdIRCChannel;
    procedure Remove(AName: String);
    function Find(AName: String; var AIndex: Integer): Boolean;
    function Get(AName: String): TIdIRCChannel;
    procedure ChangedNick(AUser: TIdIRCUser; ANewNick: String);
    procedure Quit(AUser: TIdIRCUser);
  public
    procedure Sort; virtual;
    property Items[Index: Integer] : TIdIRCChannel read GetItem write SetItem;
  end;

  { TIdIRCReplies }

  TIdIRCReplies = class(TPersistent)
  protected
    FFinger: String;
    FVersion: String;
    FUserInfo: String;
    FClientInfo: String;
  public
    constructor Create;
    procedure Assign(Source: TPersistent); override;
  published
    property Finger: String read FFinger write FFinger;
    property Version: String read FVersion write FVersion;
    property UserInfo: String read FUserInfo write FUserInfo;
    property ClientInfo: String read FClientInfo write FClientInfo;
  end;

  { TIdIRCReadThread }
  TIdIRCReadThread = class(TIdThread)
  protected
    FClient: TIdIRC;
    FRecvData: string;
    procedure Run; override;
  public
    constructor Create(AClient: TIdIRC); reintroduce;
  end;

  { TIdIRC }
  TIdIRCUserMode = (umInvisible, umOperator, umServerNotices, umWallops);
  TIdIRCUserModes = Set of TIdIRCUserMode;

  TIdIRCState = (csDisconnect, csDisconnected, csConnecting, csLoggingOn, csConnected);

  TIdIRCUpdateType = (utTopic, utMode, utNicks);

  TIdIRCOnMessage = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel; Content: String) of object;
  TIdIRCOnJoin = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel) of object;
  TIdIRCOnJoined = procedure (Sender: TObject; AChannel: TIdIRCChannel) of object;
  TIdIRCOnPart = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel) of object;
  TIdIRCOnParted = procedure (Sender: TObject; AChannel: TIdIRCChannel) of object;
  TIdIRCOnKick = procedure (Sender: TObject; AUser, AVictim: TIdIRCUser; AChannel: TIdIRCChannel) of object;
  TIdIRCOnKicked = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel) of object;
  TIdIRCOnNickChange = procedure (Sender: TObject; AUser: TIdIRCUser; ANewNick: String) of object;
  TIdIRCOnTopic = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel; const AChanName, ATopic : String) of object;
  TIdIRCOnQuit = procedure (Sender: TObject; AUser: TIdIRCUser) of object;
  TIdIRCOnNames = procedure (Sender: TObject; AUsers : TIdIRCUsers; AChannel: TIdIRCChannel) of object;
  TIdIRCOnInvite = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: String) of object;
  TIdIRCOnError = procedure (Sender: TObject; AUser: TIdIRCUser; ANumeric, AError: String) of object;
  TIdIRCOnAway = procedure (Sender: TObject; AUser: TIdIRCUser) of object;
  TIdIRCOnWallops = procedure (Sender: TObject; AUser: TIdIRCUser; AContent: String) of object;
  TIdIRCOnSystem = procedure (Sender: TObject; AUser: TIdIRCUser; ACmdCode: Integer; ACommand, AContent: String) of object;
  TIdIRCOnRaw = procedure (Sender: TObject; AUser: TIdIRCUser; ACommand, AContent: String;
    var Suppress: Boolean) of object;
  TIdIRCOnOp = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel; ATarget: TIdIRCUser) of object;
  TIdIRCOnBan = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel; AHostmask: String) of object;
  TIdIRCOnChannelMode = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel; AChanName: String; AModes: String) of object;
  TIdIRCOnChannelModeChanged = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel) of object;
  TIdIRCOnUserMode = procedure (Sender: TObject; AModes: String) of object;
  TIdIRCOnInviting = procedure (Sender: TObject; ANick, AChannel: String) of object;
  TIdIRCOnKill = procedure (Sender: TObject; User: TIdIRCUser; AVictim, AReason: String) of object;
  TIdIRCOnUnknownCommand = procedure (Sender: TObject; AUser: TIdIRCUser; ACommand, AContent: String) of object;
  TIdIRCOnCTCPQuery = procedure (Sender: TObject; User: TIdIRCUser; AChannel: TIdIRCChannel;
    Command, Args: String; var ASuppress: Boolean) of object;
  TIdIRCOnCTCPReply = procedure (Sender: TObject; AUser: TIdIRCUser; AChannel: TIdIRCChannel;
    Command, Args: String) of object;
  TIdIRCOnSend = procedure (Sender: TObject; ACommand: String) of object;
  TIdIRCOnNicksInUse = procedure (Sender: TObject; var ANick: String) of object;
  TIdIRCOnSocketError = procedure (Sender: TObject; ASocket, AMsg: String) of object;
  TIdIRCOnNoTopic = procedure (Sender: TObject; AChannel: TIdIRCChannel; AContent: String) of object;
  TIdIRCOnAwayChange = procedure (Sender: TObject; AContent: String) of object;
  TIdIRCOnNickChanged = procedure (Sender: TObject; AOldNick: String) of object;
  TIdIRCOnDCCChat = procedure(Sender: TObject; ANick, AIp, APort: String) of object;
  TIdIRCOnDCCSend = procedure(Sender: TObject; ANick, AIp, APort, AFileName, AFileSize: String) of object;
  TIdIRCOnDCCResume = procedure(Sender: TObject; ANick, APort, AFileName, APosition: String) of object;
  TIdIRCOnDCCAccept = procedure(Sender: TObject; ANick, APort, AFileName, APosition: String) of object;
  TIdIRCOnLinks = procedure(Sender: TObject; AMask, AServer, AHopCount, AServerInfo: String) of object;
  TIdIRCOnList = procedure(Sender: TObject; AChans: TStringList; APosition: Integer; ALast: Boolean) of object;
//  TIdIRCOnChannelMode = procedure (Sender: TObject; Nick, Address, Channel: String) of object;

  TIdIRC = class(TIdTCPClient)
  protected
    { Property fields }
    FNick: String;
    FAltNick: String;
    FUsername: String;
    FRealName: String;
    FServer: String;
    //FPort: Integer;
    FPassword: String;
    FUserMode: TIdIRCUserModes;
    FAway: Boolean;
    FNotify: TStringList;
    FReplies: TIdIRCReplies;
    FState: TIdIRCState;
    FCurrentNick: String;
    FData: TObject;
    { Event handlers }
    FOnMessage: TIdIRCOnMessage;
    FOnNotice: TIdIRCOnMessage;
    FOnAction: TIdIRCOnMessage;
    FOnConnect: TNotifyEvent;
    FOnDisconnect: TNotifyEvent;
    FOnJoin: TIdIRCOnJoin;
    FOnJoined: TIdIRCOnJoined;
    FOnPart: TIdIRCOnPart;
    FOnParted: TIdIRCOnParted;
    FOnKick: TIdIRCOnKick;
    FOnKicked: TIdIRCOnKicked;
    FOnNickChange: TIdIRCOnNickChange;
    FOnNickChanged: TIdIRCOnNickChanged;
    FOnTopic: TIdIRCOnTopic;
    FOnQuit: TIdIRCOnQuit;
    FOnNames: TIdIRCOnNames;
    FOnInvite: TIdIRCOnInvite;
    FOnPingPong: TNotifyEvent;
    FOnError: TIdIRCOnError;
    FOnAway: TIdIRCOnAway;
    FOnNowAway: TIdIRCOnAwayChange;
    FOnUnAway: TIdIRCOnAwayChange;
    FOnWallops: TIdIRCOnWallops;
    FOnSystem: TIdIRCOnSystem;
    FOnRaw: TIdIRCOnRaw;
    FOnOp: TIdIRCOnOp;
    FOnDeop: TIdIRCOnOp;
    FOnBan: TIdIRCOnBan;
    FOnUnban: TIdIRCOnBan;
    FOnVoice: TIdIRCOnOp;
    FOnDevoice: TIdIRCOnOp;
    FOnChannelMode: TIdIRCOnChannelMode;
    FOnChannelModeChanged: TIdIRCOnChannelModeChanged;
    FOnUserMode: TIdIRCOnUserMode;
    FOnUserModeChanged: TNotifyEvent;
    FOnInviting: TIdIRCOnInviting;
    FOnKill: TIdIRCOnKill;
    FOnUnknownCommand: TIdIRCOnUnknownCommand;
    FOnCTCPQuery: TIdIRCOnCTCPQuery;
    FOnCTCPReply: TIdIRCOnCTCPReply;
    FOnStateChange: TNotifyEvent;
    FOnSend: TIdIRCOnSend;
    FOnReceive: TIdIRCOnSend;
    FOnNicksInUse: TIdIRCOnNicksInUse;
    FOnSocketError: TIdIRCOnSocketError;
    FOnNoTopic: TIdIRCOnNoTopic;
    FOnDCCChat: TIdIRCOnDCCChat;
    FOnDCCSend: TIdIRCOnDCCSend;
    FOnDCCResume: TIdIRCOnDCCResume;
    FOnDCCAccept: TIdIRCOnDCCAccept;
    FOnLinks: TIdIRCOnLinks;
    FOnList: TIdIRCOnList;
//    FOnChannelMode: TIdIRCOnChannelMode;
    FOnChannelUpdate: TIdIRCOnChannelUpdate;
    { Other fields }
    FList: TStringList;
    FListLast: Integer;
    Token: TStringList;
    FullCommand: String;
    SenderNick: String;
    SenderAddress: String;
    Command: String;
    Content: String;
    FChannels: TIdIRCChannels;
    FUsers: TIdIRCUsers;
    FUser: TIdIRCUser;
    FIRCThread: TIdIRCReadThread;
    { Socket }
    FBuffer: String;
    { Socket event handlers }
    procedure SocketDataAvailable;
    //procedure SocketSessionClosed(Sender: TObject);
    //procedure SocketSessionConnected(Sender: TObject);
    { Property methods }
    procedure SetNick(AValue: String);
    function GetNick: String;
    procedure SetAltNick(AValue: String);
    procedure SeTIdIRCUsername(AValue: String);
    procedure SetRealName(AValue: String);
    procedure SetPassword(AValue: String);
    procedure SeTIdIRCUserMode(AValue: TIdIRCUserModes);
    procedure SeTIdIRCReplies(AValue: TIdIRCReplies);
    //procedure SetServer(Value: String);
    //procedure SetPort(Value: Integer);
    //function GetConnected: Boolean;
    //function GetLocalHost: String;
    //function GetLocalIPAddr: String;
    //procedure SetSocksServer(Value: String);
    //procedure SetSocksPort(Value: String);
    { Other methods }
    procedure SeTIdIRCState(AState: TIdIRCState);
    procedure TokenizeCommand;
    function MatchCommand: Integer;
    procedure ParseCommand;
    function MatchDCC(ADCC: String): Integer;
    function MatchCTCP(ACTCP: String): Integer;
    procedure ParseDCC(ADCC: String);
    procedure ParseCTCPQuery;
    procedure ParseCTCPReply;
    function ParseChannelModeChange(AChannelToken: Integer): Boolean;
    function ParseUserModeChange: Boolean;
  public

    constructor Create(AOwner: TComponent); override;
    destructor Destroy; override;
    procedure Loaded; override;
    procedure Connect(const ATimeout: Integer = IdTimeoutDefault); override;
    procedure Disconnect(AForce: Boolean); reintroduce; overload;
    function IsChannel(AChannel: String): Boolean;
    function IsOp(ANick: String): Boolean;
    function IsVoice(ANick: String): Boolean;
    function MatchHostmask(AAddress, AHostmask: PChar): Boolean;
    procedure Raw(ALine: String);
    procedure Say(ATarget, AMsg: String);
    procedure Notice(ATarget, AMsg: String);
    procedure Action(ATarget, AMsg: String);
    procedure CTCPQuery(ATarget, ACommand, AParameters: String);
    procedure CTCPReply(ATarget, ACTCP, AReply: String);
    procedure Join(AChannels : String; const AKeys: String ='');    {Do not Localize}
    procedure Part(AChannels : String; const AReason: String = '');    {Do not Localize}
    procedure Kick(AChannel, ANick, AReason: String);
    procedure Quit(AReason: String);
    procedure Mode(AChannel, AModes : String; const AParams: String = '');    {Do not Localize}
    procedure GetTopic(AChannel: String);
    procedure SetTopic(AChannel, ATopic: String);
    procedure SetAwayMessage(AMsg: String);
    procedure ClearAwayMessage;
    function GetModeString: String;
    { Public properties }
    //property Connected: Boolean read GetConnected;
    property Away: Boolean read FAway;
    property Notify: TStringList read FNotify write FNotify;
    property State: TIdIRCState read FState;
    property Channels: TIdIRCChannels read FChannels;
    property Users: TIdIRCUsers read FUsers;

    property IRCThread: TIdIRCReadThread read FIRCThread;
    //property LocalHost: String read GetLocalHost;
    //property LocalIPAddr: String read GetLocalIPAddr;
    //property Data: TObject read FData write FData;
  published
    { Published properties }
    property Nick: String read GetNick write SetNick;
    property AltNick: String read FAltNick write SetAltNick;
    property Username: String read FUsername write SeTIdIRCUsername;
    property RealName: String read FRealName write SetRealName;
    //property Server: String read FServer write SetServer;
    //property Port: Integer read FPort write SetPort;
    property Port default IdPORT_IRC;
    property Password: String read FPassword write SetPassword;
    property Replies: TIdIRCReplies read FReplies write SeTIdIRCReplies;
    property UserMode: TIdIRCUserModes read FUserMode write SeTIdIRCUserMode;
    //property SocksServer: String read FSocksServer write SetSocksServer;
    //property SocksPort: String read FSocksPort write SetSocksPort;
    { Published events }
    property OnMessage: TIdIRCOnMessage read FOnMessage write FOnMessage;
    property OnNotice: TIdIRCOnMessage read FOnNotice write FOnNotice;
    property OnAction: TIdIRCOnMessage read FOnAction write FOnAction;
    property OnConnect: TNotifyEvent read FOnConnect write FOnConnect;
    property OnDisconnect: TNotifyEvent read FOnDisconnect write FOnDisconnect;
    property OnJoin: TIdIRCOnJoin read FOnJoin write FOnJoin;
    property OnJoined: TIdIRCOnJoined read FOnJoined write FOnJoined;
    property OnPart: TIdIRCOnPart read FOnPart write FOnPart;
    property OnParted: TIdIRCOnParted read FOnParted write FOnParted;
    property OnKick: TIdIRCOnKick read FOnKick write FOnKick;
    property OnKicked: TIdIRCOnKicked read FOnKicked write FOnKicked;
    property OnNickChange: TIdIRCOnNickChange read FOnNickChange write FOnNickChange;
    property OnNickChanged: TIdIRCOnNickChanged read FOnNickChanged write FOnNickChanged;
    property OnTopic: TIdIRCOnTopic read FOnTopic write FOnTopic;
    property OnQuit: TIdIRCOnQuit read FOnQuit write FOnQuit;
    property OnNames: TIdIRCOnNames read FOnNames write FOnNames;
    property OnInvite: TIdIRCOnInvite read FOnInvite write FOnInvite;
    property OnPingPong: TNotifyEvent read FOnPingPong write FOnPingPong;
    property OnError: TIdIRCOnError read FOnError write FOnError;
    property OnAway: TIdIRCOnAway read FOnAway write FOnAway;
    property OnNowAway: TIdIRCOnAwayChange read FOnNowAway write FOnNowAway;
    property OnUnAway: TIdIRCOnAwayChange read FOnUnAway write FOnUnAway;
    property OnWallops: TIdIRCOnWallops read FOnWallops write FOnWallops;
    property OnSystem: TIdIRCOnSystem read FOnSystem write FOnSystem;
    property OnRaw: TIdIRCOnRaw read FOnRaw write FOnRaw;
    property OnOp: TIdIRCOnOp read FOnOp write FOnOp;
    property OnDeop: TIdIRCOnOp read FOnDeop write FOnDeop;
    property OnBan: TIdIRCOnBan read FOnBan write FOnBan;
    property OnUnban: TIdIRCOnBan read FOnUnban write FOnUnban;
    property OnVoice: TIdIRCOnOp read FOnVoice write FOnVoice;
    property OnDevoice: TIdIRCOnOp read FOnDevoice write FOnDevoice;
    property OnChannelMode: TIdIRCOnChannelMode read FOnChannelMode write FOnChannelMode;
    property OnChannelModeChanged: TIdIRCOnChannelModeChanged read FOnChannelModeChanged write FOnChannelModeChanged;
    property OnUserMode: TIdIRCOnUserMode read FOnUserMode write FOnUserMode;
    property OnUserModeChanged: TNotifyEvent read FOnUserModeChanged write FOnUserModeChanged;
    property OnInviting: TIdIRCOnInviting read FOnInviting write FOnInviting;
    property OnKill: TIdIRCOnKill read FOnKill write FOnKill;
    property OnUnknownCommand: TIdIRCOnUnknownCommand read FOnUnknownCommand write FOnUnknownCommand;
    property OnCTCPQuery: TIdIRCOnCTCPQuery read FOnCTCPQuery write FOnCTCPQuery;
    property OnCTCPReply: TIdIRCOnCTCPReply read FOnCTCPReply write FOnCTCPReply;
    property OnStateChange: TNotifyEvent read FOnStateChange write FOnStateChange;
    property OnSend: TIdIRCOnSend read FOnSend write FOnSend;
    property OnReceive: TIdIRCOnSend read FOnReceive write FOnReceive;
    property OnNicksInUse: TIdIRCOnNicksInUse read FOnNicksInUse write FOnNicksInUse;
    property OnSocketError: TIdIRCOnSocketError read FOnSocketError write FOnSocketError;
    property OnNoTopic: TIdIRCOnNoTopic read FOnNoTopic write FOnNoTopic;
    property OnDCCChat: TIdIRCOnDCCChat read FOnDCCChat write FOnDCCChat;
    property OnDCCSend: TIdIRCOnDCCSend read FOnDCCSend write FOnDCCSend;
    property OnDCCResume: TIdIRCOnDCCResume read FOnDCCResume write FOnDCCResume;
    property OnDCCAccept: TIdIRCOnDCCAccept read FOnDCCAccept write FOnDCCAccept;
    property OnLinks: TIdIRCOnLinks read FOnLinks write FOnLinks;
    property OnList: TIdIRCOnList read FOnList write FOnList;
//    property OnChannelMode: TIdIRCOnChannelMode read FOnChannelMode write FOnChannelMode;
    property OnChannelUpdate: TIdIRCOnChannelUpdate read FOnChannelUpdate write FOnChannelUpdate;
  End;//TIdIRC

const
  { RFC1459 specifies 15 as the maximum token count in any one line. }
  { I changed this to 30, becuase 15 causes problems on servers that don't stick to RFC  - MRE - 4/16/02}
  IdIrcMinTokenCount: Byte = 30;

implementation

uses
  IdResourceStrings,
  SysUtils;

const
  { Responses from the server that do not appear as a numeric. }
  Commands: Array [0..12] of String = ('PRIVMSG', 'NOTICE', 'JOIN', 'PART', 'KICK', 'MODE',    {Do not Localize}
    'NICK', 'QUIT', 'INVITE', 'KILL', 'PING', 'WALLOPS', 'TOPIC');    {Do not Localize}
  { Standard CTCP queries and replies. }
  CTCPs: Array [0..9] of String = ('ACTION', 'SOUND', 'PING', 'FINGER', 'USERINFO', 'VERSION',    {Do not Localize}
    'CLIENTINFO', 'TIME', 'ERROR', 'DCC');    {Do not Localize}
  { Standard DCC queries and replies. }
  DCCs: Array [0..3] of String = ('SEND', 'CHAT', 'RESUME', 'ACCEPT');

  { The characters for the channel modes. In the same order as TIdIRCChannelModes. }
  ChannelModeChars: array [0..7] of Char = ('p', 's', 'i', 't', 'n', 'm', 'l', 'k');    {Do not Localize}
  { The characters for the user modes.  In the same order as TIdIRCUserModes. }
  UserModeChars: array [0..3] of Char = ('i', 'o', 's', 'w');    {Do not Localize}
  { Default CTCP Version and ClientInfo replies (just a bit of advertising if
    the client coder forgets to specify any other values). }

  IRCChannelPrefixes = ['&','#','+','!']; {do not translate}    {Do not Localize}

{ Register the component TIdIRC in Delphi. }

{ //////////////////////////////////////////////////////////////////////////// }
{ TIdIRCUser }
{ //////////////////////////////////////////////////////////////////////////// }

{ Create a new user in our list. }
constructor TIdIRCUser.Create(AClient: TIdIRC; ANick, AAddress: String);
begin
  inherited Create( AClient.Users );
  FClient := AClient;
  FNick := ANick;
  FAddress := AAddress;
  FData := nil;
  FReason := '';    {Do not Localize}
  Count := 1;
end;

{ Delete the user from our list. }
destructor TIdIRCUser.Destroy;
begin
  inherited Destroy;
end;

{ Send a private message to the user. }
procedure TIdIRCUser.Say(AMsg: String);
begin
  FClient.Say(FNick, AMsg);
end;

{ //////////////////////////////////////////////////////////////////////////// }
{ TIdIRCUsers }
{ //////////////////////////////////////////////////////////////////////////// }

{ Create the list of users. }
constructor TIdIRCUsers.Create(AClient: TIdIRC);
begin
  inherited Create (TIdIRCUser);
  FClient := AClient;
end;

{ Delete the list of users. }
destructor TIdIRCUsers.Destroy;
begin
  inherited Destroy;
end;

procedure TIdIRCUsers.SetItem ( Index: Integer; const Value: TIdIRCUser );
begin
 inherited SetItem (Index, Value);
end;

{inherited GetItem for our items property}

function TIdIRCUsers.GetItem(Index: Integer): TIdIRCUser;
begin
  Result := TIdIRCUser( inherited GetItem(Index));
end;

{ Increments the reference count for the user.  If the user does not exist,
  then a new user is created with a reference count of one.  If the user
  already exists, the address is updated.  Returns the user object. }
function TIdIRCUsers.Add(ANick, AAddress: String): TIdIRCUser;
var
  Index: Integer;
begin
  if Find(ANick, Index) then
  { The user already exists, so increment the reference count. }
  begin
    Result := Items[Index];
    if (AAddress <> '') and (Result.Address <> AAddress) then    {Do not Localize}
    begin
      Result.Address := AAddress;
    end;
    Inc(Result.Count);
  end
  else
  { Create a new user with a reference count of one. }
  begin

    Result := TIdIRCUser.Create(FClient, ANick, AAddress);

  end;
end;

{ Decrement the reference count for this user.  If the reference count becomes
  zero, then delete the user object and remove the nick from the list (if the
  nick in the list refers to the same user object). }
procedure TIdIRCUsers.Remove(AUser: TIdIRCUser);
var
  Index: Integer;
begin
  Dec(AUser.Count);
  if AUser.Count = 0 then
  begin
    if Find(AUser.Nick, Index) and ((Items[Index] as TIdIRCUser) = AUser) then
    begin
      Items[Index].Free;
    end;
  end;
end;

{ Returns the address for the specified nick, if available. }
function TIdIRCUsers.Address(ANick: String): String;
var
  Index: Integer;
begin
  Result := '';    {Do not Localize}
  if Find(ANick, Index) then
  begin
    Result := Items[Index].Address;
  end;
end;

{ Searches for the given nick. Returns True and the index number of the nick
  if found. }
function TIdIRCUsers.Find(ANick: String; var AIndex: Integer): Boolean;
begin
  { Need a case-insensitive search.  So it has to be done manually, I guess. }
  Result := False;
  AIndex := 0;
  while AIndex < Count do
  begin
    Result := AnsiCompareText(ANick, Items[AIndex].FNick) = 0;
    if Result then
    begin
      Exit;
    end;
    Inc(AIndex);
  end;
  { Search failed, so Index is set to -1. }
  AIndex := -1;
end;

{ Returns the user object for the given nick.  If the nick is not found, then
  it returns nil. }
function TIdIRCUsers.Get(ANick: String): TIdIRCUser;
var
  Index: Integer;
begin
  Result := nil;
  if Find(ANick, Index) then
  begin
    Result := Items[Index];
  end;
end;

{sort user list}
procedure TIdIRCUsers.Sort;
{I found this procedure at:

http://groups.google.com/groups?q=Sort+TCollection&start=30&hl=en&safe=off&rnum=35&selm=904181166%40f761.n5030.z2.FidoNet.ftn

and it seems to look good.}

  function DoCompare(AItem1, AItem2 : TIdIRCUser) : Integer;
  begin
    if Assigned(FOnSortCompareUsers) then
    begin
      FOnSortCompareUsers(Self,AItem1, AItem2, Result);
    end
    else
    begin
      Result := 0;
    end;
  end;

  procedure SwapItems(i, j : Integer);
  var
    T : TIdIRCUser;
  begin
    T := Items[i];
    Items[i] := Items[j];
    Items[j] := T;
  end;

  procedure SortItems(iStart, iEnd : Integer);
  var
    i, j : Integer;
    Med : TIdIRCUser;
  begin
    while iStart < iEnd do
    begin
      i := iStart;
      j := iEnd;

      if iStart = iEnd-1 then
      begin
        if DoCompare(Items[iStart], Items[iEnd]) > 0 then
        begin
          SwapItems(iStart, iEnd);
        end;
        Break;
      end;

      Med := Items[(i + j) div 2];

      repeat
        while DoCompare(Items[i], Med) < 0 do
        begin
          Inc(i);
        end;
        while DoCompare(Items[j], Med) > 0 do
        begin
          Dec(j);
        end;
        if i <= j then
        begin
          SwapItems(i, j);
          Inc(i);
          Dec(j);
        end;
      until i > j;

      if j-iStart > iEnd-i then
      begin
        SortItems(i, iEnd);
        iEnd := j;
      end
      else
      begin
        SortItems(iStart, j);
        iStart := i;
      end;
    end;
  end;

begin
  if Count > 0 then
  begin
    SortItems(0, Count - 1);
  end;
end;

{ Changes the user's nick. }    {Do not Localize}
procedure TIdIRCUsers.Nick(AFromNick, AToNick: String);
var
  Index: Integer;
  User: TIdIRCUser;
begin
  if Find(AFromNick, Index) then
  begin
    User := Items[Index];
    User.Nick := AToNick;
{I'm leaving this all commented because I'm not sure if it is needed or not due   
to some comments made by the author}
{    items[Index].Free;
    if Find(AToNick, Index) then
    { The ToNick already exists (probably from the previous user having quit
      IRC and a query window is still open), so replace the existing user
      object with the new user object.}
{      FNickList.Objects[Index] := User
    else
    { Add the user to the list with the new nick. }
{    begin
      Index := FNickList.Add(AToNick);
      FNickList.Objects[Index] := User;
    end;  }
  end;
end;

{ Purge the users list. }


{ //////////////////////////////////////////////////////////////////////////// }
{ TIdIRCChannel }
{ //////////////////////////////////////////////////////////////////////////// }

{ Create a new channel in the channel list. }
constructor TIdIRCChannel.Create(AClient: TIdIRC; AName: String);
begin
  inherited Create(AClient.FChannels);
  FClient := AClient;
  FName := AName;
  FTopic := '';    {Do not Localize}
  FMode := [];
  FLimit := 0;
  FKey := '';    {Do not Localize}
  FNames := TStringList.Create;
  FBans := TStringList.Create;
  FModeChange := False;
  FActive := False;
  FCloseType := ctReset;
  FData := nil;
  { Attach the event handler for channel updates. }
  FOnChannelUpdate := FClient.OnChannelUpdate;
end;

{ Delete a channel from the channel list. }
destructor TIdIRCChannel.Destroy;
begin
  FNames.Free;
  FBans.Free;
  inherited Destroy;
end;

{ Set the topic of the channel. }
procedure TIdIRCChannel.SetTopic(AValue: String);
begin
  FClient.SetTopic(FName, AValue);
end;

{ Compile a mode command to change the mode of the channel. }
procedure TIdIRCChannel.SetMode(AValue: TIdIRCChannelModes);
var
  Element: TIdIRCChannelMode;
  Difference: TIdIRCChannelModes;
  TempOptions: String;
begin
  TempOptions := '';    {Do not Localize}
  { If no difference in modes, then exit. }
  if FMode = AValue then
  begin
    Exit;
  end;
  { Calculate which modes have been removed. }
  Difference := FMode - AValue;
  if Difference <> [] then
  begin
    if ChangeType <> ctSubtract then
    begin
      TempOptions := TempOptions + '-';    {Do not Localize}
      ChangeType := ctSubtract;
    end;
    for Element := cmPrivate to cmKey do
    begin
      if Element in Difference then
      begin
        TempOptions := TempOptions + ChannelModeChars[Ord(Element)];
      end;
    end;
  end;
  { Calculate which modes have been added. }
  Difference := AValue - FMode;
  if Difference <> [] then
  begin
    if ChangeType <> ctAdd then
    begin
      TempOptions := TempOptions + '+';    {Do not Localize}
      ChangeType := ctAdd;
    end;
    { Will not add Limit or Key modes.  These must be added with the Limit and
      Key properties. }
    for Element := cmPrivate to cmKey do
    begin
      if (Element <> cmUserLimit) and (Element <> cmKey) then
      begin
        if Element in Difference then
        begin
          TempOptions := TempOptions + ChannelModeChars[Ord(Element)];
        end;
      end;
    end;
  end;
  { If compiling mode changes. }
  if FModeChange then
  begin
    { Add the mode change. }
    ModeOptions := ModeOptions + TempOptions;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, TempOptions, '');    {Do not Localize}
  end;
end;

procedure TIdIRCChannel.SetLimit(AValue: Integer);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctAdd then
    begin
      ModeOptions := ModeOptions + '+';    {Do not Localize}
      ChangeType := ctAdd;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'l';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + Format('%s', [FLimit]);    {Do not Localize}
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '+l', Format('%s', [FLimit]));    {Do not Localize}
  end;
end;

procedure TIdIRCChannel.SetKey(AValue: String);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctAdd then
    begin
      ModeOptions := ModeOptions + '+';    {Do not Localize}
      ChangeType := ctAdd;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'k';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + FKey;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '+k', FKey);    {Do not Localize}
  end;
end;

{ Send a message to the channel. }
procedure TIdIRCChannel.Say(AMsg: String);
begin
  FClient.Say(FName, AMsg);
end;

{ Part the channel. }
procedure TIdIRCChannel.Part(AReason: String);
begin
  FClient.Part(FName, AReason);
end;

{ Kick a person from the channel. }
procedure TIdIRCChannel.Kick(ANick, AReason: String);
begin
  FClient.Kick(FName, ANick, AReason);
end;

{ Begin compiling all subsequent mode changes into one mode command. }
procedure TIdIRCChannel.BeginMode;
begin
  ModeOptions := '';    {Do not Localize}
  ModeParams := '';    {Do not Localize}
  ChangeType := ctNone;
  FModeChange := True;
end;

{ Send all compiled mode changes as one mode command. }
procedure TIdIRCChannel.EndMode;
begin
  { If no mode changes have been compiled, then do not send the command. }
  if ModeOptions <> '' then    {Do not Localize}
  begin
    FClient.Mode(FName, ModeOptions, ModeParams);
  end;
  FModeChange := False;
end;

{ Give a user channel operator status. }
procedure TIdIRCChannel.Op(ANick: String);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctAdd then
    begin
      ModeOptions := ModeOptions + '+';    {Do not Localize}
      ChangeType := ctAdd;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'o';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + ANick;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '+o', ANick);    {Do not Localize}
  end;
end;

{ Remove channel operator status from a user. }
procedure TIdIRCChannel.Deop(ANick: String);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctSubtract then
    begin
      ModeOptions := ModeOptions + '-';    {Do not Localize}
      ChangeType := ctSubtract;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'o';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + ANick;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '-o', ANick);    {Do not Localize}
  end;
end;

{ Give a user a voice in a moderated channel. }
procedure TIdIRCChannel.Voice(ANick: String);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctAdd then
    begin
      ModeOptions := ModeOptions + '+';    {Do not Localize}
      ChangeType := ctAdd;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'v';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + ANick;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '+v', ANick);    {Do not Localize}
  end;
end;

{ Remove the voice from a user in a moderated channel. }
procedure TIdIRCChannel.Devoice(ANick: String);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctSubtract then
    begin
      ModeOptions := ModeOptions + '-';    {Do not Localize}
      ChangeType := ctSubtract;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'v';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + ANick;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '-v', ANick);    {Do not Localize}
  end;
end;

{ Ban a user from the channel. }
procedure TIdIRCChannel.Ban(AHostmask: String);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctAdd then
    begin
      ModeOptions := ModeOptions + '+';    {Do not Localize}
      ChangeType := ctAdd;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'b';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + AHostmask;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '+b', AHostmask);    {Do not Localize}
  end;
end;

{ Remove the ban from the channel. }
procedure TIdIRCChannel.Unban(AHostmask: String);
begin
  { If compiling mode changes. }
  if FModeChange then
  begin
    { If the change type needs to be modified. }
    if ChangeType <> ctSubtract then
    begin
      ModeOptions := ModeOptions + '-';    {Do not Localize}
      ChangeType := ctSubtract;
    end;
    { Add the mode change. }
    ModeOptions := ModeOptions + 'b';    {Do not Localize}
    { If there are already some parameters, then add a space separator. }
    if ModeParams <> '' then    {Do not Localize}
    begin
      ModeParams := ModeParams + ' ';    {Do not Localize}
    end;
    { Add the parameter. }
    ModeParams := ModeParams + AHostmask;
  end
  { Send the mode change immediately. }
  else
  begin
    FClient.Mode(FName, '-b', AHostmask);    {Do not Localize}
  end;
end;

{ Call to change the topic without sending a topic command. }
procedure TIdIRCChannel.TopicChanged(ATopic: String);
begin
  if FTopic <> ATopic then
  begin
    FTopic := ATopic;
  end;
  if Assigned(FOnChannelUpdate) then
  begin
    FOnChannelUpdate(Self, cuTopic, nil, 0);
  end;
end;

{ Call to change the channel mode without sending a mode command. }
procedure TIdIRCChannel.ModeChanged(AMode: TIdIRCChannelModes);
begin
  if FMode <> AMode then
  begin
    FMode := AMode;
  end;
  if Assigned(FOnChannelUpdate) then
  begin
    FOnChannelUpdate(Self, cuMode, nil, 0);
  end;
end;

{ Call to change the channel limit without sending a mode command. }
procedure TIdIRCChannel.LimitChanged(ALimit: Integer);
begin
  if FLimit <> ALimit then
  begin
    FLimit := ALimit;
  end;
  if Assigned(FOnChannelUpdate) then
  begin
    FOnChannelUpdate(Self, cuMode, nil, 0);
  end;
end;

{ Call to change the channel key without sending a mode command. }
procedure TIdIRCChannel.KeyChanged(AKey: String);
begin
  if FKey <> AKey then
  begin
    FKey := AKey;
  end;
  if Assigned(FOnChannelUpdate) then
  begin
    FOnChannelUpdate(Self, cuMode, nil, 0);
  end;
end;

{ Return a string representation of the channel mode. }
function TIdIRCChannel.GetModeString: String;
var
  Element: TIdIRCChannelMode;
begin
  { Only bother if there are actually modes to show. }
  if FMode <> [] then
  begin
    Result := '+';    {Do not Localize}
    { Add all mode characters. }
    for Element := cmPrivate to cmKey do
    begin
      if Element in FMode then
      begin
        Result := Result + ChannelModeChars[Ord(Element)];
      end;
    end;
    { Add limit if present. }
    if cmUserLimit in FMode then
    begin
      Result := Format('%s %d', [Result, FLimit]);    {Do not Localize}
    end;
    { Add key if present. }
    if cmKey in FMode then
    begin
      Result := Format('%s %s', [Result, FKey]);    {Do not Localize}
    end
  end
  else
  begin
    Result := '';    {Do not Localize}
  end;
end;

{ Add a new user to the channel. }
function TIdIRCChannel.AddUser(ANick, AAddress: String): TIdIRCUser;
var
  IsOp, HasVoice: Boolean;
  Attributes, Index: Integer;
begin
  { Op and Voice status are declared by @ and + symbols.  If a person has voice
    only, then the + is placed before the nick.  If the person has ops, then the
    @ symbol is placed before the nick.  If the person has ops and voice (rather
    pointless, but can happen) then the @ goes in front and the + goes at the
    end. }
  IsOp := (Length(ANick)>0) and (ANick[1] = '@');    {Do not Localize}
  Attributes := 0;
  if IsOp then
  begin
    Attributes := Attributes + 1;
    ANick := Copy(ANick, 2, Length(ANick) - 1);
    HasVoice := (Length(ANick)>0) and (ANick[Length(ANick)] = '+');    {Do not Localize}
    if HasVoice then
    begin
      Attributes := Attributes + 2;
      ANick := Copy(ANick, 1, Length(ANick) - 1);
    end;
  end
  else
  begin
    HasVoice := (Length(ANick)>0) and (ANick[1] = '+');    {Do not Localize}
    if HasVoice then
    begin
      Attributes := Attributes + 2;
      ANick := Copy(ANick, 2, Length(ANick) - 1);
    end;
  end;
  Result := nil;
  { If the nick already exists, don't add. }    {Do not Localize}
  if not Find(ANick, Index) then
  begin
    { Add this user to the list. }
    Result := FClient.Users.Add(ANick, AAddress);
    FNames.AddObject(ANick, Pointer(Attributes));
  end;
end;

{ Remove a user from the channel. }
procedure TIdIRCChannel.RemoveUser(AUser: TIdIRCUser);
var
  Index: Integer;
begin
  if Find(AUser.Nick, Index) then
  begin
    FNames.Delete(Index);
    { Release the user object. }
    FClient.Users.Remove(AUser);
  end;
end;

{ Returns True if the user is in the channel. }
function TIdIRCChannel.HasUser(ANick: String): Boolean;
var
  Index: Integer;
begin
  Result := Find(ANick, Index);
end;

{ Search for a nick in the channel. }
function TIdIRCChannel.Find(ANick: String; var AIndex: Integer): Boolean;
begin
  { Need a case-insensitive search.  So it has to be done manually, I guess. }
  Result := False;
  AIndex := 0;
  while AIndex < FNames.Count do
  begin
    Result := AnsiCompareText(ANick, FNames[AIndex]) = 0;
    if Result then
    begin
      Exit;
    end;
    Inc(AIndex);
  end;
  { Search failed, so Index is set to -1. }
  AIndex := -1;
end;

{ User got op status. }
procedure TIdIRCChannel.GotOp(AUser: TIdIRCUser);
var
  Index, Attr: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    { Add the op flag. }
    Attr := Integer(FNames.Objects[Index]) or 1;
    FNames.Objects[Index] := Pointer(Attr);
    { Tell the world we changed this user's status. }    {Do not Localize}
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuUser, AUser, Attr);
    end;
  end;
end;

{ User lost op status. }
procedure TIdIRCChannel.GotDeop(AUser: TIdIRCUser);
var
  Index, Attr: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check Aif the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    { Remove the op flag. }
    Attr := Integer(FNames.Objects[Index]) and (not 1);
    FNames.Objects[Index] := Pointer(Attr);
    { Tell the world we changed this user's status. }    {Do not Localize}
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuUser, AUser, Attr);
    end;
  end;
end;

{ User got voice status. }
procedure TIdIRCChannel.GotVoice(AUser: TIdIRCUser);
var
  Index, Attr: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    { Add the voice flag. }
    Attr := Integer(FNames.Objects[Index]) or 2;
    FNames.Objects[Index] := Pointer(Attr);
    { Tell the world we changed this user's status. }    {Do not Localize}
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuUser, AUser, Attr);
    end;
  end;
end;

{ User lost voice status. }
procedure TIdIRCChannel.GotDevoice(AUser: TIdIRCUser);
var
  Index, Attr: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    { Remove the voice flag. }
    Attr := Integer(FNames.Objects[Index]) and (not 2);
    FNames.Objects[Index] := Pointer(Attr);
    { Tell the world we changed this user's status. }    {Do not Localize}
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuUser, AUser, Attr);
    end;
  end;
end;

{ User changed nick. }
procedure TIdIRCChannel.ChangedNick(AUser: TIdIRCUser; ANewNick: String);
var
  Index: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    FNames[Index] := ANewNick;
    { Tell the world this user changed nick. }
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuNick, AUser, Index);
    end;
  end;
end;

{ User joined. }
procedure TIdIRCChannel.Joined(AUser: TIdIRCUser);
var
  Index: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    Exit;
  end;
  { Add to the names list. }
  Index := FNames.AddObject(AUser.Nick, Pointer(0));
  { Tell the world this user joined. }
  if Assigned(FOnChannelUpdate) then
  begin
    FOnChannelUpdate(Self, cuJoin, AUser, Index);
  end;
end;

{ User parted. }
procedure TIdIRCChannel.Parted(AUser: TIdIRCUser);
var
  Index: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    { Tell the world this user quit. }
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuPart, AUser, Index);
    end;
    { Remove from the names list. }
    FNames.Delete(Index);
  end;
end;

{ User was kicked. }
procedure TIdIRCChannel.Kicked(AUser: TIdIRCUser);
var
  Index: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    { Tell the world this user was kicked. }
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuKick, AUser, Index);
    end;
    { Remove from the names list. }
    FNames.Delete(Index);
  end;
end;

{ User quit. }
procedure TIdIRCChannel.Quit(AUser: TIdIRCUser);
var
  Index: Integer;
begin
  { No user object, so fail. }
  if AUser = nil then
  begin
    Exit;
  end;
  { Check if the user is in this channel. }
  if Find(AUser.Nick, Index) then
  begin
    { Tell the world this user quit. }
    if Assigned(FOnChannelUpdate) then
    begin
      FOnChannelUpdate(Self, cuQuit, AUser, Index);
    end;
    { Remove from the names list. }
    FNames.Delete(Index);
  end;
end;

{ //////////////////////////////////////////////////////////////////////////// }
{ TIdIRCChannels }
{ //////////////////////////////////////////////////////////////////////////// }

{ Create the list of channels. }
constructor TIdIRCChannels.Create(AClient: TIdIRC);
begin
  inherited Create(TIdIRCChannel);
  FClient := AClient;
end;

{ Delete the list of channels. }
destructor TIdIRCChannels.Destroy;
begin
  inherited Destroy;
end;

{ Add a new channel. If channel of this name exists, delete the previous
  channel object and create a new object. Returns the channel object. }
function TIdIRCChannels.Add(AName: String): TIdIRCChannel;
var
  Index: Integer;
begin
  { Object of this name already exists, so delete it. }
  if Find(AName, Index) then
  begin
    Items[Index].Free;
  end;
  { Create new channel object and add it. }
  Result := TIdIRCChannel.Create(FClient, AName);
end;

{ Remove a channel. }
procedure TIdIRCChannels.Remove(AName: String);
var
  Index: Integer;
begin
  if Find(AName, Index) then
  begin
    Items[Index].Free;
  end;
end;

{ Search for a specific channel name, and return the index if found. }
function TIdIRCChannels.Find(AName: String; var AIndex: Integer): Boolean;
begin
  { Need a case-insensitive search.  So it has to be done manually, I guess. }
  Result := False;
  AIndex := 0;
  while AIndex < Count do
  begin
    Result := AnsiCompareText(AName, Items[AIndex].Name) = 0;
    if Result then
    begin
      Exit;
    end;
    Inc(AIndex);
  end;
  { Search failed, so Index is set to -1. }
  AIndex := -1;
end;

{ Return the channel object for the name given.  If the channel name is not
  found, then it returns nil. }
function TIdIRCChannels.Get(AName: String): TIdIRCChannel;
var
  Index: Integer;
begin
  Result := nil;
  if Find(AName, Index) then
  begin
    Result := GetItem(Index);
  end;
end;

{inherited SetItem for our items property}
procedure TIdIRCChannels.SetItem ( Index: Integer; const Value: TIdIRCChannel );
begin
 inherited SetItem (Index, Value);
end;

{inherited GetItem for our items property}

function TIdIRCChannels.GetItem(Index: Integer): TIdIRCChannel;
begin
  Result := TIdIRCChannel( inherited GetItem(Index));
end;

{ A user changed nick, so go through all channels and notify of the change. }
procedure TIdIRCChannels.ChangedNick(AUser: TIdIRCUser; ANewNick: String);
var
  Index: Integer;
begin
  for Index := 0 to Count - 1 do
  begin
    GetItem(Index).ChangedNick(AUser, ANewNick);
  end;
end;

{ A user quit, so go through all channels and notify of the quit. }
procedure TIdIRCChannels.Quit(AUser: TIdIRCUser);
var
  Index: Integer;
begin
  for Index := 0 to Count - 1 do
  begin
    GetItem(Index).Quit(AUser);
  end;
end;

{ //////////////////////////////////////////////////////////////////////////// }
{ TIdIRCReplies }
{ //////////////////////////////////////////////////////////////////////////// }

constructor TIdIRCReplies.Create;
begin
  inherited Create;
  FFinger := '';    {Do not Localize}
  FVersion := '';    {Do not Localize}
  FUserInfo := '';    {Do not Localize}
  FClientInfo := '';    {Do not Localize}
end;

procedure TIdIRCReplies.Assign(Source: TPersistent);
begin
  if Source is TIdIRCReplies then
  begin
    FFinger := TIdIRCReplies(Source).Finger;
    FVersion := TIdIRCReplies(Source).Version;
    FUserInfo := TIdIRCReplies(Source).UserInfo;
    FClientInfo := TIdIRCReplies(Source).ClientInfo;
  end;
end;

{ //////////////////////////////////////////////////////////////////////////// }
{ TIdIRC }
{ //////////////////////////////////////////////////////////////////////////// }

constructor TIdIRC.Create(AOwner: TComponent);
var
  Index: Integer;
begin
  inherited Create(AOwner);
  FList := TStringList.Create;
  FNotify := TStringList.Create;
  FReplies := TIdIRCReplies.Create;
  with FReplies do
  begin
    Finger := '';    {Do not Localize}
    Version := RSIRCClientVersion;
    UserInfo := '';    {Do not Localize}
    ClientInfo := Format(RSIRCClientInfo,[RSIRCClientVersion]);
  end;
  FNick := RSIRCNick;    {Do not Localize}
  FAltNick := RSIRCAltNick;    {Do not Localize}
  FUserName := RSIRCUserName;    {Do not Localize}
  FRealName := RSIRCRealName;    {Do not Localize}
  FServer := '';    {Do not Localize}
  Port := IdPORT_IRC;
  FUserMode := [];
  FState := csDisconnected;
  FCurrentNick := '';    {Do not Localize}
  FData := nil;
  { The following objects only needed during run-time. }
  if not (csDesigning in ComponentState) then
  begin
    Token := TStringList.Create;
    FChannels := TIdIRCChannels.Create(Self);
    FUsers := TIdIRCUsers.Create(Self);
    { Create a list of up to MinTokenCount tokens with a null string. }
    for Index := 0 to IdIrcMinTokenCount - 1 do
    begin
      Token.Add('');    {Do not Localize}
    end;
  end;
end;

destructor TIdIRC.Destroy;
begin
  { Free all allocated objects. }
  if not (csDesigning in ComponentState) then
  begin
    { If still connected, the leave gracefully. }
    if Connected then
    begin
      Disconnect(True);
    end;
    Token.Free;
    FChannels.Free;
    FUsers.Free;
  end;
  FList.Free;
  FNotify.Free;
  FReplies.Free;
  inherited Destroy;
end;

procedure TIdIRC.Loaded;
begin
  inherited Loaded;
end;

{ Data has arrived at the socket. }
procedure TIdIRC.SocketDataAvailable;
begin

  { Get all the data that we received and add it to the end of the current
    buffer. }
  if fState = csDisconnected then
  begin
    exit;
  end;
  FBuffer := IRCThread.FRecvData;
  FullCommand := FBuffer;

  if Length(FBuffer) > 0 then begin
    { Pass to the raw receive event handler. }
    if Assigned(FOnReceive) then begin
     FOnReceive(Self, FBuffer);
    end;
    { Process the received command. }
    ParseCommand;
  end;
end;

{ Connect to the IRC server. }
procedure TIdIRC.Connect;
var LOurAddr : String;
    LServerAddr : String;
begin
  { If already connected or in the process of connecting, the force a
    disconnect. }
  if Connected then
  begin
    Disconnect(TRUE);
  end;
  { Clear the channel and user lists. }
  FChannels.Clear;
  FUsers.Clear;
  { Get a user object for ourselves. }
  FUser := FUsers.Add(FNick, '');    {Do not Localize}
  { Set the current nick. }
  FCurrentNick := FNick;
  { Set the current state. }
  SeTIdIRCState(csConnecting);
  { Set the properties of the socket and start the connection process. }


  inherited Connect;

  SeTIdIRCState(csLoggingOn);

  try
    if Assigned(FOnConnect) then begin
      OnConnect(SELF);
    end;

    if Connected then begin
      FIRCThread := TIdIRCReadThread.Create(SELF);
    end;
  //we let the user override the IP address if they need to use the BoundIP
  //property (that may be needed for some multihorned computers on more than
  //one network.
  if (Length(BoundIP)>0) then
  begin
    LOurAddr := BoundIP;
  end
  else
  begin
    LOurAddr := GStack.LocalAddress;
  end;
  //we want to let the user override the Server parameter with their own if they
  //want.  Otherwise, just use our local address.
  if (Length(FServer)>0) then
  begin
    LServerAddr := FServer;
  end
  else
  begin
    LServerAddr := LOurAddr;
  end;
  { If there is a password supplied, then send it first. }
  if FPassword <> '' then    {Do not Localize}
  begin
    Raw(Format('PASS %s', [FPassword]));    {Do not Localize}
  end;
  { Send the nick and user information. }
  Raw(Format('NICK %s', [FNick]));    {Do not Localize}

  Raw(Format('USER %s %s %s :%s', [FUsername, LOurAddr, LServerAddr, FRealName]));    {Do not Localize}

//  SeTIdIRCState(csConnected);

  except
    on E: EIdSocketError do
      raise EComponentError.Create(RSIRCCanNotConnect);
  end;
end;

{ Force a disconnect from the IRC server. }
procedure TIdIRC.Disconnect(AForce: Boolean);
begin
    { Close the connection. }

    if (FState <> csConnected) and (AForce<>TRUE) then
    begin
      exit;
    end;
    { Release our user object. }
    FUsers.Remove(FUser);

    SeTIdIRCState(csDisconnect);
    if Assigned(FOnDisconnect) then
    begin
       FOnDisconnect(self);
    end;
    if Assigned(IRCThread) then begin
       // TODO: FreeOnTerminate:=FALSE; .Terminate; FreeAndNIL()
       IRCThread.Terminate;
    end;

    inherited Disconnect;
    SeTIdIRCState(csDisconnected);
    if Assigned(FOnDisconnected) then
    begin
       FOnDisconnected(Self);
    end;
end;

{ Send a command to the server. }
procedure TIdIRC.Raw(ALine: String);
begin
  { Send the string directly to the server without processing.  Line is
    terminated by CR-LF pair. }
  if Connected then
  begin
    WriteLn(Aline+#13#10);
    if Assigned(FOnSend) then
    begin
      FOnSend(Self, ALine);
    end;
  end
  else
  begin
    if Assigned(FOnError) then
    begin
      FOnError(Self, nil, '', RSIRCNotConnected);    {Do not Localize}
    end;
  end;
end;

{ Send a message to the specified target (channel or user). }
procedure TIdIRC.Say(ATarget, AMsg: String);
begin
  Raw(Format('PRIVMSG %s :%s', [ATarget, AMsg]));    {Do not Localize}
end;

{ Send a notice to the specified target (channel or user). }
procedure TIdIRC.Notice(ATarget, AMsg: String);
begin
  Raw(Format('NOTICE %s :%s', [ATarget, AMsg]));    {Do not Localize}
end;

{ Send an action (just a wrapper for a CTCP query). }
procedure TIdIRC.Action(ATarget, AMsg: String);
begin
  CTCPQuery(ATarget, 'ACTION', AMsg);    {Do not Localize}
end;

{ Send a CTCP request to the specifed target (channel or user). }
procedure TIdIRC.CTCPQuery(ATarget, ACommand, AParameters: String);
begin
  Say(ATarget, Format(#1'%s %s'#1, [Uppercase(ACommand), AParameters]));    {Do not Localize}
end;

{ Send a CTCP reply to the specified target (primarily a user, but could be a
  channel). }
procedure TIdIRC.CTCPReply(ATarget, ACTCP, AReply: String);
begin
  Notice(ATarget, Format(#1'%s %s'#1, [ACTCP, AReply]));    {Do not Localize}
end;

{ Join the channels, using the keys supplied.  Channels and Keys are comma-
  separated lists of channel names and keys for those channels that require
  a key. }
procedure TIdIRC.Join(AChannels : String; const AKeys: String = '');    {Do not Localize}
begin
  if Length(AKeys) <> 0 then
  begin
    Raw(Format('JOIN %s %s', [AChannels, AKeys]))    {Do not Localize}
  end
  else
  begin
    Raw(Format('JOIN %s', [AChannels]));    {Do not Localize}
  end;
end;

{ Part the channels, using the given reason (if the server supports part
  messages).  Channels is a comma-separated list of channel names to part. }
procedure TIdIRC.Part(AChannels : String; const AReason: String = '');    {Do not Localize}
begin
  if Length(AReason) <> 0 then
  begin
    Raw(Format('PART %s :%s', [AChannels, AReason]))    {Do not Localize}
  end
  else
  begin
    Raw(Format('PART %s', [AChannels]));    {Do not Localize}
  end;
end;

{ Kick a person from a channel. }
procedure TIdIRC.Kick(AChannel, ANick, AReason: String);
begin
  Raw(Format('KICK %s %s :%s', [AChannel, ANick, AReason]));    {Do not Localize}
end;

{ Quit IRC. }
procedure TIdIRC.Quit(AReason: String);
begin
  Raw(Format('QUIT :%s', [AReason]));    {Do not Localize}
end;

{ Set the mode of a channel. }
procedure TIdIRC.Mode(AChannel, AModes : String; const AParams: String = '');    {Do not Localize}
begin
  if AParams = '' then    {Do not Localize}
  begin
    Raw(Format('MODE %s %s', [AChannel, AModes]))    {Do not Localize}
  end
  else
  begin
    Raw(Format('MODE %s %s %s', [AChannel, AModes, AParams]));    {Do not Localize}
  end;
end;

{ Return True if connected, or in the process of connecting. }
{
function TIdIRC.GetConnected: Boolean;
begin
  Result := FState <> csDisconnected;
end;
}

{ Send the TOPIC command to retrieve the current topic and nick of the person
  who set the topic for the specified channel. }
procedure TIdIRC.GetTopic(AChannel: String);
begin
  Raw(Format('TOPIC %s', [AChannel]));    {Do not Localize}
end;

{ Set the topic of the specified channel to the string Topic. }
procedure TIdIRC.SetTopic(AChannel, ATopic: String);
begin
  Raw(Format('TOPIC %s :%s', [AChannel, ATopic]));    {Do not Localize}
end;

{ Set an away message. }
procedure TIdIRC.SetAwayMessage(AMsg: String);
begin
  Raw(Format('AWAY %s', [AMsg]));    {Do not Localize}
end;

{ Clear the away message. }
procedure TIdIRC.ClearAwayMessage;
begin
  Raw('AWAY');    {Do not Localize}
end;

{ Return the Nick property. }
function TIdIRC.GetNick: String;
begin
  if Connected then
  begin
    Result := FCurrentNick
  end
  else
  begin
    Result := FNick;
  end;
end;

{ Return the local host name. }
{
function TIdIRC.GetLocalHost: String;
begin
  Result := LocalHost;
end;
}

{ Return the local IP address. }
{
function TIdIRC.GetLocalIPAddr: String;
begin
  Result := FSocket.BoundIP;
end;
}

{ Change the user's nick. }    {Do not Localize}
procedure TIdIRC.SetNick(AValue: String);
begin
  { Only allow direct change if not connected... }
  if not Connected then
  begin
    if FNick <> AValue then
    begin
      FNick := AValue;
    end;
  end
  else
  begin
  { else send a NICK command and only change the nick if the command is
    successful }
    Raw(Format('NICK %s', [AValue]));    {Do not Localize}
  end;
end;

{ Change the user's alternative nick. }    {Do not Localize}
procedure TIdIRC.SetAltNick(AValue: String);
begin
  if FAltNick <> AValue then
  begin
    FAltNick := AValue;
  end;
end;

{ Change the user's username. }    {Do not Localize}
procedure TIdIRC.SeTIdIRCUsername(AValue: String);
begin
  if FUsername <> AValue then
  begin
    FUsername := AValue;
  end;
end;

{ Change the user's real name. }    {Do not Localize}
procedure TIdIRC.SetRealName(AValue: String);
begin
  if FRealName <> AValue then
  begin
    FRealName := AValue;
  end;
end;


{ Change the password for the server . }
procedure TIdIRC.SetPassword(AValue: String);
begin
  if FPassword <> AValue then
  begin
    FPassword := AValue;
  end;
end;

{ Change the user's mode. }    {Do not Localize}
procedure TIdIRC.SeTIdIRCUserMode(AValue: TIdIRCUserModes);
begin
  { Only allow direct change if not connected... }
  if not Connected then
  begin
    if FUserMode <> AValue then
    begin
      FUserMode := AValue;
    end;
  end
  else
  { else send a mode change command and only change the user mode if the
    command is successful }
  begin
    { Only modify the values that have actually changed }
    { FIXME: Needs to be completed. }
  end;
end;

{ Set the CTCP replies. }
procedure TIdIRC.SeTIdIRCReplies(AValue: TIdIRCReplies);
begin
  { Copy the given TIdIRCReplies object to the internal object. }
  FReplies.Assign(AValue);
end;

{ Change the current state. }
procedure TIdIRC.SeTIdIRCState(AState: TIdIRCState);
begin
  if AState <> FState then
  begin
    FState := AState;
    if Assigned(FOnStateChange) then
    begin
      FOnStateChange(Self);
    end;
  end;
end;

{ Split into SenderNick, SenderAddress, Command, Content and separate Tokens. }
procedure TIdIRC.TokenizeCommand;
var
  Index: Integer;
  Count: Integer;
begin
  { Set the values to null strings. }
  SenderNick := '';    {Do not Localize}
  SenderAddress := '';    {Do not Localize}
  Command := '';    {Do not Localize}
  Content := '';    {Do not Localize}
  { Extract the sender of the message first if it is present. }
  if (Length(FullCommand)>0) and (FullCommand[1] = ':') then    {Do not Localize}
  begin
    Index := Pos(' ', FullCommand);    {Do not Localize}
    SenderAddress := Copy(FullCommand, 2, Index - 2);
    FullCommand := Copy(FullCommand, Index + 1, 512);
    { Copy the full address to the first token. }
    Token[0] := SenderAddress;
    { See if the address contains a nick as well. }
    Index := Pos('!', SenderAddress);    {Do not Localize}
    if Index > 0 then
    begin
      { Extract the nick from the address. }
      SenderNick := Copy(SenderAddress, 1, Index - 1);
      SenderAddress := Copy(SenderAddress, Index + 1, 512);
    end;
  end
  else
  begin
    { Make the first token a null string. }
    Token[0] := '';    {Do not Localize}
  end;
  { Extract the command. }
  Index := Pos(' ', FullCommand);    {Do not Localize}
  Command := Copy(FullCommand, 1, Index - 1);
  FullCommand := Copy(FullCommand, Index + 1, 512);
  { Copy the Command to the second token. }
  Token[1] := Command;
  { Extract the rest of the arguments into Content and Token. }
  Content := FullCommand;
  Count := 2;
  while Length(FullCommand) > 0 do
  begin
    { If the argument is prefixed by a semi-colon, then the rest of the line is
      treated as one argument. }
    if (Length(FullCommand)>0) and (FullCommand[1] = ':') then    {Do not Localize}
    begin
      Token[Count] := Copy(FullCommand, 2, Length(FullCommand) - 1);
      FullCommand := '';    {Do not Localize}
    end
    else
    begin
      Index := Pos(' ', FullCommand);    {Do not Localize}
      if Index > 0 then
      begin
        { Copy the argument and remove it from the string. }
        Token[Count] := Copy(FullCommand, 1, Index - 1);
        { Remove that token and process the remaining string. }
        FullCommand := Copy(FullCommand, Index + 1, 512);
      end
      else
      begin
        { Must be the last argument, so copy the entire remaining string. }
        Token[Count] := Copy(FullCommand, 1, 512);
        FullCommand := '';    {Do not Localize}
      end;
    end;
    Inc(Count);
  end;
  { Fill any empty tokens with a null string. }
  for Index := Count to IdIrcMinTokenCount - 1 do
  begin
    Token[Index] := '';    {Do not Localize}
  end;
end;

{ Attempt to match the given command with one of a list of commands.  If a
  match is found, then the index of that command is returned, else the return
  value is -1. }
function TIdIRC.MatchCommand: Integer;
var
  Index: Integer;
begin
  Index := 0;
  Result := -1;
  while (Result < 0) and (Index <= High(Commands)) do
  begin
    if Command = Commands[Index] then
    begin
      Result := Index;
    end;
    Inc(Index);
  end;
end;

{ Parse the string and call any appropriate event handlers. }
procedure TIdIRC.ParseCommand;
var
  CommandNumber: Integer;
  Suppress: Boolean;
  Index: Integer;
  Channel: TIdIRCChannel;
  User, Target: TIdIRCUser;
  lcTemp : String;
begin
  { Break up the command into its tokens. }
  TokenizeCommand;
  { Get a reference to a user object for the sender. }
  User := FUsers.Add(SenderNick, SenderAddress);
  { If an OnRaw event handler is assigned, then call it. }
  if Assigned(FOnRaw) then
  begin
    Suppress := False;
    FOnRaw(Self, User, Command, Content, Suppress);
    { If the user set Suppress to True, then stop processing for this string. }
    if Suppress then
    begin
      { Fixed 28/11/99. If Suppress was set to True, the User object would not
        have been released. }
      FUsers.Remove(User);
      Exit;
    end;
  end;
  { Try to match a numeric command.  If not a valid numeric command, then
    returns -1. }
  CommandNumber := StrToIntDef(Command, -1);
  if CommandNumber > -1 then
  begin
    case CommandNumber of
      1,                    { 001 }
      2,                    { 002 }
      3,                    { 003 }
      4:                    { 004 }
                    begin
                      { Apparently these are the first messages sent back from
                        the server, so set the Server to the address of the
                        sender of these messages.  This is the actual address
                        of the server we are on. }
                      FServer := SenderAddress;
                      { Set state to connected.  May need this elsewhere too. }
                      SeTIdIRCState(csConnected);
                      if Assigned(FOnSystem) then
                      begin
                        FOnSystem(Self, User, CommandNumber,'WELCOME', Content);    {Do not Localize}
                      end;
                    end;
      6,                    {NOT NAMED IN RFC2812 - /MAP LINE}
      7:                    {NOT NAMED IN RFC2812 - END of /MAP}
            if Assigned(FOnSystem) then
            begin
              FOnSystem(Self, User, CommandNumber,'MAP', Format('%s', [Token[3]]));
            end;
      RPL_TRACELINK,        { 200 }
      RPL_TRACECONNECTING,  { 201 }
      RPL_TRACEHANDSHAKE,   { 202 }
      RPL_TRACEUNKNOWN,     { 203 }
      RPL_TRACEOPERATOR,    { 204 }
      RPL_TRACEUSER,        { 205 }
      RPL_TRACESERVER,      { 206 }
      RPL_TRACENEWTYPE:     { 208 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'TRACE', Content);    {Do not Localize}
                    end;
      RPL_STATSLINKINFO,    { 211 }
      RPL_STATSCOMMANDS,    { 212 }
      RPL_STATSCLINE,       { 213 }
      RPL_STATSNLINE,       { 214 }
      RPL_STATSILINE,       { 215 }
      RPL_STATSKLINE,       { 216 }
      RPL_STATSYLINE:       { 218 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'STATS', Content);    {Do not Localize}
                    end;
      RPL_ENDOFSTATS:       { 219 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'STATS', Format('%s %s', [Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_UMODEIS:          { 221 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'UMODE', Format('%s %s', [Token[2], Token[3]]));    {Do not Localize}
                    end;
      RPL_STATSLLINE,       { 241 }
      RPL_STATSUPTIME,      { 242 }
      RPL_STATSOLINE,       { 243 }
      RPL_STATSHLINE:       { 244 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'STATS', Content);    {Do not Localize}
                    end;
      250,                  {NOT NAMED IN RFC2812 - Highest Connection Count}
      RPL_LUSERCLIENT,      { 251 }
      RPL_LUSEROP,          { 252 }
      RPL_LUSERUNKNOWN,     { 253 }
      RPL_LUSERCHANNELS,    { 254 }
      RPL_LUSERME:          { 255 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'LUSER', Format('%s %s',[Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_ADMINME,          { 256 }
      RPL_ADMINLOC1,        { 257 }
      RPL_ADMINLOC2,        { 258 }
      RPL_ADMINEMAIL:       { 259 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'ADMIN', Content);    {Do not Localize}
                    end;
      RPL_TRACELOG:         { 261 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'TRACE', Content);    {Do not Localize}
                    end;
      265,                  {NOT NAMED IN RFC2812 - Current Local Users}
      266:                  {NOT NAMED IN RFC2812 - Current Global Users}
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'LUSER', Token[3]);    {Do not Localize}
                    end;

      RPL_AWAY:             { 301 }
                    begin
                      { Store the away reason in the user object. }
                      User.Reason := Token[4];
                      if Assigned(FOnAway) then
                      begin
                        FOnAway(Self, User);
                      end;
                    end;
      RPL_USERHOST:         { 302 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'USERHOST', Token[3]);
                    end;
      RPL_ISON:             { 303 }
                    { Check to see if this is a response to a notify request. }
                    { FIXME: Needs to be implemented. }
                    { Not a notify request response, so just output as received. }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'ISON', Token[2]);    {Do not Localize}
                    end;
      RPL_UNAWAY:           { 305 }
                    begin
                      FAway := False;
                      if Assigned(FOnUnAway) then
                      begin
                        FOnUnAway(Self, Token[3]);
                      end;
                    end;
      RPL_NOWAWAY:          { 306 }
                    begin
                      FAway := True;
                      if Assigned(FOnNowAway) then
                      begin
                        FOnNowAway(Self, Token[3]);
                      end;
                    end;
      307: { :server 307 yournick whoisnick :is a registered and identified nick }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Format('%s %s', [Token[3], Token[4]]));    {Do not Localize}
                    end;

      RPL_WHOISUSER:        { 311 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Format('%s is %s@%s %s %s', [Token[3], Token[4], Token[5], Token[6], Token[7]]));    {Do not Localize}
                    end;
      RPL_WHOISSERVER:      { 312 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Format('%s is using %s %s', [Token[3], Token[4], Token[5]]));    {Do not Localize}
                    end;
      RPL_WHOISOPERATOR:    { 313 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Format('%s %s', [Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_WHOWASUSER:       { 314 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOWAS', Format('%s was %s@%s %s %s', [Token[3], Token[4], Token[5], Token[6], Token[7]]));    {Do not Localize}
                    end;
      RPL_ENDOFWHO:         { 315 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHO', Format('%s :%s', [Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_WHOISIDLE:        { 317 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Format('%s has been idle %s seconds, signed on at %s', [Token[3], Token[4], Token[5]]));    {Do not Localize}
                    end;
      RPL_ENDOFWHOIS:       { 318 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Format('%s :%s', [Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_WHOISCHANNELS:    { 319 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Format('%s is on %s', [Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_LISTSTART:        { 321 }
                    begin
                      if Assigned(FOnList) then
                      begin
                        FList.Clear;
                        FListLast:= 0;
                        FOnList(Self, FList, 0, False);
                      end;
                      if Assigned(FOnSystem) then
                      begin
                        FOnSystem(Self, User, CommandNumber,'LIST', 'Start of LIST');    {Do not Localize}
                      end;
                    end;
      RPL_LIST:             { 322 }
                    if Assigned(FOnList) then
                    begin
                      FList.Add(Format('%s %s %s', [Token[3], Token[4], Token[5]]));
                      if (FList.Count - FListLast = 40) then //SOMEONE MAY WANT TO SET THIS NUMBER!
                        begin
                          FOnList(Self, FList, FListLast, False);
                          FListLast:= FList.Count - 1;
                        end;
                    end;
      RPL_LISTEND:          { 323 }
                    begin
                      if Assigned(FOnSystem) then
                      begin
                        FOnSystem(Self, User, CommandNumber,'LIST', Token[3]);    {Do not Localize}
                      end;
                      if Assigned(FOnList) then
                      begin
                        FOnList(Self, FList, FListLast, True);
                        FList.Clear;
                        FListLast:= 0;
                      end;
                    end;
      RPL_CHANNELMODEIS:    { 324 }
      { :sender 324 nick channel +mode [param[ param]] }
                    begin
                      { Can safely call this function, because there should be
                        no +/-b, +/-o or +/-v modes (therefore the events
                        OnBan, OnUnban, OnOp, OnDeop, OnVoice and OnDevoice
                        will not get called). }

                      lcTemp:= Token[4];
                      for Index:= 5 to Token.Count - 1 do
                        begin
                          if Token[Index] <> '' then
                            begin
                              lcTemp:= lcTemp + ' ' + Token[Index];
                            end;
                       end;

                      if Assigned(FOnChannelMode) then
                        begin
                          FOnChannelMode(Self, nil, FChannels.Get(Token[3]), Token[3], lcTemp);
                        end;  

                      ParseChannelModeChange(3);
                      { FOnChannelMode(Sender, SenderNick, SenderAddress,
                        Channel) }
//                      if Assigned(FOnChannelMode) then
//                        FOnChannelMode(Self, SenderNick, SenderAddress, Token[3]);
                    end;
      329:                  { 329 }
      { :sender 329 nick channel time }
                    begin
                      if Assigned(FOnSystem) then
                        FOnSystem(Self, User, CommandNumber, Command, Content);
                    end;
      RPL_NOTOPIC:          { 331 }
                    begin
                      { Set topic in channel object. }
                      Channel := FChannels.Get(Token[3]);
                      if Channel <> nil then
                      begin
                        Channel.TopicChanged('');    {Do not Localize}
                        { FOnNoTopic(Sender, Channel) }
                        if Assigned(FOnNoTopic) then
                          FOnNoTopic(Self, Channel, Token[4]);
                      end;
                    end;
      RPL_TOPIC:            { 332 }
                    begin
                      { Set topic in channel object. }
                      Channel := FChannels.Get(Token[3]);
                      if Channel <> nil then
                      begin
                        Channel.TopicChanged(Token[4]);
                        { FOnTopic(Sender, User, Channel) }
                      end;
                      if Assigned(FOnTopic) then
                      begin
                        FOnTopic(Self, User, Channel, Token[3],Token[4]);
                      end;
                    end;
      RPL_INVITING:         { 341 }
                    if Assigned(FOnInviting) then
                    begin
                      FOnInviting(Self, Token[3], Token[4]);
                    end;
      RPL_SUMMONING:        { 342 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'SUMMON', Format('%s has been summoned', [Token[2]]));    {Do not Localize}
                    end;
      RPL_VERSION:          { 351 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'VERSION', Format('%s %s %s', [Token[3], Token[4], Token[5]]));  {Do not Localize}
                    end;
      RPL_WHOREPLY:         { 352 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHO', Token[2]);    {Do not Localize}
                    end;
      RPL_NAMREPLY:         { 353 }
      { :sender 353 nick = channel :[name[ name...]] }
                    begin
                      if Assigned(FOnSystem) then
                      begin
                        FOnSystem(Self, User, CommandNumber,'NAMES', Format('%s :%s', [Token[4], Token[5]]));    {Do not Localize}
                      end;
                      { Scan through names and add to channel. }
                      Channel := FChannels.Get(Token[4]);
                      if Channel <> nil then
                      begin
                        while Length(Token[5]) > 0 do
                        begin
                          Index := Pos(' ', Token[5]);    {Do not Localize}
                          if Index > 0 then
                          begin
                            Channel.AddUser(Copy(Token[5], 1, Index - 1), '');    {Do not Localize}
                            Token[5] := Copy(Token[5], Index + 1, 512);
                          end
                          else
                          begin
                            Channel.AddUser(Token[5], '');    {Do not Localize}
                            Token[5] := '';    {Do not Localize}
                          end;
                        end;
                        { Inform of a change in the channel info. }
                        if Assigned(Channel.OnChannelUpdate) then
                          Channel.OnChannelUpdate(Channel, cuNames, nil, 0);
                      end;
                    end;
      RPL_LINKS:            { 364 }
                    if Assigned(FOnLinks) then
                    begin
                      lcTemp:= Token[5];
                      FOnLinks(Self, Token[4], Token[3], COPY(lcTemp, 1, POS(' ', lcTemp) - 1), COPY(lcTemp, POS(' ', lcTemp) + 1, Length(lcTemp)));
                    end;
      RPL_ENDOFLINKS:       { 365 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'LINKS', Format('%s %s', [Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_ENDOFNAMES:       { 366 }
                   begin
                     Channel := FChannels.Get(Token[3]);
                     if Assigned(FOnSystem) then
                     begin
                       FOnSystem(Self, User, CommandNumber,'NAMES', Format('%s :%s', [Token[3], Token[4]]));    {Do not Localize}
                     end;
                     if Assigned(FOnNames) then
                     begin
                       FOnNames(Self,fUsers,Channel);
                     end;
                   end;
      RPL_BANLIST:          { 367 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'BANS', Format('%s %s', [Token[2], Token[3]]));    {Do not Localize}
                    end;
      RPL_ENDOFBANLIST:     { 368 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'BANS', Format('%s :%s', [Token[2], Token[3]]));    {Do not Localize}
                    end;
      RPL_ENDOFWHOWAS:      { 369 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOWAS', Format('%s :%s', [Token[3], Token[4]]));    {Do not Localize}
                    end;
      RPL_INFO:             { 371 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'INFO', Token[2]);    {Do not Localize}
                    end;
      RPL_MOTD:             { 372 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'MOTD', Token[3]);    {Do not Localize}
                    end;
      RPL_ENDOFINFO:        { 374 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'INFO', Token[2]);    {Do not Localize}
                    end;
      RPL_MOTDSTART:        { 375 }
                    begin
                      { Set state to connected.  May need this elsewhere too. }
                      SeTIdIRCState(csConnected);
                      if Assigned(FOnSystem) then
                      begin
                        FOnSystem(Self, User, CommandNumber,'MOTD', Token[3]);    {Do not Localize}
                      end;
                    end;
      RPL_ENDOFMOTD:        { 376 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'MOTD', Token[3]);    {Do not Localize}
                    end;
      RPL_YOUREOPER:        { 381 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'OPER', Token[2]);    {Do not Localize}
                    end;
      RPL_REHASHING:        { 382 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'REHASH', Format('%s :%s', [Token[2], Token[3]]));    {Do not Localize}
                    end;
      RPL_TIME:             { 391 }
                   if Assigned(FOnSystem) then
                   begin
                     if UpperCase(Token[0]) = UpperCase(Token[3]) then
                     begin
                       FOnSystem(Self, User, CommandNumber,'TIME', Format('%s :%s', [Token[0], Token[4]]))
                     end
                     else
                     begin
                       FOnSystem(Self, User, CommandNumber,'TIME', Format('%s :%s', [Token[0], Token[3]]));
                     end;
                   end;
      RPL_USERSSTART:       { 392 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'USERS', Token[2]);    {Do not Localize}
                    end;
      RPL_USERS:            { 393 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'USERS', Token[2]);    {Do not Localize}
                    end;
      RPL_ENDOFUSERS:       { 394 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'USERS', Token[2]);    {Do not Localize}
                    end;
      RPL_NOUSERS:          { 395 }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'USERS', Token[2]);    {Do not Localize}
                    end;
      { All responses from 401 to 502 are errors. }
      ERR_NOSUCHNICK..      { 401 }
      ERR_USERSDONTMATCH:   { 502 }
                    begin
                      { Call the general error handler. }
                      if Assigned(FOnError) then
                      begin
                        FOnError(Self, User, Command, Content);
                      end;
                      { ERR_NICKNAMEINUSE special case for registration
                        process. }
                      { FIXME: Need to update own user object with chosen nick. }
                      if (CommandNumber >= ERR_NONICKNAMEGIVEN) and (CommandNumber <= ERR_NICKNAMEINUSE) and (FState = csLoggingOn) then
                      begin
                        { Try the AltNick. }
                        if FCurrentNick = FNick then
                        begin
                          FCurrentNick:= FAltNick;
                        end
                        { Tried the AltNick, so ask the user for another one. }
                        else
                        begin
                           if FCurrentNick = FAltNick then
                           begin
                            if Assigned(FOnNicksInUse) then
                              FOnNicksInUse(Self, FCurrentNick)
                            else
                              FCurrentNick := '';    {Do not Localize}
                            end;
                        end;
                        { If there is another nick to try, send it. }
                        if FCurrentNick <> '' then    {Do not Localize}
                        begin
                          SetNick(FCurrentNick);
                        end
                        else
                        begin
                          Disconnect(True);
                        end;
                      end;
                    end;
      614: { :server 614 yournick :whoisnick (host.net) is using modes: +modes }
                    if Assigned(FOnSystem) then
                    begin
                      FOnSystem(Self, User, CommandNumber,'WHOIS', Token[3]);    {Do not Localize}
                    end;

      else
      begin
        if Assigned(FOnUnknownCommand) then
        begin
          FOnUnknownCommand(Self, User, Command, Content);
        end;
      end;
    end;
  end
  else
  begin
    { Try to match with a text command. }
    CommandNumber := MatchCommand;
    if CommandNumber > -1 then
    begin
      case CommandNumber of
        0:
            { PRIVMSG nick/#channel :message }
            { Check for CTCP query. }

            if (Token[3] <> '') AND (Token[3][1] = #1) then
            begin
              ParseCTCPQuery;
            end
            else
            begin
              if Assigned(FOnMessage) then
              begin
                FOnMessage(Self, User, FChannels.Get(Token[2]), Token[3]);
              end;
            end;
        1:
            { NOTICE nick/#channel :message }
            { Check for CTCP reply. }
            if (Token[3] <> '') and (Token[3][1] = #1) then
            begin
              ParseCTCPReply;
            end
            else
            begin
              if Assigned(FOnNotice) then
              begin
                FOnNotice(Self, User, FChannels.Get(Token[2]), Token[3]);
              end;
            end;
        2:
            { JOIN #channel }
            if SenderNick = FCurrentNick then
            begin
              { Add the channel object to the channel list, and set it as
                active. }
              Channel := FChannels.Add(Token[2]);
              Channel.Active := True;
              { Need to send a MODE query so we can get the channel mode. }
              Mode(Token[2], '', '');    {Do not Localize}
              if Assigned(FOnJoined) then
              begin
                FOnJoined(Self, Channel);
              end;
            end
            else
            begin
              { Add the new user to the channel object. }
              Channel := FChannels.Get(Token[2]);
              Channel.Joined(User);
              if Assigned(FOnJoin) then
              begin
                FOnJoin(Self, User, Channel);
              end;
            end;
        3:
            { PART #channel }
            begin
              { Store the part reason in the user object. }
              User.Reason := Token[3];
              if SenderNick = FCurrentNick then
              begin
                { Mark the channel object as inactive. }
                Channel := FChannels.Get(Token[2]);
                Channel.Active := False;
                Channel.CloseType := ctPart;
                if Assigned(FOnParted) then
                begin
                  FOnParted(Self, Channel);
                end;
                FChannels.Remove(Token[2]);
              end
              else
              begin
                Channel := FChannels.Get(Token[2]);
                Channel.Parted(User);
                if Assigned(FOnPart) then
                  FOnPart(Self, User, Channel);
              end;
            end;
        4:
            { KICK #channel target :reason }
            begin
              { Store the kick reason in the user object. }
              User.Reason := Token[4];
              if Token[3] = FCurrentNick then
              begin
                { Mark the channel object as inactive. }
                Channel := FChannels.Get(Token[2]);
                Channel.Active := False;
                Channel.CloseType := ctKick;
                if Assigned(FOnKicked) then
                begin
                  FOnKicked(Self, User, Channel);
                end;
                FChannels.Remove(Token[2]);
              end
              else
              begin
                Channel := FChannels.Get(Token[2]);
                Target := FUsers.Add(Token[3], '');    {Do not Localize}
                { Copy the kick reason to the target's user object. }    {Do not Localize}
                Target.Reason := User.Reason;
                if Assigned(FOnKick) then
                begin
                  FOnKick(Self, User, Target, Channel);
                end;
                Channel.Kicked(Target);
                FUsers.Remove(Target);
              end;
            end;
        5:
            { MODE nick/#channel +/-modes parameters... }
            if IsChannel(Token[2]) then
            { Channel mode change }
            begin
              if FChannels.Find(Token[2], Index) then
              begin
                lcTemp:= Token[3];
                for Index:= 4 to Token.Count - 1 do
                begin
                  //TODO:  This could be better as noted in BUg report 531202
                  //but it does work on a temporary basis.  This is necessary as there
                  //is more than one entry for User Modes
                  if Token[Index] <> '' then
                  begin
                    lcTemp:= lcTemp + ' ' + Token[Index];
                  end;
                end;
                if Assigned(FOnChannelMode) then
                begin
                  FOnChannelMode(Self, FUsers.Get(SenderNick), FChannels.Get(Token[2]), Token[2], lcTemp);
                end;
                ParseChannelModeChange(2);
//                if ParseChannelModeChange(2) then
//                  if Assigned(FOnChannelModeChanged) then
//                    with FChannels.Get(Token[2]) do
//                      FOnChannelModeChanged(Self, SenderNick, SenderAddress, Token[2], Mode, Limit, Key);
              end;
            end
            else
            { User mode change }
            begin
              if Token[2] = FCurrentNick then
              begin
                if Assigned(FOnUserMode) then
                begin
                  FOnUserMode(Self, Token[3]);
                end;
                if ParseUserModeChange then
                begin
                  if Assigned(FOnUserModeChanged) then
                  begin
                    FOnUserModeChanged(Self);
                  end;
                end;
              end;
            end;
        6:
            { NICK newnick }
            begin
              if (SenderNick = FCurrentNick) then
              begin
                lcTemp:= FCurrentNick;
                FCurrentNick := Token[2];
                if Assigned(FOnNickChanged) then
                begin
                  FOnNickChanged(Self, lcTemp);
                end;
              end
              else
              begin
                if Assigned(FOnNickChange) then
                begin
                  FOnNickChange(Self, User, Token[2]);
                end;
              end;
              { Go through all channels and inform of the nick change. }
              FChannels.ChangedNick(User, Token[2]);
              { Apply the new nick. }
              User.Nick := Token[2];
            end;
        7:
            { QUIT :reason }
            begin
              { Store the quit reason. }
              User.Reason := Token[2];
              if Assigned(FOnQuit) then
              begin
                FOnQuit(Self, User);
              end;
              { Go through all channels and inform of the quit. }
              FChannels.Quit(User);
            end;
        8:
            { INVITE nick :#channel }
            if Assigned(FOnInvite) then
            begin
              FOnInvite(Self, User, Token[3]);
            end;
        9:
            { KILL nick :reason }
            if Assigned(FOnKill) then
            begin
              FOnKill(Self, User, Token[2], Token[3]);
            end;
        10:
            { PING server }
            begin
              { Send the PONG response }
              Raw(Format('PONG :%s', [Token[2]]));    {Do not Localize}
              if Assigned(FOnPingPong) then
              begin
                FOnPingPong(Self);
              end;
            end;
        11:
            { WALLOPS :message }
            if Assigned(FOnWallops) then
            begin
              FOnWallops(Self, User, Token[2]);
            end;
        12:
            {TOPIC}
            begin
              Channel := fChannels.Get(Token[2]);
              if Channel <> nil then
              begin
                Channel.TopicChanged(Token[3]);
                if Assigned(FOnTopic) then
                begin
                  FOnTopic(Self, User, Channel, Channel.Name, Token[3]);
                end;
              end;
            end;
      end;
    end
    else
    { Unknown command from server }
    begin
      if Assigned(FOnUnknownCommand) then
      begin
        FOnUnknownCommand(Self, User, Command, Content);
      end;
    end;
  end;
  { Release the sender user object. }
  FUsers.Remove(User);
end;

{ Attempt to match the given DCC command with one of a list of DCC commands.
  If a match is found, then the index of that command is returned, else the
  return value is -1. }
function TIdIRC.MatchDCC(ADCC: String): Integer;
var
  Index: Integer;
begin
  Index := 0;
  Result := -1;
  while (Result < 0) and (Index <= High(DCCs)) do
  begin
    if ADCC = DCCs[Index] then
    begin
      Result := Index;
    end;
    Inc(Index);
  end;
end;


{ Attempt to match the given CTCP command with one of a list of CTCP commands.
  If a match is found, then the index of that command is returned, else the
  return value is -1. }
function TIdIRC.MatchCTCP(ACTCP: String): Integer;
var
  Index: Integer;
begin
  Index := 0;
  Result := -1;
  while (Result < 0) and (Index <= High(CTCPs)) do
  begin
    if ACTCP = CTCPs[Index] then
    begin
      Result := Index;
    end;
    Inc(Index);
  end;
end;

{ Parse a DCC query and call the appropriate event handlers. }
procedure TIdIRC.ParseDCC(ADCC: String);
var
  DCCToken: TStringList;
begin
  DCCToken:= TStringList.Create;
  ADCC:= ADCC + ' ';
  while POS(' ', ADCC) > 0 do
  begin
    DCCToken.Add(COPY(ADCC, 1, POS(' ', ADCC) -1));
    DELETE(ADCC, 1, POS(' ', ADCC));
  end;

  case MatchDCC(DCCToken[0]) of
    0:
      {SEND}
      begin
        if Assigned(FOnDCCSend) then
        begin
          FOnDCCSend(Self, SenderNick, DCCToken[2], DCCToken[3], DCCToken[1], DCCToken[4]);
        end;
      end;
    1:
      {CHAT}
      begin
        if Assigned(FOnDCCChat) then
        begin
          FOnDCCChat(Self, SenderNick, DCCToken[2], DCCToken[3]);
        end;
      end;
    2:
      {RESUME}
      begin
        if Assigned(FOnDCCResume) then
        begin
          FOnDCCResume(Self, SenderNick, DCCToken[2], DCCToken[1], DCCToken[3]);
        end;
      end;
    3:
      {ACCEPT}
      begin
        if Assigned(FOnDCCAccept) then
        begin
          FOnDCCAccept(Self, SenderNick, DCCToken[2], DCCToken[1], DCCToken[3]);
        end;
      end;
  end;
  DCCToken.Free;
end;


{ Parse a CTCP query and call the appropriate event handlers. }
procedure TIdIRC.ParseCTCPQuery;
var
  CTCP, Args: String;
  Index, L: Integer;
  User: TIdIRCUser;
  Suppress: Boolean;
begin
  L := Length(Token[3]);
  Index := Pos(' ', Token[3]);    {Do not Localize}
  if Index > 0 then
  begin
    { CTCP command plus parameters. }
    CTCP := Copy(Token[3], 2, Index - 2);
    Args := Copy(Token[3], Index + 1, L - Index - 1);
  end
  else
  begin
    { No parameters. }
    CTCP := Copy(Token[3], 2, L - 2);
    Args := '';    {Do not Localize}
  end;
  Suppress := False;
  User := FUsers.Add(SenderNick, SenderAddress);
  case MatchCTCP(CTCP) of
    -1:
      { Unknown CTCP query. }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        { Suppressing an unknown CTCP query has no meaning, so ignore the
          Suppress variable. }
        end;
      end;
    0:
      { ACTION }
      begin
        if Assigned(FOnAction) then
          FOnAction(Self, User, FChannels.Get(Token[2]), Args);
      end;
    1:
      { SOUND }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
        { Suppressing an CTCP SOUND query has no meaning, so ignore the
          Suppress variable. }
      end;
    2:
      { PING }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
        { Suppress the standard PING response if requested. }
        if not Suppress then
        begin
          CTCPReply(SenderNick, CTCP, Args);
        end;
      end;
    3:
      { FINGER }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
        { Suppress the standard FINGER response if requested. }
        if not Suppress then
        begin
          CTCPReply(SenderNick, CTCP, Replies.Finger);
        end;
      end;
    4:
      { USERINFO }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
        { Suppress the standard USERINFO response if requested. }
        if not Suppress then
        begin
          CTCPReply(SenderNick, CTCP, Replies.UserInfo);
        end;
      end;
    5:
      { VERSION }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
        { Suppress the standard VERSION response if requested. }
        if not Suppress then
        begin
          CTCPReply(SenderNick, CTCP, Replies.Version);
        end;
      end;
    6:
      { CLIENTINFO }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
        { Suppress the standard CLIENTINFO response if requested. }
        if not Suppress then
        begin
          CTCPReply(SenderNick, CTCP, Replies.ClientInfo);
        end;
      end;
    7:
      { TIME }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
        { Suppress the standard TIME response if requested. }
        if not Suppress then
        begin
          CTCPReply(SenderNick, CTCP, Format(RSIRCTimeIsNow, [DateTimeToStr(Now)]));    {Do not Localize}
        end;
      end;
    8:
      { ERROR }
      begin
        if Assigned(FOnCTCPQuery) then
        begin
          FOnCTCPQuery(Self, User, FChannels.Get(Token[2]), CTCP, Args, Suppress);
        end;
      end;
    9:
      { DCC }
      begin
        ParseDCC(Args);
      end;
  end;
  { Release the user object. }
  FUsers.Remove(User);
end;

{ Parse a CTCP reply and call the appropriate event handlers. }
procedure TIdIRC.ParseCTCPReply;
var
  CTCP, Args: String;
  Index, L: Integer;
  User: TIdIRCUser;
begin
  L := Length(Token[3]);
  Index := Pos(' ', Token[3]);    {Do not Localize}
  if Index > 0 then
  begin
    { CTCP command plus parameters. }
    CTCP := Copy(Token[3], 2, Index - 2);
    Args := Copy(Token[3], Index + 1, L - Index - 1);
  end
  else
  begin
    { No parameters. }
    CTCP := Copy(Token[3], 2, L - 2);
    Args := '';    {Do not Localize}
  end;
  User := FUsers.Add(SenderNick, SenderAddress);
  case MatchCTCP(CTCP) of
    -1..8:
      begin
        if Assigned(FOnCTCPReply) then
        begin
          FOnCTCPReply(Self, User, FChannels.Get(Token[2]), CTCP, Args);
        end;
      end;
    9:
      { DCC }
      begin
        { FIXME: To be completed. }
      end;
  end;
  { Release the user object. }
  FUsers.Remove(User);
end;

{ Evaluate the channel mode change command. }
function TIdIRC.ParseChannelModeChange(AChannelToken: Integer): Boolean;
var
  i: Integer;
  j: Integer;
  Channel: TIdIRCChannel;
  User, Target: TIdIRCUser;
  ChangeType: TIdIRCChangeType;
  NewChannelMode: TIdIRCChannelModes;
begin
  Result := False;
  ChangeType := ctAdd;
  Channel := FChannels.Get(Token[AChannelToken]);
  if Channel = nil then
  begin
    Exit;
  end;
  User := FUsers.Get(SenderNick);
  NewChannelMode := Channel.Mode;
  j := AChannelToken + 2;  { Token 4 is the first parameter }
  for i := 1 to Length(Token[AChannelToken + 1]) do
    case Token[AChannelToken + 1][i] of
      '+':    {Do not Localize}
            { Add mode. }
            ChangeType := ctAdd;
      '-':    {Do not Localize}
            { Remove mode. }
            ChangeType := ctSubtract;
      'b':    {Do not Localize}
            { Set/Remove channel ban. }
            if ChangeType = ctAdd then
            begin
              if Assigned(FOnBan) then
              begin
                FOnBan(Self, User, Channel, Token[j]);
              end;
              Inc(j);
            end
            else
            begin
              if Assigned(FOnUnban) then
              begin
                FOnUnban(Self, User, Channel, Token[j]);
              end;
              Inc(j);
            end;
      'i':    {Do not Localize}
            { Invite only channel. }
            if ChangeType = ctAdd then
            begin
              NewChannelMode := NewChannelMode + [cmInviteOnly];
            end
            else
            begin
              NewChannelMode := NewChannelMode - [cmInviteOnly];
            end;
      'k':    {Do not Localize}
            { Set/Remove channel key. }
            if ChangeType = ctAdd then
            begin
              NewChannelMode := NewChannelMode + [cmKey];
              Channel.KeyChanged(Token[j]);
              Inc(j);
            end
            else
            begin
              NewChannelMode := NewChannelMode - [cmKey];
              Channel.KeyChanged('');    {Do not Localize}
            end;
      'l':    {Do not Localize}
            { Set/Remove user limit. }
            if ChangeType = ctAdd then
            begin
              NewChannelMode := NewChannelMode + [cmUserLimit];
              Channel.LimitChanged(StrToIntDef(Token[j], 0));
              Inc(j);
            end
            else
            begin
              NewChannelMode := NewChannelMode - [cmUserLimit];
              Channel.LimitChanged(0);
            end;
      'm':    {Do not Localize}
            { Moderated channel. }
            if ChangeType = ctAdd then
            begin
              NewChannelMode := NewChannelMode + [cmModerated]
            end
            else
            begin
              NewChannelMode := NewChannelMode - [cmModerated];
            end;
      'n':    {Do not Localize}
            { No External Messages. }
            if ChangeType = ctAdd then
            begin
              NewChannelMode := NewChannelMode + [cmNoExternalMessages]
            end
            else
            begin
              NewChannelMode := NewChannelMode - [cmNoExternalMessages];
            end;
      'o':    {Do not Localize}
            { Give or take operator priviliges. }
            begin
              Target := FUsers.Get(Token[j]);
              if ChangeType = ctAdd then
              begin
                if Assigned(FOnOp) then
                begin
                  FOnOp(Self, User, Channel, Target);
                end;
                { Update the attributes. }
                Channel.GotOp(Target);
                Inc(j);
              end
              else
              begin
                if Assigned(FOnDeop) then
                begin
                  FOnDeop(Self, User, Channel, FUsers.Get(Token[j]));
                end;
                { Update the attributes. }
                Channel.GotDeop(Target);
                Inc(j);
              end;
            end;
      'p':    {Do not Localize}
            { Private channel. }
            if ChangeType = ctAdd then
            begin
              NewChannelMode := NewChannelMode + [cmPrivate]
            end
            else
            begin
              NewChannelMode := NewChannelMode - [cmPrivate];
            end;
      's':    {Do not Localize}
            { Secret channel. }
            if ChangeType = ctAdd then
              NewChannelMode := NewChannelMode + [cmSecret]
            else
              NewChannelMode := NewChannelMode - [cmSecret];
      't':    {Do not Localize}
            { Only operators set topic. }
            if ChangeType = ctAdd then
            begin
              NewChannelMode := NewChannelMode + [cmOpsSetTopic]
            end
            else
            begin
              NewChannelMode := NewChannelMode - [cmOpsSetTopic];
            end;
      'v':    {Do not Localize}
            { Give or take a voice on a moderated channel. }
            begin
              Target := FUsers.Get(Token[j]);
              if ChangeType = ctAdd then
              begin
                if Assigned(FOnVoice) then
                begin
                  FOnVoice(Self, User, Channel, Target);
                end;
                { Update the attributes. }
                Channel.GotVoice(Target);
                Inc(j);
              end
              else
              begin
                if Assigned(FOnDevoice) then
                begin
                  FOnDevoice(Self, User, Channel, Target);
                end;
                { Update the attributes. }
                Channel.GotDevoice(Target);
                Inc(j);
              end;
            end;
    end;
  Result := (Channel.Mode <> NewChannelMode);
  if Result then
  begin
    Channel.ModeChanged(NewChannelMode);
  end;
end;

{ Evaluate user mode change. }
function TIdIRC.ParseUserModeChange: Boolean;
var
  i: Integer;
  ChangeType: TIdIRCChangeType;
  NewUserMode: TIdIRCUserModes;
begin
  ChangeType := ctAdd;
  NewUserMode := FUserMode;
  for i := 1 to Length(Token[3]) do
  begin
    if (Length(Token[3])>0) then
    begin
      case Token[3][i] of
      '+':    {Do not Localize}
            { Add mode. }
            ChangeType := ctAdd;
      '-':    {Do not Localize}
            { Remove mode. }
            ChangeType := ctSubtract;
      'i':    {Do not Localize}
            { Invisible. }
            if ChangeType = ctAdd then
            begin
              NewUserMode := NewUserMode + [umInvisible]
            end
            else
            begin
              NewUserMode := NewUserMode - [umInvisible];
            end;
      'o':    {Do not Localize}
            { IRC Operator. }
            if ChangeType = ctAdd then
            begin
              NewUserMode := NewUserMode + [umOperator]
            end
            else
            begin
              NewUserMode := NewUserMode - [umOperator];
            end;
      's':    {Do not Localize}
            { Receive server notices. }
            if ChangeType = ctAdd then
            begin
              NewUserMode := NewUserMode + [umServerNotices]
            end
            else
            begin
              NewUserMode := NewUserMode - [umServerNotices];
            end;
      'w':    {Do not Localize}
            { Receive wallops. }
            if ChangeType = ctAdd then
            begin
              NewUserMode := NewUserMode + [umWallops]
            end
            else
            begin
              NewUserMode := NewUserMode - [umWallops];
            end;
      end;
    end;
  end;
  Result := (FUserMode <> NewUserMode);
  if Result then
  begin
    FUserMode := NewUserMode;
  end;
end;

{ Return True if the string Channel is a channel name. }
function TIdIRC.IsChannel(AChannel: String): Boolean;
begin
  Result := (Length(AChannel)>0) and (AChannel[1] in IRCChannelPrefixes);
end;

{ Return True if the string Nick is a channel operator. }
function TIdIRC.IsOp(ANick: String): Boolean;
begin
  Result := (Length(Nick)>0) and (Nick[1] = '@');    {Do not Localize}
end;

{ Return True if the string Nick has a voice. }
function TIdIRC.IsVoice(ANick: String): Boolean;
begin
  Result := (Length(Nick)>0) and (Nick[Length(Nick)] = '+');    {Do not Localize}
end;

{ Returns True if the address matches the hostmask.  Uses a recursive method
  to perform the check.  }
function TIdIRC.MatchHostmask(AAddress, AHostmask: PChar): Boolean;
begin
  if StrComp(AHostmask, '*') = 0 then    {Do not Localize}
  begin
    Result := True;
  end
  else
  begin
    if (AAddress^ = #0) and (AHostmask^ <> #0) then
    begin
      Result := False;
    end
    else
    begin
      if (AAddress^ = #0) then
      begin
        Result := True;
      end
      else
      case AHostmask^ of
      '*':    {Do not Localize}
            if MatchHostmask(AAddress, AHostmask + 1) then
            begin
              Result := True;
            end
            else
            begin
              Result := MatchHostmask(AAddress + 1, AHostmask);
            end;
      '?':    {Do not Localize}
            Result := MatchHostmask(AAddress + 1, AHostmask + 1);
      else
        if AAddress^ = AHostmask^ then
        begin
          Result := MatchHostmask(AAddress + 1, AHostmask + 1)
        end
        else
        begin
          Result := False;
        end;
      end;
    end;
  end;
end;

{ Return a string representation of the user mode. }
function TIdIRC.GetModeString: String;
var
  Element: TIdIRCUserMode;
begin
  { Only bother if there are actually modes to show. }
  if FUserMode <> [] then
  begin
    Result := '+';    {Do not Localize}
    { Add all mode characters. }
    for Element := umInvisible to umWallops do
    begin
      if Element in FUserMode then
      begin
        Result := Result + UserModeChars[Ord(Element)];
      end;
    end;
  end
  else
  begin
    Result := '';    {Do not Localize}
  end;
end;


constructor TIdIRCReadThread.Create(AClient: TIdIRC);
begin
  inherited Create(False);
  FClient := AClient;
  FreeOnTerminate := True;
end;

procedure TIdIRCReadThread.Run;
begin
  FRecvData := FClient.ReadLn;
  Synchronize(FClient.SocketDataAvailable);
  FClient.CheckForDisconnect;
end;

procedure TIdIRCChannels.Sort;
{I found this procedure at:

http://groups.google.com/groups?q=Sort+TCollection&start=30&hl=en&safe=off&rnum=35&selm=904181166%40f761.n5030.z2.FidoNet.ftn

and it seems to look good.}

  function DoCompare(AItem1, AItem2 : TIdIRCChannel) : Integer;
  begin
    if Assigned(FOnSortCompareChanels) then
    begin
      FOnSortCompareChanels(Self,AItem1, AItem2, Result);
    end
    else
    begin
      Result := 0;
    end;
  end;

  procedure SwapItems(i, j : Integer);
  var
    T : TIdIRCChannel;
  begin
    T := Items[i];
    Items[i] := Items[j];
    Items[j] := T;
  end;

  procedure SortItems(iStart, iEnd : Integer);
  var
    i, j : Integer;
    Med : TIdIRCChannel;
  begin
    while iStart < iEnd do
    begin
      i := iStart;
      j := iEnd;

      if iStart = iEnd-1 then
      begin
        if DoCompare(Items[iStart], Items[iEnd]) > 0 then
        begin
          SwapItems(iStart, iEnd);
        end;
        Break;
      end;

      Med := Items[(i + j) div 2];

      repeat
        while DoCompare(Items[i], Med) < 0 do
        begin
          Inc(i);
        end;
        while DoCompare(Items[j], Med) > 0 do
        begin
          Dec(j);
        end;
        if i <= j then
        begin
          SwapItems(i, j);
          Inc(i);
          Dec(j);
        end;
      until i > j;

      if j-iStart > iEnd-i then
      begin
        SortItems(i, iEnd);
        iEnd := j;
      end
      else
      begin
        SortItems(iStart, j);
        iStart := i;
      end;
    end;
  end;

begin
  if Count > 0 then
  begin
    SortItems(0, Count - 1);
  end;
end;

end.


