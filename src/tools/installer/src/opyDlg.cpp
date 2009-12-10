// opyDlg.cpp : implementation file
//

#include "stdafx.h"
#include "Installer.h"
#include "opyDlg.h"
//#include <zlib.h>
#include <untgz.h>

DWORD WINAPI fExtract(LPVOID param)
{
	CopyDlg *dlg = (CopyDlg*) param;
	if (dlg->untgz("xcart.tgz", &(dlg->m_ShowExtract))) {
		dlg->EndDialog(IDOK);
		return 0;
	}
	return -1;
}

// CopyDlg dialog

IMPLEMENT_DYNAMIC(CopyDlg, CDialog)
CopyDlg::CopyDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CopyDlg::IDD, pParent)
{
}

CopyDlg::~CopyDlg()
{
}

void CopyDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT1, m_ShowExtract);
}


BEGIN_MESSAGE_MAP(CopyDlg, CDialog)
	ON_BN_CLICKED(IDOK, OnBnClickedOk)
	ON_BN_CLICKED(IDCANCEL, OnBnClickedCancel)
END_MESSAGE_MAP()


// CopyDlg message handlers

void CopyDlg::OnBnClickedOk()
{
	parent->ShowWindow(SW_SHOW);
	OnOK();
}

BOOL CopyDlg::OnInitDialog()
{
	if (!CDialog::OnInitDialog()) {
		return FALSE;
	}
	HICON m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon

	DWORD dwThreadId, dwThrdParam;
	hThread = CreateThread(NULL, 0, fExtract, this, 0, &dwThreadId);
	return TRUE;
}

bool CopyDlg::untgz(char* filename, CEdit* control)
{
	gzFile f;
	f = gzopen(filename, "rb");
	if (f == NULL) {
		return false;
	}
	int result = tar(f, TGZ_EXTRACT, 1, 1, &filename, control, &(this->parent->file_list));
	return (bool)(!result);
}

void CopyDlg::OnBnClickedCancel()
{
	TerminateThread(hThread, 0);
	this->EndDialog(IDCANCEL);
}
