//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#pragma hdrstop

#include "completed.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tf_completed *f_completed;
//---------------------------------------------------------------------------
__fastcall Tf_completed::Tf_completed(TComponent* Owner)
	: TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tf_completed::_cancelClick(TObject *Sender)
{
	_main->Close();
}
//---------------------------------------------------------------------------
void __fastcall Tf_completed::_continueClick(TObject *Sender)
{
	AnsiString url = _main->http_address + "/install.php?";
	ShellExecute(NULL, "Open", url.c_str(), NULL, "", NULL);
   try {
   	rDeleteDir(_main->temp_dir);
   } catch (...) {
   }
   _main->remove_files = false;
}
//---------------------------------------------------------------------------
