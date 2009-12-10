#include "InstallerDlg.h"
#include <stdio.h>
#include <stdlib.h>
#include <curl/curl.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include "afxcmn.h"

#pragma once

// UploadDlg dialog

class UploadDlg : public CDialog
{
	DECLARE_DYNAMIC(UploadDlg)

public:
	CInstallerDlg* parent;
	UploadDlg(CWnd* pParent = NULL);   // standard constructor
	virtual ~UploadDlg();

// Dialog Data
	enum { IDD = IDD_UPLOAD };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

	DECLARE_MESSAGE_MAP()
public:
	CURL* curl;
	CList <char*, char*> dirlist;
	char ftp_host[300];
	HANDLE hThread;
	CEdit current_file_control;
	CString current_variable;
	bool ftp_command(/*CString*/char*);
	bool upload_file(/*CString*/ char*);
	bool create_dir(/*CString*/char*);
	bool connect();
	bool createFtpDirRec(char*, char*);
	void OnCancel();

	BOOL OnInitDialog();
	VOID OnOK();

	char temp_url[1000];
	char temp_command[1000];

	bool upload_all();
	void ftpCreatePath(char* path);

	afx_msg void OnBnClickedButton1();
	CProgressCtrl progress_control;
};
