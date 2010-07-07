{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category details (readonly) and subcategories list page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<table border="0" width="100%" IF="category.category_id">

  <tr>

    <td valign="top" IF="category.hasImage()">
 		  <img src="{category.image.getUrl()}" border="0" width="{category.image.width}" height="{category.image.height}" />
    </td>

    <td width="100%" valign="top">

      <table border="0" cellpadding="3" cellspacing="1" valign="top">

        <tr>
          <td nowrap>Category name:</td>
          <td>&nbsp;</td>
          <td class="FormButton">{category.name}</td>
        </tr>

        <tr>
          <td nowrap>Description:</td>
          <td>&nbsp;</td>
          <td class="FormButton">{category.description}</td>
        </tr>

        <tr>
          <td nowrap>Availability:</td>
          <td>&nbsp;</td>
          <td class="FormButton">{if:category.enabled}Enabled{else:}Disabled{end:}</td>
        </tr>

        <tr>
          <td nowrap>Membership access:</td>
          <td>&nbsp;</td>
          <td class="FormButton">
            {if:isSelected(#0#,category.membership_id)}No membership
            {else:}
              {foreach:getMemberships(),membership}
                {if:category.membership_id=membership.membership_id}{category.membership.name}{end:}
              {end:}
            {end:}
          </td>
        </tr>

        <tr>
          <td nowrap>Parent category:</td>
          <td>&nbsp;</td>
          {if:parentCategory.category_id}
          <td class="FormButton"><a href="admin.php?target=categories&category_id={parentCategory.category_id}">{parentCategory.name}</a></td>
          {else:}
          <td class="FormButton"><a href="admin.php?target=categories">[Root Level]</a></td>
          {end:}
        </tr>

        {displayViewListContent(#category.modify.childs#)}

        <tr>
          <td colspan="3">
            <input type="button" value="Modify" onClick="onModifyClick('{category.category_id}')" />
          </td>
        </tr>

      </table>

    </td>

  </tr>

</table>

<p IF="!categories">There are no categories</p>

<p>

<form name="CategoryForm" method="post" action="admin.php">

  <table border="0" cellpadding="3" cellspacing="1" width="90%">

    <tr>
      <th colspan="5" align="left" IF="category">
        <span class="FormButton" IF="category">Subcategories structure</span>
        <hr />
      </th>
    </tr>

    <tr FOREACH="categories,id,cat" class="{getRowClass(id,##,#TableRow#)}">

      <td width="100%">
        <img src="images/spacer.gif" height="1" width="{getIndentation(cat.depth,20)}" />
        <a href="admin.php?target=categories&category_id={cat.category_id}" title="Click here to access/add subcategories" onClick="this.blur()"><font class="ItemsList"><u>{cat.name:h}</u></font></a> ({cat.products_count} products){if:!cat.enabled}&nbsp;&nbsp;<font color=red>(disabled)</font>{end:}
      </td>

      <td nowrap>
        &nbsp;&nbsp;
        <input type="button" value="Add before" onClick="onAddBeforeClick('{cat.category_id}')" />
      </td>

      <td nowrap>
        &nbsp;&nbsp;
        <input type="button" value="Add after" onClick="onAddAfterClick('{cat.category_id}')" />
      </td>

      <td nowrap>
        &nbsp;&nbsp;
        <input type="button" value="Add child" onClick="onAddChildClick('{cat.category_id}')" IF="isCategoryLeafNode(cat.category_id)" />
      </td>

      <td nowrap>
        &nbsp;&nbsp;
        &nbsp;&nbsp;
        <input type="button" value="Delete" onClick="onDeleteClick('{cat.category_id}')" />
        &nbsp;&nbsp;
        <input type="button" value="Delete subcategories" onClick="onDeleteSubcatsClick('{cat.category_id}')" IF="!isCategoryLeafNode(cat.category_id)" />
      </td>

    </tr>

  </table>

  <br />

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="category_id" value="{category.category_id}" />
  <input type="hidden" name="action" />
  <input type="hidden" name="mode" />

  <table border="0" cellpadding="3" cellspacing="1" width="90%">

    <tr>
      <td IF="!categories">
        <input id="add" type="button" value="Add category" onClick="onAddClick()">
      </td>		
      <td align="right" IF="category.subCategoriesCount|isRootCategories(categories)">
        <input id="delete_all_button" type="button" value="Delete all" onClick="onDeleteClick({category.category_id})">
      </td>		
    </tr>

  </table>

</form>

<script language="javascript">

function onAddClick()
{
    document.location = "admin.php?target=category&mode=add_child";
}

function onDeleteClick(category_id)
{
	document.location = "admin.php?target=categories&category_id=" + category_id + "&mode=delete";
}	

function onDeleteSubcatsClick(category_id)
{
	document.location = "admin.php?target=categories&category_id=" + category_id + "&mode=delete&subcats=1";
}	

function onAddBeforeClick(category_id)
{
	document.location = "admin.php?target=category&category_id=" + category_id + "&mode=add_before";
}	

function onAddAfterClick(category_id)
{
	document.location = "admin.php?target=category&category_id=" + category_id + "&mode=add_after";
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

