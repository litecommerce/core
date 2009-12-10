// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdIRC.pas' rev: 5.00

#ifndef IdIRCHPP
#define IdIRCHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdGlobal.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idirc
{
//-- type declarations -------------------------------------------------------
class DELPHICLASS TIdIRCUser;
class DELPHICLASS TIdIRC;
#pragma option push -b-
enum TIdIRCUserMode { umInvisible, umOperator, umServerNotices, umWallops };
#pragma option pop

typedef Set<TIdIRCUserMode, umInvisible, umWallops>  TIdIRCUserModes;

class DELPHICLASS TIdIRCReplies;
class PASCALIMPLEMENTATION TIdIRCReplies : public Classes::TPersistent 
{
	typedef Classes::TPersistent inherited;
	
protected:
	AnsiString FFinger;
	AnsiString FVersion;
	AnsiString FUserInfo;
	AnsiString FClientInfo;
	
public:
	__fastcall TIdIRCReplies(void);
	virtual void __fastcall Assign(Classes::TPersistent* Source);
	
__published:
	__property AnsiString Finger = {read=FFinger, write=FFinger};
	__property AnsiString Version = {read=FVersion, write=FVersion};
	__property AnsiString UserInfo = {read=FUserInfo, write=FUserInfo};
	__property AnsiString ClientInfo = {read=FClientInfo, write=FClientInfo};
public:
	#pragma option push -w-inl
	/* TPersistent.Destroy */ inline __fastcall virtual ~TIdIRCReplies(void) { }
	#pragma option pop
	
};


#pragma option push -b-
enum TIdIRCState { csDisconnect, csDisconnected, csConnecting, csLoggingOn, csConnected };
#pragma option pop

class DELPHICLASS TIdIRCChannel;
typedef void __fastcall (__closure *TIdIRCOnMessage)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel, AnsiString Content);

typedef void __fastcall (__closure *TIdIRCOnJoin)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel);

typedef void __fastcall (__closure *TIdIRCOnJoined)(System::TObject* Sender, TIdIRCChannel* AChannel
	);

typedef void __fastcall (__closure *TIdIRCOnPart)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel);

typedef void __fastcall (__closure *TIdIRCOnParted)(System::TObject* Sender, TIdIRCChannel* AChannel
	);

typedef void __fastcall (__closure *TIdIRCOnKick)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCUser* 
	AVictim, TIdIRCChannel* AChannel);

typedef void __fastcall (__closure *TIdIRCOnKicked)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel);

typedef void __fastcall (__closure *TIdIRCOnNickChange)(System::TObject* Sender, TIdIRCUser* AUser, 
	AnsiString ANewNick);

typedef void __fastcall (__closure *TIdIRCOnNickChanged)(System::TObject* Sender, AnsiString AOldNick
	);

typedef void __fastcall (__closure *TIdIRCOnTopic)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel, const AnsiString AChanName, const AnsiString ATopic);

typedef void __fastcall (__closure *TIdIRCOnQuit)(System::TObject* Sender, TIdIRCUser* AUser);

class DELPHICLASS TIdIRCUsers;
typedef void __fastcall (__closure *TIdIRCOnNames)(System::TObject* Sender, TIdIRCUsers* AUsers, TIdIRCChannel* 
	AChannel);

typedef void __fastcall (__closure *TIdIRCOnInvite)(System::TObject* Sender, TIdIRCUser* AUser, AnsiString 
	AChannel);

typedef void __fastcall (__closure *TIdIRCOnError)(System::TObject* Sender, TIdIRCUser* AUser, AnsiString 
	ANumeric, AnsiString AError);

typedef void __fastcall (__closure *TIdIRCOnAway)(System::TObject* Sender, TIdIRCUser* AUser);

typedef void __fastcall (__closure *TIdIRCOnAwayChange)(System::TObject* Sender, AnsiString AContent
	);

typedef void __fastcall (__closure *TIdIRCOnWallops)(System::TObject* Sender, TIdIRCUser* AUser, AnsiString 
	AContent);

typedef void __fastcall (__closure *TIdIRCOnSystem)(System::TObject* Sender, TIdIRCUser* AUser, int 
	ACmdCode, AnsiString ACommand, AnsiString AContent);

typedef void __fastcall (__closure *TIdIRCOnRaw)(System::TObject* Sender, TIdIRCUser* AUser, AnsiString 
	ACommand, AnsiString AContent, bool &Suppress);

typedef void __fastcall (__closure *TIdIRCOnOp)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel, TIdIRCUser* ATarget);

typedef void __fastcall (__closure *TIdIRCOnBan)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel, AnsiString AHostmask);

typedef void __fastcall (__closure *TIdIRCOnChannelMode)(System::TObject* Sender, TIdIRCUser* AUser, 
	TIdIRCChannel* AChannel, AnsiString AChanName, AnsiString AModes);

