{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * User type
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.profile.search.search_form", weight="200")
 *}

<tr>
  <td>{t(#User type#)}</td>
  <td>
    <select name="user_type">
      <option value=""{if:!getParam(#user_type#)} selected="selected"{end:}>{t(#All#)}</option>
      <option value="A"{if:getParam(#user_type#)=#A#} selected="selected"{end:}>{t(#Administrator#)}</option>
      <option value="C"{if:getParam(#user_type#)=#C#} selected="selected"{end:}>{t(#Non-administrator#)}</option>
    </select>
  </td>
</tr>
