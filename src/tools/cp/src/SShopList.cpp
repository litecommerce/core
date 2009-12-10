#include "SShopList.h"
SShopList::SShopList()
{
   data = new TList();
   Create();
}

void SShopList::Create()
{
   data->Clear();
   TRegistry* reg = new TRegistry();
   TStringList* cur_shops = new TStringList();
   AnsiString reg_path = "software\\LiteCommerce\\shops\\";
   if (!reg->OpenKey(reg_path, false)) {
      delete reg;
      return;
   }
   reg->GetKeyNames(cur_shops);
   reg->CloseKey();
   delete reg;
   for (int i=0; i<cur_shops->Count; i++) {
      SShop* shop = new SShop(cur_shops->Strings[i]);
      Add(shop);
   }
   cur_shops->Clear();
   delete cur_shops;
}

SShopList::~SShopList()
{
   data->Clear();
   delete data;
}

void SShopList::Add(SShop* shop)
{
   data->Add(shop);
}

void SShopList::Remove(AnsiString name)
{
   SShop* shop = getByName(name);
   if (shop != NULL) {
      shop->Delete();
      data->Delete(delete_index);
      delete shop;
   }
}

SShop* SShopList::getByName(AnsiString name)
{
   SShop* value = NULL;
   delete_index = -1;
   for(int i=0; i<data->Count; i++) {
      if ( ((SShop*)(data->Items[i]))->http_url == name ) {
         value = (SShop*)data->Items[i];
         delete_index = i;
         break;
      }
   }
   return value;
}
SShop* SShopList::getByIndex(int Index)
{
   return (SShop*)(data->Items[Index]);
}