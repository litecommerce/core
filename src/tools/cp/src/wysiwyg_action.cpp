//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "wysiwyg_action.h"
#include "w_get_thread.h" 
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma link "ThreadForm"
#pragma resource "*.dfm"
TAction_wysiwyg_get *Action_wysiwyg_get;
//---------------------------------------------------------------------------
__fastcall TAction_wysiwyg_get::TAction_wysiwyg_get(TComponent* Owner)
	: TProtoThreadForm(Owner)
{
	confirmText = "Are you sure you want to cancel downloading HTML design?";
   shop = mainWindow->shops->getByName(mainWindow->wysiwyg_frame->wysiwyg_shop->Items->Strings[mainWindow->wysiwyg_frame->wysiwyg_shop->ItemIndex]);
   download_dir = mainWindow->wysiwyg_frame->wysiwyg_dir->Text;
}
//---------------------------------------------------------------------------
void __fastcall TAction_wysiwyg_get::FormCreate(TObject *Sender)
{
   runing = true;
   Thread_w_get* get_thread = new Thread_w_get(true);
   get_thread->_parent = this;
   get_thread->OnTerminate = OnThreadComplete;
   thr = (TThread*) get_thread;
   get_thread->Resume();
}
//---------------------------------------------------------------------------
