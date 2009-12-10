//---------------------------------------------------------------------------

#include <vcl.h>
#include <Registry.hpp>
#include <regex/regexpr.hpp>
#include <zlib.h>
#pragma hdrstop

#include "u_main.h"
//---------------------------------------------------------------------------
#pragma package(smart_init)

#pragma link "IdBaseComponent"
#pragma link "IdComponent"
#pragma link "IdFTP"
#pragma link "IdTCPClient"
#pragma link "IdTCPConnection"
#pragma link "IdHTTP"

#pragma link "extract"
#pragma link "license"
#pragma link "settings"
#pragma link "upload"
#pragma link "test"
#pragma link "splash"
#pragma link "completed"

#pragma resource "*.dfm"
T_main *_main;

//---------------------------------------------------------------------------
void CreateArchive(LPSTR filename, LPSTR outname)
{
	DWORD arx_len;
	DWORD read;
	BYTE* archive;
	CopyFile(filename, "temp.arx", FALSE);
	HANDLE hFile = CreateFile("temp.arx", GENERIC_READ, 0, (LPSECURITY_ATTRIBUTES) NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, (HANDLE) NULL);
	SetFilePointer (hFile, -4, NULL, FILE_END);
	ReadFile(hFile, (LPVOID)&arx_len, 4, &read, NULL);
	SetFilePointer (hFile, -1 * (arx_len + 4), NULL, FILE_END);
	archive = new BYTE[arx_len + 1];
	ReadFile(hFile, (LPVOID)archive, arx_len, &read, NULL);
	CloseHandle(hFile);
	hFile = CreateFile(outname, GENERIC_WRITE, 0, (LPSECURITY_ATTRIBUTES) NULL, CREATE_ALWAYS, FILE_ATTRIBUTE_NORMAL, (HANDLE) NULL);
	WriteFile(hFile, archive, arx_len, &read, NULL);
	CloseHandle(hFile);
	delete(archive);
	DeleteFile("temp.arx");
}

//---------------------------------------------------------------------------
void rDeleteDir(AnsiString dirname) {
  TSearchRec SearchRec;
  int Result;
  AnsiString tempstr;

  tempstr.cat_printf("%s\\*.*",dirname);
  Result = FindFirst(tempstr, faAnyFile, SearchRec);
  while (Result == 0) {
    tempstr = "";
    tempstr.cat_printf("%s\\%s",dirname, SearchRec.Name);
    if (SearchRec.Name != "." && SearchRec.Name != ".."){
      if (SearchRec.Attr & faDirectory) {
        rDeleteDir(tempstr);
      } else {
        DeleteFile(tempstr);
      }
    }
    Result = FindNext(SearchRec);
  }
  FindClose(SearchRec);
  RemoveDirectory(dirname.c_str());
}

__fastcall T_main::T_main(TComponent* Owner)
	: TForm(Owner)
{
// intrface
	frames = new TList();
   frames->Add((void*) _extract);
   frames->Add((void*) _license);
   frames->Add((void*) _settings);
   frames->Add((void*) _upload);
   frames->Add((void*) _splash);
   frames->Add((void*) _completed);
   frames->Add((void*) _test);

// options
	archive_name = "litecommerce.tar.gz";
   tar_name = "archive.tar";
   temp_dir = GetCurrentDir() + "\\LiteCommerce";
   license_file = "COPYRIGHT";

// help file options
   help_resource_name = "HELP_FILE";
   help_resource_type = "HELP";
   help_file = "settings.hlp";

// data
	test_success = false;
   remove_files = true;

   files = new TStringList();
   dirs = new TStringList();

   thread = NULL;
}
//---------------------------------------------------------------------------

void __fastcall T_main::FormCreate(TObject *Sender)
{
	getSettings();

   remove_files = true;

   // Extracting help file from resource
   TResourceStream* res;
   res = new TResourceStream((int)MainInstance, help_resource_name, help_resource_type.c_str());
   res->SaveToFile(help_file);
   delete res;
   //

	// Unpacking archive
   CreateArchive(Application->ExeName.c_str(), archive_name.c_str());
   //
   try {
		log = new TFileStream("install.log", fmCreate | fmShareDenyNone);
	} catch (...) {
   	log = NULL;
   }
   for (int i=0; i <= ParamCount(); i++) {
   	if (LowerCase(ParamStr(i)) == "-e") {
         ShowExtract();
         extract_only = true;
         return;
      }
   }
   extract_only = false;
   OnCloseQuery = FormCloseQuery;
   ShowSplash();
}
//---------------------------------------------------------------------------

void __fastcall T_main::FormClose(TObject *Sender, TCloseAction &Action)
{
   if (log != NULL) {
		delete log;
   }
   delete files;
   delete frames;
   delete dirs;
   if (remove_files) {
      rDeleteDir(temp_dir);
   }
}
//---------------------------------------------------------------------------

void __fastcall T_main::OnExtract(TObject* Sender)
{
	if (!extract_only) {
		ShowLicense();
   } else {
   	remove_files = false;
   	Close();
   }
   thread = NULL;
}
//---------------------------------------------------------------------------

