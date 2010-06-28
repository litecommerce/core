{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Language selector for editor page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form action="admin.php" method="post" name="change_language_form" class="change-language">
  <input type="hidden" name="target" value="{getTarget()}">
  <input type="hidden" name="action" value="change_language">

  <select name="language" onchange="javascript: $(this.form).submit();">
    <option FOREACH="getLanguages(),code,language" value="{code}" selected="{isLanguageSelected(code)}">{language}</option>
  </select>
</form>
