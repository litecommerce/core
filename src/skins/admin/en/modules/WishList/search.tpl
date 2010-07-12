{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Wishlists search form template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form name="wishlist_form" action="admin.php" method="GET">

  <input type="hidden" name="target" value="wishlists" />
  <input type="hidden" name="mode" value="search" />
  <input type="hidden" name="action" value="search" />

  <table border="0" cellspacing="0" cellpadding="3">

  	<tr>
  		<td class=FormButton width="100px">List ID:</td>
      <td>
        <input type="text" name="startId" size="5" value="{getCondition(#startId#):h}" />
        &nbsp;-&nbsp;
        <input type="text" name="endId" size="5" value="{getCondition(#endId#):h}" />
      </td>
    </tr>

    <tr>
      <td class=FormButton>E-mail:</td>
      <td><input type="text" name="email" size="14" value="{getCondition(#email#):h}" /></td>
    </tr>

    <tr>
      <td class=FormButton>SKU:</td>
      <td><input type="text" name="sku" size="14" value="{getCondition(#sku#):h}" /></td>
    </tr>

    <tr>
      <td class=FormButton>Product name:</td>
      <td><input type="text" name="productTitle" size="14" value="{getCondition(#productTitle#):h}" /></td>
    </tr>

  	<tr>
	  	<td class=FormButton noWrap height=10>Creation date from:</td>
		  <td height=10><widget class="\XLite\View\Date" field="startDate" value="{getCondition(#startDate#)}" /></td>
  	</tr>

    <tr>
      <td class=FormButton noWrap height=10>Creation date through:</td>
      <td height=10><widget class="\XLite\View\Date" field="endDate" value="{getCondition(#endDate#)}" /></td>
    </tr>

    <tr>
		<td></td>
		<td align="left"><input type="submit" value="Search" /></td>
    </tr>

  </table>

</form>
