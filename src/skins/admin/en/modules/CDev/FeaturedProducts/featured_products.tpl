{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Featured products management template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{if:featuredProductsList}
  <widget class="\XLite\Module\CDev\FeaturedProducts\View\Form\Update" name="fpupdate_form" />

     <table class="data-table" width="100%">

      <tr>
        <th style="width:30px">{t(#Delete#)}</th>
        <th style="width:30px">{t(#Pos.#)}</th>
        <th>Title</th>
      </tr>

      <tr FOREACH="featuredProductsList,idx,featuredProduct" class="{getRowClass(idx,##,#highlight#)}">
        <td align="center"><input type="checkbox" name="delete[{featuredProduct.id}]" /></td>
        <td align="center"><input type="text" size="4" name="orderbys[{featuredProduct.id}]" value="{featuredProduct.order_by}" /></td>
        <td class="table-label">
          <a href="admin.php?target=product&product_id={featuredProduct.product.product_id}">{featuredProduct.product.name:h}</a>
          <span IF="{!featuredProduct.product.enabled}" color="red">&nbsp;&nbsp;&nbsp;(not available for sale)</span>
        </td>
      </tr>

    </table>

  <br />

  <widget class="\XLite\View\Button\Submit" label="{t(#Update#)}" />

  <widget name="fpupdate_form" end />

{else:}

  <p>{t(#No featured products defined for this category#)}</p>

{end:}

<br /><br />

<widget template="common/dialog.tpl" head="{t(#Add featured products#)}" body="modules/CDev/FeaturedProducts/product/search.tpl" />

{* Open <form ...> tag *}
<widget class="\XLite\Module\CDev\FeaturedProducts\View\Form\Add" name="products_form" />

  {* List of products *}
  <widget class="\XLite\Module\CDev\FeaturedProducts\View\Admin\FeaturedProducts" />

{* Close </form> tag *}
<widget name="products_form" end />
