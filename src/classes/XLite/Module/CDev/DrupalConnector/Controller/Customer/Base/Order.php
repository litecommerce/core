<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer\Base;

/**
 * Order controller
 *
 */
abstract class Order extends \XLite\Controller\Customer\Base\Order implements \XLite\Base\IDecorator
{
    /**
     * Argument convertion: <DRUPAL> --> <LC>
     *
     * @param string $path     Portal path
     * @param array  $args     Druapl URL arguments OPTIONAL
     * @param array  $pageArgs LC-specific URL arguments OPTIONAL
     *
     * @return array
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
     * @param array  $args LC URL arguments OPTIONAL
     *
     * @return array
     */
    public static function getPortalDrupalArgs($path, array $args = array())
    {
        foreach (array('profileId' => 'profile_id', 'orderId' => 'order_id') as $var => $param) {

            $$var = empty($args[$param]) ? \XLite\Core\Request::getInstance()->$param : $args[$param];
            unset($args[$param]);
        }

        list($path, $args) = parent::getPortalDrupalArgs($path, $args);

        $path = preg_replace('/\%/', static::getDrupalProfileId($profileId), $path, 1);
        $path = preg_replace('/\%/', $orderId, $path, 1);

        return array($path, $args);
    }


    /**
     * Get ID of LC profile
     *
     * @param integer $id Drupal profile ID
     *
     * @return integer
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
     */
    protected static function getDrupalProfileId($id)
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSIdByProfileId($id);
    }

    /**
     * Check if current page is the "Orders" portal
     *
     * @return boolean
     */
    protected static function isPortal()
    {
        return (bool) \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getPortalByPath('user/%/orders/%');
    }
}
