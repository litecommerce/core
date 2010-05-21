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
<table border="0" cellpadding="2" cellspacing="0">
<tr FOREACH="getCategories(),_category">
	<td valign="bottom"><img src="images/category_arrow.gif" width="12" height="13" alt=""></td>
    <td width="100%"><a href="{buildURL(#category#,##,_ARRAY_(#category_id#^_category.category_id))}" class="SidebarItems"><FONT class="CategoriesList">{_category.name}</FONT></a></td>
</tr>
</table>
