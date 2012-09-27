{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item buttons
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="productBlock.info", weight="400")
 *}
<widget
  IF="product.isAvailable()"
  class="\XLite\View\Form\Product\AddToCart"
  name="add_to_cart_{product.product_id}"
  product="{product}"
  className="add-to-cart" />
  <widget class="\XLite\View\Button\Submit" style="product-add2cart" label="Add to cart" />
<widget name="add_to_cart_{product.product_id}" end />
