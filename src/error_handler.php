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
 * PHP version 5.0.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

define('LC_ERR_TAG_MSG',   '@MSG@');
define('LC_ERR_TAG_ERROR', '@ERROR@');
define('LC_ERR_TAG_CODE',  '@CODE@');

define('LC_ERROR_PAGE_MESSAGE', 'ERROR: "' . LC_ERR_TAG_ERROR . '" (' . LC_ERR_TAG_CODE . ') - ' . LC_ERR_TAG_MSG);

/**
 * Display error message
 *
 * @param string  $code    Error code
 * @param string  $message Error message
 * @param string  $page    Template of message to display
 *
 * @return void
 * @see    ____func_see____
 * @since  3.0.0
 */
function showErrorPage($code, $message, $page = LC_ERROR_PAGE_MESSAGE, $prefix = 'ERROR_')
{
    echo str_replace(
        array(LC_ERR_TAG_MSG, LC_ERR_TAG_ERROR, LC_ERR_TAG_CODE),
        array($message, str_replace($prefix, '', $code), defined($code) ? constant($code) : 'N/A'),
        $page
    );

    exit (intval($code) ? $code : 1);
}

// Check PHP version before any other operations
if (!defined('LC_DO_NOT_CHECK_PHP_VERSION') && version_compare(PHP_VERSION, '5.3.0', '<')) {
    showErrorPage('ERROR_UNSUPPORTED_PHP_VERSION', 'Min allowed PHP version is 5.3.0');
}
