//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "n_move_shop_action.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma link "ThreadForm"
#pragma resource "*.dfm"
Tmove_shop *move_shop;
//---------------------------------------------------------------------------
__fastcall Tmove_shop::Tmove_shop(TComponent* Owner)
	: TProtoThreadForm(Owner)
{
	confirmText = "Are you want to cancel moving your shop?";
}
//---------------------------------------------------------------------------

void __fastcall Tmove_shop::FormCreate(TObject *Sender)
{
   runing = true;
	Thread_backup* req_thread;
   req_thread = new Thread_backup(true, true, true);
   req_thread->_parent = this;
   req_thread->OnTerminate = OnBackupComplete;
   thr = (TThread*) req_thread;
   req_thread->Resume();
}
//---------------------------------------------------------------------------

void __fastcall Tmove_shop::OnBackupComplete(TObject *Sender)
{
	if (!this->success) {
   	runing = false;
      return;
   }
   delete thr;
   this->__bytes->Caption = "";
   restore_thr *restore = new restore_thr(true, true);
   restore->_parent = this;
   restore->OnTerminate = OnRestoreComplete;
   thr = (TThread*) restore;
   restore->Resume();
}
//---------------------------------------------------------------------------

void __fastcall Tmove_shop::OnRestoreComplete(TObject *Sender)
{
	runing = false;
	__b_ok->Enabled = this->success;
	if (!this->success) {
   	__m_display->Lines->Add("Control panel was unable to move your shop. Press \"Cancel\" button to continue.");
   } else {
   	__m_display->Lines->Add("Your shop moved successefully. Press \"Ok\" button to continue.");
   }
}
//---------------------------------------------------------------------------

