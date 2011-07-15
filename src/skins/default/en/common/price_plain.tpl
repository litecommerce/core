{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Price widget
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
{if:isDisplayOnlyPrice()}

  <span class="price product-price">{formatPrice(getListPrice()):h}</span>

{else:}

  <div class="price product-price">{formatPrice(getListPrice()):h}</div>

  <div IF="{isSalePriceEnabled()}" class="product-market-price">
    {t(#List price#)}:
    <span class="price">{formatPrice(getSalePrice()):h}</span>
    <span IF="{isSaveEnabled()}">, {t(#you save#)}:
      <span class="save">{getSaveValueAbsolute()} ({getSaveValuePercent()}%)</span>
    </span>
  </div>

{end:}
