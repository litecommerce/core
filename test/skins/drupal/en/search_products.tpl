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
<widget class="XLite_View_Form_Search_Product_Simple" name="search_form" />

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
				<td><widget class="XLite_View_Button_Submit" label="Go" /></td>
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

  <widget name="search_form" end />

<br />
