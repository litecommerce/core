{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10061: IdAbout.pas 
{
{   Rev 1.4    6/5/2003 06:45:36 AM  JPMugaas
{ Bas's name was omitted.
}
{
{   Rev 1.3    6/5/2003 06:26:46 AM  JPMugaas
{ Noted other personell changes.
}
{
{   Rev 1.2    6/5/2003 06:18:02 AM  JPMugaas
{ Noted J. Berg resignation.
}
{
{   Rev 1.1    6/5/2003 06:10:08 AM  JPMugaas
{ Rune resigned.
}
{
{   Rev 1.0    2002.11.12 10:29:44 PM  czhower
}
unit IdAbout;

interface

uses
  {$IFDEF LINUX}
  QStdCtrls, QForms, QExtCtrls, QControls, QComCtrls, QGraphics, Types, Qt,
  {$ELSE}
  Windows, Messages, StdCtrls, Buttons, ExtCtrls, Graphics, Controls, ComCtrls, Forms,
  {$ENDIF}
  Classes, SysUtils;

type
  TformAbout = class(TForm)

  private
    { Private declarations }
    imgLogo: TImage;
    {$IFDEF LINUX}
    CreditList:TTextViewer;
    TextStrm : TStream;
    {$ELSE}
    CreditList: TRichEdit;
    {$ENDIF}
    Panel1: TPanel;
    Panel2: TPanel;
    btnOk: TButton;

    lblCopyright: TLabel;
    lblVersion: TLabel;
    lblName: TLabel;
    lblPleaseVisitUs : TLabel;
    lblURL : TLabel;
    procedure BeginUpdate;
    procedure AddHeader(const AHeader : String);
    procedure AddEntry(const AName : String; const ACompany: String = '');
    procedure EndUpdate;
  public
    { Public declarations }
    constructor Create(AOwner : TComponent); override;
    {$IFDEF LINUX}
    //workaround for problem - form position does not work like in VCL
    procedure CenterForm;
    {$ELSE}
    procedure lblURLClick(Sender: TObject);
    {$ENDIF}

  end;

var
  formAbout: TformAbout;

Procedure ShowAboutBox(const AProductName, AProductVersion : String);

Procedure ShowDlg;

implementation
uses
  {$IFNDEF Linux}ShellApi,{$ENDIF}
  IdGlobal, IdResourceStrings;

{$R IdCreditsBitmap.res}

Procedure ShowDlg;
begin
  ShowAboutBox(RSAAboutBoxCompName, gsIdVersion);
end;

{$IFDEF LINUX}
procedure StrToStream(const AStr : String; AStream : TStream);
begin
  if AStr <> '' then
  begin
    AStream.Write(AStr[1],Length(AStr));
  end;
end;
{$ENDIF}

Procedure ShowAboutBox(const AProductName, AProductVersion : String);
begin
  with TformAbout.Create(Application) do
  try
    lblName.Caption := AProductName;
    lblVersion.Caption := Format ( RSAAboutBoxVersion, [ AProductVersion ] );
    ShowModal;
  finally
    Free;
  end;
end;

{ TformAbout }

procedure TformAbout.AddEntry(const AName : String; const ACompany: String = '');
begin
  {$IFDEF LINUX}
  StrToStream(Format('<P>%s',[AName]),TextStrm);
  if ACompany = '' then
  begin
    StrToStream('</P>',TextStrm);
  end
  else
  begin
    StrToStream(Format('<BR>%s</P>',[ACompany]),TextStrm);
  end;
  {$ELSE}
  CreditList.Lines.Add(AName);
  if ACompany <> '' then
  begin
    CreditList.Lines.Add(ACompany);
  end;
  CreditList.Lines.Add('');
  {$ENDIF}
end;

procedure TformAbout.AddHeader(const AHeader: String);
begin
  {$IFDEF LINUX}
  StrToStream(Format('<H1>%s</H1>',[AHeader]),TextStrm);
  {$ELSE}
  CreditList.SelAttributes.Size := 14;
  CreditList.SelAttributes.Style := [fsBold];
  CreditList.Lines.Add(AHeader);
  {$ENDIF}
end;

procedure TformAbout.BeginUpdate;
begin
  {$IFDEF LINUX}
    CreditList.TextColor := clBlack;
  CreditList.Paper.Color := clWhite;
  TextStrm := TMemoryStream.Create;
  StrToStream('<HTML><BODY><CENTER>',TextStrm);
  {$ELSE}

  CreditList.Color := clWHite;
  CreditList.Clear;
  CreditList.Paragraph.Alignment := taCenter;
  CreditList.DefAttributes.Name := 'Arial';
  CreditList.DefAttributes.Color := clBlack;
  CreditList.DefAttributes.Size := 10;
  CreditList.DefAttributes.Style := [];
  {$ENDIF}
end;

{$IFDEF LINUX}
procedure TformAbout.CenterForm;
//workaround for problem - form position does not work like in VCL
begin
 Left := (Screen.Width - Width) div 2;
 Top  := (Screen.Height - Height) div 2;
end;
{$ENDIF}

constructor TformAbout.Create(AOwner: TComponent);
begin
  inherited CreateNew(AOwner);
  BorderIcons := [biSystemMenu];
  ClientHeight := 384;
  ClientWidth := 435;
  Position := poScreenCenter;
  Color := clGray;
  Font.Color := clBlack;
  Font.Height := -11;
  {$IFNDEF LINUX}
  Font.Charset := DEFAULT_CHARSET;
  Font.Name := 'MS Sans Serif';    {Do not Localize}
  BorderStyle := bsDialog;
  {$ELSE}
  Font.Name := 'helvetica';    {Do not Localize}
  BorderStyle := fbsDialog;
  CenterForm;
  {$ENDIF}

  Constraints.MaxHeight := Height;
  Constraints.MaxWidth := Width;
  Constraints.MinHeight := Height;
  Constraints.MinWidth := Width;

  Caption := RSAAboutFormCaption;

  ClientWidth := 435;
  PixelsPerInch := 96;
  Font.Style := [];

  imgLogo := TImage.Create(Self);
  imgLogo.Parent := Self;
  imgLogo.Picture.Bitmap.LoadFromResourceName(HInstance, 'TIDABOUTPICTURE');    {Do not Localize}
  imgLogo.AutoSize := True;
  imgLogo.Top := 8;
  imgLogo.Height := 8;
  imgLogo.Left := 8;
  
  Panel1 := TPanel.Create(Self);
  Panel1.Parent := Self;
  Panel1.BevelOuter := bvNone;
  Panel1.BevelInner := bvNone;
  Panel1.ParentColor := True;
  Panel1.ParentFont := True;
  Panel1.Left := 224;
  Panel1.Top := 8;
  Panel1.Width := 203;
  Panel1.Height := 137;
  Panel1.Anchors := [akLeft,akTop,akRight];

  lblCopyright:= TLabel.Create(Self);
  lblCopyright.Parent := Panel1;
  lblCopyright.Left := 6;
  lblCopyright.Top := 80;
  lblCopyright.Width := 193;
  lblCopyright.Height := 75;
  lblCopyright.Alignment := taCenter;
  lblCopyright.Anchors := [akLeft, akTop, akRight];
  lblCopyright.AutoSize := False;
  lblCopyright.Caption := RSAAboutBoxCopyright;
  {$IFNDEF LINUX}
  lblCopyright.Font.Charset := DEFAULT_CHARSET;
  {$ENDIF}
  lblCopyright.Font.Height := -11;
  lblCopyright.Font.Style := [];
  lblCopyright.ParentFont := False;
  lblCopyright.Transparent := True;
  lblCopyright.WordWrap := True;
  lblVersion := TLabel.Create(Self);
  lblVersion.Parent := Panel1;
  lblVersion.Left := 6;
  lblVersion.Top := 56;
  lblVersion.Width := 193;
  lblVersion.Height := 26;
  lblVersion.Alignment := taCenter;
  lblVersion.Anchors := [akLeft, akTop, akRight];
  lblVersion.AutoSize := False;
  {$IFNDEF LINUX}
  lblVersion.Font.Charset := DEFAULT_CHARSET;
  {$ENDIF}
  lblVersion.Font.Height := -13;
  lblVersion.Font.Name := 'Times New Roman';    {Do not Localize}
  lblVersion.Font.Style := [fsBold];
  lblVersion.ParentFont := False;
  lblVersion.Transparent := True;
  lblName:= TLabel.Create(Self);
  lblName.Parent := Panel1;
  lblName.Left := 6;
  lblName.Top := 16;
  lblName.Width := 193;
  lblName.Height := 49;
  lblName.Alignment := taCenter;
  lblName.Anchors := [akLeft, akTop, akRight];
  lblName.AutoSize := False;
  {$IFNDEF LINUX}
  lblName.Font.Charset := DEFAULT_CHARSET;
  {$ENDIF}
  lblName.Font.Height := -19;
  lblName.Font.Name := 'Times New Roman';    {Do not Localize}
  lblName.Font.Style := [fsBold];
  lblName.ParentFont := False;
  lblName.Transparent := True;
  //bottum panel owned controls
  Panel2 := TPanel.Create(Self);
  Panel2.Parent := Self;
  Panel2.Height := 40;
  Panel2.BevelInner := bvNone;
  Panel2.Align := alBottom;
  Panel2.ParentColor := True;
  Panel2.ParentFont := True;
  Panel2.BevelOuter := bvNone;
  Panel2.BevelInner := bvNone;
    {Panel1 owned-controls}
  lblPleaseVisitUs:= TLabel.Create(Self);
  lblPleaseVisitUs.Parent := Panel2;
  lblPleaseVisitUs.Left := 8;
  lblPleaseVisitUs.Top := 4;
  lblPleaseVisitUs.Width := 337; //304;
  lblPleaseVisitUs.Height := 17;
  lblPleaseVisitUs.Alignment := taCenter;
  lblPleaseVisitUs.Anchors := [akLeft, akTop, akRight];
  lblPleaseVisitUs.AutoSize := False;
  {$IFNDEF LINUX}
  lblPleaseVisitUs.Font.Charset := DEFAULT_CHARSET;
  {$ENDIF}

  lblPleaseVisitUs.Font.Height := -11;
  lblPleaseVisitUs.Font.Style := [];
  lblPleaseVisitUs.ParentFont := False;
  lblPleaseVisitUs.Transparent := True;
  lblPleaseVisitUs.Caption := RSAAboutBoxPleaseVisit;
  lblURL:= TLabel.Create(Self);
  lblURL.Parent := Panel2;
  lblURL.AutoSize := False;
  lblURL.Left := 8;
  lblURL.Top := 20;
  lblURL.Width :=  304;
  lblURL.Height := 13;
  lblURL.Anchors := [akLeft, akTop, akRight];
  {$IFNDEF LINUX}
  lblURL.Cursor := crHandPoint;
  lblURL.Font.Color := clRed; //clBtnHighlight;
  lblURL.OnClick := lblURLClick;
  lblURL.Font.Style := [fsUnderline];
  lblURL.Font.Charset := DEFAULT_CHARSET;
  {$ENDIF}
  lblURL.Alignment := taCenter;
  lblURL.Anchors := [akLeft, akTop, akRight];
  lblURL.AutoSize := False;
  lblURL.Font.Height := -11;
  lblURL.ParentFont := False;
  lblURL.Transparent := True;
  lblURL.Caption := RSAAboutBoxIndyWebsite;
  btnOk := TButton.Create(Panel2);
  btnOk.Parent := Panel2;

  btnOk.Left := 352;
  btnOk.Top := 8;
  btnOk.Width := 75;
  btnOk.Height := 25;
  btnOk.Anchors := [akTop, akRight];
  btnOk.Cancel := True;
  btnOk.Caption := RSAAboutOk;
  btnOk.Default := True;
  btnOk.ModalResult := 1;
  btnOk.TabOrder := 0;
  {$IFDEF LINUX}
  CreditList := TTextViewer.Create(Self);
  {$ELSE}
  CreditList := TRichEdit.Create(Self);

  CreditList.ReadOnly := True;
  CreditList.ScrollBars := ssVertical;
  {$ENDIF}
  CreditList.Parent := Self;
  CreditList.Left := 8;
  CreditList.Top := 152;
  CreditList.Width := 419;
  CreditList.Height := 192;
  CreditList.Anchors := [akLeft,akTop,akRight,akBottom];
  BeginUpdate;
  AddHeader(RSAAboutCreditsCoordinator);

  AddEntry('Kudzu (Chad Z. Hower)','Atozed Software');

  AddHeader(RSAAboutCreditsCoCordinator);
  AddEntry('Hadi Hariri','Atozed Software');

  AddHeader('The Indy Pit Crew');
  AddEntry('Allen Bauer','Borland Software Corporation');
  AddEntry('Allen O''Neill','Springboard Technologies Ltd');
  AddEntry('Andrew Cumming');
  AddEntry('Andrew Neillans','ABCC Computers');
  AddEntry('Andrew Peter Mee');
  AddEntry('Andrew P.Rybin');
  AddEntry('Bas Gooijen');
  AddEntry('Ciaran Costelloe');
  AddEntry('Colin Wilson');
  AddEntry('Darren Kosinski','Borland Software Corporation');
  AddEntry('Dave Nottage');
  AddEntry('Dennies Chang');
  AddEntry('Don Siders');
  AddEntry('Doychin Bondzhev','Atozed Software');
  AddEntry('Grahame Grieve','Kestral Computing');
  AddEntry('Gregor Ibic','Intelicom d.o.o.');
  AddEntry('Idan Cohen');
  AddEntry('J. Peter Mugaas');
  AddEntry('Jan Pedersen','JPSoft DK');
  AddEntry('Jim Gunkel','Nevrona Designs');
  AddEntry('Mark Holmes');
  AddEntry('Remy Lebeau');

  AddEntry('Slaven Radic','Poco Systems');
  AddEntry('Stephane Grobety');
  AddEntry('Sergio Perry');
  AddEntry('Tommi Prami');
  AddEntry('Vladimir Vassiliev');
  AddHeader(RSAAboutCreditsDocumentation);
  AddEntry('Don Siders');
  AddHeader(RSAAboutCreditsDemos);
  AddEntry('Allen O''Neill','Springboard Technologies Ltd');
  AddHeader(RSAAboutCreditsDistribution);
  AddEntry('J. Peter Mugaas');
  AddHeader(RSAAboutCreditsRetiredPast);
  AddEntry('Charles Stack');
  AddEntry('Chuck Smith');
  AddEntry('Johannes Berg');
  AddEntry('Rune Moberg');
  EndUpdate;
end;

procedure TformAbout.EndUpdate;
begin
  {$IFDEF LINUX}
  StrToStream('</CENTER></BODY></HTML>',TextStrm);
  CreditList.LoadFromStream(TextStrm);
  FreeAndNil(TextStrm);
  {$ELSE}
  CreditList.SelStart := 0;
  {$ENDIF}
end;
{$IFNDEF LINUX}
procedure TformAbout.lblURLClick(Sender: TObject);
begin
  ShellAPI.shellExecute((Self as TControl).Handle,PChar('open'),PChar(lblURL.Caption),nil,nil, 0);    {Do not Localize}
end;
{$ENDIF}

end.
