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

/**
 * Send to friend page
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class XLite_Module_WishList_View_SendToFriend extends XLite_View_Dialog
{
    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Tell a friend';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/WishList/send_to_friend';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getProduct()->is('available');
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'send_friend';
    
        return $result;
    }
}

