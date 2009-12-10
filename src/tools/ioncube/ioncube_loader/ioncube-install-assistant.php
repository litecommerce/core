<?php

//
// ionCube Loader Install Assistant v1.2
//
// Last modified 2003-08-05
//
// Copyright (c) 2003 ionCube Ltd.
//

function emit_image($data)
{
  header("Content-type: image/gif");
  header("Cache-control: public");
  echo base64_decode($data[4]);
}

function img_link($img, $width = 0, $height = 0)
{
  global $HTTP_SERVER_VARS, $images;
  
  if ($imgdata = @$images[$img]) {
    $w = ($width ? $width : $imgdata[0]);
    $h = ($height ? $height : $imgdata[1]);
    $t = $imgdata[2];
    $u = $imgdata[3];

    $im = "<img border=0 title=\"$t\" alt=\"$t\" width=$w height=$h src=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?img=$img\">";
    
    return ($u ? "<a target=_blank href=\"http://$u\">$im</a>" : $im);
  } else {
    return '';
  }
}

function query_self($text, $query)
{
  global $HTTP_SERVER_VARS;
  
  if (use_html()) {
    return '<a target=_blank href="'.$HTTP_SERVER_VARS['REQUEST_URI']."?q=$query\">$text</a>";
  } else {
    return $text;
  }
}

$images = array();

