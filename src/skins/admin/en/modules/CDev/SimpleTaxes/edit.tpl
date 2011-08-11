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
    {t(#After you enabled this tax it will be included in product prices#)}<br />
    {t(#This taxis calculated based on customer's billing address.#)}
  </div>

  <widget name="editForm" class="\XLite\Module\CDev\SimpleTaxes\View\Form\EditTax" />

  <div class="tax-title">
    <label for="tax-title">{t(#Tax title#)}:</label>
    <span class="required">*</span>
    <input type="text" name="name" value="{tax.getName()}" />
    {if:tax.getEnabled()}
      <widget class="\XLite\View\Button\Link" location="{buildURL(#taxes#,#switch#,_ARRAY_(#page#^page))}" label="{t(#Tax enabled#)}" style="tax-switcher enabled" />
    {else:}
      <widget class="\XLite\View\Button\Link" location="{buildURL(#taxes#,#switch#,_ARRAY_(#page#^page))}" label="{t(#Tax disabled#)}" style="tax-switcher disabled" />
    {end:}

  </div>

  <div IF="isVAT()" class="vat-base">
    <span>{t(#Product prices are defined including this tax calculated for#)}:</span>
    <div>
      <widget class="\XLite\Module\CDev\SimpleTaxes\View\MembershipSelector" field="vatMembership" value="{tax.getVATMembership()}" />
      <span>{t(#and#)}</span>
      <widget class="\XLite\Module\CDev\SimpleTaxes\View\ZoneSelector" field="vatZone" value="{tax.getVATZone()}" />
    </div>
  </div>

  <h2>{t(#Rates / Conditions#)}</h2>

  <table class="data">

    <tr>
      <th>{t(#Pos.#)}</th>
      <th>{t(#Product class#)}</th>
      <th>{t(#User membership#)}</th>
      <th>{t(#Zone#)}</th>
      <th>{t(#Rate#)}</th>
    </tr>

    <tr FOREACH="tax.getRates(),rate">
      <td class="position"><input type="text" name="rate[{rate.getId()}][position]" value="{rate.getPosition()}" /></td>
      <td class="class"><widget class="\XLite\Module\CDev\SimpleTaxes\View\ProductClassSelector" field="rate[{rate.getId()}][productClass]" value="{rate.getProductClass()}" /></td>
      <td class="membership"><widget class="\XLite\Module\CDev\SimpleTaxes\View\MembershipSelector" field="rate[{rate.getId()}][membership]" value="{rate.getMembership()}" /></td>
      <td class="zone"><widget class="\XLite\Module\CDev\SimpleTaxes\View\ZoneSelector" field="rate[{rate.getId()}][zone]" value="{rate.getZone()}" /></td>
      <td class="rate">
        <input type="text" name="rate[{rate.getId()}][value]" value="{rate.getValue()}" />
        <select name="rate[{rate.getId()}][type]">
          <option value="a"{if:rate.getType()=#a#} selected="selected"{end:}>%</option>
          <option value="p"{if:rate.getType()=#p#} selected="selected"{end:}>$</option>
        </select>
        <img src="images/spacer.gif" class="separator" alt="" />
        <widget class="\XLite\View\Button\Link" location="{buildURL(#taxes#,#removeRate#,_ARRAY_(#page#^page,#id#^rate.getId()))}" label="" style="rate-remove" />
      </td>
    </tr>

  </table>

  <widget class="\XLite\View\Button\Regular" label="{t(#New rate#)}" />

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Submit#)}" style="action" />
  </div>

  <widget name="editForm" end />

</div>
