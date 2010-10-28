{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Anonymous profile block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="\XLite\View\Form\Checkout\UpdateProfile" name="createProfile" className="create" />

  <div class="create">
    <h3>{t(#A new customer? Enter your e-mail#)}:</h3>

    {displayViewListContent(#checkout.profile.create#)}

  </div>

  <div class="or"><span>{t(#or#)}</span></div>

  <div class="login">
    <h3>{t(#Already have an account?#)}</h3>
    <widget class="\XLite\View\Button\Link" label="Login here" location="{getLoginURL()}" />
  </div>

<widget name="createProfile" end />
