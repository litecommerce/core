{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice billing address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.bottom.address", weight="20")
 *}
<td class="bill" IF="order.profile&order.profile.getBillingAddress()">
  <strong>{t(#Billing address#)}</strong>
  <p>
    {baddress.title} {baddress.firstname} {baddress.lastname}
  </p>

  <p>
    {baddress.street}<br />
    {baddress.city}, {baddress.state.state}, {baddress.zipcode}<br />
    {baddress.country.country}
  </p>

  <p IF="baddress.phone">
    {t(#Phone#)}: {baddress.phone}
  </p>

  <p>
    {t(#E-mail#)}: <a href="mailto:{order.profile.login}">{order.profile.login}</a>
  </p>

</td>
