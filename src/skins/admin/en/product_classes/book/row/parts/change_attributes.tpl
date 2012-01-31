{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Change attributes" link
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="productClasses.book.row", weight="700")
 *}

<div IF="getAttributesNumber()&!isNew()" class="product-class-change-attributes">
  <widget class="\XLite\View\Button\Popup\Link" label="Change attributes" popupTarget="product_class_assign_attributes" popupWidget="\XLite\View\ProductClasses\Book\AssignAttributes" />
</div>
