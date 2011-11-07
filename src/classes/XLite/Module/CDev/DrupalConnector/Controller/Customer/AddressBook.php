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
 * @since     1.0.1
 */

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer;

/**
 * Address book page controller
 *
 * @see   ____class_see____
 * @since 1.0.1
 */
class AddressBook extends \XLite\Controller\Customer\AddressBook implements \XLite\Base\IDecorator
{
    /**
     * Argument convertion: <DRUPAL> --> <LC>
     *
     * @param string $path     Portal path
     * @param array  $args     Druapl URL arguments OPTIONAL
     * @param array  $pageArgs LC-specific URL arguments OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.1
     */
    public static function getPortalLCArgs($path, array $args = array(), array $pageArgs = array())
    {
        $result = parent::getPortalLCArgs($path, $args, $pageArgs);

        if (static::isPortal() && !empty($args[1])) {
            $result['profile_id'] = static::getLCProfileId($args[1]);
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
     * @see    ____func_see____
     * @since  1.0.1
     */
    public static function getPortalDrupalArgs($path, array $args = array())
    {
        $id = empty($args['profile_id']) ? \XLite\Core\Request::getInstance()->profile_id : $args['profile_id'];
        unset($args['profile_id']);

        list($path, $args) = parent::getPortalDrupalArgs($path, $args);

        $path = preg_replace('/\%/', static::getDrupalProfileId($id), $path, 1);

        return array($path, $args);
    }
    
    
    /**
     * Get ID of LC profile
     *
     * @param integer $id Drupal profile ID
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.1
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
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected static function getDrupalProfileId($id)
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSIdByProfileId($id);
    }

    /**
     * Check if current page is the "Orders" portal
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected static function isPortal()
    {
        return (bool) \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getPortalByPath('user/%/address-book');
    }


    /**
     * Initialization
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function init()
    {
        parent::init();

        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS() && static::isPortal()) {
            drupal_set_title(t('Address book'));
        }
    }


    /**
     * Skip profileId param correction for Drupal storefront 
     * 
     * @param integer $profileId Profile ID
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function correctProfileIdForURLParams($profileId)
    {
        if (!\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $profileId = parent::correctProfileIdForURLParams($profileId);
        }

        return $profileId;
    }
}
