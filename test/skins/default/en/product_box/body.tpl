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
<div>

  <div class="name">{product.name}</div>

  <div IF="product.hasImage()" class="image">
    <img src="{product.imageURL}" alt="" />
  </div>

  <div IF="{product.sku}" class="sku">
    <strong>SKU:</strong>
    <span>{product.sku}</span>
  </div>

  <widget class="XLite_View_Price" product="{product}" template="common/price_plain.tpl" />

  <br />

  <widget class="XLite_View_BuyNow" product="{product}" />

</div>
