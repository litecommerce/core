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

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ProductPopup extends AAdmin
{
    public $params = array('target', "formName", "spanName", "formField", 'mode', 'search_productsku', 'substring', 'search_category', 'subcategory_search', 'pageID', 'status');
    public $template = "product_popup.tpl";
    // search form parameters	
    public $extra_parameters = '<input type="hidden" name="mode" value="search">';
    public $form_target = 'product_popup';
    public $form_action = 'default';
    public $products = null;

    function getProducts()
    {
        if (is_null($this->products)) {
            $p = new \XLite\Model\Product();
            $this->products = $p->advancedSearch($this->substring,
                    $this->search_productsku,
                    $this->search_category,
                    $this->subcategory_search);
            $this->productsFound = count($this->products);
        }
        return $this->products;
    }

}
