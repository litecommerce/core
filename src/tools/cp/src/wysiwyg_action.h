//---------------------------------------------------------------------------

#ifndef wysiwyg_actionH
#define wysiwyg_actionH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include "ThreadForm.h"
#include "MainWnd.h"
//---------------------------------------------------------------------------

class TAction_wysiwyg_get : public TProtoThreadForm
{
__published:	// IDE-managed Components
	void __fastcall FormCreate(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall TAction_wysiwyg_get(TComponent* Owner);
   AnsiString download_dir;
   SShop* shop;
};
//---------------------------------------------------------------------------
extern PACKAGE TAction_wysiwyg_get *Action_wysiwyg_get;
//---------------------------------------------------------------------------
#endif
