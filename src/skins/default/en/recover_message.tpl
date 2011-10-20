{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Password recovery message
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<p IF="link_mailed">{t(#The confirmation URL link was mailed to email#,_ARRAY_(#email#^email)_}</p>
<p IF="!link_mailed">{t(#The email with your account information was mailed to email#,_ARRAY_(#email#^email)_}</p>
<br />
