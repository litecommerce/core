{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div IF="hasResults()" class="widget items-list widgetclass-{getWidgetClass()} widgettarget-{getWidgetTarget()} sessioncell-{getSessionCell()}">

  {displayCommentedData(getItemsListParams())}

  <div IF="pager.isVisible()" class="pager pager-top {pager.getCSSClasses()}">{pager.display()}</div>

  <div IF="isHeaderVisible()" class="list-header"><list name="header" type="inherited" /></div>

  <widget template="{getPageBodyTemplate()}" />

  <div IF="pager.isVisibleBottom()" class="pager pager-bottom {pager.getCSSClasses()}">{pager.display()}</div>

  <div IF="isFooterVisible()" class="list-footer"><list name="footer" type="inherited" /></div>

</div>

<widget IF="isEmptyListTemplateVisible()" template="{getEmptyListTemplate()}" />