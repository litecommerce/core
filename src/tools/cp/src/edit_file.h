//---------------------------------------------------------------------------

#ifndef edit_fileH
#define edit_fileH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <ComCtrls.hpp>
#include <ImgList.hpp>
//---------------------------------------------------------------------------
class Tselect_edit_file : public TForm
{
__published:	// IDE-managed Components
	TPanel *bottom;
	TButton *_edit;
	TButton *_cancel;
	TListView *files;
	TImageList *icons;
	void __fastcall _cancelClick(TObject *Sender);
	void __fastcall filesSelectItem(TObject *Sender, TListItem *Item,
          bool Selected);
	void __fastcall filesDblClick(TObject *Sender);
	void __fastcall filesChange(TObject *Sender, TListItem *Item,
          TItemChange Change);
	void __fastcall _editClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tselect_edit_file(TComponent* Owner);

   void EditFile(void);

   AnsiString filename;
   bool canedit;
};
//---------------------------------------------------------------------------
extern PACKAGE Tselect_edit_file *select_edit_file;
//---------------------------------------------------------------------------
#endif
