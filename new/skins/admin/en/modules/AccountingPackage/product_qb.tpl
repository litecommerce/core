{* Ordered product details *}
{foreach:order.items,item}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Product{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}-{item.total}{delimiter}{order.order_id}{delimiter}#{item.product_id}: {item.name:h}{delimiter}{item.price}{delimiter}-{item.amount}{delimiter}{item.sku:h}{delimiter}N{delimiter}

{end:}
