//---------------------------------------------------------------------------


#ifndef completedH
#define completedH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tf_completed : public TFrame
{
__published:	// IDE-managed Components
	TPanel *Panel1;
	TButton *_continue;
	TButton *_cancel;
	TPanel *_screen;
	TLabel *Label1;
	TLabel *Label2;
	TLabel *Label3;
        TLabel *Label4;
        TEdit *Label5;
	void __fastcall _cancelClick(TObject *Sender);
	void __fastcall _continueClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tf_completed(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tf_completed *f_completed;
//---------------------------------------------------------------------------
#endif
