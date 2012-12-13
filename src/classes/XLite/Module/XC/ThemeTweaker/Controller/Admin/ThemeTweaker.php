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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\XC\ThemeTweaker\Controller\Admin;

/**
 * CustomJavaScript controller
 *
 */
abstract class ThemeTweaker extends \XLite\Controller\Admin\AAdmin
{
    /**
     * FIXME- backward compatibility
     *
     * @var   array
     */
    protected $params = array('target');

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL()
            || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage theme tweaker');
    }

    /**
     * Save file
     *
     * @return void
     */
    protected function doActionSaveFile()
    {
        \Includes\Utils\FileManager::write($this->getFileName(), \XLite\Core\Request::getInstance()->code);
    }

    /**
     * Get file content
     *
     * @return string
     */
    public function getFileContent()
    {
        return \Includes\Utils\FileManager::read($this->getFileName());
    }

    /**
     * Get file name 
     *
     * @return string
     */
    protected function getFileName()
    {
        return \XLite\Module\XC\ThemeTweaker\Main::getThemeDir()
                . str_replace('_', '.', \XLite\Core\Request::getInstance()->target);
    }
}
