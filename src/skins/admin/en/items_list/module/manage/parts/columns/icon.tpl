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
 * @ListChild (list="itemsList.module.manage.columns", weight="30")
 *}
<td class="icon" width="90">
  <a name="{module.getName()}"></a>
  <div class="icon-container">

    <div IF="!module.getEnabled()" class="addon-disabled">
      <img src="images/spacer.gif" width="48" height="48" alt="" />
    </div>

    <div class="module-icon">
      {if:module.hasIcon()}
        <img src="{module.getIconURL()}" width="48" height="48" border="0" />
      {else:}
        <img src="images/addon_default.png" width="48" height="48" border="0" />
      {end:}
    </div>

 </div>
</td>

