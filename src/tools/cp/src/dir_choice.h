//---------------------------------------------------------------------------

#ifndef dir_choiceH
#define dir_choiceH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <FileCtrl.hpp>
#include <Menus.hpp>
//---------------------------------------------------------------------------
class Tdirectory_choice : public TForm
{
__published:	// IDE-managed Components
        TButton *dir_ok;
        TButton *dir_cancel;
        TButton *create_dir;
        TPopupMenu *createdir_popup;
        TMenuItem *C1;
        TDirectoryListBox *dir_list;
        void __fastcall dir_cancelClick(TObject *Sender);
        void __fastcall dir_okClick(TObject *Sender);
        void __fastcall C1Click(TObject *Sender);
        void __fastcall create_dirClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
        __fastcall Tdirectory_choice(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tdirectory_choice *directory_choice;
//---------------------------------------------------------------------------
#endif
