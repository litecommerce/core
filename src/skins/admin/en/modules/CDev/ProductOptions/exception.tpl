{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modify exception
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<ul>
  <li FOREACH="getGroups(),group">
    <label for="exception_{eid}_{group.getGroupId()}">{group.getName()}</label>
    <select name="exceptions[{eid}][{group.getGroupId()}]" id="exception_{eid}_{group.getGroupId()}">
      <option value="" selected="{isNotPartException(exception,group)}">{t(#None#)}</option>
      <option FOREACH="group.getOptions(),option" value="{option.getOptionId()}" selected="isOptionSelected(exception,option)">{option.getName()}</option>
    </select>
  </li>
</ul>
