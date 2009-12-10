//---------------------------------------------------------------------------

#include <vcl.h>
#include "constants.h"
#pragma hdrstop

#include "f_Upgrade.h"
#include "upgrade_action.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tupgrade_frm *upgrade_frm;
//---------------------------------------------------------------------------
__fastcall Tupgrade_frm::Tupgrade_frm(TComponent* Owner)
   : TFrame(Owner)
{
}
//---------------------------------------------------------------------------
void __fastcall Tupgrade_frm::SpeedButton1Click(TObject *Sender)
{
   if (upg_open->Execute()) {
      upg_file->Text = upg_open->FileName;
   }
}
//---------------------------------------------------------------------------

void __fastcall Tupgrade_frm::btn_upgradeClick(TObject *Sender)
{
   type = upgrade;
   if (this->upg_shop->Items->Strings[upg_shop->ItemIndex] == ADD_SHOP) {
      return;
   }
   if (upg_file->Text.IsEmpty()) {
		AnsiString message = "Please, choose upgrade archive.";
		MessageBox(NULL, message.c_str(), "Warning!", MB_OK|MB_ICONEXCLAMATION);
      return;
   }
   Tupgrade_act *upgrade_act;
   upgrade_act = new Tupgrade_act(this);
   upgrade_act->ShowModal();
   delete upgrade_act;
}
//---------------------------------------------------------------------------


void __fastcall Tupgrade_frm::btn_hotfixClick(TObject *Sender)
{
   type = hotfix;
   if (this->upg_shop->Items->Strings[upg_shop->ItemIndex] == ADD_SHOP) {
      return;
   }
   if (upg_file->Text.IsEmpty()) {
		AnsiString message = "Please, choose hotfix archive.";
		MessageBox(NULL, message.c_str(), "Warning!", MB_OK|MB_ICONEXCLAMATION);
      return;
   }
   Tupgrade_act *upgrade_act;
   upgrade_act = new Tupgrade_act(this);
   upgrade_act->ShowModal();
   delete upgrade_act;
}
//---------------------------------------------------------------------------

