#pragma once
#include "installerdlg.h"
#include <afxtempl.h>
#include "afxwin.h"



// CopyDlg dialog

class CopyDlg : public CDialog
{
	DECLARE_DYNAMIC(CopyDlg)

public:
	CopyDlg(CWnd* pParent = NULL);   // standard constructor
	virtual ~CopyDlg();

// Dialog Data
	enum { IDD = IDD_DIALOG1 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

	DECLARE_MESSAGE_MAP()
public:
	//CList <CString, CString&> file_list;
	CInstallerDlg* parent;
	afx_msg void OnBnClickedOk();
	BOOL OnInitDialog();
	HANDLE hThread;
	bool untgz(char*, CEdit*);
	CEdit m_ShowExtract;
	afx_msg void OnBnClickedCancel();
};
