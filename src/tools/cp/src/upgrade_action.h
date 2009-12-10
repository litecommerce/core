//---------------------------------------------------------------------------

#ifndef upgrade_actionH
#define upgrade_actionH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include "ThreadForm.h"
#include <ExtCtrls.hpp>
#include "MainWnd.h"
#include "constants.h"
//---------------------------------------------------------------------------
class Tupgrade_act : public TProtoThreadForm
{
__published:	// IDE-managed Components
	void __fastcall FormCreate(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tupgrade_act(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tupgrade_act *upgrade_act;
//---------------------------------------------------------------------------
#endif
