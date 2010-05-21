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

<p>You are receiving this e-mail message because you have requested to recover your forgotten password.<br>
If you did not submit such a request, it might mean that somebody was trying to gain access to your account at {config.Company.company_name}.

<p>To confirm that this request was submitted by you, click on the link below:<br>
<a href="{url}">{url:h}</a>

<p>Alternatively, you can copy and paste the link URL into the 'Location' field of your browser.<br>
Once you confirm the request, an e-mail message containing your new password will be sent to you.

<br>

<p>{signature:h}
</body>
</html>
