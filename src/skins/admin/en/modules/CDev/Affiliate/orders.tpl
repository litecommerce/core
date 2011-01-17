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
{foreach:sales,sidx,sale}
{sale.order.order_id}{delimiter}{formatDate(sale.order.date)}{delimiter}{sale.partner.login:h}{delimiter}{sale.partner.billing_firstname:h}{delimiter}{sale.partner.billing_lastname:h}{delimiter}{sale.partner.billing_address:h}{delimiter}{sale.partner.billing_city:h}{delimiter}{sale.partner.billingState.state:h}{delimiter}{sale.partner.billingCountry.country:h}{delimiter}{sale.order.subtotal}{delimiter}{sale.commissions}{delimiter}<widget template="common/order_status.tpl" order="{sale.order}">{delimiter}{if:sale.paid=#0#}Pending{else:}Paid{end:}{crlf}
{end:}
