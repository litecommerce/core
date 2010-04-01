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

  <div class="substring">
    <label for="search_substring">Search for:</label>
    <input type="text" class="substring" id="search_substring" name="search[substring]" value="{search.substring}" />
  </div>

  <div class="price">
    <label>Price, $ (range):</label>
    <input type="text" class="start wheel-ctrl field-float field-positive" name="search[start_price]" value="{search.start_price}" />&ndash;<input type="text" class="end wheel-ctrl field-float field-positive" name="search[end_price]" value="{search.end_price}" />
  </div>

  <div class="weight">
    <label>Wight, {config.General.weight_symbol} (range):</label>
    <input type="text" class="start wheel-ctrl field-integer field-positive" name="search[start_weight]" value="{search.start_weight}" />&ndash;<input type="text" class="end wheel-ctrl field-integer field-positive" name="search[end_weight]" value="{search.end_weight}" />
  </div>

  <div class="category">
    <label>Category:</label>
    <widget template="modules/AdvancedSearch/select_category.tpl" class="XLite_View_CategorySelect" fieldName="search[category]" allOption selectedCategoryId="{search.category}" />
  </div>

  <div class="buttons-row">
    <widget class="XLite_View_Button_Submit" label="Search" />
  </div>

<widget name="adsearch_form" end />

<script type="text/javascript">
<!--
new advancedSearchController($('.advanced-search-sidebar'));
-->
</script>

