{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkbox list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.17
 *}

<ul class="checkbox-list">
  <li FOREACH="getOptions(),optionValue,optionLabel">
    <input {getItemDumpAttributesCode(optionValue):h} />
    <input {getItemAttributesCode(optionValue):h} />
    <label for="{getItemId(optionValue):h}">{optionLabel:h}</label>
  </li>
</ul>
