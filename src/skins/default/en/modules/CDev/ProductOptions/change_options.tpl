{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Change options
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<widget class="\XLite\Module\CDev\ProductOptions\View\Form\Item\ChangeOptions" name="change_options" className="change-options" />
  <widget class="\XLite\Module\CDev\ProductOptions\View\ProductOptions" product="{getProduct()}" selectedOptions="{getSelectedOptions()}" />

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Change#)}" style="action" />
  </div>

<widget name="change_options" end />
