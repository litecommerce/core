//---------------------------------------------------------------------------

#include <vcl.h>
#include <ComObj.hpp>
#include "MainWnd.h"
#pragma hdrstop

#include "edit_file.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tselect_edit_file *select_edit_file;
//---------------------------------------------------------------------------
__fastcall Tselect_edit_file::Tselect_edit_file(TComponent* Owner)
	: TForm(Owner)
{ 
}
//---------------------------------------------------------------------------
void __fastcall Tselect_edit_file::_cancelClick(TObject *Sender)
{
	Close();	
}
//---------------------------------------------------------------------------

void __fastcall Tselect_edit_file::filesSelectItem(TObject *Sender,
      TListItem *Item, bool Selected)
{
	filename = Item->Caption;
}
//---------------------------------------------------------------------------

void __fastcall Tselect_edit_file::filesDblClick(TObject *Sender)
{
	EditFile();
}
//---------------------------------------------------------------------------

void __fastcall Tselect_edit_file::filesChange(TObject *Sender,
      TListItem *Item, TItemChange Change)
{
	if (Change != ctState) {
   	return;
   }
   canedit = Item->Selected;
   this->_edit->Enabled = Item->Selected;

}
//---------------------------------------------------------------------------

void Tselect_edit_file::EditFile(void)
{
   if (canedit) {
      if (mainWindow->html_editor != "") {
      	STARTUPINFO start;
      	PROCESS_INFORMATION p_info;
        ZeroMemory( &start, sizeof(start) );
      	GetStartupInfo(&start);
      	CreateProcess(
                NULL,
                (mainWindow->html_editor + " " + "\"" + mainWindow->wysiwyg_frame->wysiwyg_dir->Text.c_str() + "\\" + filename + "\"").c_str(),
                NULL, NULL,
         	false,
         	0,
         	NULL,
         	NULL,
         	&start,
         	&p_info
      	);
      } else {
			ShellExecute(NULL,"open", filename.c_str(), NULL, mainWindow->wysiwyg_dir.c_str(), SW_SHOWNORMAL);
      }
   }
}

void __fastcall Tselect_edit_file::_editClick(TObject *Sender)
{
      if (mainWindow->html_editor == "") {
         if (MessageDlg("You didn't choose default HTML editor\r\nDo you wish to continue?", mtConfirmation, TMsgDlgButtons() << mbYes << mbNo, 0) == mrNo) {
            return;
         }
      }
      EditFile();
}
//---------------------------------------------------------------------------

