{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Date type selector
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.profile.search.search_form", weight="800")
 *}

<tr>
  <td>{t(#Search for users that are#)}</td>
  <td>
    <select name="date_type">
      <option value=""{if:getParam(#date_type#)=##} selected="selected"{end:}>{t(#Please select one#)}...</option>
      <option value="R"{if:getParam(#date_type#)=#R#} selected="selected"{end:}>{t(#Registered#)}</option>
      <option value="L"{if:getParam(#date_type#)=#L#} selected="selected"{end:}>{t(#Last logged in#)}</option>
    </select>
  </td>
</tr>
