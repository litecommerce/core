{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Advanced search
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="XLite_Module_AdvancedSearch_View_Form_Search" name="adsearch_form" className="advanced-search" />

  <table cellspacing="0" class="form-table">

    <tr class="substring">
      <td class="row-title" rowspan="2">Search for:</td>
      <td class="search-query"><input type="text" name="search[substring]" value="{search.substring}" /></td>
      <td class="top-button"><widget class="XLite_View_Button_Submit" label="Search" /></td>
    </tr> 

    <tr>
      <td colspan="2" class="substring-options">

        <input type="radio" name="search[logic]" value="2" id="search_logic_2" checked="{isSelected(search.logic,#2#)}" />
        <label for="search_logic_2">All words</label>

        <input type="radio" name="search[logic]" value="1" id="search_logic_1" checked="{isSelected(search.logic,#1#)}" />
        <label for="search_logic_1">Any word</label>

        <input type="radio" name="search[logic]" value="3" id="search_logic_3" checked="{isSelected(search.logic,#3#)}" />
        <label for="search_logic_3">Exact phrase</label>

      </td>
    </tr>

    <tr>
      <td colspan="3"><hr /></td>
    </tr>

    <tr>
      <td class="row-title" rowspan="2">Search in:</td>
      <td class="substring-options" colspan="2">

        <input type="checkbox" id="search_title" name="search[title]" checked="{search.title}" />
        <label for="search_title">Title</label>

        <input type="checkbox" id="search_brief_description" name="search[brief_description]" checked="{search.brief_description}" />
        <label for="search_brief_description">Description</label>

      </td>
    </tr>

    <tr>
      <td class="substring-options" colspan="2">
        
        <input type="checkbox" id="search_meta_tags" name="search[meta_tags]" checked="{search.meta_tags}" />
        <label for="search_meta_tags">Meta tags</label>

        <input type="checkbox" id="search_extra_fields" name="search[extra_fields]" checked="{search.extra_fields}" />
        <label for="search_extra_fields">Extra fields</label>

        {if:xlite.ProductOptionsEnabled}
        <input type="checkbox" id="search_options" name="search[options]" checked="{search.options}" />
        <label for="search_options">Product options</label>
        {end:}
      </td>
    </tr>   

    <tr class="separator">
      <td class="row-title" rowspan="2">Category:</td>
      <td colspan="2">
        <widget template="modules/AdvancedSearch/select_category.tpl" class="XLite_View_CategorySelect" fieldName="search[category]" allOption selectedCategoryId="{search.category}" />
      </td>
    </tr>

    <tr>
      <td class="substring-options" colspan="2">
        <input type="checkbox" id="search_subcategories" name="search[subcategories]" checked="{search.subcategories}" />
        <label for="search_subcategories">search in subcategories</label>
      </td>
    </tr>

    <tr class="separator">
      <td class="row-title">SKU:</td>
      <td colspan="2"><input type="text" name="search[sku]" value="{search.sku}" /></td>
    </tr>  

    <tr class="price">
      <td class="row-title">Price, $ (range):</td>
      <td colspan="2">
        <input type="text" class="start wheel-ctrl field-float field-positive" name="search[start_price]" value="{search.start_price}" />&ndash;<input type="text" class="end wheel-ctrl field-float field-positive" name="search[end_price]" value="{search.end_price}" />
      </td>
    </tr> 

    <tr class="weight">
      <td class="row-title">Weight, {config.General.weight_symbol} (range):</td>
      <td colspan="2">
        <input type="text" class="start wheel-ctrl field-integer field-positive" name="search[start_weight]" value="{search.start_weight}" />&ndash;<input type="text" class="end wheel-ctrl field-integer field-positive" name="search[end_weight]" value="{search.end_weight}" />
      </td>
    </tr>

    <tr class="buttons-row">
      <td><a href="javascript:void(0);" class="reset">Reset</a></td>
      <td colspan="2"><widget class="XLite_View_Button_Submit" label="Search" /></td>
    </tr>

  </table>

<widget name="adsearch_form" end />

<script type="text/javascript">
<!--
new advancedSearchController($('.advanced-search'));
-->
</script>
