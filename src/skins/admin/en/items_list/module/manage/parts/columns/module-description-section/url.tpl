{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main description section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.columns.module-description-section", weight="30")
 * @ListChild (list="itemsList.module.install.columns.module-description-section", weight="30")
 *}

<div class="module-url" IF="module.getPageURL()">
  <a href="{module.getPageURL()}" target="_blank">{t(#Visit add-on's page#)}</a>
</div>
