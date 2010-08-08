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

namespace XLite\Controller\Admin;


/**
 * Products list controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ProductList extends AAdmin
{
    /**
     * doActionUpdate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->updateInBatch($this->getPostedData());
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->deleteInBatch($this->getToDelete());
    }

    /**
     * doActionClone 
     * FIXME - to revise
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    /*protected function doActionClone()
    {
        if (isset(\XLite\Core\Request::getInstance()->product_ids) && is_array(\XLite\Core\Request::getInstance()->product_ids)) {

            foreach (\XLite\Core\Request::getInstance()->product_ids as $product_id) {
    			$p = new \XLite\Model\Product($product_id);
                $product = $p->cloneObject();

    			foreach ($p->get('categories') as $category) {
    				$product->addCategory($category);
                }

    			$product->set('name', $product->get('name') . ' (CLONE)');
    			$product->update();
                $this->set('status', 'cloned');
            }
        }
    }*/
}

