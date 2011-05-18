{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.module.manage.columns.icon", weight="300")
 * @ListChild (list="itemsList.module.install.columns.icon", weight="300")
 *}

<div IF="!module.getEnabled()" class="addon-icon addon-disabled-note">
  {t(#Disabled#)}
</div>
