//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "splash.h"
#include "u_main.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tf_splash *f_splash;
//---------------------------------------------------------------------------
__fastcall Tf_splash::Tf_splash(TComponent* Owner)
	: TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tf_splash::_cancelClick(TObject *Sender)
{
	//_main->Exit();
	_main->Close();
}
//---------------------------------------------------------------------------
void __fastcall Tf_splash::_continueClick(TObject *Sender)
{
	_main->ShowExtract();	
}
//---------------------------------------------------------------------------
