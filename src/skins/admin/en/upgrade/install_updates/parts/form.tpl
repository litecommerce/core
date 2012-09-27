{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Updates list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.install_updates.sections", weight="200")
 *}

<form action="admin.php" method="post">

  <input type="hidden" name="target" value="upgrade">

  <div class="update-module-list-frame">

    <div class="upgrade-button">
      <widget class="\XLite\View\Upgrade\SelectCoreVersion\Button" />
    </div>

    <ul class="update-module-list">

      <li class="update-module-info" FOREACH="getUpgradeEntries(),entry">
        <list name="sections.form" type="inherited" entry="{entry}" />
      </li>

    </ul>

    <div class="clear"></div>

    <widget class="\XLite\View\Button\Submit" label="{t(#Install updates#)}" style="center main-button" />

  </div>

</form>
