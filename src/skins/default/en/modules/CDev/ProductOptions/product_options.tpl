{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product options
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script IF="product.hasOptionValidator()" type="text/javascript">
$(document).ready(
  function() {
    $('form[name="add_to_cart"]').submit(
      function(event) {
        {validatorJSCode:h}
      }
    );
  }
);
</script>

<ul class="product-options">
  <li FOREACH="product.getProductOptions(),option" class="product-option">
    <strong class="subtitle">{option.opttext:h}</strong>

    {if:!option.empty}

    <select IF="option.opttype=#SelectBox#" name="product_options[{option.optclass}]">
      <option FOREACH="option.productOptions,opt" value="{opt.option_id}" selected="{isOptionSelected(option,opt.option_id)}" >
        {opt.option:h}
        <widget class="\XLite\Module\CDev\ProductOptions\View\ProductOptionModifier" option="{opt}" optionGroup="{option}" product="{product}" />
	  	</option>
    </select>

    <ul IF="option.opttype=#Radio button#">
      <li FOREACH="option.productOptions,oid,opt">
        <input type="radio" id="product_option_{option.optclass}_{opt.option_id}" name="product_options[{option.optclass}]" value="{opt.option_id}" checked="{isOptionSelected(option,opt.option_id)}" />
        <label for="product_option_{option.optclass}_{opt.option_id}">
          {opt.option:h}
          <widget class="\XLite\Module\CDev\ProductOptions\View\ProductOptionModifier" option="{opt}" optionGroup="{option}" product="{product}" />
        </label>
      </li>
    </ul>

    {else:}

      <input IF="option.opttype=#Text#" type="text" name="product_options[{option.optclass}]" value="{getOptionText(option)}" size="{option.cols}" />
      <textarea IF="option.opttype=#Textarea#" cols="{option.cols}" rows="{option.rows}" name="product_options[{option.optclass}]">{getOptionText(option)}</textarea>

    {end:}

  </li>
</ul>

<widget template="modules/CDev/ProductOptions/options_exception.tpl" />
