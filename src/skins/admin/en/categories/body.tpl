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
      <widget template="categories/parts/image.tpl" image="{category.getImage()}" />
    </td>

    <td>

      <table>

        <tr>
          <td class="table-label">{t(#Category name#)}:</td>
          <td>&nbsp;</td>
          <td>{category.getName()}</td>
        </tr>

        <tr>
          <td class="table-label">{t(#Description#)}:</td>
          <td>&nbsp;</td>
          <td class="category-description">{category.getDescription():h}</td>
        </tr>

        <tr>
          <td class="table-label">{t(#Availability#)}:</td>
          <td>&nbsp;</td>
          <td IF="category.getEnabled()">{t(#Enabled#)}</td>
          <td IF="!category.getEnabled()">{t(#Disabled#)}</td>
        </tr>

        <tr>
          <td class="table-label">{t(#Membership access#)}:</td>
          <td>&nbsp;</td>
          <td IF="isSelected(#0#,category.getMembership())">{t(#No membership#)}</td>
          <td IF="!isSelected(#0#,category.getMembership())">
          {foreach:getMemberships(),membership}
            {if:category.getMembership()=membership.getMembershipId()}{category.membership.getName()}{end:}
          {end:}
          </td>
        </tr>

        <tr>
          <td class="table-label">{t(#Parent category#)}:</td>
          <td>&nbsp;</td>

          <td IF="!getRootCategoryId()=category.parent.getCategoryId()">
            <a href="{buildURL(#categories#,##,_ARRAY_(#category_id#^category.parent.getCategoryId()))}">{category.parent.getName()}</a>
          </td>

          <td IF="getRootCategoryId()=category.parent.getCategoryId()">
            <a href="{buildURL(#categories#)}">[{t(#Root Level#)}]</a>
          </td>
        </tr>

        {displayViewListContent(#category.modify.children#)}

        <tr>
          <td colspan="3">
            <widget class="\XLite\View\Button\Regular" label="{t(#Modify#)}" jsCode="onModifyClick('{category.getCategoryId()}')" />
          </td>
        </tr>

      </table>

    </td>

  </tr>

</table>

<widget
  IF="getRootCategoryId()=category.getCategoryId()"
  class="\XLite\View\Button\Regular"
  id="modify-root"
  label="{t(#Modify root category (the front shop page)#)}"
  jsCode="self.location='{buildURL(#category#,##,_ARRAY_(#category_id#^getRootCategoryId(),#mode#^#modify#))}'" />

<br />
<br />

<form method="post" action="admin.php">

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="category_id" value="{category.getCategoryId()}" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="mode" />

  <table class="category-data">

    <tr>
      <th colspan="2" align="left" IF="category">

        <span IF="category.getParentId()=0">{t(#Categories#)}</span>
        <span IF="!category.getParentId()=0">{t(#Subcategories#)}</span>

        <hr />

      </th>
    </tr>

    <tbody IF="category&category.hasSubcategories()">

    <tr
      FOREACH="getSubcategories(getCategoryId()),id,cat"
      class="{getRowClass(id,##,#highlight#)}"
      onmouseover="javascript:jQuery('.hidden-{cat.getCategoryId()}').show()"
      onmouseout="javascript:jQuery('.hidden-{cat.getCategoryId()}').hide()">

      <td class="table-label" colspan="2">

        <span class="category-position">
          <input type="text" name="{getNamePostedData(#pos#,cat.getCategoryId())}" value="{cat.getPos()}" size="4" />
          <span class="category-position-text">{t(#Pos.:#)}</span>
        </span>

        &nbsp;&nbsp;

        <a
          href="{buildURL(#categories#,##,_ARRAY_(#category_id#^cat.getCategoryId()))}"
          title="{t(#Click here to access/add subcategories#)}"
          onclick="this.blur()">{cat.getName():h}</a>

        ({cat.getProductsCount()} products){if:!cat.getEnabled()}&nbsp;&nbsp;<span class="star">({t(#disabled#)})</span>{end:}

        &nbsp;&nbsp;

        <a class="hidden hidden-{cat.getCategoryId()}" href="javascript:void(0);" onclick="onAddChildClick('{cat.getCategoryId()}')">{t(#Add child#)}</a>

        &nbsp;&nbsp;

        <widget
          class="\XLite\View\Button\DeleteCategory"
          categoryId="{cat.getCategoryId()}"
          style="hidden hidden-{cat.getCategoryId()}" />

      </td>

    </tr>

    </tbody>

    <tr IF="!category&category.hasSubcategories()">

      <td colspan="2">{t(#There are no categories#)}</td>

    </tr>

    <tr>

      <td>
        <widget IF="category&category.hasSubcategories()" class="\XLite\View\Button\Submit" label="{t(#Update#)}" />
        <widget class="\XLite\View\Button\Regular" id="add" label="{t(#Add subcategory#)}" jsCode="onAddChildClick({getCategoryId()})" />
      </td>

      <td IF="category&category.getSubCategoriesCount()" align="right">
        <widget class="\XLite\View\Button\DeleteCategory" id="delete_all_button" label="{t(#Delete all#)}" />
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
