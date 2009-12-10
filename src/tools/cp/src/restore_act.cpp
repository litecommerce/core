//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "restore_act.h"
#include "restore_thread.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma link "ThreadForm"
#pragma resource "*.dfm"
Taction_restore *action_restore;
//---------------------------------------------------------------------------
__fastcall Taction_restore::Taction_restore(TComponent* Owner)
	: TProtoThreadForm(Owner)
{
	confirmText = "Are you sure you want to cancel restoring?";
   this->shop_name = mainWindow->backup_restore_frame->backup_choose_shop->Items->Strings[mainWindow->backup_restore_frame->backup_choose_shop->ItemIndex];
   shop = mainWindow->shops->getByName(shop_name);
   full = mainWindow->backup_restore_frame->full;
   SQLfile = mainWindow->backup_restore_frame->restore_sql_name->Text;
   TARfile = mainWindow->backup_restore_frame->restore_arx_name->Text;
   this->runing = true;
}
//---------------------------------------------------------------------------
void __fastcall Taction_restore::FormCreate(TObject *Sender)
{
   restore_thr *restore = new restore_thr(true);
   restore->_parent = this;
   restore->OnTerminate = OnThreadComplete;
   thr = (TThread*) restore;
   restore->Resume();
}
//---------------------------------------------------------------------------