$images['logo'] = array(180,41,'ionCube Logo','www.ioncube.com','R0lGODlhtAApAMYAAAICAgoKCr6+vigoKKKiolJSUnJycu4eJmBgYGpqauRYUG5ubspORtxEWdRlc/Z4oPWtqsfHx9o+Wr46Tua5uc/Pz9pybsLCwtN9djo6OsIyQtbW1uVjVYaGhuZqZq4qMiAgINra2ri4uOp+fs46UuoqJvZaeYqKitlPVt7e3vZum8qCdvY6LkxMTN6+su4mJhgYGPY+KtY0Po6Ojt6injQ0NOpGTto6W946WPYqKu9XjZKSku1NYdqdnbCwsOLi4t4yLvaGitpqbvYiK9aemhAQEPZfjNJOSvIhJPZCS+KKipycnAYGBvO1sejo6Oa/u/BPefZqnfYmLaqqqllZWfY6SX5+fkNDQ/JOWPQvL+CYnO4iJu5NSPImKfbCxdZudfYqNvbq6vU2MfY+TP///////////////////////////////////////////////////////////////////////////////////////////////////////////////yH5BAEKAH8ALAAAAAC0ACkAAAf+gDQeYjlDXYQ5iYqLjI2LWWJZLFkmKH+XmJmam5ydnp+goaKjpKWZFE1cJV1ZMV1IsLGys7Szr7BRUKa7vL2+v8A0QTFJxUljyMnKy8zJVWPPVdIPlsDW19jZoww2JlFGJjrhRkY65Obl5+rp7OTsUQ3a8vP0vysODkL6+V9C/V//AvoTCHAgQHxCLHzBUK+hw4cQI0qcCNELhItNLmrcyLGjRwgZaTSxSIGiyZOmVhwBUuJAiRclYsqcSbMmzSw5tsTkQgLUj58/UAr9U4GA0Sn1LqpCsqWLo6dQc0ASIyWHmAcSPokowrVIhKEnEQAYy2QDPQpPuHTZcmDLlhf+cOPKnUs3rlu3SF6ouPGJwNixX8FuchKBwIkECU74MGtNLFnG8ih4sfGW7d3LmDNrrvwCCRgVWT1N+QsgsOBLIloUIf2XCQwRwBwDKHt28hYkbTfr3n3ghU7Qn0b/vXD6T4garJMDWBK7NWRtkm3APXCglnVZmn13zgLcU4gZ4Hc4OS0ggPLkPpo/rk2Zae7dmmfd7f3ic+jioSowSZ6hgw8ROyCwGnG/yEbbPNHBZRl88DH1Vm45dIcfKKuRdkVQm0QwXoHO0eOCbW69x+CIvpUgITAViCCChrukKEIFnhjA2gIOGcjYDyoKsCEoIagoQgqbRHcbW9dZB19n9mn+FUARS5qWSQRX7EfaAARw4gQISwag4wIVjlXEDpxIOVYLo0zBJJMCbJLBmWRiYmMFGbCWAYyddNCll1bsmKBvImKmIGbyZQbTXn215uQlVJw31gDP/eGEeWPtMMB5CWjiA2kBhDDKEqTBpgkIf2WQiYEdQMoaE0hp4sSk54Gg6R9ChqjbCzn86RtbbVGn61uddREFX6KRdugViv5VxHM/mHrnqU7KBsAVpHD6l6eZgDqWqG4WiymdmFjbGmsDXCLkrZc5iFsWNrDwDDJJEFOMu8Wwm4QNWBU6XCYLsNYCAT4kcGe4mDzKWhEdiFAqaQVkwupYVW7a6SYLY3uJs2P+1bDEFIlaiK+FImxwQcZjdQCrbbhVB8ttTCERoQlQQMGDDi63LLPMPMwMhQ4aBCcsJimYCkCql2zg7XKYJEuaq5gIcHQmpjLBbSjSjkVtt6GOyhoVmUQNQACMOdFlwlZ7OTJl5AK6RRIPyKCEFlr00APbbsftNtxra0GEC/aOReAfVpBGoyZK/wVCwD6nV62xGP4hptPRPqxJxGGPBcOOlywMQMN+/ZV40KZWMK6IDrqVhApHUJ6NcIBhYjmQmwy9odGSm95Ca68q/hfXjU/betXZ/vV3JieQVukfsoHgxPHIOwHDX0skKOuQC46hAgph0JO53pcof3snzgYm8LX+m4DMRO0+p+mw7o/zPrHjT2r8R5xkdSW/8OPihdfJW1ShwhYKcOB//xzwQAAHKMD+GRAFFtDZXwLTM2NxjzSpgh0AwJYJ8dVuaCI7n9R2B77ejeVpl6jAlC4xNG214AmT6Qx1ynYXMPAAC+kiRCQkwYIa1lAMOJTKJIwgg7wBgEA/6FIROpEA0jTsewAYXgVphwmQAQBgotDa1By1vA6u7y8g/MMGSFMDEmrLQnuyTOju4hvcOEhXuFlQdW5THe7chxOoKw0mqjiWTiBngZeQoBKbyMRLXG8shoMa+y6RAjFJjHikCSQmRICwS8APACAQgQAkSclJCkAAngMRdUL+N8aU3Q9/KTuZb9aSJE/8cW933KCq7kQ434Wvj5cQEwBgsDlPaI05mbiU+hD5F6xpIl9/scIlgAmAIYIijPfrDBnN5RYFebIyZNyCGxWYukvsYISa6AAXi2aqPSIKln/Q5tEaxQlG/oWCl0glAA7prCLUzlE+85Q5x4JLTwjJPWbzkz5Z+ILe/OaNmzhlwO5EhR0RQJbUQuLv+EiWd/6hhExAQAV25IQKLMB8G5Alt4pIGnaypgasc8LsjLUjOgZAkZcQQAbA5DxBgZKT+LsL/vqJpBNtIo5O0tosr9ACywGgTXns5isbqokU0JE0RQCBUqUENMsxgaclXGfkjMX+02UJExN/BEANDLCEDlChiiy1TT9z00loznRIMY3mNE1Jmr0N84sDoJxChzobh/4hBY8sFpj8qKgAeMuj3zpPXDXB0WLNYGyVWWNZh0SuvOSFhWzxTSk7EUe3XmIJy2pNAkznBDF58w8W7MQSosqaDL41OTD42C5lU4QlHPUvA7DrH3aQ2dakJ1agi+aILnOrA6y1ExUogHCpINsfzKAGMOjKABKQxUskQLgFQOkfCABdBJhOExcwQA1AID8QZMAAjfLBmrgygA6MZwnQPSxWoZuAMPzAAAPoSg322okUnAC5XYFBDTrAGBRyAWXz0dUmcyXgApeMjKMk1DWSdxKWBn8Ceb84Xi0f7GBMfIgL/URCCcx1Mg7HAq0d1u1bVICDCZk4IihUQEvagoQuxCUvnKmLgv7EK14p+MQ4PksqstCFIbS4xV0I8o9fIWRbCDnIXfDnELLwgHjk+MnaGAEWsGCCKlv5yljOspazHIUgOBnKYAbGByRQZR2Y+cxoNnM41HzmNaf5zDczxwTCTOc62/nOpAgEADs=');
$images['blank'] = array(0,0,'','','R0lGODlhCgAKAID/AMDAwAAAACH5BAEAAAAALAAAAAAKAAoAAAIIhI+py+0PYysAOw==');

