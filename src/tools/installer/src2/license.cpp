//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#pragma hdrstop

#include "license.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tf_license *f_license;
//---------------------------------------------------------------------------
__fastcall Tf_license::Tf_license(TComponent* Owner)
	: TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tf_license::_cancelClick(TObject *Sender)
{
//	_main->Exit();
	_main->Close();
}
//---------------------------------------------------------------------------
void __fastcall Tf_license::_agreeClick(TObject *Sender)
{
  	_continue->Enabled = _agree->Checked;
}
//---------------------------------------------------------------------------
void __fastcall Tf_license::_continueClick(TObject *Sender)
{
	_main->ShowSettings();	
}
//---------------------------------------------------------------------------
