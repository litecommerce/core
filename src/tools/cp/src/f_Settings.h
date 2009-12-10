//---------------------------------------------------------------------------


#ifndef f_SettingsH
#define f_SettingsH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ComCtrls.hpp>
#include <ExtCtrls.hpp>
#include "SShopList.h"
//#include "WebControl.h"
#include <Buttons.hpp>
#include <Dialogs.hpp>

//---------------------------------------------------------------------------
class Tsettings_frm : public TFrame
{
__published:	// IDE-managed Components
   TPanel *settings_bkgr;
   TPageControl *settings_pages;
   TTabSheet *shop_opts;
   TPanel *Panel1;
   TPanel *Panel2;
   TLabel *Label1;
   TComboBox *shops;
   TPanel *Panel3;
   TButton *shop_save;
   TButton *shop_delete;
   TPanel *Panel4;
   TGroupBox *mysql_settings;
   TLabel *Label7;
   TLabel *Label8;
   TLabel *Label9;
   TLabel *Label10;
   TEdit *mysql_host;
   TEdit *mysql_db;
   TEdit *mysql_login;
   TEdit *mysql_password;
   TTabSheet *proxy_opts;
   TPanel *ftp_props_bkgr;
   TPanel *Panel5;
   TButton *proxy_save;
	TTabSheet *apps_opts;
	TPanel *Panel6;
	TPanel *Panel7;
	TEdit *_editor;
	TSpeedButton *SpeedButton1;
	TOpenDialog *editor_od;
	TLabel *Label18;
        TButton *test_settings;
        TGroupBox *ftp_settings;
        TLabel *Label2;
        TLabel *Label6;
        TLabel *Label4;
        TLabel *Label5;
        TCheckBox *ftp_passive;
        TEdit *ftp_password;
        TEdit *ftp_login;
        TEdit *ftp_dir;
        TEdit *ftp_host;
        TEdit *ftp_port;
        TLabel *Label3;
        TGroupBox *GroupBox2;
        TLabel *Label11;
        TLabel *Label12;
        TLabel *Label13;
        TEdit *http_url;
        TEdit *admin_login;
        TEdit *admin_password;
        TGroupBox *proxy_serv_port;
        TCheckBox *use_proxy;
        TCheckBox *use_proxy_login;
        TPanel *Panel8;
        TLabel *Label17;
        TEdit *proxy_login;
        TEdit *proxy_password;
        TLabel *Label16;
        TPanel *Panel9;
        TLabel *Label14;
        TEdit *proxy_server;
        TEdit *proxy_port;
        TLabel *Label15;
	TPanel *settings_head;
   void __fastcall FrameResize(TObject *Sender);
   void __fastcall shop_saveClick(TObject *Sender);
   void __fastcall shop_deleteClick(TObject *Sender);
   void __fastcall shopsChange(TObject *Sender);
   void __fastcall proxy_saveClick(TObject *Sender);
	void __fastcall SpeedButton1Click(TObject *Sender);
   void __fastcall settings_pagesChange(TObject *Sender);
   void __fastcall test_settingsClick(TObject *Sender);
   void __fastcall use_proxyClick(TObject *Sender);
   void __fastcall use_proxy_loginClick(TObject *Sender);

private:	// User declarations
   bool is_new_shop;
public:		// User declarations
   __fastcall Tsettings_frm(TComponent* Owner);
   void ShowShopParameters(SShop* shop);
   void SaveEditor(void);
   void ReadEditor(void);
   SShop* FillShop(SShop* shop);
};
//---------------------------------------------------------------------------
extern PACKAGE Tsettings_frm *settings_frm;
//---------------------------------------------------------------------------
#endif
