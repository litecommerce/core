{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Head cell
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *}

{if:column.sort}
  <a href="{buildURL(getTarget(),##,_ARRAY_(#sortBy#^column.sort,#sortOrder#^getSortDirectionNext(column)))}" data-sort="{column.sort}" data-direction="{getSortOrder()}" class="{getSortLinkClass(column)}">
    {column.name}
    {if:isColumnSorted(column)}
      <span class="dir">{if:#asc#=getSortOrder()}&uarr;{else:}&darr;{end:}</span>
    {end:}
  </a>
{else:}
  {column.name}
{end:}
<list type="inherited" name="{getCellListNamePart(#head#,column)}" column="{column}" />
