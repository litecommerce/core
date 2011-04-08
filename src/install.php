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
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */


/**
 * LiteCommerce (standalone edition) web installation wizard
 * 
 * @package LiteCommerce
 * @see     ____class_see____
 * @since   1.0.0
 */


if (!(basename(__FILE__) === 'install.php')) { // it is not install.php file
    die();
}

define ('XLITE_INSTALL_MODE', 1);
define('LC_DO_NOT_REBUILD_CACHE', true);

if (version_compare(phpversion(), '5.3.0') < 0) {
    die('LiteCommerce cannot start on PHP version earlier than 5.3.0 (' . phpversion(). ' is currently used)');
}

$filesToInclude = array(
    '/Includes/install/init.php',  // Installation initialization
    '/Includes/install/install.php', // Installation functions
    '/Includes/install/templates/common_html.php', // Installation common html blocks functions
);

foreach ($filesToInclude as $_file) {

    if (!file_exists($includeFuncsFile = realpath(dirname(__FILE__)) . $_file)) {
        die('Fatal error: Couldn\'t find file ' . $includeFuncsFile);
    }

    include_once $includeFuncsFile;
}



// Installation modules (steps)
$modules = array (
	array( // 0
			"name"          => 'default',
			"comment"       => xtr('License agreement'),
            "auth_required" => false,
			"js_back"       => 0,
			"js_next"       => 1,
            "remove_params" => array(
                'auth_code',
                'new_installation',
                'force_current',
                'start_at'
            )
		),
	array( // 1
			"name"          => 'check_cfg',
			"comment"       => xtr('Environment checking'),
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array(
                'xlite_http_host',
                'xlite_https_host',
                'xlite_web_dir',
                'mysqlhost',
                'mysqlbase',
                'mysqluser',
                'mysqlpass',
                'mysqlport',
                'mysqlsock',
            )
		),
	array( // 2
			"name"          => 'cfg_install_db',
			"comment"       => xtr('Configuring LiteCommerce'),
            "auth_required" => true,
			"js_back"       => 1,
			"js_next"       => 1,
		    "remove_params" => array(
                'demo',
                'install_data',
                'images_to_fs'
            )
        ),
	array( // 4
			"name"          => 'install_dirs',
			"comment"       => xtr('Setting up templates'),
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array()
        ),
	array( // 5
			"name"          => 'install_cache',
			"comment"       => xtr('Building cache'),
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array()
		),

	array( // 6
			"name"          => 'cfg_create_admin',
			"comment"       => xtr('Creating administrator account'),
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 1,
            "remove_params" => array(
                'login',
                'password',
                'confirm_password'
            )
		),
	array( // 7
			"name"          => 'install_done',
			"comment"       => xtr('Installation complete'),
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array()
		)
);

/*
 * Process service requests
 */
if (isset($_GET['target']) && $_GET['target'] == 'install') {

	// Loopback action
	if (isset($_GET['action']) && $_GET['action'] == 'loopback_test') {
		die('LOOPBACK-TEST-OK');
	}

    // Return HTTP_HOST value
    if (isset($_GET['action']) && $_GET['action'] == 'http_host') {
        die($_SERVER['HTTP_HOST']);
    }

    // Building cache action
    if (isset($_GET['action']) && $_GET['action'] == 'build_cache') {

        $step = 0;
        $result = true;

        show_install_html_header();
        show_install_css();
?>

</head>

<body>

<?php

        $jsDots =<<<OUT
<span id="progress-dots"></span>

<script type="text/javascript">

loaded = false;
maxCounter = 100;
counter = 0;

function doProgressDots() {

    if (!loaded && counter < maxCounter) {
        document.getElementById('progress-dots').innerHTML = document.getElementById('progress-dots').innerHTML + ' .';
        counter = counter + 1;
        setTimeout('doProgressDots()', 1000);
    }
}

doProgressDots();

</script>

OUT;

        if (isset($_GET['step']) && intval($_GET['step']) > 0) {

            $step = intval($_GET['step']);

            if ($step <= 3) {
                echo xtr('Building cache: Pass #:step', array(':step' => $step)) . $jsDots;
                echo '</body></html>';
                echo str_repeat(' ', 10000);
                flush();
                $result = doBuildCache();
            
            } else {
                die('<div id="finish">' . xtr('Cache is built') . '</div>');
            }

        } else {
            $pdoErrorMsg = '';
            echo xtr('Building cache: Preparing for cache generation and dropping an old LiteCommerce tables if exists') . $jsDots;
            echo '</body></html>';
            echo str_repeat(' ', 10000); 
            flush();
            $result = doRemoveCache(null, $pdoErrorMsg);
        }

        if ($result) {
            $location = sprintf('install.php?target=install&action=build_cache&step=%d&%d', ++$step, time());

?>

<script type="text/javascript">
    loaded = true;
    self.location="<?php echo $location; ?>";
</script>

<noscript>
    <a href="' . $location . '"><?php echo xtr('Click here to redirect'); ?></a>
</noscript>

<?php
        }

        exit();
    }

    // Creating dirs action
    if (isset($_GET['action']) && $_GET['action'] == 'dirs') {

        $result = true;

        show_install_html_header();
        show_install_css();
?>

<script type="text/javascript"> 
    loaded = false; 
 
    function refresh() { 
        window.scroll(0, 100000); 
                 
        if (loaded == false) 
           setTimeout('refresh()', 1000); 
    } 
     
    setTimeout('refresh()', 1000); 
</script>

<body>

<?php

        echo str_repeat(' ', 1000); flush();
        $result = doInstallDirs();

?>
        
<script type="text/javascript">
    loaded = true;
</script>

<div id="finish"></div>

</body>
</html>

<?php
        exit();
    }


	// Memory test action
	if (isset($_GET['action']) && $_GET['action'] == 'memory_test' && isset($_GET['size'])) {
        $size = intval($_GET['size']);

		if ($size <= 0) {
			die('MEMORY-TEST-INVALID-PARAMS');
		}

		if (!function_exists('memory_get_usage')) {
			die("MEMORY-TEST-SKIPPED\n" . xtr('Reason: memory_get_usage() is disabled on your hosting.'));
		}

		// check memory limit set
        $res = @ini_get('memory_limit');

        if (!check_memory_limit($res, $size . 'M')) {
            die('MEMORY-TEST-LIMIT-FAILED');
        }

		$size -= (ceil(memory_get_usage() / (1024*1024)) + 1);

        $array = array();

		for ($i = 0; $i < $size; $i++) {
			$array[] = str_repeat('*', 1024 * 1024);
		}

		die('MEMORY-TEST-OK');
	}

    // Recursion test action
	if (isset($_GET['action']) && $_GET['action'] == 'recursion_test') {
		recursion_depth_test(1);
		die('RECURSION-TEST-OK');
	}
}

