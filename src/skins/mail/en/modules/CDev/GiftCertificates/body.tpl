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
<body IF="!gc.ecard_id">
Dear {gc.recipient},
<p>
{gc.purchaser} sent you a Gift Certificate for {price_format(gc.amount):h}
<p>
Message:<br>
{gc.formattedMessage:h}
<p>&nbsp;
<table border="1" cellspacing="0" cellpadding="5">
<tr><td>Gift Certificate ID: {gc.gcid}</td></tr>
</table>
<br>
In order to redeem this gift certificate please follow these steps:
<ol>
<li> Go to our site at <a href="{config.Company.company_website:r}">{config.Company.company_website}</a>
<li> Add to cart some products
<li> Click 'checkout'
<li> Enter your personal details
<li> Select 'Gift Certificate' as payment method
<li> Enter your Gift Certificate ID and click 'Submit order' button
</ol>
{signature:h}
</body>
<body IF="gc.ecard_id">
{gc.showECardBody()}
</body>
</html>
