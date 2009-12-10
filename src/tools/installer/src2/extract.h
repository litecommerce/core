//---------------------------------------------------------------------------


#ifndef extractH
#define extractH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <ComCtrls.hpp>
//---------------------------------------------------------------------------
class Tf_extract : public TFrame
{
__published:	// IDE-managed Components
	TPanel *Panel1;
	TButton *_cancel;
	TLabel *FileName;
	TProgressBar *FilesProgress;
	TPanel *Head;
	void __fastcall _cancelClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tf_extract(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tf_extract *f_extract;
//---------------------------------------------------------------------------
#endif
