//---------------------------------------------------------------------------

#include <vcl.h>
#include <LibTar.hpp>
#include "FileUtil.h"
#include "constants.h"
#pragma hdrstop

#include <stdio.h>
#include "restore_thread.h"
#pragma package(smart_init)
//---------------------------------------------------------------------------
	_fastcall restore_thr::restore_thr(bool CreateSuspended)
: WebThread(CreateSuspended)
{
	files = new  TList();
   dirs_info = new TList();
   dirs = new TStringList();
   temp_is_clean = false;
   movie_shop = false;
}
//---------------------------------------------------------------------------

__fastcall restore_thr::restore_thr(bool CreateSuspended, bool movie): WebThread(CreateSuspended)
{
	movie_shop = true;
	files = new  TList();
   dirs_info = new TList();
   dirs = new TStringList();
   temp_is_clean = false;
   movie_shop = movie;
}
//---------------------------------------------------------------------------

__fastcall restore_thr::~restore_thr()
{
   if (!temp_is_clean) {
		rDeleteDir(restore_dir);
   }
}
//---------------------------------------------------------------------------

void __fastcall restore_thr::Execute()
{
	// --- INIT section
   Init();
   ChangeCaption("Connecting to server");
   if (!Connect()) {
   	return;
   }
   if (!CdHome()) {
      PrintLn("* ERROR: Unable to create shop home dir " + shop->ftp_dir);
   	return;
   }

   bool restore_result = false;
   AnsiString message;
   AnsiString caption;

	if (! full) {
		if (!RestoreDB()) {
			message = "Control Panel failed to restore shop database. Please, check that you have selected valid SQL database backup file. Press \"Cancel\" button to continue.";
			caption = "Database restoring failed";
         restore_result = false;
      } else {
			message = "The shop database has been restored successfully. Press \"OK\" button to continue.";
			caption = "Database successefully restored";
         restore_result = true;
      }
	} else {
   	if (!RestoreShop()) {
			message = "Control Panel failed to restore your shop database. Press \"Cancel\" button to continue.";
			caption = "Shop restoring failed";
         restore_result = false;
   	} else {
      	message = "The shop has been restored successefully. Press \"Ok\" button to continue.";
			caption = "Shop successefully restored";
         restore_result = true;
      }
   }

   PrintLn("Removing temporary files ... ");
   rDeleteDir(restore_dir);
   temp_is_clean = true;
   Print("[OK]");
	if (!movie_shop) {
   	ChangeCaption(caption);
   	PrintLn(message);
   }
   _parent->SetResult(restore_result);
}
//---------------------------------------------------------------------------

bool restore_thr::Extract(void)
{
   TTarArchive* archive = new TTarArchive(tar_file, tmSaveText);
   TTarDirRec tar_rec;
   archive->Reset();
   int files_count = 0;
   full_with_sql = false; // считаем, что архив не содержит sql dump пока не доказано обратное

   createDirs(restore_dir);
   PrintLn("Extracting files ...");
   ChangeCaption("Extracting files");

   while (archive->FindNext(tar_rec)) {
   	files_count ++;
      if (tar_rec.FileType == 5) { //directory
      	createDirs(restore_dir + "/" + tar_rec.Name);
         dirs->Add(tar_rec.Name);
         fileinfo *_dir = new fileinfo();
         _dir->getPermissions(PermissionString(tar_rec.Permissions));
         _dir->name = tar_rec.Name;

         dirs_info->Add((void*)_dir);
      } else { // File
      	PrintLn("\tExtracting file " + tar_rec.Name);
				// Особый случай: каталог var/* не содержится в архиве
				// var/backup/sqldump.sql.php - дамп БД
         if (tar_rec.Name == "var/backup/sqldump.sql.php") {
         	// архив содержит дамп БД
            // он не должен быть включен в список закачиваемых файлов
            createDirs(restore_dir + "/" + getDir(tar_rec.Name));
            full_with_sql = true;
         } else {
         	fileinfo *_file = new fileinfo();
            _file->getPermissions(PermissionString(tar_rec.Permissions));
            _file->name = tar_rec.Name;

            files->Add((void*)_file);
         }
         archive->ReadFile(restore_dir + "/" + tar_rec.Name);
      }
   }
   delete archive;
   PrintLn("Files sucessfully extracted");

	return true;
}
//---------------------------------------------------------------------------

