//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "move_shop_action.h"
#include "restore_thread.h"
#include "MainWnd.h"
#include "FileUtil.h"
#include <LibTar.hpp>
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tmove_shop_act *move_shop_act;
SShop *current_mode_shop;
Tmove_shop_thread* move_thread;

//---------------------------------------------------------------------------
__fastcall Tmove_shop_act::Tmove_shop_act(TComponent* Owner)
        : TForm(Owner)
{
}
void __fastcall modify_config(AnsiString restore_dir,SShop* uploaded_shop_params)
{
   AnsiString post;
   TFileStream* conf_file = new TFileStream(restore_dir + "/etc/config.php",fmOpenRead);
   char *buffer = new char[conf_file->Size+1];
   conf_file->Read(buffer,conf_file->Size);
   post = AnsiString(buffer,conf_file->Size);
   FileClose(conf_file->Handle);
   TRegExpr *regex = new TRegExpr();
   regex->Expression = "/hostspec[ ]*=[ ]*\"([^\"]*)\"/";
   if (regex->Exec(post));
   post = regex->Replace(post,"hostspec = \"" + uploaded_shop_params->mysql_host + "\"",false);
   regex->Expression = "database[ ]*=[ ]*\"([^\"]*)\"";
   post = regex->Replace(post,"database = \"" + uploaded_shop_params->mysql_db + "\"",false);
   regex->Expression = "username[ ]*=[ ]*\"([^\"]*)\"";
   post = regex->Replace(post,"username = \"" + uploaded_shop_params->mysql_login + "\"",false);
   regex->Expression = "password[ ]*=[ ]*\"([^\"]*)\"";
   post = regex->Replace(post,"password = \"" + uploaded_shop_params->mysql_password + "\"",false);
   regex->ModifierG = false;
   regex->Expression = "^http://(.*)/(.*)$";
   AnsiString web_url;
   AnsiString web_dir;
   if (regex->Exec(uploaded_shop_params->http_url))
      {
        web_url = regex->Substitute("$1");
        web_dir = regex->Substitute("$2");
        web_dir = "/" + web_dir;
      }
   regex->ModifierG = true;
   regex->Expression = "http_host[ ]*=[ ]*\"([^\"]*)\"";
   post = regex->Replace(post,"http_host = \"" + web_url + "\"",false);
   regex->Expression = "https_host[ ]*=[ ]*\"([^\"]*)\"";
   post = regex->Replace(post,"https_host = \"" + web_url + "\"",false);
   regex->Expression = "web_dir[ ]*=[ ]*\"([^\"]*)\"";
   post = regex->Replace(post,"web_dir = \"" + web_dir + "\"",false);
   TFileStream* cconf_file =new TFileStream(restore_dir + "/etc/config.php",fmOpenWrite);
   cconf_file->Write(post.c_str(),post.Length());
   FileClose(cconf_file->Handle);
}
//---------------------------------------------------------------------------
void __fastcall Tmove_shop_act::FormCreate(TObject *Sender)
{
        move_thread = new Tmove_shop_thread(true);
        move_thread->_parent = this;
        move_thread->Resume();
}
//---------------------------------------------------------------------------
__fastcall Tmove_shop_thread::Tmove_shop_thread (bool CreateSuspended)
 : TThread(CreateSuspended)
{
        move_shop_ctrl = new WebControl();
}
//---------------------------------------------------------------------------
void __fastcall Tmove_shop_thread::Execute()
{
   __try {
    _parent->_ok->Enabled = false;
    _parent->_cancel->Enabled = true;
    move_thread->fetched_shop_params = mainWindow->shops->getByName(mainWindow->move_frame->src->Items->Strings[mainWindow->move_frame->src->ItemIndex]);
    move_thread->uploaded_shop_params = mainWindow->shops->getByName(mainWindow->move_frame->dst->Items->Strings[mainWindow->move_frame->dst->ItemIndex]);

    move_shop_ctrl->setFtpHost(fetched_shop_params->ftp_host.c_str(),
                                   fetched_shop_params->ftp_port.c_str());

   this->text = "Testing connection to source shop...";
   Synchronize(change_caption);
   this->text = "Trying to connect FTP server....";
   Synchronize(change_caption);
   if (!mainWindow->settings_frame->test_connect_settings(move_shop_ctrl,fetched_shop_params))
   {
       this->text = "Connection failed. Check FTP Address, Login or Password.";
       Synchronize(change_caption);
       delete move_shop_ctrl;
       return;
   }
   this->text = "Successfully tested.";
   Synchronize(change_caption);
   this->text = "Trying to login FTP account and check URL...";
   Synchronize(change_caption);
      switch(mainWindow->settings_frame->test_ftp_settings(move_shop_ctrl,fetched_shop_params))
      {
        case -1 : this->text = "File upload failed. Check FTP Port or Upload directory.";
                  Synchronize(change_caption);
                  return;
        case -2 : this->text = "Connection failed. Check Shop URL, Admin e-mail or Admin Password.";
                  Synchronize(change_caption);
                  return;
        case -3 : this->text = "Connection failed. Check Shop URL, Admin e-mail or Admin Password.";
                  Synchronize(change_caption);
                  return;
      }
   this->text = "Successfully tested.";
   Synchronize(change_caption);
   this->text =  "Connection tested.";
   Synchronize(change_caption);

   this->text =  "Making backup of source shop...";
   Synchronize(change_caption);

   _parent->Label1->Visible = true;
   _parent->bytes_log->Visible = true;
   temporary_file = new TFileStream(GetCurrentDir()+"\\temp.tar",fmCreate);
   AnsiString url = fetched_shop_params->http_url + "/admin.php?";
   AnsiString post = "target=files&action=tar&mode=full&login=" + fetched_shop_params->admin_email + "&password=" + fetched_shop_params->admin_password;
   move_shop_ctrl->getDataMoving(url.c_str(),post.c_str(),this);
   delete temporary_file;
   _parent->Label1->Visible = false;
   _parent->bytes_log->Visible = false;
   if (FileExists(GetCurrentDir()+"\\temp.tar"))
           this->text =  "Making backup of source shop completed.";
           Synchronize(change_caption);

   this->text =  "Extracting files from source shop archive...";
   Synchronize(change_caption);

   AnsiString restore_dir = "~temp";
   CreateDirectory(restore_dir.c_str(), NULL);
   _parent->Label1->Visible = true;
   _parent->_cancel->Enabled = false;
   TList *files = new  TList();
   TStringList *dirs = new TStringList();
   TTarArchive* archive = new TTarArchive("temp.tar", tmSaveText);
   TTarDirRec tar_rec;
   archive->Reset();
   int files_count = 0;
     while (archive->FindNext(tar_rec)) {
      	files_count ++;
         if (tar_rec.FileType == 5) { //directory
            createDirs(restore_dir + "/" + tar_rec.Name);
            dirs->Add(tar_rec.Name);
         } else {
                if (tar_rec.Name == "var/backup/sqldump.sql.php")
                        {
                            createDirs(restore_dir + "/" + getDir(tar_rec.Name));
                            dirs->Add("var");
                            dirs->Add("var/backup");
                        }

            archive->ReadFile(restore_dir + "/" + tar_rec.Name);
           this->text = "Extracting file " + tar_rec.Name + " ...";
           Synchronize(change_label);
           fileinfo *_file = new fileinfo();
           _file->getPermissions(PermissionString(tar_rec.Permissions));
            _file->name = tar_rec.Name;
            files->Add((void*)_file);
         }
      }
   _parent->_cancel->Enabled = true;
   delete archive;
   if (files_count == 0) {
         this->text = "\r\nControl panel is unable to restore the whole shop\r\nBackup file is not valid TAR file or corrupted.";
         Synchronize(change_caption);
         delete move_shop_ctrl;
         return;
      }
   _parent->Label1->Visible = false;
   this->text = "Files successfully extracted.";
   Synchronize(change_caption);
   this->text = "Modifying config.php with settings of destination shop...";
   Synchronize(change_caption);
   modify_config(restore_dir,uploaded_shop_params);
   this->text = "Modifying config.php completed.";
   Synchronize(change_caption);
    delete move_shop_ctrl;
   move_shop_ctrl = new WebControl();

   this->text = "Testing connection to destination shop...";
   Synchronize(change_caption);
   this->text = "Trying to connect FTP server....";
   Synchronize(change_caption);
   if (!mainWindow->settings_frame->test_connect_settings(move_shop_ctrl,uploaded_shop_params))
   {
       this->text = "Connection failed. Check FTP Address, Login or Password.";
       Synchronize(change_caption);
       delete move_shop_ctrl;
       return;
   }
   this->text = "Successfully tested.";
   Synchronize(change_caption);
   this->text = "Trying to login FTP account and check URL...";
   Synchronize(change_caption);
      switch(mainWindow->settings_frame->test_ftp_settings(move_shop_ctrl,uploaded_shop_params))
      {
        case -1 : this->text = "File upload failed. Check FTP Port or Upload directory.";
                  Synchronize(change_caption);
                  return;
      }
   this->text = "Successfully tested.";
   Synchronize(change_caption);
   this->text =  "Connection tested.";
   Synchronize(change_caption);

   move_shop_ctrl->ftpCommand(AnsiString("MKD " + uploaded_shop_params->ftp_dir).c_str());
   move_shop_ctrl->ftpCommand(AnsiString("SITE CHMOD 0777 " + uploaded_shop_params->ftp_dir).c_str());
   move_shop_ctrl->setFtpHomeUrl(uploaded_shop_params->ftp_dir.c_str());

   _parent->Label1->Visible = true;
      this->text = "Uploading files to destination shop...";
   Synchronize(change_caption);
   move_shop_ctrl->ftpCreateDirRec(dirs);
      for(int i = 0; i < files->Count; i++) {
      	fileinfo* _file = (fileinfo*) files->Items[i];
           this->text = "Uploading file " + _file->name + " ...";
           Synchronize(change_label);
         if ( !move_shop_ctrl->ftpUploadFile(_file->name.c_str(), restore_dir.c_str())) {
              this->text = "File upload failed. Connection lost.";
              Synchronize(change_caption);
              delete move_shop_ctrl;
              return;
         }
   	}
   _parent->Label1->Visible = false;
   this->text = "Files were uploaded successfully.";
   Synchronize(change_caption);
   this->text = "Restoring original permissions ... ";
   Synchronize(change_caption);
   move_shop_ctrl->ftpCommand("SITE CHMOD 0777 catalog");
   move_shop_ctrl->ftpCommand("SITE CHMOD 0777 classes/modules");
   move_shop_ctrl->ftpCommand("SITE CHMOD 0777 skins");
   move_shop_ctrl->ftpCommand("SITE CHMOD 0777 cart.html");
   move_shop_ctrl->ftpCommand("SITE CHMOD 0777 images");
   move_shop_ctrl->ftpCommand("SITE CHMOD 0777 var");
// CURL specific
/*
   struct curl_slist *headerlist = NULL;
   for(int i = 0; i < files->Count; i++) {
     	fileinfo* _file = (fileinfo*) files->Items[i];
        	headerlist = curl_slist_append(headerlist, ("SITE CHMOD " + _file->permissions + " " + _file->name).c_str());
         rDeleteDir(restore_dir);
   	}
     	if (move_shop_ctrl->ftpCommand(headerlist))
                this->text = "Restoring original permissions succeeded.";
        else
                this->text = "Restoring original permissions FAILED.";
*/                
      Synchronize(change_caption);
      delete files;
      delete dirs;
      rDeleteDir(restore_dir);
     _parent->_ok->Enabled = true;
     _parent->_cancel->Enabled = false;
    } catch(...) {

    }
    _parent->_ok->Enabled = true;
}
//---------------------------------------------------------------------------
void Tmove_shop_thread::get_tar_pack(void* data, int bytes)
{
      temporary_file->Write(data, bytes);
      downloaded  += bytes;
      _parent->bytes_log->Caption = downloaded;
}
//---------------------------------------------------------------------------
void __fastcall Tmove_shop_thread::change_caption()
{
        _parent->move_log->Lines->Add(text);
}
//---------------------------------------------------------------------------
void __fastcall Tmove_shop_thread::change_label()
{
        _parent->Label1->Caption = text;
}
//---------------------------------------------------------------------------
void __fastcall Tmove_shop_act::_cancelClick(TObject *Sender)
{
        move_thread->Suspend();
        __try
        {
        if (move_thread->temporary_file->Handle)
                {
                   FileClose(move_thread->temporary_file->Handle);
                 }
        if (FileExists(GetCurrentDir() + "\\temp.rar")) DeleteFile(GetCurrentDir()+ "\\temp.rar");
        }
        catch(...) {}
        move_thread->Terminate();
        Close();
}
//---------------------------------------------------------------------------

