<?php

// Directory where xlite-tests.config.php is located
define('XLITE_DEV_CONFIG_DIR', '/u/xcart/etc');

// Directory with external libs (PHPUnit etc)
define('XLITE_DEV_LIB_DIR', '/u/xcart/lib');


// URL of LiteCommerce store (path only, w/o script)
define('SELENIUM_SOURCE_URL_ADMIN', 'http://xcart2-530.crtdev.local/~xcart/general/projects/xlite/build/src/modules/lc_connector/litecommerce/');

// URL of Drupal+LC store
define('SELENIUM_SOURCE_URL', 'http://xcart2-530.crtdev.local/~xcart/general/projects/xlite/build/src');

// Directory where Drupal installed
define('DRUPAL_SITE_PATH', '/u/xcart/public_html/general/projects/xlite/build/src');

// Settings for screenshots capturing (on failed tests)
define('SELENIUM_SCREENSHOTS_PATH', 'C:\\Inetpub\\wwwroot\\selenium-screenshots');
define('SELENIUM_SCREENSHOTS_URL', 'http://cormorant.crtdev.local/selenium-screenshots');

// Coverage script URL
define('SELENIUM_COVERAGE_URL', 'http://xcart2-530.crtdev.local/~xcart/general/projects/xlite/build/.dev/tests/PHPUnit');

