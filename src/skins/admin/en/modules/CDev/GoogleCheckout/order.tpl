{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:validGoogleOrder}

{* Error Messages *}
<div IF="!valid">
<font class="ErrorMessage" IF="!error">There are errors in the form!</font>
<font class="ErrorMessage" IF="error">Error:</font>&nbsp;
<font class="ErrorMessage" IF="error=#message_send_failed#">Cannot instruct Google Checkout to post a message in the customer's Google Checkout account.</font>
<font class="ErrorMessage" IF="error=#order_archive_failed#">Cannot instruct Google Checkout to archive the order.</font>
<font class="ErrorMessage" IF="error=#order_unarchive_failed#">Cannot instruct Google Checkout to unarchive the order.</font>
<font class="ErrorMessage" IF="error=#order_process_failed#">Order processing failed.</font>
<font class="ErrorMessage" IF="error=#order_tracking_failed#">Adding order tracking data failed.</font>
<font class="ErrorMessage" IF="error=#order_deliver_failed#">Cannot instruct Google Checkout to set order's fulfillment state to DELIVERED.</font>
<font class="ErrorMessage" IF="error=#order_refund_failed#">Cannot instruct Google Checkout to refund the order.</font>
<font class="ErrorMessage" IF="error=#order_cancel_failed#">Cannot instruct Google Checkout to cancel the order.</font>
<font class="ErrorMessage" IF="error=#merchant_number_failed#">Cannot instruct Google Checkout to associate a merchant-assigned order number with the order.</font>
<font class="ErrorMessage" IF="error=#order_charge_failed#">Cannot instruct Google Checkout to charge the order.</font>
</div>
<div IF="errorMessage">
<font class="ErrorMessage">Reason:</font>&nbsp;
<font class="ErrorMessage">{errorMessage:h}</font>
</div>

{* Success Messages *}
<div IF="valid">
<font class="SuccessMessage" IF="success=#message_sent#">The message has been sent to the customer.</font>
<font class="SuccessMessage" IF="success=#order_archived#">Google Checkout has been instructed to archive the order.</font>
<font class="SuccessMessage" IF="success=#order_unarchived#">Google Checkout has been instructed to unarchive the order.</font>
<font class="SuccessMessage" IF="success=#order_processed#">Google Checkout has been instructed to process the order.</font>
<font class="SuccessMessage" IF="success=#order_add_tracking#">Order tracking data added.</font>
<font class="SuccessMessage" IF="success=#order_update_tracking#">Order tracking data updated.</font>
<font class="SuccessMessage" IF="success=#order_deliver#">Google Checkout has been instructed to set order's fulfillment state to DELIVERED.</font>
<font class="SuccessMessage" IF="success=#order_refund#">Google Checkout has been instructed to refund the order.</font>
<font class="SuccessMessage" IF="success=#order_cancel#">Google Checkout has been instructed to cancel the order.</font>
<font class="SuccessMessage" IF="success=#merchant_number#">Google Checkout has been instructed to associate a merchant-assigned order number with the order.</font>
<font class="SuccessMessage" IF="success=#order_charge#">Google Checkout has been instructed to charge the order.</font>
</div>

<script language="Javascript">
function submitAction(form, action)
{
	if (confirm("Are you sure?"))
	{
		form.action.value = action;
		form.submit();
	}
}
</script>

<br>

{* Google Checkout order details *}
<table border=0 cellpadding=3 cellspacing=2 width="100%">
	<tr>
		<td><widget template="modules/CDev/GoogleCheckout/separator.tpl" caption="Google Checkout order details"></td>
	</tr>
	<tr>
		<td><b>Google Checkout Order Id:</b> {order.google_id}</td>
	</tr>
	<tr>
		<td class="ErrorMessage" IF="order.google_status=#P#">Order partially refunded.</td>
		<td class="ErrorMessage" IF="order.google_status=#R#">Order refunded.</td>
		<td class="ErrorMessage" IF="order.google_status=#C#">Order canceled.</td>
	</tr>

	<tr>
		<td>
