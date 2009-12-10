//---------------------------------------------------------------------------
#ifndef f_UpgradeH
#define f_UpgradeH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <Buttons.hpp>
#include <Dialogs.hpp>
#include "constants.h"
#include <ComCtrls.hpp>
//---------------------------------------------------------------------------
class Tupgrade_frm : public TFrame
{
__published:	// IDE-managed Components
   TPanel *upgrade_bkgr;
   TPanel *Panel1;
   TOpenDialog *upg_open;
   TPanel *Panel4;
   TComboBox *upg_shop;
   TLabel *Label1;
   TEdit *upg_file;
   TSpeedButton *SpeedButton1;
   TButton *btn_upgrade;
   TLabel *Label2;
   void __fastcall SpeedButton1Click(TObject *Sender);
   void __fastcall btn_upgradeClick(TObject *Sender);
   void __fastcall btn_hotfixClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
   __fastcall Tupgrade_frm(TComponent* Owner);
   upgrade_type type;
};
//---------------------------------------------------------------------------
extern PACKAGE Tupgrade_frm *upgrade_frm;
//---------------------------------------------------------------------------
#endif
