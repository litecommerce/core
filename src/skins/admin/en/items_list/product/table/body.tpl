{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (table variant)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<table class="data-table items-list">

  <tr>
    <list name="header" type="inherited" product="{product}" />
  </tr>

  <tr FOREACH="getPageData(),idx,product" class="{getRowClass(idx,##,#highlight#)}">
    <list name="columns" type="inherited" product="{product}" />
  </tr>

  <tr FOREACH="getViewList(#itemsList.product.table.admin.items#),w">
    {w.display()}
  </tr>

</table>
