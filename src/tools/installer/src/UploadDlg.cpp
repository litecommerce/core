// UploadDlg.cpp : implementation file
//

#include "stdafx.h"
#include "Installer.h"
#include "UploadDlg.h"

#include <stdio.h>
#include <curl/curl.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <afxtempl.h>

// serv functions
size_t read_function(char *bufptr, size_t size, size_t buffer, void* from)
{
	DWORD how;
	ReadFile((HANDLE) from, bufptr, buffer, &how, NULL);
	return (int) how; 
}

DWORD WINAPI fUpload(LPVOID param)
{
	UploadDlg *dlg = (UploadDlg*) param;
	if (dlg->upload_all()) {
		if (dlg->m_hWnd) {
			::EndDialog(dlg->m_hWnd, IDOK);
			dlg->parent->ShowFinalDialog();
		}
		return 0;
	}
	MessageBox(NULL, "Error uploading files", "Error", 0);
	if (dlg->m_hWnd) {
		dlg->OnCancel();
		//::EndDialog(dlg->m_hWnd, IDCANCEL);
	}
	return -1;
}

// UploadDlg dialog

IMPLEMENT_DYNAMIC(UploadDlg, CDialog)
UploadDlg::UploadDlg(CWnd* pParent /*=NULL*/)
	: CDialog(UploadDlg::IDD, pParent)
	, current_variable(_T(""))
{
}

UploadDlg::~UploadDlg()
{
}

void UploadDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT1, current_file_control);
	DDX_Text(pDX, IDC_EDIT1, current_variable);
	DDX_Control(pDX, IDC_PROGRESS1, progress_control);
}

bool UploadDlg::upload_file(/*CString*/ char* filename)
{

	int hd;
	long size;
	CURLcode res;

	if (!curl) {
		return false;
	}

	char full_name[MAX_PATH];
	sprintf(full_name, "tmp/%s", filename);

	HANDLE h_file = CreateFile(/*filename*/full_name, GENERIC_READ, FILE_SHARE_READ, NULL, 
				OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
	size = (long) GetFileSize(h_file, NULL);

	sprintf(temp_url, "%s%s\0", ftp_host, filename/*.GetString()*/);

	curl_easy_setopt(curl, CURLOPT_POSTQUOTE, NULL);
    curl_easy_setopt(curl, CURLOPT_READFUNCTION, read_function);
	curl_easy_setopt(curl, CURLOPT_UPLOAD, TRUE) ;
    curl_easy_setopt(curl, CURLOPT_URL, temp_url);
    curl_easy_setopt(curl, CURLOPT_INFILE, h_file);
    curl_easy_setopt(curl, CURLOPT_INFILESIZE, size);
    res = curl_easy_perform(curl);
	
	current_file_control.SetWindowText(filename);
	
	CloseHandle(h_file);

	if (!res) {
		return true;
	};
	return false;
}

bool UploadDlg::ftp_command(char* command)
{
	//sprintf(temp_url, "%s/\0", parent->ftp_host.GetString());

	struct curl_slist *headerlist=NULL;
	CURLcode res;

	curl_easy_setopt(curl, CURLOPT_UPLOAD, FALSE);
	headerlist = curl_slist_append(headerlist, command);
    curl_easy_setopt(curl, CURLOPT_URL, /*temp_url*/ftp_host);
	curl_easy_setopt(curl, CURLOPT_POSTQUOTE, headerlist);

	res = curl_easy_perform(curl);
    curl_slist_free_all (headerlist);
	return (!(res == CURLE_FTP_QUOTE_ERROR));
}

bool UploadDlg::connect()
{
	curl_global_init(CURL_GLOBAL_ALL);
	curl = curl_easy_init();
	if (!curl) return false;

	CString _user_pwd = parent->ftp_login + ":" + parent->ftp_password;
	char* user_pwd = new char[_user_pwd.GetLength()];
	sprintf(user_pwd, "%s\0", _user_pwd.GetString());

	curl_easy_setopt(curl, CURLOPT_USERPWD, user_pwd);
	
	if (parent->use_proxy) {
		CString proxy_user_pwd = parent->proxy_login + ":" + parent->proxy_password;
		CString proxy_opt = parent->proxy_server + ":" + parent->proxy_port;

		char* _proxy_user_pwd = new char[proxy_user_pwd.GetLength()];
		sprintf(_proxy_user_pwd, "%s\0", proxy_user_pwd.GetString());

		char* _proxy_opt = new char[proxy_opt.GetLength()];
		sprintf(_proxy_opt, "%s\0", proxy_opt.GetString());

		curl_easy_setopt(curl, CURLOPT_PROXY, _proxy_opt);
		curl_easy_setopt(curl, CURLOPT_PROXYUSERPWD, _proxy_user_pwd);
		curl_easy_setopt(curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
	}
	
	if (!parent->ftp_passive) {
		char* _ftp_port = new char[parent->ftp_port.GetLength()];
		sprintf(_ftp_port, "%s\0", parent->ftp_port.GetString());
		curl_easy_setopt(curl, CURLOPT_FTPPORT, _ftp_port);
	}


	return true;
}

bool UploadDlg::createFtpDirRec(char* dir, char* path)
{
	char _path[MAX_PATH];
	char _dir[MAX_PATH];
	
	GetCurrentDirectory(MAX_PATH, (char*)_dir);

	bool res = SetCurrentDirectory(dir);
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
			
			if (!create_dir((char*)_path)) {
				return false;
			}
			if (!createFtpDirRec(FindFileData.cFileName, (char*)_path)) {
				return false;
			}
		}
	}
	SetCurrentDirectory((char*)_dir);
    FindClose(hFind);
	return true;
}

