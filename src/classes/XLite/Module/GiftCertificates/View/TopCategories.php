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
 * Sidebar categories list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_View_TopCategories extends XLite_View_TopCategories implements XLite_Base_IDecorator
{
    /**
     * Assemble item CSS class name 
     * 
     * @param int                  $index    item number
     * @param intr                 $count    items count
     * @param XLite_Model_Category $category current category
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleItemClassName($index, $count, XLite_Model_Category $category)
    {
        $classes = explode(' ', parent::assembleItemClassName($index, $count, $category));
        $classes = preg_grep('/^last$/Ss', $classes, PREG_GREP_INVERT);
    
        return implode(' ', $classes);
    }

    /**
     * Assemble list item link class name
     *
     * @param integer              $i        item number
     * @param integer              $count    items count
     * @param XLite_Model_Category $category current category
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleListItemClassName($i, $count, XLite_View_Abstract $widget)
    {
        $class = explode(' ', parent::assembleListItemClassName($i, $count, $widget));

        if ($widget instanceof XLite_Module_GiftCertificates_View_TopCategoriesItem)  {
            if ('gift_certificate' == XLite_Core_Request::getInstance()->target) {
                $class[] = 'active-trail';
            }
            $class[] = 'gift-link';
        }

        return implode(' ', $class);
    }
}
