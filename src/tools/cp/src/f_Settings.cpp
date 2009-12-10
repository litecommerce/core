//---------------------------------------------------------------------------

#include <vcl.h>
#include "constants.h";
#pragma hdrstop

#include "f_Settings.h"
#include "MainWnd.h";
#include "test_shop_action.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tsettings_frm *settings_frm;
//---------------------------------------------------------------------------
__fastcall Tsettings_frm::Tsettings_frm(TComponent* Owner)
   : TFrame(Owner)
{
        settings_pages->ActivePage->Name == "shop_opts";
}
//---------------------------------------------------------------------------
void __fastcall Tsettings_frm::FrameResize(TObject *Sender)
{
   //this->ftp_settings->Width = this->Width/2;
}
//---------------------------------------------------------------------------
void Tsettings_frm::ShowShopParameters(SShop* _shop)
{
   ftp_host->Text       = _shop->ftp_host;
   ftp_port->Text       = _shop->ftp_port;
   ftp_dir->Text        = _shop->ftp_dir;
   ftp_login->Text      = _shop->ftp_login;
   ftp_password->Text   = _shop->ftp_password;
   ftp_passive->Checked = _shop->ftp_passive;

   mysql_host->Text     = _shop->mysql_host;
   mysql_db->Text       = _shop->mysql_db;
   mysql_login->Text    = _shop->mysql_login;
   mysql_password->Text = _shop->mysql_password;

   http_url->Text       = _shop->http_url;
   admin_login->Text    = _shop->admin_email;
   admin_password->Text = _shop->admin_password;
}

//---------------------------------------------------------------------------
SShop* Tsettings_frm::FillShop(SShop* _shop)
{
   _shop->ftp_host       = ftp_host->Text;

   _shop->ftp_host = _shop->ftp_host.Trim();
   _shop->ftp_host = _shop->ftp_host.LowerCase();

   if (_shop->ftp_host.Pos("ftp://") == 1) {
   	_shop->ftp_host = _shop->ftp_host.SubString(7, _shop->ftp_host.Length() - 6);
   }

   if (_shop->ftp_host.Length() > 0) {
      if (_shop->ftp_host[_shop->ftp_host.Length()] == '/') {
   	   _shop->ftp_host = _shop->ftp_host.SubString(1, _shop->ftp_host.Length() - 1);
      }
   }

   _shop->ftp_port       = ftp_port->Text;
   _shop->ftp_dir        = ftp_dir->Text;
   _shop->ftp_login      = ftp_login->Text;
   _shop->ftp_password   = ftp_password->Text;
   _shop->ftp_passive    = ftp_passive->Checked;

   _shop->mysql_host     = mysql_host->Text;
   _shop->mysql_db       = mysql_db->Text;
   _shop->mysql_login    = mysql_login->Text;
   _shop->mysql_password = mysql_password->Text;

   _shop->http_url       = http_url->Text;

   if (_shop->http_url.Pos("http://") == 0) {
   	_shop->http_url = "http://" + _shop->http_url;
   }
   if (_shop->http_url[_shop->http_url.Length()] == '/') {
   	_shop->http_url = _shop->http_url.SubString(1, _shop->http_url.Length() - 1);
   }
   _shop->admin_email    = admin_login->Text;
   _shop->admin_password = admin_password->Text;
   return _shop;
}

//---------------------------------------------------------------------------
void __fastcall Tsettings_frm::shop_saveClick(TObject *Sender)
{
   if (shops->Items->Strings[shops->ItemIndex] == ADD_SHOP) {
      is_new_shop = true;
   } else {
      is_new_shop = false;
   }

   SShop *_shop;
   if (is_new_shop) {
      _shop = new SShop();
   } else {
      _shop = mainWindow->shops->getByName(this->shops->Items->Strings[shops->ItemIndex]);
      if (_shop == NULL) return;
   }
   FillShop(_shop);
   if (!_shop->IsFilled()) {
		AnsiString message = "The following required fields are missed:\n" + _shop->GetEmptyFields();
		MessageBox(NULL, message.c_str(), "Warning!", MB_OK|MB_ICONEXCLAMATION);
      delete _shop;
      return;
   }
   if (is_new_shop) {
      if (_shop->Add()) {
         mainWindow->shops->Add(_shop);
         mainWindow->updateShops();
      }
   } else {
      _shop->DeleteByName(this->shops->Items->Strings[shops->ItemIndex]);
      _shop->Save();
      mainWindow->updateShops();
   }
	ShowShopParameters(_shop);
}
//---------------------------------------------------------------------------
void __fastcall Tsettings_frm::shop_deleteClick(TObject *Sender)
{
   if (shops->Items->Strings[shops->ItemIndex] == ADD_SHOP) {
      return;
   };

	if (Application->MessageBox("Are you sure you want to delete current settings?", "Confirmation", MB_YESNO|MB_ICONQUESTION) != IDYES) {
      return;
   }

   mainWindow->shops->Remove(this->shops->Items->Strings[shops->ItemIndex]);
   mainWindow->updateShops();
	shopsChange(Sender);
}
//---------------------------------------------------------------------------

