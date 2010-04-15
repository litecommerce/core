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
<tbody id="optionsException" IF="invalid_options">
<tr>
<td colspan=2 class="ErrorMessage">The option combination you selected:</td>
</tr>

<tr FOREACH="invalid_options,option,value">
<td width="30%"><b>{option:h}:</b></td>
<td>{value:h}</td>
</tr>

<tr>
<td colspan=2 class="ErrorMessage">is not available. Please make another choice.</td>
</tr>

<tr>
<td colspan=2>&nbsp;</td>
</tr>
</tbody>
