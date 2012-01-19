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

        <td class="substring-cell">
          <input type="text" class="form-text" size="30" maxlength="200" name="substring" value="{getCondition(#substring#)}" />
        </td>

        <td>
          <widget class="\XLite\View\Button\Submit" label="{t(#Search products#)}" style="search-form-submit" />
        </td>

      </tr>

      <tr class="including-options-list">

        <td>

          <ul class="search-including-options">

            <li>
              <input type="radio" name="including" id="including-all" value="all" checked="{getChecked(#including#,#all#)}" />
              <label for="including-all">{t(#All words#)}<label/>
            </li>

            <li>
              <input type="radio" name="including" id="including-any" value="any" checked="{getChecked(#including#,#any#)}" />
              <label for="including-any">{t(#Any word#)}</label>
            </li>

            <li>
              <input type="radio" name="including" id="including-phrase" value="phrase" checked="{getChecked(#including#,#phrase#)}" />
              <label for="including-phrase">{t(#Exact phrase#)}</label>
            </li>

          </ul>

        </td>

        <td class="less-search-options-cell">
          <a href="javascript:void(0);" onclick="javascript:core.toggleText(this,'Less search options','#advanced_search_options');">{t(#More search options#)}</a>
        </td>

    </table>

    <table id="advanced_search_options" class="advanced-search-options">

      <tr>

        <td class="option-name title-search-by-options">
          {t(#Search in#)}:
        </td>

        <td>

          <ul class="search-by-options">

            <li><label for="by-title">
              <input type="checkbox" name="by_title" id="by-title" value="Y" checked="{getChecked(#by_title#)}" />
              {t(#Title#)}
            </label></li>

            <li><label for="by-descr">
              <input type="checkbox" name="by_descr" id="by-descr" value="Y" checked="{getChecked(#by_descr#)}" />
              {t(#Description#)}
            </label></li>

            <li><label for="by-sku">
              <input type="checkbox" name="by_sku" id="by-sku" value="Y" checked="{getChecked(#by_sku#)}" />
              {t(#SKU#)}
            </label></li>

          </ul>

        </td>
      </tr>

      <tr>
        <td class="option-name title-category">
          {t(#Category#)}:
        </td>
        <td>
          <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryIds="{_ARRAY_(getCondition(#categoryId#))}" allOption />
        </td>
      </tr>

    </table>

  </div>

  <widget name="simple_products_search" end />

</div>

<widget class="\XLite\View\ItemsList\Product\Customer\Search" />
