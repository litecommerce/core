{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10371: IdTelnet.pas 
{
{   Rev 1.0    2002.11.12 10:55:40 PM  czhower
}
unit IdTelnet;

// CHANGES
//
// 26-05-2000 SG: Converted to Indy, no other change
// 13-JAN-2000 MTL: Moved to new Palette Scheme (Winshoes Servers)
//
///////////////////////////////////////////////////////////////////////////////
// Author: Mark Holmes
// ..
// This is the telnet client component. I'm still testing    {Do not Localize}
// There is no real terminal emulation other than dumb terminal
//
////////////////////////////////////////////////////////////////////////////////
{
******************************************************************************
Date        Author Change.
--------------------------------------------------------------------------------
05-Mar-2000 Mark   Added constants for telnet implememtation.
07-Mar-2000 Mark   Added a bunch of stuff... it's very much a work in progress   
03-01-2002 Andrew P.Rybin  Renamings and standardization
}

interface

uses
  Classes,
  IdException,
  IdGlobal,
  IdStack,
  IdTCPClient, IdThread,
  SysUtils;

const
  { These are the telnet command constansts from RFC 854 }
  TNC_EOR                = #239;    // End of Record RFC 885
  TNC_SE                 = #240;    // End of subnegotiation parameters.
  TNC_NOP                = #241;    // No operation.
  TNC_DATA_MARK          = #242;    // The data stream portion of a Synch.
                                    // This should always be accompanied
                                    // by a TCP Urgent notification.
  TNC_BREAK              = #243;    // NVT character BRK.
  TNC_IP                 = #244;    // The function IP.
  TNC_AO                 = #245;    // The function ABORT OUTPUT.
  TNC_AYT                = #246;    // The function ARE YOU THERE.
  TNC_EC                 = #247;    // The function ERASE CHARACTER.
  TNC_EL                 = #248;    // The function ERASE LINE.
  TNC_GA                 = #249;    // The GO AHEAD signal.
  TNC_SB                 = #250;    // Indicates that what follows is
                                    // subnegotiation of the indicated
                                    // option.
  TNC_WILL               = #251;    // Indicates the desire to begin
                                    // performing, or confirmation that
                                    // you are now performing, the
                                    // indicated option.
  TNC_WONT               = #252;    // Indicates the refusal to perform,
                                    // or continue performing, the
                                    // indicated option.
  TNC_DO                 = #253;    // Indicates the request that the
                                    // other party perform, or
                                    // confirmation that you are expecting
                                    // the other party to perform, the
                                    // indicated option.
  TNC_DONT               = #254;    // Indicates the demand that the
                                    // other party stop performing,
                                    // or confirmation that you are no
                                    // longer expecting the other party
                                    // to perform, the indicated option.
  TNC_IAC                = #255;    // Data Byte 255.

  { Telnet options from RFC 1010 }
  TNO_BINARY             = #0;      // Binary Transmission
  TNO_ECHO               = #1;      // Echo
  TNO_RECONNECT          = #2;      // Reconnection
  TNO_SGA                = #3;      // Suppress Go Ahead
  TNO_AMSN               = #4;      // Approx Message Size Negotiation
  TNO_STATUS             = #5;      // Status
  TNO_TIMING_MARK        = #6;      // Timing Mark
  TNO_RCTE               = #7;      // Remote Controlled Trans and Echo -BELL
  TNO_OLW                = #8;      // Output Line Width
  TNO_OPS                = #9;      // Output Page Size
  TNO_OCRD               = #10;     // Output Carriage-Return Disposition
  TNO_OHTS               = #11;     // Output Horizontal Tab Stops
  TNO_OHTD               = #12;     // Output Horizontal Tab Disposition
  TNO_OFD                = #13;     // Output Formfeed Disposition
  TNO_OVT                = #14;     // Output Vertical Tabstops
  TNO_OVTD               = #15;     // Output Vertical Tab Disposition
  TNO_OLD                = #16;     // Output Linefeed Disposition
  TNO_EA                 = #17;     // Extended ASCII
  TNO_LOGOUT             = #18;     // Logout
  TNO_BYTE_MACRO         = #19;     // Byte Macro
  TNO_DET                = #20;     // Data Entry Terminal
  TNO_SUPDUP             = #21;     // SUPDUP
  TNO_SUPDUP_OUTPUT      = #22;     // SUPDUP Output
  TNO_SL                 = #23;     // Send Location
  TNO_TERMTYPE           = #24;     // Terminal Type
  TNO_EOR                = #25;     // End of Record
  TNO_TACACS_ID          = #26;     // TACACS User Identification
  TNO_OM                 = #27;     // Output Marking
  TNO_TLN                = #28;     // Terminal Location Number
  TNO_3270REGIME         = #29;     // 3270 regime
  TNO_X3PAD	         = #30;     // X.3 PAD
  TNO_NAWS      	 = #31;     // Window size
  TNO_TERM_SPEED         = #32;     // Terminal speed
  TNO_RFLOW              = #33;     // Remote flow control
  TNO_LINEMODE           = #34;     // Linemode option
  TNO_XDISPLOC	         = #35;     // X Display Location
  TNO_AUTH               = #37;     // Authenticate
  TNO_ENCRYPT            = #38;     // Encryption option

  TNO_EOL                = #255;    // Extended-Options-List                               [84,JBP]

  // Sub options
  TNOS_TERM_IS           = #0;
  TNOS_TERMTYPE_SEND     = #1;      // Sub option
  TNOS_REPLY             = #2;
  TNOS_NAME              = #3;
type
  TIdTelnet = class;

  {Various states for telnet }
  TIdTelnetState =(tnsDATA, tnsIAC, tnsIAC_SB, tnsIAC_WILL, tnsIAC_DO, tnsIAC_WONT,
     tnsIAC_DONT, tnsIAC_SBIAC, tnsIAC_SBDATA, tnsSBDATA_IAC);

  {Commands to telnet client from server}
  TIdTelnetCommand = (tncNoLocalEcho, tncLocalEcho, tncEcho);

  TIdTelnetDataAvailEvent = procedure (Sender: TIdTelnet; const Buffer: String) of object;
  TIdTelnetCommandEvent = procedure(Sender: TIdTelnet; Status: TIdTelnetCommand) of object;

  {This object is for the thread that listens for the telnet server responses
  to key input and initial protocol negotiations }

  TIdTelnetReadThread = class(TIdThread)
  protected
    FClient: TIdTelnet;
    FRecvData: String;
    //
    procedure Run; override;
  public
    constructor Create(AClient: TIdTelnet); reintroduce;

    property  Client: TIdTelnet read FClient;
    property  RecvData: String read FRecvData write FRecvData;
  End; //TIdTelnetReadThread

  TIdTelnet = class(TIdTCPClient)
  protected
    fState: TIdTelnetState;
    fReply: Char;
    fSentDoDont: String;
    fSentWillWont: String;
    fReceivedDoDont: String;
    fReceivedWillWont: String;
    fTerminal : String;
    FOnDataAvailable: TIdTelnetDataAvailEvent;
    fIamTelnet: Boolean;
    FOnDisconnect: TNotifyEvent;
    FOnConnect: TNotifyEvent;
    FOnTelnetCommand: TIdTelnetCommandEvent;
    FTelnetThread: TIdTelnetReadThread;
    //
    procedure DoOnDataAvailable;
    procedure SetOnTelnetCommand(const Value: TIdTelnetCommandEvent);
    // what is our current state ?
    property State : TIdTelnetState read fState write fState;
    // what we send to the telnet server in response to protocol negotiations
    property Reply : Char read fReply write fReply;
    // did we send a DO DONT command?
    property SentDoDont : String read fSentDoDont write fSentDoDont;
    // did we send a WILL WONT command?
    property SentWillWont: String read fSentWillWont write fSentWillWont;
    // did we receive a DO DONT request from the server?
    property ReceivedDoDont: String read fReceivedDoDont write fReceivedDoDont;
    // did we receive a WILL WONT answer from the server?
    property ReceivedWillWont: String read fReceivedWillWont write fReceivedWillWont;
    // Are we connected to a telnet server or some other server?
    property IamTelnet: Boolean read fIamTelnet write fIamTelnet;
    // Protocol negotiation begins here
    function Negotiate(const Buf: String) : String;
    // Handle the termtype request
    procedure Handle_SB(CurrentSb: Byte; sbData: String; sbCount: Integer);
    // Send the protocol resp to the server based on what's in Reply    {Do not Localize}
    procedure SendNegotiationResp(var Resp: String);
    // Update the telnet status
    procedure DoTelnetCommand(Status: TIdTelnetCommand);
  public
    //
    constructor Create(AOwner: TComponent); override;
    procedure Connect(const ATimeout: Integer = IdTimeoutDefault); override;
    procedure Disconnect; override;
    procedure SendCh(Ch: Char);

    property TelnetThread: TIdTelnetReadThread read FTelnetThread;
  published
    property OnTelnetCommand: TIdTelnetCommandEvent read FOnTelnetCommand write SetOnTelnetCommand;
    property OnDataAvailable: TIdTelnetDataAvailEvent read FOnDataAvailable write FOnDataAvailable;
    property Terminal: string read fTerminal write fTerminal;
    property OnConnect: TNotifyEvent read FOnConnect write FOnConnect;
    property OnDisconnect: TNotifyEvent read FOnDisconnect write FOnDisconnect;
  end;

  EIdTelnetError = class(EIdException);
  EIdTelnetClientConnectError = class(EIdTelnetError);
  EIdTelnetServerOnDataAvailableIsNil = class(EIdTelnetError);

implementation

uses
  IdResourceStrings;

constructor TIdTelnetReadThread.Create(AClient: TIdTelnet);
begin
  inherited Create(False);
  FClient := AClient;
  FreeOnTerminate:= FALSE; //other way TRUE
end;

procedure TIdTelnetReadThread.Run;
begin
  // if we have data run it through the negotiation routine. If we aren't    {Do not Localize}
  // connected to a telnet server then the data just passes through the
  // negotiate routine unchanged.
  FRecvData := FClient.Negotiate(FClient.CurrentReadBuffer);
  // if textual data is returned by the server then send this data to
  // the client app
  Synchronize(FClient.DoOnDataAvailable);
  FClient.CheckForDisconnect;
end;

{ TIdTelnet }

procedure TIdTelnet.SendCh(Ch : Char);
begin
  // this  code is necessary to allow the client to receive data properly
  // from a non-telnet server
  if Ch <> CR then
    Write(Ch)
  else if (Ch = CR) and (IamTelnet = True) then
    Write(Ch)
  else
    Write(EOL);
end;

constructor TIdTelnet.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
  Port := 23;
  State := tnsData;
  SentDoDont := #0;
  SentWillWont := #0;
  ReceivedDoDont := #0;
  ReceivedWillWont := #0;
  Terminal := 'dumb';    {Do not Localize}
  IamTelnet := False;
end;

procedure TIdTelnet.Disconnect;
begin
  if Assigned(FTelnetThread) then begin
    FTelnetThread.Terminate;
  end;
  IAmTelnet := False;
  inherited Disconnect;
  if Assigned(FOnDisconnect) then begin
    OnDisconnect(SELF);
  end;
  if Assigned(FTelnetThread) then begin
    FTelnetThread.WaitFor;
  end;
  FreeAndNIL(FTelnetThread);
End;//Disconnect

procedure TIdTelnet.DoOnDataAvailable;
begin
  if Assigned(FOnDataAvailable) then begin
    OnDataAvailable(SELF,FTelnetThread.FRecvData);
  end else begin
    raise EIdTelnetServerOnDataAvailableIsNil.Create(RSTELNETSRVOnDataAvailableIsNil);
  end;
end;

procedure TIdTelnet.Connect(const ATimeout: Integer = IdTimeoutDefault);
begin
  inherited Connect(ATimeout);
  try
    if Assigned(FOnConnect) then begin
      OnConnect(SELF);
    end;
    // create the reading thread and assign the current Telnet object to it
    FTelnetThread := TIdTelnetReadThread.Create(SELF);
  except on E: EIdSocketError do
    raise EIdTelnetClientConnectError.Create(RSTELNETCLIConnectError);  // translate
  end;
end;

procedure TIdTelnet.SendNegotiationResp(var Resp: String);
begin
  Write(Resp);
  Resp := '';    {Do not Localize}
end;

procedure TIdTelnet.Handle_SB(CurrentSB: Byte; sbData: String; sbCount: Integer);
var
  Resp : String;
begin
  if (sbcount > 0) and (sbdata = TNOS_TERMTYPE_SEND) then
  begin
    // if someone inadvertantly sets Termnal to null
    // You can set termial to anything you want i supose but be prepared to handle
    // the data emulation yourself
    if Terminal = '' then    {Do not Localize}
      Terminal := 'dumb';    {Do not Localize}
    Resp := TNC_IAC + TNC_SB + TNO_TERMTYPE + TNOS_TERM_IS + Terminal + TNC_IAC + TNC_SE;
    SendNegotiationResp(Resp);
  end;
  // add authentication code here
end;

function TIdTelnet.Negotiate(const Buf: String) : String;
var
  LCount: Integer;
  bOffset   : Integer;
  nOffset   : Integer;
  B         : Char;
  nBuf      : String;
  sbBuf     : String;
  sbCount   : Integer;
  CurrentSb : Integer;
  SendBuf   : String;
begin
  bOffset := 1;
  nOffset := 0;
  sbCount := 1;
  CurrentSB := 1;
  nbuf := '';    {Do not Localize}
  LCount := Length(Buf);
  while bOffset < LCount + 1 do
  begin
    b := Buf[bOffset];
    case State of
      tnsData: { start of command sequence }
        if b = TNC_IAC then
        begin
          IamTelnet := True;
          State := tnsIAC;
        end
        else
          nbuf := nbuf + b;

      tnsIAC: { a Do request }
              case b of
                TNC_IAC:
                  begin
                    State := tnsData;
                    inc(nOffset);
                    nbuf[nOffset] := TNC_IAC;
                  end;
                TNC_WILL:
                  State := tnsIAC_WILL;
                TNC_WONT:
                  State := tnsIAC_WONT;
                TNC_DONT:
                  State := tnsIAC_DONT;
                TNC_DO:
                  State := tnsIAC_DO;
                TNC_EOR:
                  State := tnsDATA;
                TNC_SB:
                  begin
                    State := tnsIAC_SB;
                    sbCount := 0;
                  end;
              else
                State := tnsData; // the default setting
              end; //end case b
      tnsIAC_WILL:
        begin
          case b of
            TNO_ECHO:
              begin
                Reply := TNC_DO;
                DoTelnetCommand(tncNoLocalEcho);
//                doStatus('NOLOCALECHO');    {Do not Localize}
              end;
            TNO_EOR:
              Reply := TNC_DO;
          else
            Reply := TNC_DONT;
          end; // end case b

        //  if (Reply <> SentDoDont) or (TNC_WILL <> ReceivedWillWont) then
          begin
            SendBuf := TNC_IAC + Reply + b;
            SendNegotiationResp(SendBuf);
            SentDoDont := Reply;
            ReceivedWillWont := TNC_WILL;
          end;
          State := tnsData;
        end; // end of tnsIAC_WILL

      tnsIAC_WONT:
        begin
          case b of
            TNO_ECHO:
              begin
                DoTelnetCommand(tncLocalEcho);
//                Dostatus('LOCALECHO');    {Do not Localize}
                Reply := TNC_DONT;
              end;
            TNO_EOR:
              Reply := TNC_DONT;
          else
            Reply := TNC_DONT;
          end; // end case b

        //  if (Reply <> SentDoDont) or (ReceivedWillWont <> TNC_WONT) then
          begin
            SendBuf := TNC_IAC + Reply + b;
            SendNegotiationResp(SendBuf);
            SentDoDont := Reply;
            ReceivedWillWont := TNC_WILL;
          end;
          State := tnsData;

        end; // end tnsIAC_WONT
      tnsIAC_DO:
      begin
        case b of
          TNO_ECHO:
            begin
              DoTelnetCommand(tncLocalEcho);
              Reply := TNC_WILL;
            end;
          TNO_TERMTYPE:
            Reply := TNC_WILL;
          //TNO_NAWS:
          TNO_AUTH:
          begin
//            if(Authentication) then
//            Reply := TNC_WILL
//            else
            Reply := TNC_WONT;
          end;
        else
          Reply := TNC_WONT;
        end; // end of case b
        //if (Reply <> SentWillWont) or (ReceivedDoDont <> TNC_DO) then
        begin
          SendBuf := TNC_IAC + Reply + b;
          SendNegotiationResp(SendBuf);
          SentWillWont := Reply;
          ReceivedDoDont := TNC_DO;
        end;
        State := tnsData;
      end;
      tnsIAC_DONT:
      begin
        case b of
          TNO_ECHO:
            begin
              DoTelnetCommand(tncEcho);
//              DoStatus('ECHO');    {Do not Localize}
              Reply := TNC_WONT;
            end;
          TNO_NAWS:
            Reply := TNC_WONT;
          TNO_AUTH:
            Reply := TNC_WONT
        else
          Reply := TNC_WONT;
        end; // end case b

      //  if (Reply <> SentWillwont) or (TNC_DONT <> ReceivedDoDont) then
        begin
          SendBuf := TNC_IAC + reply + b;
          SendNegotiationResp(SendBuf);
        end;
        State := tnsData;
      end;

      tnsIAC_SB:
        begin
          if b = TNC_IAC then
            State := tnsIAC_SBIAC
          else begin
            CurrentSb := Ord(b);
            sbCount := 0;
            State := tnsIAC_SBDATA;
          end;
        end;
      tnsIAC_SBDATA:
        begin
          if b = TNC_IAC then
            State := tnsSBDATA_IAC
          else begin
            inc(sbCount);
            sbBuf := b;
          end;
        end;
      tnsSBDATA_IAC:
        case b of
          TNC_IAC:
            begin
              State := tnsIAC_SBDATA;
              inc(sbCount);
              sbBuf[sbCount] := TNC_IAC;
            end;
          TNC_SE:
            begin
              handle_sb(CurrentSB,sbBuf,sbCount);
              CurrentSB	:= 0;
              State := tnsData;
            end;
          TNC_SB:
            begin
              handle_sb(CurrentSB,sbBuf,sbCount);
              CurrentSB	:= 0;
              State := tnsIAC_SB;
            end
         else
           State := tnsDATA;
         end;
      else
        State := tnsData;
    end; // end case State
    inc(boffset);
  end; // end while
  Result := nBuf;
end;

procedure TIdTelnet.SetOnTelnetCommand(const Value: TIdTelnetCommandEvent);
begin
  FOnTelnetCommand := Value;
end;

procedure TIdTelnet.DoTelnetCommand(Status: TIdTelnetCommand);
begin
  if Assigned(FOnTelnetCommand) then
    FOnTelnetCommand(Self, Status);
end;

END.
