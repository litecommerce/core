{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Subcategories list (grid style)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<table class="subcategory-icons">

  <tr class="info" FOREACH="getCategoryRows(),row">
    <td FOREACH="row,idx,subcategory" class="hcategory">
      {if:subcategory}
      <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}" class="hcategory">
      <span class="subcategory-icon">
        <widget class="\XLite\View\Image" image="{subcategory.image}" maxWidth="{getIconWidth()}" maxHeight="{getIconHeight()}" alt="{subcategory.name}" centerImage=1 />
      </span>
      <br />
      <span class="subcategory-name">{subcategory.name}</span>
      </a>
      {else:}
      &nbsp;
      {end:}
    </td>
  </tr>

  {displayListPart(#items#)}

</table>

{displayViewListContent(#subcategories.base#)}

<div class="subcategories-separator"></div>