<table border=0>
	<tr>
		<td align="right" nowrap>Order total:</td>
		<td>&nbsp;</td>
		<td>{price_format(order.google_total):h}</td>
	</tr>
	<tr>
		<td align="right" nowrap>Total charged amount:</td>
		<td>&nbsp;</td>
		<td>{price_format(order.google_details.total_charge_amount):h}</td>
	</tr>
	<tr IF="order.google_details.refund_amount">
		<td align="right"><b>Refund amount</b>:</td>
		<td>&nbsp;</td>
		<td class="ErrorMessage">{price_format(order.google_details.refund_amount):h}</td>
	</tr>
	<tr>
		<td align="right" nowrap>Buyer Id:</td>
		<td>&nbsp;</td>
		<td>{order.google_details.buyer_id:h}</td>
	</tr>
	<tr>
		<td align="right">IP address:</td>
		<td>&nbsp;</td>
		<td>{order.google_details.ip_address:h}</td>
	</tr>
	<tr>
		<td align="right">Partial CC number:</td>
		<td>&nbsp;</td>
		<td>{order.google_details.partial_cc_number:h}</td>
	</tr>
	<tr>
		<td align="right">Buyer account age:</td>
		<td>&nbsp;</td>
		<td>{order.google_details.buyer_account_age:h} day(s)</td>
	</tr>
	<tr>
		<td align="right">Fulfillment state:</td>
		<td>&nbsp;</td>
		<td>{order.google_details.fulfillment_state:h}</td>
	</tr>
	<tr>
		<td align="right">Financial state:</td>
		<td>&nbsp;</td>
		<td>{order.google_details.financial_state:h}</td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td><b>Note</b>: Please be aware that Google Checkout takes some time to update an order status after you issue a status update request, so you should expect a delay before the updated status appears in the order details.</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>


{if:!googleOrderCanceled}

{if:googleAllowCharge}
{* Charge order *}
<form action="admin.php" method="POST" name="charge_form">
<input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="">

<table border=0 cellpadding=3 cellspacing=2 width="100%">
    <tr>
        <td><widget template="modules/CDev/GoogleCheckout/separator.tpl" caption="Charge order"></td>
    </tr>
	<tr>
		<td>This command instructs Google Checkout to charge the buyer for the order. After the order reaches the CHARGEABLE order state, you have seven days - 168 hours - to capture funds by issuing the 'Charge order' command.</td>
	</tr>
	<tr>
		<td>
<table border=0>
	<tr>
		<td nowrap align=right><b>Charge amount ($):</b></td>
		<td>&nbsp;</td>
		<td><input type="text" name="charge_amount" {if:charge_amount}value="{charge_amount}"{else:}value="{order.googleRemainCharge}"{end:}></td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="charge_amount"></td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td><input type="button" value="Charge order" OnClick="submitAction(charge_form, 'gcheckout_charge_order');"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
{end:}


{* Refund order *}
<form action="admin.php" method="POST" name="refund_cancel_form">
<input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="">

<table border=0 cellpadding=3 cellspacing=2 width="100%">
{if:googleAllowRefund}
	<tr>
		<td><widget template="modules/CDev/GoogleCheckout/separator.tpl" caption="Refund order"></td>
	</tr>
	<tr>
		<td>
			This command instructs Google Checkout to refund the buyer for the order. You may issue the 'Refund order' command after the order has been charged and is in the CHARGED financial order state.
			<br><br>
			<b>Note:</b> The 'Refund order' command will not affect the current state of the order in your store. If you wish the order state change caused by the refund to take effect on the side of the store, you will need to edit the order manually using AOM (Advanced Order Management) add-on module.
		</td>
	</tr>
	<tr>
		<td>
<table border=0>
	<tr>
		<td nowrap align=right><b>Refund amount ($):</b></td>
		<td>&nbsp;</td>
		<td><input type="text" name="refund_amount" value="{order.googleRemainRefund}"></td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="refund_amount"></td>
	</tr>
	<tr>
		<td nowrap align=right><b>Reason:</b></td>
		<td>&nbsp;</td>
		<td>
			<select name="refund_reason">
				<option value="">Select reason...</option>
				<option FOREACH="googleRefundReasons,v" value="{v:h}">{v:h}</option>
				<option value="Other">Other (describe below)</option>
			</select>
		</td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="refund_reason"></td>
	</tr>
	<tr>
		<td nowrap align=right><b>Comment:</b></td>
		<td>&nbsp;</td>
		<td><textarea name="refund_comment" rows=3 cols=70>{refund_comment:h}</textarea></td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="refund_comment"></td>
	</tr>
	<tr>
		<td colspan=3><input type="button" value="Refund order" OnClick="submitAction(refund_cancel_form, 'gcheckout_refund_order');"></td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
{end:}

{* Cancel order *}
{if:googleAllowCancel}
	<tr>
		<td><widget template="modules/CDev/GoogleCheckout/separator.tpl" caption="Cancel order"></td>
	</tr>
	<tr>
		<td>This command instructs Google Checkout to cancel the order. Use the fields below to specify a reason why you canceled the order and provide a comment.</td>
	</tr>
	<tr>
		<td>
<table border=0>
	<tr>
		<td nowrap align=right><b>Reason:</b></td>
		<td>&nbsp;</td>
		<td>
			<select name="cancel_reason">
				<option value="">Select reason...</option>
				<option FOREACH="googleCancelReasons,v" value="{v:h}">{v:h}</option>
				<option value="Other">Other (describe below)</option>
			</select>
		</td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="cancel_reason"></td>
	</tr>
	<tr>
		<td nowrap align=right><b>Comment:</b></td>
		<td>&nbsp;</td>
		<td><textarea name="cancel_comment" rows=3 cols=70>{cancel_comment:h}</textarea></td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="cancel_comment"></td>
	</tr>
	<tr>
		<td colspan=3><input type="button" value="Cancel order" OnClick="submitAction(refund_cancel_form, 'gcheckout_cancel_order');"></td>
	</tr>