bool restore_thr::UploadShopFiles(void)
{
   ChangeCaption("Restoring directory structure");
	PrintLn("Restoring directory structure ... ");
   if (!FtpCreateDirs(dirs)) {
   	Print("[FAILED]");
      return false;
   }
   Print("[OK]");

   ChangeCaption("Uploading files");
   PrintLn("Uploading files ... ");
   bool upload_success;
   for(int i = 0; i < files->Count; i++) {
   	fileinfo* _file = (fileinfo*) files->Items[i];
      PrintLn("\tUploading file " + _file->name + " ... ");
      upload_success = FtpUpload(restore_dir + "\\" + _file->name, _file->name);
      if (!upload_success) {
      	Print("[FAILED]");
         Synchronize(ShowConfirm);
         if (!skip) {
         	return false;
         }
      }
      if (upload_success) {
      	Print("[OK]");
      }
   }
   return true;
}
//---------------------------------------------------------------------------

bool restore_thr::RestorePermissions(void)
{
   bool result;
	if (restore_original_permissions) {
   	result = RestoreDefaultPermissions();
   } else {
   	result = RestoreTARPermissions();
   }
   return result;
}
//---------------------------------------------------------------------------

void __fastcall restore_thr::ShowConfirm(void)
{
	if (MessageDlg("File upload is failed. Continue upload files ?", mtWarning, TMsgDlgButtons() << mbYes << mbNo, 0) == mrNo) {
   	skip = false;
   } else {
   	skip = true;
   }
}
//---------------------------------------------------------------------------

