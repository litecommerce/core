#include "installerdlg.h"
#include "afxwin.h"
#pragma once


// ConnectionDlg dialog

class ConnectionDlg : public CDialog
{
	DECLARE_DYNAMIC(ConnectionDlg)

public:
	CInstallerDlg* parent;
	ConnectionDlg(CWnd* pParent = NULL);   // standard constructor
	BOOL OnInitDialog();
	virtual ~ConnectionDlg();

// Dialog Data
	enum { IDD = IDD_FTP_AND_PROXY_CONFIG };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support
	HICON m_hIcon;

	DECLARE_MESSAGE_MAP()
public:
	CString ftp_host;
	CString ftp_port;
	CButton passive_mode_control;
	BOOL passive_mode;
	CString ftp_home;
	CString ftp_login;
	CString ftp_password;
	CString http_url;
	CButton use_proxy_control;
	BOOL use_proxy;
	CEdit proxy_server_control;
	CString proxy_server;
	CEdit proxy_port_control;
	CString proxy_port;
	CButton proxy_login_control;
	BOOL proxy_login;
	CEdit proxy_user_control;
	CString proxy_user;
	CEdit proxy_password_control;
	CString proxy_password;
	afx_msg void OnBnClickedCheck3();

	void setEnable();
	void updateSettings();
	afx_msg void OnBnClickedCheck4();
	afx_msg void OnBnClickedButton1();
	void OnPaint();
};
