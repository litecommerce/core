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
<table IF="category.subcategories" cellpadding="5" cellspacing="0">
  <tr>
    <td valign="top" IF="category.hasImage()">
      <img src="{category.getImageURL()}" alt="" />
    </td>
    <td valign="top">
      <table FOREACH="category.subcategories,subcategory">
        <tr>
          <td>
            <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}"><font class="ItemsList">{subcategory.name}</font></a>
          </td>
        </tr>    
      </table>
    </td>
  </tr>
</table>
