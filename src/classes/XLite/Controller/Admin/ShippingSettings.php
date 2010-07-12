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

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ShippingSettings extends \XLite\Controller\Admin\AAdmin
{
    public $params = array('target');
    public $page = "shipping_methods";
    public $pages = array('shipping_methods' => 'Methods ',
         			   'shipping_zones' => 'Zones',
                       'shipping_rates' => 'Charges'
                       );

    public $_shippings = null;

    function getShippings()
    {
        if (!is_null($this->_shippings)) {
            return $this->_shippings;
        }

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
        
        return $this->_shippings;
    }
}