if ($img = @$_GET['img']) {
  if ($imgdata = @$images[$img]) {
    emit_image($imgdata);
  }
  exit(0);
}

if ($q = @$_GET['q']) {
  if ($q == 'phpinfo') {
    phpinfo(INFO_GENERAL);
  }
  exit(0);
}

//
// Determine PHP flavour
//

$php_version = phpversion();
$php_flavour = substr($php_version,0,3);


//
// Get the full name and short name for the OS
//

$os_name = substr(php_uname(),0,strpos(php_uname(),' '));
$os_code = strtolower(substr($os_name,0,3));

$dll_sfix = (($os_code == 'win') ? '.dll' : '.so');


//
// Analyse the PHP build
//

ob_start();
phpinfo(INFO_GENERAL);
$php_info = ob_get_contents();
ob_end_clean();

$thread_safe = false;
$debug_build = false;
$php_ini_path = '';

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
    if (@!file_exists($php_ini_path)) {
      $php_ini_path = '';
    }
  }
}



//
// We now know enough to give guidance
//
$ts = ((($os_code != 'win') && $thread_safe) ? '_ts' : '');

$required_loader = "ioncube_loader_${os_code}_${php_flavour}${ts}${dll_sfix}";


function use_html()
{
  return (php_sapi_name() != 'cli');
}

function para($text)
{
  return ($text . (use_html() ? '<p>' : "\n\n"));
}

function code($text)
{
  return (use_html() ? "<code>$text</code>" : $text);
}

function table($contents)
{
  if (use_html()) {
    $res = '<table bgcolor="#e0e0ff" cellpadding=5 cellspacing=0 border=0>';
    foreach ($contents as $row) {
      $res .= "<tr>\n";
      foreach ($row as $cell) {
	$res .= "<td>$cell</td>\n";
      }
      $res .= "</tr>\n";
    }
    $res .= "</table>\n";
  } else {
    $colwidths = array();
    foreach ($contents as $row) {
      $cv = 0;
      foreach ($row as $cell) {
	$l = @$colwidths[$cv];
	$cl = strlen($cell);

	if ($cl > $l) {
	  $colwidths[$cv] = $cl;
	}
	$cv++;
      }
    }
    $tw = 0;
    foreach ($colwidths as $cw) { $tw += ($cw + 2); }
    $tw2 = $tw + count($colwidths) - 1 + 2;
    $res = '+' . str_repeat('-',$tw2 - 2) . "+\n";
    foreach ($contents as $row) {
      $cv = 0;
      foreach ($row as $cell) {
	$res .= '| ' . str_pad($cell, $colwidths[$cv]) . ' ';
	$cv++;
      }
      $res .= "|\n";
    }
    $res .= '+' . str_repeat('-',$tw2 - 2) . "+\n";
  }

  return $res;
}

function ilia_header()
{
  if (use_html()) {
    return '<html>
<head>
<title>ionCube Loader Install Assistant</title>
</head>
<body bgcolor=white>
<table width="100%" cellpadding=5 cellspacing=0 border=0>
<tr>
<td>'.img_link('logo').'</td>
<td valign=bottom align=center><font face="helvetica,verdana" size="+2">Loader Install Assistant</font></td>
<td>'.img_link('blank',180,1).'</td></tr>
</table>
<p>';
  } else {
    return '
ionCube Loader Install Assistant
--------------------------------

';
  }
}

function heading($text)
{
  if (use_html()) {
    return para("<font face=\"helvetica,verdana\"><b>$text</b></font>");
  } else {
    return para($text . "\n" . str_repeat('-', strlen($text)));
  }
}

