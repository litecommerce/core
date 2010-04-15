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
{foreach:order.items,item}

SPL{delimiter}INVOICE{delimiter}{date_format(order,#date#,config.AccountingPackage.qb_date_format)}{delimiter}Sales:Product{delimiter}{order.profile.billing_lastname}, {order.profile.billing_firstname} - ID# {if:order.origProfile}{order.origProfile.profile_id}{else:}{order.profile.profile_id}{end:}{delimiter}Website:Retail{delimiter}-{item.total}{delimiter}{order.order_id}{delimiter}#{item.product_id}: {item.name:h}{delimiter}{item.price}{delimiter}-{item.amount}{delimiter}{item.sku:h}{delimiter}N{delimiter}

{end:}
