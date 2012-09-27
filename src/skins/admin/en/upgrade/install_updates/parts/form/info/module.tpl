{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Upgrade entry icon
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.install_updates.sections.form.info", weight="200")
 *}

<li class="module" IF="isModule(entry)">
  <ul class="details">
  <list name="sections.form.info.module" type="inherited" entry="{entry}" />
  </ul>
</li>
