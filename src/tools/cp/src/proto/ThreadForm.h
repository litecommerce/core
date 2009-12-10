//---------------------------------------------------------------------------

#ifndef ThreadFormH
#define ThreadFormH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class TProtoThreadForm : public TForm
{
__published:	// IDE-managed Components
	TPanel *__p_bottom;
	TPanel *__p_center;
	TButton *__b_cancel;
	TButton *__b_ok;
	TMemo *__m_display;
	TLabel *__bytes;
	void __fastcall __b_okClick(TObject *Sender);
	void __fastcall __b_cancelClick(TObject *Sender);
	void __fastcall FormCloseQuery(TObject *Sender, bool &CanClose);
	void __fastcall FormClose(TObject *Sender, TCloseAction &Action);
	void __fastcall FormKeyPress(TObject *Sender, char &Key);
protected:
	bool runing;
   bool success;
   TThread* thr;
   AnsiString confirmText;

   void __fastcall OnThreadComplete(TObject* Sender);
private:	// User declarations
public:		// User declarations
	__fastcall TProtoThreadForm(TComponent* Owner);
   void SetResult(bool result);
};
//---------------------------------------------------------------------------
extern PACKAGE TProtoThreadForm *ProtoThreadForm;
//---------------------------------------------------------------------------
#endif
