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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Shipping settings controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Controller_Admin_ShippingSettings extends XLite_Controller_Admin_ShippingSettings
implements XLite_Base_IDecorator
{
    /**
     * Get shippings list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippings()
    {
        if (is_null($this->_shippings)) {

            parent::getShippings();

            $method = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();

            foreach ($this->_shippings as $shippingKey => $shipping) {
                if ($shipping->get('class') == 'ups') {
                    $name = $method->getNameUPS($shipping->get('name'));

                    $name = str_replace('<sup>nd', '-nd', $name);
                    $name = str_replace('<sup>', ' ', $name);
                    $name = str_replace('</sup>', '', $name);

                    $this->_shippings[$shippingKey]->set('name', $name);
                }
            }
        }

        return $this->_shippings;
    }
}
