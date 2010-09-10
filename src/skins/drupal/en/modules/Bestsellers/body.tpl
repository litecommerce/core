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
<table width="100%">

<tr>

<td foreach="getData(),bestseller">

<a href="{bestseller.getURL()}">
<widget class="\XLite\View\Image" image="{bestseller.getThumbnail()}" alt="{bestseller.name}" />
<br />
{bestseller.name}
</a>

</td>

</tr>

</table>
