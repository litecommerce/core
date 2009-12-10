//---------------------------------------------------------------------------

#include <vcl.h>
#include "constants.h"
#pragma hdrstop

#include "upgrade_thread.h"
#pragma package(smart_init)

__fastcall upgrade_thr::upgrade_thr(bool CreateSuspended)
   : WebThread(CreateSuspended)
{
   dirs = new TStringList();
   files = new TStringList();
   archive = NULL;
   manifest = new Manifest();
}
//---------------------------------------------------------------------------

void __fastcall upgrade_thr::Init()
{
   tgz_file = mainWindow->upgrade_frame->upg_file->Text;
   tar_file = "temp.tar";
   extract_dir = "upgrade";
   shop = mainWindow->shops->getByName(mainWindow->upgrade_frame->upg_shop->Items->Strings[mainWindow->upgrade_frame->upg_shop->ItemIndex]);
   _parent->SetResult(false);
   __parent = _parent;
   _display = _parent->__m_display;
   remove_list_filename = extract_dir + "\\upgrade\\remove-files";
   HttpInit();
}
//---------------------------------------------------------------------------

bool upgrade_thr::DoUpgrade(void)
{
   if (!VerifyVersion()) {
   	return false;
   }
   if (!Upload()) {
   	return false;
   }
   RemoveOldFiles();
   Upgrade();
   return true;
}
//---------------------------------------------------------------------------

bool upgrade_thr::Extract(void)
{
   ChangeCaption("Extracting archive");
	PrintLn ("Unzipping archive ... ");
	if (!UnZip(tgz_file, tar_file)) {
   	Print ("[FAILED]");
      return false;
   }
   Print ("[OK]");

 	archive = new TTarArchive(tar_file, tmSaveText);

 	TTarDirRec tar_rec;
 	archive->Reset();
 	int files_count = 0;

   PrintLn("Extracting files");
	while (archive->FindNext(tar_rec)) {
   	files_count ++;
		if (tar_rec.FileType == 5) { //directory
			createDirs(extract_dir + "/" + tar_rec.Name);
         if (dirs->IndexOf(tar_rec.Name) == -1) {
         	dirs->Add(tar_rec.Name);
         }
      } else { // File
      	PrintLn("\tExtracting file " + tar_rec.Name);
         files->Add(tar_rec.Name);
         AnsiString __dir = getDir(tar_rec.Name);
         if (dirs->IndexOf(__dir) == -1 && !__dir.IsEmpty()) {
         	dirs->Add(__dir);
         }
         createDirs(extract_dir + "/" + __dir);
         archive->ReadFile(extract_dir + "/" + tar_rec.Name);
		}
   }
   delete archive;
   archive = NULL;
   DeleteFile(tar_file);
   PrintLn("Files extracted sucessfully");
   return true;
}
//---------------------------------------------------------------------------

