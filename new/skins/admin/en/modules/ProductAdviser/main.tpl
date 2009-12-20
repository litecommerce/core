{if:mode=#process#}
<widget target="CustomerNotifications" template="common/dialog.tpl" body="modules/ProductAdviser/customer_notifications.tpl" head="Send Customer Notifications" IF="xlite.config.ProductAdviser.customer_notifications_enabled">
{else:}
<widget target="CustomerNotifications" template="common/dialog.tpl" body="modules/ProductAdviser/customer_notifications.tpl" head="Customer Notifications" IF="xlite.config.ProductAdviser.customer_notifications_enabled">
{end:}
