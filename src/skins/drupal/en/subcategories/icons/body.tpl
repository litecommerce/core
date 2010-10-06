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
<ul class="subcategory-icons grid-list" IF="getSubcategories()">
  <li FOREACH="getSubcategories(),subcategory">
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}">
      <span class="subcategory-icon">
        <widget class="\XLite\View\Image" image="{subcategory.image}" maxWidth="{getIconWidth()}" maxHeight="{getIconHeight()}" alt="{subcategory.name}" centerImage=1 />
      </span>
      <span class="subcategory-name">{subcategory.name}</span>
    </a>
  </li>
  <li FOREACH="getNestedViewList(#children#),item">{item.display()}</li>
</ul>
{displayViewListContent(#subcategories.base#)}
