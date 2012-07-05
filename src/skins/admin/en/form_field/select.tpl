{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<select {getAttributesCode():h}>
  {foreach:getOptions(),optionValue,optionLabel}
    {if:isGroup(optionLabel)}
      <optgroup {getOptionGroupAttributesCode(optionValue,optionLabel):h}>
        {foreach:optionLabel.options,optionValue2,optionLabel2}
          <option {getOptionAttributesCode(optionValue2):h}>{optionLabel2:h}</option>
        {end:}
      </optgroup>
    {else:}
      <option {getOptionAttributesCode(optionValue):h}>{optionLabel:h}</option>
    {end:}
  {end:}
</select>
