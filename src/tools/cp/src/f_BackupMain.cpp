//---------------------------------------------------------------------------

#include <vcl.h>
#include "constants.h"
#pragma hdrstop

#include "f_BackupMain.h"
#include "backup_action.h"
#include "restore_act.h"
//#include <shlobj.h>
#include "MainWnd.h"
#include <io.h>
//---------------------------------------------------------------------------
#pragma package(smart_init)
#pragma resource "*.dfm"
Tbackup_frm *backup_frm;
//---------------------------------------------------------------------------
__fastcall Tbackup_frm::Tbackup_frm(TComponent* Owner)
   : TFrame(Owner)
{ /*  TRegistry* reg = new TRegistry();
    reg->OpenKey("Software\\LiteCommerce\\Backup", true);
    if (!reg->ValueExists("last_db_backup_file"))
            this->database_backup->Text = "No backups were made";
    else if (reg->ReadString("last_db_backup_file") == "")
            this->database_backup->Text = "Last backup was erased";
    else this->database_backup->Text = reg->ReadString("last_db_backup_file");
    reg->CloseKey();*/
}
//---------------------------------------------------------------------------
void __fastcall Tbackup_frm::restore_sql_openClick(TObject *Sender)
{
   if (restore_sql_od->Execute()) {
      restore_sql_name->Text = restore_sql_od->FileName;
      this->btn_db_restore->Enabled = check_shop();
   }
}
//---------------------------------------------------------------------------


bool Tbackup_frm::check_shop()
{
   if (backup_choose_shop->Items->Strings[backup_choose_shop->ItemIndex] == ADD_SHOP) {
      return false;
   }
   return true;
}

void __fastcall Tbackup_frm::btn_db_backupClick(TObject *Sender)
{
   TRegistry *reg = new TRegistry();
   if (!check_shop()) {
      return;
   }
   this->save_dback_file->DefaultExt ="sql";
   if (this->save_dback_file->Execute()) {
      mainWindow->dbackup_filename = this->save_dback_file->FileName;
      reg->OpenKey("Software\\LiteCommerce\\Backup", true);
      reg->WriteString("last_db_backup_file",mainWindow->dbackup_filename);
      reg->CloseKey();
   } else {
   	return;
   }
   full = false;

   Tbackup_act* backup_wnd = new Tbackup_act(this);
   backup_wnd->ShowModal();
   delete backup_wnd;
   delete reg;
}
//---------------------------------------------------------------------------

void __fastcall Tbackup_frm::btn_full_backupClick(TObject *Sender)
{
   TRegistry *reg = new TRegistry();
   if (!check_shop()) {
      return;
   }
   this->save_sback_file->DefaultExt = "tar";
   if (this->save_sback_file->Execute()) {
   	mainWindow->sbackup_filename = this->save_sback_file->FileName;
      reg->OpenKey("Software\\LiteCommerce\\Backup", true);
      reg->WriteString("last_sh_backup_file",mainWindow->sbackup_filename);
      reg->CloseKey();
   } else {
   	return;
   }
   full = true;
   Tbackup_act* backup_wnd = new Tbackup_act(this);
   backup_wnd->ShowModal();
   delete backup_wnd;
   delete reg;
}
//---------------------------------------------------------------------------

void __fastcall Tbackup_frm::btn_db_restoreClick(TObject *Sender)
{
	if (!check_shop()) {
      return;
   }
 	if (!FileExists(restore_sql_name->Text)) {
   	btn_db_restore->Enabled = false;
      return;
   }
   full = false;
   Taction_restore* restore_wnd = new Taction_restore(this);
   restore_wnd->ShowModal();
   delete restore_wnd;
}
//---------------------------------------------------------------------------

