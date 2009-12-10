//---------------------------------------------------------------------------


#ifndef settingsH
#define settingsH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tf_settings : public TFrame
{
__published:	// IDE-managed Components
	TPanel *Panel1;
	TButton *_cancel;
	TButton *_continue;
	TGroupBox *__ftp;
	TGroupBox *__shop;
	TGroupBox *__proxy;
	TEdit *ftp_server;
	TEdit *ftp_port;
	TLabel *Label1;
	TLabel *Label2;
	TEdit *ftp_dir;
	TLabel *Label3;
	TCheckBox *passive_mode;
	TEdit *ftp_login;
	TLabel *Label4;
	TLabel *Label5;
	TEdit *ftp_pass;
	TLabel *Label6;
	TEdit *http_address;
	TLabel *Label7;
	TCheckBox *use_proxy;
	TEdit *proxy_server;
	TLabel *Label8;
	TEdit *proxy_port;
	TLabel *Label9;
	TCheckBox *proxy_auth;
	TEdit *proxy_login;
	TEdit *proxy_pass;
	TLabel *Label10;
	TLabel *Label11;
	TButton *_help;
	void __fastcall _cancelClick(TObject *Sender);
	void __fastcall use_proxyClick(TObject *Sender);
	void __fastcall proxy_authClick(TObject *Sender);
	void __fastcall _continueClick(TObject *Sender);
	void __fastcall _helpClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tf_settings(TComponent* Owner);
	AnsiString BoolToString(bool value);
};
//---------------------------------------------------------------------------
extern PACKAGE Tf_settings *f_settings;
//---------------------------------------------------------------------------
#endif
