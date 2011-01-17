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
<table width="80%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="CenterBorder">
            <table width="100%" cellspacing="1" cellpadding="2" border="0">
                <tr class="TableHead">
                    <th width="150px">Date</th>
                    <th>Logged as</th>
                </tr>
				<tbody IF="recentAdmins">
				<tr FOREACH="recentAdmins,recentAdmin" class="DialogBox">
					<td align="center">{formatTime(recentAdmin.last_login):h}</td>
					<td>{recentAdmin.login:h}</td>
				</tr>
			</table>	
		</td>
	</tr>
</table>
