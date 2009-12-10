//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "test_shop_thread.h"
#pragma package(smart_init)
//---------------------------------------------------------------------------

__fastcall TetShopThread::TetShopThread(bool CreateSuspended)
	: WebThread(CreateSuspended)
{
}
//---------------------------------------------------------------------------

void __fastcall TetShopThread::Execute()
{
   Init();

   PrintLn("Checking FTP settings ... ");
   PrintLn(" ");

   if (!Connect()) {
   	return;
   }
   if (!CdHome()) {
      PrintLn("* ERROR: Unable to create or enter shop home dir " + shop->ftp_dir);
   	return;
   }
   AnsiString test_dir = Application->ExeName.SubString(1, Application->ExeName.LastDelimiter("\\") - 1) + "\\test_files";
   AnsiString content = "";
   createDirs(test_dir);
   TFileStream* test_file = new TFileStream(test_dir + "\\upload_test.txt", fmCreate);
   randomize();
   for(int i = 0; i < 10; i++) {
   	content += IntToStr(random(9));
   }
   test_file->Write(content.c_str(), 10);
   delete test_file;

   PrintLn(" - Uploading test file ... ");
   if (!FtpUpload(test_dir + "\\upload_test.txt", "upload_test.txt")) {
   	Print("[FAILED]");
      return;
   }
   Print("[OK]");

   PrintLn(" ");
   PrintLn("Checking HTTP settings ... ");

   PrintLn(" - Checking HTTP connection ... ");
   AnsiString URL = shop->http_url + "/upload_test.txt";
   if(!Get(URL) || _response != content) {
   	Print("[FAILED]");
      return;
   }
   Print("[OK]");

   PrintLn(" - Checking shop administrator's settings ... ");

   URL = shop->http_url + "/admin.php?";
   _post->Clear();
   _post->Add("target=upgrade");
   _post->Add("action=version");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password=" + shop->admin_password);
   bool result = Post(URL);
   if (!result || _response == "ACCESS DENIED") {
   	Print("[FAILED]");
      return;
   }
   Print("[OK]");

   PrintLn(" ");
   PrintLn("All tests passed successefully. Press \"Ok\" button to continue.");
   _parent->SetResult(true);
   rDeleteDir(test_dir);
}
//---------------------------------------------------------------------------

void __fastcall TetShopThread::Init(void)
{
   shop = _parent->shop;
   __parent = _parent;
   _display = _parent->__m_display;
	HttpInit();
}
//---------------------------------------------------------------------------

