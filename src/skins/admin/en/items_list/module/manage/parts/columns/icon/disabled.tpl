{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.columns.icon", weight="100")
 * @ListChild (list="itemsList.module.install.columns.icon", weight="100")
 *}

<div IF="!isEnabled(module)" class="addon-disabled">
  <img IF="isInstalled(module)" src="images/spacer.gif" class="disabled" alt="{t(#Disabled#)}" />
</div>
