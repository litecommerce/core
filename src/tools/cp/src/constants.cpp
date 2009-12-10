#include "constants.h"

DEFAULT_PERMISSIONS/*[][2]*/ = {
	{".", "0777"},
	{"etc/config.php", "0666"},
	{"classes/modules", "0777"},
	{"bin/*", "0755"},
	//{"LICENSE", "0666"},
};

FTP_FAILURE_MESSAGE = "\r\nPossible reasons:\r\n1. Ftp server name, login and password are specified incorrectly. Please check your ftp access information.\r\n2. Your firewall does not allow passive mode ftp connections. Try to uncheck passive mode checkbox to resolve this.";

HTTP_FAILURE_MESSAGE = "\r\nInstallation wizard was unable to make test upload to your ftp server.\r\nPossible reasons:\r\n1. You specified 'Upload directory' parameter incorrectly. The Upload directory does not exist or you do not have write permissions for this directory.\r\n2. Your firewall does not allow passive mode ftp connections. Try to uncheck passive mode checkbox to resolve this.";

ADD_SHOP = "Add shop";

