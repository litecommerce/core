//---------------------------------------------------------------------------

#include <vcl.h>
#pragma hdrstop
USERES("Installer.res");
USERES("help.res");
USEFORM("u_main.cpp", _main);
USEOBJ("lib\zlib\adler32.obj");
USEOBJ("lib\zlib\deflate.obj");
USEOBJ("lib\zlib\infblock.obj");
USEOBJ("lib\zlib\infcodes.obj");
USEOBJ("lib\zlib\inffast.obj");
USEOBJ("lib\zlib\inflate.obj");
USEOBJ("lib\zlib\inftrees.obj");
USEOBJ("lib\zlib\infutil.obj");
USEOBJ("lib\zlib\trees.obj");
USEOBJ("lib\zlib\compress.obj");
USEOBJ("lib\zlib\crc32.obj");
USEOBJ("lib\zlib\gzio.obj");
USEOBJ("lib\zlib\maketree.obj");
USEOBJ("lib\zlib\uncompr.obj");
USEOBJ("lib\zlib\zutil.obj");
USEFORM("extract.cpp", f_extract); /* TFrame: File Type */
USEUNIT("extract_thr.cpp");
USEOBJ("lib\regex\RegExpr.obj");
USEOBJ("lib\tar\LibTar.obj");
USEFORM("license.cpp", f_license); /* TFrame: File Type */
USEFORM("settings.cpp", f_settings); /* TFrame: File Type */
USEFORM("upload.cpp", f_upload); /* TFrame: File Type */
USEUNIT("upload_thr.cpp");
USEFORM("owerwrite_dlg.cpp", owerwrite);
USEFORM("splash.cpp", f_splash); /* TFrame: File Type */
USEFORM("completed.cpp", f_completed); /* TFrame: File Type */
USEFORM("test.cpp", f_test); /* TFrame: File Type */
USEUNIT("test_thr.cpp");
//---------------------------------------------------------------------------
HANDLE CheckInstance( const char *Name )
{
   HANDLE Mutex = CreateMutex(NULL, true, Name);
   int r = GetLastError();
   if ( r != 0 ) {
      return 0;
   }
   return Mutex;
}

//---------------------------------------------------------------------------
WINAPI WinMain(HINSTANCE, HINSTANCE, LPSTR, int)
{
	const char * mutex_name = "LiteCommerceInstaller";

   HANDLE Mutex = CheckInstance(mutex_name);

   if (!Mutex) {
   	MessageBox(NULL, "LiteCommerce installer is already running", "Error", MB_OK|MB_ICONEXCLAMATION );
      return -1;
   }

	try
	{
		Application->Initialize();
		Application->CreateForm(__classid(T_main), &_main);
		Application->Run();
	}
	catch (Exception &exception)
	{
		//Application->ShowException(&exception);
	}

   ReleaseMutex(Mutex);
	return 0;
}
//---------------------------------------------------------------------------
