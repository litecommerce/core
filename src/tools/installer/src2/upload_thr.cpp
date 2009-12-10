//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#include <regex/regexpr.hpp>
#pragma hdrstop

#include "upload_thr.h"
#pragma package(smart_init)
//---------------------------------------------------------------------------

TStringList* splitDirs(AnsiString dir) {
   TRegExpr *regex = new TRegExpr();
   TStringList *out = new TStringList();
   regex->Expression = "[\\\\\/]";
   regex->Split(dir, out);
   for (int i = 1; i < out->Count; i++) {
   	out->Strings[i] = out->Strings[i - 1] + "/" + out->Strings[i];
   }
   delete regex;
	return out;
}
//---------------------------------------------------------------------------

__fastcall upload_thread::upload_thread(bool CreateSuspended)
	: TThread(CreateSuspended)
{
   this->FreeOnTerminate = true;
	_main->upload_all = false;
   terminate = false;
   _main->ifc->OnWork = ifcWork;
}
//---------------------------------------------------------------------------

bool upload_thread::IsDir(AnsiString dir)
{
	AnsiString cur = _main->ifc->RetrieveCurrentDir();
   try {
   	_main->ifc->ChangeDir(dir);
   } catch (...) {
   	return false;
   }
   _main->ifc->ChangeDir(cur);
   return true;
}
//---------------------------------------------------------------------------

bool upload_thread::IsFile(AnsiString file)
{
   int pos = file.LastDelimiter("/");
   AnsiString filename = file.SubString(pos + 1, file.Length() - pos);
   AnsiString path = file.SubString(1, pos - 1);

   try {
      _main->ifc->List(NULL, path, true);
      for (int i = 0; i < _main->ifc->DirectoryListing->Count; i++) {
      	if (_main->ifc->DirectoryListing->Items[i]->FileName == filename &&
         	 _main->ifc->DirectoryListing->Items[i]->ItemType == ditFile ) {
			   return true;
         }
      }
   } catch (...) {
   	return false;
   }
   return false;
}
//---------------------------------------------------------------------------

bool upload_thread::CreateDir(AnsiString dirname)
{
	bool create_result = true;
   if (!IsDir(dirname)) {
   	try{
   		_main->ifc->MakeDir(dirname);
   	}catch (...) {
   		_main->WriteLog(" * Creating directory " + dirname + " failed\n");
      	create_result = false;
      }
   }
   if (!IsDir(dirname)) {
   		_main->WriteLog(" * Scanning directory " + dirname + " failed\n");
      create_result = false;
   }
   return create_result;
}
//---------------------------------------------------------------------------


void __fastcall upload_thread::Execute()
{
   _main->upload_run = true;
	setProgress(0);
   bool upload_result = true;

	_main->WriteLog("Connecting to server ... ");
	Connect();

   if (!_main->ifc->Connected()) {
      ShowMessage("Could not connect. Check your settings");
		_main->WriteLog("[FAILED]\n");
   	_main->WriteLog("-------------------------------------\n\n");
   	DoError();
   	return;
   }

	_main->WriteLog("[OK]\n");
   /**************************************************/
   // creating home dir
   TStringList *home = splitDirs(_main->ftp_dir);
   for(int i = 0; i < home->Count; i++) {
      if (!IsDir(home->Strings[i])) {
      	try {
      		_main->ifc->MakeDir(home->Strings[i]);
         } catch (...) {
         }
      }
   }
   delete home;

   _main->ifc->ChangeDir(_main->ftp_dir);

   // creating dirs
   /**************************************************/
	_main->WriteLog("Creating directories...\n");
   for(int i = 0; i < _main->dirs->Count; i++) {
   	AnsiString dirname = _main->dirs->Strings[i];
      bool create_result;
      int retry_result;
      _main->WriteLog(" " + dirname + "\n");

      setFileName("Creating directory " + dirname);
      do {
         if (!SmartReconnect(_main->ftp_dir)) {
         	create_result = false;
            continue;
         }
         create_result = CreateDir(dirname);
      } while (
      	!create_result &&
         (retry_result = MessageBox(NULL, ("Creating directory " + dirname +" failed. Try again?").c_str(), "Error", MB_YESNOCANCEL|MB_ICONQUESTION)) == IDYES
      		);
      if (!create_result && retry_result == IDCANCEL) {
        	DoError();
         return;
      }
   }

   // uploading files
   /**************************************************/
   _main->WriteLog("Uploading files...\n");
   for (int i = 0; i < _main->files->Count; i++) {
   	setFileProgress(0);
      AnsiString filename = _main->files->Strings[i];
      setFileName("Uploading file " + filename);
      _main->WriteLog(" " + filename + "\n");
      int retry_result;

      _main->skip = (! _main->upload_all);
      if (!_main->upload_all && IsFile(filename)) {
      	Synchronize(ShowConfirm);
         if (_main->skip) {
         	continue;
         }
      }

      do {
         upload_result = true;
         setFileProgress(0);
         setFileMax(GetFileLen(_main->temp_dir + "\\" + filename));

         if (!SmartReconnect(_main->ftp_dir)) {
         	upload_result = false;
            continue;
         }
      	try {
      		_main->ifc->Put(_main->temp_dir + "\\" + filename, filename, false);
      	} catch(...) {
      		_main->WriteLog("Error " + filename + "\n");
         	upload_result = false;
      	}
      } while(
      	!upload_result &&
         (retry_result = MessageBox(NULL, ("Uploading file " + filename +" failed. Try again?").c_str(), "Error", MB_YESNOCANCEL|MB_ICONQUESTION)) == IDYES
      		);

      if (!upload_result && retry_result == IDCANCEL) {
        	DoError();
         return;
      }
		setProgress(i);
   }
   /**************************************************/
   //Chmod
   _main->ifc->Quote("SITE CHMOD 777 .");
   _main->ifc->Quote("SITE CHMOD 0666 etc/config.php");
   _main->ifc->Quote("SITE CHMOD 0777 classes/modules");
   _main->ifc->Quote("SITE CHMOD 0666 cart.html");
   _main->ifc->Quote("SITE CHMOD 0666 shop_closed.html");
   /**************************************************/
   /**************************************************/
   //Disconnect
   try {
   	_main->ifc->Quit();
   } catch (...) {
   	_main->WriteLog("* ERROR: could not disconnect from the server\n");
   }
}