typedef void __fastcall (__closure *TIdIRCOnChannelModeChanged)(System::TObject* Sender, TIdIRCUser* 
	AUser, TIdIRCChannel* AChannel);

typedef void __fastcall (__closure *TIdIRCOnUserMode)(System::TObject* Sender, AnsiString AModes);

typedef void __fastcall (__closure *TIdIRCOnInviting)(System::TObject* Sender, AnsiString ANick, AnsiString 
	AChannel);

typedef void __fastcall (__closure *TIdIRCOnKill)(System::TObject* Sender, TIdIRCUser* User, AnsiString 
	AVictim, AnsiString AReason);

typedef void __fastcall (__closure *TIdIRCOnUnknownCommand)(System::TObject* Sender, TIdIRCUser* AUser
	, AnsiString ACommand, AnsiString AContent);

typedef void __fastcall (__closure *TIdIRCOnCTCPQuery)(System::TObject* Sender, TIdIRCUser* User, TIdIRCChannel* 
	AChannel, AnsiString Command, AnsiString Args, bool &ASuppress);

typedef void __fastcall (__closure *TIdIRCOnCTCPReply)(System::TObject* Sender, TIdIRCUser* AUser, TIdIRCChannel* 
	AChannel, AnsiString Command, AnsiString Args);

typedef void __fastcall (__closure *TIdIRCOnSend)(System::TObject* Sender, AnsiString ACommand);

typedef void __fastcall (__closure *TIdIRCOnNicksInUse)(System::TObject* Sender, AnsiString &ANick);
	

typedef void __fastcall (__closure *TIdIRCOnSocketError)(System::TObject* Sender, AnsiString ASocket
	, AnsiString AMsg);

typedef void __fastcall (__closure *TIdIRCOnNoTopic)(System::TObject* Sender, TIdIRCChannel* AChannel
	, AnsiString AContent);

typedef void __fastcall (__closure *TIdIRCOnDCCChat)(System::TObject* Sender, AnsiString ANick, AnsiString 
	AIp, AnsiString APort);

typedef void __fastcall (__closure *TIdIRCOnDCCSend)(System::TObject* Sender, AnsiString ANick, AnsiString 
	AIp, AnsiString APort, AnsiString AFileName, AnsiString AFileSize);

typedef void __fastcall (__closure *TIdIRCOnDCCResume)(System::TObject* Sender, AnsiString ANick, AnsiString 
	APort, AnsiString AFileName, AnsiString APosition);

typedef void __fastcall (__closure *TIdIRCOnDCCAccept)(System::TObject* Sender, AnsiString ANick, AnsiString 
	APort, AnsiString AFileName, AnsiString APosition);

typedef void __fastcall (__closure *TIdIRCOnLinks)(System::TObject* Sender, AnsiString AMask, AnsiString 
	AServer, AnsiString AHopCount, AnsiString AServerInfo);

typedef void __fastcall (__closure *TIdIRCOnList)(System::TObject* Sender, Classes::TStringList* AChans
	, int APosition, bool ALast);

#pragma option push -b-
enum TIdIRCChannelUpdateType { cuMode, cuTopic, cuUser, cuNames, cuNick, cuJoin, cuPart, cuKick, cuQuit 
	};
#pragma option pop

typedef void __fastcall (__closure *TIdIRCOnChannelUpdate)(System::TObject* Sender, TIdIRCChannelUpdateType 
	AUpdateType, TIdIRCUser* AUser, int AInfo);

class DELPHICLASS TIdIRCChannels;
typedef void __fastcall (*TIdIRCSortCompareChanels)(System::TObject* Sender, TIdIRCChannel* AItem1, 
	TIdIRCChannel* AItem2, int &AResult);

class PASCALIMPLEMENTATION TIdIRCChannels : public Classes::TCollection 
{
	typedef Classes::TCollection inherited;
	
protected:
	TIdIRC* FClient;
	TIdIRCSortCompareChanels FOnSortCompareChanels;
	HIDESBASE TIdIRCChannel* __fastcall GetItem(int Index);
	HIDESBASE void __fastcall SetItem(int Index, const TIdIRCChannel* Value);
	
public:
	__fastcall TIdIRCChannels(TIdIRC* AClient);
	__fastcall virtual ~TIdIRCChannels(void);
	HIDESBASE TIdIRCChannel* __fastcall Add(AnsiString AName);
	void __fastcall Remove(AnsiString AName);
	bool __fastcall Find(AnsiString AName, int &AIndex);
	TIdIRCChannel* __fastcall Get(AnsiString AName);
	void __fastcall ChangedNick(TIdIRCUser* AUser, AnsiString ANewNick);
	void __fastcall Quit(TIdIRCUser* AUser);
	virtual void __fastcall Sort(void);
	__property TIdIRCChannel* Items[int Index] = {read=GetItem, write=SetItem};
};


