<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 *
 * PHPUnit testing settings
 *
 */


// Define test mode: 1 - deployment test; 0 - web tests
define('TEST_DEPLOYMENT_MODE', @TEST_DEPLOYMENT_MODE@);


// MySQL executables
//define('TEST_MYSQLDUMP_BIN', 'c:\\Dev\\xampp\\mysql\\bin\\mysqldump.exe');
//define('TEST_MYSQL_BIN', 'c:\\Dev\\xampp\\mysql\\bin\\mysql.exe');


// Directory where xlite-tests.config.php is located
define('XLITE_DEV_CONFIG_DIR', '@XLITE_DEV_CONFIG_DIR@');

// Directory with external libs (PHPUnit etc)
define('XLITE_DEV_LIB_DIR', '@XLITE_DEV_LIB_DIR@');

/**
 * List of directories PHPUnit cloned from GitHub: http://www.phpunit.de/
 * PHPUnit v.3.5 required
 */
$__include_paths = array(
    'dbunit',
    'php-code-coverage',
    'php-file-iterator',
    'php-text-template',
    'php-timer',
    'php-token-stream',
    'phpunit',
    'phpunit-mock-objects',
    'phpunit-selenium',
);


// URL of LiteCommerce store (path only, w/o script)
define('SELENIUM_SOURCE_URL_ADMIN', '@SELENIUM_SOURCE_URL_ADMIN@');

if (1 === TEST_DEPLOYMENT_MODE) {

    // URL of Drupal+LC store
    define('SELENIUM_SOURCE_URL', '@SELENIUM_SOURCE_URL@');

    // Directory where Drupal installed
    define('DRUPAL_SITE_PATH', '@DRUPAL_SITE_PATH@');

} else {

    // URL of Drupal+LC store
    define('SELENIUM_SOURCE_URL', '@SELENIUM_SOURCE_URL@');

    // Directory where Drupal installed
    define('DRUPAL_SITE_PATH', '@DRUPAL_SITE_PATH@');
}

// Settings for screenshots capturing (on failed tests)
define('SELENIUM_SCREENSHOTS_PATH', '@SELENIUM_SCREENSHOTS_PATH@');
define('SELENIUM_SCREENSHOTS_URL', '@SELENIUM_SCREENSHOTS_URL@');

// Selenium server host
define('SELENIUM_SERVER', '@SELENIUM_SERVER@');

// Selenium TTL (sec)
define('SELENIUM_TTL', 60);

// Directory where logs will be stored. Comment this out for storing logs in xlite/var/log by default
//define('TESTS_LOG_DIR', '');


// Browsers list

$availableBrowsersList = array(
/*
        array(
            'name'    => 'Safari (Windows)',
            'browser' => '*safari',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'Google chrome (Windows)',
            'browser' => '*googlechrome C:\Documents and Settings\rnd\Local Settings\Application Data\Google\Chrome\Application\chrome.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'IE 8 (Windows)',
            'browser' => '*iexplore',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'FireFox 3 (Windows)',
            'browser' => '*firefox C:\Program Files\Mozilla Firefox 3\firefox.exe',
            'host'    => 'localhost',
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'FireFox 3.5 (Windows)',
            'browser' => '*firefox C:\Program Files\Mozilla Firefox 3.5\firefox.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'FireFox 3.6 (Windows)',
            'browser' => '*firefox C:\Program Files\Mozilla Firefox-3.6.15\firefox.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'FireFox 4 (Windows)',
            'browser' => '*firefox C:\Program Files\Mozilla Firefox 4\firefox.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
            'sleep'   => 1,
        ),
 */
        array(
            'name'    => 'FireFox 5 (Windows)',
            'browser' => '*firefox C:\Program Files\Firefox_5\firefox.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
            'sleep'   => 1,
        ),
/*
        array(
            'name'    => 'Opera 9 (Windows)',
            'browser' => '*opera C:\Program Files\Opera9\opera.exe',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
         array(
            'name'    => 'Opera 10 (Windows)',
            'browser' => '*opera C:\Program Files\Opera10\opera.exe',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'Opera 11 (Windows)',
            'browser' => '*opera C:\Program Files\Opera11\opera.exe',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
*/
);


// Use constant to pass browsers list to the test core as PHPUnit rewrites global variables
define('XLITE_TEST_BROWSERS_LIST', serialize($availableBrowsersList));


// Prepare include_path
if (defined('XLITE_DEV_LIB_DIR')) {
    foreach ($__include_paths as $k => $v) {
        $__include_paths[] = XLITE_DEV_LIB_DIR . DIRECTORY_SEPARATOR . $v;
    }
}

// Define constant for include_path
define('XLITE_DEV_PHPUNIT_PATH', implode(PATH_SEPARATOR, $__include_paths));
