{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10305: IdResourceStrings.pas 
{
{   Rev 1.3    16/06/2003 11:06:24 PM  ANeillans
{ Copyright update
}
{
{   Rev 1.2    4/20/03 1:46:38 PM  RLebeau
{ Added RSStackInvalidIP.
}
{
{   Rev 1.1    3/3/2003 03:31:54 AM  JPMugaas
{ Removed extra "is" from the PWD message in IdFTPServer.
}
{
{   Rev 1.0    2002.11.12 10:50:18 PM  czhower
}
unit IdResourceStrings;

interface

resourcestring
  // General
  RSAlreadyConnected = 'Already connected.';
  RSByteIndexOutOfBounds = 'Byte index out of range.';
  RSCannotAllocateSocket = 'Cannot allocate socket.';
  RSConnectionClosedGracefully = 'Connection Closed Gracefully.';
  RSCouldNotBindSocket = 'Could not bind socket. Address and port are already in use.';
  RSFailedTimeZoneInfo = 'Failed attempting to retrieve time zone information.';
  RSNoBindingsSpecified = 'No bindings specified.';
  RSOnExecuteNotAssigned = 'OnExecute not assigned.';
  RSNotAllBytesSent = 'Not all bytes sent.';
  RSNotEnoughDataInBuffer = 'Not enough data in buffer.';
  RSPackageSizeTooBig = 'Package Size Too Big.';
  RSRawReceiveError0 = 'Raw Receive Error = 0.';
  RSICMPReceiveError0 = 'ICMP Receive Error = 0.';
  RSWinsockInitializationError = 'Winsock Initialization Error.';
  RSCouldNotLoad = '%s could not be loaded.';
  RSSetSizeExceeded = 'Set Size Exceeded.';
  RSThreadClassNotSpecified = 'Thread Class Not Specified.';
  RSFileNotFound = 'File "%s" not found';
  RSCannotChangeDebugTargetAtWhileActive = 'Cannot change target while active.';
  RSOnlyOneAntiFreeze = 'Only one TIdAntiFreeze can exist per application.';
  RSInterceptCircularLink = '%d: Circular links are not allowed';
  RSInterceptPropIsNil = 'InterceptEnabled cannot be set to true when Intercept is nil.';
  RSIOHandlerPropInvalid = 'IOHandler value is not valid';
  RSNotConnected         = 'Not Connected';
  RSObjectTypeNotSupported = 'Object type not supported.';
  RSAcceptWaitCannotBeModifiedWhileServerIsActive
    = 'AcceptWait property cannot be modified while server is active.';
  RSTerminateThreadTimeout = 'Terminate Thread Timeout';
  RSNoExecuteSpecified = 'No execute handler found.';
  RSIdNoDataToRead = 'No data to read.';
  RSCanNotBindRange = 'Can not bind in port range (%d - %d)';
  RSInvalidPortRange = 'Invalid Port Range (%d - %d)';
  RSReadTimeout = 'Read Timeout';
  RSReadLnMaxLineLengthExceeded = 'Max line length exceeded.';
  RSUDPReceiveError0 = 'UDP Receive Error = 0.';
  RSNoCommandHandlerFound = 'No command handler found.';
  RSCannotPerformTaskWhileServerIsActive = 'Cannot perform task while server is active.';
  //  TIdEMailAddress 
  RSEMailSymbolOutsideAddress = '@ Outside address';
  //ZLIB Intercept
  RSZLCompressorInitializeFailure = 'Unable to initialize compressor';
  RSZLDecompressorInitializeFailure = 'Unable to initialize decompressor';
  RSZLCompressionError = 'Compression error';
  RSZLDecompressionError = 'Decompression error';
  //Winsock 2 Stub
  RSWS2CallError = 'Error on call Winsock2 library function %s';
  RSWS2LoadError = 'Error on loading Winsock2 library (%s)';
  //MIME Types
  RSMIMEExtensionEmpty = 'Extension is empty';
  RSMIMEMIMETypeEmpty = 'Mimetype is empty';
  RSMIMEMIMEExtAlreadyExists = 'Extension already exits';
  // IdRegister
  RSRegIndyClients = 'Indy Clients';
  RSRegIndyServers = 'Indy Servers';
  RSRegIndyIntercepts = 'Indy Intercepts';
  RSRegIndyIOHandlers = 'Indy I/O Handlers';
  RSRegIndyMisc = 'Indy Misc';
  // Status Strings
  RSStatusResolving = 'Resolving hostname %s.';
  RSStatusConnecting = 'Connecting to %s.';
  RSStatusConnected = 'Connected.';
  RSStatusDisconnecting = 'Disconnecting.';
  RSStatusDisconnected = 'Disconnected.';
  RSStatusText = '%s';
  // TIdTCPClient
  RSConnectTimeout = 'Connect timed out.';
  // MessageClient Strings
  RSMsgClientEncodingText = 'Encoding text';
  RSMsgClientEncodingAttachment = 'Encoding attachment';
  RSMsgClientUnkownMessagePartType = 'Unknown Message Part Type.';
  RSMsgClientInvalidEncoding = 'Invalid Encoding. UU only allows Body and Attachments';
  // NNTP Exceptions
  RSNNTPConnectionRefused = 'Connection explicitly refused by NNTP server.';
  RSNNTPStringListNotInitialized = 'Stringlist not initialized!';
  RSNNTPNoOnNewsgroupList = 'No OnNewsgroupList event has been defined.';
  RSNNTPNoOnNewGroupsList = 'No OnNewGroupsList event has been defined.';
  RSNNTPNoOnNewNewsList = 'No OnNewNewsList event has been defined.';
  // Log strings
  RSLogConnected = 'Connected.';
  RSLogDisconnected = 'Disconnected.';
  RSLogEOL = '<EOL>'; //End of Line
  RSLogCR  = '<CR>'; //Carriage Return
  RSLogLF  = '<LF>'; //Line feed
  RSLogRecv = 'Recv '; //Receive
  RSLogSent = 'Sent '; //Send
  RSLogStat = 'Stat '; //Status
  // HTTP Status
  RSHTTPChunkStarted = 'Chunk Started';
  RSHTTPContinue = 'Continue';
  RSHTTPSwitchingProtocols = 'Switching protocols';
  RSHTTPOK = 'OK';
  RSHTTPCreated = 'Created';
  RSHTTPAccepted = 'Accepted';
  RSHTTPNonAuthoritativeInformation = 'Non-authoritative Information';
  RSHTTPNoContent = 'No Content';
  RSHTTPResetContent = 'Reset Content';
  RSHTTPPartialContent = 'Partial Content';
  RSHTTPMovedPermanently = 'Moved Permanently';
  RSHTTPMovedTemporarily = 'Moved Temporarily';
  RSHTTPSeeOther = 'See Other';
  RSHTTPNotModified = 'Not Modified';
  RSHTTPUseProxy = 'Use Proxy';
  RSHTTPBadRequest = 'Bad Request';
  RSHTTPUnauthorized = 'Unauthorized';
  RSHTTPForbidden = 'Forbidden';
  RSHTTPNotFound = 'Not Found';
  RSHTTPMethodeNotallowed = 'Method not allowed';
  RSHTTPNotAcceptable = 'Not Acceptable';
  RSHTTPProxyAuthenticationRequired = 'Proxy Authentication Required';
  RSHTTPRequestTimeout = 'Request Timeout';
  RSHTTPConflict = 'Conflict';
  RSHTTPGone = 'Gone';
  RSHTTPLengthRequired = 'Length Required';
  RSHTTPPreconditionFailed = 'Precondition Failed';
  RSHTTPRequestEntityToLong = 'Request Entity To Long';
  RSHTTPRequestURITooLong = 'Request-URI Too Long. 256 Chars max';
  RSHTTPUnsupportedMediaType = 'Unsupported Media Type';
  RSHTTPInternalServerError = 'Internal Server Error';
  RSHTTPNotImplemented = 'Not Implemented';
  RSHTTPBadGateway = 'Bad Gateway';
  RSHTTPServiceUnavailable = 'Service Unavailable';
  RSHTTPGatewayTimeout = 'Gateway timeout';
  RSHTTPHTTPVersionNotSupported = 'HTTP version not supported';
  RSHTTPUnknownResponseCode = 'Unknown Response Code';
  // HTTP Other
  RSHTTPHeaderAlreadyWritten = 'Header has already been written.';
  RSHTTPErrorParsingCommand = 'Error in parsing command.';
  RSHTTPUnsupportedAuthorisationScheme = 'Unsupported authorization scheme.';
  RSHTTPCannotSwitchSessionStateWhenActive = 'Cannot change session state when the server is active.';
  //HTTP Authentication
  RSHTTPAuthAlreadyRegistered = 'This authentication method is already registered with class name %s.';
  //HTTP Authentication Digeest
  RSHTTPAuthInvalidHash = 'Unsupported hash algorithm. This implementation supports only MD5 encoding.';
  //SSPI Authentication
  {
  Note: CompleteToken is an API function Name:
  }
  RSHTTPSSPISuccess = 'Successfull API call';
  RSHTTPSSPINotEnoughMem = 'Not enough memory is available to complete this request';
  RSHTTPSSPIInvalidHandle = 'The handle specified is invalid';
  RSHTTPSSPIFuncNotSupported = 'The function requested is not supported';
  RSHTTPSSPIUnknownTarget = 'The specified target is unknown or unreachable';
  RSHTTPSSPIInternalError = 'The Local Security Authority cannot be contacted';
  RSHTTPSSPISecPackageNotFound = 'The requested security package does not exist';
  RSHTTPSSPINotOwner = 'The caller is not the owner of the desired credentials';
  RSHTTPSSPIPackageCannotBeInstalled = 'The security package failed to initialize, and cannot be installed';
  RSHTTPSSPIInvalidToken = 'The token supplied to the function is invalid';
  RSHTTPSSPICannotPack = 'The security package is not able to marshall the logon buffer, so the logon attempt has failed';
  RSHTTPSSPIQOPNotSupported = 'The per-message Quality of Protection is not supported by the security package';
  RSHTTPSSPINoImpersonation = 'The security context does not allow impersonation of the client';
  RSHTTPSSPILoginDenied = 'The logon attempt failed';
  RSHTTPSSPIUnknownCredentials = 'The credentials supplied to the package were not recognized';
  RSHTTPSSPINoCredentials = 'No credentials are available in the security package';
  RSHTTPSSPIMessageAltered = 'The message or signature supplied for verification has been altered';
  RSHTTPSSPIOutOfSequence = 'The message supplied for verification is out of sequence';
  RSHTTPSSPINoAuthAuthority = 'No authority could be contacted for authentication.';
  RSHTTPSSPIContinueNeeded = 'The function completed successfully, but must be called again to complete the context';
  RSHTTPSSPICompleteNeeded = 'The function completed successfully, but CompleteToken must be called';
  RSHTTPSSPICompleteContinueNeeded =  'The function completed successfully, but both CompleteToken and this function must be called to complete the context';
  RSHTTPSSPILocalLogin = 'The logon was completed, but no network authority was available. The logon was made using locally known information';
  RSHTTPSSPIBadPackageID = 'The requested security package does not exist';
  RSHTTPSSPIContextExpired = 'The context has expired and can no longer be used.';
  RSHTTPSSPIIncompleteMessage = 'The supplied message is incomplete.  The signature was not verified.';
  RSHTTPSSPIIncompleteCredentialNotInit =  'The credentials supplied were not complete, and could not be verified. The context could not be initialized.';
  RSHTTPSSPIBufferTooSmall = 'The buffers supplied to a function was too small.';
  RSHTTPSSPIIncompleteCredentialsInit = 'The credentials supplied were not complete, and could not be verified. Additional information can be returned from the context.';
  RSHTTPSSPIRengotiate = 'The context data must be renegotiated with the peer.';
  RSHTTPSSPIWrongPrincipal = 'The target principal name is incorrect.';
  RSHTTPSSPINoLSACode = 'There is no LSA mode context associated with this context.';
  RSHTTPSSPITimeScew = 'The clocks on the client and server machines are skewed.';
  RSHTTPSSPIUntrustedRoot = 'The certificate chain was issued by an untrusted authority.';
  RSHTTPSSPIIllegalMessage = 'The message received was unexpected or badly formatted.';
  RSHTTPSSPICertUnknown = 'An unknown error occurred while processing the certificate.';
  RSHTTPSSPICertExpired = 'The received certificate has expired.';
  RSHTTPSSPIEncryptionFailure = 'The specified data could not be encrypted.';
  RSHTTPSSPIDecryptionFailure = 'The specified data could not be decrypted.';
  RSHTTPSSPIAlgorithmMismatch = 'The client and server cannot communicate, because they do not possess a common algorithm.';
  RSHTTPSSPISecurityQOSFailure = 'The security context could not be established due to a failure in the requested quality of service (e.g. mutual authentication or delegation).';
  RSHTTPSSPIUnknwonError = 'Unknown error';
  {
  Note to translators - the parameters for the next message are below:

  Failed Function Name
  Error Number
  Error Number
  Error Message by Number
  }

  RSHTTPSSPIErrorMsg = 'SSPI %s returns error #%d(0x%x): %s';

  RSHTTPSSPIInterfaceInitFailed = 'SSPI interface has failed to initialise properly';
  RSHTTPSSPINoPkgInfoSpecified = 'No PSecPkgInfo specified';
  RSHTTPSSPINoCredentialHandle = 'No credential handle acquired';
  RSHTTPSSPICanNotChangeCredentials = 'Can not change credentials after handle aquired. Use Release first';
  RSHTTPSSPIUnknwonCredentialUse = 'Unknown credentials use';
  RSHTTPSSPIDoAuquireCredentialHandle = 'Do AcquireCredentialsHandle first';
  RSHTTPSSPICompleteTokenNotSupported = 'CompleteAuthToken is not supported';

  //Block Cipher Intercept
  RSBlockIncorrectLength = 'Incorrect length in received block';

  // FTP
  RSFTPUnknownHost = 'Unknown';
  RSInvalidFTPListingFormat = 'Unknown FTP listing format';
  RSFTPStatusReady = 'Connection established';
  RSFTPStatusStartTransfer = 'Starting FTP transfer';
  RSFTPStatusDoneTransfer  = 'Transfer complete';
  RSFTPStatusAbortTransfer = 'Transfer aborted';

  // Property editor exceptions
  RSCorruptServicesFile = '%s is corrupt.';
  RSInvalidServiceName = '%s is not a valid service.';
  // Stack Error Messages
  RSStackError = 'Socket Error # %d' + #13#10 + '%s';
  RSStackInvalidIP = '%s is not a valid IP address.';
  RSStackEINTR = 'Interrupted system call.';
  RSStackEBADF = 'Bad file number.';
  RSStackEACCES = 'Access denied.';
  RSStackEFAULT = 'Bad address.';
  RSStackEINVAL = 'Invalid argument.';
  RSStackEMFILE = 'Too many open files.';
  RSStackEWOULDBLOCK = 'Operation would block. ';
  RSStackEINPROGRESS = 'Operation now in progress.';
  RSStackEALREADY = 'Operation already in progress.';
  RSStackENOTSOCK = 'Socket operation on non-socket.';
  RSStackEDESTADDRREQ = 'Destination address required.';
  RSStackEMSGSIZE = 'Message too long.';
  RSStackEPROTOTYPE = 'Protocol wrong type for socket.';
  RSStackENOPROTOOPT = 'Bad protocol option.';
  RSStackEPROTONOSUPPORT = 'Protocol not supported.';
  RSStackESOCKTNOSUPPORT = 'Socket type not supported.';
  RSStackEOPNOTSUPP = 'Operation not supported on socket.';
  RSStackEPFNOSUPPORT = 'Protocol family not supported.';
  RSStackEAFNOSUPPORT = 'Address family not supported by protocol family.';
  RSStackEADDRINUSE = 'Address already in use.';
  RSStackEADDRNOTAVAIL = 'Cannot assign requested address.';
  RSStackENETDOWN = 'Network is down.';
  RSStackENETUNREACH = 'Network is unreachable.';
  RSStackENETRESET = 'Net dropped connection or reset.';
  RSStackECONNABORTED = 'Software caused connection abort.';
  RSStackECONNRESET = 'Connection reset by peer.';
  RSStackENOBUFS = 'No buffer space available.';
  RSStackEISCONN = 'Socket is already connected.';
  RSStackENOTCONN = 'Socket is not connected.';
  RSStackESHUTDOWN = 'Cannot send or receive after socket is closed.';
  RSStackETOOMANYREFS = 'Too many references, cannot splice.';
  RSStackETIMEDOUT = 'Connection timed out.';
  RSStackECONNREFUSED = 'Connection refused.';
  RSStackELOOP = 'Too many levels of symbolic links.';
  RSStackENAMETOOLONG = 'File name too long.';
  RSStackEHOSTDOWN = 'Host is down.';
  RSStackEHOSTUNREACH = 'No route to host.';
  RSStackENOTEMPTY = 'Directory not empty';
  RSStackEPROCLIM = 'Too many processes.';
  RSStackEUSERS = 'Too many users.';
  RSStackEDQUOT = 'Disk Quota Exceeded.';
  RSStackESTALE = 'Stale NFS file handle.';
  RSStackEREMOTE = 'Too many levels of remote in path.';
  RSStackSYSNOTREADY = 'Network subsystem is unavailable.';
  RSStackVERNOTSUPPORTED = 'WINSOCK DLL Version out of range.';
  RSStackNOTINITIALISED = 'Winsock not loaded yet.';
  RSStackHOST_NOT_FOUND = 'Host not found.';
  RSStackTRY_AGAIN = 'Non-authoritative response (try again or check DNS setup).';
  RSStackNO_RECOVERY = 'Non-recoverable errors: FORMERR, REFUSED, NOTIMP.';
  RSStackNO_DATA = 'Valid name, no data record (check DNS setup).';

  RSCMDNotRecognized = 'command not recognized';

  RSGopherNotGopherPlus = '%s is not a Gopher+ server';

  RSCodeNoError     = 'RCode NO Error';
  RSCodeQueryFormat = 'DNS Server Reports Query Format Error';
  RSCodeQueryServer = 'DNS Server Reports Query Server Error';
  RSCodeQueryName   = 'DNS Server Reports Query Name Error';
  RSCodeQueryNotImplemented = 'DNS Server Reports Query Not Implemented Error';
  RSCodeQueryQueryRefused = 'DNS Server Reports Query Refused Error';
  RSCodeQueryUnknownError = 'Server Returned Unknown Error';

  RSDNSTimeout = 'TimedOut';
  RSDNSMFIsObsolete = 'MF is an Obsolete Command. USE MX.';
  RSDNSMDISObsolete = 'MD is an Obsolete Command. Use MX.';
  RSDNSMailAObsolete = 'MailA is an Obsolete Command. USE MX.';
  RSDNSMailBNotImplemented = '-Err 501 MailB is not implemented';

  RSQueryInvalidQueryCount = 'Invalid Query Count %d';
  RSQueryInvalidPacketSize = 'Invalid Packet Size %d';
  RSQueryLessThanFour = 'Received Packet is too small. Less than 4 bytes %d';
  RSQueryInvalidHeaderID = 'Invalid Header Id %d';
  RSQueryLessThanTwelve = 'Received Packet is too small. Less than 12 bytes %d';
  RSQueryPackReceivedTooSmall = 'Received Packet is too small. %d';

  { LPD Client Logging event strings }
  RSLPDDataFileSaved = 'Data file saved to %s';
  RSLPDControlFileSaved = 'Control file save to %s';
  RSLPDDirectoryDoesNotExist = 'Directory %s does not exist';
  RSLPDServerStartTitle = 'Winshoes LPD Server %s ';
  RSLPDServerActive = 'Server status: active';
  RSLPDQueueStatus  = 'Queue %s status: %s';
  RSLPDClosingConnection = 'closing connection';
  RSLPDUnknownQueue = 'Unknown queue %s';
  RSLPDConnectTo = 'connected with %s';
  RSLPDAbortJob = 'abort job';
  RSLPDReceiveControlFile = 'Receive control file';
  RSLPDReceiveDataFile = 'Receive data file';

  { LPD Exception Messages }
  RSLPDNoQueuesDefined = 'Error: no queues defined';

  { Trivial FTP Exception Messages }
  RSTimeOut = 'Timeout';
  RSTFTPUnexpectedOp = 'Unexpected operation from %s:%d';
  RSTFTPUnsupportedTrxMode = 'Unsupported transfer mode: "%s"';
  RSTFTPDiskFull = 'Unable to complete write request, progress halted at %d bytes';
  RSTFTPFileNotFound = 'Unable to open %s';
  RSTFTPAccessDenied = 'Access to %s denied';

  { MESSAGE Exception messages }
  RSTIdTextInvalidCount = 'Invalid Text count. TIdText must be greater than 1';
  RSTIdMessagePartCreate = 'TIdMessagePart can not be created.  Use descendant classes. ';
  RSTIdMessageErrorSavingAttachment = 'Error saving attachment.';

  { POP Exception Messages }
  RSPOP3FieldNotSpecified = ' not specified';
  RSPOP3UnrecognizedPOP3ResponseHeader = 'Unrecognized POP3 Response Header:'#10'"%s"'; //APR: user will see Server response    {Do not Localize}
  RSPOP3ServerDoNotSupportAPOP = 'Server do not support APOP (no timestamp)';//APR    {Do not Localize}

  { IdIMAP4 Exception Messages }
  RSIMAP4ConnectionStateError = 'Unable to execute command, wrong connection state;' +
                                 'Current connection state: %s.';
  RSUnrecognizedIMAP4ResponseHeader = 'Unrecognized IMAP4 Response Header.';

  { IdIMAP4 Connection State strings }
  RSIMAP4ConnectionStateAny = 'Any';
  RSIMAP4ConnectionStateNonAuthenticated = 'Non Authenticated';
  RSIMAP4ConnectionStateAuthenticated = 'Authenticated';
  RSIMAP4ConnectionStateSelected = 'Selected';

  { Telnet Server }
  RSTELNETSRVUsernamePrompt = 'Username: ';
  RSTELNETSRVPasswordPrompt = 'Password: ';
  RSTELNETSRVInvalidLogin = 'Invalid Login.';
  RSTELNETSRVMaxloginAttempt = 'Allowed login attempts exceeded, good bye.';
  RSTELNETSRVNoAuthHandler = 'No authentication handler has been specified.';
  RSTELNETSRVWelcomeString = 'Indy Telnet Server';
  RSTELNETSRVOnDataAvailableIsNil = 'OnDataAvailable event is nil.';

  { Telnet Client }
  RSTELNETCLIConnectError = 'server not responding';
  RSTELNETCLIReadError = 'Server did not respond.';

  { Network Calculator }
  RSNETCALInvalidIPString     = 'The string %s does not translate into a valid IP.';
  RSNETCALCInvalidNetworkMask = 'Invalid network mask.';
  RSNETCALCInvalidValueLength = 'Invalid value length: Should be 32.';
  RSNETCALConfirmLongIPList = 'There is too many IP addresses in the specified range (%d) to be displayed at design time.';
  { IdentClient}
  RSIdentReplyTimeout = 'Reply Timed Out:  The server did not return a response and the query has been abandoned';
  RSIdentInvalidPort = 'Invalid Port:  The foreign or local port is not specified correctly or invalid';
  RSIdentNoUser = 'No User:  Port pair is not used or not used by an identifiable user';
  RSIdentHiddenUser = 'Hidden User:  Information was not returned at a user''s request';
  RSIdentUnknownError = 'Unknown or other error: Can not determine owner, other error, or the error can not be revealed.';
  { About Box stuff }
  RSAAboutFormCaption = 'About';
  RSAAboutBoxCompName = 'Internet Direct (Indy)';
  RSAAboutMenuItemName = 'About Internet &Direct (Indy) %s...';
  RSAAboutBoxVersion = 'Version %s';
  RSAAboutBoxCopyright = 'Copyright (c) 1993 - 2003'#13#10
   + 'Kudzu (Chad Z. Hower)'#13#10
   + 'and the'#13#10
   + 'Indy Pit Crew';
  RSAAboutBoxPleaseVisit = 'For the latest updates and information please visit:';
  RSAAboutBoxIndyWebsite = 'http://www.nevrona.com/indy/';    {Do not Localize}
  RSAAboutCreditsCoordinator = 'Project Coordinator';
  RSAAboutCreditsCoCordinator = 'Project Co-Coordinator';
  RSAAboutCreditsDocumentation = 'Documentation Coordinator';
  RSAAboutCreditsDemos = 'Demos Coordinator';
  RSAAboutCreditsDistribution = 'Distribution Coordinator';
  RSAAboutCreditsRetiredPast = 'Retired/Past Contributors';
  RSAAboutOk = 'OK';
  {Binding Editor stuff}
  {
  Note to translators - Please Read!!!

  For all the constants except RSBindingFormCaption, there may be an
  & symbol before a letter or number.  This is rendered as that chractor being
  underlined.  In addition, the charactor after the & symbol along with the ALT
  key enables a user to move to that control.  Since these are on one form, be
  careful to ensure that the same letter or number does not have a & before it
  in more than one string, otherwise an ALT key sequence will be broken.

  }
  RSBindingFormCaption = 'Binding Editor';
  RSBindingAddCaption = '&Add';
  RSBindingRemoveCaption = '&Remove';
  RSBindingLabelBindings = '&Bindings';
  RSBindingHostnameLabel = '&IP Address';
  RSBindingPortLabel = '&Port';
  RSBindingOkButton = 'OK';
  RSBindingCancel   = 'Cancel';
  {}
  RSBindingAll = 'All'; //all IP addresses
  RSBindingAny = 'Any'; //any port
  { Tunnel messages }
  RSTunnelGetByteRange = 'Call to %s.GetByte [property Bytes] with index <> [0..%d]';
  RSTunnelTransformErrorBS = 'Error in transformation before send';
  RSTunnelTransformError = 'Transform failed';
  RSTunnelCRCFailed = 'CRC Failed';
  RSTunnelConnectMsg = 'Connecting';
  RSTunnelDisconnectMsg = 'Disconnect';
  RSTunnelConnectToMasterFailed = 'Cannt connect to the Master server';
  RSTunnelDontAllowConnections = 'Do not allow connctions now';
  RSTunnelMessageTypeError = 'Message type recognition error';
  RSTunnelMessageHandlingError = 'Message handling failed';
  RSTunnelMessageInterpretError = 'Interpretation of message failed';
  RSTunnelMessageCustomInterpretError = 'Custom message interpretation failed';

  { Socks messages }
  RSSocksRequestFailed = 'Request rejected or failed.';
  RSSocksRequestServerFailed = 'Request rejected because SOCKS server cannot connect.';
  RSSocksRequestIdentFailed = 'Request rejected because the client program and identd report different user-ids.';
  RSSocksUnknownError = 'Unknown socks error.';
  RSSocksServerRespondError = 'Socks server did not respond.';
  RSSocksAuthMethodError = 'Invalid socks authentication method.';
  RSSocksAuthError = 'Authentication error to socks server.';
  RSSocksServerGeneralError = 'General SOCKS server failure.';
  RSSocksServerPermissionError = 'Connection not allowed by ruleset.';
  RSSocksServerNetUnreachableError = 'Network unreachable.';
  RSSocksServerHostUnreachableError = 'Host unreachable.';
  RSSocksServerConnectionRefusedError = 'Connection refused.';
  RSSocksServerTTLExpiredError = 'TTL expired.';
  RSSocksServerCommandError = 'Command not supported.';
  RSSocksServerAddressError = 'Address type not supported.';

  { FTP }
  RSDestinationFileAlreadyExists = 'Destination file already exists.';

  { SSL messages }
  RSSSLAcceptError = 'Error accepting connection with SSL.';
  RSSSLConnectError = 'Error connecting with SSL.';
  RSSSLSettingCipherError = 'SetCipher failed.';
  RSSSLCreatingContextError = 'Error creating SSL context.';
  RSSSLLoadingRootCertError = 'Could not load root certificate.';
  RSSSLLoadingCertError = 'Could not load certificate.';
  RSSSLLoadingKeyError = 'Could not load key, check password.';
  RSSSLGetMethodError = 'Error geting SSL method.';
  RSSSLDataBindingError = 'Error binding data to SSL socket.';
  {IdMessage Component Editor}
  RSMsgCmpEdtrNew = '&New Message Part...';
  RSMsgCmpEdtrExtraHead = 'Extra Headers Text Editor';
  RSMsgCmpEdtrBodyText = 'Body Text Editor';
  {IdICMPClient}
  RSICMPNotEnoughtBytes = 'Not enough bytes received';
  RSICMPNonEchoResponse = 'Non-echo type response received';
  RSICMPWrongDestination ='Received someone else''s packet';
  {IdNNTPServer}
  RSNNTPServerNotRecognized = 'Command not recognized';
  RSNNTPServerGoodBye = 'Goodbye';
  {IdGopherServer}
  RSGopherServerNoProgramCode = 'Error: No program code to return request!';

  {IdSyslog}
  RSInvalidSyslogPRI = 'Invalid syslog message: incorrect PRI section';
  RSInvalidSyslogPRINumber = 'Invalid syslog message: incorrect PRI number "%s"';
  RSInvalidSyslogTimeStamp = 'Invalid syslog message: incorrect timestamp "%s"';
  RSInvalidSyslogPacketSize = 'Invalid Syslog message: packet too large (%d bytes)';
  RSInvalidHostName = 'Invalid host name. A SYSLOG host name cannot contain any space ("%s")+';

  {IdOpenSSL}
  RSOSSLModeNotSet = 'Mode has not been set.';
  RSOSSLCouldNotLoadSSLLibrary = 'Could not load SSL library.';
  RSOSSLStatusString = 'SSL status: "%s"';
  RSOSSLConnectionDropped = 'SSL connection has dropped.';
  RSOSSLCertificateLookup = 'SSL certificate request error.';
  RSOSSLInternal = 'SSL library internal error.';

  {IdWinsockStack}
  RSWSockStack = 'Winsock stack';
  {IdSMTPServer}
  RSSMTPSvrCmdNotRecognized = 'Command Not Recognised';
  RSSMTPSvrQuit = 'Signing Off';
  RSSMTPSvrOk   = 'Ok';
  RSSMTPSvrStartData = 'Start mail input; end with <CRLF>.<CRLF>';
  RSSMTPSvrAddressOk = '%s Address Okay';
  RSSMTPSvrAddressError = '%s Address Error';
  RSSMTPSvrAddressWillForward = 'User not local, Will forward';
  RSSMTPSvrWelcome = 'Welcome to the INDY SMTP Server';
  RSSMTPSvrHello = 'Hello %s';
  RSSMTPSvrNoHello = 'Polite people say HELO';
  RSSMTPSvrCmdGeneralError = 'Syntax Error - Command not understood: %s';
  RSSMTPSvrXServer = 'Indy SMTP Server';
  RSSMTPSvrReceivedHeader = 'by DNSName [127.0.0.1] running Indy SMTP';
  RSSMTPSvrAuthFailed = 'Authentication Failed';
  {IdPOP3Server}
  RSPOP3SvrNotHandled = 'Command Not Handled: %s';
  // TIdCoder3to4
  RSUnevenSizeInDecodeStream = 'Uneven size in DecodeToStream.';
  RSUnevenSizeInEncodeStream = 'Uneven size in Encode.';
  // TIdMessageCoder
  RSMessageDecoderNotFound = 'Message decoder not found';
  RSMessageEncoderNotFound = 'Message encoder not found';
  // TIdMessageCoderMIME
  RSMessageCoderMIMEUnrecognizedContentTrasnferEncoding = 'Unrecognized content trasnfer encoding.';
  // TIdMessageCoderUUE
  RSUnrecognizedUUEEncodingScheme = 'Unrecognized UUE encoding scheme.';
  // TIdICMPCast
  RSIPMCastInvalidMulticastAddress = 'The supplied IP address is not a valid multicast address [224.0.0.0 to 239.255.255.255].';
  RSIPMCastNotSupportedOnWin32 = 'This function is not supported on Win32.';
  { IdFTPServer }
  RSFTPDefaultGreeting = 'Indy FTP Server ready.';
  RSFTPOpenDataConn = 'Data connection already open; transfer starting.';
  RSFTPDataConnToOpen = 'File status okay; about to open data connection.';
  RSFTPCmdSuccessful = '%s Command successful.';
  RSFTPServiceOpen = 'Service ready for new user.';
  RSFTPServerClosed = 'Service closing control connection.';
  RSFTPDataConn = 'Data connection open; no transfer in progress.';
  RSFTPDataConnClosed = 'Closing data connection.';
  RSFTPDataConnClosedAbnormally = 'Data connection closed abnormally.';
  RSFTPPassiveMode = 'Entering Passive Mode (%s).';
  RSFTPUserLogged = 'User logged in, proceed.';
  RSFTPAnonymousUserLogged = 'Anonymous user logged in, proceed.';
  RSFTPFileActionCompleted = 'Requested file action okay, completed.';
  RSFTPDirFileCreated = '"%s" created.';
  RSFTPUserOkay = 'User name okay, need password.';
  RSFTPAnonymousUserOkay = 'Anonymous login OK, send e-mail as password.';
  RSFTPNeedLoginWithUser = 'Login with USER first.';
  RSFTPNeedAccountForLogin = 'Need account for login.';
  RSFTPFileActionPending = 'Requested file action pending further information.';
  RSFTPServiceNotAvailable = 'Service not available, closing control connection.';
  RSFTPCantOpenDataConn = 'Can''t open data connection.';
  RSFTPFileActionNotTaken = 'Requested file action not taken.';
  RSFTPFileActionAborted = 'Requested action aborted: local error in processing.';
  RSFTPRequestedActionNotTaken = 'Requested action not taken.';
  RSFTPCmdSyntaxError = 'Syntax error, command unrecognized.';
  RSFTPCmdNotImplemented = '"%s" Command not implemented.';
  RSFTPUserNotLoggedIn = 'Not logged in.';
  RSFTPNeedAccForFiles = 'Need account for storing files.';
  RSFTPActionNotTaken = 'Requested action not taken.';
  RSFTPActionAborted = 'Requested action aborted: page type unknown.';
  RSFTPRequestedFileActionAborted = 'Requested file action aborted.';
  RSFTPRequestedFileActionNotTaken = 'Requested action not taken.';
  RSFTPMaxConnections = 'Maximum connections limit exceeded. Try again later.';
  //Note to translators, it may be best to leave the stuff in quotes as the very first
  //part of any phrase otherwise, a FTP client might get confused.
  RSFTPCurrentDirectoryIs = '"%s" is working directory.';
  RSFTPTYPEChanged = 'Type set to %s.';
  RSFTPMODEChanged = 'Mode set to %s.';
  RSFTPSTRUChanged = 'Structure set to %s.';
  RSFTPSITECmdsSupported = 'The following SITE commands are supported:' +
                            #13 + ' HELP  DIRSTYLE';
  RSFTPDirectorySTRU = '%s directory structure.';
  RSFTPCmdEndOfStat = 'End of Status';
  RSFTPCmdExtsSupported = 'Extensions supported:'#13#10'SIZE'#13#10'PASV'#13#10'REST'#13#10'End of extentions.';

  RSFTPNoOnDirEvent = 'No OnListDirectory event found!';

  {SYSLog Message}
  // facility
  STR_SYSLOG_FACILITY_KERNEL     = 'kernel messages';
  STR_SYSLOG_FACILITY_USER       = 'user-level messages';
  STR_SYSLOG_FACILITY_MAIL       = 'mail system';
  STR_SYSLOG_FACILITY_SYS_DAEMON = 'system daemons';
  STR_SYSLOG_FACILITY_SECURITY1  = 'security/authorization messages (1)';
  STR_SYSLOG_FACILITY_INTERNAL   = 'messages generated internally by syslogd';
  STR_SYSLOG_FACILITY_LPR        = 'line printer subsystem';
  STR_SYSLOG_FACILITY_NNTP       = 'network news subsystem';
  STR_SYSLOG_FACILITY_UUCP       = 'UUCP subsystem';
  STR_SYSLOG_FACILITY_CLOCK1     = 'clock daemon (1)';
  STR_SYSLOG_FACILITY_SECURITY2  = 'security/authorization messages (2)';
  STR_SYSLOG_FACILITY_FTP        = 'FTP daemon';
  STR_SYSLOG_FACILITY_NTP        = 'NTP subsystem';
  STR_SYSLOG_FACILITY_AUDIT      = 'log audit';
  STR_SYSLOG_FACILITY_ALERT      = 'log alert';
  STR_SYSLOG_FACILITY_CLOCK2     = 'clock daemon (2)';
  STR_SYSLOG_FACILITY_LOCAL0     = 'local use 0  (local0)';
  STR_SYSLOG_FACILITY_LOCAL1     = 'local use 1  (local1)';
  STR_SYSLOG_FACILITY_LOCAL2     = 'local use 2  (local2)';
  STR_SYSLOG_FACILITY_LOCAL3     = 'local use 3  (local3)';
  STR_SYSLOG_FACILITY_LOCAL4     = 'local use 4  (local4)';
  STR_SYSLOG_FACILITY_LOCAL5     = 'local use 5  (local5)';
  STR_SYSLOG_FACILITY_LOCAL6     = 'local use 6  (local6)';
  STR_SYSLOG_FACILITY_LOCAL7     = 'local use 7  (local7)';
  STR_SYSLOG_FACILITY_UNKNOWN    = 'Unknown or illegale facility code';

  // Severity
  STR_SYSLOG_SEVERITY_EMERGENCY     = 'Emergency: system is unusable';
  STR_SYSLOG_SEVERITY_ALERT         = 'Alert: action must be taken immediately';
  STR_SYSLOG_SEVERITY_CRITICAL      = 'Critical: critical conditions';
  STR_SYSLOG_SEVERITY_ERROR         = 'Error: error conditions';
  STR_SYSLOG_SEVERITY_WARNING       = 'Warning: warning conditions';
  STR_SYSLOG_SEVERITY_NOTICE        = 'Notice: normal but significant condition';
  STR_SYSLOG_SEVERITY_INFORMATIONAL = 'Informational: informational messages';
  STR_SYSLOG_SEVERITY_DEBUG         = 'Debug: debug-level messages';
  STR_SYSLOG_SEVERITY_UNKNOWN       = 'Unknown or illegale security code';

  {LPR Messages}
  RSLPRError = 'Reply %s on Job ID %s';
  RSLPRUnknown = 'Unknown';

  {IRC Messages}
  RSIRCCanNotConnect = 'IRC Connect Failed';
  RSIRCNotConnected = 'Not connected to server.';
  RSIRCClientVersion =  'TIdIRC 1.061 by Steve Williams';
  RSIRCClientInfo = '%s Non-visual component for 32-bit Delphi.';
  RSIRCNick = 'Nick';
  RSIRCAltNick = 'OtherNick';
  RSIRCUserName = 'username';
  RSIRCRealName = 'Real name';
  RSIRCTimeIsNow = 'Local time is %s';

  {HL7 Lower Layer Protocol Messages}
  RSHL7StatusStopped           = 'Stopped';
  RSHL7StatusNotConnected      = 'Not Connected';
  RSHL7StatusFailedToStart     = 'Failed to Start: %s';
  RSHL7StatusFailedToStop      = 'Failed to Stop: %s';
  RSHL7StatusConnected         = 'Connected';
  RSHL7StatusConnecting        = 'Connecting';
  RSHL7StatusReConnect         = 'Reconnect at %s: %s';
  RSHL7NotWhileWorking         = 'You cannot set %s while the HL7 Component is working';
  RSHL7NotWorking              = 'Attempt to %s while the HL7 Component is not working';
  RSHL7NotFailedToStop         = 'Interface is unusable due to failure to stop';
  RSHL7AlreadyStarted          = 'Interface was already started';
  RSHL7AlreadyStopped          = 'Interface was already stopped';
  RSHL7ModeNotSet              = 'Mode is not initialised';
  RSHL7NoAsynEvent             = 'Component is in Asynchronous mode but OnMessageArrive has not been hooked';
  RSHL7NoSynEvent              = 'Component is in Synchronous mode but  OnMessageReceive has not been hooked';
  RSHL7InvalidPort             = 'Assigned Port value %d is invalid';
  RSHL7ImpossibleMessage       = 'A message has been received but the commication mode is unknown';
  RSHL7UnexpectedMessage       = 'Unexpected message arrived to an interface that is not listening';
  RSHL7UnknownMode             = 'Unknown mode';
  RSHL7ClientThreadNotStopped  = 'Unable to stop client thread';
  RSHL7SendMessage             = 'Send a message';
  RSHL7NoConnectionFound       = 'Server Connection not locatable when sending message';
  RSHL7WaitForAnswer           = 'You cannot send a message while you are still waiting for an answer';

  { MultipartFormData }
  RSMFDIvalidObjectType        = 'Unsupported object type. You can assign only one of the following types or thir descendants: TStrings, TStream.';
  { TIdURI exceptions }
  RSURINoProto                 = 'Protocol field is empty';
  RSURINoHost                  = 'Host field is empty';
  { TIdIOHandlerThrottle}
  RSIHTChainedNotAssigned      = 'You must chain this component to another I/O Handler before using it';
  { TIdSNPP}
  RSSNPPNoMultiLine            = 'TIdSNPP Mess command only supports single line Messages.';
  {TIdThread}
  RSThreadTerminateAndWaitFor  = 'Cannot call TerminateAndWaitFor on FreeAndTerminate threads';
implementation

end.
