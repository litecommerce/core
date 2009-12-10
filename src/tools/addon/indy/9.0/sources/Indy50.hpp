// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'Indy50.pas' rev: 5.00

#ifndef Indy50HPP
#define Indy50HPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <IdASN1Util.hpp>	// Pascal unit
#include <Registry.hpp>	// Pascal unit
#include <IniFiles.hpp>	// Pascal unit
#include <SyncObjs.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <TypInfo.hpp>	// Pascal unit
#include <Consts.hpp>	// Pascal unit
#include <ActiveX.hpp>	// Pascal unit
#include <Messages.hpp>	// Pascal unit
#include <SysUtils.hpp>	// Pascal unit
#include <SysConst.hpp>	// Pascal unit
#include <Windows.hpp>	// Pascal unit
#include <IdWinSock2.hpp>	// Pascal unit
#include <IdWhoIsServer.hpp>	// Pascal unit
#include <IdWhois.hpp>	// Pascal unit
#include <IdVCard.hpp>	// Pascal unit
#include <IdUserAccounts.hpp>	// Pascal unit
#include <IdURI.hpp>	// Pascal unit
#include <IdUDPServer.hpp>	// Pascal unit
#include <IdUDPClient.hpp>	// Pascal unit
#include <IdUDPBase.hpp>	// Pascal unit
#include <IdTunnelSlave.hpp>	// Pascal unit
#include <IdTunnelMaster.hpp>	// Pascal unit
#include <IdTunnelCommon.hpp>	// Pascal unit
#include <IdTrivialFTPServer.hpp>	// Pascal unit
#include <IdTrivialFTPBase.hpp>	// Pascal unit
#include <IdTrivialFTP.hpp>	// Pascal unit
#include <IdTimeUDPServer.hpp>	// Pascal unit
#include <IdTimeUDP.hpp>	// Pascal unit
#include <IdTimeServer.hpp>	// Pascal unit
#include <IdTime.hpp>	// Pascal unit
#include <IdThreadSafe.hpp>	// Pascal unit
#include <IdThreadMgrPool.hpp>	// Pascal unit
#include <IdThreadMgrDefault.hpp>	// Pascal unit
#include <IdThreadMgr.hpp>	// Pascal unit
#include <IdThreadComponent.hpp>	// Pascal unit
#include <IdThread.hpp>	// Pascal unit
#include <IdTelnetServer.hpp>	// Pascal unit
#include <IdTelnet.hpp>	// Pascal unit
#include <IdTCPStream.hpp>	// Pascal unit
#include <IdTCPServer.hpp>	// Pascal unit
#include <IdTCPConnection.hpp>	// Pascal unit
#include <IdTCPClient.hpp>	// Pascal unit
#include <IdSysLogServer.hpp>	// Pascal unit
#include <IdSysLogMessage.hpp>	// Pascal unit
#include <IdSysLog.hpp>	// Pascal unit
#include <IdSync.hpp>	// Pascal unit
#include <IdStrings.hpp>	// Pascal unit
#include <IdStream.hpp>	// Pascal unit
#include <IdStackWindows.hpp>	// Pascal unit
#include <IdStackConsts.hpp>	// Pascal unit
#include <IdStack.hpp>	// Pascal unit
#include <IdSSLOpenSSLHeaders.hpp>	// Pascal unit
#include <IdSSLOpenSSL.hpp>	// Pascal unit
#include <IdSocks.hpp>	// Pascal unit
#include <IdSocketHandle.hpp>	// Pascal unit
#include <IdSNTP.hpp>	// Pascal unit
#include <IdSNPP.hpp>	// Pascal unit
#include <IdSNMP.hpp>	// Pascal unit
#include <IdSMTPServer.hpp>	// Pascal unit
#include <IdSMTP.hpp>	// Pascal unit
#include <IdSimpleServer.hpp>	// Pascal unit
#include <IdServerIOHandlerSocket.hpp>	// Pascal unit
#include <IdServerIOHandler.hpp>	// Pascal unit
#include <IdRSHServer.hpp>	// Pascal unit
#include <IdRSH.hpp>	// Pascal unit
#include <IdRFCReply.hpp>	// Pascal unit
#include <IdRexecServer.hpp>	// Pascal unit
#include <IdRexec.hpp>	// Pascal unit
#include <IdResourceStrings.hpp>	// Pascal unit
#include <IdRemoteCMDServer.hpp>	// Pascal unit
#include <IdRemoteCMDClient.hpp>	// Pascal unit
#include <IdRawHeaders.hpp>	// Pascal unit
#include <IdRawFunctions.hpp>	// Pascal unit
#include <IdRawClient.hpp>	// Pascal unit
#include <IdRawBase.hpp>	// Pascal unit
#include <IdQOTDUDPServer.hpp>	// Pascal unit
#include <IdQOTDUDP.hpp>	// Pascal unit
#include <IdQotdServer.hpp>	// Pascal unit
#include <IdQotd.hpp>	// Pascal unit
#include <IdPOP3Server.hpp>	// Pascal unit
#include <IdPOP3.hpp>	// Pascal unit
#include <IdNTLM.hpp>	// Pascal unit
#include <IdNNTPServer.hpp>	// Pascal unit
#include <IdNNTP.hpp>	// Pascal unit
#include <IdNetworkCalculator.hpp>	// Pascal unit
#include <IdMultipartFormData.hpp>	// Pascal unit
#include <IdMIMETypes.hpp>	// Pascal unit
#include <IdMessageCollection.hpp>	// Pascal unit
#include <IdMessageCoderXXE.hpp>	// Pascal unit
#include <IdMessageCoderUUE.hpp>	// Pascal unit
#include <IdMessageCoderMIME.hpp>	// Pascal unit
#include <IdMessageCoder.hpp>	// Pascal unit
#include <IdMessageClient.hpp>	// Pascal unit
#include <IdMessage.hpp>	// Pascal unit
#include <IdMappedPortUDP.hpp>	// Pascal unit
#include <IdMappedPortTCP.hpp>	// Pascal unit
#include <IdMappedFTP.hpp>	// Pascal unit
#include <IdMailBox.hpp>	// Pascal unit
#include <IdLPR.hpp>	// Pascal unit
#include <IdLogStream.hpp>	// Pascal unit
#include <IdLogFile.hpp>	// Pascal unit
#include <IdLogEvent.hpp>	// Pascal unit
#include <IdLogDebug.hpp>	// Pascal unit
#include <IdLogBase.hpp>	// Pascal unit
#include <IdIrcServer.hpp>	// Pascal unit
#include <IdIRC.hpp>	// Pascal unit
#include <IdIPWatch.hpp>	// Pascal unit
#include <IdIPMCastServer.hpp>	// Pascal unit
#include <IdIPMCastClient.hpp>	// Pascal unit
#include <IdIPMCastBase.hpp>	// Pascal unit
#include <IdIOHandlerThrottle.hpp>	// Pascal unit
#include <IdIOHandlerStream.hpp>	// Pascal unit
#include <IdIOHandlerSocket.hpp>	// Pascal unit
#include <IdIOHandler.hpp>	// Pascal unit
#include <IdIntercept.hpp>	// Pascal unit
#include <IdIMAP4Server.hpp>	// Pascal unit
#include <IdIMAP4.hpp>	// Pascal unit
#include <IdIdentServer.hpp>	// Pascal unit
#include <IdIdent.hpp>	// Pascal unit
#include <IdIcmpClient.hpp>	// Pascal unit
#include <IdHTTPServer.hpp>	// Pascal unit
#include <IdHTTPHeaderInfo.hpp>	// Pascal unit
#include <IdHTTP.hpp>	// Pascal unit
#include <IdHL7.hpp>	// Pascal unit
#include <IdHeaderList.hpp>	// Pascal unit
#include <IdHashMessageDigest.hpp>	// Pascal unit
#include <IdHashElf.hpp>	// Pascal unit
#include <IdHashCRC.hpp>	// Pascal unit
#include <IdHash.hpp>	// Pascal unit
#include <IdGopherServer.hpp>	// Pascal unit
#include <IdGopherConsts.hpp>	// Pascal unit
#include <IdGopher.hpp>	// Pascal unit
#include <IdGlobal.hpp>	// Pascal unit
#include <IdFTPServer.hpp>	// Pascal unit
#include <IdFTPList.hpp>	// Pascal unit
#include <IdFTPCommon.hpp>	// Pascal unit
#include <IdFTP.hpp>	// Pascal unit
#include <IdFingerServer.hpp>	// Pascal unit
#include <IdFinger.hpp>	// Pascal unit
#include <IdException.hpp>	// Pascal unit
#include <IdEMailAddress.hpp>	// Pascal unit
#include <IdEchoUDPServer.hpp>	// Pascal unit
#include <IdEchoUDP.hpp>	// Pascal unit
#include <IdEchoServer.hpp>	// Pascal unit
#include <IdEcho.hpp>	// Pascal unit
#include <IdDNSResolver.hpp>	// Pascal unit
#include <IdDiscardUDPServer.hpp>	// Pascal unit
#include <IdDiscardServer.hpp>	// Pascal unit
#include <IdDICTServer.hpp>	// Pascal unit
#include <IdDayTimeUDPServer.hpp>	// Pascal unit
#include <IdDayTimeUDP.hpp>	// Pascal unit
#include <IdDayTimeServer.hpp>	// Pascal unit
#include <IdDayTime.hpp>	// Pascal unit
#include <IdDateTimeStamp.hpp>	// Pascal unit
#include <IdCustomHTTPServer.hpp>	// Pascal unit
#include <IdCookieManager.hpp>	// Pascal unit
#include <IdCookie.hpp>	// Pascal unit
#include <IdCompressionIntercept.hpp>	// Pascal unit
#include <IdComponent.hpp>	// Pascal unit
#include <IdCoderXXE.hpp>	// Pascal unit
#include <IdCoderUUE.hpp>	// Pascal unit
#include <IdCoderQuotedPrintable.hpp>	// Pascal unit
#include <IdCoderMIME.hpp>	// Pascal unit
#include <IdCoderHeader.hpp>	// Pascal unit
#include <IdCoder3to4.hpp>	// Pascal unit
#include <IdCoder.hpp>	// Pascal unit
#include <IdChargenUDPServer.hpp>	// Pascal unit
#include <IdChargenServer.hpp>	// Pascal unit
#include <IdBlockCipherIntercept.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <IdAuthenticationNTLM.hpp>	// Pascal unit
#include <IdAuthenticationManager.hpp>	// Pascal unit
#include <IdAuthenticationDigest.hpp>	// Pascal unit
#include <IdAuthentication.hpp>	// Pascal unit
#include <IdAssignedNumbers.hpp>	// Pascal unit
#include <IdAntiFreezeBase.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Indy50
{
//-- type declarations -------------------------------------------------------
//-- var, const, procedure ---------------------------------------------------

}	/* namespace Indy50 */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Indy50;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// Indy50
