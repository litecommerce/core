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
<TR IF="target=#product_list#">
		<TD class="FormButton" noWrap height=10 colspan="3">
			Show new arrivals only
			<input type="checkbox" name="new_arrivals_search" checked="{new_arrivals_search}" value="1">
		</TD>
	</TR>
	<TR IF="target=#product#&page=#related_products#">
		<TD class="FormButton" noWrap height=10 colspan="3">
			Show new arrivals only
			<input type="checkbox" name="new_arrivals_search" checked="{new_arrivals_search}" value="1">
		</TD>
	</TR>
