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

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Module 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Module extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * List of registered portals 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $portals = array();


    /**
     * Constructor
     * 
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        $this->registerPortals();
    }

    /**
     * For custom modules; ability to add Drupal menu nodes
     * 
     * @param array &$menus List of node descriptions
     *  
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addMenus(array &$menus)
    {
    }

    /**
     * Register a portal
     *
     * @param string  $url        Drupal URL
     * @param string  $controller Controller class name
     * @param string  $title      Portal title
     * @param integer $type       Node type
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerPortal($url, $controller, $title = '', $type = MENU_LOCAL_TASK)
    {
        $this->portals[$url] = new \XLite\Module\CDev\DrupalConnector\Model\Portal($url, $controller, $title, $type);
    }

    /**
     * Here we can register so called "portals": controllers with custom URLs
     * 
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerPortals()
    {
        $this->registerPortal('user/%/orders', '\XLite\Controller\Customer\OrderList', 'Order history');
        $this->registerPortal('user/%/orders/%', '\XLite\Controller\Customer\Order');
        $this->registerPortal('user/%/orders/%/invoice', '\XLite\Controller\Customer\Invoice');

        // So called "landing link"
        $this->registerPortal(
            'admin/lc_admin_area', '\XLite\Controller\Customer\AdminAreaRedirector', 'LC admin area', MENU_NORMAL_ITEM
        );
    }

    /**
     * Prepare portals for Drupal hook "menu"
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPortalMenus()
    {
        $menus = array();

        foreach ($this->portals as $portal) {
            $menus[$portal->getURL()] = $portal->getDrupalMenuDescription();
        }

        return $menus;
    }

    /**
     * Prepare list of Drupal menu descriptions (e.g. add portals)
     *
     * @param array $menus List to prepare
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareMenus(array $menus)
    {
        return $this->getPortalMenus() + $menus;
    }

    /**
     * Getter
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPortals()
    {
        return $this->portals;
    }

    /**
     * Check if there is a portal corresponding to the passed path
     * 
     * @param string $path Druapl path to check
     *  
     * @return \XLite\Module\CDev\DrupalConnector\Model\Portal|null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPortal($path)
    {
        return isset($this->portals[$path]) ? $this->portals[$path] : null;
    }


    // ------------------------------ Drupal hook handlers -

    /**
     * Hook "init" 
     * 
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function invokeHookInit()
    {
        require_once LC_MODULES_DIR . 'CDev/DrupalConnector/Drupal/Include/Callbacks.php';
    }

    /**
     * Hook "menu"
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function invokeHookMenu()
    {
        $menus = array(

            'admin/modules/lc_connector' => array(
                'title'            => 'LC Connector',
                'description'      => 'Settings for the LC connector module.',
                'page callback'    => 'drupal_get_form',
                'page arguments'   => array('lcConnectorGetSettingsForm'),
                'access arguments' => array('administer users'),
            ),

            \XLite\Module\CDev\DrupalConnector\Core\Converter::DRUPAL_ROOT_NODE . '/%' => array(
                'title'            => 'Store',
                'title callback'   => 'lcConnectorGetControllerTitle',
                'page callback'    => 'lcConnectorGetControllerContent',
                'access callback'  => 'lc_connector_check_controller_access',
                'type'             => MENU_CALLBACK,
            ),
        );

        // For developers' purposes
        $this->addMenus($menus);

        return $this->prepareMenus($menus);
    }
}
