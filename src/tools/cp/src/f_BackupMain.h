//---------------------------------------------------------------------------


#ifndef f_BackupMainH
#define f_BackupMainH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <Buttons.hpp>
#include <Dialogs.hpp>
#include <ComCtrls.hpp>
#include <LibTar.hpp>

//---------------------------------------------------------------------------
class Tbackup_frm : public TFrame
{
__published:	// IDE-managed Components
   TPanel *backup_bkgr;
   TPanel *backup_info_panel;
   TLabel *Label2;
        TSaveDialog *save_sback_file;
        TOpenDialog *restore_sql_od;
        TOpenDialog *restore_arx_o_dialog;
        TGroupBox *backup_shop;
        TComboBox *backup_choose_shop;
        TPageControl *PageControl1;
        TTabSheet *TabSheet1;
        TTabSheet *TabSheet2;
        TSaveDialog *save_dback_file;
        TGroupBox *GroupBox1;
        TEdit *restore_sql_name;
        TLabel *Label1;
        TSpeedButton *restore_sql_open;
        TButton *btn_db_restore;
        TGroupBox *GroupBox3;
        TEdit *restore_arx_name;
        TSpeedButton *restore_arx_open;
        TButton *btn_full_restore;
        TLabel *Label3;
        TGroupBox *GroupBox2;
        TButton *btn_db_backup;
        TGroupBox *GroupBox4;
        TButton *btn_full_backup;
        TCheckBox *sql_include;
        TCheckBox *modify_config;
        TCheckBox *restore_defaults;
	TMemo *Memo1;
	TPanel *Panel1;
	TPanel *Panel2;
	TMemo *Memo2;
   void __fastcall restore_sql_openClick(TObject *Sender);
   void __fastcall btn_db_backupClick(TObject *Sender);
   void __fastcall btn_full_backupClick(TObject *Sender);
   void __fastcall btn_db_restoreClick(TObject *Sender);
   void __fastcall btn_full_restoreClick(TObject *Sender);
	void __fastcall restore_arx_openClick(TObject *Sender);
	void __fastcall backup_choose_shopChange(TObject *Sender);
   bool __fastcall is_right_tar(TTarArchive *archive);
	void __fastcall restore_arx_nameChange(TObject *Sender);
	void __fastcall restore_sql_nameChange(TObject *Sender);
protected:
	bool valid_shop;
private:	// User declarations
public:		// User declarations
   __fastcall Tbackup_frm(TComponent* Owner);
   bool check_shop();
   bool full;
   AnsiString backup_dir;
   bool setBackupDir(AnsiString caption);
   void Activate(TButton* button, TEdit* edit = NULL);
};
//---------------------------------------------------------------------------
extern PACKAGE Tbackup_frm *backup_frm;
//---------------------------------------------------------------------------
#endif
