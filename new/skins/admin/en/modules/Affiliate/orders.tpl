{foreach:sales,sidx,sale}
{sale.order.order_id}{delimiter}{date_format(sale.order.date)}{delimiter}{sale.partner.login:h}{delimiter}{sale.partner.billing_firstname:h}{delimiter}{sale.partner.billing_lastname:h}{delimiter}{sale.partner.billing_address:h}{delimiter}{sale.partner.billing_city:h}{delimiter}{sale.partner.billingState.state:h}{delimiter}{sale.partner.billingCountry.country:h}{delimiter}{sale.order.subtotal}{delimiter}{sale.commissions}{delimiter}<widget template="common/order_status.tpl" order="{sale.order}">{delimiter}{if:sale.paid=#0#}Pending{else:}Paid{end:}{crlf}
{end:}
