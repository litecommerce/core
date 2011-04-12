{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.module.install.header", weight="100")
 *}

<div class="addons-filters">

  <div class="features">
    <ul>
      {foreach:getSortOptions(),fltr,desc}
        <li class="{if:fltr=getSortBy()}hl{end:}"><a href="{buildURL(#addons_list#,##,_ARRAY_(%static::PARAM_SORT_BY%^fltr))}">{t(desc)}</a></li>
      {end:}
    </ul>
  </div>

  <div class="price-filter">

    {* :TODO: move JS to controller *}
    <select name="priceFilter" onchange="javascript: location.replace(this.value);">
      <option FOREACH="getPriceFilterOptions(),name,label" value="{getActionURL(_ARRAY_(#priceFilter#^name))}" selected="{isSelected(getParam(#priceFilter#),name)}">{t(label)}</option>
    </select>

<script type="text/javascript">
<!--
jQuery(document).ready(function(){
  jQuery('select[name=priceFilter]').selectmenu({style:'popup'});
});
-->
</script>

  </div>

  <div class="clear"></div>

</div>
