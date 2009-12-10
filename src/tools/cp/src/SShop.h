#include <Registry.hpp>
#pragma once

class SShop {
	public:
		///////////////////////
		// ftp settings {{{
		AnsiString ftp_host;
		AnsiString ftp_port;
		AnsiString ftp_dir;
		AnsiString ftp_login;
		AnsiString ftp_password;
		bool 		ftp_passive;
		// }}}

		///////////////////////
		// MySQL settings {{{
		AnsiString mysql_host;
		AnsiString mysql_db;
		AnsiString mysql_login;
		AnsiString mysql_password;
		// }}}

		///////////////////////
		// other settings {{{
		AnsiString http_url;
		AnsiString admin_email;
		AnsiString admin_password;
		// }}}

		///////////////////////
		// Methods {{{
		SShop();
		SShop(AnsiString name);
		~SShop();

		bool IsFilled(void);
		bool Add();
		void Delete();
		void DeleteByName(AnsiString name);
		void Save();
		AnsiString GetEmptyFields(void);
		bool CheckEmptyShopURL(void);
		// }}}

	private:
		void SaveToRegistry(TRegistry* reg);
};
