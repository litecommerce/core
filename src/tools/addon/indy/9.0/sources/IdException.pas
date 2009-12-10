{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10155: IdException.pas 
{
{   Rev 1.0    2002.11.12 10:37:50 PM  czhower
}
unit IdException;

interface

uses
  SysUtils;

type
  EIdException = class(Exception);
  TClassIdException = class of EIdException;

  // You can add EIdSilentException to the list of ignored exceptions to reduce debugger "trapping"
  // of "normal" exceptions
  EIdSilentException = class(EIdException);
  // EIdConnClosedGracefully is raised when remote side closes connection normally
  EIdConnClosedGracefully = class(EIdSilentException);

  EIdAlreadyConnected = class(EIdException);
  // EIdClosedSocket is raised if .Disconnect has been called and an operation is attempted
  // or Connect has not been called
  EIdClosedSocket = class(EIdException);
  EIdResponseError = class(EIdException);
  EIdReadTimeout = class(EIdException);
  EIdReadLnMaxLineLengthExceeded = class(EIdException);

  EIdInvalidServiceName = class(EIdException);
  // This exception is for protocol errors such as 404 HTTP error and also
  // SendCmd / GetResponse
  EIdProtocolReplyError = class(EIdException)
  protected
    FReplyErrorCode : Integer;
  public
    // Params must be in this order to avoid conflict with CreateHelp
    // constructor in CBuilder
    constructor CreateError(const AErrCode: Integer;
     const AReplyMessage: string); reintroduce; virtual;
    property ReplyErrorCode: Integer read FReplyErrorCode;
  end;

  EInvalidSyslogMessage = class(EIdException);
  EIdSSLProtocolReplyError = class(EIdProtocolReplyError);
  EIdConnectTimeout = class(EIdException);
  EIdConnectException = class(EIdException);
  EIdSocksError = class(EIdException);
  EIdSocksRequestFailed = class(EIdSocksError);
  EIdSocksRequestServerFailed = class(EIdSocksError);
  EIdSocksRequestIdentFailed = class(EIdSocksError);
  EIdSocksUnknownError = class(EIdSocksError);
  EIdSocksServerRespondError = class(EIdSocksError);
  EIdSocksAuthMethodError = class(EIdSocksError);
  EIdSocksAuthError = class(EIdSocksError);
  EIdSocksServerGeneralError = class(EIdSocksError);
  EIdSocksServerPermissionError = class (EIdSocksError);
  EIdSocksServerNetUnreachableError = class (EIdSocksError);
  EIdSocksServerHostUnreachableError = class (EIdSocksError);
  EIdSocksServerConnectionRefusedError = class (EIdSocksError);
  EIdSocksServerTTLExpiredError = class (EIdSocksError);
  EIdSocksServerCommandError = class (EIdSocksError);
  EIdSocksServerAddressError = class (EIdSocksError);

  //IdIMAP4 Exception
  EIdConnectionStateError = class(EIdException);

  // THE EDnsResolverError is used so the resolver can repond to only resolver execeptions.
  EIdDnsResolverError = Class(EIdException);

  {Socket exceptions}
  EIdInvalidSocket = class(EIdException);

  EIdSocketError = class(EIdException)
  private
    FLastError: Integer;
  public
    // Params must be in this order to avoid conflict with CreateHelp
    // constructor in CBuilder
    constructor CreateError(const AErr: Integer; const AMsg: string); virtual;
    //
    property LastError: Integer read FLastError;
  end;

  {TIdTrivial FTP Exception }
  EIdTFTPException               = class(EIdException);
  EIdTFTPFileNotFound            = class(EIdTFTPException);
  EIdTFTPAccessViolation         = class(EIdTFTPException);
  EIdTFTPAllocationExceeded      = class(EIdTFTPException);
  EIdTFTPIllegalOperation        = class(EIdTFTPException);
  EIdTFTPUnknownTransferID       = class(EIdTFTPException);
  EIdTFTPFileAlreadyExists       = class(EIdTFTPException);
  EIdTFTPNoSuchUser              = class(EIdTFTPException);
  EIdTFTPOptionNegotiationFailed = class(EIdTFTPException);  // RFC 1782

  {Icmp exceptions}
  EIdIcmpException = class(EIdException);

  EIdSetSizeExceeded = class(EIdException);

implementation

{ EIdProtocolReplyError }

constructor EIdProtocolReplyError.CreateError(const AErrCode: Integer;
  const AReplyMessage: string);
begin
  inherited Create(AReplyMessage);
  FReplyErrorCode := AErrCode;
end;

constructor EIdSocketError.CreateError(const AErr: Integer; const AMsg: string);
begin
  inherited Create(AMsg);
  FLastError := AErr;
end;

end.
