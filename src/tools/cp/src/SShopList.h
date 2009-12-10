#include <classes.hpp>
#include "SShop.h"

class SShopList
{
   public:
   TList* data;

   SShopList();
   ~SShopList();

   SShop* getByName(AnsiString name);
   SShop* getByIndex(int Index);
   void Add(SShop* shop);
   void Remove(AnsiString name);
   void Create();

   private:
   int delete_index;
};
