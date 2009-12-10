//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "backup_thread.h"
#pragma package(smart_init)
//---------------------------------------------------------------------------

__fastcall Thread_backup::Thread_backup(bool CreateSuspended)
	: WebThread(CreateSuspended)
{
   full_backup_file = NULL;
   is_move_shop = false;
}
//---------------------------------------------------------------------------

__fastcall Thread_backup::Thread_backup(bool CreateSuspended, bool full, bool inc_sql):WebThread(CreateSuspended)
{
   full_backup_file = NULL;
	this->full = full;
   this->inc_sql = inc_sql;
   is_move_shop = true;
}
//---------------------------------------------------------------------------

__fastcall Thread_backup::~Thread_backup()
{
	if (full_backup_file != NULL) {
   	delete full_backup_file;
	}
}
//---------------------------------------------------------------------------

void __fastcall Thread_backup::Init()
{
	_display = _parent->__m_display;
   __parent = _parent;
   AnsiString restore_dir = Application->ExeName.SubString(1, Application->ExeName.LastDelimiter("\\") - 1) + "\\temp_restore_files";
   if (!is_move_shop) {
   	shop = mainWindow->shops->getByName(mainWindow->backup_restore_frame->backup_choose_shop->Items->Strings[mainWindow->backup_restore_frame->backup_choose_shop->ItemIndex]);
		full = mainWindow->backup_restore_frame->full;
   	inc_sql = mainWindow->backup_restore_frame->sql_include->Checked;
   	f_backup_filename = mainWindow->sbackup_filename;
   	d_backup_filename = mainWindow->dbackup_filename;
   } else {
      f_backup_filename = restore_dir + "\\move.tar";
      d_backup_filename = restore_dir + "\\move.sql";
      shop = mainWindow->shops->getByName(mainWindow->move_frame->src->Items->Strings[mainWindow->move_frame->src->ItemIndex]);
   }
   HttpInit();
}
//---------------------------------------------------------------------------

void __fastcall Thread_backup::Execute()
{
	Init();
   if (!Connect()) {
   	return;
   }
   if (!CdHome(false)) {
      PrintLn("* ERROR: Shop home dir " + shop->ftp_dir + " not found");
   	return;
   }
   if (full) {
   	if (FullBackup()) {
      	_parent->SetResult(true);
         if (!is_move_shop) {
         	PrintLn("Backup finished successfully. Press \"Ok\" button to return to Control Panel");
         }
      } else {
      	if (!is_move_shop) {
      		PrintLn("Shop full backup failed. Press \"Cancel\" button to return to Control Panel");
         }
      }
   } else {
      if (DbBackup()) {
      	_parent->SetResult(true);
         if (!is_move_shop) {
         	PrintLn("Backup finished successfully. Press \"Ok\" button to return to Control Panel");
         }
      } else {
      	if (!is_move_shop) {
      		PrintLn("Database backup failed. Press \"Cancel\" button to return to Control Panel");
         }
      }
   }
}
//---------------------------------------------------------------------------

bool Thread_backup::_CreateServerDbBackup(void)
{
   AnsiString URL = shop->http_url + "/admin.php?";
   ChangeCaption("Creating database backup");
   PrintLn("Creating database backup, please, wait ... ");

   _post->Clear();
   _post->Add("target=db");
   _post->Add("action=backup");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password=" + shop->admin_password);
   _post->Add("mode=cp");
   _post->Add("write_to_file=yes");

   Post(URL);

   if (_response.SubString(_response.Length()-1, 2) != "OK") {
   	Print("[FAILED]");
      return false;
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool Thread_backup::_DownloadDbBackup(void)
{
	ChangeCaption("Downloading SQL dump");
   PrintLn("Downloading SQL dump ... ");

   AnsiString path = "var/backup/sqldump.sql.php";

   mainWindow->ifc->OnWork = OnDownload;
   bool result = FtpDownload(path, d_backup_filename);
   mainWindow->ifc->OnWork = NULL;

   if (!result) {
   	Print("[FAILED]");
   } else {
   	Print("[OK]");
   }
	return result;
}
//---------------------------------------------------------------------------

bool Thread_backup::_RemoveSqlDump(void)
{
   AnsiString URL = shop->http_url + "/admin.php?";

	ChangeCaption("Removing SQL dump from server");
   PrintLn("Removing SQL dump from server ... ");

   _post->Clear();
   _post->Add("target=db");
   _post->Add("action=delete");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password=" + shop->admin_password);
   _post->Add("mode=cp");
   _post->Add("write_to_file=yes");

   Post(URL);

   if (_response.UpperCase() != "OK") {
   	Print("FAILED]");
      return false;
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool Thread_backup::DbBackup(void)
{
   if (!_CreateServerDbBackup()) {
   	return false;
   }
   if (!_DownloadDbBackup()) {
   	return false;
   }
   if (!_RemoveSqlDump()) {
   	PrintLn("*** Warning: Could not remove the database dump file. Please, remove the file var/backup/sqldump.sql.php manualy");
   }

   ChangeCaption("Database backup completed");
   return true;
}
//---------------------------------------------------------------------------

bool Thread_backup::FullBackup(void)
{
   AnsiString URL = shop->http_url + "/admin.php?";
   try {
   	AnsiString path = getDir(f_backup_filename);
      createDirs(path);
		full_backup_file = new TFileStream(f_backup_filename, fmCreate);
   } catch (...) {
   	PrintLn("ERROR: Unable to create local file " + mainWindow->sbackup_filename);
      return false;
   }
   ChangeCaption("Creating shop full backup");

   _post->Clear();
   _post->Add("target=files");
   _post->Add("action=tar");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password=" + shop->admin_password);

   if (inc_sql) {
   	_post->Add("mode=full");
   }

   PrintLn("Creating shop full backup ... ");

   mainWindow->ihc->OnWork = OnDownload;
   bool result = Post(URL, full_backup_file);
   mainWindow->ihc->OnWork = NULL;
   delete full_backup_file;
   full_backup_file = NULL;

   if (!result) {
   	Print("[FAILED]");
   } else {
   	Print("[OK]");
   }
   return result;
}
//---------------------------------------------------------------------------

void __fastcall Thread_backup::_setSizeLabelText(void)
{
	//_parent->downloaded_size->Caption = _size_label_text;
	_parent->__bytes->Caption = _size_label_text;
}
//---------------------------------------------------------------------------

void Thread_backup::PrintDownloadedSize(AnsiString text)
{
	_size_label_text = text;
   Synchronize(_setSizeLabelText);
}
//---------------------------------------------------------------------------

void __fastcall Thread_backup::OnDownload(TObject *Sender, TWorkMode AWorkMode, const int AWorkCount)
{
	PrintDownloadedSize("Downloaded " + IntToStr(AWorkCount) + " bytes");
}
//---------------------------------------------------------------------------

