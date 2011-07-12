{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Hard reset
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.completed.backup.actions", weight="200")
 * @ListChild (list="upgrade.step.ready_to_install.create_backup.actions", weight="200")
 *}

<div class="upgrade-note hard-reset">
  {t(#Disable all modules in the system#)} (<a href="{getHardResetURL()}">{t(#hard reset#)}</a>)
</div>
