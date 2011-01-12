{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<h2 IF="mode=#search#">{pager.itemsTotal} results found{if:getParam(#substring#)} for the "{getParam(#substring#)}" keyword{end:}</h2>
<h2 IF="!mode=#search#">Featured add-ons</h2>

<div class="addons-filters">

  <div class="features">
    <ul>
      {foreach:getSortOptions(),fltr,desc}
        <li class="{if:fltr=getSortOption()}hl{end:}"><a href="{buildUrl(#addons_list#,##,_ARRAY_(%static::PARAM_SORT_BY%^fltr,#mode#^mode))}">{t(desc)}</a></li>
      {end:}
    </ul>
  </div>

  <div class="price-filter" IF="mode=#search#">

    <form action="admin.php" method="post" name="pricefilterform">
      <input type="hidden" name="target" value="addons_list" />
      <input type="hidden" name="action" value="search" />

      <select name="priceFilter" onchange="javascript:this.form.submit();">
        <option value="all">{t(#No price filtering#)}</option>
        <option value="free"{if:getCondition(#priceFilter#)=#free#} selected="selected"{end:}>{t(#Free add-ons#)}</option>
        <option value="paid"{if:getCondition(#priceFilter#)=#paid#} selected="selected"{end:}>{t(#Commercial add-ons#)}</option>
      </select>

<script type="text/javascript">
<!--
jQuery(document).ready(function(){
  jQuery('select[name=priceFilter]').selectmenu({style:'popup'});
});
-->
</script>

    </form>
  </div>

  <div class="clear"></div>

</div>

<table cellspacing="0" cellpadding="0" class="items-list data-table modules-list">

  <tr FOREACH="getPageData(),idx,module" class="{getRowClass(idx,##,#TableRow#)}">
    {displayListPart(#columns#,_ARRAY_(#module#^module))}
  </tr>

</table>
