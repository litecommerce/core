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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * Get pattern for header search
 */
function getHeaderPattern($key)
{
    $pattern = null;

    switch ($key) {

        case 'php':
            $pattern = '/^(<\?php.*\/\*\*.+\*\/\s)/sSUm';
            break;

        case 'tpl':
            $pattern = '/^(.*\{\*\*.*\*\}\s)/sSUm';
            break;

        case 'css':
        case 'js':
            $pattern = '/^(.*\/\*\*.*\*\/\s)/sSUm';
            break;

        case 'yaml':
            $pattern = '/(^#.*\s+$)+/SsUm';
            break;

        default:
            // No default actions
    }

    return $pattern;
}

/**
 * Parse header and get comment
 */
function getComment($key, $header)
{
    $comment = null;

    switch($key) {

        case 'tpl':

            $pattern = '/\{\*\*\s*\*\s*(\S.*)$/sSUm';

            if (preg_match($pattern, $header, $match)) {
                $comment = $match[1];
            }
            break;

        case 'css':
        case 'js':

            $pattern = '/\/\*\*\s*\*\s*(\w.*)$/sSUm';

            if (preg_match($pattern, $header, $match)) {
                $comment = $match[1];
            }

            break;

        case 'yaml':

            $pattern = '/^# vim.*#\s*(^#\s+(\w.*)$)/sSUm';

            if (preg_match($pattern, $header, $match)) {
                $comment = $match[2];
            }

            break;

        default:
            // No default actions
    }

    return $comment;
}

/**
 * Replace header
 */
function replace($fileName, $newHeader)
{
    $result = false;

    if (file_exists($fileName)) {

        $content = file_get_contents($fileName);
        
        $fileType = preg_replace('/^.*\.(\w+)$/sSU', '\1', basename($fileName));

        $headerPattern = getHeaderPattern($fileType);

        if (preg_match($headerPattern, $content, $match)) {

            $year = null;

            if (preg_match('/Copyright \(c\) ([\d\- ]+) Creative/m', $match[1], $match2)) {
                $year = $match2[1];
            }

            $comment = getComment($fileType, $match[1]);

            if ('tpl' == $fileType) {

                preg_match_all('/^( \* @(\w+)\W.+)$/sSUm', $match[1], $tags);

                if (!empty($tags[2])) {
                    $bottom = '';
                    foreach ($tags[2] as $k => $tag) {
                        if (!in_array($tag, array('author', 'copyright', 'license', 'link', 'since'))) {
                            $bottom .= $tags[1][$k] . PHP_EOL;
                        }
                    }

                    if (!empty($bottom)) {
                        $newHeader = preg_replace('/(^ \*\}$)/sSUm', ' *' . PHP_EOL . $bottom . '\1', $newHeader);
                    }
                }
            }

            if (empty($year)) {
                $year = '2011';
            }

            if (!empty($year)) {

                if (preg_match('/^\d+$/', $year) && 2012 > intval($year)) {
                    $year = sprintf('%s-2012', $year);
                }

                $replaceHeader = str_replace(
                    array('%yearplaceholder%', '%commentplaceholder%'),
                    array($year, $comment),
                    $newHeader
                );

                if ($newContent = preg_replace($headerPattern, $replaceHeader, $content, 1)) {
                    file_put_contents($fileName, $newContent);
                    $result = true;
                }

            } else {
                $result = 'ERROR: cannot detect year in ' . $fileName;
            }

        } else {
            $result = 'ERROR: header not found in ' . $fileName;
        }

    } else {
        $result = 'ERROR: File ' . $fileName . ' not found';
    }

    return $result;
}

/**
 * Check header
 */
function check($fileName, $newHeader)
{
    $result = false;

    $fileType = preg_replace('/^.*\.(\w+)$/sSU', '\1', basename($fileName));

    $newHeader = preg_replace('/[\n\r]/', '', $newHeader);

    if ('tpl' == $fileType) {

        $newHeader = preg_replace('/(^.*$)\Z/SUm', '', $newHeader);
        $newHeader = preg_replace('/(^.*$)\Z/SUm', '', $newHeader);

        $newHeader = trim($newHeader);
    }

    $headerPattern = preg_quote($newHeader, '/');
    $headerPattern = str_replace('%yearplaceholder%', '[\\d\\- ]+', $headerPattern);
    $headerPattern = str_replace('%commentplaceholder%', '.+', $headerPattern);

    if (file_exists($fileName)) {
        $content = file_get_contents($fileName);

        $content = preg_replace('/[\r\n]/', '', $content);

        if (preg_match('/(' . $headerPattern . ')/sSU', $content, $match)) {
            $result = true;

        } else {
            $result = 'ERROR: ' . $fileName . ' has different header';
        }

    } else {
        $result = 'ERROR: File ' . $fileName . ' not found';
    }

    return $result;
}


