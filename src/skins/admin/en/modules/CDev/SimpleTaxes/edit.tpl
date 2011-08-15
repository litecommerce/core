{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Tax edit page
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="edit-tax">

  <div IF="isVAT()" class="top-note">
    <div>
      {t(#After you enabled this tax it will be included in product prices#)}<br />
      {t(#This taxis calculated based on customer's billing address.#)}
    </div>
  </div>

  <widget name="editForm" class="\XLite\Module\CDev\SimpleTaxes\View\Form\EditTax" />

  <table class="form" cellspacing="0">

    <tr>
      <td class="label"><label for="tax-title">{t(#Tax title#)}:</label></td>
      <td class="star">*</td>
      <td><input type="text" name="name" value="{tax.getName()}" /></td>
      <td class="button">
        {if:tax.getEnabled()}
          <widget class="\XLite\Module\CDev\SimpleTaxes\View\Button\SwitchTax" label="{t(#Tax enabled#)}" style="tax-switcher enabled" />
        {else:}
          <widget class="\XLite\Module\CDev\SimpleTaxes\View\Button\SwitchTax" label="{t(#Tax disabled#)}" style="tax-switcher disabled" />
        {end:}
      </td>
    </tr>

  </table>

  <div IF="isVAT()" class="vat-base">
    <p>{t(#Product prices are defined including this tax calculated for#)}:</p>
    <div>
      <widget class="\XLite\Module\CDev\SimpleTaxes\View\MembershipSelector" field="vatMembership" value="{tax.getVATMembership()}" />
      <span>{t(#and#)}</span>
      <widget class="\XLite\Module\CDev\SimpleTaxes\View\ZoneSelector" field="vatZone" value="{tax.getVATZone()}" />
      <widget
        class="\XLite\View\Tooltip"
        id="vat-help-text"
        text="{t(#!!!#):h}"
        caption=""
        isImageTag="true"
        className="help" />
    </div>
  </div>

  <h2>{t(#Rates / Conditions#)}</h2>

  <p class="rates-note">{t(#If the product is assigned to multiple classes only the first tax rate with highest priority will be applied on it.#)}</p>

  <table class="data">

    <tr>
      <th class="position">{t(#Priority#)}</th>
      <th>{t(#Product class#)}</th>
      <th>{t(#User membership#)}</th>
      <th>{t(#Zone#)}</th>
      <th>{t(#Rate#)}</th>
    </tr>

    <tr FOREACH="tax.getRates(),rate">
      <td class="position"><input type="text" name="rates[{rate.getId()}][position]" value="{rate.getPosition()}" /></td>
      <td class="class"><widget class="\XLite\Module\CDev\SimpleTaxes\View\ProductClassSelector" field="rates[{rate.getId()}][productClass]" value="{rate.getProductClass()}" /></td>
      <td class="membership"><widget class="\XLite\Module\CDev\SimpleTaxes\View\MembershipSelector" field="rates[{rate.getId()}][membership]" value="{rate.getMembership()}" /></td>
      <td class="zone"><widget class="\XLite\Module\CDev\SimpleTaxes\View\ZoneSelector" field="rates[{rate.getId()}][zone]" value="{rate.getZone()}" /></td>
      <td class="rate">
        <input type="text" name="rates[{rate.getId()}][value]" value="{rate.getValue()}" />
        <span class="percent">%</span>
        <img src="images/spacer.gif" class="separator" alt="" />
        <widget class="\XLite\View\Button\Regular" label="" style="rate-remove" jsCode="return false;" />
      </td>
    </tr>

    <tr class="new-template" style="display: none;">
      <td class="position"><input type="text" name="rates[%][position]" /></td>
      <td class="class"><widget class="\XLite\Module\CDev\SimpleTaxes\View\ProductClassSelector" field="rates[%][productClass]" /></td>
      <td class="membership"><widget class="\XLite\Module\CDev\SimpleTaxes\View\MembershipSelector" field="rates[%][membership]" /></td>
      <td class="zone"><widget class="\XLite\Module\CDev\SimpleTaxes\View\ZoneSelector" field="rates[%][zone]" /></td>
      <td class="rate">
        <input type="text" name="rates[%][value]" />
        <span class="percent">%</span>
        <img src="images/spacer.gif" class="separator" alt="" />
        <widget class="\XLite\View\Button\Regular" label="" style="rate-remove" jsCode="return false;" />
      </td>
    </tr>

  </table>

  <widget class="\XLite\View\Button\Regular" label="{t(#New rate#)}" style="new-rate" jsCode="return false;" />

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Save#)}" style="action" />
  </div>

  <widget name="editForm" end />

</div>
