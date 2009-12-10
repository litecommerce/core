//---------------------------------------------------------------------------

#ifndef w_get_threadH
#define w_get_threadH
#include "wysiwyg_action.h"
#include "web_thread.h"
//---------------------------------------------------------------------------
#include <Classes.hpp>
//---------------------------------------------------------------------------
class Thread_w_get : public WebThread
{
private:
protected:
   TFileStream* tar_file;
	TFileStream *timestamp;
   TTarArchive* archive;
   AnsiString skins_dir;
   TStringList* files;

   bool remove_tar; // true if we need to remove temporary tar file
   void __fastcall Execute();
public:
   TAction_wysiwyg_get* _parent;
   __fastcall Thread_w_get(bool CreateSuspended);
   __fastcall ~Thread_w_get();
   void __fastcall Init();
	void SaveTimeStamp();
   bool Extract(void);
   bool DownloadSkins(void);
   bool PrepareSkins(void);
   bool GetSkinDecoration(void);

   void Clear(void);
};
//---------------------------------------------------------------------------
#endif