void restore_thr::ModifyConfig(AnsiString restore_dir, SShop* __shop)
{
   AnsiString post;
   TFileStream* conf_file = new TFileStream(restore_dir + "/etc/config.php", fmOpenRead);
   char *buffer = new char[conf_file->Size+1];
   conf_file->Read(buffer, conf_file->Size);
   post = AnsiString(buffer, conf_file->Size);
   FileClose(conf_file->Handle);
   TRegExpr *regex = new TRegExpr();
   regex->Expression = "/hostspec[ ]*=[ ]*\"([^\"]*)\"/";
//   if (regex->Exec(post));
	if (!__shop->mysql_host.IsEmpty()) {
   	post = regex->Replace(post,"hostspec = \"" + __shop->mysql_host + "\"",false);
   }
   regex->Expression = "database[ ]*=[ ]*\"([^\"]*)\"";
   if (!__shop->mysql_db.IsEmpty()) {
   	post = regex->Replace(post,"database = \"" + __shop->mysql_db + "\"",false);
   }
   regex->Expression = "username[ ]*=[ ]*\"([^\"]*)\"";
   if (!__shop->mysql_login.IsEmpty()) {
   	post = regex->Replace(post,"username = \"" + __shop->mysql_login + "\"",false);
   }
   regex->Expression = "password[ ]*=[ ]*\"([^\"]*)\"";
   if (!__shop->mysql_password.IsEmpty()) {
   	post = regex->Replace(post,"password = \"" + __shop->mysql_password + "\"",false);
   }
   regex->ModifierG = false;
   regex->Expression = "^http://(.*)/(.*)$";
   AnsiString web_url;
   AnsiString web_dir;
   if (regex->Exec(__shop->http_url)) {
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

bool restore_thr::RestoreShop(void)
{
	tar_file = TARfile;

   if (!Extract()) {
   	return false;
   }
   if (mainWindow->backup_restore_frame->modify_config->Checked || movie_shop) {
   	PrintLn("Modifying configuration files...");
      ModifyConfig(restore_dir,shop);
      PrintLn("Modifying completed.");
   }
	if (!UploadShopFiles()) {
   	return false;
   }
   if (!RestorePermissions()) {
   	return false;
   }
   if (full_with_sql) {
   	if (!RestoreDB()) {
      	return false;
		}
   }
   return true;
}
//---------------------------------------------------------------------------

void restore_thr::RenameSQL(void)
{
	createDirs(restore_dir + "/var/backup");
	CopyFile(SQLfile.c_str(), (restore_dir + "/var/backup/sqldump.sql.php").c_str(), false);
}
//---------------------------------------------------------------------------

AnsiString restore_thr::CreateRestoreScript(void)
{
   AnsiString pwd = GeneratePassword();

   createDirs(restore_dir);
	// Creating db_restore.php from resourses
	TResourceStream* res;
	res = new TResourceStream((int)MainInstance, "DB_RESTORE", "SCRIPT");
	res->SaveToFile(restore_dir + "\\db_restore.php");
	delete res;

	// Insert new password into db_restore.php
	ReplaceInFile(restore_dir + "\\db_restore.php", "%PASSWORD%", pwd);

   return pwd;
}
//---------------------------------------------------------------------------

bool restore_thr::UploadRestoreScript(void)
{
	PrintLn("Uploading restore script ... ");
   ChangeCaption("Uploading restore script");
   if (!FtpUpload(restore_dir + "\\db_restore.php", "db_restore.php")) {
   	Print("[FAILED]");
      return false;
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool restore_thr::UploadSqlDump(void)
{
   PrintLn("Uploading SQL dump ... ");
   ChangeCaption("Uploading SQL dump");
	if (!FtpUpload(restore_dir + "/var/backup/sqldump.sql.php", "var/backup/sqldump.sql.php")) {
   	Print("[FALSE]");
      return false;
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool restore_thr::RunRestoreScript(AnsiString password)
{
	AnsiString URL = shop->http_url + "/db_restore.php?";
	_post->Clear();
   _post->Add("password=" + password);
   PrintLn("Restoring database ... ");
   ChangeCaption("Restoring database");
   if (!Post(URL) || _response.Pos("ERROR:") != 0) {
      Print("[FAILED]");
   	return false;
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool restore_thr::RestoreDB(void)
{
   AnsiString pwd;

	pwd = CreateRestoreScript();
	RenameSQL();

	if (!FtpCreateDir("var/backup", "0777")) {
   	return false;
   }
	if (!UploadRestoreScript()) {
   	return false;
   }
   if (!UploadSqlDump()) {
   	return false;
   }
   if (!RunRestoreScript(pwd)) {
   	return false;
   }

	return true;
}
//---------------------------------------------------------------------------

bool restore_thr::RestoreTARPermissions(void)
{
	PrintLn("Restoring permissions from archive ... ");
   ChangeCaption("Restoring permissions from archive");
	for(int i = 0; i < files->Count; i++) {
		fileinfo* _file = (fileinfo*) files->Items[i];
		if (!FtpCommand("SITE CHMOD " + _file->permissions + " " + _file->name)) {
      	Print("[FAILED]");
         return false;
      }
	}
	for(int i = 0; i < dirs_info->Count; i++) {
		fileinfo* _dir = (fileinfo*) dirs_info->Items[i];
		if (!FtpCommand("SITE CHMOD " + _dir->permissions + " " + _dir->name)) {
      	Print("[FAILED]");
         return false;
      }
	}
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool restore_thr::RestoreDefaultPermissions(void)
{
	int size = sizeof(DEFAULT_PERMISSIONS) / sizeof(DEFAULT_PERMISSIONS[0]);
	PrintLn("Restoring original permissions ... ");
   ChangeCaption("Restoring original permissions");
   for (int i = 0; i < size; i++) {
   	char* command = new char[MAX_PATH + 16];
   	sprintf (command, "SITE CHMOD %s %s", DEFAULT_PERMISSIONS[i][1], DEFAULT_PERMISSIONS[i][0]);
		if (!FtpCommand(AnsiString(command))) {
      	Print("[FAILED]");
         return false;
      }
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

AnsiString restore_thr::GeneratePassword(int pwd_len)
{
	// generates random string contains numbers and latin letters
	randomize();
	AnsiString result = "";
	int way;
	char chr;

	for (int i = 0; i < pwd_len; i++) {
		way = random(3);
		switch (way) {
			case 0: // numbers;
				result += IntToStr(random(10));
				break;
			case 1: // ABC...Z
				chr = random('Z' - 'A' + 1) + 'A';
				result += AnsiString(chr);
				break;
			case 2: // abc...z
				chr = random('z' - 'a' + 1) + 'a';
				result += AnsiString(chr);
				break;
		}
	}
	return result;
}
//---------------------------------------------------------------------------

void __fastcall restore_thr::Init(void)
{
	_display = _parent->__m_display;
   __parent = _parent;
   restore_dir = Application->ExeName.SubString(1, Application->ExeName.LastDelimiter("\\") - 1) + "\\temp_restore_files";
   if (!movie_shop) {
   	shop = mainWindow->shops->getByName(mainWindow->backup_restore_frame->backup_choose_shop->Items->Strings[mainWindow->backup_restore_frame->backup_choose_shop->ItemIndex]);
      full = mainWindow->backup_restore_frame->full;
      SQLfile = mainWindow->backup_restore_frame->restore_sql_name->Text;
      TARfile = mainWindow->backup_restore_frame->restore_arx_name->Text;
      restore_original_permissions = mainWindow->backup_restore_frame->restore_defaults->Checked;
   } else {
   	shop = mainWindow->shops->getByName(mainWindow->move_frame->dst->Items->Strings[mainWindow->move_frame->dst->ItemIndex]);
      full = true;
      TARfile = restore_dir + "\\move.tar";
      SQLfile = restore_dir + "\\move.sql";
      restore_original_permissions = true;
   }
   HttpInit();
}
