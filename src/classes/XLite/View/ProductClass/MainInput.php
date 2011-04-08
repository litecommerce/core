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

namespace XLite\View\ProductClass;

/**
 * Product classes main input widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class MainInput extends \XLite\View\AView
{

    const CLASS_NAME = 'className';
    const CLASS_ID   = 'classId';

    /**
     * Return allowed targets
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'product_classes';

        return $result;
    }

    /** 
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {   
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::CLASS_NAME => new \XLite\Model\WidgetParam\String('className', null),
            self::CLASS_ID   => new \XLite\Model\WidgetParam\String('classId', null),
        );  
    }   

    /**
     * getDefaultTemplate()
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'product_classes/list/main_input.tpl';
    }
}
