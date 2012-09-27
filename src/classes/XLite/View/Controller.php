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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View;

/**
 * Controller main widget
 *
 */
class Controller extends \XLite\View\AView
{
    /**
     * Content of the currnt page
     * NOTE: this is a text, so it's not passed by reference; do not wrap it into a getter (or pass by reference)
     * NOTE: until it's not accessing via the function, do not change its access modifier
     *
     * @var string
     */
    public static $bodyContent = null;


    /**
     * Send headers
     *
     * @return void
     */
    public static function sendHeaders()
    {
        // send no-cache headers
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: text/html; charset=utf-8');
    }

    /**
     * __construct
     *
     * @param array  $params          Widget params OPTIONAL
     * @param string $contentTemplate Central area template OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array(), $contentTemplate = null)
    {
        parent::__construct($params);

        $this->template = $contentTemplate;
    }

    /**
     * Show current page and, optionally, footer
     *
     * @param string $template Template file name OPTIONAL
     *
     * @return void
     */
    public function display($template = null)
    {
        if (!$this->isSilent()) {
            $this->displayPage($template);
        }

        if ($this->isDumpStarted()) {
            $this->refreshEnd();
        }

        $this->postprocess();
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return $this->isAJAXCenterRequest() ? 'center_top.tpl' : 'body.tpl';
    }

    /**
     * Check - current request is AJAX background request for page center or not
     *
     * @return boolean
     */
    protected function isAJAXCenterRequest()
    {
        return $this->isAJAX() && \XLite\Core\Request::getInstance()->only_center;
    }

    /**
     * Get body classes
     *
     * @return string
     */
    protected function getBodyClasses()
    {
        return implode(' ', $this->defineBodyClasses());
    }

    /**
     * Define body classes list
     *
     * @return array
     */
    protected function defineBodyClasses()
    {
        $classes = array(
            'area-' . (\XLite::isAdminZone() ? 'a' : 'c'),
        );

        foreach (array_reverse(\XLite\Core\Layout::getInstance()->getSkins()) as $skin) {
            $classes[] = 'skin-' . $skin;
        }

        $classes[] = 'target-' . (\XLite\Core\Request::getInstance()->target ?: \XLite::TARGET_DEFAULT);

        $first = $this->isSidebarFirstVisible();
        $second = $this->isSidebarSecondVisible();

        if ($first && $second) {
            $classes[] = 'two-sidebars';

        } elseif ($first || $second) {
            $classes[] = 'one-sidebar';

        } else {
            $classes[] = 'no-sidebars';
        }

        if ($first) {
            $classes[] = 'sidebar-first';
        }

        if ($second) {
            $classes[] = 'sidebar-second';
        }

        return $classes;
    }

    /**
     * Chewck - first sidebar is visible or not
     *
     * @return boolean
     */
    protected function isSidebarFirstVisible()
    {
        return !in_array(\XLite\Core\Request::getInstance()->target, array('cart', 'product', 'checkout'));
    }

    /**
     * Check - second sidebar is visible or not
     *
     * @return boolean
     */
    protected function isSidebarSecondVisible()
    {
        return false;
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_SILENT       => new \XLite\Model\WidgetParam\Bool('Silent', false),
            self::PARAM_DUMP_STARTED => new \XLite\Model\WidgetParam\Bool('Dump started', false)
        );
    }

    /**
     * isSilent
     *
     * @return boolean
     */
    protected function isSilent()
    {
        return $this->getParam(self::PARAM_SILENT);
    }

    /**
     * isDumpStarted
     *
     * @return boolean
     */
    protected function isDumpStarted()
    {
        return $this->getParam(self::PARAM_DUMP_STARTED);
    }

    /**
     * getContentWidget
     *
     * @return \XLite\View\AView
     */
    protected function getContentWidget()
    {
        return $this->getWidget(array(\XLite\View\AView::PARAM_TEMPLATE => $this->template), '\XLite\View\Content');
    }

    /**
     * prepareContent
     *
     * @return void
     */
    protected function prepareContent()
    {
        self::$bodyContent = $this->getContentWidget()->getContent();
    }

    /**
     * Return TRUE  if widget must be displayed inside CMS content.
     * Return FALSE if standalone display mode of LC is used.
     *
     * @return boolean
     */
    protected function useDefaultDisplayMode()
    {
        return $this->isExported();
    }

    /**
     * displayPage
     *
     * @param string $template Template file name OPTIONAL
     *
     * @return void
     */
    protected function displayPage($template = null)
    {
        // Send page headers first
        static::sendHeaders();

        if ($this->useDefaultDisplayMode()) {
            // Display widget content inside some CMS content
            $this->getContentWidget()->display($template);

        } else {
            // Display widget in standalone display mode
            $this->prepareContent();

            parent::display($template);
        }
    }

    /**
     * refreshEnd
     *
     * @return void
     */
    protected function refreshEnd()
    {
        func_refresh_end();
    }

    /**
     * Get body class
     *
     * @return string
     */
    protected function getBodyClass()
    {
        $classes = array(
            str_replace('_', '-', \XLite\Core\Request::getInstance()->target),
        );

        return implode(' ', $classes);
    }
}