class DELPHICLASS TIdIRCReadThread;
class PASCALIMPLEMENTATION TIdIRCReadThread : public Idthread::TIdThread 
{
	typedef Idthread::TIdThread inherited;
	
protected:
	TIdIRC* FClient;
	AnsiString FRecvData;
	virtual void __fastcall Run(void);
	
public:
	__fastcall TIdIRCReadThread(TIdIRC* AClient);
public:
	#pragma option push -w-inl
	/* TIdThread.Destroy */ inline __fastcall virtual ~TIdIRCReadThread(void) { }
	#pragma option pop
	
};


class PASCALIMPLEMENTATION TIdIRC : public Idtcpclient::TIdTCPClient 
{
	typedef Idtcpclient::TIdTCPClient inherited;
	
protected:
	AnsiString FNick;
	AnsiString FAltNick;
	AnsiString FUsername;
	AnsiString FRealName;
	AnsiString FServer;
	AnsiString FPassword;
	TIdIRCUserModes FUserMode;
	bool FAway;
	Classes::TStringList* FNotify;
	TIdIRCReplies* FReplies;
	TIdIRCState FState;
	AnsiString FCurrentNick;
	System::TObject* FData;
	TIdIRCOnMessage FOnMessage;
	TIdIRCOnMessage FOnNotice;
	TIdIRCOnMessage FOnAction;
	Classes::TNotifyEvent FOnConnect;
	Classes::TNotifyEvent FOnDisconnect;
	TIdIRCOnJoin FOnJoin;
	TIdIRCOnJoined FOnJoined;
	TIdIRCOnPart FOnPart;
	TIdIRCOnParted FOnParted;
	TIdIRCOnKick FOnKick;
	TIdIRCOnKicked FOnKicked;
	TIdIRCOnNickChange FOnNickChange;
	TIdIRCOnNickChanged FOnNickChanged;
	TIdIRCOnTopic FOnTopic;
	TIdIRCOnQuit FOnQuit;
	TIdIRCOnNames FOnNames;
	TIdIRCOnInvite FOnInvite;
	Classes::TNotifyEvent FOnPingPong;
	TIdIRCOnError FOnError;
	TIdIRCOnAway FOnAway;
	TIdIRCOnAwayChange FOnNowAway;
	TIdIRCOnAwayChange FOnUnAway;
	TIdIRCOnWallops FOnWallops;
	TIdIRCOnSystem FOnSystem;
	TIdIRCOnRaw FOnRaw;
	TIdIRCOnOp FOnOp;
	TIdIRCOnOp FOnDeop;
	TIdIRCOnBan FOnBan;
	TIdIRCOnBan FOnUnban;
	TIdIRCOnOp FOnVoice;
	TIdIRCOnOp FOnDevoice;
	TIdIRCOnChannelMode FOnChannelMode;
	TIdIRCOnChannelModeChanged FOnChannelModeChanged;
	TIdIRCOnUserMode FOnUserMode;
	Classes::TNotifyEvent FOnUserModeChanged;
	TIdIRCOnInviting FOnInviting;
	TIdIRCOnKill FOnKill;
	TIdIRCOnUnknownCommand FOnUnknownCommand;
	TIdIRCOnCTCPQuery FOnCTCPQuery;
	TIdIRCOnCTCPReply FOnCTCPReply;
	Classes::TNotifyEvent FOnStateChange;
	TIdIRCOnSend FOnSend;
	TIdIRCOnSend FOnReceive;
	TIdIRCOnNicksInUse FOnNicksInUse;
	TIdIRCOnSocketError FOnSocketError;
	TIdIRCOnNoTopic FOnNoTopic;
	TIdIRCOnDCCChat FOnDCCChat;
	TIdIRCOnDCCSend FOnDCCSend;
	TIdIRCOnDCCResume FOnDCCResume;
	TIdIRCOnDCCAccept FOnDCCAccept;
	TIdIRCOnLinks FOnLinks;
	TIdIRCOnList FOnList;
	TIdIRCOnChannelUpdate FOnChannelUpdate;
	Classes::TStringList* FList;
	int FListLast;
	Classes::TStringList* Token;
	AnsiString FullCommand;
	AnsiString SenderNick;
	AnsiString SenderAddress;
	AnsiString Command;
	AnsiString Content;
	TIdIRCChannels* FChannels;
	TIdIRCUsers* FUsers;
	TIdIRCUser* FUser;
	TIdIRCReadThread* FIRCThread;
	AnsiString FBuffer;
	void __fastcall SocketDataAvailable(void);
	void __fastcall SetNick(AnsiString AValue);
	AnsiString __fastcall GetNick();
	void __fastcall SetAltNick(AnsiString AValue);
	void __fastcall SeTIdIRCUsername(AnsiString AValue);
	void __fastcall SetRealName(AnsiString AValue);
	void __fastcall SetPassword(AnsiString AValue);
	void __fastcall SeTIdIRCUserMode(TIdIRCUserModes AValue);
	void __fastcall SeTIdIRCReplies(TIdIRCReplies* AValue);
	void __fastcall SeTIdIRCState(TIdIRCState AState);
	void __fastcall TokenizeCommand(void);
	int __fastcall MatchCommand(void);
	void __fastcall ParseCommand(void);
	int __fastcall MatchDCC(AnsiString ADCC);
	int __fastcall MatchCTCP(AnsiString ACTCP);
	void __fastcall ParseDCC(AnsiString ADCC);
	void __fastcall ParseCTCPQuery(void);
	void __fastcall ParseCTCPReply(void);
	bool __fastcall ParseChannelModeChange(int AChannelToken);
	bool __fastcall ParseUserModeChange(void);
	
public:
	__fastcall virtual TIdIRC(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdIRC(void);
	virtual void __fastcall Loaded(void);
	virtual void __fastcall Connect(const int ATimeout);
	HIDESBASE void __fastcall Disconnect(bool AForce)/* overload */;
	bool __fastcall IsChannel(AnsiString AChannel);
	bool __fastcall IsOp(AnsiString ANick);
	bool __fastcall IsVoice(AnsiString ANick);
	bool __fastcall MatchHostmask(char * AAddress, char * AHostmask);
	void __fastcall Raw(AnsiString ALine);
	void __fastcall Say(AnsiString ATarget, AnsiString AMsg);
	void __fastcall Notice(AnsiString ATarget, AnsiString AMsg);
	void __fastcall Action(AnsiString ATarget, AnsiString AMsg);
	void __fastcall CTCPQuery(AnsiString ATarget, AnsiString ACommand, AnsiString AParameters);
	void __fastcall CTCPReply(AnsiString ATarget, AnsiString ACTCP, AnsiString AReply);
	void __fastcall Join(AnsiString AChannels, const AnsiString AKeys);
	void __fastcall Part(AnsiString AChannels, const AnsiString AReason);
	void __fastcall Kick(AnsiString AChannel, AnsiString ANick, AnsiString AReason);
	void __fastcall Quit(AnsiString AReason);
	void __fastcall Mode(AnsiString AChannel, AnsiString AModes, const AnsiString AParams);
	void __fastcall GetTopic(AnsiString AChannel);
	void __fastcall SetTopic(AnsiString AChannel, AnsiString ATopic);
	void __fastcall SetAwayMessage(AnsiString AMsg);
	void __fastcall ClearAwayMessage(void);
	AnsiString __fastcall GetModeString();
	__property bool Away = {read=FAway, nodefault};
	__property Classes::TStringList* Notify = {read=FNotify, write=FNotify};
	__property TIdIRCState State = {read=FState, nodefault};
	__property TIdIRCChannels* Channels = {read=FChannels};
	__property TIdIRCUsers* Users = {read=FUsers};
	__property TIdIRCReadThread* IRCThread = {read=FIRCThread};
	
__published:
	__property AnsiString Nick = {read=GetNick, write=SetNick};
	__property AnsiString AltNick = {read=FAltNick, write=SetAltNick};
	__property AnsiString Username = {read=FUsername, write=SeTIdIRCUsername};
	__property AnsiString RealName = {read=FRealName, write=SetRealName};
	__property Port ;
	__property AnsiString Password = {read=FPassword, write=SetPassword};
	__property TIdIRCReplies* Replies = {read=FReplies, write=SeTIdIRCReplies};
	__property TIdIRCUserModes UserMode = {read=FUserMode, write=SeTIdIRCUserMode, nodefault};
	__property TIdIRCOnMessage OnMessage = {read=FOnMessage, write=FOnMessage};
	__property TIdIRCOnMessage OnNotice = {read=FOnNotice, write=FOnNotice};
	__property TIdIRCOnMessage OnAction = {read=FOnAction, write=FOnAction};
	__property Classes::TNotifyEvent OnConnect = {read=FOnConnect, write=FOnConnect};
	__property Classes::TNotifyEvent OnDisconnect = {read=FOnDisconnect, write=FOnDisconnect};
	__property TIdIRCOnJoin OnJoin = {read=FOnJoin, write=FOnJoin};
	__property TIdIRCOnJoined OnJoined = {read=FOnJoined, write=FOnJoined};
	__property TIdIRCOnPart OnPart = {read=FOnPart, write=FOnPart};
	__property TIdIRCOnParted OnParted = {read=FOnParted, write=FOnParted};
	__property TIdIRCOnKick OnKick = {read=FOnKick, write=FOnKick};
	__property TIdIRCOnKicked OnKicked = {read=FOnKicked, write=FOnKicked};
	__property TIdIRCOnNickChange OnNickChange = {read=FOnNickChange, write=FOnNickChange};
	__property TIdIRCOnNickChanged OnNickChanged = {read=FOnNickChanged, write=FOnNickChanged};
	__property TIdIRCOnTopic OnTopic = {read=FOnTopic, write=FOnTopic};
	__property TIdIRCOnQuit OnQuit = {read=FOnQuit, write=FOnQuit};
	__property TIdIRCOnNames OnNames = {read=FOnNames, write=FOnNames};
	__property TIdIRCOnInvite OnInvite = {read=FOnInvite, write=FOnInvite};
	__property Classes::TNotifyEvent OnPingPong = {read=FOnPingPong, write=FOnPingPong};
	__property TIdIRCOnError OnError = {read=FOnError, write=FOnError};
	__property TIdIRCOnAway OnAway = {read=FOnAway, write=FOnAway};
	__property TIdIRCOnAwayChange OnNowAway = {read=FOnNowAway, write=FOnNowAway};
	__property TIdIRCOnAwayChange OnUnAway = {read=FOnUnAway, write=FOnUnAway};
	__property TIdIRCOnWallops OnWallops = {read=FOnWallops, write=FOnWallops};
	__property TIdIRCOnSystem OnSystem = {read=FOnSystem, write=FOnSystem};
	__property TIdIRCOnRaw OnRaw = {read=FOnRaw, write=FOnRaw};
	__property TIdIRCOnOp OnOp = {read=FOnOp, write=FOnOp};
	__property TIdIRCOnOp OnDeop = {read=FOnDeop, write=FOnDeop};
	__property TIdIRCOnBan OnBan = {read=FOnBan, write=FOnBan};
	__property TIdIRCOnBan OnUnban = {read=FOnUnban, write=FOnUnban};
	__property TIdIRCOnOp OnVoice = {read=FOnVoice, write=FOnVoice};
	__property TIdIRCOnOp OnDevoice = {read=FOnDevoice, write=FOnDevoice};
	__property TIdIRCOnChannelMode OnChannelMode = {read=FOnChannelMode, write=FOnChannelMode};
	__property TIdIRCOnChannelModeChanged OnChannelModeChanged = {read=FOnChannelModeChanged, write=FOnChannelModeChanged
		};
	__property TIdIRCOnUserMode OnUserMode = {read=FOnUserMode, write=FOnUserMode};
	__property Classes::TNotifyEvent OnUserModeChanged = {read=FOnUserModeChanged, write=FOnUserModeChanged
		};
	__property TIdIRCOnInviting OnInviting = {read=FOnInviting, write=FOnInviting};
	__property TIdIRCOnKill OnKill = {read=FOnKill, write=FOnKill};
	__property TIdIRCOnUnknownCommand OnUnknownCommand = {read=FOnUnknownCommand, write=FOnUnknownCommand
		};
	__property TIdIRCOnCTCPQuery OnCTCPQuery = {read=FOnCTCPQuery, write=FOnCTCPQuery};
	__property TIdIRCOnCTCPReply OnCTCPReply = {read=FOnCTCPReply, write=FOnCTCPReply};
	__property Classes::TNotifyEvent OnStateChange = {read=FOnStateChange, write=FOnStateChange};
	__property TIdIRCOnSend OnSend = {read=FOnSend, write=FOnSend};
	__property TIdIRCOnSend OnReceive = {read=FOnReceive, write=FOnReceive};
	__property TIdIRCOnNicksInUse OnNicksInUse = {read=FOnNicksInUse, write=FOnNicksInUse};
	__property TIdIRCOnSocketError OnSocketError = {read=FOnSocketError, write=FOnSocketError};
	__property TIdIRCOnNoTopic OnNoTopic = {read=FOnNoTopic, write=FOnNoTopic};
	__property TIdIRCOnDCCChat OnDCCChat = {read=FOnDCCChat, write=FOnDCCChat};
	__property TIdIRCOnDCCSend OnDCCSend = {read=FOnDCCSend, write=FOnDCCSend};
	__property TIdIRCOnDCCResume OnDCCResume = {read=FOnDCCResume, write=FOnDCCResume};
	__property TIdIRCOnDCCAccept OnDCCAccept = {read=FOnDCCAccept, write=FOnDCCAccept};
	__property TIdIRCOnLinks OnLinks = {read=FOnLinks, write=FOnLinks};
	__property TIdIRCOnList OnList = {read=FOnList, write=FOnList};
	__property TIdIRCOnChannelUpdate OnChannelUpdate = {read=FOnChannelUpdate, write=FOnChannelUpdate};
		
};


class PASCALIMPLEMENTATION TIdIRCUser : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	TIdIRC* FClient;
	AnsiString FNick;
	AnsiString FAddress;
	System::TObject* FData;
	AnsiString FReason;
	
public:
	int Count;
	__fastcall TIdIRCUser(TIdIRC* AClient, AnsiString ANick, AnsiString AAddress);
	__fastcall virtual ~TIdIRCUser(void);
	void __fastcall Say(AnsiString AMsg);
	__property AnsiString Nick = {read=FNick, write=FNick};
	__property AnsiString Address = {read=FAddress, write=FAddress};
	__property System::TObject* Data = {read=FData, write=FData};
	__property AnsiString Reason = {read=FReason, write=FReason};
};


