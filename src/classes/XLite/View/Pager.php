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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Common pager 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Pager extends \XLite\View\Pager\APager
{
    /**
     * Widget parameter names
     */

    const PARAM_DATA = 'data';


    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DATA => new \XLite\Model\WidgetParam\Collection('Data', array()),
        );
    }

    /**
     * Return number of items per page
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageDefault()
    {
        return 20;
    }

    /**
     * Return number of pages to display
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPagesPerFrame()
    {
        return 5;
    }

    /**
     * Return list of items to display on the current page
     *
     * @param int $start index of the first item on the page
     * @param int $count number of items per page
     *
     * @return array|\Doctrine\ORM\PersistentCollection
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDataFrame($start, $count)
    {
        return $this->getParam(self::PARAM_DATA);
    }
}
