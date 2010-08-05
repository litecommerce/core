{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Display product options as radio buttons list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul>
  <li FOREACH="option.getOptions(),opt">
    <input type="radio" id="product_option_{opt.getOptionId()}" name="product_options[{option.getGroupId()}]" value="{opt.getOptionId()}" checked="{isOptionSelected(opt)}" />
    <label for="product_option_{opt.getOptionId()}">
      {opt.getName():h}
      <widget class="\XLite\Module\ProductOptions\View\ProductOptionModifier" option="{opt}" />
    </label>
  </li>
</ul>
