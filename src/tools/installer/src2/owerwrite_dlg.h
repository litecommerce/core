//---------------------------------------------------------------------------

#ifndef owerwrite_dlgH
#define owerwrite_dlgH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <Graphics.hpp>
//---------------------------------------------------------------------------
class Towerwrite : public TForm
{
__published:	// IDE-managed Components
	TPanel *Panel1;
	TButton *_owerwite;
	TButton *_skip;
	TButton *_qwerwrite_all;
	TButton *_cancel;
	TLabel *Label1;
	TImage *Image1;
	void __fastcall _skipClick(TObject *Sender);
	void __fastcall _owerwiteClick(TObject *Sender);
	void __fastcall _qwerwrite_allClick(TObject *Sender);
	void __fastcall _cancelClick(TObject *Sender);
	void __fastcall FormCreate(TObject *Sender);
private:	// User declarations
public:		// User declarations
	__fastcall Towerwrite(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Towerwrite *owerwrite;
//---------------------------------------------------------------------------
#endif
