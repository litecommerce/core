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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector;

/**
 * CMS connector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Handler extends \XLite\Core\CMSConnector
{
    /**
     * areHooksEnabled 
     * 
     * @var    bool
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $areHooksEnabled = true;

    /**
     * Forbid URL rewrite routine
     * 
     * @var    boolean
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public static $forbidURLRewrite = false;

    /**
     * Portals (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $portals = null;

    /**
     * Portal parameters 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $portalParams = array();

    /**
     * Landing link 
     * 
     * @var    \XLite\Module\CDev\DrupalConnector\Model\LandingLink
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $landingLink;

    /**
     * Set hooks availability status
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function enableHooks()
    {
        self::$areHooksEnabled = true;
    }

    /**
     * Set hooks availability status
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function disableHooks()
    {
        self::$areHooksEnabled = false;
    }

    /**
     * areHooksEnabled 
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function areHooksEnabled()
    {
        return self::$areHooksEnabled;
    }

    /**
     * Return name of current CMS 
     * 
     * @return string
     * @access public
     * @since  1.0.0
     */
    public function getCMSName()
    {
        return '____DRUPAL____';
    }

    /**
     * Return the default controller name 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getDefaultTarget()
    {
        return 'drupal';
    }

    /**
     * Get landing link 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLandingLink()
    {
        if (!isset($this->landingLink)) {
            \XLite\Core\Database::getRepo('XLite\Module\CDev\DrupalConnector\Model\LandingLink')->removeExpired();

            $this->landingLink = new \XLite\Module\CDev\DrupalConnector\Model\LandingLink();
            $this->landingLink->setSessionId(\XLite\Core\Session::getInstance()->getID());

            \XLite\Core\Database::getEM()->persist($this->landingLink);
            \XLite\Core\Database::getEM()->flush();
        }

        return $this->landingLink->getLink();
    }

    /**
     * Get portals 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPortals()
    {
        if (is_null($this->portals)) {
            $this->definePortals();
        }

        return $this->portals;
    }

    /**
     * Define portals list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function definePortals()
    {
        $this->portals = array(
            /*
            'user/%/addresses' => array(
                'menu'   => array(
                    'title'            => 'Addresses',
                    'description'      => 'Addresses',
                    'access callback'  => 'lc_connector_user_access',
                    'access arguments' => array(1),
                    'weight'           => 50,
                ),
                'target' => 'address_book',
                'prefix' => array($this, 'getAddressesURLPrefix'),
                'argumentsPreprocessor' => array($this, 'getOrdersArgPreprocess'),
            ),
            */
            'user/%/orders' => array(
                'menu'   => array(
                    'title'            => 'Orders history',
                    'access callback'  => 'lc_connector_user_access',
                    'access arguments' => array(1),
                    'weight'           => 100,
                ),
                'target' => 'order_list',
                'prefix' => array($this, 'getOrdersURLPrefix'),
                'argumentsPreprocessor' => array($this, 'getOrdersArgPreprocess'),
            ),
            'user/%/orders/%' => array(
                'menu'   => array(
                    'access callback'  => 'lc_connector_order_access',
                    'access arguments' => array(1, 3),
                    'weight'           => 100,
                    'type'             => MENU_CALLBACK,
                ),
                'target' => 'order',
                'prefix' => array($this, 'getOrderURLPrefix'),
                'argumentsPreprocessor' => array($this, 'getOrderArgPreprocess'),
            ),
            'user/%/orders/%/invoice' => array(
                'menu'   => array(
                    'title'            => 'Invoice',
                    'description'      => 'Invoice',
                    'access callback'  => 'lc_connector_order_access',
                    'access arguments' => array(1, 3),
                    'weight'           => 100,
                    'type'             => MENU_CALLBACK,
                ),
                'target' => 'invoice',
                'prefix' => array($this, 'getInvoiceURLPrefix'),
                'argumentsPreprocessor' => array($this, 'getOrderArgPreprocess'),
            ),

        );

        if (\XLite\Core\Database::getRepo('\XLite\Model\Module')->isModuleActive('CDev\WishList')) {
            $this->portals['user/%/wishlist'] = array(
                'menu'   => array(
                    'title'            => 'Wish list',
                    'description'      => 'Wish list',
                    'access callback'  => 'lc_connector_user_access',
                    'access arguments' => array(1),
                    'weight'           => 110,
                ),
                'target' => 'wishlist',
                'prefix' => array($this, 'getWishlistURLPrefix'),
                'argumentsPreprocessor' => array($this, 'getOrdersArgPreprocess'),
            );
        }
    }

    /**
     * Check - specified target is portal or not
     * 
     * @param string $target Target code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPortal($target)
    {
        return false !== $this->getPortalKey($target);
    }

    /**
     * Get portal prefix 
     * 
     * @param string $target  Target code
     * @param string $action  Action code
     * @param array  &$params Parameters
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPortalPrefix($target, $action, array &$params)
    {
        $key = $this->getPortalKey($target);
        $portals = $this->getPortals();
        $portal = $portals[$key];
        unset($portals);

        $result = false;

        if (isset($portal['prefix'])) {
            $prefix = array();

            if (isset($params['printable']) && $params['printable']) {
                $prefix[] = 'print';
                unset($params['printable']);
            }

            $this->portalParams = $params;

            if (is_callable($portal['prefix'])) {
                $prefix = array_merge($prefix, call_user_func($portal['prefix']));

            } elseif (is_string($portal['prefix'])) {
                $prefix = array_merge($prefix, explode('/', $portal['prefix']));

            } elseif (is_array($portal['prefix'])) {
                $prefix = array_merge($prefix, $portal['prefix']);
            }

            $defaultTarget = false;
            if (is_array($portal['target'])) {
                $defaultTarget = array_shift($portal['target']);

            } elseif (is_string($portal['target'])) {
                $defaultTarget = $portal['target'];
            }

            if (!$defaultTarget || $target != $defaultTarget) {
                $prefix[] = 'target-' . $target;
            }

            if ($action) {
                $prefix[] = 'action-' . $action;
            }

            $result = implode('/', $prefix);
            $params = $this->portalParams;
        }

        if (false === $result) {
            $result = implode(
                '/',
                array(\XLite\Module\CDev\DrupalConnector\Core\Converter::DRUPAL_ROOT_NODE, $target, $action)
            );
        }

        return $result;
    }

    /**
     * Get portal key by target
     * 
     * @param string $target Target code
     *  
     * @return string|false
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPortalKey($target)
    {
        $result = false;

        foreach ($this->getPortals() as $k => $value) {
            if (
                (is_array($value['target']) && in_array($target, $value['target']))
                || $target == $value['target']
            ) {
                $result = $k;
                break;
            }
        }

        return $result;
    }

    /**
     * getAddressesURLPrefix 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddressesURLPrefix()
    {
        $uid = user_uid_optional_to_arg('%');
        
        return array(
            'user',
            $uid,
            'addresses',
        );
    }

    /**
     * Get URL prefix for Orders portal
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrdersURLPrefix()
    {
        $uid = user_uid_optional_to_arg('%');

        return array(
            'user',
            $uid,
            'orders',
        );
    }

    /**
     * Get URL prefix for Order portal
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrderURLPrefix()
    {
        $result = $this->getOrdersURLPrefix();

        if (isset($this->portalParams['order_id']) && $this->portalParams['order_id']) {
            $result[] = $this->portalParams['order_id'];
            unset($this->portalParams['order_id']);

        } else {
            $result[] = 0;
        }

        return $result;
    }

    /**
     * Get URL prefix for Invoice portal
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInvoiceURLPrefix()
    {
        $result = $this->getOrderURLPrefix();

        $result[] = 'invoice';

        return $result;
    }

    /**
     * Orders page arguments preprocessing
     * 
     * @param array $args Arguments
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrdersArgPreprocess(array $args)
    {
        $result = array();

        $profile = \LC_Connector_Handler_Profile::getInstance()->getProfile($args[1]);
        if ($profile) {
            $result['profile_id'] = $profile->getProfileId();
        }

        return $result;
    }

    /**
     * Orders page arguments preprocessing
     * 
     * @param array $args Arguments
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrderArgPreprocess(array $args)
    {
        $result = $this->getOrdersArgPreprocess($args);

        $result['order_id'] = $args[3];

        return $result;
    }

    /**
     * Get URL prefix for Wishlist portal
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWishlistURLPrefix()
    {
        $uid = user_uid_optional_to_arg('%');

        return array(
            'user',
            $uid,
            'wishlist',
        );
    }

    /**
     * Get canonical URL by clean URL 
     * 
     * @param string $path Clean url
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURLByCleanURL($path)
    {
        self::$forbidURLRewrite = true;

        $result = parent::getURLByCleanURL($path);

        self::$forbidURLRewrite = false;

        return $result;
    }
}

