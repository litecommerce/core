{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product search form template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="search-product-form">

  <widget class="\XLite\View\Form\Product\Search\Customer\Main" name="simple_products_search" />

  <div class="search-form">

    <table class="search-form-main-part">

      <tr>
        <list name="products.search.conditions.substring" />
      </tr>

      <tr class="including-options-list">

        <td>

          <ul class="search-including-options">
            <list name="products.search.conditions.phrase" />
          </ul>

        </td>

        <td class="less-search-options-cell">
          <a href="javascript:void(0);" onclick="javascript:core.toggleText(this,'Less search options','#advanced_search_options');">{t(#More search options#)}</a>
        </td>

    </table>

    <table id="advanced_search_options" class="advanced-search-options">
      <list name="products.search.conditions.advanced" />
    </table>

  </div>

  <widget name="simple_products_search" end />

</div>

<widget class="\XLite\View\ItemsList\Product\Customer\Search" />
