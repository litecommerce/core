{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category details (readonly) and subcategories list page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{* TODO full refactoring *}

<table IF="category&!getRootCategoryId()=category.getCategoryId()">

  <tr>

    <td IF="category.hasImage()">
      <img src="{category.image.getURL()}" width="{category.image.width}" height="{category.image.height}" alt="" />
    </td>

    <td>

      <table>

        <tr>
          <td class="table-label">{t(#Category name#)}:</td>
          <td>&nbsp;</td>
          <td>{category.name}</td>
        </tr>

        <tr>
          <td class="table-label">{t(#Description#)}:</td>
          <td>&nbsp;</td>
          <td>{category.description}</td>
        </tr>

        <tr>
          <td class="table-label">{t(#Availability#)}:</td>
          <td>&nbsp;</td>
          <td>{if:category.enabled}{t(#Enabled3#)}{else:}{t(#Disabled#)}{end:}</td>
        </tr>

        <tr>
          <td class="table-label">{t(#Membership access#)}:</td>
          <td>&nbsp;</td>
          <td>
            {if:isSelected(#0#,category.membership)}{t(#No membership#)}
            {else:}
              {foreach:getMemberships(),membership}
                {if:category.membership=membership.membership_id}{category.membership.name}{end:}
              {end:}
            {end:}
          </td>
        </tr>

        <tr>
          <td class="table-label">{t(#Parent category#)}:</td>
          <td>&nbsp;</td>

          <td IF="!getRootCategoryId()=category.parent.getCategoryId()">
            <a href="admin.php?target=categories&category_id={category.parent.getCategoryId()}">{category.parent.getName()}</a>
          </td>

          <td IF="getRootCategoryId()=category.parent.getCategoryId()">
            <a href="admin.php?target=categories">[{t(#Root Level#)}]</a>
          </td>
        </tr>

        {displayViewListContent(#category.modify.children#)}

        <tr>
          <td colspan="3">
            <widget class="\XLite\View\Button\Regular" label="Modify" jsCode="onModifyClick('{category.category_id}')" />
          </td>
        </tr>

      </table>

    </td>

  </tr>

</table>

<widget IF="getRootCategoryId()=category.getCategoryId()" class="\XLite\View\Button\Regular" id="modify-root" label="Modify root category (the front shop page)" jsCode="self.location='{buildURL(#category#,##,_ARRAY_(#category_id#^getRootCategoryId(),#mode#^#modify#))}'" />

<br />
<br />

<form name="CategoryForm" method="post" action="admin.php">

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="category_id" value="{category.category_id}" />
  <input type="hidden" name="action" />
  <input type="hidden" name="mode" />

  <table class="category-data">

    <tr>
      <th colspan="2" align="left" IF="category">

        <span IF="category.parent=0">{t(#Categories#)}</span>
        <span IF="!category.parent=0">{t(#Subcategories#)}</span>

        <hr />

      </th>
    </tr>

    <tbody IF="category&category.hasSubcategories()">

    <tr FOREACH="getSubcategories(getCategoryId()),id,cat" class="{getRowClass(id,##,#highlight#)}" onmouseover="javascript:jQuery('.hidden-{cat.category_id}').show()" onmouseout="javascript:jQuery('.hidden-{cat.category_id}').hide()">

      <td class="table-label" colspan="2">

        <a href="admin.php?target=categories&category_id={cat.category_id}" title="Click here to access/add subcategories" onclick="this.blur()">{cat.name:h}</a> ({cat.products_count} products){if:!cat.enabled}&nbsp;&nbsp;<span class="star">(disabled)</span>{end:}

        &nbsp;&nbsp;

        <a class="hidden hidden-{cat.category_id}" href="javascript:void(0);" onclick="onAddChildClick('{cat.category_id}')">{t(#Add child#)}</a>

        &nbsp;&nbsp;

        <widget class="\XLite\View\Button\DeleteCategory" categoryId="{cat.category_id}" style="hidden hidden-{cat.category_id}" />

        &nbsp;&nbsp;

        <widget class="\XLite\View\Button\DeleteCategory" categoryId="{cat.category_id}" style="hidden hidden-{cat.category_id}" IF="cat.hasSubcategories()" label="Delete subcategories" removeSubcategories=true />

      </td>

    </tr>

    </tbody>

    <tr IF="!category&category.hasSubcategories()">

      <td colspan="2">{t(#There are no categories#)}</td>

    </tr>

    <tr>

      <td>
        <widget class="\XLite\View\Button\Regular" id="add" label="Add subcategory" jsCode="onAddChildClick({getCategoryId()})" />
      </td>

      <td align="right" IF="category&category.getSubCategoriesCount()">
        <widget class="\XLite\View\Button\DeleteCategory" id="delete_all_button" label="Delete all" />
      </td>

    </tr>

  </table>

</form>

<script type="text/javascript">

{*TODO remove!!!*}

function onAddChildClick(category_id)
{
	document.location = "admin.php?target=category&category_id=" + category_id + "&mode=add_child";
}

function onModifyClick(category_id)
{
    document.location = "admin.php?target=category&category_id=" + category_id + "&mode=modify";
}

</script>
