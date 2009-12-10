//---------------------------------------------------------------------------


#ifndef uploadH
#define uploadH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ComCtrls.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tf_upload : public TFrame
{
__published:	// IDE-managed Components
	TLabel *FileName;
	TPanel *Panel1;
	TButton *_cancel;
	TProgressBar *total_progress;
	TProgressBar *file_progress;
	TPanel *Panel2;
	void __fastcall _cancelClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Tf_upload(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tf_upload *f_upload;
//---------------------------------------------------------------------------
#endif
