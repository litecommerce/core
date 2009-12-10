// WebDlg.cpp : implementation file
//

#include "stdafx.h"
#include "Installer.h"
#include "WebDlg.h"


// WebDlg dialog

IMPLEMENT_DYNAMIC(WebDlg, CDialog)
WebDlg::WebDlg(CWnd* pParent /*=NULL*/)
	: CDialog(WebDlg::IDD, pParent)
{
}

WebDlg::~WebDlg()
{
}

void WebDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(WebDlg, CDialog)
	ON_BN_CLICKED(IDOK, OnBnClickedOk)
END_MESSAGE_MAP()


// WebDlg message handlers

void WebDlg::OnBnClickedOk()
{
	CString url = parent->http_location + "/install.php?";
	ShellExecute(NULL, "Open", url.GetString(), NULL, "", NULL);
	OnOK();
}
