//---------------------------------------------------------------------------

#ifndef upgrade_threadH
#define upgrade_threadH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include "upgrade_action.h"
#include "FileUtil.h"
#include "web_thread.h"
//---------------------------------------------------------------------------

struct Manifest
{
   upgrade_type type;
   AnsiString main;
   AnsiString description;
   AnsiString readme;
   AnsiString fromver;
   AnsiString tover;
   TStringList* remove_files;
};
//---------------------------------------------------------------------------

class upgrade_thr : public WebThread
{
private:
protected:
   void __fastcall Execute();
public:
   Tupgrade_act* _parent;
   TStringList* files; // Extracted files
   TStringList *dirs;
   AnsiString tgz_file;
   AnsiString tar_file;
   AnsiString extract_dir;
   AnsiString remove_list_filename;
   AnsiString ConfirmMessage;
   Manifest *manifest;

   TTarArchive* archive;
   bool proceed;

   __fastcall upgrade_thr(bool CreateSuspended);
	__fastcall ~upgrade_thr();
   void __fastcall Init();
   bool VerifyVersionResponse(AnsiString resp);
   void Upgrade(void);

   bool DoUpgrade(void);
   bool DoHotFix(void);
   bool Extract(void);
   bool VerifyVersion(void);
   bool Upload(void);
   void __fastcall ShowConfirm(void);
   bool ConfirmUpgrade(void);
   bool RemoveOldFiles(void);
   void ParseManifest(void);
   bool ConfirmHotfix(void);
};
//---------------------------------------------------------------------------
#endif
