//---------------------------------------------------------------------------

#ifndef move_shop_actionH
#define move_shop_actionH
//---------------------------------------------------------------------------
#include "WebControl.h"
#include "MainWnd.h"
#include "f_Move.h"
#include <Classes.hpp>
#include <Controls.hpp>
#include <StdCtrls.hpp>
#include <Forms.hpp>
#include <RegExpr.hpp>
//---------------------------------------------------------------------------
class Tmove_shop_act : public TForm
{
__published:	// IDE-managed Components
        TMemo *move_log;
        TButton *_ok;
        TButton *_cancel;
        TLabel *Label1;
        TLabel *bytes_log;
        void __fastcall FormCreate(TObject *Sender);
        void __fastcall _cancelClick(TObject *Sender);
private:	// User declarations
public:		// User declarations
        __fastcall Tmove_shop_act(TComponent* Owner);
};
class Tmove_shop_thread : public TThread
{
        private:
        protected:
                void __fastcall Execute();
        public:
                Tmove_shop_act* _parent;
                SShop* fetched_shop_params;
                SShop* uploaded_shop_params;
                TFileStream* temporary_file;
                WebControl* move_shop_ctrl;
                AnsiString text;
                long unsigned int downloaded;
                __fastcall Tmove_shop_thread(bool CreateSuspended);
                void __fastcall change_label();
                void get_tar_pack(void* data, int bytes);
                void __fastcall change_caption();
};
void __fastcall modify_config(AnsiString posting,SShop* uploaded_shop_params);
//---------------------------------------------------------------------------
extern PACKAGE Tmove_shop_act *move_shop_act;
//---------------------------------------------------------------------------
#endif
