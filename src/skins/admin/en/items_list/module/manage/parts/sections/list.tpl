{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * 
 * @ListChild (list="itemsList.module.manage.sections", weight="400")
 *}

<widget class="\XLite\View\Form\Module\Manage" name="modules_manage_form" />

  <table cellspacing="0" cellpadding="0" class="data-table items-list modules-list">

    <tr FOREACH="getPageData(),idx,module" class="module-{module.getModuleId()}{if:!module.getEnabled()} disabled{end:}">
      {displayInheritedViewListContent(#columns#,_ARRAY_(#module#^module))}
    </tr>

  </table>

  <div class="buttons modules-buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Save changes#)}" style="action" />
  </div>

<widget name="modules_manage_form" end />