void __fastcall T_main::OnUpload(TObject* Sender)
{
	_main->upload_run = false;
   thread = NULL;
	ShowCompleted();
}
//---------------------------------------------------------------------------

void __fastcall T_main::OnTest(TObject* Sender)
{
	_test->_back->Enabled = true;
   _test->_continue->Enabled = test_success;
   thread = NULL;
}
//---------------------------------------------------------------------------

void T_main::ShowSplash(void)
{
   DisableAllframes();
   _splash->Visible = true;
   _splash->Align = alClient;
   Caption = "  LiteCommerce installer";
   Height = 390;
}
//---------------------------------------------------------------------------

void T_main::ShowExtract(void)
{
   DisableAllframes();
   _extract->Visible = true;
   _extract->Align = alClient;
   Height = 320;
   t_extract = new extract_thread(true);
   thread = (TThread*) t_extract;
   t_extract->OnTerminate = OnExtract;
   t_extract->Resume();
}
//---------------------------------------------------------------------------

void T_main::ShowLicense(void)
{
   DisableAllframes();
   Height = 500;
   Caption = "  LiteCommerce installer - License agreement";
   _license->Visible = true;
   _license->Align = alClient;
   TFileStream* fl_license;
   try {
   	if (!FileExists(temp_dir + "\\" + license_file)) {
   		_license->display->Lines->Add("Could not open file with license text");
      	_license->_agree->Enabled = false;
      	return;
      }
   	fl_license = new TFileStream (temp_dir + "\\" + license_file, fmOpenRead);
      char* l_text = new char[fl_license->Size];
      fl_license->Read(l_text, fl_license->Size);
      l_text[fl_license->Size - 1] = 0;
      _license->display->Lines = ReformatLicense(AnsiString(l_text));
      delete l_text;
   } catch (...) {
   	_license->display->Lines->Add("Could not open file with license text");
      _license->_agree->Enabled = false;
   }
   delete fl_license;
}
//---------------------------------------------------------------------------

void T_main::ShowSettings(void)
{
   DisableAllframes();
   Caption = "  LiteCommerce installer - Settings";

	_settings->passive_mode->Checked = passive;
   _settings->ftp_server->Text = ftp_host;
   _settings->ftp_port->Text = ftp_port;
   _settings->ftp_dir->Text = ftp_dir;
   _settings->http_address->Text = http_address;
   _settings->ftp_login->Text = ftp_login;
   _settings->use_proxy->Checked = proxy;
   _settings->proxy_server->Text = proxy_server;
   _settings->proxy_port->Text = proxy_port;
   _settings->proxy_auth->Checked = proxy_auth;
   _settings->proxy_login->Text = proxy_login;

   _settings->proxy_server->Enabled = _settings->use_proxy->Checked;
	_settings->proxy_port->Enabled = _settings->use_proxy->Checked;
	_settings->proxy_auth->Enabled = _settings->use_proxy->Checked;
	_settings->proxy_login->Enabled = _settings->use_proxy->Checked & _settings->proxy_auth->Checked;
	_settings->proxy_pass->Enabled = _settings->use_proxy->Checked & _settings->proxy_auth->Checked;

   _settings->Visible = true;
   _settings->ftp_server->SetFocus();

   _settings->Align = alClient;

   Height = 535;
}
//---------------------------------------------------------------------------

void T_main::ShowUpload(void)
{
   DisableAllframes();
   Caption = "  LiteCommerce installer - Uploading files";
   _upload->Visible = true;
   _upload->Align = alClient;
   _upload->total_progress->Min = 0;
   _upload->total_progress->Max = files_count - 1;
   Height = 320;
   t_upload = new upload_thread(true);
   thread = (TThread*) t_upload;
   t_upload->OnTerminate = OnUpload;
   t_upload->Resume();
}
//---------------------------------------------------------------------------

void T_main::ShowCompleted(void)
{
   DisableAllframes();
   OnCloseQuery = NULL;
   Caption = "  LiteCommerce installer - Phase 1 completed";
   _completed->Visible = true;
   _completed->Align = alClient;
   _completed->Label5->Text = _main->http_address + "/install.php";

   Height = 390;
}
//---------------------------------------------------------------------------

void T_main::ShowTest(void)
{
   DisableAllframes();
   Caption = "  LiteCommerce installer - Settings test";
   _test->Visible = true;
   _test->Align = alClient;
   _test->_back->Enabled = false;
   _test->_continue->Enabled = false;

   t_test = new test_thread(true);
   thread = (TThread*) t_test;
   t_test->OnTerminate = OnTest;
   t_test->Resume();

   Height = 390;
}
//---------------------------------------------------------------------------

void T_main::DisableAllframes(void)
{
	for (int i = 0; i < frames->Count; i++) {
   	((TFrame*)frames->Items[i])->Visible = false;
   }
}
//---------------------------------------------------------------------------

