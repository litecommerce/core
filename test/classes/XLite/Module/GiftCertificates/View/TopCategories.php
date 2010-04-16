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
     * Get widget templates directory
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/GiftCertificates/categories/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

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
     * Assemble gift certificate item CSS class name 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleGiftItemClassName()
    {
        return 'gift_certificate' == XLite_Core_Request::getInstance()->target
            ? 'leaf last active-trail gift-link'
            : 'leaf last gift-link';
    }

    /**
     * Assemble list item gift certificate link class name
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleGiftLinkClassName()
    {
        return 'gift_certificate' == XLite_Core_Request::getInstance()->target
            ? 'active'
            : '';
    }

    /**
     * Check - is root level or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isRoot()
    {
        return !$this->getParam(self::PARAM_IS_SUBTREE);
    }
}
