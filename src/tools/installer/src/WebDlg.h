#include "installerdlg.h"
#pragma once


// WebDlg dialog

class WebDlg : public CDialog
{
	DECLARE_DYNAMIC(WebDlg)

public:
	CInstallerDlg* parent;
	WebDlg(CWnd* pParent = NULL);   // standard constructor
	virtual ~WebDlg();

// Dialog Data
	enum { IDD = IDD_WEBINSTALL };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

	DECLARE_MESSAGE_MAP()
public:
	afx_msg void OnBnClickedOk();
};
