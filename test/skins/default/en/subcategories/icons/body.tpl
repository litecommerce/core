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
<table IF="category.getSubcategories()" cellpadding="5" cellspacing="0" border="0" width="100%">
  <tbody FOREACH="split(category.getSubcategories(),4),row">
    <tr>
      <td valign="middle" FOREACH="row,subcategory" align="center" width="25%">
        <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0" alt=""></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0" alt=""></span></a>&nbsp;
      </td>
    </tr>
    <tr>
      <td valign="top" FOREACH="row,subcategory" align="center" width="25%">
        <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}" IF="subcategory"><FONT class="ItemsList">{subcategory.name}</FONT></a>
        <br>
        <br>
      </td>
    </tr>
  </tbody>
</table>
