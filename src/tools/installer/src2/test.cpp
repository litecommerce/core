//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#pragma hdrstop

#include "test.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tf_test *f_test;
//---------------------------------------------------------------------------
__fastcall Tf_test::Tf_test(TComponent* Owner)
	: TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tf_test::_backClick(TObject *Sender)
{
	_main->ShowSettings();
}
//---------------------------------------------------------------------------

void __fastcall Tf_test::_continueClick(TObject *Sender)
{
	_main->ShowUpload();
}
//---------------------------------------------------------------------------

void __fastcall Tf_test::_cancelClick(TObject *Sender)
{
	_main->t_test->Suspend();
   _main->t_test->OnTerminate = NULL;
   _main->t_test->Terminate();
   delete _main->t_test;
   _main->ShowSettings();
}
//---------------------------------------------------------------------------

