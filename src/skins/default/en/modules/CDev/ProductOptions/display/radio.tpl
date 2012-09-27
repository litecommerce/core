{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Display product options as radio buttons list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul>
  <li FOREACH="option.getOptions(),opt">
    <input type="radio" id="product_option_{opt.getOptionId()}" name="product_options[{option.getGroupId()}]" value="{opt.getOptionId()}" checked="{isOptionSelected(opt)}" />
    <label for="product_option_{opt.getOptionId()}">
      {opt.getName()}
      <widget class="\XLite\Module\CDev\ProductOptions\View\ProductOptionModifier" option="{opt}" />
    </label>
  </li>
</ul>
