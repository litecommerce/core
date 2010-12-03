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
	<td valign=top>
        {if:label}{label:h}{else:}Categories:{end:}<br><br>
        <i>To (un)select more than one category,<br>Ctrl-click it</i>
    </td>
	<td valign=top>
        <select id="category_selector" name="selected_categories[]" multiple size="10">
		    <option FOREACH="categories,v" value="{v.category_id:r}" selected="{isSelectedItem(#selected_categories#,v.category_id)}">{v.stringPath:h}</option>
		</select>

        <widget template="modules/CDev/EcommerceReports/checker.tpl" selector="category_selector">
        
	</td>
</tr>
