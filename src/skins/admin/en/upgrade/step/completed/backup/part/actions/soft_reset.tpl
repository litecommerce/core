{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Soft reset
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.step.completed.backup.actions", weight="100")
 * @ListChild (list="upgrade.step.ready_to_install.create_backup.actions", weight="100")
 *}

<div class="upgrade-note soft-reset">
  {t(#Disable suspicious modules#)} (<a href="{getSoftResetURL()}">{t(#soft reset#)}</a>)
</div>
