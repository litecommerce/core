//---------------------------------------------------------------------------

#ifndef backup_actionH
#define backup_actionH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include "ThreadForm.h"
#include <ExtCtrls.hpp>

#include "MainWnd.h"
#include "f_BackupMain.h"
//---------------------------------------------------------------------------
class Tbackup_act : public TProtoThreadForm
{
__published:	// IDE-managed Components
	void __fastcall FormCreate(TObject *Sender);
	void __fastcall FormClose(TObject *Sender, TCloseAction &Action);
private:	// User declarations
public:		// User declarations
	__fastcall Tbackup_act(TComponent* Owner);

   SShop* shop;
   AnsiString shop_name;
   AnsiString backup_dir;

   bool full;
   bool inc_sql;
};
//---------------------------------------------------------------------------
extern PACKAGE Tbackup_act *backup_act;
//---------------------------------------------------------------------------
#endif
