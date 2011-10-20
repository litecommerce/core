{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Entry new version
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.ready_to_install.entries_list.sections.table.columns.error", weight="400")
 *}

<td class="error-messages" colspan="3">
  <div class="error-message-block">
    <div class="error-message" FOREACH="messages,message">
      {message}
    </div>
  </div>
</td>