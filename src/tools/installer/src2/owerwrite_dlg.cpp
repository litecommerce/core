//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "owerwrite_dlg.h"
#include "u_main.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Towerwrite *owerwrite;
//---------------------------------------------------------------------------
__fastcall Towerwrite::Towerwrite(TComponent* Owner)
	: TForm(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Towerwrite::_skipClick(TObject *Sender)
{
	_main->skip = true;
   Close();
}
//---------------------------------------------------------------------------
void __fastcall Towerwrite::_owerwiteClick(TObject *Sender)
{
	_main->skip = false;
   Close();
}
//---------------------------------------------------------------------------
void __fastcall Towerwrite::_qwerwrite_allClick(TObject *Sender)
{
	_main->upload_all = true;
   _main->skip = false;
   Close();
}
//---------------------------------------------------------------------------
void __fastcall Towerwrite::_cancelClick(TObject *Sender)
{
   _main->Close();
   Close();
}
//---------------------------------------------------------------------------
void __fastcall Towerwrite::FormCreate(TObject *Sender)
{
	Left = _main->Left + (_main->Width - Width)/2;
	Top = _main->Top + 75;
}
//---------------------------------------------------------------------------

