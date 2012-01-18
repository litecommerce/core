{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Table model list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div IF="hasResults()" class="{getContainerClass()}">

  <div IF="isHeaderVisible()" class="list-header">
    <div FOREACH="getTopActions(),tpl" class="button-container"><widget template="{tpl:h}" /></div>
    {displayInheritedViewListContent(#header#)}
  </div>

  <widget template="{getPageBodyTemplate()}" />

  <div IF="pager.isVisibleBottom()" class="table-pager">{pager.display()}</div>

  <div IF="isFooterVisible()" class="list-footer">
    <div FOREACH="getBottomActions(),tpl" class="button-container"><widget template="{tpl:h}" /></div>
    {displayInheritedViewListContent(#footer#)}
  </div>

</div>

<widget IF="isEmptyListTemplateVisible()" template="{getEmptyListTemplate()}" />

<script type="text/javascript">
//<![CDATA[
jQuery().ready(
  function() {
    new TableItemsList('{getSessionCell()}', {getURLParamsJS():h}, {getURLAJAXParamsJS():h});
  }
);
//]]>
</script>