void __fastcall upload_thread::Connect(void)
{
   if (!_main->ifc->Connected()) {
   	_main->ifc->Username = _main->ftp_login;
   	_main->ifc->Password = _main->ftp_password;
   	_main->ifc->Host = _main->ftp_host;
   	_main->ifc->Port = StrToInt(_main->ftp_port);
   	_main->ifc->Passive = _main->passive;

   	try {
   		_main->ifc->Connect(true, 600);
   	} catch(...) {
   	}
   }
}
//---------------------------------------------------------------------------

void __fastcall upload_thread::_setFileName(void)
{
	_main->_upload->FileName->Caption = text;
}
//---------------------------------------------------------------------------

void __fastcall upload_thread::_setTotalProgress(void)
{
	_main->_upload->total_progress->Position = pos;
}
//---------------------------------------------------------------------------

void upload_thread::setFileName(AnsiString filename)
{
	text = filename;
   Synchronize(_setFileName);
}
//---------------------------------------------------------------------------

void upload_thread::setProgress(int pos)
{
	this->pos = pos;
   Synchronize(_setTotalProgress);
}
//---------------------------------------------------------------------------

void upload_thread::createDirs(void)
{
}
//---------------------------------------------------------------------------

int upload_thread::uploadFiles(void)
{
	return 0;
}
//---------------------------------------------------------------------------

void upload_thread::DoError(void)
{
	_main->ShowSettings();
   Suspend();
   Terminate();
}
//---------------------------------------------------------------------------

__fastcall upload_thread::~upload_thread()
{
}
//---------------------------------------------------------------------------

void __fastcall upload_thread::ShowConfirm(void)
{
  	Towerwrite* ow = new Towerwrite(_main->Owner);
   ow->ShowModal();
   delete ow;
}
//---------------------------------------------------------------------------

bool upload_thread::SmartReconnect(AnsiString init_path)
{
	if (_main->ifc->Connected()) {
   	return true;
   }

   Connect();

   if (init_path == NULL) {
   	return _main->ifc->Connected();
   }

   try {
   	_main->ifc->ChangeDir(init_path);
   } catch(...) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

void __fastcall upload_thread::_setFileMax(void)
{
	_main->_upload->file_progress->Max = file_max;
}
//---------------------------------------------------------------------------

void __fastcall upload_thread::_setFileProgress(void)
{
	_main->_upload->file_progress->Position = file_pos;
}
//---------------------------------------------------------------------------

void upload_thread::setFileProgress(int pos)
{
	file_pos = pos;
   Synchronize(_setFileProgress);
}
//---------------------------------------------------------------------------

void upload_thread::setFileMax(int max)
{
	file_max = max;
   Synchronize(_setFileMax);
}
//---------------------------------------------------------------------------

int upload_thread::GetFileLen(AnsiString name)
{
   struct stat statbuf;
	if (stat(name.c_str(), &statbuf) == -1) {
   	return 0;
   }
   return statbuf.st_size;
}
//---------------------------------------------------------------------------

void __fastcall upload_thread::ifcWork(TObject *Sender, TWorkMode AWorkMode,
      const int AWorkCount)
{
	setFileProgress(AWorkCount);
}
//---------------------------------------------------------------------------

