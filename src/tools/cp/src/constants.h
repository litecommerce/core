#ifndef Constants__H
#define Constants__H
//---------------------------------------------------------------------------
#include <vcl.h>
#include <classes.hpp>
//---------------------------------------------------------------------------
typedef enum {upgrade = 0, hotfix = 1, u_unknown = 2} upgrade_type;

// default permissions
char* const DEFAULT_PERMISSIONS[][2] = {
	{".", "0777"},
	{"etc/config.php", "0666"},
	{"classes/modules", "0777"},
	{"bin/*", "0755"},
   {"var", "0777"}
};

// FTP check failure message
AnsiString const FTP_FAILURE_MESSAGE = "\r\nPossible reasons:\r\n1. Ftp server name, login and password are specified incorrectly. Please check your ftp access information.\r\n2. Your firewall does not allow passive mode ftp connections. Try to uncheck passive mode checkbox to resolve this.";

// HTTP check failure message
AnsiString const HTTP_FAILURE_MESSAGE = "\r\nInstallation wizard was unable to make test upload to your ftp server.\r\nPossible reasons:\r\n1. You specified 'Upload directory' parameter incorrectly. The Upload directory does not exist or you do not have write permissions for this directory.\r\n2. Your firewall does not allow passive mode ftp connections. Try to uncheck passive mode checkbox to resolve this.";

//
AnsiString const ADD_SHOP = "Add shop";

// file contains upgrade_from and upgrade_to versions
AnsiString const UPGRADE_VERSION_FILE = "UPGRADEVER";

//---------------------------------------------------------------------------
#endif
