//---------------------------------------------------------------------------

#ifndef form_AddShopH
#define form_AddShopH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include "f_AddShop.h"
//---------------------------------------------------------------------------
class Tadd_shop : public TForm
{
__published:	// IDE-managed Components
   Tadd_shop_frm *add_shop_frm1;
private:	// User declarations
public:		// User declarations
   __fastcall Tadd_shop(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tadd_shop *add_shop;
//---------------------------------------------------------------------------
#endif
