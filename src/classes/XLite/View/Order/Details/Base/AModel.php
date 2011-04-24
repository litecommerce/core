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

namespace XLite\View\Order\Details\Base;

/**
 * AModel 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AModel extends \XLite\View\Model\AModel
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'order';

        return $result;
    }
    
    /**
     * Return current order ID
     *
     * NOTE: this method is public since it's used 
     * by the external widgets (e.g. forms)
     * 
     * @return integer 
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOrderId()
    {
        return intval(\XLite\Core\Request::getInstance()->order_id);
    }
    
    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Order
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Order')->find($this->getOrderId());
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Order\Details\Admin\Form';
    }

    /**
     * Return title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Order #' . $this->getOrderId() . ' details';
    }
}
