{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Language selector for editor page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<widget class="XLite\View\Form\LanguageSelector" name="languageSelector" />

  <select name="language" onchange="javascript: jQuery(this.form).submit();">
    <option FOREACH="getLanguages(),code,language" value="{code:h}" selected="{isLanguageSelected(code)}">{language}</option>
  </select>

<widget name="languageSelector" end />
