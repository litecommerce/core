//---------------------------------------------------------------------------

#include "WebControl.h"
#include "backup_thread.h"
#include "w_get_thread.h"
//#include "move_shop_action.h"
#include "MainWnd.h"

size_t _read_function(char *bufptr, size_t size, size_t buffer, void* from)
{
	DWORD how;
	ReadFile((HANDLE) from, bufptr, buffer, &how, NULL);
	return (int) how;
}

size_t WriteMemoryCallback(void *ptr, size_t size, size_t nmemb, void *data)
{
	register int realsize = size * nmemb;
	struct Response *mem = (struct Response *)data;

	mem->memory = (char *)realloc(mem->memory, mem->size + realsize + 1);
	if (mem->memory) {
		memcpy(&(mem->memory[mem->size]), ptr, realsize);
		mem->size += realsize;
		mem->memory[mem->size] = 0;
	}
	return realsize;
}

size_t write_with_mthread(char *ptr, size_t size, size_t nmemb, void *data)
{
	register int realsize = size * nmemb;
	return realsize;
}

//---------------------------------------------------------------------------
WebControl::WebControl(void)
{
   extern PACKAGE TmainWindow *mainWindow;
   fc = mainWindow->ifc;
   hc = mainWindow->ihc;
}

//---------------------------------------------------------------------------
WebControl::~WebControl(void)
{
//	delete fc;
//   delete hc;
}

//---------------------------------------------------------------------------
void WebControl::setUser(char* user, char* password)
{
	fc->Username = AnsiString(user);
   fc->Password = AnsiString(password);
}

//---------------------------------------------------------------------------
void WebControl::setProxy(char* proxy)
{
	hc->ProxyParams->Clear();
   hc->ProxyParams->ProxyServer = AnsiString(proxy);
//   hc->ProxyParams->ProxyPort = port;
}

//---------------------------------------------------------------------------
void WebControl::setProxy(char* proxy, char* p_user, char* p_password)
{
	hc->ProxyParams->Clear();
   hc->ProxyParams->ProxyServer = AnsiString(proxy);
   hc->ProxyParams->BasicAuthentication = true;
   hc->ProxyParams->ProxyPassword = AnsiString(p_password);
   hc->ProxyParams->ProxyUsername = AnsiString(p_user);
//   hc->ProxyParams->ProxyPort = port;
}

//---------------------------------------------------------------------------
bool WebControl::connect(char* usr, char* pass)
{
	fc->Username = AnsiString(usr);
   fc->Password = AnsiString(pass);
   try {
   	fc->Connect(true, 600);
      initial_dir = fc->RetrieveCurrentDir();
   } catch (...) {
      return false;
   }
	return true;
}

//---------------------------------------------------------------------------
bool WebControl::ftpCreateDir(char* dir)
{
   AnsiString _dir = AnsiString(dir);
   AnsiString _cur = fc->RetrieveCurrentDir();

   try {
   	fc->ChangeDir(_dir);
      fc->ChangeDir(_cur);
      return true;
   } catch (...) {
   	try {
			fc->MakeDir(_dir);
      } catch (...) {
      	return false;
      }
   }
   return true;
}

