//---------------------------------------------------------------------------
#include <vcl.h>
#include "MainWnd.h"
#include "constants.h"
#include "FileUtil.h"
#pragma hdrstop

#include "f_Wysiwyg.h"
#include "form_AddShop.h"
#include "wysiwyg_action.h"
#include "w_pub_action.h"
#include "edit_file.h"
#include <io.h>
//#include <shlobj.h>
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Twysiwyg_frm *wysiwyg_frm;
//---------------------------------------------------------------------------
__fastcall Twysiwyg_frm::Twysiwyg_frm(TComponent* Owner)
   : TFrame(Owner)
{
	action_downloaded = false;
	TRegistry* reg = new TRegistry;
   reg->OpenKey("Software\\LiteCommerce\\WYSWYG",true);
   if (!reg->ValueExists("wyswyg_dir"))
           this->wysiwyg_dir->Text = "";
   else
           this->wysiwyg_dir->Text = reg->ReadString("wyswyg_dir");
   if (this->wysiwyg_dir->Text != "")
        templates->Enabled = true;
   reg->CloseKey();
   delete reg;
}
//---------------------------------------------------------------------------
void __fastcall Twysiwyg_frm::wysiwyg_shopChange(TObject *Sender)
{
  mainWindow->settings_frame->shops->ItemIndex = wysiwyg_shop->ItemIndex;
   if (wysiwyg_shop->Items->Strings[wysiwyg_shop->ItemIndex] == ADD_SHOP) {
      mainWindow->disable_all_view();
      mainWindow->settings_frame->Align = alClient;
      mainWindow->settings_frame->Visible = true;
      mainWindow->settings_frame->shops->ItemIndex = mainWindow->settings_frame->shops->Items->Count - 1;
      mainWindow->frame_sender = 1;
      mainWindow->settings_frame->shopsChange(Sender);
   }
}
//---------------------------------------------------------------------------


void __fastcall Twysiwyg_frm::select_dirClick(TObject *Sender)
{
  AnsiString Dir = GetCurrentDir();
  if (SelectDirectory(Dir, TSelectDirOpts() << sdAllowCreate << sdPerformCreate << sdPrompt,1000))
        mainWindow->wysiwyg_frame->wysiwyg_dir->Text = Dir;
  if (!FileExists("timestamp.local")) this->templates->Enabled = false;
}
//---------------------------------------------------------------------------

void __fastcall Twysiwyg_frm::download_designClick(TObject *Sender)
{
   if (wysiwyg_dir->Text == "") {
		AnsiString message = "You must specify directory to download design to";
		MessageBox(NULL, message.c_str(), "Warning!", MB_OK|MB_ICONEXCLAMATION);
      return;
   }

   if (!DirectoryExists(wysiwyg_dir->Text)) {
   	createDirs(wysiwyg_dir->Text);
   }

   if (wysiwyg_shop->Items->Strings[wysiwyg_shop->ItemIndex] == ADD_SHOP) {
		AnsiString message = "Please select shop";
		MessageBox(NULL, message.c_str(), "Warning!", MB_OK|MB_ICONEXCLAMATION);
      return;
   }
   TAction_wysiwyg_get* w_get_wnd = new TAction_wysiwyg_get(this);
   w_get_wnd->ShowModal();
   delete w_get_wnd;
   if (mainWindow->wysiwyg_frame->wysiwyg_dir->Text != "") templates->Enabled = true;
   //action_downloaded = true;
}
//---------------------------------------------------------------------------

void __fastcall Twysiwyg_frm::publish_designClick(TObject *Sender)
{
   if (wysiwyg_dir->Text == "") {
		AnsiString message = "You must specify directory to publish skin from";
		MessageBox(NULL, message.c_str(), "Error!", MB_OK|MB_ICONERROR);
      return;
   }
	Tw_publich_action* frm = new Tw_publich_action(this);
   frm->ShowModal();
   delete frm;
}
//---------------------------------------------------------------------------
void __fastcall Twysiwyg_frm::templatesClick(TObject *Sender)
{
   /*
   if (mainWindow->html_editor == "") {
   	mainWindow->disable_all_view();
      mainWindow->settings_frame->Align = alClient;
      mainWindow->settings_frame->Visible = true;
      mainWindow->settings_frame->settings_pages->ActivePageIndex = 2;
      return;
   }
   */
   TListItem *item;
   TRegistry* reg = new TRegistry;
   try {
   	reg->OpenKey("Software\\LiteCommerce\\WYSWYG",true);
   	reg->WriteString("wyswyg_dir",mainWindow->wysiwyg_frame->wysiwyg_dir->Text);
   	reg->CloseKey();
   	select_edit_file->files->Items->Clear();
   } __finally {
   	delete reg;
   }
   if (action_downloaded)
   {
        for (int i = 0; i < mainWindow->skin_files->Count; i++) {

              item = select_edit_file->files->Items->Add();
              item->Caption = mainWindow->skin_files->Strings[i];
           }
   }
        else
   {
        SetCurrentDir(this->wysiwyg_dir->Text);
        TSearchRec sk_files;
        if (FindFirst(this->wysiwyg_dir->Text+"\\*.*", faVolumeID, sk_files) == 0)
          {
            do
              {
                      item = select_edit_file->files->Items->Add();
                      item->Caption = sk_files.Name;
              }
          while (FindNext(sk_files) == 0);
          FindClose(sk_files);
          }
   }
   select_edit_file->files->Items->Item[0]->Selected = true;
   for (int i = 0; i < select_edit_file->files->Items->Count ; i++)
        if (select_edit_file->files->Items->Item[i]->Caption == "Main.html")
           select_edit_file->files->Items->Item[i]->Selected = true;
   select_edit_file->Show();
}
//---------------------------------------------------------------------------

