{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<br /><br /><br />

<div class="AdminTitle">Test Australia Post rates calculation</div>

<br />

<form action="admin.php#test_" method="get" target="shipping_test">

  <input type="hidden" name="target" value="aupost" />
  <input type="hidden" name="action" value="test" />

  <table border="0" cellpadding="3" cellspacing="0">

    <tr>
      <td>Package weight (g):</td>
      <td IF="!weight=##"><input type="text" name="weight" size="10" value="{weight:r}" /></td>
      <td IF="weight=##"><input type="text" name="weight" size="10" value="100" /></td>
    </tr>

    <tr>
      <td colspan="2"><br /><b>Source address</b></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Country:</td> 
      <td>
        <input type="text" size="15" value="Australia" disabled />
        <span IF="!config.Company.location_country=#AU#"><font class="Star">(!)</font> <a href="admin.php?target=settings&page=Company"><u>Company country</u></a> has wrong value</span>
      </td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Postal Code:</td>
      <td IF="!sourceZipcode=##"><input type="text" name="sourceZipcode" size="10" value="{sourceZipcode:r}" /></td>
      <td IF="sourceZipcode=##"><input type="text" name="sourceZipcode" size="10" value="{config.Company.location_zipcode:r}" /></td>
    </tr>

    <tr>
      <td colspan="2"><br /><b>Destination address</b></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Country: {config.Shipping.anonymous_country}</td> 
      <td IF="!destinationCountry=##"><widget class="\XLite\View\CountrySelect" field="destinationCountry" value="{destinationCountry}" /></td>
      <td IF="destinationCountry=##"><widget class="\XLite\View\CountrySelect" field="destinationCountry" country="{config.Shipping.anonymous_country}" fieldId="destinationCountry_select" /></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Postal/ZIP Code:</td>
      <td IF="!sourceZipcode=##"><input type="text" name="destinationZipcode" size="10" value="{destinationZipcode:r}" /></td>
      <td IF="sourceZipcode=##"><input type="text" name="destinationZipcode" size="10" value="{config.Shipping.anonymous_zipcode:r}" /></td>
    </tr>

  </table>

  <br /><br />

  <input type="submit" value="Calculate rates" />

  <div>Note: a new window will open</div>

</form>

