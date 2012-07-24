{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Body
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<html>
<body>
<b>{t(#Name#)}:</b> {data.name}<br />
<b>{t(#E-mail#)}:</b> {data.email}<br />
<b>{t(#Subject#)}:</b> {data.subject}<br />
<p>
{data.message:nl2br}
</body>
</html>
