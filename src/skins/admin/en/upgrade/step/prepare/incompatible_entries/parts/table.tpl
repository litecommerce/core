{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Entries list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.step.prepare.incompatible_entries.sections", weight="200")
 *}

<table class="incompatible-modules-list">
  <tr FOREACH="getIncompatibleEntries(),entry">
    <list name="sections.table.columns" type="inherited" entry="{entry}" />
  </tr>
</table>