/**
 * Main function
 */
function main($setting)
{
    global $totalCounter, $checkedCounter;

    extract($setting);

    // Get header
    $newHeader = file_get_contents($newHeaderFile);

    if (!empty($pattern)) {
        foreach ($pattern as $k => $v) {
            $pattern[$k] = preg_quote($v, '/');
        }
        $pattern = '/.+\.(' . implode('|', $pattern) . ')$/';
    }

    // Get exclude pattern
    if (!empty($excludedPatterns)) {
        foreach ($excludedPatterns as $k => $v) {
            $v = trim($v);
            if (!empty($v)) {
                $excludedPatterns[$k] = preg_quote($v, '/');
            }
        }
        $excludedPattern = '/(' . implode('|', $excludedPatterns) . ')/SsU';
    }

    if (is_dir($fileName)) {

        $dirIterator = new RecursiveDirectoryIterator($fileName);
        $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $filePath => $fileObject) {

            if (empty($excludedPattern) || !preg_match($excludedPattern, $filePath)) {

                if (!is_dir($filePath)) {

                    $totalCounter ++;

                    if (empty($pattern) || preg_match($pattern, $filePath, $matches)) {
        
                        $res = defined('ONLY_CHECK') ? check($filePath, $newHeader) : replace($filePath, $newHeader);

                        $checkedCounter ++;

                        if (true !== $res) {
                            echo PHP_EOL . $res;

                        } else {
                            echo '.';
                        }
                    }
                }
            }
        }

    } else {

        $res = defined('ONLY_CHECK') ? check($fileName, $newHeader) : replace($fileName, $newHeader);

        $checkedCounter ++;
        $totalCounter ++;

        if (true !== $res) {
            echo PHP_EOL . $res;
        }
    }
}




/**
 * Get input options
 */
$options = getopt("cf:n:p:s:x:");


/**
 * Check input options
 */

$settings = array();

// Settings file: -s file
if (isset($options['s'])) {

    if (file_exists($options['s'])) {
        include_once $options['s'];

    } else {
        die('ERROR: file with new header not found' . PHP_EOL);
    }
}

if (empty($settings)) {

    $setting = array();

    // Perform only checking the headers: -c
    if (isset($options['c']) && !defined('ONLY_CHECK')) {
        define('ONLY_CHECK', true);
    }

    // New header for replacement: -n file
    if (isset($options['n'])) {

        if (file_exists($options['n'])) {
            $setting['newHeaderFile'] = $options['n'];

        } else {
            die('ERROR: file with new header not found' . PHP_EOL);
        }

    } else {
    	echo '-n argument is not defined' . PHP_EOL;
    	exit (1);
    }

    // File/directory to check/replace header: -f file
    if (isset($options['f'])) {

        if (file_exists($options['f'])) {
            $setting['fileName'] = $options['f'];

        } else {
            die('ERROR: File ' . $options['f'] . ' not found');
        }

    } else {
    	echo '-f argument is not defined' . PHP_EOL;
    	exit (1);
    }

    // File type (if directory is specified): -p php
    if (isset($options['p'])) {
        $setting['pattern'] = array($options['p']);
    }

    // File contained filter for excluding files from search: -x file
    if (isset($options['x'])) {

        if (file_exists($options['x'])) {

            $excludedPatterns = file($options['x']);

            $setting['excludedPatterns'] = $excludedPatterns;

        } else {
            die('ERROR: File ' . $options['x'] . ' not found');
        }
    }

    $settings[] = $setting;
}


$totalCounter = 0;
$checkedCounter = 0;

foreach ($settings as $s) {
    main($s);    
}

$action = (defined('ONLY_CHECK') ? 'Checked' : 'Processed');

echo PHP_EOL;
echo "$action $checkedCounter of $totalCounter file(s)" . PHP_EOL;

