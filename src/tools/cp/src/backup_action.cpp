//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "backup_action.h"
#include "backup_thread.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma link "ThreadForm"
#pragma resource "*.dfm"

//---------------------------------------------------------------------------
Thread_backup* req_thread;
Tbackup_act *backup_act;
//---------------------------------------------------------------------------
__fastcall Tbackup_act::Tbackup_act(TComponent* Owner)
	: TProtoThreadForm(Owner)
{
	confirmText = "Are you sure you want to cancel backup procedure?";
   shop_name = mainWindow->backup_restore_frame->backup_choose_shop->Items->Strings[mainWindow->backup_restore_frame->backup_choose_shop->ItemIndex];
   shop = mainWindow->shops->getByName(shop_name);
   backup_dir = mainWindow->backup_restore_frame->backup_dir;
   full = mainWindow->backup_restore_frame->full;
   inc_sql = mainWindow->backup_restore_frame->sql_include->Checked;
}
//---------------------------------------------------------------------------
void __fastcall Tbackup_act::FormCreate(TObject *Sender)
{
   runing = true;
   req_thread = new Thread_backup(true);
   req_thread->_parent = this;
   req_thread->OnTerminate = OnThreadComplete;
   thr = (TThread*) req_thread;
   req_thread->Resume();
}
//---------------------------------------------------------------------------
void __fastcall Tbackup_act::FormClose(TObject *Sender,
      TCloseAction &Action)
{
/*
	if (req_thread->full_backup_file != NULL) {
   	delete req_thread->full_backup_file;
	}
*/   
}
//---------------------------------------------------------------------------