bool UploadDlg::create_dir(char* dir)
{
	sprintf(temp_command, "MKD %s", dir);
	current_file_control.SetWindowText(dir);
    return ftp_command(temp_command);
}

BEGIN_MESSAGE_MAP(UploadDlg, CDialog)
	ON_BN_CLICKED(IDC_BUTTON1, OnBnClickedButton1)
END_MESSAGE_MAP()


// UploadDlg message handlers

void UploadDlg::OnBnClickedButton1()
{
}

bool UploadDlg::upload_all()
{
	progress_control.SetRange(0, parent->file_list.GetCount());
	connect();

	sprintf(ftp_host, "%s/\0", (char*)parent->ftp_host.GetString());
	char home_path[255];
	sprintf(home_path, "%s/\0", (char*)parent->ftp_home_dir.GetString());
	
	ftpCreatePath(home_path);

	sprintf(ftp_host, "%s%s\0", ftp_host, home_path);

	if (!createFtpDirRec("tmp", "")) {
		return false;
	}

	POSITION pos = parent->file_list.GetHeadPosition();
	for (int i=0;i < parent->file_list.GetCount();i++)
	{
		if (!upload_file((char*)parent->file_list.GetNext(pos).GetString())) {
			return false;
		}
		progress_control.SetPos(i);
	}

	sprintf(temp_command, "SITE CHMOD 0777 .");
	ftp_command(temp_command);

	sprintf(temp_command, "SITE CHMOD 0666 etc/config.php");
	ftp_command(temp_command);

	/*
	sprintf(temp_command, "SITE CHMOD 0777 bin/*.sh");
	ftp_command(temp_command);

	sprintf(temp_command, "SITE CHMOD 0755 bin/*.pl");
	ftp_command(temp_command);
	*/

	curl_easy_cleanup(curl);
	curl_global_cleanup();
	return true;
}

BOOL UploadDlg::OnInitDialog()
{
	if (!CDialog::OnInitDialog()) {
		return FALSE;
	}
	
	HICON m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon
	
	DWORD dwThreadId, dwThrdParam;
	hThread = CreateThread(NULL, 0, fUpload, this, 0, &dwThreadId);
	return TRUE;
}

void UploadDlg::OnCancel()
{
	TerminateThread(hThread, 0);
	CDialog::OnCancel();
}

VOID UploadDlg::OnOK()
{
	
	//TerminateThread(hThread, 0);
	CDialog::OnOK();
}

void UploadDlg::ftpCreatePath(char* pathname)
{
	char *p;
	char *s;
	p = pathname;
	while( p && *p ) {
		s = strchr(p,'/');
		if( s ) *s = 0;
		create_dir(pathname);
		if( s ){
			*s = '/';
			p = s + 1;
		}
		else {
			break;
		}
	}
}
