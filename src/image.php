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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

// To avoid superflous checks
define('XLITE_INSTALL_MODE', true);
define('LC_DO_NOT_REBUILD_CACHE', true);

require_once (__DIR__ . DIRECTORY_SEPARATOR . 'top.inc.php');

if (isset($_REQUEST['target'])) {
    switch ($_REQUEST['target']) {

        case 'module':
            if (!empty($_REQUEST['author']) && !empty($_REQUEST['name'])) {
                $path = \Includes\Utils\ModulesManager::getModuleIconFile($_REQUEST['author'], $_REQUEST['name']);
            }
            break;

        default:
            // ...
    }

    if (!empty($path)) {

        $type   = 'png';
        $data   = \Includes\Utils\FileManager::read($path);
        $length = strlen($data);

        header('Content-Type: image/' . $type);
        header('Content-Length: ' . $length);

        echo ($data);
    }
}
