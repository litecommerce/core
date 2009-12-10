{foreach:payments,payment}
{payment.partner.login}{delimiter}{payment.partner.billing_firstname} {payment.partner.billing_lastname}{delimiter}{payment.paid}{delimiter}{payment.approved}{delimiter}{payment.pending}{delimiter}{payment.min_limit}{crlf}
{end:}
