// AgreeDlg.cpp : implementation file
//

#include "stdafx.h"
#include "Installer.h"
#include "AgreeDlg.h"


// AgreeDlg dialog

IMPLEMENT_DYNAMIC(AgreeDlg, CDialog)
AgreeDlg::AgreeDlg(CWnd* pParent /*=NULL*/)
	: CDialog(AgreeDlg::IDD, pParent)
	, ViewLicense(_T(""))
	, can_continue(FALSE)
{
}

AgreeDlg::~AgreeDlg()
{
}

void AgreeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT1, ViewLicenseControl);
	DDX_Text(pDX, IDC_EDIT1, ViewLicense);
	DDX_Control(pDX, IDC_BUTTON1, next_btn);
	DDX_Check(pDX, IDC_CHECK1, can_continue);
	DDX_Control(pDX, IDC_CHECK1, agreeControl);
}

BOOL AgreeDlg::OnInitDialog()
{
	if (!CDialog::OnInitDialog()) {
		return FALSE;
	}
	HICON m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon

	HANDLE l_file; 
	l_file = CreateFile("tmp/COPYRIGHT", GENERIC_READ, FILE_SHARE_READ, NULL, 
		OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
	if (!l_file) {
		return false;
	}
	char* rbuff = new char[GetFileSize(l_file, NULL)];

	DWORD total;
	bool result = ReadFile(l_file, rbuff, GetFileSize(l_file, NULL), &total, NULL);
	CloseHandle((HANDLE)l_file);
	rbuff[total] = '\0';
	ViewLicense = CString(rbuff);
	ViewLicense.Replace("\n", "\r\n");
	UpdateData(FALSE);
	return TRUE;
}


BEGIN_MESSAGE_MAP(AgreeDlg, CDialog)
	ON_BN_CLICKED(IDCANCEL, OnBnClickedCancel)
	ON_BN_CLICKED(IDC_BUTTON1, OnBnClickedButton1)
	ON_BN_CLICKED(IDC_CHECK1, OnBnClickedCheck1)
END_MESSAGE_MAP()


// AgreeDlg message handlers

void AgreeDlg::OnBnClickedCancel()
{
	this->EndDialog(IDCANCEL);
}

void AgreeDlg::OnBnClickedButton1()
{
	this->EndDialog(IDOK);
}

void AgreeDlg::OnBnClickedCheck1()
{
	if (agreeControl.GetCheck()) {
		next_btn.EnableWindow();
	} else {
		next_btn.EnableWindow(FALSE);
	}
}
