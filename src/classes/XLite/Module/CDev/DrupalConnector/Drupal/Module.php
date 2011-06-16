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
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Module
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Module extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * So called "landing" link path.
     * NOTE: do not wrap this constant into a function: * it can cause the perfomance loss
     */
    const LANDING_LINK_PATH = 'admin/lc_admin_area';

    /**
     * Drupal page URL user come from
     */
    const PARAM_DRUPAL_RETURN_URL = 'drupalReturnURL';


    /**
     * List of registered portals
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $portals = array();


    // {{{ Auxiliary methods

    /**
     * For custom modules; ability to add Drupal menu nodes
     *
     * @param array &$menus List of node descriptions
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addMenus(array &$menus)
    {
    }

    /**
     * Return URL to redirect to
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAdminAreaURLArgs()
    {
        $query = '';

        if (\XLite\Core\Auth::getInstance()->isAdmin()) {
            $query .= '?' . \XLite\Core\Session::getInstance()->getName();
            $query .= '=' . \XLite\Core\Session::getInstance()->getId();
            $query .= '&' . self::PARAM_DRUPAL_RETURN_URL;
            $query .= '=' . urlencode(\Includes\Utils\URLManager::getCurrentURL());
        }

        return $query;
    }

    // }}}

    // {{{ Portals

    /**
     * Getter
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return \XLite\Module\CDev\DrupalConnector\Model\Portal
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPortal($path)
    {
        return isset($this->portals[$path]) ? $this->portals[$path] : null;
    }


    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        $this->registerPortals();
    }

    /**
     * Register a portal
     *
     * @param string  $url        Drupal URL
     * @param string  $controller Controller class name
     * @param string  $title      Portal title OPTIONAL
     * @param integer $type       Node type OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function registerPortal($url, $controller, $title = '', $type = MENU_LOCAL_TASK)
    {
        $this->portals[$url] = new \XLite\Module\CDev\DrupalConnector\Model\Portal($url, $controller, $title, $type);
    }

    /**
     * Here we can register so called "portals": controllers with custom URLs
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function registerPortals()
    {
        $this->registerPortal('user/%/orders', '\XLite\Controller\Customer\OrderList', 'Orders');
        $this->registerPortal('user/%/orders/%', '\XLite\Controller\Customer\Order');
        $this->registerPortal('user/%/orders/%/invoice', '\XLite\Controller\Customer\Invoice');

        $this->registerPortal('user/%/address-book', '\XLite\Controller\Customer\AddressBook', 'Address book');

        // So called "landing link"
        $this->registerPortal(
            self::LANDING_LINK_PATH, '\XLite\Controller\Admin\Main', 'LC admin area', MENU_NORMAL_ITEM
        );
    }

    /**
     * Prepare portals for Drupal hook "menu"
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareMenus(array $menus)
    {
        return $this->getPortalMenus() + $menus;
    }

    // }}}

    // {{{ Drupal hook handlers

    /**
     * Hook "init"
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function invokeHookInit()
    {
        include_once LC_DIR_MODULES . 'CDev/DrupalConnector/Drupal/Include/Callbacks.php';
    }

    /**
     * Hook "menu"
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function invokeHookMenu()
    {
        $menus = array(

            \XLite\Module\CDev\DrupalConnector\Core\Converter::DRUPAL_ROOT_NODE => array(
                'title'            => 'Store',
                'title callback'   => 'lcConnectorGetControllerTitle',
                'page callback'    => 'lcConnectorGetControllerContent',
                'access callback'  => 'lc_connector_check_controller_access',
                'type'             => MENU_CALLBACK,
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

    /**
     * Optimize javscript files list
     *
     * @param array $list Files list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function optimizeJSFiles(array $list)
    {
        if (\XLite\Core\Config::getInstance()->CDev->DrupalConnector->minify_resources) {
            uasort($list, 'drupal_sort_css_js');

            $i = 0;

            foreach ($list as $name => $script) {
                $list[$name]['weight'] = $i;
                $list[$name]['group'] = JS_DEFAULT;
                $list[$name]['defer'] = true;
                $list[$name]['every_page'] = false;

                $i++;
            }
        }

        return $list;
    }

    /**
     * Optimize CSS files list
     *
     * @param array $list Files list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function optimizeCSSFiles($list)
    {
        if (\XLite\Core\Config::getInstance()->CDev->DrupalConnector->minify_resources) {
            uasort($list, 'drupal_sort_css_js');

            $i = 0;

            foreach ($list as $name => $style) {
                $list[$name]['weight'] = $i;
                $list[$name]['group'] = CSS_DEFAULT;
                $list[$name]['every_page'] = false;

                $i++;
            }
        }

        return $list;
    }

    /**
     * Alters outbound URLs
     *
     * @param string &$path        The outbound path to alter
     * @param array  &$options     A set of URL options
     * @param string $originalPath The original path, before being altered by any modules
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function translateOutboundURL(&$path, array &$options, $originalPath)
    {
        if (self::LANDING_LINK_PATH === $path) {
            $path = \Includes\Utils\URLManager::getShopURL('admin.php' . $this->getAdminAreaURLArgs());
            $options['external'] = true;

        } else {
            $url = $this->getHandler()->getDrupalCleanURL($path, $options);
            if ($url) {
                $path = $url;
            }
        }
    }

    /**
     * Alters inbound URLs
     *
     * @param string &$path        The inbound path to alter
     * @param string $originalPath The original path, before being altered by any modules
     * @param string $pathLanguage Path language
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function translateInboundURL(&$path, $originalPath, $pathLanguage)
    {
        if ($path) {
            $url = $this->getHandler()->getURLByCleanURL($path);
            if ($url) {
                $path = $url;
            }
        }
    }

    /**
     * Initialize drupal_root_url option
     *
     * @param string $url Drupal base URL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setDrupalRootURL($url)
    {
        \XLite\Core\Database::getRepo('XLite\Model\Config')
            ->createOption(
                array(
                    'name'     => 'drupal_root_url',
                    'category' => 'CDev\\DrupalConnector',
                    'value'    => $url,
                )
            );
    }

    /**
     * Run cron tasks
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function runCronTasks()
    {
        $cron = new \XLite\Controller\Console\Cron;
        $cron->runTasksDirect();
    }

    // }}}
}
