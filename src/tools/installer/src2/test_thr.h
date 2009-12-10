//---------------------------------------------------------------------------

#ifndef test_thrH
#define test_thrH
//---------------------------------------------------------------------------
#include <Classes.hpp>
//---------------------------------------------------------------------------
class test_thread : public TThread
{
private:
protected:
	void __fastcall Execute();
   bool answer;
   AnsiString _text;
   int _line_num;
   AnsiString localName;
   AnsiString remoteName;
   void __fastcall _print(void);
   void __fastcall _add_to_line(void);
   void __fastcall _clear_display(void);

public:
	__fastcall test_thread(bool CreateSuspended);
	void __fastcall Connect(void);
   bool Login(void);
   void __fastcall Run(void);
	void __fastcall Disconnect(void);

   bool IsDir(AnsiString dir);
   bool IsFile(AnsiString file);

   void Print(AnsiString text);
   void AddToLine(AnsiString text, int line);
   void ClearDisplay(void);
   void CreateHomeDir(void);
   void CreateTestFile(AnsiString name);

   bool ConnectTest(void);
   bool LoginTest(void);
   bool UploadTest(void);
   bool HTTPTest(void);

   void OnConnectError(void);
   void OnLoginError(void);
   void OnUploadError(void);
   void OnHTTPError(void);

   void OnSuccess(void);
};
//---------------------------------------------------------------------------
#endif
