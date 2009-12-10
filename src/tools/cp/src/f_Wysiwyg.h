//---------------------------------------------------------------------------


#ifndef f_WysiwygH
#define f_WysiwygH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <Buttons.hpp>
#include <FileCtrl.hpp>
//---------------------------------------------------------------------------
class Twysiwyg_frm : public TFrame
{
__published:	// IDE-managed Components
   TPanel *wysiwyg_bkgr;
   TPanel *Panel1;
   TGroupBox *common;
   TComboBox *wysiwyg_shop;
   TLabel *Label1;
   TEdit *wysiwyg_dir;
   TLabel *Label2;
   TButton *download_design;
   TButton *publish_design;
   TButton *restore_design;
   TSpeedButton *select_dir;
   TButton *templates;

   void __fastcall wysiwyg_shopChange(TObject *Sender);
   void __fastcall select_dirClick(TObject *Sender);
   void __fastcall download_designClick(TObject *Sender);
	void __fastcall publish_designClick(TObject *Sender);
   void __fastcall templatesClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
   __fastcall Twysiwyg_frm(TComponent* Owner);
	bool action_downloaded;
};
//---------------------------------------------------------------------------
extern PACKAGE Twysiwyg_frm *wysiwyg_frm;
//---------------------------------------------------------------------------
#endif
