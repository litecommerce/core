//---------------------------------------------------------------------------

#include <vcl.h>
#include <RegExpr.hpp>
#include <LibTar.hpp>
#include <FileCtrl.hpp>
#include "FileUtil.h"
#pragma hdrstop

#include "w_get_thread.h"
#include "MainWnd.h"
#pragma package(smart_init)
//---------------------------------------------------------------------------

__fastcall Thread_w_get::Thread_w_get(bool CreateSuspended)
   : WebThread(CreateSuspended)
{
	files = new TStringList();
	archive = NULL;
   tar_file = NULL;
	timestamp = NULL;
   remove_tar = false;
}
//---------------------------------------------------------------------------

void __fastcall Thread_w_get::Execute()
{
	Init();
   if (!Connect()) {
   	return;
   }
   if (!CdHome(false)) {
      PrintLn("* ERROR: Shop home dir " + shop->ftp_dir + " not found");
   	return;
   }
   mainWindow->skin_files->Clear();
   bool result = GetSkinDecoration();
   Clear();
  	PrintLn(" ");
   if (!result) {
   	PrintLn("Skins decoration failed. Press \"Cancel\" button to return to control panel.");
   } else {
   	PrintLn("Skins decoration fetched successefully. Press \"Ok\" button to return to control panel.");
   }
   _parent->SetResult(result);
   mainWindow->wysiwyg_frame->action_downloaded = result;
}
//---------------------------------------------------------------------------

__fastcall Thread_w_get::~Thread_w_get()
{
	Clear();
}
//---------------------------------------------------------------------------

void Thread_w_get::SaveTimeStamp()
{
	AnsiString path = _parent->download_dir;
   timestamp = new TFileStream(path + "\\" + "timestamp.local", fmCreate);

   for(int i = 0; i < files->Count; i++) {
   	AnsiString filename = files->Strings[i];
      int age = FileAge(path + "\\" + filename);
      timestamp->Write((">" + filename + "=").c_str(), filename.Length() + 2);
      timestamp->Write(IntToStr(age).c_str(), IntToStr(age).Length());
      timestamp->Write("\n", 1);
   }

   delete timestamp;
   timestamp = NULL;
}
//---------------------------------------------------------------------------

void __fastcall Thread_w_get::Init()
{
	__parent = _parent;
   _display = _parent->__m_display;
   shop = _parent->shop;
   skins_dir = _parent->download_dir;
   HttpInit();
}
//---------------------------------------------------------------------------

bool Thread_w_get::Extract(void)
{
   PrintLn("- Extracting skins ... ");
   try {
      archive = new TTarArchive(skins_dir + "/skins.tar", tmSaveText);
      remove_tar = true;
   } catch (...) {
      Print("[FAILED]");
      return false;
   }
   TTarDirRec tar_rec;
   archive->Reset();
   while (archive->FindNext(tar_rec)) {
      AnsiString _path = updatePath(skins_dir + "/" + tar_rec.Name);
      if (tar_rec.FileType == 5) { //directory
         createDirs(_path);
      } else {
         AnsiString filename = getFileName(tar_rec.Name);
         AnsiString localPath = _path;
         if (int _pos = localPath.Pos(skins_dir) != 0) {
            localPath = localPath.Delete(_pos, skins_dir.Length() + 1);
         }
         files->Add(localPath);

         if (localPath.Pos("\\") == 0 && localPath.Pos("/") == 0) {
         	mainWindow->skin_files->Add(localPath);
         }
         archive->ReadFile(_path);
      }
   }
   delete archive;
   archive = NULL;
   DeleteFile(skins_dir + "/skins.tar");
   remove_tar = false;
 	Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool Thread_w_get::DownloadSkins(void)
{
   AnsiString URL = shop->http_url + "/admin.php?";
   bool success = true;
   PrintLn("- Downloading skins ... ");
	tar_file = new TFileStream(skins_dir + "/skins.tar", fmCreate);
   _post->Clear();
   _post->Add("target=files");
   _post->Add("action=tar_skins");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password=" + shop->admin_password);
   if (!Post(URL, tar_file)) {
   	Print("[FAILED]");
      success = false;
   } else {
   	Print("[OK]");
   }
   delete tar_file;
   tar_file = NULL;
   return success;
}
//---------------------------------------------------------------------------

bool Thread_w_get::PrepareSkins(void)
{
	PrintLn("- Preparing skin decoration ... ");
   AnsiString URL = shop->http_url + "/admin.php?";
   _post->Clear();
   _post->Add("target=wysiwyg");
   _post->Add("action=export");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password=" + shop->admin_password);
   _post->Add("mode=cp");

   if (!Post(URL) || _response.UpperCase() != "OK") {
   	Print("[FAILED]");
      return false;
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool Thread_w_get::GetSkinDecoration(void)
{
	if (!PrepareSkins()) {
   	return false;
   }
   if (!DownloadSkins()) {
   	return false;
   }
   if (!Extract()) {
   	return false;
   }
   SaveTimeStamp();
   return true;
}
//---------------------------------------------------------------------------

void Thread_w_get::Clear(void)
{
   if (tar_file != NULL) {
   	delete tar_file;
      tar_file = NULL;
   }

   if (archive != NULL) {
   	delete archive;
      archive = NULL;
   }

   if (timestamp != NULL) {
   	delete timestamp;
      timestamp = NULL;
   }

   if (files != NULL) {
   	delete files;
   	files = NULL;
   }

   if (remove_tar) {
   	try {
			DeleteFile(skins_dir + "/skins.tar");
      } catch(...) {}
      remove_tar = false;
   }
}
//---------------------------------------------------------------------------

