//---------------------------------------------------------------------------

#ifndef backup_threadH
#define backup_threadH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include "backup_action.h"
#include "web_thread.h"

//---------------------------------------------------------------------------
class Thread_backup : public WebThread
{
private:
protected:
	void __fastcall Execute();
   void __fastcall Init(void);

////////////////////////////
// output downloaded bytes count
   void __fastcall _setSizeLabelText(void);
   void PrintDownloadedSize(AnsiString text);
   AnsiString _size_label_text;

	bool full;
   bool inc_sql;
   bool is_move_shop;
   AnsiString f_backup_filename;
   AnsiString d_backup_filename;
	void __fastcall OnDownload(TObject *Sender, TWorkMode AWorkMode, const int AWorkCount);
public:
   TProtoThreadForm* _parent;
   TFileStream* full_backup_file;

	__fastcall Thread_backup(bool CreateSuspended);
	__fastcall Thread_backup(bool CreateSuspended, bool full, bool inc_sql);
   __fastcall ~Thread_backup();
////////////////////////////
// Database backup & related
   bool DbBackup(void);
   bool _CreateServerDbBackup(void);
   bool _DownloadDbBackup(void);
	bool _RemoveSqlDump(void);
////////////////////////////
// Full backup
   bool FullBackup(void);
};
//---------------------------------------------------------------------------
#endif