void __fastcall Tbackup_frm::btn_full_restoreClick(TObject *Sender)
{
   if (!check_shop()) {
      return;
   }
   TTarArchive *archive = new TTarArchive(restore_arx_name->Text,tmSaveText);
   if (!is_right_tar(archive))
        if (MessageDlg("It's not a backup archive,\r\nDo you wish to continue ?", mtConfirmation, TMsgDlgButtons() << mbYes << mbNo, 0) == mrNo)
                return;
   delete archive;
   if (!FileExists(restore_arx_name->Text))
        {
                btn_full_restore->Enabled = false;
                return;
        }
   full = true;
   __try {
   Taction_restore* restore_wnd = new Taction_restore(this);
   restore_wnd->ShowModal();
   delete restore_wnd;
   }
   catch(...)
        {

        }
}
//---------------------------------------------------------------------------

bool Tbackup_frm::setBackupDir(AnsiString caption)
{
/*
   BROWSEINFO bi;
   char GDir[MAX_PATH];
  	char FolderName[MAX_PATH];

   TRegistry *reg = new TRegistry();
   reg->OpenKey("Software\\LiteCommerce\\Backup", true);
   if (!reg->ValueExists("backup_dir")) {
   	reg->WriteString("backup_dir", "");
   }
   AnsiString initial_dir = reg->ReadString("backup_dir");

  	LPITEMIDLIST ItemID;
  	memset(&bi, 0, sizeof(BROWSEINFO));
  	memset(GDir, 0, MAX_PATH);
  	bi.hwndOwner      = Handle;
  	bi.pszDisplayName = FolderName;
  	bi.lpszTitle      = caption.c_str();
  	ItemID = SHBrowseForFolder(&bi);
   if (ItemID == NULL) {
   	delete reg;
      return false;
   }
  	SHGetPathFromIDList(ItemID, GDir);
   this->backup_dir = GDir;
   if (access(GDir, 6) == -1) {
      ShowMessage("Could not write to the selected directory");
   	delete reg;
      return false;
   }
   reg->WriteString("backup_dir", this->backup_dir);
   delete reg;
   return true;
*/
	return false;   
}

void __fastcall Tbackup_frm::restore_arx_openClick(TObject *Sender)
{
   if (restore_arx_o_dialog->Execute()) {
      restore_arx_name->Text = restore_arx_o_dialog->FileName;
   }
}
//---------------------------------------------------------------------------

void __fastcall Tbackup_frm::backup_choose_shopChange(TObject *Sender)
{
   mainWindow->settings_frame->shops->ItemIndex = backup_choose_shop->ItemIndex;

	Activate(btn_db_restore, restore_sql_name);
	Activate(btn_full_restore, restore_arx_name);
	Activate(btn_db_backup);
	Activate(btn_full_backup);

   if (backup_choose_shop->Items->Strings[backup_choose_shop->ItemIndex] == ADD_SHOP) {
   	mainWindow->frame_sender = 0;
   	mainWindow->btn_settingsClick(Sender);
   }
}
//---------------------------------------------------------------------------

bool __fastcall Tbackup_frm::is_right_tar(TTarArchive *archive)
{
   TTarDirRec tar_rec;
   archive->Reset();
   while (archive->FindNext(tar_rec))
   if (tar_rec.Name == "etc/config.php") {
   	return true;
   }
   return false;
}
//---------------------------------------------------------------------------

void Tbackup_frm::Activate(TButton* button, TEdit* edit)
{
	if (edit != NULL) {
		button->Enabled = ((edit->Text != "") && (backup_choose_shop->Items->Strings[backup_choose_shop->ItemIndex] != ADD_SHOP));
   } else {
		button->Enabled = (backup_choose_shop->Items->Strings[backup_choose_shop->ItemIndex] != ADD_SHOP);
   }
}

void __fastcall Tbackup_frm::restore_arx_nameChange(TObject *Sender)
{
	Activate(btn_full_restore, restore_arx_name);
}
//---------------------------------------------------------------------------

void __fastcall Tbackup_frm::restore_sql_nameChange(TObject *Sender)
{
	Activate(btn_db_restore, restore_sql_name);
}
//---------------------------------------------------------------------------


