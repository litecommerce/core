{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Display product options as select box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select name="product_options[{option.getGroupId()}]">
  <option FOREACH="option.getActiveOptions(),opt" value="{opt.getOptionId()}" selected="{isOptionSelected(opt)}" >
    {opt.getName():h}
    <widget class="\XLite\Module\CDev\ProductOptions\View\ProductOptionModifier" option="{opt}" />
  </option>
</select>
