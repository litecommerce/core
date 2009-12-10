//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "f_AddShop.h"
#include "MainWnd.h";
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tadd_shop_frm *add_shop_frm;
//---------------------------------------------------------------------------
__fastcall Tadd_shop_frm::Tadd_shop_frm(TComponent* Owner)
   : TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tadd_shop_frm::shop_saveClick(TObject *Sender)
{
   SShop *_shop;
   _shop = new SShop();
   _shop->ftp_host       = ftp_host->Text;
   _shop->ftp_port       = ftp_port->Text;
   _shop->ftp_dir        = ftp_dir->Text;
   _shop->ftp_login      = ftp_login->Text;
   _shop->ftp_password   = ftp_password->Text;

   _shop->mysql_host     = mysql_host->Text;
   _shop->mysql_db       = mysql_db->Text;
   _shop->mysql_login    = mysql_login->Text;
   _shop->mysql_password = mysql_password->Text;

   _shop->http_url       = http_url->Text;
   _shop->admin_email    = admin_login->Text;
   _shop->admin_password = admin_password->Text;

   if (_shop->Add()) {
      mainWindow->shops->Add(_shop);
      mainWindow->updateShops();
   }
   ((TForm*)Parent)->Close();
}
//---------------------------------------------------------------------------

