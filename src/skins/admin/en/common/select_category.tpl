{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category selection dropdown box template 
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<select name="{getParam(#fieldName#)}" size="1" {if:nonFixed} style="width:200pt" {else:}  class="FixedSelect" {end:}  >
   <option value="" IF="getParam(#allOption#)">All</option>
   <option value="" IF="getParam(#noneOption#)">None</option>
   <option value="" IF="getParam(#rootOption#)" class="CenterBorder">[Root Level]</option>
	{foreach:getCategories(),key,category}
	{if:!category.category_id=getParam(#currentCategoryId#)}
    <option value="{category.category_id:r}" selected="{isCategorySelected(category)}" style="padding-left: {category.getIndentation(15)}px;">{category.name:h}</option>
  {end:}
	{end:}
  <option value="" IF="isDisplayNoCategories()">-- No categories --</option>
</select>
