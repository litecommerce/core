{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<html>
<head><title>{t(#Sign in notification#)}</title></head>
<body>

<p>{t(#Thank you for registering at LiteCommerce#,_ARRAY_(#LiteCommerce#^config.Company.company_name))}!</p>

{if:password}
  <p>{t(#Your account email is X.#,_ARRAY_(#email#^profile.getLogin()))}</p>
  <p>{t(#Your account password is X.#,_ARRAY_(#password#^password))}</p>
{end:}

<p>{signature:h}</p>

</body>
</html>
