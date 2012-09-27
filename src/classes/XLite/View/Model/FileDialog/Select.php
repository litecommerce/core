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

namespace XLite\View\Model\FileDialog;

/**
 * File select dialog model widget
 *
 */
class Select extends \XLite\View\Model\AModel
{

    /**
     * Return object name
     *
     * @return string
     */
    public function getObject()
    {
        return \XLite\Core\Request::getInstance()->object;
    }

    /**
     * Return object identificator
     *
     * @return string
     */
    public function getObjectId()
    {
        return \XLite\Core\Request::getInstance()->objectId;
    }

    /**
     * Return file object name
     *
     * @return string
     */
    public function getFileObject()
    {
        return \XLite\Core\Request::getInstance()->fileObject;
    }

    /**
     * Return file object identificator
     *
     * @return string
     */
    public function getFileObjectId()
    {
        return \XLite\Core\Request::getInstance()->fileObjectId;
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Profile
     */
    protected function getDefaultModelObject()
    {
        return null;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\FileDialog\Select';
    }
}
