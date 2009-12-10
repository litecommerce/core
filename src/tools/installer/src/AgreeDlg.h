#include "installerdlg.h"
#include "afxwin.h"
#pragma once


// AgreeDlg dialog

class AgreeDlg : public CDialog
{
	DECLARE_DYNAMIC(AgreeDlg)

public:
	AgreeDlg(CWnd* pParent = NULL);   // standard constructor
	virtual ~AgreeDlg();
	CInstallerDlg* parent;

// Dialog Data
	enum { IDD = IDD_AGREE };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

	DECLARE_MESSAGE_MAP()
public:
	CEdit ViewLicenseControl;
	CString ViewLicense;
	BOOL OnInitDialog();
	afx_msg void OnBnClickedCancel();
	afx_msg void OnBnClickedButton1();
	CButton next_btn;
	afx_msg void OnBnClickedCheck1();
	BOOL can_continue;
	CButton agreeControl;
};