</table>
		</td>
	</tr>
{end:}
</table>
</form>


{* Fulfillment commands *}
<form action="admin.php" method="POST" name="fulfillment_form">
<input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="">

<table border=0 cellpadding=3 cellspacing=2 width="100%">
	<tr>
		<td><widget template="modules/CDev/GoogleCheckout/separator.tpl" caption="Fulfillment commands"></td>
	</tr>
{if:googleAllowProcess}
	<tr>
		<td>The 'Process order' command instructs Google Checkout to update the order's fulfillment state from NEW to PROCESSING.</td>
	</tr>
	<tr>
		<td><input type="button" value="Process order" OnClick="submitAction(fulfillment_form, 'gcheckout_process_order');"></td>
	</tr>
{end:}
	<tr>
		<td>The <a href="http://checkout.google.com/seller/policies.html"><u>Google Checkout Program Policies and Guidelines</u></a> state that you must provide shipment tracking information for your orders as that information becomes available. This command does not impact the order's fulfillment state.</td>
	</tr>
    <tr>
        <td><b>Carrier</b>:
			<select name="google_carrier">
				<option FOREACH="googleCarriersList,v" value="{v}" selected="v=order.googleShippingCarrirer">{v:h}</option>
			</select>
			(Note: Allowed values for carrier are DHL, FedEx, UPS, USPS and Other)
		</td>
    </tr>
    <tr>
        <td><b>Tracking number</b>: <input type="text" name="tracking_no" value="{order.tracking:h}"></td>   
    </tr>
    <tr>
        <td><input type="button" {if:order.tracking}value="Update tracking data"{else:}value="Add tracking data"{end:} OnClick="submitAction(fulfillment_form, 'gcheckout_tracking_data');"></td>
    </tr>
{if:googleAllowDeliver}
	<tr>
		<td>The 'Deliver' command instructs Google Checkout to update the order's fulfillment state from either NEW or PROCESSING to DELIVERED. You may want to send this command after the order has been charged and shipped. The 'Add tracking data' command instructs Google Checkout to associate a shipper's tracking number with the order.</td>
	</tr>
	<tr>
		<td><input type="checkbox" name="deliver_email" value=1>Send e-mail to the customer</td>
	</tr>
	<tr>
		<td><input type="button" value="Deliver order" OnClick="submitAction(fulfillment_form, 'gcheckout_deliver_order');"></td>
	</tr>
{end:}
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</form>

{end:} {* //googleOrderCanceled *}


{* Send message *}
<form action="admin.php" method="POST" name="message_form">
<input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="">

<table border=0 cellpadding=3 cellspacing=2 width="100%">
	<tr>
		<td><widget template="modules/CDev/GoogleCheckout/separator.tpl" caption="Send message"></td>
	</tr>
	<tr>
		<td>This command instructs Google Checkout to place a message in the customer's Google Checkout account. It may also include an optional argument instructing Google Checkout to also send the message to the customer by email. This command does not impact the order's fulfillment state.</td>
	</tr>
	<tr>
		<td>
<table border=0>
	<tr>
		<td>Message:<br><textarea name="message" rows=7 cols=70>{message:h}</textarea></td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="message"></td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td><input type="checkbox" name="message_email" {if:message_email}checked{end:} value=1>Send e-mail to the customer</td>
	</tr>
	<tr>
		<td><input type="button" value="Send message" OnClick="submitAction(message_form, 'gcheckout_send_messge');"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</form>


{* Archiving commands *}
{if:googleAllowAcrhive}
<form action="admin.php" method="POST" name="archive_form">
<input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="">

<table border=0 cellpadding=3 cellspacing=2 width="100%">
	<tr>
		<td><widget template="modules/CDev/GoogleCheckout/separator.tpl" caption="Archiving commands"></td>
	</tr>
	<tr>
		<td>Archiving commands enable you to manage the list of orders in your Merchant Center inbox: the 'Archive order' command moves the order from the Merchant Center inbox to the Merchant Center archive; the 'Unarchive order' command restores the order from the archive back to the inbox. Archiving commands do not have any impact on the order's state or on the information that is communicated to the customer in connection with the order. It is recommended that you only archive orders after they have been delivered or canceled.</td>
	</tr>
	<tr IF="!order.google_details.google_archived">
		<td><input type="button" value="Archive order" OnClick="submitAction(archive_form, 'gcheckout_archive_order');"></td>
	</tr>
	<tr IF="order.google_details.google_archived">
		<td><input type="button" value="Unarchive order" OnClick="submitAction(archive_form, 'gcheckout_unarchive_order');"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
{end:}

</table>
{else:}
This GoogleCheckout order is not valid.
{end:}
