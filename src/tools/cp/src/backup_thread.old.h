#pragma hdrstop
//---------------------------------------------------------------------------

#ifndef backup_threadH
#define backup_threadH
#include "backup_action.h"
//#include "web_thread.h"
//---------------------------------------------------------------------------

class Thread_backup : public /*WebThread*/TThread
{
private:
protected:
   void __fastcall Execute();
public:
   __fastcall Thread_backup(bool CreateSuspended);
   Tbackup_act* _parent;
   void Backup();
};
//---------------------------------------------------------------------------
#endif
