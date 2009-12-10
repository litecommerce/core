//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "extract_thr.h"
#include "u_main.h"

#pragma package(smart_init)
#include <zlib.h>
#include <regex/regexpr.hpp>

bool ungzip(AnsiString infile, AnsiString outfile)
{
	int buffer_size = 512;

   TFileStream* fs = new TFileStream(outfile, fmCreate);

   System::Byte *buffer = new System::Byte[buffer_size];
   gzFile gzf = gzopen(infile.c_str(), "rb");
   if (!gzf) {
   	delete fs;
   	delete buffer;
      DeleteFile(outfile);
      return false;
   }
   int result;
   while((result = gzread(gzf, buffer, buffer_size)) != 0) {
   	fs->Write((void*)buffer, result);
   }
   gzclose(gzf);

   delete fs;
   delete buffer;
   return true;
}

bool createDirs(AnsiString dir)
{
   AnsiString c_dir = GetCurrentDir();
   TRegExpr *regex = new TRegExpr();
   TStringList *out = new TStringList();
   regex->Expression = "[\\\\\/]";
   regex->Split(dir, out);

   for (int i=0; i<out->Count; i++) {
      if (out->Strings[i].Pos(":") != 0) {
         SetCurrentDir(out->Strings[i] + "\\");
      } else {
      	try {
         	CreateDirectory(out->Strings[i].c_str(), NULL);
         } catch(...) {}
         if (!SetCurrentDir(out->Strings[i] + "\\")) {
            return false;
         }
      }
   }
   delete out;
   delete regex;
   SetCurrentDir(c_dir);
   return true;
}

//---------------------------------------------------------------------------

__fastcall extract_thread::extract_thread(bool CreateSuspended)
	: TThread(CreateSuspended)
{
	files_count = 0;
   pos = 0;
}
//---------------------------------------------------------------------------
void __fastcall extract_thread::Execute()
{
	_main->files_size = 0;
	_main->files_count = 0;
	addString("Extracting files, please wait...");
	setCaption("  LiteCommerce installer - Extracting files");
   //Log
   _main->WriteLog("Unziping file " + _main->archive_name + "\n");
   //End
	if (!ungzip(_main->archive_name, _main->tar_name)) {
   	ShowMessage("Installation archive is invalid or was not found");
//      _main->cancel = true;
   	//Log
   	_main->WriteLog("*** ERROR: could not extract archive ***\n");
   	//End
      return;
   }
   getFilesCount();
   Synchronize(setProgressMax);
/***********************************/
   	createDirs(_main->temp_dir);
      archive = new TTarArchive(_main->tar_name, tmSaveText);
      TTarDirRec tar_rec;
      archive->Reset();
		//Log
   	_main->WriteLog("Extracting tar file " + _main->tar_name + ":\n");
   	//End

      while (archive->FindNext(tar_rec)) {
         if (tar_rec.FileType == 5) { //directory
            createDirs(_main->temp_dir + "/" + tar_rec.Name);
            _main->dirs->Add(tar_rec.Name.SubString(1, tar_rec.Name.Length() - 1));
         } else {
            archive->ReadFile(_main->temp_dir + "/" + tar_rec.Name);
            addString("Extracting file " + tar_rec.Name);
            _main->files->Add(tar_rec.Name);
            _main->files_size += tar_rec.Size;
				_main->files_count ++;
         	Synchronize(_setProgressPos);
            _main->WriteLog(" " + _main->temp_dir + "/" + tar_rec.Name + "\n");
            pos ++;
         }
      }
      delete archive;
      DeleteFile(_main->tar_name);
   	_main->WriteLog("-------------------------------------\n\n");

/***********************************/
	_main->dirs->Sort();
	addString("Files successefuly extracted");
	setCaption("  LiteCommerce installer - Files successefuly extracted");
}
//---------------------------------------------------------------------------


void __fastcall extract_thread::_setCaption()
{
	_main->Caption = caption;
}

void extract_thread::setCaption(AnsiString caption)
{
	this->caption = caption;
   Synchronize(_setCaption);
}

void __fastcall extract_thread::_addString()
{
   _main->_extract->FileName->Caption = text;
}

void extract_thread::addString(AnsiString text)
{
	this->text = text;
   Synchronize(_addString);
}

void extract_thread::getFilesCount(void)
{
      TTarArchive* archive = new TTarArchive(_main->tar_name, tmSaveText);
      TTarDirRec tar_rec;
      archive->Reset();
      while (archive->FindNext(tar_rec)) {
         if (tar_rec.FileType != 5) {
         	files_count ++;
         }
      }
      delete archive;
}

void __fastcall extract_thread::setProgressMax(void)
{
   if (files_count > 0) {
		_main->_extract->FilesProgress->Max = files_count - 1;
   }
	_main->_extract->FilesProgress->Min = 0;
}

void __fastcall extract_thread::_setProgressPos(void)
{
	_main->_extract->FilesProgress->Position = pos;
}

void extract_thread::setProgressPos(int pos)
{
	this->pos = pos;
	Synchronize(_setProgressPos);
}
