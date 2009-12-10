//---------------------------------------------------------------------------


#ifndef f_MoveH
#define f_MoveH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tmove_frm : public TFrame
{
__published:	// IDE-managed Components
   TPanel *move_bkgr;
   TPanel *Panel1;
   TGroupBox *GroupBox1;
   TPanel *Panel2;
   TGroupBox *shop_to;
   TButton *move;
   TComboBox *src;
   TComboBox *dst;
   void __fastcall srcChange(TObject *Sender);
   void __fastcall dstChange(TObject *Sender);
        void __fastcall moveClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
   __fastcall Tmove_frm(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tmove_frm *move_frm;
//---------------------------------------------------------------------------
#endif
