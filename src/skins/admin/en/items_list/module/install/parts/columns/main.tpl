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
 * @ListChild (list="itemsList.module.install.columns", weight="50")
 *}

<td width="40%" valign="top">
  <div class="name">{module.getModuleName()}</div>
  <div class="version">{t(#Version#)}: {module.getVersion()}</div>
  <div IF="module.getInstalled()" class="installed">{t(#Already installed#)}</div>
  <div class="price-info">
    <span class="price-value" IF="module.isFree()">{t(#Free#)}</span>
    <span class="price-value" IF="!module.isFree()">{formatPrice(module.getPrice())}</span>
    <span IF="module.getPurchased()" class="purchased">({t(#Purchased#)})</span>
    <div class="install" IF="canInstall(module)">
      <widget class="\XLite\View\Button\Submit" label="{t(#Install#)}" />
    </div>
    <div class="purchase" IF="canPurchase(module)">
      <widget class="\XLite\View\Button\Submit" label="{t(#Purchase#)}" />
      <span class="enter-license">{t(#or#)} <a href="#">{t(#enter license key#)}</a></span>
    </div>
  </div>
    
  <div IF="module.isUpdateAvailable()" class="upgrade-note">
    {t(#You have an outdated version#)}
    <br />
    <widget class="\XLite\View\Button\Submit" label="{t(#Upgrade#)}" /> {t(#to v.#)}{module.getLastVersion()}
  </div>

</td>
