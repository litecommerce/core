//---------------------------------------------------------------------------

#include <vcl.h>
#include "constants.h"
#pragma hdrstop

#include "MainWnd.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma link "f_BackupMain"
#pragma link "f_Settings"
#pragma link "f_Wysiwyg"
#pragma link "f_Move"
#pragma link "f_Upgrade"

#pragma link "IdBaseComponent"
#pragma link "IdComponent"
#pragma link "IdFTP"
#pragma link "IdHTTP"
#pragma link "IdTCPClient"
#pragma link "IdTCPConnection"
#pragma resource "*.dfm"
TmainWindow *mainWindow;
//---------------------------------------------------------------------------
__fastcall TmainWindow::TmainWindow(TComponent* Owner)
   : TForm(Owner)
{
   skin_files = new TStringList();

   frames = new TList();
   frames->Add(backup_restore_frame);
   frames->Add(wysiwyg_frame);
   frames->Add(move_frame);
   frames->Add(upgrade_frame);

   add_frames = new TList();
   add_frames->Add(settings_frame);

   shops = new SShopList();

   shop_boxes = new TList();
   shop_boxes->Add(settings_frame->shops);
   shop_boxes->Add(backup_restore_frame->backup_choose_shop);
   shop_boxes->Add(wysiwyg_frame->wysiwyg_shop);
   shop_boxes->Add(move_frame->src);
   shop_boxes->Add(move_frame->dst);
   shop_boxes->Add(upgrade_frame->upg_shop);

   control_buttons = new TList();
   control_buttons->Add(btn_BkpRes);
   control_buttons->Add(btn_wysiwyg);
   control_buttons->Add(btn_move);
   control_buttons->Add(btn_upgrade);
}
//---------------------------------------------------------------------------

void __fastcall TmainWindow::FormCreate(TObject *Sender)
{
   btn_BkpRes->Caption = "Backup\nRestore";
   btn_wysiwyg->Caption = "WYSIWYG\nDesign";
   btn_move->Caption = "Move\nShop";
   btn_upgrade->Caption = "Upgrade";

   updateShops();

   TRegistry *reg;
   reg = new TRegistry();
   if (reg->OpenKey("software\\LiteCommerce\\Proxy", false)) {
      proxy = reg->ReadString("proxy");
      proxy_port = reg->ReadString("proxy_port");
      proxy_login = reg->ReadString("proxy_login");
      proxy_pass = reg->ReadString("proxy_password");
      use_proxy = reg->ReadBool("use_proxy");
      use_proxy_auth = reg->ReadBool("use_proxy_login");

      settings_frame->proxy_server->Text = proxy;
      settings_frame->proxy_port->Text = proxy_port;
      settings_frame->proxy_login->Text = proxy_login;
      settings_frame->proxy_password->Text = proxy_pass;
      settings_frame->use_proxy->Checked = use_proxy;
      settings_frame->use_proxy_login->Checked = use_proxy_auth;

      reg->CloseKey();
   }
   settings_frame->proxy_server->Enabled = use_proxy;
   settings_frame->proxy_port->Enabled = use_proxy;
   settings_frame->use_proxy_login->Enabled = use_proxy;

   settings_frame->proxy_login->Enabled = (use_proxy & use_proxy_auth);
   settings_frame->proxy_password->Enabled = (use_proxy & use_proxy_auth);

   if (reg->OpenKey("software\\LiteCommerce\\Applications", false)) {
      html_editor = reg->ReadString("html_editor");
      reg->CloseKey();
   }
   delete reg;

   disable_all_view();
   //control_button_click(0);
   btn_settingsClick(Sender);
   btn_settings->Down = true;
 }
//---------------------------------------------------------------------------

void TmainWindow::control_button_click(int btn)
{
   for(int i=0; i<frames->Count; i++) {
      if (i != btn) {
         ((TFrame*)frames->Items[i])->Visible = false;
      }
   }
   if (btn < frames->Count) {
      ((TFrame*)frames->Items[btn])->Align = alClient;
      ((TFrame*)frames->Items[btn])->Visible = true;
   }
   for(int i=0; i<add_frames->Count; i++) {
   	((TFrame*)add_frames->Items[i])->Visible = false;
   }
}
//---------------------------------------------------------------------------

void __fastcall TmainWindow::btn_BkpResClick(TObject *Sender)
{
   control_button_click(0);
}
//---------------------------------------------------------------------------

void __fastcall TmainWindow::btn_wysiwygClick(TObject *Sender)
{
   control_button_click(1);
}
//---------------------------------------------------------------------------

void __fastcall TmainWindow::btn_moveClick(TObject *Sender)
{
  control_button_click(2);
}
//---------------------------------------------------------------------------

void __fastcall TmainWindow::btn_upgradeClick(TObject *Sender)
{
   control_button_click(3);
}
//---------------------------------------------------------------------------

void __fastcall TmainWindow::btn_settingsClick(TObject *Sender)
{
   disable_all_view();
   settings_frame->Align = alClient;
   settings_frame->Visible = true;
   settings_frame->shopsChange(Sender);
   settings_frame->_editor->Text = html_editor;
	settings_frame->settings_pages->ActivePageIndex = 0;
}
//---------------------------------------------------------------------------

void TmainWindow::disable_all_view()
{
	for (int i=0; i < control_buttons->Count; i++) {
   	((TSpeedButton*)control_buttons->Items[i])->AllowAllUp = true;
   	((TSpeedButton*)control_buttons->Items[i])->Down = false;
   	((TSpeedButton*)control_buttons->Items[i])->AllowAllUp = false;
   }

   for(int i=0; i<frames->Count; i++) {
      ((TFrame*)frames->Items[i])->Visible = false;
   }
}
//---------------------------------------------------------------------------

void TmainWindow::updateShops(void)
{
   for (int j=0; j < shop_boxes->Count; j++) {
      TComboBox* box = (TComboBox*)shop_boxes->Items[j];
      int index = box->ItemIndex;
      if (index < 0) {
      	index = 0;
      }
      box->Clear();
      for (int i = 0; i < shops->data->Count; i++) {
         AnsiString str = ((SShop*)(shops->data->Items[i]))->http_url;
         box->Items->Add(((SShop*)(shops->data->Items[i]))->http_url);
      }
      box->Items->Add(ADD_SHOP);
      box->ItemIndex = index;
   }
}
//---------------------------------------------------------------------------



void __fastcall TmainWindow::main_splitCanResize(TObject *Sender,
      int &NewSize, bool &Accept)
{
   Accept = false;
}
//---------------------------------------------------------------------------