typedef void __fastcall (*TIdIRCSortCompareUsers)(System::TObject* Sender, TIdIRCUser* AItem1, TIdIRCUser* 
	AItem2, int &AResult);

class PASCALIMPLEMENTATION TIdIRCUsers : public Classes::TCollection 
{
	typedef Classes::TCollection inherited;
	
protected:
	TIdIRC* FClient;
	TIdIRCSortCompareUsers FOnSortCompareUsers;
	HIDESBASE void __fastcall SetItem(int Index, const TIdIRCUser* Value);
	HIDESBASE TIdIRCUser* __fastcall GetItem(int Index);
	
public:
	__fastcall TIdIRCUsers(TIdIRC* AClient);
	__fastcall virtual ~TIdIRCUsers(void);
	HIDESBASE TIdIRCUser* __fastcall Add(AnsiString ANick, AnsiString AAddress);
	void __fastcall Remove(TIdIRCUser* AUser);
	AnsiString __fastcall Address(AnsiString ANick);
	bool __fastcall Find(AnsiString ANick, int &AIndex);
	TIdIRCUser* __fastcall Get(AnsiString ANick);
	void __fastcall Nick(AnsiString AFromNick, AnsiString AToNick);
	void __fastcall Sort(void);
	__property TIdIRCUser* Items[int Index] = {read=GetItem, write=SetItem};
	__property TIdIRCSortCompareUsers OnSortCompareUsers = {read=FOnSortCompareUsers, write=FOnSortCompareUsers
		};
};


