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
<html>
<head><title>Sign in notification</title></head>
<body>
<p>Dear {profile.login}!

<p>You have been registered successfully

<p align="left">Your profile:

<p align="left">
<table border="0" cellspacing="0" cellpadding="2">

<!-- ********************** BILLING ADDRESS *************************** -->

<tr>
    <td colspan="2"><b>Billing Address</b><br><hr size="1" noshade></td>
</tr>
<tr>
    <td align="right">Title:</td>
    <td>{profile.billing_title}</td>
</tr>
<tr>
    <td align="right">First Name:</td>
    <td>{profile.billing_firstname}</td>
</tr>
<tr>
    <td align="right">Last Name:</td>
    <td>{profile.billing_lastname}</td>
</tr>
<tr>
    <td align="right">Company:</td>
    <td>{profile.billing_company}</td>        
</tr>
<tr>
    <td align="right">Phone:</td>
    <td>{profile.billing_phone}</td>    
</tr>
<tr>
    <td align="right">Fax:</td>
    <td>{profile.billing_fax}</td>
</tr>
<tr>
    <td align="right">Address:</td>
    <td>{profile.billing_address}</td>
</tr>
<tr>
    <td align="right">City:</td>
    <td>{profile.billing_city}</td>
</tr>
<tr>
    <td align="right">State:</td>
    <td>{profile.billingState.state}</td>
</tr>
<tr>
    <td align="right">Country:</td>
    <td>{profile.billingCountry.country}</td>
</tr>
<tr>
    <td align="right">Zip code:</td>
    <td>{profile.billing_zipcode}</td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
</tr>

<!-- ********************* SHIPPING ADDRESS ************************** -->


<tr>
    <td colspan="2"><b>Shipping Address</b><br><hr size="1" noshade></td>
</tr>
<tr>
    <td align="right">Title:</td>
    <td>{profile.shipping_title}</td>
</tr>
<tr>
    <td align="right">First Name:</td>
    <td>{profile.shipping_firstname}</td>
</tr>
<tr>
    <td align="right">Last Name:</td>
    <td>{profile.shipping_lastname}</td>
</tr>
<tr>
    <td align="right">Company:</td>
    <td>{profile.shipping_company}</td>        
</tr>
<tr>
    <td align="right">Phone:</td>
    <td>{profile.shipping_phone}</td>    
</tr>
<tr>
    <td align="right">Fax:</td>
    <td>{profile.shipping_fax}</td>
</tr>
<tr>
    <td align="right">Address:</td>
    <td>{profile.shipping_address}</td>
</tr>
<tr>
    <td align="right">City:</td>
    <td>{profile.shipping_city}</td>
</tr>
<tr>
    <td align="right">State:</td>
    <td>{profile.shippingState.state}</td>
</tr>
<tr>
    <td align="right">Country:</td>
    <td>{profile.shippingCountry.country}</td>
</tr>
<tr>
    <td align="right">Zip code:</td>
    <td>{profile.shipping_zipcode}</td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
</tr>

<tbody IF="config.Memberships.memberships">
<tr>
    <td colspan="2"><b>Signup for membership</b><br><hr size="1" noshade></td>
</tr>
<tr>
    <td align="right">Sign-up membership:</td>
    <td>{profile.pending_membership}</td>
</tr>
<tr>
    <td align="right">Granted membership:</td>
    <td>{profile.membership}</td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile={profile}>
</tbody>

</table>

<p>{signature:h}
</body>
</html>
