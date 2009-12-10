//---------------------------------------------------------------------------

#ifndef restore_threadH
#define restore_threadH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include "MainWnd.h"
#include "restore_act.h"
//---------------------------------------------------------------------------
class restore_thr : public TThread
{
private:
protected:
   void __fastcall Execute();
   void __fastcall Print();
public:
   __fastcall restore_thr(bool CreateSuspended);
   Taction_restore* _parent;
   WebControl* wctrl;
   AnsiString text;
   void print(AnsiString Text);

   AnsiString sql_file;
   AnsiString tar_file;
};
//---------------------------------------------------------------------------
#endif
