{* SVN $Id$ *}
<form action="{buildURLPath(#search#)}" method="get" name="search_form">
  <input FOREACH="buildURLArguments(#search#),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

  <table cellpadding="0" cellspacing="0">
    <tr>
    	<td><img src="images/searchbox_left.gif" width="9" height="78" alt="" /></td>
    	<td width="100%" class="SearchBoxBG">

		    <table>

      		<tr>
      			<td colspan="2">Find product:</td>
      		</tr>

      		<tr>
		        <td>
              <span IF="!substring:r"><input type="text" name="substring" style="width: 75pt; color: #888888;" value="Find product" onfocus="javascript: this.value = ''; this.style.color = '#000000';"></span>
			        <span IF="substring:r"><input type="text" name="substring" style="width: 75pt;" value="{substring:r}"></span>
			      </td>
		        <td><widget template="common/button2.tpl" href="javascript: document.search_form.submit()" label="GO" img="btn2_arrows.gif"></td>
		      </tr>

		      <tr IF="xlite.AdvancedSearchEnabled">
      			<td>
			        &nbsp;<a href="cart.php?target=advanced_search" title="Advanced Search" class="AdvancedSearchLink">Advanced search</a>
    		    </td>
		      </tr>

    		</table>

	    </td>
	    <td><img src="images/searchbox_right.gif" width="9" height="78" alt="" /></td>
    </tr>

  </table>

</form>
<BR>