#pragma option push -b-
enum TIdIRCChangeType { ctNone, ctAdd, ctSubtract };
#pragma option pop

#pragma option push -b-
enum TIdIRCChannelMode { cmPrivate, cmSecret, cmInviteOnly, cmOpsSetTopic, cmNoExternalMessages, cmModerated, 
	cmUserLimit, cmKey };
#pragma option pop

typedef Set<TIdIRCChannelMode, cmPrivate, cmKey>  TIdIRCChannelModes;

#pragma option push -b-
enum TIdIRCCloseType { ctReset, ctPart, ctKick };
#pragma option pop

class PASCALIMPLEMENTATION TIdIRCChannel : public Classes::TCollectionItem 
{
	typedef Classes::TCollectionItem inherited;
	
protected:
	TIdIRC* FClient;
	AnsiString FName;
	AnsiString FTopic;
	TIdIRCChannelModes FMode;
	int FLimit;
	AnsiString FKey;
	Classes::TStringList* FNames;
	Classes::TStringList* FBans;
	bool FActive;
	System::TObject* FData;
	bool FModeChange;
	AnsiString ModeOptions;
	AnsiString ModeParams;
	TIdIRCChangeType ChangeType;
	TIdIRCCloseType FCloseType;
	TIdIRCOnChannelUpdate FOnChannelUpdate;
	void __fastcall SetTopic(AnsiString AValue);
	void __fastcall SetMode(TIdIRCChannelModes AValue);
	void __fastcall SetLimit(int AValue);
	void __fastcall SetKey(AnsiString AValue);
	AnsiString __fastcall GetModeString();
	
public:
	__fastcall TIdIRCChannel(TIdIRC* AClient, AnsiString AName);
	__fastcall virtual ~TIdIRCChannel(void);
	void __fastcall Say(AnsiString AMsg);
	void __fastcall Part(AnsiString AReason);
	void __fastcall Kick(AnsiString ANick, AnsiString AReason);
	void __fastcall BeginMode(void);
	void __fastcall EndMode(void);
	void __fastcall Op(AnsiString ANick);
	void __fastcall Deop(AnsiString ANick);
	void __fastcall Voice(AnsiString ANick);
	void __fastcall Devoice(AnsiString ANick);
	void __fastcall Ban(AnsiString AHostmask);
	void __fastcall Unban(AnsiString AHostmask);
	void __fastcall TopicChanged(AnsiString ATopic);
	void __fastcall ModeChanged(TIdIRCChannelModes AMode);
	void __fastcall LimitChanged(int ALimit);
	void __fastcall KeyChanged(AnsiString AKey);
	TIdIRCUser* __fastcall AddUser(AnsiString ANick, AnsiString AAddress);
	void __fastcall RemoveUser(TIdIRCUser* AUser);
	bool __fastcall HasUser(AnsiString ANick);
	bool __fastcall Find(AnsiString ANick, int &AIndex);
	void __fastcall GotOp(TIdIRCUser* AUser);
	void __fastcall GotDeop(TIdIRCUser* AUser);
	void __fastcall GotVoice(TIdIRCUser* AUser);
	void __fastcall GotDevoice(TIdIRCUser* AUser);
	void __fastcall ChangedNick(TIdIRCUser* AUser, AnsiString ANewNick);
	void __fastcall Joined(TIdIRCUser* AUser);
	void __fastcall Parted(TIdIRCUser* AUser);
	void __fastcall Kicked(TIdIRCUser* AUser);
	void __fastcall Quit(TIdIRCUser* AUser);
	__property AnsiString Name = {read=FName};
	__property AnsiString Topic = {read=FTopic, write=SetTopic};
	__property TIdIRCChannelModes Mode = {read=FMode, write=SetMode, nodefault};
	__property int Limit = {read=FLimit, write=SetLimit, nodefault};
	__property AnsiString Key = {read=FKey, write=SetKey};
	__property bool ModeChange = {read=FModeChange, nodefault};
	__property AnsiString ModeString = {read=GetModeString};
	__property Classes::TStringList* Names = {read=FNames};
	__property Classes::TStringList* Bans = {read=FBans};
	__property bool Active = {read=FActive, write=FActive, nodefault};
	__property TIdIRCCloseType CloseType = {read=FCloseType, write=FCloseType, nodefault};
	__property System::TObject* Data = {read=FData, write=FData};
	__property TIdIRCOnChannelUpdate OnChannelUpdate = {read=FOnChannelUpdate, write=FOnChannelUpdate};
		
};


