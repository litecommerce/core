{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<tr>
	<td valign=top class="FormButton">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Categories<br><i>To (un)select more than one category,<br>Ctrl-click it</i></td>
        <td class="Star" valign=top>&nbsp;*&nbsp;</td>
	</tr>
	</table>
	</td>
	<td>
        <select IF="target=#add_product#" name="product_categories[]" multiple size="10">
    		<option FOREACH="categories,v" value="{v.category_id:r}" selected="{isSelectedCategory(v.category_id)}">{v.stringPath:h}</option>
    		<option IF="isEmpty(categories)" value="0" selected>-- No categories --</option>
        </select>
        
        <select IF="target=#product#" name="categories[]" multiple size="10">
        	{if:isEmpty(categories)}
    		<option value="0" selected>-- No categories --</option>
    		{else:}
    		{foreach:categories,v}
    			{if:product.inCategory(v)}
    				<option value="{v.category_id:r}" selected>{v.stringPath:h}</option>
    			{end:}
    		{end:}
    		{foreach:categories,v}
    			{if:!product.inCategory(v)}
    				<option value="{v.category_id:r}">{v.stringPath:h}</option>
    			{end:}
    		{end:}
    		{end:}
		</select>

		<span IF="target=#add_product#"><widget class="XLite_Module_MultiCategories_Validator_MultiCategoriesValidator" field="product_categories"></span>
        <span IF="target=#product#"><widget class="XLite_Module_MultiCategories_Validator_MultiCategoriesValidator" field="categories"></span>
	</td>
</tr>
