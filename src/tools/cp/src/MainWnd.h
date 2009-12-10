//---------------------------------------------------------------------------

#ifndef MainWndH
#define MainWndH
//---------------------------------------------------------------------------
#include "IdBaseComponent.hpp"
#include "IdComponent.hpp"
#include "IdFTP.hpp"
#include "IdHTTP.hpp"
#include "IdTCPClient.hpp"
#include "IdTCPConnection.hpp"

#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <Buttons.hpp>
#include <ExtCtrls.hpp>
#include <Menus.hpp>
#include <ComCtrls.hpp>
#include "f_BackupMain.h"
#include "f_Settings.h"
#include "f_Wysiwyg.h"
#include "f_Move.h"
#include "f_Upgrade.h"
//---------------------------------------------------------------------------
class TmainWindow : public TForm
{
__published:	// IDE-managed Components
   TBevel *left_pannel;
   TSplitter *main_split;
   TSpeedButton *btn_BkpRes;
   TStatusBar *status;
   TSpeedButton *btn_wysiwyg;
   TSpeedButton *btn_move;
   TSpeedButton *btn_upgrade;
   TPanel *main_wnd_panel;
   TBevel *main_wnd_border;
   Twysiwyg_frm *wysiwyg_frame;
   Tmove_frm *move_frame;
   Tupgrade_frm *upgrade_frame;
	TSpeedButton *btn_settings;
	Tsettings_frm *settings_frame;
	TIdFTP *ifc;
	TIdHTTP *ihc;
	Tbackup_frm *backup_restore_frame;
   void __fastcall FormCreate(TObject *Sender);
   void __fastcall btn_BkpResClick(TObject *Sender);
   void __fastcall btn_wysiwygClick(TObject *Sender);
   void __fastcall btn_moveClick(TObject *Sender);
   void __fastcall btn_upgradeClick(TObject *Sender);
	void __fastcall btn_settingsClick(TObject *Sender);
   void __fastcall main_splitCanResize(TObject *Sender, int &NewSize,
          bool &Accept);
private:	// User declarations
   TList *frames;
   TList *add_frames;
   TList *shop_boxes;
   TList *control_buttons;
public:		// User declarations

	// Downloaded skin decoration
   TStringList* skin_files;
   AnsiString wysiwyg_dir;

   // Default HTML editor
   AnsiString html_editor;

   __fastcall TmainWindow(TComponent* Owner);
   void control_button_click(int btn);
   SShopList *shops;
   void disable_all_view();
   void updateShops(void);

   AnsiString proxy;
   AnsiString proxy_port;
   AnsiString proxy_login;
   AnsiString proxy_pass;
   AnsiString dbackup_filename;
   AnsiString sbackup_filename;
   int frame_sender;
   bool use_proxy;
   bool use_proxy_auth;

};
//---------------------------------------------------------------------------
extern PACKAGE TmainWindow *mainWindow;
//---------------------------------------------------------------------------
#endif
