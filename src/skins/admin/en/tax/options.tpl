{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Tax options
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
<!--
function showTaxMsg() 
{
	var tax_incl = document.getElementById('tax_included');
	var msg_text = document.getElementById('tax_message');
  if (msg_text && tax_incl) {
  	msg_text.style.display = 0 == tax_incl.selectedIndex ? 'none' : '';
  }
}
-->
</script>

<form action="admin.php" method="POST" name="taxes_form">
  <input type="hidden" name="target" value="taxes">
  <input type="hidden" name="action" value="update_options">

  <table cellspacing="1" cellpadding="3">

    <tr>
    	<td class=ProductDetails width="30%">Address to use for tax calculations:</td>
    	<td class=ProductDetails>
        <select name="use_billing_info">
    		  <option value="N" selected="{config.Taxes.use_billing_info=0}">Shipping info</option>
      		<option value="Y" selected="{config.Taxes.use_billing_info=1}">Billing info</option>
    		</select>
    	</td>
    </tr>

    <tr>
      <td>Taxes included in product prices:</td>
      <td>
        <select id="tax_included" name="prices_include_tax" onchange="javascript: showTaxMsg();">
          <option value="N" selected="{config.Taxes.prices_include_tax=0}">No</option>    
          <option value="Y" selected="{config.Taxes.prices_include_tax=1}">Yes</option>   
        </select>
      </td>
    </tr>

    <tbody id="tax_message">

      <tr IF="discountUsedForTaxes">
        <td>Discounts charged after taxes application:</td>
        <td>
          <select name="discounts_after_taxes">
            <option value="N" selected="{config.Taxes.discounts_after_taxes=0}">No</option>    
            <option value="Y" selected="{config.Taxes.discounts_after_taxes=1}">Yes</option>   
          </select>
        </td>
      </tr>

      <tr>
        <td>Message next to the product price when tax is included:</td>
        <td><input type="text" name="include_tax_message" value="{config.Taxes.include_tax_message:r}" /></td>
      </tr>

    </tbody>

  </table>

<script type="text/javascript">
<!--
showTaxMsg();
-->
</script>

  <h2>Taxes to display</h2>

  <div IF="!taxes._taxes">no taxes</div>
	<table IF="taxes._taxes" cellspacing="1" class="data-table">
   	<tr>
			<th>Pos.</th>
			<th>Tax name</th>
			<th>Display name</th>
			<th>Registration number</th>
			<th><input type="checkbox" class="column-selector" /></th>
		</tr>

		<tr FOREACH="taxes._taxes,ind,tax" class="{getRowClass(ind,##,#highlight#)}">
			<td><input type="text" name="data[{ind}][pos]" value="{getIndex(tax,ind)}" class="orderby" /></td>
			<td><input type="text" name="data[{ind}][name]" value="{getTaxName(tax):r}" class="field-required" /></td>
			<td><input type="text" name="data[{ind}][display_label]" value="{getDisplayName(tax):r}" class="field-requried" /></td>
			<td><input type="text" name="data[{ind}][registration]" value="{getRegistration(tax):r}" /></td>	
			<td><input type="checkbox" name="deleted[]" value="{ind}" /></td>
		</tr>
	</table>


  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="Update" />
    <widget class="\XLite\View\Button\DeleteSelected" action="delete_tax" confirm="You are about to delete selected taxes display. Are you sure you want to delete them?" />
  </div>

</form>

<form action="admin.php" method="POST" name="add_tax_form">
  <input type="hidden" name="target" value="taxes">
  <input type="hidden" name="action" value="add_tax">

  <h2>Add new tax</h2>

  <ul class="form">

    <li>
      <label for="new_pos">Position</label>
      <input type="text" id="new_pos" name="new[pos]" value="0" class="orderby" />
    </li>

    <li>
      <label for="tax_name">Tax name</label>
      <input type="text" id="tax_name" name="new[name]" value="" class="field-required" />
    </li>

    <li>
      <label for="tax_label">Display name</label>
      <input type="text" id="tax_label" name="new[display_label]" value="" class="field-required" />
    </li>

    <li>
      <label for="new_registration">Registration number</label>
			<input type="text" id="new_registration" name="new[registration]" value="" />
    </li>

  </ul>

  <widget class="\XLite\View\Button\Submit" label="Add" />

</form>
