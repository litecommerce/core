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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer;

/**
 * OrderList 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderList extends \XLite\Controller\Customer\OrderList implements \XLite\Base\IDecorator
{
    /**
     * Get order ID from Drupal URL
     * 
     * @param string $path Portal path
     * @param array  $args Druapl URL arguments
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getOrderIdFromDrupalArgs($path, array $args)
    {
        return 'user/%/orders' === $path && !empty($args[1]) ? array('user_id' => $args[1]) : array();
    }

    /**
     * Argument convertion: <DRUPAL> --> <LC>
     *
     * @param string $path     Portal path
     * @param array  $args     Druapl URL arguments
     * @param array  $pageArgs LC-specific URL arguments
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPortalLCArgs($path, array $args = array(), array $pageArgs = array())
    {
        return parent::getPortalLCArgs($path, $args, $pageArgs) + static::getOrderIdFromDrupalArgs($path, $args);
    }
}
