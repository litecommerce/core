//---------------------------------------------------------------------------


#ifndef licenseH
#define licenseH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tf_license : public TFrame
{
__published:	// IDE-managed Components
	TPanel *Panel1;
	TButton *_cancel;
	TButton *_continue;
	TMemo *display;
	TCheckBox *_agree;
	void __fastcall _cancelClick(TObject *Sender);
	void __fastcall _agreeClick(TObject *Sender);
	void __fastcall _continueClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tf_license(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tf_license *f_license;
//---------------------------------------------------------------------------
#endif
