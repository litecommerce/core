{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Welcome page for logged admin
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="admin-welcome">

  <h1>{t(#Steps before launching your store#)}</h1>

  <div class="block-content">

  <div class="step-items">
    <ul>
      <li class="item-store">{t(#Specify your store information#)}</li>
      <li class="item-products">{t(#Add your products#)}</li>
      <li class="item-taxes">{t(#Setup address zones and taxes#)}</li>
      <li class="item-shipping">{t(#Configure shipping methods#)}</li>
      <li class="item-payment">{t(#Choose payment methods#)}</li>
      <li class="item-domain">{t(#Link your website domain name#)}</li>
      <li class="item-final">{t(#Start selling!#)}</li>
    </ul>
  </div>

  <div class="welcome-footer-bg"></div>

  <div class="welcome-footer">
    <div class="do-not-show">
      <input type="checkbox" name="doNotShowAtStartup" id="doNotShowAtStartup" />
      <label for="doNotShowAtStartup">{t(#Do not show at startup anymore#)}</label>
    </div>
    <div class="close-button">
      {t(#CLOSE#)}
    </div>
  </div>

  </div>

</div>

