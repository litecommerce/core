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

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Controller
 *
 */
class Controller extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Instance of the LC viewer
     *
     * @var \XLite\View\Controller
     */
    protected $viewer;

    /**
     * Flag to determine if some common actions are already performed
     *
     * @var boolean
     */
    protected $arePreinitialized = false;

    /**
     * Resources weight counter
     *
     * @var integer
     */
    protected $resourcesCounter = 0;

    /**
     * Get RDF namespaces 
     * 
     * @return array
     */
    public function getRDFNamespaces()
    {
        return \XLite\View\AView::getRegisteredNamespaces();
    }

    /**
     * Get head prefixes 
     * 
     * @return array
     */
    public function getHeadPrefixes()
    {
        return \XLite\View\Header::defineHeadPrefixes();
    }

    // {{{ Menu callbacks

    /**
     * Return page title
     *
     * @return string
     */
    public function getTitle()
    {
        // Perform some common actions
        $this->performCommonActions();

        return static::t($this->getViewer()->getTitle());
    }

    /**
     * Update variables before they pass to the template
     *
     * @param array &$variables Array of variables
     *
     * @return void
     */
    public function updateTemplateVars(array &$variables)
    {
        // Get page title for current target
        $title = $this->getPageTitle();

        // Assign title variable if it's defined
        if (!isset($title)) {
            $variables['title'] = '';
        }

        $this->registerLCResources();
    }

    /**
     * Update meta tags array before this pass to the template
     *
     * @param array &$elements Array of meta tags
     *
     * @return void
     */
    public function updateMetaTags(array &$elements)
    {
        if (!defined('MAINTENANCE_MODE')) {

            foreach (array('description' => 'getMetaDescription', 'keywords' => 'getKeywords') as $name => $method) {
                // $method stored into static array
                $content = $this->getViewer()->$method();

                if ($content) {
                    $elements['lc_connector_meta_' . $name] = array(
                        '#type' => 'html_tag',
                        '#tag'  => 'meta',
                        '#attributes' => array(
                            'name'    => $name,
                            'content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
                        ),
                    );
                }
            }
        }
    }

    /**
     * Return content for central region
     *
     * @return string
     */
    public function getContent()
    {
        // Perform some common actions
        $this->performCommonActions();

        $title   = $this->getTitle();
        $trail   = array();
        $content = $this->getViewer()->getContent();

        if ($this->getViewer()->isTitleVisible() && !empty($title)) {
            $trail[] = array(
                'title'             => $title,
                'link_path'         => '',
                'localized_options' => array('html' => true),
                'type'              => 0,
            );
        }

        menu_set_active_trail($trail);

        // Set value for <title> tag
        drupal_set_title(static::t($this->getViewer()->getPageTitle()));

        return $this->isAJAX() ? $this->displayAJAXContent($content) : $content;
    }

    /**
     * The "access callback"
     *
     * @return boolean
     */
    public function checkAccess()
    {
        return true;
    }

    // }}}

    // {{{ Ancillary methods

    /**
     * Return LC viewer for current controller
     *
     * @return \XLite\View\Controller
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
     */
    protected function isAJAX()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'];
    }

    /**
     * Display content for the AJAX requests
     *
     * @param string $content Content to display
     *
     * @return void
     */
    protected function displayAJAXContent($content)
    {
        // Dispatch events
        \XLite\Core\Event::getInstance()->display();
        \XLite\Core\Event::getInstance()->clear();

        // Send headers. TODO: Should be one header sending point.
        \XLite\View\Controller::sendHeaders();

        // Display content
        echo ('<h2 class="ajax-title-loadable">' . $this->getTitle() . '</h2>');
        echo ('<div class="ajax-container-loadable">' . $content . '</div>');

        exit (0);
    }

    /**
     * Set no-cache headers
     *
     * @return void
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
     * @return void
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
    }

    /**
     * Get page title
     *
     * @return string
     */
    protected function getPageTitle()
    {
        return $this->getViewer()->isTitleVisible() ? $this->getTitle() : null;
    }

    /**
     * Common actions for "getTitle()" and "getContent()"
     *
     * @return void
     */
    protected function performCommonActions()
    {
        if (!$this->arePreinitialized) {

            // Set no-cache headers
            if (!headers_sent()) {
                $this->setNocacheHeaders();
            }

            // Set LC breadcrumbs
            $this->setBreadcrumbs();

            $this->arePreinitialized = true;
        }
    }

    // }}}

    // {{{ Resources (CSS and JS)

    /**
     * Register LC widget resources
     *
     * @return void
     */
    protected function registerLCResources()
    {
        foreach (\XLite\View\AView::getRegisteredResources() as $type => $files) {
            $method = 'drupal_add_' . $type;

            if (function_exists($method)) {
                foreach ($files as $name => $data) {
                    // $method assembled from 'drupal_add_' + $type
                    $method($data['file'], $this->getResourceInfo($type, $data));
                }
            }
        }

        $metas = trim(implode(PHP_EOL, \XLite\View\AView::getRegisteredMetas()));
        if ($metas) {
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $string = '<' . '?xml version="1.0" encoding="UTF-8"?' . '><body>' . $metas . '</body>';

            if (@$dom->loadHTML($string)) {
                $i = 0;
                foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
                    if ($node instanceOf \DOMNode) {
                        $tag = array('#type' => 'html_tag', '#tag' => $node->nodeName, '#attributes' => array());
                        foreach ($node->attributes as $attribute) {
                            $tag['#attributes'][$attribute->name] = $attribute->value;
                        }
                        drupal_add_html_head($tag, 'lc3' . $i);
                        $i++;
                    }
                }
            }
        }
    }

    /**
     * Get resource description in Drupal format
     *
     * @param string $type Resource type ("js" or "css")
     * @param array  $file Resource file info
     *
     * @return void
     */
    protected function getResourceInfo($type, array $file)
    {
        return $this->getResourceInfoCommon($file) + $this->{__FUNCTION__ . strtoupper($type)}($file);
    }

    /**
     * Get resource description in Drupal format
     *
     * @param array $file Resource file info
     *
     * @return void
     */
    protected function getResourceInfoCommon(array $file)
    {
        $this->resourcesCounter++;

        return array(
            'type'     => 'file',
            'basename' => $this->getResourceBasename($file['file']),
            'weight'   => $this->resourcesCounter,
        );
    }

    /**
     * Get resource description in Drupal format
     *
     * @param array $file Resource file info
     *
     * @return void
     */
    protected function getResourceInfoJS(array $file)
    {
        $scope = $this->getJSScope($file['file']);

        return array(
            'scope' => $scope,
            'defer' => ('footer' == $scope),
        );
    }

    /**
     * Get resource description in Drupal format
     *
     * @param array $file Resource file info
     *
     * @return void
     */
    protected function getResourceInfoCSS(array $file)
    {
        return array(
            'group' => CSS_DEFAULT,
            'media' => $file['media'],
        );
    }

    /**
     * Get JS scope
     *
     * @param string $file Resource file path
     *
     * @return string
     */
    protected function getJSScope($file)
    {
        return preg_match('/.skins.common.js./Ss', $file) ? 'header' : 'footer';
    }

    /**
     * Get file unique basename
     *
     * @param string $file Resource file path
     *
     * @return string
     */
    protected function getResourceBasename($file)
    {
        return preg_replace('/\.(css|js)$/Ss', '.' . $this->getUniqueID($file) . '.$1', basename($file));
    }

    /**
     * Return unique identifier
     *
     * @param string $file Resource file path
     *
     * @return string
     */
    protected function getUniqueID($file)
    {
        return hash('md4', $file);
    }

    // }}}
}
