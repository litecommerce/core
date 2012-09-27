{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice : header : address box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.head", weight="10")
 *}
<td class="address">
  <strong>{config.Company.company_name}</strong>
  <p>
    {if:config.Company.location_address}{config.Company.location_address}<br />{end:}
    {if:config.Company.location_city}{config.Company.location_city}, {end:}{if:config.Company.locationState.state}{config.Company.locationState.state}, {end:}{if:config.Company.location_zipcode}{config.Company.location_zipcode}{end:}<br />
    {if:config.Company.locationCountry}{config.Company.locationCountry.getCountry()}{end:}
  </p>
  <p IF="config.Company.company_phone|config.Company.company_fax">
    {if:config.Company.company_phone}{t(#Phone#)}: {config.Company.company_phone}<br />{end:}
    {if:config.Company.company_fax}{t(#Fax#)}: {config.Company.company_fax}{end:}
  </p>
  <p IF="config.Company.company_website" class="url">
    <a href="{config.Company.company_website}">{config.Company.company_website}</a>
  </p>
</td>
