//---------------------------------------------------------------------------


#ifndef splashH
#define splashH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tf_splash : public TFrame
{
__published:	// IDE-managed Components
	TPanel *Panel1;
	TButton *_cancel;
	TButton *_continue;
	TShape *Shape1;
	TLabel *Label1;
	TLabel *Label2;
	TLabel *Label3;
	TLabel *Label4;
	void __fastcall _cancelClick(TObject *Sender);
	void __fastcall _continueClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tf_splash(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tf_splash *f_splash;
//---------------------------------------------------------------------------
#endif
