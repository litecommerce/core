{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns", weight="70")
 *}
<td width="60%">
  <div class="description">
    {getInstalledProperty(module,#description#)}
  </div>
  <div class="module-url">
    <a href="{module.getPageURL()}" target="_blank">{t(#Visit add-on\'s page#)}</a>
  </div>
</td>

