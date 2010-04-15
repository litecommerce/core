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

<p>You are receiving this e-mail message because some of the .htaccess files were missing or failed the security files verification procedure. The list of these files is included:</p>
<br />
<br />
{foreach:errors,error}
{error.file} : [{error.error}]<br />
{end:}
<br />
<p>For more information on security files verification procedure consult the LiteCommerce reference manual.</p>

<p>{signature:h}
</body>
</html>
