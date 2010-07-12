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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\MultiCategories\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AddProduct extends \XLite\Controller\Admin\AddProduct implements \XLite\Base\IDecorator
{
    function action_add()
    {
        parent::action_add();
        if (isset($this->product_categories) && !empty($this->product_categories)) {
            $product = $this->get('product');
            $categories = $product->get('categories');
            for ($i = 0; $i < count($categories); $i++) {
                $product->deleteCategory($categories[$i]);
            }
            foreach ($this->product_categories as $catId) {
                $cat = new \XLite\Model\Category($catId);
                if (!$product->inCategory($cat)) {
                    $product->addCategory($cat);
                }
            }
        }
    }

    function isSelectedCategory($categoryID)
    {
        return in_array($categoryID, is_array($this->product_categories) ? $this->product_categories : array());
    }
}
