<?php

//
// ionCube Run Time Loading Compatibility Tester 1.7
//
// Last modified 2003-12-22
//
// Copyright (c) 2002-2003 ionCube Ltd.
//

//
// Detect some system parameters
//
function ic_system_info()
{
  $thread_safe = false;
  $debug_build = false;
  $cgi_cli = false;
  $php_ini_path = '';

  ob_start();
  phpinfo(INFO_GENERAL);
  $php_info = ob_get_contents();
  ob_end_clean();

  foreach (split("\n",$php_info) as $line) {
    if (eregi('command',$line)) {
      continue;
    }

    if (eregi('thread safety.*(enabled|yes)',$line)) {
      $thread_safe = true;
    }

    if (eregi('debug.*(enabled|yes)',$line)) {
      $debug_build = true;
    }

    if (eregi("configuration file.*(</B></td><TD ALIGN=\"left\">| => |v\">)([^ <]*)(.*</td.*)?",$line,$match)) {
      $php_ini_path = $match[2];

      //
      // If we can't access the php.ini file then we probably lost on the match
      //
      if (!@file_exists($php_ini_path)) {
	$php_ini_path = '';
      }
    }

    $cgi_cli = ((strpos(php_sapi_name(),'cgi') !== false) ||
		(strpos(php_sapi_name(),'cli') !== false));
  }

  return array('THREAD_SAFE' => $thread_safe,
	       'DEBUG_BUILD' => $debug_build,
	       'PHP_INI'     => $php_ini_path,
	       'CGI_CLI'     => $cgi_cli);
}


//
// Text or HTML output?
//
$nl =  ((php_sapi_name() == 'cli') ? "\n" : '<br>');

$ok = true;
$already_installed = false;

//
// Where are we?
//
$here = dirname(__FILE__);

echo "\n";

//
// Is the loader already installed?
//
if (extension_loaded('ionCube Loader')) {
  echo "An ionCube Loader is already installed and run-time loading is unnecessary.
Encoded files should load without problems.$nl$nl
If you have problems running encoded files make sure that if you installed
using FTP that you use binary mode. If unpacking files with WinZIP you must 
disable the 'TAR Smart CR/LF conversion' feature.$nl$nl";
  $already_installed = true;
} else {
  //
  // Intro
  //
  echo "Testing whether your system supports run-time loading...$nl$nl";
}



//
// Test some system info
//
$sys_info = ic_system_info();

if (!$already_installed) {
  if ($sys_info['THREAD_SAFE'] && !$sys_info['CGI_CLI']) {
    echo "Your PHP install appears to have threading support and run-time Loading
is only possible on threaded web servers if using the CGI, FastCGI or
CLI interface.$nl${nl}To run encoded files please install the Loader in the php.ini file.$nl";
    $ok = false;
  }

  if ($sys_info['DEBUG_BUILD']) {
    echo "Your PHP installation appears to be built with debugging support
enabled and this is incompatible with ionCube Loaders.$nl${nl}Debugging support in PHP produces slower execution, is
not recommended for production builds and was probably a mistake.${nl}${nl}You should rebuild PHP without the --enable-debug option and if
you obtained your PHP install from an RPM then the producer of the
RPM should be notified so that it can be corrected.$nl";
    $ok = false;
  }

  //
  // Check safe mode and for a valid extensions directory
  //
  if (ini_get('safe_mode')) {
    echo "PHP safe mode is enabled and run time loading will not be possible.$nl";
    $ok = false;
  } elseif (!is_dir(realpath(ini_get('extension_dir')))) {
    echo "The setting of extension_dir in the php.ini file is not a directory
or may not exist and run time loading will not be possible. You do not need
write permissions on the extension_dir but for run-time loading to work
a path from the extensions directory to wherever the Loader is installed
must exist.$nl";
    $ok = false;
  }

  // If ok to try and find a Loader
  if ($ok) {
    //
    // Look for a Loader
    //

    // Old style naming should be long gone now
    $test_old_name = false;

    $_u = php_uname();
    $_os = substr($_u,0,strpos($_u,' '));
    $_os_key = strtolower(substr($_u,0,3));

    $_php_version = phpversion();
    $_php_family = substr($_php_version,0,3);

    $_loader_sfix = (($_os_key == 'win') ? '.dll' : '.so');

    $_ln_old="ioncube_loader.$_loader_sfix";
    $_ln_old_loc="/ioncube/$_ln_old";

    $_ln_new="ioncube_loader_${_os_key}_${_php_family}${_loader_sfix}";
    $_ln_new_loc="/ioncube/$_ln_new";

    echo "${nl}Looking for Loader '$_ln_new'";
    if ($test_old_name) {
      echo " or '$_ln_old'";
    }
    echo $nl.$nl;

    $_oid = $_id = realpath(ini_get('extension_dir'));
    $_here = dirname(__FILE__);
    if ((@$_id[1]) == ':') {
      $_id = str_replace('\\','/',substr($_id,2));
      $_here = substr($_here,2);
    }
    $_rd=str_repeat('/..',substr_count($_id,'/')).$_here.'/';

    echo "Extensions Dir: $_id$nl";
    echo "Relative Path:  $_rd$nl";

    $_ln = '';
    $_i=strlen($_rd);
    while($_i--) {
      if($_rd[$_i]=='/') {
	if ($test_old_name) {
	  // Try the old style Loader name
	  $_lp=substr($_rd,0,$_i).$_ln_old_loc;
	  $_fqlp=$_oid.$_lp;
	  if(@file_exists($_fqlp)) {
	    echo "Found Loader:   $_fqlp$nl";
	    $_ln=$_lp;
	    break;
	  }
	}
	// Try the new style Loader name
	$_lp=substr($_rd,0,$_i).$_ln_new_loc;
	$_fqlp=$_oid.$_lp;
	if(@file_exists($_fqlp)) {
	  echo "Found Loader:   $_fqlp$nl";
	  $_ln=$_lp;
	  break;
	}
      }
    }

    //
    // If Loader not found, try the fallback of in the extensions directory
    //
    if (!$_ln) {
      if ($test_old_name) {
	if (@file_exists($_id.$_ln_old_loc)) {
	  $_ln = $_ln_old_loc;
	}
      }
      if (@file_exists($_id.$_ln_new_loc)) {
	$_ln = $_ln_new_loc;
      }

      if ($_ln) {
	echo "Found Loader $_ln in extensions directory.$nl";
      }
    }

    echo $nl;

    if ($_ln) {
      echo "Trying to install Loader - this may produce an error...$nl$nl";
      dl($_ln);

      if(extension_loaded('ionCube Loader')) {
	echo "The Loader was successfully installed and encoded files should be able to
automatically install the Loader when needed. No changes to your php.ini file
are required to use encoded files on this system.${nl}";
      } else {
	echo "The Loader was not installed.$nl";
      } 
    } else {
      echo "Run-time loading should be possible on your system but no suitable Loader
was found.$nl$nl";
      echo "The $_os Loader for PHP $_php_family releases is required.$nl";
    }
  }
}

$email = 'support@ioncube.com';
if (php_sapi_name() != 'cli') {
  $email = "<a href=\"mailto:$email\">$email</a>";
}

echo "${nl}Please send the output of this script to $email if you
have questions or require assistance.$nl$nl";

?>
