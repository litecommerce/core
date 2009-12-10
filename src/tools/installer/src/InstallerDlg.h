#include <afxtempl.h>

// InstallerDlg.h : header file
//

#pragma once


// CInstallerDlg dialog
class CInstallerDlg : public CDialog
{
// Construction
public:
	CInstallerDlg(CWnd* pParent = NULL);	// standard constructor

// Dialog Data
	enum { IDD = IDD_INSTALLER_DIALOG };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV support


// Implementation
protected:
	HICON m_hIcon;

	// Generated message map functions
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	DECLARE_MESSAGE_MAP()
public:
	CList <CString, CString&> file_list;
	
	CString ftp_host;
	CString ftp_home_dir;
	CString ftp_login;
	CString ftp_password;
	CString ftp_port;

	CString proxy_server;
	CString proxy_port;
	CString proxy_login;
	CString proxy_password;

	CString http_location;

	bool use_proxy;
	bool ftp_passive;
	bool use_proxy_login;
    
	afx_msg void OnBnClickedButton1();
	void ShowAgreeDialog();
	void ShowFtpDialog();
	void ShowUploadDialog();
	void ShowFinalDialog();
};
