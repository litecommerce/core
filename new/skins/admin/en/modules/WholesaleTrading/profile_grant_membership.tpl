{if:membership_history}
<a IF="mode=#modify#" href="javascript: if ( confirm('Are you sure you want to change mempership?') ) grantMembership()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <font class="FormButton">Grant membership</font></a>
{else:}
<a IF="mode=#modify#" href="javascript: grantMembership()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <font class="FormButton">Grant membership</font></a>
{end:}
