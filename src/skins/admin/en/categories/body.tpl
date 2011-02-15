{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category details (readonly) and subcategories list page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<table width="100%" IF="category&!getRootCategoryId()=category.getCategoryId()">

  <tr>

    <td valign="top" IF="category.hasImage()">
 		  <img src="{category.image.getUrl()}" width="{category.image.width}" height="{category.image.height}" alt="" />
    </td>

    <td style="width:100%;" valign="top">

      <table cellpadding="3" cellspacing="1" valign="top">

        <tr>
          <td nowrap>Category name:</td>
          <td>&nbsp;</td>
          <td>{category.name}</td>
        </tr>

        <tr>
          <td nowrap>Description:</td>
          <td>&nbsp;</td>
          <td>{category.description}</td>
        </tr>

        <tr>
          <td nowrap>Availability:</td>
          <td>&nbsp;</td>
          <td>{if:category.enabled}Enabled{else:}Disabled{end:}</td>
        </tr>

        <tr>
          <td nowrap>Membership access:</td>
          <td>&nbsp;</td>
          <td>

            {if:isSelected(#0#,category.membership)}No membership
            {else:}
              {foreach:getMemberships(),membership}
                {if:category.membership=membership.membership_id}{category.membership.name}{end:}
              {end:}
            {end:}

          </td>
        </tr>

        <tr>
          <td nowrap>Parent category:</td>
          <td>&nbsp;</td>
          {if:!getRootCategoryId()=category.parent.getCategoryId()}
          <td><a href="admin.php?target=categories&category_id={category.parent.getCategoryId()}">{category.parent.getName()}</a></td>
          {else:}
          <td><a href="admin.php?target=categories">[Root Level]</a></td>
          {end:}
        </tr>

        {displayViewListContent(#category.modify.childs#)}

        <tr>
          <td colspan="3">
            <widget class="\XLite\View\Button\Regular" label="Modify" jsCode="onModifyClick('{category.category_id}')" />
          </td>
        </tr>

      </table>

    </td>

  </tr>

</table>

<p>

<form name="CategoryForm" method="post" action="admin.php">

  <table cellpadding="3" cellspacing="1" width="90%">

    <tr>
      <th colspan="5" align="left" IF="category">
        <span IF="category">Subcategories structure</span>
        <hr />
      </th>
    </tr>

    {if:category.hasSubcategories()}
    <tr FOREACH="getSubcategories(getCategoryId()),id,cat" class="{getRowClass(id,##,#highlight#)}">

      <td style="width:100%;">
        <a href="admin.php?target=categories&category_id={cat.category_id}" title="Click here to access/add subcategories" onclick="this.blur()">{cat.name:h}</a> ({cat.products_count} products){if:!cat.enabled}&nbsp;&nbsp;<span class="star">(disabled)</span>{end:}
      </td>

      <td nowrap>
        <widget class="\XLite\View\Button\Regular" label="Add child" jsCode="onAddChildClick('{cat.category_id}')" />
      </td>

      <td nowrap>
        <widget class="\XLite\View\Button\Regular" label="Delete" jsCode="onDeleteClick('{cat.category_id}')" />
        &nbsp;&nbsp;
        <widget class="\XLite\View\Button\Regular" IF="cat.hasSubcategories()" label="Delete subcategories" jsCode="onDeleteSubcatsClick('{cat.category_id}')" />
      </td>

    </tr>
    {else:}
    <tr>
      <td>There are no categories</td>
    </tr>
    {end:}

  </table>

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="category_id" value="{category.category_id}" />
  <input type="hidden" name="action" />
  <input type="hidden" name="mode" />

  <table cellpadding="3" cellspacing="1" width="90%">

    <tr>
      <td>
        <widget class="\XLite\View\Button\Regular" id="add" label="Add category" jsCode="onAddChildClick({getCategoryId()})" />
      </td>		
      <td align="right" IF="category.getSubCategoriesCount()">
        <widget class="\XLite\View\Button\Regular" id="delete_all_button" label="Delete all" jsCode="onDeleteSubcatsClick({category.category_id})" />
      </td>		
    </tr>

  </table>

</form>

<script type="text/javascript">

function onDeleteClick(category_id)
{
	document.location = "admin.php?target=categories&category_id=" + category_id + "&mode=delete";
}	

function onDeleteSubcatsClick(category_id)
{
  document.location = "admin.php?target=categories&category_id=" + category_id + "&mode=delete&subcats=1";
}

function onAddChildClick(category_id)
{
	document.location = "admin.php?target=category&category_id=" + category_id + "&mode=add_child";
}	

function onModifyClick(category_id)
{
    document.location = "admin.php?target=category&category_id=" + category_id + "&mode=modify";
}	

</script>