#pragma option push -b-
enum TIdIRCUpdateType { utTopic, utMode, utNicks };
#pragma option pop

//-- var, const, procedure ---------------------------------------------------
static const Byte RPL_TRACELINK = 0xc8;
static const Byte RPL_TRACECONNECTING = 0xc9;
static const Byte RPL_TRACEHANDSHAKE = 0xca;
static const Byte RPL_TRACEUNKNOWN = 0xcb;
static const Byte RPL_TRACEOPERATOR = 0xcc;
static const Byte RPL_TRACEUSER = 0xcd;
static const Byte RPL_TRACESERVER = 0xce;
static const Byte RPL_TRACENEWTYPE = 0xd0;
static const Byte RPL_STATSLINKINFO = 0xd3;
static const Byte RPL_STATSCOMMANDS = 0xd4;
static const Byte RPL_STATSCLINE = 0xd5;
static const Byte RPL_STATSNLINE = 0xd6;
static const Byte RPL_STATSILINE = 0xd7;
static const Byte RPL_STATSKLINE = 0xd8;
static const Byte RPL_STATSYLINE = 0xda;
static const Byte RPL_ENDOFSTATS = 0xdb;
static const Byte RPL_UMODEIS = 0xdd;
static const Byte RPL_STATSLLINE = 0xf1;
static const Byte RPL_STATSUPTIME = 0xf2;
static const Byte RPL_STATSOLINE = 0xf3;
static const Byte RPL_STATSHLINE = 0xf4;
static const Byte RPL_LUSERCLIENT = 0xfb;
static const Byte RPL_LUSEROP = 0xfc;
static const Byte RPL_LUSERUNKNOWN = 0xfd;
static const Byte RPL_LUSERCHANNELS = 0xfe;
static const Byte RPL_LUSERME = 0xff;
static const Word RPL_ADMINME = 0x100;
static const Word RPL_ADMINLOC1 = 0x101;
static const Word RPL_ADMINLOC2 = 0x102;
static const Word RPL_ADMINEMAIL = 0x103;
static const Word RPL_TRACELOG = 0x105;
static const Word RPL_NONE = 0x12c;
static const Word RPL_AWAY = 0x12d;
static const Word RPL_USERHOST = 0x12e;
static const Word RPL_ISON = 0x12f;
static const Word RPL_UNAWAY = 0x131;
static const Word RPL_NOWAWAY = 0x132;
static const Word RPL_WHOISUSER = 0x137;
static const Word RPL_WHOISSERVER = 0x138;
static const Word RPL_WHOISOPERATOR = 0x139;
static const Word RPL_WHOWASUSER = 0x13a;
static const Word RPL_ENDOFWHO = 0x13b;
static const Word RPL_WHOISIDLE = 0x13d;
static const Word RPL_ENDOFWHOIS = 0x13e;
static const Word RPL_WHOISCHANNELS = 0x13f;
static const Word RPL_LISTSTART = 0x141;
static const Word RPL_LIST = 0x142;
static const Word RPL_LISTEND = 0x143;
static const Word RPL_CHANNELMODEIS = 0x144;
static const Word RPL_NOTOPIC = 0x14b;
static const Word RPL_TOPIC = 0x14c;
static const Word RPL_INVITING = 0x155;
static const Word RPL_SUMMONING = 0x156;
static const Word RPL_VERSION = 0x15f;
static const Word RPL_WHOREPLY = 0x160;
static const Word RPL_NAMREPLY = 0x161;
static const Word RPL_LINKS = 0x16c;
static const Word RPL_ENDOFLINKS = 0x16d;
static const Word RPL_ENDOFNAMES = 0x16e;
static const Word RPL_BANLIST = 0x16f;
static const Word RPL_ENDOFBANLIST = 0x170;
static const Word RPL_ENDOFWHOWAS = 0x171;
static const Word RPL_INFO = 0x173;
static const Word RPL_MOTD = 0x174;
static const Word RPL_ENDOFINFO = 0x176;
static const Word RPL_MOTDSTART = 0x177;
static const Word RPL_ENDOFMOTD = 0x178;
static const Word RPL_YOUREOPER = 0x17d;
static const Word RPL_REHASHING = 0x17e;
static const Word RPL_TIME = 0x187;
static const Word RPL_USERSSTART = 0x188;
static const Word RPL_USERS = 0x189;
static const Word RPL_ENDOFUSERS = 0x18a;
static const Word RPL_NOUSERS = 0x18b;
static const Word ERR_NOSUCHNICK = 0x191;
static const Word ERR_NOSUCHSERVER = 0x192;
static const Word ERR_NOSUCHCHANNEL = 0x193;
static const Word ERR_CANNOTSENDTOCHAN = 0x194;
static const Word ERR_TOOMANYCHANNELS = 0x195;
static const Word ERR_WASNOSUCHNICK = 0x196;
static const Word ERR_TOOMANYTARGETS = 0x197;
static const Word ERR_NOORIGIN = 0x199;
static const Word ERR_NORECIPIENT = 0x19b;
static const Word ERR_NOTEXTTOSEND = 0x19c;
static const Word ERR_NOTOPLEVEL = 0x19d;
static const Word ERR_WILDTOPLEVEL = 0x19e;
static const Word ERR_UNKNOWNCOMMAND = 0x1a5;
static const Word ERR_NOMOTD = 0x1a6;
static const Word ERR_NOADMININFO = 0x1a7;
static const Word ERR_FILEERROR = 0x1a8;
static const Word ERR_NONICKNAMEGIVEN = 0x1af;
static const Word ERR_ERRONEUSNICKNAME = 0x1b0;
static const Word ERR_NICKNAMEINUSE = 0x1b1;
static const Word ERR_NICKCOLLISION = 0x1b4;
static const Word ERR_USERNOTINCHANNEL = 0x1b9;
static const Word ERR_NOTONCHANNEL = 0x1ba;
static const Word ERR_USERONCHANNEL = 0x1bb;
static const Word ERR_NOLOGIN = 0x1bc;
static const Word ERR_SUMMONDISABLED = 0x1bd;
static const Word ERR_USERSDISABLED = 0x1be;
static const Word ERR_NOTREGISTERED = 0x1c3;
static const Word ERR_NEEDMOREPARAMS = 0x1cd;
static const Word ERR_ALREADYREGISTRED = 0x1ce;
static const Word ERR_NOPERMFORHOST = 0x1cf;
static const Word ERR_PASSWDMISMATCH = 0x1d0;
static const Word ERR_YOUREBANNEDCREEP = 0x1d1;
static const Word ERR_KEYSET = 0x1d3;
static const Word ERR_CHANNELISFULL = 0x1d7;
static const Word ERR_UNKNOWNMODE = 0x1d8;
static const Word ERR_INVITEONLYCHAN = 0x1d9;
static const Word ERR_BANNEDFROMCHAN = 0x1da;
static const Word ERR_BADCHANNELKEY = 0x1db;
static const Word ERR_NOPRIVILEGES = 0x1e1;
static const Word ERR_CHANOPRIVSNEEDED = 0x1e2;
static const Word ERR_CANTKILLSERVER = 0x1e3;
static const Word ERR_NOOPERHOST = 0x1eb;
static const Word ERR_UMODEUNKNOWNFLAG = 0x1f5;
static const Word ERR_USERSDONTMATCH = 0x1f6;
extern PACKAGE Byte IdIrcMinTokenCount;

}	/* namespace Idirc */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idirc;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdIRC
