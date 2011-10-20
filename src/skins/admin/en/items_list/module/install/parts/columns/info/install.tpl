{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="itemsList.module.install.columns.module-main-section", weight="200")
 *}

{* TODO refactor into FORM widgets *}

<div class="install-section" IF="canInstall(module)">

  <form action="admin.php" method="post" >
    <input type="hidden" name="target" value="addon_install" />
    <input type="hidden" name="action" value="view_license" />
    <input type="hidden" name="moduleId" value="{module.getModuleId()}" />

    <div class="install">
      {* :FIXME: widget must be removed; all functionality must be moved here *}
      <widget class="\XLite\View\Button\Addon\Install" moduleId="{module.getModuleId()}" />
    </div>
  </form>

</div>