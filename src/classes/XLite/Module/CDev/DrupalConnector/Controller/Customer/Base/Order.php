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

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer\Base;

/**
 * Order controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Order extends \XLite\Controller\Customer\Base\Order implements \XLite\Base\IDecorator
{
    /**
     * Get ID of LC profile
     *
     * @param integer $id Drupal profile ID
     *
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getLCProfileId($id)
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getProfileIdByCMSId($id);
    }

    /**
     * Get ID of Drupal profile
     *
     * @param integer $id LC profile ID
     *
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getDrupalProfileId($id)
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSIdByProfileId($id);
    }

    /**
     * Check if current page is the "Order history" portal
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isPortal()
    {
        return (bool) \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getPortalByPath('user/%/orders/%');
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
        $result = parent::getPortalLCArgs($path, $args, $pageArgs);

        if (static::isPortal() && !empty($args[1]) && !empty($args[3])) {
            $result['profile_id'] = static::getLCProfileId($args[1]);
            $result['order_id'] = $args[3];
        }

        return $result;
    }

    /**
     * Argument convertion: <LC> --> <DRUPAL>
     *
     * @param string $path Drupal path
     * @param array  $args LC URL arguments
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPortalDrupalArgs($path, array $args = array())
    {
        foreach (array('profileId' => 'profile_id', 'orderId' => 'order_id') as $var => $param) {

            $$var = empty($args[$param]) ? \XLite\Core\Request::getInstance()->$param : $args[$param];
            unset($args[$param]);
        }

        $result = parent::getPortalDrupalArgs($path, $args);

        $result = preg_replace('/\%/', static::getDrupalProfileId($profileId), $result, 1);
        $result = preg_replace('/\%/', $orderId, $result, 1);

        return $result;
    }
}
