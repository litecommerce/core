{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Notify me box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="\XLite\Module\ProductAdviser\View\Form\Product\NotifyMe" name="notify_me" product="{getProduct()}" className="notify-me" />

  <table cellspacing="0" class="form-table">

    <tr>
      <td>Your e-mail:</td>
      <td>
        <input type="text" size="30" name="email" value="{email}" />
        <widget class="\XLite\Validator\EmailValidator" field="email" />
      </td>
    </tr>

    <tr>
      <td>Your name:</td>
      <td>
        <input type="text" size="50" name="person_info" value="{auth.profile.billing_title} {auth.profile.billing_firstname} {auth.profile.billing_lastname}" />
        <span class="optional-label">optional</span>
      </td>
    </tr>

  </table>

  <div class="button-row">
    <widget class="\XLite\View\Button\Submit" label="Notify me" />
  </div>

<widget name="notify_me" end />
