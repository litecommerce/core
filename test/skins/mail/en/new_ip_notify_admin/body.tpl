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
<body>
<p>{config.Company.company_name}: Automated help-desk system

<p>You are receiving this e-mail message because you have tried to log in to admin back-end from a new IP address : {waiting_ip.ip}<br>
If you did not make a login attempt from this IP address, it might mean that somebody was trying to gain access to your account at {config.Company.company_name}.

<p>To confirm that this login attempt was made by you, click on the link below:<br>
<a href="{xlite.getShopUrl(#admin.php#)}?target=add_ip&mode=add&unique_key={waiting_ip.unique_key}">{xlite.getShopUrl(#admin.php#):h}?target=add_ip&mode=add&unique_key={waiting_ip.unique_key}</a>

<p>Alternatively, you can copy and paste the link URL into the 'Location' field of your browser.<br>
Once you confirm the login attempt, the IP address, from which you tried to log in, will be added to the list of allowed IP addresses.

<br>

<p>{signature:h}
</body>
</html>
