//---------------------------------------------------------------------------

#ifndef extract_thrH
#define extract_thrH
#include <tar/libtar.hpp>
//---------------------------------------------------------------------------
#include <Classes.hpp>
//---------------------------------------------------------------------------
class extract_thread : public TThread
{
private:
protected:
	AnsiString text;
	AnsiString caption;
	void __fastcall Execute();
   long files_count;
   long pos;
public:
	__fastcall extract_thread(bool CreateSuspended);
	void __fastcall _setCaption();
	void setCaption(AnsiString caption);
	void __fastcall _addString();
	void addString(AnsiString text);
   void getFilesCount(void);
	void __fastcall setProgressMax(void);
	void __fastcall _setProgressPos(void);
	void setProgressPos(int pos);

   TTarArchive* archive;
};
//---------------------------------------------------------------------------
#endif
