{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<p align="justify">This section displays order placement statistics for today, this week and this month.</p>

<br /><br />

<table class="data-table" width="80%">
    <tr class=TableHead bgcolor=#ffffff>
        <th align=left>Status</th>
        <th>Today</th>
        <th>This week</th>
        <th>This month</th>
    </tr>
    <tr class="dialog-box">
        <td>Processed</td>
        <td align=center>{stat.processed.today}</td>
        <td align=center>{stat.processed.week}</td>
        <td align=center>{stat.processed.month}</td>
    </tr>
    <tr class="highlight">
        <td>Queued</td>
        <td align=center>{stat.queued.today}</td>
        <td align=center>{stat.queued.week}</td>
        <td align=center>{stat.queued.month}</td>
    </tr>
    <tr class="dialog-box">
        <td>Failed/Declined</td>
        <td align=center>{stat.failed.today}</td>
        <td align=center>{stat.failed.week}</td>
        <td align=center>{stat.failed.month}</td>
    </tr>
    <tr class="highlight">
        <td>Incomplete</td>
        <td align=center>{stat.not_finished.today}</td>
        <td align=center>{stat.not_finished.week}</td>
        <td align=center>{stat.not_finished.month}</td>
    </tr>
    <tr class="dialog-box">
        <td align=right><b>Gross total:</b></td>
        <td align=center>{price_format(stat.total.today):h}</td>
        <td align=center>{price_format(stat.total.week):h}</td>
        <td align=center>{price_format(stat.total.month):h}</td>
    </tr>
    <tr class="dialog-box">
        <td align=right><b>Payments received:</b></td>
        <td align=center>{price_format(stat.paid.today):h}</td>
        <td align=center>{price_format(stat.paid.week):h}</td>
        <td align=center>{price_format(stat.paid.month):h}</td>
    </tr>
    </table>

<br /><br />
<widget class="\XLite\View\Button\Regular" label="Perform order search" jsCode="self.location='admin.php?target=order_list';" />
