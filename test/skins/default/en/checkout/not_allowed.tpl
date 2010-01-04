<p IF="cart.maxOrderAmountError">
In order to perform checkout your order subtotal must be less than {price_format(config.General.maximal_order_amount):h}
</p>
<p IF="cart.minOrderAmountError">
In order to perform checkout your order subtotal must be more than {price_format(config.General.minimal_order_amount):h}
</p>
<div>
<widget class="XLite_View_Button"label="Go back" href="javascript: history.go(-1)" font="FormButton">
</div>

