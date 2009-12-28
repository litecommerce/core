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
					<td align="center">{time_format(recentAdmin.last_login):h}</td>
					<td>{recentAdmin.login:h}</td>
				</tr>
			</table>	
		</td>
	</tr>
</table>
