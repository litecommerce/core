{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice shipping address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.bottom.address", weight="10")
 *}
<td class="ship" IF="saddress&getShippingModifier()&shippingModifier.getMethod()">
  <strong>{t(#Shipping address#)}</strong>
  <p>
    {saddress.title} {saddress.firstname} {saddress.lastname}
  </p>

  <p>
    {saddress.street}<br />
    {saddress.city}, {saddress.state.state}, {saddress.zipcode}<br />
    {saddress.country.country}
  </p>

  <p IF="saddress.phone" class="last">
    {t(#Phone#)}: {saddress.phone}
  </p>
</td>
