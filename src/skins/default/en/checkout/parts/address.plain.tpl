{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Plain address block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="address-box">
  <div class="name">{address.name}</div>
  <div class="address">
    {address.street}<br />
    {address.city}, {address.state.state}, {address.zipcode}<br />
    {address.country.country}<br />
  </div>
  <div class="phone" IF="address.phone">{t(#Phone#)}: {address.phone}</div>
</div>