function ilia_analysis()
{
  global $php_version, $php_flavour, $os_name, $thread_safe, $php_ini_path, $required_loader,$os_code;

  $res = para('Analysis of your system configuration shows:')
    . table(array(array("PHP Version",$php_version),
		  array("Operating System",$os_name),
		  array("Threaded PHP",($thread_safe ? 'Yes' : 'No')),
		  array("php.ini file", ($php_ini_path ? $php_ini_path : query_self('Check phpinfo() output for location','phpinfo'))),
		  array("Required Loader",$required_loader)
		  ))
    . para('');

  $res .= heading('Installing the Loader in php.ini');

  $res .= para('To install the loader in your '.code('php.ini').' file, just edit
the '.($php_ini_path ? code($php_ini_path).' ' : '') . 'file and add the following line before any
other '.code('zend_extension').' lines:');

  if ($os_code == 'win') {
    if (use_html()) {
      $path = '&lt;drive&gt;:\\&lt;path&gt;\\';
    } else {
      $path = '<drive>:\\<path>\\';
    }

    $ini = "zend_extension_ts = $path$required_loader";
  } else {
    if (use_html()) {
      $path = '/&lt;path&gt;/';
    } else {
      $path = '/<path>/';
    }

    if ($thread_safe) {
      $ini = "zend_extension_ts = $path$required_loader";
    } else {
      $ini = "zend_extension = $path$required_loader";
    }
  }

  if (use_html()) {
    $res .= "<table bgcolor=\"#c8e8c8\" cellpadding=2 cellspacing=0 border=0><tr><td><code>$ini</code></td></tr></table><p>";
  } else {
    $res .= para("  $ini");
  }

  if ($os_code == 'win') {
    $res .= para('where '.code($path).' is where you\'ve installed the loader.');
  } else {
      $res .= para('where '.code($path).' is where you\'ve installed the loader, e.g. '.code('/usr/local/ioncube/'));
  }

  $res .= para("Finally, stop and restart your web server software for the changes to
take effect.");

  if (!ini_get('safe_mode') && ($os_code != 'win')) {
    $res .= heading('Installing the Loader for run-time loading');

    $res .= para('To install for runtime loading, create a directory called '.code('ioncube') . '
at or above the top level of your encoded files, and ensure that the directory
contains the '.code($required_loader) . ' loader. If run-time install of
the Loader is possible on your system then encoded files should automatically
install the loader when needed.');
  }

  return $res;
}

function ilia_footer()
{
  $email = 'support@ioncube.com';
  if (use_html()) $email = "<a href=\"mailto:$email\">$email</a>";

  $res = heading('Further Help');

  $res .= para("Please contact $email if you require further assistance with
your install.");

  if (use_html()) {
    $res .= '
<table width="100%" cellpadding=0 cellspacing=5 border=0>
<tr bgcolor="#8080e0">
<td>'.img_link('blank',1,1).'</td>
</tr>
<tr align=center>
<td><font size="1" face="helvetica,verdana,sans-serif">Copyright 2002-2003 ionCube Ltd.&nbsp;&nbsp;All rights reserved.</td>
</tr>
</table>
</body>
</html>
';
  }

  return $res;
}

function ilia_debug_builds_unsupported()
{
  $email = 'support@ioncube.com';
  if (use_html()) $email = "<a href=\"mailto:$email\">$email</a>";

  return para('IMPORTANT NOTE: Your PHP installation may be incorrect
------------------------------------------------------

Your PHP installation appears to be built with debugging
support enabled, and extensions cannot be installed in this case.')
    .para('Debugging support in PHP produces slower execution, is not recommended for
production builds, and was probably a mistake.')
    .para("Debugging support may sometimes be incorrectly detected, and so please
continue to follow the installation instructions and try the Loader. 
However do email us at $email if the Loader fails to be 
installed, and include a web link to either this script or a page that 
calls phpinfo() so that we can help.");
}


//
// Create the output
//

echo ilia_header();

echo ilia_analysis();

if ($debug_build) {
  echo ilia_debug_builds_unsupported();
}

echo ilia_footer();
