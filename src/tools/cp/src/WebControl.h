#include <vcl.h>
#include <classes.hpp>
#include <stdio.h>
#include "FileUtil.h"

#include "IdBaseComponent.hpp"
#include "IdComponent.hpp"
#include "IdFTP.hpp"
#include "IdHTTP.hpp"
#include "IdTCPClient.hpp"
#include "IdTCPConnection.hpp"

#pragma hdrstop
#ifndef WebControlH
#define WebControlH

//---------------------------------------------------------------------------

struct Response
{
	char *memory;
	size_t size;
};

class WebControl
{
protected:
	char ftp_host[300];
   char ftp_port[10];
	char temp_url[300];
	char usr_pwd[255];
	char p_usr_pwd[255];
	char p_server_port[255];
	char local_file_dir[255];
   AnsiString initial_dir;

	bool use_proxy;
	bool proxy_auth;
	Response resp;
	char ftp_home_url[600];

   AnsiString _post_response;
   void *_main;
	TIdFTP *fc;
	TIdHTTP *hc;

public:
	WebControl(void);
	bool connect(char* usr, char* pass);

	void setUser(char*, char*);
	void setProxy(char*);
	void setProxy(char*, char*, char*);
	void setFtpHomeUrl(char*);
	void setFtpHost(char*,char*);

	bool ftpCreateDir(char*);
	bool ftpUploadFile(char* dir, char* file = NULL);
   bool ftpDownloadFile(char* file, char* file_name);
   bool _ftpDownloadFile(char* file, /*char* file_name,*/ void* data);
	bool ftpCommand(char*);
	void ftpCreatePath(char*);

	bool ftpCreateDirRec(char* dir, char* path);
	bool ftpCreateDirRec(char* dir);
	bool ftpCreateDirRec(TStringList*);
	void ftpFillDirQue(char* dir, char* path);
	void _ftpFillDirQue(TStringList *dirs);
	void setLocalFileDir(char*);

	bool getURL(char*);
	bool getData(char*);
	bool getData(char*, char*, void*);
	bool getWData(char*, char*, void*);
        bool getDataMoving(char* url, char* post, void* dlg);
	char* getResponse();
	int getResponseLenght();
	void clearResponse();

	~WebControl(void);
	///////////////////////
	// DEBUG
	///////////////////////
	//CURL* curl;
	///////////////////////
	// END DEBUG
	///////////////////////
   void setMemoryResponse();
   void setFileResponse();
   bool sendPost(char*, TStringList*);
   void createLocaPath(char* path);
};
#endif