// First error flag
$first_error = null;

// Error flag
$error = false;

// Check copyright file
define('COPYRIGHT_FILE', './LICENSE.txt');
define('COPYRIGHT_EXISTS', @file_exists(COPYRIGHT_FILE));

$current = 0;
$params = array();

if (COPYRIGHT_EXISTS) {
    $current = (isset($_POST['current']) ? intval($_POST['current']) : 0);
    $params = (isset($_POST['params']) && is_array($_POST['params']) ? $_POST['params'] : array());
}

// Process 'Go back' action: remove params
if (isset($_POST['go_back']) && $_POST['go_back'] === '1') {

    for ($i = $current; $i < count($modules); $i++) {

        for ($j = 0; $j < count($modules[$i]['remove_params']); $j++) {

            if(isset($params[$modules[$i]['remove_params'][$j]])) {
                unset($params[$modules[$i]['remove_params'][$j]]);
            }
        }
    }
}

// Force current step processing
if (isset($params['force_current']) && !isset($params['start_at']) ) {
    $params['start_at'] = $params['force_current'];
}

if (isset($params['force_current']) && $params['force_current'] == get_step('check_cfg')) {
	$params['new_installation'] = $params['force_current'];
	unset($params['force_current']);
}

if ($current < 0 || $current >= count($modules)) {
	die(xtr('Fatal error: Invalid current step. Stopped.'));
}

// check for the pre- and post- methods

if ($current) {

    if (isset($modules[$current - 1]['post_func'])) {

		check_authcode($params);
        $func = 'module_' . $modules[$current - 1]['name'] . '_post_func';

        if (function_exists($func)) {
            $func();

        } else {
            die(xtr('Internal error: function :funcname() not found', array(':funcname' => $func)));
        }
    }
}

// should the current be set here?
if (isset($params['force_current']) && (isset($_POST['go_back']) && $_POST['go_back'] === '0') ) {
	$current = $params['force_current'];
	check_authcode($params);
	unset($params['force_current']);
}

$skinsDir = 'skins_original/admin/en/';

// start html output

show_install_html_header();

show_install_css();

include LC_ROOT_DIR . 'Includes/install/templates/common_js_code.js.php'; 

?>

  <script type="text/javascript">

<?php


// show module's pertinent scripts

// 'back' button's script
switch ($modules[$current]['js_back']) {
	case 0:
		default_js_back();
		break;
	case 1:
		$func = 'module_' . $modules[$current]['name'] . '_js_back';
		$func();
		break;
	default:
		die('Fatal error: Invalid js_back value for module ' . $modules[$current]['name']);
}

// 'next' button's script
switch ($modules[$current]['js_next']) {
	case 0:
		default_js_next();
		break;
	case 1:
		$func = 'module_' . $modules[$current]['name'] . '_js_next';
		$func();
		break;
	default:
}

?>

function setNextButtonDisabled(flag)
{
    if (flag) {
        document.getElementById('next-button').className = 'next-button disabled-button';
        document.getElementById('next-button').disabled = 'disabled';

    } else {
        document.getElementById('next-button').className = 'next-button';
        document.getElementById('next-button').disabled = '';
    }
}

  </script>

