{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Table model list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div {getContainerAttributesAsString():h}>
  {displayCommentedData(getItemsListParams())}
  <div IF="isHeaderVisible()" class="list-header">
    <div FOREACH="getTopActions(),tpl" class="button-container"><widget template="{tpl:h}" /></div>
    <list name="header" type="inherited" />
  </div>

  <widget IF="isPageBodyVisible()" template="{getPageBodyTemplate()}" />
  <widget IF="!isPageBodyVisible()" template="{getEmptyListTemplate()}" />

  <div IF="isPagerVisible()" class="table-pager">{pager.display()}</div>

  <div IF="isFooterVisible()" class="list-footer">
    <div FOREACH="getBottomActions(),tpl" class="button-container"><widget template="{tpl:h}" /></div>
    <list name="footer" type="inherited" />
  </div>

</div>

<widget IF="isPanelVisible()" class="{getPanelClass()}" />
