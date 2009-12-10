//---------------------------------------------------------------------------

#ifndef w_pub_actionH
#define w_pub_actionH
//---------------------------------------------------------------------------
#include "MainWnd.h"
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <CheckLst.hpp>
//---------------------------------------------------------------------------
class Tw_publich_action : public TForm
{
__published:	// IDE-managed Components
   TPanel *background;
   TMemo *display;
   TButton *_cancel;
   TButton *_ok;
	TCheckListBox *files_list;
	TButton *_modified;
	TButton *_all;
	TButton *_none;
	TButton *_publish;
   void __fastcall _okClick(TObject *Sender);
	void __fastcall FormCreate(TObject *Sender);
	void __fastcall _modifiedClick(TObject *Sender);
	void __fastcall _allClick(TObject *Sender);
	void __fastcall _noneClick(TObject *Sender);
	void __fastcall _publishClick(TObject *Sender);
	void __fastcall _cancelClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
   __fastcall Tw_publich_action(TComponent* Owner);
   void __fastcall onEndThread(TObject* Sender);
   SShop* shop;
   AnsiString shop_name;
   bool success;
   AnsiString skin_dir;
   TStringList *modified;
   TStringList *selected;
   TStringList* files;
   TStringList* timestamp;
   TThread* thr;
};
//---------------------------------------------------------------------------
extern PACKAGE Tw_publich_action *w_publich_action;
//---------------------------------------------------------------------------
#endif
