//---------------------------------------------------------------------------

#include <vcl.h>
#include "constants.h"
#pragma hdrstop

//#include "move_shop_action.h"
#include "n_move_shop_action.h"
#include "MainWnd.h"
#include "f_Move.h"
#include "form_AddShop.h"

//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tmove_frm *move_frm;
//---------------------------------------------------------------------------
__fastcall Tmove_frm::Tmove_frm(TComponent* Owner)
   : TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tmove_frm::srcChange(TObject *Sender)
{
   if (src->Items->Strings[src->ItemIndex] == ADD_SHOP) {
        mainWindow->settings_frame->shops->ItemIndex = src->ItemIndex;
        mainWindow->frame_sender = 2;
   	mainWindow->btn_settingsClick(Sender);
   }
}
//---------------------------------------------------------------------------

void __fastcall Tmove_frm::dstChange(TObject *Sender)
{
   if (dst->Items->Strings[dst->ItemIndex] == ADD_SHOP) {
        mainWindow->settings_frame->shops->ItemIndex = dst->ItemIndex;
        mainWindow->frame_sender = 2;
   	mainWindow->btn_settingsClick(Sender);
   }
}
//---------------------------------------------------------------------------
void __fastcall Tmove_frm::moveClick(TObject *Sender)
{
   if (src->Items->Strings[src->ItemIndex] == dst->Items->Strings[dst->ItemIndex]) {
      Application->MessageBox("You are trying move your shop to the same destination. Please, select another destination.", "Error!", MB_OK|MB_ICONSTOP);
   	return;
   }
/*
	Tmove_shop_act* move_shop_wnd = new Tmove_shop_act(this);
   move_shop_wnd->ShowModal();
   delete move_shop_wnd;
*/
	Tmove_shop* wnd = new Tmove_shop(this);
   wnd->ShowModal();
   delete wnd;
}
//---------------------------------------------------------------------------

