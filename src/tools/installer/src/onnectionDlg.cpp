// onnectionDlg.cpp : implementation file
//

#include "stdafx.h"
#include "Installer.h"
#include "onnectionDlg.h"


// ConnectionDlg dialog

IMPLEMENT_DYNAMIC(ConnectionDlg, CDialog)
ConnectionDlg::ConnectionDlg(CWnd* pParent /*=NULL*/)
	: CDialog(ConnectionDlg::IDD, pParent)
	, ftp_host(_T(""))
	, ftp_port(_T(""))
	, passive_mode(FALSE)
	, ftp_home(_T(""))
	, ftp_login(_T(""))
	, ftp_password(_T(""))
	, http_url(_T(""))
	, use_proxy(FALSE)
	, proxy_server(_T(""))
	, proxy_port(_T(""))
	, proxy_login(FALSE)
	, proxy_user(_T(""))
	, proxy_password(_T(""))
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

ConnectionDlg::~ConnectionDlg()
{
}

BOOL ConnectionDlg::OnInitDialog()
{
	if (!CDialog::OnInitDialog()) {
		return false;
	}
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon
	
	ftp_host = parent->ftp_host;
	ftp_port = parent->ftp_port;
	passive_mode = parent->ftp_passive;
	ftp_home = parent->ftp_home_dir;
	ftp_login = parent->ftp_login;
	ftp_password = parent->ftp_password;
	http_url = parent->http_location;
	use_proxy = parent->use_proxy;
	proxy_server = parent->proxy_server;
	proxy_port = parent->proxy_port;
	proxy_login = parent->use_proxy_login;
	proxy_user = parent->proxy_login;
	proxy_password = parent->proxy_password;

	UpdateData(false);
	setEnable();

	return true;
}

void ConnectionDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_EDIT1, ftp_host);
	DDX_Text(pDX, IDC_EDIT2, ftp_port);
	DDX_Control(pDX, IDC_CHECK1, passive_mode_control);
	DDX_Check(pDX, IDC_CHECK1, passive_mode);
	DDX_Text(pDX, IDC_EDIT11, ftp_home);
	DDX_Text(pDX, IDC_EDIT3, ftp_login);
	DDX_Text(pDX, IDC_EDIT4, ftp_password);
	DDX_Text(pDX, IDC_EDIT5, http_url);
	DDX_Control(pDX, IDC_CHECK3, use_proxy_control);
	DDX_Check(pDX, IDC_CHECK3, use_proxy);
	DDX_Control(pDX, IDC_EDIT6, proxy_server_control);
	DDX_Text(pDX, IDC_EDIT6, proxy_server);
	DDX_Control(pDX, IDC_EDIT8, proxy_port_control);
	DDX_Text(pDX, IDC_EDIT8, proxy_port);
	DDX_Control(pDX, IDC_CHECK4, proxy_login_control);
	DDX_Check(pDX, IDC_CHECK4, proxy_login);
	DDX_Control(pDX, IDC_EDIT9, proxy_user_control);
	DDX_Text(pDX, IDC_EDIT9, proxy_user);
	DDX_Control(pDX, IDC_EDIT10, proxy_password_control);
	DDX_Text(pDX, IDC_EDIT10, proxy_password);
}


BEGIN_MESSAGE_MAP(ConnectionDlg, CDialog)
	ON_BN_CLICKED(IDC_CHECK3, OnBnClickedCheck3)
	ON_BN_CLICKED(IDC_CHECK4, OnBnClickedCheck4)
	ON_BN_CLICKED(IDC_BUTTON1, OnBnClickedButton1)
END_MESSAGE_MAP()


// ConnectionDlg message handlers

// proxy settings enable/disable
void ConnectionDlg::OnBnClickedCheck3()
{
	setEnable();
}

void ConnectionDlg::setEnable()
{
	UpdateData();
	this->proxy_login_control.EnableWindow(use_proxy);
	this->proxy_port_control.EnableWindow(use_proxy);
	this->proxy_server_control.EnableWindow(use_proxy);

	this->proxy_password_control.EnableWindow(use_proxy & proxy_login);
	this->proxy_user_control.EnableWindow(use_proxy & proxy_login);
}

void ConnectionDlg::OnBnClickedCheck4()
{
	setEnable();
}

void ConnectionDlg::updateSettings()
{
	UpdateData();
	
	char chr = ftp_host.GetAt(ftp_host.GetLength() - 1);
	if ( chr == '/') {
		ftp_host = ftp_host.Left(ftp_host.GetLength() - 1);
	}

	chr = ftp_home.GetAt(ftp_home.GetLength() - 1);
	if (chr == '/') {
		ftp_home = ftp_home.Left(ftp_home.GetLength() - 1);
	}

	chr = http_url.GetAt(http_url.GetLength() - 1);
	if (chr == '/') {
		http_url = http_url.Left(http_url.GetLength() - 1);
	}

	parent->ftp_host = ftp_host;
	parent->ftp_port = ftp_port;
	parent->ftp_passive = passive_mode;
	parent->ftp_home_dir = ftp_home;
	parent->ftp_login = ftp_login;
	parent->ftp_password = ftp_password;
	parent->http_location = http_url;
	parent->use_proxy = use_proxy;
	parent->proxy_server = proxy_server;
	parent->proxy_port = proxy_port;
	parent->use_proxy_login = proxy_login;
	parent->proxy_login = proxy_user;
	parent->proxy_password = proxy_password;
}

void ConnectionDlg::OnBnClickedButton1()
{
	updateSettings();
	EndDialog(IDOK);
}

void ConnectionDlg::OnPaint() 
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
