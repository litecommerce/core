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
     * Package management error
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $packingError = array();


    // {{{ Error handler

    /**
     * Return error description
     * 
     * @return string|array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPackingError()
    {
        if (!empty($this->packingError)) {
            list($message, $code) = $this->packingError;
        }

        return empty($message) ? null : $message;
    }

    /**
     * Set error description
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setPackingError($message, $code = null)
    {
        $this->packingError = array($message, $code);
    }

    // }}}

    // {{{ Working with packages

    /**
     * Save archive in temporary directory and unpack it
     * 
     * @param string $source    Archive content
     * @param string $extension File extension OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function unpack($source, $extension = null)
    {
        $dir = null;

        if (!empty($source)) {

            // Check and set extension
            if (!isset($extension)) {
                $extension = \Includes\Utils\PHARManager::getExtension() ?: 'tar';
            }

            // Get unique file name
            $file = tempnam($this->getTempDir(), 'phr');

            // Remove temporary file and add the extension
            if ($file) {
                \Includes\Utils\FileManager::delete($file);
                 $file .= '.' . $extension;
            }

            // Save data into created file
            if ($file && \Includes\Utils\FileManager::write($file, $source)) {

                // Extract archive files into a new directory
                $dir = \Includes\Utils\PHARManager::unpack($file, $this->getTempDir());

                if ($dir) {
                    \Includes\Utils\FileManager::delete($file);
                } else {
                    $this->setPackingError('Unable to extract archive files');
                }

            } else {
                $this->setPackingError('Unable to save archive in temporary directory');
            }

        } else {
            $this->setPackingError('An empty package recieved');
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
        return LC_DIR_TMP;
    }

    // }}}
}
