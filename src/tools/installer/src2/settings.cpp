//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#pragma hdrstop

#include "settings.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tf_settings *f_settings;
//---------------------------------------------------------------------------
__fastcall Tf_settings::Tf_settings(TComponent* Owner)
	: TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tf_settings::_cancelClick(TObject *Sender)
{
	_main->Close();
}
//---------------------------------------------------------------------------
void __fastcall Tf_settings::use_proxyClick(TObject *Sender)
{
	proxy_server->Enabled = use_proxy->Checked;
	proxy_port->Enabled = use_proxy->Checked;
	proxy_auth->Enabled = use_proxy->Checked;
	proxy_login->Enabled = use_proxy->Checked & proxy_auth->Checked;
	proxy_pass->Enabled = use_proxy->Checked & proxy_auth->Checked;
}
//---------------------------------------------------------------------------
void __fastcall Tf_settings::proxy_authClick(TObject *Sender)
{
	proxy_login->Enabled = use_proxy->Checked & proxy_auth->Checked;
	proxy_pass->Enabled = use_proxy->Checked & proxy_auth->Checked;
}
//---------------------------------------------------------------------------
void __fastcall Tf_settings::_continueClick(TObject *Sender)
{
	_main->passive = passive_mode->Checked;
   _main->ftp_host = ftp_server->Text;
   _main->ftp_port = ftp_port->Text;
   _main->ftp_dir = ftp_dir->Text;
   _main->http_address = http_address->Text;
   _main->ftp_login = ftp_login->Text;
   _main->ftp_password = ftp_pass->Text;
   _main->proxy = use_proxy->Checked;
   _main->proxy_server = proxy_server->Text;
   _main->proxy_port = proxy_port->Text;
   _main->proxy_auth = proxy_auth->Checked;
   _main->proxy_login = proxy_login->Text;
   _main->proxy_password = proxy_pass->Text;

   _main->updateSettings();
   _main->setSettings();

// LOG
	_main->WriteLog("Installation settings: \n");
	_main->WriteLog("ftp address           : " + _main->ftp_host + "\n");
	_main->WriteLog("ftp port              : " + _main->ftp_port + "\n");
	_main->WriteLog("ftp directory         : " + _main->ftp_dir + "\n");
	_main->WriteLog("ftp login             : " + _main->ftp_login + "\n");
	_main->WriteLog("passive mode          : " + BoolToString(_main->passive) + "\n");
	_main->WriteLog("shop url              : " + _main->http_address + "\n");
	_main->WriteLog("use proxy             : " + BoolToString(_main->proxy) + "\n");
	_main->WriteLog("proxy server          : " + _main->proxy_server + "\n");
	_main->WriteLog("proxy port            : " + _main->proxy_port + "\n");
	_main->WriteLog("proxy authentication  : " + BoolToString(_main->proxy_auth) + "\n");
	_main->WriteLog("proxy login           : " + _main->proxy_login + "\n");
  	_main->WriteLog("-------------------------------------\n");
// END
   _main->ShowTest();
}
//---------------------------------------------------------------------------

void __fastcall Tf_settings::_helpClick(TObject *Sender)
{
	ShellExecute(NULL, "open", _main->help_file.c_str(), "", "", 1);
}
//---------------------------------------------------------------------------


AnsiString Tf_settings::BoolToString(bool value)
{
	if (value) {
   	return "yes";
   }
   return "no";
}
