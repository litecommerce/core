//---------------------------------------------------------------------------

#ifndef u_mainH
#define u_mainH
//---------------------------------------------------------------------------
#include "IdBaseComponent.hpp"
#include "IdComponent.hpp"
#include "IdFTP.hpp"
#include "IdTCPClient.hpp"
#include "IdTCPConnection.hpp"
#include "IdHTTP.hpp"

#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
#include <Graphics.hpp>
#include "syncobjs.hpp"

#include "extract.h"
#include "extract_thr.h"
#include "upload_thr.h"
#include "license.h"
#include "settings.h"
#include "upload.h"
#include "splash.h"
#include "completed.h"
#include "test.h"
#include "test_thr.h"

//---------------------------------------------------------------------------
// public functions:
void rDeleteDir(AnsiString dirname);

//

class T_main : public TForm
{
__published:	// IDE-managed Components
	TPanel *__screen;
	Tf_license *_license;
	Tf_settings *_settings;
	TImage *__header;
	TPanel *__top;
	TPanel *__spacer;
	TPanel *__left;
	TPanel *__right;
	Tf_splash *_splash;
	Tf_extract *_extract;
	Tf_upload *_upload;
	Tf_completed *_completed;
	Tf_test *_test;
	TIdFTP *ifc;
	TIdHTTP *ihc;
	void __fastcall FormCreate(TObject *Sender);
	void __fastcall FormClose(TObject *Sender, TCloseAction &Action);
	void __fastcall FormKeyPress(TObject *Sender, char &Key);
	void __fastcall FormCloseQuery(TObject *Sender, bool &CanClose);
private:	// User declarations
public:		// User declarations
	__fastcall T_main(TComponent* Owner);

   /**********************************/
   // methods
  	void __fastcall OnExtract(TObject* Sender);
	void __fastcall OnUpload(TObject* Sender);
	void __fastcall OnTest(TObject* Sender);

   //void __fastcall ExitAfterFinish(TObject* Sender); 

   void ShowSplash(void);
	void ShowExtract(void);
	void ShowLicense(void);
   void ShowSettings(void);
   void ShowTest(void);
   void ShowUpload(void);
   void ShowCompleted(void);
   /**********************************/
   // options
   bool passive;
   bool proxy;
   bool proxy_auth;

   bool upload_all;
   bool skip;
   bool extract_only;

   bool test_success;

   // выполняется ли upload thread
   bool upload_run;

   // Выполняемый thread
   TThread *thread;

   AnsiString ftp_host;
   AnsiString ftp_port;
   AnsiString ftp_dir;
   AnsiString ftp_login;
   AnsiString ftp_password;
   AnsiString proxy_server;
   AnsiString proxy_port;
   AnsiString proxy_login;
   AnsiString proxy_password;
   AnsiString http_address;

   TFileStream *log;

   AnsiString archive_name;
   AnsiString tar_name;
   AnsiString temp_dir;

   TStringList* files;
   TStringList* dirs;

   extract_thread* t_extract;
   upload_thread* t_upload;
   test_thread* t_test;

   AnsiString license_file;

   AnsiString help_resource_name;
   AnsiString help_resource_type;
   AnsiString help_file;

   long files_size;
   long files_count;

   /**********************************/
   // interface variables;
   TList *frames;
   bool remove_files;
   /**********************************/
   // Util functions
	TStringList* ReformatLicense(AnsiString license);
	void getSettings();
	void setSettings();
	void updateSettings();
	void WriteLog(AnsiString msg);
   void DisableAllframes(void);
};
//---------------------------------------------------------------------------
extern PACKAGE T_main *_main;
//---------------------------------------------------------------------------
#endif
