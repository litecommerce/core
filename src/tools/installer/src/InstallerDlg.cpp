// InstallerDlg.cpp : implementation file
//

#include "stdafx.h"
#include "Installer.h"
#include "InstallerDlg.h"
#include "onnectionDlg.h"
#include "UploadDlg.h";
#include "opyDlg.h"
#include "AgreeDlg.h"
#include "WebDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CAboutDlg dialog used for App About

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// Dialog Data
	enum { IDD = IDD_ABOUTBOX };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

// Implementation
protected:
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()


// CInstallerDlg dialog



CInstallerDlg::CInstallerDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CInstallerDlg::IDD, pParent)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);

	ftp_host = "ftp://";
	ftp_home_dir = "public_html";
	ftp_login = "ivf";
	ftp_password = "";
	ftp_port = "21";

	proxy_server = "192.168.10.1";
	proxy_port = "3128";
	proxy_login = "ivf";
	proxy_password = "";

	http_location = "http://";

	use_proxy = false;
	ftp_passive = true;
	use_proxy_login = false;
}

void CInstallerDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CInstallerDlg, CDialog)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	//}}AFX_MSG_MAP
	ON_BN_CLICKED(IDC_BUTTON1, OnBnClickedButton1)
END_MESSAGE_MAP()


// CInstallerDlg message handlers

BOOL CInstallerDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// Add "About..." menu item to system menu.

	// IDM_ABOUTBOX must be in the system command range.
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// Set the icon for this dialog.  The framework does this automatically
	//  when the application's main window is not a dialog
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon

	// TODO: Add extra initialization here
	
	return TRUE;  // return TRUE  unless you set the focus to a control
}

void CInstallerDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

// If you add a minimize button to your dialog, you will need the code below
//  to draw the icon.  For MFC applications using the document/view model,
//  this is automatically done for you by the framework.

void CInstallerDlg::OnPaint() 
{
	if (IsIconic())
	{
		CPaintDC dc(this); // device context for painting

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// Center icon in client rectangle
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// Draw the icon
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// The system calls this function to obtain the cursor to display while the user drags
//  the minimized window.
HCURSOR CInstallerDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}

void CInstallerDlg::OnBnClickedButton1()
{
	CopyDlg extract_dlg;

	this->ShowWindow(SW_HIDE);
	extract_dlg.parent = this;
	int ret = extract_dlg.DoModal();
	if (ret == IDCANCEL) {
		EndDialog(0);
	} else {
		ShowAgreeDialog();
	}
}

void CInstallerDlg::ShowAgreeDialog()
{
	AgreeDlg agree_dlg;
	agree_dlg.parent = this;
	int ret = agree_dlg.DoModal();
	if (ret == IDCANCEL) {
		RemoveDirectory("x-lite");
		this->DestroyWindow(); 
	} else {
		ShowFtpDialog();
	}
}

void CInstallerDlg::ShowFtpDialog()
{
	ConnectionDlg conn_dlg;
	conn_dlg.parent = this;
	int ret = conn_dlg.DoModal();
	if (ret == IDCANCEL) {
		RemoveDirectory("x-lite");
		this->DestroyWindow();
	} else {
		ShowUploadDialog();
	}
}

void CInstallerDlg::ShowUploadDialog()
{
	UploadDlg conn_dlg;
	conn_dlg.parent = this;
	int ret = conn_dlg.DoModal();
	RemoveDirectory("x-lite");
	if (ret == IDCANCEL) {
		this->DestroyWindow();
	} else {
		ShowFinalDialog();
	}
}

void CInstallerDlg::ShowFinalDialog()
{
	WebDlg dlg;
	dlg.parent = this;
	dlg.DoModal();
	this->DestroyWindow();
}
