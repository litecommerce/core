{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modify exception
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul>
  <li FOREACH="getGroups(),group">
    <label for="exception_{eid}_{group.getGroupId()}">{group.getName()}</label>
    <select name="exceptions[{eid}][{group.getGroupId()}]" id="exception_{eid}_{group.getGroupId()}">
      <option value="" selected="{isNotPartException(exception,group)}">None</option>
      <option FOREACH="group.getOptions(),option" value="{option.getOptionId()}" selected="isOptionSelected(exception,option)">{option.getName()}</option>
    </select>
  </li>
</ul>
