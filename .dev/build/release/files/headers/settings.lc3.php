<?php

/**
 * Settings for checking headers 
 *
 */

if (!defined('ONLY_CHECK')) {
    define('ONLY_CHECK', true);
}

$baseDir = realpath(__DIR__ . '/../../../../../src/');

$GPL = array(
    'DrupalConnector',
    'XMLSitemapDrupal',
);

$externalPatterns = array(
    'Module/CDev/AmazonS3Images/lib',
    'Module/CDev/ContactUs/recaptcha/recaptchalib.php',
    'Includes/Decorator/Plugin/Doctrine/Utils/ModelGenerator',
    'skins/common/js/jquery',
    'skins/common/js/php.js',
    'skins/common/js/ui',
    'skins/common/js/validationEngine',
    'skins/common/js/cloud-zoom.min.js',
    'skins/common/css/',
    'skins/common/colorpicker',
    'skins/default/en/css/jq',
    'skins/default/en/common/ui.datepicker.css',
    'skins/admin/en/modules/CDev/TinyMCE/js/tinymce',
    'skins/common/ui/jquery-ui.css',
);

$nonOSLPatterns = array_merge($GPL, $externalPatterns);

$settings[] = array(
    'fileName'         => $baseDir . '/skins',
    'excludedPatterns' => $nonOSLPatterns,
    'newHeaderFile'    => __DIR__ . '/header.osl.tpl.txt',
    'pattern'          => array('tpl'),
);

$settings[] = array(
    'fileName'         => $baseDir . '/skins',
    'excludedPatterns' => $nonOSLPatterns,
    'newHeaderFile'    => __DIR__ . '/header.osl.css.txt',
    'pattern'          => array('css', 'js'),
);

$settings[] = array(
    'fileName'         => $baseDir . '/',
    'excludedPatterns' => $nonOSLPatterns,
    'newHeaderFile'    => __DIR__ . '/header.osl.yaml.txt',
    'pattern'          => array('yaml'),
);

$settings[] = array(
    'fileName'         => $baseDir . '/classes',
    'excludedPatterns' => $nonOSLPatterns,
    'newHeaderFile'    => __DIR__ . '/header.osl.php.txt',
    'pattern'          => array('php'),
);

$settings[] = array(
    'fileName'         => $baseDir . '/Includes',
    'excludedPatterns' => $nonOSLPatterns,
    'newHeaderFile'    => __DIR__ . '/header.osl.php.txt',
    'pattern'          => array('php'),
);

foreach ( (array) glob ($baseDir . '/*.php') as $file) {
    $settings[] = array(
        'fileName' => $file,
        'newHeaderFile'    => __DIR__ . '/header.osl.php.txt',
    );
}

// GPL checking

foreach ($GPL as $module) {

$settings[] = array(
    'fileName'         => $baseDir . '/classes/XLite/Module/CDev/' . $module,
    'excludedPatterns' => $externalPatterns,
    'newHeaderFile'    => __DIR__ . '/header.gpl.php.txt',
    'pattern'          => array('php'),

);
$settings[] = array(
    'fileName'         => $baseDir . '/classes/XLite/Module/CDev/' . $module,
    'excludedPatterns' => $externalPatterns,
    'newHeaderFile'    => __DIR__ . '/header.gpl.yaml.txt',
    'pattern'          => array('yaml'),
);
$settings[] = array(
    'fileName'         => $baseDir . '/skins/admin/en/modules/CDev/' . $module,
    'excludedPatterns' => $externalPatterns,
    'newHeaderFile'    => __DIR__ . '/header.gpl.tpl.txt',
    'pattern'          => array('tpl'),
);
$settings[] = array(
    'fileName'         => $baseDir . '/skins/admin/en/modules/CDev/' . $module,
    'excludedPatterns' => $externalPatterns,
    'newHeaderFile'    => __DIR__ . '/header.gpl.css.txt',
    'pattern'          => array('css', 'js'),
);
$settings[] = array(
    'fileName'         => $baseDir . '/skins/default/en/modules/CDev/' . $module,
    'excludedPatterns' => $externalPatterns,
    'newHeaderFile'    => __DIR__ . '/header.gpl.tpl.txt',
    'pattern'          => array('tpl'),
);
$settings[] = array(
    'fileName'         => $baseDir . '/skins/default/en/modules/CDev/' . $module,
    'excludedPatterns' => $externalPatterns,
    'newHeaderFile'    => __DIR__ . '/header.gpl.css.txt',
    'pattern'          => array('css', 'js'),
);

}

foreach ($settings as $k => $s) {
    if (!file_exists($s['fileName'])) {
        unset($settings[$k]);
    }
}



