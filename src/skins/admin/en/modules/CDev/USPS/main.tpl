{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * USPS module settings main page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<b>U.S.P.S.</b> module allows to use online shipping rates calculation via <a href="http://www.usps.com/webtools">USPS Web Tools Ecommerce API</a>.

<br /><br />

<widget template="modules/CDev/USPS/config.tpl" />

<widget template="modules/CDev/USPS/test.tpl" IF="config.CDev.USPS.userid" />
