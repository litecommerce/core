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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin\Base;

/**
 * PackManager 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class PackManager extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Save archive in temporary directory and unpack it
     * 
     * @param string $source Archive content
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function unpack($source)
    {
        $dir = null;

        // Get unique file name
        $file = tempnam($this->getTempDir(), 'phr');

        // Save data into created file
        if ($file && \Includes\Utils\FileManager::write($file, $source)) {
            $dir = \Includes\Utils\PHARManager::unpack($file, $this->getTempDir());

            if (!$dir) {
                \XLite\Core\TopMessage::addError('Unable to extract archive files');
            }

        } else {
            \XLite\Core\TopMessage::addError('Unable to save archive in temporary directory');
        }

        return $dir;
    }

    /**
     * Return dir to temporary save and unpack archives
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTempDir()
    {
        return LC_TMP_DIR;
    }
}
