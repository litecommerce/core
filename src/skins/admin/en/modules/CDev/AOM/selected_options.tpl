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
<p>
<table border="0" celpadding="0" cellspacing="0">
<tr>
    <td colspan="2"><b>Selected options:</b></td>
</tr>
<tr FOREACH="item.productOptions,option" valign=top>
    <td height="25" valign="middle">{option.class:h}:</td>
    <td height="25" valign="middle">{option.option:h}</td>
</tr>
</table>
</p>
