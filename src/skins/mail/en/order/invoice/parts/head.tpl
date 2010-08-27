{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice head
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.base", weight="10")
 *}
<table cellspacing="0" class="invoice-header">
  <tr>
    <td class="left"><img src="images/invoice_logo.png" alt="{config.Company.company_name}" class="logo" /></td>
    <td class="right">
      <strong>{config.Company.company_name}</strong>
      <p>
        {config.Company.location_address}<br />
        {config.Company.location_city}, {config.Company.locationState.state}, {config.Company.location_zipcode}<br />
        {config.Company.location_country}
      </p>
      <p IF="config.Company.company_phone|config.Company.company_fax">
        {if:config.Company.company_phone}Phone: {config.Company.company_phone}<br />{end:}
        {if:config.Company.company_fax}Fax: {config.Company.company_fax}{end:}
      </p>
      <p IF="config.Company.company_website">
        <a href="{config.Company.company_website}">{config.Company.company_website}</a>
      </p>
    </td>
  </tr>
</table>
