//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#pragma hdrstop

#include "upload.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tf_upload *f_upload;

//---------------------------------------------------------------------------
__fastcall Tf_upload::Tf_upload(TComponent* Owner)
	: TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tf_upload::_cancelClick(TObject *Sender)
{
	_main->t_upload->Suspend();
   if (MessageBox(NULL, "Are you sure you want to cancel upload?", "Confirm cancel upload", MB_YESNO|MB_ICONQUESTION) == IDYES) {
      _main->t_upload->OnTerminate = NULL;
      _main->t_upload->Terminate();
      delete _main->t_upload;
      _main->ShowSettings();
   }
   _main->t_upload->Resume();
}
//---------------------------------------------------------------------------

