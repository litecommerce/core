{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details SKU main block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.common.product-attributes", weight="200")
 *}
<li IF="product.getSKU()" class="identifier product-sku">
  <div><strong class="type">{t(#SKU#)}</strong></div>
  <span class="value">{product.sku}</span>
</li>
