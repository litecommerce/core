{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Core version select popup
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<form action="admin.php" method="post">
  <input type="hidden" name="target" value="upgrade" />
  <input type="hidden" name="action" value="select_core_version" />

  <div class="upgrade-core-frame">
    <list name="upgrade.selector-version.details" />
  </div>

</form>
