{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.columns.module-main-section", weight="40")
 *}

<div IF="isModuleUpdateAvailable(module)" class="note version upgrade">
  {t(#Version#)}:&nbsp;{getModuleVersion(getModuleForUpdate(module))}&nbsp;{t(#is available#)}<br />
  <a href="{buildURL(#upgrade#,##,_ARRAY_(#mode#^#install_updates#))}">{t(#Update module#)}</a>
</div>
