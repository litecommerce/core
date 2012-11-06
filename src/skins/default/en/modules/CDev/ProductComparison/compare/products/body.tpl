{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Body
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="add-to-compare">
  <div class="compare-popup">
    <div class="compare-checkbox">
      <input id="{getCheckboxId(product.product_id)}" type="checkbox" data-id="{product.product_id}" />
      <label for="{getCheckboxId(product.product_id)}">{t(#Compare#)}</label>
    </div>
    <div class="compare-button">
      <span class="compare-products-selected">{getTitle()}</span>
      <widget class="\XLite\View\Button\Link" location="{buildURL(#product_comparison#)}" label="Compare" style="action" />
    </div>
  </div>
</div>
