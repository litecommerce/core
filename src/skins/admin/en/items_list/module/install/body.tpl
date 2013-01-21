{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<table cellspacing="0" cellpadding="0" class="data-table items-list modules-list">

  <tr FOREACH="getPageData(),idx,module" class="{getModuleClassesCSS(module)}">
    <list name="columns" type="inherited" module="{getModuleFromMarketplace(module)}" />
  </tr>

</table>
