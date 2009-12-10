//---------------------------------------------------------------------------

#ifndef restore_actH
#define restore_actH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include "ThreadForm.h"
#include "f_BackupMain.h"
#include "MainWnd.h"
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Taction_restore : public TProtoThreadForm
{
__published:	// IDE-managed Components
	void __fastcall FormCreate(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Taction_restore(TComponent* Owner);
   SShop* shop;
   AnsiString shop_name;
   bool full;
	AnsiString SQLfile;
	AnsiString TARfile;
};
//---------------------------------------------------------------------------
extern PACKAGE Taction_restore *action_restore;
//---------------------------------------------------------------------------
#endif
