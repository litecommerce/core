{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Column with checkboxes
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.profile.search.columns", weight="10")
 *}

<td class="profileid table-label">
  <widget
    class="XLite\View\FormField\Input\UserProfileId"
    fieldOnly=true
    fieldName="profile_id"
    value="{profile.profile_id}"
    isChecked="{isSelected(id,#0#)}" />
</td>
