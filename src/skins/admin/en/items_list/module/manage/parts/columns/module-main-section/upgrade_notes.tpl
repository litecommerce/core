{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns.module-main-section", weight="40")
 *}
<div IF="isModuleUpgradeAvailable(module)" class="note version upgrade">
  {t(#A new version is available#)}<br />
  <widget class="\XLite\View\Button\Regular" label="Upgrade" /> {t(#to v.#)} {getMaxModuleVersion(module)}
</div>
