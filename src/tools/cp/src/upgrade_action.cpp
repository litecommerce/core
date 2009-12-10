//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "upgrade_action.h"
#include "upgrade_thread.h"

//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma link "ThreadForm"
#pragma resource "*.dfm"
Tupgrade_act *upgrade_act;
//---------------------------------------------------------------------------
__fastcall Tupgrade_act::Tupgrade_act(TComponent* Owner)
	: TProtoThreadForm(Owner)
{
 	confirmText = "Are you sure you want to cancel ugrade/hotfix?";
}
//---------------------------------------------------------------------------
void __fastcall Tupgrade_act::FormCreate(TObject *Sender)
{
   runing = true;
   upgrade_thr* u_thr;

   u_thr = new upgrade_thr(true);
   u_thr->_parent = this;
   u_thr->OnTerminate = OnThreadComplete;
   thr = (TThread*) u_thr;
   u_thr->Resume();
}
//---------------------------------------------------------------------------
