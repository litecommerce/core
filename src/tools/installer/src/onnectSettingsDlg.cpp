// onnectSettingsDlg.cpp : implementation file
//

#include "stdafx.h"
#include "Installer.h"
#include "onnectSettingsDlg.h"


// ConnectSettingsDlg dialog

IMPLEMENT_DYNCREATE(ConnectSettingsDlg, CDHtmlDialog)

ConnectSettingsDlg::ConnectSettingsDlg(CWnd* pParent /*=NULL*/)
	: CDHtmlDialog(ConnectSettingsDlg::IDD, ConnectSettingsDlg::IDH, pParent)
	, ftp_host(_T(""))
	, passive_mode(FALSE)
	, ftp_dir(_T(""))
{
}

ConnectSettingsDlg::~ConnectSettingsDlg()
{
}

void ConnectSettingsDlg::DoDataExchange(CDataExchange* pDX)
{
	CDHtmlDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_EDIT1, ftp_host);
	DDX_Control(pDX, IDC_EDIT2, ftp_port_control);
	DDX_Control(pDX, IDC_CHECK1, passive_mode_control);
	DDX_Check(pDX, IDC_CHECK1, passive_mode);
	DDX_Text(pDX, IDC_EDIT11, ftp_dir);
}

BOOL ConnectSettingsDlg::OnInitDialog()
{
	CDHtmlDialog::OnInitDialog();
	return TRUE;  // return TRUE  unless you set the focus to a control
}

BEGIN_MESSAGE_MAP(ConnectSettingsDlg, CDHtmlDialog)
END_MESSAGE_MAP()

BEGIN_DHTML_EVENT_MAP(ConnectSettingsDlg)
	DHTML_EVENT_ONCLICK(_T("ButtonOK"), OnButtonOK)
	DHTML_EVENT_ONCLICK(_T("ButtonCancel"), OnButtonCancel)
END_DHTML_EVENT_MAP()



// ConnectSettingsDlg message handlers

HRESULT ConnectSettingsDlg::OnButtonOK(IHTMLElement* /*pElement*/)
{
	OnOK();
	return S_OK;  // return TRUE  unless you set the focus to a control
}

HRESULT ConnectSettingsDlg::OnButtonCancel(IHTMLElement* /*pElement*/)
{
	OnCancel();
	return S_OK;  // return TRUE  unless you set the focus to a control
}
