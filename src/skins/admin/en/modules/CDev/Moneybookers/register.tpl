{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Language selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="payment.methods.body", zone="admin", weight="150")
 *}
<p IF="!config.CDev.Moneybookers.email" class="mb-register-note">{t(#If you don't have moneybookers account yet, please sign up for the free moneybookers account at: http://www.moneybookers.com#):h}</p>
