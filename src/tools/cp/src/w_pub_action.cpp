//---------------------------------------------------------------------------

#include <vcl.h>
#include "MainWnd.h"
#include <LibTar.hpp>
#include "w_put_thread.h"
#include "FileUtil.h"
#pragma hdrstop

#include "w_pub_action.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tw_publich_action *w_publich_action;
bool published = false;
//---------------------------------------------------------------------------
__fastcall Tw_publich_action::Tw_publich_action(TComponent* Owner)
   : TForm(Owner)
{
   this->shop_name = mainWindow->wysiwyg_frame->wysiwyg_shop->Items->Strings[mainWindow->wysiwyg_frame->wysiwyg_shop->ItemIndex];
   shop = mainWindow->shops->getByName(shop_name);
   skin_dir = mainWindow->wysiwyg_frame->wysiwyg_dir->Text;

   modified = new TStringList();
   selected = new TStringList();
}
//---------------------------------------------------------------------------
void __fastcall Tw_publich_action::_okClick(TObject *Sender)
{
   Close();
}
//---------------------------------------------------------------------------
void __fastcall Tw_publich_action::FormCreate(TObject *Sender)
{
	files = new TStringList();
	get_files(skin_dir, files);
   for(int i = 0; i < files->Count; i++) {
   	AnsiString filename = files->Strings[i].Delete(1, skin_dir.Length() + 1);
      this->files_list->Items->Add(filename);
   }
	_modifiedClick(Sender);
}
//---------------------------------------------------------------------------
void __fastcall Tw_publich_action::_modifiedClick(TObject *Sender)
{
	this->files_list->Enabled = false;
   modified->Clear();
	_noneClick(Sender);
	timestamp = new TStringList();
   try {
   	timestamp->LoadFromFile(skin_dir + "\\timestamp.local");
   } catch (...) {
   	// нету такого файла - ну значит все измененные...
   }

   for(int i = 0; i < files->Count; i++) {
   	AnsiString filename = files->Strings[i].Delete(1, skin_dir.Length() + 1);
      int cur_time = FileAge(files->Strings[i]);
      AnsiString saved_time = "";
      AnsiString current_time = IntToStr(cur_time);
      for(int j = 0; j < timestamp->Count; j++) {
      	if(timestamp->Strings[j].Pos(">" + filename + "=") == 1) {
         	saved_time = timestamp->Strings[j].Delete(1, filename.Length() + 2);
         	break;
         }
      }
      if(current_time != saved_time) {
      	modified->Add(filename);
         int index = this->files_list->Items->IndexOf(filename);
         this->files_list->Checked[index] = true;
      }
   }
   this->files_list->Enabled = true;
}
//---------------------------------------------------------------------------
void __fastcall Tw_publich_action::_allClick(TObject *Sender)
{
	for (int i=0; i < this->files_list->Items->Count; i ++) {
		this->files_list->Checked[i] = true;
   }
}
//---------------------------------------------------------------------------
void __fastcall Tw_publich_action::_noneClick(TObject *Sender)
{
	for (int i=0; i < this->files_list->Items->Count; i ++) {
		this->files_list->Checked[i] = false;
   }
}
//---------------------------------------------------------------------------
void __fastcall Tw_publich_action::_publishClick(TObject *Sender)
{
   this->_modified->Visible = false;
   this->_all->Visible = false;
   this->_none->Visible = false;
   this->_publish->Visible = false;
   this->files_list->Visible = false;
    this->_ok->Visible = true;

   TTarWriter* tar = new TTarWriter("skins.tar", fmCreate);
   AnsiString fname;
	for (int i=0; i < this->files_list->Items->Count; i ++) {
		if (files_list->Checked[i] == true) {
         fname = files_list->Items->Strings[i];
   		tar->AddFile(this->skin_dir + "/" + fname, updateUPath(fname));
      }
   }
   delete tar;
   this->Caption = "Publishing files";

	w_put_thread* thread = new w_put_thread(true);
   thr = (TThread*)thread;
	thread->_parent = this;
   thread->OnTerminate = onEndThread;
   published=true;
   thread->Resume();
}
//---------------------------------------------------------------------------
void __fastcall Tw_publich_action::onEndThread(TObject* Sender)
{
	if (success) {
      // помечаем файлы как не измененные
   	AnsiString fname;
		for (int i=0; i < this->files_list->Items->Count; i ++) {
         fname = files_list->Items->Strings[i];
      	bool in_timestamp = false;
   		for (int j=0; j < timestamp->Count; j++) {
   			if(timestamp->Strings[j].Pos(">" + fname + "=") == 1) {
         		in_timestamp = true;
            	timestamp->Strings[j] = ">" + fname + "=" + FileAge(this->skin_dir + "\\" + fname);
      		}
         }
      	if (!in_timestamp) {
      		timestamp->Add(">" + fname + "=" + FileAge(this->skin_dir + "\\" + fname));
      	}
      }
   	timestamp->SaveToFile(skin_dir + "\\timestamp.local");

   	_ok->Enabled = true;
   }
}

void __fastcall Tw_publich_action::_cancelClick(TObject *Sender)
{
  if (published)
  {
   thr->Suspend();
   thr->OnTerminate = NULL;
   thr->Terminate();
  }
  published = false;
   Close();
}
//---------------------------------------------------------------------------


