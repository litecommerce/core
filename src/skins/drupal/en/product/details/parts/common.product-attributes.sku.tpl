{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details SKU main block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="product.details.common.product-attributes", weight="10")
 *}
<li IF="product.getSKU()" class="identifier product-sku">
  <strong class="type">{t(#SKU#)}:</strong>
  <span class="value">{product.sku}</span>
</li>
