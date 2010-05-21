{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Advanced search form (vertical)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="XLite_Module_AdvancedSearch_View_Form_QuickSearch" name="adsearch_form" className="advanced-search-sidebar" />

  {* Had to use TABLE because it's the only cross-browser solution to make the widget fit 100% width of a sidebar *}

  <table>

    <tr class="search-string form-field">
      <td colspan="3">
        <label for="search-string">Search for:</label>
        <input type="text" id="search-string" name="search[substring]" value="{search.substring}" />
      </td>
    </tr>

    <tr class="search-price">
      <td colspan="3">
        <label for="search-price-start">Price, $ (range):</label>
      </td>
    </tr>
    <tr class="search-price value-range form-field">
      <td class="start">
        <input type="text" class="start wheel-ctrl field-float field-positive" id="search-price-start" name="search[start_price]" value="{search.start_price}" />
      </td>
      <td class="dash">&ndash;</td>
      <td class="end">
        <input type="text" class="end wheel-ctrl field-float field-positive" id="search-price-end" name="search[end_price]" value="{search.end_price}" />
      </td>
    </tr>

    <tr class="search-weight">
      <td colspan="3">
        <label for="search-weight-start">Weight, {config.General.weight_symbol} (range):</label>
      </td>
    </tr>
    <tr class="search-weight value-range form-field">
      <td class="start">
        <input type="text" class="start wheel-ctrl field-integer field-positive" id="search-weight-start" name="search[start_weight]" value="{search.start_weight}" />
      </td>
      <td class="dash">&ndash;</td>
      <td class="end">
        <input type="text" class="end wheel-ctrl field-integer field-positive" id="search-weight-end" name="search[end_weight]" value="{search.end_weight}" />
      </td>
    </tr>

    <tr class="search-category form-field">
      <td colspan="3">
        <label for="search-category">Category:</label>
        <widget template="modules/AdvancedSearch/select_category.tpl" class="XLite_View_CategorySelect" fieldName="search[category]" allOption selectedCategoryId="{search.category}" />
      </td>
    </tr>

  </table>

  <div class="buttons-row">
    <widget class="XLite_View_Button_Submit" label="Search" />
  </div>

<widget name="adsearch_form" end />

<script type="text/javascript">
<!--
new advancedSearchController($('.advanced-search-sidebar'));
-->
</script>

