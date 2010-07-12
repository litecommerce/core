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
<ul class="lc-subcategory-icons" IF="category.getSubcategories()">
  <li FOREACH="category.getSubcategories(),subcategory">
    {* FF2 requires an extra div in order to display "inner-blocks" properly *}
    <a href="{buildURL(#category#,##,_ARRAY_(#category_id#^subcategory.category_id))}" class="lc-subcategory-icon">
      <span class="lc-subcategory-icon">
        <widget class="\XLite\View\Image" image="{subcategory.image}" maxWidth="{getIconWidth()}" maxHeight="{getIconHeight()}" alt="{subcategory.name}" centerImage=1 />
      </span>
      <span class="lc-subcategory-name">{subcategory.name}</span>
    </a>
  </li>
  <li FOREACH="getViewList(#subcategories.childs#),w">
    {w.display()}
  </li>
</ul>
{displayViewListContent(#subcategories.base#)}
