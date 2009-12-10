#pragma hdrstop
//---------------------------------------------------------------------------

#include "web_thread.h"

//---------------------------------------------------------------------------

void __fastcall WebThread::_changeCaption()
{
	__parent->Caption = _caption;
}
//---------------------------------------------------------------------------

void __fastcall WebThread::_print()
{
   int count = _display->Lines->Count;
   if (count == 0) {
   	_display->Lines->Add(_text);
   } else {
   	AnsiString NewText = _display->Lines->Strings[count - 1] + _text;
      _display->Lines->Strings[count - 1] = NewText;
   }
}
//---------------------------------------------------------------------------

void __fastcall WebThread::_println()
{
   _display->Lines->Add(_text);
}
//---------------------------------------------------------------------------

void WebThread::Print(AnsiString text)
{
   _text = text;
	Synchronize(_print);
}
//---------------------------------------------------------------------------

void WebThread::PrintLn(AnsiString text)
{
   _text = text;
	Synchronize(_println);
}
//---------------------------------------------------------------------------

void WebThread::ChangeCaption(AnsiString caption)
{
	_caption = caption;
   Synchronize(_changeCaption);
}
//---------------------------------------------------------------------------

__fastcall WebThread::WebThread(bool CreateSuspended)
			: TThread(CreateSuspended)
{
	_post = new TStringList();
}
//---------------------------------------------------------------------------

__fastcall WebThread::~WebThread()
{
	FtpDisconnect();
}
//---------------------------------------------------------------------------

bool WebThread::Connect(void)
{
   Print("- Connecting to FTP server ... ");
   if (FtpConnect()) {
   	Print("[OK]");
   } else {
   	Print("[FAILED]");
      return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebThread::CdHome(bool create)
{
	if (create) {
   	if (!FtpCreateDir(shop->ftp_dir, "0777")) {
      	return false;
      }
   }
   try {
   	mainWindow->ifc->ChangeDir(shop->ftp_dir);
   } catch (...) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebThread::IsDir(AnsiString dir)
{
   AnsiString cur_dir;
   try {
   	cur_dir = mainWindow->ifc->RetrieveCurrentDir();
   } catch (...) {
   	return false;
   }
	try {
   	mainWindow->ifc->ChangeDir(dir);
   	mainWindow->ifc->ChangeDir(cur_dir);
   } catch (...) {
   	return false;
   }

   return true;
}
//---------------------------------------------------------------------------

bool WebThread::FtpCreateDir(AnsiString dir, AnsiString mode)
{
   if (IsDir(dir)) {
   	return true;
   }

	TStringList *dirs = SplitDirs(dir);
   for (int i = 0; i < dirs->Count; i++) {
   	if (!IsDir(dirs->Strings[i])) {
         try {
      		mainWindow->ifc->MakeDir(dirs->Strings[i]);
         } catch (...) {
         	return false;
         }
      }
   }
   if (!IsDir(dir)) {
   	return false;
   }
   if (mode != NULL) {
   	if (!FtpCommand("SITE CHMOD " + mode + " " + dir)) {
      	return false;
      }
   }
   return true;
}

//---------------------------------------------------------------------------

bool WebThread::FtpConnect(void)
{
	if (!FtpDisconnect()) {
   	return false;
   }

   AnsiString ftp_host = (shop->ftp_host.LowerCase()).Trim();

   if (ftp_host.Pos("ftp://") == 1) {
   	ftp_host = ftp_host.SubString(7, ftp_host.Length() - 6);
   }
   if (ftp_host[ftp_host.Length()] == '/') {
   	ftp_host = ftp_host.SubString(1, ftp_host.Length() - 1);
   }


   mainWindow->ifc->Host = ftp_host;
   int port;
   try {
   	port = shop->ftp_port.ToInt();
      mainWindow->ifc->Port = port;
   } catch (...) {
   }
   mainWindow->ifc->Username = shop->ftp_login;
   mainWindow->ifc->Password = shop->ftp_password;
   mainWindow->ifc->Passive = shop->ftp_passive;

   try {
   	mainWindow->ifc->Connect(true, _timeout);
   } catch (...) {
   	return false;
   }

	return true;
}
//---------------------------------------------------------------------------

bool WebThread::FtpDisconnect(void)
{
	if (mainWindow->ifc->Connected()) {
   	try {
      	mainWindow->ifc->Quit();
      } catch (...) {
      	try {
      		mainWindow->ifc->Abort();
         } catch (...) {
         	return false;
         }
      }
   }
   return true;
}
//---------------------------------------------------------------------------
bool WebThread::FtpDownload(AnsiString src, AnsiString dst)
{
	try {
   	mainWindow->ifc->Get(src, dst, true, false);
   } catch (...) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebThread::FtpUpload(AnsiString src, AnsiString dst)
{
   try {
   	mainWindow->ifc->Put(src, dst, false);
   } catch (...) {
		return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebThread::FtpRemoveFile(AnsiString name)
{
	try {
   	mainWindow->ifc->Delete(name);
   } catch (...) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebThread::FtpCommand(AnsiString command)
{
	try {
   	mainWindow->ifc->Quote(command);
   } catch(...) {
   	return false;
   }
	return true;
}
//---------------------------------------------------------------------------

bool WebThread::FtpCreateDirs(TStringList* dirs)
{
	for (int i = 0; i < dirs->Count; i++) {
      if (dirs->Strings[i].IsEmpty()) {
      	continue;
      }
   	if (!FtpCreateDir(dirs->Strings[i])) {
      	return false;
      }
   }
   return true;
}
//---------------------------------------------------------------------------

void WebThread::HttpInit(void)
{
	mainWindow->ihc->ProxyParams->Clear();
   if (mainWindow->use_proxy) {
   	mainWindow->ihc->ProxyParams->ProxyServer = mainWindow->proxy;
      mainWindow->ihc->ProxyParams->ProxyPort = mainWindow->proxy_port.ToInt();
      if (mainWindow->use_proxy_auth) {
      	mainWindow->ihc->ProxyParams->BasicAuthentication = true;
         mainWindow->ihc->ProxyParams->ProxyUsername = mainWindow->proxy_login;
         mainWindow->ihc->ProxyParams->ProxyPassword = mainWindow->proxy_pass;
      }
   }
}
//---------------------------------------------------------------------------

bool WebThread::Post(AnsiString url, TStringList* post)
{
	TStringList* cur_post;
   if (post == NULL) {
   	cur_post = _post;
   } else {
   	cur_post = post;
   }

   try {
   	_response = mainWindow->ihc->Post(url, cur_post);
   } catch (...) {
   	_response = mainWindow->ihc->ResponseText;
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebThread::Post(AnsiString url, TFileStream* file, TStringList* post)
{
	TStringList* cur_post;
   if (post == NULL) {
   	cur_post = _post;
   } else {
   	cur_post = post;
   }

   try {
   	mainWindow->ihc->Post(url, cur_post, file);
   } catch (...) {
   	_response = mainWindow->ihc->ResponseText;
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebThread::Get(AnsiString URL)
{
	try {
   	_response = mainWindow->ihc->Get(URL);
   } catch (...) {
   	_response = mainWindow->ihc->ResponseText;
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

