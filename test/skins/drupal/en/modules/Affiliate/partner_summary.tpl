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
<p>This section reflects general statistics.</p>

<br>

<table border=0 cellpadding=3 cellspacing=1>
<tr><td>Total sales:</td><td align=right>{sales.total}</td></tr>
<tr><td>Total unapproved sales:</td><td align=right>{sales.queued}</td></tr>
<tr><td>Pending sales commissions:</td><td align=right>{price_format(sales.pending):h}</td></tr>
<tr><td>Approved sales commissions:</td><td align=right>{price_format(sales.approved):h}</td></tr>
<tr><td>Paid sales commissions:</td><td align=right>{price_format(sales.paid):h}</td></tr>
</table>
