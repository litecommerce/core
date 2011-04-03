{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item buttons
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="itemsList.product.table.customer.columns", weight="50")
 *}
<widget class="\XLite\View\Form\Product\AddToCart" name="add_to_cart_{product.product_id}" product="{product}" className="add-to-cart" />
  <widget IF="!product.inventory.isOutOfStock()" class="\XLite\View\Button\Image" style="product-add2cart" label="Add to cart" />
  <widget IF="product.inventory.isOutOfStock()" class="\XLite\View\Button\Image" label="Out of stock" jsCode="return false;" />
<widget name="add_to_cart_{product.product_id}" end />
