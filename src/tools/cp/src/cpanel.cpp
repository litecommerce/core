//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop
USERES("cpanel.res");
USEFORM("MainWnd.cpp", mainWindow);
USEFORM("f_BackupMain.cpp", backup_frm); /* TFrame: File Type */
USEFORM("f_Settings.cpp", settings_frm); /* TFrame: File Type */
USEFORM("f_Wysiwyg.cpp", wysiwyg_frm); /* TFrame: File Type */
USEFORM("f_Move.cpp", move_frm); /* TFrame: File Type */
USEFORM("f_Upgrade.cpp", upgrade_frm); /* TFrame: File Type */
USEFORM("f_AddShop.cpp", add_shop_frm); /* TFrame: File Type */
USEFORM("form_AddShop.cpp", add_shop);
USEUNIT("restore_thread.cpp");
USEUNIT("upgrade_thread.cpp");
USEOBJ("lib\zlib\adler32.obj");
USEOBJ("lib\zlib\compress.obj");
USEOBJ("lib\zlib\crc32.obj");
USEOBJ("lib\zlib\deflate.obj");
USEOBJ("lib\zlib\gzio.obj");
USEOBJ("lib\zlib\infblock.obj");
USEOBJ("lib\zlib\infcodes.obj");
USEOBJ("lib\zlib\inffast.obj");
USEOBJ("lib\zlib\inflate.obj");
USEOBJ("lib\zlib\inftrees.obj");
USEOBJ("lib\zlib\infutil.obj");
USEOBJ("lib\zlib\maketree.obj");
USEOBJ("lib\zlib\trees.obj");
USEOBJ("lib\zlib\uncompr.obj");
USEOBJ("lib\zlib\zutil.obj");
USEOBJ("lib\tar\LibTar.obj");
USEOBJ("lib\regex\RegExpr.obj");
USEUNIT("w_get_thread.cpp");
USEFORM("w_pub_action.cpp", w_publich_action);
USEUNIT("w_put_thread.cpp");
USEUNIT("FileUtil.cpp");
USEFORM("edit_file.cpp", select_edit_file);
USERES("scripts.res");
USEUNIT("SShop.cpp");
USEUNIT("SShopList.cpp");
USEUNIT("backup_thread.cpp");
USEFORM("proto\ThreadForm.cpp", ProtoThreadForm);
USEFORM("backup_action.cpp", backup_act);
USEFORM("restore_act.cpp", action_restore);
USEFORM("upgrade_action.cpp", upgrade_act);
USEFORM("wysiwyg_action.cpp", Action_wysiwyg_get);
USEFORM("n_move_shop_action.cpp", move_shop);
USEFORM("test_shop_action.cpp", test_shop);
USEUNIT("test_shop_thread.cpp");
USEUNIT("web_thread.cpp");
//---------------------------------------------------------------------------
WINAPI WinMain(HINSTANCE, HINSTANCE, LPSTR, int)
{
   try
   {
       Application->Initialize();
       Application->Title = "LiteCommerce control panel";
		Application->CreateForm(__classid(TmainWindow), &mainWindow);
       Application->CreateForm(__classid(Tadd_shop), &add_shop);
       Application->CreateForm(__classid(Tselect_edit_file), &select_edit_file);
       Application->Run();
   }
   catch (Exception &exception)
   {
       Application->ShowException(&exception);
   }
   return 0;
}
//---------------------------------------------------------------------------
