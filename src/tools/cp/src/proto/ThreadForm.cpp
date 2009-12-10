//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "ThreadForm.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
TProtoThreadForm *ProtoThreadForm;
//---------------------------------------------------------------------------
__fastcall TProtoThreadForm::TProtoThreadForm(TComponent* Owner)
	: TForm(Owner)
{
	runing = true;
}
//---------------------------------------------------------------------------
void __fastcall TProtoThreadForm::__b_okClick(TObject *Sender)
{
	Close();
}
//---------------------------------------------------------------------------
void __fastcall TProtoThreadForm::__b_cancelClick(TObject *Sender)
{
	Close();	
}
//---------------------------------------------------------------------------
void __fastcall TProtoThreadForm::FormCloseQuery(TObject *Sender,
      bool &CanClose)
{
	if (this->runing) {
      thr->Suspend();
   	if (Application->MessageBox(confirmText.c_str(), "Confirmation", MB_YESNO|MB_ICONQUESTION	) == IDYES) {
   		thr->Terminate();
   		delete thr;
         thr = NULL;
         CanClose = true;
   	} else {
         CanClose = false;
   		thr->Resume();
   	}
   } else {
   	CanClose = true;
   }
}
//---------------------------------------------------------------------------

void __fastcall TProtoThreadForm::OnThreadComplete(TObject* Sender)
{
   runing = false;
	__b_ok->Enabled = success;
}
//---------------------------------------------------------------------------

void TProtoThreadForm::SetResult(bool result)
{
	success = result;
}
//---------------------------------------------------------------------------

void __fastcall TProtoThreadForm::FormClose(TObject *Sender,
      TCloseAction &Action)
{
   if (thr != NULL) {
		delete thr;
   }
}
//---------------------------------------------------------------------------

void __fastcall TProtoThreadForm::FormKeyPress(TObject *Sender, char &Key)
{
	if (Key == 27) {
   	Close();
   }
}
//---------------------------------------------------------------------------