// Переформатирование лицензии для правильного отображения
TStringList* T_main::ReformatLicense(AnsiString license)
{
   TRegExpr *regex = new TRegExpr();
   TStringList *out = new TStringList();
   TStringList *tmp = new TStringList();

   // 1. пометить все подпараграфы в тексте (типа \n2.1)
   regex->Expression = "[\n]([0-9][\.][0-9])";
   license = regex->Replace(license, "<<split_pattern_here>>$1", true);

   // 2. разбить лицензию на параграфы (типа \n\n2.)
   regex->Expression = "[\n][\n]";
   regex->Split(license, tmp);

   // 3. заменить в каждом параграфе переводы каретки
   // на пробелы
   regex->Expression = "[\n]";
   for (int i = 0; i < tmp->Count; i++) {
   	AnsiString str = tmp->Strings[i];
      str = regex->Replace(str, " ", false);
      tmp->Strings[i] = str + "\r\n";
   }

   // 4. разбить все параграфы на подпараграфы
   regex->Expression = "<<split_pattern_here>>";
   for (int i = 0; i < tmp->Count; i++) {
      TStringList* addon = new TStringList;
   	AnsiString str = tmp->Strings[i];
      regex->Split(str, addon);
      // Если есть подпараграфы, поставить перевод каретки после
      // названия параграфа
      if (addon->Count > 1) {
      	out->Add(addon->Strings[0]);
      	out->Add("");
         for (int i = 1; i < addon->Count; i++) {
         	out->Add(addon->Strings[i]);
         }
		// иначе просто добавить параграф
      } else {
      	out->AddStrings(addon);
      }
      delete addon;
   }

   delete tmp;
   delete regex;
	return out;
}
//---------------------------------------------------------------------------

void T_main::getSettings()
{
	TRegistry *reg = new TRegistry();
   if (reg->OpenKey("Software\\LiteCommerce\\InstalledShop", false)) {

   	ftp_host = reg->ReadString("ftp_host");
   	ftp_port = reg->ReadString("ftp_port");
   	ftp_dir = reg->ReadString("ftp_dir");
   	ftp_login = reg->ReadString("ftp_login");
   	proxy_server = reg->ReadString("proxy_server");
   	proxy_port = reg->ReadString("proxy_port");
   	proxy_login = reg->ReadString("proxy_login");
   	http_address = reg->ReadString("http_address");

   	passive = reg->ReadBool("passive");
   	proxy = reg->ReadBool("proxy");
   	proxy_auth = reg->ReadBool("proxy_auth");

   	reg->CloseKey();
   } else {
   	passive = true;
      ftp_port = "21";
   }
   delete reg;
}
//---------------------------------------------------------------------------

void T_main::setSettings()
{
	TRegistry *reg = new TRegistry();
   reg->OpenKey("Software\\LiteCommerce\\InstalledShop", true);

	reg->WriteString("ftp_host", ftp_host);
   reg->WriteString("ftp_port", ftp_port);
   reg->WriteString("ftp_dir", ftp_dir);
   reg->WriteString("ftp_login", ftp_login);
   reg->WriteString("proxy_server", proxy_server);
   reg->WriteString("proxy_port", proxy_port);
   reg->WriteString("proxy_login", proxy_login);
   reg->WriteString("http_address", http_address);

   reg->WriteBool("passive", passive);
   reg->WriteBool("proxy", proxy);
   reg->WriteBool("proxy_auth", proxy_auth);

   reg->CloseKey();
   delete reg;
}
//---------------------------------------------------------------------------

void T_main::updateSettings()
{
   http_address = http_address.LowerCase();
   http_address = http_address.Trim();
   AnsiString tmp = http_address.LowerCase();
	if(tmp.Pos("http://") != 1) {
   	http_address = "http://" + http_address;
   }
   if ((tmp.c_str())[tmp.Length() - 1] == '/') {
   	http_address = http_address.SubString(1, http_address.Length() - 1);
   }

   ftp_host = ftp_host.LowerCase();
   ftp_host = ftp_host.Trim();
   tmp = ftp_host.LowerCase();
   if (tmp.Pos("ftp://") == 1) {
   	ftp_host = ftp_host.Delete(1, 6);
   }
   if ((tmp.c_str())[tmp.Length() - 1] == '/') {
   	ftp_host = ftp_host.SubString(1, ftp_host.Length() - 1);
   }
}
//---------------------------------------------------------------------------

void T_main::WriteLog(AnsiString msg)
{
   if (log == NULL) {
   	return;
   }
   int len = msg.Length();
	this->log->Write(msg.c_str(), len);
}
//---------------------------------------------------------------------------

void __fastcall T_main::FormKeyPress(TObject *Sender, char &Key)
{
	if (Key == 27) {
   	Close();
   }
}
//---------------------------------------------------------------------------

void __fastcall T_main::FormCloseQuery(TObject *Sender, bool &CanClose)
{
   if (thread != NULL) {
   	thread->Suspend();
   }
	if (Application->MessageBox("Are you sure you want to cancel LiteCommerce installation?", "Confirm", MB_YESNO|MB_ICONQUESTION) == IDNO) {
   	if (thread != NULL) {
         thread->Resume();
      }
   	CanClose = false;
   } else {
   	if (thread != NULL) {
      	thread->OnTerminate = NULL;
         thread->Terminate();
      }
   	CanClose = true;
   }
}
//---------------------------------------------------------------------------

