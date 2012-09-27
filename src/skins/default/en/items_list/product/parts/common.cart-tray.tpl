{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.cart")
 *}

<div class="cart-tray">
  <div class="tray-area">
    <div class="drop-here tray-status">{t(#Drop items here to shop#)}</div>
    <div class="product-added tray-status">{t(#Product added to bag#)}</div>
    <div class="progress-bar"><div class="block-wait"><div></div></div></div>
  </div>
</div>
<div class="preload-cart-tray"></div>
