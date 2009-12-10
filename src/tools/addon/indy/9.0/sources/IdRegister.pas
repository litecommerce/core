{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10297: IdRegister.pas 
{
{   Rev 1.0    2002.11.12 10:49:44 PM  czhower
}
unit IdRegister;

{$I IdCompilerDefines.inc}

interface

{DEFINE Borland}

{$IFDEF Borland}
  {$R IdRegister.dcr}
{$ELSE}
  {$R IdRegisterCool.dcr}
{$ENDIF}
uses
{$IFDEF VCL6ORABOVE}DesignIntf, DesignEditors,{$ELSE}Dsgnintf,{$ENDIF}
  Classes;

// Procs
  procedure Register;

implementation

uses
  IdAntiFreeze, IdBlockCipherIntercept,
  IdChargenServer,
  IdChargenUDPServer, IdCoder3to4,
  IdCoderMIME, IdCoderQuotedPrintable,
  IdCoderUUE, IdCoderXXE,
  IdCompressionIntercept,
  IdCookieManager, IdDateTimeStamp,
  IdDayTime, IdDayTimeServer,
  IdDayTimeUDP, IdDayTimeUDPServer,
  IdDICTServer, IdDiscardServer,
  IdDiscardUDPServer, IdDNSResolver,
  IdEcho, IdEchoServer,
  IdEchoUDP, IdEchoUDPServer,
  IdFinger, IdFingerServer,
  IdFTP, IdFTPServer,
  IdGopher, IdGopherServer,
  IdHashMessageDigest, IdHL7,
  IdHTTP,
  IdHTTPServer, IdIcmpClient,
  IdIdent, IdIdentServer,
  IdIMAP4, IdIMAP4Server,
  IdIntercept, IdIOHandlerSocket,
  IdIOHandlerStream,
  IdIOHandlerThrottle,
  IdIPMCastClient,
  IdIPMCastServer, IdIPWatch,
  IdIRC, IdIrcServer,
  IdLogDebug, IdLogEvent,
  IdLogFile, IdLogStream, IdLPR,
  IdMailBox, IdMappedFTP, IdMappedPortTCP,
  IdMappedPortUDP, IdMessage,
  IdMessageCoderMIME, IdNetworkCalculator,
  IdNNTP, IdNNTPServer,
  IdPOP3, IdPOP3Server,
  IdQotd, IdQotdServer,
  IdQOTDUDP, IdQOTDUDPServer,
  IdResourceStrings, IdRexec,
  IdRexecServer, IdRSH,
  IdRSHServer, IdServerIOHandler,
  IdServerIOHandlerSocket, IdSimpleServer,
  IdSMTP, IdSMTPServer,
  IdSNMP, IdSNPP,
  IdSNTP, IdSocks,
  IdSSLOpenSSL, IdSysLog,
  IdSysLogMessage, IdSysLogServer,
  IdTCPClient, IdTCPServer,
  IdTelnet, IdTelnetServer,
  IdThreadComponent,
  IdThreadMgrDefault, IdThreadMgrPool,
  IdTime, IdTimeServer,
  IdTimeUDP, IdTimeUDPServer,
  IdTrivialFTP, IdTrivialFTPServer,
  IdTunnelMaster, IdTunnelSlave,
  IdUDPClient, IdUDPServer,
  IdUserAccounts, IdVCard,
  IdWhois, IdWhoIsServer;

procedure Register;
begin
  RegisterComponents(RSRegIndyClients, [   {do not localize}
   TIdTCPClient,
   TIdUDPClient,
   //
   TIdDayTime,
   TIdDayTimeUDP,
   TIdDNSResolver,
   TIdEcho,
   TIdEchoUDP,
   TIdFinger,
   TIdFTP,
   TIdGopher,
   TIdHTTP,
   TIdIcmpClient,
   TIdIdent,
   TIdIMAP4,
   TIdIPMCastClient,
   TIdIRC,
   TIdLPR,
   TIdNNTP,
   TIdPOP3,
   TIdQOTD,
   TIdQOTDUDP,
   TIdRexec,
   TIdRSH,
   TIdSMTP,
   TIdSNMP,
   TIdSNPP,
   TIdSNTP,
   TIdSysLog,
   TIdTelnet,
   TIdTime,
   TIdTimeUDP,
   TIdTrivialFTP,
   TIdWhois]);

  RegisterComponents(RSRegIndyServers, [
   TIdTCPServer,
   TIdUDPServer,
   TIdChargenServer,
   TIdChargenUDPServer,
   TIdDayTimeServer,
   TIdDayTimeUDPServer,
   TIdDICTServer,
   TIdDISCARDServer,
   TIdDiscardUDPServer,
   TIdECHOServer,
   TIdEchoUDPServer,
   TIdFingerServer,
   TIdFTPServer,
   TIdGopherServer,
   TIdHTTPServer,
   TIdIdentServer,
   TIdIMAP4Server,
   TIdIPMCastServer,
   TIdIRCServer,
   TIdMappedFTP,
   TIdMappedPop3,
   TIdMappedPortTCP,
   TIdMappedPortUDP,
   TIdMappedTelnet,
   TIdNNTPServer,
   TIdPOP3Server,
   TIdQOTDServer,
   TIdQotdUDPServer,
   TIdRexecServer,
   TIdRSHServer,
   TIdSimpleServer,
   TIdSMTPServer,
   TIdSyslogServer,
   TIdTelnetServer,
   TIdTimeServer,
   TIdTimeUDPServer,
   TIdTrivialFTPServer,
   TIdTunnelMaster,
   TIdTunnelSlave,
   TIdWhoIsServer

   ]);
  RegisterComponents(RSRegIndyIntercepts,
    [TIdBlockCipherIntercept,
     TIdConnectionIntercept,
     TIdCompressionIntercept,
     TIdLogDebug,
     TIdLogEvent,
     TIdLogFile,
     TIdLogStream]);
  RegisterComponents(RSRegIndyIOHandlers,[
   {Open SSL should be supported in Kylix now}
   TIdIOHandlerSocket,
   TIdIOHandlerStream,
   TIdIOHandlerThrottle,
   TIdServerIOHandlerSocket,
   TIdServerIOHandlerSSL,
   TIdSSLIOHandlerSocket]
  );

  RegisterComponents(RSRegIndyMisc, [  {do not localize}
    TIdSocksInfo,
    TIdAntiFreeze,
   TIdCookieManager,
   TIdEncoderMIME,
   TIdEncoderUUE,
   TIdEncoderXXE,
   TIdEncoderQuotedPrintable,
   TIdDateTimeStamp,
   TIdDecoderMIME,
   TIdDecoderUUE,
   TIdDecoderXXE,
   TIdDecoderQuotedPrintable,
   TIdIPWatch,
   TIdHL7,

   TIdMailBox,
   TIdMessage,
   TIdMessageDecoderMIME,
   TIdNetworkCalculator,
   TIdSysLogMessage,
   TIdThreadComponent,
   TIdThreadMgrDefault,
   TIdThreadMgrPool,
   TIdUserManager,
   TIdVCard
   ]);
end;

end.
