{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Dependencies list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.columns.module-main-section.cannot_enable.dependencies", weight="200")
 * @ListChild (list="itemsList.module.install.columns.module-main-section.cannot_enable.dependencies", weight="200")
 *}

<ul>
  <li FOREACH="getDependencyModules(module),depend">
    <list name="details" type="nested" depend="{depend}" />
  </li>
</ul>
