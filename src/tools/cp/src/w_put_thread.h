//---------------------------------------------------------------------------
#include "w_pub_action.h"
#include "mainWnd.h"
#include "web_thread.h"

#ifndef w_put_threadH
#define w_put_threadH
//---------------------------------------------------------------------------
#include <Classes.hpp>
//---------------------------------------------------------------------------
class w_put_thread : public WebThread
{
private:
protected:
	void __fastcall Execute();
public:
	__fastcall w_put_thread(bool CreateSuspended);
   Tw_publich_action* _parent;
   void __fastcall Init(void);
   bool DoPublish(void);
   bool Upload(void);
   bool Publish(void);
   bool UnTar(void);
};
//---------------------------------------------------------------------------
#endif
