{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.sections", weight="400")
 *}

<widget class="\XLite\View\Form\Module\Manage" name="modules_manage_form" />

  <table cellspacing="0" cellpadding="0" class="data-table items-list modules-list">

    <tr FOREACH="getPageData(),idx,module" class="module-{module.getModuleId()}{if:!module.getEnabled()} disabled{end:}">
      <list name="columns" type="inherited" module="{module}" />
    </tr>

  </table>

  <div class="buttons modules-buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Save changes#)}" style="action" />
  </div>

<widget name="modules_manage_form" end />
