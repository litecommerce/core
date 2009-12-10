//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop

#include "w_put_thread.h"
#pragma package(smart_init)
//---------------------------------------------------------------------------

__fastcall w_put_thread::w_put_thread(bool CreateSuspended)
	: WebThread(CreateSuspended)
{
}
//---------------------------------------------------------------------------
void __fastcall w_put_thread::Execute()
{
	Init();
   if (!Connect()) {
   	return;
   }
   if (!CdHome(false)) {
      PrintLn("* ERROR: Shop home dir " + shop->ftp_dir + " not found");
   	return;
   }
   if (!DoPublish()) {
   	PrintLn("Control panel was unable to publish your design. Press \"Cancel\" button to continue.");
   } else {
   	PrintLn("Your design is published successefully. Press \"Ok\" button to continue.");
      _parent->success = true;
   }
}
//---------------------------------------------------------------------------

void __fastcall w_put_thread::Init(void)
{
	__parent = _parent;
   _display = _parent->display;
   shop = _parent->shop;
   HttpInit();
}
//---------------------------------------------------------------------------

bool w_put_thread::DoPublish(void)
{
	if (!Upload()) {
   	return false;
   }
   if (!UnTar()) {
   	return false;
   }
   if (!Publish()) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool w_put_thread::Upload(void)
{
	PrintLn("Uploading skins ... ");

   if (!FtpUpload("skins.tar", "skins.tar")) {
      Print("[FAILED]");
      return false;
   }
   Print("[OK]");
   DeleteFile("skins.tar");
   return true;
}
//---------------------------------------------------------------------------

bool w_put_thread::UnTar(void)
{
   AnsiString URL = shop->http_url + "/admin.php?";

   PrintLn("Extracting decorated skins ... ");
   _post->Clear();
   _post->Add("target=files");
   _post->Add("action=untar_skins");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password="+ shop->admin_password);
   _post->Add("mode=cp");

   if (!Post(URL) || _response.UpperCase() != "OK") {
   	Print("[FAILED]");
      return false;
   }
   Print ("[OK]");
   return true;
}
//---------------------------------------------------------------------------

bool w_put_thread::Publish(void)
{
   AnsiString URL = shop->http_url + "/admin.php?";

   PrintLn("Publishing design ... ");
   _post->Clear();
   _post->Add("target=wysiwyg");
   _post->Add("action=import");
   _post->Add("login=" + shop->admin_email);
   _post->Add("password=" + shop->admin_password);
   _post->Add("mode=cp");

   if (!Post(URL) || _response.UpperCase() != "OK") {
		Print("[FAILED]");
      TStringList *slResp = ConvertCR(_response);
      for (int i = 0; i < slResp->Count; i++) {
         PrintLn(slResp->Strings[i]);
      }
      return false;
   }

   Print("[OK]");
   return true;
}
//---------------------------------------------------------------------------

