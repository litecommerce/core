//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#include "extract_thr.h"
#pragma hdrstop

#include "extract.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tf_extract *f_extract;
//---------------------------------------------------------------------------
__fastcall Tf_extract::Tf_extract(TComponent* Owner)
	: TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tf_extract::_cancelClick(TObject *Sender)
{
   _main->Close();
}
//---------------------------------------------------------------------------

