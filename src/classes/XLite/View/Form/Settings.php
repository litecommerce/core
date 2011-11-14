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

namespace XLite\View\Form;

/**
 * Settings dialog form
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Settings extends \XLite\View\Form\AForm
{
    /**
     * getDefaultTarget
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTarget()
    {
        return \XLite\Core\Request::getInstance()->target;
    }

    /**
     * getDefaultAction
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultAction()
    {
        return 'update';
    }

    /**
     * Ability to add the 'enctype="multipart/form-data"' form attribute
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isMultipart()
    {
        return true;
    }

    /**
     * getDefaultClassName
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultClassName()
    {
        $class = parent::getDefaultClassName();

        $class .= ($class ? ' ' : '') . 'validationEngine';

        return $class;
    }

    /**
     * Required form parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCommonFormParams()
    {
        $list = parent::getCommonFormParams();

        $list['page'] = $this->page;

        if ('module' == \XLite\Core\Request::getInstance()->target) {
            $list['moduleId'] = \XLite\Core\Request::getInstance()->moduleId;
        }

        if (\XLite\Core\Request::getInstance()->return) {
            $list['return'] = \XLite\Core\Request::getInstance()->return;
        }

        return $list;
    }

}
