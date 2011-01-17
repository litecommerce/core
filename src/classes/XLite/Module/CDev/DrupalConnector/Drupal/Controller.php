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
 * Controller 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Controller extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Instance of the LC viewer 
     * 
     * @var    \XLite\View\Controller
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $viewer;

    /**
     * Flag to determine if some common actions are already performed
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $arePreinitialized = false;


    // ------------------------------ Ancillary methods - 

    /**
     * Return LC viewer for current controller
     * 
     * @return \XLite\View\Controller
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getViewer()
    {
        if (!isset($this->viewer)) {
            $this->viewer = $this->getHandler()->getViewer();
        }

        return $this->viewer;
    }

    /**
     * Check if current request is an AJAX one
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isAJAX()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'];
    }

    /**
     * Display content for the AJAX requests
     *
     * @param string $content Content to display
     * 
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function displayAJAXContent($content)
    {
        // Dispatch events
        \XLite\Core\Event::getInstance()->display();
        \XLite\Core\Event::getInstance()->clear();

        // Display content
        echo '<h2 class="ajax-title-loadable">' . $this->getTitle() . '</h2>';
        echo '<div class="ajax-container-loadable">' . $content . '</div>';

        exit(0);
    }

    /**
     * Set no-cache headers 
     * 
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setNocacheHeaders()
    {
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        if (\XLite\Core\Request::getInstance()->isHTTPS()) {

            header('Cache-Control: private, must-revalidate');

        } else {

            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Pragma: no-cache');
        }
    }

    /**
     * Set LC breadcrumbs 
     * 
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setBreadcrumbs()
    {
        $widget = $this->getHandler()->getWidget('\XLite\\View\\Location');

        $lcNodes = array_map(
            function (\XLite\View\Location\Node $node) {
                return $node->getContent();
            },
            $widget->getNodes()
        );
        array_shift($lcNodes);

        // Add store root node
        $trails = menu_get_active_trail();
        array_splice(
            $trails,
            1,
            0,
            array(
                array(
                    'title'             => t('Store'),
                    'href'              => \XLite\Core\Converter::buildFullURL(),
                    'link_path'         => '',
                    'localized_options' => array(),
                    'type'              => MENU_VISIBLE_IN_BREADCRUMB,
                ),
            )
        );
        menu_set_active_trail($trails);

        $drupalNodes = array_slice(drupal_get_breadcrumb(), 0, 2);
        drupal_set_breadcrumb(array_merge($drupalNodes, $lcNodes));

        if ($widget->getProtectedWidget()) {
            $this->registerResources($widget->getProtectedWidget());
        }
    }

    /**
     * Common actions for "getTitle()" and "getContent()"
     * 
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performCommonActions()
    {
        if (!$this->arePreinitialized) {

            // Set no-cache headers
            headers_sent() ?: $this->setNocacheHeaders();

            // Set LC breadcrumbs
            $this->setBreadcrumbs();

            $this->arePreinitialized = true;
        }
    }


    // ------------------------------ Menu callbacks -

    /**
     * Return page title
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        // Perform some common actions
        $this->performCommonActions();

        return $this->getHandler()->getViewer()->getPageTitle();
    }

    /**
     * Return content for central region
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getContent()
    {
        // Perform some common actions
        $this->performCommonActions();

        // Current viewer
        $viewer = $this->getHandler()->getViewer();

        $content = $viewer->getContent();
        $this->registerResources($viewer);

        $trail = array();

        if ($viewer->isTitleVisible() && ($title = $this->getTitle())) {
            $trail[] = array(
                'title'             => $this->getTitle(),
                'link_path'         => '',
                'localized_options' => array('html' => true),
                'type'              => 0,
            );
        }

        menu_set_active_trail($trail);

        return $this->isAJAX() ? $this->displayAJAXContent($content) : $content;
    }

    /**
     * The "access callback"
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkAccess()
    {
        return true;
    }
}
