//---------------------------------------------------------------------------

#ifndef n_move_shop_actionH
#define n_move_shop_actionH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include "ThreadForm.h"
#include <ExtCtrls.hpp>
#include "backup_thread.h"
#include "restore_thread.h"
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tmove_shop : public TProtoThreadForm
{
__published:	// IDE-managed Components
	void __fastcall FormCreate(TObject *Sender);
private:	// User declarations
   void __fastcall OnBackupComplete(TObject *Sender);
   void __fastcall OnRestoreComplete(TObject *Sender);
public:		// User declarations
	__fastcall Tmove_shop(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tmove_shop *move_shop;
//---------------------------------------------------------------------------
#endif
