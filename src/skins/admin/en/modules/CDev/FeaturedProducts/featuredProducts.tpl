{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Featured products management template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{if:featuredProductsList}
  <widget class="\XLite\Module\CDev\FeaturedProducts\View\Form\Update" name="fpupdate_form" />

    <table border="0" cellpadding="0" cellspacing="0" width="100%">

    <tr>
      <td bgcolor="#dddddd">

        <table cellpadding="2" cellspacing="1" border="0" width="100%">

          <tr class="TableHead" bgcolor="#ffffff">
            <th width="2"0>Delete</th>
            <th width="2"0>Pos.</th>
            <th>Title</th>
          </tr>

          <tr bgcolor="#ffffff" FOREACH="featuredProductsList,featuredProduct">
            <td align="center" width="2"0><input type="checkbox" name="delete[{featuredProduct.id}]" /></td>
            <td align="center" width="2"0><input type="text" size="4" name="orderbys[{featuredProduct.id}]" value="{featuredProduct.order_by}" /></td>
            <td nowrap>
              <a href="admin.php?target=product&product_id={featuredProduct.product.product_id}">{featuredProduct.product.name:h}</a>
              <font IF="{!featuredProduct.product.enabled}" color="red">&nbsp;&nbsp;&nbsp;(not available for sale)</font>
            </td>
          </tr>

        </table>

      </td>
    </tr>

    </table>

  <br />

  <widget class="\XLite\View\Button\Submit" label="Update" />

  <widget name="fpupdate_form" end />

{else:}

  <p>{t(#No featured products defined for this category#)}</p>

{end:}

<br /><br />

<widget template="common/dialog.tpl" head="Add featured products" body="modules/CDev/FeaturedProducts/product/search.tpl" />

{* Open <form ...> tag *}
<widget class="\XLite\Module\CDev\FeaturedProducts\View\Form\Add" name="products_form" />

  {* List of products *}
  <widget class="\XLite\Module\CDev\FeaturedProducts\View\Admin\FeaturedProducts" />

{* Close </form> tag *}
<widget name="products_form" end />
