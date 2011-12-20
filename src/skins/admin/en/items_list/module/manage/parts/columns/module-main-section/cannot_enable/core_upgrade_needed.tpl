{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Core upgrade needed" note
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *
 * @ListChild (list="itemsList.module.manage.columns.module-main-section.cannot_enable", weight="200")
 * @ListChild (list="itemsList.module.install.columns.module-main-section.cannot_enable", weight="200")
 *}

<div IF="isCoreUpgradeNeeded(module)" class="note version error">
  {displayNestedViewListContent(#core_upgrade_needed#,_ARRAY_(#module#^module))}
</div>
