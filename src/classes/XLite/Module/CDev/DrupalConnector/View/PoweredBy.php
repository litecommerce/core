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

namespace XLite\Module\CDev\DrupalConnector\View;

/**
 * 'Powered by' widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PoweredBy extends \XLite\View\PoweredBy implements \XLite\Base\IDecorator
{
    /**
     * Check - display widget as link or as box
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isLink()
    {
        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $result = drupal_is_front_page();

        } else {
            $result = parent::isLink();
        }

        return $result;
    }
}

