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

namespace XLite\Module\ProductAdviser\Core;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class CMSConnector extends \XLite\Core\CMSConnector implements \XLite\Base\IDecorator
{
    /**
     * Constructor
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        $this->widgetsList['\XLite\Module\ProductAdviser\View\NewArrivals']     = 'New arrivals';
        $this->widgetsList['\XLite\Module\ProductAdviser\View\RecentlyViewed']  = 'Recently viewed products';
        $this->widgetsList['\XLite\Module\ProductAdviser\View\RelatedProducts'] = 'Related products';
        $this->widgetsList['\XLite\Module\ProductAdviser\View\ProductAlsoBuy']  = 'People who buy this product also buy';

        $this->pageTypes['recently_viewed'] = 'Recently viewed products';
        $this->pageTypes['new_arrivals']    = 'New arrivals';
    }
}
