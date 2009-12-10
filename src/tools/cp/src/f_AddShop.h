//---------------------------------------------------------------------------


#ifndef f_AddShopH
#define f_AddShopH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <ExtCtrls.hpp>
//---------------------------------------------------------------------------
class Tadd_shop_frm : public TFrame
{
__published:	// IDE-managed Components
   TPanel *Panel1;
   TPanel *Panel3;
   TButton *shop_save;
   TPanel *Panel2;
   TGroupBox *ftp_settings;
   TLabel *Label2;
   TLabel *Label3;
   TLabel *Label4;
   TLabel *Label5;
   TLabel *Label6;
   TEdit *ftp_host;
   TEdit *ftp_port;
   TEdit *ftp_dir;
   TEdit *ftp_login;
   TEdit *ftp_password;
   TGroupBox *mysql_settings;
   TLabel *Label7;
   TLabel *Label8;
   TLabel *Label9;
   TLabel *Label10;
   TEdit *mysql_host;
   TEdit *mysql_db;
   TEdit *mysql_login;
   TEdit *mysql_password;
   TGroupBox *shop_settings;
   TLabel *Label11;
   TLabel *Label12;
   TLabel *Label13;
   TEdit *http_url;
   TEdit *admin_login;
   TEdit *admin_password;
   void __fastcall shop_saveClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
   __fastcall Tadd_shop_frm(TComponent* Owner);
};
//---------------------------------------------------------------------------
extern PACKAGE Tadd_shop_frm *add_shop_frm;
//---------------------------------------------------------------------------
#endif
