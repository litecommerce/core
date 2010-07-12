{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form action="{buildUrl(#advanced_search#)}" method="POST" name="adsearch_form" id="adsearch_form">
  <input type="hidden" name="mode" value="found">

  <div>
    <input type="text" size="48" id="search_substring" name="search[substring]" value="{search.substring}">
  </div>

  <div>
    <label for="search_logic">Containing:</label>
    <select id="search_logic" name="search[logic]">
      <option value="1" selected="{isSelected(search.logic,#1#)}">Any of these words (OR)</option>
      <option value="2" selected="{isSelected(search.logic,#2#)}">All of these words (AND)</option>
      <option value="3" selected="{isSelected(search.logic,#3#)}">The exact phrase</option>
    </select>
  </div>

  <div>
    <strong>Search in:</strong>

    <input type="checkbox" id="search_title" name="search[title]" checked="{search.title}" /><label for="search_title">Title</label>
    <br />

    <input type="checkbox" id="search_brief_description" name="search[brief_description]" checked="{search.brief_description}" /><label for="search_brief_description">Description</label>
    <br />
    
    <input type="checkbox" id="search_description" name="search[description]" checked="{search.description}" /><label for="search_description">Full description</label>
    <br />

    <input type="checkbox" id="search_meta_tags" name="search[meta_tags]" checked="{search.meta_tags}" /><label for="search_meta_tags">Meta tags</label>
    <br />

    <input type="checkbox" id="search_extra_fields" name="search[extra_fields]" checked="{search.extra_fields}" /><label for="search_extra_fields">Extra fields</label>
    <br />

    {if:xlite.ProductOptionsEnabled}
      <input type="checkbox" id="search_options" name="search[options]" checked="{search.options}" /><label for="search_options">Product options</label>
    {end:}
  </div>

  <div>
    <label>Category:</label>
    <widget template="modules/AdvancedSearch/select_category.tpl" class="\XLite\View\CategorySelect" fieldName="search[category]" allOption search="{search}">
    <br />

    <input type="checkbox" id="search_subcategories" name="search[subcategories]" checked="{search.subcategories}" /><label for="search_subcategories">search in subcategories</label>
  </div>

  <div>
    <label for="search_sku">SKU:</label>
    <input type="text" size="12" id="search_sku" name="search[sku]" value="{search.sku}" />
  </div>

  <div IF="prices">
    <label for="search_price">Price:</label>
    <select id="search_price" name="search[price]">
      <option value="">Select range</option>
      <option FOREACH="prices,v" value="{v.start:h},{v.end:h}" selected="{isSelected(search.price,strcat(v.start,v.end,#,#))}">
        {preparePriceOption(v)}
      </option>
    </select>
  </div>

  <div If="weights">
    <label for="search_weight">Weight:</label>
    <select id="search_weight" name="search[weight]">
      <option value="">Select range</option>
      <option FOREACH="weights,v" value="{v.start:h},{v.end:h}" selected="{isSelected(search.weight,strcat(v.start,v.end,#,#))}">
        {prepareWeightOption(v)}
      </option>
    </select>
  </div>

  <widget class="\XLite\View\Button" label="Search" type="button">

</form>
