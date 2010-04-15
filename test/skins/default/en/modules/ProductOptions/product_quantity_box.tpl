{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div IF="product.productOptions&product.inventory.found&!product.tracking" class="quantity">

  <strong>Quantity:</strong>
  <span IF="{product.inventory.amount}">{product.inventory.amount} item(s) available</span>
  <span IF="{!product.inventory.amount}">- out of stock -</span>
</div>

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/product_quantity.tpl" visible="{xlite.PA_InventorySupport}">

<div IF="product.productOptions&product.tracking&product.outOfStock" class="quantity">

  <strong>Quantity:</strong>
  <span>- out of stock -</span>

</div>
