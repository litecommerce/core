//---------------------------------------------------------------------------

#include <vcl.h>
#include "u_main.h"
#include <regex/regexpr.hpp>
#pragma hdrstop

#include "test_thr.h"
#pragma package(smart_init)

//---------------------------------------------------------------------------

TStringList* splitDirs(AnsiString dir) {
   TRegExpr *regex = new TRegExpr();
   TStringList *out = new TStringList();
   regex->Expression = "[\\\\\/]";
   regex->Split(dir, out);
   for (int i = 1; i < out->Count; i++) {
   	out->Strings[i] = out->Strings[i - 1] + "/" + out->Strings[i];
   }
   delete regex;
	return out;
}
//---------------------------------------------------------------------------

__fastcall test_thread::test_thread(bool CreateSuspended)
	: TThread(CreateSuspended)
{
   localName = "upload_test.txt";
   remoteName = localName;
}
//---------------------------------------------------------------------------

bool test_thread::ConnectTest(void)
{
   Print("Checking FTP settings ... ");
	_main->WriteLog("Connecting to server ... ");

   Connect();

	if (!_main->ifc->Connected()) {
      return false;
   }
  	_main->WriteLog("[OK]\n");
   AddToLine("[OK]", 0);
   return true;
}
//---------------------------------------------------------------------------
void test_thread::OnConnectError()
{
	AddToLine("[FAILED]", 0);
   Print("\r\nInstallation wizard was unable to connect to the specified ftp server " + _main->ftp_host);
   Print("\r\nPossible reasons: \r\n");
   Print("1. Ftp server name or port are specified incorrectly. Please check your ftp access information.");
   Print("2. Your firewall does not allow passive mode ftp connections. Try to uncheck passive mode checkbox to resolve this.");
   _main->WriteLog("[FAILED]\n");
   _main->WriteLog("-------------------------------------\n\n");
}
//---------------------------------------------------------------------------

bool test_thread::LoginTest(void)
{
	Print("Logging in ... ");
	_main->WriteLog("Loging in ... ");

   if (!Login()) {
   	return false;
   }
   _main->WriteLog("[OK]\n");
   AddToLine("[OK]", 1);
   return true;
}
//---------------------------------------------------------------------------

void test_thread::OnLoginError(void)
{
	AddToLine("[FAILED]", 1);
   Print("\r\n\Installation wizard was unable to login into your FTP server " + _main->ftp_host);
   Print("\r\nPossible reasons: \r\n");
   Print("1. Ftp server name, port, user name or password are specified incorrectly. Please check your ftp access information.");
   _main->WriteLog("[FAILED]\n");
   _main->WriteLog("-------------------------------------\n\n");
}
//---------------------------------------------------------------------------

bool test_thread::UploadTest(void)
{
   Print("Uploading test file ... ");
   _main->WriteLog("Uploading test file ... ");

	// CD home dir
   try {
   	_main->ifc->ChangeDir(_main->ftp_dir);
   } catch (...) {
   	CreateHomeDir();
      try {
   		_main->ifc->ChangeDir(_main->ftp_dir);
      } catch (...) {
      	return false;
      }
   }

	CreateTestFile(localName);

   try {
   	_main->ifc->Put(localName, remoteName, false);
   } catch (...) {
      return false;
   }

   if (!IsFile(remoteName)) {
      return false;
   }

   AddToLine("[OK]", 2);
   _main->WriteLog("[OK]\n");

   return true;
}
//---------------------------------------------------------------------------

void test_thread::OnUploadError(void)
{
	DeleteFile(localName);
   AddToLine("[FAILED]", 2);
   Print("\r\nInstallation wizard was unable to make test upload to your ftp server.");
   Print("\r\nPossible reasons: \r\n");
   Print("1. You specified 'Upload directory' parameter incorrectly. The Upload directory does not exist or you do not have write permissions for this directory.");
   Print("2. Your firewall does not allow passive mode ftp connections. Try to uncheck passive mode checkbox to resolve this.");

   _main->WriteLog("[FAILED]\n");
  	_main->WriteLog("-------------------------------------\n\n");
}
//---------------------------------------------------------------------------

bool test_thread::HTTPTest(void)
{
   AnsiString Response;

   Print("Verifying shop URL ... ");
   _main->WriteLog("Verifying shop URL ... ");

   _main->ihc->ProxyParams->Clear();
   if (_main->proxy) {
      if (_main->proxy_server == "" || _main->proxy_port =="") {
      	return false;
      }
   	_main->ihc->ProxyParams->ProxyServer = _main->proxy_server;
   	_main->ihc->ProxyParams->ProxyPort = StrToInt(_main->proxy_port);

      if (_main->proxy_auth) {
      	if (_main->proxy_login == "" || _main->proxy_password == "") {
         	return false;
         }
         _main->ihc->ProxyParams->BasicAuthentication = true;
   		_main->ihc->ProxyParams->ProxyUsername = _main->proxy_login;
   		_main->ihc->ProxyParams->ProxyPassword = _main->proxy_password;
      }
   }
   try {
   	Response = _main->ihc->Get(_main->http_address + "/" + remoteName);
   } catch (...) {
      return false;
   }

   if (Response != "OK") {
      return false;
   }

   AddToLine("[OK]", 3);
   return true;
}
//---------------------------------------------------------------------------