void __fastcall Tsettings_frm::shopsChange(TObject *Sender)
{
   SShop* _shop;
   if (shops->Items->Strings[shops->ItemIndex] == ADD_SHOP) {
      _shop = new SShop();
   } else {
      _shop = mainWindow->shops->getByName(shops->Items->Strings[shops->ItemIndex]);
   }
	ShowShopParameters(_shop);
}
//---------------------------------------------------------------------------

void __fastcall Tsettings_frm::proxy_saveClick(TObject *Sender)
{
   TRegistry *reg;
   reg = new TRegistry();
   reg->OpenKey("software\\LiteCommerce\\Proxy", true);
   reg->WriteString("proxy", this->proxy_server->Text);
   reg->WriteString("proxy_port", this->proxy_port->Text);
   reg->WriteString("proxy_login", this->proxy_login->Text);
   reg->WriteString("proxy_password", this->proxy_password->Text);
   reg->WriteBool("use_proxy", this->use_proxy->Checked);
   reg->WriteBool("use_proxy_login", this->use_proxy_login->Checked);
   reg->CloseKey();
   delete reg;

   mainWindow->proxy = proxy_server->Text;
   mainWindow->proxy_port = proxy_port->Text;
   mainWindow->proxy_login = proxy_login->Text;
   mainWindow->proxy_pass = proxy_password->Text;
   mainWindow->use_proxy = use_proxy->Checked;
   mainWindow->use_proxy_auth = use_proxy_login->Checked;
}
//---------------------------------------------------------------------------
void __fastcall Tsettings_frm::SpeedButton1Click(TObject *Sender)
{
   // Choice HTML editor
	if (editor_od->Execute()) {
   	if (FileExists(editor_od->FileName)) {
      	_editor->Text = editor_od->FileName;
         SaveEditor();
      }
   }
}
//---------------------------------------------------------------------------

void Tsettings_frm::SaveEditor() {
   TRegistry* reg = new TRegistry();
   reg->OpenKey("Software\\LiteCommerce\\Applications", true);
   reg->WriteString("html_editor", _editor->Text);
   reg->CloseKey();
   delete reg;
   mainWindow->html_editor = _editor->Text;
}
//---------------------------------------------------------------------------

void __fastcall Tsettings_frm::settings_pagesChange(TObject *Sender)
{
	if (settings_pages->ActivePage->Name == "apps_opts") {
   	ReadEditor();
   }
}
//---------------------------------------------------------------------------
void Tsettings_frm::ReadEditor() {
   TRegistry* reg = new TRegistry();
   try {
   	reg->OpenKey("Software\\LiteCommerce\\Applications", true);
      _editor->Text = reg->ReadString("html_editor");
      reg->CloseKey();
   }
   __finally
   {
      delete reg;
   }
}
//---------------------------------------------------------------------------
void __fastcall Tsettings_frm::test_settingsClick(TObject *Sender)
{
   if (shops->Items->Strings[shops->ItemIndex] == ADD_SHOP) {
      return;
   }
   SShop* testing_shop = new SShop();
   FillShop(testing_shop);
   Ttest_shop* test_wnd = new Ttest_shop(this);
   test_wnd->shop = testing_shop;
   test_wnd->ShowModal();
	delete test_wnd;
}
//---------------------------------------------------------------------------
void __fastcall Tsettings_frm::use_proxyClick(TObject *Sender)
{
	mainWindow->use_proxy = this->use_proxy->Checked;

   proxy_server->Enabled = mainWindow->use_proxy;
   proxy_port->Enabled = mainWindow->use_proxy;
   use_proxy_login->Enabled = mainWindow->use_proxy;

   proxy_login->Enabled = (mainWindow->use_proxy & mainWindow->use_proxy_auth);
   proxy_password->Enabled = (mainWindow->use_proxy & mainWindow->use_proxy_auth);
}
//---------------------------------------------------------------------------

void __fastcall Tsettings_frm::use_proxy_loginClick(TObject *Sender)
{
	mainWindow->use_proxy_auth = this->use_proxy_login->Checked;
   
   proxy_server->Enabled = mainWindow->use_proxy;
   proxy_port->Enabled = mainWindow->use_proxy;
   use_proxy_login->Enabled = mainWindow->use_proxy;

   proxy_login->Enabled = (mainWindow->use_proxy & mainWindow->use_proxy_auth);
   proxy_password->Enabled = (mainWindow->use_proxy & mainWindow->use_proxy_auth);
}
//---------------------------------------------------------------------------

bool __fastcall test_mysql_settings(SShop *testing_shop)
{
	return true;
}
//---------------------------------------------------------------------------

bool __fastcall test_account_settings(SShop *testing_shop)
{
	return true;
}
//---------------------------------------------------------------------------


