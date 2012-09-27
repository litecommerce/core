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

namespace XLite\View\PagerOrig;

/**
 * Simple pager
 *
 */
class Simple extends \XLite\View\AView
{

    /**
     * Widget parameters
     */
    const PARAM_PAGES = 'pages';
    const PARAM_PAGE  = 'page';
    const PARAM_URL   = 'url';


    /**
     * Check - link to previous page exists or not
     *
     * @return boolean
     */
    public function isPrevPage()
    {
        return 1 < $this->getParam(self::PARAM_PAGE);
    }

    /**
     * Get URL to previous page
     *
     * @return string
     */
    public function getPrevURL()
    {
        return $this->getParam(self::PARAM_URL) . '&page=' . ($this->getParam(self::PARAM_PAGE) - 1);
    }

    /**
     * Check - link to next page is exists or not
     *
     * @return boolean
     */
    public function isNextPage()
    {
        return $this->getParam(self::PARAM_PAGES) > $this->getParam(self::PARAM_PAGE);
    }

    /**
     * Get URL to next page
     *
     * @return string
     */
    public function getNextURL()
    {
        return $this->getParam(self::PARAM_URL) . '&page=' . ($this->getParam(self::PARAM_PAGE) + 1);
    }

    /**
     * Get URL to last page
     *
     * @return string
     */
    public function getLastURL()
    {
        return $this->getParam(self::PARAM_URL) . '&page=' . $this->getParam(self::PARAM_PAGES);
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'common/simple_pager.css';

        return $list;
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'common/simple_pager.tpl';
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
            self::PARAM_PAGES => new \XLite\Model\WidgetParam\Int('Pages count', 0),
            self::PARAM_PAGE  => new \XLite\Model\WidgetParam\Int('Current page', 1),
            self::PARAM_URL   => new \XLite\Model\WidgetParam\String('Link URL', null),
        );
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && 1 < $this->getParam(self::PARAM_PAGES)
            && $this->getParam(self::PARAM_URL);
    }
}