void test_thread::OnHTTPError(void)
{
	DeleteFile(localName);
   try {
   	_main->ifc->Delete(remoteName);
   } catch(...) {
   	Print("\r\nWARNING: Installation wizard was unable to remove test file from your FTP server\r\n");
   }
   AddToLine("[FAILED]", 3);
   Print("\r\nInstallation wizard was unable to access your store installation site via HTTP/WWW.\r\n");
	Print("Possible reasons:\r\n");
   Print ("1. Domain name or path to your site are specified incorrectly. Please check your shop URL settings.");
	if (_main->proxy) {
		Print("2. Proxy address or port are specified incorrectly or your proxy server requires authentication with login and password. Please check your Proxy settings.");
      if (_main->proxy_auth) {
      	Print("3. Proxy user name or password are specified incorrectly. Please check your Proxy authentication settings.");
      }
   }
   Print ("\r\nAlso make sure that 'Upload directory' and 'Shop URL' are pointing to the same location on your server.");
   _main->test_success = false;

   _main->WriteLog("[FAILED]\n");
  	_main->WriteLog("-------------------------------------\n\n");
}
//---------------------------------------------------------------------------

void __fastcall test_thread::Execute()
{
   Run();
   Disconnect();
}
//---------------------------------------------------------------------------

void __fastcall test_thread::Connect(void)
{
   if (_main->ifc->Connected()) {
   	try {
      	_main->ifc->Quit();
      } catch (...) {
      	_main->ifc->Abort();
      }
   }

   _main->ifc->Host = _main->ftp_host;
   _main->ifc->Port = StrToInt(_main->ftp_port);
   _main->ifc->Passive = _main->passive;

   try {
   	_main->ifc->Connect(false, 600);
   } catch(...) {
   }
}
//---------------------------------------------------------------------------

bool test_thread::Login(void)
{
   _main->ifc->Username = _main->ftp_login;
   _main->ifc->Password = _main->ftp_password;
   try {
   	_main->ifc->Login();
   } catch(...) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool test_thread::IsDir(AnsiString dir)
{
	AnsiString cur = _main->ifc->RetrieveCurrentDir();
   try {
   	_main->ifc->ChangeDir(dir);
   } catch (...) {
   	return false;
   }
   _main->ifc->ChangeDir(cur);
   return true;
}
//---------------------------------------------------------------------------

bool test_thread::IsFile(AnsiString file)
{
   int pos = file.LastDelimiter("/");
   AnsiString filename = file.SubString(pos + 1, file.Length() - pos);
   AnsiString path = file.SubString(1, pos - 1);

   try {
      _main->ifc->List(NULL, path, true);
      for (int i = 0; i < _main->ifc->DirectoryListing->Count; i++) {
      	if (_main->ifc->DirectoryListing->Items[i]->FileName == filename &&
         	 _main->ifc->DirectoryListing->Items[i]->ItemType == ditFile ) {
			   return true;
         }
      }
   } catch (...) {
   	return false;
   }
   return false;
}
//---------------------------------------------------------------------------

void __fastcall test_thread::Run()
{
	_main->test_success = false;
   ClearDisplay();
   if (!ConnectTest()) {
      OnConnectError();
   	return;
   }
   if (!LoginTest()) {
   	OnLoginError();
      return;
   }
	if (!UploadTest()) {
   	OnUploadError();
      return;
   }
   if (!HTTPTest()) {
   	OnHTTPError();
      return;
   }
   OnSuccess();
}
//---------------------------------------------------------------------------

void __fastcall test_thread::Disconnect(void)
{
   if (!_main->ifc->Connected()) {
   	return;
   }
   try {
   	_main->ifc->Quit();
   } catch (...) {
   	_main->WriteLog("* ERROR: could not disconnect from the server\n");
   }
}
//---------------------------------------------------------------------------

void __fastcall test_thread::_print()
{
	_main->_test->display->Lines->Add(this->_text);
}
//---------------------------------------------------------------------------

void __fastcall test_thread::_add_to_line(void)
{
   _main->_test->display->Lines->Strings[_line_num] = _main->_test->display->Lines->Strings[_line_num] + _text;
}
//---------------------------------------------------------------------------
void __fastcall test_thread::_clear_display(void)
{
	_main->_test->display->Clear();
}

//---------------------------------------------------------------------------

void test_thread::Print(AnsiString text)
{
	this->_text = text;
   Synchronize(_print);
}
//---------------------------------------------------------------------------

void test_thread::AddToLine(AnsiString text, int line)
{
	this->_text = text;
   this->_line_num = line;
   Synchronize(_add_to_line);
}
//---------------------------------------------------------------------------

void test_thread::ClearDisplay(void)
{
	Synchronize(_clear_display);
}
//---------------------------------------------------------------------------

void test_thread::CreateHomeDir(void)
{
   TStringList *home = splitDirs(_main->ftp_dir);
   for(int i = 0; i < home->Count; i++) {
      if (!IsDir(home->Strings[i])) {
      	try {
      		_main->ifc->MakeDir(home->Strings[i]);
         } catch (...) {
         }
      }
   }
   delete home;
}
//---------------------------------------------------------------------------

void test_thread::CreateTestFile(AnsiString name)
{
   TFileStream *test = new TFileStream(name, fmCreate);
   test->Write("OK", 2);
   delete test;
}
//---------------------------------------------------------------------------

void test_thread::OnSuccess(void)
{
	DeleteFile(localName);
   Print ("\r\nAll settings tests passed successfully, click \"Continue\" button to continue installation.");
	_main->test_success = true;
}
//---------------------------------------------------------------------------

