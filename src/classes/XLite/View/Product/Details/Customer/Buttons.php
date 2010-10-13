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

namespace XLite\View\Product\Details\Customer;

/**
 * Buttons
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @ListChild (list="product.details.page.info", weight="100")
 * @ListChild (list="product.details.quicklook.info", weight="100")
 */
class Buttons extends \XLite\View\Product\Details\Customer\ACustomer
{
    /**
     * Return the default widget template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/parts/:list.buttons.tpl';
    }


    /**
     * Return the widget template
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        $tpl = parent::getTemplate();

        // We had to do the replacement there because viewListName is NULL when in getDefaultTemplate()
        $list = str_replace('product.details.', '', $this->viewListName);

        return str_replace(':list', $list, $tpl);
    }

}
