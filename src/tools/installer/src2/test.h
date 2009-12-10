//---------------------------------------------------------------------------


#ifndef testH
#define testH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tf_test : public TFrame
{
__published:	// IDE-managed Components
	TPanel *Panel1;
	TMemo *display;
	TButton *_cancel;
	TButton *_continue;
	TButton *_back;
	void __fastcall _backClick(TObject *Sender);
	void __fastcall _continueClick(TObject *Sender);
	void __fastcall _cancelClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tf_test(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tf_test *f_test;
//---------------------------------------------------------------------------
#endif
