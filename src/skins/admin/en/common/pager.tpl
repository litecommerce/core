{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<p IF="moreThanOnePage" class="navigation-path">
<table width="90%">
<tr>
<td>
{t(#Result pages#)}:&nbsp;{foreach:pageURLs,num,pageURL}{if:isCurrentPage(num)}<b>[{num}]</b>{else:}<a href="{pageURL:h}">[{num}]</a>{end:} {end:}
</td>
</tr>
</table>
</p>
