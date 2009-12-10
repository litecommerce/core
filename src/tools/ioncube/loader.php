<?php 
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003-2009     |
| Creative Development LLC. All Rights Reserved.                               |
+------------------------------------------------------------------------------+
*
* ionCube runtime loader
*
* $Id: loader.php,v 1.28 2009/09/13 12:56:00 fundaev Exp $
*/

// Ioncube Loader should be installed or available for dynamic loading

function func_lc_get_php_info() 
{
    static $lc_php_info;

    if (!isset($lc_php_info)) {
        $lc_php_info = array(
            "thread_safe" => false,
            "debug_build" => false,
            "php_ini_path" => ''
            );
    } else {
        return $lc_php_info;
    }

    ob_start();
    phpinfo(INFO_GENERAL);
    $php_info = ob_get_contents();
    ob_end_clean();

    $os_name = substr(php_uname(),0,strpos(php_uname(),' '));
    $os_code = strtolower(substr($os_name,0,3));
    $dll_sfix = (($os_code == 'win') ? '.dll' : '.so');

    foreach (split("\n",$php_info) as $line) {
        if (eregi('command', $line)) {
            continue;
        }
        if (eregi('thread safety.*(enabled|yes)', $line)) {
            $lc_php_info["thread_safe"] = true;
        }
        if (eregi('debug.*(enabled|yes)', $line)) {
            $lc_php_info["debug_build"] = true;
        }
        if (eregi("configuration file.*(</B></td><TD ALIGN=\"left\">| => |v\">)([^ <]*)(.*</td.*)?",$line,$match)) {
            $lc_php_info["php_ini_path"] = $match[2];
            //
            // If we can't access the php.ini file then we probably lost on the match
            //
            if (!ini_get("safe_mode") && @!file_exists($lc_php_info["php_ini_path"])) {
                $lc_php_info["php_ini_path"] = '';
            }
        }
    }
    return $lc_php_info;
}

function func_lc_get_ioncube_loader() 
{
    if (ini_get("safe_mode")) 
    	return false;

    $info = func_lc_get_php_info();
    $_ds = DIRECTORY_SEPARATOR;
    $php_version = phpversion();
    $php_flavour = substr($php_version,0,3);
    $os_name = substr(php_uname(),0,strpos(php_uname(),' '));
    $os_code = strtolower(substr($os_name,0,3));
    $dll_sfix = (($os_code == 'win') ? '.dll' : '.so');
    $ts = ((($os_code != 'win') && $info["thread_safe"]) ? '_ts' : '');
    $loader = "ioncube_loader_${os_code}_${php_flavour}${ts}${dll_sfix}";

    return $loader;
}

if (!function_exists("get_php_execution_mode")) {
function get_php_execution_mode() {
    global $options;
	return isset($options['filesystem_permissions']['permission_mode']) ? $options['filesystem_permissions']['permission_mode'] : 0;
}
}

