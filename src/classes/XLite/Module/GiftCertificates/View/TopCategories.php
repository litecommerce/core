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

namespace XLite\Module\GiftCertificates\View;

/**
 * Sidebar categories list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class TopCategories extends \XLite\View\TopCategories implements \XLite\Base\IDecorator
{
    /**
     * Assemble item CSS class name 
     * 
     * @param integer              $index    item number
     * @param intr                 $count    items count
     * @param \XLite\Model\Category $category current category
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleItemClassName($index, $count, \XLite\Model\Category $category)
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
     * @param \XLite\Model\Category $category current category
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleListItemClassName($i, $count, \XLite\View\AView $widget)
    {
        $class = explode(' ', parent::assembleListItemClassName($i, $count, $widget));

        if ($widget instanceof \XLite\Module\GiftCertificates\View\TopCategoriesItem)  {
            if ('gift_certificate' == \XLite\Core\Request::getInstance()->target) {
                $class[] = 'active-trail';
            }
            $class[] = 'gift-link';
        }

        return implode(' ', $class);
    }
}
