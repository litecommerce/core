{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Pick address from address book
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="\XLite\View\Form\SelectAddress" name="selectAddress" className="select-address" />

  {if:hasAddresses()}

    <ul class="addresses">
      <li FOREACH="getAddresses(),address" class="{getItemClassName(address,addressArrayPointer)}">
        <widget template="checkout/parts/address.plain.tpl" address="{address}" />
        <div IF="address.getIsShipping()" class="shipping"></div>
        <div IF="address.getIsBilling()" class="billing"></div>
      </li>
    </ul>

  {else:}

    <p>{t(#Addresses list is empty#)}</p>

  {end:}

  <!--div class="goto">
    <a href="{buildURL(#address_book#)}">{t(#Go to the address book#)}</a>
  </div-->

<widget name="selectAddress" end />
