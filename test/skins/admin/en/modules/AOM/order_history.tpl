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
<script>
    function VisibleBox(key)
    {
        var open = document.getElementById("open_" + key);
		var close = document.getElementById("close_" + key);
        if (open.style.display == "") {
            open.style.display = "none";
            close.style.display = "";
        } else {
            open.style.display = "";
            close.style.display = "none";
        }
	}

	function clearCCinfo()
	{
		if (confirm("Are you sure you want to remove Credit Card info from order history?")) {
			document.order_history.submit();
		}
	}
</script>

<div IF="order.orderHistory">
<form action="admin.php" method="POST" name="order_history">
<input type="hidden" name="target" value="order">
<input type="hidden" name="action" value="clear_history_cc_info">
<input type="hidden" name="order_id" value="{order.order_id}">
<input type="button" name="clear_cc_info" value="Clear Credit Card info" OnClick="clearCCinfo();">
</form>
<p>
</div>

			<table border="0" width="100%" cellspacing="1" cellpadding="3">
				<tr class="TableHead">
					<th>Date</th>
					<th>Changed by</th>
					<th>Details</th>
				</tr>	
				<tbody IF="order.orderHistory">
				<tr class="{getRowClass(#0#,#TableRow#)}" FOREACH="order.orderHistory,key,history">
					<td valign="top" width="120" nowrap>{time_format(history.date)}</td>
					<td valign="top" width="120" nowrap>{history.login}</td>
					<td id="open_details{key}" onClick="VisibleBox('details{key}')"><b>Details</b> <img src="images/modules/AOM/close.gif">
						<table IF="history.changes">
							<tr IF="history.changes.order.created"> 	
								<td>Order #{history.changes.order.created} is created</td>
							</tr>
                            <tr IF="history.changes.order.cloned">
                                <td>Order is cloned (original order is <u>#<a href="admin.php?target=order&order_id={history.changes.order.cloned}">{history.changes.order.cloned}</a></u>)</td>
                            </tr>							
                            <tr IF="history.changes.order.split">
                                <td>{if:order.order_id=history.changes.order.split.parent}Order splitted into order #<u><a href="admin.php?target=order&order_id={history.changes.order.split.child}">{history.changes.order.split.child}</a></u>{else:}Order splitted off from order #<u><a href="admin.php?target=order&order_id={history.changes.order.split.parent}">{history.changes.order.split.parent}</a></u>{end:}</td>
	                        </tr>
							<tr IF="history.changes.items">
								<td>
									<table IF="history.changes.items.deleted">
										<tr>
											<td><b>Items deleted: </b></td>
										</tr>	
										<tr FOREACH="history.changes.items.deleted,name"><td>&nbsp;{name:h}</td></tr>			
									</table>		
                                    <table IF="history.changes.items.added">
                                        <tr>
                                            <td><b>Items added: </b></td>
                                        </tr>   
                                        <tr FOREACH="history.changes.items.added,name"><td>&nbsp;{name:h}</td></tr>           
                                    </table> 
                                    <table IF="history.changes.items.updated" width="100%">
                                        <tr>
                                            <td colspan="3"><b>Items updated: </b></td>
                                        </tr>
										<tbody IF="history.changes.items.updated.price">
										<tr>
											<td colspan="3"><b>Price: </b></td>			
										</tr>	
                                        <tr>
                                            <td>Product</td>         
											<td>Old</td>	
											<td>New</td>
                                        </tr>
										</tbody>
                                        <tr FOREACH="history.changes.items.updated.price,item">
											<td FOREACH="item,name,value">{if:name=#name#}{value:h}{else:}{price_format(value):h}{end:}</td>
										</tr>
										<tbody IF="history.changes.items.updated.amount">
                                        <tr IF="history.changes.items.updated.amount">
                                            <td colspan="3"><b>Amount: </b></td>         
                                        </tr>   
										<tr>
                                            <td>Product</td>
                                            <td>Old</td>   
                                            <td>New</td>
                                        </tr>
                                        </tbody>

                                        <tr FOREACH="history.changes.items.updated.amount,item">
                                            <td FOREACH="item,name,value" align="right">{value:h}</td>
                                        </tr>
                                    </table> 
								</td>
							</tr>
                            <tr IF="history.changes.changedStatus&!isSelected(history.changes.status,history.changes.changedStatus)">
                                <td><b>Status changed: </b> {if:history.changes.status}{history.changes.status}{else:}[blank]{end:} => {history.changes.changedStatus}</td>
                            </tr>
                            <tr IF="!isSelected(history.changes.admin_notes,history.changes.changedAdmin_notes)">
                                <td><b>Admin notes changed: </b>{if:history.changes.admin_notes}{history.changes.admin_notes:h}{else:}[blank]{end:} => {if:history.changes.changedAdmin_notes}{history.changes.changedAdmin_notes:h}{else:}[blank]{end:}</td>
                            </tr>
                            <tr IF="!isSelected(history.changes.notes,history.changes.changedNotes)">
                                <td><b>Customer notes changed: </b>{if:history.changes.notes}{history.changes.notes:h}{else:}[blank]{end:} => {if:history.changes.changedNotes}{history.changes.changedNotes:h}{else:}[blank]{end:}</td>
                            </tr>
                            <tr IF="history.changes.changedDetails">
                                <td>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td colspan="2"><b>Details changed: </b></td>	
										</tr>
										{foreach:history.changes.details,dkey,detail}
											{foreach:history.changes.changedDetails,cdkey,changedDetail}
												{if:dkey=cdkey}
												<tr>
													<td>{getLabelDescription(dkey)}:&nbsp;</td>
													<td>{if:detail}{detail:h}{else:}[blank]{end:} => {changedDetail}</td>
												</tr>				
												{end:}
											{end:}
										{end:}
									</table>
								</td>
                            </tr>
                            <tr IF="history.changes.changedProfile">
                                <td>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td colspan="2"><b>Profile changed: </b></td>	
										</tr>
										{foreach:history.changes.profile,pkey,detail}
											{foreach:history.changes.changedProfile,cpkey,changedDetail}
												{if:pkey=cpkey}
												<tr>
													<td>{pkey}:&nbsp;</td>
													<td>{if:detail}{detail:h}{else:}[blank]{end:} => {changedDetail:h}</td>
												</tr>				
												{end:}
											{end:}
										{end:}
									</table>
								</td>
                            </tr>
                            <tr IF="history.changes.changedTotals">
                                <td>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td colspan="2"><b>Totals changed: </b></td>	
										</tr>
										{foreach:history.changes.totals,pkey,detail}
											{foreach:history.changes.changedTotals,cpkey,changedDetail}
												{if:pkey=cpkey}
												<tr>
													<td>{pkey}: </td>
													<td>{if:detail}{detail:h}{else:}[blank]{end:} => {changedDetail:h}</td>
												</tr>				
												{end:}
											{end:}
										{end:}
									</table>
								</td>
                            </tr>
						</table>	
					</td>
					<td id="close_details{key}" style="display: none;" onClick="VisibleBox('details{key}')"><b>Details </b><img src="images/modules/AOM/open.gif"></td>
				</tr>
				<tr>
					<td colspan="3"><hr style="color: #E5EBEF; background-color: #E5EBEF; height: 1px; border: 0px;"></td>
					</td>
				</tr>
				</tbody>
				<tr class="DialogBox" IF="!order.orderHistory">
					<td colspan="3">Order history is clear</td>
				</tr>
			</table>
