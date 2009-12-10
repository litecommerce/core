//---------------------------------------------------------------------------

#ifndef test_shop_threadH
#define test_shop_threadH
//---------------------------------------------------------------------------
#include <Classes.hpp>
#include "web_thread.h"
#include "test_shop_action.h"
//---------------------------------------------------------------------------
class TetShopThread : public WebThread
{
private:
protected:
	void __fastcall Execute();
public:
	__fastcall TetShopThread(bool CreateSuspended);
   void __fastcall Init(void);
   Ttest_shop* _parent;
};
//---------------------------------------------------------------------------
#endif
