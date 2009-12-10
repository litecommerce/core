//---------------------------------------------------------------------------

#ifndef test_shop_actionH
#define test_shop_actionH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include "ThreadForm.h"
#include <ExtCtrls.hpp>
#include "MainWnd.h"
//---------------------------------------------------------------------------
class Ttest_shop : public TProtoThreadForm
{
__published:	// IDE-managed Components
	void __fastcall FormCreate(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Ttest_shop(TComponent* Owner);
   SShop* shop;
};
//---------------------------------------------------------------------------
extern PACKAGE Ttest_shop *test_shop;
//---------------------------------------------------------------------------
#endif
