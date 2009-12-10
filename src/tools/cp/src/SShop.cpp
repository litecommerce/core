#include "SShop.h"
// Implementation {{{
SShop::SShop()
{
	ftp_port = "21";
   ftp_passive = true;
}

// Constructor by name {{{
SShop::SShop(AnsiString name)
{
	if (name == "" || name == NULL) {
		return;
	}

	TRegistry *reg = new TRegistry;
	reg->RootKey = HKEY_CURRENT_USER;
	AnsiString reg_path = "software\\LiteCommerce\\shops\\" + name;
	reg->OpenKey(reg_path, false);

	ftp_host       = reg->ReadString("ftp_host");
	ftp_port       = reg->ReadString("ftp_port");
	ftp_dir        = reg->ReadString("ftp_dir");
	ftp_login      = reg->ReadString("ftp_login");
	ftp_password   = reg->ReadString("ftp_password");

	mysql_host     = reg->ReadString("mysql_host");
	mysql_db       = reg->ReadString("mysql_db");
	mysql_login    = reg->ReadString("mysql_login");
	mysql_password = reg->ReadString("mysql_password");

	http_url       = reg->ReadString("http_url");
	admin_email    = reg->ReadString("admin_email");
	admin_password = reg->ReadString("admin_password");

	try {
		ftp_passive = reg->ReadBool("ftp_passive");
	} catch (...){
		ftp_passive = true;
		reg->WriteBool("ftp_passive", ftp_passive);
	}

	reg->CloseKey();
	delete reg;
}
// }}}

SShop::~SShop()
{
}

// method Add: add shop data to the registry {{{
bool SShop::Add()
{
	if (this->http_url == "" || this->http_url == NULL) {
		return false;
	}

	TRegistry* reg = new TRegistry();
	reg->RootKey = HKEY_CURRENT_USER;
	AnsiString reg_path = "software\\LiteCommerce\\shops\\" + this->http_url;
	if (reg->KeyExists(reg_path)) {
		AnsiString message = "Shop " + this->http_url + " already exists";
		MessageBox(NULL, message.c_str(), "Warning!", MB_OK|MB_ICONEXCLAMATION);
		return false;
	}
	reg->OpenKey(reg_path, true);
	SaveToRegistry(reg);
	reg->CloseKey();
	delete reg;
	return true;
}
// }}}

void SShop::Save()
{
	if (this->http_url == "" || this->http_url == NULL) {
		return;
	}

	TRegistry* reg = new TRegistry();
	reg->RootKey = HKEY_CURRENT_USER;
	AnsiString reg_path = "software\\LiteCommerce\\shops\\" + this->http_url;
	reg->OpenKey(reg_path, true);
	SaveToRegistry(reg);
	reg->CloseKey();
	delete reg;
}

void SShop::SaveToRegistry(TRegistry* reg)
{
	reg->WriteString("ftp_host", ftp_host);
	reg->WriteString("ftp_port", ftp_port);
	reg->WriteString("ftp_dir", ftp_dir);
	reg->WriteString("ftp_login", ftp_login);
	reg->WriteString("ftp_password", ftp_password);
	reg->WriteBool("ftp_passive", ftp_passive);

	reg->WriteString("mysql_host", mysql_host);
	reg->WriteString("mysql_db", mysql_db);
	reg->WriteString("mysql_login", mysql_login);
	reg->WriteString("mysql_password", mysql_password);

	reg->WriteString("http_url", http_url);
	reg->WriteString("admin_email", admin_email);
	reg->WriteString("admin_password", admin_password);
}

// removes shop data from registry
void SShop::Delete()
{
	if (this->http_url == "" || this->http_url == NULL) {
		return;
	}
	TRegistry* reg = new TRegistry();
	AnsiString reg_path = "software\\LiteCommerce\\shops\\" + this->http_url;
	if (reg->KeyExists(reg_path)) {
		reg->OpenKey(reg_path, false);
		TStringList* values = new TStringList;
		reg->GetValueNames(values);
		for (int i=0; i < values->Count; i++) {
			reg->DeleteValue(values->Strings[i]);
		}
		reg->CloseKey();
		reg->DeleteKey(reg_path);
	}
	delete reg;
}

void SShop::DeleteByName(AnsiString name)
{
	if (name == "" || name == NULL) {
		return;
	}
	TRegistry* reg = new TRegistry();
	AnsiString reg_path = "software\\LiteCommerce\\shops\\" + name;
	if (reg->KeyExists(reg_path)) {
		reg->DeleteKey(reg_path);
	}
	delete reg;
}


bool SShop::IsFilled(void)
{
	return (
			(this->ftp_dir == "") || (this->ftp_host == "") ||
			(this->ftp_port == "") || (!CheckEmptyShopURL()) ||
			(this->admin_email == "") || (this->admin_password == "")
		   ) ? false : true;
}

AnsiString SShop::GetEmptyFields(void)
{
	AnsiString result = "";

	result += (this->ftp_host == "ftp://" || this->ftp_host == "") ? ("- FTP Server address;\n") : "";
	result += (this->ftp_port == "") ? ("- FTP Server port;\n") : "";
	result += (this->ftp_dir == "") ? ("- FTP upload directory;\n") : "";
	result += (this->ftp_login == "") ? ("- FTP login;\n") : "";
	result += (this->ftp_password == "") ? ("- FTP password;\n") : "";
 	result += (!CheckEmptyShopURL()) ? ("- Shop URL;\n") : "";
	result += (this->admin_email == "") ? ("- Administrator e-mail;\n") : "";
	result += (this->admin_password == "") ? ("- Administrator password;") : "";

	return result;
}

bool SShop::CheckEmptyShopURL(void)
{
   AnsiString test;

   test = this->http_url;
   test = test.UpperCase();
   if (test.SubString(1,11) == "HTTP://HTTP") {
      test = this->http_url;
      this->http_url = test.SubString(8,test.Length());
      test = this->http_url;
      test = test.UpperCase();
   }

   if (!(test.Length() > 7)) {
      return false;
   } else {
      if (test.SubString(1,5) == "HTTPS") {
         test = test.SubString(1,8);
	      if (test.AnsiCompare("HTTPS://") != 0) {
            return false;
         }
      } else {
         test = test.SubString(1,7);
	      if (test.AnsiCompare("HTTP://") != 0) {
            return false;
         }
      }
   }

   return true;
}
// }}}