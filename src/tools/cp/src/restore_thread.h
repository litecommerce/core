//---------------------------------------------------------------------------

#ifndef restore_threadH
#define restore_threadH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <RegExpr.hpp>
#include "MainWnd.h"
#include "restore_act.h"
#include "FileUtil.h"
#include "FileInfo.h"

#include "web_thread.h"

//---------------------------------------------------------------------------
class restore_thr : public WebThread
{
	private:
	protected:
		void __fastcall Execute();
      void __fastcall Init(void);

      bool movie_shop;
      bool full;
      AnsiString TARfile;
      AnsiString SQLfile;
      bool restore_original_permissions;
	public:
		__fastcall restore_thr(bool CreateSuspended);
		__fastcall restore_thr(bool CreateSuspended, bool movie);
      __fastcall ~restore_thr();
		/*Taction_restore**/TProtoThreadForm* _parent;
		//////////////////////////////////////

		//////////////////////////////////////
      // Database restore
		bool RestoreDB(void); // restore database
		void RenameSQL(void); // rename given sql backup file
      AnsiString CreateRestoreScript(void); //return script run password
      bool UploadRestoreScript(void);
      bool UploadSqlDump(void);
      bool RunRestoreScript(AnsiString pass);
      void ModifyConfig(AnsiString dir, SShop* shop);

		//////////////////////////////////////
      // Full shop restore
      bool RestoreShop(void); // full shop restore
      bool Extract(void);
      bool UploadShopFiles(void);
		bool RestorePermissions(void);
		bool RestoreTARPermissions(void); // restore files permissions from tar file
		bool RestoreDefaultPermissions(void); // restore default files permissions
      void __fastcall ShowConfirm(void);
      bool skip;
		bool full_with_sql; // is there SQL backup in archive
		AnsiString restore_dir; // temporary directory with backup files
		TList* files;
      TList* dirs_info;
   	TStringList *dirs;

	protected:
		AnsiString GeneratePassword(int pwd_len = 10); // generate password to restore shop
		//////////////////////////////////////

		AnsiString sql_file;
		AnsiString tar_file;
      bool temp_is_clean; //false if temp files has not been removed yet
};

//---------------------------------------------------------------------------
#endif
