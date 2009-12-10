#pragma once
#include "afxwin.h"


// ConnectSettingsDlg dialog

class ConnectSettingsDlg : public CDHtmlDialog
{
	DECLARE_DYNCREATE(ConnectSettingsDlg)

public:
	ConnectSettingsDlg(CWnd* pParent = NULL);   // standard constructor
	virtual ~ConnectSettingsDlg();
// Overrides
	HRESULT OnButtonOK(IHTMLElement *pElement);
	HRESULT OnButtonCancel(IHTMLElement *pElement);

// Dialog Data
	enum { IDD = IDD_FTP_AND_PROXY_CONFIG, IDH = IDR_HTML_ONNECTSETTINGSDLG };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support
	virtual BOOL OnInitDialog();

	DECLARE_MESSAGE_MAP()
	DECLARE_DHTML_EVENT_MAP()
public:
	CString ftp_host;
	CEdit ftp_port_control;
	CButton passive_mode_control;
	BOOL passive_mode;
	CString ftp_dir;
};
