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
<html>
<head><title>Referral order</title></head>
<body>
<p>Dear {partner.login:h}!</p>

<p>An order has been placed at {config.Company.company_name:h}. This order resulted from a referral from you.</p>

<p>Order id# {payment.order_id:r}</p>

<p>You will receive a payout of {price_format(payment,#commissions#):h} for this order.</p>

<p>{signature:h}</p>
</body>
</html>
