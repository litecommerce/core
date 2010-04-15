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
<head><title>Your Gift Certificate will expire soon!</title></head>
<body>
<p>Dear {cert.recipient},</p>

<p>you have received this message because your gift certificate {cert.gcid} will expire on {date_format(cert.expirationDate)}.</p>

<p>{signature:h}</p>
</body>
</html>