</head>

<body>

<div id="page-container" class="install-page">

  <div id="header">

    <div class="logo"></div>

    <div class="sw-version">
      <div class="current">LiteCommerce shopping cart software v.<?php echo LC_VERSION; ?></div>
      <div class="upgrade-note">
        &copy; 2011 <a href="http://www.cdev.ru/">Creative Development LLC</a>
      </div>
    </div>

    <h1><?php echo xtr('Installation wizard'); ?></h1>

  </div><!-- [/header] -->

<?php

/**
 * Generating an array for displaying installation steps
 */

$rows = array();

foreach ($modules as $id => $moduleData) {

    $index = $id + 1;
    $currentIndex = $current + 1;

    $divIndex = null;
    $stepTitle = (string)$index;

    $row = array();

    $row[] = 'step-row';

    if ($currentIndex > $index) {
        $arrowClass = 'prev-prev';

    } elseif ($currentIndex == $index) {
        $arrowClass = 'prev-next';
        $stepTitle = xtr('Step :step', array(':step' => $index)) . ': ' . $moduleData['comment'];

    } else {
        $row[] = 'next';
        $arrowClass = 'next-next';
    }

    if ($index == 1) {
        $row[] = 'first';

    } elseif ($index == count($modules)) {
        $row[] = 'last';
    }

    $rows[] = sprintf('<li class="%s">%s</li>', implode(' ', $row), $stepTitle);

    if ($index < count($modules)) {
        $rows[] = sprintf('<li class="step-row %s"></li>', $arrowClass);
    }
}

?>

  <div class="steps-bar">

    <ul class="steps">

<?php

// Display installation steps
foreach ($rows as $row) {
    echo $row . "\n";
}

?>

    </ul>

  </div>

<noscript>
    <div class="ErrorMessage"><?php echo xtr('This installer requires JavaScript to function properly.<br />Please enable Javascript in your web browser.'); ?></div>
</noscript>


<div class="content">

<?php

/* common form */


// check whether the form encoding type is set
$enctype = (isset($modules[$current]['form_enctype']) ? 'enctype="' . $modules[$current]['form_enctype'] . '"'  : '');

?>

<form method="post" name="ifrm" action="install.php" <?php print $enctype ?>>

<?php

// get full function's name to call the corresponding module
$func = 'module_' . $modules[$current]['name'];

// check the auth code if required
if ($modules[$current]['auth_required']) {
	check_authcode($params);
}

// run module
$res = $func($params);

?>

<br />
<br />

<?php

// show navigation buttons
$prev = $current;

if (!$res) {
    $current += 1;
}

if ($current < count($modules)) {


    if (!empty($params)) {

	    foreach ($params as $key => $val) {

?>

  <input type="hidden" name="params[<?php echo $key ?>]" value="<?php echo $val ?>" />

<?php
    
        }
    }

?>

  <input type="hidden" name="go_back" value="0" />
  <input type="hidden" name="current" value="<?php echo $current ?>" />

<?php

if (isset($autoPost)) {

    
?>

    <div><?php echo xtr('Redirecting to the next step...'); ?></div>

    <script type="text/javascript">
    document.ifrm.submit();
    </script>

<?php

} else {

?>

<table class="buttons-bar" align="center" cellspacing="20">

<tr>

<td>
<?php
    if ($prev > 0) {
?>
  <input type="button" id="back-button" class="small-button" value="<?php echo xtr('Back'); ?>" onclick="javascript:document.ifrm.go_back.value='1'; return step_back();" />
<?php
    } else {
?>
  <input type="button" class="small-button disabled-button" id="back-button" value="<?php echo xtr('Back'); ?>" disabled="disabled" />
<?php
    }
?>
</td>

<?php

    if (isset($tryAgain) && true == $tryAgain) {

 ?>

<td>
  <input id="try-button" name="try_again" type="button" value="<?php echo xtr('Try again'); ?>" onclick="javascript:document.ifrm.go_back.value='1'; document.ifrm.current.value='1'; document.ifrm.submit();" />
</td>

<?php
       
    }

?>

<td class="next-button-layer">
  <input id="next-button" name="next_button" type="submit" value="<?php echo xtr('Next'); ?>"<?php echo ($error || $current == get_step('check_cfg') ? ' class="next-button disabled-button" disabled="disabled"' : ' class="next-button"'); ?> onclick="javascript: if (step_next()) { ifrm.submit(); return true; } else { return false; }" />
</td>

</tr>

</table>

<?php

}
}


?>

</form>

<?php

/* common bottom */

?>

<br />
<br />
<br />

</div><!-- [/content] -->

</div><!-- [/page-container] -->

<?php 

if (2 == $current) {
    include_once LC_DIR . '/Includes/install/templates/step1_report.tpl.php'; 
}

?>

<script type="text/javascript">

var element = document.getElementById('report-layer');

if (element) {
    element.style.height = (screen.availHeight + 200) + 'px';
}

</script>

</body>
</html>