bool upgrade_thr::VerifyVersion(void)
{
	PrintLn("Comparing your store vresion with upgrade version ... ");
   AnsiString URL = shop->http_url + "/";

   if (manifest->fromver == "1.2.1" || manifest->fromver == "1.2.0") {
   	URL += "cart.php?";
   } else {
   	URL += "admin.php?";
   }

   _post->Add("target=upgrade");
   _post->Add("action=version");
   Post(URL);
   AnsiString installed_version = _response;

   if(!VerifyVersionResponse(installed_version)) {
   	PrintLn("Control panel was unable to fetch your LiteCommerce version");
      return false;
   }

   if (installed_version != manifest->fromver) {
   	PrintLn("ERROR: You have LiteCommerce version " + installed_version +
      ". This patch applies only to LiteCommerce version " + manifest->fromver);
      return false;
   }
   if (!ConfirmUpgrade()) {
      return false;
   }
   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

void __fastcall upgrade_thr::ShowConfirm(void)
{
	if (Application->MessageBox(
      		(ConfirmMessage).c_str(),
            "Confirmation",
            MB_YESNO|MB_ICONQUESTION) != IDYES) {
   	proceed = false;
   } else {
   	proceed = true;
   }
}
//---------------------------------------------------------------------------

bool upgrade_thr::Upload(void)
{
   PrintLn("Creating directory structure ... ");
   if (!FtpCreateDirs(dirs)) {
   	Print("[FAILED]");
      return false;
   }
   Print("[OK]");

	PrintLn("Uploading files ... ");
   for(int i = 0; i < files->Count; i++) {
		PrintLn("\tUploading file " + files->Strings[i] + " ... ");
		if (!FtpUpload(extract_dir + "\\" + files->Strings[i], files->Strings[i])) {
			Print("[FAILED]");
         return false;
		}
      Print("[OK]");
	}
   PrintLn("Files uploaded successfully");
   return true;
}
//---------------------------------------------------------------------------

void __fastcall upgrade_thr::Execute()
{
	Init();
   if (!Connect()) {
   	return;
   }
   if (!CdHome(false)) {
      PrintLn("* ERROR: Shop home dir " + shop->ftp_dir + " not found");
   	return;
   }
	if (!Extract()) {
   	return;
   }
   ParseManifest();
   switch (manifest->type) {
      // Upgrade
      case upgrade:
      if (!DoUpgrade()) {
   	   PrintLn("Upgrade failed. Press \"Cancel\" button to return to control panel.");
      } else {
   	   _parent->SetResult(true);
      }
      break;
      // HotFix
      case hotfix:
      if (!DoHotFix()) {
   	   PrintLn("Hotfix failed. Press \"Cancel\" button to return to control panel.");
      } else {
   	   _parent->SetResult(true);
      }
      break;
      // Unknown
      default:
   	   PrintLn("Given archive is neither upgrade no hotfix. Press \"Cancel\" button to return to control panel.");
      break;
   }
}
//---------------------------------------------------------------------------

__fastcall upgrade_thr::~upgrade_thr()
{
	delete dirs;
   delete files;
   rDeleteDir(extract_dir);
   try {
   	DeleteFile(tar_file);
   } catch (...) {
   }
   if (archive != NULL) {
   	delete archive;
   }
}
//---------------------------------------------------------------------------

void upgrade_thr::Upgrade(void)
{
   AnsiString URL;
   URL = shop->http_url + "/";
   if (manifest->fromver == "1.2.1" || manifest->fromver == "1.2.0") {
   	URL += "cart.php?";
   } else {
   	URL += "admin.php?";
   }

   URL += "target=upgrade&action=upgrade&from_ver=" +
   manifest->fromver +
   "&to_ver=" +
   manifest->tover;
   AnsiString text = "Control Panel uploaded all necessary upgrade files to your web server. To finish web part of upgrade Control Panel will now strart your default web browser. If for some reason your web browser is not started automatically please manually open the following URL: " + URL;
   PrintLn("");
   PrintLn(text);

	ShellExecute(NULL, "open", (URL).c_str(), "", "", 1);
}
//---------------------------------------------------------------------------

bool upgrade_thr::VerifyVersionResponse(AnsiString resp)
{
	TRegExpr *regex = new TRegExpr();
	regex->Expression = "^[0-9]\.[0-9]\.[0-9]$";
   bool ansver = regex->Exec(resp);
	delete regex;
	return ansver;
}
//---------------------------------------------------------------------------

bool upgrade_thr::RemoveOldFiles(void)
{
   if(manifest->remove_files->Count == 0) {
   	return true;
   }

   bool success = true;
   PrintLn("Removing old files ... ");
   for (int i = 0; i < manifest->remove_files->Count; i++) {
      if (manifest->remove_files->Strings[i].IsEmpty()) {
         continue;
      }
   	if (!FtpRemoveFile(manifest->remove_files->Strings[i])) {
         success = false;
      }
   }

   if (success) {
   	Print("[OK]");
      return true;
   } else {
   	Print("[FAILED]");
   	PrintLn("Warning: control panel was unable to remove some old files");
   	PrintLn("Please, remove those files manually:");
   	for (int i = 0; i < manifest->remove_files->Count; i++) {
      	PrintLn(manifest->remove_files->Strings[i]);
      }
   }
   return false;
}
//---------------------------------------------------------------------------

bool upgrade_thr::ConfirmUpgrade(void)
{
   ConfirmMessage = "This patch will upgrade your LiteCommerce from version " + manifest->fromver + " to version " + manifest->tover +	". Do you want to proceed?";
   Synchronize(ShowConfirm);
   if (!proceed) {
      PrintLn("Upgrade cancelled by user");
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool upgrade_thr::ConfirmHotfix(void)
{
   try {
      TStringList* tmp = new TStringList;
      tmp->LoadFromFile(extract_dir + "\\" + manifest->readme);
      for (int i = 0; i < tmp->Count; i ++) {
         ConfirmMessage += tmp->Strings[i] + "\r\n";
      }
      tmp->Clear();
      delete tmp;
   } catch(...) {}
   ConfirmMessage += "Are you sure you want to apply this hotfix?";
   Synchronize(ShowConfirm);

   if (!proceed) {
      PrintLn("Hotfix cancelled by user");
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool upgrade_thr::DoHotFix(void)
{
   if (!ConfirmHotfix()) {
      return false;
   }
   if (!Upload()) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

void upgrade_thr::ParseManifest(void)
{
   manifest->type = u_unknown;
   manifest->main = "";
   manifest->description = "";
   manifest->readme = "";
   manifest->fromver = "0.0.0";
   manifest->tover = "0.0.0";
   manifest->remove_files = new TStringList();
   try {
      TStringList* tmp = new TStringList;
      tmp->LoadFromFile(extract_dir + "\\MANIFEST");
         for (int i=0; i<tmp->Count; i++) {
            AnsiString str = tmp->Strings[i];
            int eq_pos = 0;
            if ((eq_pos = str.Pos("=")) == 0) {
               continue;
            }
            AnsiString name = str.SubString(1, eq_pos - 1).Trim();
            AnsiString value = str.SubString(eq_pos + 1, str.Length()).Trim();
            if (name == "type") {
               if (value == "upgrade") {
                  manifest->type = upgrade;
               } else if (value == "hotfix") {
                  manifest->type = hotfix;
               }
            } else if (name == "main") {
               manifest->main = value;
            } else if (name == "description") {
               manifest->description = value;
            } else if (name == "readme") {
               manifest->readme = value;
            } else if (name == "from_ver") {
               manifest->fromver = value;
            } else if (name == "to_ver") {
               manifest->tover = value;
            } else if (name == "remove_files") {
               TRegExpr* regex = new TRegExpr;
               regex->Expression = ";";
               regex->InputString = value;
               regex->Split(value, manifest->remove_files);
               for (int i = 0; i < manifest->remove_files->Count; i++) {
                  manifest->remove_files->Strings[i] = manifest->remove_files->Strings[i].Trim();
               }
               delete regex;
            }
         }
      delete tmp;
   } catch(...){}
}
//---------------------------------------------------------------------------

