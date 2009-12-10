//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "backup_db_req.h"

#pragma package(smart_init)
//---------------------------------------------------------------------------

__fastcall backup_db_request::backup_db_request(bool CreateSuspended)
   : TThread(CreateSuspended)
{
   wctrl = new WebControl();
}
//---------------------------------------------------------------------------
void __fastcall backup_db_request::Execute()
{
   Backup();
}
//---------------------------------------------------------------------------
void backup_db_request::Backup()
{
   /******************************************
   Connecting
   ******************************************/
   SShop * shop = mainWindow->shops->getByName(_parent->shop_name);
   wctrl->setFtpHost(shop->ftp_host.c_str());

   bool connect;

   if (mainWindow->use_proxy) {
      if (mainWindow->use_proxy_auth) {
         connect = wctrl->connect(
            shop->ftp_login.c_str(),
            shop->ftp_password.c_str(),
            mainWindow->proxy.c_str(),
            mainWindow->proxy_port.c_str(),
            mainWindow->proxy_login.c_str(),
            mainWindow->proxy_pass.c_str()
         );
      } else {
         connect = wctrl->connect(
            shop->ftp_login.c_str(),
            shop->ftp_password.c_str(),
            mainWindow->proxy.c_str(),
            mainWindow->proxy_port.c_str()
         );
      }
   } else {
      connect = wctrl->connect(shop->ftp_login.c_str(), shop->ftp_password.c_str());
   }

   if (!connect) {
      this->text = "Connection failed";
      Synchronize(add_text);
      _parent->db_success = false;
      delete wctrl;
      return;
   }

   /******************************************
   Creating backup
   ******************************************/
   changeCaption("Creating database backup");
   this->text = "Creating database backup ... ";
   Synchronize(add_text);
   AnsiString URL = shop->http_url + "/admin.php?";
   AnsiString post = "target=db&action=backup&login="
    + shop->admin_email
    + "&password="
    + shop->admin_password
    + "&mode=cp&write_to_file=yes";
   wctrl->clearResponse();
   wctrl->sendPost(URL.c_str(), post.c_str());
   AnsiString resp(wctrl->getResponse());
   bool success = false;

   if (resp.SubString(resp.Length()-1, 2) == "OK") {
      success = true;
   }

   if (!success) {
      this->text = "Failed";
      Synchronize(add_text);
      _parent->db_success = false;
      delete wctrl;
      return;
   }
   _parent->db_success = true;

   /******************************************
   Download file;
   ******************************************/
   changeCaption("Downloading SQL dump");
   AnsiString path = shop->ftp_dir + "/var/backup/sqldump.sql";
   sql_backup_file = new TFileStream(_parent->backup_dir + "\\sqldump.sql", fmCreate);

   if (wctrl->_ftpDownloadFile(path.c_str(), this)) {
      text = "Uploading file success";
   } else {
      text = "Uploading file failed";
      Synchronize(add_text);
      _parent->db_success = false;
      return;
   }
   text = "[OK]";
   Synchronize(add_to_line);

   delete sql_backup_file;
   /******************************************
   Removing backup
   ******************************************/
   post = "target=db&action=delete&login="
    + shop->admin_email
    + "&password="
    + shop->admin_password
    + "&mode=cp&write_to_file=yes";
   wctrl->clearResponse();
   wctrl->sendPost(URL.c_str(), post.c_str());
   resp = wctrl->getResponse();

   if (resp == "OK") {
      success = true;
   } else {
      success = false;
   }

   if (!success) {
      text = "[FAILED]";
      Synchronize(add_to_line);
      _parent->db_success = false;
      delete wctrl;
      return;
   }
   _parent->db_success = true;

   /******************************************
   download full backup;
   ******************************************/

   if (_parent->full) {
      changeCaption("Creating shop full backup");
      backup_file = new TFileStream(_parent->backup_dir + "\\backup.tar", fmCreate);
      downloaded = 0;
      post = "target=files&action=tar&login="
      + shop->admin_email
      + "&password="
      + shop->admin_password;

      text = "Creating shop full backup ... ";
      add_text();
      wctrl->getData(URL.c_str(), post.c_str(), this);
      delete backup_file;
      text = "[OK]";
      Synchronize(add_to_line);
   }
   delete wctrl;
   text = "\r\nBackup finished successfully";
   add_text();
   changeCaption("Backup finished successfully");
   delete backup_file;
}

void __fastcall backup_db_request::add_text()
{
   _parent->db_otput->Lines->Add(this->text);
}

void backup_db_request::getBackupPortion(void* data, int bytes)
{
   backup_file->Write(data, bytes);
   this->downloaded += bytes;
   Synchronize(setDownloadedBytes);
}

void backup_db_request::getBackupSqlPortion(void* data, int bytes)
{
   sql_backup_file->Write(data, bytes);
   this->downloaded += bytes;
   Synchronize(setDownloadedBytes);
}

void __fastcall backup_db_request::add_to_line()
{
   AnsiString str = _parent->db_otput->Lines->Strings[_parent->db_otput->Lines->Count - 1];
   str += text;
   _parent->db_otput->Lines->Strings[_parent->db_otput->Lines->Count-1] = str;
}

void __fastcall backup_db_request::setDownloadedBytes()
{
   _parent->downloaded_size->Caption = "Dowloaded " +
      IntToStr(downloaded) +
      " bytes";
}

void __fastcall backup_db_request::changeFormCaption()
{
   _parent->Caption = this->caption;
}

void backup_db_request::changeCaption(AnsiString Caption)
{
   this->caption = Caption;
   Synchronize(changeFormCaption);
}
