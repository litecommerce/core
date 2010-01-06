{* E-mail sent to admin after partner signs up successfully *}
<html>
<head><title>Sign in notification</title></head>
<body>
<p>New partner has been registered

<p align="left">Profile:

<p align="left">
<table border="0" cellspacing="0" cellpadding="2">

<!-- ********************** BILLING ADDRESS *************************** -->

<tr>
    <td colspan="2"><b>Billing information</b><br><hr size="1" noshade></td>
</tr>
<tr>
    <td align="right">Title:</td>
    <td>{profile.billing_title:h}</td>
</tr>
<tr>
    <td align="right">First Name:</td>
    <td>{profile.billing_firstname:h}</td>
</tr>
<tr>
    <td align="right">Last Name:</td>
    <td>{profile.billing_lastname:h}</td>
</tr>
<tr>
    <td align="right">Company:</td>
    <td>{profile.billing_company:h}</td>        
</tr>
<tr>
    <td align="right">Phone:</td>
    <td>{profile.billing_phone:h}</td>    
</tr>
<tr>
    <td align="right">Fax:</td>
    <td>{profile.billing_fax:h}</td>
</tr>
<tr>
    <td align="right">Address:</td>
    <td>{profile.billing_address:h}</td>
</tr>
<tr>
    <td align="right">City:</td>
    <td>{profile.billing_city:h}</td>
</tr>
<tr>
    <td align="right">State:</td>
    <td>{profile.billingState.state:h}</td>
</tr>
<tr>
    <td align="right">Country:</td>
    <td>{profile.billingCountry.country:h}</td>
</tr>
<tr>
    <td align="right">Zip code:</td>
    <td>{profile.billing_zipcode:h}</td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
</tr>

</table>

<p>{signature:h}
</body>
</html>
