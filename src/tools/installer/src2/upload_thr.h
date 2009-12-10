//---------------------------------------------------------------------------

#ifndef upload_thrH
#define upload_thrH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include "owerwrite_dlg.h"
#include <sys/stat.h>
//---------------------------------------------------------------------------
class upload_thread : public TThread
{
private:
protected:
	void __fastcall Execute();
   int pos;
   AnsiString text;
   // File progress properties
   int file_max;
   int file_pos;
	void __fastcall _setFileName(void);
	void __fastcall _setTotalProgress(void);
   void __fastcall _setFileMax(void);
   void __fastcall _setFileProgress(void);
   int GetFileLen(AnsiString name);
public:
   bool cancel;
	__fastcall upload_thread(bool CreateSuspended);
   __fastcall ~upload_thread();

	void setFileName(AnsiString filename);
	void setProgress(int pos);
   void setFileProgress(int pos);
   void setFileMax(int max);
	void createDirs(void); // NOT IMPLEMENTED
	int uploadFiles(void); // NOT IMPLEMENTED
	void DoError(void);

	void __fastcall ShowConfirm(void);
	void __fastcall Connect(void);

   bool terminate;
   bool IsDir(AnsiString dir);
   bool IsFile(AnsiString file);
   bool CreateDir(AnsiString dir);
   bool SmartReconnect(AnsiString init_path = NULL);

	void __fastcall ifcWork(TObject *Sender, TWorkMode AWorkMode, const int AWorkCount);
};
//---------------------------------------------------------------------------
#endif