//---------------------------------------------------------------------------
bool WebControl::ftpUploadFile(char* file, char* dir)
{
   char* file_name = new char;
	if (dir != NULL) {
		sprintf(file_name, "%s/%s", dir, file);
	} else {
		sprintf(file_name, "%s\0", file);
	}
   try {
   	fc->Put(AnsiString(file_name), AnsiString(file), false);
   } catch(...) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebControl::ftpDownloadFile(char* ftp_file, char* local_file)
{
   createDirs(getDir(local_file));
/*
   if (FileExists(local_file)) {
   	DeleteFile(local_file);
   }
*/   
   try {
   	fc->Get(ftp_file, local_file, true, false);
   } catch (...) {
   	return false;
   }
   return true;
}
//---------------------------------------------------------------------------

bool WebControl::ftpCommand(char* command)
{
   AnsiString cmd = AnsiString(command);
   try {
   	fc->Quote(cmd);
   } catch (...) {
   	return false;
   }
   return true;
}

//---------------------------------------------------------------------------

// Need to be bool
void WebControl::setFtpHomeUrl(char* home)
{
   try {
      fc->ChangeDir(initial_dir);
		fc->ChangeDir(AnsiString(home));
   } catch (...) {
   }
}
//---------------------------------------------------------------------------

void WebControl::setFtpHost(char* host,char* port)
{
	fc->Host = AnsiString(host);
   fc->Port = StrToInt(AnsiString(port));
}
//---------------------------------------------------------------------------

void WebControl::ftpCreatePath(char* pathname)
{
	char temp[200];
	sprintf(temp, "%s\0", pathname);
	char *p;
	char *s;
	int result;
	p = temp;
	while( p && *p ) {
		s = strchr(p,'/');
		if(s) {
			*s = '\0';
		}
		ftpCreateDir(temp);
		if(s) {
			*s = '/';
			p = s + 1;
		}
		else {
			break;
		}
	}
}
//---------------------------------------------------------------------------

bool WebControl::ftpCreateDirRec(char* dir, char* path)
{
	char _path[MAX_PATH];
	char _dir[MAX_PATH];

	GetCurrentDirectory(MAX_PATH, (char*)_dir);

	SetCurrentDirectory(dir);
	WIN32_FIND_DATA FindFileData;
	HANDLE hFind;
	hFind = FindFirstFile("*", &FindFileData);
	while (FindNextFile(hFind, &FindFileData)) {
		if((FindFileData.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)
				&& (strcmp(FindFileData.cFileName, "."))
				&& (strcmp(FindFileData.cFileName, ".."))) {
			if (strlen(path) > 0) {
				sprintf((char*)_path, "%s/%s\0", path, FindFileData.cFileName);
			} else {
				sprintf((char*)_path, "%s\0", FindFileData.cFileName);
			}

			if (!ftpCreateDir((char*)_path)) {
				return false;
			}
			if (!ftpCreateDirRec(FindFileData.cFileName, (char*)_path)) {
				return false;
			}
		}
	}
	SetCurrentDirectory((char*)_dir);
	FindClose(hFind);
	return true;
}
//---------------------------------------------------------------------------

void WebControl::ftpFillDirQue(char* dir, char* path)
{
/*
	char _path[MAX_PATH];
	char _dir[MAX_PATH];

	GetCurrentDirectory(MAX_PATH, (char*)_dir);

	SetCurrentDirectory(dir);
	WIN32_FIND_DATA FindFileData;
	HANDLE hFind;
	hFind = FindFirstFile("*", &FindFileData);
	while (FindNextFile(hFind, &FindFileData)) {
		if((FindFileData.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)
				&& (strcmp(FindFileData.cFileName, "."))
				&& (strcmp(FindFileData.cFileName, ".."))) {
			if (strlen(path) > 0) {
				sprintf((char*)_path, "%s/%s\0", path, FindFileData.cFileName);
			} else {
				sprintf((char*)_path, "%s\0", FindFileData.cFileName);
			}

			char command[255];
			sprintf(command, "MKD %s", (char*)_path);
			post_que = curl_slist_append(post_que, command);

			ftpFillDirQue(FindFileData.cFileName, (char*)_path);
		}
	}
	SetCurrentDirectory((char*)_dir);
	FindClose(hFind);
   */
}

//---------------------------------------------------------------------------
void WebControl::_ftpFillDirQue(TStringList *dirs)
{
/*
	for (int i = 0; i < dirs->Count; i++) {
		post_que = curl_slist_append(post_que, ("MKD " + dirs->Strings[i]).c_str());
	}
*/
}

//---------------------------------------------------------------------------
bool WebControl::ftpCreateDirRec(TStringList *dirs)
{
/*
	_ftpFillDirQue(dirs);
	bool result = ftpCommand(post_que);

	post_que = NULL;
	return !result;
*/
	return false;
}

//---------------------------------------------------------------------------
bool WebControl::ftpCreateDirRec(char* dir)
{
/*
	ftpFillDirQue(dir, "");
	bool result = ftpCommand(post_que);
	post_que = NULL;
	return !result;
*/
	return false;
}

//---------------------------------------------------------------------------
void WebControl::setLocalFileDir(char* dir)
{
	sprintf(local_file_dir, "%s/\0", dir);
}

//---------------------------------------------------------------------------
char* WebControl::getResponse()
{
	return _post_response.c_str();
}

//---------------------------------------------------------------------------
int WebControl::getResponseLenght()
{
	return _post_response.Length();
}

//---------------------------------------------------------------------------
void WebControl::clearResponse()
{
}

//---------------------------------------------------------------------------
bool WebControl::getURL(char* url)
{
   /*
	CURLcode res;
	if (use_proxy) {
		curl_easy_setopt(curl, CURLOPT_PROXY, p_server_port);
		if (proxy_auth) {
			curl_easy_setopt(curl, CURLOPT_PROXYUSERPWD, p_usr_pwd);
		}
		curl_easy_setopt(curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
	}

	curl_easy_setopt(curl, CURLOPT_URL, url);
	curl_easy_setopt(curl, CURLOPT_POSTQUOTE, NULL);
	curl_easy_setopt(curl, CURLOPT_UPLOAD, FALSE);
	curl_easy_setopt(curl, CURLOPT_USERPWD, usr_pwd);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
	curl_easy_setopt(curl, CURLOPT_FILE, (void *)&resp);
	res = curl_easy_perform(curl);
	curl_easy_setopt(curl, CURLOPT_URL, ftp_home_url);
	return (res == CURLE_OK);
   */
   return false;
}

//---------------------------------------------------------------------------
bool WebControl::getData(char* url)
{
   /*
	CURLcode res;
	if (use_proxy) {
		curl_easy_setopt(curl, CURLOPT_PROXY, p_server_port);
		if (proxy_auth) {
			curl_easy_setopt(curl, CURLOPT_PROXYUSERPWD, p_usr_pwd);
		}
		curl_easy_setopt(curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
	}

	curl_easy_setopt(curl, CURLOPT_URL, url);
	curl_easy_setopt(curl, CURLOPT_POSTQUOTE, NULL);
	curl_easy_setopt(curl, CURLOPT_UPLOAD, FALSE);
	curl_easy_setopt(curl, CURLOPT_USERPWD, usr_pwd);
	curl_easy_setopt(curl, CURLOPT_FILE, (void *)&resp);
	res = curl_easy_perform(curl);
	curl_easy_setopt(curl, CURLOPT_URL, ftp_home_url);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
	return (res == CURLE_OK);
   */
   return false;
}

//---------------------------------------------------------------------------
bool WebControl::getData(char* url, char* post, void* dlg)
{
   /*
	CURLcode res;
	if (use_proxy) {
		curl_easy_setopt(curl, CURLOPT_PROXY, p_server_port);
		if (proxy_auth) {
			curl_easy_setopt(curl, CURLOPT_PROXYUSERPWD, p_usr_pwd);
		}
		curl_easy_setopt(curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
	}

	curl_easy_setopt(curl, CURLOPT_URL, url);
	curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post);
	curl_easy_setopt(curl, CURLOPT_POSTQUOTE, NULL);
	curl_easy_setopt(curl, CURLOPT_UPLOAD, FALSE);
	curl_easy_setopt(curl, CURLOPT_USERPWD, usr_pwd);
	curl_easy_setopt(curl, CURLOPT_FILE, (void *)dlg);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_with_thread);
	res = curl_easy_perform(curl);
	curl_easy_setopt(curl, CURLOPT_URL, ftp_home_url);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
	return (res == CURLE_OK);
   */return false;
}

//---------------------------------------------------------------------------
bool WebControl::getDataMoving(char* url, char* post, void* dlg)
{
   /*
	CURLcode res;
	if (use_proxy) {
		curl_easy_setopt(curl, CURLOPT_PROXY, p_server_port);
		if (proxy_auth) {
			curl_easy_setopt(curl, CURLOPT_PROXYUSERPWD, p_usr_pwd);
		}
		curl_easy_setopt(curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
	}

	curl_easy_setopt(curl, CURLOPT_URL, url);
	curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post);
	curl_easy_setopt(curl, CURLOPT_POSTQUOTE, NULL);
	curl_easy_setopt(curl, CURLOPT_UPLOAD, FALSE);
	curl_easy_setopt(curl, CURLOPT_USERPWD, usr_pwd);
	curl_easy_setopt(curl, CURLOPT_FILE, (void *)dlg);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_with_mthread);
	res = curl_easy_perform(curl);
	curl_easy_setopt(curl, CURLOPT_URL, ftp_home_url);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
	return (res == CURLE_OK);
   */ return false;
}

//---------------------------------------------------------------------------
bool WebControl::getWData(char* url, char* post, void* dlg)
{
   /*
	CURLcode res;
	if (use_proxy) {
		curl_easy_setopt(curl, CURLOPT_PROXY, p_server_port);
		if (proxy_auth) {
			curl_easy_setopt(curl, CURLOPT_PROXYUSERPWD, p_usr_pwd);
		}
		curl_easy_setopt(curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
	}

	curl_easy_setopt(curl, CURLOPT_URL, url);
	curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post);
	curl_easy_setopt(curl, CURLOPT_POSTQUOTE, NULL);
	curl_easy_setopt(curl, CURLOPT_UPLOAD, FALSE);
	curl_easy_setopt(curl, CURLOPT_USERPWD, usr_pwd);
	curl_easy_setopt(curl, CURLOPT_FILE, (void *)dlg);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, _vcl_write_w_get_function);
	res = curl_easy_perform(curl);
	curl_easy_setopt(curl, CURLOPT_URL, ftp_home_url);
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
	return (res == CURLE_OK);
   */return false;
}

//---------------------------------------------------------------------------
void WebControl::setMemoryResponse()
{
   /*
	curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
	curl_easy_setopt(curl, CURLOPT_FILE, (void *)&resp);
   */
}

//---------------------------------------------------------------------------
void WebControl::setFileResponse()
{
	//curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, _write_function);
}

//---------------------------------------------------------------------------
bool WebControl::sendPost(char* url, TStringList* post)
{

   try {
		_post_response = hc->Post(AnsiString(url), post);
   } catch (...) {
   	return false;
   }
	return true;
}

//---------------------------------------------------------------------------
void WebControl::createLocaPath(char* path)
{
	char temp[200];
	sprintf(temp, "%s\0", path);
	char *p;
	char *s;
	int result;
	p = temp;
	while( p && *p ) {
		s = strchr(p,'/');
		if(s) {
			*s = '\0';
		}
		CreateDirectory(temp, NULL);
		if(s) {
			*s = '/';
			p = s + 1;
		}
		else {
			break;
		}
	}
}
