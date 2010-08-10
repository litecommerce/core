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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Tabs;

/**
 * Tabs related to shipping settings
 * 
 * @package    XLite
 * @subpackage View
 * @see        ____class_see____
 * @since      3.0.0
 */
class ShippingSettings extends ATabs
{

    /**
     * Description of tabs related to shipping settings and their targets
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $tabs = array(
        'shipping_methods' => array(
            'title' => 'Methods',
            'template' => 'shipping/methods.tpl',
        ),
        'shipping_zones' => array(
            'title' => 'Zones',
            'template' => 'shipping/zones.tpl',
        ),
        'shipping_rates' => array(
            'title' => 'Rates',
            'template' => 'shipping/charges.tpl',
        ),
    );

    /**
     * Saved from \XLite\Controller\Admin\ShippingSettings controller.
     * TODO: check whether it is outdated and should be removed, or not
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $_shippings = null;

    /**
     * Saved from \XLite\Controller\Admin\ShippingSettings controller.
     * TODO: check whether it is outdated and should be removed, or not
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippings()
    {
        if (is_null($this->_shippings)) {

            $shipping = new \XLite\Model\Shipping();
            $modules = $shipping->getModules();
            $modules = (is_array($modules)) ? array_keys($modules) : array();
            $shippings = $shipping->findAll();
            $this->_shippings = array();
            foreach ($shippings as $shipping) {
                if (in_array($shipping->get('class'), $modules) && $shipping->get('enabled')) {
                    $this->_shippings[] = $shipping;
                }
            }
        }

        return $this->_shippings;
    }


    /**
     * Returns a list of modules defining shipping methods
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingModules()
    {
        return \XLite\Model\Shipping::getModules();
    }


}
