<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */


/**
 * LiteCommerce installation texts (English)
 *
 * @package LiteCommerce
 * @see     ____class_see____
 * @since   1.0.0
 */


if (!defined('XLITE_INSTALL_MODE')) {
	die('Incorrect call of the script. Stopping.');
}

$translation = array (
  'Installation script' => 'Installation script',
  'PHP version' => 'PHP version',
  'PHP safe_mode' => 'PHP safe_mode',
  'Disabled functions' => 'Disabled functions',
  'Memory limit' => 'Memory limit',
  'File uploads' => 'File uploads',
  'MySQL support' => 'MySQL support',
  'PDO extension' => 'PDO extension',
  'Upload file size limit' => 'Upload file size limit',
  'Memory allocation test' => 'Memory allocation test',
  'Recursion test' => 'Recursion test',
  'File permissions' => 'File permissions',
  'MySQL version' => 'MySQL version',
  'GDlib extension' => 'GDlib extension',
  'Phar extension' => 'Phar extension',
  'HTTPS bouncers' => 'HTTPS bouncers',
  'XML extensions support' => 'XML extensions support',
  'Internal error: function :func() does not exists' => 'Internal error: function :func() does not exists',
  'Checking requirements is successfully complete' => 'Checking requirements completed successfully',
  'Some requirements are failed' => 'Some requirements failed',
  'LiteCommerce installation script not found. Restore it  and try again' => 'LiteCommerce installation script not found. Restore it and try again',
  'PHP Version must be :minver as a minimum' => 'PHP Version must be at least :minver',
  'PHP Version must be not greater than :maxver' => 'PHP Version must be not greater than :maxver',
  'Unsupported PHP version detected' => 'Unsupported PHP version detected',
  'PHP safe_mode option value should be Off if PHP is earlier 5.3.0' => 'PHP safe_mode option must be set to Off if PHP is earlier 5.3.0',
  'PHP option magic_quotes_sybase value should be Off if PHP is earlier 5.3.0' => 'PHP option magic_quotes_sybase must be set to Off if PHP is earlier 5.3.0',
  'PHP option sql.safe_mode value should be Off' => 'PHP option sql.safe_mode must be set to Off',
  'Disabled functions discovered (:funclist) that must be enabled' => 'Disabled functions found (:funclist) that must be enabled',
  'Unlimited' => 'Unlimited',
  'PHP memory_limit option value should be :minval as a minimum' => 'PHP memory_limit option must be at least :minval',
  'PHP file_uploads option value should be On' => 'PHP file_uploads option must be set to On',
  'Support MySQL is disabled in PHP. It must be enabled.' => 'MySQL support is disabled in PHP. It must be enabled.',
  'PDO extension with MySQL support must be installed.' => 'PDO extension with MySQL support must be installed.',
  'PHP option upload_max_filesize should contain a value. It is empty currently.' => 'PHP option upload_max_filesize must contain a value. It is currently empty.',
  'PHP allow_url_fopen option value should be On' => 'PHP allow_url_fopen option must be set to On',
  'Memory allocation test failed. Response:' => 'Memory allocation test failed. Response:',
  'Recursion test failed.' => 'Recursion test failed.',
  'unknown' => 'unknown',
  'Can\'t connect to MySQL server' => 'Cannot connect to MySQL server',
  'MySQL version must be :minver as a minimum.' => 'MySQL version must be at least :minver.',
  'Cannot get the MySQL server version' => 'Cannot get MySQL server version',
  'GDlib extension v.2.0 or later required for some modules.' => 'GDlib extension v.2.0 or later required for some modules.',
  'Phar extension is not loaded' => 'Phar extension not loaded',
  'libcurl extension is not found' => 'libcurl extension not found',
  'libcurl extension found but it does not support secure protocols' => 'libcurl extension found but does not support secure protocols',
  'XML/Expat and DOM extensions are required for some modules.' => 'XML/Expat and DOM extensions are required for some modules.',
  'config_writing_error' => 'Cannot open configuration file \':configfile\' for writing. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.',
  'mysql_connection_error' => 'Cannot connect to MySQL server:pdoerr.<br />This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.',
  'doRemoveCache() failed' => 'doRemoveCache() failed',
  'Creating directories...' => 'Creating directories...',
  'Creating .htaccess files...' => 'Creating .htaccess files...',
  'Copying templates...' => 'Copying templates...',
  'copy_files() failed' => 'copy_files() failed',
  'Updating config file...' => 'Updating config file...',
  'fatal_error_creating_dirs' => 'Fatal error occurred while creating directories; probably due to incorrect directory permissions. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.',
  'Login and password can\'t be empty.' => 'Login and password cannot be empty.',
  'Updating primary administrator profile...' => 'Updating primary administrator profile...',
  'Registering primary administrator profile...' => 'Registering primary administrator profile...',
  'ERROR' => 'ERROR',
  'cannot_connect_mysql_server' => 'Cannot connect to MySQL server or select required database :pdoerr.<br />Click the \'BACK\' button and review the MySQL server settings you have provided.',
  'script_renamed_text' => '
To ensure the safety of your LiteCommerce installation, the file "install.php" has been renamed to ":newname".

Should you decide to re-install LiteCommerce, make sure to rename the file ":newname" back to "install.php" and then open the following URL in your browser:
     http://:host:webdir/install.php
',
  'script_renamed_text_html' => '
<p>To ensure the safety of your LiteCommerce installation, the file "install.php" has been renamed to ":newname".</p>

<p>Should you decide choose to re-install LiteCommerce, make sure to rename the file ":newname" back to "install.php"</p>
',
  'script_cannot_be_renamed_text' => '<P><font color="red"><b>WARNING!</b> The install.php script could not be renamed! To ensure the safety of your LiteCommerce installation and prevent unauthorized use of this script, rename or delete the script manually.</font></P>',
  'correct_permissions_text' => '
Before you start using your LiteCommerce shopping system, please set the following secure file permissions:<br /><br />

<code>:perms</code>
',
  'congratulations_text' => '
Congratulations!

LiteCommerce software has been successfully installed and is now available at the following URLs:

CUSTOMER ZONE (FRONT-END)
     http://:host:webdir/cart.php

ADMINISTRATOR ZONE (BACKOFFICE)
     http://:host:webdir/admin.php
     Login (e-mail): :login
     Password:       :password

:perms

:renametext

Auth code for running install.php script is: :authcode

Thank you for choosing LiteCommerce shopping system!

--
LiteCommerce Installation Wizard

',
  'Installation complete' => 'Installation complete',
  'LiteCommerce software has been successfully installed and is now available at the following URLs:' => 'LiteCommerce software has been successfully installed and is now available at the following URLs:',
  'CUSTOMER ZONE (FRONT-END)' => 'CUSTOMER ZONE (FRONT-END)',
  'ADMINISTRATOR ZONE (BACKOFFICE)' => 'ADMINISTRATOR ZONE (BACKOFFICE)',
  'Your auth code for running install.php in the future is:' => 'Your auth code for running install.php in the future is:',
  'PLEASE WRITE THIS CODE DOWN UNLESS YOU ARE GOING TO REMOVE \':filename\'' => 'PLEASE WRITE THIS CODE DOWN UNLESS YOU ARE GOING TO REMOVE \':filename\'',
  'Creating directory: [:dirname]... ' => 'Creating directory: [:dirname]... ',
  'Already exists' => 'Already exists',
  'Failed to create directories' => 'Failed to create directories',
  'Creating file: [:filename]... ' => 'Creating file: [:filename]... ',
  'Failed to create files' => 'Failed to create files',
  'Click here to see more details' => 'Click here to see more details',
  'Failed' => 'Failed',
  'Skipped' => 'Skipped',
  'Fatal error' => 'Fatal error',
  'Please correct the error(s) before proceeding to the next step.' => 'Please correct the error(s) before proceeding to the next step.',
  'Warning' => 'Warning',
  'Installation script renamed to :filename' => 'Installation script renamed to :filename',
  'Warning! Installation script renaming failed' => 'Warning! Renaming installation script failed',
  'Incorrect auth code! You cannot proceed with the installation.' => 'Incorrect auth code! You cannot proceed with the installation.',
  'Config file not found (:filename)' => 'Config file (:filename) not found',
  'Cannot open config file \':filename\' for writing!' => 'Cannot open config file \':filename\' for writing!',
  'Config file \':filename\' write failed!' => 'Writing to config file \':filename\' failed!',
  'You must accept the License Agreement to proceed with the installation. If you do not agree with the terms of the License Agreement, do not install the software.' => 'You must accept the License Agreement to proceed with the installation. If you do not agree with the terms of the License Agreement, do not install the software.',
  'Environment checking' => 'Environment check',
  'Inspecting server configuration' => 'Checking server configuration',
  'Environment' => 'Environment',
  'Environment checking failed' => 'Environment check failed',
  'Critical dependencies' => 'Critical dependencies',
  'Critical dependencies failed' => 'Critical dependencies failed',
  'Non-critical dependencies' => 'Non-critical dependencies',
  'Non-critical dependencies failed' => 'Non-critical dependencies failed',
  'Web server name' => 'Web server name',
  'Hostname of your web server (E.g.: www.example.com).' => 'Web server hostname (e.g.: www.example.com).',
  'Secure web server name' => 'Secure web server name',
  'Hostname of your secure (HTTPS-enabled) web server (E.g.: secure.example.com). If omitted, it is assumed to be the same as the web server name.' => 'Secure (HTTPS-enabled) web server hostname (e.g.: secure.example.com).<br />If omitted, it is assumed to be the same as the web server name.',
  'LiteCommerce web directory' => 'LiteCommerce web directory',
  'Path to LiteCommerce files within the web space of your web server (E.g.: /shop).' => 'Path to LiteCommerce files within the web space of your web server (E.g.: /shop).',
  'MySQL server name' => 'MySQL server name',
  'Hostname or IP address of your MySQL server.' => 'MySQL server hostname or IP address.',
  'MySQL server port' => 'MySQL server port',
  'If your database server is listening to a non-standard port, specify its number (e.g. 3306).' => 'If your database server is listening to a non-standard port, specify the port number here (e.g. 3306).',
  'MySQL server socket' => 'MySQL server socket',
  'If your database server is used a non-standard socket, specify it (e.g. /tmp/mysql-5.1.34.sock).' => 'If your database server uses a non-standard socket, specify it here (e.g. /tmp/mysql-5.1.34.sock).',
  'MySQL database name' => 'MySQL database name',
  'The name of the existing database to use (if the database does not exist on the server, you should create it to continue the installation).' => 'Name of an existing database to use (if the database does not exist on the server,<br />create it to continue the installation).',
  'MySQL username' => 'MySQL username',
  'MySQL username. The user must have full access to the database specified above.' => 'MySQL username. The user must have unrestricted access to above specified database.',
  'MySQL password' => 'MySQL password',
  'Password for the above MySQL username.' => 'Password for the above specified MySQL username.',
  'Install sample catalog' => 'Install sample catalog',
  'Specify whether you would like to setup sample categories and products?' => 'Would you like to set up sample categories and products?',
  'Yes' => 'Yes',
  'No' => 'No',
  'The web server name and/or web drectory is invalid! Press \'BACK\' button and review web server settings you provided' => 'The web server name and/or web directory is invalid! Click the \'BACK\' button and review the web server settings you have provided',
  'Cannot open file \':filename\' for writing. To install the software, please correct the problem and start the installation again...' => 'Cannot open the file \':filename\' for writing. To install the software, please correct the problem and start the installation again...',
  'Installation Wizard has detected LiteCommerce tables' => 'Installation Wizard has detected that the specified database has existing LiteCommerce tables. If you continue with the installation, the tables will be purged.<br /><br />Click the \'Back\' button to specify a different database or click the \'Next\' button to proceed and overwrite the existing database.',
  'Can\'t connect to MySQL server specified:pdoerr<br /> Press \'BACK\' button and review MySQL server settings you provided.' => 'Cannot connect to specified MySQL server :pdoerr<br /> Click the \'BACK\' button and review the MySQL server settings you have provided.',
  'You must provide web server name' => 'You must provide a web server name',
  'You must provide MySQL server name' => 'You must provide a MySQL server name',
  'You must provide MySQL username' => 'You must provide a MySQL username',
  'You must provide MySQL database name' => 'You must provide a MySQL database name',
  'Building cache notice' => 'The cache building process may take several minutes. Please be patient and wait until the cache is built. Then click the \'Next\' button below to continue.',
  'E-mail' => 'E-mail',
  'E-mail address of the store administrator' => 'E-mail address of store administrator',
  'Password' => 'Password',
  'Confirm password' => 'Confirm password',
  'E-mail and password that you provide on this screen will be used to create primary administrator profile. Use them as credentials to access the Administrator Zone of your online store.' => 'The e-mail address and password you provide on this screen will be used for creating the primary administrator profile. Use them as the credentials for accessing the Administrator Zone of your online store.',
  'Please, enter non-empty password' => 'Please enter a non-empty password',
  'Please, enter non-empty password confirmation' => 'Please enter a non-empty password confirmation',
  'Password doesn\'t match confirmation!' => 'Password doesn\'t match confirmation!',
  'Please, specify a valid e-mail address!' => 'Please specify a valid e-mail address!',
  'Permissions checking failed. Please make sure that the following files have writable permissions:n<br /><br /><i>:perms</i>' => 'Permission check failed. Please make sure the following files are writable:n<br /><br /><i>:perms</i>',
  'Permissions checking failed. Please make sure that the following file permissions are assigned (UNIX only):n<br /><br /><i>:perms</i>' => 'Permission check failed. Please make sure the following file permissions are set (UNIX only):n<br /><br /><i>:perms</i>',
  'Cache building procedure failed:<br />nnRequest URL: :requesturl<br />nnResponse: :response' => 'Cache building procedure failed:<br />nnRequest URL: :requesturl<br />nnResponse: :response',
  'License agreement' => 'License agreement',
  'Environment checking' => 'Environment check',
  'Configuring LiteCommerce' => 'Configuring LiteCommerce',
  'Setting up templates' => 'Setting up templates',
  'Building cache' => 'Building cache',
  'Creating administrator account' => 'Creating administrator account',
  'Building cache: Pass #:step...' => 'Building cache: Pass #:step...',
  'Cache is built' => 'Cache is built',
  'Building cache: Preparing for cache generation and dropping old LiteCommerce tables if exists' => 'Building cache: Preparing for generating cache and dropping old LiteCommerce tables, if exist',
  'Click here to redirect' => 'Click here to redirect',
  'Reason: memory_get_usage() is disabled on your hosting.' => 'Reason: memory_get_usage() is disabled on your server.',
  'Fatal error: Invalid current step. Stopped.' => 'Fatal error: Invalid current step. Stopped.',
  'Internal error: function :funcname() not found' => 'Internal error: function :funcname() not found',
  'Installation Wizard' => 'Installation Wizard',
  'Version' => 'Version',
  'Step :step' => 'Step :step',
  'This installer requires JavaScript to function properly.<br />Please enable Javascript in your web browser.' => 'This installer requires JavaScript to function properly.<br />Please enable Javascript in your web browser.',
  'Back' => 'Back',
  'Try again' => 'Try again',
  'Next' => 'Next',
  'Status' => 'Status',
  'Non-critical dependency failed' => 'Non-critical dependency failed',
  'requirements_failed_text' => 'Our testing has identified some problems. Do you want to send us a report on your server configuration and test results, so that we could analyze it and fix the problems?',
  'Send report' => 'Send report',
  'requirement_warning_text' => 'Your server configuration is not optimal. This can make your LiteCommerce-based store partially or fully inoperable.<br />Continue installation anyway?',
  'Yes, I want to continue the installation.' => 'Yes, I want to continue the installation.',
  '[original report]' => '[original report]',
  '[replicated report]' => '[replicated report]',
  'Report generation failed.' => 'Report generation failed.',
  'Technical problems report' => 'Technical problems report',
  'ask_send_report_text' => 'Our testing has detected some problems. The report with the test results will be sent to our support HelpDesk, so that we could analyze and fix the problems. To monitor this issue, please login to your <a href="https://secure.qtmsoft.com/" target="_blank">HelpDesk</a>. If you do not have a HelpDesk account, you can <a href="https://secure.qtmsoft.com/customer.php?area=login&amp;target=register" target="_blank">create one here</a>.
<br /><br />You can find more information about LiteCommerce software at <a href="http://litecommerce.com/faqs.html" target="_blank"><u>LiteCommerce FAQs</u></a> page.',
  'See details' => 'See details',
  'Hide details' => 'Hide details',
  'Additional comments' => 'Additional comments',
  'Close window' => 'Close window',
  'Auth code' => 'Auth code',
  'Prevents unauthorized use of installation script' => 'Prevents unauthorized use<br />of the installation script',
  'I accept the License Agreement' => 'I accept the License Agreement and the <a href="http://www.litecommerce.com/privacy-policy.html" target="_blank">Privacy policy</a>',
  'Could not find license agreement file.<br />Aborting installation.' => 'Could not find license agreement file.<br />Aborting installation.',
  'lc_php_version_description' => 'PHP versions <b>5.3.0+</b> are currently supported.<br /><br />This version of LiteCommerce will work on any OS,<br />where PHP/MySQL meets the minimum <a href="http://www.litecommerce.com/server_requirements.html">system requirements</a>.
<br /><br />You can find more information on LiteCommerce software<br />at <a href="http://www.litecommerce.com/faqs.html">http://www.litecommerce.com/faqs.html</a>.',
  'lc_php_disable_functions_description' => 'Some functions, used by LiteCommerce, are found disabled. Check these functions are not listed in "disable_functions" option or all php extensions required for these functions availability are enabled in php.ini file. Please correct this and try again.',
  'lc_php_memory_limit_description' => 'To ensure the proper operation of LiteCommerce, the file_uploads option in php.ini should be set to 1. Please correct this parameter and try again.',
  'lc_php_mysql_support_description' => 'To ensure the proper operation of LiteCommerce with the database, the MySQL extension must be loaded in php.ini file.<br /><br />Please correct this or contact the support services of your hosting provider to adjust this parameter.',
  'lc_php_pdo_mysql_description' => 'PDO extension with enabled MySQL support is used by LiteCommerce for connecting to the database. Please make sure this extension is loaded in your php.ini file and try again.',
  'lc_php_file_uploads_description' => 'The configuration of the server where LiteCommerce will be installed meets the Server requirements; however, some server software issues, which can impair LiteCommerce operation, have been identified on the server.<br /><br />To ensure the proper operation of LiteCommerce, the value of the upload_max_filesize variable in php.ini file should contain the maximum size of files allowed for upload.',
  'lc_php_upload_max_filesize_description' => 'To ensure the proper operation of LiteCommerce, the value of upload_max_filesize option in php.ini file should contain the maximum size of files allowed for upload. Please correct this option or contact your hosting provider\'s support service to adjust this parameter.',
  'lc_php_gdlib_description' => 'GDLib 2.0 or better required for automatic generation of product thumbnails form product images and for some other modules. GDLib must be compiled with libJpeg (ensure that PHP is configured with the option --with-jpeg-dir=DIR, where DIR is the directory where libJpeg is installed). Please contact the support services of your hosting provider to adjust this parameter.',
  'lc_php_phar_description' => 'Phar extension is required for installing external LiteCommerce addons from the marketplace. Please contact your hosting provider\'s support service to adjust this parameter.',
  'lc_https_bouncer_description' => 'libCURL module with HTTPS protocol support and a valid SSL certificate are required for processing credit cards using Authorize.NET, PayPal or other payment gateways or using real-time shipping calculation services (these services require your website to accept secure connections via HTTPS/SSL). Please contact your hosting provider\'s support service to adjust this parameter.',
  'lc_xml_support_description' => 'Xml/EXPAT and DOMDocument extensions for PHP are required for using real-time shipping modules, as well as payment modules. Please contact your hosting provider\'s support service to adjust this parameter.',
  'DocBlocks support' => 'DocBlocks support',
  'DockBlock is not supported message' => 'The DocBlock feature is not supported by your PHP. This feature is required for the operation of LiteCommerce.',
  'eAccelerator loaded message' => 'The cause of blocking DocBlock feature may be the eAccelerator extension. Disable this extension and try again.',
  'lc_docblocks_support_description' => 'The Docblocks comments are used in LiteCommerce and should not be stripped out by any PHP extensions.<br /><br />If you have the eAccelerator extension loaded, unload it in php.ini file or reconfigure eAccelerator with the --with-eaccelerator-doc-comment-inclusion switch, then clean eAccelerator cache directory.',
  'Redirecting to the next step...' => 'Redirecting to the next step...',
  'Preparing data for cache generation...' => 'Preparing data for generating cache...',
  'Config file' => 'Config file',
  'lc_config_file_description' => 'Config file does not exist and cannot be copied from the default config file. It is required to proceed an installation.<br /><br />Please, follow these steps: <br /><br />1. Go to directory :dir<br />2. Copy <i>:file1</i> to <i>:file2</i><br />3. Set writeable permissions on <i>:file2</i><br /><br />Then try again.',
  'PHP option magic_quotes_runtime that must be disabled' => 'PHP option magic_quotes_runtime that must be disabled',
  'lc_php_magic_quotes_runtime_description' => 'PHP option "magic_quotes_runtime" is deprecated in PHP 5.3 and if presented in php.ini file it should be disabled for LiteCommerce correct operation. Please correct this parameter in your php.ini file and try again.',
);