function func_lc_load_ioncube_encoder() 
{
    global $lc_ioncube_loader_error;

	if(ini_get("safe_mode") || !ini_get("enable_dl")) {
		$lc_ioncube_loader_error = 1;
        return false;
    }    

    $loader = func_lc_get_ioncube_loader();
    $ext = ini_get('extension_dir');

    // attempt to load from extensions dir
    @dl($loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
	$lc_ioncube_loader_error |= 2;

    $ds = DIRECTORY_SEPARATOR;

    // attempt to load from the current directory
    $local_path = "." . $ds . "ioncube" . $ds;
	@dl($local_path . $loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
    $lc_ioncube_loader_error |= 4;

    //attemp to load from the current directory with chdir()
    $chdir_path = "." . $ds . "ioncube" . $ds;
    if(chdir($chdir_path)){
        @dl($loader);
        chdir(".." . $ds);
        if(extension_loaded('ionCube Loader')){
            return true;
        }
    }
    $lc_ioncube_loader_error |= 8;

    // attempt to load from the current directory with root as a top
    $cwd = getcwd() . $ds . "ioncube" . $ds;
    @dl($cwd . $loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
    $lc_ioncube_loader_error |= 16;

    // attempt to load from an extension dir as a current 
    $os_name = substr(php_uname(),0,strpos(php_uname(),' '));
    $os_code = strtolower(substr($os_name,0,3));
    if ($os_code == 'win') {
        $cwd = substr($cwd, 2, strlen($cwd));
    }
    $ext_path = realpath($ext);
    if ($ext_path === false) {
	   	$ext_path = $ext;
	}
	$relative_path = str_repeat("${ds}..", substr_count($ext_path, $ds)) . $cwd;
    @dl($relative_path . $loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
    $lc_ioncube_loader_error |= 32;

    return false;
}

function func_lc_get_ioncube_loader_error()
{
    global $lc_ioncube_loader_error;
	
	return "0x".strtoupper(sprintf("%02x", $lc_ioncube_loader_error));
}

function func_lc_get_is_forbidden_version()
{
    $php_version = phpversion();
    $forbidden_versions = array
    (
    	array("min" => "4.2.2", "max" => "4.2.3"),
    	array("min" => "5.0.0", "max" => "5.0.9"),
    );

    foreach($forbidden_versions as $fpv) {
    	if ($php_version >= $fpv["min"] && $php_version <= $fpv["max"]) {
    		return true;
    	}
    }

    return false;
}

function func_lc_show_splash($message)
{
    $splash_str = <<<EOT
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" style="FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; COLOR: #292E7F; FONT-SIZE: 12px; MARGIN-TOP: 0 px; MARGIN-BOTTOM: 0 px; MARGIN-LEFT: 0 px; MARGIN-RIGHT: 0 px; BACKGROUND-COLOR: #FFFFFF;">
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center" height="100%">
<TR>
<TD style="BACKGROUND-COLOR: #6685A5;">&nbsp;</TD>
</TR>
<TR>
<TD height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<TR>
<TD style="BACKGROUND-COLOR: #6685A5;" height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<TR>
<TD height="100%" valign="center" align="center">
<TABLE border="0" cellspacing="0" cellpadding="0" width="700" height="70%"> 
<TR><TD>
<P align="center"> $message; </P>
</TD></TR></TABLE>
</TD>
</TR>
</TABLE>
</BODY>
EOT;

	echo $splash_str;
}


// check for forbidden versions
if (func_lc_get_is_forbidden_version()) {
	$message = "<B>Unsupported version of PHP (" . phpversion() . ").<BR>Currently versions 4.1.0 - 4.4.X, 5.1.X and 5.2.X are supported. <BR><BR> </B>";
	$message .= '</P><P align="justify">This version of LiteCommerce will work on any OS where PHP/MySQL meets minimum <a href="http://www.litecommerce.com/server_requirements.html">system requirements</a>.<br><br>For more information about LiteCommerce software, please visit <a href="http://www.litecommerce.com">LiteCommerce website</a>.';
	func_lc_show_splash($message);
	die;
}

// check for the extension installed
if ((bool) extension_loaded('ionCube Loader')) {
	return;
}
if(func_lc_load_ioncube_encoder() && function_exists('_il_exec')) {
	return _il_exec();
} else {
	$message = 'This software is protected by <b>ionCube PHP Encoder</b>.<br>System failed to install appropriate <a href="http://www.ioncube.com/loader_download.php">ionCube PHP Loader</a> (<b>'.func_lc_get_ioncube_loader().'</b>) for your system (internal code '.func_lc_get_ioncube_loader_error().').<br><br>
</P><P align="justify">Possible reasons:<br>
1. You are using MS Windows and LiteCommerce and PHP are located on different logical drives (eg. PHP is installed on disk C: and LiteCommerce on disk D:). To resolve this, move LiteCommerce to the same disk as PHP or put all loader files from the "ioncube" directory to the directory defined in the variable extension_dir in php.ini file (c:\php4\extensions\ by default).
Also, you can visit the <a href="http://www.ioncube.com/loader_download.php">ionCube PHP Loader homepage</a>, download the valid loaders for your system and install them following the instructions, provided by README file in loaders archive. <br>
2. Unsupported version of PHP (currently versions 4.1.0 - 4.4.X, 5.1.X and 5.2.X are supported)<br>
3. Dynamic loading is disabled at your system (eg. safe mode is on, or enable_dl is off)<br>
4. Unsupported operation system (currently LiteCommerce software is available for Windows NT/2000/XP, FreeBSD 4, Linux Intel, Solaris Sparc, NetBSD).<br><br>

This version of LiteCommerce will work on any OS where PHP/MySQL meets minimum <a href="http://www.litecommerce.com/server_requirements.html">system requirements</a>.<br><br>
For more information about LiteCommerce software, please visit <a href="http://www.litecommerce.com">LiteCommerce website</a>.';
	func_lc_show_splash($message);
	die;
}
?>
