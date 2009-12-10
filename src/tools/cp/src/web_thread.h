#pragma hdrstop
//---------------------------------------------------------------------------

#ifndef web_threadH
#define web_threadH

//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <vcl.h>
#include <LibTar.hpp>
#include "FileUtil.h"
//---------------------------------------------------------------------------
#include "MainWnd.h"
//---------------------------------------------------------------------------
class WebThread : public TThread
{
private:
protected:
   AnsiString _text;
   AnsiString _caption;

   TMemo* _display;
   TForm* __parent;

   SShop* shop;

   int _timeout;
   TStringList* _post;
   AnsiString _response;

   void __fastcall _changeCaption();
   void __fastcall _print();
   void __fastcall _println();
   virtual void __fastcall Init(void) = 0;
public:
   __fastcall WebThread(bool CreateSuspend);
   __fastcall ~WebThread();

   void Print(AnsiString text);
   void PrintLn(AnsiString text);
   void ChangeCaption(AnsiString Caption);

	bool Connect(void);
   bool CdHome(bool create = true);
   bool IsDir(AnsiString dir);
   bool FtpCreateDir(AnsiString dir, AnsiString mode = NULL);
   bool FtpConnect(void);
   bool FtpDisconnect(void);
	bool FtpDownload(AnsiString src, AnsiString dst);
   bool FtpUpload(AnsiString src, AnsiString dst);
   bool FtpRemoveFile(AnsiString name);
   bool FtpCommand(AnsiString command);
	bool FtpCreateDirs(TStringList* dirs);
   void HttpInit(void);
   bool Post(AnsiString URL, TStringList* post = NULL);
   bool Post(AnsiString URL, TFileStream* file, TStringList* post = NULL);
   bool Get(AnsiString URL);
};
//---------------------------------------------------------------------------
extern PACKAGE TmainWindow *mainWindow;
//---------------------------------------------------------------------------
#endif