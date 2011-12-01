<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 *
 * PHPUnit testing settings
 *
 */


// Define test mode: 1 - deployment test; 0 - web tests
define('TEST_DEPLOYMENT_MODE', 0);


// MySQL executables
define('TEST_MYSQLDUMP_BIN', 'c:\\Dev\\xampp\\mysql\\bin\\mysqldump.exe');
define('TEST_MYSQL_BIN', 'c:\\Dev\\xampp\\mysql\\bin\\mysql.exe');


// Directory where xlite-tests.config.php is located
define('XLITE_DEV_CONFIG_DIR', 'c:\\Dev\\etc');

// Directory with external libs (PHPUnit etc)
define('XLITE_DEV_LIB_DIR', 'c:\\Dev\\lib');

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
define('SELENIUM_SOURCE_URL_ADMIN', 'http://localhost/xlite/src');


if (1 === TEST_DEPLOYMENT_MODE) {

    // URL of Drupal+LC store
    define('SELENIUM_SOURCE_URL', 'http://localhost/xlite_cms');

    // Directory where Drupal installed
    define('DRUPAL_SITE_PATH', 'c:\\Dev\\xampp\\htdocs\\xlite\\src');


} else {

    // URL of Drupal+LC store
    define('SELENIUM_SOURCE_URL', 'http://localhost/xlite_cms');

    // Directory where Drupal installed
    define('DRUPAL_SITE_PATH', 'c:\\Dev\\xampp\\htdocs\\xlite\\src');

}

// Settings for screenshots capturing (on failed tests)
define('SELENIUM_SCREENSHOTS_PATH', 'c:\\Dev\\xampp\\htdocs\\selenium-screenshots');
define('SELENIUM_SCREENSHOTS_URL', 'http://localhost/selenium-screenshots');

// Selenium server host
define('SELENIUM_SERVER', 'localhost');

// Selenium TTL (sec)
define('SELENIUM_TTL', 60);

// Directory where logs will be stored. Comment this out for storing logs in xlite/var/log by default
//define('TESTS_LOG_DIR', '');


// Prevent tests failure caused by email errors (useful when sendmail not configured)
//define('TESTS_IGNORE_EMAIL_ERRORS', 1);


// Browsers list

$availableBrowsersList = array(
/*
        array(
            'name'    => 'Safari (Windows)',
            'browser' => '*safari C:\Program Files\Safari\Safari.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'Google chrome (Windows)',
            'browser' => '*googlechrome C:\Documents and Settings\user\Local Settings\Application Data\Google\Chrome\Application\chrome.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
        array(
            'name'    => 'IE (Windows)',
            'browser' => '*iexplore C:\Program Files\Internet Explorer\iexplore.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
        ),
 */
        array(
            'name'    => 'FireFox 8 (Windows)',
            'browser' => '*firefox C:\Program Files\Firefox_5\firefox.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => SELENIUM_TTL,
            'sleep'   => 1,
        ),
/*
        array(
            'name'    => 'Opera (Windows)',
            'browser' => '*opera C:\Program Files\Opera\opera.exe',
            'host'    => 'SELENIUM_SERVER',
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
