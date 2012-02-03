{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<div IF="hasResults()" class="items-list widgetclass-{getWidgetClass()} widgettarget-{getWidgetTarget()} sessioncell-{getSessionCell()}">

  <div IF="pager.isVisible()" class="pager-top {pager.getCSSClasses()}">{pager.display()}</div>

  <div IF="isHeaderVisible()" class="list-header">{displayInheritedViewListContent(#header#)}</div>

  <widget template="{getPageBodyTemplate()}" />

  <div IF="pager.isVisibleBottom()" class="pager-bottom {pager.getCSSClasses()}">{pager.display()}</div>

  <div IF="isFooterVisible()" class="list-footer">{displayInheritedViewListContent(#footer#)}</div>

</div>

<widget IF="isEmptyListTemplateVisible()" template="{getEmptyListTemplate()}" />

<script type="text/javascript">
//<![CDATA[
  new ItemsList('{getSessionCell()}', {getURLParamsJS():h}, {getURLAJAXParamsJS():h});
//]]>
</script>
