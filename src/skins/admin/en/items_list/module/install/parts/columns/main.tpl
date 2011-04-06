{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{* TODO : to REMOVE *}

<td style="width:40%;" valign="top">

  <div class="name">{module.getModuleName()}</div>

  <div class="version">{t(#Version#)}: {module.getVersion()}</div>

  <div IF="module.getInstalled()" class="installed">{t(#Already installed#)}</div>

  <div class="price-info">

    <span class="price-value" IF="module.isFree()">{t(#Free#)}</span>
    <span class="price-value" IF="!module.isFree()">{formatPrice(module.getPrice())}</span>
    <span IF="module.isPurchased()" class="purchased">({t(#Purchased#)})</span>

    <form action="admin.php" method="post">
      <input type="hidden" name="target" value="module_installation" />
      <input type="hidden" name="action" value="get_license" />
      <input type="hidden" name="module_id" value="{module.getModuleId()}" />

      <div class="install" IF="canInstall(module)">
        <widget class="\XLite\View\Button\InstallAddon" moduleId="{module.getModuleId()}" />
      </div>

      <div class="purchase" IF="canPurchase(module)">
        <widget class="\XLite\View\Button\Submit" label="{t(#Purchase#)}" />
      </div>

    </form>

  </div>
    
</td>
