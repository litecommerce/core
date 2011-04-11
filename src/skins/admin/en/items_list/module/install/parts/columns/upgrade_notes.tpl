{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.module.install.columns.module-main-section", weight="40")
 *}

<div IF="isModuleUpdateAvailable(module)" class="note version upgrade">
  {t(#Installed version#)}:&nbsp;{module.getVersion()}&nbsp;({t(#outdated#)})<br />
  <a href="#">{t(#Update module#)}</a>
</div>
