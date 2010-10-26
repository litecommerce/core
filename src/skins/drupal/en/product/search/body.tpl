{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product search form template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="search-product-form">

  <widget class="\XLite\View\Form\Product\Search\Customer\Main" name="simple_products_search" />

  <div class="search-form">

    <table class="search-form-main-part">

      <tr>
        <td class="substring-cell">
          <input type="text" class="form-text" size="30" name="substring" value="{getCondition(#substring#)}">
        </td>
        <td>
          <widget class="\XLite\View\Button\Submit" label="Search products" style="search-form-submit" />
        </td>
      </tr> 

      <tr class="including-options-list">
        <td>

          <ul class="search-including-options">
            <li><label for="including-all">
            <input type="radio" name="including" id="including-all" value="all" checked="{getChecked(#including#,#all#)}" />
            All words
            <label/></li>
            <li><label for="including-any">
            <input type="radio" name="including" id="including-any" value="any" checked="{getChecked(#including#,#any#)}" />
            Any word
            </label></li>
            <li><label for="including-phrase">
            <input type="radio" name="including" id="including-phrase" value="phrase" checked="{getChecked(#including#,#phrase#)}" /> 
            Exact phrase
            </label></li>
          </ul>

        </td>
        <td class="less-search-options-cell">
          <a href="javascript:void(0);" onclick="javascript:$('#advanced_search_options').toggle();">Less search options</a>
        </td>

    </table>

    <table id="advanced_search_options" class="advanced-search-options">

      <tr>
        <td class="option-name">
          Search in: 
        </td>
        <td>

          <ul class="search-by-options">
            <li><label for="by-title">
              <input type="checkbox" name="by_title" id="by-title" value="Y" checked="{getChecked(#by_title#)}" />
              Title
            </label></li>
            <li><label for="by-descr">
              <input type="checkbox" name="by_descr" id="by-descr" value="Y" checked="{getChecked(#by_descr#)}" /> 
              Description
            </label></li>
            <li><label for="by-sku">
              <input type="checkbox" name="by_sku" id="by-sku" value="Y" checked="{getChecked(#by_sku#)}" /> 
              SKU
            </label></li>
          </ul>

        </td>
      </tr>

      <tr>
        <td class="option-name">
          Category:
        </td>
        <td>
          <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryId="{getCondition(#categoryId#):r}" allOption />
        </td>
      </tr>

    </table>

  </div>

  <widget name="simple_products_search" end />

</div>

<widget class="\XLite\View\ItemsList\Product\Customer\Search" />

