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

namespace XLite\View\Pager\Admin\Model;

/**
 * Infinity pager
 * 
 */
class Infinity extends \XLite\View\Pager\Admin\Model\AModel
{
    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams[self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_ONLY_PAGES]->setValue(true);
    }

    /**
     * Get items per page (default)
     *
     * @return integer
     */
    protected function getItemsPerPageDefault()
    {
        return $this->getItemsPerPageMax();
    }

    /**
     * Return maximal possible items number per page
     *
     * @return integer
     */
    protected function getItemsPerPageMax()
    {
        return 1000000000;
    }

    /**
     * getItemsPerPage
     *
     * @return integer
     */
    protected function getItemsPerPage()
    {
        return $this->getItemsPerPageMax();
    }

    /**
     * Return ID of the current page
     *
     * @return integer
     */
    protected function getPageId()
    {
        return 0;
    }

    /**
     * Get direcory
     *
     * @return string
     */
    protected function getDir()
    {
        return 'pager/model';
    }

}
