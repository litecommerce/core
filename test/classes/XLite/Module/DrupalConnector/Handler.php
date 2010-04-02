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

/**
 * CMS connector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_DrupalConnector_Handler extends XLite_Core_CMSConnector
{
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
     * Return array of <lc_key, cms_key> pairs for user profiles
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getUserDataTranslationTable()
    {
        return parent::getUserDataTranslationTable() + array(
            'cms_profile_id' => array(
                self::USER_DATA_FIELD => 'uid',
            ),
            'login' => array(
                self::USER_DATA_FIELD => 'mail',
            ),
            'password' => array(
                self::USER_DATA_FIELD    => 'pass',
                self::USER_DATA_CALLBACK => array('XLite_Model_Auth' , 'encryptPassword'),
            ),
        );
    }


    /**
     * Method to access the singleton
     *
     * @return XLite_Module_DrupalConnector_Handler
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
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
        $link = new XLite_Module_DrupalConnector_Model_LandingLink();
        $link->create();

        return $link->getLink();
    }

    /**
     * Get previous messages
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTopMessages()
    {
        return XLite_Core_TopMessage::getInstance()->getPreviousMessages();
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
            'user/%/orders_history' => array(
                'menu'   => array(
                    'title'             => 'Orders history',
                    'description'       => 'Orders history',
                    'access arguments'  => array('access user profiles'),
                    'weight'            => 100,
                ),
                'target' => array('order_list', 'order'),
                'prefix' => array($this, 'getOrderURLPrefix'),
            ),
        );
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
     * @param string $target Target code
     * @param string $action Action code
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPortalPrefix($target, $action)
    {
        $key = $this->getPortalKey($target);
        $portals = $this->getPortals();
        $portal = $portals[$key];
        unset($portals);

        $result = false;

        if (isset($portal['prefix'])) {
            $prefix = array();
            if (is_callable($portal['prefix'])) {
                $prefix = call_user_func($portal['prefix']);

            } elseif (is_string($portal['prefix'])) {
                $prefix = explode('/', $portal['prefix']);

            } elseif (is_array($portal['prefix'])) {
                $prefix = $portal['prefix'];
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
        }

        if (false === $result) {
            $result = implode(
                '/',
                array(XLite_Module_DrupalConnector_Core_Converter::DRUPAL_ROOT_NODE, $target, $action)
            );
        }

        return $result;
    }

    /**
     * Get portal key by target
     * 
     * @param string $target Target code
     *  
     * @return string or false
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
     * Get URL prefix for Orders / Order portal
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrderURLPrefix()
    {
        $uid = user_uid_optional_to_arg('%');

        return array(
            'user',
            $uid,
            'orders_history',
        );
    }
}

