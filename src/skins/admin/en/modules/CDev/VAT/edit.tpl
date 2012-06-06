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

<div class="edit-vat-tax">

  <div class="top-note">
    <div>
      {t(#After you enable this tax it will be included in product prices#)}<br />
      {t(#This tax is calculated based on customer's billing address#)}.
    </div>
  </div>

  <widget name="editForm" class="\XLite\Module\CDev\VAT\View\Form\EditTax" />

  <table class="form" cellspacing="0">

    <tr>
      <td class="label"><label for="tax-title">{t(#Tax title#)}:</label></td>
      <td class="star">*</td>
      <td><input type="text" name="name" value="{tax.getName()}" class="field-required" /></td>
      <td class="button {if:tax.getEnabled()}enabled{else:}disabled{end:}">
          <widget class="\XLite\View\Button\SwitchState" label="{t(#Tax enabled#)}" enabled="true" action="switch" />
          <widget class="\XLite\View\Button\SwitchState" label="{t(#Tax disabled#)}" enabled="false" action="switch" />
      </td>
    </tr>

  </table>

  <div class="vat-options">
    <div class="vat-options-block">
      <widget class="\XLite\View\FormField\Input\Checkbox" fieldName="display_prices_including_vat" isChecked="{config.CDev.VAT.display_prices_including_vat=#Y#}" label="{t(#Display prices in catalog including VAT#)}" />
    </div>
    <div class="vat-options-block">
      <widget class="\XLite\Module\CDev\VAT\View\FormField\LabelModeSelector" fieldName="display_inc_vat_label" value="{config.CDev.VAT.display_inc_vat_label}" label="{t(#Display 'inc/ex VAT' labels next to prices#)}" help="{t(#If this option is ticked all prices in the catalog will be shown with 'inc VAT' or 'ex VAT' label depending on whether included VAT into the price or not. If you choose do not display this label, you have to place information about it somewhere on the catalog pages as it must be clear for customers.#)}" />
    </div>
  </div>

  <div class="vat-base">
    <p>{t(#Product prices are defined including this tax calculated for#)}:</p>
    <div>
      <widget class="\XLite\Module\CDev\VAT\View\Taxes\MembershipSelector" field="vatMembership" value="{tax.getVATMembership()}" />
      <span>{t(#and#)}</span>
      <widget class="\XLite\Module\CDev\VAT\View\Taxes\ZoneSelector" field="vatZone" value="{tax.getVATZone()}" />
      <widget
        class="\XLite\View\Tooltip"
        id="vat-help-text"
        text="{t(#Select the membership level and area. for which product prices, including VAT, are defined by the shop administrator#):h}"
        caption=""
        isImageTag="true"
        className="help" />
    </div>
  </div>

  <h2>{t(#Rates / Conditions#)}</h2>

  <p class="rates-note">{t(#If the product is assigned to multiple classes, only the first tax rate with the highest priority will be applied to it.#)}</p>

  <table class="data{if:tax.rates.count()=0} empty-data{end:}">

    <tr class="head">
      <th class="position">{t(#Priority#)}</th>
      <th>{t(#Product class#)}</th>
      <th>{t(#User membership#)}</th>
      <th>{t(#Zone#)}</th>
      <th>{t(#Rate#)}</th>
    </tr>

    <tr FOREACH="tax.getRates(),rate">
      <td class="position"><input type="text" name="rates[{rate.getId()}][position]" value="{rate.getPosition()}" /></td>
      <td class="class"><widget class="\XLite\View\Taxes\ProductClassSelector" field="rates[{rate.getId()}][productClass]" value="{rate.getProductClass()}" /></td>
      <td class="membership"><widget class="\XLite\View\Taxes\MembershipSelector" field="rates[{rate.getId()}][membership]" value="{rate.getMembership()}" /></td>
      <td class="zone"><widget class="\XLite\View\Taxes\ZoneSelector" field="rates[{rate.getId()}][zone]" value="{rate.getZone()}" /></td>
      <td class="rate">
        <input type="text" name="rates[{rate.getId()}][value]" value="{rate.getValue()}" />
        <span class="percent">%</span>
        <img src="images/spacer.gif" class="separator" alt="" />
        <widget class="\XLite\View\Button\Regular" label="" style="rate-remove" jsCode="return false;" />
      </td>
    </tr>

    <tr class="new-template" style="display: none;">
      <td class="position"><input type="text" name="rates[%][position]" /></td>
      <td class="class"><widget class="\XLite\View\Taxes\ProductClassSelector" field="rates[%][productClass]" /></td>
      <td class="membership"><widget class="\XLite\View\Taxes\MembershipSelector" field="rates[%][membership]" /></td>
      <td class="zone"><widget class="\XLite\View\Taxes\ZoneSelector" field="rates[%][zone]" /></td>
      <td class="rate">
        <input type="text" name="rates[%][value]" />
        <span class="percent">%</span>
        <img src="images/spacer.gif" class="separator" alt="" />
        <widget class="\XLite\View\Button\Regular" label="" style="rate-remove" jsCode="return false;" />
      </td>
    </tr>

    <tr class="no-data-note">
      <td colspan="5">{t(#No tax rate defined#)}</td>
    </tr>

  </table>

  <div>
    <widget class="\XLite\View\Button\Regular" label="{t(#New rate#)}" style="new-rate" jsCode="return false;" />
  </div>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Save#)}" style="action" />
  </div>

  <widget name="editForm" end />

</div>
