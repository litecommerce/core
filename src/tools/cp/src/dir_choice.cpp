//---------------------------------------------------------------------------

#include <vcl.h>
#include <FileCtrl.hpp>
#pragma hdrstop
#include "MainWnd.h"
#include "dir_choice.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tdirectory_choice *directory_choice;
//---------------------------------------------------------------------------
__fastcall Tdirectory_choice::Tdirectory_choice(TComponent* Owner)
        : TForm(Owner)
{
        dir_list->PopupMenu = createdir_popup;
}
//---------------------------------------------------------------------------

void __fastcall Tdirectory_choice::dir_cancelClick(TObject *Sender)
{
        Close();
}
//---------------------------------------------------------------------------

void __fastcall Tdirectory_choice::dir_okClick(TObject *Sender)
{
/*        if (!DirectoryExists(curr_dir->Text))
        {
           if (MessageDlg("Create directory ?", mtConfirmation, TMsgDlgButtons() << mbYes << mbNo, 0) == mrYes)
                if (CreateDir(curr_dir->Text))
                   mainWindow->wysiwyg_frame->wysiwyg_dir->Text = curr_dir->Text;
                else   {
                        throw Exception("can't create directory");
                        mainWindow->wysiwyg_frame->wysiwyg_dir->Text = GetCurrentDir();
                }
        } else  */
                mainWindow->wysiwyg_frame->wysiwyg_dir->Text = GetCurrentDir();
        Close();
}
//---------------------------------------------------------------------------

void __fastcall Tdirectory_choice::C1Click(TObject *Sender)
{
     create_dirClick(Sender);
}
//---------------------------------------------------------------------------
void __fastcall Tdirectory_choice::create_dirClick(TObject *Sender)
{
  if (SelectDirectory(Dir, TSelectDirOpts() << sdAllowCreate << sdPerformCreate << sdPrompt,1))
            mainWindow->wysiwyg_frame->wysiwyg_dir->Text = Dir;

/*     AnsiString asd = GetCurrentDir();
     CreateDir(GetCurrentDir()+"\\asd");
     dir_list->Update();
     SelectDirectory()*/
}
//---------------------------------------------------------------------------
