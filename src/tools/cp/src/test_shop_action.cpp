//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "test_shop_action.h"
#include "test_shop_thread.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma link "ThreadForm"
#pragma resource "*.dfm"
Ttest_shop *test_shop;
//---------------------------------------------------------------------------
__fastcall Ttest_shop::Ttest_shop(TComponent* Owner)
	: TProtoThreadForm(Owner)
{
	confirmText = "Are you sure you want to cancel the test?";
}
//---------------------------------------------------------------------------
void __fastcall Ttest_shop::FormCreate(TObject *Sender)
{
   runing = true;
	TetShopThread* test_thread = new TetShopThread(true);
   thr = (TThread*)test_thread;
   test_thread->_parent = this;
   test_thread->OnTerminate = OnThreadComplete;
   test_thread->Resume();
}
//---------------------------------------------------------------------------

