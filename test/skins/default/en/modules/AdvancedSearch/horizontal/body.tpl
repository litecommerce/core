{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Advanced search form (horizontal)
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
<!--
function ClearAllFilters()
{
  var filters = ["search_substring", "search_sku", "search_price", "search_weight", "search_logic", "search_subcategories", "search_title", "search_description", "search_brief_description","search_category", "search_meta_tags", "search_extra_fields", "search_options"];
  for (var i = 0; i < filters.length; i++) {
    var Element = document.getElementById(filters[i]);
    if (Element) {
      switch (filters[i]) {
        case "search_sku":
        case "search_substring":
          Element.value = "";
          break;

        case "search_title":
        case "search_description":
        case "search_brief_description":
        case "search_meta_tags":
        case "search_extra_fields":
        case "search_options":
        case "search_subcategories":
          Element.checked = true;
          break;

        case "search_logic":
        case "search_price":
        case "search_weight":
        case "search_category":
          Element.selectedIndex = 0;
          break;
      }
    }
  }
}

function SaveFilters()
{
  var form = document.getElementById('adsearch_form');
  if (form) {
    form.mode.value = '';
    formaction.value = 'save_filters';
    form.submit();
  }
}
-->
</script>

<form action="{buildUrl(#advanced_search#)}" method="POST" name="adsearch_form" id="adsearch_form">
  <input type="hidden" name="mode" value="found">
  <input type="hidden" name="action" value="">

  <table cellpadding="0" cellspacing="10" width="600">

    <tr>
      <td width="100px">Search for:</td>
      <td colspan="3"><input type="text" size="48" id="search_substring" name="search[substring]" value="{search.substring}"></td>
      <td width="100px"><widget class="XLite_View_Button_Submit" label="Search" /></td>
    </tr>  

    <tr>
      <td>Containing:</td>
      <td align="left" colspan="3">
        <select id="search_logic" name="search[logic]">
          <option value="1" selected="{isSelected(search.logic,#1#)}">Any of these words (OR)</option>
          <option value="2" selected="{isSelected(search.logic,#2#)}">All of these words (AND)</option>
          <option value="3" selected="{isSelected(search.logic,#3#)}">The exact phrase</option>
        </select>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>

  <table cellpadding="0" cellspacing="10" width="600">

    <tr>
      <td class="TopTab" colspan="5">Advanced search options:</td>
    </tr>

    <tr>
      <td width="100px">Search in:</td>
      <td align="left"><input type="checkbox" id="search_title" name="search[title]" checked="{search.title}" onClick="this.blur()">Title</td>
      <td align="center"><input type="checkbox" id="search_brief_description" name="search[brief_description]" checked="{search.brief_description}" onClick="this.blur()"> Description</td> 
      <td align="right"><input type="checkbox" id="search_description" name="search[description]" checked="{search.description}" onClick="this.blur()"> Full description </td>
      <td width="100px">&nbsp;</td>
    </tr>   

    <tr>
      <td width="100px">&nbsp;</td>
      <td align="left"><input type="checkbox" id="search_meta_tags" name="search[meta_tags]" checked="{search.meta_tags}" onClick="this.blur()">Meta tags</td>
      <td align="center"><input type="checkbox" id="search_extra_fields" name="search[extra_fields]" checked="{search.extra_fields}" onClick="this.blur()">Extra fields</td>
      <td align="right">{if:xlite.ProductOptionsEnabled}<input type="checkbox" id="search_options" name="search[options]" checked="{search.options}" onClick="this.blur()"> Product options{else:}&nbsp;{end:}</td>
      <td width="100px">&nbsp;</td>
    </tr>   

    <tr>
      <td>Category:</td>
      <td colspan="3"> <widget template="modules/AdvancedSearch/select_category.tpl" class="XLite_View_CategorySelect" fieldName="search[category]" allOption search="{search}"></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td colspan="3"><input type="checkbox" id="search_subcategories" name="search[subcategories]" checked="{search.subcategories}" onClick="this.blur()"> search in subcategories</td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td>SKU:</td>
      <td colspan="3"><input type="text" size="12" id="search_sku" name="search[sku]" value="{search.sku}"></td>
      <td>&nbsp;</td>
    </tr>  

    <tr>
      <td>Price:</td>
      <td colspan="4">
        <input type="text" class="start" name="search[start_price]" value="{search.start_price}" />&ndash;<input type="text" class="end" name="search[end_price]" value="{search.end_price}" />
      </td>
    </tr> 

    <tr>
      <td>Weight:</td>
      <td colspan="4">
        <input type="text" class="start" name="search[start_weight]" value="{search.start_weight}" />&ndash;<input type="text" class="end" name="search[end_weight]" value="{search.end_weight}" />
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td><widget class="XLite_View_Button_Submit" label="Search" /></td>
      <td valign="middle" nowrap>
        <a href="javascript: ClearAllFilters();" onclick="javascript: this.blur();"><img src="images/go.gif" width="13" height="13" alt=""><strong>&nbsp;Clear settings</strong></a>
      </td>
      <td valign="middle" nowrap colspan="2">
        <a if="{auth.logged}" href="javascript: void(SaveFilters());" onclick="javascript: this.blur();"><img src="images/go.gif" width="13" height="13" alt=""><strong>&nbsp;Save settings</strong></a>
      </td>
    </tr>

  </table>

</form>
